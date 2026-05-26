---
schemaVersion: 1
generatedAt: 2026-05-25T21:20:00-04:00
kind: ui_meta_builder_proposal
producedBy: codex
status: proposed
---

# Proposta: Construtor de Telas Metadata-Driven com XML

Este documento formaliza a ideia de uma tela de manutencao visual "arrasta e solta" para gerar definicoes de telas consumidas pelo menu do Reversa.

## 1. Decisao objetiva

Sim, e viavel criar um construtor visual baseado em PO-UI para montar:

- telas de listagem;
- telas de cadastro simples;
- filtros;
- acoes de toolbar;
- colunas e campos.

O resultado pode ser exportado em XML para manter compatibilidade com um modelo declarativo de menu. Porem, o XML deve ser tratado como **artefato de definicao**, nao como UI executavel por si so.

## 2. Limite tecnico importante

Angular + PO-UI nao interpretam XML nativamente como uma tela completa no mesmo estilo do legado Adianti.

Portanto, a arquitetura correta e:

1. o usuario monta a tela no builder;
2. o builder salva uma definicao estruturada da tela;
3. a definicao e exportada para XML;
4. o menu le o XML;
5. um **renderizador Angular metadata-driven** converte essa definicao em componentes PO-UI.

Sem esse renderizador intermediario, o XML sozinho nao entrega a funcionalidade.

## 3. Onde essa abordagem encaixa melhor

Esta proposta serve bem para telas com estrategia **Preservada** ja mapeadas na migracao:

- `po-page-list` + `po-table`;
- `po-page-edit` + `po-dynamic-form`;
- combos, filtros, campos de texto, datas, selects e labels;
- CRUDs administrativos e cadastros simples.

Nao e boa candidata para telas **Modernizadas** ou com comportamento rico:

- dashboards com `po-chart`;
- fluxos com `po-stepper`;
- grids mestre-detalhe complexos;
- telas com logica procedural pesada;
- componentes muito customizados.

## 4. Escopo recomendado do MVP

O MVP deve suportar apenas dois tipos de tela:

1. `list`
2. `form`

Cada tela deve permitir:

- titulo;
- rota;
- recurso backend;
- permissao;
- filtros;
- colunas;
- campos;
- acoes padrao (`new`, `edit`, `delete`, `refresh`, `export`);
- layout responsivo simples com `po-row` e `po-field`.

## 5. Arquitetura proposta

## 5.1 Componentes

### A. Screen Builder UI

Tela Angular para manutencao visual:

- painel esquerdo com componentes disponiveis;
- canvas central com drop zones;
- painel direito com propriedades do item selecionado;
- preview;
- validacao;
- botao de exportar XML.

### B. Metadata Model

Modelo canonico em objeto estruturado, preferencialmente JSON/TypeScript:

- mais facil de validar;
- mais facil de versionar;
- mais simples para testes;
- mais aderente ao runtime Angular.

### C. XML Serializer

Camada que converte o modelo canonico para XML.

### D. Menu Registry

Cadastro que liga:

- `route`;
- `screenId`;
- `screenType`;
- `resource`;
- `xmlDefinition`.

### E. Runtime Renderer

Motor Angular que interpreta a definicao e instancia wrappers PO-UI.

## 5.2 Fluxo

1. Usuario abre "Manutencao de Telas".
2. Monta listagem ou formulario.
3. Sistema valida compatibilidade do layout.
4. Definicao e salva no repositorio de metadados.
5. XML e gerado.
6. Menu passa a apontar para o `screenId`.
7. Renderer carrega a definicao e monta a tela em runtime.

## 6. Modelo conceitual minimo

```xml
<screen id="cadastro-clientes" type="list" version="1">
  <meta>
    <title>Clientes</title>
    <route>/cadastros/clientes</route>
    <resource>/clientes</resource>
    <permission>cadastros.clientes.read</permission>
  </meta>

  <toolbar>
    <action id="new" label="Novo" />
    <action id="refresh" label="Atualizar" />
  </toolbar>

  <filters>
    <field name="nome" type="string" label="Nome" />
    <field name="status" type="select" label="Status">
      <option value="A" label="Ativo" />
      <option value="B" label="Bloqueado" />
    </field>
  </filters>

  <table>
    <column property="id" label="Codigo" width="100" />
    <column property="nome" label="Nome" />
    <column property="status" label="Status" type="label" />
  </table>
</screen>
```

Formulario simples:

```xml
<screen id="cadastro-vendedor" type="form" version="1">
  <meta>
    <title>Vendedor</title>
    <route>/gerencia/vendedores/editar</route>
    <resource>/commercial/vendedores</resource>
    <permission>gerencia.vendedores.write</permission>
  </meta>

  <form>
    <field name="nome" type="string" label="Nome" required="true" span="6" />
    <field name="email" type="email" label="E-mail" span="6" />
    <field name="status" type="select" label="Status" required="true" span="3">
      <option value="A" label="Ativo" />
      <option value="I" label="Inativo" />
    </field>
  </form>
</screen>
```

## 7. Modelo canonico recomendado

Apesar da exigencia de XML, o formato interno recomendado e JSON.

Exemplo:

```json
{
  "id": "cadastro-clientes",
  "type": "list",
  "meta": {
    "title": "Clientes",
    "route": "/cadastros/clientes",
    "resource": "/clientes",
    "permission": "cadastros.clientes.read"
  },
  "toolbar": [
    { "id": "new", "label": "Novo" },
    { "id": "refresh", "label": "Atualizar" }
  ],
  "filters": [
    { "name": "nome", "type": "string", "label": "Nome" },
    { "name": "status", "type": "select", "label": "Status", "options": [
      { "value": "A", "label": "Ativo" },
      { "value": "B", "label": "Bloqueado" }
    ] }
  ],
  "table": {
    "columns": [
      { "property": "id", "label": "Codigo", "width": "100" },
      { "property": "nome", "label": "Nome" },
      { "property": "status", "label": "Status", "type": "label" }
    ]
  }
}
```

Motivo: Angular trabalha melhor com objetos. O XML deve ser uma projecao serializada.

## 8. Mapeamento para PO-UI

| Conceito do XML | Componente alvo |
| :--- | :--- |
| `screen[type=list]` | `po-page-list` |
| `screen[type=form]` | `po-page-edit` |
| `filters.field` | `po-dynamic-form` ou filtros no header |
| `table.column` | `po-table-column[]` |
| `toolbar.action` | `PoPageAction[]` |
| `form.field` | `PoDynamicFormField[]` |

## 9. Regras de guardrail

Para evitar virar um "low-code" fragil, o builder deve impor limites:

- permitir apenas componentes homologados;
- bloquear JavaScript livre dentro do XML;
- tratar validacoes por tipos conhecidos;
- limitar acoes a comandos suportados pelo runtime;
- versionar a definicao de tela;
- registrar autor, data e historico;
- separar layout de regra de negocio.

Regra principal: o builder monta interface e contrato declarativo, mas regra de negocio continua no backend.

## 10. Riscos

### Risco 1: XML excessivamente generico

Se o XML tentar representar qualquer comportamento arbitrario, o renderer vira um framework paralelo dificil de manter.

Mitigacao:

- manter DSL pequena;
- suportar apenas `list` e `form` no inicio;
- promover excecoes para telas codificadas manualmente.

### Risco 2: Paridade ilusoria

Gerar a tela visualmente nao garante que validacoes, permissoes e side effects do legado estejam corretos.

Mitigacao:

- atrelar cada tela a um endpoint real;
- exigir mapeamento de permissao;
- validar paridade com testes de fluxo.

### Risco 3: Acoplamento com PO-UI

Se a definicao usar conceitos muito especificos do PO-UI, trocar a biblioteca depois fica caro.

Mitigacao:

- DSL deve falar de `field`, `column`, `action`, `layout`;
- o adaptador runtime faz a traducao para PO-UI.

## 11. Recomendacao tecnica

Recomendacao pragmatica:

1. usar **JSON como fonte da verdade**;
2. gerar XML apenas para compatibilidade e inspecao humana;
3. criar um renderer Angular limitado a `list` e `form`;
4. aplicar isso primeiro em cadastros simples;
5. manter dashboards e telas complexas fora do builder.

## 12. POC sugerida

Prova de conceito minima:

1. criar tela "Manutencao de Telas";
2. permitir montar uma `list screen`;
3. salvar definicao em tabela `ui_screen_definition`;
4. exportar XML;
5. menu abrir a rota generica `/runtime-screen/:screenId`;
6. renderer montar `po-page-list` + `po-table`;
7. consumir um endpoint CRUD simples, por exemplo vendedores.

## 13. Criterio de pronto da POC

- criar uma listagem sem codigo manual de componente;
- abrir via menu;
- carregar dados de endpoint real;
- respeitar permissao;
- exportar XML valido;
- reabrir a mesma definicao para edicao.

## 14. Conclusao

A ideia e boa para acelerar migracao de CRUDs preservados e reduzir repeticao de codigo. Ela nao substitui o desenvolvimento Angular tradicional, mas pode virar um acelerador interno de telas padrao.

Se a intencao for manter disciplina arquitetural, o caminho correto e **builder visual + metadata canonico + renderer Angular + exportacao XML**, e nao XML executado diretamente.
