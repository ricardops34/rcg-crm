<?php

class ViewVendas extends TRecord
{
    const TABLENAME  = 'view_vendas';
    const PRIMARYKEY = 'vendedor1_id';
    const IDPOLICY   =  'max'; // {max, serial}

    

    /**
     * Constructor method
     */
    public function __construct($id = NULL, $callObjectLoad = TRUE)
    {
        parent::__construct($id, $callObjectLoad);
        parent::addAttribute('cliente_id');
        parent::addAttribute('ano');
        parent::addAttribute('mes');
            
    }

    
}

