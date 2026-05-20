<?php

class BlogAniversarios extends TRecord
{
    const TABLENAME  = 'blog_aniversarios';
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
        parent::addAttribute('nome');
        parent::addAttribute('filial_id');
        parent::addAttribute('dia');
        parent::addAttribute('mes');
        parent::addAttribute('dt_alteracao');
        parent::addAttribute('dt_inclusao');
        parent::addAttribute('status');
        parent::addAttribute('system_user_id');
            
    }

    
}

