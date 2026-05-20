<?php

class VendaVendedorProduto extends TRecord
{
    const TABLENAME  = 'venda_vendedor_produto';
    const PRIMARYKEY = 'id';
    const IDPOLICY   =  'max'; // {max, serial}

    

    /**
     * Constructor method
     */
    public function __construct($id = NULL, $callObjectLoad = TRUE)
    {
        parent::__construct($id, $callObjectLoad);
        parent::addAttribute('nota_fiscal');
        parent::addAttribute('serie_fiscal');
        parent::addAttribute('dt_emissao');
        parent::addAttribute('tipo');
        parent::addAttribute('vendedor_nome');
        parent::addAttribute('cliente_fantasia');
        parent::addAttribute('cliente_municipio');
        parent::addAttribute('cliente_estado');
        parent::addAttribute('produto_codigo');
        parent::addAttribute('produto_descricao');
        parent::addAttribute('vlr_tabela');
        parent::addAttribute('quantidade');
        parent::addAttribute('vlr_unitario');
        parent::addAttribute('vlr_desconto');
        parent::addAttribute('vlr_total');
        parent::addAttribute('vendedor_cod');
        parent::addAttribute('mes');
        parent::addAttribute('ano');
        parent::addAttribute('produto_categoria');
        parent::addAttribute('categoria_descricao');
        parent::addAttribute('produto_sub_categoria');
        parent::addAttribute('sub_categoria_descricao');
        parent::addAttribute('cliente_codigo');
        parent::addAttribute('cliente_razao');
        parent::addAttribute('cliente_id');
            
    }

    
}

