<?php

class VendedorNotaFiscal extends TRecord
{
    const TABLENAME  = 'vendedor_nota_fiscal';
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
        parent::addAttribute('ano');
        parent::addAttribute('mes');
        parent::addAttribute('nota_saida_cliente_id');
        parent::addAttribute('nota_saida_vlr_itens');
            
    }

    
}

