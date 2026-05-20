<?php

class ViewPrecos extends TRecord
{
    const TABLENAME  = 'view_precos';
    const PRIMARYKEY = 'id';
    const IDPOLICY   =  'max'; // {max, serial}

    

    /**
     * Constructor method
     */
    public function __construct($id = NULL, $callObjectLoad = TRUE)
    {
        parent::__construct($id, $callObjectLoad);
        parent::addAttribute('tabela_preco_id');
        parent::addAttribute('cod_erp');
        parent::addAttribute('descricao');
        parent::addAttribute('item');
        parent::addAttribute('produto_id');
        parent::addAttribute('preco');
            
    }

    
}

