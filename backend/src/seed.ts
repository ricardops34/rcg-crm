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

export async function seed(crmDataSource: DataSource, securityDataSource: DataSource) {
  console.log('🌱 Iniciando seeding de dados de teste (Multi-DB)...');

  try {
    // 1. Verificar/Criar Filial no CRM
    const filialRepo = crmDataSource.getRepository(Filial);
    let filial = await filialRepo.findOne({ where: { codErp: '001' } });
    
    if (!filial) {
      filial = new Filial();
      filial.nome = 'RCG Matriz';
      filial.codErp = '001';
      filial.status = 'A';
      filial = await filialRepo.save(filial);
      console.log('✅ Filial criada no CRM');
    }

    // 2. Verificar/Criar Estrutura de Permissão no Security
    const userRepo = securityDataSource.getRepository(SystemUser);
    const existingAdmin = await userRepo.findOne({ where: { login: 'admin' } });

    if (existingAdmin) {
      console.log('ℹ️ Usuário admin já existe no Security. Pulando seeding de permissões.');
    } else {
      console.log('📦 Inserindo dados iniciais no Security...');

      const groupRepo = securityDataSource.getRepository(SystemGroup);
      const programRepo = securityDataSource.getRepository(SystemProgram);
      const gpRepo = securityDataSource.getRepository(SystemGroupProgram);

      const group = new SystemGroup();
      group.name = 'Admin';
      const savedGroup = await groupRepo.save(group);

      const programs = [
        { name: 'Usuários', controller: 'SystemUserList' },
        { name: 'Vendedores', controller: 'VendedorList' },
        { name: 'Clientes', controller: 'ClienteList' },
        { name: 'Dashboard', controller: 'DashboardVendedor' },
        { name: 'MCV', controller: 'MvcList' },
      ];

      for (const pData of programs) {
        const program = new SystemProgram();
        program.name = pData.name;
        program.controller = pData.controller;
        const savedProgram = await programRepo.save(program);

        const groupProgram = new SystemGroupProgram();
        groupProgram.systemGroupId = savedGroup.id;
        groupProgram.systemProgramId = savedProgram.id;
        await gpRepo.save(groupProgram);
      }
      console.log('✅ Estrutura RBAC criada no Security');

      // Criar Usuário (Admin) no Security
      const user = new SystemUser();
      user.name = 'Ricardo Admin';
      user.login = 'admin';
      user.password = await bcrypt.hash('admin', 10);
      user.email = 'ricardo@admin.com';
      user.active = 'Y';
      user.systemUnitId = filial.id; // Vinculado ao ID da filial do CRM
      const savedUser = await userRepo.save(user);

      const userGroupRepo = securityDataSource.getRepository(SystemUserGroup);
      const userGroup = new SystemUserGroup();
      userGroup.systemUserId = savedUser.id;
      userGroup.systemGroupId = savedGroup.id;
      await userGroupRepo.save(userGroup);
      console.log('✅ Usuário Admin criado e vinculado ao grupo no Security');
    }

    // 3. Seeding de Negócios no CRM (se não houver vendedores)
    const vendedorRepo = crmDataSource.getRepository(Vendedor);
    const existingVendedor = await vendedorRepo.findOne({ where: { codErp: 'V001' } });

    if (!existingVendedor) {
      const vendedor = new Vendedor();
      vendedor.nome = 'Ricardo Vendedor';
      vendedor.codErp = 'V001';
      vendedor.email = 'ricardo@vendedor.com';
      vendedor.status = 'A';
      vendedor.filialId = filial.id;
      const savedVendedor = await vendedorRepo.save(vendedor);
      console.log('✅ Vendedor criado no CRM');

      const clienteRepo = crmDataSource.getRepository(Cliente);
      const cliente1 = new Cliente();
      cliente1.razao = 'FARMACIA EXEMPLO LTDA';
      cliente1.fantasia = 'FARMA EXEMPLO';
      cliente1.codErp = 'C001';
      cliente1.cnpjCpf = '12345678000199';
      cliente1.status = 'A';
      cliente1.vendedorId = savedVendedor.id;
      cliente1.filialId = filial.id;
      await clienteRepo.save(cliente1);

      const cliente2 = new Cliente();
      cliente2.razao = 'DROGARIA TESTE S/A';
      cliente2.codErp = 'C002';
      cliente2.status = 'B';
      cliente2.vendedorId = savedVendedor.id;
      cliente2.filialId = filial.id;
      await clienteRepo.save(cliente2);
      console.log('✅ Clientes criados no CRM');

      const metaRepo = crmDataSource.getRepository(MetaVendedorMes);
      const meta = new MetaVendedorMes();
      meta.vendedorId = savedVendedor.id;
      meta.mes = (new Date().getMonth() + 1).toString().padStart(2, '0');
      meta.ano = new Date().getFullYear().toString();
      meta.valor = 50000;
      meta.numeroCliente = 20;
      await metaRepo.save(meta);
      console.log('✅ Meta criada no CRM');
    }

    console.log('✨ Todos os dados de teste sincronizados nos bancos.');
  } catch (error) {
    console.error('❌ Erro durante o seeding sincronizado:', error.message);
    throw error;
  }
}
