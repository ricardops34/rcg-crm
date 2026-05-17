# API REST, Design Técnico

## Interface

O sistema utiliza um servidor REST customizado (`MadRestServer`) que roteia requisições para classes de serviço em `app/service/api`.

### Padrão de Serviço (`AdiantiRecordService`)

A maioria das APIs herda de uma base comum, expondo métodos padrão de CRUD, mas implementando o método especializado `StoreArray` para integrações em lote.

| Serviço | Métodos Identificados | Domínio |
|---------|-----------------------|---------|
| `ClienteRestService` | `StoreArray`, `load`, `delete` | Clientes e Atributos Fiscais. 🟢 |
| `PedidoRestService` | `StoreArray`, `save` | Cabeçalho e Metadados de Venda. 🟢 |
| `WhatsAppRestService`| `telefone` | Integração Bot / CRM. 🟢 |
| `TituloReceberRestService`| `StoreArray`, `load` | Financeiro / Boletos. 🟢 |

## Fluxo Principal (`StoreArray` - Importação)

Padrão observado em quase todos os serviços de sincronização:

1. **Input:** Recebe um JSON com um nó `conteudo` contendo um array de objetos.
2. **Loop de Processamento:**
    - Abre transação no banco de destino.
    - **Resolução de FKs:** Traduz códigos externos (`cod_erp`) para IDs internos da base rcg. 🟢
    - Exemplo (Cliente): Se receber `vendedor => "V001"`, busca o ID do vendedor que possui `cod_erp == "V001"`.
3. **Persistência:** Instancia o Active Record correspondente, popula via `fromArray` e executa `store()`. 🟢
4. **Log e Retorno:** Acumula o status de cada item (OK ou erro com mensagem) em um array de resposta. 🟢

## Fluxo de Integração WhatsApp (`telefone`)

1. **Input:** Recebe parâmetro `telefone` formatado (ex: `55679...`).
2. **Sanitização:** Extrai o número do celular, trata o prefixo do país (DDI), DDD e adiciona o '9' se necessário. 🟢
3. **Busca de Cliente:** Localiza cliente ativo (`status = 'A'`) com aquele número de celular. 🟢
4. **Cálculo Financeiro Express:**
    - Consulta faturas em aberto (`saldo > 0`).
    - Soma valores `vencidos` e `vencendo`.
    - Recupera a data da última compra. 🟢
5. **Output:** Retorna JSON pronto para o bot responder ao cliente com saudação e situação financeira.

## Infraestrutura do Servidor (`MadRestServer`)

- **Router:** Utiliza `Mad\Rest\Router` para mapear os verbos HTTP.
- **Middleware:** Validação de tokens e permissões antes da execução do serviço. 🟡
- **Output:** Força cabeçalhos de JSON e lida com erros globais retornando HTTP 500 com o stack trace (se configurado). 🟢

## Dependências

- **MadRest Library:** Componente de roteamento e request/response. 🟢
- **Adianti Record Service:** Facade para operações de Active Record via API. 🟢
- **PHPMailer:** Usado em alguns serviços para disparar alertas via API. 🟡

## Observabilidade

- **API Logs:** O sistema retorna um log detalhado de processamento por item no corpo da resposta da API. 🟢

## Riscos e Lacunas

- 🔴 **Rate Limiting:** Não foi encontrada lógica de controle de vazão (throlling) no `MadRestServer`. Integrações massivas podem degradar a performance do banco.
- 🟡 **Versão de API:** Não há versionamento explícito na URL (ex: `/api/v1/`). Mudanças estruturais quebram integrações legadas imediatamente.
