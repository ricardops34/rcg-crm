<?php

class ViewBaseVenda extends TRecord
{
    const TABLENAME  = 'view_base_venda';
    const PRIMARYKEY = 'id';
    const IDPOLICY   =  'max'; // {max, serial}

    /**
     * Constructor method
     */
    public function __construct($id = NULL, $callObjectLoad = TRUE)
    {
        parent::__construct($id, $callObjectLoad);
        parent::addAttribute('categoria_id');
        parent::addAttribute('produto_id');
        parent::addAttribute('ano');
        parent::addAttribute('mes');
        parent::addAttribute('vlr_total');
        parent::addAttribute('vlr_bruto');
        parent::addAttribute('vlr_dev');
        parent::addAttribute('cliente_id');
        parent::addAttribute('vendedor_id');
        parent::addAttribute('nota_saida_item');
        parent::addAttribute('cfop');
        parent::addAttribute('cliente_vendedor_id');
    
    }

}

