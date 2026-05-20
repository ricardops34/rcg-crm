<?php

class ProdutoSite extends TPage
{
public function __construct( $param )
    {
        parent::__construct();
        
        TPage::include_css('https://maxcdn.bootstrapcdn.com/font-awesome/4.3.0/css/font-awesome.min.css');
        TPage::include_css('app/lib/include/css/ProdutoSite.css'); 
        
        $template = new THtmlRenderer('app/resources/ProdutoSite.html');
        $template->enableSection('main');


        //TPage::include_js('https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js');
        parent::add($template);
    }
    
    // função executa ao clicar no item de menu
    public function onShow($param = null)
    {
        
    }
}
