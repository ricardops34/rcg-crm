<?php

class ViewVendedorClienteStatus extends TRecord
{
    const TABLENAME  = 'view_vendedor_cliente_status';
    const PRIMARYKEY = 'vendedor_id';
    const IDPOLICY   =  'max'; // {max, serial}

    

    /**
     * Constructor method
     */
    public function __construct($id = NULL, $callObjectLoad = TRUE)
    {
        parent::__construct($id, $callObjectLoad);
        parent::addAttribute('cliente_status');
        parent::addAttribute('vendedor_desligado');
        parent::addAttribute('quantidade');
            
    }

    
}

