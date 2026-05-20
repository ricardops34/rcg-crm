<?php

class ViewProdutoEstoquePreco extends TRecord
{
    const TABLENAME  = 'view_produto_estoque_preco';
    const PRIMARYKEY = 'produto_id';
    const IDPOLICY   =  'max'; // {max, serial}

  
    const PRIMARYKEY = 'produto_cod_erp';

    /**
     * Constructor method
     */
    public function __construct($id = NULL, $callObjectLoad = TRUE)
    {
        parent::__construct($id, $callObjectLoad);
        parent::addAttribute('produto_cod_erp');
        parent::addAttribute('produto_descricao');
        parent::addAttribute('armazem_id');
        parent::addAttribute('armazem_descricao');
        parent::addAttribute('produto_saldo');
        parent::addAttribute('tabela_preco_id');
        parent::addAttribute('precos_descricao');
        parent::addAttribute('preco');
        parent::addAttribute('armazem_cod_erp');
    
    }

}

