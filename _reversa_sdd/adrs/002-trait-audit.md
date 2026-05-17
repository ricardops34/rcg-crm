# ADR 002: Auditoria Baseada em Traits e Bancos Separados

## Status
Aceito (Retroativo) 🟢

## Contexto
Para fins de conformidade e suporte, o sistema precisava registrar todas as alterações de dados (quem mudou, o que mudou e quando). Inserir essa lógica manualmente em cada Controller causaria duplicação de código e risco de esquecimento.

## Decisão
Utilizar o padrão de **Traits** do PHP combinando com o ciclo de vida do Active Record (`TRecord`). 
- Criada a `SystemChangeLogTrait`.
- Modelos que requerem auditoria (ex: `Cliente`) apenas importam a trait.
- A lógica de log intercepta o método `store()` e `delete()`, gravando os deltas em uma tabela dedicada (`system_change_log`) em um banco de dados separado (`log`).

## Alternativas consideradas
- **Triggers em Banco de Dados:** Difícil manutenção e portabilidade entre diferentes motores (MySQL vs MSSQL).
- **Log manual nos Controllers:** Propenso a erros e difícil padronização.

## Consequências
- **Positivas:** Auditoria centralizada e "mágica"; fácil de ativar em novas entidades; banco de dados de log separado não degrada a performance de escrita do banco principal.
- **Negativas:** Requer que todas as escritas passem pelo Active Record (não captura comandos SQL brutos via DB::query).
