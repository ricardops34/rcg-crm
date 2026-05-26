# Configurador - Builder visual de telas

## Objetivo

O modulo Configurador concentra a criacao e manutencao visual de telas dinamicas do sistema. A primeira versao deve permitir montar telas simples de cadastro, consulta e listagem usando componentes e metadados compativeis com PO UI.

## Decisao arquitetural

- O builder visual e uma experiencia Angular.
- O drag and drop usa `@angular/cdk/drag-drop`.
- O PO UI e usado como runtime de formulario, tabela, detalhe e componentes finais.
- O schema interno TypeScript e a fonte da verdade durante a edicao.
- O XML e o formato de persistencia, integracao e publicacao.
- O XML nunca deve conter JavaScript, callbacks ou expressoes executaveis.

## Camadas

1. Builder

Responsavel por paleta, canvas, ordenacao, selecao de campos, painel de propriedades e preview.

2. Schema interno

Representa a tela em objetos tipados. Esse modelo deve ser validado antes de salvar ou publicar.

3. Serializacao XML

Converte o schema interno para XML e, em etapa posterior, le XML salvo para reconstruir o schema.

4. Runtime renderer

Interpreta uma definicao publicada e escolhe o renderer adequado:

- `edit`: `po-dynamic-form` ou `po-page-dynamic-edit`
- `table`: `po-page-dynamic-table`
- `detail`: `po-page-dynamic-detail`
- `custom`: renderer proprio com registry de blocos permitidos

5. Menu dinamico

O menu continua apontando para controllers. Telas dinamicas devem usar um controller registrado e resolver o `screenId` por metadados do programa ou por uma tabela de definicoes.

## MVP

O MVP inicial dentro de `/admin/configurador` deve entregar:

- Paleta com campos basicos.
- Canvas com drag and drop para adicionar e reordenar campos.
- Painel de propriedades para label, propriedade, tipo, obrigatoriedade, mascara e colunas responsivas.
- Preview visual em formulario.
- XML gerado em memoria.

Fora do MVP inicial:

- Persistencia no backend.
- Parser XML completo.
- Validacao estrutural com `zod` ou `ajv`.
- Runtime dinamico em rota generica.
- Controle de versao e publicacao.

## Estrutura XML inicial

```xml
<screen id="novo-cadastro" version="1" type="edit">
  <meta title="Nova tela" module="Configurador" />
  <layout kind="form">
    <section id="main" title="Principal">
      <field property="nome" type="string" label="Nome" required="true" gridMd="6" />
    </section>
  </layout>
</screen>
```

## Regras de seguranca

- Permitir apenas tipos de campo registrados.
- Permitir apenas actions registradas.
- Permitir apenas data sources autorizados.
- Validar XML/schema no backend antes de publicar.
- Escapar valores textuais ao serializar XML.
- Separar tela em rascunho e tela publicada.

## Proximas etapas

1. Evoluir o MVP para salvar o schema em memoria local ou backend.
2. Criar parser XML para carregar definicoes existentes.
3. Criar renderer dinamico inicial para `edit`.
4. Associar definicoes publicadas ao menu.
5. Implementar versionamento e permissao por tela.
