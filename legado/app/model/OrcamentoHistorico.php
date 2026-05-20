<?php

class OrcamentoHistorico extends TRecord
{
    const TABLENAME  = 'orcamento_historico';
    const PRIMARYKEY = 'id';
    const IDPOLICY   =  'serial'; // {max, serial}

    const CREATEDAT  = 'dt_inclusao';
    const UPDATEDAT  = 'dt_alteracao';

    private Orcamento $orcamento;
    private OrcamentoEstado $orcamento_estado;
    private OrcamentoEstado $orcamento_proximo_estado;

    

    /**
     * Constructor method
     */
    public function __construct($id = NULL, $callObjectLoad = TRUE)
    {
        parent::__construct($id, $callObjectLoad);
        parent::addAttribute('orcamento_id');
        parent::addAttribute('orcamento_estado_id');
        parent::addAttribute('observacao');
        parent::addAttribute('orcamento_proximo_estado_id');
        parent::addAttribute('system_user_id');
        parent::addAttribute('dt_alteracao');
        parent::addAttribute('dt_inclusao');
        parent::addAttribute('dt_evento');
            
    }

    /**
     * Method set_orcamento
     * Sample of usage: $var->orcamento = $object;
     * @param $object Instance of Orcamento
     */
    public function set_orcamento(Orcamento $object)
    {
        $this->orcamento = $object;
        $this->orcamento_id = $object->id;
    }

    /**
     * Method get_orcamento
     * Sample of usage: $var->orcamento->attribute;
     * @returns Orcamento instance
     */
    public function get_orcamento()
    {
    
        // loads the associated object
        if (empty($this->orcamento))
            $this->orcamento = new Orcamento($this->orcamento_id);
    
        // returns the associated object
        return $this->orcamento;
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
    public function set_orcamento_proximo_estado(OrcamentoEstado $object)
    {
        $this->orcamento_proximo_estado = $object;
        $this->orcamento_proximo_estado_id = $object->id;
    }

    /**
     * Method get_orcamento_proximo_estado
     * Sample of usage: $var->orcamento_proximo_estado->attribute;
     * @returns OrcamentoEstado instance
     */
    public function get_orcamento_proximo_estado()
    {
    
        // loads the associated object
        if (empty($this->orcamento_proximo_estado))
            $this->orcamento_proximo_estado = new OrcamentoEstado($this->orcamento_proximo_estado_id);
    
        // returns the associated object
        return $this->orcamento_proximo_estado;
    }

    
}

