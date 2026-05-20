<?php

class BlogComunicados extends TRecord
{
    const TABLENAME  = 'blog_comunicados';
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
        parent::addAttribute('filial_id');
        parent::addAttribute('titulo');
        parent::addAttribute('texto');
        parent::addAttribute('data_postagem');
        parent::addAttribute('link_externo');
        parent::addAttribute('link_texto');
        parent::addAttribute('status');
        parent::addAttribute('ordenacao');
        parent::addAttribute('data_validade');
        parent::addAttribute('dt_alteracao');
        parent::addAttribute('dt_inclusao');
        parent::addAttribute('system_user_id');
            
    }

    
}

