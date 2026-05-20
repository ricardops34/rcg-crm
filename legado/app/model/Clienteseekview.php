<?php

class Clienteseekview extends TRecord
{
    const TABLENAME  = 'clienteseekview';
    const PRIMARYKEY = 'cod_erp';
    const IDPOLICY   =  'max'; // {max, serial}

    /**
     * Constructor method
     */
    public function __construct($id = NULL, $callObjectLoad = TRUE)
    {
        parent::__construct($id, $callObjectLoad);
        parent::addAttribute('razao');
        parent::addAttribute('fantasia');
        parent::addAttribute('telefone');
        parent::addAttribute('email');
        parent::addAttribute('situacao');
        parent::addAttribute('vendedor_id');
        parent::addAttribute('cliente_id');
        parent::addAttribute('cnpj_cpf');
        parent::addAttribute('ultima_visita');
        parent::addAttribute('data_cadastro');
        parent::addAttribute('condicao_pagamento_id');
        parent::addAttribute('tabela_preco_id');
        parent::addAttribute('seguimento_id');
        parent::addAttribute('municipio_id');
    
    }

    public function get_estado_id()
    {
    
        // loads the associated object
        if (empty($this->municipio))
            $this->municipio = new Municipio($this->municipio_id);
    
        // returns the associated object
        return $this->municipio->estado_id;
    }

    public function get_vendedor()
    {
    
        $cRet = $this->vendedor_id;
        $oVendedor = new Vendedor($this->vendedor_id);

        if($oVendedor){
            $cRet = $oVendedor->nome_reduzido;
        }

        return $cRet;
    }

    public function get_tabela_preco()
    {
    
    
        $cRet = $this->tabela_preco_id;
        $oTabela_preco = new TabelaPreco($this->tabela_preco_id);

        if($oTabela_preco){
            $cRet = $oTabela_preco->descricao;
        }

        return $cRet;
    }

            
}

