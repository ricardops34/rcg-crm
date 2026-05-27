import { Controller, Get, Post, Put, Delete, Body, Param, UseGuards } from '@nestjs/common';
import { ApiTags, ApiOperation, ApiBearerAuth, ApiResponse, ApiBody } from '@nestjs/swagger';
import { UnitsService } from './units.service';
import { JwtAuthGuard } from '../auth/guards/jwt-auth.guard';
import { PermissionsGuard } from '../auth/guards/permissions.guard';
import { RequirePermission } from '../auth/decorators/permissions.decorator';
import { CreateUnitDto, UpdateUnitDto } from './dto/admin-forms.dto';
import { ClsService } from 'nestjs-cls';
import { UploadService } from './services/upload.service';

@ApiTags('Admin / Units')
@ApiBearerAuth()
@Controller('admin/units')
@UseGuards(JwtAuthGuard, PermissionsGuard)
export class UnitsController {
  constructor(
    private readonly unitsService: UnitsService,
    private readonly uploadService: UploadService,
    private readonly cls: ClsService,
  ) {}

  @Get()
  @ApiOperation({ summary: 'Lista todas as unidades' })
  async findAll() {
    return this.unitsService.findAll();
  }

  @Get(':id')
  @ApiOperation({ summary: 'Obtém detalhes de uma unidade' })
  async findOne(@Param('id') id: string) {
    return this.unitsService.findOne(+id);
  }

  @Post()
  @RequirePermission('SystemUnitList')
  @ApiOperation({ summary: 'Cadastra uma nova unidade' })
  @ApiBody({ type: CreateUnitDto })
  @ApiResponse({ status: 201, description: 'Unidade criada com sucesso' })
  async create(@Body() data: CreateUnitDto) {
    return this.unitsService.save(data);
  }

  @Put(':id')
  @RequirePermission('SystemUnitList')
  @ApiOperation({ summary: 'Atualiza dados de uma unidade' })
  @ApiBody({ type: UpdateUnitDto })
  @ApiResponse({ status: 200, description: 'Unidade atualizada com sucesso' })
  async update(@Param('id') id: string, @Body() data: UpdateUnitDto) {
    return this.unitsService.save({ ...data, id: +id });
  }

  @Delete(':id')
  @RequirePermission('SystemUnitList')
  @ApiOperation({ summary: 'Remove uma unidade' })
  @ApiResponse({ status: 200, description: 'Unidade removida com sucesso' })
  async remove(@Param('id') id: string) {
    return this.unitsService.remove(+id);
  }

  @Get('diagnostico/uso-disco')
  @ApiOperation({ summary: 'Obtém o diagnóstico de consumo de cota de disco em tempo real para o tenant ativo' })
  async diagnosticoUsoDisco() {
    const user = this.cls.get('user');
    const tenantId = user?.unitId || 1;

    const unit = await this.unitsService.findOne(tenantId);
    const limiteMb = unit?.limiteDiscoMb ?? 1000;

    const usoBytes = this.uploadService.obterUsoDiscoTenant(tenantId);
    const usoMb = parseFloat((usoBytes / (1024 * 1024)).toFixed(2));
    const percentualUsado = parseFloat(((usoMb / limiteMb) * 100).toFixed(1));

    return {
      tenantId,
      tenantName: unit?.name || 'Unidade Principal',
      limiteMb,
      usoBytes,
      usoMb,
      percentualUsado,
    };
  }

  @Post('maintenance/disk-cleanup')
  @RequirePermission('SystemUnitList')
  @ApiOperation({ summary: 'Dispara a limpeza de arquivos órfãos físicos no disco para o tenant ativo' })
  async executarLimpeza() {
    const user = this.cls.get('user');
    const tenantId = user?.unitId || 1;

    const resultado = await this.uploadService.executarLimpezaOrfaosAuto(tenantId);
    const megabytesLiberados = parseFloat((resultado.bytesLiberados / (1024 * 1024)).toFixed(2));

    return {
      tenantId,
      arquivosRemovidos: resultado.arquivosRemovidos,
      bytesLiberados: resultado.bytesLiberados,
      megabytesLiberados,
      mensagem: `Limpeza finalizada com sucesso! Foram removidos ${resultado.arquivosRemovidos} arquivos órfãos, liberando ${megabytesLiberados} MB.`,
    };
  }
}
