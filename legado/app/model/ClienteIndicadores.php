<?php

class ClienteIndicadores extends TRecord
{
    const TABLENAME  = 'cliente_indicadores';
    const PRIMARYKEY = 'id';
    const IDPOLICY   =  'max'; // {max, serial}

    

    /**
     * Constructor method
     */
    public function __construct($id = NULL, $callObjectLoad = TRUE)
    {
        parent::__construct($id, $callObjectLoad);
        parent::addAttribute('cliente_codigo');
        parent::addAttribute('cliente_fantasia');
        parent::addAttribute('cliente_municipio');
        parent::addAttribute('cliente_estado');
        parent::addAttribute('cliente_status');
        parent::addAttribute('vendedor_codigo');
        parent::addAttribute('vendedor_nome');
        parent::addAttribute('cliente_data_cadastro');
        parent::addAttribute('cliente_primeira_compra');
        parent::addAttribute('cliente_ultima_compra');
        parent::addAttribute('dias_sem_compra');
        parent::addAttribute('vendedor_id');
            
    }

    
}

