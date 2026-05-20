CREATE TABLE armazem( 
      `id`  INT  AUTO_INCREMENT    NOT NULL  , 
      `cod_erp` char  (6)   NOT NULL  , 
      `descricao` varchar  (50)   NOT NULL  , 
      `status` varchar  (1)   , 
      `dt_inclusao` datetime   , 
      `dt_alteracao` datetime   , 
      `system_unit_id` int   , 
 PRIMARY KEY (id)) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci; 

CREATE TABLE atendimento( 
      `id`  INT  AUTO_INCREMENT    NOT NULL  , 
      `atendimento_tipo_id` int   NOT NULL  , 
      `vendedor_id` int   NOT NULL  , 
      `codigo_cliente` varchar  (10)   , 
      `cliente_id` int   , 
      `horario_inicial` datetime   NOT NULL  , 
      `horario_final` datetime   NOT NULL  , 
      `titulo` text   , 
      `cor` text   , 
      `retorno` datetime   , 
      `observacao` text   , 
      `dt_inclusao` datetime   , 
      `dt_alteracao` datetime   , 
      `dt_delete` datetime   , 
      `user_id_create` int   , 
      `user_id_update` int   , 
      `user_id_delete` int   , 
      `nome` varchar  (100)   , 
      `telefone` varchar  (50)   , 
      `email` varchar  (100)   , 
      `status` char  (1)   , 
      `orcamento_id` int   , 
      `nota_saida_id` int   , 
 PRIMARY KEY (id)) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci; 

CREATE TABLE atendimento_tipo( 
      `id`  INT  AUTO_INCREMENT    NOT NULL  , 
      `cod_erp` varchar  (10)   , 
      `descricao` varchar  (50)   , 
      `cor` text   , 
      `icone` varchar  (100)   , 
      `dt_alteracao` datetime   , 
      `dt_inclusao` datetime   , 
      `retorno` char  (1)   , 
      `editar` char  (1)   , 
      `excluir` char  (1)   , 
      `atendimento` char  (1)   , 
      `venda` char  (1)   , 
      `cadastro` char  (1)   , 
      `cobranca` char  (1)   , 
 PRIMARY KEY (id)) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci; 

CREATE TABLE blog_aniversarios( 
      `id`  INT  AUTO_INCREMENT    NOT NULL  , 
      `nome` text   NOT NULL  , 
      `filial_id` int   , 
      `dia` int   NOT NULL  , 
      `mes` int   NOT NULL  , 
      `dt_alteracao` datetime   , 
      `dt_inclusao` datetime   , 
      `status` char  (1)   , 
      `system_user_id` int   , 
 PRIMARY KEY (id)) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci; 

CREATE TABLE blog_comunicados( 
      `id`  INT  AUTO_INCREMENT    NOT NULL  , 
      `filial_id` int   , 
      `titulo` text   NOT NULL  , 
      `texto` text   NOT NULL  , 
      `data_postagem` date   NOT NULL  , 
      `link_externo` text   , 
      `link_texto` text   , 
      `status` char  (1)   , 
      `ordenacao` int   , 
      `data_validade` date   , 
      `dt_alteracao` datetime   , 
      `dt_inclusao` datetime   , 
      `system_user_id` int   , 
 PRIMARY KEY (id)) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci; 

CREATE TABLE blog_noticias( 
      `id`  INT  AUTO_INCREMENT    NOT NULL  , 
      `titulo` text   NOT NULL  , 
      `texto` text   NOT NULL  , 
      `data_postagem` date   NOT NULL  , 
      `imagem` text   , 
      `autor` text   , 
      `status` char  (1)   , 
      `dt_alteracao` datetime   , 
      `dt_inclusao` datetime   , 
      `data_validade` date   , 
      `system_user_id` int   , 
 PRIMARY KEY (id)) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci; 

CREATE TABLE calendario_orcamento( 
      `id`  INT  AUTO_INCREMENT    NOT NULL  , 
      `orcamento_id` int   NOT NULL  , 
      `horario_inicial` datetime   NOT NULL  , 
      `horario_final` datetime   NOT NULL  , 
      `titulo` text   , 
      `cor` text   , 
      `observacao` text   , 
 PRIMARY KEY (id)) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci; 

CREATE TABLE caracteristica( 
      `id`  INT  AUTO_INCREMENT    NOT NULL  , 
      `descricao` varchar  (50)   , 
      `tipo` char  (1)   , 
      `dt_alteracao` datetime   , 
      `dt_inclusao` datetime   , 
 PRIMARY KEY (id)) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci; 

CREATE TABLE categoria( 
      `id`  INT  AUTO_INCREMENT    NOT NULL  , 
      `filial_id` int   , 
      `cod_erp` varchar  (6)   NOT NULL  , 
      `descricao` varchar  (200)   NOT NULL  , 
      `usado` char  (1)   , 
      `site` char  (1)   , 
      `status` char  (1)   , 
      `dt_alteracao` datetime   , 
      `dt_inclusao` datetime   , 
 PRIMARY KEY (id)) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci; 

CREATE TABLE cep( 
      `id`  INT  AUTO_INCREMENT    NOT NULL  , 
      `cep` varchar  (8)   , 
      `estado_id` int   , 
      `cidade_id` int   , 
      `bairro` varchar  (500)   , 
      `endereco` varchar  (500)   , 
      `longitude` double   , 
      `latitude` double   , 
      `service` varchar  (100)   , 
 PRIMARY KEY (id)) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci; 

CREATE TABLE cliente( 
      `id`  INT  AUTO_INCREMENT    NOT NULL  , 
      `filial_id` int   , 
      `cod_erp` varchar  (10)   , 
      `status` char  (1)   , 
      `tipo` char  (1)   , 
      `razao` varchar  (100)   NOT NULL  , 
      `tipo_cliente_id` int   , 
      `fantasia` varchar  (50)   , 
      `endereco` varchar  (100)   , 
      `complemento` varchar  (50)   , 
      `bairro` varchar  (50)   , 
      `uf` char  (2)   , 
      `municipio` varchar  (50)   , 
      `municipio_id` int   , 
      `cep` varchar  (8)   , 
      `telefone1` varchar  (20)   , 
      `telefone2` varchar  (20)   , 
      `fax` char  (20)   , 
      `celular` varchar  (20)   , 
      `celular2` char  (20)   , 
      `contato` varchar  (30)   , 
      `cnpj_cpf` varchar  (20)   , 
      `ie` varchar  (20)   , 
      `im` char  (20)   , 
      `contribuinte` char  (1)   , 
      `rg` varchar  (20)   , 
      `nascimento` date   , 
      `email` varchar  (100)   , 
      `vendedor_id` int   , 
      `condicao_pagamento_id` int   , 
      `tabela_preco_id` int   , 
      `primeira_compra` date   , 
      `ultima_compra` date   , 
      `data_cadastro` date   , 
      `dt_alteracao` datetime   , 
      `sinc` char  (1)   , 
      `dt_inclusao` datetime   , 
      `destaca_ie` char  (1)   , 
      `seguimento_id` int   , 
      `site` char  (100)   , 
      `obs` text   , 
      `filial_cadastro` int   , 
      `cliente` char  (1)   , 
      `latitude` double   , 
      `log_int` text   , 
      `longitude` double   , 
      `limite` double   , 
      `vencimento_limite` date   , 
      `risco` char  (1)   , 
      `system_unit_id` int   , 
      `carteira` char  (1)   , 
      `obs_bloqueio` text   , 
      `dt_bloqueio` datetime   , 
      `motivo_bloqueio` char  (1)   , 
      `motivo_bloqueio_id` int   , 
      `dt_reativacao` date   , 
      `obs_reativacao` text   , 
      `ultima_visita` datetime   , 
      `ultimo_atendimento` datetime   , 
      `regiao_cliente_id` int   , 
      `reg_ativo` char  (1)   , 
      `dt_revisao` datetime   , 
      `situacao_cadastral_id` int   , 
      `data_rfb` date   , 
 PRIMARY KEY (id)) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci; 

CREATE TABLE cliente_acesso( 
      `id`  INT  AUTO_INCREMENT    NOT NULL  , 
      `cliente_id` int   , 
      `user_id` int   , 
      `dt_alteracao` datetime   , 
      `dt_inclusao` datetime   , 
      `dt_delete` datetime   , 
      `login` varchar  (100)   , 
      `senha` text   , 
      `status` char  (1)   , 
 PRIMARY KEY (id)) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci; 

CREATE TABLE cliente_atendimento( 
      `id`  INT  AUTO_INCREMENT    NOT NULL  , 
      `cliente_id` int   NOT NULL  , 
      `vendedor_id` int   NOT NULL  , 
      `horario_inicial` datetime   NOT NULL  , 
      `horario_final` datetime   NOT NULL  , 
      `titulo` text   , 
      `cor` text   , 
      `observacao` text   , 
      `dt_alteracao` datetime   , 
      `dt_inclusao` datetime   , 
 PRIMARY KEY (id)) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci; 

CREATE TABLE cliente_atualizacao( 
      `id`  INT  AUTO_INCREMENT    NOT NULL  , 
      `cliente_id` int   , 
      `situacao_cadastral_id` int   , 
      `atividade_principal_id` int   , 
      `razao` text   NOT NULL  , 
      `fantasia` text   , 
      `tipo_logradouro` varchar  (20)   , 
      `logradouro` text   , 
      `numero` varchar  (10)   , 
      `complemento` text   , 
      `bairro` varchar  (50)   , 
      `municipio_id` int   , 
      `cep` varchar  (8)   , 
      `telefone1` varchar  (20)   , 
      `telefone2` varchar  (20)   , 
      `fax` char  (20)   , 
      `celular` varchar  (20)   , 
      `celular2` char  (20)   , 
      `contato` varchar  (30)   , 
      `cnpj_cpf` varchar  (20)   , 
      `ie` varchar  (20)   , 
      `im` char  (20)   , 
      `email` varchar  (100)   , 
      `site` char  (100)   , 
      `latitude` double   , 
      `longitude` double   , 
      `dt_alteracao` datetime   , 
      `dt_inclusao` datetime   , 
      `data_situacao_especial` datetime   , 
      `situacao_especial` varchar  (10)   , 
      `atualizado_em` datetime   , 
      `data_situacao_cadastral` datetime   , 
      `simples` varchar  (10)   , 
      `tipo` varchar  (10)   , 
      `pais_id` int   , 
      `mei` varchar  (10)   , 
      `porte` varchar  (100)   , 
      `natureza_juridica` varchar  (100)   , 
      `capital_social` double   , 
 PRIMARY KEY (id)) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci; 

CREATE TABLE cliente_cnae( 
      `id`  INT  AUTO_INCREMENT    NOT NULL  , 
      `cliente_id` int   NOT NULL  , 
      `cnae_id` int   NOT NULL  , 
      `dt_alteracao` datetime   , 
      `dt_inclusao` datetime   , 
 PRIMARY KEY (id)) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci; 

CREATE TABLE cliente_condicao( 
      `id`  INT  AUTO_INCREMENT    NOT NULL  , 
      `condicao_pagamento_id` int   NOT NULL  , 
      `cliente_id` int   NOT NULL  , 
      `padrao` char  (1)   , 
      `dt_inclusao` datetime   , 
      `dt_alteracao` datetime   , 
 PRIMARY KEY (id)) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci; 

CREATE TABLE cliente_contato( 
      `id`  INT  AUTO_INCREMENT    NOT NULL  , 
      `cliente_id` int   NOT NULL  , 
      `tipo_contato_id` int   NOT NULL  , 
      `nome` varchar  (100)   , 
      `telefone` char  (9)   , 
      `email` varchar  (100)   , 
      `situacao` char  (1)   , 
      `dt_inclusao` datetime   , 
      `dt_alteracao` datetime   , 
 PRIMARY KEY (id)) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci; 

CREATE TABLE cliente_historico( 
      `id`  INT  AUTO_INCREMENT    NOT NULL  , 
      `cliente_id` int   NOT NULL  , 
      `vendedor_id` int   , 
      `motivo_id` int   , 
      `observacao` text   , 
      `dt_alteracao` datetime   , 
      `dt_inclusao` datetime   , 
      `data_movimento` date   , 
 PRIMARY KEY (id)) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci; 

CREATE TABLE cliente_socios( 
      `id`  INT  AUTO_INCREMENT    NOT NULL  , 
      `cliente_id` int   NOT NULL  , 
      `nome` varchar  (100)   , 
      `tipo` varchar  (20)   , 
      `data_entrada` date   , 
      `faixa_etaria` varchar  (100)   , 
      `qualificacao_socio` varchar  (100)   , 
      `descricao` varchar  (100)   , 
      `cpf_cnpj_socio` varchar  (100)   , 
      `dt_alteracao` datetime   , 
      `dt_inclusao` datetime   , 
 PRIMARY KEY (id)) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci; 

CREATE TABLE cliente_vendedor_mes( 
      `id`  INT  AUTO_INCREMENT    NOT NULL  , 
      `filial_id` int   , 
      `empresa_id` int   , 
      `vendedor_id` int   NOT NULL  , 
      `ano` char  (4)   NOT NULL  , 
      `janeiro` double     DEFAULT 0, 
      `fevereiro` double     DEFAULT 0, 
      `marco` double     DEFAULT 0, 
      `abril` double     DEFAULT 0, 
      `maio` double     DEFAULT 0, 
      `junho` double     DEFAULT 0, 
      `julho` double     DEFAULT 0, 
      `agosto` double     DEFAULT 0, 
      `setembro` double     DEFAULT 0, 
      `outubro` double     DEFAULT 0, 
      `novembro` double     DEFAULT 0, 
      `dezembro` double     DEFAULT 0, 
      `total_carteira` double     DEFAULT 0, 
      `total_rcg` double     DEFAULT 0, 
      `total_avulso` double     DEFAULT 0, 
      `total_bloqueado` double   , 
      `vendedor_nome` varchar  (100)   , 
 PRIMARY KEY (id)) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci; 

CREATE TABLE cnae( 
      `id`  INT  AUTO_INCREMENT    NOT NULL  , 
      `cod_erp` varchar  (10)   , 
      `secao` varchar  (1)   , 
      `divisao` varchar  (10)   , 
      `grupo` varchar  (10)   , 
      `classe` varchar  (10)   , 
      `subclasse` varchar  (10)   , 
      `descricao` text   , 
      `dt_inclusao` datetime   , 
      `dt_alteracao` datetime   , 
 PRIMARY KEY (id)) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci; 

CREATE TABLE condicao_pagamento( 
      `id`  INT  AUTO_INCREMENT    NOT NULL  , 
      `filial_id` int   , 
      `cod_erp` varchar  (3)   NOT NULL  , 
      `descricao` varchar  (100)   NOT NULL  , 
      `forma` char  (3)   , 
      `status` char  (1)   , 
      `dt_inclusao` datetime   , 
      `dt_alteracao` datetime   , 
      `system_unit_id` int   , 
 PRIMARY KEY (id)) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci; 

CREATE TABLE empresa( 
      `id`  INT  AUTO_INCREMENT    NOT NULL  , 
      `cod_erp` char  (2)   , 
      `system_unit_id` int   , 
      `nome` varchar  (100)   NOT NULL  , 
      `logo` text   , 
      `status` char  (1)   , 
      `dt_inclusao` datetime   , 
      `dt_alteracao` datetime   , 
 PRIMARY KEY (id)) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci; 

CREATE TABLE estado( 
      `id`  INT  AUTO_INCREMENT    NOT NULL  , 
      `cod_erp` varchar  (2)   NOT NULL  , 
      `sigla` varchar  (2)   NOT NULL  , 
      `descricao` varchar  (100)   NOT NULL  , 
      `codigo_ibge` varchar  (10)   , 
 PRIMARY KEY (id)) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci; 

CREATE TABLE estoque( 
      `id`  INT  AUTO_INCREMENT    NOT NULL  , 
      `filial_id` int   , 
      `produto_id` int   NOT NULL  , 
      `armazem_id` int   NOT NULL  , 
      `saldo` double   , 
      `reserva` double   , 
      `system_unit_id` int   , 
      `ult_compra` date   , 
      `ult_preco` double   , 
      `custo` double   , 
      `dt_inclusao` datetime   , 
      `dt_alteracao` datetime   , 
 PRIMARY KEY (id)) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci; 

CREATE TABLE fabricante( 
      `id`  INT  AUTO_INCREMENT    NOT NULL  , 
      `cod_erp` char  (6)   NOT NULL  , 
      `descricao` varchar  (200)   NOT NULL  , 
      `dt_inclusao` datetime   , 
      `dt_alteracao` datetime   , 
 PRIMARY KEY (id)) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci; 

CREATE TABLE ficha_tecnica( 
      `id`  INT  AUTO_INCREMENT    NOT NULL  , 
      `produto_id` int   NOT NULL  , 
      `arquivo` varchar  (200)   , 
      `validade` date   , 
      `dt_alteracao` datetime   , 
      `dt_inclusao` datetime   , 
 PRIMARY KEY (id)) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci; 

CREATE TABLE filial( 
      `id`  INT  AUTO_INCREMENT    NOT NULL  , 
      `cod_emp` char  (6)   , 
      `cod_erp` char  (6)   NOT NULL  , 
      `system_unit_id` int   , 
      `apelido` varchar  (50)   , 
      `matriz` char  (1)   , 
      `nome` varchar  (100)   NOT NULL  , 
      `fantasia` varchar  (100)   , 
      `tipo` char  (1)   , 
      `cnpj` varchar  (14)   , 
      `cpf` char  (11)   , 
      `cep` varchar  (8)   , 
      `endereco` varchar  (100)   , 
      `complemento` varchar  (100)   , 
      `bairro` varchar  (50)   , 
      `municipio` varchar  (50)   , 
      `uf` char  (2)   , 
      `email` varchar  (100)   , 
      `status` char  (1)   , 
      `dt_alteracao` datetime   , 
      `dt_inclusao` datetime   , 
 PRIMARY KEY (id)) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci; 

CREATE TABLE fornecedor( 
      `id`  INT  AUTO_INCREMENT    NOT NULL  , 
      `filial_id` int   , 
      `cod_erp` varchar  (10)   , 
      `status` char  (1)   , 
      `razao` varchar  (100)   NOT NULL  , 
      `tipo` int   , 
      `fantasia` varchar  (50)   , 
      `endereco` varchar  (100)   , 
      `complemento` varchar  (50)   , 
      `bairro` varchar  (50)   , 
      `uf` char  (2)   , 
      `municipio` varchar  (50)   , 
      `municipio_id` int   , 
      `cep` varchar  (8)   , 
      `telefone1` varchar  (20)   , 
      `telefone2` varchar  (20)   , 
      `fax` char  (20)   , 
      `celular` varchar  (20)   , 
      `contato` varchar  (30)   , 
      `cnpj_cpf` varchar  (20)   , 
      `ie` varchar  (20)   , 
      `im` char  (20)   , 
      `contribuinte` char  (1)   , 
      `rg` varchar  (20)   , 
      `nascimento` date   , 
      `email` varchar  (100)   , 
      `condicao_pagamento_id` int   , 
      `primeira_compra` date   , 
      `ultima_visita` date   , 
      `sinc` char  (1)   , 
      `data_cadastro` date   , 
      `dt_alteracao` datetime   , 
      `dt_inclusao` datetime   , 
      `destaca_ie` char  (1)   , 
      `seguimento_id` int   , 
      `site` char  (100)   , 
      `obs` text   , 
      `filial_cadastro` int   , 
      `latitude` double   , 
      `log_int` text   , 
      `longitude` double   , 
      `system_unit_id` int   , 
 PRIMARY KEY (id)) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci; 

CREATE TABLE meta_vendedor_categoria( 
      `id`  INT  AUTO_INCREMENT    NOT NULL  , 
      `meta_vendedor_mes_id` int   NOT NULL  , 
      `categoria_id` int   NOT NULL  , 
      `cod_erp` varchar  (6)   , 
      `descricao` varchar  (200)   , 
      `valor` double   , 
      `dt_alteracao` datetime   , 
      `dt_inclusao` datetime   , 
      `dt_delete` datetime   , 
 PRIMARY KEY (id)) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci; 

CREATE TABLE meta_vendedor_mes( 
      `id`  INT  AUTO_INCREMENT    NOT NULL  , 
      `vendedor_id` int   NOT NULL  , 
      `mes` varchar  (2)   NOT NULL  , 
      `ano` varchar  (4)   NOT NULL  , 
      `tipo` char  (1)   , 
      `valor` double   NOT NULL  , 
      `numero_cliente` double   , 
      `novo_cliente` double   , 
      `dt_alteracao` datetime   , 
      `dt_inclusao` datetime   , 
      `dt_delete` datetime   , 
 PRIMARY KEY (id)) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci; 

CREATE TABLE motivo_bloqueio( 
      `id`  INT  AUTO_INCREMENT    NOT NULL  , 
      `cod_erp` varchar  (10)   , 
      `dt_alteracao` datetime   , 
      `dt_inclusao` datetime   , 
      `descricao` char  (40)   , 
 PRIMARY KEY (id)) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci; 

CREATE TABLE municipio( 
      `id`  INT  AUTO_INCREMENT    NOT NULL  , 
      `cod_erp` varchar  (10)   NOT NULL  , 
      `descricao` varchar  (200)   NOT NULL  , 
      `estado_id` int   , 
      `codigo_ibge` varchar  (10)   , 
 PRIMARY KEY (id)) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci; 

CREATE TABLE natureza( 
      `id`  INT  AUTO_INCREMENT    NOT NULL  , 
      `cod_erp` varchar  (20)   , 
      `descricao` text   , 
      `dt_alteracao` datetime   , 
      `dt_inclusao` datetime   , 
 PRIMARY KEY (id)) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci; 

CREATE TABLE negociacao( 
      `id`  INT  AUTO_INCREMENT    NOT NULL  , 
      `filial_id` int   , 
      `cod_erp` varchar  (10)   , 
      `dt_alteracao` datetime   , 
      `dt_inclusao` datetime   , 
      `cliente_id` int   , 
      `vendedor_id` int   , 
      `atendimento_tipo_id` int   NOT NULL  , 
      `observacao` text   , 
      `system_unit_id` int   , 
      `system_users_id` int   , 
      `tipo` int   , 
      `status` char   , 
      `atendimento_id` int   NOT NULL  , 
 PRIMARY KEY (id)) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci; 

CREATE TABLE negociacao_titulo_receber( 
      `id`  INT  AUTO_INCREMENT    NOT NULL  , 
      `dt_alteracao` datetime   , 
      `negociacao_id` int   NOT NULL  , 
      `dt_inclusao` datetime   , 
      `titulo_receber_id` int   NOT NULL  , 
      `vencimento` date   , 
      `valor` double   , 
 PRIMARY KEY (id)) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci; 

CREATE TABLE nota_entrada( 
      `id`  INT  AUTO_INCREMENT    NOT NULL  , 
      `filial_id` int   , 
      `cliente_id` int   , 
      `fornecedor_id` int   , 
      `nota_fiscal` varchar  (9)   , 
      `serie_fiscal` varchar  (3)   , 
      `especie_fiscal` varchar  (10)   , 
      `condicao_id` int   , 
      `numero_titulo` varchar  (9)   , 
      `prefixo_titulo` varchar  (6)   , 
      `dt_emissao` date   , 
      `tipo` char  (1)   , 
      `comodato` char  (1)   , 
      `vlr_bruto` double   , 
      `vlr_icms` double   , 
      `base_icms` double   , 
      `vlr_ipi` double   , 
      `base_ipi` double   , 
      `vlr_mercadoria` double   , 
      `vlr_desconto` double   , 
      `vlr_comodato` double   , 
      `vlr_itens` double   , 
      `vlr_devolucao` double   , 
      `transportadora` varchar  (6)   , 
      `tp_frete` char  (1)   , 
      `vlr_frete` double   , 
      `vendedor1_id` int   , 
      `vendedor2_id` int   , 
      `chave_nfe` varchar  (100)   , 
      `dt_nfe` date   , 
      `hr_nfe` varchar  (10)   , 
      `mensagem_nf` text   , 
      `numero_origem` varchar  (6)   , 
      `serie_origem` varchar  (3)   , 
      `intermediador` varchar  (6)   , 
      `reg_ativo` char  (1)   , 
      `mes` varchar  (2)   , 
      `ano` varchar  (4)   , 
      `system_unit_id` int   , 
      `date_danfe` datetime   , 
      `dt_inclusao` datetime   , 
      `dt_alteracao` datetime   , 
 PRIMARY KEY (id)) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci; 

CREATE TABLE nota_entrada_item( 
      `id`  INT  AUTO_INCREMENT    NOT NULL  , 
      `nota_entrada_id` int   NOT NULL  , 
      `item` varchar  (4)   , 
      `produto_id` int   , 
      `qtd` double   , 
      `vlr_unitario` double   , 
      `tes_id` int   , 
      `vlr_tabela` double   , 
      `vlr_bruto` double   , 
      `base_icms` double   , 
      `aliq_icms` double   , 
      `vlr_icms` double   , 
      `base_ipi` double   , 
      `aliq_ipi` double   , 
      `vlr_ipi` double   , 
      `vlr_total` double   , 
      `vlr_dev` double   , 
      `qtd_dev` double   , 
      `perc_desconto` double   , 
      `vlr_desconto` double   , 
      `peso` double   , 
      `pedido_item_id` int   , 
      `reg_ativo` char  (1)   , 
      `tes` varchar  (3)   , 
      `estoque` char  (1)   , 
      `financeiro` char  (1)   , 
      `ano` varchar  (4)   , 
      `mes` varchar  (2)   , 
      `cliente_id` int   , 
      `vendedor1_id` int   , 
      `vendedor2_id` int   , 
      `dt_emissao` date   , 
      `cfop` char  (4)   , 
      `perc_comissao` double   , 
      `comissao` double   , 
      `comodato` char  (1)   , 
 PRIMARY KEY (id)) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci; 

CREATE TABLE nota_saida( 
      `id`  INT  AUTO_INCREMENT    NOT NULL  , 
      `filial_id` int   , 
      `cliente_id` int   , 
      `fornecedor_id` int   , 
      `nota_fiscal` varchar  (9)   , 
      `serie_fiscal` varchar  (3)   , 
      `especie_fiscal` varchar  (10)   , 
      `condicao_id` int   , 
      `numero_titulo` varchar  (9)   , 
      `prefixo_titulo` varchar  (6)   , 
      `dt_emissao` date   , 
      `tipo` char  (1)   , 
      `comodato` char  (1)   , 
      `vlr_bruto` double   , 
      `vlr_icms` double   , 
      `base_icms` double   , 
      `vlr_ipi` double   , 
      `base_ipi` double   , 
      `vlr_mercadoria` double   , 
      `vlr_desconto` double   , 
      `vlr_comodato` double   , 
      `vlr_itens` double   , 
      `vlr_devolucao` double   , 
      `transportadora` varchar  (6)   , 
      `tp_frete` char  (1)   , 
      `vlr_frete` double   , 
      `vendedor1_id` int   , 
      `vendedor2_id` int   , 
      `chave_nfe` varchar  (100)   , 
      `dt_nfe` date   , 
      `hr_nfe` varchar  (10)   , 
      `mensagem_nf` text   , 
      `numero_origem` varchar  (6)   , 
      `serie_origem` varchar  (3)   , 
      `intermediador` varchar  (6)   , 
      `reg_ativo` char  (1)   , 
      `mes` varchar  (2)   , 
      `ano` varchar  (4)   , 
      `system_unit_id` int   , 
      `date_danfe` datetime   , 
      `dt_inclusao` datetime   , 
      `dt_alteracao` datetime   , 
 PRIMARY KEY (id)) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci; 

CREATE TABLE nota_saida_item( 
      `id`  INT  AUTO_INCREMENT    NOT NULL  , 
      `nota_saida_id` int   NOT NULL  , 
      `item` varchar  (4)   , 
      `produto_id` int   , 
      `qtd` double   , 
      `vlr_unitario` double   , 
      `tes_id` int   , 
      `vlr_tabela` double   , 
      `vlr_bruto` double   , 
      `base_icms` double   , 
      `aliq_icms` double   , 
      `vlr_icms` double   , 
      `base_ipi` double   , 
      `aliq_ipi` double   , 
      `vlr_ipi` double   , 
      `vlr_total` double   , 
      `vlr_dev` double   , 
      `qtd_dev` double   , 
      `perc_desconto` double   , 
      `vlr_desconto` double   , 
      `peso` double   , 
      `pedido_item_id` int   , 
      `reg_ativo` char  (1)   , 
      `tes` varchar  (3)   , 
      `estoque` char  (1)   , 
      `financeiro` char  (1)   , 
      `ano` varchar  (4)   , 
      `mes` varchar  (2)   , 
      `cliente_id` int   , 
      `vendedor1_id` int   , 
      `vendedor2_id` int   , 
      `dt_emissao` date   , 
      `cfop` char  (4)   , 
      `perc_comissao` double   , 
      `comissao` double   , 
      `comodato` char  (1)   , 
      `tipo` char  (1)   , 
 PRIMARY KEY (id)) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci; 

CREATE TABLE notasaida_xml( 
      `id`  INT  AUTO_INCREMENT    NOT NULL  , 
      `nota_saida_id` int   , 
      `xml_sig` text   , 
      `xml_tss` text   , 
      `xml_cancelamento` text   , 
      `nota_fiscal` varchar  (9)   , 
      `serie_fiscal` varchar  (3)   , 
      `chave` varchar  (100)   , 
      `protocolo` varchar  (100)   , 
      `modelo` varchar  (2)   , 
      `destinatario` varchar  (14)   , 
      `remetente` varchar  (14)   , 
      `situcao` varchar  (1)   , 
      `situcao_cancelamento` varchar  (1)   , 
      `situcao_email` varchar  (1)   , 
      `email` varchar  (100)   , 
      `data_nfe` date   , 
      `hora_nfe` time   , 
      `ano` varchar  (4)   , 
      `mes` varchar  (2)   , 
 PRIMARY KEY (id)) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci; 

CREATE TABLE orcamento( 
      `id`  INT  AUTO_INCREMENT    NOT NULL  , 
      `emissao` datetime   , 
      `retorno` datetime   , 
      `observacao` text   , 
      `cliente_id` int   , 
      `tabela_preco_id` int   , 
      `condicao_pagamento_id` int   , 
      `pedido_id` int   , 
      `vendedor_id` int   , 
      `estado_id` int   , 
      `municipio_id` int   , 
      `codigo_cliente` varchar  (8)   , 
      `telefone` varchar  (50)   NOT NULL  , 
      `nome` text   NOT NULL  , 
      `email` varchar  (100)   , 
      `orcamento_estado_id` int   , 
      `dt_cancelamento` datetime   , 
      `dt_faturamento` datetime   , 
      `latitude` double   , 
      `longitude` double   , 
      `valor_total` double   , 
      `sinc` char  (1)   , 
      `log_int` text   , 
      `dt_inclusao` datetime   , 
      `system_unit_id` int   , 
      `dt_alteracao` datetime   , 
      `system_user_id` int   , 
 PRIMARY KEY (id)) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci; 

CREATE TABLE orcamento_estado( 
      `id`  INT  AUTO_INCREMENT    NOT NULL  , 
      `cod_erp` varchar  (10)   , 
      `descricao` varchar  (100)   , 
      `cor` varchar  (10)   , 
      `cor_texto` varchar  (10)   , 
      `editar` char  (1)   , 
      `excluir` char  (1)   , 
      `imprimir` char  (1)   , 
      `cancelar` char  (1)   , 
      `dt_inclusao` datetime   , 
      `dt_alteracao` datetime   , 
      `sistema` char  (1)   , 
      `ordem` int   , 
      `icone` text   , 
      `exibir_regua` char  (1)   , 
 PRIMARY KEY (id)) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci; 

CREATE TABLE orcamento_historico( 
      `id`  INT  AUTO_INCREMENT    NOT NULL  , 
      `orcamento_id` int   NOT NULL  , 
      `orcamento_estado_id` int   NOT NULL  , 
      `observacao` text   , 
      `orcamento_proximo_estado_id` int   , 
      `system_user_id` int   , 
      `dt_alteracao` datetime   , 
      `dt_inclusao` datetime   , 
      `dt_evento` datetime   , 
 PRIMARY KEY (id)) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci; 

CREATE TABLE orcamento_item( 
      `id`  INT  AUTO_INCREMENT    NOT NULL  , 
      `orcamento_id` int   NOT NULL  , 
      `produto_id` int   NOT NULL  , 
      `codigo_produto` varchar  (30)   , 
      `quantidade` double   , 
      `preco_unit` double   , 
      `preco_tabela` double   , 
      `desconto` double   , 
      `acrescimo` double   , 
      `preco_total` double   , 
      `dt_alteracao` datetime   , 
      `dt_inclusao` datetime   , 
 PRIMARY KEY (id)) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci; 

CREATE TABLE orcamento_proximo_estado( 
      `id`  INT  AUTO_INCREMENT    NOT NULL  , 
      `orcamento_estado_id` int   NOT NULL  , 
      `proximo_estado_id` int   , 
      `dt_alteracao` datetime   , 
      `dt_inclusao` datetime   , 
 PRIMARY KEY (id)) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci; 

CREATE TABLE pais( 
      `id`  INT  AUTO_INCREMENT    NOT NULL  , 
      `cod_erp` varchar  (10)   , 
      `nome` varchar  (100)   , 
      `dt_alteracao` datetime   , 
      `dt_inclusao` datetime   , 
      `sigla` varchar  (4)   , 
      `comex_id` varchar  (10)   , 
 PRIMARY KEY (id)) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci; 

CREATE TABLE parametro( 
      `id`  INT  AUTO_INCREMENT    NOT NULL  , 
      `filial_id` int   , 
      `parametro` varchar  (50)   NOT NULL  , 
      `conteudo` varchar  (100)   , 
      `tipo` char  (1)   NOT NULL  , 
 PRIMARY KEY (id)) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci; 

CREATE TABLE pedido( 
      `id`  INT  AUTO_INCREMENT    NOT NULL  , 
      `filial_id` int   , 
      `pedido_estado_id` int   , 
      `cliente_id` int   NOT NULL  , 
      `cliente_entrega_id` int   , 
      `vendedor1_id` int   NOT NULL  , 
      `vendedor2_id` int   , 
      `cod_erp` char  (6)   , 
      `dt_emissao` date   , 
      `transportadora_id` int   , 
      `tabela_id` int   , 
      `condicao_pagamento_id` int   , 
      `sinc` char  (1)   , 
      `mes` varchar  (2)   , 
      `ano` varchar  (4)   , 
      `tipo` char  (1)   , 
      `nota_fiscal` varchar  (9)   , 
      `serie` varchar  (3)   , 
      `mensagem_nf` varchar  (100)   , 
      `tp_frete` char  (1)   , 
      `vlr_frete` double   , 
      `vlr_total` double   , 
      `vlr_comodato` double   , 
      `presencial` char  (1)   , 
      `pedido_origem` char  (1)   , 
      `log_int` text   , 
      `user_id` int   , 
      `intermediador_id` int   , 
      `dt_inclusao` datetime   , 
      `dt_alteracao` datetime   , 
      `orcamento_id` int   , 
      `nota_saida_id` int   , 
      `system_unit_id` int   , 
 PRIMARY KEY (id)) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci; 

CREATE TABLE pedido_estado( 
      `id`  INT  AUTO_INCREMENT    NOT NULL  , 
      `cod_erp` varchar  (2)   , 
      `descricao` char  (100)   , 
      `cor` varchar  (10)   , 
      `cor_texto` varchar  (10)   , 
 PRIMARY KEY (id)) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci; 

CREATE TABLE pedido_item( 
      `id`  INT  AUTO_INCREMENT    NOT NULL  , 
      `pedido_id` int   NOT NULL  , 
      `produto_id` int   NOT NULL  , 
      `item` varchar  (4)   , 
      `vlr_unitario` double   , 
      `vlr_item` double   , 
      `quantidade` double   , 
      `per_desconto` double   , 
      `vlr_desconto` double   , 
      `vlr_acrescimo` double   , 
      `vlr_total` double   , 
      `armazem_id` int   , 
      `tipo_movimentacao_id` int   , 
      `dt_inclusao` datetime   , 
      `dt_alteracao` datetime   , 
      `mes` varchar  (2)   , 
      `ano` varchar  (4)   , 
 PRIMARY KEY (id)) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci; 

CREATE TABLE produto( 
      `id`  INT  AUTO_INCREMENT    NOT NULL  , 
      `filial_id` int   , 
      `cod_erp` varchar  (30)   NOT NULL  , 
      `descricao` varchar  (100)   NOT NULL  , 
      `tipo` char  (2)   , 
      `um` char  (2)   , 
      `categoria_id` int   , 
      `sub_categoria_id` int   , 
      `fabricante_id` int   , 
      `armazem_id` int   , 
      `codigo_barras` varchar  (30)   , 
      `codigo_fabricante` char  (60)   , 
      `qtd_embalagem` double   , 
      `observacao` varchar  (200)   , 
      `foto` varchar  (200)   , 
      `status` char  (1)   , 
      `ncm` char  (20)   , 
      `origem` char  (2)   , 
      `peso_bruto` double   , 
      `peso` double   , 
      `marca` varchar  (20)   , 
      `te_id` int   , 
      `ts_id` int   , 
      `sinc` char  (1)   , 
      `ponto_pedido` double   , 
      `estoque_seguranca` double   , 
      `dt_ult_compra` date   , 
      `ult_preco` double   , 
      `informacoes_tecnicas` text   , 
      `dados_tecnicos` text   , 
      `system_unit_id` int   , 
      `dt_inclusao` datetime   , 
      `dt_alteracao` datetime   , 
 PRIMARY KEY (id)) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci; 

CREATE TABLE produto_caracteristica( 
      `id`  INT  AUTO_INCREMENT    NOT NULL  , 
      `caracteristica_id` int   NOT NULL  , 
      `produto_id` int   NOT NULL  , 
      `des_caracteristica` text   , 
      `dt_caracteristica` date   , 
      `vlr_caracteristica` double   , 
      `dt_alteracao` datetime   , 
      `dt_inclusao` datetime   , 
 PRIMARY KEY (id)) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci; 

CREATE TABLE regiao_cliente( 
      `id`  INT  AUTO_INCREMENT    NOT NULL  , 
      `cod_erp` varchar  (10)   , 
      `descricao` text   , 
      `dt_alteracao` datetime   , 
      `dt_inclusao` datetime   , 
      `cor` text   , 
 PRIMARY KEY (id)) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci; 

CREATE TABLE segmento( 
      `id`  INT  AUTO_INCREMENT    NOT NULL  , 
      `cod_erp` varchar  (6)   NOT NULL  , 
      `descricao` varchar  (200)   NOT NULL  , 
      `status` varchar  (1)   , 
      `reg_ativo` varchar  (1)   , 
      `dt_inclusao` datetime   , 
      `dt_alteracao` datetime   , 
 PRIMARY KEY (id)) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci; 

CREATE TABLE situacao_cadastral( 
      `id`  INT  AUTO_INCREMENT    NOT NULL  , 
      `descricao` varchar  (100)   NOT NULL  , 
      `motivo_bloqueio_id` int   , 
      `dt_alteracao` datetime   , 
      `dt_inclusao` datetime   , 
 PRIMARY KEY (id)) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci; 

CREATE TABLE step( 
      `id`  INT  AUTO_INCREMENT    NOT NULL  , 
      `grupo` char  (20)   , 
      `sequencia` int   , 
      `variavel` char  (2)   , 
      `descricao` char  (20)   , 
      `cor` char  (20)   , 
      `column_6` int   , 
 PRIMARY KEY (id)) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci; 

CREATE TABLE sub_categoria( 
      `id`  INT  AUTO_INCREMENT    NOT NULL  , 
      `categoria_id` int   NOT NULL  , 
      `cod_erp` varchar  (6)   NOT NULL  , 
      `descricao` varchar  (200)   NOT NULL  , 
      `status` char  (1)   , 
      `dt_inclusao` datetime   , 
      `dt_alteracao` datetime   , 
 PRIMARY KEY (id)) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci; 

CREATE TABLE supervisor( 
      `filial_id` int   , 
      `id`  INT  AUTO_INCREMENT    NOT NULL  , 
      `cod_erp` varchar  (6)   , 
      `system_users_id` int   , 
      `nome` varchar  (100)   NOT NULL  , 
      `nome_reduzido` varchar  (50)   , 
      `ddd` varchar  (3)   , 
      `telefone` varchar  (15)   , 
      `celular` varchar  (15)   , 
      `email` varchar  (100)   , 
      `status` char  (1)   , 
      `vendedor` varchar  (1)   , 
      `dashboard` char  (1)   , 
      `dt_alteracao` datetime   , 
      `dt_inclusao` datetime   , 
      `dt_nascmento` date   , 
      `system_unit_id` int   , 
      `desligado` char  (1)   , 
 PRIMARY KEY (id)) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci; 

CREATE TABLE supervisor_vendedor( 
      `id`  INT  AUTO_INCREMENT    NOT NULL  , 
      `vendedor_id` int   NOT NULL  , 
      `supervisor_id` int   NOT NULL  , 
      `sistema` char  (1)   , 
      `dt_alteracao` datetime   , 
      `dt_inclusao` datetime   , 
 PRIMARY KEY (id)) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci; 

CREATE TABLE tabela_preco( 
      `id`  INT  AUTO_INCREMENT    NOT NULL  , 
      `empresa_id` int   , 
      `filial_id` int   , 
      `cod_erp` varchar  (3)   NOT NULL  , 
      `descricao` varchar  (50)   NOT NULL  , 
      `status` char  (1)   , 
      `dt_inicio` date   , 
      `dt_fim` date   , 
      `dt_inclusao` datetime   , 
      `dt_alteracao` datetime   , 
      `utiliza` char  (1)   , 
      `system_unit_id` int   , 
 PRIMARY KEY (id)) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci; 

CREATE TABLE tabela_preco_item( 
      `id`  INT  AUTO_INCREMENT    NOT NULL  , 
      `item` int   , 
      `tabela_preco_id` int   NOT NULL  , 
      `produto_id` int   NOT NULL  , 
      `preco` double   , 
      `status` char  (1)   , 
      `dt_alteracao` datetime   , 
      `dt_inclusao` datetime   , 
 PRIMARY KEY (id)) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci; 

CREATE TABLE tipo_cliente( 
      `id`  INT  AUTO_INCREMENT    NOT NULL  , 
      `cod_erp` varchar  (6)   , 
      `descricao` varchar  (50)   , 
      `status` char  (1)   , 
 PRIMARY KEY (id)) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci; 

CREATE TABLE tipo_contato( 
      `id`  INT  AUTO_INCREMENT    NOT NULL  , 
      `cod_erp` varchar  (10)   , 
      `descricao` varchar  (50)   , 
      `dt_alteracao` datetime   , 
      `dt_inclusao` datetime   , 
 PRIMARY KEY (id)) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci; 

CREATE TABLE tipo_entrada_saida( 
      `id`  INT  AUTO_INCREMENT    NOT NULL  , 
      `filial_id` int   , 
      `empresa_id` int   , 
      `cod_erp` varchar  (10)   NOT NULL  , 
      `tipo` char  (1)   , 
      `descricao` varchar  (100)   NOT NULL  , 
      `finalidade` varchar  (100)   , 
      `status` char  (1)   , 
      `cfop` char  (4)   , 
      `dt_alteracao` datetime   , 
      `dt_inclusao` datetime   , 
      `system_unit_id` int   , 
 PRIMARY KEY (id)) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci; 

CREATE TABLE titulo_receber( 
      `id`  INT  AUTO_INCREMENT    NOT NULL  , 
      `dt_alteracao` datetime   , 
      `filial_id` int   , 
      `dt_inclusao` datetime   , 
      `cliente_id` int   NOT NULL  , 
      `vendedor_id` int   , 
      `natureza_id` int   , 
      `emissao` date   NOT NULL  , 
      `numero` char  (9)   NOT NULL  , 
      `parcela` char  (3)   NOT NULL  , 
      `prefixo` char  (3)   NOT NULL  , 
      `tipo` char  (3)   , 
      `saldo` double   , 
      `valor` double   , 
      `decrescimo` double   , 
      `acrescimo` double   , 
      `valor_juros` double   , 
      `perc_juros` double   , 
      `mora_dia` double   , 
      `taxa_multa` double   , 
      `dt_digitacao` date   , 
      `vencimento` date   NOT NULL  , 
      `venc_real` date   NOT NULL  , 
      `venc_orig` date   , 
      `pedido_id` int   , 
      `banco` char  (3)   , 
      `agencia` char  (10)   , 
      `conta` char  (20)   , 
      `nosso_numero` char  (50)   , 
      `id_cnab` char  (20)   , 
      `cod_barras` char  (50)   , 
      `lin_digitavel` varchar  (50)   , 
      `forma_pgto` char  (1)   , 
      `controle_bco` char  (02)   , 
      `dig_nosso_numero` char  (1)   , 
      `impresso` char  (1)   , 
      `origem` char  (10)   , 
      `historico` text   , 
      `usr_inclusao` char  (10)   , 
      `usr_alteracao` char  (10)   , 
      `reg_ativo` char  (1)   , 
      `baixa` date   , 
      `system_unit_id` int   , 
      `e1_recno` int   , 
      `nota_fiscal_id` int   , 
      `vias` int   , 
      `situacao` char  (1)   , 
 PRIMARY KEY (id)) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci; 

CREATE TABLE transportadora( 
      `id`  INT  AUTO_INCREMENT    NOT NULL  , 
      `filial_id` int   , 
      `cod_erp` char  (6)   , 
      `descricao` varchar  (100)   , 
      `status` char  (1)   , 
      `dt_inclusao` datetime   , 
      `dt_alteracao` datetime   , 
      `system_unit_id` int   , 
 PRIMARY KEY (id)) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci; 

CREATE TABLE venda_mes_cliente( 
      `id`  INT  AUTO_INCREMENT    NOT NULL  , 
      `filial_id` int   , 
      `empresa_id` int   , 
      `cliente_id` int   NOT NULL  , 
      `ano` char  (4)   NOT NULL  , 
      `janeiro` double     DEFAULT 0, 
      `fevereiro` double     DEFAULT 0, 
      `mes` char  (2)   , 
      `marco` double     DEFAULT 0, 
      `abril` double     DEFAULT 0, 
      `maio` double     DEFAULT 0, 
      `junho` double     DEFAULT 0, 
      `julho` double     DEFAULT 0, 
      `agosto` double     DEFAULT 0, 
      `setembro` double     DEFAULT 0, 
      `outubro` double     DEFAULT 0, 
      `novembro` double     DEFAULT 0, 
      `dezembro` double     DEFAULT 0, 
      `cliente_nome` varchar  (100)   , 
 PRIMARY KEY (id)) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci; 

CREATE TABLE venda_mes_produto( 
      `id`  INT  AUTO_INCREMENT    NOT NULL  , 
      `filial_id` int   , 
      `empresa_id` int   , 
      `produto_id` int   NOT NULL  , 
      `ano` char  (4)   NOT NULL  , 
      `janeiro` double     DEFAULT 0, 
      `fevereiro` double     DEFAULT 0, 
      `marco` double     DEFAULT 0, 
      `abril` double     DEFAULT 0, 
      `maio` double     DEFAULT 0, 
      `junho` double     DEFAULT 0, 
      `julho` double     DEFAULT 0, 
      `agosto` double     DEFAULT 0, 
      `setembro` double     DEFAULT 0, 
      `outubro` double     DEFAULT 0, 
      `novembro` double     DEFAULT 0, 
      `dezembro` double     DEFAULT 0, 
      `produto_nome` varchar  (100)   , 
 PRIMARY KEY (id)) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci; 

CREATE TABLE vendedor( 
      `id`  INT  AUTO_INCREMENT    NOT NULL  , 
      `filial_id` int   , 
      `cod_erp` varchar  (6)   , 
      `system_users_id` int   , 
      `nome` varchar  (100)   NOT NULL  , 
      `nome_reduzido` varchar  (50)   , 
      `ddd` varchar  (3)   , 
      `telefone` varchar  (15)   , 
      `celular` varchar  (15)   , 
      `email` varchar  (100)   , 
      `status` char  (1)   , 
      `vendedor` varchar  (1)   , 
      `dashboard` char  (1)   , 
      `dt_alteracao` datetime   , 
      `dt_inclusao` datetime   , 
      `dt_nascmento` date   , 
      `system_unit_id` int   , 
      `supervisor` char  (1)   , 
      `supervisor_id` int   , 
      `desligado` char  (1)   , 
 PRIMARY KEY (id)) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci; 

CREATE TABLE vendedor_atendimento( 
      `id`  INT  AUTO_INCREMENT    NOT NULL  , 
      `vendedor_id` int   NOT NULL  , 
      `dt_alteracao` datetime   , 
      `dt_inclusao` datetime   , 
      `inicial` time   , 
      `final` time   , 
      `tipo` char  (1)   , 
      `dias_semana` varchar  (20)   , 
 PRIMARY KEY (id)) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci; 

CREATE TABLE vendedor_cliente( 
      `id`  INT  AUTO_INCREMENT    NOT NULL  , 
      `vendedor_id` int   NOT NULL  , 
      `cliente_id` int   NOT NULL  , 
      `status` char  (1)   , 
 PRIMARY KEY (id)) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci; 

 
 ALTER TABLE cliente ADD UNIQUE (cod_erp);
 ALTER TABLE parametro ADD UNIQUE (filial_id);
 ALTER TABLE parametro ADD UNIQUE (parametro);
 ALTER TABLE produto ADD UNIQUE (cod_erp);
  
 ALTER TABLE atendimento ADD CONSTRAINT fk_atendimento_1 FOREIGN KEY (vendedor_id) references vendedor(id); 
ALTER TABLE atendimento ADD CONSTRAINT fk_atendimento_2 FOREIGN KEY (cliente_id) references cliente(id); 
ALTER TABLE atendimento ADD CONSTRAINT fk_atendimento_3 FOREIGN KEY (atendimento_tipo_id) references atendimento_tipo(id); 
ALTER TABLE calendario_orcamento ADD CONSTRAINT fk_calendario_orcamento_1 FOREIGN KEY (orcamento_id) references orcamento(id); 
ALTER TABLE categoria ADD CONSTRAINT fk_produto_grupo_2 FOREIGN KEY (filial_id) references filial(id); 
ALTER TABLE cliente ADD CONSTRAINT fk_cliente_filial FOREIGN KEY (filial_id) references filial(id); 
ALTER TABLE cliente ADD CONSTRAINT fk_cliente_municipio FOREIGN KEY (municipio_id) references municipio(id); 
ALTER TABLE cliente ADD CONSTRAINT fk_cliente_vendedor FOREIGN KEY (vendedor_id) references vendedor(id); 
ALTER TABLE cliente ADD CONSTRAINT fk_cliente_tipo FOREIGN KEY (tipo_cliente_id) references tipo_cliente(id); 
ALTER TABLE cliente ADD CONSTRAINT fk_cliente_segmento FOREIGN KEY (seguimento_id) references segmento(id); 
ALTER TABLE cliente ADD CONSTRAINT fk_cliente_condicao FOREIGN KEY (condicao_pagamento_id) references condicao_pagamento(id); 
ALTER TABLE cliente ADD CONSTRAINT fk_cliente_tabela_preco FOREIGN KEY (tabela_preco_id) references tabela_preco(id); 
ALTER TABLE cliente ADD CONSTRAINT fk_cliente_bloqueio FOREIGN KEY (motivo_bloqueio_id) references motivo_bloqueio(id); 
ALTER TABLE cliente ADD CONSTRAINT fk_cliente_regiao FOREIGN KEY (regiao_cliente_id) references regiao_cliente(id); 
ALTER TABLE cliente ADD CONSTRAINT fk_cliente_10 FOREIGN KEY (situacao_cadastral_id) references situacao_cadastral(id); 
ALTER TABLE cliente_acesso ADD CONSTRAINT fk_acesso_meu_cliente_cliente FOREIGN KEY (cliente_id) references cliente(id); 
ALTER TABLE cliente_atendimento ADD CONSTRAINT fk_atendimento_cliente_1 FOREIGN KEY (cliente_id) references cliente(id); 
ALTER TABLE cliente_atendimento ADD CONSTRAINT fk_atendimento_cliente_2 FOREIGN KEY (vendedor_id) references vendedor(id); 
ALTER TABLE cliente_atualizacao ADD CONSTRAINT fk_cliente_cliente FOREIGN KEY (cliente_id) references cliente(id); 
ALTER TABLE cliente_atualizacao ADD CONSTRAINT fk_cliente_atualizacao_2 FOREIGN KEY (situacao_cadastral_id) references situacao_cadastral(id); 
ALTER TABLE cliente_atualizacao ADD CONSTRAINT fk_cliente_atualizacao_3 FOREIGN KEY (atividade_principal_id) references cnae(id); 
ALTER TABLE cliente_atualizacao ADD CONSTRAINT fk_cliente_atualizacao_4 FOREIGN KEY (pais_id) references pais(id); 
ALTER TABLE cliente_cnae ADD CONSTRAINT fk_cliente_cnae FOREIGN KEY (cnae_id) references cnae(id); 
ALTER TABLE cliente_condicao ADD CONSTRAINT fk_cliente_condicao_1 FOREIGN KEY (condicao_pagamento_id) references condicao_pagamento(id); 
ALTER TABLE cliente_condicao ADD CONSTRAINT fk_cliente_condicao_2 FOREIGN KEY (cliente_id) references cliente(id); 
ALTER TABLE cliente_contato ADD CONSTRAINT fk_cliente_contato_1 FOREIGN KEY (cliente_id) references cliente(id); 
ALTER TABLE cliente_contato ADD CONSTRAINT fk_cliente_contato_2 FOREIGN KEY (tipo_contato_id) references tipo_contato(id); 
ALTER TABLE cliente_historico ADD CONSTRAINT fk_cliente_inativo_1 FOREIGN KEY (cliente_id) references cliente(id); 
ALTER TABLE cliente_vendedor_mes ADD CONSTRAINT fk_cliente_vendedor_mes_vendedor FOREIGN KEY (vendedor_id) references vendedor(id); 
ALTER TABLE estoque ADD CONSTRAINT fk_estoque_armazem FOREIGN KEY (armazem_id) references armazem(id); 
ALTER TABLE estoque ADD CONSTRAINT fk_estoque_produto FOREIGN KEY (produto_id) references produto(id); 
ALTER TABLE estoque ADD CONSTRAINT fk_estoque_filial FOREIGN KEY (filial_id) references filial(id); 
ALTER TABLE ficha_tecnica ADD CONSTRAINT fk_ficha_tecnica_1 FOREIGN KEY (produto_id) references produto(id); 
ALTER TABLE fornecedor ADD CONSTRAINT fk_fornecedor_filial FOREIGN KEY (filial_id) references filial(id); 
ALTER TABLE meta_vendedor_categoria ADD CONSTRAINT fk_meta_vendedor_categoria_1 FOREIGN KEY (meta_vendedor_mes_id) references meta_vendedor_mes(id); 
ALTER TABLE meta_vendedor_categoria ADD CONSTRAINT fk_meta_vendedor_categoria_2 FOREIGN KEY (categoria_id) references categoria(id); 
ALTER TABLE meta_vendedor_mes ADD CONSTRAINT fk_meta_vendedor_mes_1 FOREIGN KEY (vendedor_id) references vendedor(id); 
ALTER TABLE municipio ADD CONSTRAINT fk_municipio_uf FOREIGN KEY (estado_id) references estado(id); 
ALTER TABLE negociacao ADD CONSTRAINT fk_negociacao_1 FOREIGN KEY (cliente_id) references cliente(id); 
ALTER TABLE negociacao ADD CONSTRAINT fk_negociacao_2 FOREIGN KEY (vendedor_id) references vendedor(id); 
ALTER TABLE negociacao ADD CONSTRAINT fk_negociacao_4 FOREIGN KEY (atendimento_id) references atendimento(id); 
ALTER TABLE negociacao ADD CONSTRAINT fk_negociacao_3 FOREIGN KEY (atendimento_tipo_id) references atendimento_tipo(id); 
ALTER TABLE negociacao_titulo_receber ADD CONSTRAINT fk_negociacao_titulo_receber_1 FOREIGN KEY (negociacao_id) references negociacao(id); 
ALTER TABLE negociacao_titulo_receber ADD CONSTRAINT fk_negociacao_titulo_receber_2 FOREIGN KEY (titulo_receber_id) references titulo_receber(id); 
ALTER TABLE nota_entrada ADD CONSTRAINT fk_nota_entrada_fornecedor FOREIGN KEY (fornecedor_id) references fornecedor(id); 
ALTER TABLE nota_entrada ADD CONSTRAINT fk_nota_entrada_cliente FOREIGN KEY (cliente_id) references cliente(id); 
ALTER TABLE nota_entrada ADD CONSTRAINT fk_nota_entrada_vendedor FOREIGN KEY (vendedor1_id) references vendedor(id); 
ALTER TABLE nota_entrada_item ADD CONSTRAINT fk_nota_entrada_nota FOREIGN KEY (nota_entrada_id) references nota_entrada(id); 
ALTER TABLE nota_saida ADD CONSTRAINT fk_nota_saida_vendedor1 FOREIGN KEY (vendedor1_id) references vendedor(id); 
ALTER TABLE nota_saida ADD CONSTRAINT fk_nota_saida_vendedor2 FOREIGN KEY (vendedor2_id) references vendedor(id); 
ALTER TABLE nota_saida ADD CONSTRAINT fk_nota_saida_filial FOREIGN KEY (filial_id) references filial(id); 
ALTER TABLE nota_saida ADD CONSTRAINT fk_nota_saida_cliente FOREIGN KEY (cliente_id) references cliente(id); 
ALTER TABLE nota_saida ADD CONSTRAINT fk_nota_saida_fornecedor FOREIGN KEY (fornecedor_id) references fornecedor(id); 
ALTER TABLE nota_saida_item ADD CONSTRAINT fk_nota_saida_item_nota FOREIGN KEY (nota_saida_id) references nota_saida(id); 
ALTER TABLE nota_saida_item ADD CONSTRAINT fk_nota_saida_item_produto FOREIGN KEY (produto_id) references produto(id); 
ALTER TABLE nota_saida_item ADD CONSTRAINT fk_nota_saida_item_tes FOREIGN KEY (tes_id) references tipo_entrada_saida(id); 
ALTER TABLE nota_saida_item ADD CONSTRAINT fk_nota_saida_item_cliente FOREIGN KEY (cliente_id) references cliente(id); 
ALTER TABLE nota_saida_item ADD CONSTRAINT fk_nota_saida_item_vendedor1 FOREIGN KEY (vendedor1_id) references vendedor(id); 
ALTER TABLE nota_saida_item ADD CONSTRAINT fk_nota_saida_item_vendedor2 FOREIGN KEY (vendedor2_id) references vendedor(id); 
ALTER TABLE notasaida_xml ADD CONSTRAINT fk_notasaida_xml FOREIGN KEY (nota_saida_id) references nota_saida(id); 
ALTER TABLE orcamento ADD CONSTRAINT fk_orcamento_cliente FOREIGN KEY (cliente_id) references cliente(id); 
ALTER TABLE orcamento ADD CONSTRAINT fk_orcamento_tabela_preco FOREIGN KEY (tabela_preco_id) references tabela_preco(id); 
ALTER TABLE orcamento ADD CONSTRAINT fk_orcamento_pedido FOREIGN KEY (pedido_id) references pedido(id); 
ALTER TABLE orcamento ADD CONSTRAINT fk_orcamento_uf FOREIGN KEY (estado_id) references estado(id); 
ALTER TABLE orcamento ADD CONSTRAINT fk_orcamento_condicao_pagamento FOREIGN KEY (condicao_pagamento_id) references condicao_pagamento(id); 
ALTER TABLE orcamento ADD CONSTRAINT fk_orcamento_estado_orcamento FOREIGN KEY (orcamento_estado_id) references orcamento_estado(id); 
ALTER TABLE orcamento ADD CONSTRAINT fk_orcamento_municipio FOREIGN KEY (municipio_id) references municipio(id); 
ALTER TABLE orcamento ADD CONSTRAINT fk_orcamento_vendedor FOREIGN KEY (vendedor_id) references vendedor(id); 
ALTER TABLE orcamento_historico ADD CONSTRAINT fk_orcamento_historico_orcamento FOREIGN KEY (orcamento_id) references orcamento(id); 
ALTER TABLE orcamento_historico ADD CONSTRAINT fk_orcamento_historico_estado FOREIGN KEY (orcamento_estado_id) references orcamento_estado(id); 
ALTER TABLE orcamento_historico ADD CONSTRAINT fk_orcamento_historico_proximo_estado FOREIGN KEY (orcamento_proximo_estado_id) references orcamento_estado(id); 
ALTER TABLE orcamento_item ADD CONSTRAINT fk_orcaemento_item_orcamento FOREIGN KEY (orcamento_id) references orcamento(id); 
ALTER TABLE orcamento_item ADD CONSTRAINT fk_orcaemento_item_produto FOREIGN KEY (produto_id) references produto(id); 
ALTER TABLE orcamento_proximo_estado ADD CONSTRAINT fk_orcamento_proximo_estado_1 FOREIGN KEY (orcamento_estado_id) references orcamento_estado(id); 
ALTER TABLE orcamento_proximo_estado ADD CONSTRAINT fk_orcamento_proximo_estado_proximo FOREIGN KEY (proximo_estado_id) references orcamento_estado(id); 
ALTER TABLE parametro ADD CONSTRAINT fk_parametro_filial FOREIGN KEY (filial_id) references filial(id); 
ALTER TABLE pedido ADD CONSTRAINT fk_pedido_cliente FOREIGN KEY (cliente_id) references cliente(id); 
ALTER TABLE pedido ADD CONSTRAINT fk_pedido_pedido_estado FOREIGN KEY (pedido_estado_id) references pedido_estado(id); 
ALTER TABLE pedido ADD CONSTRAINT fk_pedido_condicao_pagamento FOREIGN KEY (condicao_pagamento_id) references condicao_pagamento(id); 
ALTER TABLE pedido ADD CONSTRAINT fk_pedido_trasnportadora FOREIGN KEY (transportadora_id) references transportadora(id); 
ALTER TABLE pedido ADD CONSTRAINT fk_pedido_cliente_entrega FOREIGN KEY (cliente_entrega_id) references cliente(id); 
ALTER TABLE pedido ADD CONSTRAINT fk_pedido_vendedor1 FOREIGN KEY (vendedor1_id) references vendedor(id); 
ALTER TABLE pedido ADD CONSTRAINT fk_pedido_vendedor2 FOREIGN KEY (vendedor2_id) references vendedor(id); 
ALTER TABLE pedido ADD CONSTRAINT fk_pedido_orcamento FOREIGN KEY (orcamento_id) references orcamento(id); 
ALTER TABLE pedido ADD CONSTRAINT fk_pedido_nota_fiscal FOREIGN KEY (nota_saida_id) references nota_saida(id); 
ALTER TABLE pedido_item ADD CONSTRAINT fk_pedido_item_pedido FOREIGN KEY (pedido_id) references pedido(id); 
ALTER TABLE pedido_item ADD CONSTRAINT fk_pedido_item_produto FOREIGN KEY (produto_id) references produto(id); 
ALTER TABLE pedido_item ADD CONSTRAINT fk_pedido_item_armazem FOREIGN KEY (armazem_id) references armazem(id); 
ALTER TABLE produto ADD CONSTRAINT fk_produto_filial FOREIGN KEY (filial_id) references filial(id); 
ALTER TABLE produto ADD CONSTRAINT fk_produto_categoria FOREIGN KEY (categoria_id) references categoria(id); 
ALTER TABLE produto ADD CONSTRAINT fk_produto_armazem FOREIGN KEY (armazem_id) references armazem(id); 
ALTER TABLE produto ADD CONSTRAINT fk_produto_fabricante FOREIGN KEY (fabricante_id) references fabricante(id); 
ALTER TABLE produto ADD CONSTRAINT fk_produto_sub_categoria FOREIGN KEY (sub_categoria_id) references sub_categoria(id); 
ALTER TABLE produto ADD CONSTRAINT fk_produto_te FOREIGN KEY (te_id) references tipo_entrada_saida(id); 
ALTER TABLE produto ADD CONSTRAINT fk_produto_ts FOREIGN KEY (ts_id) references tipo_entrada_saida(id); 
ALTER TABLE produto_caracteristica ADD CONSTRAINT fk_produto_caracteristica_1 FOREIGN KEY (caracteristica_id) references caracteristica(id); 
ALTER TABLE produto_caracteristica ADD CONSTRAINT fk_produto_caracteristica_2 FOREIGN KEY (produto_id) references produto(id); 
ALTER TABLE situacao_cadastral ADD CONSTRAINT fk_situacao_cadastral_1 FOREIGN KEY (motivo_bloqueio_id) references motivo_bloqueio(id); 
ALTER TABLE sub_categoria ADD CONSTRAINT fk_sub_categoria_1 FOREIGN KEY (categoria_id) references categoria(id); 
ALTER TABLE supervisor_vendedor ADD CONSTRAINT fk_supervisor_vendedor_1 FOREIGN KEY (vendedor_id) references vendedor(id); 
ALTER TABLE supervisor_vendedor ADD CONSTRAINT fk_supervisor_vendedor_2 FOREIGN KEY (supervisor_id) references supervisor(id); 
ALTER TABLE tabela_preco_item ADD CONSTRAINT fk_tabela_preco_item_produto FOREIGN KEY (produto_id) references produto(id); 
ALTER TABLE tabela_preco_item ADD CONSTRAINT fk_tabela_preco_item_tabela FOREIGN KEY (tabela_preco_id) references tabela_preco(id); 
ALTER TABLE titulo_receber ADD CONSTRAINT fk_titulo_receber_cliente FOREIGN KEY (cliente_id) references cliente(id); 
ALTER TABLE titulo_receber ADD CONSTRAINT fk_titulo_receber_vendedor FOREIGN KEY (vendedor_id) references vendedor(id); 
ALTER TABLE titulo_receber ADD CONSTRAINT fk_titulo_receber_filial FOREIGN KEY (filial_id) references filial(id); 
ALTER TABLE titulo_receber ADD CONSTRAINT fk_titulo_receber_pedido FOREIGN KEY (pedido_id) references pedido(id); 
ALTER TABLE titulo_receber ADD CONSTRAINT fk_titulo_receber_nota_fiscal FOREIGN KEY (nota_fiscal_id) references nota_saida(id); 
ALTER TABLE venda_mes_cliente ADD CONSTRAINT fk_venda_mes_cliente_1 FOREIGN KEY (cliente_id) references cliente(id); 
ALTER TABLE venda_mes_produto ADD CONSTRAINT fk_venda_mes_produto_1 FOREIGN KEY (produto_id) references produto(id); 
ALTER TABLE vendedor ADD CONSTRAINT fk_vendedor_filial FOREIGN KEY (filial_id) references filial(id); 
ALTER TABLE vendedor_atendimento ADD CONSTRAINT fk_vendedor_atendimento_1 FOREIGN KEY (vendedor_id) references vendedor(id); 
ALTER TABLE vendedor_cliente ADD CONSTRAINT fk_cliente_vendedor_1 FOREIGN KEY (vendedor_id) references vendedor(id); 
ALTER TABLE vendedor_cliente ADD CONSTRAINT fk_cliente_vendedor_2 FOREIGN KEY (cliente_id) references cliente(id); 

 CREATE VIEW cliente_atendido_mes AS     SELECT DISTINCT
        nota_saida.cliente_id as cliente_id,
        nota_saida.vendedor1_id as vendedor1,
        nota_saida.vendedor2_id as vendedor2,
        nota_saida.mes as mes,
        nota_saida.ano as ano
    FROM nota_saida
    WHERE nota_saida.numero_titulo <> '' 
     AND nota_saida.tipo = 'N'
     AND nota_saida.reg_ativo = 'S'  
     AND nota_saida.especie_fiscal = 'SPED'
; 

CREATE VIEW cliente_indicadores AS select distinct 
cliente.id  as id 
,cliente.cod_erp as cliente_codigo
,cliente.fantasia as cliente_fantasia
,municipio.descricao as cliente_municipio
,estado.sigla as cliente_estado
,cliente.status as cliente_status
,vendedor.id as vendedor_id
,vendedor.cod_erp as vendedor_codigo
,vendedor.nome as vendedor_nome
,cliente.data_cadastro as cliente_data_cadastro
,cliente.primeira_compra as cliente_primeira_compra
,cliente.ultima_visita as cliente_ultima_compra
,DATEDIFF(CURDATE(), cliente.ultima_visita) as dias_sem_compra
from cliente 
join vendedor on vendedor.id = cliente.vendedor_id
join municipio on municipio.id = cliente.municipio_id
join estado on estado.id = municipio.estado_id;; 

CREATE VIEW cliente_notafiscal AS SELECT 
    nota_saida.id as "id",
    nota_saida.ano as "ano",
    nota_saida.mes as "mes",
    nota_saida.nota_fiscal as "nota_fiscal",
    nota_saida.serie_fiscal as "serie_fiscal",
    nota_saida.especie_fiscal as "especie_fiscal",
    nota_saida.condicao_id as "condicao_id",
    nota_saida.dt_emissao as "dt_emissao",
    nota_saida.vlr_bruto as "vlr_bruto",
    nota_saida.vlr_mercadoria as "vlr_mercadoria",
    nota_saida.vlr_desconto as "vlr_desconto",
    nota_saida.vlr_comodato as "vlr_comodato",
    nota_saida.vlr_itens as "vlr_itens",
    nota_saida.vlr_devolucao as "vlr_devolucao",
    nota_saida.vlr_frete as "vlr_frete",
    nota_saida.vendedor1_id as "vendedor1_id",
    nota_saida.comodato as "comodato",
    cliente.id as "cliente_id",
    cliente.cod_erp as "cod_erp",
    cliente.razao as "razao",
    cliente.fantasia as "fantasia",
    vendedor.id as "vendedor_id",
    vendedor.cod_erp as "cod_erp_vendedor",
    vendedor.nome as "nome",
    vendedor.nome_reduzido as "nome_reduzido",
    datediff(curdate(), nota_saida.dt_emissao) as "dias"
FROM 
    nota_saida, 
    cliente, 
    vendedor
WHERE 
    nota_saida.cliente_id = cliente.id AND 
    nota_saida.vendedor1_id = vendedor.id AND
    nota_saida.reg_ativo = 'S' 
ORDER BY dias,ano,mes desc;; 

CREATE VIEW cliente_positivado AS SELECT DISTINCT 
	1 as id
	,nota_saida.ano as ano
	,nota_saida.mes as mes
	,nota_saida.cliente_id as cliente_id
	,nota_saida.vendedor1_id as vendedor_id
	,meta_vendedor_mes.numero_cliente as objetivo_numero_cliente
 FROM nota_saida
 left join meta_vendedor_mes on ( 
    meta_vendedor_mes.vendedor_id = nota_saida.vendedor1_id and 
    meta_vendedor_mes.ano = nota_saida.ano and 
    meta_vendedor_mes.mes = nota_saida.mes ) 
 join cliente on (cliente.id = nota_saida.cliente_id)
 join vendedor on (
    nota_saida.vendedor1_id  = vendedor.id 
    and vendedor.status = 'A'
    ) 
  where nota_saida.tipo = 'N'
  and nota_saida.reg_ativo = 'S'; 

CREATE VIEW clienteseekview AS select 
cod_erp as cod_erp
,razao as razao
,fantasia as fantasia
,cnpj_cpf as cnpj_cpf
,telefone1 as telefone
,email as email
,status as situacao
,vendedor_id as vendedor_id
,id as cliente_id
,municipio_id as municipio_id
,ultima_visita as ultima_visita
,data_cadastro as data_cadastro
,condicao_pagamento_id as condicao_pagamento_id
,tabela_preco_id as tabela_preco_id
,seguimento_id as seguimento_id
from cliente
where status <> 'B' and cod_erp <> ''; 

CREATE VIEW cliente_top10 AS SELECT 
    1 AS id
    ,cliente_id AS cliente_id
    ,vendedor1_id AS vendedor1_id
    ,mes AS mes
    ,ano AS ano
    ,count(nota_fiscal) as nota_fiscal
    ,sum(vlr_itens) as vlr_total
FROM nota_saida 
join vendedor on (
    vendedor.id = vendedor1_id
    AND vendedor.status = 'A'
)
WHERE vlr_comodato = 0
GROUP BY cliente_id ,vendedor1_id ,mes ,ano
ORDER BY vlr_total  DESC 
; 

CREATE VIEW mvc AS select cliente.id as id
,cliente.filial_id as filial
,cliente.cod_erp as codigo
,cliente.status as situacao
,cliente.razao as razao
,cliente.fantasia as fantasia
,cliente.primeira_compra as primeira_compra
,cliente.ultima_compra as ultima_compra
,cliente.ultima_visita as ultima_visita
,cliente.ultimo_atendimento as ultimo_atendimento
,cliente.dt_bloqueio as dt_bloqueio
,cliente.motivo_bloqueio as  motivo_bloqueio
,cliente.carteira as carteira
,municipio.id as municipio_id
,municipio.descricao as municipio_descricao
,estado.id as estado_id
,estado.sigla as estado_sigla
,vendedor.id as vendedor_id
,vendedor.nome as vendedor_nome
,vendedor.nome_reduzido as vendedor_reduzido
,vendedor.system_users_id as system_user_id
,regiao_cliente.id as regiao_id
,regiao_cliente.cod_erp as regiao_codigo
,regiao_cliente.descricao as regiao_descricao
,lpad(date_format(curdate(),'%c'), 2, '0') as mes  
,date_format(curdate(),'%Y') as ano  
,(curdate() - interval 3 month) as tres
,datediff(curdate(), cliente.ultima_compra) as dias
from cliente
join municipio on (cliente.municipio_id = municipio.id)
join estado on (municipio.estado_id = estado.id)
left join vendedor on (cliente.vendedor_id = vendedor.id)
left join regiao_cliente on (cliente.regiao_cliente_id = regiao_cliente.id )
where cliente.cod_erp not in('00000002','00000001') AND cliente.reg_ativo = 'S'; 

CREATE VIEW pivot_cliente_atendido_mes AS SELECT 
    cliente_atendido_mes.vendedor1 as vendedor1_id
    ,cliente_atendido_mes.vendedor2 as vendedor2_id
    ,cliente_atendido_mes.ano as ano 
	,IF(cliente_atendido_mes.mes = '01', COUNT(cliente_id), 0) AS janeiro 
	,IF(cliente_atendido_mes.mes = '02', COUNT(cliente_id), 0) AS fevereiro
	,IF(cliente_atendido_mes.mes = '03', COUNT(cliente_id), 0) AS marco
	,IF(cliente_atendido_mes.mes = '04', COUNT(cliente_id), 0) AS abril 
	,IF(cliente_atendido_mes.mes = '05', COUNT(cliente_id), 0) AS maio
	,IF(cliente_atendido_mes.mes = '06', COUNT(cliente_id), 0) AS junho 
	,IF(cliente_atendido_mes.mes = '07', COUNT(cliente_id), 0) AS julho 
	,IF(cliente_atendido_mes.mes = '08', COUNT(cliente_id), 0) AS agosto 
	,IF(cliente_atendido_mes.mes = '09', COUNT(cliente_id), 0) AS setembro 
	,IF(cliente_atendido_mes.mes = '10', COUNT(cliente_id), 0) AS outubro
	,IF(cliente_atendido_mes.mes = '11', COUNT(cliente_id), 0) AS novembro 
	,IF(cliente_atendido_mes.mes = '12', COUNT(cliente_id), 0) AS dezembro 
 FROM cliente_atendido_mes
 GROUP BY vendedor1,vendedor2,ano;; 

CREATE VIEW pivot_venda_mes_cliente AS SELECT 
	cliente.id as cliente_id 
	,cliente.vendedor_id as cliente_vendedor_id
    ,nota_saida.vendedor1_id as nota_saida_vendedor_id
	,cliente.razao as cliente_nome  
    ,venda_ano.ano as ano 
	,SUM(IF(nota_saida.mes = '01', (nota_saida_item.vlr_bruto - nota_saida_item.vlr_dev), 0)) AS janeiro 
	,SUM(IF(nota_saida.mes = '02', (nota_saida_item.vlr_bruto - nota_saida_item.vlr_dev), 0)) AS fevereiro
	,SUM(IF(nota_saida.mes = '03', (nota_saida_item.vlr_bruto - nota_saida_item.vlr_dev), 0)) AS marco
	,SUM(IF(nota_saida.mes = '04', (nota_saida_item.vlr_bruto - nota_saida_item.vlr_dev), 0)) AS abril 
	,SUM(IF(nota_saida.mes = '05', (nota_saida_item.vlr_bruto - nota_saida_item.vlr_dev), 0)) AS maio
	,SUM(IF(nota_saida.mes = '06', (nota_saida_item.vlr_bruto - nota_saida_item.vlr_dev), 0)) AS junho 
	,SUM(IF(nota_saida.mes = '07', (nota_saida_item.vlr_bruto - nota_saida_item.vlr_dev), 0)) AS julho 
	,SUM(IF(nota_saida.mes = '08', (nota_saida_item.vlr_bruto - nota_saida_item.vlr_dev), 0)) AS agosto 
	,SUM(IF(nota_saida.mes = '09', (nota_saida_item.vlr_bruto - nota_saida_item.vlr_dev), 0)) AS setembro 
	,SUM(IF(nota_saida.mes = '10', (nota_saida_item.vlr_bruto - nota_saida_item.vlr_dev), 0)) AS outubro
	,SUM(IF(nota_saida.mes = '11', (nota_saida_item.vlr_bruto - nota_saida_item.vlr_dev), 0)) AS novembro 
	,SUM(IF(nota_saida.mes = '12', (nota_saida_item.vlr_bruto - nota_saida_item.vlr_dev), 0)) AS dezembro 
 FROM view_ano_base_cliente venda_ano
 left join nota_saida on (nota_saida.cliente_id = venda_ano.cliente_id 
     AND venda_ano.ano = nota_saida.ano
     AND nota_saida.numero_titulo <> '' 
     AND nota_saida.tipo = 'N'
     AND nota_saida.especie_fiscal = 'SPED'
     AND nota_saida.reg_ativo = 'S'  
     )
 left join nota_saida_item on (nota_saida_item.nota_saida_id = nota_saida.id  AND nota_saida_item.reg_ativo = 'S')
 JOIN cliente on (cliente.id = nota_saida.cliente_id )
 JOIN vendedor on (nota_saida.vendedor1_id = vendedor.id )
 group by cliente_id,nota_saida.vendedor1_id,ano  
; 

CREATE VIEW pivot_vendas AS SELECT 
	vendedor.id as vendedor_id 
    ,vendedor.cod_erp as vendedor_cod_erp 
    ,vendedor.nome as vendedor_nome 
    ,venda_ano.cliente_id as cliente_id
    ,cliente.cod_erp as cliente_cod_erp 
    ,cliente.razao as cliente_razao 
    ,venda_ano.ano as ano 
	,SUM(IF(venda_mes.mes = '01', valor, 0)) AS JANEIRO 
	,SUM(IF(venda_mes.mes = '02', valor, 0)) AS FEVEREIRO 
	,SUM(IF(venda_mes.mes = '03', valor, 0)) AS MARCO 
	,SUM(IF(venda_mes.mes = '04', valor, 0)) AS ABRIL 
	,SUM(IF(venda_mes.mes = '05', valor, 0)) AS MAIO 
	,SUM(IF(venda_mes.mes = '06', valor, 0)) AS JUNHO 
	,SUM(IF(venda_mes.mes = '07', valor, 0)) AS JULHO 
	,SUM(IF(venda_mes.mes = '08', valor, 0)) AS AGOSTO 
	,SUM(IF(venda_mes.mes = '09', valor, 0)) AS SETEMBRO 
	,SUM(IF(venda_mes.mes = '10', valor, 0)) AS OUTUBRO 
	,SUM(IF(venda_mes.mes = '11', valor, 0)) AS NOVEMBRO 
	,SUM(IF(venda_mes.mes = '12', valor, 0)) AS DEZEMBRO 
 FROM venda_mes_cliente venda_ano
 join cliente on (cliente.id = venda_ano.cliente_id)
 join vendedor on (vendedor.id = cliente.vendedor_id)
 left join vendas_ano_mes venda_mes on (venda_mes.cliente_id = venda_ano.cliente_id and venda_mes.ano = venda_ano.ano)
    Group by vendedor_id, vendedor_cod_erp ,vendedor_nome ,cliente_cod_erp ,cliente_razao ,ano; 

CREATE VIEW vendas_ano_mes AS select vendedor_nota_fiscal.vendedor_id as vendedor_id 
	,vendedor_cod_erp as vendedor_cod_erp 
	,vendedor_nome as vendedor_nome 
	,cliente.cod_erp as cliente_cod_erp 
	,cliente.razao as cliente_razao 
	,cliente.id as cliente_id
	,ano as ano 
	,mes as mes 
	,sum(nota_saida_vlr_itens) AS valor
	from vendedor_nota_fiscal
    JOIN cliente ON (nota_saida_cliente_id = cliente.id ) 
GROUP BY vendedor_nota_fiscal.vendedor_id,vendedor_cod_erp, vendedor_nome,cliente.id , cliente.cod_erp, cliente.razao, ano, mes; 

CREATE VIEW vendas_vendedor_mes AS SELECT sum(vlr_mercadoria) as vlr_mercadoria 
        ,sum(vlr_bruto) as vlr_bruto
        ,sum(vlr_devolucao) as vlr_devolucao 
        ,sum(vlr_comodato) as vlr_comodato 
        ,sum(vlr_bruto) - sum(vlr_devolucao)  as vlr_liquido
        ,nota_saida.mes as mes 
        ,nota_saida.ano as ano 
        ,especie_fiscal as especie_fiscal
        ,vendedor1_id as vendedor1_id 
        ,vendedor.status as vendedor1_status
        ,nota_saida.reg_ativo as reg_ativo
        FROM nota_saida 
        JOIN vendedor on( vendedor.id = nota_saida.vendedor1_id  )
        JOIN cliente  on( cliente.id = nota_saida.cliente_id)
        where nota_saida.numero_titulo <> '' 
        AND nota_saida.tipo = 'N'
        group by ano,mes,vendedor1_id,especie_fiscal,vendedor1_id,vendedor1_status,reg_ativo;; 

CREATE VIEW venda_vendedor_produto AS select distinct nota_saida.id  as id 
,nota_saida.nota_fiscal as nota_fiscal
,nota_saida.serie_fiscal as serie_fiscal
,nota_saida.dt_emissao as dt_emissao
,nota_saida.mes as mes
,nota_saida.ano as ano
,nota_saida.tipo as tipo
,vendedor.cod_erp as vendedor_cod
,vendedor.nome as vendedor_nome
,cliente.id as cliente_id
,cliente.cod_erp as cliente_codigo
,cliente.razao as cliente_razao
,cliente.fantasia as cliente_fantasia
,municipio.descricao as cliente_municipio
,estado.sigla as cliente_estado
,produto.cod_erp as produto_codigo
,produto.descricao as produto_descricao
,categoria.cod_erp as produto_categoria
,categoria.descricao as categoria_descricao
,sub_categoria.cod_erp as produto_sub_categoria
,sub_categoria.descricao as sub_categoria_descricao
,nota_saida_item.vlr_tabela as vlr_tabela
,nota_saida_item.qtd as quantidade
,nota_saida_item.vlr_unitario as vlr_unitario
,nota_saida_item.vlr_desconto as vlr_desconto
,nota_saida_item.vlr_total as vlr_total
from nota_saida
join vendedor on vendedor.id = nota_saida.vendedor1_id
join cliente on cliente.id = nota_saida.cliente_id
join municipio on municipio.id = cliente.municipio_id
join estado on estado.id = municipio.estado_id
join nota_saida_item on nota_saida_item.nota_saida_id = nota_saida.id
join produto on produto.id = nota_saida_item.produto_id
join categoria on categoria.id = produto.categoria_id
join sub_categoria on sub_categoria.id = produto.sub_categoria_id
where nota_saida.reg_ativo = 'S' and nota_saida.numero_titulo <> ''
; 

CREATE VIEW vendedor_nota_fiscal AS SELECT 
    vendedor.id as vendedor_id 
    ,vendedor.cod_erp as vendedor_cod_erp 
    ,vendedor.nome as vendedor_nome 
    ,nota_saida.ano as ano 
    ,nota_saida.mes as mes 
	,nota_saida.cliente_id as nota_saida_cliente_id
    ,nota_saida.vlr_itens AS nota_saida_vlr_itens
    FROM vendedor 
    JOIN nota_saida ON(nota_saida.vendedor1_id = vendedor.id and nota_saida.numero_titulo <> '' and nota_saida.reg_ativo = 'S'  ) 
; 

CREATE VIEW view_ano_base AS select distinct ano as ano
, 0 as 01
, 0 as 02
, 0 as 03
, 0 as 04
, 0 as 05
, 0 as 06
, 0 as 07
, 0 as 08
, 0 as 09
, 0 as 10
, 0 as 11
, 0 as 12
from nota_saida_item;; 

CREATE VIEW view_ano_base_cliente AS SELECT cliente.id as cliente_id
,view_ano_base.ano as ano
,view_ano_base.01 as 01
,view_ano_base.02 as 02
,view_ano_base.03 as 03
,view_ano_base.04 as 04
,view_ano_base.05 as 05
,view_ano_base.06 as 06
,view_ano_base.07 as 07
,view_ano_base.08 as 08
,view_ano_base.09 as 09
,view_ano_base.10 as 10
,view_ano_base.11 as 11
,view_ano_base.12 as 12
FROM view_ano_base,cliente;; 

CREATE VIEW view_base_cliente AS select distinct 
cliente_id as cliente_id
,vendedor_id as vendedor_id
,ano as ano
,mes as mes
,id as nota_fiscal_id
from view_base_venda;; 

CREATE VIEW view_base_cliente_mes AS select DISTINCT 
cliente_id as cliente_id
,vendedor_id as vendedor_id
,ano as ano
,mes as mes
,count(nota_fiscal_id) as nr_notas
from view_base_cliente
group by cliente_id
,vendedor_id
,ano
,mes; 

CREATE VIEW view_base_venda AS SELECT 
nota_saida.id as id
,nota_saida.ano as ano
,nota_saida.mes as mes
,nota_saida_item.item as nota_saida_item
,nota_saida_item.cfop as cfop
,nota_saida_item.vlr_total as vlr_total
,nota_saida_item.vlr_bruto as vlr_bruto
,nota_saida_item.vlr_dev as vlr_dev
,categoria.id as categoria_id
,produto.id as produto_id
,cliente.id as cliente_id
,nota_saida.vendedor1_id as vendedor_id 
,cliente.vendedor_id as cliente_vendedor_id 
FROM nota_saida 
join nota_saida_item on (nota_saida_item.nota_saida_id = nota_saida.id 
    and nota_saida_item.reg_ativo = 'S')
join produto on (nota_saida_item.produto_id = produto.id)
join categoria on (produto.categoria_id = categoria.id)
join cliente on (nota_saida.cliente_id = cliente.id)
WHERE nota_saida.tipo = 'N'
    and nota_saida.numero_titulo <> ''
    and nota_saida.reg_ativo = 'S'  
    and nota_saida.especie_fiscal = 'SPED'


; 

CREATE VIEW view_base_venda_categoria AS     select 
    cliente_id as cliente_id
    ,categoria_id as categoria_id
    ,vendedor_id as vendedor_id
    ,ano as ano
    ,IF(mes = '01', vlr_total, 0) AS janeiro 
    ,IF(mes = '02', vlr_total, 0) AS fevereiro
    ,IF(mes = '03', vlr_total, 0) AS marco
    ,IF(mes = '04', vlr_total, 0) AS abril 
    ,IF(mes = '05', vlr_total, 0) AS maio
    ,IF(mes = '06', vlr_total, 0) AS junho 
    ,IF(mes = '07', vlr_total, 0) AS julho 
    ,IF(mes = '08', vlr_total, 0) AS agosto 
    ,IF(mes = '09', vlr_total, 0) AS setembro 
    ,IF(mes = '10', vlr_total, 0) AS outubro
    ,IF(mes = '11', vlr_total, 0) AS novembro 
    ,IF(mes = '12', vlr_total, 0) AS dezembro 
    from view_base_venda; 

CREATE VIEW view_base_venda_categoria_ano AS select
cliente_id as cliente_id
,categoria_id as categoria_id
,vendedor_id as vendedor_id
,ano as ano
,sum(janeiro) AS janeiro 
,sum(fevereiro) AS fevereiro
,sum(marco) AS marco
,sum(abril) AS abril 
,sum(maio) AS maio
,sum(junho) AS junho 
,sum(julho) AS julho 
,sum(agosto) AS agosto 
,sum(setembro) AS setembro 
,sum(outubro) AS outubro
,sum(novembro) AS novembro 
,sum(dezembro) AS dezembro 
from view_base_venda_categoria
group by cliente_id,categoria_id,vendedor_id,ano;; 

CREATE VIEW view_base_venda_cliente AS     select 
    cliente_id as cliente_id
    ,vendedor_id as vendedor_id
    ,ano as ano
    ,IF(mes = '01', vlr_total, 0) AS janeiro 
    ,IF(mes = '02', vlr_total, 0) AS fevereiro
    ,IF(mes = '03', vlr_total, 0) AS marco
    ,IF(mes = '04', vlr_total, 0) AS abril 
    ,IF(mes = '05', vlr_total, 0) AS maio
    ,IF(mes = '06', vlr_total, 0) AS junho 
    ,IF(mes = '07', vlr_total, 0) AS julho 
    ,IF(mes = '08', vlr_total, 0) AS agosto 
    ,IF(mes = '09', vlr_total, 0) AS setembro 
    ,IF(mes = '10', vlr_total, 0) AS outubro
    ,IF(mes = '11', vlr_total, 0) AS novembro 
    ,IF(mes = '12', vlr_total, 0) AS dezembro 
    from view_base_venda; 

CREATE VIEW view_base_venda_cliente_ano AS select
cliente_id as cliente_id
,vendedor_id as vendedor_id
,ano as ano
,sum(janeiro) AS janeiro 
,sum(fevereiro) AS fevereiro
,sum(marco) AS marco
,sum(abril) AS abril 
,sum(maio) AS maio
,sum(junho) AS junho 
,sum(julho) AS julho 
,sum(agosto) AS agosto 
,sum(setembro) AS setembro 
,sum(outubro) AS outubro
,sum(novembro) AS novembro 
,sum(dezembro) AS dezembro 
from view_base_venda_cliente
group by cliente_id,vendedor_id,ano;; 

CREATE VIEW view_base_venda_produto AS     select 
    cliente_id as cliente_id
    ,produto_id as produto_id
    ,vendedor_id as vendedor_id
    ,ano as ano
    ,IF(mes = '01', vlr_total, 0) AS janeiro 
    ,IF(mes = '02', vlr_total, 0) AS fevereiro
    ,IF(mes = '03', vlr_total, 0) AS marco
    ,IF(mes = '04', vlr_total, 0) AS abril 
    ,IF(mes = '05', vlr_total, 0) AS maio
    ,IF(mes = '06', vlr_total, 0) AS junho 
    ,IF(mes = '07', vlr_total, 0) AS julho 
    ,IF(mes = '08', vlr_total, 0) AS agosto 
    ,IF(mes = '09', vlr_total, 0) AS setembro 
    ,IF(mes = '10', vlr_total, 0) AS outubro
    ,IF(mes = '11', vlr_total, 0) AS novembro 
    ,IF(mes = '12', vlr_total, 0) AS dezembro 
    from view_base_venda; 

CREATE VIEW view_base_venda_produto_ano AS select
cliente_id as cliente_id
,produto_id as produto_id
,vendedor_id as vendedor_id
,ano as ano
,sum(janeiro) AS janeiro 
,sum(fevereiro) AS fevereiro
,sum(marco) AS marco
,sum(abril) AS abril 
,sum(maio) AS maio
,sum(junho) AS junho 
,sum(julho) AS julho 
,sum(agosto) AS agosto 
,sum(setembro) AS setembro 
,sum(outubro) AS outubro
,sum(novembro) AS novembro 
,sum(dezembro) AS dezembro 
from view_base_venda_produto
group by cliente_id,produto_id,vendedor_id,ano;; 

CREATE VIEW view_cliente_saldo_titulo AS select 
cliente.id as id
,cliente.cod_erp as cod_erp
,cliente.razao as razao
,cliente.fantasia as fantasia
,cliente.vendedor_id as vendedor_id
,cliente.tipo as tipo
,cliente.status as status
,cliente.situacao_cadastral_id as situacao_id
,round(IFNULL((select sum(titulo_receber.saldo) 
    from titulo_receber 
    where titulo_receber.reg_ativo = 'S'
    and saldo > 0
    and titulo_receber.venc_real < curdate()
    and titulo_receber.cliente_id = cliente.id
    group by titulo_receber.cliente_id
 ), 0),2) as vencido
 ,round(IFNULL((select sum(titulo_receber.saldo) 
    from titulo_receber 
    where titulo_receber.reg_ativo = 'S'
    and saldo > 0
    and titulo_receber.venc_real >= curdate()
    and titulo_receber.cliente_id = cliente.id
    group by titulo_receber.cliente_id
 ), 0),2) as aberto
,round(IFNULL((select sum(titulo_receber.saldo) 
    from titulo_receber 
    where titulo_receber.reg_ativo = 'S'
    and saldo > 0
    and titulo_receber.cliente_id = cliente.id
    group by titulo_receber.cliente_id
 ), 0),2) as saldo
,round(IFNULL((select count(titulo_receber.id) 
    from titulo_receber 
    where titulo_receber.reg_ativo = 'S'
    and saldo > 0
    and titulo_receber.cliente_id = cliente.id
    group by titulo_receber.cliente_id
 ), 0),2) as quantidade
,round(IFNULL(
   (select DATEDIFF(CURDATE(), min(titulo_receber.venc_real) ) 
    from titulo_receber 
    where titulo_receber.reg_ativo = 'S'
    and saldo > 0
    and titulo_receber.venc_real < CURDATE()
    and titulo_receber.cliente_id = cliente.id
    group by titulo_receber.cliente_id
   )
, 0),2) as MaiorAtraso
from cliente 
where cliente.reg_ativo = 'S' 
GROUP BY cliente.id; 

CREATE VIEW view_cliente_vendedor AS SELECT cliente.id as cliente_id
,cliente.cod_erp as cliente_cod_erp
,cliente.razao as cliente_razao
,cliente.status as cliente_situacao
,cliente.carteira as cliente_carteira
,cliente.ultima_visita as cliente_ult_visita
,cliente.ultima_compra as cliente_ult_compra
,cliente.ultimo_atendimento as cliente_ult_atendimento
,cliente.data_cadastro as cliente_dt_cadastro 
,vendedor.id as vendedor_id
,vendedor.cod_erp as vendedor_cod_erp
,vendedor.nome as vendedor_nome
FROM cliente 
join vendedor on (vendedor.id = vendedor_id)
where cliente.reg_ativo = 'S';
; 

CREATE VIEW view_precos AS select 
tabela_preco_item.id as id
,tabela_preco_item.tabela_preco_id as tabela_preco_id
,tabela_preco.cod_erp as cod_erp
,tabela_preco.descricao as descricao
,tabela_preco_item.item as item
,tabela_preco_item.produto_id as produto_id
,tabela_preco_item.preco as preco
from tabela_preco_item
join tabela_preco on (tabela_preco.id = tabela_preco_item.tabela_preco_id and tabela_preco.status = 'S'); 

CREATE VIEW view_produto_estoque_preco AS select produto.id as produto_id
,produto.cod_erp as produto_cod_erp
,produto.descricao as produto_descricao
,produto.armazem_id as armazem_id
,armazem.cod_erp as armazem_cod_erp
,armazem.descricao as armazem_descricao
,estoque.saldo as produto_saldo
,view_precos.tabela_preco_id as tabela_preco_id
,view_precos.descricao as precos_descricao
,view_precos.preco as preco
from produto
left join estoque on (estoque.produto_id = produto.id and estoque.armazem_id = produto.armazem_id )
left join armazem on (armazem.id = estoque.armazem_id)
left join view_precos on (view_precos.produto_id = produto.id); 

CREATE VIEW view_produto_orcamento AS select 
produto.cod_erp as produto_cod_erp
,produto.id as produto_id
,produto.descricao as produto_descricao
,produto.status as situacao
,armazem.id as armazem_id
,armazem.cod_erp as armazem_cod_erp
,armazem.descricao as armazem_descricao
,estoque.saldo as produto_saldo
,view_precos.tabela_preco_id as tabela_preco_id
,view_precos.descricao as precos_descricao
,view_precos.preco as preco
,produto.id as ultima_venda
,produto.id as ultimo_preco
from produto
left join estoque on (estoque.produto_id = produto.id and estoque.armazem_id = produto.armazem_id )
left join armazem on (armazem.id = estoque.armazem_id)
left join view_precos on (view_precos.produto_id = produto.id); 

CREATE VIEW view_qtd_venda_mes_vendedor AS select vendedor1_id as vendedor1_id
,ano as ano
,sum(janeiro) as janeiro
,sum(fevereiro) as fevereiro
,sum(marco) as marco
,sum(abril) as abril
,sum(maio) as maio
,sum(junho) as junho
,sum(julho) as julho
,sum(agosto) as agosto
,sum(setembro) as setembro
,sum(outubro) as outubro
,sum(novembro) as novembro
,sum(dezembro) as dezembro
from view_venda_mes_vendedor
group by vendedor1_id,ano; 

CREATE VIEW view_titulo_cliente AS select titulo_receber.id as id
,cliente_id as cliente_id
,vendedor.id as vendedor_id 
,titulo_receber.prefixo as prefixo
,titulo_receber.tipo as tipo
,titulo_receber.numero as numero
,titulo_receber.parcela as parcela
,titulo_receber.emissao as emissao
,titulo_receber.venc_real as vencimento
,titulo_receber.saldo as saldo
,titulo_receber.valor as valor
,titulo_receber.forma_pgto as forma
,titulo_receber.pedido_id as pedido_id
,titulo_receber.nota_fiscal_id as nota_fiscal_id
,titulo_receber.origem as origem
,titulo_receber.situacao as portador
,CASE 
	WHEN titulo_receber.saldo > 0  AND titulo_receber.venc_real > curdate() THEN "A RECEBER"
	WHEN titulo_receber.saldo > 0  AND titulo_receber.venc_real <= curdate() THEN "EM ATRASO"
	WHEN titulo_receber.saldo = 0  THEN "RECEBIDO"
	ELSE "RECEBIDO"
END as situacao
,MONTH(titulo_receber.venc_real) as mesVencimento
,YEAR(titulo_receber.venc_real) as anoVencimento
,MONTH(titulo_receber.emissao) as mesEmissao
,YEAR(titulo_receber.emissao) as anoEmissao
,datediff(curdate(), titulo_receber.venc_real) as dias
from titulo_receber
join cliente on (cliente.reg_ativo = 'S' and cliente.id = titulo_receber.cliente_id)
join vendedor on (vendedor.id = cliente.vendedor_id)
where titulo_receber.reg_ativo = 'S'; 

CREATE VIEW view_total_catogoria_mes AS select distinct 
categoria.id as id
,categoria.cod_erp as cod_erp
,categoria.descricao as categoria
,vendedor.id as vendedor_id
,view_venda_categoria.nota_saida_mes as mes
,view_venda_categoria.nota_saida_ano  as ano
,meta_vendedor_categoria.valor as vlr_objetivo
,sum(view_venda_categoria.nota_saida_item_vlr_total) as vlr_total
,sum(view_venda_categoria.nota_saida_item_vlr_liquido) as vlr_liquido
,round(sum(view_venda_categoria.nota_saida_item_vlr_total) * 100 /sum(meta_vendedor_categoria.valor),2) as perc_total
,round(sum(view_venda_categoria.nota_saida_item_vlr_liquido) * 100/sum(meta_vendedor_categoria.valor),2) as perc_liquido
from view_venda_categoria
left join categoria on (
	categoria.id = view_venda_categoria.categoria_id
)
left join vendedor on (
    view_venda_categoria.vendedor_id = vendedor.id
)
left join meta_vendedor_mes on (
    meta_vendedor_mes.vendedor_id = view_venda_categoria.vendedor_id
    and meta_vendedor_mes.ano = view_venda_categoria.nota_saida_ano    
    and meta_vendedor_mes.mes = view_venda_categoria.nota_saida_mes
	and meta_vendedor_mes.dt_delete IS NULL
    
)
left join meta_vendedor_categoria on (
	meta_vendedor_categoria.meta_vendedor_mes_id = meta_vendedor_mes.id
	and meta_vendedor_categoria.cod_erp = categoria.cod_erp
	and meta_vendedor_categoria.dt_delete IS NULL
)
group by categoria.id
,categoria.cod_erp
,categoria.descricao 
,vendedor.id 
,view_venda_categoria.nota_saida_mes 
,view_venda_categoria.nota_saida_ano 
,meta_vendedor_categoria.valor ; 

CREATE VIEW view_ultimo_preco AS SELECT nota_saida_item.id as id
,produto_id as produto_id
,nota_saida.cliente_id as cliente_id
,nota_saida_id as nota_saida_id
,nota_saida.nota_fiscal  as nota_fiscal 
,nota_saida.serie_fiscal  as serie_fiscal
,nota_saida.especie_fiscal as especie_fiscal
,nota_saida_item.qtd as quantidade
,nota_saida_item.vlr_unitario  as vlr_unitario
,nota_saida_item.vlr_tabela as vlr_tabela
,nota_saida_item.vlr_desconto as vlr_desconto
,financeiro  as financeiro
,estoque as estoque
,nota_saida.vendedor1_id as vendedor_id
,tes as tes
,max(nota_saida.dt_emissao) as dt_emissao
FROM nota_saida_item join nota_saida on (nota_saida_item.nota_saida_id = nota_saida.id and nota_saida.reg_ativo = 'S') 
WHERE nota_saida_item.reg_ativo = 'S' 
group by produto_id,cliente_id; 

CREATE VIEW view_venda_categoria AS SELECT 
categoria.id as categoria_id
,categoria.cod_erp as categoria_cod_erp
,categoria.descricao as categoria_descricao
,produto.id as produto_id
,nota_saida.id as nota_saida_id 
,nota_saida.nota_fiscal as nota_fiscal
,nota_saida.especie_fiscal as especie_fiscal
,nota_saida.ano as nota_saida_ano
,nota_saida.mes as nota_saida_mes
,nota_saida_item.vlr_bruto as nota_saida_item_vlr_total
,(nota_saida_item.vlr_bruto - nota_saida_item.vlr_dev) as nota_saida_item_vlr_liquido
,cliente.id as cliente_id
,cliente.cod_erp as cliente_cod_erp
,cliente.vendedor_id as cliente_vendedor_id
,vendedor.id as vendedor_id 
,vendedor.status as vendedor_status
FROM nota_saida 
join nota_saida_item on (nota_saida_item.nota_saida_id = nota_saida.id and nota_saida_item.reg_ativo = 'S')
left join vendedor on(vendedor.id = nota_saida.vendedor1_id  )    
left join produto on (nota_saida_item.produto_id = produto.id)
left join categoria on (produto.categoria_id = categoria.id)
left join cliente on (nota_saida.cliente_id = cliente.id)
where nota_saida.numero_titulo <> '' 
and nota_saida.tipo = 'N'
and nota_saida.reg_ativo = 'S'  
and nota_saida.especie_fiscal = 'SPED'
      ; 

CREATE VIEW view_venda_categoria_ano AS select 
id as id
,vendedor_id as vendedor_id
,categoria_cod_erp as cod_erp
,categoria_descricao as descricao
,ano as ano
,sum(janeiro) as janeiro
,sum(fevereiro) as fevereiro
,sum(marco) as marco
,sum(abril) as abril
,sum(maio) as maio
,sum(junho) as junho
,sum(julho) as julho
,sum(agosto) as agosto
,sum(setembro) as setembro
,sum(outubro) as outubro
,sum(novembro) as novembro
,sum(dezembro) as dezembro
from view_venda_categoria_mes
group by view_venda_categoria_mes.id,vendedor_id,ano,categoria_cod_erp,categoria_descricao;; 

CREATE VIEW view_venda_categoria_mes AS select 
categoria_id as id
,categoria_cod_erp as categoria_cod_erp
,categoria_descricao as categoria_descricao
,vendedor_id as vendedor_id
,nota_saida_ano as ano
,IF(nota_saida_mes = '01', nota_saida_item_vlr_liquido, 0) AS janeiro 
,IF(nota_saida_mes = '02', nota_saida_item_vlr_liquido, 0) AS fevereiro
,IF(nota_saida_mes = '03', nota_saida_item_vlr_liquido, 0) AS marco
,IF(nota_saida_mes = '04', nota_saida_item_vlr_liquido, 0) AS abril 
,IF(nota_saida_mes = '05', nota_saida_item_vlr_liquido, 0) AS maio
,IF(nota_saida_mes = '06', nota_saida_item_vlr_liquido, 0) AS junho 
,IF(nota_saida_mes = '07', nota_saida_item_vlr_liquido, 0) AS julho 
,IF(nota_saida_mes = '08', nota_saida_item_vlr_liquido, 0) AS agosto 
,IF(nota_saida_mes = '09', nota_saida_item_vlr_liquido, 0) AS setembro 
,IF(nota_saida_mes = '10', nota_saida_item_vlr_liquido, 0) AS outubro
,IF(nota_saida_mes = '11', nota_saida_item_vlr_liquido, 0) AS novembro 
,IF(nota_saida_mes = '12', nota_saida_item_vlr_liquido, 0) AS dezembro 
from view_venda_categoria; 

CREATE VIEW view_venda_cliente AS SELECT 
cliente.id as cliente_id
,cliente.cod_erp as cliente_cod_erp
,produto.id as produto_id
,nota_saida.dt_emissao as nota_saida_dt_emissao
,nota_saida_item.ano as nota_saida_item_ano
,nota_saida_item.mes as nota_saida_item_mes
,nota_saida_item.vlr_bruto as nota_saida_item_vlr_total
,(nota_saida_item.vlr_bruto - nota_saida_item.vlr_dev) as nota_saida_item_vlr_liquido
,cliente.vendedor_id as cliente_vendedor_id
,nota_saida.vendedor1_id  as nota_vendedor_id 
FROM nota_saida_item
join nota_saida on (nota_saida_item.nota_saida_id = nota_saida.id 
    and nota_saida.numero_titulo <> '' 
    and nota_saida.tipo = 'N'
    and nota_saida.reg_ativo = 'S'  
    and nota_saida.especie_fiscal = 'SPED')
JOIN vendedor on( vendedor.id = nota_saida.vendedor1_id  )    
left join produto on (nota_saida_item.produto_id = produto.id)
join cliente on (nota_saida.cliente_id = cliente.id); 

CREATE VIEW view_venda_cliente_mes AS SELECT cliente_id as cliente_id
,nota_vendedor_id as nota_vendedor_id
,cliente_vendedor_id as cliente_vendedor_id
,nota_saida_item_ano as ano
,nota_saida_item_mes as mes
,nota_saida_dt_emissao as dt_emissao
,nota_saida_item_vlr_total as vlr_total
FROM view_venda_cliente
GROUP BY cliente_id,nota_vendedor_id,nota_saida_item_ano,nota_saida_item_mes; 

CREATE VIEW view_venda_mes AS select vendedor1_id as vendedor1_id
,ano as ano
,mes as mes
,count(cliente_id) as qtd
from view_vendas 
group by vendedor1_id,ano,mes; 

CREATE VIEW view_venda_mes_vendedor AS select 
vendedor1_id as vendedor1_id
,ano as ano
,IF(mes = '01', qtd, 0) AS janeiro 
,IF(mes = '02', qtd, 0) AS fevereiro
,IF(mes = '03', qtd, 0) AS marco
,IF(mes = '04', qtd, 0) AS abril 
,IF(mes = '05', qtd, 0) AS maio
,IF(mes = '06', qtd, 0) AS junho 
,IF(mes = '07', qtd, 0) AS julho 
,IF(mes = '08', qtd, 0) AS agosto 
,IF(mes = '09', qtd, 0) AS setembro 
,IF(mes = '10', qtd, 0) AS outubro
,IF(mes = '11', qtd, 0) AS novembro 
,IF(mes = '12', qtd, 0) AS dezembro 
from view_venda_mes; 

CREATE VIEW view_venda_regiao AS select regiao_cliente.id as regiao_id
,regiao_cliente.descricao as regiao_descricao
,nota_saida.mes as mes
,nota_saida.ano as ano
,nota_saida_item.produto_id as produto_id
,nota_saida.cliente_id as cliente_id
,nota_saida_item.vlr_bruto as vlr_total
,(nota_saida_item.vlr_bruto - nota_saida_item.vlr_dev) as vlr_liquido
,nota_saida_item.vlr_dev as vlr_dev
from regiao_cliente
left join nota_saida on ( nnota_saida.numero_titulo <> '' 
    and nota_saida.tipo = 'N'
    and nota_saida.reg_ativo = 'S'  
    and nota_saida.especie_fiscal = 'SPED'
 )
left join nota_saida_item on (nota_saida_item.nota_saida_id = nota_saida.id 
    and nota_saida_item.reg_ativo = 'S' 
    and nota_saida.mes = nota_saida_item.mes
    and nota_saida.ano = nota_saida_item.ano
    )
left join cliente on ( nota_saida.cliente_id = cliente.id and regiao_cliente.id = cliente.regiao_cliente_id)
; 

CREATE VIEW view_venda_regiao_mes AS  
 
select view_venda_regiao.regiao_id as regiao_id
,view_venda_regiao.regiao_descricao as regiao_descricao
,view_venda_regiao.mes as mes
,view_venda_regiao.ano as ano
,round(sum(vlr_total),2) as vlr_total
,round(sum(vlr_liquido),2) as vlr_liquido
from view_venda_regiao
group by view_venda_regiao.regiao_id, view_venda_regiao.regiao_descricao, view_venda_regiao.mes, view_venda_regiao.ano;
; 

CREATE VIEW view_vendas AS SELECT distinct 
vendedor1_id as vendedor1_id
,cliente_id as cliente_id
,ano as ano
,mes as mes
FROM nota_saida
WHERE nota_saida.numero_titulo <> '' 
 AND nota_saida.tipo = 'N'
 AND nota_saida.reg_ativo = 'S'  
 AND nota_saida.especie_fiscal = 'SPED'; 

CREATE VIEW view_vendedor_cliente_status AS SELECT 
vendedor.id as vendedor_id
,cliente.status as cliente_status
,vendedor.desligado as vendedor_desligado
,count(*) as quantidade 
FROM cliente 
join vendedor on (vendedor.id = cliente.vendedor_id)
group by cliente.status,vendedor.id,vendedor.desligado;; 

CREATE VIEW view_vendedor_venda AS select vendedor.id as vendedor_id
,vendedor.nome_reduzido as nome_reduzido
,vendedor.nome as nome
,vendedor.status as situacao
,nota_saida.mes as mes
,nota_saida.ano as ano
,nota_saida_item.produto_id as produto_id
,produto.cod_erp as produto_cod_erp
,produto.descricao as produto_descricao
,categoria.id as categoria_id
,categoria.cod_erp as categoria_cod_erp
,categoria.descricao as categoria_descricao
,nota_saida.cliente_id as cliente_id
,cliente.razao as cliente_razao
,cliente.fantasia as cliente_fantasia
,nota_saida_item.vlr_bruto as nota_saida_item_vlr_total
,(nota_saida_item.vlr_bruto - nota_saida_item.vlr_dev) as nota_saida_item_vlr_liquido
from vendedor

left join nota_saida on ( nota_saida.vendedor1_id = vendedor.id
    and nota_saida.numero_titulo <> '' 
    and nota_saida.tipo = 'N'
    and nota_saida.reg_ativo = 'S'  
    and nota_saida.especie_fiscal = 'SPED'
 )
left join nota_saida_item on (nota_saida_item.nota_saida_id = nota_saida.id 
    and nota_saida_item.reg_ativo = 'S' 
    and nota_saida.mes = nota_saida_item.mes
    and nota_saida.ano = nota_saida_item.ano
    )
left join cliente on ( nota_saida.cliente_id = cliente.id)
left join produto on ( nota_saida_item.produto_id = produto.id)
left join categoria on ( produto.categoria_id = categoria.id)
; 

CREATE VIEW view_vendedor_venda_mes AS select distinct view_vendedor_venda.vendedor_id as vendedor_id
,vendedor.nome as nome
,vendedor.nome_reduzido as nome_reduzido
,view_vendedor_venda.mes as mes
,view_vendedor_venda.ano as ano
,0 as positivacao
,round(sum(nota_saida_item_vlr_total),2) as vlr_total
,round(sum(nota_saida_item_vlr_liquido),2) as vlr_liquido
,meta_vendedor_mes.valor as vlr_objetivo
,CASE
    WHEN meta_vendedor_mes.valor > 0 THEN round((round(sum(nota_saida_item_vlr_total),2) * 100)/meta_vendedor_mes.valor,2)
    ELSE 0
END as perc_total
,CASE
    WHEN meta_vendedor_mes.valor > 0 THEN round((round(sum(nota_saida_item_vlr_liquido),2) * 100)/meta_vendedor_mes.valor,2)
    ELSE 0
END as perc_liquido
from view_vendedor_venda
join vendedor on (vendedor.id = view_vendedor_venda.vendedor_id)
left join meta_vendedor_mes on (meta_vendedor_mes.vendedor_id = view_vendedor_venda.vendedor_id 
                                and meta_vendedor_mes.ano = view_vendedor_venda.ano 
                                and meta_vendedor_mes.mes = view_vendedor_venda.mes
                                and meta_vendedor_mes.dt_delete is null
                                )
/*left join view_base_cliente_mes on (
view_base_cliente_mes.vendedor_id = view_vendedor_venda.vendedor_id 
    and view_base_cliente_mes.ano = view_vendedor_venda.ano 
    and view_base_cliente_mes.mes = view_vendedor_venda.mes)
*/
group by view_vendedor_venda.vendedor_id ,vendedor.nome ,vendedor.nome_reduzido ,view_vendedor_venda.mes ,view_vendedor_venda.ano ,meta_vendedor_mes.valor;; 
 
