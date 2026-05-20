<?php

class OrcamentoProximoEstado extends TRecord
{
    const TABLENAME  = 'orcamento_proximo_estado';
    const PRIMARYKEY = 'id';
    const IDPOLICY   =  'serial'; // {max, serial}

    const CREATEDAT  = 'dt_inclusao';
    const UPDATEDAT  = 'dt_alteracao';

    private OrcamentoEstado $orcamento_estado;
    private OrcamentoEstado $proximo_estado;

    

    /**
     * Constructor method
     */
    public function __construct($id = NULL, $callObjectLoad = TRUE)
    {
        parent::__construct($id, $callObjectLoad);
        parent::addAttribute('orcamento_estado_id');
        parent::addAttribute('proximo_estado_id');
        parent::addAttribute('dt_alteracao');
        parent::addAttribute('dt_inclusao');
            
    }

    /**
     * Method set_orcamento_estado
     * Sample of usage: $var->orcamento_estado = $object;
     * @param $object Instance of OrcamentoEstado
     */
    public function set_orcamento_estado(OrcamentoEstado $object)
    {
        $this->orcamento_estado = $object;
        $this->orcamento_estado_id = $object->id;
    }

    /**
     * Method get_orcamento_estado
     * Sample of usage: $var->orcamento_estado->attribute;
     * @returns OrcamentoEstado instance
     */
    public function get_orcamento_estado()
    {
    
        // loads the associated object
        if (empty($this->orcamento_estado))
            $this->orcamento_estado = new OrcamentoEstado($this->orcamento_estado_id);
    
        // returns the associated object
        return $this->orcamento_estado;
    }
    /**
     * Method set_orcamento_estado
     * Sample of usage: $var->orcamento_estado = $object;
     * @param $object Instance of OrcamentoEstado
     */
    public function set_proximo_estado(OrcamentoEstado $object)
    {
        $this->proximo_estado = $object;
        $this->proximo_estado_id = $object->id;
    }

    /**
     * Method get_proximo_estado
     * Sample of usage: $var->proximo_estado->attribute;
     * @returns OrcamentoEstado instance
     */
    public function get_proximo_estado()
    {
    
        // loads the associated object
        if (empty($this->proximo_estado))
            $this->proximo_estado = new OrcamentoEstado($this->proximo_estado_id);
    
        // returns the associated object
        return $this->proximo_estado;
    }

    
}

