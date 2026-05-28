import { Module } from '@nestjs/common';
import { TypeOrmModule } from '@nestjs/typeorm';
import { SystemUnit } from './entities/system-unit.entity';
import { SystemGroup } from './entities/system-group.entity';
import { SystemProgram } from './entities/system-program.entity';
import { SystemModule } from './entities/system-module.entity';
import { SystemParameter } from './entities/system-parameter.entity';
import { SystemPreference } from './entities/system-preference.entity';
import { SystemUserGroup } from './entities/system-user-group.entity';
import { SystemUserProgram } from './entities/system-user-program.entity';
import { SystemGroupProgram } from './entities/system-group-program.entity';
import { SystemUser } from './entities/system-user.entity';
import { SystemUserUnit } from './entities/system-user-unit.entity';
import { UsersService } from './users.service';
import { PermissionsService } from './permissions.service';
import { GroupsService } from './groups.service';
import { ProgramsService } from './programs.service';
import { ModulesService } from './modules.service';
import { UnitsService } from './units.service';
import { ParametersService } from './parameters.service';
import { UsersController } from './users.controller';
import { GroupsController } from './groups.controller';
import { ProgramsController } from './programs.controller';
import { ModulesController } from './modules.controller';
import { UnitsController } from './units.controller';
import { ParametersController } from './parameters.controller';
import { MailService } from './services/mail.service';
import { UploadService } from './services/upload.service';

@Module({
  imports: [
    TypeOrmModule.forFeature(
      [
        SystemUnit,
        SystemGroup,
        SystemProgram,
        SystemModule,
        SystemParameter,
        SystemPreference,
        SystemUserGroup,
        SystemUserProgram,
        SystemGroupProgram,
        SystemUser,
        SystemUserUnit,
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
    UnitsService,
    ParametersService,
    MailService,
    UploadService,
  ],
  controllers: [
    UsersController,
    GroupsController,
    ProgramsController,
    ModulesController,
    UnitsController,
    ParametersController,
  ],
  exports: [
    TypeOrmModule,
    UsersService,
    PermissionsService,
    GroupsService,
    ProgramsService,
    ModulesService,
    UnitsService,
    ParametersService,
    MailService,
    UploadService,
  ],
})
export class AdminModule {}
