# Sistema — Casos de Borda (Edge Cases)

## 1. Parâmetro nulo vs String Vazia
- **Cenário:** Um registro existe em `parametro`, mas a coluna `conteudo` está vazia.
- **Comportamento Legado:** O sistema retorna a string vazia `""` e não prossegue para o fallback global. 🟢
- **Risco:** O gestor pode "anular" uma configuração global para uma unidade específica de forma intencional ou acidental.

## 2. Inconsistência entre Banco de Dados e application.ini
- **Cenário:** A conexão com o banco de dados principal falha, mas o sistema precisa carregar o log de erro.
- **Comportamento Legado:** Os logs dependem de uma conexão PDO com o banco `log`. Se as credenciais no `application.ini` estiverem erradas, o sistema entra em "Fatal Error" e o erro original é perdido por falha do próprio sistema de log. 🔴
- **Risco:** Falha silenciosa em situações catastróficas.

## 3. Logs de SQL com Dados Sensíveis (LGPD/PCI)
- **Cenário:** Um usuário altera a senha ou insere um cartão de crédito.
- **Comportamento Legado:** O `SystemSqlLog` grava o comando `INSERT/UPDATE` completo, incluindo os valores dos campos (bind params) em texto simples. 🟢
- **Risco:** Exposição de PII (Personally Identifiable Information) e segredos no banco de logs. Recomenda-se filtrar campos sensíveis no rebuild.

## 4. Auditoria de Alteração em Lote (Bulk Update)
- **Cenário:** Um script SQL é executado diretamente no banco (ex: via DBeaver) para atualizar o preço de 1.000 produtos.
- **Comportamento Legado:** O `SystemChangeLog` NÃO é disparado, pois ele reside na camada de aplicação (Traits do PHP). 🟢
- **Impacto:** Perda de rastreabilidade para manutenções manuais na base de dados.
