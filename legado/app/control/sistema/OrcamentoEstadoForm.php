<?php

class OrcamentoEstadoForm extends TPage
{
    protected BootstrapFormBuilder $form;
    private $formFields = [];
    private static $database = 'erp_online';
    private static $activeRecord = 'OrcamentoEstado';
    private static $primaryKey = 'id';
    private static $formName = 'form_OrcamentoEstadoForm';

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
        $this->form->setFormTitle("Cadastro de orcamento estado");


        $id = new THidden('id');
        $cod_erp = new TEntry('cod_erp');
        $sistema = new TEntry('sistema');
        $ordem = new TEntry('ordem');
        $exibir_regua = new TEntry('exibir_regua');
        $descricao = new TEntry('descricao');
        $cor = new TColor('cor');
        $cor_texto = new TColor('cor_texto');
        $icone = new TIcon('icone');
        $editar = new TRadioGroup('editar');
        $cancelar = new TRadioGroup('cancelar');
        $excluir = new TRadioGroup('excluir');
        $imprimir = new TRadioGroup('imprimir');


        $sistema->setMaxLength(1);
        $cod_erp->setMaxLength(10);
        $descricao->setMaxLength(100);
        $exibir_regua->setMaxLength(1);

        $editar->addItems(["S"=>"Sim","N"=>"Não"]);
        $excluir->addItems(["S"=>"Sim","N"=>"Não"]);
        $cancelar->addItems(["S"=>"Sim","N"=>"Não"]);
        $imprimir->addItems(["S"=>"Sim","N"=>"Não"]);

        $editar->setLayout('horizontal');
        $excluir->setLayout('horizontal');
        $cancelar->setLayout('horizontal');
        $imprimir->setLayout('horizontal');

        $editar->setUseButton();
        $excluir->setUseButton();
        $cancelar->setUseButton();
        $imprimir->setUseButton();

        $id->setSize(200);
        $cor->setSize(100);
        $ordem->setSize('100%');
        $icone->setSize('100%');
        $editar->setSize('100%');
        $cod_erp->setSize('100%');
        $sistema->setSize('100%');
        $excluir->setSize('100%');
        $cancelar->setSize('100%');
        $imprimir->setSize('100%');
        $descricao->setSize('100%');
        $cor_texto->setSize('100%');
        $exibir_regua->setSize('100%');

        $row1 = $this->form->addFields([new TLabel("Código:", null, '14px', null, '100%'),$id,$cod_erp],[new TLabel("Sistema:", null, '14px', null, '100%'),$sistema],[new TLabel("Ordem:", null, '14px', null, '100%'),$ordem],[new TLabel("Exibir na Regua:", null, '14px', null, '100%'),$exibir_regua]);
        $row1->layout = [' col-sm-3',' col-sm-3',' col-sm-3',' col-sm-3'];

        $row2 = $this->form->addFields([new TLabel("Descrição:", null, '14px', null, '100%'),$descricao],[new TLabel("Cor:", null, '14px', null, '100%'),$cor],[new TLabel("Cor Texto:", null, '14px', null, '100%'),$cor_texto],[new TLabel("Ícone:", null, '14px', null, '100%'),$icone]);
        $row2->layout = [' col-sm-3',' col-sm-3',' col-sm-3',' col-sm-3'];

        $row3 = $this->form->addFields([new TLabel("Pode Editar:", null, '14px', null, '100%'),$editar],[new TLabel("Pode Cancelar:", null, '14px', null, '100%'),$cancelar],[new TLabel("Pode Excluir:", null, '14px', null, '100%'),$excluir],[new TLabel("Pode Imprimir:", null, '14px', null, '100%'),$imprimir]);
        $row3->layout = [' col-sm-3',' col-sm-3',' col-sm-3',' col-sm-3'];

        // create the form actions
        $btn_onsave = $this->form->addAction("Salvar", new TAction([$this, 'onSave']), 'fas:save #ffffff');
        $this->btn_onsave = $btn_onsave;
        $btn_onsave->addStyleClass('btn-primary'); 

        $btn_onclear = $this->form->addAction("Limpar formulário", new TAction([$this, 'onClear']), 'fas:eraser #dd5a43');
        $this->btn_onclear = $btn_onclear;

        $btn_onshow = $this->form->addAction("Voltar", new TAction(['OrcamentoEstadoList', 'onShow']), 'fas:arrow-left #000000');
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

            $object = new OrcamentoEstado(); // create an empty object 

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
            TApplication::loadPage('OrcamentoEstadoList', 'onShow', $loadPageParam); 

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

                $object = new OrcamentoEstado($key); // instantiates the Active Record 

                $font = new TElement('font');
                $font->style="color:'".$object->cor_texto."'; font-size:12px; font-weight:lighter;"; 
                $font->add($object->descricao);

                $div = new TElement('span');
                $div->class="label label-default";
                $div->style="width:120px; text-shadow:none; background-color:{".$object->cor."} "; 
                $div->add($font);

                $object->exemplo = $div ;

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

