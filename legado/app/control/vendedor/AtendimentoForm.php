<?php

class AtendimentoForm extends TPage
{
    protected BootstrapFormBuilder $form;
    private $formFields = [];
    private static $database = 'erp_online';
    private static $activeRecord = 'Atendimento';
    private static $primaryKey = 'id';
    private static $formName = 'form_AtendimentoForm';

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
        $this->form->setFormTitle("Atendimento");

        $criteria_atendimento_tipo_id = new TCriteria();

        $atendimento_tipo_id = new TDBCombo('atendimento_tipo_id', 'erp_online', 'AtendimentoTipo', 'id', '{descricao}','id asc' , $criteria_atendimento_tipo_id );
        $id = new THidden('id');
        $horario_inicial = new TDateTime('horario_inicial');
        $vendedor_id = new TEntry('vendedor_id');
        $cliente_id = new TEntry('cliente_id');
        $observacao = new TText('observacao');

        $atendimento_tipo_id->addValidation("Atendimento tipo id", new TRequiredValidator()); 
        $horario_inicial->addValidation("Horário inicial", new TRequiredValidator()); 

        $atendimento_tipo_id->enableSearch();
        $horario_inicial->setMask('dd/mm/yyyy hh:ii');
        $horario_inicial->setDatabaseMask('yyyy-mm-dd hh:ii');
        $horario_inicial->setValue(date('d/m/Y H:i:s'));
        $cliente_id->setValue($param["cliente_id"] ?? "");

        $cliente_id->setEditable(false);
        $vendedor_id->setEditable(false);
        $horario_inicial->setEditable(false);

        $id->setSize(200);
        $cliente_id->setSize('100%');
        $vendedor_id->setSize('100%');
        $observacao->setSize('100%', 70);
        $horario_inicial->setSize('100%');
        $atendimento_tipo_id->setSize('100%');

        $row1 = $this->form->addFields([new TLabel("Atendimento:", '#ff0000', '14px', null, '100%'),$atendimento_tipo_id,$id],[new TLabel("Data", '#ff0000', '14px', null, '100%'),$horario_inicial,$vendedor_id,$cliente_id]);
        $row1->layout = ['col-sm-6','col-sm-6'];

        $row2 = $this->form->addFields([new TLabel("Observação:", null, '14px', null, '100%'),$observacao]);
        $row2->layout = [' col-sm-12'];

        // create the form actions

        $btn_onsave = $this->form->addHeaderAction("Salvar", new TAction([$this, 'onSave']), 'fas:save #ffffff');
        $this->btn_onsave = $btn_onsave;
        $btn_onsave->addStyleClass('btn-primary'); 

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

            $object = new Atendimento(); // create an empty object 

            $data = $this->form->getData(); // get form data as array
            $object->fromArray( (array) $data); // load the object with data

            $object->store(); // save the object 

            // get the generated {PRIMARY_KEY}
            $data->id = $object->id; 

            $this->form->setData($data); // fill form data
            TTransaction::close(); // close the transaction

            new TMessage('info', "Registro salvo", $messageAction); 

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

                $object = new Atendimento($key); // instantiates the Active Record 

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

