import { Injectable, BadRequestException } from '@nestjs/common';
import { InjectRepository } from '@nestjs/typeorm';
import { Repository, DataSource } from 'typeorm';
import { SystemUnit } from '../entities/system-unit.entity';
import * as fs from 'fs';
import * as path from 'path';

@Injectable()
export class UploadService {
  private readonly uploadRootDir = path.resolve(process.cwd(), 'uploads');

  constructor(
    @InjectRepository(SystemUnit, 'security')
    private readonly unitRepository: Repository<SystemUnit>,
    private readonly dataSource: DataSource,
  ) {
    // Garante que o diretório raiz de uploads exista
    if (!fs.existsSync(this.uploadRootDir)) {
      fs.mkdirSync(this.uploadRootDir, { recursive: true });
    }
  }

  /**
   * Obtém o uso de disco total de um tenant em bytes de forma recursiva.
   */
  obterUsoDiscoTenant(tenantId: number): number {
    const tenantDir = path.join(this.uploadRootDir, `tenant_${tenantId}`);
    if (!fs.existsSync(tenantDir)) {
      return 0;
    }

    const calcularTamanhoRecursivo = (dirPath: string): number => {
      let total = 0;
      const items = fs.readdirSync(dirPath);

      for (const item of items) {
        const fullPath = path.join(dirPath, item);
        try {
          const stats = fs.statSync(fullPath);
          if (stats.isDirectory()) {
            total += calcularTamanhoRecursivo(fullPath);
          } else {
            total += stats.size;
          }
        } catch (e) {
          // Ignora erros ao obter dados de arquivos específicos
        }
      }
      return total;
    };

    return calcularTamanhoRecursivo(tenantDir);
  }

  /**
   * Verifica a cota de armazenamento e salva o arquivo fisicamente no disco.
   * Retorna o caminho relativo a partir de 'uploads/'.
   */
  async verificarCotaESalvar(
    tenantId: number,
    file: Express.Multer.File,
    subpasta: string,
  ): Promise<string> {
    // 1. Obter cota configurada na filial (tabela system_unit)
    const unit = await this.unitRepository.findOne({ where: { id: tenantId } });
    const limiteMb = unit?.limiteDiscoMb ?? 1000; // Padrão: 1 GB (1000 MB)
    const limiteBytes = limiteMb * 1024 * 1024;

    // 2. Calcular uso atual + tamanho do novo arquivo
    const usoAtualBytes = this.obterUsoDiscoTenant(tenantId);
    const novoTamanhoBytes = usoAtualBytes + file.size;

    if (novoTamanhoBytes > limiteBytes) {
      const usoAtualMb = (usoAtualBytes / (1024 * 1024)).toFixed(2);
      throw new BadRequestException(
        `Limite de cota de armazenamento excedido para este tenant. Limite contratado: ${limiteMb} MB. Espaço ocupado atualmente: ${usoAtualMb} MB.`,
      );
    }

    // 3. Higienizar nome do arquivo e gerar nome único
    const extensao = path.extname(file.originalname).toLowerCase();
    const nomeHigienizado = path
      .basename(file.originalname, extensao)
      .replace(/[^a-zA-Z0-9_-]/g, '_');
    const timestamp = Date.now();
    const nomeArquivoUnico = `${nomeHigienizado}_${timestamp}${extensao}`;

    // 4. Estruturar caminho físico absoluto e criar pastas
    const relativeFolder = path.join(`tenant_${tenantId}`, subpasta);
    const targetFolder = path.join(this.uploadRootDir, relativeFolder);

    if (!fs.existsSync(targetFolder)) {
      fs.mkdirSync(targetFolder, { recursive: true });
    }

    const fullPath = path.join(targetFolder, nomeArquivoUnico);

    // 5. Escrever arquivo em disco
    fs.writeFileSync(fullPath, file.buffer);

    // Retorna a URL ou caminho estático relativo a partir de 'uploads/'
    // Usamos caminhos com barras normais (/) para compatibilidade de rotas web
    return `uploads/tenant_${tenantId}/${subpasta}/${nomeArquivoUnico}`.replace(/\\/g, '/');
  }

  /**
   * Remove fisicamente um arquivo do disco e higieniza pastas vazias.
   */
  removerArquivoFisico(caminhoRelativo: string): void {
    if (!caminhoRelativo) return;

    // Limpa caminhos absolutos acidentais para evitar que subam na árvore de diretórios
    const safePath = caminhoRelativo.replace(/^uploads[\\/]/, '');
    const absolutePath = path.join(this.uploadRootDir, safePath);

    try {
      if (fs.existsSync(absolutePath)) {
        fs.unlinkSync(absolutePath);

        // Higienização recursiva de diretórios vazios subindo na árvore
        let currentDir = path.dirname(absolutePath);
        while (currentDir !== this.uploadRootDir) {
          if (fs.existsSync(currentDir) && fs.readdirSync(currentDir).length === 0) {
            fs.rmdirSync(currentDir);
            currentDir = path.dirname(currentDir);
          } else {
            break;
          }
        }
      }
    } catch (e) {
      // Ignora falhas silenciosamente ao tentar remover arquivo inexistente
    }
  }

  /**
   * Varre todos os arquivos do diretório do tenant físico e remove órfãos.
   */
  executarLimpezaOrfaos(
    tenantId: number,
    arquivosValidosBanco: string[],
  ): { arquivosRemovidos: number; bytesLiberados: number } {
    const tenantDir = path.join(this.uploadRootDir, `tenant_${tenantId}`);
    if (!fs.existsSync(tenantDir)) {
      return { arquivosRemovidos: 0, bytesLiberados: 0 };
    }

    let arquivosRemovidos = 0;
    let bytesLiberados = 0;

    // Converte a lista de caminhos relativos válidos para termos de comparação segura
    const caminhosValidosSet = new Set(
      arquivosValidosBanco.map((c) => path.resolve(process.cwd(), c)),
    );

    const varrerELimpar = (dirPath: string) => {
      const items = fs.readdirSync(dirPath);

      for (const item of items) {
        const fullPath = path.join(dirPath, item);
        const stats = fs.statSync(fullPath);

        if (stats.isDirectory()) {
          varrerELimpar(fullPath);
        } else {
          // Se o arquivo físico absoluto não está nos válidos do banco de dados, remove
          if (!caminhosValidosSet.has(path.resolve(fullPath))) {
            try {
              const size = stats.size;
              fs.unlinkSync(fullPath);
              arquivosRemovidos++;
              bytesLiberados += size;
            } catch (e) {
              // falha na deleção individual
            }
          }
        }
      }

      // Se a pasta ficou vazia, remove-a
      if (fs.readdirSync(dirPath).length === 0 && dirPath !== tenantDir) {
        try {
          fs.rmdirSync(dirPath);
        } catch (e) {}
      }
    };

    varrerELimpar(tenantDir);

    return {
      arquivosRemovidos,
      bytesLiberados,
    };
  }

  /**
   * Executa a limpeza automatizada de arquivos órfãos para um tenant específico.
   * Consulta o banco de dados principal de forma autocontida (evitando dependências circulares).
   */
  async executarLimpezaOrfaosAuto(
    tenantId: number,
  ): Promise<{ arquivosRemovidos: number; bytesLiberados: number }> {
    // 1. Obter todas as imagens da galeria de produtos no banco
    const imagensProdutos: { caminho: string }[] = await this.dataSource.query(
      `SELECT caminho FROM public.produto_imagem WHERE system_unit_id = $1`,
      [tenantId],
    );

    // 2. Obter todos os anexos de atendimentos no banco
    const anexosAtendimentos: { anexo: string }[] = await this.dataSource.query(
      `SELECT anexo FROM public.atendimento WHERE system_unit_id = $1 AND anexo IS NOT NULL AND anexo != ''`,
      [tenantId],
    );

    // 3. Obter todos os logotipos de clientes no banco
    const logosClientes: { logo: string }[] = await this.dataSource.query(
      `SELECT logo FROM public.cliente WHERE system_unit_id = $1 AND logo IS NOT NULL AND logo != ''`,
      [tenantId],
    );

    // 4. Consolidar os caminhos válidos relativos salvos no banco
    const caminhosValidos: string[] = [
      ...imagensProdutos.map((i) => i.caminho),
      ...anexosAtendimentos.map((a) => a.anexo),
      ...logosClientes.map((l) => l.logo),
    ];

    // 5. Executar limpeza física no disco
    return this.executarLimpezaOrfaos(tenantId, caminhosValidos);
  }
}
