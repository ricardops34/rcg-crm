# Supervisor — Requisitos

## Visão Geral
Esta unit gerencia a hierarquia comercial de campo, permitindo que gestores (Supervisores) organizem e monitorem suas equipes de vendas. Ela atua como uma camada de abstração entre a gerência estratégica e a execução operacional, garantindo que o fluxo de informações e indicadores flua corretamente dentro da árvore de subordinação.

## Responsabilidades
- Manter o cadastro de Supervisores e seu vínculo com usuários de sistema. 🟢
- Gerir a composição das equipes de vendas (relação Supervisor x Vendedor). 🟢
- Fornecer visão operacional da saúde da carteira de clientes subordinada. 🟢
- Permitir a filtragem dinâmica de indicadores baseada na estrutura de liderança. 🟢

## Regras de Negócio
- **Vínculo N:N:** Um supervisor pode liderar múltiplos vendedores, e um vendedor pode (teoricamente pela estrutura de banco `supervisor_vendedor`) estar vinculado a mais de um supervisor, embora a prática operacional sugira subordinação direta única. 🟢
- **Filtro de Equipe:** Ao selecionar um supervisor em qualquer tela de gestão, o sistema deve recarregar os campos de seleção de vendedor para exibir apenas os membros de sua equipe. 🟢
- **Controle de Inatividade:** Supervisores marcados como `desligado = 'S'` não devem aparecer em filtros ativos, mas seus registros históricos de subordinação devem ser preservados. 🟢
- **KPIs de Carteira:** O sistema deve calcular automaticamente a quantidade de clientes ativos e bloqueados para cada vendedor da equipe do supervisor. 🟢

## Requisitos Funcionais

| ID | Requisito | Prioridade | Critério de Aceite |
|----|-----------|-----------|-------------------|
| RF-01 | Cadastro de Supervisor | Must | Vincular um nome de supervisor a um `system_user_id` válido. |
| RF-02 | Composição de Equipe | Must | Interface para adicionar/remover vendedores da equipe de um supervisor. |
| RF-03 | Dashboard do Supervisor | Must | Exibir indicadores de performance e saúde da carteira (Ativos/Bloqueados) da equipe. |
| RF-04 | Filtro Cascata (AJAX) | Must | Atualizar a lista de vendedores em tempo real ao trocar o supervisor no filtro. |
| RF-05 | Histórico de Liderança | Should | Preservar a data de inclusão (`dt_inclusao`) do vendedor na equipe. |

## Requisitos Não Funcionais

| Tipo | Requisito inferido | Evidência no código | Confiança |
|------|--------------------|---------------------|-----------|
| Performance | Uso de AJAX para recarregamento de combos de equipe | `app/control/supervisor/DashboardSupervisor.php` | 🟢 |
| Integridade | Chaves estrangeiras obrigatórias em `supervisor_vendedor` | `_reversa_sdd/database/data-dictionary.md` | 🟢 |
| Usabilidade | Representação de lista de subordinados como string separada por vírgula | `app/model/Supervisor.php:get_supervisor_vendedor_vendedor_to_string` | 🟢 |

## Critérios de Aceitação

```gherkin
Cenário: Troca de Supervisor no Filtro
Dado que o Supervisor "Carlos" lidera os vendedores "A" e "B"
E o Supervisor "Ana" lidera os vendedores "C" e "D"
Quando o usuário selecionar "Carlos" no filtro de Supervisor
Então o campo de seleção de Vendedor deve exibir apenas "A" e "B"

Cenário: Análise de Saúde da Carteira
Dado que o vendedor "A" possui 10 clientes ativos e 2 bloqueados
Quando o supervisor visualizar o dashboard da equipe
Então o sistema deve totalizar 12 clientes para o vendedor "A" com o detalhamento do status
```

## Prioridade (MoSCoW)

| Requisito | MoSCoW | Justificativa |
|-----------|--------|---------------|
| Gestão de Equipe (N:N) | Must | Base para o funcionamento do RBAC e filtros de faturamento por supervisor. |
| Dashboard Operacional | Must | Permite a tomada de decisão sobre clientes bloqueados na base. |
| Filtro Cascata | Should | Melhora a usabilidade, mas o filtro manual via ID ainda funcionaria. |
| Nome Reduzido | Could | Facilitador visual para interfaces mobile/compactas. |

## Rastreabilidade de Código

| Arquivo | Função / Classe | Cobertura |
|---------|-----------------|-----------|
| `app/model/Supervisor.php` | `Supervisor` | 🟢 |
| `app/model/SupervisorVendedor.php` | `SupervisorVendedor` | 🟢 |
| `app/control/supervisor/DashboardSupervisor.php`| `DashboardSupervisor` | 🟢 |
| `app/model/Vendedor.php` | `get_vendedor()` | 🟢 |
