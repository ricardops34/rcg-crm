<?php

class ProdutoCaracteristica extends TRecord
{
    const TABLENAME  = 'produto_caracteristica';
    const PRIMARYKEY = 'id';
    const IDPOLICY   =  'serial'; // {max, serial}

    const CREATEDAT  = 'dt_inclusao';
    const UPDATEDAT  = 'dt_alteracao';

    private Caracteristica $caracteristica;
    private Produto $produto;

    

    /**
     * Constructor method
     */
    public function __construct($id = NULL, $callObjectLoad = TRUE)
    {
        parent::__construct($id, $callObjectLoad);
        parent::addAttribute('caracteristica_id');
        parent::addAttribute('produto_id');
        parent::addAttribute('des_caracteristica');
        parent::addAttribute('dt_caracteristica');
        parent::addAttribute('vlr_caracteristica');
        parent::addAttribute('dt_alteracao');
        parent::addAttribute('dt_inclusao');
            
    }

    /**
     * Method set_caracteristica
     * Sample of usage: $var->caracteristica = $object;
     * @param $object Instance of Caracteristica
     */
    public function set_caracteristica(Caracteristica $object)
    {
        $this->caracteristica = $object;
        $this->caracteristica_id = $object->id;
    }

    /**
     * Method get_caracteristica
     * Sample of usage: $var->caracteristica->attribute;
     * @returns Caracteristica instance
     */
    public function get_caracteristica()
    {
    
        // loads the associated object
        if (empty($this->caracteristica))
            $this->caracteristica = new Caracteristica($this->caracteristica_id);
    
        // returns the associated object
        return $this->caracteristica;
    }
    /**
     * Method set_produto
     * Sample of usage: $var->produto = $object;
     * @param $object Instance of Produto
     */
    public function set_produto(Produto $object)
    {
        $this->produto = $object;
        $this->produto_id = $object->id;
    }

    /**
     * Method get_produto
     * Sample of usage: $var->produto->attribute;
     * @returns Produto instance
     */
    public function get_produto()
    {
    
        // loads the associated object
        if (empty($this->produto))
            $this->produto = new Produto($this->produto_id);
    
        // returns the associated object
        return $this->produto;
    }

    
}

