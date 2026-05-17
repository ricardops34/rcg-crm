# Matriz de Rastreabilidade: Código x Especificação (Code/Spec Matrix)

Mapeamento final entre os arquivos do legado analisados e suas respectivas unidades de especificação no SDD.

| Arquivo do Legado | Unit (Módulo) | Cobertura | Observação |
|:------------------|:--------------|:---------:|:-----------|
| `app/control/admin/LoginForm.php` | `admin` | 🟢 | Autenticação e rehash de senha. |
| `app/model/admin/SystemUsers.php` | `admin` | 🟢 | Regras de RBAC e validação. |
| `app/control/cadastros/ClienteForm.php` | `cadastros` | 🟢 | Cadastro mestre multi-aba. |
| `app/model/Cliente.php` | `cadastros` | 🟢 | Auditoria via ChangeLogTrait. |
| `app/control/cobranca/NegociacaoList.php` | `cobranca` | 🟢 | Validação de títulos vencidos. |
| `app/model/TituloReceber.php` | `cobranca` | 🟢 | Contas a receber. |
| `app/control/communication/SystemMessageForm.php` | `communication` | 🟢 | Mensageria interna. |
| `app/model/communication/SystemNotification.php` | `communication` | 🟢 | Motor de alertas. |
| `app/control/desenvolvimento/OrcamentoForm.php` | `desenvolvimento` | 🟢 | Protótipo master-detail. |
| `app/control/desenvolvimento/BoletoBradesco.php` | `desenvolvimento` | 🟢 | Integração OpenBoleto. |
| `app/control/gerencia/DashboardGerencia.php` | `gerencia` | 🟢 | KPIs e Rankings de equipe. |
| `app/model/MetaVendedorMes.php` | `gerencia` | 🟢 | Motor de objetivos mensais. |
| `app/control/meu_cliente/LoginClienteForm.php` | `meu_cliente` | 🟢 | Autenticação B2B isolada. |
| `app/control/meu_cliente/PainelClienteForm.php` | `meu_cliente` | 🟢 | Portal do cliente. |
| `app/control/relatorios/Danfe.php` | `relatorios` | 🟢 | Emissão via NFePHP. |
| `app/control/sistema/ParametroForm.php` | `sistema` | 🟢 | Configurações dinâmicas. |
| `app/model/log/SystemSqlLog.php` | `sistema` | 🟢 | Auditoria técnica de SQL. |
| `app/control/supervisor/DashboardSupervisor.php` | `supervisor` | 🟢 | Filtro cascata de equipe. |
| `app/control/vendedor/DashboardVendedor.php` | `vendedor` | 🟢 | Performance individual. |
| `app/model/Mvc.php` | `vendedor` | 🟢 | Inteligência CRM (Média). |

## Resumo de Cobertura
- **Units Documentadas:** 11 de 11 identificadas.
- **Percentual de Mapeamento Técnico:** ~90% (arquivos principais cobertos). 🟡
- **Lacunas Identificadas:** Jobs de banco de dados (Stored Procedures/Triggers) sem código PHP associado.
