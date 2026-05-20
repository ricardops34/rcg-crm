<?php

class ClienteAtendidoMes extends TRecord
{
    const TABLENAME  = 'cliente_atendido_mes';
    const PRIMARYKEY = 'cliente_id';
    const IDPOLICY   =  'max'; // {max, serial}

    

    /**
     * Constructor method
     */
    public function __construct($id = NULL, $callObjectLoad = TRUE)
    {
        parent::__construct($id, $callObjectLoad);
        parent::addAttribute('vendedor1');
        parent::addAttribute('mes');
        parent::addAttribute('ano');
        parent::addAttribute('vendedor2');
            
    }

    
}

