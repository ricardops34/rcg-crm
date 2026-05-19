# Data Migration Plan — Reversa

Este documento detalha o plano de migração e sincronização de dados entre o legado (PHP) e a nova stack (NestJS), operando em um cenário de **Banco de Dados Compartilhado**.

## 1. Premissa Estratégica
Como o banco de dados PostgreSQL é compartilhado entre as duas aplicações durante a fase de transição (Strangler Fig), não haverá um "Dump & Load" massivo. A migração foca na **Evolução do Esquema** e **Enriquecimento de Dados**.

## 2. Fase de Preparação (D-Day - Infra)
Execução de scripts DDL para preparar o terreno:
1. **Adição de Colunas Modernas**: `password_hash`, `mfa_secret`, `refresh_token` na tabela de usuários.
2. **UUIDs**: Adição de colunas UUID em tabelas transacionais (`orcamento`, `pedido`) para referenciamento externo estável.
3. **Audit Triggers**: Instalação de triggers no PostgreSQL para que inserções no legado alimentem a tabela `modern_audit_log` (opcional, se paridade total de auditoria for requerida).

## 3. Estratégia de Migração de Senhas (Lazy Migration)
Para evitar o reset forçado de senha de todos os usuários:
1. O usuário tenta login no NestJS.
2. O NestJS verifica se `password_hash` está vazio.
3. Se estiver vazio, o NestJS valida a senha fornecida contra o MD5 legado (coluna `password` original).
4. Se o MD5 bater:
    - O NestJS gera o Bcrypt da senha.
    - Salva o Bcrypt na coluna `password_hash`.
    - Limpa (opcionalmente) ou mantém a coluna `password` original para compatibilidade.
5. Se o MD5 não bater, o login falha.

## 4. Backfill de Dados (Scripts de Manutenção)
Execução de Jobs assíncronos via NestJS (ou scripts SQL diretos) para popular campos novos em registros existentes:
- **Search Vectors**: Popular `search_vector` em `cliente` e `produto` para habilitar busca rápida.
- **Normalization**: Ajustar máscaras de CPF/CNPJ para padrão único se houver divergências no legado.
- **Hierarquia**: Gerar os vínculos de supervisão recursiva com base na tabela `supervisor_vendedor` legada.

## 5. Triggers de Sincronização (Bridge)
Para garantir que o legado não quebre regras de negócio modernas:
- **Trigger de Validação**: Impedir inserção de pedidos para clientes com status 'B' (Bloqueado), mesmo via SQL direto ou PHP legado.
- **Trigger de Auditoria**: Garantir que `dt_alteracao` seja sempre atualizado via `BEFORE UPDATE` no banco.

## 6. Plano de Validação (Smoke Tests de Dados)
Scripts automatizados que rodam a cada entrega de módulo:
| Teste | Descrição | Tolerância |
| :--- | :--- | :--- |
| **Row Count Sync** | Compara contagem de registros entre as Views do legado e as Entidades do novo. | 0 erros |
| **Financial Parity** | Compara a soma de `saldo` em `titulo_receber` por cliente. | 0.01 centavos |
| **Performance Check** | Valida se os novos índices estão sendo usados pelas queries do NestJS. | < 200ms |

## 7. Rollback de Dados
Como o banco é o mesmo, o rollback de código (reverter NestJS para PHP via Traefik) é imediato. 
- As colunas novas adicionadas são inofensivas para o legado.
- Em caso de corrupção de dados via NestJS, o rollback depende dos backups diários do RDS/PostgreSQL.
