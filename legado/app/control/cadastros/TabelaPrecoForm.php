<?php

class TabelaPrecoForm extends TPage
{
    protected BootstrapFormBuilder $form;
    private $formFields = [];
    private static $database = 'erp_online';
    private static $activeRecord = 'TabelaPreco';
    private static $primaryKey = 'id';
    private static $formName = 'form_TabelaPrecoForm';

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
        $this->form->setFormTitle("Cadastro Tabela de Preço");


        $id = new TEntry('id');
        $empresa_id = new TEntry('empresa_id');
        $filial_id = new TEntry('filial_id');
        $cod_erp = new TEntry('cod_erp');
        $descricao = new TEntry('descricao');
        $status = new TEntry('status');
        $dt_inicio = new TDate('dt_inicio');
        $dt_fim = new TDate('dt_fim');
        $dt_inclusao = new TDateTime('dt_inclusao');
        $dt_alteracao = new TDateTime('dt_alteracao');
        $utiliza = new TEntry('utiliza');
        $system_unit_id = new TEntry('system_unit_id');

        $cod_erp->addValidation("Cod erp", new TRequiredValidator()); 
        $descricao->addValidation("Descricao", new TRequiredValidator()); 

        $id->setEditable(false);
        $status->setMaxLength(1);
        $cod_erp->setMaxLength(3);
        $utiliza->setMaxLength(1);
        $descricao->setMaxLength(50);

        $dt_fim->setMask('dd/mm/yyyy');
        $dt_inicio->setMask('dd/mm/yyyy');
        $dt_inclusao->setMask('dd/mm/yyyy hh:ii');
        $dt_alteracao->setMask('dd/mm/yyyy hh:ii');

        $dt_fim->setDatabaseMask('yyyy-mm-dd');
        $dt_inicio->setDatabaseMask('yyyy-mm-dd');
        $dt_inclusao->setDatabaseMask('yyyy-mm-dd hh:ii');
        $dt_alteracao->setDatabaseMask('yyyy-mm-dd hh:ii');

        $id->setSize(100);
        $dt_fim->setSize(110);
        $status->setSize('100%');
        $dt_inicio->setSize(110);
        $cod_erp->setSize('100%');
        $utiliza->setSize('100%');
        $dt_inclusao->setSize(150);
        $filial_id->setSize('100%');
        $descricao->setSize('100%');
        $dt_alteracao->setSize(150);
        $empresa_id->setSize('100%');
        $system_unit_id->setSize('100%');

        $row1 = $this->form->addFields([new TLabel("Id:", null, '14px', null, '100%'),$id],[new TLabel("Empresa id:", null, '14px', null, '100%'),$empresa_id]);
        $row1->layout = ['col-sm-6','col-sm-6'];

        $row2 = $this->form->addFields([new TLabel("Filial id:", null, '14px', null, '100%'),$filial_id],[new TLabel("Cod erp:", '#ff0000', '14px', null, '100%'),$cod_erp]);
        $row2->layout = ['col-sm-6','col-sm-6'];

        $row3 = $this->form->addFields([new TLabel("Descricao:", '#ff0000', '14px', null, '100%'),$descricao],[new TLabel("Status:", null, '14px', null, '100%'),$status]);
        $row3->layout = ['col-sm-6','col-sm-6'];

        $row4 = $this->form->addFields([new TLabel("Dt inicio:", null, '14px', null, '100%'),$dt_inicio],[new TLabel("Dt fim:", null, '14px', null, '100%'),$dt_fim]);
        $row4->layout = ['col-sm-6','col-sm-6'];

        $row5 = $this->form->addFields([new TLabel("Dt inclusao:", null, '14px', null, '100%'),$dt_inclusao],[new TLabel("Dt alteracao:", null, '14px', null, '100%'),$dt_alteracao]);
        $row5->layout = ['col-sm-6','col-sm-6'];

        $row6 = $this->form->addFields([new TLabel("Utiliza:", null, '14px', null, '100%'),$utiliza],[new TLabel("system_unit_id:", null, '14px', null, '100%'),$system_unit_id]);
        $row6->layout = ['col-sm-6','col-sm-6'];

        // create the form actions
        $btn_onsave = $this->form->addAction("Salvar", new TAction([$this, 'onSave']), 'fas:save #ffffff');
        $this->btn_onsave = $btn_onsave;
        $btn_onsave->addStyleClass('btn-primary'); 

        $btn_onclear = $this->form->addAction("Limpar formulário", new TAction([$this, 'onClear']), 'fas:eraser #dd5a43');
        $this->btn_onclear = $btn_onclear;

        $btn_onshow = $this->form->addAction("Voltar", new TAction(['TabelaPrecoList', 'onShow']), 'fas:arrow-left #000000');
        $this->btn_onshow = $btn_onshow;

        // vertical box container
        $container = new TVBox;
        $container->style = 'width: 100%';
        $container->class = 'form-container';
        if(empty($param['target_container']))
        {
            $container->add(TBreadCrumb::create(["Cadastros","Cadastro Tabela de Preço"]));
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

            $object = new TabelaPreco(); // create an empty object 

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

            TToast::show('success', "Registro salvo", 'topRight', 'far:check-circle');
            TApplication::loadPage('TabelaPrecoList', 'onShow', $loadPageParam); 

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

                $object = new TabelaPreco($key); // instantiates the Active Record 

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

