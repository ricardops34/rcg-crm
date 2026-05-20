<?php

class ViewVendaCliente extends TRecord
{
    const TABLENAME  = 'view_venda_cliente';
    const PRIMARYKEY = 'cliente_id';
    const IDPOLICY   =  'max'; // {max, serial}

    

    /**
     * Constructor method
     */
    public function __construct($id = NULL, $callObjectLoad = TRUE)
    {
        parent::__construct($id, $callObjectLoad);
        parent::addAttribute('cliente_cod_erp');
        parent::addAttribute('produto_id');
        parent::addAttribute('nota_saida_item_ano');
        parent::addAttribute('nota_saida_item_mes');
        parent::addAttribute('nota_saida_item_vlr_total');
        parent::addAttribute('nota_saida_item_vlr_liquido');
        parent::addAttribute('cliente_vendedor_id');
        parent::addAttribute('nota_vendedor_id');
        parent::addAttribute('nota_saida_dt_emissao');
            
    }

    
}

