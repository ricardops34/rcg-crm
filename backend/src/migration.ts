import { Injectable } from '@nestjs/common';
import { DataSource } from 'typeorm';
import * as fs from 'fs';
import * as readline from 'readline';
import * as path from 'path';

@Injectable()
export class MigrationDataService {
  constructor(private dataSource: DataSource) {}

  async migrateMySQLtoPG(filePath: string, targetDataSource?: DataSource) {
    const ds = targetDataSource || this.dataSource;
    const absolutePath = path.resolve(filePath);
    if (!fs.existsSync(absolutePath)) {
      console.error(`Arquivo não encontrado: ${absolutePath}`);
      return;
    }

    try {
      await ds.query("SET session_replication_role = 'replica'");
      console.log('🔓 Verificações de integridade desabilitadas.');
    } catch (e) {
      console.warn('⚠️ Falha ao desabilitar FKs.');
    }

    const logPath = path.join(path.dirname(absolutePath), 'migration_errors.log');
    const logStream = fs.createWriteStream(logPath, { flags: 'a' });
    logStream.write(`\n\n--- INÍCIO DA MIGRAÇÃO: ${new Date().toISOString()} ---\n`);
    logStream.write(`Arquivo: ${filePath}\n`);

    const fileStream = fs.createReadStream(absolutePath);
    const rl = readline.createInterface({
      input: fileStream,
      crlfDelay: Infinity,
    });

    console.log(`Iniciando: ${filePath}...`);
    
    let queryBuffer = '';
    let lineCount = 0;
    let insertCount = 0;

    for await (const line of rl) {
      lineCount++;
      const trimmedLine = line.trim();
      
      // Ignorar linhas vazias e comentários simples
      if (trimmedLine === '' || trimmedLine.startsWith('--') || trimmedLine.startsWith('#')) {
        continue;
      }

      queryBuffer += ' ' + line;

      // Se a linha termina com ; (pode ter espaços ou comentários depois)
      if (trimmedLine.match(/;(\s*(\/\*.*\*\/)?\s*)*$/)) {
        let pgQuery = queryBuffer.trim();
        queryBuffer = ''; 

        // Limpar comentários e espaços extras
        const cleanDetect = pgQuery.replace(/^\/\*[\s\S]*?\*\/|^\s*--.*?\n|^\s*#.*?\n/gm, '').trim();

        if (cleanDetect.toUpperCase().startsWith('INSERT INTO')) {
          let convertedQuery = pgQuery
            .replace(/`/g, '"') 
            .replace(/\\'/g, "''") 
            .replace(/\\"/g, '"') 
            .replace(/\\r\\n/g, '\n')
            .replace(/\\n/g, '\n');

          // Corrigir números longos SEM ASPAS (CNPJ/CPF)
          let lastQuery;
          do {
            lastQuery = convertedQuery;
            convertedQuery = convertedQuery.replace(/([,\(\s])(\d{11,})([,\)\s])/g, "$1'$2'$3");
          } while (convertedQuery !== lastQuery);

          try {
            await ds.query(convertedQuery);
            insertCount++;
            if (insertCount % 500 === 0) console.log(`${insertCount} registros inseridos...`);
          } catch (err) {
            const msg = err.message.toLowerCase();
            if (msg.includes('does not exist') || msg.includes('duplicate key') || msg.includes('already exists')) {
               logStream.write(`Linha ${lineCount}: Ignorado - ${err.message}\n`);
            } else {
               logStream.write(`Linha ${lineCount}: ERRO REAL - ${err.message}\n`);
               logStream.write(`Query problemática (truncada): ${convertedQuery.substring(0, 1000)}...\n`);
            }
          }
        }
      }
    }

    try {
      await ds.query("SET session_replication_role = 'origin'");
      console.log('🔒 Verificações reabilitadas.');
    } catch (e) {}

    logStream.write(`--- FIM DA MIGRAÇÃO: ${new Date().toISOString()} - Total Inserções: ${insertCount} ---\n`);
    logStream.end();
    console.log(`Finalizado: ${insertCount} registros inseridos.`);

    await this.fixSequences(ds);

    // NOVO: Normalizar estrutura de menu após migração
    if (ds.options.name === 'security' || filePath.includes('permissao')) {
      await this.normalizeMenuStructure(ds);
    }
    }

    async normalizeMenuStructure(ds: DataSource) {
    console.log('📦 Normalizando estrutura de módulos e rotinas...');

    // 1. Garantir que os módulos básicos existam com ícones oficiais PO-UI
    const modules = [
      { name: 'Vendas', icon: 'po-icon-finance', order: 1 },
      { name: 'Cadastro', icon: 'po-icon-user-add', order: 2 },
      { name: 'Gerência', icon: 'po-icon-chart-line', order: 3 },
      { name: 'Administração', icon: 'po-icon-settings', order: 4 },
      { name: 'Sistema', icon: 'po-icon-device-desktop', order: 5 },
      { name: 'Desenvolvimento', icon: 'po-icon-xml', order: 6 }
    ];

    for (const mod of modules) {
      await ds.query(`
        INSERT INTO system_module (name, icon, "order")
        SELECT $1, $2, $3
        WHERE NOT EXISTS (SELECT 1 FROM system_module WHERE name = $1)
      `);
      // Garantir atualização do ícone se já existir
      await ds.query(`UPDATE system_module SET icon = $2, "order" = $3 WHERE name = $1`, [mod.name, mod.icon, mod.order]);
    }

    // 2. Mapear rotinas para seus respectivos módulos
    const mapping = [
      { controller: 'DashboardVendedor', module: 'Vendas' },
      { controller: 'MvcList', module: 'Vendas' },
      { controller: 'AtendimentoCalendarFormView', module: 'Vendas' },
      
      { controller: 'ClienteList', module: 'Cadastro' },
      { controller: 'VendedorList', module: 'Cadastro' },
      { controller: 'MetaVendedorMesList', module: 'Cadastro' },
      { controller: 'ProdutoList', module: 'Cadastro' },
      { controller: 'CategoriaList', module: 'Cadastro' },
      { controller: 'TabelaPrecoList', module: 'Cadastro' },
      
      { controller: 'DashboardGerencia', module: 'Gerência' },
      { controller: 'DashboardRegiao', module: 'Gerência' },
      
      { controller: 'SystemUserList', module: 'Administração' },
      { controller: 'SystemGroupList', module: 'Administração' },
      { controller: 'SystemUnitList', module: 'Administração' },
      { controller: 'SystemProgramList', module: 'Administração' },
      
      { controller: 'PedidoEstadoList', module: 'Sistema' },
      { controller: 'ParametroList', module: 'Sistema' }
    ];

    for (const item of mapping) {
      await ds.query(`
        UPDATE system_program 
        SET system_module_id = (SELECT id FROM system_module WHERE name = $1 LIMIT 1)
        WHERE controller LIKE $2
      `, [item.module, `%${item.controller}%`]);
    }

    console.log('✅ Estrutura de menu normalizada.');
    }

    async fixSequences(targetDataSource?: DataSource) {
    const ds = targetDataSource || this.dataSource;
    const tables = await ds.query(`
      SELECT table_name FROM information_schema.tables 
      WHERE table_schema = 'public' AND table_type = 'BASE TABLE'
    `);

    for (const table of tables) {
      const tableName = table.table_name;
      try {
        await ds.query(`
          SELECT setval(pg_get_serial_sequence('"${tableName}"', 'id'), coalesce(max(id), 1), true) FROM "${tableName}";
        `);
      } catch (e) {}
    }
  }
}
