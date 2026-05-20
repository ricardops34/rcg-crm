<?php

class ViewVendedorVendaMes extends TRecord
{
    const TABLENAME  = 'view_vendedor_venda_mes';
    const PRIMARYKEY = 'vendedor_id';
    const IDPOLICY   =  'max'; // {max, serial}

    

    /**
     * Constructor method
     */
    public function __construct($id = NULL, $callObjectLoad = TRUE)
    {
        parent::__construct($id, $callObjectLoad);
        parent::addAttribute('mes');
        parent::addAttribute('ano');
        parent::addAttribute('vlr_total');
        parent::addAttribute('vlr_liquido');
        parent::addAttribute('vlr_objetivo');
        parent::addAttribute('perc_total');
        parent::addAttribute('perc_liquido');
        parent::addAttribute('nome');
        parent::addAttribute('nome_reduzido');
        parent::addAttribute('positivacao');
            
    }

    
}

