<?php

class PivotVendas extends TRecord
{
    const TABLENAME  = 'pivot_vendas';
    const PRIMARYKEY = 'vendedor_id';
    const IDPOLICY   =  'max'; // {max, serial}

    /**
     * Constructor method
     */
    public function __construct($id = NULL, $callObjectLoad = TRUE)
    {
        parent::__construct($id, $callObjectLoad);
        parent::addAttribute('vendedor_cod_erp');
        parent::addAttribute('vendedor_nome');
        parent::addAttribute('cliente_cod_erp');
        parent::addAttribute('cliente_razao');
        parent::addAttribute('ano');
        parent::addAttribute('JANEIRO');
        parent::addAttribute('FEVEREIRO');
        parent::addAttribute('MARCO');
        parent::addAttribute('ABRIL');
        parent::addAttribute('MAIO');
        parent::addAttribute('JUNHO');
        parent::addAttribute('JULHO');
        parent::addAttribute('AGOSTO');
        parent::addAttribute('SETEMBRO');
        parent::addAttribute('OUTUBRO');
        parent::addAttribute('NOVEMBRO');
        parent::addAttribute('DEZEMBRO');
        parent::addAttribute('cliente_id');
    
    }

    public function get_data_cadastro()
    {

        TTransaction::open('erp_online');
        $oCliente = Cliente::where('id', '=', $this->cliente_id)->first();
        TTransaction::close();
        if($oCliente){
            return $oCliente->data_cadastro;
        }else{
            return null;
        }
    
    }
  
    
}

