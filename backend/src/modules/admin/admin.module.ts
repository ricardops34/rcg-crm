import { Module } from '@nestjs/common';
import { TypeOrmModule } from '@nestjs/typeorm';
import { SystemUnit } from './entities/system-unit.entity';
import { SystemGroup } from './entities/system-group.entity';
import { SystemProgram } from './entities/system-program.entity';
import { SystemModule } from './entities/system-module.entity';
import { SystemUserGroup } from './entities/system-user-group.entity';
import { SystemGroupProgram } from './entities/system-group-program.entity';
import { SystemUser } from './entities/system-user.entity';
import { UsersService } from './users.service';
import { PermissionsService } from './permissions.service';
import { GroupsService } from './groups.service';
import { ProgramsService } from './programs.service';
import { ModulesService } from './modules.service';
import { UsersController } from './users.controller';
import { GroupsController } from './groups.controller';
import { ProgramsController } from './programs.controller';
import { ModulesController } from './modules.controller';

@Module({
  imports: [
    TypeOrmModule.forFeature(
      [
        SystemUnit,
        SystemGroup,
        SystemProgram,
        SystemModule,
        SystemUserGroup,
        SystemGroupProgram,
        SystemUser,
      ],
      'security',
    ),
  ],
  providers: [
    UsersService,
    PermissionsService,
    GroupsService,
    ProgramsService,
    ModulesService,
  ],
  controllers: [
    UsersController,
    GroupsController,
    ProgramsController,
    ModulesController,
  ],
  exports: [
    TypeOrmModule,
    UsersService,
    PermissionsService,
    GroupsService,
    ProgramsService,
    ModulesService,
  ],
})
export class AdminModule {}
