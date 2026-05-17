import { Injectable } from '@nestjs/common';
import { DataSource } from 'typeorm';

@Injectable()
export class ErpTranslationService {
  constructor(private dataSource: DataSource) {}

  async findIdByCodErp(tableName: string, codErp: string): Promise<number | null> {
    if (!codErp) return null;

    const result = await this.dataSource.query(
      `SELECT id FROM "${tableName}" WHERE cod_erp = $1 LIMIT 1`,
      [codErp.trim()]
    );

    return result.length > 0 ? result[0].id : null;
  }

  async resolveRelationIds(data: any, relations: { [key: string]: string }): Promise<any> {
    const resolvedData = { ...data };

    for (const [field, targetTable] of Object.entries(relations)) {
      if (data[field]) {
        const id = await this.findIdByCodErp(targetTable, data[field]);
        if (id) {
          resolvedData[`${field}_id`] = id;
          delete resolvedData[field];
        }
      }
    }

    return resolvedData;
  }
}
