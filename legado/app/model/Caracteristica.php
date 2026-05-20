<?php

class Caracteristica extends TRecord
{
    const TABLENAME  = 'caracteristica';
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
        parent::addAttribute('descricao');
        parent::addAttribute('tipo');
        parent::addAttribute('dt_alteracao');
        parent::addAttribute('dt_inclusao');
            
    }

    /**
     * Method getProdutoCaracteristicas
     */
    public function getProdutoCaracteristicas()
    {
        $criteria = new TCriteria;
        $criteria->add(new TFilter('caracteristica_id', '=', $this->id));
        return ProdutoCaracteristica::getObjects( $criteria );
    }

    public function set_produto_caracteristica_caracteristica_to_string($produto_caracteristica_caracteristica_to_string)
    {
        if(is_array($produto_caracteristica_caracteristica_to_string))
        {
            $values = Caracteristica::where('id', 'in', $produto_caracteristica_caracteristica_to_string)->getIndexedArray('id', 'id');
            $this->produto_caracteristica_caracteristica_to_string = implode(', ', $values);
        }
        else
        {
            $this->produto_caracteristica_caracteristica_to_string = $produto_caracteristica_caracteristica_to_string;
        }

        $this->vdata['produto_caracteristica_caracteristica_to_string'] = $this->produto_caracteristica_caracteristica_to_string;
    }

    public function get_produto_caracteristica_caracteristica_to_string()
    {
        if(!empty($this->produto_caracteristica_caracteristica_to_string))
        {
            return $this->produto_caracteristica_caracteristica_to_string;
        }
    
        $values = ProdutoCaracteristica::where('caracteristica_id', '=', $this->id)->getIndexedArray('caracteristica_id','{caracteristica->id}');
        return implode(', ', $values);
    }

    public function set_produto_caracteristica_produto_to_string($produto_caracteristica_produto_to_string)
    {
        if(is_array($produto_caracteristica_produto_to_string))
        {
            $values = Produto::where('id', 'in', $produto_caracteristica_produto_to_string)->getIndexedArray('descricao', 'descricao');
            $this->produto_caracteristica_produto_to_string = implode(', ', $values);
        }
        else
        {
            $this->produto_caracteristica_produto_to_string = $produto_caracteristica_produto_to_string;
        }

        $this->vdata['produto_caracteristica_produto_to_string'] = $this->produto_caracteristica_produto_to_string;
    }

    public function get_produto_caracteristica_produto_to_string()
    {
        if(!empty($this->produto_caracteristica_produto_to_string))
        {
            return $this->produto_caracteristica_produto_to_string;
        }
    
        $values = ProdutoCaracteristica::where('caracteristica_id', '=', $this->id)->getIndexedArray('produto_id','{produto->descricao}');
        return implode(', ', $values);
    }

    
}

