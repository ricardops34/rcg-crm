import { Module } from '@nestjs/common';
import { TypeOrmModule } from '@nestjs/typeorm';
import { SystemUnit } from './entities/system-unit.entity';
import { SystemGroup } from './entities/system-group.entity';
import { SystemProgram } from './entities/system-program.entity';
import { SystemUser } from './entities/system-user.entity';
import { SystemUserGroup } from './entities/system-user-group.entity';
import { SystemGroupProgram } from './entities/system-group-program.entity';
import { SystemUserUnit } from './entities/system-user-unit.entity';
import { SystemChangeLog } from './entities/system-change-log.entity';
import { AuditSubscriber } from './subscribers/audit.subscriber';

import { UsersService } from './users.service';
import { PermissionsService } from './permissions.service';
import { UsersController } from './users.controller';
import { GroupsController } from './groups.controller';

@Module({
  imports: [
    TypeOrmModule.forFeature([
      SystemUnit,
      SystemGroup,
      SystemProgram,
      SystemUser,
      SystemUserGroup,
      SystemGroupProgram,
      SystemUserUnit,
      SystemChangeLog
    ], 'security')
  ],
  providers: [AuditSubscriber, UsersService, PermissionsService],
  controllers: [UsersController, GroupsController],
  exports: [TypeOrmModule, UsersService, PermissionsService],
})
export class AdminModule {}
