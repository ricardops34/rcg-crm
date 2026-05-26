# Documentacao do Banco: Tabelas

Fonte analisada em 2026-05-26:
- `database/erp_online-pgsql.sql`

## Visao Geral

O schema do ERP/CRM cobre principalmente estes dominios:

- Comercial: `cliente`, `vendedor`, `meta_vendedor_mes`, `tabela_preco`, `orcamento`, `pedido`
- CRM e agenda: `atendimento`, `atendimento_tipo`, `vendedor_atendimento`
- Financeiro: `titulo_receber`, `negociacao`, `negociacao_titulo_receber`
- Faturamento e compras: `nota_saida`, `nota_saida_item`, `nota_entrada`, `nota_entrada_item`
- Catalogo e estoque: `produto`, `categoria`, `sub_categoria`, `estoque`, `armazem`
- Cadastros auxiliares: `municipio`, `estado`, `pais`, `condicao_pagamento`, `tipo_cliente`, `regiao_cliente`
- Conteudo/blog: `blog_aniversarios`, `blog_comunicados`, `blog_noticias`

## Tabelas Principais

### `cliente`

- Papel: cadastro central de clientes, carteira e situacao comercial.
- Colunas relevantes:
  - identidade: `id`, `cod_erp`, `razao`, `fantasia`, `cnpj_cpf`
  - localizacao: `municipio_id`, `uf`, `cep`, `latitude`, `longitude`
  - relacionamento comercial: `vendedor_id`, `condicao_pagamento_id`, `tabela_preco_id`, `regiao_cliente_id`
  - ciclo comercial: `primeira_compra`, `ultima_compra`, `ultima_visita`, `ultimo_atendimento`
  - controle: `status`, `reg_ativo`, `motivo_bloqueio_id`, `situacao_cadastral_id`
- Tamanho estrutural: 64 colunas.

### `vendedor`

- Papel: cadastro de vendedores e vinculacao com usuario do sistema.
- Colunas relevantes:
  - identidade: `id`, `cod_erp`, `nome`, `nome_reduzido`
  - integracao: `system_users_id`, `system_unit_id`
  - operacao: `status`, `dashboard`, `supervisor`, `supervisor_id`, `desligado`
- Tamanho estrutural: 20 colunas.

### `atendimento`

- Papel: registros de CRM e agendamentos comerciais.
- Colunas relevantes:
  - relacoes: `atendimento_tipo_id`, `vendedor_id`, `cliente_id`, `orcamento_id`, `nota_saida_id`
  - agenda: `horario_inicial`, `horario_final`, `retorno`
  - conteudo: `titulo`, `cor`, `observacao`
  - auditoria: `dt_inclusao`, `dt_alteracao`, `dt_delete`, `user_id_create`, `user_id_update`, `user_id_delete`
- Tamanho estrutural: 23 colunas.

### `titulo_receber`

- Papel: contas a receber e base de inadimplencia.
- Colunas relevantes:
  - relacoes: `cliente_id`, `vendedor_id`, `natureza_id`, `pedido_id`, `nota_fiscal_id`
  - financeiro: `saldo`, `valor`, `decrescimo`, `acrescimo`, `valor_juros`, `mora_dia`, `taxa_multa`
  - datas: `emissao`, `vencimento`, `venc_real`, `baixa`
  - controle: `reg_ativo`, `situacao`
- Tamanho estrutural: 47 colunas.

### `nota_saida` e `nota_saida_item`

- Papel: faturamento, base de vendas e analytics.
- Colunas relevantes em `nota_saida`:
  - relacoes: `cliente_id`, `vendedor1_id`, `vendedor2_id`, `filial_id`
  - negocio: `nota_fiscal`, `dt_emissao`, `dt_nfe`, `tipo`, `comodato`, `reg_ativo`
  - agregacoes: `vlr_bruto`, `vlr_mercadoria`, `vlr_devolucao`, `vlr_comodato`, `mes`, `ano`
- Colunas relevantes em `nota_saida_item`:
  - relacoes: `nota_saida_id`, `produto_id`, `cliente_id`, `vendedor1_id`, `vendedor2_id`
  - metricas: `qtd`, `vlr_unitario`, `vlr_bruto`, `vlr_total`, `vlr_dev`, `comissao`
  - particionamento logico: `ano`, `mes`, `dt_emissao`

### `meta_vendedor_mes` e `meta_vendedor_categoria`

- Papel: metas mensais por vendedor e por categoria.
- Chaves de negocio:
  - `meta_vendedor_mes`: `vendedor_id`, `ano`, `mes`, `dt_delete`
  - `meta_vendedor_categoria`: `meta_vendedor_mes_id`, `categoria_id`, `cod_erp`, `dt_delete`

### `produto`, `categoria`, `sub_categoria`, `estoque`, `tabela_preco_item`

- Papel: catalogo, estoque e precificacao.
- Uso principal no sistema:
  - listagem e edicao de produtos
  - views de preco e estoque
  - cruzamento com vendas e orcamentos

## Inventario Completo das Tabelas

Formato:
- `nome`: quantidade de colunas
- `colunas`: lista na ordem do DDL

### Cadastros base

- `armazem`: 7
  - `id`, `cod_erp`, `descricao`, `status`, `dt_inclusao`, `dt_alteracao`, `system_unit_id`
- `caracteristica`: 5
  - `id`, `descricao`, `tipo`, `dt_alteracao`, `dt_inclusao`
- `categoria`: 9
  - `id`, `filial_id`, `cod_erp`, `descricao`, `usado`, `site`, `status`, `dt_alteracao`, `dt_inclusao`
- `sub_categoria`: 7
  - `id`, `categoria_id`, `cod_erp`, `descricao`, `status`, `dt_inclusao`, `dt_alteracao`
- `fabricante`: 5
  - `id`, `cod_erp`, `descricao`, `dt_inclusao`, `dt_alteracao`
- `tipo_cliente`: 4
  - `id`, `cod_erp`, `descricao`, `status`
- `tipo_contato`: 5
  - `id`, `cod_erp`, `descricao`, `dt_alteracao`, `dt_inclusao`
- `tipo_entrada_saida`: 12
  - `id`, `filial_id`, `empresa_id`, `cod_erp`, `tipo`, `descricao`, `finalidade`, `status`, `cfop`, `dt_alteracao`, `dt_inclusao`, `system_unit_id`
- `condicao_pagamento`: 9
  - `id`, `filial_id`, `cod_erp`, `descricao`, `forma`, `status`, `dt_inclusao`, `dt_alteracao`, `system_unit_id`
- `parametro`: 5
  - `id`, `filial_id`, `parametro`, `conteudo`, `tipo`
- `empresa`: 8
  - `id`, `cod_erp`, `system_unit_id`, `nome`, `logo`, `status`, `dt_inclusao`, `dt_alteracao`
- `filial`: 21
  - `id`, `cod_emp`, `cod_erp`, `system_unit_id`, `apelido`, `matriz`, `nome`, `fantasia`, `tipo`, `cnpj`, `cpf`, `cep`, `endereco`, `complemento`, `bairro`, `municipio`, `uf`, `email`, `status`, `dt_alteracao`, `dt_inclusao`
- `transportadora`: 8
  - `id`, `filial_id`, `cod_erp`, `descricao`, `status`, `dt_inclusao`, `dt_alteracao`, `system_unit_id`
- `natureza`: 5
  - `id`, `cod_erp`, `descricao`, `dt_alteracao`, `dt_inclusao`
- `motivo_bloqueio`: 5
  - `id`, `cod_erp`, `dt_alteracao`, `dt_inclusao`, `descricao`
- `segmento`: 7
  - `id`, `cod_erp`, `descricao`, `status`, `reg_ativo`, `dt_inclusao`, `dt_alteracao`
- `regiao_cliente`: 6
  - `id`, `cod_erp`, `descricao`, `dt_alteracao`, `dt_inclusao`, `cor`
- `situacao_cadastral`: 5
  - `id`, `descricao`, `motivo_bloqueio_id`, `dt_alteracao`, `dt_inclusao`
- `step`: 7
  - `id`, `grupo`, `sequencia`, `variavel`, `descricao`, `cor`, `column_6`

### Geografia e classificacao

- `pais`: 7
  - `id`, `cod_erp`, `nome`, `dt_alteracao`, `dt_inclusao`, `sigla`, `comex_id`
- `estado`: 5
  - `id`, `cod_erp`, `sigla`, `descricao`, `codigo_ibge`
- `municipio`: 5
  - `id`, `cod_erp`, `descricao`, `estado_id`, `codigo_ibge`
- `cep`: 9
  - `id`, `cep`, `estado_id`, `cidade_id`, `bairro`, `endereco`, `longitude`, `latitude`, `service`
- `cnae`: 10
  - `id`, `cod_erp`, `secao`, `divisao`, `grupo`, `classe`, `subclasse`, `descricao`, `dt_inclusao`, `dt_alteracao`

### Comercial e CRM

- `cliente`: 64
  - `id`, `filial_id`, `cod_erp`, `status`, `tipo`, `razao`, `tipo_cliente_id`, `fantasia`, `endereco`, `complemento`, `bairro`, `uf`, `municipio`, `municipio_id`, `cep`, `telefone1`, `telefone2`, `fax`, `celular`, `celular2`, `contato`, `cnpj_cpf`, `ie`, `im`, `contribuinte`, `rg`, `nascimento`, `email`, `vendedor_id`, `condicao_pagamento_id`, `tabela_preco_id`, `primeira_compra`, `ultima_compra`, `data_cadastro`, `dt_alteracao`, `sinc`, `dt_inclusao`, `destaca_ie`, `seguimento_id`, `site`, `obs`, `filial_cadastro`, `cliente`, `latitude`, `log_int`, `longitude`, `limite`, `vencimento_limite`, `risco`, `system_unit_id`, `carteira`, `obs_bloqueio`, `dt_bloqueio`, `motivo_bloqueio`, `motivo_bloqueio_id`, `dt_reativacao`, `obs_reativacao`, `ultima_visita`, `ultimo_atendimento`, `regiao_cliente_id`, `reg_ativo`, `dt_revisao`, `situacao_cadastral_id`, `data_rfb`
- `cliente_acesso`: 9
  - `id`, `cliente_id`, `user_id`, `dt_alteracao`, `dt_inclusao`, `dt_delete`, `login`, `senha`, `status`
- `cliente_atendimento`: 10
  - `id`, `cliente_id`, `vendedor_id`, `horario_inicial`, `horario_final`, `titulo`, `cor`, `observacao`, `dt_alteracao`, `dt_inclusao`
- `cliente_atualizacao`: 39
  - `id`, `cliente_id`, `situacao_cadastral_id`, `atividade_principal_id`, `razao`, `fantasia`, `tipo_logradouro`, `logradouro`, `numero`, `complemento`, `bairro`, `municipio_id`, `cep`, `telefone1`, `telefone2`, `fax`, `celular`, `celular2`, `contato`, `cnpj_cpf`, `ie`, `im`, `email`, `site`, `latitude`, `longitude`, `dt_alteracao`, `dt_inclusao`, `data_situacao_especial`, `situacao_especial`, `atualizado_em`, `data_situacao_cadastral`, `simples`, `tipo`, `pais_id`, `mei`, `porte`, `natureza_juridica`, `capital_social`
- `cliente_cnae`: 5
  - `id`, `cliente_id`, `cnae_id`, `dt_alteracao`, `dt_inclusao`
- `cliente_condicao`: 6
  - `id`, `condicao_pagamento_id`, `cliente_id`, `padrao`, `dt_inclusao`, `dt_alteracao`
- `cliente_contato`: 9
  - `id`, `cliente_id`, `tipo_contato_id`, `nome`, `telefone`, `email`, `situacao`, `dt_inclusao`, `dt_alteracao`
- `cliente_historico`: 8
  - `id`, `cliente_id`, `vendedor_id`, `motivo_id`, `observacao`, `dt_alteracao`, `dt_inclusao`, `data_movimento`
- `cliente_socios`: 11
  - `id`, `cliente_id`, `nome`, `tipo`, `data_entrada`, `faixa_etaria`, `qualificacao_socio`, `descricao`, `cpf_cnpj_socio`, `dt_alteracao`, `dt_inclusao`
- `vendedor`: 20
  - `id`, `filial_id`, `cod_erp`, `system_users_id`, `nome`, `nome_reduzido`, `ddd`, `telefone`, `celular`, `email`, `status`, `vendedor`, `dashboard`, `dt_alteracao`, `dt_inclusao`, `dt_nascmento`, `system_unit_id`, `supervisor`, `supervisor_id`, `desligado`
- `supervisor`: 18
  - `filial_id`, `id`, `cod_erp`, `system_users_id`, `nome`, `nome_reduzido`, `ddd`, `telefone`, `celular`, `email`, `status`, `vendedor`, `dashboard`, `dt_alteracao`, `dt_inclusao`, `dt_nascmento`, `system_unit_id`, `desligado`
- `supervisor_vendedor`: 6
  - `id`, `vendedor_id`, `supervisor_id`, `sistema`, `dt_alteracao`, `dt_inclusao`
- `vendedor_cliente`: 4
  - `id`, `vendedor_id`, `cliente_id`, `status`
- `vendedor_atendimento`: 8
  - `id`, `vendedor_id`, `dt_alteracao`, `dt_inclusao`, `inicial`, `final`, `tipo`, `dias_semana`
- `atendimento`: 23
  - `id`, `atendimento_tipo_id`, `vendedor_id`, `codigo_cliente`, `cliente_id`, `horario_inicial`, `horario_final`, `titulo`, `cor`, `retorno`, `observacao`, `dt_inclusao`, `dt_alteracao`, `dt_delete`, `user_id_create`, `user_id_update`, `user_id_delete`, `nome`, `telefone`, `email`, `status`, `orcamento_id`, `nota_saida_id`
- `atendimento_tipo`: 14
  - `id`, `cod_erp`, `descricao`, `cor`, `icone`, `dt_alteracao`, `dt_inclusao`, `retorno`, `editar`, `excluir`, `atendimento`, `venda`, `cadastro`, `cobranca`
- `meta_vendedor_mes`: 11
  - `id`, `vendedor_id`, `mes`, `ano`, `tipo`, `valor`, `numero_cliente`, `novo_cliente`, `dt_alteracao`, `dt_inclusao`, `dt_delete`
- `meta_vendedor_categoria`: 9
  - `id`, `meta_vendedor_mes_id`, `categoria_id`, `cod_erp`, `descricao`, `valor`, `dt_alteracao`, `dt_inclusao`, `dt_delete`
- `cliente_vendedor_mes`: 22
  - `id`, `filial_id`, `empresa_id`, `vendedor_id`, `ano`, `janeiro`, `fevereiro`, `marco`, `abril`, `maio`, `junho`, `julho`, `agosto`, `setembro`, `outubro`, `novembro`, `dezembro`, `total_carteira`, `total_rcg`, `total_avulso`, `total_bloqueado`, `vendedor_nome`

### Financeiro

- `titulo_receber`: 47
  - `id`, `dt_alteracao`, `filial_id`, `dt_inclusao`, `cliente_id`, `vendedor_id`, `natureza_id`, `emissao`, `numero`, `parcela`, `prefixo`, `tipo`, `saldo`, `valor`, `decrescimo`, `acrescimo`, `valor_juros`, `perc_juros`, `mora_dia`, `taxa_multa`, `dt_digitacao`, `vencimento`, `venc_real`, `venc_orig`, `pedido_id`, `banco`, `agencia`, `conta`, `nosso_numero`, `id_cnab`, `cod_barras`, `lin_digitavel`, `forma_pgto`, `controle_bco`, `dig_nosso_numero`, `impresso`, `origem`, `historico`, `usr_inclusao`, `usr_alteracao`, `reg_ativo`, `baixa`, `system_unit_id`, `e1_recno`, `nota_fiscal_id`, `vias`, `situacao`
- `negociacao`: 14
  - `id`, `filial_id`, `cod_erp`, `dt_alteracao`, `dt_inclusao`, `cliente_id`, `vendedor_id`, `atendimento_tipo_id`, `observacao`, `system_unit_id`, `system_users_id`, `tipo`, `status`, `atendimento_id`
- `negociacao_titulo_receber`: 7
  - `id`, `dt_alteracao`, `negociacao_id`, `dt_inclusao`, `titulo_receber_id`, `vencimento`, `valor`

### Faturamento, compras e vendas

- `nota_entrada`: 42
  - `id`, `filial_id`, `cliente_id`, `fornecedor_id`, `nota_fiscal`, `serie_fiscal`, `especie_fiscal`, `condicao_id`, `numero_titulo`, `prefixo_titulo`, `dt_emissao`, `tipo`, `comodato`, `vlr_bruto`, `vlr_icms`, `base_icms`, `vlr_ipi`, `base_ipi`, `vlr_mercadoria`, `vlr_desconto`, `vlr_comodato`, `vlr_itens`, `vlr_devolucao`, `transportadora`, `tp_frete`, `vlr_frete`, `vendedor1_id`, `vendedor2_id`, `chave_nfe`, `dt_nfe`, `hr_nfe`, `mensagem_nf`, `numero_origem`, `serie_origem`, `intermediador`, `reg_ativo`, `mes`, `ano`, `system_unit_id`, `date_danfe`, `dt_inclusao`, `dt_alteracao`
- `nota_entrada_item`: 36
  - `id`, `nota_entrada_id`, `item`, `produto_id`, `qtd`, `vlr_unitario`, `tes_id`, `vlr_tabela`, `vlr_bruto`, `base_icms`, `aliq_icms`, `vlr_icms`, `base_ipi`, `aliq_ipi`, `vlr_ipi`, `vlr_total`, `vlr_dev`, `qtd_dev`, `perc_desconto`, `vlr_desconto`, `peso`, `pedido_item_id`, `reg_ativo`, `tes`, `estoque`, `financeiro`, `ano`, `mes`, `cliente_id`, `vendedor1_id`, `vendedor2_id`, `dt_emissao`, `cfop`, `perc_comissao`, `comissao`, `comodato`
- `nota_saida`: 42
  - `id`, `filial_id`, `cliente_id`, `fornecedor_id`, `nota_fiscal`, `serie_fiscal`, `especie_fiscal`, `condicao_id`, `numero_titulo`, `prefixo_titulo`, `dt_emissao`, `tipo`, `comodato`, `vlr_bruto`, `vlr_icms`, `base_icms`, `vlr_ipi`, `base_ipi`, `vlr_mercadoria`, `vlr_desconto`, `vlr_comodato`, `vlr_itens`, `vlr_devolucao`, `transportadora`, `tp_frete`, `vlr_frete`, `vendedor1_id`, `vendedor2_id`, `chave_nfe`, `dt_nfe`, `hr_nfe`, `mensagem_nf`, `numero_origem`, `serie_origem`, `intermediador`, `reg_ativo`, `mes`, `ano`, `system_unit_id`, `date_danfe`, `dt_inclusao`, `dt_alteracao`
- `nota_saida_item`: 37
  - `id`, `nota_saida_id`, `item`, `produto_id`, `qtd`, `vlr_unitario`, `tes_id`, `vlr_tabela`, `vlr_bruto`, `base_icms`, `aliq_icms`, `vlr_icms`, `base_ipi`, `aliq_ipi`, `vlr_ipi`, `vlr_total`, `vlr_dev`, `qtd_dev`, `perc_desconto`, `vlr_desconto`, `peso`, `pedido_item_id`, `reg_ativo`, `tes`, `estoque`, `financeiro`, `ano`, `mes`, `cliente_id`, `vendedor1_id`, `vendedor2_id`, `dt_emissao`, `cfop`, `perc_comissao`, `comissao`, `comodato`, `tipo`
- `notasaida_xml`: 20
  - `id`, `nota_saida_id`, `xml_sig`, `xml_tss`, `xml_cancelamento`, `nota_fiscal`, `serie_fiscal`, `chave`, `protocolo`, `modelo`, `destinatario`, `remetente`, `situcao`, `situcao_cancelamento`, `situcao_email`, `email`, `data_nfe`, `hora_nfe`, `ano`, `mes`
- `venda_mes_cliente`: 19
  - `id`, `filial_id`, `empresa_id`, `cliente_id`, `ano`, `janeiro`, `fevereiro`, `mes`, `marco`, `abril`, `maio`, `junho`, `julho`, `agosto`, `setembro`, `outubro`, `novembro`, `dezembro`, `cliente_nome`
- `venda_mes_produto`: 18
  - `id`, `filial_id`, `empresa_id`, `produto_id`, `ano`, `janeiro`, `fevereiro`, `marco`, `abril`, `maio`, `junho`, `julho`, `agosto`, `setembro`, `outubro`, `novembro`, `dezembro`, `produto_nome`

### Orcamento e pedido

- `orcamento`: 27
  - `id`, `emissao`, `retorno`, `observacao`, `cliente_id`, `tabela_preco_id`, `condicao_pagamento_id`, `pedido_id`, `vendedor_id`, `estado_id`, `municipio_id`, `codigo_cliente`, `telefone`, `nome`, `email`, `orcamento_estado_id`, `dt_cancelamento`, `dt_faturamento`, `latitude`, `longitude`, `valor_total`, `sinc`, `log_int`, `dt_inclusao`, `system_unit_id`, `dt_alteracao`, `system_user_id`
- `orcamento_estado`: 15
  - `id`, `cod_erp`, `descricao`, `cor`, `cor_texto`, `editar`, `excluir`, `imprimir`, `cancelar`, `dt_inclusao`, `dt_alteracao`, `sistema`, `ordem`, `icone`, `exibir_regua`
- `orcamento_historico`: 9
  - `id`, `orcamento_id`, `orcamento_estado_id`, `observacao`, `orcamento_proximo_estado_id`, `system_user_id`, `dt_alteracao`, `dt_inclusao`, `dt_evento`
- `orcamento_item`: 12
  - `id`, `orcamento_id`, `produto_id`, `codigo_produto`, `quantidade`, `preco_unit`, `preco_tabela`, `desconto`, `acrescimo`, `preco_total`, `dt_alteracao`, `dt_inclusao`
- `orcamento_proximo_estado`: 5
  - `id`, `orcamento_estado_id`, `proximo_estado_id`, `dt_alteracao`, `dt_inclusao`
- `pedido`: 33
  - `id`, `filial_id`, `pedido_estado_id`, `cliente_id`, `cliente_entrega_id`, `vendedor1_id`, `vendedor2_id`, `cod_erp`, `dt_emissao`, `transportadora_id`, `tabela_id`, `condicao_pagamento_id`, `sinc`, `mes`, `ano`, `tipo`, `nota_fiscal`, `serie`, `mensagem_nf`, `tp_frete`, `vlr_frete`, `vlr_total`, `vlr_comodato`, `presencial`, `pedido_origem`, `log_int`, `user_id`, `intermediador_id`, `dt_inclusao`, `dt_alteracao`, `orcamento_id`, `nota_saida_id`, `system_unit_id`
- `pedido_estado`: 5
  - `id`, `cod_erp`, `descricao`, `cor`, `cor_texto`
- `pedido_item`: 17
  - `id`, `pedido_id`, `produto_id`, `item`, `vlr_unitario`, `vlr_item`, `quantidade`, `per_desconto`, `vlr_desconto`, `vlr_acrescimo`, `vlr_total`, `armazem_id`, `tipo_movimentacao_id`, `dt_inclusao`, `dt_alteracao`, `mes`, `ano`

### Produto, estoque e precos

- `produto`: 33
  - `id`, `filial_id`, `cod_erp`, `descricao`, `tipo`, `um`, `categoria_id`, `sub_categoria_id`, `fabricante_id`, `armazem_id`, `codigo_barras`, `codigo_fabricante`, `qtd_embalagem`, `observacao`, `foto`, `status`, `ncm`, `origem`, `peso_bruto`, `peso`, `marca`, `te_id`, `ts_id`, `sinc`, `ponto_pedido`, `estoque_seguranca`, `dt_ult_compra`, `ult_preco`, `informacoes_tecnicas`, `dados_tecnicos`, `system_unit_id`, `dt_inclusao`, `dt_alteracao`
- `produto_caracteristica`: 8
  - `id`, `caracteristica_id`, `produto_id`, `des_caracteristica`, `dt_caracteristica`, `vlr_caracteristica`, `dt_alteracao`, `dt_inclusao`
- `ficha_tecnica`: 6
  - `id`, `produto_id`, `arquivo`, `validade`, `dt_alteracao`, `dt_inclusao`
- `estoque`: 12
  - `id`, `filial_id`, `produto_id`, `armazem_id`, `saldo`, `reserva`, `system_unit_id`, `ult_compra`, `ult_preco`, `custo`, `dt_inclusao`, `dt_alteracao`
- `tabela_preco`: 12
  - `id`, `empresa_id`, `filial_id`, `cod_erp`, `descricao`, `status`, `dt_inicio`, `dt_fim`, `dt_inclusao`, `dt_alteracao`, `utiliza`, `system_unit_id`
- `tabela_preco_item`: 8
  - `id`, `item`, `tabela_preco_id`, `produto_id`, `preco`, `status`, `dt_alteracao`, `dt_inclusao`

### Conteudo e apoio

- `blog_aniversarios`: 9
  - `id`, `nome`, `filial_id`, `dia`, `mes`, `dt_alteracao`, `dt_inclusao`, `status`, `system_user_id`
- `blog_comunicados`: 13
  - `id`, `filial_id`, `titulo`, `texto`, `data_postagem`, `link_externo`, `link_texto`, `status`, `ordenacao`, `data_validade`, `dt_alteracao`, `dt_inclusao`, `system_user_id`
- `blog_noticias`: 11
  - `id`, `titulo`, `texto`, `data_postagem`, `imagem`, `autor`, `status`, `dt_alteracao`, `dt_inclusao`, `data_validade`, `system_user_id`
- `calendario_orcamento`: 7
  - `id`, `orcamento_id`, `horario_inicial`, `horario_final`, `titulo`, `cor`, `observacao`
- `fornecedor`: 42
  - `id`, `filial_id`, `cod_erp`, `status`, `razao`, `tipo`, `fantasia`, `endereco`, `complemento`, `bairro`, `uf`, `municipio`, `municipio_id`, `cep`, `telefone1`, `telefone2`, `fax`, `celular`, `contato`, `cnpj_cpf`, `ie`, `im`, `contribuinte`, `rg`, `nascimento`, `email`, `condicao_pagamento_id`, `primeira_compra`, `ultima_visita`, `sinc`, `data_cadastro`, `dt_alteracao`, `dt_inclusao`, `destaca_ie`, `seguimento_id`, `site`, `obs`, `filial_cadastro`, `latitude`, `log_int`, `longitude`, `system_unit_id`

## Observacoes Estruturais

- O DDL exposto descreve tabelas, mas quase nao explicita `FOREIGN KEY`, `UNIQUE` e `INDEX` para a base transacional.
- Grande parte dos campos monetarios usa `float`; para valores financeiros o ideal e `numeric`.
- Ano e mes aparecem em varios pontos como `char`/`varchar`, o que dificulta consistencia e pode degradar comparacoes e indexacao.
