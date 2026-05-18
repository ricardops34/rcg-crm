import { Test, TestingModule } from '@nestjs/testing';
import { getRepositoryToken } from '@nestjs/typeorm';
import { TabelaPrecoService } from './tabela-preco.service';
import { TabelaPreco } from '../../entities/tabela-preco.entity';
import { TabelaPrecoItem } from '../../entities/tabela-preco-item.entity';
import { NotFoundException } from '@nestjs/common';

describe('TabelaPrecoService', () => {
  let service: TabelaPrecoService;
  let tabelaPrecoRepository;
  let tabelaPrecoItemRepository;

  beforeEach(async () => {
    const module: TestingModule = await Test.createTestingModule({
      providers: [
        TabelaPrecoService,
        {
          provide: getRepositoryToken(TabelaPreco),
          useValue: {
            find: jest.fn(),
            findOne: jest.fn(),
          },
        },
        {
          provide: getRepositoryToken(TabelaPrecoItem),
          useValue: {
            findOne: jest.fn(),
          },
        },
      ],
    }).compile();

    service = module.get<TabelaPrecoService>(TabelaPrecoService);
    tabelaPrecoRepository = module.get(getRepositoryToken(TabelaPreco));
    tabelaPrecoItemRepository = module.get(getRepositoryToken(TabelaPrecoItem));
  });

  it('should be defined', () => {
    expect(service).toBeDefined();
  });

  describe('getProductPrice', () => {
    it('should return the price when item exists and is active', async () => {
      const mockItem = { preco: 100.5, status: 'A' };
      tabelaPrecoItemRepository.findOne.mockResolvedValue(mockItem);

      const price = await service.getProductPrice(1, 10);
      expect(price).toBe(100.5);
      expect(tabelaPrecoItemRepository.findOne).toHaveBeenCalledWith({
        where: { tabelaPrecoId: 1, produtoId: 10, status: 'A' }
      });
    });

    it('should throw NotFoundException when item does not exist', async () => {
      tabelaPrecoItemRepository.findOne.mockResolvedValue(null);

      await expect(service.getProductPrice(1, 10)).rejects.toThrow(NotFoundException);
    });
  });
});
