# Especificação de Telas: Módulo Gerência

Este documento detalha os componentes e comportamentos das interfaces do módulo de Gerência.

## 1. Dashboard Gerencial
**Propósito:** Fornecer uma visão macro do desempenho comercial para supervisores e administradores.

- **Estado:** Preenchido (exibindo indicadores de BI).
- **Contexto:** Acessado via menu principal "Gerência > Dashboard".
- **Elementos de Interface:**
    - **Indicadores (KPIs):** Sugestão de Venda (R$), Ticket Médio, Devoluções, Clientes Positivados.
    - **Gráficos:** Vendas por Categoria (Gráfico de barras horizontal/TableChart).
    - **Ranking:** Listagem de vendedores com barra de progresso (% atingimento da meta).
    - **Filtros:** Vendedor, Ano, Mês, Dia.
- **Screenshot:** `screenshots/dashboard-gerencial.png`

## 2. Cadastro de Vendedores
**Propósito:** Gerenciar os dados cadastrais e status dos representantes comerciais.

- **Estado:** Formulário de edição.
- **Contexto:** Acessado via menu "Gerência > Vendedores".
- **Elementos de Interface:**
    - **Campos:** Nome, Nome Reduzido, E-mail, Celular, Usuário de Sistema (FK), Status (Ativo/Bloqueado/Desligado).
    - **Ações:** Salvar, Novo, Excluir (se permitido), Voltar para Lista.
- **Screenshot:** `screenshots/cadastro-de-vendedores.png`

## 3. Cadastro de Objetivos (Metas)
**Propósito:** Definir as metas financeiras e operacionais por vendedor e período.

- **Estado:** Formulário mestre-detalhe.
- **Contexto:** Acessado via menu "Gerência > Metas".
- **Elementos de Interface:**
    - **Mestre:** Vendedor, Mês, Ano, Valor Total da Meta, Metas de Novos Clientes e Positivação.
    - **Detalhe (Grade):** Desdobramento da meta por Categoria de Produto.
    - **Ações:** Adicionar item na grade, Salvar meta consolidada.
- **Screenshot:** `screenshots/cadastro-de-objetivos.png`
