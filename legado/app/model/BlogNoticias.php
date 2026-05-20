<?php

class BlogNoticias extends TRecord
{
    const TABLENAME  = 'blog_noticias';
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
        parent::addAttribute('titulo');
        parent::addAttribute('texto');
        parent::addAttribute('data_postagem');
        parent::addAttribute('imagem');
        parent::addAttribute('autor');
        parent::addAttribute('status');
        parent::addAttribute('dt_alteracao');
        parent::addAttribute('dt_inclusao');
        parent::addAttribute('data_validade');
        parent::addAttribute('system_user_id');
            
    }

    
}

