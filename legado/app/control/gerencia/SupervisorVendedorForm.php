<?php

class SupervisorVendedorForm extends TPage
{
    protected BootstrapFormBuilder $form;
    private $formFields = [];
    private static $database = 'erp_online';
    private static $activeRecord = 'SupervisorVendedor';
    private static $primaryKey = 'id';
    private static $formName = 'form_SupervisorVendedorForm';

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
        $this->form->setFormTitle("Supervisor X Vendedor");

        $criteria_supervisor_id = new TCriteria();
        $criteria_vendedor_id = new TCriteria();

        $filterVar = "A";
        $criteria_vendedor_id->add(new TFilter('status', '=', $filterVar)); 

        $id = new TEntry('id');
        $sistema = new TCombo('sistema');
        $supervisor_id = new TDBCombo('supervisor_id', 'erp_online', 'Supervisor', 'id', '{nome_reduzido}','nome_reduzido asc' , $criteria_supervisor_id );
        $vendedor_id = new TDBCombo('vendedor_id', 'erp_online', 'Vendedor', 'id', '{nome_reduzido}','nome asc' , $criteria_vendedor_id );

        $sistema->addValidation("Sistema", new TRequiredValidator()); 
        $supervisor_id->addValidation("Supervisor", new TRequiredValidator()); 
        $vendedor_id->addValidation("Vendedor", new TRequiredValidator()); 

        $sistema->addItems(["S"=>"Sim","N"=>"Não"]);
        $sistema->setValue("N");
        $id->setEditable(false);
        $sistema->setEditable(false);

        $sistema->enableSearch();
        $vendedor_id->enableSearch();
        $supervisor_id->enableSearch();

        $id->setSize(100);
        $sistema->setSize('100%');
        $vendedor_id->setSize('100%');
        $supervisor_id->setSize('100%');

        $row1 = $this->form->addFields([new TLabel("Id:", null, '14px', null, '100%'),$id],[new TLabel("Sistema:", null, '14px', null, '100%'),$sistema]);
        $row1->layout = [' col-sm-6','col-sm-6'];

        $row2 = $this->form->addFields([new TLabel("Supervisor:", '#ff0000', '14px', null, '100%'),$supervisor_id],[new TLabel("Vendedor:", '#ff0000', '14px', null, '100%'),$vendedor_id]);
        $row2->layout = ['col-sm-6','col-sm-6'];

        // create the form actions
        $btn_onsave = $this->form->addAction("Salvar", new TAction([$this, 'onSave']), 'fas:save #ffffff');
        $this->btn_onsave = $btn_onsave;
        $btn_onsave->addStyleClass('btn-primary'); 

        $btn_onshow = $this->form->addAction("Votar", new TAction(['SupervisorVendedorList', 'onShow']), 'fas:arrow-left #000000');
        $this->btn_onshow = $btn_onshow;

        // vertical box container
        $container = new TVBox;
        $container->style = 'width: 100%';
        $container->class = 'form-container';
        if(empty($param['target_container']))
        {
            $container->add(TBreadCrumb::create(["Gerencia","Supervisor X Vendedor"]));
        }
        $container->add($this->form);

        parent::add($container);

    }

    public function onSave($param = null) 
    {
        try
        {
            TTransaction::open(self::$database); // open a transaction

            $messageAction = null;

            $this->form->validate(); // validate form data

            $object = new SupervisorVendedor(); // create an empty object 

            $data = $this->form->getData(); // get form data as array
            $object->fromArray( (array) $data); // load the object with data

            if(empty($data->sistema)){
                $data->sistema = "N";
            }

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

            TToast::show('success', "Registro salvo", 'topRight', 'far:check-circle');
            TApplication::loadPage('SupervisorVendedorList', 'onShow', $loadPageParam); 

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

                $object = new SupervisorVendedor($key); // instantiates the Active Record 

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

