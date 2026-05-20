<?php

class ViewVendaMes extends TRecord
{
    const TABLENAME  = 'view_venda_mes';
    const PRIMARYKEY = 'vendedor1_id';
    const IDPOLICY   =  'max'; // {max, serial}

    

    /**
     * Constructor method
     */
    public function __construct($id = NULL, $callObjectLoad = TRUE)
    {
        parent::__construct($id, $callObjectLoad);
        parent::addAttribute('ano');
        parent::addAttribute('mes');
        parent::addAttribute('qtd');
            
    }

    
}

