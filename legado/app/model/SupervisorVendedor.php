<?php

class SupervisorVendedor extends TRecord
{
    const TABLENAME  = 'supervisor_vendedor';
    const PRIMARYKEY = 'id';
    const IDPOLICY   =  'serial'; // {max, serial}

    const CREATEDAT  = 'dt_inclusao';
    const UPDATEDAT  = 'dt_alteracao';

    private Vendedor $vendedor;
    private Supervisor $supervisor;

    /**
     * Constructor method
     */
    public function __construct($id = NULL, $callObjectLoad = TRUE)
    {
        parent::__construct($id, $callObjectLoad);
        parent::addAttribute('vendedor_id');
        parent::addAttribute('supervisor_id');
        parent::addAttribute('sistema');
        parent::addAttribute('dt_alteracao');
        parent::addAttribute('dt_inclusao');
    
    }

    /**
     * Method set_vendedor
     * Sample of usage: $var->vendedor = $object;
     * @param $object Instance of Vendedor
     */
    public function set_vendedor(Vendedor $object)
    {
        $this->vendedor = $object;
        $this->vendedor_id = $object->id;
    }

    /**
     * Method get_vendedor
     * Sample of usage: $var->vendedor->attribute;
     * @returns Vendedor instance
     */
    public function get_vendedor()
    {
    
        // loads the associated object
        if (empty($this->vendedor))
            $this->vendedor = new Vendedor($this->vendedor_id);
    
        // returns the associated object
        return $this->vendedor;
    }
    /**
     * Method set_supervisor
     * Sample of usage: $var->supervisor = $object;
     * @param $object Instance of Supervisor
     */
    public function set_supervisor(Supervisor $object)
    {
        $this->supervisor = $object;
        $this->supervisor_id = $object->id;
    }

    /**
     * Method get_supervisor
     * Sample of usage: $var->supervisor->attribute;
     * @returns Supervisor instance
     */
    public function get_supervisor()
    {
    
        // loads the associated object
        if (empty($this->supervisor))
            $this->supervisor = new Supervisor($this->supervisor_id);
    
        // returns the associated object
        return $this->supervisor;
    }

    public function get_clientes_ativos()
    {
        $nClientes = 0 ;

        $oClientes = Cliente::where('vendedor_id',  '=', $this->vendedor_id)
                     ->where('status',  '!=',  'B')
                     ->where('situacao_cadastral_id',  '=',  1)
                     ->orderBy('id')
                     ->load();    

        if ($oClientes)
            {
                foreach($oClientes  as $oCliente )
                {
                     $nClientes += 1;
                }
            }
        return $nClientes;
    }

    public function get_clientes_bloqueados()
    {
        $nClientes = 0 ;

        $oClientes = Cliente::where('vendedor_id',  '=', $this->vendedor_id)
                     ->where('status',  '=',  'B')
                     ->where('situacao_cadastral_id',  'NOT IN', array(1,4,5))
                     ->orderBy('id')
                     ->load();    

        if ($oClientes)
            {
                foreach($oClientes  as $oCliente )
                {
                     $nClientes += 1;
                }
            }
        return $nClientes;
    }

    
}

