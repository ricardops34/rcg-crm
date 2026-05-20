<?php

class ViewTotalCatogoriaMes extends TRecord
{
    const TABLENAME  = 'view_total_catogoria_mes';
    const PRIMARYKEY = 'id';
    const IDPOLICY   =  'max'; // {max, serial}

    

    /**
     * Constructor method
     */
    public function __construct($id = NULL, $callObjectLoad = TRUE)
    {
        parent::__construct($id, $callObjectLoad);
        parent::addAttribute('cod_erp');
        parent::addAttribute('categoria');
        parent::addAttribute('vendedor_id');
        parent::addAttribute('ano');
        parent::addAttribute('mes');
        parent::addAttribute('vlr_total');
        parent::addAttribute('vlr_liquido');
        parent::addAttribute('vlr_objetivo');
        parent::addAttribute('perc_total');
        parent::addAttribute('perc_liquido');
            
    }

    
}

