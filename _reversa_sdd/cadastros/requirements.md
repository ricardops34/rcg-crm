# Cadastros — Requisitos

## Visão Geral
Esta unit gerencia as entidades fundamentais (Master Data) do ERP, como Clientes, Produtos, Tabelas de Preço e Categorias. É a base de dados mestre que alimenta todos os processos transacionais de venda, faturamento e cobrança.

## Responsabilidades
- Manter o cadastro detalhado de Clientes (PJ/PF) e suas associações operacionais. 🟢
- Gerir o catálogo de Produtos com atributos técnicos e fiscais. 🟢
- Configurar Tabelas de Preço e regras de precificação por item. 🟢
- Realizar validações cadastrais (CNPJ/CPF) e consultas externas (RFB). 🟢
- Rastrear alterações de dados sensíveis via Change Log. 🟢

## Regras de Negócio
- **Validação de Documento:** O sistema deve validar a máscara e a integridade de CPF/CNPJ. Para CNPJ, suporta consulta automatizada à base da Receita Federal. 🟢
- **Auditoria Obrigatória:** Entidades críticas como `Cliente` e `Produto` devem registrar histórico de todas as alterações de campos (quem, quando, de/para). 🟢 (Ref: ADR 002)
- **Status do Cliente:** Clientes bloqueados ('B') não podem realizar novos orçamentos ou pedidos, mas permanecem na base para histórico financeiro. 🟢
- **Relacionamentos Fortes:** Um cliente deve estar obrigatoriamente vinculado a um Vendedor e uma Condição de Pagamento padrão. 🟢
- **Tabelas de Preço:** Apenas tabelas com status 'Ativo' podem ser vinculadas a novos orçamentos. 🟢

## Requisitos Funcionais

| ID | Requisito | Prioridade | Critério de Aceite |
|----|-----------|-----------|-------------------|
| RF-01 | Cadastro de Cliente | Must | Formulário multi-aba com dados fiscais, contatos e limites de crédito. |
| RF-02 | Cadastro de Produto | Must | Inclusão de fotos, NCM, pesos e categorias. |
| RF-03 | Gestão de Tabelas de Preço | Must | Definição de valores por produto com vigência opcional. |
| RF-04 | Consulta RFB | Should | Autopreenchimento de endereço e razão social via CNPJ. |
| RF-05 | Change Log | Must | Gravação automática de deltas no banco `log`. |

## Requisitos Não Funcionais

| Tipo | Requisito inferido | Evidência no código | Confiança |
|------|--------------------|---------------------|-----------|
| Performance | Lazy Loading de relacionamentos no Active Record | `app/model/Cliente.php` | 🟢 |
| Integridade | Constraints de unicidade para `cod_erp` | `app/database/erp_online-pgsql.sql` | 🟢 |
| Usabilidade | Interface multi-aba para formulários complexos | `app/control/cadastros/ClienteForm.php` | 🟢 |

## Critérios de Aceitação

```gherkin
Cenário: Bloqueio de Cliente por Inadimplência
Dado que o cliente "Empresa X" está com status 'B'
Quando um vendedor tentar iniciar um orçamento para este cliente
Então o sistema deve exibir alerta "Cliente Bloqueado" e impedir a seleção

Cenário: Auditoria de Mudança de Limite
Dado que um usuário Admin altera o campo `limite` do cliente de R$ 1000 para R$ 5000
Quando a alteração for salva
Então um registro deve ser criado em `system_change_log` contendo o delta de alteração
```

## Prioridade (MoSCoW)

| Requisito | MoSCoW | Justificativa |
|-----------|--------|---------------|
| Master Data (Cliente/Produto) | Must | Sem dados mestre, nenhuma transação (Venda/NF) é possível. |
| Auditoria (Change Log) | Must | Requisito de compliance para ERPs. |
| Consulta Externa (RFB) | Should | Agiliza o cadastro, mas não é impeditivo funcional. |
| Fotos de Produto | Could | Auxílio visual, mas o sistema opera apenas com o código. |

## Rastreabilidade de Código

| Arquivo | Função / Classe | Cobertura |
|---------|-----------------|-----------|
| `app/model/Cliente.php` | `Cliente` | 🟢 |
| `app/model/Produto.php` | `Produto` | 🟢 |
| `app/control/cadastros/ClienteForm.php` | `ClienteForm` | 🟢 |
| `app/model/log/SystemChangeLogTrait.php`| `register()` | 🟢 |
