<?php

class Vendedor extends TRecord
{
    const TABLENAME  = 'vendedor';
    const PRIMARYKEY = 'id';
    const IDPOLICY   =  'serial'; // {max, serial}

    const CREATEDAT  = 'dt_inclusao';
    const UPDATEDAT  = 'dt_alteracao';

    private Filial $filial;
    private SystemUsers $system_users;

    /**
     * Constructor method
     */
    public function __construct($id = NULL, $callObjectLoad = TRUE)
    {
        parent::__construct($id, $callObjectLoad);
        parent::addAttribute('filial_id');
        parent::addAttribute('cod_erp');
        parent::addAttribute('system_users_id');
        parent::addAttribute('nome');
        parent::addAttribute('nome_reduzido');
        parent::addAttribute('ddd');
        parent::addAttribute('telefone');
        parent::addAttribute('celular');
        parent::addAttribute('email');
        parent::addAttribute('status');
        parent::addAttribute('vendedor');
        parent::addAttribute('dashboard');
        parent::addAttribute('dt_alteracao');
        parent::addAttribute('dt_inclusao');
        parent::addAttribute('dt_nascmento');
        parent::addAttribute('system_unit_id');
        parent::addAttribute('supervisor');
        parent::addAttribute('supervisor_id');
        parent::addAttribute('desligado');
    
    }

    /**
     * Method set_filial
     * Sample of usage: $var->filial = $object;
     * @param $object Instance of Filial
     */
    public function set_filial(Filial $object)
    {
        $this->filial = $object;
        $this->filial_id = $object->id;
    }

    /**
     * Method get_filial
     * Sample of usage: $var->filial->attribute;
     * @returns Filial instance
     */
    public function get_filial()
    {
    
        // loads the associated object
        if (empty($this->filial))
            $this->filial = new Filial($this->filial_id);
    
        // returns the associated object
        return $this->filial;
    }
    /**
     * Method set_system_users
     * Sample of usage: $var->system_users = $object;
     * @param $object Instance of SystemUsers
     */
    public function set_system_users(SystemUsers $object)
    {
        $this->system_users = $object;
        $this->system_users_id = $object->id;
    }

    /**
     * Method get_system_users
     * Sample of usage: $var->system_users->attribute;
     * @returns SystemUsers instance
     */
    public function get_system_users()
    {
        try{
        TTransaction::openFake('permission');
        // loads the associated object
        if (empty($this->system_users))
            $this->system_users = new SystemUsers($this->system_users_id);
        TTransaction::close();
        }catch(Exception $e){
            TTransaction::close();
        }
        // returns the associated object
        return $this->system_users;
    }

    /**
     * Method getNotaSaidas
     */
    public function getNotaSaidasByVendedor1s()
    {
        $criteria = new TCriteria;
        $criteria->add(new TFilter('vendedor1_id', '=', $this->id));
        return NotaSaida::getObjects( $criteria );
    }
    /**
     * Method getNotaSaidas
     */
    public function getNotaSaidasByVendedor2s()
    {
        $criteria = new TCriteria;
        $criteria->add(new TFilter('vendedor2_id', '=', $this->id));
        return NotaSaida::getObjects( $criteria );
    }
    /**
     * Method getClientes
     */
    public function getClientes()
    {
        $criteria = new TCriteria;
        $criteria->add(new TFilter('vendedor_id', '=', $this->id));
        return Cliente::getObjects( $criteria );
    }
    /**
     * Method getMetaVendedorMess
     */
    public function getMetaVendedorMess()
    {
        $criteria = new TCriteria;
        $criteria->add(new TFilter('vendedor_id', '=', $this->id));
        return MetaVendedorMes::getObjects( $criteria );
    }
    /**
     * Method getPedidos
     */
    public function getPedidosByVendedor1s()
    {
        $criteria = new TCriteria;
        $criteria->add(new TFilter('vendedor1_id', '=', $this->id));
        return Pedido::getObjects( $criteria );
    }
    /**
     * Method getPedidos
     */
    public function getPedidosByVendedor2s()
    {
        $criteria = new TCriteria;
        $criteria->add(new TFilter('vendedor2_id', '=', $this->id));
        return Pedido::getObjects( $criteria );
    }
    /**
     * Method getNegociacaos
     */
    public function getNegociacaos()
    {
        $criteria = new TCriteria;
        $criteria->add(new TFilter('vendedor_id', '=', $this->id));
        return Negociacao::getObjects( $criteria );
    }
    /**
     * Method getOrcamentos
     */
    public function getOrcamentos()
    {
        $criteria = new TCriteria;
        $criteria->add(new TFilter('vendedor_id', '=', $this->id));
        return Orcamento::getObjects( $criteria );
    }
    /**
     * Method getTituloRecebers
     */
    public function getTituloRecebers()
    {
        $criteria = new TCriteria;
        $criteria->add(new TFilter('vendedor_id', '=', $this->id));
        return TituloReceber::getObjects( $criteria );
    }
    /**
     * Method getClienteVendedorMess
     */
    public function getClienteVendedorMess()
    {
        $criteria = new TCriteria;
        $criteria->add(new TFilter('vendedor_id', '=', $this->id));
        return ClienteVendedorMes::getObjects( $criteria );
    }
    /**
     * Method getVendedorClientes
     */
    public function getVendedorClientes()
    {
        $criteria = new TCriteria;
        $criteria->add(new TFilter('vendedor_id', '=', $this->id));
        return VendedorCliente::getObjects( $criteria );
    }
    /**
     * Method getNotaSaidaItems
     */
    public function getNotaSaidaItemsByVendedor1s()
    {
        $criteria = new TCriteria;
        $criteria->add(new TFilter('vendedor1_id', '=', $this->id));
        return NotaSaidaItem::getObjects( $criteria );
    }
    /**
     * Method getNotaSaidaItems
     */
    public function getNotaSaidaItemsByVendedor2s()
    {
        $criteria = new TCriteria;
        $criteria->add(new TFilter('vendedor2_id', '=', $this->id));
        return NotaSaidaItem::getObjects( $criteria );
    }
    /**
     * Method getAtendimentos
     */
    public function getAtendimentos()
    {
        $criteria = new TCriteria;
        $criteria->add(new TFilter('vendedor_id', '=', $this->id));
        return Atendimento::getObjects( $criteria );
    }
    /**
     * Method getNotaEntradas
     */
    public function getNotaEntradas()
    {
        $criteria = new TCriteria;
        $criteria->add(new TFilter('vendedor1_id', '=', $this->id));
        return NotaEntrada::getObjects( $criteria );
    }
    /**
     * Method getVendedorAtendimentos
     */
    public function getVendedorAtendimentos()
    {
        $criteria = new TCriteria;
        $criteria->add(new TFilter('vendedor_id', '=', $this->id));
        return VendedorAtendimento::getObjects( $criteria );
    }
    /**
     * Method getSupervisorVendedors
     */
    public function getSupervisorVendedors()
    {
        $criteria = new TCriteria;
        $criteria->add(new TFilter('vendedor_id', '=', $this->id));
        return SupervisorVendedor::getObjects( $criteria );
    }
    /**
     * Method getClienteAtendimentos
     */
    public function getClienteAtendimentos()
    {
        $criteria = new TCriteria;
        $criteria->add(new TFilter('vendedor_id', '=', $this->id));
        return ClienteAtendimento::getObjects( $criteria );
    }

    public function set_nota_saida_filial_to_string($nota_saida_filial_to_string)
    {
        if(is_array($nota_saida_filial_to_string))
        {
            $values = Filial::where('id', 'in', $nota_saida_filial_to_string)->getIndexedArray('apelido', 'apelido');
            $this->nota_saida_filial_to_string = implode(', ', $values);
        }
        else
        {
            $this->nota_saida_filial_to_string = $nota_saida_filial_to_string;
        }

        $this->vdata['nota_saida_filial_to_string'] = $this->nota_saida_filial_to_string;
    }

    public function get_nota_saida_filial_to_string()
    {
        if(!empty($this->nota_saida_filial_to_string))
        {
            return $this->nota_saida_filial_to_string;
        }
    
        $values = NotaSaida::where('vendedor2_id', '=', $this->id)->getIndexedArray('filial_id','{filial->apelido}');
        return implode(', ', $values);
    }

    public function set_nota_saida_cliente_to_string($nota_saida_cliente_to_string)
    {
        if(is_array($nota_saida_cliente_to_string))
        {
            $values = Cliente::where('id', 'in', $nota_saida_cliente_to_string)->getIndexedArray('razao', 'razao');
            $this->nota_saida_cliente_to_string = implode(', ', $values);
        }
        else
        {
            $this->nota_saida_cliente_to_string = $nota_saida_cliente_to_string;
        }

        $this->vdata['nota_saida_cliente_to_string'] = $this->nota_saida_cliente_to_string;
    }

    public function get_nota_saida_cliente_to_string()
    {
        if(!empty($this->nota_saida_cliente_to_string))
        {
            return $this->nota_saida_cliente_to_string;
        }
    
        $values = NotaSaida::where('vendedor2_id', '=', $this->id)->getIndexedArray('cliente_id','{cliente->razao}');
        return implode(', ', $values);
    }

    public function set_nota_saida_fornecedor_to_string($nota_saida_fornecedor_to_string)
    {
        if(is_array($nota_saida_fornecedor_to_string))
        {
            $values = Fornecedor::where('id', 'in', $nota_saida_fornecedor_to_string)->getIndexedArray('id', 'id');
            $this->nota_saida_fornecedor_to_string = implode(', ', $values);
        }
        else
        {
            $this->nota_saida_fornecedor_to_string = $nota_saida_fornecedor_to_string;
        }

        $this->vdata['nota_saida_fornecedor_to_string'] = $this->nota_saida_fornecedor_to_string;
    }

    public function get_nota_saida_fornecedor_to_string()
    {
        if(!empty($this->nota_saida_fornecedor_to_string))
        {
            return $this->nota_saida_fornecedor_to_string;
        }
    
        $values = NotaSaida::where('vendedor2_id', '=', $this->id)->getIndexedArray('fornecedor_id','{fornecedor->id}');
        return implode(', ', $values);
    }

    public function set_nota_saida_vendedor1_to_string($nota_saida_vendedor1_to_string)
    {
        if(is_array($nota_saida_vendedor1_to_string))
        {
            $values = Vendedor::where('id', 'in', $nota_saida_vendedor1_to_string)->getIndexedArray('nome', 'nome');
            $this->nota_saida_vendedor1_to_string = implode(', ', $values);
        }
        else
        {
            $this->nota_saida_vendedor1_to_string = $nota_saida_vendedor1_to_string;
        }

        $this->vdata['nota_saida_vendedor1_to_string'] = $this->nota_saida_vendedor1_to_string;
    }

    public function get_nota_saida_vendedor1_to_string()
    {
        if(!empty($this->nota_saida_vendedor1_to_string))
        {
            return $this->nota_saida_vendedor1_to_string;
        }
    
        $values = NotaSaida::where('vendedor2_id', '=', $this->id)->getIndexedArray('vendedor1_id','{vendedor1->nome}');
        return implode(', ', $values);
    }

    public function set_nota_saida_vendedor2_to_string($nota_saida_vendedor2_to_string)
    {
        if(is_array($nota_saida_vendedor2_to_string))
        {
            $values = Vendedor::where('id', 'in', $nota_saida_vendedor2_to_string)->getIndexedArray('nome', 'nome');
            $this->nota_saida_vendedor2_to_string = implode(', ', $values);
        }
        else
        {
            $this->nota_saida_vendedor2_to_string = $nota_saida_vendedor2_to_string;
        }

        $this->vdata['nota_saida_vendedor2_to_string'] = $this->nota_saida_vendedor2_to_string;
    }

    public function get_nota_saida_vendedor2_to_string()
    {
        if(!empty($this->nota_saida_vendedor2_to_string))
        {
            return $this->nota_saida_vendedor2_to_string;
        }
    
        $values = NotaSaida::where('vendedor2_id', '=', $this->id)->getIndexedArray('vendedor2_id','{vendedor2->nome}');
        return implode(', ', $values);
    }

    public function set_cliente_filial_to_string($cliente_filial_to_string)
    {
        if(is_array($cliente_filial_to_string))
        {
            $values = Filial::where('id', 'in', $cliente_filial_to_string)->getIndexedArray('apelido', 'apelido');
            $this->cliente_filial_to_string = implode(', ', $values);
        }
        else
        {
            $this->cliente_filial_to_string = $cliente_filial_to_string;
        }

        $this->vdata['cliente_filial_to_string'] = $this->cliente_filial_to_string;
    }

    public function get_cliente_filial_to_string()
    {
        if(!empty($this->cliente_filial_to_string))
        {
            return $this->cliente_filial_to_string;
        }
    
        $values = Cliente::where('vendedor_id', '=', $this->id)->getIndexedArray('filial_id','{filial->apelido}');
        return implode(', ', $values);
    }

    public function set_cliente_tipo_cliente_to_string($cliente_tipo_cliente_to_string)
    {
        if(is_array($cliente_tipo_cliente_to_string))
        {
            $values = TipoCliente::where('id', 'in', $cliente_tipo_cliente_to_string)->getIndexedArray('descricao', 'descricao');
            $this->cliente_tipo_cliente_to_string = implode(', ', $values);
        }
        else
        {
            $this->cliente_tipo_cliente_to_string = $cliente_tipo_cliente_to_string;
        }

        $this->vdata['cliente_tipo_cliente_to_string'] = $this->cliente_tipo_cliente_to_string;
    }

    public function get_cliente_tipo_cliente_to_string()
    {
        if(!empty($this->cliente_tipo_cliente_to_string))
        {
            return $this->cliente_tipo_cliente_to_string;
        }
    
        $values = Cliente::where('vendedor_id', '=', $this->id)->getIndexedArray('tipo_cliente_id','{tipo_cliente->descricao}');
        return implode(', ', $values);
    }

    public function set_cliente_municipio_to_string($cliente_municipio_to_string)
    {
        if(is_array($cliente_municipio_to_string))
        {
            $values = Municipio::where('id', 'in', $cliente_municipio_to_string)->getIndexedArray('cod_erp', 'cod_erp');
            $this->cliente_municipio_to_string = implode(', ', $values);
        }
        else
        {
            $this->cliente_municipio_to_string = $cliente_municipio_to_string;
        }

        $this->vdata['cliente_municipio_to_string'] = $this->cliente_municipio_to_string;
    }

    public function get_cliente_municipio_to_string()
    {
        if(!empty($this->cliente_municipio_to_string))
        {
            return $this->cliente_municipio_to_string;
        }
    
        $values = Cliente::where('vendedor_id', '=', $this->id)->getIndexedArray('municipio_id','{municipio->cod_erp}');
        return implode(', ', $values);
    }

    public function set_cliente_vendedor_to_string($cliente_vendedor_to_string)
    {
        if(is_array($cliente_vendedor_to_string))
        {
            $values = Vendedor::where('id', 'in', $cliente_vendedor_to_string)->getIndexedArray('nome', 'nome');
            $this->cliente_vendedor_to_string = implode(', ', $values);
        }
        else
        {
            $this->cliente_vendedor_to_string = $cliente_vendedor_to_string;
        }

        $this->vdata['cliente_vendedor_to_string'] = $this->cliente_vendedor_to_string;
    }

    public function get_cliente_vendedor_to_string()
    {
        if(!empty($this->cliente_vendedor_to_string))
        {
            return $this->cliente_vendedor_to_string;
        }
    
        $values = Cliente::where('vendedor_id', '=', $this->id)->getIndexedArray('vendedor_id','{vendedor->nome}');
        return implode(', ', $values);
    }

    public function set_cliente_condicao_pagamento_to_string($cliente_condicao_pagamento_to_string)
    {
        if(is_array($cliente_condicao_pagamento_to_string))
        {
            $values = CondicaoPagamento::where('id', 'in', $cliente_condicao_pagamento_to_string)->getIndexedArray('descricao', 'descricao');
            $this->cliente_condicao_pagamento_to_string = implode(', ', $values);
        }
        else
        {
            $this->cliente_condicao_pagamento_to_string = $cliente_condicao_pagamento_to_string;
        }

        $this->vdata['cliente_condicao_pagamento_to_string'] = $this->cliente_condicao_pagamento_to_string;
    }

    public function get_cliente_condicao_pagamento_to_string()
    {
        if(!empty($this->cliente_condicao_pagamento_to_string))
        {
            return $this->cliente_condicao_pagamento_to_string;
        }
    
        $values = Cliente::where('vendedor_id', '=', $this->id)->getIndexedArray('condicao_pagamento_id','{condicao_pagamento->descricao}');
        return implode(', ', $values);
    }

    public function set_cliente_tabela_preco_to_string($cliente_tabela_preco_to_string)
    {
        if(is_array($cliente_tabela_preco_to_string))
        {
            $values = TabelaPreco::where('id', 'in', $cliente_tabela_preco_to_string)->getIndexedArray('descricao', 'descricao');
            $this->cliente_tabela_preco_to_string = implode(', ', $values);
        }
        else
        {
            $this->cliente_tabela_preco_to_string = $cliente_tabela_preco_to_string;
        }

        $this->vdata['cliente_tabela_preco_to_string'] = $this->cliente_tabela_preco_to_string;
    }

    public function get_cliente_tabela_preco_to_string()
    {
        if(!empty($this->cliente_tabela_preco_to_string))
        {
            return $this->cliente_tabela_preco_to_string;
        }
    
        $values = Cliente::where('vendedor_id', '=', $this->id)->getIndexedArray('tabela_preco_id','{tabela_preco->descricao}');
        return implode(', ', $values);
    }

    public function set_cliente_seguimento_to_string($cliente_seguimento_to_string)
    {
        if(is_array($cliente_seguimento_to_string))
        {
            $values = Segmento::where('id', 'in', $cliente_seguimento_to_string)->getIndexedArray('descricao', 'descricao');
            $this->cliente_seguimento_to_string = implode(', ', $values);
        }
        else
        {
            $this->cliente_seguimento_to_string = $cliente_seguimento_to_string;
        }

        $this->vdata['cliente_seguimento_to_string'] = $this->cliente_seguimento_to_string;
    }

    public function get_cliente_seguimento_to_string()
    {
        if(!empty($this->cliente_seguimento_to_string))
        {
            return $this->cliente_seguimento_to_string;
        }
    
        $values = Cliente::where('vendedor_id', '=', $this->id)->getIndexedArray('seguimento_id','{seguimento->descricao}');
        return implode(', ', $values);
    }

    public function set_cliente_motivo_bloqueio_to_string($cliente_motivo_bloqueio_to_string)
    {
        if(is_array($cliente_motivo_bloqueio_to_string))
        {
            $values = MotivoBloqueio::where('id', 'in', $cliente_motivo_bloqueio_to_string)->getIndexedArray('id', 'id');
            $this->cliente_motivo_bloqueio_to_string = implode(', ', $values);
        }
        else
        {
            $this->cliente_motivo_bloqueio_to_string = $cliente_motivo_bloqueio_to_string;
        }

        $this->vdata['cliente_motivo_bloqueio_to_string'] = $this->cliente_motivo_bloqueio_to_string;
    }

    public function get_cliente_motivo_bloqueio_to_string()
    {
        if(!empty($this->cliente_motivo_bloqueio_to_string))
        {
            return $this->cliente_motivo_bloqueio_to_string;
        }
    
        $values = Cliente::where('vendedor_id', '=', $this->id)->getIndexedArray('motivo_bloqueio_id','{motivo_bloqueio->id}');
        return implode(', ', $values);
    }

    public function set_cliente_regiao_cliente_to_string($cliente_regiao_cliente_to_string)
    {
        if(is_array($cliente_regiao_cliente_to_string))
        {
            $values = RegiaoCliente::where('id', 'in', $cliente_regiao_cliente_to_string)->getIndexedArray('id', 'id');
            $this->cliente_regiao_cliente_to_string = implode(', ', $values);
        }
        else
        {
            $this->cliente_regiao_cliente_to_string = $cliente_regiao_cliente_to_string;
        }

        $this->vdata['cliente_regiao_cliente_to_string'] = $this->cliente_regiao_cliente_to_string;
    }

    public function get_cliente_regiao_cliente_to_string()
    {
        if(!empty($this->cliente_regiao_cliente_to_string))
        {
            return $this->cliente_regiao_cliente_to_string;
        }
    
        $values = Cliente::where('vendedor_id', '=', $this->id)->getIndexedArray('regiao_cliente_id','{regiao_cliente->id}');
        return implode(', ', $values);
    }

    public function set_cliente_situacao_cadastral_to_string($cliente_situacao_cadastral_to_string)
    {
        if(is_array($cliente_situacao_cadastral_to_string))
        {
            $values = SituacaoCadastral::where('id', 'in', $cliente_situacao_cadastral_to_string)->getIndexedArray('descricao', 'descricao');
            $this->cliente_situacao_cadastral_to_string = implode(', ', $values);
        }
        else
        {
            $this->cliente_situacao_cadastral_to_string = $cliente_situacao_cadastral_to_string;
        }

        $this->vdata['cliente_situacao_cadastral_to_string'] = $this->cliente_situacao_cadastral_to_string;
    }

    public function get_cliente_situacao_cadastral_to_string()
    {
        if(!empty($this->cliente_situacao_cadastral_to_string))
        {
            return $this->cliente_situacao_cadastral_to_string;
        }
    
        $values = Cliente::where('vendedor_id', '=', $this->id)->getIndexedArray('situacao_cadastral_id','{situacao_cadastral->descricao}');
        return implode(', ', $values);
    }

    public function set_meta_vendedor_mes_vendedor_to_string($meta_vendedor_mes_vendedor_to_string)
    {
        if(is_array($meta_vendedor_mes_vendedor_to_string))
        {
            $values = Vendedor::where('id', 'in', $meta_vendedor_mes_vendedor_to_string)->getIndexedArray('nome', 'nome');
            $this->meta_vendedor_mes_vendedor_to_string = implode(', ', $values);
        }
        else
        {
            $this->meta_vendedor_mes_vendedor_to_string = $meta_vendedor_mes_vendedor_to_string;
        }

        $this->vdata['meta_vendedor_mes_vendedor_to_string'] = $this->meta_vendedor_mes_vendedor_to_string;
    }

    public function get_meta_vendedor_mes_vendedor_to_string()
    {
        if(!empty($this->meta_vendedor_mes_vendedor_to_string))
        {
            return $this->meta_vendedor_mes_vendedor_to_string;
        }
    
        $values = MetaVendedorMes::where('vendedor_id', '=', $this->id)->getIndexedArray('vendedor_id','{vendedor->nome}');
        return implode(', ', $values);
    }

    public function set_pedido_pedido_estado_to_string($pedido_pedido_estado_to_string)
    {
        if(is_array($pedido_pedido_estado_to_string))
        {
            $values = PedidoEstado::where('id', 'in', $pedido_pedido_estado_to_string)->getIndexedArray('id', 'id');
            $this->pedido_pedido_estado_to_string = implode(', ', $values);
        }
        else
        {
            $this->pedido_pedido_estado_to_string = $pedido_pedido_estado_to_string;
        }

        $this->vdata['pedido_pedido_estado_to_string'] = $this->pedido_pedido_estado_to_string;
    }

    public function get_pedido_pedido_estado_to_string()
    {
        if(!empty($this->pedido_pedido_estado_to_string))
        {
            return $this->pedido_pedido_estado_to_string;
        }
    
        $values = Pedido::where('vendedor2_id', '=', $this->id)->getIndexedArray('pedido_estado_id','{pedido_estado->id}');
        return implode(', ', $values);
    }

    public function set_pedido_cliente_to_string($pedido_cliente_to_string)
    {
        if(is_array($pedido_cliente_to_string))
        {
            $values = Cliente::where('id', 'in', $pedido_cliente_to_string)->getIndexedArray('razao', 'razao');
            $this->pedido_cliente_to_string = implode(', ', $values);
        }
        else
        {
            $this->pedido_cliente_to_string = $pedido_cliente_to_string;
        }

        $this->vdata['pedido_cliente_to_string'] = $this->pedido_cliente_to_string;
    }

    public function get_pedido_cliente_to_string()
    {
        if(!empty($this->pedido_cliente_to_string))
        {
            return $this->pedido_cliente_to_string;
        }
    
        $values = Pedido::where('vendedor2_id', '=', $this->id)->getIndexedArray('cliente_id','{cliente->razao}');
        return implode(', ', $values);
    }

    public function set_pedido_cliente_entrega_to_string($pedido_cliente_entrega_to_string)
    {
        if(is_array($pedido_cliente_entrega_to_string))
        {
            $values = Cliente::where('id', 'in', $pedido_cliente_entrega_to_string)->getIndexedArray('razao', 'razao');
            $this->pedido_cliente_entrega_to_string = implode(', ', $values);
        }
        else
        {
            $this->pedido_cliente_entrega_to_string = $pedido_cliente_entrega_to_string;
        }

        $this->vdata['pedido_cliente_entrega_to_string'] = $this->pedido_cliente_entrega_to_string;
    }

    public function get_pedido_cliente_entrega_to_string()
    {
        if(!empty($this->pedido_cliente_entrega_to_string))
        {
            return $this->pedido_cliente_entrega_to_string;
        }
    
        $values = Pedido::where('vendedor2_id', '=', $this->id)->getIndexedArray('cliente_entrega_id','{cliente_entrega->razao}');
        return implode(', ', $values);
    }

    public function set_pedido_vendedor1_to_string($pedido_vendedor1_to_string)
    {
        if(is_array($pedido_vendedor1_to_string))
        {
            $values = Vendedor::where('id', 'in', $pedido_vendedor1_to_string)->getIndexedArray('nome', 'nome');
            $this->pedido_vendedor1_to_string = implode(', ', $values);
        }
        else
        {
            $this->pedido_vendedor1_to_string = $pedido_vendedor1_to_string;
        }

        $this->vdata['pedido_vendedor1_to_string'] = $this->pedido_vendedor1_to_string;
    }

    public function get_pedido_vendedor1_to_string()
    {
        if(!empty($this->pedido_vendedor1_to_string))
        {
            return $this->pedido_vendedor1_to_string;
        }
    
        $values = Pedido::where('vendedor2_id', '=', $this->id)->getIndexedArray('vendedor1_id','{vendedor1->nome}');
        return implode(', ', $values);
    }

    public function set_pedido_vendedor2_to_string($pedido_vendedor2_to_string)
    {
        if(is_array($pedido_vendedor2_to_string))
        {
            $values = Vendedor::where('id', 'in', $pedido_vendedor2_to_string)->getIndexedArray('nome', 'nome');
            $this->pedido_vendedor2_to_string = implode(', ', $values);
        }
        else
        {
            $this->pedido_vendedor2_to_string = $pedido_vendedor2_to_string;
        }

        $this->vdata['pedido_vendedor2_to_string'] = $this->pedido_vendedor2_to_string;
    }

    public function get_pedido_vendedor2_to_string()
    {
        if(!empty($this->pedido_vendedor2_to_string))
        {
            return $this->pedido_vendedor2_to_string;
        }
    
        $values = Pedido::where('vendedor2_id', '=', $this->id)->getIndexedArray('vendedor2_id','{vendedor2->nome}');
        return implode(', ', $values);
    }

    public function set_pedido_transportadora_to_string($pedido_transportadora_to_string)
    {
        if(is_array($pedido_transportadora_to_string))
        {
            $values = Transportadora::where('id', 'in', $pedido_transportadora_to_string)->getIndexedArray('id', 'id');
            $this->pedido_transportadora_to_string = implode(', ', $values);
        }
        else
        {
            $this->pedido_transportadora_to_string = $pedido_transportadora_to_string;
        }

        $this->vdata['pedido_transportadora_to_string'] = $this->pedido_transportadora_to_string;
    }

    public function get_pedido_transportadora_to_string()
    {
        if(!empty($this->pedido_transportadora_to_string))
        {
            return $this->pedido_transportadora_to_string;
        }
    
        $values = Pedido::where('vendedor2_id', '=', $this->id)->getIndexedArray('transportadora_id','{transportadora->id}');
        return implode(', ', $values);
    }

    public function set_pedido_condicao_pagamento_to_string($pedido_condicao_pagamento_to_string)
    {
        if(is_array($pedido_condicao_pagamento_to_string))
        {
            $values = CondicaoPagamento::where('id', 'in', $pedido_condicao_pagamento_to_string)->getIndexedArray('descricao', 'descricao');
            $this->pedido_condicao_pagamento_to_string = implode(', ', $values);
        }
        else
        {
            $this->pedido_condicao_pagamento_to_string = $pedido_condicao_pagamento_to_string;
        }

        $this->vdata['pedido_condicao_pagamento_to_string'] = $this->pedido_condicao_pagamento_to_string;
    }

    public function get_pedido_condicao_pagamento_to_string()
    {
        if(!empty($this->pedido_condicao_pagamento_to_string))
        {
            return $this->pedido_condicao_pagamento_to_string;
        }
    
        $values = Pedido::where('vendedor2_id', '=', $this->id)->getIndexedArray('condicao_pagamento_id','{condicao_pagamento->descricao}');
        return implode(', ', $values);
    }

    public function set_pedido_orcamento_to_string($pedido_orcamento_to_string)
    {
        if(is_array($pedido_orcamento_to_string))
        {
            $values = Orcamento::where('id', 'in', $pedido_orcamento_to_string)->getIndexedArray('dt_faturamento', 'dt_faturamento');
            $this->pedido_orcamento_to_string = implode(', ', $values);
        }
        else
        {
            $this->pedido_orcamento_to_string = $pedido_orcamento_to_string;
        }

        $this->vdata['pedido_orcamento_to_string'] = $this->pedido_orcamento_to_string;
    }

    public function get_pedido_orcamento_to_string()
    {
        if(!empty($this->pedido_orcamento_to_string))
        {
            return $this->pedido_orcamento_to_string;
        }
    
        $values = Pedido::where('vendedor2_id', '=', $this->id)->getIndexedArray('orcamento_id','{orcamento->dt_faturamento}');
        return implode(', ', $values);
    }

    public function set_pedido_nota_saida_to_string($pedido_nota_saida_to_string)
    {
        if(is_array($pedido_nota_saida_to_string))
        {
            $values = NotaSaida::where('id', 'in', $pedido_nota_saida_to_string)->getIndexedArray('id', 'id');
            $this->pedido_nota_saida_to_string = implode(', ', $values);
        }
        else
        {
            $this->pedido_nota_saida_to_string = $pedido_nota_saida_to_string;
        }

        $this->vdata['pedido_nota_saida_to_string'] = $this->pedido_nota_saida_to_string;
    }

    public function get_pedido_nota_saida_to_string()
    {
        if(!empty($this->pedido_nota_saida_to_string))
        {
            return $this->pedido_nota_saida_to_string;
        }
    
        $values = Pedido::where('vendedor2_id', '=', $this->id)->getIndexedArray('nota_saida_id','{nota_saida->id}');
        return implode(', ', $values);
    }

    public function set_negociacao_cliente_to_string($negociacao_cliente_to_string)
    {
        if(is_array($negociacao_cliente_to_string))
        {
            $values = Cliente::where('id', 'in', $negociacao_cliente_to_string)->getIndexedArray('razao', 'razao');
            $this->negociacao_cliente_to_string = implode(', ', $values);
        }
        else
        {
            $this->negociacao_cliente_to_string = $negociacao_cliente_to_string;
        }

        $this->vdata['negociacao_cliente_to_string'] = $this->negociacao_cliente_to_string;
    }

    public function get_negociacao_cliente_to_string()
    {
        if(!empty($this->negociacao_cliente_to_string))
        {
            return $this->negociacao_cliente_to_string;
        }
    
        $values = Negociacao::where('vendedor_id', '=', $this->id)->getIndexedArray('cliente_id','{cliente->razao}');
        return implode(', ', $values);
    }

    public function set_negociacao_vendedor_to_string($negociacao_vendedor_to_string)
    {
        if(is_array($negociacao_vendedor_to_string))
        {
            $values = Vendedor::where('id', 'in', $negociacao_vendedor_to_string)->getIndexedArray('nome', 'nome');
            $this->negociacao_vendedor_to_string = implode(', ', $values);
        }
        else
        {
            $this->negociacao_vendedor_to_string = $negociacao_vendedor_to_string;
        }

        $this->vdata['negociacao_vendedor_to_string'] = $this->negociacao_vendedor_to_string;
    }

    public function get_negociacao_vendedor_to_string()
    {
        if(!empty($this->negociacao_vendedor_to_string))
        {
            return $this->negociacao_vendedor_to_string;
        }
    
        $values = Negociacao::where('vendedor_id', '=', $this->id)->getIndexedArray('vendedor_id','{vendedor->nome}');
        return implode(', ', $values);
    }

    public function set_negociacao_atendimento_tipo_to_string($negociacao_atendimento_tipo_to_string)
    {
        if(is_array($negociacao_atendimento_tipo_to_string))
        {
            $values = AtendimentoTipo::where('id', 'in', $negociacao_atendimento_tipo_to_string)->getIndexedArray('id', 'id');
            $this->negociacao_atendimento_tipo_to_string = implode(', ', $values);
        }
        else
        {
            $this->negociacao_atendimento_tipo_to_string = $negociacao_atendimento_tipo_to_string;
        }

        $this->vdata['negociacao_atendimento_tipo_to_string'] = $this->negociacao_atendimento_tipo_to_string;
    }

    public function get_negociacao_atendimento_tipo_to_string()
    {
        if(!empty($this->negociacao_atendimento_tipo_to_string))
        {
            return $this->negociacao_atendimento_tipo_to_string;
        }
    
        $values = Negociacao::where('vendedor_id', '=', $this->id)->getIndexedArray('atendimento_tipo_id','{atendimento_tipo->id}');
        return implode(', ', $values);
    }

    public function set_negociacao_atendimento_to_string($negociacao_atendimento_to_string)
    {
        if(is_array($negociacao_atendimento_to_string))
        {
            $values = Atendimento::where('id', 'in', $negociacao_atendimento_to_string)->getIndexedArray('id', 'id');
            $this->negociacao_atendimento_to_string = implode(', ', $values);
        }
        else
        {
            $this->negociacao_atendimento_to_string = $negociacao_atendimento_to_string;
        }

        $this->vdata['negociacao_atendimento_to_string'] = $this->negociacao_atendimento_to_string;
    }

    public function get_negociacao_atendimento_to_string()
    {
        if(!empty($this->negociacao_atendimento_to_string))
        {
            return $this->negociacao_atendimento_to_string;
        }
    
        $values = Negociacao::where('vendedor_id', '=', $this->id)->getIndexedArray('atendimento_id','{atendimento->id}');
        return implode(', ', $values);
    }

    public function set_orcamento_cliente_to_string($orcamento_cliente_to_string)
    {
        if(is_array($orcamento_cliente_to_string))
        {
            $values = Cliente::where('id', 'in', $orcamento_cliente_to_string)->getIndexedArray('razao', 'razao');
            $this->orcamento_cliente_to_string = implode(', ', $values);
        }
        else
        {
            $this->orcamento_cliente_to_string = $orcamento_cliente_to_string;
        }

        $this->vdata['orcamento_cliente_to_string'] = $this->orcamento_cliente_to_string;
    }

    public function get_orcamento_cliente_to_string()
    {
        if(!empty($this->orcamento_cliente_to_string))
        {
            return $this->orcamento_cliente_to_string;
        }
    
        $values = Orcamento::where('vendedor_id', '=', $this->id)->getIndexedArray('cliente_id','{cliente->razao}');
        return implode(', ', $values);
    }

    public function set_orcamento_tabela_preco_to_string($orcamento_tabela_preco_to_string)
    {
        if(is_array($orcamento_tabela_preco_to_string))
        {
            $values = TabelaPreco::where('id', 'in', $orcamento_tabela_preco_to_string)->getIndexedArray('descricao', 'descricao');
            $this->orcamento_tabela_preco_to_string = implode(', ', $values);
        }
        else
        {
            $this->orcamento_tabela_preco_to_string = $orcamento_tabela_preco_to_string;
        }

        $this->vdata['orcamento_tabela_preco_to_string'] = $this->orcamento_tabela_preco_to_string;
    }

    public function get_orcamento_tabela_preco_to_string()
    {
        if(!empty($this->orcamento_tabela_preco_to_string))
        {
            return $this->orcamento_tabela_preco_to_string;
        }
    
        $values = Orcamento::where('vendedor_id', '=', $this->id)->getIndexedArray('tabela_preco_id','{tabela_preco->descricao}');
        return implode(', ', $values);
    }

    public function set_orcamento_condicao_pagamento_to_string($orcamento_condicao_pagamento_to_string)
    {
        if(is_array($orcamento_condicao_pagamento_to_string))
        {
            $values = CondicaoPagamento::where('id', 'in', $orcamento_condicao_pagamento_to_string)->getIndexedArray('descricao', 'descricao');
            $this->orcamento_condicao_pagamento_to_string = implode(', ', $values);
        }
        else
        {
            $this->orcamento_condicao_pagamento_to_string = $orcamento_condicao_pagamento_to_string;
        }

        $this->vdata['orcamento_condicao_pagamento_to_string'] = $this->orcamento_condicao_pagamento_to_string;
    }

    public function get_orcamento_condicao_pagamento_to_string()
    {
        if(!empty($this->orcamento_condicao_pagamento_to_string))
        {
            return $this->orcamento_condicao_pagamento_to_string;
        }
    
        $values = Orcamento::where('vendedor_id', '=', $this->id)->getIndexedArray('condicao_pagamento_id','{condicao_pagamento->descricao}');
        return implode(', ', $values);
    }

    public function set_orcamento_pedido_to_string($orcamento_pedido_to_string)
    {
        if(is_array($orcamento_pedido_to_string))
        {
            $values = Pedido::where('id', 'in', $orcamento_pedido_to_string)->getIndexedArray('id', 'id');
            $this->orcamento_pedido_to_string = implode(', ', $values);
        }
        else
        {
            $this->orcamento_pedido_to_string = $orcamento_pedido_to_string;
        }

        $this->vdata['orcamento_pedido_to_string'] = $this->orcamento_pedido_to_string;
    }

    public function get_orcamento_pedido_to_string()
    {
        if(!empty($this->orcamento_pedido_to_string))
        {
            return $this->orcamento_pedido_to_string;
        }
    
        $values = Orcamento::where('vendedor_id', '=', $this->id)->getIndexedArray('pedido_id','{pedido->id}');
        return implode(', ', $values);
    }

    public function set_orcamento_vendedor_to_string($orcamento_vendedor_to_string)
    {
        if(is_array($orcamento_vendedor_to_string))
        {
            $values = Vendedor::where('id', 'in', $orcamento_vendedor_to_string)->getIndexedArray('nome', 'nome');
            $this->orcamento_vendedor_to_string = implode(', ', $values);
        }
        else
        {
            $this->orcamento_vendedor_to_string = $orcamento_vendedor_to_string;
        }

        $this->vdata['orcamento_vendedor_to_string'] = $this->orcamento_vendedor_to_string;
    }

    public function get_orcamento_vendedor_to_string()
    {
        if(!empty($this->orcamento_vendedor_to_string))
        {
            return $this->orcamento_vendedor_to_string;
        }
    
        $values = Orcamento::where('vendedor_id', '=', $this->id)->getIndexedArray('vendedor_id','{vendedor->nome}');
        return implode(', ', $values);
    }

    public function set_orcamento_estado_to_string($orcamento_estado_to_string)
    {
        if(is_array($orcamento_estado_to_string))
        {
            $values = Estado::where('id', 'in', $orcamento_estado_to_string)->getIndexedArray('sigla', 'sigla');
            $this->orcamento_estado_to_string = implode(', ', $values);
        }
        else
        {
            $this->orcamento_estado_to_string = $orcamento_estado_to_string;
        }

        $this->vdata['orcamento_estado_to_string'] = $this->orcamento_estado_to_string;
    }

    public function get_orcamento_estado_to_string()
    {
        if(!empty($this->orcamento_estado_to_string))
        {
            return $this->orcamento_estado_to_string;
        }
    
        $values = Orcamento::where('vendedor_id', '=', $this->id)->getIndexedArray('estado_id','{estado->sigla}');
        return implode(', ', $values);
    }

    public function set_orcamento_municipio_to_string($orcamento_municipio_to_string)
    {
        if(is_array($orcamento_municipio_to_string))
        {
            $values = Municipio::where('id', 'in', $orcamento_municipio_to_string)->getIndexedArray('cod_erp', 'cod_erp');
            $this->orcamento_municipio_to_string = implode(', ', $values);
        }
        else
        {
            $this->orcamento_municipio_to_string = $orcamento_municipio_to_string;
        }

        $this->vdata['orcamento_municipio_to_string'] = $this->orcamento_municipio_to_string;
    }

    public function get_orcamento_municipio_to_string()
    {
        if(!empty($this->orcamento_municipio_to_string))
        {
            return $this->orcamento_municipio_to_string;
        }
    
        $values = Orcamento::where('vendedor_id', '=', $this->id)->getIndexedArray('municipio_id','{municipio->cod_erp}');
        return implode(', ', $values);
    }

    public function set_orcamento_orcamento_estado_to_string($orcamento_orcamento_estado_to_string)
    {
        if(is_array($orcamento_orcamento_estado_to_string))
        {
            $values = OrcamentoEstado::where('id', 'in', $orcamento_orcamento_estado_to_string)->getIndexedArray('descricao', 'descricao');
            $this->orcamento_orcamento_estado_to_string = implode(', ', $values);
        }
        else
        {
            $this->orcamento_orcamento_estado_to_string = $orcamento_orcamento_estado_to_string;
        }

        $this->vdata['orcamento_orcamento_estado_to_string'] = $this->orcamento_orcamento_estado_to_string;
    }

    public function get_orcamento_orcamento_estado_to_string()
    {
        if(!empty($this->orcamento_orcamento_estado_to_string))
        {
            return $this->orcamento_orcamento_estado_to_string;
        }
    
        $values = Orcamento::where('vendedor_id', '=', $this->id)->getIndexedArray('orcamento_estado_id','{orcamento_estado->descricao}');
        return implode(', ', $values);
    }

    public function set_titulo_receber_filial_to_string($titulo_receber_filial_to_string)
    {
        if(is_array($titulo_receber_filial_to_string))
        {
            $values = Filial::where('id', 'in', $titulo_receber_filial_to_string)->getIndexedArray('apelido', 'apelido');
            $this->titulo_receber_filial_to_string = implode(', ', $values);
        }
        else
        {
            $this->titulo_receber_filial_to_string = $titulo_receber_filial_to_string;
        }

        $this->vdata['titulo_receber_filial_to_string'] = $this->titulo_receber_filial_to_string;
    }

    public function get_titulo_receber_filial_to_string()
    {
        if(!empty($this->titulo_receber_filial_to_string))
        {
            return $this->titulo_receber_filial_to_string;
        }
    
        $values = TituloReceber::where('vendedor_id', '=', $this->id)->getIndexedArray('filial_id','{filial->apelido}');
        return implode(', ', $values);
    }

    public function set_titulo_receber_cliente_to_string($titulo_receber_cliente_to_string)
    {
        if(is_array($titulo_receber_cliente_to_string))
        {
            $values = Cliente::where('id', 'in', $titulo_receber_cliente_to_string)->getIndexedArray('razao', 'razao');
            $this->titulo_receber_cliente_to_string = implode(', ', $values);
        }
        else
        {
            $this->titulo_receber_cliente_to_string = $titulo_receber_cliente_to_string;
        }

        $this->vdata['titulo_receber_cliente_to_string'] = $this->titulo_receber_cliente_to_string;
    }

    public function get_titulo_receber_cliente_to_string()
    {
        if(!empty($this->titulo_receber_cliente_to_string))
        {
            return $this->titulo_receber_cliente_to_string;
        }
    
        $values = TituloReceber::where('vendedor_id', '=', $this->id)->getIndexedArray('cliente_id','{cliente->razao}');
        return implode(', ', $values);
    }

    public function set_titulo_receber_vendedor_to_string($titulo_receber_vendedor_to_string)
    {
        if(is_array($titulo_receber_vendedor_to_string))
        {
            $values = Vendedor::where('id', 'in', $titulo_receber_vendedor_to_string)->getIndexedArray('nome', 'nome');
            $this->titulo_receber_vendedor_to_string = implode(', ', $values);
        }
        else
        {
            $this->titulo_receber_vendedor_to_string = $titulo_receber_vendedor_to_string;
        }

        $this->vdata['titulo_receber_vendedor_to_string'] = $this->titulo_receber_vendedor_to_string;
    }

    public function get_titulo_receber_vendedor_to_string()
    {
        if(!empty($this->titulo_receber_vendedor_to_string))
        {
            return $this->titulo_receber_vendedor_to_string;
        }
    
        $values = TituloReceber::where('vendedor_id', '=', $this->id)->getIndexedArray('vendedor_id','{vendedor->nome}');
        return implode(', ', $values);
    }

    public function set_titulo_receber_pedido_to_string($titulo_receber_pedido_to_string)
    {
        if(is_array($titulo_receber_pedido_to_string))
        {
            $values = Pedido::where('id', 'in', $titulo_receber_pedido_to_string)->getIndexedArray('id', 'id');
            $this->titulo_receber_pedido_to_string = implode(', ', $values);
        }
        else
        {
            $this->titulo_receber_pedido_to_string = $titulo_receber_pedido_to_string;
        }

        $this->vdata['titulo_receber_pedido_to_string'] = $this->titulo_receber_pedido_to_string;
    }

    public function get_titulo_receber_pedido_to_string()
    {
        if(!empty($this->titulo_receber_pedido_to_string))
        {
            return $this->titulo_receber_pedido_to_string;
        }
    
        $values = TituloReceber::where('vendedor_id', '=', $this->id)->getIndexedArray('pedido_id','{pedido->id}');
        return implode(', ', $values);
    }

    public function set_titulo_receber_nota_fiscal_to_string($titulo_receber_nota_fiscal_to_string)
    {
        if(is_array($titulo_receber_nota_fiscal_to_string))
        {
            $values = NotaSaida::where('id', 'in', $titulo_receber_nota_fiscal_to_string)->getIndexedArray('id', 'id');
            $this->titulo_receber_nota_fiscal_to_string = implode(', ', $values);
        }
        else
        {
            $this->titulo_receber_nota_fiscal_to_string = $titulo_receber_nota_fiscal_to_string;
        }

        $this->vdata['titulo_receber_nota_fiscal_to_string'] = $this->titulo_receber_nota_fiscal_to_string;
    }

    public function get_titulo_receber_nota_fiscal_to_string()
    {
        if(!empty($this->titulo_receber_nota_fiscal_to_string))
        {
            return $this->titulo_receber_nota_fiscal_to_string;
        }
    
        $values = TituloReceber::where('vendedor_id', '=', $this->id)->getIndexedArray('nota_fiscal_id','{nota_fiscal->id}');
        return implode(', ', $values);
    }

    public function set_cliente_vendedor_mes_vendedor_to_string($cliente_vendedor_mes_vendedor_to_string)
    {
        if(is_array($cliente_vendedor_mes_vendedor_to_string))
        {
            $values = Vendedor::where('id', 'in', $cliente_vendedor_mes_vendedor_to_string)->getIndexedArray('nome', 'nome');
            $this->cliente_vendedor_mes_vendedor_to_string = implode(', ', $values);
        }
        else
        {
            $this->cliente_vendedor_mes_vendedor_to_string = $cliente_vendedor_mes_vendedor_to_string;
        }

        $this->vdata['cliente_vendedor_mes_vendedor_to_string'] = $this->cliente_vendedor_mes_vendedor_to_string;
    }

    public function get_cliente_vendedor_mes_vendedor_to_string()
    {
        if(!empty($this->cliente_vendedor_mes_vendedor_to_string))
        {
            return $this->cliente_vendedor_mes_vendedor_to_string;
        }
    
        $values = ClienteVendedorMes::where('vendedor_id', '=', $this->id)->getIndexedArray('vendedor_id','{vendedor->nome}');
        return implode(', ', $values);
    }

    public function set_vendedor_cliente_vendedor_to_string($vendedor_cliente_vendedor_to_string)
    {
        if(is_array($vendedor_cliente_vendedor_to_string))
        {
            $values = Vendedor::where('id', 'in', $vendedor_cliente_vendedor_to_string)->getIndexedArray('nome', 'nome');
            $this->vendedor_cliente_vendedor_to_string = implode(', ', $values);
        }
        else
        {
            $this->vendedor_cliente_vendedor_to_string = $vendedor_cliente_vendedor_to_string;
        }

        $this->vdata['vendedor_cliente_vendedor_to_string'] = $this->vendedor_cliente_vendedor_to_string;
    }

    public function get_vendedor_cliente_vendedor_to_string()
    {
        if(!empty($this->vendedor_cliente_vendedor_to_string))
        {
            return $this->vendedor_cliente_vendedor_to_string;
        }
    
        $values = VendedorCliente::where('vendedor_id', '=', $this->id)->getIndexedArray('vendedor_id','{vendedor->nome}');
        return implode(', ', $values);
    }

    public function set_vendedor_cliente_cliente_to_string($vendedor_cliente_cliente_to_string)
    {
        if(is_array($vendedor_cliente_cliente_to_string))
        {
            $values = Cliente::where('id', 'in', $vendedor_cliente_cliente_to_string)->getIndexedArray('razao', 'razao');
            $this->vendedor_cliente_cliente_to_string = implode(', ', $values);
        }
        else
        {
            $this->vendedor_cliente_cliente_to_string = $vendedor_cliente_cliente_to_string;
        }

        $this->vdata['vendedor_cliente_cliente_to_string'] = $this->vendedor_cliente_cliente_to_string;
    }

    public function get_vendedor_cliente_cliente_to_string()
    {
        if(!empty($this->vendedor_cliente_cliente_to_string))
        {
            return $this->vendedor_cliente_cliente_to_string;
        }
    
        $values = VendedorCliente::where('vendedor_id', '=', $this->id)->getIndexedArray('cliente_id','{cliente->razao}');
        return implode(', ', $values);
    }

    public function set_nota_saida_item_nota_saida_to_string($nota_saida_item_nota_saida_to_string)
    {
        if(is_array($nota_saida_item_nota_saida_to_string))
        {
            $values = NotaSaida::where('id', 'in', $nota_saida_item_nota_saida_to_string)->getIndexedArray('id', 'id');
            $this->nota_saida_item_nota_saida_to_string = implode(', ', $values);
        }
        else
        {
            $this->nota_saida_item_nota_saida_to_string = $nota_saida_item_nota_saida_to_string;
        }

        $this->vdata['nota_saida_item_nota_saida_to_string'] = $this->nota_saida_item_nota_saida_to_string;
    }

    public function get_nota_saida_item_nota_saida_to_string()
    {
        if(!empty($this->nota_saida_item_nota_saida_to_string))
        {
            return $this->nota_saida_item_nota_saida_to_string;
        }
    
        $values = NotaSaidaItem::where('vendedor2_id', '=', $this->id)->getIndexedArray('nota_saida_id','{nota_saida->id}');
        return implode(', ', $values);
    }

    public function set_nota_saida_item_produto_to_string($nota_saida_item_produto_to_string)
    {
        if(is_array($nota_saida_item_produto_to_string))
        {
            $values = Produto::where('id', 'in', $nota_saida_item_produto_to_string)->getIndexedArray('descricao', 'descricao');
            $this->nota_saida_item_produto_to_string = implode(', ', $values);
        }
        else
        {
            $this->nota_saida_item_produto_to_string = $nota_saida_item_produto_to_string;
        }

        $this->vdata['nota_saida_item_produto_to_string'] = $this->nota_saida_item_produto_to_string;
    }

    public function get_nota_saida_item_produto_to_string()
    {
        if(!empty($this->nota_saida_item_produto_to_string))
        {
            return $this->nota_saida_item_produto_to_string;
        }
    
        $values = NotaSaidaItem::where('vendedor2_id', '=', $this->id)->getIndexedArray('produto_id','{produto->descricao}');
        return implode(', ', $values);
    }

    public function set_nota_saida_item_tes_to_string($nota_saida_item_tes_to_string)
    {
        if(is_array($nota_saida_item_tes_to_string))
        {
            $values = TipoEntradaSaida::where('id', 'in', $nota_saida_item_tes_to_string)->getIndexedArray('id', 'id');
            $this->nota_saida_item_tes_to_string = implode(', ', $values);
        }
        else
        {
            $this->nota_saida_item_tes_to_string = $nota_saida_item_tes_to_string;
        }

        $this->vdata['nota_saida_item_tes_to_string'] = $this->nota_saida_item_tes_to_string;
    }

    public function get_nota_saida_item_tes_to_string()
    {
        if(!empty($this->nota_saida_item_tes_to_string))
        {
            return $this->nota_saida_item_tes_to_string;
        }
    
        $values = NotaSaidaItem::where('vendedor2_id', '=', $this->id)->getIndexedArray('tes_id','{tes->id}');
        return implode(', ', $values);
    }

    public function set_nota_saida_item_cliente_to_string($nota_saida_item_cliente_to_string)
    {
        if(is_array($nota_saida_item_cliente_to_string))
        {
            $values = Cliente::where('id', 'in', $nota_saida_item_cliente_to_string)->getIndexedArray('razao', 'razao');
            $this->nota_saida_item_cliente_to_string = implode(', ', $values);
        }
        else
        {
            $this->nota_saida_item_cliente_to_string = $nota_saida_item_cliente_to_string;
        }

        $this->vdata['nota_saida_item_cliente_to_string'] = $this->nota_saida_item_cliente_to_string;
    }

    public function get_nota_saida_item_cliente_to_string()
    {
        if(!empty($this->nota_saida_item_cliente_to_string))
        {
            return $this->nota_saida_item_cliente_to_string;
        }
    
        $values = NotaSaidaItem::where('vendedor2_id', '=', $this->id)->getIndexedArray('cliente_id','{cliente->razao}');
        return implode(', ', $values);
    }

    public function set_nota_saida_item_vendedor1_to_string($nota_saida_item_vendedor1_to_string)
    {
        if(is_array($nota_saida_item_vendedor1_to_string))
        {
            $values = Vendedor::where('id', 'in', $nota_saida_item_vendedor1_to_string)->getIndexedArray('nome', 'nome');
            $this->nota_saida_item_vendedor1_to_string = implode(', ', $values);
        }
        else
        {
            $this->nota_saida_item_vendedor1_to_string = $nota_saida_item_vendedor1_to_string;
        }

        $this->vdata['nota_saida_item_vendedor1_to_string'] = $this->nota_saida_item_vendedor1_to_string;
    }

    public function get_nota_saida_item_vendedor1_to_string()
    {
        if(!empty($this->nota_saida_item_vendedor1_to_string))
        {
            return $this->nota_saida_item_vendedor1_to_string;
        }
    
        $values = NotaSaidaItem::where('vendedor2_id', '=', $this->id)->getIndexedArray('vendedor1_id','{vendedor1->nome}');
        return implode(', ', $values);
    }

    public function set_nota_saida_item_vendedor2_to_string($nota_saida_item_vendedor2_to_string)
    {
        if(is_array($nota_saida_item_vendedor2_to_string))
        {
            $values = Vendedor::where('id', 'in', $nota_saida_item_vendedor2_to_string)->getIndexedArray('nome', 'nome');
            $this->nota_saida_item_vendedor2_to_string = implode(', ', $values);
        }
        else
        {
            $this->nota_saida_item_vendedor2_to_string = $nota_saida_item_vendedor2_to_string;
        }

        $this->vdata['nota_saida_item_vendedor2_to_string'] = $this->nota_saida_item_vendedor2_to_string;
    }

    public function get_nota_saida_item_vendedor2_to_string()
    {
        if(!empty($this->nota_saida_item_vendedor2_to_string))
        {
            return $this->nota_saida_item_vendedor2_to_string;
        }
    
        $values = NotaSaidaItem::where('vendedor2_id', '=', $this->id)->getIndexedArray('vendedor2_id','{vendedor2->nome}');
        return implode(', ', $values);
    }

    public function set_atendimento_atendimento_tipo_to_string($atendimento_atendimento_tipo_to_string)
    {
        if(is_array($atendimento_atendimento_tipo_to_string))
        {
            $values = AtendimentoTipo::where('id', 'in', $atendimento_atendimento_tipo_to_string)->getIndexedArray('id', 'id');
            $this->atendimento_atendimento_tipo_to_string = implode(', ', $values);
        }
        else
        {
            $this->atendimento_atendimento_tipo_to_string = $atendimento_atendimento_tipo_to_string;
        }

        $this->vdata['atendimento_atendimento_tipo_to_string'] = $this->atendimento_atendimento_tipo_to_string;
    }

    public function get_atendimento_atendimento_tipo_to_string()
    {
        if(!empty($this->atendimento_atendimento_tipo_to_string))
        {
            return $this->atendimento_atendimento_tipo_to_string;
        }
    
        $values = Atendimento::where('vendedor_id', '=', $this->id)->getIndexedArray('atendimento_tipo_id','{atendimento_tipo->id}');
        return implode(', ', $values);
    }

    public function set_atendimento_vendedor_to_string($atendimento_vendedor_to_string)
    {
        if(is_array($atendimento_vendedor_to_string))
        {
            $values = Vendedor::where('id', 'in', $atendimento_vendedor_to_string)->getIndexedArray('nome', 'nome');
            $this->atendimento_vendedor_to_string = implode(', ', $values);
        }
        else
        {
            $this->atendimento_vendedor_to_string = $atendimento_vendedor_to_string;
        }

        $this->vdata['atendimento_vendedor_to_string'] = $this->atendimento_vendedor_to_string;
    }

    public function get_atendimento_vendedor_to_string()
    {
        if(!empty($this->atendimento_vendedor_to_string))
        {
            return $this->atendimento_vendedor_to_string;
        }
    
        $values = Atendimento::where('vendedor_id', '=', $this->id)->getIndexedArray('vendedor_id','{vendedor->nome}');
        return implode(', ', $values);
    }

    public function set_atendimento_cliente_to_string($atendimento_cliente_to_string)
    {
        if(is_array($atendimento_cliente_to_string))
        {
            $values = Cliente::where('id', 'in', $atendimento_cliente_to_string)->getIndexedArray('razao', 'razao');
            $this->atendimento_cliente_to_string = implode(', ', $values);
        }
        else
        {
            $this->atendimento_cliente_to_string = $atendimento_cliente_to_string;
        }

        $this->vdata['atendimento_cliente_to_string'] = $this->atendimento_cliente_to_string;
    }

    public function get_atendimento_cliente_to_string()
    {
        if(!empty($this->atendimento_cliente_to_string))
        {
            return $this->atendimento_cliente_to_string;
        }
    
        $values = Atendimento::where('vendedor_id', '=', $this->id)->getIndexedArray('cliente_id','{cliente->razao}');
        return implode(', ', $values);
    }

    public function set_nota_entrada_cliente_to_string($nota_entrada_cliente_to_string)
    {
        if(is_array($nota_entrada_cliente_to_string))
        {
            $values = Cliente::where('id', 'in', $nota_entrada_cliente_to_string)->getIndexedArray('razao', 'razao');
            $this->nota_entrada_cliente_to_string = implode(', ', $values);
        }
        else
        {
            $this->nota_entrada_cliente_to_string = $nota_entrada_cliente_to_string;
        }

        $this->vdata['nota_entrada_cliente_to_string'] = $this->nota_entrada_cliente_to_string;
    }

    public function get_nota_entrada_cliente_to_string()
    {
        if(!empty($this->nota_entrada_cliente_to_string))
        {
            return $this->nota_entrada_cliente_to_string;
        }
    
        $values = NotaEntrada::where('vendedor1_id', '=', $this->id)->getIndexedArray('cliente_id','{cliente->razao}');
        return implode(', ', $values);
    }

    public function set_nota_entrada_fornecedor_to_string($nota_entrada_fornecedor_to_string)
    {
        if(is_array($nota_entrada_fornecedor_to_string))
        {
            $values = Fornecedor::where('id', 'in', $nota_entrada_fornecedor_to_string)->getIndexedArray('id', 'id');
            $this->nota_entrada_fornecedor_to_string = implode(', ', $values);
        }
        else
        {
            $this->nota_entrada_fornecedor_to_string = $nota_entrada_fornecedor_to_string;
        }

        $this->vdata['nota_entrada_fornecedor_to_string'] = $this->nota_entrada_fornecedor_to_string;
    }

    public function get_nota_entrada_fornecedor_to_string()
    {
        if(!empty($this->nota_entrada_fornecedor_to_string))
        {
            return $this->nota_entrada_fornecedor_to_string;
        }
    
        $values = NotaEntrada::where('vendedor1_id', '=', $this->id)->getIndexedArray('fornecedor_id','{fornecedor->id}');
        return implode(', ', $values);
    }

    public function set_nota_entrada_vendedor1_to_string($nota_entrada_vendedor1_to_string)
    {
        if(is_array($nota_entrada_vendedor1_to_string))
        {
            $values = Vendedor::where('id', 'in', $nota_entrada_vendedor1_to_string)->getIndexedArray('nome', 'nome');
            $this->nota_entrada_vendedor1_to_string = implode(', ', $values);
        }
        else
        {
            $this->nota_entrada_vendedor1_to_string = $nota_entrada_vendedor1_to_string;
        }

        $this->vdata['nota_entrada_vendedor1_to_string'] = $this->nota_entrada_vendedor1_to_string;
    }

    public function get_nota_entrada_vendedor1_to_string()
    {
        if(!empty($this->nota_entrada_vendedor1_to_string))
        {
            return $this->nota_entrada_vendedor1_to_string;
        }
    
        $values = NotaEntrada::where('vendedor1_id', '=', $this->id)->getIndexedArray('vendedor1_id','{vendedor1->nome}');
        return implode(', ', $values);
    }

    public function set_vendedor_atendimento_vendedor_to_string($vendedor_atendimento_vendedor_to_string)
    {
        if(is_array($vendedor_atendimento_vendedor_to_string))
        {
            $values = Vendedor::where('id', 'in', $vendedor_atendimento_vendedor_to_string)->getIndexedArray('nome', 'nome');
            $this->vendedor_atendimento_vendedor_to_string = implode(', ', $values);
        }
        else
        {
            $this->vendedor_atendimento_vendedor_to_string = $vendedor_atendimento_vendedor_to_string;
        }

        $this->vdata['vendedor_atendimento_vendedor_to_string'] = $this->vendedor_atendimento_vendedor_to_string;
    }

    public function get_vendedor_atendimento_vendedor_to_string()
    {
        if(!empty($this->vendedor_atendimento_vendedor_to_string))
        {
            return $this->vendedor_atendimento_vendedor_to_string;
        }
    
        $values = VendedorAtendimento::where('vendedor_id', '=', $this->id)->getIndexedArray('vendedor_id','{vendedor->nome}');
        return implode(', ', $values);
    }

    public function set_supervisor_vendedor_vendedor_to_string($supervisor_vendedor_vendedor_to_string)
    {
        if(is_array($supervisor_vendedor_vendedor_to_string))
        {
            $values = Vendedor::where('id', 'in', $supervisor_vendedor_vendedor_to_string)->getIndexedArray('nome', 'nome');
            $this->supervisor_vendedor_vendedor_to_string = implode(', ', $values);
        }
        else
        {
            $this->supervisor_vendedor_vendedor_to_string = $supervisor_vendedor_vendedor_to_string;
        }

        $this->vdata['supervisor_vendedor_vendedor_to_string'] = $this->supervisor_vendedor_vendedor_to_string;
    }

    public function get_supervisor_vendedor_vendedor_to_string()
    {
        if(!empty($this->supervisor_vendedor_vendedor_to_string))
        {
            return $this->supervisor_vendedor_vendedor_to_string;
        }
    
        $values = SupervisorVendedor::where('vendedor_id', '=', $this->id)->getIndexedArray('vendedor_id','{vendedor->nome}');
        return implode(', ', $values);
    }

    public function set_supervisor_vendedor_supervisor_to_string($supervisor_vendedor_supervisor_to_string)
    {
        if(is_array($supervisor_vendedor_supervisor_to_string))
        {
            $values = Supervisor::where('id', 'in', $supervisor_vendedor_supervisor_to_string)->getIndexedArray('nome_reduzido', 'nome_reduzido');
            $this->supervisor_vendedor_supervisor_to_string = implode(', ', $values);
        }
        else
        {
            $this->supervisor_vendedor_supervisor_to_string = $supervisor_vendedor_supervisor_to_string;
        }

        $this->vdata['supervisor_vendedor_supervisor_to_string'] = $this->supervisor_vendedor_supervisor_to_string;
    }

    public function get_supervisor_vendedor_supervisor_to_string()
    {
        if(!empty($this->supervisor_vendedor_supervisor_to_string))
        {
            return $this->supervisor_vendedor_supervisor_to_string;
        }
    
        $values = SupervisorVendedor::where('vendedor_id', '=', $this->id)->getIndexedArray('supervisor_id','{supervisor->nome_reduzido}');
        return implode(', ', $values);
    }

    public function set_cliente_atendimento_cliente_to_string($cliente_atendimento_cliente_to_string)
    {
        if(is_array($cliente_atendimento_cliente_to_string))
        {
            $values = Cliente::where('id', 'in', $cliente_atendimento_cliente_to_string)->getIndexedArray('razao', 'razao');
            $this->cliente_atendimento_cliente_to_string = implode(', ', $values);
        }
        else
        {
            $this->cliente_atendimento_cliente_to_string = $cliente_atendimento_cliente_to_string;
        }

        $this->vdata['cliente_atendimento_cliente_to_string'] = $this->cliente_atendimento_cliente_to_string;
    }

    public function get_cliente_atendimento_cliente_to_string()
    {
        if(!empty($this->cliente_atendimento_cliente_to_string))
        {
            return $this->cliente_atendimento_cliente_to_string;
        }
    
        $values = ClienteAtendimento::where('vendedor_id', '=', $this->id)->getIndexedArray('cliente_id','{cliente->razao}');
        return implode(', ', $values);
    }

    public function set_cliente_atendimento_vendedor_to_string($cliente_atendimento_vendedor_to_string)
    {
        if(is_array($cliente_atendimento_vendedor_to_string))
        {
            $values = Vendedor::where('id', 'in', $cliente_atendimento_vendedor_to_string)->getIndexedArray('nome', 'nome');
            $this->cliente_atendimento_vendedor_to_string = implode(', ', $values);
        }
        else
        {
            $this->cliente_atendimento_vendedor_to_string = $cliente_atendimento_vendedor_to_string;
        }

        $this->vdata['cliente_atendimento_vendedor_to_string'] = $this->cliente_atendimento_vendedor_to_string;
    }

    public function get_cliente_atendimento_vendedor_to_string()
    {
        if(!empty($this->cliente_atendimento_vendedor_to_string))
        {
            return $this->cliente_atendimento_vendedor_to_string;
        }
    
        $values = ClienteAtendimento::where('vendedor_id', '=', $this->id)->getIndexedArray('vendedor_id','{vendedor->nome}');
        return implode(', ', $values);
    }

  
      public function get_nome_status(){
    
        $cNome = rtrim(ltrim($this->nome_reduzido));
        $cReturn = "";
        if(empty($this->nome_reduzido)){
            $cNome =  rtrim(ltrim($this->nome));
        }

        $cReturn = '<b>'.$cNome.'</b>';;
        if($this->status == 'B'){ //fas fa-lock
            $icone = new TElement('i');
            $icone->class="fas fa-lock";
            $cReturn = $icone.'<s>'.$cNome.'</s>';
        }
    
        return $cReturn;
    }
    
}

