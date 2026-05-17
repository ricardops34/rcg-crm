# Sistema, Tarefas de Implementação

## Pré-requisitos
- [ ] Banco de dados `log` e tabelas de infraestrutura (`parametro`, `municipio`, `estado`) criados.
- [ ] Driver de banco de dados com suporte a listeners/hooks.
- [ ] Configuração de ambiente (ENV) para URLs de APIs externas.

## Tarefas

- [ ] T-01, Implementar Biblioteca de Utilidades de Texto
  - Origem no legado: `app/service/sistema/SisFunction.php:NoAcento`
  - Critério de pronto: Função global para sanitização de strings (removendo acentos, convertendo para caixa alta e tratando caracteres especiais).
  - Confiança: 🟢

- [ ] T-02, Motor de Parâmetros Hierárquicos
  - Origem no legado: `app/model/Parametro.php` e `SisFunction::GetParm`
  - Critério de pronto: Função capaz de resolver uma chave, priorizando o valor da unidade ativa e caindo para o global se ausente.
  - Confiança: 🟢

- [ ] T-03, Integrar Serviços de Endereço e CNPJ
  - Origem no legado: `CEPService.php` e `CNPJService.php`
  - Critério de pronto: Implementar busca em APIs externas com lógica de auto-população de tabelas locais de Município e Estado para garantir IDs válidos.
  - Confiança: 🟢

- [ ] T-04, Criar Listener de Auditoria SQL e Change Log
  - Origem no legado: `app/model/log/SystemSqlLog.php` e `SystemChangeLogTrait.php`
  - Critério de pronto: Interceptar comandos de escrita e gravar metadados técnicos (Trace, IP, Usuário) e deltas de dados no banco `log`.
  - Confiança: 🟢

- [ ] T-05, Implementar Cache de Consultas Externas
  - Origem no legado: `app/service/sistema/CEPCacheService.php`
  - Critério de pronto: Persistir resultados de buscas de CEP em tabela local para reduzir latência e custos de API.
  - Confiança: 🟢

## Tarefas de Teste

- [ ] TT-01, Validar normalização: "João da Conceição" deve virar "JOAO DA CONCEICAO".
- [ ] TT-02, Consultar um CEP nunca visto e validar se Município e Estado foram criados corretamente no banco local.
- [ ] TT-03, Forçar erro em API externa e validar se o log foi gravado em `api_error`.
- [ ] TT-04, Verificar se o log de SQL captura a linha exata do código que disparou a query (Rastreabilidade).

## Tarefas de Migração de Dados (se aplicável)

- [ ] TM-01, Migrar tabelas de parâmetros, estados e municípios brasileiros (IBGE).

## Ordem Sugerida
1. T-01 e T-02: Fundamentais para a lógica de negócio e configuração.
2. T-03 e T-05: Agilizam os cadastros mestre.
3. T-04: Camada de segurança.

## Lacunas Pendentes (🔴)
- Definir se o rebuild utilizará um serviço de logs externo (ex: ELK, Graylog) ou se manterá a persistência em banco relacional local.
