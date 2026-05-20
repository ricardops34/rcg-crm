<?php

class VendedorAtendimentoForm extends TWindow
{
    protected BootstrapFormBuilder $form;
    private $formFields = [];
    private static $database = 'erp_online';
    private static $activeRecord = 'VendedorAtendimento';
    private static $primaryKey = 'id';
    private static $formName = 'form_VendedorAtendimentoForm';

    /**
     * Form constructor
     * @param $param Request
     */
    public function __construct( $param )
    {
        parent::__construct();
        parent::setSize(0.8, null);
        parent::setTitle("VendedorAtendimento");
        parent::setProperty('class', 'window_modal');

        if(!empty($param['target_container']))
        {
            $this->adianti_target_container = $param['target_container'];
        }

        // creates the form
        $this->form = new BootstrapFormBuilder(self::$formName);
        // define the form title
        $this->form->setFormTitle("VendedorAtendimento");

        $criteria_vendedor_id = new TCriteria();

        $id = new THidden('id');
        $vendedor_id = new TDBCombo('vendedor_id', 'erp_online', 'Vendedor', 'id', '{nome}','nome asc' , $criteria_vendedor_id );
        $dias_semana = new TCheckGroup('dias_semana');
        $inicial = new TTime('inicial');
        $final = new TTime('final');
        $tipo = new TCombo('tipo');

        $vendedor_id->addValidation("Vendedor id", new TRequiredValidator()); 

        $vendedor_id->setEditable(false);
        $dias_semana->setLayout('horizontal');
        $dias_semana->setValueSeparator(',');
        $dias_semana->setBreakItems(4);
        $tipo->setValue('S');
        $tipo->enableSearch();
        $vendedor_id->enableSearch();

        $tipo->addItems(["M"=>"Mês ","S"=>"Semana","D"=>"Dia","A"=>"Agenda"]);
        $dias_semana->addItems(["0"=>"Domingo","1"=>"Segunda","2"=>"Terça Feira","3"=>"Quarta Feira","4"=>"Quinta Feira","5"=>"Sexta Feira","6"=>"Sabado"]);

        $id->setSize(200);
        $tipo->setSize('100%');
        $final->setSize('100%');
        $inicial->setSize('100%');
        $vendedor_id->setSize('100%');
        $dias_semana->setSize('100%');

        $row1 = $this->form->addFields([new TLabel("Vendedor id:", '#ff0000', '14px', null, '100%'),$id,$vendedor_id],[$dias_semana]);
        $row1->layout = [' col-sm-8',' col-sm-4'];

        $row2 = $this->form->addFields([new TLabel("Horário Inicial:", null, '14px', null, '100%'),$inicial],[new TLabel("Horário Final:", null, '14px', null, '100%'),$final],[new TLabel("Tipo de Calendário:", null, '14px', null, '100%'),$tipo]);
        $row2->layout = [' col-sm-4',' col-sm-4',' col-sm-4'];

        // create the form actions
        $btn_onsave = $this->form->addAction("Salvar", new TAction([$this, 'onSave']), 'fas:save #ffffff');
        $this->btn_onsave = $btn_onsave;
        $btn_onsave->addStyleClass('btn-primary'); 

        $btn_onclear = $this->form->addAction("Limpar formulário", new TAction([$this, 'onClear']), 'fas:eraser #dd5a43');
        $this->btn_onclear = $btn_onclear;

        parent::add($this->form);

    }

    public function onSave($param = null) 
    {
        try
        {
            TTransaction::open(self::$database); // open a transaction

            $messageAction = null;

            $this->form->validate(); // validate form data

            $object = new VendedorAtendimento(); // create an empty object 

            $data = $this->form->getData(); // get form data as array
            $object->fromArray( (array) $data); // load the object with data

            $object->store(); // save the object 

            // get the generated {PRIMARY_KEY}
            $data->id = $object->id; 

            $this->form->setData($data); // fill form data
            TTransaction::close(); // close the transaction

            new TMessage('info', "Registro salvo", $messageAction); 

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

            TTransaction::open(self::$database); // open a transaction

            if (isset($param['vendedor_id'])){

                $oVendedorAtendimento = VendedorAtendimento::where('vendedor_id', '=', $param['vendedor_id'])->first();

                if($oVendedorAtendimento){
                    $param['key'] = $oVendedorAtendimento->id;
                }else{

                    $oVendedorAtendimento = new VendedorAtendimento();
                    $oVendedorAtendimento->tipo = "S";
                    //$oVendedorAtendimento->inicial = strtotime("07:30:00");
                    //$oVendedorAtendimento->final = strtotime("18:00:00");
                    $oVendedorAtendimento->vendedor_id = $param['vendedor_id'];
                    $oVendedorAtendimento->store();

                    $param['key'] = $oVendedorAtendimento->id;
                }

            }else{
            }

            if (isset($param['key']))
            {
                $key = $param['key'];  // get the parameter $key

                $object = new VendedorAtendimento($key); // instantiates the Active Record 

                $this->form->setData($object); // fill the form 

            }
            else
            {
                $this->form->clear();
            }

            TTransaction::close(); // close the transaction 

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

