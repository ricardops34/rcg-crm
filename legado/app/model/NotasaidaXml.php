<?php

class NotasaidaXml extends TRecord
{
    const TABLENAME  = 'notasaida_xml';
    const PRIMARYKEY = 'id';
    const IDPOLICY   =  'serial'; // {max, serial}

    private NotaSaida $nota_saida;

    

    /**
     * Constructor method
     */
    public function __construct($id = NULL, $callObjectLoad = TRUE)
    {
        parent::__construct($id, $callObjectLoad);
        parent::addAttribute('nota_saida_id');
        parent::addAttribute('xml_sig');
        parent::addAttribute('xml_tss');
        parent::addAttribute('xml_cancelamento');
        parent::addAttribute('nota_fiscal');
        parent::addAttribute('serie_fiscal');
        parent::addAttribute('chave');
        parent::addAttribute('protocolo');
        parent::addAttribute('modelo');
        parent::addAttribute('destinatario');
        parent::addAttribute('remetente');
        parent::addAttribute('situcao');
        parent::addAttribute('situcao_cancelamento');
        parent::addAttribute('situcao_email');
        parent::addAttribute('email');
        parent::addAttribute('data_nfe');
        parent::addAttribute('hora_nfe');
        parent::addAttribute('ano');
        parent::addAttribute('mes');
            
    }

    /**
     * Method set_nota_saida
     * Sample of usage: $var->nota_saida = $object;
     * @param $object Instance of NotaSaida
     */
    public function set_nota_saida(NotaSaida $object)
    {
        $this->nota_saida = $object;
        $this->nota_saida_id = $object->id;
    }

    /**
     * Method get_nota_saida
     * Sample of usage: $var->nota_saida->attribute;
     * @returns NotaSaida instance
     */
    public function get_nota_saida()
    {
    
        // loads the associated object
        if (empty($this->nota_saida))
            $this->nota_saida = new NotaSaida($this->nota_saida_id);
    
        // returns the associated object
        return $this->nota_saida;
    }

    
}

