# Cadastros, Design Técnico

## Interface

### Classes e Models Principais

| Símbolo | Assinatura | Retorno | Observação |
|---------|-----------|---------|------------|
| `Cliente.store()` | `()` | `void` | Persiste cliente e dispara ChangeLogTrait. 🟢 |
| `Produto.get_estoque()` | `($filial_id)` | `float` | Retorna saldo consolidado de estoque. 🟢 |
| `ClienteForm.onSave()` | `($param)` | `void` | Captura dados da interface multi-aba. 🟢 |
| `TabelaPreco.getPreco()`| `($produto_id)` | `float` | Busca o valor unitário vigente. 🟢 |
| `DataValida` | Service Class | - | Utilitário de validação de regras de negócio e datas cadastrais. 🟢 |

## Fluxo Principal (Cadastro de Cliente)

1. **Início:** O usuário acessa `ClienteForm`.
2. **Entrada de Documento:** Ao digitar CNPJ, o sistema pode disparar `onExitCNPJ` para consulta externa. 🟢
3. **Preenchimento:** Dados são distribuídos em abas:
    - **Aba 1 (Dados Gerais):** Razão, Fantasia, CNPJ/CPF, Endereço.
    - **Aba 2 (Comercial):** Vendedor, Tabela de Preço, Condição de Pagamento.
    - **Aba 3 (Contatos):** Grid mestre-detalhe para múltiplos contatos.
4. **Validação:** Checa duplicidade de `cod_erp` ou `cnpj_cpf`. 🟢
5. **Persistência:** Chama `Cliente::store()`.
6. **Auditoria:** A `SystemChangeLogTrait` intercepta o salvamento, compara os valores antigos com os novos e grava o delta na tabela `system_change_log` (banco `log`). 🟢

## Fluxos Alternativos

- **Bloqueio de Crédito:** Se o status for alterado para 'B', o sistema grava o motivo em `obs_bloqueio` e `dt_bloqueio`. 🟢
- **Duplicidade:** Se tentar salvar um `cod_erp` já existente, o banco lança erro de `UNIQUE KEY`, capturado pelo Controller. 🟢

## Dependências

- **Adianti TRecord:** Base para todos os Active Records. 🟢
- **SystemChangeLogTrait:** Implementa a lógica de auditoria "mágica". 🟢
- **BootstrapFormBuilder:** Gera a interface multi-aba responsiva. 🟢
- **Webservice RFB:** (Opcional) Integração externa para dados cadastrais. 🟡

## Decisões de Design Identificadas

| Decisão | Evidência no código | Confiança |
|---------|---------------------|-----------|
| Auditoria via Trait no Model | `app/model/Cliente.php:use SystemChangeLogTrait` | 🟢 |
| Chaves de integração Únicas | `app/database/erp_online-pgsql.sql:ALTER TABLE cliente ADD UNIQUE (cod_erp)` | 🟢 |
| Mestre-Detalhe para Contatos | `app/control/cadastros/ClienteForm.php:BuilderMasterDetailTrait` | 🟢 |

## Estado Interno

As entidades desta unit são "Stateful" por natureza:
- `Cliente`: Status (A/B), Limite de Crédito, Saldo Devedor (via View).
- `Produto`: Saldo em Estoque (vinculado à tabela `estoque`).
- `TabelaPreco`: Vigência (Início/Fim) e Atividade (S/N).

## Observabilidade

- **Change Log:** Rastreia cada campo alterado nas tabelas mestre. 🟢
- **SQL Log:** Captura as queries de atualização disparadas pelo formulário. 🟢

## Riscos e Lacunas

- 🔴 **Inativação em Lote:** Não foi encontrada lógica de inativação automática por falta de compra no código PHP analisado (provavelmente feito por job de banco).
- 🟡 **Cálculo de Risco:** O campo `risco` no Cliente parece ser alimentado por sistema externo ou integração ERP, não há lógica de cálculo de score no código atual.
