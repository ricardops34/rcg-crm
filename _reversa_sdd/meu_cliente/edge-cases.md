# Meu Cliente — Casos de Borda (Edge Cases)

## 1. Login de Cliente com Vários Registros (CNPJ)
- **Cenário:** O mesmo CNPJ está cadastrado em `cliente_acesso` vinculado a duas filiais ou unidades de negócio diferentes.
- **Comportamento Legado:** O sistema retorna o primeiro registro encontrado no banco. 🟡
- **Risco:** O cliente pode não conseguir visualizar dados de todas as suas unidades se a conta não for única ou multi-vínculo.

## 2. Acesso com Títulos em Discussão Judicial
- **Cenário:** O cliente possui títulos na situação '6' (Judicial).
- **Comportamento Legado:** A listagem financeira pode ocultar esses títulos ou exibi-los sem opção de boleto, dependendo do filtro da `ViewTituloCliente`. 🟡
- **Impacto:** Confusão do cliente ao ver um saldo devedor diferente do acordado juridicamente.

## 3. Navegação em Abas sem Refresh de Sessão
- **Cenário:** O cliente deixa o portal aberto por 12 horas e tenta clicar em "Ver Nota Fiscal".
- **Comportamento Legado:** A sessão Adianti expira. Ao clicar, o sistema falha no construtor da página de destino e redireciona para o login. 🟢
- **Experiência:** Perda do contexto da busca que o cliente estava realizando.

## 4. Edição de Dados Sensíveis
- **Cenário:** O cliente altera o e-mail de faturamento através da tela "Meus Dados" no portal.
- **Comportamento Legado:** O sistema atualiza diretamente a tabela mestre `cliente`. 🟢
- **Risco:** Falta de validação humana (workflow) para mudanças críticas. No rebuild, recomenda-se que alterações feitas pelo portal do cliente gerem uma solicitação pendente de aprovação (como no `AtualizaCliente` dos vendedores).
