# Matriz de Permissões (RBAC) — rcg

O sistema utiliza um modelo de Controle de Acesso Baseado em Funções (RBAC), gerenciado através do módulo `admin`.

## 1. Papéis de Usuário (Grupos)

| Papel | Descrição |
|-------|-----------|
| **Admin** | Acesso total ao sistema, inclusive gestão de usuários, parâmetros e logs. |
| **Supervisor** | Visão estratégica de equipes, metas consolidadas e dashboards gerenciais. |
| **Vendedor** | Foco na carteira de clientes, agenda de atendimentos e dashboard pessoal. |
| **Cliente** | Acesso restrito ao portal B2B para consulta de seus próprios dados e notas. |

## 2. Matriz de Funcionalidades

| Funcionalidade | Vendedor | Supervisor | Admin |
|----------------|:--------:|:----------:|:-----:|
| Ver meus Clientes | ✅ | ✅ | ✅ |
| Ver todos os Clientes | ❌ | ✅ | ✅ |
| Editar Cadastros Mestre | ❌ | 🟡¹ | ✅ |
| Ver meu Dashboard | ✅ | ✅ | ✅ |
| Ver Dashboard da Equipe | ❌ | ✅ | ✅ |
| Definir Metas | ❌ | 🟡² | ✅ |
| Configurar Parâmetros | ❌ | ❌ | ✅ |
| Ver Log de Auditoria | ❌ | ❌ | ✅ |
| Gestão de Usuários | ❌ | ❌ | ✅ |

¹ Vendedores solicitam via `AtualizaCliente`, supervisores podem ter permissão de aprovação.
² Metas são geralmente definidas pelo Admin ou via integração, mas visíveis pelo Supervisor.

## 3. Restrições de Dados (Escopo)

- **Vendedor:** Restrito aos registros (`cliente`, `titulo`, `atendimento`) onde `vendedor_id == logado->vendedor_id`. 🟢 CONFIRMADO
- **Supervisor:** Pode ver registros de todos os vendedores listados em `supervisor_vendedor` para o seu `supervisor_id`. 🟢 CONFIRMADO
- **Unidade:** Se o usuário pertencer a uma unidade específica, o sistema aplica um filtro global (TCriteria) limitando os dados a `system_unit_id`. 🟡 INFERIDO
