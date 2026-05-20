<?php

class MetaVendedorMes extends TRecord
{
    const TABLENAME  = 'meta_vendedor_mes';
    const PRIMARYKEY = 'id';
    const IDPOLICY   =  'serial'; // {max, serial}

    const DELETEDAT  = 'dt_delete';
    const CREATEDAT  = 'dt_inclusao';
    const UPDATEDAT  = 'dt_alteracao';

    private Vendedor $vendedor;

    

    /**
     * Constructor method
     */
    public function __construct($id = NULL, $callObjectLoad = TRUE)
    {
        parent::__construct($id, $callObjectLoad);
        parent::addAttribute('vendedor_id');
        parent::addAttribute('mes');
        parent::addAttribute('ano');
        parent::addAttribute('tipo');
        parent::addAttribute('valor');
        parent::addAttribute('numero_cliente');
        parent::addAttribute('novo_cliente');
        parent::addAttribute('dt_alteracao');
        parent::addAttribute('dt_inclusao');
        parent::addAttribute('dt_delete');
            
    }

    /**
     * Method set_vendedor
     * Sample of usage: $var->vendedor = $object;
     * @param $object Instance of Vendedor
     */
    public function set_vendedor(Vendedor $object)
    {
        $this->vendedor = $object;
        $this->vendedor_id = $object->id;
    }

    /**
     * Method get_vendedor
     * Sample of usage: $var->vendedor->attribute;
     * @returns Vendedor instance
     */
    public function get_vendedor()
    {
    
        // loads the associated object
        if (empty($this->vendedor))
            $this->vendedor = new Vendedor($this->vendedor_id);
    
        // returns the associated object
        return $this->vendedor;
    }

    /**
     * Method getMetaVendedorCategorias
     */
    public function getMetaVendedorCategorias()
    {
        $criteria = new TCriteria;
        $criteria->add(new TFilter('meta_vendedor_mes_id', '=', $this->id));
        return MetaVendedorCategoria::getObjects( $criteria );
    }

    public function set_meta_vendedor_categoria_meta_vendedor_mes_to_string($meta_vendedor_categoria_meta_vendedor_mes_to_string)
    {
        if(is_array($meta_vendedor_categoria_meta_vendedor_mes_to_string))
        {
            $values = MetaVendedorMes::where('id', 'in', $meta_vendedor_categoria_meta_vendedor_mes_to_string)->getIndexedArray('id', 'id');
            $this->meta_vendedor_categoria_meta_vendedor_mes_to_string = implode(', ', $values);
        }
        else
        {
            $this->meta_vendedor_categoria_meta_vendedor_mes_to_string = $meta_vendedor_categoria_meta_vendedor_mes_to_string;
        }

        $this->vdata['meta_vendedor_categoria_meta_vendedor_mes_to_string'] = $this->meta_vendedor_categoria_meta_vendedor_mes_to_string;
    }

    public function get_meta_vendedor_categoria_meta_vendedor_mes_to_string()
    {
        if(!empty($this->meta_vendedor_categoria_meta_vendedor_mes_to_string))
        {
            return $this->meta_vendedor_categoria_meta_vendedor_mes_to_string;
        }
    
        $values = MetaVendedorCategoria::where('meta_vendedor_mes_id', '=', $this->id)->getIndexedArray('meta_vendedor_mes_id','{meta_vendedor_mes->id}');
        return implode(', ', $values);
    }

    public function set_meta_vendedor_categoria_categoria_to_string($meta_vendedor_categoria_categoria_to_string)
    {
        if(is_array($meta_vendedor_categoria_categoria_to_string))
        {
            $values = Categoria::where('id', 'in', $meta_vendedor_categoria_categoria_to_string)->getIndexedArray('descricao', 'descricao');
            $this->meta_vendedor_categoria_categoria_to_string = implode(', ', $values);
        }
        else
        {
            $this->meta_vendedor_categoria_categoria_to_string = $meta_vendedor_categoria_categoria_to_string;
        }

        $this->vdata['meta_vendedor_categoria_categoria_to_string'] = $this->meta_vendedor_categoria_categoria_to_string;
    }

    public function get_meta_vendedor_categoria_categoria_to_string()
    {
        if(!empty($this->meta_vendedor_categoria_categoria_to_string))
        {
            return $this->meta_vendedor_categoria_categoria_to_string;
        }
    
        $values = MetaVendedorCategoria::where('meta_vendedor_mes_id', '=', $this->id)->getIndexedArray('categoria_id','{categoria->descricao}');
        return implode(', ', $values);
    }

    /**
     * Method onBeforeDelete
     */
    public function onBeforeDelete()
    {
            

        if(MetaVendedorCategoria::where('meta_vendedor_mes_id', '=', $this->id)->first())
        {
            throw new Exception("Não é possível deletar este registro pois ele está sendo utilizado em outra parte do sistema");
        }
    
    }

    
}

