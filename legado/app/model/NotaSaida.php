<?php

class NotaSaida extends TRecord
{
    const TABLENAME  = 'nota_saida';
    const PRIMARYKEY = 'id';
    const IDPOLICY   =  'serial'; // {max, serial}

    private Vendedor $vendedor1;
    private Vendedor $vendedor2;
    private Filial $filial;
    private Cliente $cliente;
    private Fornecedor $fornecedor;

    

    /**
     * Constructor method
     */
    public function __construct($id = NULL, $callObjectLoad = TRUE)
    {
        parent::__construct($id, $callObjectLoad);
        parent::addAttribute('filial_id');
        parent::addAttribute('cliente_id');
        parent::addAttribute('fornecedor_id');
        parent::addAttribute('nota_fiscal');
        parent::addAttribute('serie_fiscal');
        parent::addAttribute('especie_fiscal');
        parent::addAttribute('condicao_id');
        parent::addAttribute('numero_titulo');
        parent::addAttribute('prefixo_titulo');
        parent::addAttribute('dt_emissao');
        parent::addAttribute('tipo');
        parent::addAttribute('comodato');
        parent::addAttribute('vlr_bruto');
        parent::addAttribute('vlr_icms');
        parent::addAttribute('base_icms');
        parent::addAttribute('vlr_ipi');
        parent::addAttribute('base_ipi');
        parent::addAttribute('vlr_mercadoria');
        parent::addAttribute('vlr_desconto');
        parent::addAttribute('vlr_comodato');
        parent::addAttribute('vlr_itens');
        parent::addAttribute('vlr_devolucao');
        parent::addAttribute('transportadora');
        parent::addAttribute('tp_frete');
        parent::addAttribute('vlr_frete');
        parent::addAttribute('vendedor1_id');
        parent::addAttribute('vendedor2_id');
        parent::addAttribute('chave_nfe');
        parent::addAttribute('dt_nfe');
        parent::addAttribute('hr_nfe');
        parent::addAttribute('mensagem_nf');
        parent::addAttribute('numero_origem');
        parent::addAttribute('serie_origem');
        parent::addAttribute('intermediador');
        parent::addAttribute('reg_ativo');
        parent::addAttribute('mes');
        parent::addAttribute('ano');
        parent::addAttribute('system_unit_id');
        parent::addAttribute('date_danfe');
        parent::addAttribute('dt_inclusao');
        parent::addAttribute('dt_alteracao');
            
    }

    /**
     * Method set_vendedor
     * Sample of usage: $var->vendedor = $object;
     * @param $object Instance of Vendedor
     */
    public function set_vendedor1(Vendedor $object)
    {
        $this->vendedor1 = $object;
        $this->vendedor1_id = $object->id;
    }

    /**
     * Method get_vendedor1
     * Sample of usage: $var->vendedor1->attribute;
     * @returns Vendedor instance
     */
    public function get_vendedor1()
    {
    
        // loads the associated object
        if (empty($this->vendedor1))
            $this->vendedor1 = new Vendedor($this->vendedor1_id);
    
        // returns the associated object
        return $this->vendedor1;
    }
    /**
     * Method set_vendedor
     * Sample of usage: $var->vendedor = $object;
     * @param $object Instance of Vendedor
     */
    public function set_vendedor2(Vendedor $object)
    {
        $this->vendedor2 = $object;
        $this->vendedor2_id = $object->id;
    }

    /**
     * Method get_vendedor2
     * Sample of usage: $var->vendedor2->attribute;
     * @returns Vendedor instance
     */
    public function get_vendedor2()
    {
    
        // loads the associated object
        if (empty($this->vendedor2))
            $this->vendedor2 = new Vendedor($this->vendedor2_id);
    
        // returns the associated object
        return $this->vendedor2;
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
     * Method set_cliente
     * Sample of usage: $var->cliente = $object;
     * @param $object Instance of Cliente
     */
    public function set_cliente(Cliente $object)
    {
        $this->cliente = $object;
        $this->cliente_id = $object->id;
    }

    /**
     * Method get_cliente
     * Sample of usage: $var->cliente->attribute;
     * @returns Cliente instance
     */
    public function get_cliente()
    {
    
        // loads the associated object
        if (empty($this->cliente))
            $this->cliente = new Cliente($this->cliente_id);
    
        // returns the associated object
        return $this->cliente;
    }
    /**
     * Method set_fornecedor
     * Sample of usage: $var->fornecedor = $object;
     * @param $object Instance of Fornecedor
     */
    public function set_fornecedor(Fornecedor $object)
    {
        $this->fornecedor = $object;
        $this->fornecedor_id = $object->id;
    }

    /**
     * Method get_fornecedor
     * Sample of usage: $var->fornecedor->attribute;
     * @returns Fornecedor instance
     */
    public function get_fornecedor()
    {
    
        // loads the associated object
        if (empty($this->fornecedor))
            $this->fornecedor = new Fornecedor($this->fornecedor_id);
    
        // returns the associated object
        return $this->fornecedor;
    }

    /**
     * Method getNotaSaidaItems
     */
    public function getNotaSaidaItems()
    {
        $criteria = new TCriteria;
        $criteria->add(new TFilter('nota_saida_id', '=', $this->id));
        return NotaSaidaItem::getObjects( $criteria );
    }
    /**
     * Method getPedidos
     */
    public function getPedidos()
    {
        $criteria = new TCriteria;
        $criteria->add(new TFilter('nota_saida_id', '=', $this->id));
        return Pedido::getObjects( $criteria );
    }
    /**
     * Method getTituloRecebers
     */
    public function getTituloRecebers()
    {
        $criteria = new TCriteria;
        $criteria->add(new TFilter('nota_fiscal_id', '=', $this->id));
        return TituloReceber::getObjects( $criteria );
    }
    /**
     * Method getNotasaidaXmls
     */
    public function getNotasaidaXmls()
    {
        $criteria = new TCriteria;
        $criteria->add(new TFilter('nota_saida_id', '=', $this->id));
        return NotasaidaXml::getObjects( $criteria );
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
    
        $values = NotaSaidaItem::where('nota_saida_id', '=', $this->id)->getIndexedArray('nota_saida_id','{nota_saida->id}');
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
    
        $values = NotaSaidaItem::where('nota_saida_id', '=', $this->id)->getIndexedArray('produto_id','{produto->descricao}');
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
    
        $values = NotaSaidaItem::where('nota_saida_id', '=', $this->id)->getIndexedArray('tes_id','{tes->id}');
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
    
        $values = NotaSaidaItem::where('nota_saida_id', '=', $this->id)->getIndexedArray('cliente_id','{cliente->razao}');
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
    
        $values = NotaSaidaItem::where('nota_saida_id', '=', $this->id)->getIndexedArray('vendedor1_id','{vendedor1->nome}');
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
    
        $values = NotaSaidaItem::where('nota_saida_id', '=', $this->id)->getIndexedArray('vendedor2_id','{vendedor2->nome}');
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
    
        $values = Pedido::where('nota_saida_id', '=', $this->id)->getIndexedArray('pedido_estado_id','{pedido_estado->id}');
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
    
        $values = Pedido::where('nota_saida_id', '=', $this->id)->getIndexedArray('cliente_id','{cliente->razao}');
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
    
        $values = Pedido::where('nota_saida_id', '=', $this->id)->getIndexedArray('cliente_entrega_id','{cliente_entrega->razao}');
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
    
        $values = Pedido::where('nota_saida_id', '=', $this->id)->getIndexedArray('vendedor1_id','{vendedor1->nome}');
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
    
        $values = Pedido::where('nota_saida_id', '=', $this->id)->getIndexedArray('vendedor2_id','{vendedor2->nome}');
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
    
        $values = Pedido::where('nota_saida_id', '=', $this->id)->getIndexedArray('transportadora_id','{transportadora->id}');
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
    
        $values = Pedido::where('nota_saida_id', '=', $this->id)->getIndexedArray('condicao_pagamento_id','{condicao_pagamento->descricao}');
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
    
        $values = Pedido::where('nota_saida_id', '=', $this->id)->getIndexedArray('orcamento_id','{orcamento->dt_faturamento}');
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
    
        $values = Pedido::where('nota_saida_id', '=', $this->id)->getIndexedArray('nota_saida_id','{nota_saida->id}');
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
    
        $values = TituloReceber::where('nota_fiscal_id', '=', $this->id)->getIndexedArray('filial_id','{filial->apelido}');
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
    
        $values = TituloReceber::where('nota_fiscal_id', '=', $this->id)->getIndexedArray('cliente_id','{cliente->razao}');
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
    
        $values = TituloReceber::where('nota_fiscal_id', '=', $this->id)->getIndexedArray('vendedor_id','{vendedor->nome}');
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
    
        $values = TituloReceber::where('nota_fiscal_id', '=', $this->id)->getIndexedArray('pedido_id','{pedido->id}');
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
    
        $values = TituloReceber::where('nota_fiscal_id', '=', $this->id)->getIndexedArray('nota_fiscal_id','{nota_fiscal->id}');
        return implode(', ', $values);
    }

    public function set_notasaida_xml_nota_saida_to_string($notasaida_xml_nota_saida_to_string)
    {
        if(is_array($notasaida_xml_nota_saida_to_string))
        {
            $values = NotaSaida::where('id', 'in', $notasaida_xml_nota_saida_to_string)->getIndexedArray('id', 'id');
            $this->notasaida_xml_nota_saida_to_string = implode(', ', $values);
        }
        else
        {
            $this->notasaida_xml_nota_saida_to_string = $notasaida_xml_nota_saida_to_string;
        }

        $this->vdata['notasaida_xml_nota_saida_to_string'] = $this->notasaida_xml_nota_saida_to_string;
    }

    public function get_notasaida_xml_nota_saida_to_string()
    {
        if(!empty($this->notasaida_xml_nota_saida_to_string))
        {
            return $this->notasaida_xml_nota_saida_to_string;
        }
    
        $values = NotasaidaXml::where('nota_saida_id', '=', $this->id)->getIndexedArray('nota_saida_id','{nota_saida->id}');
        return implode(', ', $values);
    }

    
}

