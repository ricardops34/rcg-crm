import { Injectable, ForbiddenException, BadRequestException } from '@nestjs/common';
import { InjectRepository } from '@nestjs/typeorm';
import { Repository } from 'typeorm';
import { ClsService } from 'nestjs-cls';
import { Vendedor } from '../../entities/vendedor.entity';
import { UsersService } from '../../../admin/users.service';
import { MailService } from '../../../admin/services/mail.service';

@Injectable()
export class VendedorService {
  constructor(
    @InjectRepository(Vendedor)
    private readonly vendedorRepository: Repository<Vendedor>,
    private readonly cls: ClsService,
    private readonly usersService: UsersService,
    private readonly mailService: MailService,
  ) {}

  async findAll(
    page = 1,
    limit = 10,
    filters?: { status?: string; dashboard?: string; supervisor?: string; order?: string },
  ): Promise<[Vendedor[], number]> {
    const user = this.cls.get('user');
    const systemUnitId = user?.unitId;
    const query = this.vendedorRepository
      .createQueryBuilder('vendedor')
      .leftJoinAndSelect('vendedor.filial', 'filial');

    if (systemUnitId) {
      query.andWhere('vendedor.systemUnitId = :systemUnitId', { systemUnitId });
    }

    if (filters?.status) {
      query.andWhere('vendedor.status = :status', { status: filters.status });
    }

    if (filters?.dashboard) {
      query.andWhere('vendedor.dashboard = :dashboard', { dashboard: filters.dashboard });
    }

    if (filters?.supervisor) {
      query.andWhere('vendedor.supervisor = :supervisor', { supervisor: filters.supervisor });
    }

    // Hierarquia de Acesso Baseada em Perfis (Role-Based)
    const roles = user?.roles || [];

    if (!roles.includes('ADMIN') && !roles.includes('GERENTE')) {
      if (roles.includes('SUPERVISOR') && user.managedVendedorIds?.length > 0) {
        const sellerIds = [...user.managedVendedorIds];
        if (user.vendedorId) sellerIds.push(user.vendedorId);
        query.andWhere('vendedor.id IN (:...sellerIds)', { sellerIds: [...new Set(sellerIds)] });
      } else if (roles.includes('VENDEDOR') && user.vendedorId) {
        query.andWhere('vendedor.id = :vendedorId', { vendedorId: user.vendedorId });
      } else {
        return [[], 0];
      }
    }

    const sortMap: Record<string, string> = {
      id: 'vendedor.id',
      codErp: 'vendedor.codErp',
      nome: 'vendedor.nome',
      email: 'vendedor.email',
      filialRazao: 'filial.razao',
      status: 'vendedor.status',
      celular: 'vendedor.celular',
      supervisor: 'vendedor.supervisor',
    };

    const orderValue = filters?.order || 'nome';
    const isDescending = orderValue.startsWith('-');
    const orderKey = isDescending ? orderValue.slice(1) : orderValue;
    const orderColumn = sortMap[orderKey] || 'vendedor.nome';

    query
      .orderBy(orderColumn, isDescending ? 'DESC' : 'ASC')
      .skip((page - 1) * limit)
      .take(limit);

    return query.getManyAndCount();
  }

  async findOne(id: number): Promise<Vendedor | null> {
    const user = this.cls.get('user');
    const systemUnitId = user?.unitId;

    // Validar hierarquia no acesso individual (Role-Based)
    const roles = user?.roles || [];

    if (!roles.includes('ADMIN') && !roles.includes('GERENTE')) {
      if (roles.includes('SUPERVISOR')) {
        const sellerIds = [...(user.managedVendedorIds || [])];
        if (user.vendedorId) sellerIds.push(user.vendedorId);
        if (!sellerIds.includes(id)) {
          throw new ForbiddenException('Acesso negado a este vendedor (fora da sua equipe)');
        }
      } else if (roles.includes('VENDEDOR') && user.vendedorId !== id) {
        throw new ForbiddenException('Acesso negado ao perfil de outro vendedor');
      }
    }

    const where: any = { id };
    if (systemUnitId) {
      where.systemUnitId = systemUnitId;
    }

    return this.vendedorRepository.findOne({
      where,
      relations: ['filial'],
    });
  }

  async save(data: any): Promise<Vendedor> {
    const user = this.cls.get('user');
    const systemUnitId = user?.unitId;

    if (systemUnitId && !data.systemUnitId) {
      data.systemUnitId = systemUnitId;
    }

    const saved = await this.vendedorRepository.save(data);

    const linkedUserId = saved.systemUsersId;
    if (linkedUserId) {
      // Sync em background — falhas não bloqueiam o retorno do save
      this.usersService.syncFromVendedor(linkedUserId, {
        nomeReduzido: data.nomeReduzido,
        email: data.email,
        status: data.status,
        systemUnitId: saved.systemUnitId,
        dtNascimento: data.dtNascimento,
      }).catch(err =>
        console.warn(`[VENDEDOR] Sync usuário ${linkedUserId} falhou: ${err?.message}`)
      );
    }

    return saved;
  }

  async createUserAndSendPassword(id: number): Promise<{ success: boolean; message: string }> {
    const vendedor = await this.vendedorRepository.findOne({ where: { id } });

    if (!vendedor) {
      throw new BadRequestException('Vendedor não encontrado.');
    }
    if (vendedor.systemUsersId) {
      throw new BadRequestException('Este vendedor já possui um usuário de sistema vinculado.');
    }
    if (!vendedor.email) {
      throw new BadRequestException('Este vendedor não possui e-mail cadastrado.');
    }

    // Login é o próprio e-mail do vendedor
    const login = vendedor.email.toLowerCase().trim();
    const existing = await this.usersService.findByLogin(login);
    if (existing) {
      throw new BadRequestException(
        `O e-mail "${login}" já está cadastrado como login de outro usuário.`
      );
    }

    const tempPassword = this.generateTempPassword();

    const nomeCurto = vendedor.nomeReduzido || vendedor.nome;
    const birthday = vendedor.dtNascimento
      ? new Date(vendedor.dtNascimento).toISOString().split('T')[0]
      : undefined;

    const newUser = await this.usersService.createMinimal({
      login,
      name: nomeCurto,
      email: vendedor.email,
      password: tempPassword,
      systemUnitId: vendedor.systemUnitId,
      active: vendedor.status === 'A' ? 'Y' : 'N',
      birthday,
      forcePasswordChange: 'Y',
      acceptedTermPolicy: 'N',
      failedLoginAttempts: 0,
    });

    // Atribuir perfil VENDEDOR ao novo usuário
    await this.usersService.assignGroupByRole(newUser.id, 'VENDEDOR');

    // Vincular o usuário criado ao vendedor
    await this.vendedorRepository.update(id, { systemUsersId: newUser.id });

    await this.mailService.sendVendedorTempPassword(
      vendedor.email,
      nomeCurto,
      login,
      tempPassword,
      vendedor.systemUnitId,
    );

    return {
      success: true,
      message: `Usuário "${login}" criado e senha enviada para ${vendedor.email}`,
    };
  }

  async sendPassword(id: number): Promise<{ success: boolean; message: string }> {
    const vendedor = await this.vendedorRepository.findOne({ where: { id } });

    if (!vendedor) {
      throw new BadRequestException('Vendedor não encontrado.');
    }
    if (!vendedor.systemUsersId) {
      throw new BadRequestException('Este vendedor não possui um usuário de sistema vinculado.');
    }
    if (!vendedor.email) {
      throw new BadRequestException('Este vendedor não possui e-mail cadastrado.');
    }

    const tempPassword = this.generateTempPassword();
    const userLogin = await this.usersService.setTemporaryPassword(vendedor.systemUsersId, tempPassword);

    await this.mailService.sendVendedorTempPassword(
      vendedor.email,
      vendedor.nomeReduzido || vendedor.nome,
      userLogin,
      tempPassword,
      vendedor.systemUnitId,
    );

    return { success: true, message: `Senha temporária enviada para ${vendedor.email}` };
  }

  private generateTempPassword(): string {
    const upper = 'ABCDEFGHJKMNPQRSTUVWXYZ';
    const lower = 'abcdefghjkmnpqrstuvwxyz';
    const digits = '23456789';
    const all = upper + lower + digits;

    let password = '';
    password += upper[Math.floor(Math.random() * upper.length)];
    password += lower[Math.floor(Math.random() * lower.length)];
    password += digits[Math.floor(Math.random() * digits.length)];
    for (let i = 0; i < 7; i++) {
      password += all[Math.floor(Math.random() * all.length)];
    }
    return password.split('').sort(() => Math.random() - 0.5).join('');
  }

  async remove(id: number): Promise<void> {
    await this.findOne(id);
    await this.vendedorRepository.delete(id);
  }
}
