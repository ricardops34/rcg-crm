<?php

class ClientePublicaFormView extends TPage
{
    protected $form; // form
    private static $database = 'erp_online';
    private static $activeRecord = 'Cliente';
    private static $primaryKey = 'id';
    private static $formName = 'formView_Cliente';

    /**
     * Form constructor
     * @param $param Request
     */
    public function __construct( $param )
    {
        parent::__construct();

        if(!empty($param['target_container']))
        {
            $this->adianti_target_container = $param['target_container'];
        }

        TTransaction::open(self::$database);
        // creates the form
        $this->form = new BootstrapFormBuilder(self::$formName);
        $this->form->setTagName('div');

        $cliente = new Cliente($param['key']);
        // define the form title
        $this->form->setFormTitle("");

        if(!TSession::getValue('cliente_logado'))
        {
            new TMessage('info', 'Permissão negada! ', new TAction(['LoginClienteForm', 'onShow']));
            return false;
        }

        echo 'key'.$param['key'];

        $label2 = new TLabel("Código:", '', '12px', '', '100%');
        $text3 = new TTextDisplay($cliente->cod_erp, '', '14px', '');
        $label4 = new TLabel("Ativo:", '', '12px', '', '100%');
        $text4 = new TTextDisplay($cliente->status, '', '14px', '');
        $label6 = new TLabel("Tipo Pessoa:", '', '12px', '', '100%');
        $text5 = new TTextDisplay($cliente->tipo, '', '14px', '');
        $label8 = new TLabel("Tipo cliente:", '', '12px', '', '100%');
        $text7 = new TTextDisplay($cliente->tipo_cliente->descricao, '', '14px', '');
        $label34 = new TLabel("CNPJ/CPF:", '', '12px', '', '100%');
        $text22 = new TTextDisplay($cliente->cnpj_cpf, '', '14px', '');
        $label46 = new TLabel("Id:", '', '12px', '', '100%');
        $text1 = new TTextDisplay($cliente->id, '', '14px', '');
        $label10 = new TLabel("Razão Social:", '', '14px', 'B', '100%');
        $text6 = new TTextDisplay($cliente->razao, '', '14px', '');
        $label12 = new TLabel("Nome de Fantasia:", '', '12px', '', '100%');
        $text8 = new TTextDisplay($cliente->fantasia, '', '14px', '');
        $label14 = new TLabel("Endereço:", '', '14px', 'B', '100%');
        $text9 = new TTextDisplay($cliente->endereco, '', '14px', '');
        $label16 = new TLabel("Complemento:", '', '14px', 'B', '100%');
        $text10 = new TTextDisplay($cliente->complemento, '', '14px', '');
        $label28 = new TLabel("CEP:", '', '15px', 'B', '100%');
        $text15 = new TTextDisplay($cliente->cep, '', '14px', '');
        $label18 = new TLabel("Bairro:", '', '15px', 'B', '100%');
        $text11 = new TTextDisplay($cliente->bairro, '', '14px', '');
        $label22 = new TLabel("Municipio:", '', '15px', 'B', '100%');
        $text13 = new TTextDisplay($cliente->municipio->descricao, '', '14px', '');
        $label20 = new TLabel("UF:", '', '15px', 'B', '100%');
        $text12 = new TTextDisplay($cliente->uf, '', '14px', '');
        $label24 = new TLabel("Telefone 1:", '', '15px', 'B', '100%');
        $text16 = new TTextDisplay(new TImage('fas:phone-alt #000000').$cliente->telefone1, '', '14px', '');
        $label26 = new TLabel("Telefone 2:", '', '15px', 'B', '100%');
        $text17 = new TTextDisplay(new TImage('fas:phone-alt #000000').$cliente->telefone2, '', '14px', '');
        $label30 = new TLabel("Telefone Celular:", '', '15px', 'B', '100%');
        $text19 = new TTextDisplay(new TImage('fas:phone-alt #000000').$cliente->celular, '', '12px', '');
        $label32 = new TLabel("Telefone Celular 1:", '', '15px', 'B', '100%');
        $text20 = new TTextDisplay(new TImage('fas:phone-alt #000000').$cliente->celular2, '', '14px', '');
        $label36 = new TLabel("E-Mail:", '', '15px', 'B', '100%');
        $text28 = new TTextDisplay(new TImage('fas:mail-bulk #000000').$cliente->email, '', '14px', '');
        $label38 = new TLabel("Vendedor:", '', '15px', 'B', '100%');
        $text29 = new TTextDisplay($cliente->vendedor->nome, '', '14px', '');
        $label40 = new TLabel("Primeira Compra:", '', '15px', 'B', '100%');
        $text32 = new TTextDisplay(TDate::convertToMask($cliente->primeira_compra, 'yyyy-mm-dd', 'dd/mm/yyyy'), '', '14px', '');
        $label44 = new TLabel("Ultima Visita:", '', '15px', 'B', '100%');
        $text33 = new TTextDisplay(TDate::convertToMask($cliente->ultima_compra, 'yyyy-mm-dd', 'dd/mm/yyyy'), '', '14px', '');

        $text8->enableToggleVisibility(true);
        $text22->enableToggleVisibility(true);
        $text16->enableToggleVisibility(true);
        $text17->enableToggleVisibility(true);
        $text19->enableToggleVisibility(true);
        $text20->enableToggleVisibility(true);

        $row1 = $this->form->addFields([$label2,$text3],[$label4,$text4],[$label6,$text5],[$label8,$text7],[$label34,$text22],[$label46,$text1]);
        $row1->layout = [' col-sm-2',' col-sm-2',' col-sm-2','col-sm-2','col-sm-2','col-sm-2'];

        $row2 = $this->form->addFields([$label10,$text6],[$label12,$text8]);
        $row2->layout = [' col-sm-6','col-sm-6'];

        $row3 = $this->form->addFields([$label14,$text9],[$label16,$text10],[$label28,$text15],[$label18,$text11],[$label22,$text13],[$label20,$text12]);
        $row3->layout = [' col-sm-2','col-sm-2',' col-sm-2','col-sm-2','col-sm-2','col-sm-2'];

        $row4 = $this->form->addFields([$label24,$text16],[$label26,$text17],[$label30,$text19],[$label32,$text20]);
        $row4->layout = ['col-sm-3','col-sm-3',' col-sm-3',' col-sm-3'];

        $row5 = $this->form->addFields([$label36,$text28],[$label38,$text29],[$label40,$text32],[$label44,$text33]);
        $row5->layout = ['col-sm-3','col-sm-3',' col-sm-3',' col-sm-3'];

        // vertical box container
        $container = new TVBox;
        $container->style = 'width: 100%';
        $container->class = 'form-container';
        if(empty($param['target_container']))
        {
            $container->add(TBreadCrumb::create(["Meu Cliente","ClientePublicaFormView"]));
        }
        $container->add($this->form);

        TTransaction::close();
        parent::add($container);

    }

    public function onShow($param = null)
    {     

    }

}

