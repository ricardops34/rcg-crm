<?php

class NegociacaoTituloReceber extends TRecord
{
    const TABLENAME  = 'negociacao_titulo_receber';
    const PRIMARYKEY = 'id';
    const IDPOLICY   =  'serial'; // {max, serial}

    const CREATEDAT  = 'dt_inclusao';
    const UPDATEDAT  = 'dt_alteracao';

    private Negociacao $negociacao;
    private TituloReceber $titulo_receber;

    

    /**
     * Constructor method
     */
    public function __construct($id = NULL, $callObjectLoad = TRUE)
    {
        parent::__construct($id, $callObjectLoad);
        parent::addAttribute('dt_alteracao');
        parent::addAttribute('negociacao_id');
        parent::addAttribute('dt_inclusao');
        parent::addAttribute('titulo_receber_id');
        parent::addAttribute('vencimento');
        parent::addAttribute('valor');
            
    }

    /**
     * Method set_negociacao
     * Sample of usage: $var->negociacao = $object;
     * @param $object Instance of Negociacao
     */
    public function set_negociacao(Negociacao $object)
    {
        $this->negociacao = $object;
        $this->negociacao_id = $object->id;
    }

    /**
     * Method get_negociacao
     * Sample of usage: $var->negociacao->attribute;
     * @returns Negociacao instance
     */
    public function get_negociacao()
    {
    
        // loads the associated object
        if (empty($this->negociacao))
            $this->negociacao = new Negociacao($this->negociacao_id);
    
        // returns the associated object
        return $this->negociacao;
    }
    /**
     * Method set_titulo_receber
     * Sample of usage: $var->titulo_receber = $object;
     * @param $object Instance of TituloReceber
     */
    public function set_titulo_receber(TituloReceber $object)
    {
        $this->titulo_receber = $object;
        $this->titulo_receber_id = $object->id;
    }

    /**
     * Method get_titulo_receber
     * Sample of usage: $var->titulo_receber->attribute;
     * @returns TituloReceber instance
     */
    public function get_titulo_receber()
    {
    
        // loads the associated object
        if (empty($this->titulo_receber))
            $this->titulo_receber = new TituloReceber($this->titulo_receber_id);
    
        // returns the associated object
        return $this->titulo_receber;
    }

    
}

