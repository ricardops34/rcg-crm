<?php

class CalendarioOrcamento extends TRecord
{
    const TABLENAME  = 'calendario_orcamento';
    const PRIMARYKEY = 'id';
    const IDPOLICY   =  'serial'; // {max, serial}

    private Orcamento $orcamento;

    

    /**
     * Constructor method
     */
    public function __construct($id = NULL, $callObjectLoad = TRUE)
    {
        parent::__construct($id, $callObjectLoad);
        parent::addAttribute('orcamento_id');
        parent::addAttribute('horario_inicial');
        parent::addAttribute('horario_final');
        parent::addAttribute('titulo');
        parent::addAttribute('cor');
        parent::addAttribute('observacao');
            
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

    
}

