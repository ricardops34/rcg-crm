<?php

class ViewClienteVendedor extends TRecord
{
    const TABLENAME  = 'view_cliente_vendedor';
    const PRIMARYKEY = 'cliente_id';
    const IDPOLICY   =  'max'; // {max, serial}

    

    /**
     * Constructor method
     */
    public function __construct($id = NULL, $callObjectLoad = TRUE)
    {
        parent::__construct($id, $callObjectLoad);
        parent::addAttribute('vendedor_id');
        parent::addAttribute('cliente_situacao');
        parent::addAttribute('cliente_carteira');
        parent::addAttribute('cliente_ult_visita');
        parent::addAttribute('cliente_ult_compra');
        parent::addAttribute('cliente_ult_atendimento');
        parent::addAttribute('cliente_dt_cadastro');
        parent::addAttribute('cliente_cod_erp');
        parent::addAttribute('cliente_razao');
        parent::addAttribute('vendedor_cod_erp');
        parent::addAttribute('vendedor_nome');
            
    }

    
}

