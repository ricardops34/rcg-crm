<?php

class TabelaPrecoItem extends TRecord
{
    const TABLENAME  = 'tabela_preco_item';
    const PRIMARYKEY = 'id';
    const IDPOLICY   =  'serial'; // {max, serial}

    private Produto $produto;
    private TabelaPreco $tabela_preco;

    

    /**
     * Constructor method
     */
    public function __construct($id = NULL, $callObjectLoad = TRUE)
    {
        parent::__construct($id, $callObjectLoad);
        parent::addAttribute('item');
        parent::addAttribute('tabela_preco_id');
        parent::addAttribute('produto_id');
        parent::addAttribute('preco');
        parent::addAttribute('status');
        parent::addAttribute('dt_alteracao');
        parent::addAttribute('dt_inclusao');
            
    }

    /**
     * Method set_produto
     * Sample of usage: $var->produto = $object;
     * @param $object Instance of Produto
     */
    public function set_produto(Produto $object)
    {
        $this->produto = $object;
        $this->produto_id = $object->id;
    }

    /**
     * Method get_produto
     * Sample of usage: $var->produto->attribute;
     * @returns Produto instance
     */
    public function get_produto()
    {
    
        // loads the associated object
        if (empty($this->produto))
            $this->produto = new Produto($this->produto_id);
    
        // returns the associated object
        return $this->produto;
    }
    /**
     * Method set_tabela_preco
     * Sample of usage: $var->tabela_preco = $object;
     * @param $object Instance of TabelaPreco
     */
    public function set_tabela_preco(TabelaPreco $object)
    {
        $this->tabela_preco = $object;
        $this->tabela_preco_id = $object->id;
    }

    /**
     * Method get_tabela_preco
     * Sample of usage: $var->tabela_preco->attribute;
     * @returns TabelaPreco instance
     */
    public function get_tabela_preco()
    {
    
        // loads the associated object
        if (empty($this->tabela_preco))
            $this->tabela_preco = new TabelaPreco($this->tabela_preco_id);
    
        // returns the associated object
        return $this->tabela_preco;
    }

    
}

