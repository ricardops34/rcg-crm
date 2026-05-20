<?php

class Cep extends TRecord
{
    const TABLENAME  = 'cep';
    const PRIMARYKEY = 'id';
    const IDPOLICY   =  'serial'; // {max, serial}

    

    /**
     * Constructor method
     */
    public function __construct($id = NULL, $callObjectLoad = TRUE)
    {
        parent::__construct($id, $callObjectLoad);
        parent::addAttribute('cep');
        parent::addAttribute('estado_id');
        parent::addAttribute('cidade_id');
        parent::addAttribute('bairro');
        parent::addAttribute('endereco');
        parent::addAttribute('longitude');
        parent::addAttribute('latitude');
        parent::addAttribute('service');
            
    }

    
}

