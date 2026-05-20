<?php

class CondicaoPagamento extends TRecord
{
    const TABLENAME  = 'condicao_pagamento';
    const PRIMARYKEY = 'id';
    const IDPOLICY   =  'serial'; // {max, serial}

    const CREATEDAT  = 'dt_inclusao';
    const UPDATEDAT  = 'dt_alteracao';

    /**
     * Constructor method
     */
    public function __construct($id = NULL, $callObjectLoad = TRUE)
    {
        parent::__construct($id, $callObjectLoad);
        parent::addAttribute('filial_id');
        parent::addAttribute('cod_erp');
        parent::addAttribute('descricao');
        parent::addAttribute('forma');
        parent::addAttribute('status');
        parent::addAttribute('dt_inclusao');
        parent::addAttribute('dt_alteracao');
        parent::addAttribute('system_unit_id');
    
    }

    /**
     * Method getPedidos
     */
    public function getPedidos()
    {
        $criteria = new TCriteria;
        $criteria->add(new TFilter('condicao_pagamento_id', '=', $this->id));
        return Pedido::getObjects( $criteria );
    }
    /**
     * Method getClienteCondicaos
     */
    public function getClienteCondicaos()
    {
        $criteria = new TCriteria;
        $criteria->add(new TFilter('condicao_pagamento_id', '=', $this->id));
        return ClienteCondicao::getObjects( $criteria );
    }
    /**
     * Method getClientes
     */
    public function getClientes()
    {
        $criteria = new TCriteria;
        $criteria->add(new TFilter('condicao_pagamento_id', '=', $this->id));
        return Cliente::getObjects( $criteria );
    }
    /**
     * Method getOrcamentos
     */
    public function getOrcamentos()
    {
        $criteria = new TCriteria;
        $criteria->add(new TFilter('condicao_pagamento_id', '=', $this->id));
        return Orcamento::getObjects( $criteria );
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
    
        $values = Pedido::where('condicao_pagamento_id', '=', $this->id)->getIndexedArray('pedido_estado_id','{pedido_estado->id}');
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
    
        $values = Pedido::where('condicao_pagamento_id', '=', $this->id)->getIndexedArray('cliente_id','{cliente->razao}');
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
    
        $values = Pedido::where('condicao_pagamento_id', '=', $this->id)->getIndexedArray('cliente_entrega_id','{cliente_entrega->razao}');
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
    
        $values = Pedido::where('condicao_pagamento_id', '=', $this->id)->getIndexedArray('vendedor1_id','{vendedor1->nome}');
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
    
        $values = Pedido::where('condicao_pagamento_id', '=', $this->id)->getIndexedArray('vendedor2_id','{vendedor2->nome}');
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
    
        $values = Pedido::where('condicao_pagamento_id', '=', $this->id)->getIndexedArray('transportadora_id','{transportadora->id}');
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
    
        $values = Pedido::where('condicao_pagamento_id', '=', $this->id)->getIndexedArray('condicao_pagamento_id','{condicao_pagamento->descricao}');
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
    
        $values = Pedido::where('condicao_pagamento_id', '=', $this->id)->getIndexedArray('orcamento_id','{orcamento->dt_faturamento}');
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
    
        $values = Pedido::where('condicao_pagamento_id', '=', $this->id)->getIndexedArray('nota_saida_id','{nota_saida->id}');
        return implode(', ', $values);
    }

    public function set_cliente_condicao_condicao_pagamento_to_string($cliente_condicao_condicao_pagamento_to_string)
    {
        if(is_array($cliente_condicao_condicao_pagamento_to_string))
        {
            $values = CondicaoPagamento::where('id', 'in', $cliente_condicao_condicao_pagamento_to_string)->getIndexedArray('descricao', 'descricao');
            $this->cliente_condicao_condicao_pagamento_to_string = implode(', ', $values);
        }
        else
        {
            $this->cliente_condicao_condicao_pagamento_to_string = $cliente_condicao_condicao_pagamento_to_string;
        }

        $this->vdata['cliente_condicao_condicao_pagamento_to_string'] = $this->cliente_condicao_condicao_pagamento_to_string;
    }

    public function get_cliente_condicao_condicao_pagamento_to_string()
    {
        if(!empty($this->cliente_condicao_condicao_pagamento_to_string))
        {
            return $this->cliente_condicao_condicao_pagamento_to_string;
        }
    
        $values = ClienteCondicao::where('condicao_pagamento_id', '=', $this->id)->getIndexedArray('condicao_pagamento_id','{condicao_pagamento->descricao}');
        return implode(', ', $values);
    }

    public function set_cliente_condicao_cliente_to_string($cliente_condicao_cliente_to_string)
    {
        if(is_array($cliente_condicao_cliente_to_string))
        {
            $values = Cliente::where('id', 'in', $cliente_condicao_cliente_to_string)->getIndexedArray('razao', 'razao');
            $this->cliente_condicao_cliente_to_string = implode(', ', $values);
        }
        else
        {
            $this->cliente_condicao_cliente_to_string = $cliente_condicao_cliente_to_string;
        }

        $this->vdata['cliente_condicao_cliente_to_string'] = $this->cliente_condicao_cliente_to_string;
    }

    public function get_cliente_condicao_cliente_to_string()
    {
        if(!empty($this->cliente_condicao_cliente_to_string))
        {
            return $this->cliente_condicao_cliente_to_string;
        }
    
        $values = ClienteCondicao::where('condicao_pagamento_id', '=', $this->id)->getIndexedArray('cliente_id','{cliente->razao}');
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
    
        $values = Cliente::where('condicao_pagamento_id', '=', $this->id)->getIndexedArray('filial_id','{filial->apelido}');
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
    
        $values = Cliente::where('condicao_pagamento_id', '=', $this->id)->getIndexedArray('tipo_cliente_id','{tipo_cliente->descricao}');
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
    
        $values = Cliente::where('condicao_pagamento_id', '=', $this->id)->getIndexedArray('municipio_id','{municipio->cod_erp}');
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
    
        $values = Cliente::where('condicao_pagamento_id', '=', $this->id)->getIndexedArray('vendedor_id','{vendedor->nome}');
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
    
        $values = Cliente::where('condicao_pagamento_id', '=', $this->id)->getIndexedArray('condicao_pagamento_id','{condicao_pagamento->descricao}');
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
    
        $values = Cliente::where('condicao_pagamento_id', '=', $this->id)->getIndexedArray('tabela_preco_id','{tabela_preco->descricao}');
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
    
        $values = Cliente::where('condicao_pagamento_id', '=', $this->id)->getIndexedArray('seguimento_id','{seguimento->descricao}');
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
    
        $values = Cliente::where('condicao_pagamento_id', '=', $this->id)->getIndexedArray('motivo_bloqueio_id','{motivo_bloqueio->id}');
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
    
        $values = Cliente::where('condicao_pagamento_id', '=', $this->id)->getIndexedArray('regiao_cliente_id','{regiao_cliente->id}');
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
    
        $values = Cliente::where('condicao_pagamento_id', '=', $this->id)->getIndexedArray('situacao_cadastral_id','{situacao_cadastral->descricao}');
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
    
        $values = Orcamento::where('condicao_pagamento_id', '=', $this->id)->getIndexedArray('cliente_id','{cliente->razao}');
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
    
        $values = Orcamento::where('condicao_pagamento_id', '=', $this->id)->getIndexedArray('tabela_preco_id','{tabela_preco->descricao}');
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
    
        $values = Orcamento::where('condicao_pagamento_id', '=', $this->id)->getIndexedArray('condicao_pagamento_id','{condicao_pagamento->descricao}');
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
    
        $values = Orcamento::where('condicao_pagamento_id', '=', $this->id)->getIndexedArray('pedido_id','{pedido->id}');
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
    
        $values = Orcamento::where('condicao_pagamento_id', '=', $this->id)->getIndexedArray('vendedor_id','{vendedor->nome}');
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
    
        $values = Orcamento::where('condicao_pagamento_id', '=', $this->id)->getIndexedArray('estado_id','{estado->sigla}');
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
    
        $values = Orcamento::where('condicao_pagamento_id', '=', $this->id)->getIndexedArray('municipio_id','{municipio->cod_erp}');
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
    
        $values = Orcamento::where('condicao_pagamento_id', '=', $this->id)->getIndexedArray('orcamento_estado_id','{orcamento_estado->descricao}');
        return implode(', ', $values);
    }

}

