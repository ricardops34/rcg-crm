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
import { GroupsService } from './groups.service';
import { ProgramsService } from './programs.service';
import { UsersController } from './users.controller';
import { GroupsController } from './groups.controller';
import { ProgramsController } from './programs.controller';

@Module({
  imports: [
    TypeOrmModule.forFeature(
      [
        SystemUnit,
        SystemGroup,
        SystemProgram,
        SystemUser,
        SystemUserGroup,
        SystemGroupProgram,
        SystemUserUnit,
        SystemChangeLog,
      ],
      'security',
    ),
  ],
  providers: [
    AuditSubscriber,
    UsersService,
    PermissionsService,
    GroupsService,
    ProgramsService,
  ],
  controllers: [UsersController, GroupsController, ProgramsController],
  exports: [
    TypeOrmModule,
    UsersService,
    PermissionsService,
    GroupsService,
    ProgramsService,
  ],
})
export class AdminModule {}
