<?php

class Pedido extends TRecord
{
    const TABLENAME  = 'pedido';
    const PRIMARYKEY = 'id';
    const IDPOLICY   =  'serial'; // {max, serial}

    private Cliente $cliente;
    private PedidoEstado $pedido_estado;
    private CondicaoPagamento $condicao_pagamento;
    private Transportadora $transportadora;
    private Cliente $cliente_entrega;
    private Vendedor $vendedor1;
    private Vendedor $vendedor2;
    private Orcamento $orcamento;
    private NotaSaida $nota_saida;

    

    /**
     * Constructor method
     */
    public function __construct($id = NULL, $callObjectLoad = TRUE)
    {
        parent::__construct($id, $callObjectLoad);
        parent::addAttribute('filial_id');
        parent::addAttribute('pedido_estado_id');
        parent::addAttribute('cliente_id');
        parent::addAttribute('cliente_entrega_id');
        parent::addAttribute('vendedor1_id');
        parent::addAttribute('vendedor2_id');
        parent::addAttribute('cod_erp');
        parent::addAttribute('dt_emissao');
        parent::addAttribute('transportadora_id');
        parent::addAttribute('tabela_id');
        parent::addAttribute('condicao_pagamento_id');
        parent::addAttribute('sinc');
        parent::addAttribute('mes');
        parent::addAttribute('ano');
        parent::addAttribute('tipo');
        parent::addAttribute('nota_fiscal');
        parent::addAttribute('serie');
        parent::addAttribute('mensagem_nf');
        parent::addAttribute('tp_frete');
        parent::addAttribute('vlr_frete');
        parent::addAttribute('vlr_total');
        parent::addAttribute('vlr_comodato');
        parent::addAttribute('presencial');
        parent::addAttribute('pedido_origem');
        parent::addAttribute('log_int');
        parent::addAttribute('user_id');
        parent::addAttribute('intermediador_id');
        parent::addAttribute('dt_inclusao');
        parent::addAttribute('dt_alteracao');
        parent::addAttribute('orcamento_id');
        parent::addAttribute('nota_saida_id');
        parent::addAttribute('system_unit_id');
            
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
     * Method set_pedido_estado
     * Sample of usage: $var->pedido_estado = $object;
     * @param $object Instance of PedidoEstado
     */
    public function set_pedido_estado(PedidoEstado $object)
    {
        $this->pedido_estado = $object;
        $this->pedido_estado_id = $object->id;
    }

    /**
     * Method get_pedido_estado
     * Sample of usage: $var->pedido_estado->attribute;
     * @returns PedidoEstado instance
     */
    public function get_pedido_estado()
    {
    
        // loads the associated object
        if (empty($this->pedido_estado))
            $this->pedido_estado = new PedidoEstado($this->pedido_estado_id);
    
        // returns the associated object
        return $this->pedido_estado;
    }
    /**
     * Method set_condicao_pagamento
     * Sample of usage: $var->condicao_pagamento = $object;
     * @param $object Instance of CondicaoPagamento
     */
    public function set_condicao_pagamento(CondicaoPagamento $object)
    {
        $this->condicao_pagamento = $object;
        $this->condicao_pagamento_id = $object->id;
    }

    /**
     * Method get_condicao_pagamento
     * Sample of usage: $var->condicao_pagamento->attribute;
     * @returns CondicaoPagamento instance
     */
    public function get_condicao_pagamento()
    {
    
        // loads the associated object
        if (empty($this->condicao_pagamento))
            $this->condicao_pagamento = new CondicaoPagamento($this->condicao_pagamento_id);
    
        // returns the associated object
        return $this->condicao_pagamento;
    }
    /**
     * Method set_transportadora
     * Sample of usage: $var->transportadora = $object;
     * @param $object Instance of Transportadora
     */
    public function set_transportadora(Transportadora $object)
    {
        $this->transportadora = $object;
        $this->transportadora_id = $object->id;
    }

    /**
     * Method get_transportadora
     * Sample of usage: $var->transportadora->attribute;
     * @returns Transportadora instance
     */
    public function get_transportadora()
    {
    
        // loads the associated object
        if (empty($this->transportadora))
            $this->transportadora = new Transportadora($this->transportadora_id);
    
        // returns the associated object
        return $this->transportadora;
    }
    /**
     * Method set_cliente
     * Sample of usage: $var->cliente = $object;
     * @param $object Instance of Cliente
     */
    public function set_cliente_entrega(Cliente $object)
    {
        $this->cliente_entrega = $object;
        $this->cliente_entrega_id = $object->id;
    }

    /**
     * Method get_cliente_entrega
     * Sample of usage: $var->cliente_entrega->attribute;
     * @returns Cliente instance
     */
    public function get_cliente_entrega()
    {
    
        // loads the associated object
        if (empty($this->cliente_entrega))
            $this->cliente_entrega = new Cliente($this->cliente_entrega_id);
    
        // returns the associated object
        return $this->cliente_entrega;
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
     * Method set_orcamento
     * Sample of usage: $var->orcamento = $object;
     * @param $object Instance of Orcamento
     */
    public function set_orcamento(Orcamento $object)
    {
        $this->orcamento = $object;
        $this->orcamento_id = $object->id;
    }

    /**
     * Method get_orcamento
     * Sample of usage: $var->orcamento->attribute;
     * @returns Orcamento instance
     */
    public function get_orcamento()
    {
    
        // loads the associated object
        if (empty($this->orcamento))
            $this->orcamento = new Orcamento($this->orcamento_id);
    
        // returns the associated object
        return $this->orcamento;
    }
    /**
     * Method set_nota_saida
     * Sample of usage: $var->nota_saida = $object;
     * @param $object Instance of NotaSaida
     */
    public function set_nota_saida(NotaSaida $object)
    {
        $this->nota_saida = $object;
        $this->nota_saida_id = $object->id;
    }

    /**
     * Method get_nota_saida
     * Sample of usage: $var->nota_saida->attribute;
     * @returns NotaSaida instance
     */
    public function get_nota_saida()
    {
    
        // loads the associated object
        if (empty($this->nota_saida))
            $this->nota_saida = new NotaSaida($this->nota_saida_id);
    
        // returns the associated object
        return $this->nota_saida;
    }

    /**
     * Method getPedidoItems
     */
    public function getPedidoItems()
    {
        $criteria = new TCriteria;
        $criteria->add(new TFilter('pedido_id', '=', $this->id));
        return PedidoItem::getObjects( $criteria );
    }
    /**
     * Method getOrcamentos
     */
    public function getOrcamentos()
    {
        $criteria = new TCriteria;
        $criteria->add(new TFilter('pedido_id', '=', $this->id));
        return Orcamento::getObjects( $criteria );
    }
    /**
     * Method getTituloRecebers
     */
    public function getTituloRecebers()
    {
        $criteria = new TCriteria;
        $criteria->add(new TFilter('pedido_id', '=', $this->id));
        return TituloReceber::getObjects( $criteria );
    }

    public function set_pedido_item_pedido_to_string($pedido_item_pedido_to_string)
    {
        if(is_array($pedido_item_pedido_to_string))
        {
            $values = Pedido::where('id', 'in', $pedido_item_pedido_to_string)->getIndexedArray('id', 'id');
            $this->pedido_item_pedido_to_string = implode(', ', $values);
        }
        else
        {
            $this->pedido_item_pedido_to_string = $pedido_item_pedido_to_string;
        }

        $this->vdata['pedido_item_pedido_to_string'] = $this->pedido_item_pedido_to_string;
    }

    public function get_pedido_item_pedido_to_string()
    {
        if(!empty($this->pedido_item_pedido_to_string))
        {
            return $this->pedido_item_pedido_to_string;
        }
    
        $values = PedidoItem::where('pedido_id', '=', $this->id)->getIndexedArray('pedido_id','{pedido->id}');
        return implode(', ', $values);
    }

    public function set_pedido_item_produto_to_string($pedido_item_produto_to_string)
    {
        if(is_array($pedido_item_produto_to_string))
        {
            $values = Produto::where('id', 'in', $pedido_item_produto_to_string)->getIndexedArray('descricao', 'descricao');
            $this->pedido_item_produto_to_string = implode(', ', $values);
        }
        else
        {
            $this->pedido_item_produto_to_string = $pedido_item_produto_to_string;
        }

        $this->vdata['pedido_item_produto_to_string'] = $this->pedido_item_produto_to_string;
    }

    public function get_pedido_item_produto_to_string()
    {
        if(!empty($this->pedido_item_produto_to_string))
        {
            return $this->pedido_item_produto_to_string;
        }
    
        $values = PedidoItem::where('pedido_id', '=', $this->id)->getIndexedArray('produto_id','{produto->descricao}');
        return implode(', ', $values);
    }

    public function set_pedido_item_armazem_to_string($pedido_item_armazem_to_string)
    {
        if(is_array($pedido_item_armazem_to_string))
        {
            $values = Armazem::where('id', 'in', $pedido_item_armazem_to_string)->getIndexedArray('descricao', 'descricao');
            $this->pedido_item_armazem_to_string = implode(', ', $values);
        }
        else
        {
            $this->pedido_item_armazem_to_string = $pedido_item_armazem_to_string;
        }

        $this->vdata['pedido_item_armazem_to_string'] = $this->pedido_item_armazem_to_string;
    }

    public function get_pedido_item_armazem_to_string()
    {
        if(!empty($this->pedido_item_armazem_to_string))
        {
            return $this->pedido_item_armazem_to_string;
        }
    
        $values = PedidoItem::where('pedido_id', '=', $this->id)->getIndexedArray('armazem_id','{armazem->descricao}');
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
    
        $values = Orcamento::where('pedido_id', '=', $this->id)->getIndexedArray('cliente_id','{cliente->razao}');
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
    
        $values = Orcamento::where('pedido_id', '=', $this->id)->getIndexedArray('tabela_preco_id','{tabela_preco->descricao}');
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
    
        $values = Orcamento::where('pedido_id', '=', $this->id)->getIndexedArray('condicao_pagamento_id','{condicao_pagamento->descricao}');
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
    
        $values = Orcamento::where('pedido_id', '=', $this->id)->getIndexedArray('pedido_id','{pedido->id}');
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
    
        $values = Orcamento::where('pedido_id', '=', $this->id)->getIndexedArray('vendedor_id','{vendedor->nome}');
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
    
        $values = Orcamento::where('pedido_id', '=', $this->id)->getIndexedArray('estado_id','{estado->sigla}');
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
    
        $values = Orcamento::where('pedido_id', '=', $this->id)->getIndexedArray('municipio_id','{municipio->cod_erp}');
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
    
        $values = Orcamento::where('pedido_id', '=', $this->id)->getIndexedArray('orcamento_estado_id','{orcamento_estado->descricao}');
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
    
        $values = TituloReceber::where('pedido_id', '=', $this->id)->getIndexedArray('filial_id','{filial->apelido}');
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
    
        $values = TituloReceber::where('pedido_id', '=', $this->id)->getIndexedArray('cliente_id','{cliente->razao}');
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
    
        $values = TituloReceber::where('pedido_id', '=', $this->id)->getIndexedArray('vendedor_id','{vendedor->nome}');
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
    
        $values = TituloReceber::where('pedido_id', '=', $this->id)->getIndexedArray('pedido_id','{pedido->id}');
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
    
        $values = TituloReceber::where('pedido_id', '=', $this->id)->getIndexedArray('nota_fiscal_id','{nota_fiscal->id}');
        return implode(', ', $values);
    }

    
}

