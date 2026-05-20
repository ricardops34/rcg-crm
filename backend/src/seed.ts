import { DataSource } from 'typeorm';

export async function seed(
  crmDataSource: DataSource,
  securityDataSource: DataSource,
) {
  // O seeding de dados de teste foi removido a pedido do usuário.
  // O sistema deve ser populado exclusivamente via migração de dados reais.
  console.log('🌱 Seed skip: Aguardando migração de dados reais.');
}
