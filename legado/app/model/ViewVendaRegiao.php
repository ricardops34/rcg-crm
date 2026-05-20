<?php

class ViewVendaRegiao extends TRecord
{
    const TABLENAME  = 'view_venda_regiao';
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
        parent::addAttribute('produto_id');
        parent::addAttribute('cliente_id');
        parent::addAttribute('vlr_total');
        parent::addAttribute('vlr_liquido');
        parent::addAttribute('vlr_dev');
            
    }

    
}

