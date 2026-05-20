<?php

class VendedorForm extends TWindow
{
    protected BootstrapFormBuilder $form;
    private $formFields = [];
    private static $database = 'erp_online';
    private static $activeRecord = 'Vendedor';
    private static $primaryKey = 'id';
    private static $formName = 'form_VendedorForm';

    /**
     * Form constructor
     * @param $param Request
     */
    public function __construct( $param )
    {
        parent::__construct();
        parent::setSize(0.8, null);
        parent::setTitle("Cadastro de Vendedor");
        parent::setProperty('class', 'window_modal');

        if(!empty($param['target_container']))
        {
            $this->adianti_target_container = $param['target_container'];
        }

        // creates the form
        $this->form = new BootstrapFormBuilder(self::$formName);
        // define the form title
        $this->form->setFormTitle("Cadastro de Vendedor");

        $criteria_filial_id = new TCriteria();
        $criteria_system_users_id = new TCriteria();
        $criteria_supervisor_id = new TCriteria();

        $filterVar = 'Y';
        $criteria_system_users_id->add(new TFilter('active', '=', $filterVar)); 
        $filterVar = 'S';
        $criteria_supervisor_id->add(new TFilter('supervisor', '=', $filterVar)); 

        $id = new TEntry('id');
        $onde = new THidden('onde');
        $filial_id = new TDBCombo('filial_id', 'erp_online', 'Filial', 'id', '{apelido}','apelido asc' , $criteria_filial_id );
        $cod_erp = new TEntry('cod_erp');
        $nome = new TEntry('nome');
        $nome_reduzido = new TEntry('nome_reduzido');
        $ddd = new TEntry('ddd');
        $celular = new TEntry('celular');
        $telefone = new TEntry('telefone');
        $email = new TEntry('email');
        $system_users_id = new TDBCombo('system_users_id', 'permission', 'SystemUsers', 'id', '{name}','name asc' , $criteria_system_users_id );
        $vendedor = new TRadioGroup('vendedor');
        $dashboard = new TRadioGroup('dashboard');
        $desligado = new TRadioGroup('desligado');
        $status = new TRadioGroup('status');
        $supervisor = new TRadioGroup('supervisor');
        $supervisor_id = new TDBCombo('supervisor_id', 'erp_online', 'Vendedor', 'id', '{nome}','nome asc' , $criteria_supervisor_id );

        $supervisor->setChangeAction(new TAction([$this,'OnChangeSupervisor']));

        $nome->addValidation("Nome", new TRequiredValidator()); 
        $desligado->addValidation("Vendedor desligado?", new TRequiredValidator()); 

        $filial_id->enableSearch();
        $supervisor_id->enableSearch();
        $system_users_id->enableSearch();

        $status->setValue('A');
        $vendedor->setValue('S');
        $dashboard->setValue('S');
        $supervisor->setValue('N');
        $onde->setValue('VendedorForm');

        $vendedor->addItems(["S"=>"Sim","N"=>"Não"]);
        $dashboard->addItems(["S"=>"Sim","N"=>"Não"]);
        $desligado->addItems(["S"=>"Sim","N"=>"Não"]);
        $supervisor->addItems(["S"=>"Sim","N"=>"Não"]);
        $status->addItems(["A"=>"Ativo","B"=>"Bloqueado"]);

        $status->setLayout('horizontal');
        $vendedor->setLayout('horizontal');
        $dashboard->setLayout('horizontal');
        $desligado->setLayout('horizontal');
        $supervisor->setLayout('horizontal');

        $status->setUseButton();
        $vendedor->setUseButton();
        $dashboard->setUseButton();
        $desligado->setUseButton();
        $supervisor->setUseButton();

        $ddd->setMaxLength(3);
        $nome->setMaxLength(100);
        $cod_erp->setMaxLength(6);
        $email->setMaxLength(100);
        $celular->setMaxLength(15);
        $telefone->setMaxLength(15);
        $nome_reduzido->setMaxLength(50);

        $id->setEditable(false);
        $ddd->setEditable(false);
        $nome->setEditable(false);
        $cod_erp->setEditable(false);
        $celular->setEditable(false);
        $telefone->setEditable(false);
        $filial_id->setEditable(false);
        $nome_reduzido->setEditable(false);

        $id->setSize(100);
        $onde->setSize(200);
        $ddd->setSize('100%');
        $nome->setSize('100%');
        $email->setSize('100%');
        $status->setSize('100%');
        $cod_erp->setSize('100%');
        $celular->setSize('100%');
        $telefone->setSize('100%');
        $vendedor->setSize('100%');
        $filial_id->setSize('100%');
        $dashboard->setSize('100%');
        $desligado->setSize('100%');
        $supervisor->setSize('100%');
        $nome_reduzido->setSize('100%');
        $supervisor_id->setSize('100%');
        $system_users_id->setSize('100%');

        $this->form->appendPage("Cadastro");

        $this->form->addFields([new THidden('current_tab')]);
        $this->form->setTabFunction("$('[name=current_tab]').val($(this).attr('data-current_page'));");

        $row1 = $this->form->addFields([new TLabel("Id:", null, '14px', null, '100%'),$id,$onde],[new TLabel("Filial:", null, '14px', null, '100%'),$filial_id],[new TLabel("Código ERP:", null, '14px', null, '100%'),$cod_erp]);
        $row1->layout = [' col-sm-4',' col-sm-4',' col-sm-4'];

        $row2 = $this->form->addFields([new TLabel("Nome:", '#ff0000', '14px', null, '100%'),$nome],[new TLabel("Nome Reduzido:", null, '14px', null, '100%'),$nome_reduzido]);
        $row2->layout = ['col-sm-6','col-sm-6'];

        $row3 = $this->form->addFields([new TLabel("DDD:", null, '14px', null, '100%'),$ddd],[new TLabel("Celular:", null, '14px', null, '100%'),$celular],[new TLabel("Telefone:", null, '14px', null, '100%'),$telefone]);
        $row3->layout = [' col-sm-4',' col-sm-4',' col-sm-4'];

        $row4 = $this->form->addFields([new TLabel("E-Mail:", null, '14px', null, '100%'),$email],[new TLabel("Usuario Sistema:", null, '14px', null, '100%'),$system_users_id],[new TLabel("Vendedor:", null, '14px', null, '100%'),$vendedor],[new TLabel("Lista em Dashboard:", null, '14px', null, '100%'),$dashboard]);
        $row4->layout = [' col-sm-3','col-sm-3','col-sm-3','col-sm-3'];

        $row5 = $this->form->addFields([new TLabel("Desligado:", null, '14px', null, '100%'),$desligado],[new TLabel("Situação:", null, '14px', null, '100%'),$status],[new TLabel("Supervisor:", null, '14px', null, '100%'),$supervisor],[new TLabel("Supervisor:", null, '14px', null, '100%'),$supervisor_id]);
        $row5->layout = [' col-sm-3','col-sm-3','col-sm-3','col-sm-3'];

        // create the form actions
        $btn_onsave = $this->form->addAction("Salvar", new TAction([$this, 'onSave']), 'fas:save #ffffff');
        $this->btn_onsave = $btn_onsave;
        $btn_onsave->addStyleClass('btn-primary'); 

        $btn_onclear = $this->form->addAction("Limpar formulário", new TAction([$this, 'onClear']), 'fas:eraser #dd5a43');
        $this->btn_onclear = $btn_onclear;

        $btn_onshow = $this->form->addAction("Voltar", new TAction(['VendedorList', 'onShow']), 'fas:arrow-left #000000');
        $this->btn_onshow = $btn_onshow;

        parent::add($this->form);

    }

    public static function OnChangeSupervisor($param = null) 
    {
        try 
        {
            if($param['supervisor']){
                if($param['supervisor'] == 'N'){
                    TDBCombo::enableField(self::$formName, 'supervisor_id');
                }else{
                    TDBCombo::disableField(self::$formName, 'supervisor_id');
                }
            }

        }
        catch (Exception $e) 
        {
            new TMessage('error', $e->getMessage());    
        }
    }

    public function onSave($param = null) 
    {
        try
        {
            TTransaction::open(self::$database); // open a transaction

            $messageAction = null;

            $this->form->validate(); // validate form data

            $object = new Vendedor(); // create an empty object 

            $data = $this->form->getData(); // get form data as array
            $object->fromArray( (array) $data); // load the object with data

            $object->store(); // save the object 

            $loadPageParam = [];

            if(!empty($param['target_container']))
            {
                $loadPageParam['target_container'] = $param['target_container'];
            }

            // get the generated {PRIMARY_KEY}
            $data->id = $object->id; 

            $this->form->setData($data); // fill form data
            TTransaction::close(); // close the transaction

            if(isset($param['onde'])){

                if($param['onde'] == 'VendedorSupervisor'){
                    TToast::show('success', "Registro salvo", 'topRight', 'far:check-circle');
                    TApplication::loadPage('SupervisorVendedorList', 'onShow', $loadPageParam);
                }else{
                    TToast::show('success', "Registro salvo", 'topRight', 'far:check-circle');
                    TApplication::loadPage('VendedorList', 'onShow', $loadPageParam);
                }

            }else{

            TToast::show('success', "Registro salvo", 'topRight', 'far:check-circle');
            TApplication::loadPage('VendedorList', 'onShow', $loadPageParam); 

            }
                TWindow::closeWindow(parent::getId()); 

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

                $object = new Vendedor($key); // instantiates the Active Record 

                if (isset($param['onde'])){
                    $object->onde = $param['onde'];
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

