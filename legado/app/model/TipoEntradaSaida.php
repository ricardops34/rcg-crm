<?php

class TipoEntradaSaida extends TRecord
{
    const TABLENAME  = 'tipo_entrada_saida';
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
        parent::addAttribute('empresa_id');
        parent::addAttribute('cod_erp');
        parent::addAttribute('tipo');
        parent::addAttribute('descricao');
        parent::addAttribute('finalidade');
        parent::addAttribute('status');
        parent::addAttribute('cfop');
        parent::addAttribute('dt_alteracao');
        parent::addAttribute('dt_inclusao');
        parent::addAttribute('system_unit_id');
            
    }

    /**
     * Method getProdutos
     */
    public function getProdutosByTes()
    {
        $criteria = new TCriteria;
        $criteria->add(new TFilter('te_id', '=', $this->id));
        return Produto::getObjects( $criteria );
    }
    /**
     * Method getProdutos
     */
    public function getProdutosByTss()
    {
        $criteria = new TCriteria;
        $criteria->add(new TFilter('ts_id', '=', $this->id));
        return Produto::getObjects( $criteria );
    }
    /**
     * Method getNotaSaidaItems
     */
    public function getNotaSaidaItems()
    {
        $criteria = new TCriteria;
        $criteria->add(new TFilter('tes_id', '=', $this->id));
        return NotaSaidaItem::getObjects( $criteria );
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
    
        $values = Produto::where('ts_id', '=', $this->id)->getIndexedArray('filial_id','{filial->apelido}');
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
    
        $values = Produto::where('ts_id', '=', $this->id)->getIndexedArray('categoria_id','{categoria->descricao}');
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
    
        $values = Produto::where('ts_id', '=', $this->id)->getIndexedArray('sub_categoria_id','{sub_categoria->descricao}');
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
    
        $values = Produto::where('ts_id', '=', $this->id)->getIndexedArray('fabricante_id','{fabricante->descricao}');
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
    
        $values = Produto::where('ts_id', '=', $this->id)->getIndexedArray('armazem_id','{armazem->descricao}');
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
    
        $values = Produto::where('ts_id', '=', $this->id)->getIndexedArray('te_id','{te->id}');
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
    
        $values = Produto::where('ts_id', '=', $this->id)->getIndexedArray('ts_id','{ts->id}');
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
    
        $values = NotaSaidaItem::where('tes_id', '=', $this->id)->getIndexedArray('nota_saida_id','{nota_saida->id}');
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
    
        $values = NotaSaidaItem::where('tes_id', '=', $this->id)->getIndexedArray('produto_id','{produto->descricao}');
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
    
        $values = NotaSaidaItem::where('tes_id', '=', $this->id)->getIndexedArray('tes_id','{tes->id}');
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
    
        $values = NotaSaidaItem::where('tes_id', '=', $this->id)->getIndexedArray('cliente_id','{cliente->razao}');
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
    
        $values = NotaSaidaItem::where('tes_id', '=', $this->id)->getIndexedArray('vendedor1_id','{vendedor1->nome}');
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
    
        $values = NotaSaidaItem::where('tes_id', '=', $this->id)->getIndexedArray('vendedor2_id','{vendedor2->nome}');
        return implode(', ', $values);
    }

    
}

