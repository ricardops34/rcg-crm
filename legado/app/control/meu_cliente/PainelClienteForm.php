<?php

class PainelClienteForm extends TPage
{
    protected $form;
    private $formFields = [];
    private static $database = '';
    private static $activeRecord = '';
    private static $primaryKey = '';
    private static $formName = 'form_PainelClienteForm';

    /**
     * Form constructor
     * @param $param Request
     */
    public function __construct( $param = null)
    {
        parent::__construct();

        if(!empty($param['target_container']))
        {
            $this->adianti_target_container = $param['target_container'];
        }

        // creates the form
        $this->form = new BootstrapFormBuilder(self::$formName);
        // define the form title
        $this->form->setFormTitle("");

        if(!TSession::getValue('cliente_logado'))
        {
            new TMessage('info', 'Permissão negada! ', new TAction(['LoginClienteForm', 'onShow']));
            return false;
        }

        $rcg_logo = new TImage('http://rcgdist.com.br/sites/default/files/logo-rodape_5.png');
        $cabecalho = new BElement('h1');
        $centro = new BPageContainer();
        $button_meus_dados = new TButton('button_meus_dados');
        $button_financeiro = new TButton('button_financeiro');
        $button_notas = new TButton('button_notas');
        $button_sair = new TButton('button_sair');
        $section = new BElement('section');


        $centro->setId('centro');
        $centro->setSize('100%');
        $section->setSize('100%', 80);
        $cabecalho->setSize('100%', 80);

        $button_sair->addStyleClass('btnmeucliente');
        $button_notas->addStyleClass('btnmeucliente');
        $button_meus_dados->addStyleClass('btnmeucliente');
        $button_financeiro->addStyleClass('btnmeucliente');

        $button_meus_dados->setImage('fas:user #000000');
        $button_sair->setImage('fas:door-closed #000000');
        $button_notas->setImage('fas:align-justify #000000');
        $button_financeiro->setImage('fas:dollar-sign #000000');

        $button_sair->setAction(new TAction([$this, 'onSair']), "Sair");
        $centro->setAction(new TAction(['ClientePublicaFormView', 'onShow'], $param));
        $button_notas->setAction(new TAction(['PainelClienteForm', 'onShow'],["target_container" => "center"]), "Notas");
        $button_financeiro->setAction(new TAction(['PainelClienteForm', 'onShow'],["target_container" => "center"]), "Financeiro");
        $button_meus_dados->setAction(new TAction(['PainelClienteForm', 'onShow'],["target_container" => "centro","key" => TSession::getValue('cliente_id')]), "Meus dados");

        $rcg_logo->width = '60%';
        $rcg_logo->height = 'auto';
        $cabecalho->style = "cliente";
        $section->style = "list-group";

        $loadingContainer = new TElement('div');
        $loadingContainer->style = 'text-align:center; padding:50px';

        $icon = new TElement('i');
        $icon->class = 'fas fa-spinner fa-spin fa-3x';

        $loadingContainer->add($icon);
        $loadingContainer->add('<br>Carregando');

        $centro->add($loadingContainer);

        $this->rcg_logo = $rcg_logo;
        $this->cabecalho = $cabecalho;
        $this->centro = $centro;
        $this->section = $section;

        $id = TSession::getValue('cliente_id');

        if($id){
            TTransaction::open('erp_online');

            $oCliente = Cliente::find( $id );

            if($oCliente){

                $button_meus_dados->setAction(new TAction(['PainelClienteForm', 'onShow'],['key' => $oCliente->id]), "Meus dados");

                $cNome = "Bem vindo, ".$oCliente->razao;
                $this->cabecalho->add($cNome);
                $this->cabecalho->style = 'text-align: center';
            }

            TTransaction::close();
        }
        /*
        $btngroupbotoes  = '
          <a href="#" class="list-group-item list-group-item-action">A simple default list group item</a>
          <a href="#" class="list-group-item list-group-item-action list-group-item-primary">A simple primary list group item</a>
          <a href="#" class="list-group-item list-group-item-action list-group-item-secondary">A simple secondary list group item</a>
          <a href="#" class="list-group-item list-group-item-action list-group-item-success">A simple success list group item</a>
          <a href="#" class="list-group-item list-group-item-action list-group-item-danger">A simple danger list group item</a>
          <a href="#" class="list-group-item list-group-item-action list-group-item-warning">A simple warning list group item</a>
          <a href="#" class="list-group-item list-group-item-action list-group-item-info">A simple info list group item</a>
          <a href="#" class="list-group-item list-group-item-action list-group-item-light">A simple light list group item</a>
          <a href="#" class="list-group-item list-group-item-action list-group-item-dark">A simple dark list group item</a>
        ';
        $btngroupbotoes  = '<button type="button" class="btn btn-primary">1</button>
            <button type="button" class="btn btn-primary">2</button>';
        $btngroup->add($btngroupbotoes);
        */

        /*
        <div class="list-group">
        </div>
        */
        /*
        $menu_ui = new TElement('ul');

        $i_meusdados = new TElement('i');
        $i_meusdados->class = "fas fa-user";
        $i_meusdados->add(' Meus dados');

        $a_meusdados = new BElement('a');
        $a_meusdados->href = '#';
        $a_meusdados->add($i_meusdados);

        $li_meusdados = new BElement('li');
        $li_meusdados->class="nav-item";
        //$li_meusdados->style="height: 65.625px;";
        $li_meusdados->add($a_meusdados);

        $i_financeiro = new TElement('i');
        $i_financeiro->class = "fas fa-dollar-sign";
        $i_financeiro->add(' Financeiro');

        $a_financeiro = new BElement('a');
        $a_financeiro->href = '#';
        $a_financeiro->add($i_financeiro);

        $li_financeiros = new BElement('li');
        $li_financeiros->class="nav-item";
        //$li_financeiros->style="height: 65.625px;";
        $li_financeiros->add($a_financeiro);

        $i_sair = new TElement('i');
        $i_sair->class = "fas fa-door-closed";
        $i_sair->add(' Sair');

        $a_sair = new BElement('a');
        $a_sair->href = '#';
        $a_sair->add($i_sair);

        $li_sair = new BElement('li');
        $li_sair->class="nav-item";
        //$li_sair->style="height: 65.625px;";
        $li_sair->add($a_sair);

        $menu_ui->class="nav flex-column";

        $menu_ui->add($li_meusdados);
        $menu_ui->add($li_financeiros);
        $menu_ui->add($li_sair);

        $this->section->add($menu_ui);
        */

        $row1 = $this->form->addFields([$rcg_logo],[$cabecalho]);
        $row1->layout = [' col-sm-2  row justify-content-center',' col-sm-10  row'];

        $row2 = $this->form->addFields([$centro],[$button_meus_dados,$button_financeiro,$button_notas,$button_sair],[$section]);
        $row2->layout = ['col-sm-8  centro','col-sm-2','col-sm-2'];

        // create the form actions

        // vertical box container
        $container = new TVBox;
        $container->style = 'width: 100%';
        $container->class = 'form-container';
        if(empty($param['target_container']))
        {
            // $container->add(new TXMLBreadCrumb('menu.xml', __CLASS__));
        }
        $container->add($this->form);

        parent::add($container);

    }

    public  function onSair($param = null) 
    {
        try 
        {

            TSession::setValue('cliente_logado', false);
            TSession::setValue('cliente_id', null);
            TSession::freeSession();
            AdiantiCoreApplication::gotoPage('LoginClienteForm', '');

        }
        catch (Exception $e) 
        {
            new TMessage('error', $e->getMessage());    
        }
    }

    public function onShow($param = null)
    {               

    } 

}

