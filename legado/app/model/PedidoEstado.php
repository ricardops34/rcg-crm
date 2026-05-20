<?php

class PedidoEstado extends TRecord
{
    const TABLENAME  = 'pedido_estado';
    const PRIMARYKEY = 'id';
    const IDPOLICY   =  'serial'; // {max, serial}

    const Aberto = '1';
    const Pedido = '2';
    const Credito = '3';
    const Estoque = '4';
    const Regra = '5';
    const Verba = '6';
    const Liberado = '7';
    const Faturado = '8';
    const Cancelado = '9';
    const Error = '10';

    /**
     * Constructor method
     */
    public function __construct($id = NULL, $callObjectLoad = TRUE)
    {
        parent::__construct($id, $callObjectLoad);
        parent::addAttribute('cod_erp');
        parent::addAttribute('descricao');
        parent::addAttribute('cor');
        parent::addAttribute('cor_texto');
    
    }

    /**
     * Method getPedidos
     */
    public function getPedidos()
    {
        $criteria = new TCriteria;
        $criteria->add(new TFilter('pedido_estado_id', '=', $this->id));
        return Pedido::getObjects( $criteria );
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
    
        $values = Pedido::where('pedido_estado_id', '=', $this->id)->getIndexedArray('pedido_estado_id','{pedido_estado->id}');
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
    
        $values = Pedido::where('pedido_estado_id', '=', $this->id)->getIndexedArray('cliente_id','{cliente->razao}');
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
    
        $values = Pedido::where('pedido_estado_id', '=', $this->id)->getIndexedArray('cliente_entrega_id','{cliente_entrega->razao}');
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
    
        $values = Pedido::where('pedido_estado_id', '=', $this->id)->getIndexedArray('vendedor1_id','{vendedor1->nome}');
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
    
        $values = Pedido::where('pedido_estado_id', '=', $this->id)->getIndexedArray('vendedor2_id','{vendedor2->nome}');
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
    
        $values = Pedido::where('pedido_estado_id', '=', $this->id)->getIndexedArray('transportadora_id','{transportadora->id}');
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
    
        $values = Pedido::where('pedido_estado_id', '=', $this->id)->getIndexedArray('condicao_pagamento_id','{condicao_pagamento->descricao}');
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
    
        $values = Pedido::where('pedido_estado_id', '=', $this->id)->getIndexedArray('orcamento_id','{orcamento->dt_faturamento}');
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
    
        $values = Pedido::where('pedido_estado_id', '=', $this->id)->getIndexedArray('nota_saida_id','{nota_saida->id}');
        return implode(', ', $values);
    }

}

