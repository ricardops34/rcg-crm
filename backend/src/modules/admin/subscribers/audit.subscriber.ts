import { Injectable } from '@nestjs/common';
import {
  DataSource,
  EntitySubscriberInterface,
  EventSubscriber,
  InsertEvent,
  UpdateEvent,
  RemoveEvent,
} from 'typeorm';
import { ClsService } from 'nestjs-cls';
import { SystemChangeLog } from '../entities/system-change-log.entity';

@EventSubscriber()
@Injectable()
export class AuditSubscriber implements EntitySubscriberInterface {
  constructor(
    private readonly dataSource: DataSource,
    private readonly cls: ClsService,
  ) {
    this.dataSource.subscribers.push(this);
  }

  private isAudited(entity: any): boolean {
    return entity?.constructor?.prototype?.['isAudited'] === true;
  }

  private getLogin(): string {
    const user = this.cls.get('user');
    return user?.username || 'system';
  }

  async afterInsert(event: InsertEvent<any>) {
    if (!this.isAudited(event.entity)) return;

    const log = new SystemChangeLog();
    log.login = this.getLogin();
    log.tablename = event.metadata.tableName;
    log.operation = 'INSERT';
    log.pkvalue = String((event as any).entityId || event.entity?.id);
    log.newvalue = JSON.stringify(event.entity);

    await event.manager.save(log);
  }

  async afterUpdate(event: UpdateEvent<any>) {
    if (!this.isAudited(event.entity)) return;

    const log = new SystemChangeLog();
    log.login = this.getLogin();
    log.tablename = event.metadata.tableName;
    log.operation = 'UPDATE';
    log.pkvalue = String(event.entity?.id || event.databaseEntity?.id);
    log.oldvalue = JSON.stringify(event.databaseEntity);
    log.newvalue = JSON.stringify(event.entity);

    await event.manager.save(log);
  }

  async afterRemove(event: RemoveEvent<any>) {
    if (!this.isAudited(event.entity)) return;

    const log = new SystemChangeLog();
    log.login = this.getLogin();
    log.tablename = event.metadata.tableName;
    log.operation = 'DELETE';
    log.pkvalue = String(
      event.entityId || event.entity?.id || event.databaseEntity?.id,
    );
    log.oldvalue = JSON.stringify(event.databaseEntity);

    await event.manager.save(log);
  }
}
