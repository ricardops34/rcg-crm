import { Controller, Get, Param, UseGuards } from '@nestjs/common';
import { ApiTags, ApiOperation, ApiBearerAuth } from '@nestjs/swagger';
import { WhatsAppService } from '../services/whatsapp.service';
import { JwtAuthGuard } from '../../auth/guards/jwt-auth.guard';
import { PermissionsGuard } from '../../auth/guards/permissions.guard';
import { RequirePermission } from '../../auth/decorators/permissions.decorator';

@ApiTags('Integrações / WhatsApp')
@ApiBearerAuth()
@Controller('whatsapp')
@UseGuards(JwtAuthGuard, PermissionsGuard)
@RequirePermission('WhatsAppService')
export class WhatsAppController {
  constructor(private readonly whatsappService: WhatsAppService) {}

  @Get('telefone/:telefone')
  async getByPhone(@Param('telefone') telefone: string) {
    return this.whatsappService.getFinancialPositionByPhone(telefone);
  }
}
