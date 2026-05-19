# Target Data Model — Reversa

Este documento descreve a estrutura de dados para o sistema Reversa, focando na compatibilidade com o PostgreSQL legado e nas extensões necessárias para a modernização.

## 1. Estratégia de Evolução de Dados
- **Compatibilidade**: Nomes de tabelas e colunas legadas são preservados para evitar quebras no sistema PHP que ainda consome o banco (Strangler Fig).
- **Extensões**: Novas colunas são adicionadas com `DEFAULT` ou `NULLABLE` para não impactar o legado.
- **Auditoria**: Implementação de triggers e subscribers do TypeORM para manter o `change_log`.

## 2. Extensões de Tabelas Core

### Tabela: `system_user` (Extensão da tabela de usuários)
| Coluna | Tipo | Descrição | Motivo |
| :--- | :--- | :--- | :--- |
| `password_hash` | varchar(255) | Hash Bcrypt da senha. | Substituir MD5 legado. |
| `mfa_enabled` | boolean | Status do 2FA. | Segurança (ADM-05). |
| `mfa_secret` | varchar(64) | Segredo TOTP. | Segurança (ADM-05). |
| `refresh_token` | text | Token para renovação de sessão. | JWT Stateless. |

### Tabela: `cliente` (Extensão Master Data)
| Coluna | Tipo | Descrição | Motivo |
| :--- | :--- | :--- | :--- |
| `search_vector` | tsvector | Vetor para Full Text Search. | Performance de busca no Angular. |
| `metadata` | jsonb | Campos dinâmicos/extensões. | Flexibilidade sem alterar esquema. |

### Tabela: `orcamento` / `pedido` (Extensão Sales)
| Coluna | Tipo | Descrição | Motivo |
| :--- | :--- | :--- | :--- |
| `external_ref` | uuid | ID único para rastreio externo. | Integração com microserviços. |
| `calculation_log` | jsonb | Detalhes do cálculo de impostos/descontos. | Rastreabilidade de regras complexas. |

## 3. Novas Tabelas de Infraestrutura (Modernização)

### Tabela: `modern_audit_log`
Centraliza a auditoria de alterações disparada pelos Interceptors do NestJS.
```sql
CREATE TABLE modern_audit_log (
    id UUID PRIMARY KEY DEFAULT gen_random_uuid(),
    entity_name VARCHAR(50) NOT NULL,
    entity_id INTEGER NOT NULL,
    action VARCHAR(10) NOT NULL, -- INSERT, UPDATE, DELETE
    old_data JSONB,
    new_data JSONB,
    user_id INTEGER REFERENCES system_user(id),
    changed_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);
```

### Tabela: `domain_event_store`
Para suporte a auditoria de eventos e replicação assíncrona.
```sql
CREATE TABLE domain_event_store (
    id UUID PRIMARY KEY DEFAULT gen_random_uuid(),
    event_name VARCHAR(100) NOT NULL,
    payload JSONB NOT NULL,
    occurred_at TIMESTAMP NOT NULL,
    processed_at TIMESTAMP
);
```

## 4. Estratégia de Índices
Para suportar a UX moderna (filtros rápidos):
- `idx_cliente_razao_trgm`: Índice Trigram no nome do cliente.
- `idx_titulo_vencimento_saldo`: Índice composto para o Dashboard de Cobrança.
- `idx_meta_vendedor_mes`: Índice para performance do Analytics.

## 5. Views de Compatibilidade (BI Leve)
As views legadas serão preservadas, mas novas views materializadas (`MATERIALIZED VIEW`) serão criadas no PostgreSQL para o módulo de **Analytics**, atualizadas via Jobs do NestJS ou Triggers.
