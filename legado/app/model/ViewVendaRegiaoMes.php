<?php

class ViewVendaRegiaoMes extends TRecord
{
    const TABLENAME  = 'view_venda_regiao_mes';
    const PRIMARYKEY = 'regiao_id';
    const IDPOLICY   =  'max'; // {max, serial}

    

    /**
     * Constructor method
     */
    public function __construct($id = NULL, $callObjectLoad = TRUE)
    {
        parent::__construct($id, $callObjectLoad);
        parent::addAttribute('regiao_descricao');
        parent::addAttribute('mes');
        parent::addAttribute('ano');
        parent::addAttribute('vlr_total');
        parent::addAttribute('vlr_liquido');
            
    }

    
}

