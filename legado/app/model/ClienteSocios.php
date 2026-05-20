<?php

class ClienteSocios extends TRecord
{
    const TABLENAME  = 'cliente_socios';
    const PRIMARYKEY = 'id';
    const IDPOLICY   =  'serial'; // {max, serial}

    const CREATEDAT  = 'dt_inclusao';
    const UPDATEDAT  = 'dt_alteracao';

    

    /**
     * Constructor method
     */
    public function __construct($id = NULL, $callObjectLoad = TRUE)
    {
        parent::__construct($id, $callObjectLoad);
        parent::addAttribute('cliente_id');
        parent::addAttribute('nome');
        parent::addAttribute('tipo');
        parent::addAttribute('data_entrada');
        parent::addAttribute('faixa_etaria');
        parent::addAttribute('qualificacao_socio');
        parent::addAttribute('descricao');
        parent::addAttribute('cpf_cnpj_socio');
        parent::addAttribute('dt_alteracao');
        parent::addAttribute('dt_inclusao');
            
    }

    
}

