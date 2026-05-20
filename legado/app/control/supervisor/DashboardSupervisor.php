<?php

class DashboardSupervisor extends TPage
{
    protected $form;
    private $formFields = [];
    private static $database = '';
    private static $activeRecord = '';
    private static $primaryKey = '';
    private static $formName = 'form_DashboardSupervisor';

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
        $this->form->setFormTitle("Dashboard");

        $criteria_supervisor_id = new TCriteria();
        $criteria_vendedor_id = new TCriteria();

        $supervisor_id = new TDBCombo('supervisor_id', 'erp_online', 'Supervisor', 'id', '{nome_reduzido}','nome_reduzido asc' , $criteria_supervisor_id );
        $vendedor_id = new TDBCombo('vendedor_id', 'erp_online', 'Vendedor', 'id', '{nome_reduzido}','nome_reduzido asc' , $criteria_vendedor_id );
        $gerente_id = new TEntry('gerente_id');

        $supervisor_id->setChangeAction(new TAction([$this,'onSupervisorid']));

        $vendedor_id->enableSearch();
        $supervisor_id->enableSearch();

        $gerente_id->setSize('100%');
        $vendedor_id->setSize('100%');
        $supervisor_id->setSize('100%');


        $row1 = $this->form->addFields([new TLabel("Supervisor:", null, '14px', null, '100%'),$supervisor_id],[new TLabel("Vendedor:", null, '14px', null, '100%'),$vendedor_id],[new TLabel("Mês / Ano:", null, '14px', null, '100%'),$gerente_id]);
        $row1->layout = ['col-12 col-sm-4','col-12 col-sm-4','col-12 col-sm-4'];

        // create the form actions
        $btn_onaction = $this->form->addAction("Ação", new TAction([$this, 'onAction']), 'fas:rocket #ffffff');
        $this->btn_onaction = $btn_onaction;
        $btn_onaction->addStyleClass('btn-primary'); 

        // vertical box container
        $container = new TVBox;
        $container->style = 'width: 100%';
        $container->class = 'form-container';
        if(empty($param['target_container']))
        {
            $container->add(TBreadCrumb::create(["Supervisor","DashboardSupervisor"]));
        }
        $container->add($this->form);

        parent::add($container);

    }

    public static function onSupervisorid($param = null) 
    {
        try 
        {
            echo $param['supervisor_id'];

            if (isset($param['supervisor_id']))
            { 
                $criteria = TCriteria::create(['supervisor_id' => $param['supervisor_id']]);
                TDBCombo::reloadFromModel(self::$formName, 'vendedor_id', 'erp_online', 'Vendedor', 'id', '{nome_reduzido}', 'nome_reduzido asc', $criteria, TRUE); 
                //$vendedor_id = new TDBCombo('vendedor_id', 'erp_online', 'Vendedor', 'id', '{nome_reduzido}','nome_reduzido asc' , $criteria_vendedor_id )
            } 
            else 
            { 
                TCombo::clearField(self::$formName, 'vendedor_id'); 
            } 

        }
        catch (Exception $e) 
        {
            new TMessage('error', $e->getMessage());    
        }
    }

    public function onAction($param = null) 
    {
        try
        {

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

