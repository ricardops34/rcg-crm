import { DataSource } from 'typeorm';
import { SystemUser } from './src/modules/admin/entities/system-user.entity';
import { Vendedor } from './src/modules/commercial/entities/vendedor.entity';
import { Filial } from './src/modules/master-data/entities/filial.entity';
import { Cliente } from './src/modules/commercial/entities/cliente.entity';
import { MetaVendedorMes } from './src/modules/commercial/entities/meta-vendedor-mes.entity';
import * as bcrypt from 'bcrypt';

export async function seed(dataSource: DataSource) {
  const queryRunner = dataSource.createQueryRunner();
  await queryRunner.connect();

  console.log('🌱 Iniciando seeding de dados de teste...');

  // 1. Criar Filial
  const filial = new Filial();
  filial.nome = 'RCG Matriz';
  filial.codErp = '001';
  filial.status = 'A';
  const savedFilial = await queryRunner.manager.save(Filial, filial);

  // 2. Criar Vendedor
  const vendedor = new Vendedor();
  vendedor.nome = 'Ricardo Vendedor';
  vendedor.codErp = 'V001';
  vendedor.email = 'ricardo@vendedor.com';
  vendedor.status = 'A';
  vendedor.filialId = savedFilial.id;
  const savedVendedor = await queryRunner.manager.save(Vendedor, vendedor);

  // 3. Criar Usuário (Admin) - Senha: admin
  const user = new SystemUser();
  user.name = 'Ricardo Admin';
  user.login = 'admin';
  user.password = await bcrypt.hash('admin', 10);
  user.email = 'ricardo@admin.com';
  user.active = 'Y';
  await queryRunner.manager.save(SystemUser, user);

  // 4. Criar Clientes de Teste
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

  // 5. Criar Meta para o Vendedor
  const meta = new MetaVendedorMes();
  meta.vendedorId = savedVendedor.id;
  meta.mes = (new Date().getMonth() + 1).toString().padStart(2, '0');
  meta.ano = new Date().getFullYear().toString();
  meta.valor = 50000;
  meta.numeroCliente = 20;
  await queryRunner.manager.save(MetaVendedorMes, meta);

  console.log('✅ Dados de teste inseridos com sucesso!');
  await queryRunner.release();
}
