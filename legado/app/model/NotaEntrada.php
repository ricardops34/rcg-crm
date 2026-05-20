<?php

class NotaEntrada extends TRecord
{
    const TABLENAME  = 'nota_entrada';
    const PRIMARYKEY = 'id';
    const IDPOLICY   =  'serial'; // {max, serial}

    const CREATEDAT  = 'dt_inclusao';
    const UPDATEDAT  = 'dt_alteracao';

    private Fornecedor $fornecedor;
    private Cliente $cliente;
    private Vendedor $vendedor1;

    

    /**
     * Constructor method
     */
    public function __construct($id = NULL, $callObjectLoad = TRUE)
    {
        parent::__construct($id, $callObjectLoad);
        parent::addAttribute('filial_id');
        parent::addAttribute('cliente_id');
        parent::addAttribute('fornecedor_id');
        parent::addAttribute('nota_fiscal');
        parent::addAttribute('serie_fiscal');
        parent::addAttribute('especie_fiscal');
        parent::addAttribute('condicao_id');
        parent::addAttribute('numero_titulo');
        parent::addAttribute('prefixo_titulo');
        parent::addAttribute('dt_emissao');
        parent::addAttribute('tipo');
        parent::addAttribute('comodato');
        parent::addAttribute('vlr_bruto');
        parent::addAttribute('vlr_icms');
        parent::addAttribute('base_icms');
        parent::addAttribute('vlr_ipi');
        parent::addAttribute('base_ipi');
        parent::addAttribute('vlr_mercadoria');
        parent::addAttribute('vlr_desconto');
        parent::addAttribute('vlr_comodato');
        parent::addAttribute('vlr_itens');
        parent::addAttribute('vlr_devolucao');
        parent::addAttribute('transportadora');
        parent::addAttribute('tp_frete');
        parent::addAttribute('vlr_frete');
        parent::addAttribute('vendedor1_id');
        parent::addAttribute('vendedor2_id');
        parent::addAttribute('chave_nfe');
        parent::addAttribute('dt_nfe');
        parent::addAttribute('hr_nfe');
        parent::addAttribute('mensagem_nf');
        parent::addAttribute('numero_origem');
        parent::addAttribute('serie_origem');
        parent::addAttribute('intermediador');
        parent::addAttribute('reg_ativo');
        parent::addAttribute('mes');
        parent::addAttribute('ano');
        parent::addAttribute('system_unit_id');
        parent::addAttribute('date_danfe');
        parent::addAttribute('dt_inclusao');
        parent::addAttribute('dt_alteracao');
            
    }

    /**
     * Method set_fornecedor
     * Sample of usage: $var->fornecedor = $object;
     * @param $object Instance of Fornecedor
     */
    public function set_fornecedor(Fornecedor $object)
    {
        $this->fornecedor = $object;
        $this->fornecedor_id = $object->id;
    }

    /**
     * Method get_fornecedor
     * Sample of usage: $var->fornecedor->attribute;
     * @returns Fornecedor instance
     */
    public function get_fornecedor()
    {
    
        // loads the associated object
        if (empty($this->fornecedor))
            $this->fornecedor = new Fornecedor($this->fornecedor_id);
    
        // returns the associated object
        return $this->fornecedor;
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
     * Method getNotaEntradaItems
     */
    public function getNotaEntradaItems()
    {
        $criteria = new TCriteria;
        $criteria->add(new TFilter('nota_entrada_id', '=', $this->id));
        return NotaEntradaItem::getObjects( $criteria );
    }

    public function set_nota_entrada_item_nota_entrada_to_string($nota_entrada_item_nota_entrada_to_string)
    {
        if(is_array($nota_entrada_item_nota_entrada_to_string))
        {
            $values = NotaEntrada::where('id', 'in', $nota_entrada_item_nota_entrada_to_string)->getIndexedArray('id', 'id');
            $this->nota_entrada_item_nota_entrada_to_string = implode(', ', $values);
        }
        else
        {
            $this->nota_entrada_item_nota_entrada_to_string = $nota_entrada_item_nota_entrada_to_string;
        }

        $this->vdata['nota_entrada_item_nota_entrada_to_string'] = $this->nota_entrada_item_nota_entrada_to_string;
    }

    public function get_nota_entrada_item_nota_entrada_to_string()
    {
        if(!empty($this->nota_entrada_item_nota_entrada_to_string))
        {
            return $this->nota_entrada_item_nota_entrada_to_string;
        }
    
        $values = NotaEntradaItem::where('nota_entrada_id', '=', $this->id)->getIndexedArray('nota_entrada_id','{nota_entrada->id}');
        return implode(', ', $values);
    }

    
}

