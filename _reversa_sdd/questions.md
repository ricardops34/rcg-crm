# Questões para Validação — rcg

Este documento agrupa as lacunas e incertezas identificadas durante a engenharia reversa que precisam de decisão humana para o rebuild do sistema.

## 1. Segurança e Acesso

### Q-01: Hashing de Senha B2B
No portal do cliente (`meu_cliente`), as senhas ainda utilizam hash MD5. No portal Admin, já existe um motor de rehash para Bcrypt.
- **Pergunta:** Devemos forçar a migração de todos os clientes para Bcrypt no primeiro acesso do novo sistema ou manter o MD5 por retrocompatibilidade?
- **Sugestão:** Migrar para Bcrypt/Argon2 obrigatoriamente. 🔴

### Q-02: Sessão Única (Single Session)
O sistema legado possui uma configuração de sessão única.
- **Pergunta:** Esta trava deve ser mantida? Se sim, deve ser implementada via banco de dados/Redis para suportar ambientes escaláveis? 🔴

## 2. Comercial e Vendas

### Q-03: Conversão Orçamento -> Pedido
Identificamos o protótipo de Orçamentos no módulo `desenvolvimento`, mas a conversão automática para a tabela de Pedidos legada não está explícita.
- **Pergunta:** O novo sistema será responsável por gerar o registro físico na tabela de pedidos do ERP legado ou apenas marcar o orçamento como "Ganho"? 🔴

### Q-04: Metas Dinâmicas
Atualmente, as metas de vendedores são inseridas 100% manualmente.
- **Pergunta:** Deseja incluir lógica de sugestão de metas baseada no realizado do ano anterior (+ X%) ou manter a inserção manual? 🔴

### Q-05: Cálculo de Comissão
Não encontramos a regra de cálculo de comissão de vendedores no código PHP.
- **Pergunta:** A comissão é calculada por triggers no banco de dados ou por um processo externo ao sistema web? Deve ser implementada no rebuild? 🔴

## 3. Financeiro e Fiscal

### Q-06: Juros e Multas na Cobrança
O módulo de `cobranca` agrupa títulos mas não exibe o cálculo de juros/multa em tempo real na listagem.
- **Pergunta:** O novo sistema deve calcular juros dinamicamente no frontend ou manter o padrão de visualização do saldo seco? 🔴

### Q-07: Transmissão SEFAZ
O módulo de `relatorios` gera a DANFE a partir de XMLs já autorizados.
- **Pergunta:** O novo sistema deve realizar a transmissão (envio/assinatura) da NFe para a SEFAZ ou apenas servir como visualizador de notas emitidas por outro software? 🔴

## 4. Infraestrutura e Manutenção

### Q-08: Retenção de Logs
O sistema registra logs detalhados de SQL e alterações.
- **Pergunta:** Qual a política de retenção desejada? (ex: manter logs por 6 meses e expurgar automaticamente). 🔴

### Q-09: Hierarquia de Supervisão
A hierarquia atual possui apenas dois níveis (Supervisor -> Vendedor).
- **Pergunta:** Existe a necessidade de suportar múltiplos níveis (ex: Gerente Regional acima do Supervisor)? 🔴
