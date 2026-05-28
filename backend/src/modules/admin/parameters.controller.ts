import { Body, Controller, Delete, Get, Param, Post, Put, UseGuards } from '@nestjs/common';
import { ApiBearerAuth, ApiBody, ApiOperation, ApiResponse, ApiTags } from '@nestjs/swagger';
import { JwtAuthGuard } from '../auth/guards/jwt-auth.guard';
import { PermissionsGuard } from '../auth/guards/permissions.guard';
import { RequirePermission } from '../auth/decorators/permissions.decorator';
import { ParametersService } from './parameters.service';
import { MailService } from './services/mail.service';
import { CreateParameterDto, UpdateParameterDto } from './dto/admin-forms.dto';

@ApiTags('Admin / Parameters')
@ApiBearerAuth()
@Controller('admin/parameters')
@UseGuards(JwtAuthGuard, PermissionsGuard)
@RequirePermission('SystemParameterList')
export class ParametersController {
  constructor(
    private readonly parametersService: ParametersService,
    private readonly mailService: MailService,
  ) {}

  @Get()
  @ApiOperation({ summary: 'Lista todos os parametros do sistema' })
  async findAll() {
    return this.parametersService.findAll();
  }

  @Get(':id')
  @ApiOperation({ summary: 'Obtem detalhes de um parametro' })
  async findOne(@Param('id') id: string) {
    return this.parametersService.findOne(+id);
  }

  @Post()
  @ApiOperation({ summary: 'Cadastra um novo parametro' })
  @ApiBody({ type: CreateParameterDto })
  @ApiResponse({ status: 201, description: 'Parametro criado com sucesso' })
  async create(@Body() data: CreateParameterDto) {
    return this.parametersService.save(data);
  }

  @Put(':id')
  @ApiOperation({ summary: 'Atualiza dados de um parametro' })
  @ApiBody({ type: UpdateParameterDto })
  @ApiResponse({ status: 200, description: 'Parametro atualizado com sucesso' })
  async update(@Param('id') id: string, @Body() data: UpdateParameterDto) {
    return this.parametersService.save({ ...data, id: +id });
  }

  @Delete(':id')
  @ApiOperation({ summary: 'Remove um parametro' })
  @ApiResponse({ status: 200, description: 'Parametro removido com sucesso' })
  async remove(@Param('id') id: string) {
    return this.parametersService.remove(+id);
  }

  @Post('test-smtp')
  @ApiOperation({ summary: 'Testa a conexao SMTP enviando um e-mail de teste real' })
  @ApiResponse({ status: 200, description: 'Conexao testada com sucesso' })
  async testSmtp(@Body() data: any) {
    return this.mailService.testSmtpConnection(data);
  }
}
