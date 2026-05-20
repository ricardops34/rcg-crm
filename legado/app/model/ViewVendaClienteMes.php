<?php

class ViewVendaClienteMes extends TRecord
{
    const TABLENAME  = 'view_venda_cliente_mes';
    const PRIMARYKEY = 'cliente_id';
    const IDPOLICY   =  'max'; // {max, serial}

    

    /**
     * Constructor method
     */
    public function __construct($id = NULL, $callObjectLoad = TRUE)
    {
        parent::__construct($id, $callObjectLoad);
        parent::addAttribute('nota_vendedor_id');
        parent::addAttribute('cliente_vendedor_id');
        parent::addAttribute('ano');
        parent::addAttribute('mes');
        parent::addAttribute('dt_emissao');
        parent::addAttribute('vlr_total');
            
    }

    
}

