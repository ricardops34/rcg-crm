<?php

class Step extends TRecord
{
    const TABLENAME  = 'step';
    const PRIMARYKEY = 'id';
    const IDPOLICY   =  'serial'; // {max, serial}

    

    /**
     * Constructor method
     */
    public function __construct($id = NULL, $callObjectLoad = TRUE)
    {
        parent::__construct($id, $callObjectLoad);
        parent::addAttribute('grupo');
        parent::addAttribute('sequencia');
        parent::addAttribute('variavel');
        parent::addAttribute('descricao');
        parent::addAttribute('cor');
        parent::addAttribute('column_6');
            
    }

    
}

