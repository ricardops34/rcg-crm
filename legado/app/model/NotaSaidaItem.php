<?php

class NotaSaidaItem extends TRecord
{
    const TABLENAME  = 'nota_saida_item';
    const PRIMARYKEY = 'id';
    const IDPOLICY   =  'serial'; // {max, serial}

    private NotaSaida $nota_saida;
    private Produto $produto;
    private TipoEntradaSaida $tes;
    private Cliente $cliente;
    private Vendedor $vendedor1;
    private Vendedor $vendedor2;

    

    /**
     * Constructor method
     */
    public function __construct($id = NULL, $callObjectLoad = TRUE)
    {
        parent::__construct($id, $callObjectLoad);
        parent::addAttribute('nota_saida_id');
        parent::addAttribute('item');
        parent::addAttribute('produto_id');
        parent::addAttribute('qtd');
        parent::addAttribute('vlr_unitario');
        parent::addAttribute('tes_id');
        parent::addAttribute('vlr_tabela');
        parent::addAttribute('vlr_bruto');
        parent::addAttribute('base_icms');
        parent::addAttribute('aliq_icms');
        parent::addAttribute('vlr_icms');
        parent::addAttribute('base_ipi');
        parent::addAttribute('aliq_ipi');
        parent::addAttribute('vlr_ipi');
        parent::addAttribute('vlr_total');
        parent::addAttribute('vlr_dev');
        parent::addAttribute('qtd_dev');
        parent::addAttribute('perc_desconto');
        parent::addAttribute('vlr_desconto');
        parent::addAttribute('peso');
        parent::addAttribute('pedido_item_id');
        parent::addAttribute('reg_ativo');
        parent::addAttribute('tes');
        parent::addAttribute('estoque');
        parent::addAttribute('financeiro');
        parent::addAttribute('ano');
        parent::addAttribute('mes');
        parent::addAttribute('cliente_id');
        parent::addAttribute('vendedor1_id');
        parent::addAttribute('vendedor2_id');
        parent::addAttribute('dt_emissao');
        parent::addAttribute('cfop');
        parent::addAttribute('perc_comissao');
        parent::addAttribute('comissao');
        parent::addAttribute('comodato');
        parent::addAttribute('tipo');
            
    }

    /**
     * Method set_nota_saida
     * Sample of usage: $var->nota_saida = $object;
     * @param $object Instance of NotaSaida
     */
    public function set_nota_saida(NotaSaida $object)
    {
        $this->nota_saida = $object;
        $this->nota_saida_id = $object->id;
    }

    /**
     * Method get_nota_saida
     * Sample of usage: $var->nota_saida->attribute;
     * @returns NotaSaida instance
     */
    public function get_nota_saida()
    {
    
        // loads the associated object
        if (empty($this->nota_saida))
            $this->nota_saida = new NotaSaida($this->nota_saida_id);
    
        // returns the associated object
        return $this->nota_saida;
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
     * Method set_tipo_entrada_saida
     * Sample of usage: $var->tipo_entrada_saida = $object;
     * @param $object Instance of TipoEntradaSaida
     */
    public function set_tes(TipoEntradaSaida $object)
    {
        $this->tes = $object;
        $this->tes_id = $object->id;
    }

    /**
     * Method get_tes
     * Sample of usage: $var->tes->attribute;
     * @returns TipoEntradaSaida instance
     */
    public function get_tes()
    {
    
        // loads the associated object
        if (empty($this->tes))
            $this->tes = new TipoEntradaSaida($this->tes_id);
    
        // returns the associated object
        return $this->tes;
    }
    /**
     * Method set_cliente
     * Sample of usage: $var->cliente = $object;
     * @param $object Instance of Cliente
     */
    public function set_cliente(Cliente $object)
    {
        $this->cliente = $object;
        $this->cliente_id = $object->id;
    }

    /**
     * Method get_cliente
     * Sample of usage: $var->cliente->attribute;
     * @returns Cliente instance
     */
    public function get_cliente()
    {
    
        // loads the associated object
        if (empty($this->cliente))
            $this->cliente = new Cliente($this->cliente_id);
    
        // returns the associated object
        return $this->cliente;
    }
    /**
     * Method set_vendedor
     * Sample of usage: $var->vendedor = $object;
     * @param $object Instance of Vendedor
     */
    public function set_vendedor1(Vendedor $object)
    {
        $this->vendedor1 = $object;
        $this->vendedor1_id = $object->id;
    }

    /**
     * Method get_vendedor1
     * Sample of usage: $var->vendedor1->attribute;
     * @returns Vendedor instance
     */
    public function get_vendedor1()
    {
    
        // loads the associated object
        if (empty($this->vendedor1))
            $this->vendedor1 = new Vendedor($this->vendedor1_id);
    
        // returns the associated object
        return $this->vendedor1;
    }
    /**
     * Method set_vendedor
     * Sample of usage: $var->vendedor = $object;
     * @param $object Instance of Vendedor
     */
    public function set_vendedor2(Vendedor $object)
    {
        $this->vendedor2 = $object;
        $this->vendedor2_id = $object->id;
    }

    /**
     * Method get_vendedor2
     * Sample of usage: $var->vendedor2->attribute;
     * @returns Vendedor instance
     */
    public function get_vendedor2()
    {
    
        // loads the associated object
        if (empty($this->vendedor2))
            $this->vendedor2 = new Vendedor($this->vendedor2_id);
    
        // returns the associated object
        return $this->vendedor2;
    }

    
}

