CREATE TABLE armazem( 
      id number(10)    NOT NULL , 
      cod_erp char  (6)    NOT NULL , 
      descricao varchar  (50)    NOT NULL , 
      status varchar  (1)   , 
      dt_inclusao timestamp(0)   , 
      dt_alteracao timestamp(0)   , 
      system_unit_id number(10)   , 
 PRIMARY KEY (id)) ; 

CREATE TABLE atendimento( 
      id number(10)    NOT NULL , 
      atendimento_tipo_id number(10)    NOT NULL , 
      vendedor_id number(10)    NOT NULL , 
      codigo_cliente varchar  (10)   , 
      cliente_id number(10)   , 
      horario_inicial timestamp(0)    NOT NULL , 
      horario_final timestamp(0)    NOT NULL , 
      titulo varchar(3000)   , 
      cor varchar(3000)   , 
      retorno timestamp(0)   , 
      observacao varchar(3000)   , 
      dt_inclusao timestamp(0)   , 
      dt_alteracao timestamp(0)   , 
      dt_delete timestamp(0)   , 
      user_id_create number(10)   , 
      user_id_update number(10)   , 
      user_id_delete number(10)   , 
      nome varchar  (100)   , 
      telefone varchar  (50)   , 
      email varchar  (100)   , 
      status char  (1)   , 
      orcamento_id number(10)   , 
      nota_saida_id number(10)   , 
 PRIMARY KEY (id)) ; 

CREATE TABLE atendimento_tipo( 
      id number(10)    NOT NULL , 
      cod_erp varchar  (10)   , 
      descricao varchar  (50)   , 
      cor varchar(3000)   , 
      icone varchar  (100)   , 
      dt_alteracao timestamp(0)   , 
      dt_inclusao timestamp(0)   , 
      retorno char  (1)   , 
      editar char  (1)   , 
      excluir char  (1)   , 
      atendimento char  (1)   , 
      venda char  (1)   , 
      cadastro char  (1)   , 
      cobranca char  (1)   , 
 PRIMARY KEY (id)) ; 

CREATE TABLE blog_aniversarios( 
      id number(10)    NOT NULL , 
      nome varchar(3000)    NOT NULL , 
      filial_id number(10)   , 
      dia number(10)    NOT NULL , 
      mes number(10)    NOT NULL , 
      dt_alteracao timestamp(0)   , 
      dt_inclusao timestamp(0)   , 
      status char  (1)   , 
      system_user_id number(10)   , 
 PRIMARY KEY (id)) ; 

CREATE TABLE blog_comunicados( 
      id number(10)    NOT NULL , 
      filial_id number(10)   , 
      titulo varchar(3000)    NOT NULL , 
      texto varchar(3000)    NOT NULL , 
      data_postagem date    NOT NULL , 
      link_externo varchar(3000)   , 
      link_texto varchar(3000)   , 
      status char  (1)   , 
      ordenacao number(10)   , 
      data_validade date   , 
      dt_alteracao timestamp(0)   , 
      dt_inclusao timestamp(0)   , 
      system_user_id number(10)   , 
 PRIMARY KEY (id)) ; 

CREATE TABLE blog_noticias( 
      id number(10)    NOT NULL , 
      titulo varchar(3000)    NOT NULL , 
      texto varchar(3000)    NOT NULL , 
      data_postagem date    NOT NULL , 
      imagem varchar(3000)   , 
      autor varchar(3000)   , 
      status char  (1)   , 
      dt_alteracao timestamp(0)   , 
      dt_inclusao timestamp(0)   , 
      data_validade date   , 
      system_user_id number(10)   , 
 PRIMARY KEY (id)) ; 

CREATE TABLE calendario_orcamento( 
      id number(10)    NOT NULL , 
      orcamento_id number(10)    NOT NULL , 
      horario_inicial timestamp(0)    NOT NULL , 
      horario_final timestamp(0)    NOT NULL , 
      titulo varchar(3000)   , 
      cor varchar(3000)   , 
      observacao varchar(3000)   , 
 PRIMARY KEY (id)) ; 

CREATE TABLE caracteristica( 
      id number(10)    NOT NULL , 
      descricao varchar  (50)   , 
      tipo char  (1)   , 
      dt_alteracao timestamp(0)   , 
      dt_inclusao timestamp(0)   , 
 PRIMARY KEY (id)) ; 

CREATE TABLE categoria( 
      id number(10)    NOT NULL , 
      filial_id number(10)   , 
      cod_erp varchar  (6)    NOT NULL , 
      descricao varchar  (200)    NOT NULL , 
      usado char  (1)   , 
      site char  (1)   , 
      status char  (1)   , 
      dt_alteracao timestamp(0)   , 
      dt_inclusao timestamp(0)   , 
 PRIMARY KEY (id)) ; 

CREATE TABLE cep( 
      id number(10)    NOT NULL , 
      cep varchar  (8)   , 
      estado_id number(10)   , 
      cidade_id number(10)   , 
      bairro varchar  (500)   , 
      endereco varchar  (500)   , 
      longitude binary_double   , 
      latitude binary_double   , 
      service varchar  (100)   , 
 PRIMARY KEY (id)) ; 

CREATE TABLE cliente( 
      id number(10)    NOT NULL , 
      filial_id number(10)   , 
      cod_erp varchar  (10)   , 
      status char  (1)   , 
      tipo char  (1)   , 
      razao varchar  (100)    NOT NULL , 
      tipo_cliente_id number(10)   , 
      fantasia varchar  (50)   , 
      endereco varchar  (100)   , 
      complemento varchar  (50)   , 
      bairro varchar  (50)   , 
      uf char  (2)   , 
      municipio varchar  (50)   , 
      municipio_id number(10)   , 
      cep varchar  (8)   , 
      telefone1 varchar  (20)   , 
      telefone2 varchar  (20)   , 
      fax char  (20)   , 
      celular varchar  (20)   , 
      celular2 char  (20)   , 
      contato varchar  (30)   , 
      cnpj_cpf varchar  (20)   , 
      ie varchar  (20)   , 
      im char  (20)   , 
      contribuinte char  (1)   , 
      rg varchar  (20)   , 
      nascimento date   , 
      email varchar  (100)   , 
      vendedor_id number(10)   , 
      condicao_pagamento_id number(10)   , 
      tabela_preco_id number(10)   , 
      primeira_compra date   , 
      ultima_compra date   , 
      data_cadastro date   , 
      dt_alteracao timestamp(0)   , 
      sinc char  (1)   , 
      dt_inclusao timestamp(0)   , 
      destaca_ie char  (1)   , 
      seguimento_id number(10)   , 
      site char  (100)   , 
      obs varchar(3000)   , 
      filial_cadastro number(10)   , 
      cliente char  (1)   , 
      latitude binary_double   , 
      log_int varchar(3000)   , 
      longitude binary_double   , 
      limite binary_double   , 
      vencimento_limite date   , 
      risco char  (1)   , 
      system_unit_id number(10)   , 
      carteira char  (1)   , 
      obs_bloqueio varchar(3000)   , 
      dt_bloqueio timestamp(0)   , 
      motivo_bloqueio char  (1)   , 
      motivo_bloqueio_id number(10)   , 
      dt_reativacao date   , 
      obs_reativacao varchar(3000)   , 
      ultima_visita timestamp(0)   , 
      ultimo_atendimento timestamp(0)   , 
      regiao_cliente_id number(10)   , 
      reg_ativo char  (1)   , 
      dt_revisao timestamp(0)   , 
      situacao_cadastral_id number(10)   , 
      data_rfb date   , 
 PRIMARY KEY (id)) ; 

CREATE TABLE cliente_acesso( 
      id number(10)    NOT NULL , 
      cliente_id number(10)   , 
      user_id number(10)   , 
      dt_alteracao timestamp(0)   , 
      dt_inclusao timestamp(0)   , 
      dt_delete timestamp(0)   , 
      login varchar  (100)   , 
      senha varchar(3000)   , 
      status char  (1)   , 
 PRIMARY KEY (id)) ; 

CREATE TABLE cliente_atendimento( 
      id number(10)    NOT NULL , 
      cliente_id number(10)    NOT NULL , 
      vendedor_id number(10)    NOT NULL , 
      horario_inicial timestamp(0)    NOT NULL , 
      horario_final timestamp(0)    NOT NULL , 
      titulo varchar(3000)   , 
      cor varchar(3000)   , 
      observacao varchar(3000)   , 
      dt_alteracao timestamp(0)   , 
      dt_inclusao timestamp(0)   , 
 PRIMARY KEY (id)) ; 

CREATE TABLE cliente_atualizacao( 
      id number(10)    NOT NULL , 
      cliente_id number(10)   , 
      situacao_cadastral_id number(10)   , 
      atividade_principal_id number(10)   , 
      razao varchar(3000)    NOT NULL , 
      fantasia varchar(3000)   , 
      tipo_logradouro varchar  (20)   , 
      logradouro varchar(3000)   , 
      numero varchar  (10)   , 
      complemento varchar(3000)   , 
      bairro varchar  (50)   , 
      municipio_id number(10)   , 
      cep varchar  (8)   , 
      telefone1 varchar  (20)   , 
      telefone2 varchar  (20)   , 
      fax char  (20)   , 
      celular varchar  (20)   , 
      celular2 char  (20)   , 
      contato varchar  (30)   , 
      cnpj_cpf varchar  (20)   , 
      ie varchar  (20)   , 
      im char  (20)   , 
      email varchar  (100)   , 
      site char  (100)   , 
      latitude binary_double   , 
      longitude binary_double   , 
      dt_alteracao timestamp(0)   , 
      dt_inclusao timestamp(0)   , 
      data_situacao_especial timestamp(0)   , 
      situacao_especial varchar  (10)   , 
      atualizado_em timestamp(0)   , 
      data_situacao_cadastral timestamp(0)   , 
      simples varchar  (10)   , 
      tipo varchar  (10)   , 
      pais_id number(10)   , 
      mei varchar  (10)   , 
      porte varchar  (100)   , 
      natureza_juridica varchar  (100)   , 
      capital_social binary_double   , 
 PRIMARY KEY (id)) ; 

CREATE TABLE cliente_cnae( 
      id number(10)    NOT NULL , 
      cliente_id number(10)    NOT NULL , 
      cnae_id number(10)    NOT NULL , 
      dt_alteracao timestamp(0)   , 
      dt_inclusao timestamp(0)   , 
 PRIMARY KEY (id)) ; 

CREATE TABLE cliente_condicao( 
      id number(10)    NOT NULL , 
      condicao_pagamento_id number(10)    NOT NULL , 
      cliente_id number(10)    NOT NULL , 
      padrao char  (1)   , 
      dt_inclusao timestamp(0)   , 
      dt_alteracao timestamp(0)   , 
 PRIMARY KEY (id)) ; 

CREATE TABLE cliente_contato( 
      id number(10)    NOT NULL , 
      cliente_id number(10)    NOT NULL , 
      tipo_contato_id number(10)    NOT NULL , 
      nome varchar  (100)   , 
      telefone char  (9)   , 
      email varchar  (100)   , 
      situacao char  (1)   , 
      dt_inclusao timestamp(0)   , 
      dt_alteracao timestamp(0)   , 
 PRIMARY KEY (id)) ; 

CREATE TABLE cliente_historico( 
      id number(10)    NOT NULL , 
      cliente_id number(10)    NOT NULL , 
      vendedor_id number(10)   , 
      motivo_id number(10)   , 
      observacao varchar(3000)   , 
      dt_alteracao timestamp(0)   , 
      dt_inclusao timestamp(0)   , 
      data_movimento date   , 
 PRIMARY KEY (id)) ; 

CREATE TABLE cliente_socios( 
      id number(10)    NOT NULL , 
      cliente_id number(10)    NOT NULL , 
      nome varchar  (100)   , 
      tipo varchar  (20)   , 
      data_entrada date   , 
      faixa_etaria varchar  (100)   , 
      qualificacao_socio varchar  (100)   , 
      descricao varchar  (100)   , 
      cpf_cnpj_socio varchar  (100)   , 
      dt_alteracao timestamp(0)   , 
      dt_inclusao timestamp(0)   , 
 PRIMARY KEY (id)) ; 

CREATE TABLE cliente_vendedor_mes( 
      id number(10)    NOT NULL , 
      filial_id number(10)   , 
      empresa_id number(10)   , 
      vendedor_id number(10)    NOT NULL , 
      ano char  (4)    NOT NULL , 
      janeiro binary_double    DEFAULT 0 , 
      fevereiro binary_double    DEFAULT 0 , 
      marco binary_double    DEFAULT 0 , 
      abril binary_double    DEFAULT 0 , 
      maio binary_double    DEFAULT 0 , 
      junho binary_double    DEFAULT 0 , 
      julho binary_double    DEFAULT 0 , 
      agosto binary_double    DEFAULT 0 , 
      setembro binary_double    DEFAULT 0 , 
      outubro binary_double    DEFAULT 0 , 
      novembro binary_double    DEFAULT 0 , 
      dezembro binary_double    DEFAULT 0 , 
      total_carteira binary_double    DEFAULT 0 , 
      total_rcg binary_double    DEFAULT 0 , 
      total_avulso binary_double    DEFAULT 0 , 
      total_bloqueado binary_double   , 
      vendedor_nome varchar  (100)   , 
 PRIMARY KEY (id)) ; 

CREATE TABLE cnae( 
      id number(10)    NOT NULL , 
      cod_erp varchar  (10)   , 
      secao varchar  (1)   , 
      divisao varchar  (10)   , 
      grupo varchar  (10)   , 
      classe varchar  (10)   , 
      subclasse varchar  (10)   , 
      descricao varchar(3000)   , 
      dt_inclusao timestamp(0)   , 
      dt_alteracao timestamp(0)   , 
 PRIMARY KEY (id)) ; 

CREATE TABLE condicao_pagamento( 
      id number(10)    NOT NULL , 
      filial_id number(10)   , 
      cod_erp varchar  (3)    NOT NULL , 
      descricao varchar  (100)    NOT NULL , 
      forma char  (3)   , 
      status char  (1)   , 
      dt_inclusao timestamp(0)   , 
      dt_alteracao timestamp(0)   , 
      system_unit_id number(10)   , 
 PRIMARY KEY (id)) ; 

CREATE TABLE empresa( 
      id number(10)    NOT NULL , 
      cod_erp char  (2)   , 
      system_unit_id number(10)   , 
      nome varchar  (100)    NOT NULL , 
      logo varchar(3000)   , 
      status char  (1)   , 
      dt_inclusao timestamp(0)   , 
      dt_alteracao timestamp(0)   , 
 PRIMARY KEY (id)) ; 

CREATE TABLE estado( 
      id number(10)    NOT NULL , 
      cod_erp varchar  (2)    NOT NULL , 
      sigla varchar  (2)    NOT NULL , 
      descricao varchar  (100)    NOT NULL , 
      codigo_ibge varchar  (10)   , 
 PRIMARY KEY (id)) ; 

CREATE TABLE estoque( 
      id number(10)    NOT NULL , 
      filial_id number(10)   , 
      produto_id number(10)    NOT NULL , 
      armazem_id number(10)    NOT NULL , 
      saldo binary_double   , 
      reserva binary_double   , 
      system_unit_id number(10)   , 
      ult_compra date   , 
      ult_preco binary_double   , 
      custo binary_double   , 
      dt_inclusao timestamp(0)   , 
      dt_alteracao timestamp(0)   , 
 PRIMARY KEY (id)) ; 

CREATE TABLE fabricante( 
      id number(10)    NOT NULL , 
      cod_erp char  (6)    NOT NULL , 
      descricao varchar  (200)    NOT NULL , 
      dt_inclusao timestamp(0)   , 
      dt_alteracao timestamp(0)   , 
 PRIMARY KEY (id)) ; 

CREATE TABLE ficha_tecnica( 
      id number(10)    NOT NULL , 
      produto_id number(10)    NOT NULL , 
      arquivo varchar  (200)   , 
      validade date   , 
      dt_alteracao timestamp(0)   , 
      dt_inclusao timestamp(0)   , 
 PRIMARY KEY (id)) ; 

CREATE TABLE filial( 
      id number(10)    NOT NULL , 
      cod_emp char  (6)   , 
      cod_erp char  (6)    NOT NULL , 
      system_unit_id number(10)   , 
      apelido varchar  (50)   , 
      matriz char  (1)   , 
      nome varchar  (100)    NOT NULL , 
      fantasia varchar  (100)   , 
      tipo char  (1)   , 
      cnpj varchar  (14)   , 
      cpf char  (11)   , 
      cep varchar  (8)   , 
      endereco varchar  (100)   , 
      complemento varchar  (100)   , 
      bairro varchar  (50)   , 
      municipio varchar  (50)   , 
      uf char  (2)   , 
      email varchar  (100)   , 
      status char  (1)   , 
      dt_alteracao timestamp(0)   , 
      dt_inclusao timestamp(0)   , 
 PRIMARY KEY (id)) ; 

CREATE TABLE fornecedor( 
      id number(10)    NOT NULL , 
      filial_id number(10)   , 
      cod_erp varchar  (10)   , 
      status char  (1)   , 
      razao varchar  (100)    NOT NULL , 
      tipo number(10)   , 
      fantasia varchar  (50)   , 
      endereco varchar  (100)   , 
      complemento varchar  (50)   , 
      bairro varchar  (50)   , 
      uf char  (2)   , 
      municipio varchar  (50)   , 
      municipio_id number(10)   , 
      cep varchar  (8)   , 
      telefone1 varchar  (20)   , 
      telefone2 varchar  (20)   , 
      fax char  (20)   , 
      celular varchar  (20)   , 
      contato varchar  (30)   , 
      cnpj_cpf varchar  (20)   , 
      ie varchar  (20)   , 
      im char  (20)   , 
      contribuinte char  (1)   , 
      rg varchar  (20)   , 
      nascimento date   , 
      email varchar  (100)   , 
      condicao_pagamento_id number(10)   , 
      primeira_compra date   , 
      ultima_visita date   , 
      sinc char  (1)   , 
      data_cadastro date   , 
      dt_alteracao timestamp(0)   , 
      dt_inclusao timestamp(0)   , 
      destaca_ie char  (1)   , 
      seguimento_id number(10)   , 
      site char  (100)   , 
      obs varchar(3000)   , 
      filial_cadastro number(10)   , 
      latitude binary_double   , 
      log_int varchar(3000)   , 
      longitude binary_double   , 
      system_unit_id number(10)   , 
 PRIMARY KEY (id)) ; 

CREATE TABLE meta_vendedor_categoria( 
      id number(10)    NOT NULL , 
      meta_vendedor_mes_id number(10)    NOT NULL , 
      categoria_id number(10)    NOT NULL , 
      cod_erp varchar  (6)   , 
      descricao varchar  (200)   , 
      valor binary_double   , 
      dt_alteracao timestamp(0)   , 
      dt_inclusao timestamp(0)   , 
      dt_delete timestamp(0)   , 
 PRIMARY KEY (id)) ; 

CREATE TABLE meta_vendedor_mes( 
      id number(10)    NOT NULL , 
      vendedor_id number(10)    NOT NULL , 
      mes varchar  (2)    NOT NULL , 
      ano varchar  (4)    NOT NULL , 
      tipo char  (1)   , 
      valor binary_double    NOT NULL , 
      numero_cliente binary_double   , 
      novo_cliente binary_double   , 
      dt_alteracao timestamp(0)   , 
      dt_inclusao timestamp(0)   , 
      dt_delete timestamp(0)   , 
 PRIMARY KEY (id)) ; 

CREATE TABLE motivo_bloqueio( 
      id number(10)    NOT NULL , 
      cod_erp varchar  (10)   , 
      dt_alteracao timestamp(0)   , 
      dt_inclusao timestamp(0)   , 
      descricao char  (40)   , 
 PRIMARY KEY (id)) ; 

CREATE TABLE municipio( 
      id number(10)    NOT NULL , 
      cod_erp varchar  (10)    NOT NULL , 
      descricao varchar  (200)    NOT NULL , 
      estado_id number(10)   , 
      codigo_ibge varchar  (10)   , 
 PRIMARY KEY (id)) ; 

CREATE TABLE natureza( 
      id number(10)    NOT NULL , 
      cod_erp varchar  (20)   , 
      descricao varchar(3000)   , 
      dt_alteracao timestamp(0)   , 
      dt_inclusao timestamp(0)   , 
 PRIMARY KEY (id)) ; 

CREATE TABLE negociacao( 
      id number(10)    NOT NULL , 
      filial_id number(10)   , 
      cod_erp varchar  (10)   , 
      dt_alteracao timestamp(0)   , 
      dt_inclusao timestamp(0)   , 
      cliente_id number(10)   , 
      vendedor_id number(10)   , 
      atendimento_tipo_id number(10)    NOT NULL , 
      observacao varchar(3000)   , 
      system_unit_id number(10)   , 
      system_users_id number(10)   , 
      tipo number(10)   , 
      status char   , 
      atendimento_id number(10)    NOT NULL , 
 PRIMARY KEY (id)) ; 

CREATE TABLE negociacao_titulo_receber( 
      id number(10)    NOT NULL , 
      dt_alteracao timestamp(0)   , 
      negociacao_id number(10)    NOT NULL , 
      dt_inclusao timestamp(0)   , 
      titulo_receber_id number(10)    NOT NULL , 
      vencimento date   , 
      valor binary_double   , 
 PRIMARY KEY (id)) ; 

CREATE TABLE nota_entrada( 
      id number(10)    NOT NULL , 
      filial_id number(10)   , 
      cliente_id number(10)   , 
      fornecedor_id number(10)   , 
      nota_fiscal varchar  (9)   , 
      serie_fiscal varchar  (3)   , 
      especie_fiscal varchar  (10)   , 
      condicao_id number(10)   , 
      numero_titulo varchar  (9)   , 
      prefixo_titulo varchar  (6)   , 
      dt_emissao date   , 
      tipo char  (1)   , 
      comodato char  (1)   , 
      vlr_bruto binary_double   , 
      vlr_icms binary_double   , 
      base_icms binary_double   , 
      vlr_ipi binary_double   , 
      base_ipi binary_double   , 
      vlr_mercadoria binary_double   , 
      vlr_desconto binary_double   , 
      vlr_comodato binary_double   , 
      vlr_itens binary_double   , 
      vlr_devolucao binary_double   , 
      transportadora varchar  (6)   , 
      tp_frete char  (1)   , 
      vlr_frete binary_double   , 
      vendedor1_id number(10)   , 
      vendedor2_id number(10)   , 
      chave_nfe varchar  (100)   , 
      dt_nfe date   , 
      hr_nfe varchar  (10)   , 
      mensagem_nf varchar(3000)   , 
      numero_origem varchar  (6)   , 
      serie_origem varchar  (3)   , 
      intermediador varchar  (6)   , 
      reg_ativo char  (1)   , 
      mes varchar  (2)   , 
      ano varchar  (4)   , 
      system_unit_id number(10)   , 
      date_danfe timestamp(0)   , 
      dt_inclusao timestamp(0)   , 
      dt_alteracao timestamp(0)   , 
 PRIMARY KEY (id)) ; 

CREATE TABLE nota_entrada_item( 
      id number(10)    NOT NULL , 
      nota_entrada_id number(10)    NOT NULL , 
      item varchar  (4)   , 
      produto_id number(10)   , 
      qtd binary_double   , 
      vlr_unitario binary_double   , 
      tes_id number(10)   , 
      vlr_tabela binary_double   , 
      vlr_bruto binary_double   , 
      base_icms binary_double   , 
      aliq_icms binary_double   , 
      vlr_icms binary_double   , 
      base_ipi binary_double   , 
      aliq_ipi binary_double   , 
      vlr_ipi binary_double   , 
      vlr_total binary_double   , 
      vlr_dev binary_double   , 
      qtd_dev binary_double   , 
      perc_desconto binary_double   , 
      vlr_desconto binary_double   , 
      peso binary_double   , 
      pedido_item_id number(10)   , 
      reg_ativo char  (1)   , 
      tes varchar  (3)   , 
      estoque char  (1)   , 
      financeiro char  (1)   , 
      ano varchar  (4)   , 
      mes varchar  (2)   , 
      cliente_id number(10)   , 
      vendedor1_id number(10)   , 
      vendedor2_id number(10)   , 
      dt_emissao date   , 
      cfop char  (4)   , 
      perc_comissao binary_double   , 
      comissao binary_double   , 
      comodato char  (1)   , 
 PRIMARY KEY (id)) ; 

CREATE TABLE nota_saida( 
      id number(10)    NOT NULL , 
      filial_id number(10)   , 
      cliente_id number(10)   , 
      fornecedor_id number(10)   , 
      nota_fiscal varchar  (9)   , 
      serie_fiscal varchar  (3)   , 
      especie_fiscal varchar  (10)   , 
      condicao_id number(10)   , 
      numero_titulo varchar  (9)   , 
      prefixo_titulo varchar  (6)   , 
      dt_emissao date   , 
      tipo char  (1)   , 
      comodato char  (1)   , 
      vlr_bruto binary_double   , 
      vlr_icms binary_double   , 
      base_icms binary_double   , 
      vlr_ipi binary_double   , 
      base_ipi binary_double   , 
      vlr_mercadoria binary_double   , 
      vlr_desconto binary_double   , 
      vlr_comodato binary_double   , 
      vlr_itens binary_double   , 
      vlr_devolucao binary_double   , 
      transportadora varchar  (6)   , 
      tp_frete char  (1)   , 
      vlr_frete binary_double   , 
      vendedor1_id number(10)   , 
      vendedor2_id number(10)   , 
      chave_nfe varchar  (100)   , 
      dt_nfe date   , 
      hr_nfe varchar  (10)   , 
      mensagem_nf varchar(3000)   , 
      numero_origem varchar  (6)   , 
      serie_origem varchar  (3)   , 
      intermediador varchar  (6)   , 
      reg_ativo char  (1)   , 
      mes varchar  (2)   , 
      ano varchar  (4)   , 
      system_unit_id number(10)   , 
      date_danfe timestamp(0)   , 
      dt_inclusao timestamp(0)   , 
      dt_alteracao timestamp(0)   , 
 PRIMARY KEY (id)) ; 

CREATE TABLE nota_saida_item( 
      id number(10)    NOT NULL , 
      nota_saida_id number(10)    NOT NULL , 
      item varchar  (4)   , 
      produto_id number(10)   , 
      qtd binary_double   , 
      vlr_unitario binary_double   , 
      tes_id number(10)   , 
      vlr_tabela binary_double   , 
      vlr_bruto binary_double   , 
      base_icms binary_double   , 
      aliq_icms binary_double   , 
      vlr_icms binary_double   , 
      base_ipi binary_double   , 
      aliq_ipi binary_double   , 
      vlr_ipi binary_double   , 
      vlr_total binary_double   , 
      vlr_dev binary_double   , 
      qtd_dev binary_double   , 
      perc_desconto binary_double   , 
      vlr_desconto binary_double   , 
      peso binary_double   , 
      pedido_item_id number(10)   , 
      reg_ativo char  (1)   , 
      tes varchar  (3)   , 
      estoque char  (1)   , 
      financeiro char  (1)   , 
      ano varchar  (4)   , 
      mes varchar  (2)   , 
      cliente_id number(10)   , 
      vendedor1_id number(10)   , 
      vendedor2_id number(10)   , 
      dt_emissao date   , 
      cfop char  (4)   , 
      perc_comissao binary_double   , 
      comissao binary_double   , 
      comodato char  (1)   , 
      tipo char  (1)   , 
 PRIMARY KEY (id)) ; 

CREATE TABLE notasaida_xml( 
      id number(10)    NOT NULL , 
      nota_saida_id number(10)   , 
      xml_sig varchar(3000)   , 
      xml_tss varchar(3000)   , 
      xml_cancelamento varchar(3000)   , 
      nota_fiscal varchar  (9)   , 
      serie_fiscal varchar  (3)   , 
      chave varchar  (100)   , 
      protocolo varchar  (100)   , 
      modelo varchar  (2)   , 
      destinatario varchar  (14)   , 
      remetente varchar  (14)   , 
      situcao varchar  (1)   , 
      situcao_cancelamento varchar  (1)   , 
      situcao_email varchar  (1)   , 
      email varchar  (100)   , 
      data_nfe date   , 
      hora_nfe time   , 
      ano varchar  (4)   , 
      mes varchar  (2)   , 
 PRIMARY KEY (id)) ; 

CREATE TABLE orcamento( 
      id number(10)    NOT NULL , 
      emissao timestamp(0)   , 
      retorno timestamp(0)   , 
      observacao varchar(3000)   , 
      cliente_id number(10)   , 
      tabela_preco_id number(10)   , 
      condicao_pagamento_id number(10)   , 
      pedido_id number(10)   , 
      vendedor_id number(10)   , 
      estado_id number(10)   , 
      municipio_id number(10)   , 
      codigo_cliente varchar  (8)   , 
      telefone varchar  (50)    NOT NULL , 
      nome varchar(3000)    NOT NULL , 
      email varchar  (100)   , 
      orcamento_estado_id number(10)   , 
      dt_cancelamento timestamp(0)   , 
      dt_faturamento timestamp(0)   , 
      latitude binary_double   , 
      longitude binary_double   , 
      valor_total binary_double   , 
      sinc char  (1)   , 
      log_int varchar(3000)   , 
      dt_inclusao timestamp(0)   , 
      system_unit_id number(10)   , 
      dt_alteracao timestamp(0)   , 
      system_user_id number(10)   , 
 PRIMARY KEY (id)) ; 

CREATE TABLE orcamento_estado( 
      id number(10)    NOT NULL , 
      cod_erp varchar  (10)   , 
      descricao varchar  (100)   , 
      cor varchar  (10)   , 
      cor_texto varchar  (10)   , 
      editar char  (1)   , 
      excluir char  (1)   , 
      imprimir char  (1)   , 
      cancelar char  (1)   , 
      dt_inclusao timestamp(0)   , 
      dt_alteracao timestamp(0)   , 
      sistema char  (1)   , 
      ordem number(10)   , 
      icone varchar(3000)   , 
      exibir_regua char  (1)   , 
 PRIMARY KEY (id)) ; 

CREATE TABLE orcamento_historico( 
      id number(10)    NOT NULL , 
      orcamento_id number(10)    NOT NULL , 
      orcamento_estado_id number(10)    NOT NULL , 
      observacao varchar(3000)   , 
      orcamento_proximo_estado_id number(10)   , 
      system_user_id number(10)   , 
      dt_alteracao timestamp(0)   , 
      dt_inclusao timestamp(0)   , 
      dt_evento timestamp(0)   , 
 PRIMARY KEY (id)) ; 

CREATE TABLE orcamento_item( 
      id number(10)    NOT NULL , 
      orcamento_id number(10)    NOT NULL , 
      produto_id number(10)    NOT NULL , 
      codigo_produto varchar  (30)   , 
      quantidade binary_double   , 
      preco_unit binary_double   , 
      preco_tabela binary_double   , 
      desconto binary_double   , 
      acrescimo binary_double   , 
      preco_total binary_double   , 
      dt_alteracao timestamp(0)   , 
      dt_inclusao timestamp(0)   , 
 PRIMARY KEY (id)) ; 

CREATE TABLE orcamento_proximo_estado( 
      id number(10)    NOT NULL , 
      orcamento_estado_id number(10)    NOT NULL , 
      proximo_estado_id number(10)   , 
      dt_alteracao timestamp(0)   , 
      dt_inclusao timestamp(0)   , 
 PRIMARY KEY (id)) ; 

CREATE TABLE pais( 
      id number(10)    NOT NULL , 
      cod_erp varchar  (10)   , 
      nome varchar  (100)   , 
      dt_alteracao timestamp(0)   , 
      dt_inclusao timestamp(0)   , 
      sigla varchar  (4)   , 
      comex_id varchar  (10)   , 
 PRIMARY KEY (id)) ; 

CREATE TABLE parametro( 
      id number(10)    NOT NULL , 
      filial_id number(10)   , 
      parametro varchar  (50)    NOT NULL , 
      conteudo varchar  (100)   , 
      tipo char  (1)    NOT NULL , 
 PRIMARY KEY (id)) ; 

CREATE TABLE pedido( 
      id number(10)    NOT NULL , 
      filial_id number(10)   , 
      pedido_estado_id number(10)   , 
      cliente_id number(10)    NOT NULL , 
      cliente_entrega_id number(10)   , 
      vendedor1_id number(10)    NOT NULL , 
      vendedor2_id number(10)   , 
      cod_erp char  (6)   , 
      dt_emissao date   , 
      transportadora_id number(10)   , 
      tabela_id number(10)   , 
      condicao_pagamento_id number(10)   , 
      sinc char  (1)   , 
      mes varchar  (2)   , 
      ano varchar  (4)   , 
      tipo char  (1)   , 
      nota_fiscal varchar  (9)   , 
      serie varchar  (3)   , 
      mensagem_nf varchar  (100)   , 
      tp_frete char  (1)   , 
      vlr_frete binary_double   , 
      vlr_total binary_double   , 
      vlr_comodato binary_double   , 
      presencial char  (1)   , 
      pedido_origem char  (1)   , 
      log_int varchar(3000)   , 
      user_id number(10)   , 
      intermediador_id number(10)   , 
      dt_inclusao timestamp(0)   , 
      dt_alteracao timestamp(0)   , 
      orcamento_id number(10)   , 
      nota_saida_id number(10)   , 
      system_unit_id number(10)   , 
 PRIMARY KEY (id)) ; 

CREATE TABLE pedido_estado( 
      id number(10)    NOT NULL , 
      cod_erp varchar  (2)   , 
      descricao char  (100)   , 
      cor varchar  (10)   , 
      cor_texto varchar  (10)   , 
 PRIMARY KEY (id)) ; 

CREATE TABLE pedido_item( 
      id number(10)    NOT NULL , 
      pedido_id number(10)    NOT NULL , 
      produto_id number(10)    NOT NULL , 
      item varchar  (4)   , 
      vlr_unitario binary_double   , 
      vlr_item binary_double   , 
      quantidade binary_double   , 
      per_desconto binary_double   , 
      vlr_desconto binary_double   , 
      vlr_acrescimo binary_double   , 
      vlr_total binary_double   , 
      armazem_id number(10)   , 
      tipo_movimentacao_id number(10)   , 
      dt_inclusao timestamp(0)   , 
      dt_alteracao timestamp(0)   , 
      mes varchar  (2)   , 
      ano varchar  (4)   , 
 PRIMARY KEY (id)) ; 

CREATE TABLE produto( 
      id number(10)    NOT NULL , 
      filial_id number(10)   , 
      cod_erp varchar  (30)    NOT NULL , 
      descricao varchar  (100)    NOT NULL , 
      tipo char  (2)   , 
      um char  (2)   , 
      categoria_id number(10)   , 
      sub_categoria_id number(10)   , 
      fabricante_id number(10)   , 
      armazem_id number(10)   , 
      codigo_barras varchar  (30)   , 
      codigo_fabricante char  (60)   , 
      qtd_embalagem binary_double   , 
      observacao varchar  (200)   , 
      foto varchar  (200)   , 
      status char  (1)   , 
      ncm char  (20)   , 
      origem char  (2)   , 
      peso_bruto binary_double   , 
      peso binary_double   , 
      marca varchar  (20)   , 
      te_id number(10)   , 
      ts_id number(10)   , 
      sinc char  (1)   , 
      ponto_pedido binary_double   , 
      estoque_seguranca binary_double   , 
      dt_ult_compra date   , 
      ult_preco binary_double   , 
      informacoes_tecnicas varchar(3000)   , 
      dados_tecnicos varchar(3000)   , 
      system_unit_id number(10)   , 
      dt_inclusao timestamp(0)   , 
      dt_alteracao timestamp(0)   , 
 PRIMARY KEY (id)) ; 

CREATE TABLE produto_caracteristica( 
      id number(10)    NOT NULL , 
      caracteristica_id number(10)    NOT NULL , 
      produto_id number(10)    NOT NULL , 
      des_caracteristica varchar(3000)   , 
      dt_caracteristica date   , 
      vlr_caracteristica binary_double   , 
      dt_alteracao timestamp(0)   , 
      dt_inclusao timestamp(0)   , 
 PRIMARY KEY (id)) ; 

CREATE TABLE regiao_cliente( 
      id number(10)    NOT NULL , 
      cod_erp varchar  (10)   , 
      descricao varchar(3000)   , 
      dt_alteracao timestamp(0)   , 
      dt_inclusao timestamp(0)   , 
      cor varchar(3000)   , 
 PRIMARY KEY (id)) ; 

CREATE TABLE segmento( 
      id number(10)    NOT NULL , 
      cod_erp varchar  (6)    NOT NULL , 
      descricao varchar  (200)    NOT NULL , 
      status varchar  (1)   , 
      reg_ativo varchar  (1)   , 
      dt_inclusao timestamp(0)   , 
      dt_alteracao timestamp(0)   , 
 PRIMARY KEY (id)) ; 

CREATE TABLE situacao_cadastral( 
      id number(10)    NOT NULL , 
      descricao varchar  (100)    NOT NULL , 
      motivo_bloqueio_id number(10)   , 
      dt_alteracao timestamp(0)   , 
      dt_inclusao timestamp(0)   , 
 PRIMARY KEY (id)) ; 

CREATE TABLE step( 
      id number(10)    NOT NULL , 
      grupo char  (20)   , 
      sequencia number(10)   , 
      variavel char  (2)   , 
      descricao char  (20)   , 
      cor char  (20)   , 
      column_6 number(10)   , 
 PRIMARY KEY (id)) ; 

CREATE TABLE sub_categoria( 
      id number(10)    NOT NULL , 
      categoria_id number(10)    NOT NULL , 
      cod_erp varchar  (6)    NOT NULL , 
      descricao varchar  (200)    NOT NULL , 
      status char  (1)   , 
      dt_inclusao timestamp(0)   , 
      dt_alteracao timestamp(0)   , 
 PRIMARY KEY (id)) ; 

CREATE TABLE supervisor( 
      filial_id number(10)   , 
      id number(10)    NOT NULL , 
      cod_erp varchar  (6)   , 
      system_users_id number(10)   , 
      nome varchar  (100)    NOT NULL , 
      nome_reduzido varchar  (50)   , 
      ddd varchar  (3)   , 
      telefone varchar  (15)   , 
      celular varchar  (15)   , 
      email varchar  (100)   , 
      status char  (1)   , 
      vendedor varchar  (1)   , 
      dashboard char  (1)   , 
      dt_alteracao timestamp(0)   , 
      dt_inclusao timestamp(0)   , 
      dt_nascmento date   , 
      system_unit_id number(10)   , 
      desligado char  (1)   , 
 PRIMARY KEY (id)) ; 

CREATE TABLE supervisor_vendedor( 
      id number(10)    NOT NULL , 
      vendedor_id number(10)    NOT NULL , 
      supervisor_id number(10)    NOT NULL , 
      sistema char  (1)   , 
      dt_alteracao timestamp(0)   , 
      dt_inclusao timestamp(0)   , 
 PRIMARY KEY (id)) ; 

CREATE TABLE tabela_preco( 
      id number(10)    NOT NULL , 
      empresa_id number(10)   , 
      filial_id number(10)   , 
      cod_erp varchar  (3)    NOT NULL , 
      descricao varchar  (50)    NOT NULL , 
      status char  (1)   , 
      dt_inicio date   , 
      dt_fim date   , 
      dt_inclusao timestamp(0)   , 
      dt_alteracao timestamp(0)   , 
      utiliza char  (1)   , 
      system_unit_id number(10)   , 
 PRIMARY KEY (id)) ; 

CREATE TABLE tabela_preco_item( 
      id number(10)    NOT NULL , 
      item number(10)   , 
      tabela_preco_id number(10)    NOT NULL , 
      produto_id number(10)    NOT NULL , 
      preco binary_double   , 
      status char  (1)   , 
      dt_alteracao timestamp(0)   , 
      dt_inclusao timestamp(0)   , 
 PRIMARY KEY (id)) ; 

CREATE TABLE tipo_cliente( 
      id number(10)    NOT NULL , 
      cod_erp varchar  (6)   , 
      descricao varchar  (50)   , 
      status char  (1)   , 
 PRIMARY KEY (id)) ; 

CREATE TABLE tipo_contato( 
      id number(10)    NOT NULL , 
      cod_erp varchar  (10)   , 
      descricao varchar  (50)   , 
      dt_alteracao timestamp(0)   , 
      dt_inclusao timestamp(0)   , 
 PRIMARY KEY (id)) ; 

CREATE TABLE tipo_entrada_saida( 
      id number(10)    NOT NULL , 
      filial_id number(10)   , 
      empresa_id number(10)   , 
      cod_erp varchar  (10)    NOT NULL , 
      tipo char  (1)   , 
      descricao varchar  (100)    NOT NULL , 
      finalidade varchar  (100)   , 
      status char  (1)   , 
      cfop char  (4)   , 
      dt_alteracao timestamp(0)   , 
      dt_inclusao timestamp(0)   , 
      system_unit_id number(10)   , 
 PRIMARY KEY (id)) ; 

CREATE TABLE titulo_receber( 
      id number(10)    NOT NULL , 
      dt_alteracao timestamp(0)   , 
      filial_id number(10)   , 
      dt_inclusao timestamp(0)   , 
      cliente_id number(10)    NOT NULL , 
      vendedor_id number(10)   , 
      natureza_id number(10)   , 
      emissao date    NOT NULL , 
      numero char  (9)    NOT NULL , 
      parcela char  (3)    NOT NULL , 
      prefixo char  (3)    NOT NULL , 
      tipo char  (3)   , 
      saldo binary_double   , 
      valor binary_double   , 
      decrescimo binary_double   , 
      acrescimo binary_double   , 
      valor_juros binary_double   , 
      perc_juros binary_double   , 
      mora_dia binary_double   , 
      taxa_multa binary_double   , 
      dt_digitacao date   , 
      vencimento date    NOT NULL , 
      venc_real date    NOT NULL , 
      venc_orig date   , 
      pedido_id number(10)   , 
      banco char  (3)   , 
      agencia char  (10)   , 
      conta char  (20)   , 
      nosso_numero char  (50)   , 
      id_cnab char  (20)   , 
      cod_barras char  (50)   , 
      lin_digitavel varchar  (50)   , 
      forma_pgto char  (1)   , 
      controle_bco char  (02)   , 
      dig_nosso_numero char  (1)   , 
      impresso char  (1)   , 
      origem char  (10)   , 
      historico varchar(3000)   , 
      usr_inclusao char  (10)   , 
      usr_alteracao char  (10)   , 
      reg_ativo char  (1)   , 
      baixa date   , 
      system_unit_id number(10)   , 
      e1_recno number(10)   , 
      nota_fiscal_id number(10)   , 
      vias number(10)   , 
      situacao char  (1)   , 
 PRIMARY KEY (id)) ; 

CREATE TABLE transportadora( 
      id number(10)    NOT NULL , 
      filial_id number(10)   , 
      cod_erp char  (6)   , 
      descricao varchar  (100)   , 
      status char  (1)   , 
      dt_inclusao timestamp(0)   , 
      dt_alteracao timestamp(0)   , 
      system_unit_id number(10)   , 
 PRIMARY KEY (id)) ; 

CREATE TABLE venda_mes_cliente( 
      id number(10)    NOT NULL , 
      filial_id number(10)   , 
      empresa_id number(10)   , 
      cliente_id number(10)    NOT NULL , 
      ano char  (4)    NOT NULL , 
      janeiro binary_double    DEFAULT 0 , 
      fevereiro binary_double    DEFAULT 0 , 
      mes char  (2)   , 
      marco binary_double    DEFAULT 0 , 
      abril binary_double    DEFAULT 0 , 
      maio binary_double    DEFAULT 0 , 
      junho binary_double    DEFAULT 0 , 
      julho binary_double    DEFAULT 0 , 
      agosto binary_double    DEFAULT 0 , 
      setembro binary_double    DEFAULT 0 , 
      outubro binary_double    DEFAULT 0 , 
      novembro binary_double    DEFAULT 0 , 
      dezembro binary_double    DEFAULT 0 , 
      cliente_nome varchar  (100)   , 
 PRIMARY KEY (id)) ; 

CREATE TABLE venda_mes_produto( 
      id number(10)    NOT NULL , 
      filial_id number(10)   , 
      empresa_id number(10)   , 
      produto_id number(10)    NOT NULL , 
      ano char  (4)    NOT NULL , 
      janeiro binary_double    DEFAULT 0 , 
      fevereiro binary_double    DEFAULT 0 , 
      marco binary_double    DEFAULT 0 , 
      abril binary_double    DEFAULT 0 , 
      maio binary_double    DEFAULT 0 , 
      junho binary_double    DEFAULT 0 , 
      julho binary_double    DEFAULT 0 , 
      agosto binary_double    DEFAULT 0 , 
      setembro binary_double    DEFAULT 0 , 
      outubro binary_double    DEFAULT 0 , 
      novembro binary_double    DEFAULT 0 , 
      dezembro binary_double    DEFAULT 0 , 
      produto_nome varchar  (100)   , 
 PRIMARY KEY (id)) ; 

CREATE TABLE vendedor( 
      id number(10)    NOT NULL , 
      filial_id number(10)   , 
      cod_erp varchar  (6)   , 
      system_users_id number(10)   , 
      nome varchar  (100)    NOT NULL , 
      nome_reduzido varchar  (50)   , 
      ddd varchar  (3)   , 
      telefone varchar  (15)   , 
      celular varchar  (15)   , 
      email varchar  (100)   , 
      status char  (1)   , 
      vendedor varchar  (1)   , 
      dashboard char  (1)   , 
      dt_alteracao timestamp(0)   , 
      dt_inclusao timestamp(0)   , 
      dt_nascmento date   , 
      system_unit_id number(10)   , 
      supervisor char  (1)   , 
      supervisor_id number(10)   , 
      desligado char  (1)   , 
 PRIMARY KEY (id)) ; 

CREATE TABLE vendedor_atendimento( 
      id number(10)    NOT NULL , 
      vendedor_id number(10)    NOT NULL , 
      dt_alteracao timestamp(0)   , 
      dt_inclusao timestamp(0)   , 
      inicial time   , 
      final time   , 
      tipo char  (1)   , 
      dias_semana varchar  (20)   , 
 PRIMARY KEY (id)) ; 

CREATE TABLE vendedor_cliente( 
      id number(10)    NOT NULL , 
      vendedor_id number(10)    NOT NULL , 
      cliente_id number(10)    NOT NULL , 
      status char  (1)   , 
 PRIMARY KEY (id)) ; 

 
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
 CREATE SEQUENCE armazem_id_seq START WITH 1 INCREMENT BY 1; 

CREATE OR REPLACE TRIGGER armazem_id_seq_tr 

BEFORE INSERT ON armazem FOR EACH ROW 

    WHEN 

        (NEW.id IS NULL) 

    BEGIN 

        SELECT armazem_id_seq.NEXTVAL INTO :NEW.id FROM DUAL; 

END;
CREATE SEQUENCE atendimento_id_seq START WITH 1 INCREMENT BY 1; 

CREATE OR REPLACE TRIGGER atendimento_id_seq_tr 

BEFORE INSERT ON atendimento FOR EACH ROW 

    WHEN 

        (NEW.id IS NULL) 

    BEGIN 

        SELECT atendimento_id_seq.NEXTVAL INTO :NEW.id FROM DUAL; 

END;
CREATE SEQUENCE atendimento_tipo_id_seq START WITH 1 INCREMENT BY 1; 

CREATE OR REPLACE TRIGGER atendimento_tipo_id_seq_tr 

BEFORE INSERT ON atendimento_tipo FOR EACH ROW 

    WHEN 

        (NEW.id IS NULL) 

    BEGIN 

        SELECT atendimento_tipo_id_seq.NEXTVAL INTO :NEW.id FROM DUAL; 

END;
CREATE SEQUENCE blog_aniversarios_id_seq START WITH 1 INCREMENT BY 1; 

CREATE OR REPLACE TRIGGER blog_aniversarios_id_seq_tr 

BEFORE INSERT ON blog_aniversarios FOR EACH ROW 

    WHEN 

        (NEW.id IS NULL) 

    BEGIN 

        SELECT blog_aniversarios_id_seq.NEXTVAL INTO :NEW.id FROM DUAL; 

END;
CREATE SEQUENCE blog_comunicados_id_seq START WITH 1 INCREMENT BY 1; 

CREATE OR REPLACE TRIGGER blog_comunicados_id_seq_tr 

BEFORE INSERT ON blog_comunicados FOR EACH ROW 

    WHEN 

        (NEW.id IS NULL) 

    BEGIN 

        SELECT blog_comunicados_id_seq.NEXTVAL INTO :NEW.id FROM DUAL; 

END;
CREATE SEQUENCE blog_noticias_id_seq START WITH 1 INCREMENT BY 1; 

CREATE OR REPLACE TRIGGER blog_noticias_id_seq_tr 

BEFORE INSERT ON blog_noticias FOR EACH ROW 

    WHEN 

        (NEW.id IS NULL) 

    BEGIN 

        SELECT blog_noticias_id_seq.NEXTVAL INTO :NEW.id FROM DUAL; 

END;
CREATE SEQUENCE calendario_orcamento_id_seq START WITH 1 INCREMENT BY 1; 

CREATE OR REPLACE TRIGGER calendario_orcamento_id_seq_tr 

BEFORE INSERT ON calendario_orcamento FOR EACH ROW 

    WHEN 

        (NEW.id IS NULL) 

    BEGIN 

        SELECT calendario_orcamento_id_seq.NEXTVAL INTO :NEW.id FROM DUAL; 

END;
CREATE SEQUENCE caracteristica_id_seq START WITH 1 INCREMENT BY 1; 

CREATE OR REPLACE TRIGGER caracteristica_id_seq_tr 

BEFORE INSERT ON caracteristica FOR EACH ROW 

    WHEN 

        (NEW.id IS NULL) 

    BEGIN 

        SELECT caracteristica_id_seq.NEXTVAL INTO :NEW.id FROM DUAL; 

END;
CREATE SEQUENCE categoria_id_seq START WITH 1 INCREMENT BY 1; 

CREATE OR REPLACE TRIGGER categoria_id_seq_tr 

BEFORE INSERT ON categoria FOR EACH ROW 

    WHEN 

        (NEW.id IS NULL) 

    BEGIN 

        SELECT categoria_id_seq.NEXTVAL INTO :NEW.id FROM DUAL; 

END;
CREATE SEQUENCE cep_id_seq START WITH 1 INCREMENT BY 1; 

CREATE OR REPLACE TRIGGER cep_id_seq_tr 

BEFORE INSERT ON cep FOR EACH ROW 

    WHEN 

        (NEW.id IS NULL) 

    BEGIN 

        SELECT cep_id_seq.NEXTVAL INTO :NEW.id FROM DUAL; 

END;
CREATE SEQUENCE cliente_id_seq START WITH 1 INCREMENT BY 1; 

CREATE OR REPLACE TRIGGER cliente_id_seq_tr 

BEFORE INSERT ON cliente FOR EACH ROW 

    WHEN 

        (NEW.id IS NULL) 

    BEGIN 

        SELECT cliente_id_seq.NEXTVAL INTO :NEW.id FROM DUAL; 

END;
CREATE SEQUENCE cliente_acesso_id_seq START WITH 1 INCREMENT BY 1; 

CREATE OR REPLACE TRIGGER cliente_acesso_id_seq_tr 

BEFORE INSERT ON cliente_acesso FOR EACH ROW 

    WHEN 

        (NEW.id IS NULL) 

    BEGIN 

        SELECT cliente_acesso_id_seq.NEXTVAL INTO :NEW.id FROM DUAL; 

END;
CREATE SEQUENCE cliente_atendimento_id_seq START WITH 1 INCREMENT BY 1; 

CREATE OR REPLACE TRIGGER cliente_atendimento_id_seq_tr 

BEFORE INSERT ON cliente_atendimento FOR EACH ROW 

    WHEN 

        (NEW.id IS NULL) 

    BEGIN 

        SELECT cliente_atendimento_id_seq.NEXTVAL INTO :NEW.id FROM DUAL; 

END;
CREATE SEQUENCE cliente_atualizacao_id_seq START WITH 1 INCREMENT BY 1; 

CREATE OR REPLACE TRIGGER cliente_atualizacao_id_seq_tr 

BEFORE INSERT ON cliente_atualizacao FOR EACH ROW 

    WHEN 

        (NEW.id IS NULL) 

    BEGIN 

        SELECT cliente_atualizacao_id_seq.NEXTVAL INTO :NEW.id FROM DUAL; 

END;
CREATE SEQUENCE cliente_cnae_id_seq START WITH 1 INCREMENT BY 1; 

CREATE OR REPLACE TRIGGER cliente_cnae_id_seq_tr 

BEFORE INSERT ON cliente_cnae FOR EACH ROW 

    WHEN 

        (NEW.id IS NULL) 

    BEGIN 

        SELECT cliente_cnae_id_seq.NEXTVAL INTO :NEW.id FROM DUAL; 

END;
CREATE SEQUENCE cliente_condicao_id_seq START WITH 1 INCREMENT BY 1; 

CREATE OR REPLACE TRIGGER cliente_condicao_id_seq_tr 

BEFORE INSERT ON cliente_condicao FOR EACH ROW 

    WHEN 

        (NEW.id IS NULL) 

    BEGIN 

        SELECT cliente_condicao_id_seq.NEXTVAL INTO :NEW.id FROM DUAL; 

END;
CREATE SEQUENCE cliente_contato_id_seq START WITH 1 INCREMENT BY 1; 

CREATE OR REPLACE TRIGGER cliente_contato_id_seq_tr 

BEFORE INSERT ON cliente_contato FOR EACH ROW 

    WHEN 

        (NEW.id IS NULL) 

    BEGIN 

        SELECT cliente_contato_id_seq.NEXTVAL INTO :NEW.id FROM DUAL; 

END;
CREATE SEQUENCE cliente_historico_id_seq START WITH 1 INCREMENT BY 1; 

CREATE OR REPLACE TRIGGER cliente_historico_id_seq_tr 

BEFORE INSERT ON cliente_historico FOR EACH ROW 

    WHEN 

        (NEW.id IS NULL) 

    BEGIN 

        SELECT cliente_historico_id_seq.NEXTVAL INTO :NEW.id FROM DUAL; 

END;
CREATE SEQUENCE cliente_socios_id_seq START WITH 1 INCREMENT BY 1; 

CREATE OR REPLACE TRIGGER cliente_socios_id_seq_tr 

BEFORE INSERT ON cliente_socios FOR EACH ROW 

    WHEN 

        (NEW.id IS NULL) 

    BEGIN 

        SELECT cliente_socios_id_seq.NEXTVAL INTO :NEW.id FROM DUAL; 

END;
CREATE SEQUENCE cliente_vendedor_mes_id_seq START WITH 1 INCREMENT BY 1; 

CREATE OR REPLACE TRIGGER cliente_vendedor_mes_id_seq_tr 

BEFORE INSERT ON cliente_vendedor_mes FOR EACH ROW 

    WHEN 

        (NEW.id IS NULL) 

    BEGIN 

        SELECT cliente_vendedor_mes_id_seq.NEXTVAL INTO :NEW.id FROM DUAL; 

END;
CREATE SEQUENCE cnae_id_seq START WITH 1 INCREMENT BY 1; 

CREATE OR REPLACE TRIGGER cnae_id_seq_tr 

BEFORE INSERT ON cnae FOR EACH ROW 

    WHEN 

        (NEW.id IS NULL) 

    BEGIN 

        SELECT cnae_id_seq.NEXTVAL INTO :NEW.id FROM DUAL; 

END;
CREATE SEQUENCE condicao_pagamento_id_seq START WITH 1 INCREMENT BY 1; 

CREATE OR REPLACE TRIGGER condicao_pagamento_id_seq_tr 

BEFORE INSERT ON condicao_pagamento FOR EACH ROW 

    WHEN 

        (NEW.id IS NULL) 

    BEGIN 

        SELECT condicao_pagamento_id_seq.NEXTVAL INTO :NEW.id FROM DUAL; 

END;
CREATE SEQUENCE empresa_id_seq START WITH 1 INCREMENT BY 1; 

CREATE OR REPLACE TRIGGER empresa_id_seq_tr 

BEFORE INSERT ON empresa FOR EACH ROW 

    WHEN 

        (NEW.id IS NULL) 

    BEGIN 

        SELECT empresa_id_seq.NEXTVAL INTO :NEW.id FROM DUAL; 

END;
CREATE SEQUENCE estado_id_seq START WITH 1 INCREMENT BY 1; 

CREATE OR REPLACE TRIGGER estado_id_seq_tr 

BEFORE INSERT ON estado FOR EACH ROW 

    WHEN 

        (NEW.id IS NULL) 

    BEGIN 

        SELECT estado_id_seq.NEXTVAL INTO :NEW.id FROM DUAL; 

END;
CREATE SEQUENCE estoque_id_seq START WITH 1 INCREMENT BY 1; 

CREATE OR REPLACE TRIGGER estoque_id_seq_tr 

BEFORE INSERT ON estoque FOR EACH ROW 

    WHEN 

        (NEW.id IS NULL) 

    BEGIN 

        SELECT estoque_id_seq.NEXTVAL INTO :NEW.id FROM DUAL; 

END;
CREATE SEQUENCE fabricante_id_seq START WITH 1 INCREMENT BY 1; 

CREATE OR REPLACE TRIGGER fabricante_id_seq_tr 

BEFORE INSERT ON fabricante FOR EACH ROW 

    WHEN 

        (NEW.id IS NULL) 

    BEGIN 

        SELECT fabricante_id_seq.NEXTVAL INTO :NEW.id FROM DUAL; 

END;
CREATE SEQUENCE ficha_tecnica_id_seq START WITH 1 INCREMENT BY 1; 

CREATE OR REPLACE TRIGGER ficha_tecnica_id_seq_tr 

BEFORE INSERT ON ficha_tecnica FOR EACH ROW 

    WHEN 

        (NEW.id IS NULL) 

    BEGIN 

        SELECT ficha_tecnica_id_seq.NEXTVAL INTO :NEW.id FROM DUAL; 

END;
CREATE SEQUENCE filial_id_seq START WITH 1 INCREMENT BY 1; 

CREATE OR REPLACE TRIGGER filial_id_seq_tr 

BEFORE INSERT ON filial FOR EACH ROW 

    WHEN 

        (NEW.id IS NULL) 

    BEGIN 

        SELECT filial_id_seq.NEXTVAL INTO :NEW.id FROM DUAL; 

END;
CREATE SEQUENCE fornecedor_id_seq START WITH 1 INCREMENT BY 1; 

CREATE OR REPLACE TRIGGER fornecedor_id_seq_tr 

BEFORE INSERT ON fornecedor FOR EACH ROW 

    WHEN 

        (NEW.id IS NULL) 

    BEGIN 

        SELECT fornecedor_id_seq.NEXTVAL INTO :NEW.id FROM DUAL; 

END;
CREATE SEQUENCE meta_vendedor_categoria_id_seq START WITH 1 INCREMENT BY 1; 

CREATE OR REPLACE TRIGGER meta_vendedor_categoria_id_seq_tr 

BEFORE INSERT ON meta_vendedor_categoria FOR EACH ROW 

    WHEN 

        (NEW.id IS NULL) 

    BEGIN 

        SELECT meta_vendedor_categoria_id_seq.NEXTVAL INTO :NEW.id FROM DUAL; 

END;
CREATE SEQUENCE meta_vendedor_mes_id_seq START WITH 1 INCREMENT BY 1; 

CREATE OR REPLACE TRIGGER meta_vendedor_mes_id_seq_tr 

BEFORE INSERT ON meta_vendedor_mes FOR EACH ROW 

    WHEN 

        (NEW.id IS NULL) 

    BEGIN 

        SELECT meta_vendedor_mes_id_seq.NEXTVAL INTO :NEW.id FROM DUAL; 

END;
CREATE SEQUENCE motivo_bloqueio_id_seq START WITH 1 INCREMENT BY 1; 

CREATE OR REPLACE TRIGGER motivo_bloqueio_id_seq_tr 

BEFORE INSERT ON motivo_bloqueio FOR EACH ROW 

    WHEN 

        (NEW.id IS NULL) 

    BEGIN 

        SELECT motivo_bloqueio_id_seq.NEXTVAL INTO :NEW.id FROM DUAL; 

END;
CREATE SEQUENCE municipio_id_seq START WITH 1 INCREMENT BY 1; 

CREATE OR REPLACE TRIGGER municipio_id_seq_tr 

BEFORE INSERT ON municipio FOR EACH ROW 

    WHEN 

        (NEW.id IS NULL) 

    BEGIN 

        SELECT municipio_id_seq.NEXTVAL INTO :NEW.id FROM DUAL; 

END;
CREATE SEQUENCE natureza_id_seq START WITH 1 INCREMENT BY 1; 

CREATE OR REPLACE TRIGGER natureza_id_seq_tr 

BEFORE INSERT ON natureza FOR EACH ROW 

    WHEN 

        (NEW.id IS NULL) 

    BEGIN 

        SELECT natureza_id_seq.NEXTVAL INTO :NEW.id FROM DUAL; 

END;
CREATE SEQUENCE negociacao_id_seq START WITH 1 INCREMENT BY 1; 

CREATE OR REPLACE TRIGGER negociacao_id_seq_tr 

BEFORE INSERT ON negociacao FOR EACH ROW 

    WHEN 

        (NEW.id IS NULL) 

    BEGIN 

        SELECT negociacao_id_seq.NEXTVAL INTO :NEW.id FROM DUAL; 

END;
CREATE SEQUENCE negociacao_titulo_receber_id_seq START WITH 1 INCREMENT BY 1; 

CREATE OR REPLACE TRIGGER negociacao_titulo_receber_id_seq_tr 

BEFORE INSERT ON negociacao_titulo_receber FOR EACH ROW 

    WHEN 

        (NEW.id IS NULL) 

    BEGIN 

        SELECT negociacao_titulo_receber_id_seq.NEXTVAL INTO :NEW.id FROM DUAL; 

END;
CREATE SEQUENCE nota_entrada_id_seq START WITH 1 INCREMENT BY 1; 

CREATE OR REPLACE TRIGGER nota_entrada_id_seq_tr 

BEFORE INSERT ON nota_entrada FOR EACH ROW 

    WHEN 

        (NEW.id IS NULL) 

    BEGIN 

        SELECT nota_entrada_id_seq.NEXTVAL INTO :NEW.id FROM DUAL; 

END;
CREATE SEQUENCE nota_entrada_item_id_seq START WITH 1 INCREMENT BY 1; 

CREATE OR REPLACE TRIGGER nota_entrada_item_id_seq_tr 

BEFORE INSERT ON nota_entrada_item FOR EACH ROW 

    WHEN 

        (NEW.id IS NULL) 

    BEGIN 

        SELECT nota_entrada_item_id_seq.NEXTVAL INTO :NEW.id FROM DUAL; 

END;
CREATE SEQUENCE nota_saida_id_seq START WITH 1 INCREMENT BY 1; 

CREATE OR REPLACE TRIGGER nota_saida_id_seq_tr 

BEFORE INSERT ON nota_saida FOR EACH ROW 

    WHEN 

        (NEW.id IS NULL) 

    BEGIN 

        SELECT nota_saida_id_seq.NEXTVAL INTO :NEW.id FROM DUAL; 

END;
CREATE SEQUENCE nota_saida_item_id_seq START WITH 1 INCREMENT BY 1; 

CREATE OR REPLACE TRIGGER nota_saida_item_id_seq_tr 

BEFORE INSERT ON nota_saida_item FOR EACH ROW 

    WHEN 

        (NEW.id IS NULL) 

    BEGIN 

        SELECT nota_saida_item_id_seq.NEXTVAL INTO :NEW.id FROM DUAL; 

END;
CREATE SEQUENCE notasaida_xml_id_seq START WITH 1 INCREMENT BY 1; 

CREATE OR REPLACE TRIGGER notasaida_xml_id_seq_tr 

BEFORE INSERT ON notasaida_xml FOR EACH ROW 

    WHEN 

        (NEW.id IS NULL) 

    BEGIN 

        SELECT notasaida_xml_id_seq.NEXTVAL INTO :NEW.id FROM DUAL; 

END;
CREATE SEQUENCE orcamento_id_seq START WITH 1 INCREMENT BY 1; 

CREATE OR REPLACE TRIGGER orcamento_id_seq_tr 

BEFORE INSERT ON orcamento FOR EACH ROW 

    WHEN 

        (NEW.id IS NULL) 

    BEGIN 

        SELECT orcamento_id_seq.NEXTVAL INTO :NEW.id FROM DUAL; 

END;
CREATE SEQUENCE orcamento_estado_id_seq START WITH 1 INCREMENT BY 1; 

CREATE OR REPLACE TRIGGER orcamento_estado_id_seq_tr 

BEFORE INSERT ON orcamento_estado FOR EACH ROW 

    WHEN 

        (NEW.id IS NULL) 

    BEGIN 

        SELECT orcamento_estado_id_seq.NEXTVAL INTO :NEW.id FROM DUAL; 

END;
CREATE SEQUENCE orcamento_historico_id_seq START WITH 1 INCREMENT BY 1; 

CREATE OR REPLACE TRIGGER orcamento_historico_id_seq_tr 

BEFORE INSERT ON orcamento_historico FOR EACH ROW 

    WHEN 

        (NEW.id IS NULL) 

    BEGIN 

        SELECT orcamento_historico_id_seq.NEXTVAL INTO :NEW.id FROM DUAL; 

END;
CREATE SEQUENCE orcamento_item_id_seq START WITH 1 INCREMENT BY 1; 

CREATE OR REPLACE TRIGGER orcamento_item_id_seq_tr 

BEFORE INSERT ON orcamento_item FOR EACH ROW 

    WHEN 

        (NEW.id IS NULL) 

    BEGIN 

        SELECT orcamento_item_id_seq.NEXTVAL INTO :NEW.id FROM DUAL; 

END;
CREATE SEQUENCE orcamento_proximo_estado_id_seq START WITH 1 INCREMENT BY 1; 

CREATE OR REPLACE TRIGGER orcamento_proximo_estado_id_seq_tr 

BEFORE INSERT ON orcamento_proximo_estado FOR EACH ROW 

    WHEN 

        (NEW.id IS NULL) 

    BEGIN 

        SELECT orcamento_proximo_estado_id_seq.NEXTVAL INTO :NEW.id FROM DUAL; 

END;
CREATE SEQUENCE pais_id_seq START WITH 1 INCREMENT BY 1; 

CREATE OR REPLACE TRIGGER pais_id_seq_tr 

BEFORE INSERT ON pais FOR EACH ROW 

    WHEN 

        (NEW.id IS NULL) 

    BEGIN 

        SELECT pais_id_seq.NEXTVAL INTO :NEW.id FROM DUAL; 

END;
CREATE SEQUENCE parametro_id_seq START WITH 1 INCREMENT BY 1; 

CREATE OR REPLACE TRIGGER parametro_id_seq_tr 

BEFORE INSERT ON parametro FOR EACH ROW 

    WHEN 

        (NEW.id IS NULL) 

    BEGIN 

        SELECT parametro_id_seq.NEXTVAL INTO :NEW.id FROM DUAL; 

END;
CREATE SEQUENCE pedido_id_seq START WITH 1 INCREMENT BY 1; 

CREATE OR REPLACE TRIGGER pedido_id_seq_tr 

BEFORE INSERT ON pedido FOR EACH ROW 

    WHEN 

        (NEW.id IS NULL) 

    BEGIN 

        SELECT pedido_id_seq.NEXTVAL INTO :NEW.id FROM DUAL; 

END;
CREATE SEQUENCE pedido_estado_id_seq START WITH 1 INCREMENT BY 1; 

CREATE OR REPLACE TRIGGER pedido_estado_id_seq_tr 

BEFORE INSERT ON pedido_estado FOR EACH ROW 

    WHEN 

        (NEW.id IS NULL) 

    BEGIN 

        SELECT pedido_estado_id_seq.NEXTVAL INTO :NEW.id FROM DUAL; 

END;
CREATE SEQUENCE pedido_item_id_seq START WITH 1 INCREMENT BY 1; 

CREATE OR REPLACE TRIGGER pedido_item_id_seq_tr 

BEFORE INSERT ON pedido_item FOR EACH ROW 

    WHEN 

        (NEW.id IS NULL) 

    BEGIN 

        SELECT pedido_item_id_seq.NEXTVAL INTO :NEW.id FROM DUAL; 

END;
CREATE SEQUENCE produto_id_seq START WITH 1 INCREMENT BY 1; 

CREATE OR REPLACE TRIGGER produto_id_seq_tr 

BEFORE INSERT ON produto FOR EACH ROW 

    WHEN 

        (NEW.id IS NULL) 

    BEGIN 

        SELECT produto_id_seq.NEXTVAL INTO :NEW.id FROM DUAL; 

END;
CREATE SEQUENCE produto_caracteristica_id_seq START WITH 1 INCREMENT BY 1; 

CREATE OR REPLACE TRIGGER produto_caracteristica_id_seq_tr 

BEFORE INSERT ON produto_caracteristica FOR EACH ROW 

    WHEN 

        (NEW.id IS NULL) 

    BEGIN 

        SELECT produto_caracteristica_id_seq.NEXTVAL INTO :NEW.id FROM DUAL; 

END;
CREATE SEQUENCE regiao_cliente_id_seq START WITH 1 INCREMENT BY 1; 

CREATE OR REPLACE TRIGGER regiao_cliente_id_seq_tr 

BEFORE INSERT ON regiao_cliente FOR EACH ROW 

    WHEN 

        (NEW.id IS NULL) 

    BEGIN 

        SELECT regiao_cliente_id_seq.NEXTVAL INTO :NEW.id FROM DUAL; 

END;
CREATE SEQUENCE segmento_id_seq START WITH 1 INCREMENT BY 1; 

CREATE OR REPLACE TRIGGER segmento_id_seq_tr 

BEFORE INSERT ON segmento FOR EACH ROW 

    WHEN 

        (NEW.id IS NULL) 

    BEGIN 

        SELECT segmento_id_seq.NEXTVAL INTO :NEW.id FROM DUAL; 

END;
CREATE SEQUENCE situacao_cadastral_id_seq START WITH 1 INCREMENT BY 1; 

CREATE OR REPLACE TRIGGER situacao_cadastral_id_seq_tr 

BEFORE INSERT ON situacao_cadastral FOR EACH ROW 

    WHEN 

        (NEW.id IS NULL) 

    BEGIN 

        SELECT situacao_cadastral_id_seq.NEXTVAL INTO :NEW.id FROM DUAL; 

END;
CREATE SEQUENCE step_id_seq START WITH 1 INCREMENT BY 1; 

CREATE OR REPLACE TRIGGER step_id_seq_tr 

BEFORE INSERT ON step FOR EACH ROW 

    WHEN 

        (NEW.id IS NULL) 

    BEGIN 

        SELECT step_id_seq.NEXTVAL INTO :NEW.id FROM DUAL; 

END;
CREATE SEQUENCE sub_categoria_id_seq START WITH 1 INCREMENT BY 1; 

CREATE OR REPLACE TRIGGER sub_categoria_id_seq_tr 

BEFORE INSERT ON sub_categoria FOR EACH ROW 

    WHEN 

        (NEW.id IS NULL) 

    BEGIN 

        SELECT sub_categoria_id_seq.NEXTVAL INTO :NEW.id FROM DUAL; 

END;
CREATE SEQUENCE supervisor_id_seq START WITH 1 INCREMENT BY 1; 

CREATE OR REPLACE TRIGGER supervisor_id_seq_tr 

BEFORE INSERT ON supervisor FOR EACH ROW 

    WHEN 

        (NEW.id IS NULL) 

    BEGIN 

        SELECT supervisor_id_seq.NEXTVAL INTO :NEW.id FROM DUAL; 

END;
CREATE SEQUENCE supervisor_vendedor_id_seq START WITH 1 INCREMENT BY 1; 

CREATE OR REPLACE TRIGGER supervisor_vendedor_id_seq_tr 

BEFORE INSERT ON supervisor_vendedor FOR EACH ROW 

    WHEN 

        (NEW.id IS NULL) 

    BEGIN 

        SELECT supervisor_vendedor_id_seq.NEXTVAL INTO :NEW.id FROM DUAL; 

END;
CREATE SEQUENCE tabela_preco_id_seq START WITH 1 INCREMENT BY 1; 

CREATE OR REPLACE TRIGGER tabela_preco_id_seq_tr 

BEFORE INSERT ON tabela_preco FOR EACH ROW 

    WHEN 

        (NEW.id IS NULL) 

    BEGIN 

        SELECT tabela_preco_id_seq.NEXTVAL INTO :NEW.id FROM DUAL; 

END;
CREATE SEQUENCE tabela_preco_item_id_seq START WITH 1 INCREMENT BY 1; 

CREATE OR REPLACE TRIGGER tabela_preco_item_id_seq_tr 

BEFORE INSERT ON tabela_preco_item FOR EACH ROW 

    WHEN 

        (NEW.id IS NULL) 

    BEGIN 

        SELECT tabela_preco_item_id_seq.NEXTVAL INTO :NEW.id FROM DUAL; 

END;
CREATE SEQUENCE tipo_cliente_id_seq START WITH 1 INCREMENT BY 1; 

CREATE OR REPLACE TRIGGER tipo_cliente_id_seq_tr 

BEFORE INSERT ON tipo_cliente FOR EACH ROW 

    WHEN 

        (NEW.id IS NULL) 

    BEGIN 

        SELECT tipo_cliente_id_seq.NEXTVAL INTO :NEW.id FROM DUAL; 

END;
CREATE SEQUENCE tipo_contato_id_seq START WITH 1 INCREMENT BY 1; 

CREATE OR REPLACE TRIGGER tipo_contato_id_seq_tr 

BEFORE INSERT ON tipo_contato FOR EACH ROW 

    WHEN 

        (NEW.id IS NULL) 

    BEGIN 

        SELECT tipo_contato_id_seq.NEXTVAL INTO :NEW.id FROM DUAL; 

END;
CREATE SEQUENCE tipo_entrada_saida_id_seq START WITH 1 INCREMENT BY 1; 

CREATE OR REPLACE TRIGGER tipo_entrada_saida_id_seq_tr 

BEFORE INSERT ON tipo_entrada_saida FOR EACH ROW 

    WHEN 

        (NEW.id IS NULL) 

    BEGIN 

        SELECT tipo_entrada_saida_id_seq.NEXTVAL INTO :NEW.id FROM DUAL; 

END;
CREATE SEQUENCE titulo_receber_id_seq START WITH 1 INCREMENT BY 1; 

CREATE OR REPLACE TRIGGER titulo_receber_id_seq_tr 

BEFORE INSERT ON titulo_receber FOR EACH ROW 

    WHEN 

        (NEW.id IS NULL) 

    BEGIN 

        SELECT titulo_receber_id_seq.NEXTVAL INTO :NEW.id FROM DUAL; 

END;
CREATE SEQUENCE transportadora_id_seq START WITH 1 INCREMENT BY 1; 

CREATE OR REPLACE TRIGGER transportadora_id_seq_tr 

BEFORE INSERT ON transportadora FOR EACH ROW 

    WHEN 

        (NEW.id IS NULL) 

    BEGIN 

        SELECT transportadora_id_seq.NEXTVAL INTO :NEW.id FROM DUAL; 

END;
CREATE SEQUENCE venda_mes_cliente_id_seq START WITH 1 INCREMENT BY 1; 

CREATE OR REPLACE TRIGGER venda_mes_cliente_id_seq_tr 

BEFORE INSERT ON venda_mes_cliente FOR EACH ROW 

    WHEN 

        (NEW.id IS NULL) 

    BEGIN 

        SELECT venda_mes_cliente_id_seq.NEXTVAL INTO :NEW.id FROM DUAL; 

END;
CREATE SEQUENCE venda_mes_produto_id_seq START WITH 1 INCREMENT BY 1; 

CREATE OR REPLACE TRIGGER venda_mes_produto_id_seq_tr 

BEFORE INSERT ON venda_mes_produto FOR EACH ROW 

    WHEN 

        (NEW.id IS NULL) 

    BEGIN 

        SELECT venda_mes_produto_id_seq.NEXTVAL INTO :NEW.id FROM DUAL; 

END;
CREATE SEQUENCE vendedor_id_seq START WITH 1 INCREMENT BY 1; 

CREATE OR REPLACE TRIGGER vendedor_id_seq_tr 

BEFORE INSERT ON vendedor FOR EACH ROW 

    WHEN 

        (NEW.id IS NULL) 

    BEGIN 

        SELECT vendedor_id_seq.NEXTVAL INTO :NEW.id FROM DUAL; 

END;
CREATE SEQUENCE vendedor_atendimento_id_seq START WITH 1 INCREMENT BY 1; 

CREATE OR REPLACE TRIGGER vendedor_atendimento_id_seq_tr 

BEFORE INSERT ON vendedor_atendimento FOR EACH ROW 

    WHEN 

        (NEW.id IS NULL) 

    BEGIN 

        SELECT vendedor_atendimento_id_seq.NEXTVAL INTO :NEW.id FROM DUAL; 

END;
CREATE SEQUENCE vendedor_cliente_id_seq START WITH 1 INCREMENT BY 1; 

CREATE OR REPLACE TRIGGER vendedor_cliente_id_seq_tr 

BEFORE INSERT ON vendedor_cliente FOR EACH ROW 

    WHEN 

        (NEW.id IS NULL) 

    BEGIN 

        SELECT vendedor_cliente_id_seq.NEXTVAL INTO :NEW.id FROM DUAL; 

END;
 
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
 
