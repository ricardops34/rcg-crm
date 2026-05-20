<?php

class ViewAnoBase extends TRecord
{
    const TABLENAME  = 'view_ano_base';
    const PRIMARYKEY = 'ano';
    const IDPOLICY   =  'max'; // {max, serial}

    

    /**
     * Constructor method
     */
    public function __construct($id = NULL, $callObjectLoad = TRUE)
    {
        parent::__construct($id, $callObjectLoad);
        parent::addAttribute('01');
        parent::addAttribute('02');
        parent::addAttribute('03');
        parent::addAttribute('04');
        parent::addAttribute('05');
        parent::addAttribute('06');
        parent::addAttribute('07');
        parent::addAttribute('08');
        parent::addAttribute('09');
        parent::addAttribute('10');
        parent::addAttribute('11');
        parent::addAttribute('12');
            
    }

    
}

