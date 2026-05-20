<?php

class ClienteSenhaForm extends TWindow
{
    protected BootstrapFormBuilder $form;
    private $formFields = [];
    private static $database = 'erp_online';
    private static $activeRecord = 'Cliente';
    private static $primaryKey = 'id';
    private static $formName = 'form_ClienteSenhaForm';

    /**
     * Form constructor
     * @param $param Request
     */
    public function __construct( $param )
    {
        parent::__construct();
        parent::setSize(0.8, null);
        parent::setTitle("Senha Cliente");
        parent::setProperty('class', 'window_modal');

        if(!empty($param['target_container']))
        {
            $this->adianti_target_container = $param['target_container'];
        }

        // creates the form
        $this->form = new BootstrapFormBuilder(self::$formName);
        // define the form title
        $this->form->setFormTitle("Senha Cliente");


        $cod_erp = new TEntry('cod_erp');
        $id = new THidden('id');
        $razao = new TEntry('razao');
        $fantasia = new TEntry('fantasia');
        $cnpj_cpf = new TEntry('cnpj_cpf');
        $cliente_login = new TEntry('cliente_login');
        $cliente_senha = new TPassword('cliente_senha');
        $cliente_senha_confirma = new TPassword('cliente_senha_confirma');
        $cliente_senha_status = new TCombo('cliente_senha_status');


        $cliente_senha_status->addItems(["A"=>"Ativo","B"=>"Bloqueado"]);
        $cliente_senha_status->enableSearch();
        $cliente_senha->setInnerIcon(new TImage('fas:lock #000000'), 'left');
        $cliente_senha_confirma->setInnerIcon(new TImage('fas:lock #000000'), 'left');

        $cliente_senha->enableToggleVisibility(true);
        $cliente_senha_confirma->enableToggleVisibility(true);

        $razao->setEditable(false);
        $cod_erp->setEditable(false);
        $fantasia->setEditable(false);
        $cnpj_cpf->setEditable(false);
        $cliente_login->setEditable(false);

        $id->setSize(200);
        $razao->setSize('100%');
        $cod_erp->setSize('100%');
        $fantasia->setSize('100%');
        $cnpj_cpf->setSize('100%');
        $cliente_login->setSize('100%');
        $cliente_senha->setSize('100%');
        $cliente_senha_status->setSize('100%');
        $cliente_senha_confirma->setSize('100%');

        $row1 = $this->form->addFields([new TLabel("Codigo:", null, '14px', null, '100%'),$cod_erp,$id],[new TLabel("Razão Social:", null, '14px', null, '100%'),$razao],[new TLabel("Nome de Fantasia:", null, '14px', null, '100%'),$fantasia],[new TLabel("CPF/CNPJ:", null, '14px', null, '100%'),$cnpj_cpf]);
        $row1->layout = [' col-sm-2',' col-sm-4',' col-sm-4','col-sm-2'];

        $row2 = $this->form->addFields([new TLabel("Login:", null, '14px', null, '100%'),$cliente_login],[new TLabel("Senha:", null, '14px', null, '100%'),$cliente_senha],[new TLabel("Confirme a Senha:", null, '14px', null, '100%'),$cliente_senha_confirma],[new TLabel("Situação Login:", null, '14px', null, '100%'),$cliente_senha_status]);
        $row2->layout = [' col-sm-3',' col-sm-3',' col-sm-3',' col-sm-3'];

        // create the form actions
        $btnSalvarSenha = $this->form->addAction("Salvar Senha", new TAction([$this, 'onSalvarSenha']), 'fas:save #FFFFFF');
        $this->btnSalvarSenha = $btnSalvarSenha;
        $btnSalvarSenha->addStyleClass('btn-primary'); 

        parent::add($this->form);

    }

    public function onSalvarSenha($param = null) 
    {
        try 
        {

            $messageAction = null;

            if (empty($param['cliente_senha'])){
                //new TMessage('error', "Senha inválida"); // shows the exception error message
            }else{
                if($param['cliente_senha'] <> $param['cliente_senha_confirma']){
                    throw new Exception("Senha inválida");
                }else{
                    TTransaction::open(self::$database); // open a transaction
                    $oAcesso = ClienteAcesso::where('cliente_id', '=', $param['id'])
                        ->where('login', '=', $param['cnpj_cpf'])
                        ->first();

                    if($oAcesso){

                    }else{
                        $oAcesso = new ClienteAcesso();
                        $oAcesso->cliente_id =  $param['id'];
                    }
                    $oAcesso->user_id = TSession::getValue('userid');
                    $oAcesso->login = $param['cliente_login'];
                    $oAcesso->senha = md5($param['cliente_senha']);
                    $oAcesso->status= $param['cliente_senha_status'];
                    $oAcesso->store();

                    new TMessage('info', "Senha Gravada com Sucesso", $messageAction);

                    TTransaction::close();

                    TWindow::closeWindow(parent::getId());
                }
            }

        }
        catch (Exception $e) 
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

                $object = new Cliente($key); // instantiates the Active Record 

                $oAcesso = ClienteAcesso::where('cliente_id', '=', $object->id)
                    ->where('login', '=', $object->cnpj_cpf)
                    ->first();
                if($oAcesso){
                    $object->cliente_login = $oAcesso->login;
                    $object->cliente_senha_status  = $oAcesso->status;
                }else{
                    $object->cliente_login = $object->cnpj_cpf;
                    $object->cliente_senha_status  = "A";
                }

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

}

