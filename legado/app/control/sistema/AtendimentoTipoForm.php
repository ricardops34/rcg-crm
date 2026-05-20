<?php

class AtendimentoTipoForm extends TPage
{
    protected BootstrapFormBuilder $form;
    private $formFields = [];
    private static $database = 'erp_online';
    private static $activeRecord = 'AtendimentoTipo';
    private static $primaryKey = 'id';
    private static $formName = 'form_AtendimentoTipoForm';

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
        $this->form->setFormTitle("Cadastro Tipos de Atendimento ");


        $id = new TEntry('id');
        $cod_erp = new TEntry('cod_erp');
        $descricao = new TEntry('descricao');
        $icone = new TIcon('icone');
        $cor = new TColor('cor');
        $venda = new TRadioGroup('venda');
        $cadastro = new TRadioGroup('cadastro');
        $retorno = new TRadioGroup('retorno');
        $atendimento = new TRadioGroup('atendimento');
        $editar = new TRadioGroup('editar');
        $excluir = new TRadioGroup('excluir');

        $cod_erp->addValidation("Código", new TRequiredValidator()); 
        $descricao->addValidation("Descrição", new TRequiredValidator()); 

        $id->setEditable(false);
        $cod_erp->setMaxLength(10);
        $descricao->setMaxLength(50);

        $venda->setBreakItems(2);
        $excluir->setBreakItems(2);
        $atendimento->setBreakItems(2);

        $venda->addItems(["S"=>"Sim","N"=>"Não"]);
        $editar->addItems(["S"=>"Sim","N"=>"Não"]);
        $retorno->addItems(["S"=>"Sim","N"=>"Não"]);
        $excluir->addItems(["S"=>"Sim","N"=>"Não"]);
        $cadastro->addItems(["S"=>"Sim","N"=>"Não"]);
        $atendimento->addItems(["S"=>"Sim","N"=>"Não"]);

        $venda->setLayout('horizontal');
        $editar->setLayout('horizontal');
        $retorno->setLayout('horizontal');
        $excluir->setLayout('horizontal');
        $cadastro->setLayout('horizontal');
        $atendimento->setLayout('horizontal');

        $venda->setUseButton();
        $editar->setUseButton();
        $retorno->setUseButton();
        $excluir->setUseButton();
        $cadastro->setUseButton();
        $atendimento->setUseButton();

        $id->setSize('100%');
        $cor->setSize('100%');
        $icone->setSize('100%');
        $venda->setSize('100%');
        $editar->setSize('100%');
        $cod_erp->setSize('100%');
        $retorno->setSize('100%');
        $excluir->setSize('100%');
        $cadastro->setSize('100%');
        $descricao->setSize('100%');
        $atendimento->setSize('100%');


        $row1 = $this->form->addFields([new TLabel("Id:", null, '14px', null, '100%'),$id],[new TLabel("Código:", '#F44336', '14px', null, '100%'),$cod_erp],[new TLabel("Descrição:", '#E91E63', '14px', null, '100%'),$descricao]);
        $row1->layout = [' col-sm-3',' col-sm-3','col-sm-6'];

        $row2 = $this->form->addFields([new TLabel("Icone:", null, '14px', null, '100%'),$icone],[new TLabel("Cor:", null, '14px', null, '100%'),$cor],[new TLabel("Gera Venda:", null, '14px', null, '100%'),$venda],[new TLabel("Atualiza Cadastro:", null, '14px', null, '100%'),$cadastro]);
        $row2->layout = ['col-sm-3','col-sm-3',' col-sm-3',' col-sm-3'];

        $row3 = $this->form->addFields([new TLabel("Gera Retorno:", null, '14px', null, '100%'),$retorno],[new TLabel("Lista em Atendimento:", null, '14px', null, '100%'),$atendimento],[new TLabel("Editar:", null, '14px', null, '100%'),$editar],[new TLabel("Excluir:", null, '14px', null, '100%'),$excluir]);
        $row3->layout = [' col-sm-3',' col-sm-3',' col-sm-3',' col-sm-3'];

        // create the form actions
        $btn_onsave = $this->form->addAction("Salvar", new TAction([$this, 'onSave']), 'fas:save #ffffff');
        $this->btn_onsave = $btn_onsave;
        $btn_onsave->addStyleClass('btn-primary'); 

        $btn_onshow = $this->form->addAction("Voltar", new TAction(['AtendimentoTipoList', 'onShow']), 'fas:arrow-left #000000');
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

            $object = new AtendimentoTipo(); // create an empty object 

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
            TApplication::loadPage('AtendimentoTipoList', 'onShow', $loadPageParam); 

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

                $object = new AtendimentoTipo($key); // instantiates the Active Record 

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

