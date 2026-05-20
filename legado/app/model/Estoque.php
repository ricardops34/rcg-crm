<?php

class Estoque extends TRecord
{
    const TABLENAME  = 'estoque';
    const PRIMARYKEY = 'id';
    const IDPOLICY   =  'serial'; // {max, serial}

    const CREATEDAT  = 'dt_inclusao';
    const UPDATEDAT  = 'dt_inclusao';

    private Armazem $armazem;
    private Produto $produto;
    private Filial $filial;

    

    /**
     * Constructor method
     */
    public function __construct($id = NULL, $callObjectLoad = TRUE)
    {
        parent::__construct($id, $callObjectLoad);
        parent::addAttribute('filial_id');
        parent::addAttribute('produto_id');
        parent::addAttribute('armazem_id');
        parent::addAttribute('saldo');
        parent::addAttribute('reserva');
        parent::addAttribute('system_unit_id');
        parent::addAttribute('ult_compra');
        parent::addAttribute('ult_preco');
        parent::addAttribute('custo');
        parent::addAttribute('dt_inclusao');
        parent::addAttribute('dt_alteracao');
            
    }

    /**
     * Method set_armazem
     * Sample of usage: $var->armazem = $object;
     * @param $object Instance of Armazem
     */
    public function set_armazem(Armazem $object)
    {
        $this->armazem = $object;
        $this->armazem_id = $object->id;
    }

    /**
     * Method get_armazem
     * Sample of usage: $var->armazem->attribute;
     * @returns Armazem instance
     */
    public function get_armazem()
    {
    
        // loads the associated object
        if (empty($this->armazem))
            $this->armazem = new Armazem($this->armazem_id);
    
        // returns the associated object
        return $this->armazem;
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

    
}

