import { ApiProperty, PartialType } from '@nestjs/swagger';
import { IsNotEmpty, IsString, IsOptional, IsNumber, IsEmail, IsDateString, MaxLength } from 'class-validator';

export class CreateClienteDto {
  @ApiProperty({ example: 'CLI001', description: 'Código identificador no ERP', maxLength: 10 })
  @IsString()
  @IsOptional()
  codErp?: string;

  @ApiProperty({ example: 'J', description: 'Tipo de Pessoa (F-Física, J-Jurídica)', enum: ['F', 'J'], default: 'J' })
  @IsString()
  @IsOptional()
  tipo?: string;

  @ApiProperty({ example: '12.345.678/0001-00', description: 'CNPJ ou CPF do cliente', maxLength: 20 })
  @IsString()
  @IsOptional()
  cnpjCpf?: string;

  @ApiProperty({ example: 'AUTO POSTO RCG LTDA', description: 'Razão Social / Nome completo', maxLength: 100 })
  @IsString()
  @IsNotEmpty({ message: 'A Razão Social é obrigatória' })
  razao: string;

  @ApiProperty({ example: 'POSTO RCG', description: 'Nome Fantasia', maxLength: 50, required: false })
  @IsString()
  @IsOptional()
  fantasia?: string;

  @ApiProperty({ example: 'Av. Afonso Pena, 1000', description: 'Logradouro / Endereço', maxLength: 100, required: false })
  @IsString()
  @IsOptional()
  endereco?: string;

  @ApiProperty({ example: 'Sala 01', description: 'Complemento do endereço', maxLength: 50, required: false })
  @IsString()
  @IsOptional()
  complemento?: string;

  @ApiProperty({ example: '79002-000', description: 'CEP (apenas números)', maxLength: 8, required: false })
  @IsString()
  @IsOptional()
  cep?: string;

  @ApiProperty({ example: 'Centro', description: 'Bairro', maxLength: 50, required: false })
  @IsString()
  @IsOptional()
  bairro?: string;

  @ApiProperty({ example: 12, description: 'ID do Estado (UF)', required: false })
  @IsNumber()
  @IsOptional()
  ufId?: number;

  @ApiProperty({ example: 1050, description: 'ID do Município', required: false })
  @IsNumber()
  @IsOptional()
  municipioId?: number;

  @ApiProperty({ example: 'João Silva', description: 'Nome do contato principal', maxLength: 30, required: false })
  @IsString()
  @IsOptional()
  contato?: string;

  @ApiProperty({ example: '123456789', description: 'Inscrição Estadual', maxLength: 20, required: false })
  @IsString()
  @IsOptional()
  ie?: string;

  @ApiProperty({ example: '987654321', description: 'Inscrição Municipal', maxLength: 20, required: false })
  @IsString()
  @IsOptional()
  im?: string;

  @ApiProperty({ example: 'S', description: 'Destaca IE na NF (S/N)', maxLength: 1, required: false })
  @IsString()
  @IsOptional()
  destacaIe?: string;

  @ApiProperty({ example: 'S', description: 'Contribuinte de ICMS (S/N)', maxLength: 1, required: false })
  @IsString()
  @IsOptional()
  contribuinte?: string;

  @ApiProperty({ example: '1234567-8', description: 'RG (Pessoa Física)', maxLength: 20, required: false })
  @IsString()
  @IsOptional()
  rg?: string;

  @ApiProperty({ example: '1980-05-20', description: 'Data de nascimento', required: false })
  @IsDateString()
  @IsOptional()
  nascimento?: string;

  @ApiProperty({ example: 'financeiro@cliente.com.br', description: 'E-mail principal', maxLength: 100, required: false })
  @IsEmail()
  @IsOptional()
  email?: string;

  @ApiProperty({ example: 'www.cliente.com.br', description: 'Site da empresa', maxLength: 100, required: false })
  @IsString()
  @IsOptional()
  site?: string;

  @ApiProperty({ example: '6733224455', description: 'Telefone comercial 1', maxLength: 20, required: false })
  @IsString()
  @IsOptional()
  telefone1?: string;

  @ApiProperty({ example: '6733224456', description: 'Telefone comercial 2', maxLength: 20, required: false })
  @IsString()
  @IsOptional()
  telefone2?: string;

  @ApiProperty({ example: '67999887766', description: 'Celular 1', maxLength: 20, required: false })
  @IsString()
  @IsOptional()
  celular?: string;

  @ApiProperty({ example: '67999887755', description: 'Celular 2', maxLength: 20, required: false })
  @IsString()
  @IsOptional()
  celular2?: string;

  @ApiProperty({ example: 12, description: 'ID do Vendedor vinculado', required: false })
  @IsNumber()
  @IsOptional()
  vendedorId?: number;

  @ApiProperty({ example: 1, description: 'ID da Condição de Pagamento', required: false })
  @IsNumber()
  @IsOptional()
  condicaoPagamentoId?: number;

  @ApiProperty({ example: 1, description: 'ID da Tabela de Preço', required: false })
  @IsNumber()
  @IsOptional()
  tabelaPrecoId?: number;

  @ApiProperty({ example: 1, description: 'ID do Segmento de mercado', required: false })
  @IsNumber()
  @IsOptional()
  seguimentoId?: number;

  @ApiProperty({ example: 'A', description: 'Situação (A-Ativo, B-Bloqueado)', enum: ['A', 'B'], default: 'A' })
  @IsString()
  @IsOptional()
  status?: string;

  @ApiProperty({ example: 1, description: 'ID do Motivo de Bloqueio', required: false })
  @IsNumber()
  @IsOptional()
  motivoBloqueioId?: number;

  @ApiProperty({ example: 'Inadimplência recorrente', description: 'Observações do bloqueio', required: false })
  @IsString()
  @IsOptional()
  obsBloqueio?: string;

  @ApiProperty({ example: 'Cliente bom pagador', description: 'Observações gerais', required: false })
  @IsString()
  @IsOptional()
  obs?: string;
}

export class UpdateClienteDto extends PartialType(CreateClienteDto) {}

export class ClienteResponseDto extends CreateClienteDto {
  @ApiProperty({ example: 1, description: 'ID interno do sistema' })
  id: number;

  @ApiProperty({ example: 'M', description: 'Risco de crédito (calculado)', readOnly: true })
  risco: string;

  @ApiProperty({ example: 15000.00, description: 'Limite de crédito (concedido)', readOnly: true })
  limite: number;

  @ApiProperty({ example: '2026-05-21', description: 'Data da última compra', readOnly: true })
  ultimaCompra: string;
}
