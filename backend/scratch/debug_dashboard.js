const { Client } = require('pg');

async function run() {
  const client = new Client({
    user: 'postgres',
    password: 'admin',
    host: '127.0.0.1',
    port: 5432,
    database: 'rcgcrm'
  });

  try {
    await client.connect();
    console.log("Connected successfully.");

    // 1. Check system_user and groups
    const usersRes = await client.query("SELECT id, name, login, email FROM system_users");
    console.log("\n--- System Users ---");
    console.log(usersRes.rows);

    const userGroupsRes = await client.query(`
      SELECT sug.system_user_id, sug.system_group_id, sg.name as group_name, sg.role
      FROM system_user_group sug
      JOIN system_group sg ON sg.id = sug.system_group_id
    `);
    console.log("\n--- User Groups ---");
    console.log(userGroupsRes.rows);

    // 2. Check vendedores
    const vendedoresRes = await client.query("SELECT id, nome, email, cod_erp FROM vendedor");
    console.log("\n--- Vendedores ---");
    console.log(vendedoresRes.rows);

    // 3. Check mvc view/table and its count
    try {
      const mvcCountRes = await client.query("SELECT COUNT(*) FROM mvc");
      console.log("\n--- MVC Rows Count ---");
      console.log(mvcCountRes.rows);
      
      const mvcSampleRes = await client.query("SELECT * FROM mvc LIMIT 3");
      console.log("\n--- MVC Sample Rows ---");
      console.log(mvcSampleRes.rows);
    } catch (e) {
      console.error("\nError querying mvc:", e.message);
    }

    // 4. Check pivot_venda_mes_cliente
    try {
      const pivotCountRes = await client.query("SELECT COUNT(*) FROM pivot_venda_mes_cliente");
      console.log("\n--- pivot_venda_mes_cliente Rows Count ---");
      console.log(pivotCountRes.rows);
    } catch (e) {
      console.error("\nError querying pivot_venda_mes_cliente:", e.message);
    }

  } catch (err) {
    console.error("Error connecting or running query:", err);
  } finally {
    await client.end();
  }
}

run();
