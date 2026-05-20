<?php

class TituloReceberForm extends TPage
{
    protected BootstrapFormBuilder $form;
    private $formFields = [];
    private static $database = 'erp_online';
    private static $activeRecord = 'TituloReceber';
    private static $primaryKey = 'id';
    private static $formName = 'form_TituloReceberForm';

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

        // creates the form
        $this->form = new BootstrapFormBuilder(self::$formName);
        // define the form title
        $this->form->setFormTitle("Cadastro de titulo receber");


        $id = new TEntry('id');
        $tipo = new TEntry('tipo');
        $vendedor_nome_reduzido = new TEntry('vendedor_nome_reduzido');
        $cliente_cod_erp = new TEntry('cliente_cod_erp');
        $cliente_razao = new TEntry('cliente_razao');
        $prefixo = new TEntry('prefixo');
        $numero = new TEntry('numero');
        $parcela = new TEntry('parcela');
        $emissao = new TDate('emissao');
        $venc_real = new TDate('venc_real');
        $baixa = new TDate('baixa');
        $saldo = new TNumeric('saldo', '2', ',', '.' );
        $valor = new TNumeric('valor', '2', ',', '.' );
        $forma_pgto = new TCombo('forma_pgto');
        $historico = new TEntry('historico');
        $pedido_id = new TEntry('pedido_id');
        $nota_fiscal_id = new TEntry('nota_fiscal_id');
        $controle_bco = new TEntry('controle_bco');
        $banco = new TEntry('banco');
        $agencia = new TEntry('agencia');
        $conta = new TEntry('conta');
        $nosso_numero = new TEntry('nosso_numero');
        $dig_nosso_numero = new TEntry('dig_nosso_numero');
        $id_cnab = new TEntry('id_cnab');
        $cod_barras = new TEntry('cod_barras');
        $lin_digitavel = new TEntry('lin_digitavel');
        $impresso = new TEntry('impresso');
        $vias = new TEntry('vias');

        $cliente_razao->addValidation("Cliente", new TRequiredValidator()); 
        $prefixo->addValidation("Prefixo", new TRequiredValidator()); 
        $numero->addValidation("Numero", new TRequiredValidator()); 
        $parcela->addValidation("Parcela", new TRequiredValidator()); 
        $emissao->addValidation("Emissão", new TRequiredValidator()); 
        $venc_real->addValidation("Vencimento Real", new TRequiredValidator()); 

        $id->setEditable(false);
        $forma_pgto->addItems(["C"=>"Carteira","B"=>"Banco"]);
        $forma_pgto->enableSearch();
        $baixa->setMask('dd/mm/yyyy');
        $emissao->setMask('dd/mm/yyyy');
        $venc_real->setMask('dd/mm/yyyy');

        $baixa->setDatabaseMask('yyyy-mm-dd');
        $emissao->setDatabaseMask('yyyy-mm-dd');
        $venc_real->setDatabaseMask('yyyy-mm-dd');

        $tipo->setMaxLength(3);
        $banco->setMaxLength(3);
        $numero->setMaxLength(9);
        $conta->setMaxLength(20);
        $prefixo->setMaxLength(3);
        $parcela->setMaxLength(3);
        $agencia->setMaxLength(10);
        $id_cnab->setMaxLength(20);
        $impresso->setMaxLength(1);
        $cod_barras->setMaxLength(50);
        $controle_bco->setMaxLength(2);
        $nosso_numero->setMaxLength(50);
        $lin_digitavel->setMaxLength(50);
        $dig_nosso_numero->setMaxLength(1);

        $id->setSize(100);
        $tipo->setSize('100%');
        $vias->setSize('100%');
        $baixa->setSize('100%');
        $saldo->setSize('100%');
        $valor->setSize('100%');
        $banco->setSize('100%');
        $conta->setSize('100%');
        $numero->setSize('100%');
        $prefixo->setSize('100%');
        $parcela->setSize('100%');
        $emissao->setSize('100%');
        $agencia->setSize('100%');
        $id_cnab->setSize('100%');
        $impresso->setSize('100%');
        $venc_real->setSize('100%');
        $historico->setSize('100%');
        $pedido_id->setSize('100%');
        $forma_pgto->setSize('100%');
        $cod_barras->setSize('100%');
        $cliente_cod_erp->setSize(90);
        $controle_bco->setSize('100%');
        $nosso_numero->setSize('100%');
        $lin_digitavel->setSize('100%');
        $nota_fiscal_id->setSize('100%');
        $dig_nosso_numero->setSize('100%');
        $vendedor_nome_reduzido->setSize('100%');
        $cliente_razao->setSize('calc(100% - 110px)');


        $this->form->appendPage("Titulo");

        $this->form->addFields([new THidden('current_tab')]);
        $this->form->setTabFunction("$('[name=current_tab]').val($(this).attr('data-current_page'));");

        $row1 = $this->form->addFields([new TLabel("Id:", null, '14px', null, '100%'),$id],[new TLabel("Tipo:", null, '14px', null, '100%'),$tipo],[new TLabel("Vendedor:", null, '14px', null, '100%'),$vendedor_nome_reduzido]);
        $row1->layout = ['col-sm-2',' col-sm-4','col-sm-6'];

        $row2 = $this->form->addFields([new TLabel("Cliente:", '#ff0000', '14px', null, '100%'),$cliente_cod_erp,$cliente_razao]);
        $row2->layout = [' col-sm-12'];

        $row3 = $this->form->addFields([new TLabel("Prefixo:", '#ff0000', '14px', null, '100%'),$prefixo],[new TLabel("Numero:", '#ff0000', '14px', null, '100%'),$numero],[new TLabel("Parcela:", '#ff0000', '14px', null, '100%'),$parcela]);
        $row3->layout = [' col-sm-4',' col-sm-4',' col-sm-4'];

        $row4 = $this->form->addFields([new TLabel("Emissão:", '#ff0000', '14px', null, '100%'),$emissao],[new TLabel("Vencimento Real:", '#ff0000', '14px', null, '100%'),$venc_real],[new TLabel("Baixa:", null, '14px', null, '100%'),$baixa]);
        $row4->layout = [' col-sm-4',' col-sm-4',' col-sm-4'];

        $row5 = $this->form->addFields([new TLabel("Saldo:", null, '14px', null, '100%'),$saldo],[new TLabel("Valor:", null, '14px', null, '100%'),$valor],[new TLabel("Forma de Pagamento:", null, '14px', null, '100%'),$forma_pgto]);
        $row5->layout = [' col-sm-4',' col-sm-4',' col-sm-4'];

        $row6 = $this->form->addFields([new TLabel("Historico:", null, '14px', null, '100%'),$historico]);
        $row6->layout = [' col-sm-12'];

        $this->form->appendPage("Banco");
        $row7 = $this->form->addFields([new TLabel("Pedido:", null, '14px', null, '100%'),$pedido_id],[new TLabel("Nota Fiscal:", null, '14px', null, '100%'),$nota_fiscal_id],[new TLabel("Controle:", null, '14px', null, '100%'),$controle_bco]);
        $row7->layout = [' col-sm-4',' col-sm-4',' col-sm-4'];

        $row8 = $this->form->addFields([new TLabel("Banco:", null, '14px', null, '100%'),$banco],[new TLabel("Agencia:", null, '14px', null, '100%'),$agencia],[new TLabel("Conta:", null, '14px', null, '100%'),$conta]);
        $row8->layout = [' col-sm-4',' col-sm-4',' col-sm-4'];

        $row9 = $this->form->addFields([new TLabel("Nosso Numero:", null, '14px', null, '100%'),$nosso_numero],[new TLabel("Digito Nosso Numero:", null, '14px', null, '100%'),$dig_nosso_numero],[new TLabel("Id Cnab:", null, '14px', null, '100%'),$id_cnab]);
        $row9->layout = [' col-sm-4',' col-sm-4',' col-sm-4'];

        $row10 = $this->form->addFields([new TLabel("Codigo de Barras:", null, '14px', null, '100%'),$cod_barras],[new TLabel("Linha Digitavel:", null, '14px', null, '100%'),$lin_digitavel]);
        $row10->layout = ['col-sm-6','col-sm-6'];

        $row11 = $this->form->addFields([new TLabel("Impresso:", null, '14px', null, '100%'),$impresso],[new TLabel("Vias:", null, '14px', null, '100%'),$vias]);
        $row11->layout = ['col-sm-6',' col-sm-4'];

        // create the form actions
        $btn_onsave = $this->form->addAction("Salvar", new TAction([$this, 'onSave']), 'fas:save #ffffff');
        $this->btn_onsave = $btn_onsave;
        $btn_onsave->addStyleClass('btn-primary'); 

        $btn_onshow = $this->form->addAction("Voltar", new TAction(['TituloReceberList', 'onShow']), 'fas:arrow-left #000000');
        $this->btn_onshow = $btn_onshow;

        parent::setTargetContainer('adianti_right_panel');

        $btnClose = new TButton('closeCurtain');
        $btnClose->class = 'btn btn-sm btn-default';
        $btnClose->style = 'margin-right:10px;';
        $btnClose->onClick = "Template.closeRightPanel();";
        $btnClose->setLabel("Fechar");
        $btnClose->setImage('fas:times');

        $this->form->addHeaderWidget($btnClose);

        parent::add($this->form);

    }

    public function onSave($param = null) 
    {
        try
        {
            TTransaction::open(self::$database); // open a transaction

            $messageAction = null;

            $this->form->validate(); // validate form data

            $object = new TituloReceber(); // create an empty object 

            $data = $this->form->getData(); // get form data as array
            $object->fromArray( (array) $data); // load the object with data

            $object->store(); // save the object 

            // get the generated {PRIMARY_KEY}
            $data->id = $object->id; 

            $this->form->setData($data); // fill form data
            TTransaction::close(); // close the transaction

            TToast::show('success', "Registro salvo", 'topRight', 'far:check-circle'); 

                        TScript::create("Template.closeRightPanel();"); 

        }
        catch (Exception $e) // in case of exception
        {

            new TMessage('error', $e->getMessage()); // shows the exception error message
            $this->form->setData( $this->form->getData() ); // keep form data
            TTransaction::rollback(); // undo all pending operations
        }
    }

    public function onEdit( $param )
    {
        try
        {
            if (isset($param['key']))
            {
                $key = $param['key'];  // get the parameter $key
                TTransaction::open(self::$database); // open a transaction

                $object = new TituloReceber($key); // instantiates the Active Record 

                                $object->vendedor_nome_reduzido = $object->vendedor->nome_reduzido;
                $object->cliente_cod_erp = $object->cliente->cod_erp;
                $object->cliente_razao = $object->cliente->razao;

                $this->form->setData($object); // fill the form 

                TTransaction::close(); // close the transaction 
            }
            else
            {
                $this->form->clear();
            }
        }
        catch (Exception $e) // in case of exception
        {
            new TMessage('error', $e->getMessage()); // shows the exception error message
            TTransaction::rollback(); // undo all pending operations
        }
    }

    /**
     * Clear form data
     * @param $param Request
     */
    public function onClear( $param )
    {
        $this->form->clear(true);

    }

    public function onShow($param = null)
    {

    } 

    public static function getFormName()
    {
        return self::$formName;
    }

    public function onView( $param )
    {
        try
        {
            if (isset($param['key']))
            {
                $key = $param['key'];  
                TTransaction::open(self::$database); 

                $object = new TituloReceber($key); 

                $object->vendedor_nome_reduzido = $object->vendedor->nome_reduzido;
                $object->cliente_cod_erp = $object->cliente->cod_erp;
                $object->cliente_razao = $object->cliente->razao;

                $this->form->setData($object); 
                $this->btn_onsave->style = 'display:none';
                $this->btn_onshow->style = 'display:none';
                $this->form->setEditable(FALSE);

                TTransaction::close();  
            }
            else
            {
                $this->form->clear();
            }
        }
        catch (Exception $e) 
        {
            new TMessage('error', $e->getMessage()); 
            TTransaction::rollback(); 
        }
    }

}

