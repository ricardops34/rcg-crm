import { Module } from '@nestjs/common';
import { TypeOrmModule } from '@nestjs/typeorm';
import { SystemUnit } from './entities/system-unit.entity';
import { SystemGroup } from './entities/system-group.entity';
import { SystemProgram } from './entities/system-program.entity';
import { SystemUser } from './entities/system-user.entity';

@Module({
  imports: [
    TypeOrmModule.forFeature([
      SystemUnit,
      SystemGroup,
      SystemProgram,
      SystemUser
    ])
  ],
  exports: [TypeOrmModule],
})
export class AdminModule {}
