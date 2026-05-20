<?php

class ViewClienteSaldoTitulo extends TRecord
{
    const TABLENAME  = 'view_cliente_saldo_titulo';
    const PRIMARYKEY = 'id';
    const IDPOLICY   =  'max'; // {max, serial}

    

    /**
     * Constructor method
     */
    public function __construct($id = NULL, $callObjectLoad = TRUE)
    {
        parent::__construct($id, $callObjectLoad);
        parent::addAttribute('cod_erp');
        parent::addAttribute('razao');
        parent::addAttribute('fantasia');
        parent::addAttribute('vendedor_id');
        parent::addAttribute('vencido');
        parent::addAttribute('aberto');
        parent::addAttribute('tipo');
        parent::addAttribute('status');
        parent::addAttribute('situacao_id');
        parent::addAttribute('saldo');
        parent::addAttribute('quantidade');
        parent::addAttribute('MaiorAtraso');
            
    }

    
}

