---
name: gemini-antigravity-guidelines
description: Diretrizes comportamentais e práticas para geração e revisão de código usando modelos Gemini (antigravity) e similares (Codex-style).
license: MIT
---

# Gemini / Antigravity Guidelines

Estas diretrizes adaptam princípios de revisão e geração de código (inspirados em observações do Andrej Karpathy) para uso com modelos generativos modernos como Gemini (antigravity) e modelos estilo Codex.

Objetivo: reduzir erros comuns de LLMs ao produzir código, manter mudanças cirúrgicas no código existente, e transformar solicitações em metas verificáveis.

Princípios gerais

1. Pense antes de codificar

 - Declare suposições explicitamente. Se algo estiver incerto, peça clarificações.
 - Liste alternativas quando houver múltiplas interpretações; não escolha silenciosamente.
 - Se existe uma solução mais simples, proponha-a e explique por que prefere a alternativa solicitada.

2. Simplicidade em primeiro lugar

 - Produza o mínimo de código necessário para cumprir o pedido — nada especulativo.
 - Evite abstrações para código de uso único. Não adicione opções de configuração que o usuário não pediu.
 - Evite tratamento de erro para cenários impossíveis sem evidência de que são relevantes.

3. Mudanças cirúrgicas

 - Ao editar base de código existente, toque apenas o necessário. Não refatore código adjacente não relacionado.
 - Combine estilo com o código existente (mesma formatação, mesma convenção).
 - Se suas mudanças deixarem importações ou símbolos órfãos, remova-os; não remova deadcode preexistente sem permissão.

4. Execução orientada a objetivos

 - Converta tarefas em critérios verificáveis (ex.: "Adicionar validação" → "adicionar testes que falham antes e passam depois").
 - Para tarefas com múltiplas etapas, apresente um plano curto com checkpoints verificáveis.

Modelo-específico: notas para Gemini / Antigravity / Codex

 - Raciocínio passo-a-passo: esses modelos produzem respostas longas; quando apropriado, peça um sumário curto antes do código e, em seguida, uma explicação passo-a-passo separada das decisões.
 - Evitar hallucination: solicite que o modelo marque claramente quando está fazendo suposições (use "ASSUMPTION:").
 - Segurança de ambiente: nunca presuma acesso a segredos, credenciais, ou serviços; peça instruções para como testar localmente.
 - Preferências de saída: peça apenas o artefato necessário (por exemplo, apenas o diff/patch) e instruções curtas de execução. Exija um bloco de código com o contexto mínimo para reproduzir.
 - Testes e verificações: sempre que mudar comportamento crítico, solicite testes automatizados (unit/integration) que demonstrem a correção.

Formato recomendado para solicitações ao modelo

 - 1) Objetivo breve (uma frase).
 - 2) Entradas/saídas esperadas (ex.: função X recebe Y retorna Z).
 - 3) Restrições (linguagem, estilo, dependências permitidas).
 - 4) Critério de sucesso verificável (testes ou comportamentos observáveis).
 - 5) Exemplo(s) mínimo(s) de uso ou casos de teste.

Ao pedir um patch

 - Solicite `diff`/`patch` no formato `git` (unified diff) ou somente o arquivo inteiro quando apropriado.
 - Peça para o modelo listar exatamente quais arquivos serão alterados e por quê.

Checklist de entrega mínima

 - Assunções listadas.
 - Plano curto com passos verificáveis (quando tarefa multi-step).
 - Código mínimo necessário (sem extras).
 - Tests que falham antes e passam depois (quando aplicável).
 - Instruções curtas de execução/validação local.

Exemplo de prompt curto (modelo):

```
Objetivo: Corrigir bug X na função `calculaTotal`.
Entradas/saídas: recebe array de itens retorna number.
Restrições: TypeScript, sem novas dependências.
Critério: adicionar unit test que falha antes e passa depois.
Resposta solicitada: 1) resumo de 1 linha; 2) lista de arquivos alterados; 3) diff patch.
```

Boas práticas ao revisar saídas do modelo

 - Verifique suposições e peça justificação quando não estiver explícito.
 - Executar os testes entregues antes de aceitar a alteração.
 - Inspecione mudanças por efeitos colaterais (importes, contratos públicos, performance).

Adaptação para fluxos locais de desenvolvimento

 - Se o projeto usa um style linter/formatter, mencione o comando para aplicar (`npm run format`, `prettier --write`, `./gradlew format`, etc.).
 - Peça ao modelo para não alterar scripts de build ou CI sem justificativa clara.

Referências e tradeoffs

 - Essas regras priorizam segurança e previsibilidade em troca de velocidade criativa do modelo.
 - Para tarefas triviais (ex.: snippet isolado), relaxe algumas regras (teste mínimo, menos explicações).

---
Esta SKILL foi adaptada para orientar interações com modelos Gemini/Antigravity e variantes Codex-style. Use-a ao pedir código, patches ou revisões automáticas; ajuste o grau de rigor conforme a criticidade da alteração.
