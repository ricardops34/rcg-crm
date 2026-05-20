<?php

class Fabricante extends TRecord
{
    const TABLENAME  = 'fabricante';
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
        parent::addAttribute('dt_inclusao');
        parent::addAttribute('dt_alteracao');
            
    }

    /**
     * Method getProdutos
     */
    public function getProdutos()
    {
        $criteria = new TCriteria;
        $criteria->add(new TFilter('fabricante_id', '=', $this->id));
        return Produto::getObjects( $criteria );
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
    
        $values = Produto::where('fabricante_id', '=', $this->id)->getIndexedArray('filial_id','{filial->apelido}');
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
    
        $values = Produto::where('fabricante_id', '=', $this->id)->getIndexedArray('categoria_id','{categoria->descricao}');
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
    
        $values = Produto::where('fabricante_id', '=', $this->id)->getIndexedArray('sub_categoria_id','{sub_categoria->descricao}');
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
    
        $values = Produto::where('fabricante_id', '=', $this->id)->getIndexedArray('fabricante_id','{fabricante->descricao}');
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
    
        $values = Produto::where('fabricante_id', '=', $this->id)->getIndexedArray('armazem_id','{armazem->descricao}');
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
    
        $values = Produto::where('fabricante_id', '=', $this->id)->getIndexedArray('te_id','{te->id}');
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
    
        $values = Produto::where('fabricante_id', '=', $this->id)->getIndexedArray('ts_id','{ts->id}');
        return implode(', ', $values);
    }

    
}

