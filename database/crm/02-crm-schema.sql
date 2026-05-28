CREATE TABLE armazem( 
      id  SERIAL    NOT NULL  , 
      cod_erp char  (6)   NOT NULL  , 
      descricao varchar  (50)   NOT NULL  , 
      status varchar  (1)   , 
      dt_inclusao timestamp   , 
      dt_alteracao timestamp   , 
      system_unit_id integer   , 
 PRIMARY KEY (id)) ; 

CREATE TABLE atendimento( 
      id  SERIAL    NOT NULL  , 
      atendimento_tipo_id integer   NOT NULL  , 
      vendedor_id integer   NOT NULL  , 
      codigo_cliente varchar  (10)   , 
      cliente_id integer   , 
      horario_inicial timestamp   NOT NULL  , 
      horario_final timestamp   NOT NULL  , 
      titulo text   , 
      cor text   , 
      retorno timestamp   , 
      observacao text   , 
      dt_inclusao timestamp   , 
      dt_alteracao timestamp   , 
      dt_delete timestamp   , 
      user_id_create integer   , 
      user_id_update integer   , 
      user_id_delete integer   , 
      nome varchar  (100)   , 
      telefone varchar  (50)   , 
      email varchar  (100)   , 
      status char  (1)   , 
      orcamento_id integer   , 
      nota_saida_id integer   , 
 PRIMARY KEY (id)) ; 

CREATE TABLE atendimento_tipo( 
      id  SERIAL    NOT NULL  , 
      cod_erp varchar  (10)   , 
      descricao varchar  (50)   , 
      cor text   , 
      icone varchar  (100)   , 
      dt_alteracao timestamp   , 
      dt_inclusao timestamp   , 
      retorno char  (1)   , 
      editar char  (1)   , 
      excluir char  (1)   , 
      atendimento char  (1)   , 
      venda char  (1)   , 
      cadastro char  (1)   , 
      cobranca char  (1)   , 
 PRIMARY KEY (id)) ; 

CREATE TABLE blog_aniversarios( 
      id  SERIAL    NOT NULL  , 
      nome text   NOT NULL  , 
      filial_id integer   , 
      dia integer   NOT NULL  , 
      mes integer   NOT NULL  , 
      dt_alteracao timestamp   , 
      dt_inclusao timestamp   , 
      status char  (1)   , 
      system_user_id integer   , 
 PRIMARY KEY (id)) ; 

CREATE TABLE blog_comunicados( 
      id  SERIAL    NOT NULL  , 
      filial_id integer   , 
      titulo text   NOT NULL  , 
      texto text   NOT NULL  , 
      data_postagem date   NOT NULL  , 
      link_externo text   , 
      link_texto text   , 
      status char  (1)   , 
      ordenacao integer   , 
      data_validade date   , 
      dt_alteracao timestamp   , 
      dt_inclusao timestamp   , 
      system_user_id integer   , 
 PRIMARY KEY (id)) ; 

CREATE TABLE blog_noticias( 
      id  SERIAL    NOT NULL  , 
      titulo text   NOT NULL  , 
      texto text   NOT NULL  , 
      data_postagem date   NOT NULL  , 
      imagem text   , 
      autor text   , 
      status char  (1)   , 
      dt_alteracao timestamp   , 
      dt_inclusao timestamp   , 
      data_validade date   , 
      system_user_id integer   , 
 PRIMARY KEY (id)) ; 

CREATE TABLE calendario_orcamento( 
      id  SERIAL    NOT NULL  , 
      orcamento_id integer   NOT NULL  , 
      horario_inicial timestamp   NOT NULL  , 
      horario_final timestamp   NOT NULL  , 
      titulo text   , 
      cor text   , 
      observacao text   , 
 PRIMARY KEY (id)) ; 

CREATE TABLE caracteristica( 
      id  SERIAL    NOT NULL  , 
      descricao varchar  (50)   , 
      tipo char  (1)   , 
      dt_alteracao timestamp   , 
      dt_inclusao timestamp   , 
 PRIMARY KEY (id)) ; 

CREATE TABLE categoria( 
      id  SERIAL    NOT NULL  , 
      filial_id integer   , 
      cod_erp varchar  (6)   NOT NULL  , 
      descricao varchar  (200)   NOT NULL  , 
      usado char  (1)   , 
      site char  (1)   , 
      status char  (1)   , 
      dt_alteracao timestamp   , 
      dt_inclusao timestamp   , 
 PRIMARY KEY (id)) ; 

CREATE TABLE cep( 
      id  SERIAL    NOT NULL  , 
      cep varchar  (8)   , 
      estado_id integer   , 
      cidade_id integer   , 
      bairro varchar  (500)   , 
      endereco varchar  (500)   , 
      longitude float   , 
      latitude float   , 
      service varchar  (100)   , 
 PRIMARY KEY (id)) ; 

CREATE TABLE cliente( 
      id  SERIAL    NOT NULL  , 
      filial_id integer   , 
      cod_erp varchar  (10)   , 
      status char  (1)   , 
      tipo char  (1)   , 
      razao varchar  (100)   NOT NULL  , 
      tipo_cliente_id integer   , 
      fantasia varchar  (50)   , 
      endereco varchar  (100)   , 
      complemento varchar  (50)   , 
      bairro varchar  (50)   , 
      uf char  (2)   , 
      municipio varchar  (50)   , 
      municipio_id integer   , 
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
      vendedor_id integer   , 
      condicao_pagamento_id integer   , 
      tabela_preco_id integer   , 
      primeira_compra date   , 
      ultima_compra date   , 
      data_cadastro date   , 
      dt_alteracao timestamp   , 
      sinc char  (1)   , 
      dt_inclusao timestamp   , 
      destaca_ie char  (1)   , 
      seguimento_id integer   , 
      site char  (100)   , 
      obs text   , 
      filial_cadastro integer   , 
      cliente char  (1)   , 
      latitude float   , 
      log_int text   , 
      longitude float   , 
      limite float   , 
      vencimento_limite date   , 
      risco char  (1)   , 
      system_unit_id integer   , 
      carteira char  (1)   , 
      obs_bloqueio text   , 
      dt_bloqueio timestamp   , 
      motivo_bloqueio char  (1)   , 
      motivo_bloqueio_id integer   , 
      dt_reativacao date   , 
      obs_reativacao text   , 
      ultima_visita timestamp   , 
      ultimo_atendimento timestamp   , 
      regiao_cliente_id integer   , 
      reg_ativo char  (1)   , 
      dt_revisao timestamp   , 
      situacao_cadastral_id integer   , 
      data_rfb date   , 
 PRIMARY KEY (id)) ; 

CREATE TABLE cliente_acesso( 
      id  SERIAL    NOT NULL  , 
      cliente_id integer   , 
      user_id integer   , 
      dt_alteracao timestamp   , 
      dt_inclusao timestamp   , 
      dt_delete timestamp   , 
      login varchar  (100)   , 
      senha text   , 
      status char  (1)   , 
 PRIMARY KEY (id)) ; 

CREATE TABLE cliente_atendimento( 
      id  SERIAL    NOT NULL  , 
      cliente_id integer   NOT NULL  , 
      vendedor_id integer   NOT NULL  , 
      horario_inicial timestamp   NOT NULL  , 
      horario_final timestamp   NOT NULL  , 
      titulo text   , 
      cor text   , 
      observacao text   , 
      dt_alteracao timestamp   , 
      dt_inclusao timestamp   , 
 PRIMARY KEY (id)) ; 

CREATE TABLE cliente_atualizacao( 
      id  SERIAL    NOT NULL  , 
      cliente_id integer   , 
      situacao_cadastral_id integer   , 
      atividade_principal_id integer   , 
      razao text   NOT NULL  , 
      fantasia text   , 
      tipo_logradouro varchar  (20)   , 
      logradouro text   , 
      numero varchar  (10)   , 
      complemento text   , 
      bairro varchar  (50)   , 
      municipio_id integer   , 
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
      latitude float   , 
      longitude float   , 
      dt_alteracao timestamp   , 
      dt_inclusao timestamp   , 
      data_situacao_especial timestamp   , 
      situacao_especial varchar  (10)   , 
      atualizado_em timestamp   , 
      data_situacao_cadastral timestamp   , 
      simples varchar  (10)   , 
      tipo varchar  (10)   , 
      pais_id integer   , 
      mei varchar  (10)   , 
      porte varchar  (100)   , 
      natureza_juridica varchar  (100)   , 
      capital_social float   , 
 PRIMARY KEY (id)) ; 

CREATE TABLE cliente_cnae( 
      id  SERIAL    NOT NULL  , 
      cliente_id integer   NOT NULL  , 
      cnae_id integer   NOT NULL  , 
      dt_alteracao timestamp   , 
      dt_inclusao timestamp   , 
 PRIMARY KEY (id)) ; 

CREATE TABLE cliente_condicao( 
      id  SERIAL    NOT NULL  , 
      condicao_pagamento_id integer   NOT NULL  , 
      cliente_id integer   NOT NULL  , 
      padrao char  (1)   , 
      dt_inclusao timestamp   , 
      dt_alteracao timestamp   , 
 PRIMARY KEY (id)) ; 

CREATE TABLE cliente_contato( 
      id  SERIAL    NOT NULL  , 
      cliente_id integer   NOT NULL  , 
      tipo_contato_id integer   NOT NULL  , 
      nome varchar  (100)   , 
      telefone char  (9)   , 
      email varchar  (100)   , 
      situacao char  (1)   , 
      dt_inclusao timestamp   , 
      dt_alteracao timestamp   , 
 PRIMARY KEY (id)) ; 

CREATE TABLE cliente_historico( 
      id  SERIAL    NOT NULL  , 
      cliente_id integer   NOT NULL  , 
      vendedor_id integer   , 
      motivo_id integer   , 
      observacao text   , 
      dt_alteracao timestamp   , 
      dt_inclusao timestamp   , 
      data_movimento date   , 
 PRIMARY KEY (id)) ; 

CREATE TABLE cliente_socios( 
      id  SERIAL    NOT NULL  , 
      cliente_id integer   NOT NULL  , 
      nome varchar  (100)   , 
      tipo varchar  (20)   , 
      data_entrada date   , 
      faixa_etaria varchar  (100)   , 
      qualificacao_socio varchar  (100)   , 
      descricao varchar  (100)   , 
      cpf_cnpj_socio varchar  (100)   , 
      dt_alteracao timestamp   , 
      dt_inclusao timestamp   , 
 PRIMARY KEY (id)) ; 

CREATE TABLE cliente_vendedor_mes( 
      id  SERIAL    NOT NULL  , 
      filial_id integer   , 
      empresa_id integer   , 
      vendedor_id integer   NOT NULL  , 
      ano char  (4)   NOT NULL  , 
      janeiro float     DEFAULT 0, 
      fevereiro float     DEFAULT 0, 
      marco float     DEFAULT 0, 
      abril float     DEFAULT 0, 
      maio float     DEFAULT 0, 
      junho float     DEFAULT 0, 
      julho float     DEFAULT 0, 
      agosto float     DEFAULT 0, 
      setembro float     DEFAULT 0, 
      outubro float     DEFAULT 0, 
      novembro float     DEFAULT 0, 
      dezembro float     DEFAULT 0, 
      total_carteira float     DEFAULT 0, 
      total_rcg float     DEFAULT 0, 
      total_avulso float     DEFAULT 0, 
      total_bloqueado float   , 
      vendedor_nome varchar  (100)   , 
 PRIMARY KEY (id)) ; 

CREATE TABLE cnae( 
      id  SERIAL    NOT NULL  , 
      cod_erp varchar  (10)   , 
      secao varchar  (1)   , 
      divisao varchar  (10)   , 
      grupo varchar  (10)   , 
      classe varchar  (10)   , 
      subclasse varchar  (10)   , 
      descricao text   , 
      dt_inclusao timestamp   , 
      dt_alteracao timestamp   , 
 PRIMARY KEY (id)) ; 

CREATE TABLE condicao_pagamento( 
      id  SERIAL    NOT NULL  , 
      filial_id integer   , 
      cod_erp varchar  (3)   NOT NULL  , 
      descricao varchar  (100)   NOT NULL  , 
      forma char  (3)   , 
      status char  (1)   , 
      dt_inclusao timestamp   , 
      dt_alteracao timestamp   , 
      system_unit_id integer   , 
 PRIMARY KEY (id)) ; 

CREATE TABLE empresa( 
      id  SERIAL    NOT NULL  , 
      cod_erp char  (2)   , 
      system_unit_id integer   , 
      nome varchar  (100)   NOT NULL  , 
      logo text   , 
      status char  (1)   , 
      dt_inclusao timestamp   , 
      dt_alteracao timestamp   , 
 PRIMARY KEY (id)) ; 

CREATE TABLE estado( 
      id  SERIAL    NOT NULL  , 
      cod_erp varchar  (2)   NOT NULL  , 
      sigla varchar  (2)   NOT NULL  , 
      descricao varchar  (100)   NOT NULL  , 
      codigo_ibge varchar  (10)   , 
 PRIMARY KEY (id)) ; 

CREATE TABLE estoque( 
      id  SERIAL    NOT NULL  , 
      filial_id integer   , 
      produto_id integer   NOT NULL  , 
      armazem_id integer   NOT NULL  , 
      saldo float   , 
      reserva float   , 
      system_unit_id integer   , 
      ult_compra date   , 
      ult_preco float   , 
      custo float   , 
      dt_inclusao timestamp   , 
      dt_alteracao timestamp   , 
 PRIMARY KEY (id)) ; 

CREATE TABLE fabricante( 
      id  SERIAL    NOT NULL  , 
      cod_erp char  (6)   NOT NULL  , 
      descricao varchar  (200)   NOT NULL  , 
      dt_inclusao timestamp   , 
      dt_alteracao timestamp   , 
 PRIMARY KEY (id)) ; 

CREATE TABLE ficha_tecnica( 
      id  SERIAL    NOT NULL  , 
      produto_id integer   NOT NULL  , 
      arquivo varchar  (200)   , 
      validade date   , 
      dt_alteracao timestamp   , 
      dt_inclusao timestamp   , 
 PRIMARY KEY (id)) ; 

CREATE TABLE filial( 
      id  SERIAL    NOT NULL  , 
      cod_emp char  (6)   , 
      cod_erp char  (6)   NOT NULL  , 
      system_unit_id integer   , 
      apelido varchar  (50)   , 
      matriz char  (1)   , 
      nome varchar  (100)   NOT NULL  , 
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
      dt_alteracao timestamp   , 
      dt_inclusao timestamp   , 
 PRIMARY KEY (id)) ; 

CREATE TABLE fornecedor( 
      id  SERIAL    NOT NULL  , 
      filial_id integer   , 
      cod_erp varchar  (10)   , 
      status char  (1)   , 
      razao varchar  (100)   NOT NULL  , 
      tipo integer   , 
      fantasia varchar  (50)   , 
      endereco varchar  (100)   , 
      complemento varchar  (50)   , 
      bairro varchar  (50)   , 
      uf char  (2)   , 
      municipio varchar  (50)   , 
      municipio_id integer   , 
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
      condicao_pagamento_id integer   , 
      primeira_compra date   , 
      ultima_visita date   , 
      sinc char  (1)   , 
      data_cadastro date   , 
      dt_alteracao timestamp   , 
      dt_inclusao timestamp   , 
      destaca_ie char  (1)   , 
      seguimento_id integer   , 
      site char  (100)   , 
      obs text   , 
      filial_cadastro integer   , 
      latitude float   , 
      log_int text   , 
      longitude float   , 
      system_unit_id integer   , 
 PRIMARY KEY (id)) ; 

CREATE TABLE meta_vendedor_categoria( 
      id  SERIAL    NOT NULL  , 
      meta_vendedor_mes_id integer   NOT NULL  , 
      categoria_id integer   NOT NULL  , 
      cod_erp varchar  (6)   , 
      descricao varchar  (200)   , 
      valor float   , 
      dt_alteracao timestamp   , 
      dt_inclusao timestamp   , 
      dt_delete timestamp   , 
 PRIMARY KEY (id)) ; 

CREATE TABLE meta_vendedor_mes( 
      id  SERIAL    NOT NULL  , 
      vendedor_id integer   NOT NULL  , 
      mes varchar  (2)   NOT NULL  , 
      ano varchar  (4)   NOT NULL  , 
      tipo char  (1)   , 
      valor float   NOT NULL  , 
      numero_cliente float   , 
      novo_cliente float   , 
      dt_alteracao timestamp   , 
      dt_inclusao timestamp   , 
      dt_delete timestamp   , 
 PRIMARY KEY (id)) ; 

CREATE TABLE motivo_bloqueio( 
      id  SERIAL    NOT NULL  , 
      cod_erp varchar  (10)   , 
      dt_alteracao timestamp   , 
      dt_inclusao timestamp   , 
      descricao char  (40)   , 
 PRIMARY KEY (id)) ; 

CREATE TABLE municipio( 
      id  SERIAL    NOT NULL  , 
      cod_erp varchar  (10)   NOT NULL  , 
      descricao varchar  (200)   NOT NULL  , 
      estado_id integer   , 
      codigo_ibge varchar  (10)   , 
 PRIMARY KEY (id)) ; 

CREATE TABLE natureza( 
      id  SERIAL    NOT NULL  , 
      cod_erp varchar  (20)   , 
      descricao text   , 
      dt_alteracao timestamp   , 
      dt_inclusao timestamp   , 
 PRIMARY KEY (id)) ; 

CREATE TABLE negociacao( 
      id  SERIAL    NOT NULL  , 
      filial_id integer   , 
      cod_erp varchar  (10)   , 
      dt_alteracao timestamp   , 
      dt_inclusao timestamp   , 
      cliente_id integer   , 
      vendedor_id integer   , 
      atendimento_tipo_id integer   NOT NULL  , 
      observacao text   , 
      system_unit_id integer   , 
      system_users_id integer   , 
      tipo integer   , 
      status char   , 
      atendimento_id integer   NOT NULL  , 
 PRIMARY KEY (id)) ; 

CREATE TABLE negociacao_titulo_receber( 
      id  SERIAL    NOT NULL  , 
      dt_alteracao timestamp   , 
      negociacao_id integer   NOT NULL  , 
      dt_inclusao timestamp   , 
      titulo_receber_id integer   NOT NULL  , 
      vencimento date   , 
      valor float   , 
 PRIMARY KEY (id)) ; 

CREATE TABLE nota_entrada( 
      id  SERIAL    NOT NULL  , 
      filial_id integer   , 
      cliente_id integer   , 
      fornecedor_id integer   , 
      nota_fiscal varchar  (9)   , 
      serie_fiscal varchar  (3)   , 
      especie_fiscal varchar  (10)   , 
      condicao_id integer   , 
      numero_titulo varchar  (9)   , 
      prefixo_titulo varchar  (6)   , 
      dt_emissao date   , 
      tipo char  (1)   , 
      comodato char  (1)   , 
      vlr_bruto float   , 
      vlr_icms float   , 
      base_icms float   , 
      vlr_ipi float   , 
      base_ipi float   , 
      vlr_mercadoria float   , 
      vlr_desconto float   , 
      vlr_comodato float   , 
      vlr_itens float   , 
      vlr_devolucao float   , 
      transportadora varchar  (6)   , 
      tp_frete char  (1)   , 
      vlr_frete float   , 
      vendedor1_id integer   , 
      vendedor2_id integer   , 
      chave_nfe varchar  (100)   , 
      dt_nfe date   , 
      hr_nfe varchar  (10)   , 
      mensagem_nf text   , 
      numero_origem varchar  (6)   , 
      serie_origem varchar  (3)   , 
      intermediador varchar  (6)   , 
      reg_ativo char  (1)   , 
      mes varchar  (2)   , 
      ano varchar  (4)   , 
      system_unit_id integer   , 
      date_danfe timestamp   , 
      dt_inclusao timestamp   , 
      dt_alteracao timestamp   , 
 PRIMARY KEY (id)) ; 

CREATE TABLE nota_entrada_item( 
      id  SERIAL    NOT NULL  , 
      nota_entrada_id integer   NOT NULL  , 
      item varchar  (4)   , 
      produto_id integer   , 
      qtd float   , 
      vlr_unitario float   , 
      tes_id integer   , 
      vlr_tabela float   , 
      vlr_bruto float   , 
      base_icms float   , 
      aliq_icms float   , 
      vlr_icms float   , 
      base_ipi float   , 
      aliq_ipi float   , 
      vlr_ipi float   , 
      vlr_total float   , 
      vlr_dev float   , 
      qtd_dev float   , 
      perc_desconto float   , 
      vlr_desconto float   , 
      peso float   , 
      pedido_item_id integer   , 
      reg_ativo char  (1)   , 
      tes varchar  (3)   , 
      estoque char  (1)   , 
      financeiro char  (1)   , 
      ano varchar  (4)   , 
      mes varchar  (2)   , 
      cliente_id integer   , 
      vendedor1_id integer   , 
      vendedor2_id integer   , 
      dt_emissao date   , 
      cfop char  (4)   , 
      perc_comissao float   , 
      comissao float   , 
      comodato char  (1)   , 
 PRIMARY KEY (id)) ; 

CREATE TABLE nota_saida( 
      id  SERIAL    NOT NULL  , 
      filial_id integer   , 
      cliente_id integer   , 
      fornecedor_id integer   , 
      nota_fiscal varchar  (9)   , 
      serie_fiscal varchar  (3)   , 
      especie_fiscal varchar  (10)   , 
      condicao_id integer   , 
      numero_titulo varchar  (9)   , 
      prefixo_titulo varchar  (6)   , 
      dt_emissao date   , 
      tipo char  (1)   , 
      comodato char  (1)   , 
      vlr_bruto float   , 
      vlr_icms float   , 
      base_icms float   , 
      vlr_ipi float   , 
      base_ipi float   , 
      vlr_mercadoria float   , 
      vlr_desconto float   , 
      vlr_comodato float   , 
      vlr_itens float   , 
      vlr_devolucao float   , 
      transportadora varchar  (6)   , 
      tp_frete char  (1)   , 
      vlr_frete float   , 
      vendedor1_id integer   , 
      vendedor2_id integer   , 
      chave_nfe varchar  (100)   , 
      dt_nfe date   , 
      hr_nfe varchar  (10)   , 
      mensagem_nf text   , 
      numero_origem varchar  (6)   , 
      serie_origem varchar  (3)   , 
      intermediador varchar  (6)   , 
      reg_ativo char  (1)   , 
      mes varchar  (2)   , 
      ano varchar  (4)   , 
      system_unit_id integer   , 
      date_danfe timestamp   , 
      dt_inclusao timestamp   , 
      dt_alteracao timestamp   , 
 PRIMARY KEY (id)) ; 

CREATE TABLE nota_saida_item( 
      id  SERIAL    NOT NULL  , 
      nota_saida_id integer   NOT NULL  , 
      item varchar  (4)   , 
      produto_id integer   , 
      qtd float   , 
      vlr_unitario float   , 
      tes_id integer   , 
      vlr_tabela float   , 
      vlr_bruto float   , 
      base_icms float   , 
      aliq_icms float   , 
      vlr_icms float   , 
      base_ipi float   , 
      aliq_ipi float   , 
      vlr_ipi float   , 
      vlr_total float   , 
      vlr_dev float   , 
      qtd_dev float   , 
      perc_desconto float   , 
      vlr_desconto float   , 
      peso float   , 
      pedido_item_id integer   , 
      reg_ativo char  (1)   , 
      tes varchar  (3)   , 
      estoque char  (1)   , 
      financeiro char  (1)   , 
      ano varchar  (4)   , 
      mes varchar  (2)   , 
      cliente_id integer   , 
      vendedor1_id integer   , 
      vendedor2_id integer   , 
      dt_emissao date   , 
      cfop char  (4)   , 
      perc_comissao float   , 
      comissao float   , 
      comodato char  (1)   , 
      tipo char  (1)   , 
 PRIMARY KEY (id)) ; 

CREATE TABLE notasaida_xml( 
      id  SERIAL    NOT NULL  , 
      nota_saida_id integer   , 
      xml_sig text   , 
      xml_tss text   , 
      xml_cancelamento text   , 
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
      id  SERIAL    NOT NULL  , 
      emissao timestamp   , 
      retorno timestamp   , 
      observacao text   , 
      cliente_id integer   , 
      tabela_preco_id integer   , 
      condicao_pagamento_id integer   , 
      pedido_id integer   , 
      vendedor_id integer   , 
      estado_id integer   , 
      municipio_id integer   , 
      codigo_cliente varchar  (8)   , 
      telefone varchar  (50)   NOT NULL  , 
      nome text   NOT NULL  , 
      email varchar  (100)   , 
      orcamento_estado_id integer   , 
      dt_cancelamento timestamp   , 
      dt_faturamento timestamp   , 
      latitude float   , 
      longitude float   , 
      valor_total float   , 
      sinc char  (1)   , 
      log_int text   , 
      dt_inclusao timestamp   , 
      system_unit_id integer   , 
      dt_alteracao timestamp   , 
      system_user_id integer   , 
 PRIMARY KEY (id)) ; 

CREATE TABLE orcamento_estado( 
      id  SERIAL    NOT NULL  , 
      cod_erp varchar  (10)   , 
      descricao varchar  (100)   , 
      cor varchar  (10)   , 
      cor_texto varchar  (10)   , 
      editar char  (1)   , 
      excluir char  (1)   , 
      imprimir char  (1)   , 
      cancelar char  (1)   , 
      dt_inclusao timestamp   , 
      dt_alteracao timestamp   , 
      sistema char  (1)   , 
      ordem integer   , 
      icone text   , 
      exibir_regua char  (1)   , 
 PRIMARY KEY (id)) ; 

CREATE TABLE orcamento_historico( 
      id  SERIAL    NOT NULL  , 
      orcamento_id integer   NOT NULL  , 
      orcamento_estado_id integer   NOT NULL  , 
      observacao text   , 
      orcamento_proximo_estado_id integer   , 
      system_user_id integer   , 
      dt_alteracao timestamp   , 
      dt_inclusao timestamp   , 
      dt_evento timestamp   , 
 PRIMARY KEY (id)) ; 

CREATE TABLE orcamento_item( 
      id  SERIAL    NOT NULL  , 
      orcamento_id integer   NOT NULL  , 
      produto_id integer   NOT NULL  , 
      codigo_produto varchar  (30)   , 
      quantidade float   , 
      preco_unit float   , 
      preco_tabela float   , 
      desconto float   , 
      acrescimo float   , 
      preco_total float   , 
      dt_alteracao timestamp   , 
      dt_inclusao timestamp   , 
 PRIMARY KEY (id)) ; 

CREATE TABLE orcamento_proximo_estado( 
      id  SERIAL    NOT NULL  , 
      orcamento_estado_id integer   NOT NULL  , 
      proximo_estado_id integer   , 
      dt_alteracao timestamp   , 
      dt_inclusao timestamp   , 
 PRIMARY KEY (id)) ; 

CREATE TABLE pais( 
      id  SERIAL    NOT NULL  , 
      cod_erp varchar  (10)   , 
      nome varchar  (100)   , 
      dt_alteracao timestamp   , 
      dt_inclusao timestamp   , 
      sigla varchar  (4)   , 
      comex_id varchar  (10)   , 
 PRIMARY KEY (id)) ; 

CREATE TABLE parametro( 
      id  SERIAL    NOT NULL  , 
      filial_id integer   , 
      parametro varchar  (50)   NOT NULL  , 
      conteudo varchar  (100)   , 
      tipo char  (1)   NOT NULL  , 
 PRIMARY KEY (id)) ; 

CREATE TABLE pedido( 
      id  SERIAL    NOT NULL  , 
      filial_id integer   , 
      pedido_estado_id integer   , 
      cliente_id integer   NOT NULL  , 
      cliente_entrega_id integer   , 
      vendedor1_id integer   NOT NULL  , 
      vendedor2_id integer   , 
      cod_erp char  (6)   , 
      dt_emissao date   , 
      transportadora_id integer   , 
      tabela_id integer   , 
      condicao_pagamento_id integer   , 
      sinc char  (1)   , 
      mes varchar  (2)   , 
      ano varchar  (4)   , 
      tipo char  (1)   , 
      nota_fiscal varchar  (9)   , 
      serie varchar  (3)   , 
      mensagem_nf varchar  (100)   , 
      tp_frete char  (1)   , 
      vlr_frete float   , 
      vlr_total float   , 
      vlr_comodato float   , 
      presencial char  (1)   , 
      pedido_origem char  (1)   , 
      log_int text   , 
      user_id integer   , 
      intermediador_id integer   , 
      dt_inclusao timestamp   , 
      dt_alteracao timestamp   , 
      orcamento_id integer   , 
      nota_saida_id integer   , 
      system_unit_id integer   , 
 PRIMARY KEY (id)) ; 

CREATE TABLE pedido_estado( 
      id  SERIAL    NOT NULL  , 
      cod_erp varchar  (2)   , 
      descricao char  (100)   , 
      cor varchar  (10)   , 
      cor_texto varchar  (10)   , 
 PRIMARY KEY (id)) ; 

CREATE TABLE pedido_item( 
      id  SERIAL    NOT NULL  , 
      pedido_id integer   NOT NULL  , 
      produto_id integer   NOT NULL  , 
      item varchar  (4)   , 
      vlr_unitario float   , 
      vlr_item float   , 
      quantidade float   , 
      per_desconto float   , 
      vlr_desconto float   , 
      vlr_acrescimo float   , 
      vlr_total float   , 
      armazem_id integer   , 
      tipo_movimentacao_id integer   , 
      dt_inclusao timestamp   , 
      dt_alteracao timestamp   , 
      mes varchar  (2)   , 
      ano varchar  (4)   , 
 PRIMARY KEY (id)) ; 

CREATE TABLE produto( 
      id  SERIAL    NOT NULL  , 
      filial_id integer   , 
      cod_erp varchar  (30)   NOT NULL  , 
      descricao varchar  (100)   NOT NULL  , 
      tipo char  (2)   , 
      um char  (2)   , 
      categoria_id integer   , 
      sub_categoria_id integer   , 
      fabricante_id integer   , 
      armazem_id integer   , 
      codigo_barras varchar  (30)   , 
      codigo_fabricante char  (60)   , 
      qtd_embalagem float   , 
      observacao varchar  (200)   , 
      foto varchar  (200)   , 
      status char  (1)   , 
      ncm char  (20)   , 
      origem char  (2)   , 
      peso_bruto float   , 
      peso float   , 
      marca varchar  (20)   , 
      te_id integer   , 
      ts_id integer   , 
      sinc char  (1)   , 
      ponto_pedido float   , 
      estoque_seguranca float   , 
      dt_ult_compra date   , 
      ult_preco float   , 
      informacoes_tecnicas text   , 
      dados_tecnicos text   , 
      system_unit_id integer   , 
      dt_inclusao timestamp   , 
      dt_alteracao timestamp   , 
 PRIMARY KEY (id)) ; 

CREATE TABLE produto_caracteristica( 
      id  SERIAL    NOT NULL  , 
      caracteristica_id integer   NOT NULL  , 
      produto_id integer   NOT NULL  , 
      des_caracteristica text   , 
      dt_caracteristica date   , 
      vlr_caracteristica float   , 
      dt_alteracao timestamp   , 
      dt_inclusao timestamp   , 
 PRIMARY KEY (id)) ; 

CREATE TABLE regiao_cliente( 
      id  SERIAL    NOT NULL  , 
      cod_erp varchar  (10)   , 
      descricao text   , 
      dt_alteracao timestamp   , 
      dt_inclusao timestamp   , 
      cor text   , 
 PRIMARY KEY (id)) ; 

CREATE TABLE segmento( 
      id  SERIAL    NOT NULL  , 
      cod_erp varchar  (6)   NOT NULL  , 
      descricao varchar  (200)   NOT NULL  , 
      status varchar  (1)   , 
      reg_ativo varchar  (1)   , 
      dt_inclusao timestamp   , 
      dt_alteracao timestamp   , 
 PRIMARY KEY (id)) ; 

CREATE TABLE situacao_cadastral( 
      id  SERIAL    NOT NULL  , 
      descricao varchar  (100)   NOT NULL  , 
      motivo_bloqueio_id integer   , 
      dt_alteracao timestamp   , 
      dt_inclusao timestamp   , 
 PRIMARY KEY (id)) ; 

CREATE TABLE step( 
      id  SERIAL    NOT NULL  , 
      grupo char  (20)   , 
      sequencia integer   , 
      variavel char  (2)   , 
      descricao char  (20)   , 
      cor char  (20)   , 
      column_6 integer   , 
 PRIMARY KEY (id)) ; 

CREATE TABLE sub_categoria( 
      id  SERIAL    NOT NULL  , 
      categoria_id integer   NOT NULL  , 
      cod_erp varchar  (6)   NOT NULL  , 
      descricao varchar  (200)   NOT NULL  , 
      status char  (1)   , 
      dt_inclusao timestamp   , 
      dt_alteracao timestamp   , 
 PRIMARY KEY (id)) ; 

CREATE TABLE supervisor( 
      filial_id integer   , 
      id  SERIAL    NOT NULL  , 
      cod_erp varchar  (6)   , 
      system_users_id integer   , 
      nome varchar  (100)   NOT NULL  , 
      nome_reduzido varchar  (50)   , 
      ddd varchar  (3)   , 
      telefone varchar  (15)   , 
      celular varchar  (15)   , 
      email varchar  (100)   , 
      status char  (1)   , 
      vendedor varchar  (1)   , 
      dashboard char  (1)   , 
      dt_alteracao timestamp   , 
      dt_inclusao timestamp   , 
      dt_nascmento date   , 
      system_unit_id integer   , 
      desligado char  (1)   , 
 PRIMARY KEY (id)) ; 

CREATE TABLE supervisor_vendedor( 
      id  SERIAL    NOT NULL  , 
      vendedor_id integer   NOT NULL  , 
      supervisor_id integer   NOT NULL  , 
      sistema char  (1)   , 
      dt_alteracao timestamp   , 
      dt_inclusao timestamp   , 
 PRIMARY KEY (id)) ; 

CREATE TABLE tabela_preco( 
      id  SERIAL    NOT NULL  , 
      empresa_id integer   , 
      filial_id integer   , 
      cod_erp varchar  (3)   NOT NULL  , 
      descricao varchar  (50)   NOT NULL  , 
      status char  (1)   , 
      dt_inicio date   , 
      dt_fim date   , 
      dt_inclusao timestamp   , 
      dt_alteracao timestamp   , 
      utiliza char  (1)   , 
      system_unit_id integer   , 
 PRIMARY KEY (id)) ; 

CREATE TABLE tabela_preco_item( 
      id  SERIAL    NOT NULL  , 
      item integer   , 
      tabela_preco_id integer   NOT NULL  , 
      produto_id integer   NOT NULL  , 
      preco float   , 
      status char  (1)   , 
      dt_alteracao timestamp   , 
      dt_inclusao timestamp   , 
 PRIMARY KEY (id)) ; 

CREATE TABLE tipo_cliente( 
      id  SERIAL    NOT NULL  , 
      cod_erp varchar  (6)   , 
      descricao varchar  (50)   , 
      status char  (1)   , 
 PRIMARY KEY (id)) ; 

CREATE TABLE tipo_contato( 
      id  SERIAL    NOT NULL  , 
      cod_erp varchar  (10)   , 
      descricao varchar  (50)   , 
      dt_alteracao timestamp   , 
      dt_inclusao timestamp   , 
 PRIMARY KEY (id)) ; 

CREATE TABLE tipo_entrada_saida( 
      id  SERIAL    NOT NULL  , 
      filial_id integer   , 
      empresa_id integer   , 
      cod_erp varchar  (10)   NOT NULL  , 
      tipo char  (1)   , 
      descricao varchar  (100)   NOT NULL  , 
      finalidade varchar  (100)   , 
      status char  (1)   , 
      cfop char  (4)   , 
      dt_alteracao timestamp   , 
      dt_inclusao timestamp   , 
      system_unit_id integer   , 
 PRIMARY KEY (id)) ; 

CREATE TABLE titulo_receber( 
      id  SERIAL    NOT NULL  , 
      dt_alteracao timestamp   , 
      filial_id integer   , 
      dt_inclusao timestamp   , 
      cliente_id integer   NOT NULL  , 
      vendedor_id integer   , 
      natureza_id integer   , 
      emissao date   NOT NULL  , 
      numero char  (9)   NOT NULL  , 
      parcela char  (3)   NOT NULL  , 
      prefixo char  (3)   NOT NULL  , 
      tipo char  (3)   , 
      saldo float   , 
      valor float   , 
      decrescimo float   , 
      acrescimo float   , 
      valor_juros float   , 
      perc_juros float   , 
      mora_dia float   , 
      taxa_multa float   , 
      dt_digitacao date   , 
      vencimento date   NOT NULL  , 
      venc_real date   NOT NULL  , 
      venc_orig date   , 
      pedido_id integer   , 
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
      historico text   , 
      usr_inclusao char  (10)   , 
      usr_alteracao char  (10)   , 
      reg_ativo char  (1)   , 
      baixa date   , 
      system_unit_id integer   , 
      e1_recno integer   , 
      nota_fiscal_id integer   , 
      vias integer   , 
      situacao char  (1)   , 
 PRIMARY KEY (id)) ; 

CREATE TABLE transportadora( 
      id  SERIAL    NOT NULL  , 
      filial_id integer   , 
      cod_erp char  (6)   , 
      descricao varchar  (100)   , 
      status char  (1)   , 
      dt_inclusao timestamp   , 
      dt_alteracao timestamp   , 
      system_unit_id integer   , 
 PRIMARY KEY (id)) ; 

CREATE TABLE venda_mes_cliente( 
      id  SERIAL    NOT NULL  , 
      filial_id integer   , 
      empresa_id integer   , 
      cliente_id integer   NOT NULL  , 
      ano char  (4)   NOT NULL  , 
      janeiro float     DEFAULT 0, 
      fevereiro float     DEFAULT 0, 
      mes char  (2)   , 
      marco float     DEFAULT 0, 
      abril float     DEFAULT 0, 
      maio float     DEFAULT 0, 
      junho float     DEFAULT 0, 
      julho float     DEFAULT 0, 
      agosto float     DEFAULT 0, 
      setembro float     DEFAULT 0, 
      outubro float     DEFAULT 0, 
      novembro float     DEFAULT 0, 
      dezembro float     DEFAULT 0, 
      cliente_nome varchar  (100)   , 
 PRIMARY KEY (id)) ; 

CREATE TABLE venda_mes_produto( 
      id  SERIAL    NOT NULL  , 
      filial_id integer   , 
      empresa_id integer   , 
      produto_id integer   NOT NULL  , 
      ano char  (4)   NOT NULL  , 
      janeiro float     DEFAULT 0, 
      fevereiro float     DEFAULT 0, 
      marco float     DEFAULT 0, 
      abril float     DEFAULT 0, 
      maio float     DEFAULT 0, 
      junho float     DEFAULT 0, 
      julho float     DEFAULT 0, 
      agosto float     DEFAULT 0, 
      setembro float     DEFAULT 0, 
      outubro float     DEFAULT 0, 
      novembro float     DEFAULT 0, 
      dezembro float     DEFAULT 0, 
      produto_nome varchar  (100)   , 
 PRIMARY KEY (id)) ; 

CREATE TABLE vendedor( 
      id  SERIAL    NOT NULL  , 
      filial_id integer   , 
      cod_erp varchar  (6)   , 
      system_users_id integer   , 
      nome varchar  (100)   NOT NULL  , 
      nome_reduzido varchar  (50)   , 
      ddd varchar  (3)   , 
      telefone varchar  (15)   , 
      celular varchar  (15)   , 
      email varchar  (100)   , 
      status char  (1)   , 
      vendedor varchar  (1)   , 
      dashboard char  (1)   , 
      dt_alteracao timestamp   , 
      dt_inclusao timestamp   , 
      dt_nascmento date   , 
      system_unit_id integer   , 
      supervisor char  (1)   , 
      supervisor_id integer   , 
      desligado char  (1)   , 
 PRIMARY KEY (id)) ; 

CREATE TABLE vendedor_atendimento( 
      id  SERIAL    NOT NULL  , 
      vendedor_id integer   NOT NULL  , 
      dt_alteracao timestamp   , 
      dt_inclusao timestamp   , 
      inicial time   , 
      final time   , 
      tipo char  (1)   , 
      dias_semana varchar  (20)   , 
 PRIMARY KEY (id)) ; 

CREATE TABLE vendedor_cliente( 
      id  SERIAL    NOT NULL  , 
      vendedor_id integer   NOT NULL  , 
      cliente_id integer   NOT NULL  , 
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

 
 CREATE index idx_atendimento_vendedor_id on atendimento(vendedor_id); 
CREATE index idx_atendimento_cliente_id on atendimento(cliente_id); 
CREATE index idx_atendimento_atendimento_tipo_id on atendimento(atendimento_tipo_id); 
CREATE index idx_calendario_orcamento_orcamento_id on calendario_orcamento(orcamento_id); 
CREATE index idx_categoria_filial_id on categoria(filial_id); 
CREATE index idx_cliente_filial_id on cliente(filial_id); 
CREATE index idx_cliente_municipio_id on cliente(municipio_id); 
CREATE index idx_cliente_vendedor_id on cliente(vendedor_id); 
CREATE index idx_cliente_tipo_cliente_id on cliente(tipo_cliente_id); 
CREATE index idx_cliente_seguimento_id on cliente(seguimento_id); 
CREATE index idx_cliente_condicao_pagamento_id on cliente(condicao_pagamento_id); 
CREATE index idx_cliente_tabela_preco_id on cliente(tabela_preco_id); 
CREATE index idx_cliente_motivo_bloqueio_id on cliente(motivo_bloqueio_id); 
CREATE index idx_cliente_regiao_cliente_id on cliente(regiao_cliente_id); 
CREATE index idx_cliente_situacao_cadastral_id on cliente(situacao_cadastral_id); 
CREATE index idx_cliente_acesso_cliente_id on cliente_acesso(cliente_id); 
CREATE index idx_cliente_atendimento_cliente_id on cliente_atendimento(cliente_id); 
CREATE index idx_cliente_atendimento_vendedor_id on cliente_atendimento(vendedor_id); 
CREATE index idx_cliente_atualizacao_cliente_id on cliente_atualizacao(cliente_id); 
CREATE index idx_cliente_atualizacao_situacao_cadastral_id on cliente_atualizacao(situacao_cadastral_id); 
CREATE index idx_cliente_atualizacao_atividade_principal_id on cliente_atualizacao(atividade_principal_id); 
CREATE index idx_cliente_atualizacao_pais_id on cliente_atualizacao(pais_id); 
CREATE index idx_cliente_cnae_cnae_id on cliente_cnae(cnae_id); 
CREATE index idx_cliente_condicao_condicao_pagamento_id on cliente_condicao(condicao_pagamento_id); 
CREATE index idx_cliente_condicao_cliente_id on cliente_condicao(cliente_id); 
CREATE index idx_cliente_contato_cliente_id on cliente_contato(cliente_id); 
CREATE index idx_cliente_contato_tipo_contato_id on cliente_contato(tipo_contato_id); 
CREATE index idx_cliente_historico_cliente_id on cliente_historico(cliente_id); 
CREATE index idx_cliente_vendedor_mes_vendedor_id on cliente_vendedor_mes(vendedor_id); 
CREATE index idx_estoque_armazem_id on estoque(armazem_id); 
CREATE index idx_estoque_produto_id on estoque(produto_id); 
CREATE index idx_estoque_filial_id on estoque(filial_id); 
CREATE index idx_ficha_tecnica_produto_id on ficha_tecnica(produto_id); 
CREATE index idx_fornecedor_filial_id on fornecedor(filial_id); 
CREATE index idx_meta_vendedor_categoria_meta_vendedor_mes_id on meta_vendedor_categoria(meta_vendedor_mes_id); 
CREATE index idx_meta_vendedor_categoria_categoria_id on meta_vendedor_categoria(categoria_id); 
CREATE index idx_meta_vendedor_mes_vendedor_id on meta_vendedor_mes(vendedor_id); 
CREATE index idx_municipio_estado_id on municipio(estado_id); 
CREATE index idx_negociacao_cliente_id on negociacao(cliente_id); 
CREATE index idx_negociacao_vendedor_id on negociacao(vendedor_id); 
CREATE index idx_negociacao_atendimento_id on negociacao(atendimento_id); 
CREATE index idx_negociacao_atendimento_tipo_id on negociacao(atendimento_tipo_id); 
CREATE index idx_negociacao_titulo_receber_negociacao_id on negociacao_titulo_receber(negociacao_id); 
CREATE index idx_negociacao_titulo_receber_titulo_receber_id on negociacao_titulo_receber(titulo_receber_id); 
CREATE index idx_nota_entrada_fornecedor_id on nota_entrada(fornecedor_id); 
CREATE index idx_nota_entrada_cliente_id on nota_entrada(cliente_id); 
CREATE index idx_nota_entrada_vendedor1_id on nota_entrada(vendedor1_id); 
CREATE index idx_nota_entrada_item_nota_entrada_id on nota_entrada_item(nota_entrada_id); 
CREATE index idx_nota_saida_vendedor1_id on nota_saida(vendedor1_id); 
CREATE index idx_nota_saida_vendedor2_id on nota_saida(vendedor2_id); 
CREATE index idx_nota_saida_filial_id on nota_saida(filial_id); 
CREATE index idx_nota_saida_cliente_id on nota_saida(cliente_id); 
CREATE index idx_nota_saida_fornecedor_id on nota_saida(fornecedor_id); 
CREATE index idx_nota_saida_item_nota_saida_id on nota_saida_item(nota_saida_id); 
CREATE index idx_nota_saida_item_produto_id on nota_saida_item(produto_id); 
CREATE index idx_nota_saida_item_tes_id on nota_saida_item(tes_id); 
CREATE index idx_nota_saida_item_cliente_id on nota_saida_item(cliente_id); 
CREATE index idx_nota_saida_item_vendedor1_id on nota_saida_item(vendedor1_id); 
CREATE index idx_nota_saida_item_vendedor2_id on nota_saida_item(vendedor2_id); 
CREATE index idx_notasaida_xml_nota_saida_id on notasaida_xml(nota_saida_id); 
CREATE index idx_orcamento_cliente_id on orcamento(cliente_id); 
CREATE index idx_orcamento_tabela_preco_id on orcamento(tabela_preco_id); 
CREATE index idx_orcamento_pedido_id on orcamento(pedido_id); 
CREATE index idx_orcamento_estado_id on orcamento(estado_id); 
CREATE index idx_orcamento_condicao_pagamento_id on orcamento(condicao_pagamento_id); 
CREATE index idx_orcamento_orcamento_estado_id on orcamento(orcamento_estado_id); 
CREATE index idx_orcamento_municipio_id on orcamento(municipio_id); 
CREATE index idx_orcamento_vendedor_id on orcamento(vendedor_id); 
CREATE index idx_orcamento_historico_orcamento_id on orcamento_historico(orcamento_id); 
CREATE index idx_orcamento_historico_orcamento_estado_id on orcamento_historico(orcamento_estado_id); 
CREATE index idx_orcamento_historico_orcamento_proximo_estado_id on orcamento_historico(orcamento_proximo_estado_id); 
CREATE index idx_orcamento_item_orcamento_id on orcamento_item(orcamento_id); 
CREATE index idx_orcamento_item_produto_id on orcamento_item(produto_id); 
CREATE index idx_orcamento_proximo_estado_orcamento_estado_id on orcamento_proximo_estado(orcamento_estado_id); 
CREATE index idx_orcamento_proximo_estado_proximo_estado_id on orcamento_proximo_estado(proximo_estado_id); 
CREATE index idx_parametro_filial_id on parametro(filial_id); 
CREATE index idx_pedido_cliente_id on pedido(cliente_id); 
CREATE index idx_pedido_pedido_estado_id on pedido(pedido_estado_id); 
CREATE index idx_pedido_condicao_pagamento_id on pedido(condicao_pagamento_id); 
CREATE index idx_pedido_transportadora_id on pedido(transportadora_id); 
CREATE index idx_pedido_cliente_entrega_id on pedido(cliente_entrega_id); 
CREATE index idx_pedido_vendedor1_id on pedido(vendedor1_id); 
CREATE index idx_pedido_vendedor2_id on pedido(vendedor2_id); 
CREATE index idx_pedido_orcamento_id on pedido(orcamento_id); 
CREATE index idx_pedido_nota_saida_id on pedido(nota_saida_id); 
CREATE index idx_pedido_item_pedido_id on pedido_item(pedido_id); 
CREATE index idx_pedido_item_produto_id on pedido_item(produto_id); 
CREATE index idx_pedido_item_armazem_id on pedido_item(armazem_id); 
CREATE index idx_produto_filial_id on produto(filial_id); 
CREATE index idx_produto_categoria_id on produto(categoria_id); 
CREATE index idx_produto_armazem_id on produto(armazem_id); 
CREATE index idx_produto_fabricante_id on produto(fabricante_id); 
CREATE index idx_produto_sub_categoria_id on produto(sub_categoria_id); 
CREATE index idx_produto_te_id on produto(te_id); 
CREATE index idx_produto_ts_id on produto(ts_id); 
CREATE index idx_produto_caracteristica_caracteristica_id on produto_caracteristica(caracteristica_id); 
CREATE index idx_produto_caracteristica_produto_id on produto_caracteristica(produto_id); 
CREATE index idx_situacao_cadastral_motivo_bloqueio_id on situacao_cadastral(motivo_bloqueio_id); 
CREATE index idx_sub_categoria_categoria_id on sub_categoria(categoria_id); 
CREATE index idx_supervisor_vendedor_vendedor_id on supervisor_vendedor(vendedor_id); 
CREATE index idx_supervisor_vendedor_supervisor_id on supervisor_vendedor(supervisor_id); 
CREATE index idx_tabela_preco_item_produto_id on tabela_preco_item(produto_id); 
CREATE index idx_tabela_preco_item_tabela_preco_id on tabela_preco_item(tabela_preco_id); 
CREATE index idx_titulo_receber_cliente_id on titulo_receber(cliente_id); 
CREATE index idx_titulo_receber_vendedor_id on titulo_receber(vendedor_id); 
CREATE index idx_titulo_receber_filial_id on titulo_receber(filial_id); 
CREATE index idx_titulo_receber_pedido_id on titulo_receber(pedido_id); 
CREATE index idx_titulo_receber_nota_fiscal_id on titulo_receber(nota_fiscal_id); 
CREATE index idx_venda_mes_cliente_cliente_id on venda_mes_cliente(cliente_id); 
CREATE index idx_venda_mes_produto_produto_id on venda_mes_produto(produto_id); 
CREATE index idx_vendedor_filial_id on vendedor(filial_id); 
CREATE index idx_vendedor_atendimento_vendedor_id on vendedor_atendimento(vendedor_id); 
CREATE index idx_vendedor_cliente_vendedor_id on vendedor_cliente(vendedor_id); 
CREATE index idx_vendedor_cliente_cliente_id on vendedor_cliente(cliente_id); 
-- Migração para Uploads, Galeria de Fotos de Produtos e Cota por Tenant

-- 1. Alteração da tabela system_unit (Banco de Segurança: rcg_security)
-- Adiciona o limite de armazenamento em disco em megabytes para cada filial/tenant.
ALTER TABLE public.system_unit ADD COLUMN IF NOT EXISTS limite_disco_mb INTEGER DEFAULT 1000;

-- 2. Alteração da tabela atendimento (Banco Principal: rcgcrm)
-- Adiciona a coluna para armazenar o caminho do arquivo de anexo/comprovante.
ALTER TABLE public.atendimento ADD COLUMN IF NOT EXISTS anexo VARCHAR(255);

-- 3. Alteração da tabela cliente (Banco Principal: rcgcrm)
-- Adiciona a coluna para armazenar o caminho da logo do cliente.
ALTER TABLE public.cliente ADD COLUMN IF NOT EXISTS logo VARCHAR(255);

-- 4. Criação da tabela produto_imagem (Banco Principal: rcgcrm)
-- Tabela para gerenciar a galeria de múltiplas fotos de produtos.
CREATE TABLE IF NOT EXISTS public.produto_imagem (
  id SERIAL PRIMARY KEY,
  system_unit_id INTEGER DEFAULT 1,
  produto_id INTEGER NOT NULL REFERENCES public.produto(id) ON DELETE CASCADE,
  caminho VARCHAR(255) NOT NULL,
  principal CHAR(1) DEFAULT 'N', -- 'S' = Principal, 'N' = Galeria
  ordem INTEGER DEFAULT 0,        -- Ordenação
  dt_inclusao TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  dt_alteracao TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Índice composto para otimizar buscas por tenant e produto
CREATE INDEX IF NOT EXISTS idx_prod_img_tenant_prod ON public.produto_imagem(system_unit_id, produto_id);
-- ============================================================
-- RCG CRM - Fase 1 de Performance
-- Data: 2026-05-26
-- Baseado em: docs/database-performance-plan.md
--
-- Objetivo:
-- - acelerar MCV, agenda comercial, cliente 360 e analytics
-- - aplicar apenas melhorias de baixo risco e alto retorno
--
-- Observacoes:
-- - este script nao altera tipos de coluna nem cria FKs
-- - todos os indices usam IF NOT EXISTS
-- - revise tempo de execucao em producao antes de aplicar
-- ============================================================

-- ============================================================
-- 1. CRM / Agenda Comercial
-- ============================================================

CREATE INDEX IF NOT EXISTS idx_atendimento_vendedor_inicio
  ON atendimento (vendedor_id, horario_inicial)
  WHERE dt_delete IS NULL;

CREATE INDEX IF NOT EXISTS idx_atendimento_cliente_inicio_desc
  ON atendimento (cliente_id, horario_inicial DESC)
  WHERE dt_delete IS NULL;

CREATE INDEX IF NOT EXISTS idx_atendimento_tipo_inicio
  ON atendimento (atendimento_tipo_id, horario_inicial)
  WHERE dt_delete IS NULL;

CREATE INDEX IF NOT EXISTS idx_atendimento_retorno
  ON atendimento (retorno)
  WHERE dt_delete IS NULL AND retorno IS NOT NULL;

-- ============================================================
-- 2. MCV / Financeiro
-- ============================================================

CREATE INDEX IF NOT EXISTS idx_titulo_receber_cliente_venc_real_aberto
  ON titulo_receber (cliente_id, venc_real)
  WHERE reg_ativo = 'S' AND saldo > 0;

CREATE INDEX IF NOT EXISTS idx_titulo_receber_cliente_saldo_venc_real
  ON titulo_receber (cliente_id, saldo, venc_real)
  WHERE reg_ativo = 'S';

CREATE INDEX IF NOT EXISTS idx_titulo_receber_vendedor_venc_real
  ON titulo_receber (vendedor_id, venc_real)
  WHERE reg_ativo = 'S' AND saldo > 0;

CREATE INDEX IF NOT EXISTS idx_cliente_vendedor_reg_ativo
  ON cliente (vendedor_id, reg_ativo);

CREATE INDEX IF NOT EXISTS idx_cliente_regiao
  ON cliente (regiao_cliente_id);

CREATE INDEX IF NOT EXISTS idx_cliente_municipio
  ON cliente (municipio_id);

CREATE INDEX IF NOT EXISTS idx_cliente_ultima_compra
  ON cliente (ultima_compra);

CREATE INDEX IF NOT EXISTS idx_cliente_ultimo_atendimento
  ON cliente (ultimo_atendimento);

CREATE INDEX IF NOT EXISTS idx_cliente_cod_erp
  ON cliente (cod_erp);

-- ============================================================
-- 3. Faturamento / Vendas / Analytics
-- ============================================================

CREATE INDEX IF NOT EXISTS idx_nota_saida_vendedor1_emissao
  ON nota_saida (vendedor1_id, dt_emissao)
  WHERE reg_ativo = 'S';

CREATE INDEX IF NOT EXISTS idx_nota_saida_vendedor2_emissao
  ON nota_saida (vendedor2_id, dt_emissao)
  WHERE reg_ativo = 'S' AND vendedor2_id IS NOT NULL;

CREATE INDEX IF NOT EXISTS idx_nota_saida_cliente_emissao
  ON nota_saida (cliente_id, dt_emissao)
  WHERE reg_ativo = 'S';

CREATE INDEX IF NOT EXISTS idx_nota_saida_ano_mes_vendedor1
  ON nota_saida (ano, mes, vendedor1_id)
  WHERE reg_ativo = 'S';

CREATE INDEX IF NOT EXISTS idx_nota_saida_ano_mes_cliente
  ON nota_saida (ano, mes, cliente_id)
  WHERE reg_ativo = 'S';

CREATE INDEX IF NOT EXISTS idx_nota_saida_reg_ativo_tipo_numero_titulo
  ON nota_saida (reg_ativo, tipo, numero_titulo);

CREATE INDEX IF NOT EXISTS idx_nota_saida_item_nota_saida
  ON nota_saida_item (nota_saida_id);

CREATE INDEX IF NOT EXISTS idx_nota_saida_item_produto_ano_mes
  ON nota_saida_item (produto_id, ano, mes);

CREATE INDEX IF NOT EXISTS idx_nota_saida_item_cliente_ano_mes
  ON nota_saida_item (cliente_id, ano, mes);

CREATE INDEX IF NOT EXISTS idx_nota_saida_item_vendedor1_ano_mes
  ON nota_saida_item (vendedor1_id, ano, mes);

CREATE INDEX IF NOT EXISTS idx_nota_saida_item_vendedor2_ano_mes
  ON nota_saida_item (vendedor2_id, ano, mes)
  WHERE vendedor2_id IS NOT NULL;

CREATE INDEX IF NOT EXISTS idx_nota_saida_item_emissao
  ON nota_saida_item (dt_emissao);

-- ============================================================
-- 4. Metas
-- ============================================================

CREATE INDEX IF NOT EXISTS idx_meta_vendedor_mes_lookup
  ON meta_vendedor_mes (vendedor_id, ano, mes)
  WHERE dt_delete IS NULL;

CREATE INDEX IF NOT EXISTS idx_meta_vendedor_categoria_lookup
  ON meta_vendedor_categoria (meta_vendedor_mes_id, cod_erp)
  WHERE dt_delete IS NULL;

CREATE INDEX IF NOT EXISTS idx_meta_vendedor_categoria_categoria
  ON meta_vendedor_categoria (categoria_id)
  WHERE dt_delete IS NULL;

-- ============================================================
-- 5. Catalogo / Estoque / Preco
-- ============================================================

CREATE INDEX IF NOT EXISTS idx_tabela_preco_item_tabela_produto
  ON tabela_preco_item (tabela_preco_id, produto_id);

CREATE INDEX IF NOT EXISTS idx_estoque_produto_armazem
  ON estoque (produto_id, armazem_id);

CREATE INDEX IF NOT EXISTS idx_produto_categoria
  ON produto (categoria_id);

CREATE INDEX IF NOT EXISTS idx_produto_sub_categoria
  ON produto (sub_categoria_id);

CREATE INDEX IF NOT EXISTS idx_produto_cod_erp
  ON produto (cod_erp);

-- ============================================================
-- 6. Apoio a relacoes comerciais
-- ============================================================

DO $$
BEGIN
  IF to_regclass('public.vendedor_cliente') IS NOT NULL THEN
    EXECUTE 'CREATE INDEX IF NOT EXISTS idx_vendedor_cliente_vendedor ON vendedor_cliente (vendedor_id)';
    EXECUTE 'CREATE INDEX IF NOT EXISTS idx_vendedor_cliente_cliente ON vendedor_cliente (cliente_id)';
  ELSE
    RAISE NOTICE 'Tabela public.vendedor_cliente nao existe. Indices ignorados.';
  END IF;
END
$$;

DO $$
BEGIN
  IF to_regclass('public.supervisor_vendedor') IS NOT NULL THEN
    EXECUTE 'CREATE INDEX IF NOT EXISTS idx_supervisor_vendedor_supervisor ON supervisor_vendedor (supervisor_id)';
    EXECUTE 'CREATE INDEX IF NOT EXISTS idx_supervisor_vendedor_vendedor ON supervisor_vendedor (vendedor_id)';
  ELSE
    RAISE NOTICE 'Tabela public.supervisor_vendedor nao existe. Indices ignorados.';
  END IF;
END
$$;

-- ============================================================
-- 7. Pos aplicacao
-- ============================================================

-- Recomendado apos a criacao dos indices:
-- ANALYZE atendimento;
-- ANALYZE titulo_receber;
-- ANALYZE cliente;
-- ANALYZE nota_saida;
-- ANALYZE nota_saida_item;
-- ANALYZE meta_vendedor_mes;
-- ANALYZE meta_vendedor_categoria;
-- ANALYZE tabela_preco_item;
-- ANALYZE estoque;
-- ============================================================
-- RCG CRM - Fase 2 de Performance / Integridade
-- Constraints e unicidade segura
-- Data: 2026-05-26
--
-- Objetivo:
-- - adicionar FKs em modo NOT VALID
-- - adicionar unicidade de negocio quando nao houver duplicidade
--
-- Observacoes:
-- - execute primeiro: database/performance-phase2-audit.sql
-- - FKs NOT VALID passam a proteger dados novos
-- - validacao completa pode ser feita depois com ALTER TABLE ... VALIDATE CONSTRAINT
-- ============================================================

-- ============================================================
-- 1. Foreign Keys seguras (NOT VALID)
-- ============================================================

DO $$
BEGIN
  IF to_regclass('public.cliente') IS NOT NULL
     AND to_regclass('public.vendedor') IS NOT NULL THEN
    ALTER TABLE cliente
      ADD CONSTRAINT fk_cliente_vendedor
      FOREIGN KEY (vendedor_id) REFERENCES vendedor(id)
      NOT VALID;
  END IF;
EXCEPTION WHEN duplicate_object THEN
  RAISE NOTICE 'Constraint fk_cliente_vendedor ja existe.';
END
$$;

DO $$
BEGIN
  IF to_regclass('public.cliente') IS NOT NULL
     AND to_regclass('public.municipio') IS NOT NULL THEN
    ALTER TABLE cliente
      ADD CONSTRAINT fk_cliente_municipio
      FOREIGN KEY (municipio_id) REFERENCES municipio(id)
      NOT VALID;
  END IF;
EXCEPTION WHEN duplicate_object THEN
  RAISE NOTICE 'Constraint fk_cliente_municipio ja existe.';
END
$$;

DO $$
BEGIN
  IF to_regclass('public.cliente') IS NOT NULL
     AND to_regclass('public.condicao_pagamento') IS NOT NULL THEN
    ALTER TABLE cliente
      ADD CONSTRAINT fk_cliente_condicao_pagamento
      FOREIGN KEY (condicao_pagamento_id) REFERENCES condicao_pagamento(id)
      NOT VALID;
  END IF;
EXCEPTION WHEN duplicate_object THEN
  RAISE NOTICE 'Constraint fk_cliente_condicao_pagamento ja existe.';
END
$$;

DO $$
BEGIN
  IF to_regclass('public.atendimento') IS NOT NULL
     AND to_regclass('public.cliente') IS NOT NULL THEN
    ALTER TABLE atendimento
      ADD CONSTRAINT fk_atendimento_cliente
      FOREIGN KEY (cliente_id) REFERENCES cliente(id)
      NOT VALID;
  END IF;
EXCEPTION WHEN duplicate_object THEN
  RAISE NOTICE 'Constraint fk_atendimento_cliente ja existe.';
END
$$;

DO $$
BEGIN
  IF to_regclass('public.atendimento') IS NOT NULL
     AND to_regclass('public.vendedor') IS NOT NULL THEN
    ALTER TABLE atendimento
      ADD CONSTRAINT fk_atendimento_vendedor
      FOREIGN KEY (vendedor_id) REFERENCES vendedor(id)
      NOT VALID;
  END IF;
EXCEPTION WHEN duplicate_object THEN
  RAISE NOTICE 'Constraint fk_atendimento_vendedor ja existe.';
END
$$;

DO $$
BEGIN
  IF to_regclass('public.atendimento') IS NOT NULL
     AND to_regclass('public.atendimento_tipo') IS NOT NULL THEN
    ALTER TABLE atendimento
      ADD CONSTRAINT fk_atendimento_tipo
      FOREIGN KEY (atendimento_tipo_id) REFERENCES atendimento_tipo(id)
      NOT VALID;
  END IF;
EXCEPTION WHEN duplicate_object THEN
  RAISE NOTICE 'Constraint fk_atendimento_tipo ja existe.';
END
$$;

DO $$
BEGIN
  IF to_regclass('public.titulo_receber') IS NOT NULL
     AND to_regclass('public.cliente') IS NOT NULL THEN
    ALTER TABLE titulo_receber
      ADD CONSTRAINT fk_titulo_receber_cliente
      FOREIGN KEY (cliente_id) REFERENCES cliente(id)
      NOT VALID;
  END IF;
EXCEPTION WHEN duplicate_object THEN
  RAISE NOTICE 'Constraint fk_titulo_receber_cliente ja existe.';
END
$$;

DO $$
BEGIN
  IF to_regclass('public.titulo_receber') IS NOT NULL
     AND to_regclass('public.vendedor') IS NOT NULL THEN
    ALTER TABLE titulo_receber
      ADD CONSTRAINT fk_titulo_receber_vendedor
      FOREIGN KEY (vendedor_id) REFERENCES vendedor(id)
      NOT VALID;
  END IF;
EXCEPTION WHEN duplicate_object THEN
  RAISE NOTICE 'Constraint fk_titulo_receber_vendedor ja existe.';
END
$$;

DO $$
BEGIN
  IF to_regclass('public.nota_saida') IS NOT NULL
     AND to_regclass('public.cliente') IS NOT NULL THEN
    ALTER TABLE nota_saida
      ADD CONSTRAINT fk_nota_saida_cliente
      FOREIGN KEY (cliente_id) REFERENCES cliente(id)
      NOT VALID;
  END IF;
EXCEPTION WHEN duplicate_object THEN
  RAISE NOTICE 'Constraint fk_nota_saida_cliente ja existe.';
END
$$;

DO $$
BEGIN
  IF to_regclass('public.nota_saida_item') IS NOT NULL
     AND to_regclass('public.nota_saida') IS NOT NULL THEN
    ALTER TABLE nota_saida_item
      ADD CONSTRAINT fk_nota_saida_item_nota_saida
      FOREIGN KEY (nota_saida_id) REFERENCES nota_saida(id)
      NOT VALID;
  END IF;
EXCEPTION WHEN duplicate_object THEN
  RAISE NOTICE 'Constraint fk_nota_saida_item_nota_saida ja existe.';
END
$$;

DO $$
BEGIN
  IF to_regclass('public.nota_saida_item') IS NOT NULL
     AND to_regclass('public.produto') IS NOT NULL THEN
    ALTER TABLE nota_saida_item
      ADD CONSTRAINT fk_nota_saida_item_produto
      FOREIGN KEY (produto_id) REFERENCES produto(id)
      NOT VALID;
  END IF;
EXCEPTION WHEN duplicate_object THEN
  RAISE NOTICE 'Constraint fk_nota_saida_item_produto ja existe.';
END
$$;

DO $$
BEGIN
  IF to_regclass('public.meta_vendedor_mes') IS NOT NULL
     AND to_regclass('public.vendedor') IS NOT NULL THEN
    ALTER TABLE meta_vendedor_mes
      ADD CONSTRAINT fk_meta_vendedor_mes_vendedor
      FOREIGN KEY (vendedor_id) REFERENCES vendedor(id)
      NOT VALID;
  END IF;
EXCEPTION WHEN duplicate_object THEN
  RAISE NOTICE 'Constraint fk_meta_vendedor_mes_vendedor ja existe.';
END
$$;

DO $$
BEGIN
  IF to_regclass('public.meta_vendedor_categoria') IS NOT NULL
     AND to_regclass('public.meta_vendedor_mes') IS NOT NULL THEN
    ALTER TABLE meta_vendedor_categoria
      ADD CONSTRAINT fk_meta_vendedor_categoria_mes
      FOREIGN KEY (meta_vendedor_mes_id) REFERENCES meta_vendedor_mes(id)
      NOT VALID;
  END IF;
EXCEPTION WHEN duplicate_object THEN
  RAISE NOTICE 'Constraint fk_meta_vendedor_categoria_mes ja existe.';
END
$$;

DO $$
BEGIN
  IF to_regclass('public.tabela_preco_item') IS NOT NULL
     AND to_regclass('public.tabela_preco') IS NOT NULL THEN
    ALTER TABLE tabela_preco_item
      ADD CONSTRAINT fk_tabela_preco_item_tabela
      FOREIGN KEY (tabela_preco_id) REFERENCES tabela_preco(id)
      NOT VALID;
  END IF;
EXCEPTION WHEN duplicate_object THEN
  RAISE NOTICE 'Constraint fk_tabela_preco_item_tabela ja existe.';
END
$$;

DO $$
BEGIN
  IF to_regclass('public.tabela_preco_item') IS NOT NULL
     AND to_regclass('public.produto') IS NOT NULL THEN
    ALTER TABLE tabela_preco_item
      ADD CONSTRAINT fk_tabela_preco_item_produto
      FOREIGN KEY (produto_id) REFERENCES produto(id)
      NOT VALID;
  END IF;
EXCEPTION WHEN duplicate_object THEN
  RAISE NOTICE 'Constraint fk_tabela_preco_item_produto ja existe.';
END
$$;

-- ============================================================
-- 2. Unicidade de negocio
-- ============================================================

DO $$
DECLARE
  duplicates_count bigint;
BEGIN
  IF to_regclass('public.vendedor_cliente') IS NOT NULL THEN
    SELECT COUNT(*) INTO duplicates_count
    FROM (
      SELECT vendedor_id, cliente_id
      FROM vendedor_cliente
      GROUP BY vendedor_id, cliente_id
      HAVING COUNT(*) > 1
    ) d;

    IF duplicates_count = 0 THEN
      EXECUTE 'CREATE UNIQUE INDEX IF NOT EXISTS uq_vendedor_cliente ON vendedor_cliente (vendedor_id, cliente_id)';
    ELSE
      RAISE NOTICE 'Duplicidades encontradas em vendedor_cliente. Unique index ignorado.';
    END IF;
  END IF;
END
$$;

DO $$
DECLARE
  duplicates_count bigint;
BEGIN
  IF to_regclass('public.supervisor_vendedor') IS NOT NULL THEN
    SELECT COUNT(*) INTO duplicates_count
    FROM (
      SELECT supervisor_id, vendedor_id
      FROM supervisor_vendedor
      GROUP BY supervisor_id, vendedor_id
      HAVING COUNT(*) > 1
    ) d;

    IF duplicates_count = 0 THEN
      EXECUTE 'CREATE UNIQUE INDEX IF NOT EXISTS uq_supervisor_vendedor ON supervisor_vendedor (supervisor_id, vendedor_id)';
    ELSE
      RAISE NOTICE 'Duplicidades encontradas em supervisor_vendedor. Unique index ignorado.';
    END IF;
  END IF;
END
$$;

DO $$
DECLARE
  duplicates_count bigint;
BEGIN
  IF to_regclass('public.meta_vendedor_mes') IS NOT NULL THEN
    SELECT COUNT(*) INTO duplicates_count
    FROM (
      SELECT vendedor_id, ano, mes, tipo
      FROM meta_vendedor_mes
      WHERE dt_delete IS NULL
      GROUP BY vendedor_id, ano, mes, tipo
      HAVING COUNT(*) > 1
    ) d;

    IF duplicates_count = 0 THEN
      EXECUTE 'CREATE UNIQUE INDEX IF NOT EXISTS uq_meta_vendedor_mes_ativo ON meta_vendedor_mes (vendedor_id, ano, mes, tipo) WHERE dt_delete IS NULL';
    ELSE
      RAISE NOTICE 'Duplicidades encontradas em meta_vendedor_mes. Unique index ignorado.';
    END IF;
  END IF;
END
$$;

DO $$
DECLARE
  duplicates_count bigint;
BEGIN
  IF to_regclass('public.tabela_preco_item') IS NOT NULL THEN
    SELECT COUNT(*) INTO duplicates_count
    FROM (
      SELECT tabela_preco_id, produto_id
      FROM tabela_preco_item
      GROUP BY tabela_preco_id, produto_id
      HAVING COUNT(*) > 1
    ) d;

    IF duplicates_count = 0 THEN
      EXECUTE 'CREATE UNIQUE INDEX IF NOT EXISTS uq_tabela_preco_item ON tabela_preco_item (tabela_preco_id, produto_id)';
    ELSE
      RAISE NOTICE 'Duplicidades encontradas em tabela_preco_item. Unique index ignorado.';
    END IF;
  END IF;
END
$$;

DO $$
DECLARE
  duplicates_count bigint;
BEGIN
  IF to_regclass('public.cliente_acesso') IS NOT NULL THEN
    SELECT COUNT(*) INTO duplicates_count
    FROM (
      SELECT login
      FROM cliente_acesso
      WHERE login IS NOT NULL
      GROUP BY login
      HAVING COUNT(*) > 1
    ) d;

    IF duplicates_count = 0 THEN
      EXECUTE 'CREATE UNIQUE INDEX IF NOT EXISTS uq_cliente_acesso_login ON cliente_acesso (login) WHERE login IS NOT NULL';
    ELSE
      RAISE NOTICE 'Duplicidades encontradas em cliente_acesso.login. Unique index ignorado.';
    END IF;
  END IF;
END
$$;

-- ============================================================
-- 3. Validacao posterior
-- ============================================================

-- Execute depois de corrigir dados antigos:
-- ALTER TABLE cliente VALIDATE CONSTRAINT fk_cliente_vendedor;
-- ALTER TABLE cliente VALIDATE CONSTRAINT fk_cliente_municipio;
-- ALTER TABLE cliente VALIDATE CONSTRAINT fk_cliente_condicao_pagamento;
-- ALTER TABLE atendimento VALIDATE CONSTRAINT fk_atendimento_cliente;
-- ALTER TABLE atendimento VALIDATE CONSTRAINT fk_atendimento_vendedor;
-- ALTER TABLE atendimento VALIDATE CONSTRAINT fk_atendimento_tipo;
-- ALTER TABLE titulo_receber VALIDATE CONSTRAINT fk_titulo_receber_cliente;
-- ALTER TABLE titulo_receber VALIDATE CONSTRAINT fk_titulo_receber_vendedor;
-- ALTER TABLE nota_saida VALIDATE CONSTRAINT fk_nota_saida_cliente;
-- ALTER TABLE nota_saida_item VALIDATE CONSTRAINT fk_nota_saida_item_nota_saida;
-- ALTER TABLE nota_saida_item VALIDATE CONSTRAINT fk_nota_saida_item_produto;
-- ALTER TABLE meta_vendedor_mes VALIDATE CONSTRAINT fk_meta_vendedor_mes_vendedor;
-- ALTER TABLE meta_vendedor_categoria VALIDATE CONSTRAINT fk_meta_vendedor_categoria_mes;
-- ALTER TABLE tabela_preco_item VALIDATE CONSTRAINT fk_tabela_preco_item_tabela;
-- ALTER TABLE tabela_preco_item VALIDATE CONSTRAINT fk_tabela_preco_item_produto;
-- ============================================================
-- RCG CRM - Fase 2 de Performance / Integridade
-- Auditoria previa de dados
-- Data: 2026-05-26
--
-- Objetivo:
-- - identificar dados orfaos antes de criar FKs
-- - identificar duplicidades antes de criar unicidade
--
-- Uso:
-- - execute consulta por consulta
-- - qualquer linha retornada precisa ser tratada antes da constraint
-- ============================================================

-- ============================================================
-- 1. Orfaos - Comercial / CRM
-- ============================================================

-- cliente -> vendedor
SELECT c.id, c.vendedor_id
FROM cliente c
LEFT JOIN vendedor v ON v.id = c.vendedor_id
WHERE c.vendedor_id IS NOT NULL
  AND v.id IS NULL;

-- cliente -> municipio
SELECT c.id, c.municipio_id
FROM cliente c
LEFT JOIN municipio m ON m.id = c.municipio_id
WHERE c.municipio_id IS NOT NULL
  AND m.id IS NULL;

-- cliente -> condicao_pagamento
SELECT c.id, c.condicao_pagamento_id
FROM cliente c
LEFT JOIN condicao_pagamento cp ON cp.id = c.condicao_pagamento_id
WHERE c.condicao_pagamento_id IS NOT NULL
  AND cp.id IS NULL;

-- atendimento -> cliente
SELECT a.id, a.cliente_id
FROM atendimento a
LEFT JOIN cliente c ON c.id = a.cliente_id
WHERE a.cliente_id IS NOT NULL
  AND c.id IS NULL;

-- atendimento -> vendedor
SELECT a.id, a.vendedor_id
FROM atendimento a
LEFT JOIN vendedor v ON v.id = a.vendedor_id
WHERE a.vendedor_id IS NOT NULL
  AND v.id IS NULL;

-- atendimento -> atendimento_tipo
SELECT a.id, a.atendimento_tipo_id
FROM atendimento a
LEFT JOIN atendimento_tipo t ON t.id = a.atendimento_tipo_id
WHERE a.atendimento_tipo_id IS NOT NULL
  AND t.id IS NULL;

-- ============================================================
-- 2. Orfaos - Financeiro / Faturamento
-- ============================================================

-- titulo_receber -> cliente
SELECT tr.id, tr.cliente_id
FROM titulo_receber tr
LEFT JOIN cliente c ON c.id = tr.cliente_id
WHERE tr.cliente_id IS NOT NULL
  AND c.id IS NULL;

-- titulo_receber -> vendedor
SELECT tr.id, tr.vendedor_id
FROM titulo_receber tr
LEFT JOIN vendedor v ON v.id = tr.vendedor_id
WHERE tr.vendedor_id IS NOT NULL
  AND v.id IS NULL;

-- nota_saida -> cliente
SELECT ns.id, ns.cliente_id
FROM nota_saida ns
LEFT JOIN cliente c ON c.id = ns.cliente_id
WHERE ns.cliente_id IS NOT NULL
  AND c.id IS NULL;

-- nota_saida -> vendedor1
SELECT ns.id, ns.vendedor1_id
FROM nota_saida ns
LEFT JOIN vendedor v ON v.id = ns.vendedor1_id
WHERE ns.vendedor1_id IS NOT NULL
  AND v.id IS NULL;

-- nota_saida_item -> nota_saida
SELECT nsi.id, nsi.nota_saida_id
FROM nota_saida_item nsi
LEFT JOIN nota_saida ns ON ns.id = nsi.nota_saida_id
WHERE nsi.nota_saida_id IS NOT NULL
  AND ns.id IS NULL;

-- nota_saida_item -> produto
SELECT nsi.id, nsi.produto_id
FROM nota_saida_item nsi
LEFT JOIN produto p ON p.id = nsi.produto_id
WHERE nsi.produto_id IS NOT NULL
  AND p.id IS NULL;

-- ============================================================
-- 3. Orfaos - Metas / Preco
-- ============================================================

-- meta_vendedor_mes -> vendedor
SELECT mvm.id, mvm.vendedor_id
FROM meta_vendedor_mes mvm
LEFT JOIN vendedor v ON v.id = mvm.vendedor_id
WHERE mvm.vendedor_id IS NOT NULL
  AND v.id IS NULL;

-- meta_vendedor_categoria -> meta_vendedor_mes
SELECT mvc.id, mvc.meta_vendedor_mes_id
FROM meta_vendedor_categoria mvc
LEFT JOIN meta_vendedor_mes mvm ON mvm.id = mvc.meta_vendedor_mes_id
WHERE mvc.meta_vendedor_mes_id IS NOT NULL
  AND mvm.id IS NULL;

-- tabela_preco_item -> tabela_preco
SELECT tpi.id, tpi.tabela_preco_id
FROM tabela_preco_item tpi
LEFT JOIN tabela_preco tp ON tp.id = tpi.tabela_preco_id
WHERE tpi.tabela_preco_id IS NOT NULL
  AND tp.id IS NULL;

-- tabela_preco_item -> produto
SELECT tpi.id, tpi.produto_id
FROM tabela_preco_item tpi
LEFT JOIN produto p ON p.id = tpi.produto_id
WHERE tpi.produto_id IS NOT NULL
  AND p.id IS NULL;

-- ============================================================
-- 4. Duplicidades - Unicidade de negocio
-- ============================================================

-- vendedor_cliente
SELECT vendedor_id, cliente_id, COUNT(*)
FROM vendedor_cliente
GROUP BY vendedor_id, cliente_id
HAVING COUNT(*) > 1;

-- supervisor_vendedor
SELECT supervisor_id, vendedor_id, COUNT(*)
FROM supervisor_vendedor
GROUP BY supervisor_id, vendedor_id
HAVING COUNT(*) > 1;

-- meta_vendedor_mes (ativos)
SELECT vendedor_id, ano, mes, tipo, COUNT(*)
FROM meta_vendedor_mes
WHERE dt_delete IS NULL
GROUP BY vendedor_id, ano, mes, tipo
HAVING COUNT(*) > 1;

-- tabela_preco_item
SELECT tabela_preco_id, produto_id, COUNT(*)
FROM tabela_preco_item
GROUP BY tabela_preco_id, produto_id
HAVING COUNT(*) > 1;

-- cliente_acesso por login
SELECT login, COUNT(*)
FROM cliente_acesso
WHERE login IS NOT NULL
GROUP BY login
HAVING COUNT(*) > 1;
