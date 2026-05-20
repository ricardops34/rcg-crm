<?php

class ViewVendaCategoriaAno extends TRecord
{
    const TABLENAME  = 'view_venda_categoria_ano';
    const PRIMARYKEY = 'id';
    const IDPOLICY   =  'max'; // {max, serial}

    

    /**
     * Constructor method
     */
    public function __construct($id = NULL, $callObjectLoad = TRUE)
    {
        parent::__construct($id, $callObjectLoad);
        parent::addAttribute('vendedor_id');
        parent::addAttribute('cod_erp');
        parent::addAttribute('descricao');
        parent::addAttribute('ano');
        parent::addAttribute('janeiro');
        parent::addAttribute('fevereiro');
        parent::addAttribute('marco');
        parent::addAttribute('abril');
        parent::addAttribute('maio');
        parent::addAttribute('junho');
        parent::addAttribute('julho');
        parent::addAttribute('agosto');
        parent::addAttribute('setembro');
        parent::addAttribute('outubro');
        parent::addAttribute('novembro');
        parent::addAttribute('dezembro');
            
    }

    
}

