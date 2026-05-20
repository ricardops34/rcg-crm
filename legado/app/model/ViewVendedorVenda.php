<?php

class ViewVendedorVenda extends TRecord
{
    const TABLENAME  = 'view_vendedor_venda';
    const PRIMARYKEY = 'vendedor_id';
    const IDPOLICY   =  'max'; // {max, serial}

    

    /**
     * Constructor method
     */
    public function __construct($id = NULL, $callObjectLoad = TRUE)
    {
        parent::__construct($id, $callObjectLoad);
        parent::addAttribute('nome_reduzido');
        parent::addAttribute('nome');
        parent::addAttribute('situacao');
        parent::addAttribute('produto_id');
        parent::addAttribute('cliente_id');
        parent::addAttribute('nota_saida_item_vlr_total');
        parent::addAttribute('nota_saida_item_vlr_liquido');
        parent::addAttribute('mes');
        parent::addAttribute('ano');
        parent::addAttribute('produto_cod_erp');
        parent::addAttribute('produto_descricao');
        parent::addAttribute('categoria_id');
        parent::addAttribute('categoria_cod_erp');
        parent::addAttribute('categoria_descricao');
        parent::addAttribute('cliente_razao');
        parent::addAttribute('cliente_fantasia');
            
    }

    
}

