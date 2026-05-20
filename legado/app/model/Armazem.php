<?php

class Armazem extends TRecord
{
    const TABLENAME  = 'armazem';
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
        parent::addAttribute('cod_erp');
        parent::addAttribute('descricao');
        parent::addAttribute('status');
        parent::addAttribute('dt_inclusao');
        parent::addAttribute('dt_alteracao');
        parent::addAttribute('system_unit_id');
            
    }

    /**
     * Method getProdutos
     */
    public function getProdutos()
    {
        $criteria = new TCriteria;
        $criteria->add(new TFilter('armazem_id', '=', $this->id));
        return Produto::getObjects( $criteria );
    }
    /**
     * Method getPedidoItems
     */
    public function getPedidoItems()
    {
        $criteria = new TCriteria;
        $criteria->add(new TFilter('armazem_id', '=', $this->id));
        return PedidoItem::getObjects( $criteria );
    }
    /**
     * Method getEstoques
     */
    public function getEstoques()
    {
        $criteria = new TCriteria;
        $criteria->add(new TFilter('armazem_id', '=', $this->id));
        return Estoque::getObjects( $criteria );
    }

    public function set_produto_filial_to_string($produto_filial_to_string)
    {
        if(is_array($produto_filial_to_string))
        {
            $values = Filial::where('id', 'in', $produto_filial_to_string)->getIndexedArray('apelido', 'apelido');
            $this->produto_filial_to_string = implode(', ', $values);
        }
        else
        {
            $this->produto_filial_to_string = $produto_filial_to_string;
        }

        $this->vdata['produto_filial_to_string'] = $this->produto_filial_to_string;
    }

    public function get_produto_filial_to_string()
    {
        if(!empty($this->produto_filial_to_string))
        {
            return $this->produto_filial_to_string;
        }
    
        $values = Produto::where('armazem_id', '=', $this->id)->getIndexedArray('filial_id','{filial->apelido}');
        return implode(', ', $values);
    }

    public function set_produto_categoria_to_string($produto_categoria_to_string)
    {
        if(is_array($produto_categoria_to_string))
        {
            $values = Categoria::where('id', 'in', $produto_categoria_to_string)->getIndexedArray('descricao', 'descricao');
            $this->produto_categoria_to_string = implode(', ', $values);
        }
        else
        {
            $this->produto_categoria_to_string = $produto_categoria_to_string;
        }

        $this->vdata['produto_categoria_to_string'] = $this->produto_categoria_to_string;
    }

    public function get_produto_categoria_to_string()
    {
        if(!empty($this->produto_categoria_to_string))
        {
            return $this->produto_categoria_to_string;
        }
    
        $values = Produto::where('armazem_id', '=', $this->id)->getIndexedArray('categoria_id','{categoria->descricao}');
        return implode(', ', $values);
    }

    public function set_produto_sub_categoria_to_string($produto_sub_categoria_to_string)
    {
        if(is_array($produto_sub_categoria_to_string))
        {
            $values = SubCategoria::where('id', 'in', $produto_sub_categoria_to_string)->getIndexedArray('descricao', 'descricao');
            $this->produto_sub_categoria_to_string = implode(', ', $values);
        }
        else
        {
            $this->produto_sub_categoria_to_string = $produto_sub_categoria_to_string;
        }

        $this->vdata['produto_sub_categoria_to_string'] = $this->produto_sub_categoria_to_string;
    }

    public function get_produto_sub_categoria_to_string()
    {
        if(!empty($this->produto_sub_categoria_to_string))
        {
            return $this->produto_sub_categoria_to_string;
        }
    
        $values = Produto::where('armazem_id', '=', $this->id)->getIndexedArray('sub_categoria_id','{sub_categoria->descricao}');
        return implode(', ', $values);
    }

    public function set_produto_fabricante_to_string($produto_fabricante_to_string)
    {
        if(is_array($produto_fabricante_to_string))
        {
            $values = Fabricante::where('id', 'in', $produto_fabricante_to_string)->getIndexedArray('descricao', 'descricao');
            $this->produto_fabricante_to_string = implode(', ', $values);
        }
        else
        {
            $this->produto_fabricante_to_string = $produto_fabricante_to_string;
        }

        $this->vdata['produto_fabricante_to_string'] = $this->produto_fabricante_to_string;
    }

    public function get_produto_fabricante_to_string()
    {
        if(!empty($this->produto_fabricante_to_string))
        {
            return $this->produto_fabricante_to_string;
        }
    
        $values = Produto::where('armazem_id', '=', $this->id)->getIndexedArray('fabricante_id','{fabricante->descricao}');
        return implode(', ', $values);
    }

    public function set_produto_armazem_to_string($produto_armazem_to_string)
    {
        if(is_array($produto_armazem_to_string))
        {
            $values = Armazem::where('id', 'in', $produto_armazem_to_string)->getIndexedArray('descricao', 'descricao');
            $this->produto_armazem_to_string = implode(', ', $values);
        }
        else
        {
            $this->produto_armazem_to_string = $produto_armazem_to_string;
        }

        $this->vdata['produto_armazem_to_string'] = $this->produto_armazem_to_string;
    }

    public function get_produto_armazem_to_string()
    {
        if(!empty($this->produto_armazem_to_string))
        {
            return $this->produto_armazem_to_string;
        }
    
        $values = Produto::where('armazem_id', '=', $this->id)->getIndexedArray('armazem_id','{armazem->descricao}');
        return implode(', ', $values);
    }

    public function set_produto_te_to_string($produto_te_to_string)
    {
        if(is_array($produto_te_to_string))
        {
            $values = TipoEntradaSaida::where('id', 'in', $produto_te_to_string)->getIndexedArray('id', 'id');
            $this->produto_te_to_string = implode(', ', $values);
        }
        else
        {
            $this->produto_te_to_string = $produto_te_to_string;
        }

        $this->vdata['produto_te_to_string'] = $this->produto_te_to_string;
    }

    public function get_produto_te_to_string()
    {
        if(!empty($this->produto_te_to_string))
        {
            return $this->produto_te_to_string;
        }
    
        $values = Produto::where('armazem_id', '=', $this->id)->getIndexedArray('te_id','{te->id}');
        return implode(', ', $values);
    }

    public function set_produto_ts_to_string($produto_ts_to_string)
    {
        if(is_array($produto_ts_to_string))
        {
            $values = TipoEntradaSaida::where('id', 'in', $produto_ts_to_string)->getIndexedArray('id', 'id');
            $this->produto_ts_to_string = implode(', ', $values);
        }
        else
        {
            $this->produto_ts_to_string = $produto_ts_to_string;
        }

        $this->vdata['produto_ts_to_string'] = $this->produto_ts_to_string;
    }

    public function get_produto_ts_to_string()
    {
        if(!empty($this->produto_ts_to_string))
        {
            return $this->produto_ts_to_string;
        }
    
        $values = Produto::where('armazem_id', '=', $this->id)->getIndexedArray('ts_id','{ts->id}');
        return implode(', ', $values);
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
    
        $values = PedidoItem::where('armazem_id', '=', $this->id)->getIndexedArray('pedido_id','{pedido->id}');
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
    
        $values = PedidoItem::where('armazem_id', '=', $this->id)->getIndexedArray('produto_id','{produto->descricao}');
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
    
        $values = PedidoItem::where('armazem_id', '=', $this->id)->getIndexedArray('armazem_id','{armazem->descricao}');
        return implode(', ', $values);
    }

    public function set_estoque_filial_to_string($estoque_filial_to_string)
    {
        if(is_array($estoque_filial_to_string))
        {
            $values = Filial::where('id', 'in', $estoque_filial_to_string)->getIndexedArray('apelido', 'apelido');
            $this->estoque_filial_to_string = implode(', ', $values);
        }
        else
        {
            $this->estoque_filial_to_string = $estoque_filial_to_string;
        }

        $this->vdata['estoque_filial_to_string'] = $this->estoque_filial_to_string;
    }

    public function get_estoque_filial_to_string()
    {
        if(!empty($this->estoque_filial_to_string))
        {
            return $this->estoque_filial_to_string;
        }
    
        $values = Estoque::where('armazem_id', '=', $this->id)->getIndexedArray('filial_id','{filial->apelido}');
        return implode(', ', $values);
    }

    public function set_estoque_produto_to_string($estoque_produto_to_string)
    {
        if(is_array($estoque_produto_to_string))
        {
            $values = Produto::where('id', 'in', $estoque_produto_to_string)->getIndexedArray('descricao', 'descricao');
            $this->estoque_produto_to_string = implode(', ', $values);
        }
        else
        {
            $this->estoque_produto_to_string = $estoque_produto_to_string;
        }

        $this->vdata['estoque_produto_to_string'] = $this->estoque_produto_to_string;
    }

    public function get_estoque_produto_to_string()
    {
        if(!empty($this->estoque_produto_to_string))
        {
            return $this->estoque_produto_to_string;
        }
    
        $values = Estoque::where('armazem_id', '=', $this->id)->getIndexedArray('produto_id','{produto->descricao}');
        return implode(', ', $values);
    }

    public function set_estoque_armazem_to_string($estoque_armazem_to_string)
    {
        if(is_array($estoque_armazem_to_string))
        {
            $values = Armazem::where('id', 'in', $estoque_armazem_to_string)->getIndexedArray('descricao', 'descricao');
            $this->estoque_armazem_to_string = implode(', ', $values);
        }
        else
        {
            $this->estoque_armazem_to_string = $estoque_armazem_to_string;
        }

        $this->vdata['estoque_armazem_to_string'] = $this->estoque_armazem_to_string;
    }

    public function get_estoque_armazem_to_string()
    {
        if(!empty($this->estoque_armazem_to_string))
        {
            return $this->estoque_armazem_to_string;
        }
    
        $values = Estoque::where('armazem_id', '=', $this->id)->getIndexedArray('armazem_id','{armazem->descricao}');
        return implode(', ', $values);
    }

    
}

