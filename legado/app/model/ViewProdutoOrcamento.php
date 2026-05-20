<?php

class ViewProdutoOrcamento extends TRecord
{
    const TABLENAME  = 'view_produto_orcamento';
    const PRIMARYKEY = 'produto_cod_erp';
    const IDPOLICY   =  'max'; // {max, serial}

    

    /**
     * Constructor method
     */
    public function __construct($id = NULL, $callObjectLoad = TRUE)
    {
        parent::__construct($id, $callObjectLoad);
        parent::addAttribute('produto_id');
        parent::addAttribute('produto_descricao');
        parent::addAttribute('situacao');
        parent::addAttribute('armazem_id');
        parent::addAttribute('armazem_cod_erp');
        parent::addAttribute('armazem_descricao');
        parent::addAttribute('produto_saldo');
        parent::addAttribute('tabela_preco_id');
        parent::addAttribute('precos_descricao');
        parent::addAttribute('preco');
        parent::addAttribute('ultima_venda');
        parent::addAttribute('ultimo_preco');
            
    }

    
}

