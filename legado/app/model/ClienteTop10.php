<?php

class ClienteTop10 extends TRecord
{
    const TABLENAME  = 'cliente_top10';
    const PRIMARYKEY = 'id';
    const IDPOLICY   =  'max'; // {max, serial}

    /**
     * Constructor method
     */
    public function __construct($id = NULL, $callObjectLoad = TRUE)
    {
        parent::__construct($id, $callObjectLoad);
        parent::addAttribute('cliente_id');
        parent::addAttribute('vendedor1_id');
        parent::addAttribute('mes');
        parent::addAttribute('ano');
        parent::addAttribute('vlr_total');
        parent::addAttribute('nota_fiscal');
    
    }

  
    public function set_cliente(Cliente $object)
    {
        $this->cliente = $object;
        $this->cliente_id = $object->id;
    }

    public function get_cliente()
    {
    
        if (empty($this->cliente))
            $this->cliente = new Cliente($this->cliente_id);

        return $this->cliente;
    }

    public function set_vendedor(Vendedor $object)
    {
        $this->vendedor = $object;
        $this->vendedor1_id = $object->id;
    }

    public function get_vendedor()
    {
    
        if (empty($this->vendedor))
            $this->vendedor = new Cliente($this->vendedor1_id);
    
        return $this->vendedor;
    }
  

}

