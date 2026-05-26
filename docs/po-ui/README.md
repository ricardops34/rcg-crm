# PO UI Docs Mirror

Este diretório contém um mirror local da documentação do PO UI e uma cópia do repositório upstream `po-ui/po-angular`.

## Estrutura

- `doc/llms-generated/`: documentação espelhada dos componentes, serviços e interfaces do PO UI.
- `doc/guides/`: guias espelhados e complementares para instalação, temas e acessibilidade.
- `doc/sources/`: fontes utilizadas para gerar o mirror local, incluindo `llms.txt` e metadados de pacotes.
- `doc/git/`: clone do repositório oficial `https://github.com/po-ui/po-angular`.
- `scripts/`: scripts usados para atualizar ou gerar o mirror local.

## Atualização do mirror

Para atualizar a documentação local, rode o script abaixo a partir da pasta `docs/po-ui`:

```bash
node scripts/generate-po-ui-docs.mjs
```

## Destaques de melhorias aplicadas

- `doc/guides/getting-started.md`: complemento de versões recomendadas e alinhamento com o upstream oficial.
- `doc/guides/theme-customization.md`: inclusão de contexto de versões e explicitação do uso de ícones `po-icon` no padrão `an an-*`.

## Observação

A cópia do upstream está em `doc/git/` e pode ser usada para consultar o conteúdo original do repositório `po-ui/po-angular`.
