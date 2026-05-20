<?php

class LoginClienteForm extends TPage
{
    protected $form;
    private $formFields = [];
    private static $database = '';
    private static $activeRecord = '';
    private static $primaryKey = '';
    private static $formName = 'form_LoginClienteForm';

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

        //$this->style = 'clear:both';
        $center = new BElement('center');

        $imagem = new TImage('http://rcgdist.com.br/sites/default/files/logo-rodape_5.png');
        $cliente_login = new TEntry('cliente_login');
        $cliente_senha = new TPassword('cliente_senha');
        $button_entrar = new TButton('button_entrar');

        $cliente_login->addValidation("Login", new TRequiredValidator()); 

        $cliente_login->setTip("CPF ou CNPJ");
        $button_entrar->setAction(new TAction([$this, 'onLogin']), "Entrar");
        $button_entrar->addStyleClass('btnlogin');
        $button_entrar->setImage('fas:check #FFFFFF');
        $cliente_login->setSize('100%');
        $cliente_senha->setSize('100%');

        $cliente_senha->setInnerIcon(new TImage('fas:key #000000'), 'right');
        $cliente_login->setInnerIcon(new TImage('fas:user #000000'), 'right');

        $cliente_login->enableToggleVisibility(false);
        $cliente_senha->enableToggleVisibility(false);

        $imagem->width = '328px';
        $imagem->height = '135px';
        $imagem->id = 'imagem_login';
        $cliente_login->placeholder = "Login";
        $cliente_senha->placeholder = "Senha";

        $this->imagem = $imagem;

        $center->class = "barra-superior";
        $allia = new TImage('https://rcgdist.com.br/logo-allias.png');
        $center->add($allia);

        //$this->center = $center;
        //$this->center->style = "barra-superior";

        //<center class="barra-superior";><img src="https://rcgdist.com.br/logo-allias.png"></center>
        /*
        $cliente_login->style = 'border-radius: 6px;';//'height:35px; font-size:14px;float:left;border-bottom-left-radius: 0;border-top-left-radius: 0;';
        $cliente_senha->style = 'border-radius: 6px;';//'height:35px;font-size:14px;float:left;border-bottom-left-radius: 0;border-top-left-radius: 0;';

        $cliente_login->autofocus = 'autofocus';
        */

        $row1 = $this->form->addFields([$imagem]);
        $row1->layout = [' col-sm-12'];

        $row2 = $this->form->addFields([$cliente_login]);
        $row2->layout = [' col-sm-12'];

        $row3 = $this->form->addFields([$cliente_senha]);
        $row3->layout = [' col-sm-12'];

        $row4 = $this->form->addFields([$button_entrar]);
        $row4->layout = [' col-sm-12'];

        /*

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

        */
        /*
        $container = new TElement('div');
        $container->style = 'margin:auto; margin-top:100px; max-width:460px; border-radius:25px;';
        $container->id = 'login-wrapper';

        $h3 = new TElement('h1');
        $h3->style = 'text-align:center;';
        $h3->add('');

        //$divLogo = new TElement('div');
        //$divLogo->class = 'login-medium-logo';

        //$container->add($divLogo);
        $container->add($h3);
        $container->add($this->form);
        */

        $container = new TVBox;
        $container->style = 'width: 100%';
        $container->class = 'form-container';
        if(empty($param['target_container']))
        {
            // $container->add(new TXMLBreadCrumb('menu.xml', __CLASS__));
        }
        $container->add($center);
        $container->add($this->form);

        parent::add($container);

    }

    public  function onLogin($param = null) 
    {
        try 
        {
            $this->form->validate();

            $data = $this->form->getData();

            TTransaction::open('erp_online');

            $oCliente = ClienteAcesso::where('login', '=', $param['cliente_login'])
                ->where('senha', '=', md5($param['cliente_senha']))
                ->first();

            if($oCliente){

                $pageParam = ['key'=>$oCliente->cliente_id];

                TToast::show("show", "Login efetuado com sucesso", "topRight", "");
                TSession::setValue('cliente_logado', true);
                TSession::setValue('cliente_id', $oCliente->cliente_id);
                //TApplication::loadPage('PainelClienteForm', 'onShow', $pageParam);
                TApplication::loadPage('MeuClienteFormView', 'onShow', $pageParam);

            }else{
                //TToast::show("error", "Login o Senha Invalida", "topRight", "");
                throw new Exception('Login ou senha incorretos!');
            }

            TTransaction::close();

        }
        catch (Exception $e) 
        {
            TSession::setValue('cliente_logado', false);
            TSession::setValue('cliente_id', null);

            $this->form->setData($this->form->getData());

            new TMessage('error', $e->getMessage());    
        }
    }

    public function onShow($param = null)
    {               

    } 

}

