# Gerência — Fluxos Detalhados

## 1. Fluxo de Agregação de Dashboards (Multi-camada)

O Dashboard Gerencial não lê tabelas físicas diretamente, ele consome uma hierarquia de Views.

```mermaid
graph TD
    A[DashboardGerencia UI] --> B[View: ViewVendedorVendaMes]
    B --> C[View: ViewVendedorVenda]
    C --> D[Tabela: nota_saida_item]
    C --> E[Tabela: vendedor]
    B --> F[Tabela: meta_vendedor_mes]
    
    subgraph Camada de BI (SQL)
        B
        C
    end
    
    subgraph Camada de Dados (Física)
        D
        E
        F
    end
```

## 2. Fluxo de Controle de Permissão (Escopo Supervisor)

Garante que o gestor veja apenas o que lhe compete.

1. **Request:** Supervisor solicita Dashboard.
2. **Sessão:** O Controller lê `TSession::getValue('userunitid')` e `TSession::getValue('userid')`.
3. **Filtro de Equipe:**
    - O sistema busca em `supervisor_vendedor` todos os `vendedor_id` vinculados ao `userid` do gestor. 🟢
4. **Aplicação do TCriteria:**
    - A query enviada ao banco adiciona automaticamente `WHERE vendedor_id IN (...)`. 🟢
5. **Resultado:** O supervisor vê o ranking apenas da sua equipe. Administradores (sem filtro) veem a empresa toda.

## 3. Fluxo de Fechamento de Meta

1. No final do mês, o sistema não realiza um "frozen" (congelamento) automático.
2. Como as Views dependem de `curdate()` para alguns indicadores (ex: faturamento do dia), o histórico é preservado naturalmente pela data da Nota Fiscal.
3. **Retroatividade:** Alterar uma nota fiscal do mês passado reflete imediatamente no atingimento da meta daquele mês, pois o vínculo é dinâmico. 🟡
