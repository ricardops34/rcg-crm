import { ApiProperty } from '@nestjs/swagger';
import { IsNotEmpty, IsString, IsOptional } from 'class-validator';

export class LoginDto {
  @ApiProperty({ 
    example: 'ricardo.admin', 
    description: 'Identificador único de acesso do usuário (Login, CPF ou E-mail)',
    minLength: 3
  })
  @IsString()
  @IsNotEmpty()
  login: string;

  @ApiProperty({ 
    example: 'P@ssw0rd123!', 
    description: 'Senha de acesso do usuário. Suporta hashes MD5 (legado) e Bcrypt (moderno).', 
    required: false,
    format: 'password'
  })
  @IsString()
  @IsOptional()
  password?: string;
}

export class Verify2faDto {
  @ApiProperty({ 
    example: '458291', 
    description: 'Código numérico de 6 dígitos enviado ao e-mail cadastrado do usuário.',
    minLength: 6,
    maxLength: 6
  })
  @IsString()
  @IsNotEmpty()
  code: string;
}

export class UserProfileDto {
  @ApiProperty({ example: 105, description: 'ID interno do usuário no sistema' })
  id: number;

  @ApiProperty({ example: 'Ricardo Engenheiro', description: 'Nome completo do usuário' })
  name: string;

  @ApiProperty({ example: 'ADMIN', description: 'Papel principal do usuário no sistema' })
  role: string;

  @ApiProperty({ example: 'RCG - Matriz', description: 'Nome da Unidade de negócio vinculada' })
  unitName: string;

  @ApiProperty({ example: 12, description: 'ID do Vendedor vinculado (se aplicável)', nullable: true })
  vendedorId?: number;
}

export class LoginResponseDto {
  @ApiProperty({ 
    description: 'Indica a próxima ação necessária para completar o acesso.', 
    enum: ['2FA', 'TERMS', null], 
    required: false,
    example: '2FA'
  })
  nextStep?: string;

  @ApiProperty({ 
    description: 'Token de portador (Bearer) para autenticação nas rotas protegidas.',
    example: 'eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9...' 
  })
  accessToken: string;

  @ApiProperty({ 
    description: 'Dados detalhados do perfil do usuário. Retornado apenas quando nextStep é nulo.', 
    type: UserProfileDto,
    required: false 
  })
  user?: UserProfileDto;
}
