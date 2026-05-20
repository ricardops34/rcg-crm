<?php

class VendaMesCliente extends TRecord
{
    const TABLENAME  = 'venda_mes_cliente';
    const PRIMARYKEY = 'id';
    const IDPOLICY   =  'serial'; // {max, serial}

    private Cliente $cliente;

    /**
     * Constructor method
     */
    public function __construct($id = NULL, $callObjectLoad = TRUE)
    {
        parent::__construct($id, $callObjectLoad);
        parent::addAttribute('filial_id');
        parent::addAttribute('empresa_id');
        parent::addAttribute('cliente_id');
        parent::addAttribute('ano');
        parent::addAttribute('janeiro');
        parent::addAttribute('fevereiro');
        parent::addAttribute('mes');
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
        parent::addAttribute('cliente_nome');
    
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

    /*
    public function onBeforeStore($object)
    {
        $id = $object->cliente_id;
        $oCliente = Cliente::where('id', '=', $id)
                ->first();
    
        if($oCliente){
            $cNome = ltrim(rtrim(ltrim($oCliente->fantasia)));
            if($oCliente->tipo == 'F'){
                if(!empty($oCliente->razao)){
                    $cNome = rtrim(ltrim($oCliente->razao));
                }
            }    
            $object->cliente_nome = $cNome;
        }
    
    }
    */
    
}

