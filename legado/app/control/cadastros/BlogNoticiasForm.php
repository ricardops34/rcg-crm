<?php

class BlogNoticiasForm extends TPage
{
    protected BootstrapFormBuilder $form;
    private $formFields = [];
    private static $database = 'erp_online';
    private static $activeRecord = 'BlogNoticias';
    private static $primaryKey = 'id';
    private static $formName = 'form_BlogNoticiasForm';

    use Adianti\Base\AdiantiFileSaveTrait;

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
        $this->form->setFormTitle("Cadastro de Noticias");


        $titulo = new TEntry('titulo');
        $id = new THidden('id');
        $data_postagem = new TDate('data_postagem');
        $status = new TCombo('status');
        $texto = new THtmlEditor('texto');
        $imagem = new TFile('imagem');
        $autor = new TEntry('autor');

        $titulo->addValidation("Titulo", new TRequiredValidator()); 
        $data_postagem->addValidation("Data postagem", new TRequiredValidator()); 
        $texto->addValidation("Texto", new TRequiredValidator()); 

        $data_postagem->setMask('dd/mm/yyyy');
        $data_postagem->setDatabaseMask('yyyy-mm-dd');
        $status->addItems(["A"=>"Ativo","B"=>"Bloqueado"]);
        $status->setValue('A');
        $status->enableSearch();
        $imagem->enableFileHandling();
        $imagem->setAllowedExtensions(["jpg","png","jpeg"]);
        $autor->setEditable(false);
        $id->setSize(200);
        $autor->setSize('100%');
        $titulo->setSize('100%');
        $status->setSize('100%');
        $imagem->setSize('100%');
        $texto->setSize('100%', 250);
        $data_postagem->setSize('100%');

        $row1 = $this->form->addFields([new TLabel("Titulo:", '#ff0000', '14px', null, '100%'),$titulo,$id],[new TLabel("Data postagem:", '#ff0000', '14px', null, '100%'),$data_postagem],[new TLabel("Situação:", null, '14px', null, '100%'),$status]);
        $row1->layout = ['col-sm-6',' col-sm-3',' col-sm-3'];

        $row2 = $this->form->addFields([new TLabel("Texto:", '#ff0000', '14px', null, '100%'),$texto]);
        $row2->layout = [' col-sm-12'];

        $row3 = $this->form->addFields([new TLabel("Imagem:", null, '14px', null, '100%'),$imagem],[new TLabel("Autor:", null, '14px', null, '100%'),$autor]);
        $row3->layout = [' col-sm-8',' col-sm-4'];

        // create the form actions
        $btn_onsave = $this->form->addAction("Salvar", new TAction([$this, 'onSave']), 'fas:save #ffffff');
        $this->btn_onsave = $btn_onsave;
        $btn_onsave->addStyleClass('btn-primary'); 

        $btn_onclear = $this->form->addAction("Limpar formulário", new TAction([$this, 'onClear']), 'fas:eraser #dd5a43');
        $this->btn_onclear = $btn_onclear;

        $btn_onshow = $this->form->addAction("Voltar", new TAction(['BlogNoticiasList', 'onShow']), 'fas:arrow-left #000000');
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

            $object = new BlogNoticias(); // create an empty object 

            $data = $this->form->getData(); // get form data as array
            $object->fromArray( (array) $data); // load the object with data

            if(empty($object->system_user_id)){
                $object->system_user_id = TSession::getValue('userid'); 
            }

            $imagem_dir = 'noticias';  

            $object->store(); // save the object 

            $this->saveFile($object, $data, 'imagem', $imagem_dir);
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
            TApplication::loadPage('BlogNoticiasList', 'onShow', $loadPageParam); 

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

                $object = new BlogNoticias($key); // instantiates the Active Record 

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

