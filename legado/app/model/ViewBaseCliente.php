<?php

class ViewBaseCliente extends TRecord
{
    const TABLENAME  = 'view_base_cliente';
    const PRIMARYKEY = 'cliente_id';
    const IDPOLICY   =  'max'; // {max, serial}

    

    /**
     * Constructor method
     */
    public function __construct($id = NULL, $callObjectLoad = TRUE)
    {
        parent::__construct($id, $callObjectLoad);
        parent::addAttribute('vendedor_id');
        parent::addAttribute('ano');
        parent::addAttribute('mes');
        parent::addAttribute('nota_fiscal_id');
            
    }

    
}

