<?php

class BlogComunicadosForm extends TPage
{
    protected BootstrapFormBuilder $form;
    private $formFields = [];
    private static $database = 'erp_online';
    private static $activeRecord = 'BlogComunicados';
    private static $primaryKey = 'id';
    private static $formName = 'form_BlogComunicadosForm';

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
        $this->form->setFormTitle("Cadastro de Comunicados");


        $titulo = new TEntry('titulo');
        $id = new THidden('id');
        $data_postagem = new TDate('data_postagem');
        $status = new TCombo('status');
        $texto = new THtmlEditor('texto');
        $link_externo = new TEntry('link_externo');
        $link_texto = new TEntry('link_texto');
        $ordenacao = new TEntry('ordenacao');
        $system_user_id = new TEntry('system_user_id');

        $titulo->addValidation("Titulo", new TRequiredValidator()); 
        $data_postagem->addValidation("Data postagem", new TRequiredValidator()); 
        $texto->addValidation("Texto", new TRequiredValidator()); 
        $ordenacao->addValidation("Ordenacao", new TRequiredValidator()); 

        $data_postagem->setMask('dd/mm/yyyy');
        $data_postagem->setDatabaseMask('yyyy-mm-dd');
        $status->addItems(["A"=>"Ativo","B"=>"Bloqueado"]);
        $status->enableSearch();
        $system_user_id->setEditable(false);
        $link_texto->setInnerIcon(new TImage('fas:link #000000'), 'left');
        $link_externo->setInnerIcon(new TImage('fas:link #000000'), 'left');

        $status->setValue('A');
        $data_postagem->setValue(date('d/m/Y'));
        $system_user_id->setValue(TSession::getValue("userid"));

        $id->setSize(200);
        $titulo->setSize('100%');
        $status->setSize('100%');
        $ordenacao->setSize('100%');
        $texto->setSize('100%', 190);
        $link_texto->setSize('100%');
        $link_externo->setSize('100%');
        $data_postagem->setSize('100%');
        $system_user_id->setSize('100%');

        $row1 = $this->form->addFields([new TLabel("Titulo:", '#ff0000', '14px', null, '100%'),$titulo,$id],[new TLabel("Data postagem:", '#ff0000', '14px', null, '100%'),$data_postagem],[new TLabel("Ativo:", null, '14px', null, '100%'),$status]);
        $row1->layout = ['col-sm-6',' col-sm-3',' col-sm-3'];

        $row2 = $this->form->addFields([new TLabel("Texto:", '#ff0000', '14px', null, '100%'),$texto]);
        $row2->layout = [' col-sm-12'];

        $row3 = $this->form->addFields([new TLabel("Link externo:", null, '14px', null, '100%'),$link_externo],[new TLabel("Link texto:", null, '14px', null, '100%'),$link_texto],[new TLabel("Ordenacao:", '#ff0000', '14px', null, '100%'),$ordenacao],[new TLabel("Usuario:", null, '14px', null, '100%'),$system_user_id]);
        $row3->layout = ['col-sm-4','col-sm-4',' col-sm-2','col-sm-2'];

        // create the form actions
        $btn_onsave = $this->form->addAction("Salvar", new TAction([$this, 'onSave']), 'fas:save #ffffff');
        $this->btn_onsave = $btn_onsave;
        $btn_onsave->addStyleClass('btn-primary'); 

        $btn_onclear = $this->form->addAction("Limpar formulário", new TAction([$this, 'onClear']), 'fas:eraser #dd5a43');
        $this->btn_onclear = $btn_onclear;

        $btn_onshow = $this->form->addAction("Voltar", new TAction(['BlogComunicadosList', 'onShow']), 'fas:arrow-left #000000');
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

            $object = new BlogComunicados(); // create an empty object 

            $data = $this->form->getData(); // get form data as array
            $object->fromArray( (array) $data); // load the object with data

            if(empty($object->system_user_id)){
                $object->system_user_id = TSession::getValue('userid'); 
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
            TApplication::loadPage('BlogComunicadosList', 'onShow', $loadPageParam); 

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

                $object = new BlogComunicados($key); // instantiates the Active Record 

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

