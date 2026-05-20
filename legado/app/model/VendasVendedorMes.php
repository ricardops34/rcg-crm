<?php

class VendasVendedorMes extends TRecord
{
    const TABLENAME  = 'vendas_vendedor_mes';
    const PRIMARYKEY = 'vendedor1_id';
    const IDPOLICY   =  'max'; // {max, serial}

    

    /**
     * Constructor method
     */
    public function __construct($id = NULL, $callObjectLoad = TRUE)
    {
        parent::__construct($id, $callObjectLoad);
        parent::addAttribute('vlr_bruto');
        parent::addAttribute('vlr_devolucao');
        parent::addAttribute('vlr_comodato');
        parent::addAttribute('mes');
        parent::addAttribute('ano');
        parent::addAttribute('vlr_liquido');
        parent::addAttribute('vlr_mercadoria');
        parent::addAttribute('especie_fiscal');
        parent::addAttribute('vendedor1_status');
        parent::addAttribute('reg_ativo');
            
    }

    
}

