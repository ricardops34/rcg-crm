import { Injectable, NotFoundException, BadRequestException } from '@nestjs/common';
import { InjectRepository } from '@nestjs/typeorm';
import { Repository } from 'typeorm';
import { ClsService } from 'nestjs-cls';
import { Produto } from '../../entities/produto.entity';
import { Categoria } from '../../entities/categoria.entity';
import { ProdutoImagem } from '../../entities/produto-imagem.entity';
import { UploadService } from '../../../admin/services/upload.service';

@Injectable()
export class ProdutoService {
  constructor(
    @InjectRepository(Produto)
    private produtoRepository: Repository<Produto>,
    @InjectRepository(Categoria)
    private categoriaRepository: Repository<Categoria>,
    @InjectRepository(ProdutoImagem)
    private produtoImagemRepository: Repository<ProdutoImagem>,
    private readonly uploadService: UploadService,
    private readonly cls: ClsService,
  ) {}

  async findAll(query?: any): Promise<[Produto[], number]> {
    const user = this.cls.get('user');
    const systemUnitId = user?.unitId;

    const where: any = { status: 'A', ...query };
    if (systemUnitId) {
      where.systemUnitId = systemUnitId;
    }

    const [produtos, total] = await this.produtoRepository.findAndCount({
      relations: ['categoria', 'subCategoria', 'filial', 'imagens'],
      where,
      take: 100,
    });

    // Ordenação das imagens em memória: 'principal DESC', 'ordem ASC'
    produtos.forEach((produto) => {
      if (produto.imagens) {
        produto.imagens.sort((a, b) => {
          if (a.principal === 'S' && b.principal !== 'S') return -1;
          if (a.principal !== 'S' && b.principal === 'S') return 1;
          return a.ordem - b.ordem;
        });
      }
    });

    return [produtos, total];
  }

  async findOne(id: number): Promise<Produto> {
    const user = this.cls.get('user');
    const systemUnitId = user?.unitId;

    const where: any = { id };
    if (systemUnitId) {
      where.systemUnitId = systemUnitId;
    }

    const produto = await this.produtoRepository.findOne({
      where,
      relations: ['categoria', 'subCategoria', 'filial', 'imagens'],
    });

    if (!produto) {
      throw new NotFoundException(`Produto com ID ${id} não encontrado`);
    }

    if (produto.imagens) {
      produto.imagens.sort((a, b) => {
        if (a.principal === 'S' && b.principal !== 'S') return -1;
        if (a.principal !== 'S' && b.principal === 'S') return 1;
        return a.ordem - b.ordem;
      });
    }

    return produto;
  }

  async findCategorias(): Promise<Categoria[]> {
    const user = this.cls.get('user');
    const systemUnitId = user?.unitId;

    const where: any = { status: 'A' };
    if (systemUnitId) {
      where.systemUnitId = systemUnitId;
    }

    return this.categoriaRepository.find({ where });
  }

  async save(data: Partial<Produto>): Promise<Produto> {
    const user = this.cls.get('user');
    const systemUnitId = user?.unitId;

    if (systemUnitId && !data.systemUnitId) {
      data.systemUnitId = systemUnitId;
    }

    const produto = this.produtoRepository.create(data);
    return this.produtoRepository.save(produto);
  }

  async remove(id: number): Promise<void> {
    const produto = await this.findOne(id);
    
    // Antes de excluir o produto, remove fisicamente todos os arquivos de fotos associados no disco
    if (produto.imagens && produto.imagens.length > 0) {
      for (const img of produto.imagens) {
        this.uploadService.removerArquivoFisico(img.caminho);
      }
    }
    
    await this.produtoRepository.delete(id);
  }

  async adicionarImagem(produtoId: number, file: Express.Multer.File): Promise<ProdutoImagem> {
    const produto = await this.findOne(produtoId);
    const systemUnitId = produto.systemUnitId || 1;

    // 1. Salvar arquivo físico com verificação de cota
    const caminho = await this.uploadService.verificarCotaESalvar(
      systemUnitId,
      file,
      `produtos/produto_${produtoId}`,
    );

    // 2. Verificar se já existe alguma imagem principal
    const temPrincipal = await this.produtoImagemRepository.findOne({
      where: { produtoId, principal: 'S' },
    });

    const principal = temPrincipal ? 'N' : 'S';

    // 3. Salvar o registro no banco
    // Determinar próxima ordem
    const maxOrdem = await this.produtoImagemRepository
      .createQueryBuilder('pi')
      .select('MAX(pi.ordem)', 'max')
      .where('pi.produto_id = :produtoId', { produtoId })
      .getRawOne();
    
    const ordem = (maxOrdem?.max || 0) + 1;

    const novaImagem = this.produtoImagemRepository.create({
      produtoId,
      systemUnitId,
      caminho,
      principal,
      ordem,
    });

    const imagemSalva = await this.produtoImagemRepository.save(novaImagem);

    // 4. Se for a principal, atualiza a coluna 'foto' do produto para retrocompatibilidade
    if (principal === 'S') {
      await this.produtoRepository.update(produtoId, { foto: caminho });
    }

    return imagemSalva;
  }

  async definirPrincipal(imagemId: number): Promise<ProdutoImagem> {
    const user = this.cls.get('user');
    const systemUnitId = user?.unitId;

    const where: any = { id: imagemId };
    if (systemUnitId) {
      where.systemUnitId = systemUnitId;
    }

    const imagem = await this.produtoImagemRepository.findOne({ where });
    if (!imagem) {
      throw new NotFoundException('Imagem não encontrada');
    }

    const produtoId = imagem.produtoId;

    // 1. Definir principal = 'N' para todas as outras
    await this.produtoImagemRepository.update({ produtoId }, { principal: 'N' });

    // 2. Definir principal = 'S' para a imagem selecionada
    imagem.principal = 'S';
    await this.produtoImagemRepository.save(imagem);

    // 3. Atualizar coluna 'foto' do produto para manter compatibilidade
    await this.produtoRepository.update(produtoId, { foto: imagem.caminho });

    return imagem;
  }

  async removerImagem(imagemId: number): Promise<void> {
    const user = this.cls.get('user');
    const systemUnitId = user?.unitId;

    const where: any = { id: imagemId };
    if (systemUnitId) {
      where.systemUnitId = systemUnitId;
    }

    const imagem = await this.produtoImagemRepository.findOne({ where });
    if (!imagem) {
      throw new NotFoundException('Imagem não encontrada');
    }

    const produtoId = imagem.produtoId;
    const eraPrincipal = imagem.principal === 'S';

    // 1. Apagar do banco de dados
    await this.produtoImagemRepository.delete(imagemId);

    // 2. Deletar do disco
    this.uploadService.removerArquivoFisico(imagem.caminho);

    // 3. Se era a principal, precisamos promover outra imagem a principal
    if (eraPrincipal) {
      const proximaImagem = await this.produtoImagemRepository.findOne({
        where: { produtoId },
        order: { ordem: 'ASC', id: 'ASC' },
      });

      if (proximaImagem) {
        proximaImagem.principal = 'S';
        await this.produtoImagemRepository.save(proximaImagem);
        await this.produtoRepository.update(produtoId, { foto: proximaImagem.caminho });
      } else {
        // Sem imagens restantes
        await this.produtoRepository.update(produtoId, { foto: null });
      }
    }
  }

  async obterTodosCaminhosImagens(systemUnitId: number): Promise<string[]> {
    const imagens = await this.produtoImagemRepository.find({
      select: ['caminho'],
      where: { systemUnitId },
    });
    return imagens.map((img) => img.caminho);
  }
}
