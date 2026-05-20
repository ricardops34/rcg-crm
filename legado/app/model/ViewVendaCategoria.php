<?php

class ViewVendaCategoria extends TRecord
{
    const TABLENAME  = 'view_venda_categoria';
    const PRIMARYKEY = 'categoria_id';
    const IDPOLICY   =  'max'; // {max, serial}

    

    /**
     * Constructor method
     */
    public function __construct($id = NULL, $callObjectLoad = TRUE)
    {
        parent::__construct($id, $callObjectLoad);
        parent::addAttribute('categoria_cod_erp');
        parent::addAttribute('categoria_descricao');
        parent::addAttribute('produto_id');
        parent::addAttribute('nota_saida_ano');
        parent::addAttribute('nota_saida_mes');
        parent::addAttribute('nota_saida_item_vlr_total');
        parent::addAttribute('cliente_vendedor_id');
        parent::addAttribute('vendedor_id');
        parent::addAttribute('vendedor_status');
        parent::addAttribute('nota_saida_item_vlr_liquido');
        parent::addAttribute('nota_saida_id');
        parent::addAttribute('nota_fiscal');
        parent::addAttribute('especie_fiscal');
        parent::addAttribute('cliente_id');
        parent::addAttribute('cliente_cod_erp');
            
    }

    
}

