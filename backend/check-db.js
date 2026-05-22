const { DataSource } = require('typeorm');
require('dotenv').config();

const AppDataSource = new DataSource({
  type: 'postgres',
  host: process.env.DB_HOST,
  port: parseInt(process.env.DB_PORT),
  username: process.env.DB_USERNAME,
  password: process.env.DB_PASSWORD,
  database: process.env.DB_DATABASE,
});

const SecurityDataSource = new DataSource({
  type: 'postgres',
  host: process.env.DB_SECURITY_HOST || process.env.DB_HOST,
  port: parseInt(process.env.DB_SECURITY_PORT || process.env.DB_PORT),
  username: process.env.DB_SECURITY_USERNAME || process.env.DB_USERNAME,
  password: process.env.DB_SECURITY_PASSWORD || process.env.DB_PASSWORD,
  database: process.env.DB_SECURITY_DATABASE || 'bjs_security',
});

async function run() {
  try {
    await SecurityDataSource.initialize();
    const result = await SecurityDataSource.query(`
      SELECT column_name 
      FROM information_schema.columns 
      WHERE table_name = 'system_group'
    `);
    console.log("system_group columns:", result.map(r => r.column_name));
  } catch (err) {
    console.error("Error:", err);
  } finally {
    await SecurityDataSource.destroy();
  }
}

run();
