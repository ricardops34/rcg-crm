<?php

class ClienteAtualizacao extends TRecord
{
    const TABLENAME  = 'cliente_atualizacao';
    const PRIMARYKEY = 'id';
    const IDPOLICY   =  'serial'; // {max, serial}
    const CACHECONTROL  = 'TAPCache';

    const CREATEDAT  = 'dt_inclusao';
    const UPDATEDAT  = 'dt_alteracao';

    private Cliente $cliente;
    private SituacaoCadastral $situacao_cadastral;
    private Cnae $atividade_principal;
    private Pais $pais;

    

    /**
     * Constructor method
     */
    public function __construct($id = NULL, $callObjectLoad = TRUE)
    {
        parent::__construct($id, $callObjectLoad);
        parent::addAttribute('cliente_id');
        parent::addAttribute('situacao_cadastral_id');
        parent::addAttribute('atividade_principal_id');
        parent::addAttribute('razao');
        parent::addAttribute('fantasia');
        parent::addAttribute('tipo_logradouro');
        parent::addAttribute('logradouro');
        parent::addAttribute('numero');
        parent::addAttribute('complemento');
        parent::addAttribute('bairro');
        parent::addAttribute('municipio_id');
        parent::addAttribute('cep');
        parent::addAttribute('telefone1');
        parent::addAttribute('telefone2');
        parent::addAttribute('fax');
        parent::addAttribute('celular');
        parent::addAttribute('celular2');
        parent::addAttribute('contato');
        parent::addAttribute('cnpj_cpf');
        parent::addAttribute('ie');
        parent::addAttribute('im');
        parent::addAttribute('email');
        parent::addAttribute('site');
        parent::addAttribute('latitude');
        parent::addAttribute('longitude');
        parent::addAttribute('dt_alteracao');
        parent::addAttribute('dt_inclusao');
        parent::addAttribute('data_situacao_especial');
        parent::addAttribute('situacao_especial');
        parent::addAttribute('atualizado_em');
        parent::addAttribute('data_situacao_cadastral');
        parent::addAttribute('simples');
        parent::addAttribute('tipo');
        parent::addAttribute('pais_id');
        parent::addAttribute('mei');
        parent::addAttribute('porte');
        parent::addAttribute('natureza_juridica');
        parent::addAttribute('capital_social');
            
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
     * Method set_situacao_cadastral
     * Sample of usage: $var->situacao_cadastral = $object;
     * @param $object Instance of SituacaoCadastral
     */
    public function set_situacao_cadastral(SituacaoCadastral $object)
    {
        $this->situacao_cadastral = $object;
        $this->situacao_cadastral_id = $object->id;
    }

    /**
     * Method get_situacao_cadastral
     * Sample of usage: $var->situacao_cadastral->attribute;
     * @returns SituacaoCadastral instance
     */
    public function get_situacao_cadastral()
    {
    
        // loads the associated object
        if (empty($this->situacao_cadastral))
            $this->situacao_cadastral = new SituacaoCadastral($this->situacao_cadastral_id);
    
        // returns the associated object
        return $this->situacao_cadastral;
    }
    /**
     * Method set_cnae
     * Sample of usage: $var->cnae = $object;
     * @param $object Instance of Cnae
     */
    public function set_atividade_principal(Cnae $object)
    {
        $this->atividade_principal = $object;
        $this->atividade_principal_id = $object->id;
    }

    /**
     * Method get_atividade_principal
     * Sample of usage: $var->atividade_principal->attribute;
     * @returns Cnae instance
     */
    public function get_atividade_principal()
    {
    
        // loads the associated object
        if (empty($this->atividade_principal))
            $this->atividade_principal = new Cnae($this->atividade_principal_id);
    
        // returns the associated object
        return $this->atividade_principal;
    }
    /**
     * Method set_pais
     * Sample of usage: $var->pais = $object;
     * @param $object Instance of Pais
     */
    public function set_pais(Pais $object)
    {
        $this->pais = $object;
        $this->pais_id = $object->id;
    }

    /**
     * Method get_pais
     * Sample of usage: $var->pais->attribute;
     * @returns Pais instance
     */
    public function get_pais()
    {
    
        // loads the associated object
        if (empty($this->pais))
            $this->pais = new Pais($this->pais_id);
    
        // returns the associated object
        return $this->pais;
    }

    
}

