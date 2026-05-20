<?php

class VendasAnoMes extends TRecord
{
    const TABLENAME  = 'vendas_ano_mes';
    const PRIMARYKEY = 'vendedor_id';
    const IDPOLICY   =  'max'; // {max, serial}

    

    /**
     * Constructor method
     */
    public function __construct($id = NULL, $callObjectLoad = TRUE)
    {
        parent::__construct($id, $callObjectLoad);
        parent::addAttribute('vendedor_cod_erp');
        parent::addAttribute('vendedor_nome');
        parent::addAttribute('cliente_cod_erp');
        parent::addAttribute('cliente_razao');
        parent::addAttribute('ano');
        parent::addAttribute('mes');
        parent::addAttribute('valor');
        parent::addAttribute('cliente_id');
            
    }

    
}

