# Sistema, Design Técnico

## Interface

### Models e Serviços de Infraestrutura

| Símbolo | Assinatura | Retorno | Observação |
|---------|-----------|---------|------------|
| `Parametro.get` | `($chave, $filial_id)` | `string` | Busca valor com fallback para global. 🟢 |
| `SisFunction.NoAcento`| `($texto)` | `string` | Remove acentuação e converte para UPPER. 🟢 |
| `SisFunction.VendedorId`| `()` | `int` | Resolve vendedor_id a partir do usuário logado. 🟢 |
| `CEPService.get` | `($cep)` | `object` | Consulta CEP externa com cache e hidratação de IDs. 🟢 |
| `CNPJService.get` | `($cnpj)` | `object` | Consulta CNPJ externa e hidratação. 🟢 |
| `SystemSqlLog.onAfterQuery`| `($sql, $params)` | `void` | Gancho global para persistir auditoria. 🟢 |

## Fluxo Principal (Auditoria de SQL)

1. **Gatilho:** O framework (via driver PDO) executa qualquer comando SQL.
2. **Intercepção:** Um listener global captura o comando bruto.
3. **Coleta de Metadados:**
    - `debug_backtrace()`: Analisa a pilha de chamadas PHP para extrair o `log_trace`. 🟢
    - `$_SERVER['REMOTE_ADDR']`: Captura o IP de origem. 🟢
    - `TSession`: Resolve o login do usuário. 🟢
4. **Persistência Assíncrona (Simulada):**
    - Abre transação no banco `log`.
    - Insere em `system_sql_log`.
    - Fecha transação sem interferir no fluxo principal. 🟢

## Fluxo de Integração de Endereço (CEP)

1. **Invocação:** Chamada a `CEPService::get($cep)`.
2. **Cache:** Verifica em `CEPCacheService` se o CEP já foi consultado recentemente. 🟢
3. **Consulta Externa:** Se não houver cache, chama `BuilderCEPService::get()` (BrasilAPI/ViaCEP). 🟢
4. **Normalização:** Aplica `SisFunction::NoAcento` em Logradouro e Cidade. 🟢
5. **Hidratação de IDs:** 
    - Busca `Estado` e `Municipio` pelo código IBGE retornado.
    - Se não encontrar, insere novos registros para garantir integridade referencial. 🟢
6. **Retorno:** Objeto hidratado com os IDs internos da base.

## Dependências

- **Adianti Persistence Layer:** O sistema de log depende intimamente do ciclo de vida dos Models. 🟢
- **Database `log`:** Armazenamento isolado para evitar degradação de performance. 🟢
- **ReceitaWS / BrasilAPI:** Provedores externos de dados cadastrais. 🟢
- **MadRestServer:** (Indireto) Alguns serviços de sistema são expostos via REST. 🟢

## Decisões de Design Identificadas

| Decisão | Evidência no código | Confiança |
|---------|---------------------|-----------|
| Centralização de Helpers | `app/service/sistema/SisFunction.php` | 🟢 |
| Auto-população de Tabelas de Domínio | `app/service/sistema/CEPService.php:80` | 🟢 |
| Cache de Consultas Externas | `app/service/sistema/CEPCacheService.php` | 🟢 |
| Separação física de Logs | `app/config/log.php` | 🟢 |

## Estado Interno

O módulo gerencia metadados e caches:
- `application.ini`: Configurações de inicialização (bootstrap).
- `parametro`: Configurações de tempo de execução (runtime).
- `cep_cache`: Tabela temporária para evitar limites de APIs externas.

## Observabilidade

- **Trace de Execução:** Permite o "drill-down" de qualquer query SQL até o código PHP fonte. 🟢
- **API Error Logging:** Erros em integrações externas (CEP/CNPJ) são registrados na tabela `api_error`. 🟢

## Riscos e Lacunas

- 🔴 **Limpeza de Logs:** Não foi encontrada lógica de expurgo automático de logs antigos.
- 🟡 **Limites de APIs Externas:** O sistema depende de APIs gratuitas que podem ter limites de requisições por IP. No rebuild, recomenda-se uso de chaves pagas para ambientes de produção.
