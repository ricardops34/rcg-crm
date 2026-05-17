# Sistema — Requisitos

## Visão Geral
Esta unit gerencia a infraestrutura lógica do ERP, centralizando parâmetros globais, tabelas de domínio (estados e tipos), o motor de auditoria técnica e serviços de utilidade essenciais. Ela garante que a aplicação seja configurável por ambiente/unidade, que toda operação de banco de dados seja rastreável e que dados externos (CEP, CNPJ) sejam integrados e normalizados.

## Responsabilidades
- Prover um motor de configuração dinâmica via banco de dados (Parâmetros). 🟢
- Registrar o histórico de comandos SQL executados por usuário e IP (Log SQL). 🟢
- Capturar e armazenar deltas de alteração de campos em tabelas mestre (Change Log). 🟢
- Gerenciar os dicionários de status para Pedidos e Orçamentos. 🟢
- Controlar workflows de saneamento de dados cadastrais. 🟢
- Fornecer serviços de normalização de dados (remoção de acentos, tratamento de strings). 🟢
- Integrar com APIs externas para consulta de CEP e CNPJ com preenchimento automático. 🟢

## Regras de Negócio
- **Prioridade de Parâmetro:** Parâmetros vinculados a uma `filial_id` específica devem sobrescrever parâmetros globais (`filial_id` nulo). 🟢
- **Rastreabilidade de Erro:** O log de SQL deve capturar obrigatoriamente o `log_trace` do PHP, permitindo identificar a classe e linha exata que originou a query. 🟢
- **Auditoria de Valor:** Toda alteração em entidades mestre deve registrar o `old_value` e o `new_value`, facilitando auditorias retroativas. 🟢
- **Imutabilidade de Log:** Registros de auditoria (SQL e Change Log) devem ser imutáveis e armazenados em banco de dados isolado (`log`). 🟢
- **Normalização de Busca:** Nomes de clientes e cidades devem ser convertidos para maiúsculas e sem acentos para garantir a eficácia dos filtros de busca. 🟢
- **Hidratação de Endereço:** Consultas de CEP devem preencher automaticamente Município e Estado, criando os registros locais se não existirem (auto-população da base). 🟢

## Requisitos Funcionais

| ID | Requisito | Prioridade | Critério de Aceite |
|----|-----------|-----------|-------------------|
| RF-01 | Gestão de Parâmetros | Must | CRUD para chaves e valores com escopo por unidade. |
| RF-02 | Auditoria SQL | Must | Interceptar todas as queries e gravar metadados (IP, Usuário, Trace). |
| RF-03 | Log de Alterações | Must | Gravar deltas de campos (`column_name`, `old`, `new`) via Active Record. |
| RF-04 | Gestão de Estados | Should | Manutenção dos dicionários de status de Pedido e Orçamento. |
| RF-05 | Workflow Cadastral | Should | Interface para revisão de solicitações de atualização de clientes. |
| RF-06 | Normalização de Texto | Must | Função centralizada para remover acentuação e padronizar case. |
| RF-07 | Consulta CEP/CNPJ | Should | Integração com APIs públicas (ReceitaWS, BrasilAPI) para automação cadastral. |

## Requisitos Não Funcionais

| Tipo | Requisito inferido | Evidência no código | Confiança |
|------|--------------------|---------------------|-----------|
| Segurança | Separação física de bancos de dados (Negócio vs Log) | `app/config/log.php` | 🟢 |
| Observabilidade | Stack trace automático em logs técnicos | `app/model/log/SystemSqlLog.php` | 🟢 |
| Flexibilidade | Configuração de comportamento via banco sem redestribuição de código | `app/model/Parametro.php` | 🟢 |
| Resiliência | Cache de consultas de CEP para evitar limites de taxa de APIs externas | `app/service/sistema/CEPCacheService.php` | 🟢 |

## Critérios de Aceitação

```gherkin
Cenário: Configuração de URL de Serviço
Dado que o parâmetro "ws_url" global é "https://legacy.com"
E existe um parâmetro "ws_url" para a Filial 1 como "https://nova-api.com"
Quando o sistema solicitar o valor de "ws_url" no contexto da Filial 1
Então o valor retornado deve ser "https://nova-api.com"

Cenário: Consulta de CEP com Auto-população
Dado que um CEP novo é consultado e a cidade não existe na base local
Quando a API externa retornar os dados do CEP
Então o sistema deve criar automaticamente o registro de Estado e Município
E retornar o ID local recém-criado para o formulário
```

## Prioridade (MoSCoW)

| Requisito | MoSCoW | Justificativa |
|-----------|--------|---------------|
| Auditoria e Logs | Must | Requisito não funcional crítico para manutenção e segurança. |
| Parâmetros Dinâmicos | Must | Permite customização do ERP sem mudança de código. |
| Normalização de Texto | Must | Garante integridade de buscas e relatórios. |
| Consulta CEP/CNPJ | Should | Agiliza o cadastro, mas o preenchimento manual é fallback. |
| Workflow de Saneamento | Should | Importante para qualidade de dados, mas secundário à auditoria. |

## Rastreabilidade de Código

| Arquivo | Função / Classe | Cobertura |
|---------|-----------------|-----------|
| `app/model/Parametro.php` | `Parametro` | 🟢 |
| `app/model/log/SystemSqlLog.php` | `SystemSqlLog` | 🟢 |
| `app/model/log/SystemChangeLog.php`| `SystemChangeLog` | 🟢 |
| `app/service/sistema/SisFunction.php`| `NoAcento`, `GetParm` | 🟢 |
| `app/service/sistema/CEPService.php` | `get` | 🟢 |
| `app/service/sistema/CNPJService.php`| `get` | 🟢 |
