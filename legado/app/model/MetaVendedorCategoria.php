<?php

class MetaVendedorCategoria extends TRecord
{
    const TABLENAME  = 'meta_vendedor_categoria';
    const PRIMARYKEY = 'id';
    const IDPOLICY   =  'serial'; // {max, serial}

    const DELETEDAT  = 'dt_delete';
    const CREATEDAT  = 'dt_inclusao';
    const UPDATEDAT  = 'dt_alteracao';

    private MetaVendedorMes $meta_vendedor_mes;
    private Categoria $categoria;

    /**
     * Constructor method
     */
    public function __construct($id = NULL, $callObjectLoad = TRUE)
    {
        parent::__construct($id, $callObjectLoad);
        parent::addAttribute('meta_vendedor_mes_id');
        parent::addAttribute('categoria_id');
        parent::addAttribute('cod_erp');
        parent::addAttribute('descricao');
        parent::addAttribute('valor');
        parent::addAttribute('dt_alteracao');
        parent::addAttribute('dt_inclusao');
        parent::addAttribute('dt_delete');
    
    }

    /**
     * Method set_meta_vendedor_mes
     * Sample of usage: $var->meta_vendedor_mes = $object;
     * @param $object Instance of MetaVendedorMes
     */
    public function set_meta_vendedor_mes(MetaVendedorMes $object)
    {
        $this->meta_vendedor_mes = $object;
        $this->meta_vendedor_mes_id = $object->id;
    }

    /**
     * Method get_meta_vendedor_mes
     * Sample of usage: $var->meta_vendedor_mes->attribute;
     * @returns MetaVendedorMes instance
     */
    public function get_meta_vendedor_mes()
    {
    
        // loads the associated object
        if (empty($this->meta_vendedor_mes))
            $this->meta_vendedor_mes = new MetaVendedorMes($this->meta_vendedor_mes_id);
    
        // returns the associated object
        return $this->meta_vendedor_mes;
    }
    /**
     * Method set_categoria
     * Sample of usage: $var->categoria = $object;
     * @param $object Instance of Categoria
     */
    public function set_categoria(Categoria $object)
    {
        $this->categoria = $object;
        $this->categoria_id = $object->id;
    }

    /**
     * Method get_categoria
     * Sample of usage: $var->categoria->attribute;
     * @returns Categoria instance
     */
    public function get_categoria()
    {
    
        // loads the associated object
        if (empty($this->categoria))
            $this->categoria = new Categoria($this->categoria_id);
    
        // returns the associated object
        return $this->categoria;
    }

}

