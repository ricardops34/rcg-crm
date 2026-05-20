<?php

class MeuClienteFormView extends TPage
{
    public function __construct($param)
    {
        parent::__construct();
        
        $this->html = new THtmlRenderer('app/resources/meu_cliente/MeuClienteFormView.html');

        $replace = array();

        $this->html->enableSection('main', $replace);
        
        parent::add($this->html);
    }
    
    // função executa ao clicar no item de menu
    public function onShow($param = null)
    {
        
    }
}
