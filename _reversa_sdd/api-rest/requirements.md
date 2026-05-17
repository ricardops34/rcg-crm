# API REST — Requisitos

## Visão Geral
Esta unit documenta a interface programática (API) do sistema rcg, implementada via `MadRestServer` e serviços Adianti. Ela permite a integração bidirecional com sistemas externos, sincronização de dados mestre (ERP) e automações como envio de mensagens via WhatsApp e monitoramento de faturamento.

## Responsabilidades
- Prover endpoints REST para CRUD de entidades mestre (Cliente, Produto, Pedido). 🟢
- Permitir a sincronização de dados de faturamento (Nota Saída) e financeiro. 🟢
- Integrar com serviços de mensageria (WhatsApp) para notificações de clientes. 🟢
- Fornecer dados analíticos (Venda Mes, Estoque) para sistemas de BI externos. 🟢
- Garantir a integridade referencial durante a importação de dados legados (`StoreArray`). 🟢

## Regras de Negócio
- **Resolução de IDs Legados:** A API utiliza o campo `cod_erp` como chave de busca principal para resolver IDs internos durante inserções e atualizações (`StoreArray`). 🟢
- **Sincronização Bidirecional:** Registros processados via API recebem a flag `sinc = 'S'` para evitar loops de sincronização. 🟢
- **Conversão de Tipos:** A API deve converter descrições textuais (ex: "Ativa", "Normal") em IDs de tabelas de domínio correspondentes. 🟢
- **Inteligência WhatsApp:** O serviço de WhatsApp identifica clientes pelo número de celular e retorna um resumo financeiro (vencidos/vencendo) em tempo real. 🟢
- **Segurança de API:** O acesso é mediado pelo `MadRestServer` com suporte a middlewares de autenticação (Bearer/Basic). 🟡

## Requisitos Funcionais

| ID | Requisito | Prioridade | Critério de Aceite |
|----|-----------|-----------|-------------------|
| RF-01 | Importação de Clientes | Must | Endpoint para receber array de clientes e processar via `cod_erp`. |
| RF-02 | Sincronização de Pedidos | Must | Registrar pedidos externos garantindo vínculos com Vendedor e Transportadora. |
| RF-03 | Integração WhatsApp | Should | Buscar cliente por telefone e retornar posição financeira simplificada. |
| RF-04 | Consulta de Estoque | Should | Endpoint para retornar saldo por produto e armazém. |
| RF-05 | Download de XML (API) | Should | Fornecer o conteúdo bruto do XML da NFe via REST. |

## Requisitos Não Funcionais

| Tipo | Requisito inferido | Evidência no código | Confiança |
|------|--------------------|---------------------|-----------|
| Performance | Processamento em lote via `StoreArray` | `app/service/api/ClienteRestService.php` | 🟢 |
| Interoperabilidade| Formatos JSON e suporte a CORS | `MadRestServer.php` | 🟢 |
| Segurança | Middleware de autenticação por token | `MadRestServer.php:19` | 🟡 |

## Prioridade (MoSCoW)

| Requisito | MoSCoW | Justificativa |
|-----------|--------|---------------|
| CRUD de Entidades Mestre | Must | Vital para a sincronização com o ERP legado. |
| Resolução de IDs (cod_erp)| Must | Garante que o rebuild mantenha a integridade dos dados originais. |
| Serviço de WhatsApp | Should | Diferencial competitivo, mas não bloqueia o fluxo fiscal. |
| Gestor de Tabelas (API) | Could | Útil para manutenção técnica, mas sensível. |

## Rastreabilidade de Código

| Arquivo | Função / Classe | Cobertura |
|---------|-----------------|-----------|
| `MadRestServer.php` | `MadRestServer` | 🟢 |
| `app/service/api/ClienteRestService.php` | `StoreArray` | 🟢 |
| `app/service/api/PedidoRestService.php` | `StoreArray` | 🟢 |
| `app/service/api/WhatsAppRestService.php`| `telefone` | 🟢 |
| `app/service/api/NotaSaidaXMLRestService.php`| XML Access | 🟢 |
