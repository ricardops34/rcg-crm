import {
  Body,
  Controller,
  Get,
  Post,
  Delete,
  Param,
  ParseIntPipe,
  Query,
  Req,
  UseGuards,
  UseInterceptors,
  UploadedFile,
} from '@nestjs/common';
import { FileInterceptor } from '@nestjs/platform-express';
import { JwtAuthGuard } from '../../auth/guards/jwt-auth.guard';
import { PermissionsGuard } from '../../auth/guards/permissions.guard';
import { RequirePermission } from '../../auth/decorators/permissions.decorator';
import { ApiTags, ApiOperation, ApiBearerAuth, ApiResponse, ApiBody } from '@nestjs/swagger';
import { AtendimentoService } from '../services/atendimento.service';
import { MulterFile } from '../../admin/services/upload.service';
import { CreateAtendimentoDto, AtendimentoResponseDto, AtendimentoTipoResponseDto } from '../../crm/dto/atendimento.dto';

interface AuthenticatedRequest extends Request {
  user: {
    userId: number;
    username: string;
    vendedorId?: number;
    isGerente: boolean;
    managedVendedorIds: number[];
  };
}

@ApiTags('CRM / Atendimentos')
@ApiBearerAuth()
@Controller('crm')
@UseGuards(JwtAuthGuard, PermissionsGuard)
@RequirePermission('MvcList')
export class AtendimentoController {
  constructor(private readonly atendimentoService: AtendimentoService) {}

  @Get('tipos')
  @ApiOperation({ summary: 'Lista os tipos de atendimento disponíveis' })
  @ApiResponse({ status: 200, type: [AtendimentoTipoResponseDto] })
  async getTipos() {
    return this.atendimentoService.getTipos();
  }

  @Get('atendimentos')
  @ApiOperation({ summary: 'Lista atendimentos realizados ou agendados' })
  @ApiResponse({ status: 200, type: [AtendimentoResponseDto] })
  async findAll(
    @Req() req: AuthenticatedRequest,
    @Query('start') start?: string,
    @Query('end') end?: string,
    @Query('vendedorId') vendedorId?: number,
  ) {
    return this.atendimentoService.findAll(
      start,
      end,
      this.resolveVendedorId(req.user, vendedorId),
    );
  }

  @Post('atendimentos')
  @ApiOperation({ summary: 'Registra um novo atendimento ou agendamento' })
  @ApiBody({ type: CreateAtendimentoDto })
  @ApiResponse({ status: 201, type: AtendimentoResponseDto })
  async save(@Req() req: AuthenticatedRequest, @Body() data: CreateAtendimentoDto) {
    return this.atendimentoService.save(
      data,
      req.user.userId,
      this.resolveVendedorId(req.user, data.vendedorId),
    );
  }

  private resolveVendedorId(user: any, vendedorId?: number) {
    if (user?.isGerente) {
      return vendedorId;
    }

    if (
      vendedorId &&
      user?.managedVendedorIds?.includes(Number(vendedorId))
    ) {
      return Number(vendedorId);
    }

    return user?.vendedorId || vendedorId;
  }

  @Post('atendimentos/:id/anexo')
  @ApiOperation({ summary: 'Adiciona ou atualiza o anexo de um atendimento' })
  @UseInterceptors(FileInterceptor('file'))
  async adicionarAnexo(
    @Param('id', ParseIntPipe) id: number,
    @UploadedFile() file: MulterFile,
  ) {
    return this.atendimentoService.adicionarAnexo(id, file);
  }

  @Delete('atendimentos/:id/anexo')
  @ApiOperation({ summary: 'Remove o anexo de um atendimento' })
  async removerAnexo(@Param('id', ParseIntPipe) id: number) {
    return this.atendimentoService.removerAnexo(id);
  }
}
