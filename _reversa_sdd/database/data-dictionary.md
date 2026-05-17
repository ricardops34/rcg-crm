# Dicionário de Dados — erp_online

## 1. Núcleo Comercial

### Tabela: `cliente`
Cadastro central de clientes.
| Coluna | Tipo | Descrição |
|--------|------|-----------|
| `id` | SERIAL (PK) | Identificador único |
| `cod_erp` | varchar(10) | Código de integração legado (ÚNICO) |
| `razao` | varchar(100) | Nome/Razão Social (NOT NULL) |
| `cnpj_cpf` | varchar(20) | Documento fiscal |
| `status` | char(1) | 'A'=Ativo, 'B'=Bloqueado |
| `vendedor_id` | integer (FK) | Vendedor responsável |
| `limite` | float | Limite de crédito |
| `risco` | char(1) | Classificação de risco |

### Tabela: `vendedor`
Equipe de vendas.
| Coluna | Tipo | Descrição |
|--------|------|-----------|
| `id` | SERIAL (PK) | Identificador único |
| `nome` | varchar(100) | Nome completo |
| `supervisor_id` | integer (FK) | Supervisor direto |
| `desligado` | char(1) | Flag de atividade |

## 2. Vendas e Orçamentos

### Tabela: `orcamento`
| Coluna | Tipo | Descrição |
|--------|------|-----------|
| `id` | SERIAL (PK) | Identificador único |
| `valor_total` | float | Valor total orçado |
| `orcamento_estado_id`| integer (FK) | Status atual |

### Tabela: `orcamento_item`
| Coluna | Tipo | Descrição |
|--------|------|-----------|
| `orcamento_id` | integer (FK) | Vínculo com cabeçalho |
| `produto_id` | integer (FK) | Produto orçado |
| `preco_unit` | float | Valor unitário negociado |
| `desconto` | float | Percentual/Valor de desconto |

## 3. Financeiro

### Tabela: `titulo_receber`
Contas a receber.
| Coluna | Tipo | Descrição |
|--------|------|-----------|
| `id` | SERIAL (PK) | Identificador único |
| `numero` | char(9) | Número do título |
| `venc_real` | date | Data de vencimento efetiva |
| `saldo` | float | Valor pendente (0 = Baixado) |
| `situacao` | char(1) | Status de cobrança (0 a 6) |

## 4. Auditoria e Logs
- Campos padrão em todas as tabelas: `dt_inclusao` (timestamp), `dt_alteracao` (timestamp).
- Auditoria de exclusão lógica detectada em tabelas como `atendimento` (`dt_delete`).
