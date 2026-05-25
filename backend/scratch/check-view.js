const { Client } = require('pg');

const client = new Client({
  user: 'postgres',
  password: 'admin',
  host: '127.0.0.1',
  port: 5432,
  database: 'rcgcrm'
});

async function run() {
  try {
    await client.connect();
    console.log("Connected to DB.");

    // Check if mvc is a materialized view or a standard view
    const mvcKindRes = await client.query(`
      SELECT relname, relkind 
      FROM pg_class 
      WHERE relname IN ('mvc', 'pivot_venda_mes_cliente')
    `);
    console.log("Database Relation Types:");
    mvcKindRes.rows.forEach(row => {
      let type = 'unknown';
      if (row.relkind === 'v') type = 'Standard View';
      if (row.relkind === 'm') type = 'Materialized View';
      if (row.relkind === 'r') type = 'Ordinary Table';
      console.log(`- ${row.relname}: relkind = '${row.relkind}' (${type})`);
    });

    // Check indexes on mvc
    const mvcIndexes = await client.query(`
      SELECT indexname, indexdef 
      FROM pg_indexes 
      WHERE tablename = 'mvc'
    `);
    console.log("\nIndexes on 'mvc':");
    mvcIndexes.rows.forEach(row => {
      console.log(`- ${row.indexname}: ${row.indexdef}`);
    });

    // Check indexes on pivot_venda_mes_cliente
    const pivotIndexes = await client.query(`
      SELECT indexname, indexdef 
      FROM pg_indexes 
      WHERE tablename = 'pivot_venda_mes_cliente'
    `);
    console.log("\nIndexes on 'pivot_venda_mes_cliente':");
    pivotIndexes.rows.forEach(row => {
      console.log(`- ${row.indexname}: ${row.indexdef}`);
    });

    // Measure query time for SELECT * FROM mvc
    console.log("\nTiming SELECT COUNT(*) FROM mvc...");
    let start = Date.now();
    let res = await client.query("SELECT COUNT(*) FROM mvc");
    console.log(`- Count: ${res.rows[0].count} rows`);
    console.log(`- Time taken: ${Date.now() - start}ms`);

    // Measure query time for getMvcData query (simulate year = 2026)
    console.log("\nTiming the getMvcData query...");
    const queryStr = `
      SELECT 
        mvc.id as cliente_id,
        mvc.codigo,
        mvc.situacao,
        mvc.razao as cliente_nome,
        mvc.fantasia,
        mvc.primeira_compra,
        mvc.ultima_compra,
        mvc.dias,
        mvc.municipio_descricao,
        mvc.estado_sigla,
        mvc.carteira,
        mvc.vendedor_reduzido,
        mvc.vendedor_id,
        mvc.estado_id,
        mvc.municipio_id,
        mvc.financeiro_status,
        COALESCE(p.ano, '2026') as ano,
        COALESCE(p.janeiro, 0) as janeiro,
        COALESCE(p.fevereiro, 0) as fevereiro,
        COALESCE(p.marco, 0) as marco,
        COALESCE(p.abril, 0) as abril,
        COALESCE(p.maio, 0) as maio,
        COALESCE(p.junho, 0) as junho,
        COALESCE(p.julho, 0) as julho,
        COALESCE(p.agosto, 0) as agosto,
        COALESCE(p.setembro, 0) as setembro,
        COALESCE(p.outubro, 0) as outubro,
        COALESCE(p.novembro, 0) as novembro,
        COALESCE(p.dezembro, 0) as dezembro
      FROM mvc
      LEFT JOIN pivot_venda_mes_cliente p ON p.cliente_id = mvc.id AND CAST(p.ano AS integer) = CAST(2026 AS integer)
    `;
    start = Date.now();
    res = await client.query(queryStr);
    console.log(`- Query returned ${res.rows.length} rows.`);
    console.log(`- Time taken: ${Date.now() - start}ms`);

    // EXPLAIN ANALYZE on the query to see the bottleneck
    console.log("\nEXPLAIN ANALYZE of getMvcData query:");
    const explainRes = await client.query("EXPLAIN ANALYZE " + queryStr);
    explainRes.rows.forEach(row => console.log(row['QUERY PLAN']));

  } catch (err) {
    console.error("Error running diagnostics:", err);
  } finally {
    await client.end();
  }
}

run();
