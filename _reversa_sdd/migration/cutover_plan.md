# Cutover Plan — RCG Reversa

Este plano detalha os passos para a transição (virada de chave) entre o sistema legado RCG e a nova stack Reversa, módulo por módulo.

## 1. Janela de Manutenção Padrão
- **Dia**: Terças ou Quintas-feiras (evitar segundas e sextas).
- **Horário**: 20:00 às 22:00 (Baixo tráfego).
- **Duração Estimada**: 30 minutos por módulo.

## 2. Checklist Pré-Cutover (Geral)
1. [ ] Backup completo do PostgreSQL (`pg_dump`).
2. [ ] Imagem Docker do NestJS/Angular atualizada no Portainer.
3. [ ] Redis ativo e monitorado.
4. [ ] Scripts de rollback testados em ambiente de homologação.

## 3. Fluxo de Cutover por Fase

### Fase 1: Módulo Admin (Identity)
- **Ação**: O NestJS assume `/auth` e `/api/v2/auth`.
- **Procedimento**:
    1. Atualizar labels do Traefik no `docker-compose.yml`.
    2. Reiniciar serviço Traefik.
    3. Testar login em `/v2`.
    4. Validar se o sistema legado ainda aceita o redirecionamento (Identity Bridge).

### Fase 2: Módulos Vendedor/Gerência
- **Ação**: Usuários da força de vendas são direcionados para `/v2/vendedor`.
- **Procedimento**:
    1. Ativar rotas `/api/v2/vendedor` no NestJS.
    2. Alterar link de acesso no menu do legado (se coexistirem) ou redirecionar via Traefik.
    3. Monitorar logs de erro (Sentry/Logback).

### Fase 3: Módulo Cobrança (Crítico)
- **Ação**: Virada total da lógica financeira.
- **Procedimento**:
    1. Bloquear acesso ao módulo de cobrança no legado (Modo Read-Only).
    2. Executar script de validação de paridade final.
    3. Apontar `/api/v2/cobranca` no Traefik.
    4. Realizar um lançamento de teste e validar no banco.

## 4. Plano de Comunicação
1. **T-24h**: Aviso no dashboard do legado sobre a modernização do módulo X.
2. **T-1h**: Bloqueio temporário (se necessário).
3. **T+10min**: Email/Notificação de "Módulo X Modernizado".

## 5. Critérios de Sucesso
- Erros 5xx < 0.1% nas primeiras 2 horas.
- Zero reclamações de perda de dados.
- Tempo de resposta (p95) < 300ms para Dashboards.

## 6. Procedimento de Rollback
1. Identificar falha crítica (ex: Erro de cálculo financeiro).
2. Reverter labels do Traefik para a versão anterior do `docker-compose`.
3. Comando: `docker-compose up -d --force-recreate traefik`.
4. Validar retorno do legado.
