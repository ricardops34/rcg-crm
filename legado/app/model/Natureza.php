<?php

class Natureza extends TRecord
{
    const TABLENAME  = 'natureza';
    const PRIMARYKEY = 'id';
    const IDPOLICY   =  'serial'; // {max, serial}

    const CREATEDAT  = 'dt_inclusao';
    const UPDATEDAT  = 'dt_alteracao';

    

    /**
     * Constructor method
     */
    public function __construct($id = NULL, $callObjectLoad = TRUE)
    {
        parent::__construct($id, $callObjectLoad);
        parent::addAttribute('cod_erp');
        parent::addAttribute('descricao');
        parent::addAttribute('dt_alteracao');
        parent::addAttribute('dt_inclusao');
            
    }

    
}

