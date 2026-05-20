<?php

class ClienteContato extends TRecord
{
    const TABLENAME  = 'cliente_contato';
    const PRIMARYKEY = 'id';
    const IDPOLICY   =  'serial'; // {max, serial}

    const CREATEDAT  = 'dt_inclusao';
    const UPDATEDAT  = 'dt_alteracao';

    private Cliente $cliente;
    private TipoContato $tipo_contato;

    

    /**
     * Constructor method
     */
    public function __construct($id = NULL, $callObjectLoad = TRUE)
    {
        parent::__construct($id, $callObjectLoad);
        parent::addAttribute('cliente_id');
        parent::addAttribute('tipo_contato_id');
        parent::addAttribute('nome');
        parent::addAttribute('telefone');
        parent::addAttribute('email');
        parent::addAttribute('situacao');
        parent::addAttribute('dt_inclusao');
        parent::addAttribute('dt_alteracao');
            
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
     * Method set_tipo_contato
     * Sample of usage: $var->tipo_contato = $object;
     * @param $object Instance of TipoContato
     */
    public function set_tipo_contato(TipoContato $object)
    {
        $this->tipo_contato = $object;
        $this->tipo_contato_id = $object->id;
    }

    /**
     * Method get_tipo_contato
     * Sample of usage: $var->tipo_contato->attribute;
     * @returns TipoContato instance
     */
    public function get_tipo_contato()
    {
    
        // loads the associated object
        if (empty($this->tipo_contato))
            $this->tipo_contato = new TipoContato($this->tipo_contato_id);
    
        // returns the associated object
        return $this->tipo_contato;
    }

    
}

