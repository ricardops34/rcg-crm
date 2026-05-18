import { DataSource } from 'typeorm';
import { SystemUser } from './modules/admin/entities/system-user.entity';
import { SystemGroup } from './modules/admin/entities/system-group.entity';
import { SystemProgram } from './modules/admin/entities/system-program.entity';
import { SystemUserGroup } from './modules/admin/entities/system-user-group.entity';
import { SystemGroupProgram } from './modules/admin/entities/system-group-program.entity';
import { Vendedor } from './modules/commercial/entities/vendedor.entity';
import { Filial } from './modules/master-data/entities/filial.entity';
import { Cliente } from './modules/commercial/entities/cliente.entity';
import { MetaVendedorMes } from './modules/commercial/entities/meta-vendedor-mes.entity';
import * as bcrypt from 'bcrypt';

export async function seed(dataSource: DataSource) {
  const queryRunner = dataSource.createQueryRunner();
  await queryRunner.connect();

  console.log('🌱 Iniciando seeding de dados de teste...');

  try {
    // Verificar se já existe o usuário admin
    const userRepo = dataSource.getRepository(SystemUser);
    const existingAdmin = await userRepo.findOne({ where: { login: 'admin' } });

    if (existingAdmin) {
      console.log('ℹ️ Usuário admin já existe. Pulando seeding.');
      return;
    }

    console.log('📦 Inserindo dados iniciais...');

    // 1. Criar Filial
    const filial = new Filial();
    filial.nome = 'RCG Matriz';
    filial.codErp = '001';
    filial.status = 'A';
    const savedFilial = await queryRunner.manager.save(Filial, filial);
    console.log('✅ Filial criada');

    // 2. Criar Vendedor
    const vendedor = new Vendedor();
    vendedor.nome = 'Ricardo Vendedor';
    vendedor.codErp = 'V001';
    vendedor.email = 'ricardo@vendedor.com';
    vendedor.status = 'A';
    vendedor.filialId = savedFilial.id;
    const savedVendedor = await queryRunner.manager.save(Vendedor, vendedor);
    console.log('✅ Vendedor criado');

    // 3. Criar Estrutura de Permissão (RBAC)
    const group = new SystemGroup();
    group.name = 'Admin';
    const savedGroup = await queryRunner.manager.save(SystemGroup, group);

    const program = new SystemProgram();
    program.name = 'Usuários';
    program.controller = 'SystemUserList';
    const savedProgram = await queryRunner.manager.save(SystemProgram, program);

    const programVendedor = new SystemProgram();
    programVendedor.name = 'Vendedores';
    programVendedor.controller = 'VendedorList';
    const savedProgramVendedor = await queryRunner.manager.save(SystemProgram, programVendedor);

    const programCliente = new SystemProgram();
    programCliente.name = 'Clientes';
    programCliente.controller = 'ClienteList';
    const savedProgramCliente = await queryRunner.manager.save(SystemProgram, programCliente);

    const groupProgram = new SystemGroupProgram();
    groupProgram.systemGroupId = savedGroup.id;
    groupProgram.systemProgramId = savedProgram.id;
    await queryRunner.manager.save(SystemGroupProgram, groupProgram);

    const groupProgramVendedor = new SystemGroupProgram();
    groupProgramVendedor.systemGroupId = savedGroup.id;
    groupProgramVendedor.systemProgramId = savedProgramVendedor.id;
    await queryRunner.manager.save(SystemGroupProgram, groupProgramVendedor);

    const groupProgramCliente = new SystemGroupProgram();
    groupProgramCliente.systemGroupId = savedGroup.id;
    groupProgramCliente.systemProgramId = savedProgramCliente.id;
    await queryRunner.manager.save(SystemGroupProgram, groupProgramCliente);
    console.log('✅ Estrutura RBAC criada');

    // 4. Criar Usuário (Admin) - Senha: admin
    const user = new SystemUser();
    user.name = 'Ricardo Admin';
    user.login = 'admin';
    user.password = await bcrypt.hash('admin', 10);
    user.email = 'ricardo@admin.com';
    user.active = 'Y';
    user.systemUnitId = savedFilial.id;
    const savedUser = await queryRunner.manager.save(SystemUser, user);

    const userGroup = new SystemUserGroup();
    userGroup.systemUserId = savedUser.id;
    userGroup.systemGroupId = savedGroup.id;
    await queryRunner.manager.save(SystemUserGroup, userGroup);
    console.log('✅ Usuário Admin criado e vinculado ao grupo');

    // 5. Criar Clientes de Teste
    const cliente1 = new Cliente();
    cliente1.razao = 'FARMACIA EXEMPLO LTDA';
    cliente1.fantasia = 'FARMA EXEMPLO';
    cliente1.codErp = 'C001';
    cliente1.cnpjCpf = '12345678000199';
    cliente1.status = 'A';
    cliente1.vendedorId = savedVendedor.id;
    cliente1.filialId = savedFilial.id;
    await queryRunner.manager.save(Cliente, cliente1);

    const cliente2 = new Cliente();
    cliente2.razao = 'DROGARIA TESTE S/A';
    cliente2.codErp = 'C002';
    cliente2.status = 'B';
    cliente2.vendedorId = savedVendedor.id;
    cliente2.filialId = savedFilial.id;
    await queryRunner.manager.save(Cliente, cliente2);
    console.log('✅ Clientes criados');

    // 6. Criar Meta para o Vendedor
    const meta = new MetaVendedorMes();
    meta.vendedorId = savedVendedor.id;
    meta.mes = (new Date().getMonth() + 1).toString().padStart(2, '0');
    meta.ano = new Date().getFullYear().toString();
    meta.valor = 50000;
    meta.numeroCliente = 20;
    await queryRunner.manager.save(MetaVendedorMes, meta);
    console.log('✅ Meta criada');

    console.log('✨ Todos os dados de teste inseridos com sucesso!');
  } catch (error) {
    console.error('❌ Erro durante o seeding:', error.message);
    throw error;
  } finally {
    await queryRunner.release();
  }
}
