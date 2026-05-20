<?php

class VendaMesProduto extends TRecord
{
    const TABLENAME  = 'venda_mes_produto';
    const PRIMARYKEY = 'id';
    const IDPOLICY   =  'serial'; // {max, serial}

    private Produto $produto;

    /**
     * Constructor method
     */
    public function __construct($id = NULL, $callObjectLoad = TRUE)
    {
        parent::__construct($id, $callObjectLoad);
        parent::addAttribute('filial_id');
        parent::addAttribute('empresa_id');
        parent::addAttribute('produto_id');
        parent::addAttribute('ano');
        parent::addAttribute('janeiro');
        parent::addAttribute('fevereiro');
        parent::addAttribute('marco');
        parent::addAttribute('abril');
        parent::addAttribute('maio');
        parent::addAttribute('junho');
        parent::addAttribute('julho');
        parent::addAttribute('agosto');
        parent::addAttribute('setembro');
        parent::addAttribute('outubro');
        parent::addAttribute('novembro');
        parent::addAttribute('dezembro');
        parent::addAttribute('produto_nome');
    
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

    public function onBeforeStore($object)
    {
        $id = $object->produto_id;
        $oProduto = Produto::where('id', '=', $id)
                ->first();
    
        if($oProduto){
            $cNome = ltrim(rtrim(ltrim($oProduto->descricao)));
            $object->produto_nome = $cNome;
        }
    
    }

}

