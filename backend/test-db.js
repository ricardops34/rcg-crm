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

    try {
      const res = await client.query("SELECT * FROM view_vendedor_venda_mes LIMIT 1");
      console.log("Columns of view_vendedor_venda_mes:", res.fields.map(f => f.name));
    } catch(e) {
      console.error("Error querying view_vendedor_venda_mes:", e.message);
    }
    
    try {
      const res = await client.query("SELECT * FROM view_total_catogoria_mes LIMIT 1");
      console.log("Columns of view_total_catogoria_mes:", res.fields.map(f => f.name));
    } catch(e) {
      console.error("Error querying view_total_catogoria_mes:", e.message);
    }

  } catch (err) {
    console.error("Connection error", err.stack);
  } finally {
    await client.end();
  }
}

run();
