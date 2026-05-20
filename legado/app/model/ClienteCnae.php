<?php

class ClienteCnae extends TRecord
{
    const TABLENAME  = 'cliente_cnae';
    const PRIMARYKEY = 'id';
    const IDPOLICY   =  'serial'; // {max, serial}

    const CREATEDAT  = 'dt_inclusao';
    const UPDATEDAT  = 'dt_alteracao';

    private Cnae $cnae;

    

    /**
     * Constructor method
     */
    public function __construct($id = NULL, $callObjectLoad = TRUE)
    {
        parent::__construct($id, $callObjectLoad);
        parent::addAttribute('cliente_id');
        parent::addAttribute('cnae_id');
        parent::addAttribute('dt_alteracao');
        parent::addAttribute('dt_inclusao');
            
    }

    /**
     * Method set_cnae
     * Sample of usage: $var->cnae = $object;
     * @param $object Instance of Cnae
     */
    public function set_cnae(Cnae $object)
    {
        $this->cnae = $object;
        $this->cnae_id = $object->id;
    }

    /**
     * Method get_cnae
     * Sample of usage: $var->cnae->attribute;
     * @returns Cnae instance
     */
    public function get_cnae()
    {
    
        // loads the associated object
        if (empty($this->cnae))
            $this->cnae = new Cnae($this->cnae_id);
    
        // returns the associated object
        return $this->cnae;
    }

    
}

