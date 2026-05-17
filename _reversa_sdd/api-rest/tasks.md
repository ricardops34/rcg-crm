# API REST, Tarefas de Implementação

## Pré-requisitos
- [ ] Framework de API (FastAPI, Express, NestJS, etc.) configurado no rebuild.
- [ ] Middleware de autenticação compatível com o legado (Bearer/JWT).
- [ ] Banco de dados `erp_online` e `permission` funcionais.

## Tarefas

- [ ] T-01, Implementar Servidor REST e Roteamento
  - Origem no legado: `MadRestServer.php` e `RouteServiceProvider`
  - Critério de pronto: Capaz de receber requisições, validar tokens e despachar para os serviços correspondentes.
  - Confiança: 🟢

- [ ] T-02, Criar Motor de Resolução de IDs (StoreArray Pattern)
  - Origem no legado: `app/service/api/ClienteRestService.php`
  - Critério de pronto: Implementar utilitário que busca IDs internos baseando-se em `cod_erp` de tabelas estrangeiras durante o salvamento.
  - Confiança: 🟢

- [ ] T-03, Implementar API de Clientes e Sincronização
  - Origem no legado: `ClienteRestService.php`
  - Critério de pronto: Endpoint capaz de inserir/atualizar clientes em lote retornando log de status individual.
  - Confiança: 🟢

- [ ] T-04, Desenvolver Serviço de Integração WhatsApp
  - Origem no legado: `app/service/api/WhatsAppRestService.php`
  - Critério de pronto: Endpoint que recebe telefone e retorna resumo financeiro consolidado do cliente vinculado.
  - Confiança: 🟢

- [ ] T-05, Migrar Contratos de Pedidos e Financeiro
  - Origem no legado: `PedidoRestService.php` e `TituloReceberRestService.php`
  - Critério de pronto: Garantir a recepção de vendas e parcelas financeiras mantendo a integridade do ERP.
  - Confiança: 🟢

## Tarefas de Teste

- [ ] TT-01, Simular importação de 100 clientes via API e validar se todos os `vendedor_id` foram resolvidos corretamente via `cod_erp`.
- [ ] TT-02, Testar resposta da API de WhatsApp com um número inexistente na base.
- [ ] TT-03, Validar se a flag `sinc = 'S'` é gravada em todos os registros processados via API.
- [ ] TT-04, Testar falha de autenticação (Token inválido) e garantir retorno HTTP 401/403.

## Ordem Sugerida
1. T-01: Infraestrutura de comunicação.
2. T-02: Lógica central de tradução de dados legados.
3. T-03 e T-05: Sincronização vital do ERP.
4. T-04: Funcionalidades agregadas.

## Lacunas Pendentes (🔴)
- Definir se o rebuild utilizará **Swagger/OpenAPI** nativo para documentação de endpoints para facilitar a integração de novos parceiros.
