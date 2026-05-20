<?php

class AtendimentoCalendarForm extends TWindow
{
    protected BootstrapFormBuilder $form;
    private $formFields = [];
    private static $database = 'erp_online';
    private static $activeRecord = 'Atendimento';
    private static $primaryKey = 'id';
    private static $formName = 'form_AtendimentoCalendarForm';
    private static $startDateField = 'horario_inicial';
    private static $endDateField = 'horario_final';

    /**
     * Form constructor
     * @param $param Request
     */
    public function __construct( $param )
    {
        parent::__construct();
        parent::setSize(0.50, null);
        parent::setTitle("Atendimento");
        parent::setProperty('class', 'window_modal');

        if(!empty($param['target_container']))
        {
            $this->adianti_target_container = $param['target_container'];
        }

        // creates the form
        $this->form = new BootstrapFormBuilder(self::$formName);
        // define the form title
        $this->form->setFormTitle("Atendimento");

        $view = new THidden('view');

        $criteria_atendimento_tipo_id = new TCriteria();

        $filterVar = 'S';
        $criteria_atendimento_tipo_id->add(new TFilter('atendimento', '=', $filterVar)); 

        $vendedor_id = TSession::getValue('vendedor_id');

        if(isset($vendedor_id)){
            TTransaction::open(self::$database);
            $oVendedor = Vendedor::find( $vendedor_id );
            TTransaction::close();

            if($oVendedor){

                $this->form->setFormTitle("Atendimento - ".$oVendedor->nome);

            }else{

                TToast::show("error", "Usuário não é Vendedor.", "center", "fas:info");
                return;
            }

        }else{
            TToast::show("error", "Usuário não é Vendedor.", "center", "fas:info");
            return;
        }

        $atendimento_tipo_id = new TDBCombo('atendimento_tipo_id', 'erp_online', 'AtendimentoTipo', 'id', '{descricao}','id asc' , $criteria_atendimento_tipo_id );
        $vendedor_id = new THidden('vendedor_id');
        $horario_inicial = new TDateTime('horario_inicial');
        $cliente_id = new THidden('cliente_id');
        $orcamento_id = new THidden('orcamento_id');
        $horario_final = new TDateTime('horario_final');
        $id = new THidden('id');
        $nota_saida_id = new THidden('nota_saida_id');
        $codigo_cliente = new TSeekButton('codigo_cliente');
        $btn_cliente = new TButton('btn_cliente');
        $nome = new TEntry('nome');
        $retorno = new TDateTime('retorno');
        $email = new TEntry('email');
        $status = new THidden('status');
        $telefone = new TEntry('telefone');
        $observacao = new TText('observacao');

        $codigo_cliente->setExitAction(new TAction([$this,'onExistCodigoCliente']));

        $atendimento_tipo_id->addValidation("Atendimento tipo id", new TRequiredValidator()); 
        $horario_inicial->addValidation("Horário inicial", new TRequiredValidator()); 
        $horario_final->addValidation("Horário final", new TRequiredValidator()); 

        $atendimento_tipo_id->enableSearch();
        $btn_cliente->setAction(new TAction([$this, 'onCliente']), "");
        $btn_cliente->addStyleClass('btn-success');
        $btn_cliente->setImage('fas:user #FFFFFF');
        $status->setValue('A');
        $vendedor_id->setValue(TSession::getValue('vendedor_id' ));

        $retorno->setMask('dd/mm/yyyy hh:ii');
        $horario_final->setMask('dd/mm/yyyy hh:ii');
        $horario_inicial->setMask('dd/mm/yyyy hh:ii');

        $retorno->setDatabaseMask('yyyy-mm-dd hh:ii');
        $horario_final->setDatabaseMask('yyyy-mm-dd hh:ii');
        $horario_inicial->setDatabaseMask('yyyy-mm-dd hh:ii');

        $nome->setMaxLength(100);
        $email->setMaxLength(100);
        $telefone->setMaxLength(50);

        $id->setSize(200);
        $status->setSize(200);
        $nome->setSize('100%');
        $email->setSize('100%');
        $cliente_id->setSize(200);
        $retorno->setSize('100%');
        $vendedor_id->setSize(200);
        $telefone->setSize('100%');
        $orcamento_id->setSize(200);
        $nota_saida_id->setSize(200);
        $horario_final->setSize('100%');
        $horario_inicial->setSize('100%');
        $observacao->setSize('100%', 110);
        $atendimento_tipo_id->setSize('100%');
        $codigo_cliente->setSize('calc(100% - 60px)');

        $seed = AdiantiApplicationConfig::get()['general']['seed'];
        $codigo_cliente_seekAction = new TAction(['ClienteSeekWindow', 'onShow']);
        $seekFilters = [];
        $seekFields = base64_encode(serialize([
            ['name'=> 'codigo_cliente', 'column'=>'{cod_erp}'],
            ['name'=> 'cliente_id', 'column'=>'{id}'],
            ['name'=> 'email', 'column'=>'{email}'],
            ['name'=> 'telefone', 'column'=>'{telefone1}'],
            ['name'=> 'nome', 'column'=>'{razao}']
        ]));

        $seekFilters = base64_encode(serialize($seekFilters));
        $codigo_cliente_seekAction->setParameter('_seek_filter_column', 'cod_erp');
        $codigo_cliente_seekAction->setParameter('_seek_fields', $seekFields);
        $codigo_cliente_seekAction->setParameter('_seek_filters', $seekFilters);
        $codigo_cliente_seekAction->setParameter('_seek_hash', md5($seed.$seekFields.$seekFilters));
        $codigo_cliente->setAction($codigo_cliente_seekAction);

        $row1 = $this->form->addFields([new TLabel("Atendimento Tipo:", '#ff0000', '14px', null, '100%'),$atendimento_tipo_id,$vendedor_id],[new TLabel("Horário inicial:", '#ff0000', '14px', null, '100%'),$horario_inicial,$cliente_id,$orcamento_id],[new TLabel("Horário final:", '#ff0000', '14px', null, '100%'),$horario_final,$id,$nota_saida_id]);
        $row1->layout = [' col-sm-4',' col-sm-4',' col-sm-4'];

        $row2 = $this->form->addFields([new TLabel("Cliente:", null, '14px', null, '100%'),$codigo_cliente,$btn_cliente],[new TLabel("Nome:", null, '14px', null, '100%'),$nome],[new TLabel("Retornar em:", null, '14px', null, '100%'),$retorno]);
        $row2->layout = [' col-sm-3',' col-sm-5',' col-sm-4'];

        $row3 = $this->form->addFields([new TLabel("E-Mail:", null, '14px', null, '100%'),$email,$status],[new TLabel("Telefone:", null, '14px', null, '100%'),$telefone]);
        $row3->layout = [' col-sm-6',' col-sm-6'];

        $row4 = $this->form->addFields([new TLabel("Observações:", null, '14px', null, '100%'),$observacao]);
        $row4->layout = [' col-sm-12'];

        $this->form->addFields([$view]);

        // create the form actions
        $btn_fechar = $this->form->addAction("Fechar", new TAction([$this, 'onFechar']), 'fas:window-close #000000');
        $this->btn_fechar = $btn_fechar;

        $btn_salvar = $this->form->addAction("Salvar", new TAction([$this, 'onSave']), 'fas:save #ffffff');
        $this->btn_salvar = $btn_salvar;
        $btn_salvar->addStyleClass('btn-info'); 

        $btn_orcamento = $this->form->addAction("Orçamento", new TAction([$this, 'onOrcamento']), 'fas:cart-plus #FFFFFF');
        $this->btn_orcamento = $btn_orcamento;
        $btn_orcamento->addStyleClass('btn-success'); 

        $btn_venda = $this->form->addAction("Venda", new TAction([$this, 'onVenda']), 'fas:shopping-cart #FFFFFF');
        $this->btn_venda = $btn_venda;
        $btn_venda->addStyleClass('btn-success'); 

        $btn_excluir = $this->form->addAction("Excluir", new TAction([$this, 'onDelete']), 'fas:trash-alt #FFFFFF');
        $this->btn_excluir = $btn_excluir;
        $btn_excluir->addStyleClass('btn-danger'); 

        $btn_notafiscal = $this->form->addAction("Nota Fiscal", new TAction([$this, 'onNotaFiscal']), 'fas:print #000000');
        $this->btn_notafiscal = $btn_notafiscal;

        parent::add($this->form);

    }

    public static function onExistCodigoCliente($param = null) 
    {
        try 
        {

            //$oTela = new stdClass();
            //$oTela->codigo_cliente = $param['codigo_cliente'];
            //$object->fieldName = 'insira o novo valor aqui'; //sample
            //TForm::sendData(self::$formName, $oTela, true, false, 400);

        }
        catch (Exception $e) 
        {
            new TMessage('error', $e->getMessage());    
        }
    }

    public static function onCliente($param = null) 
    {
        try 
        {
            if(isset($param['cliente_id']) and !empty($param['cliente_id'])){

                 TTransaction::open(self::$database);
                 $oCliente = Cliente::find( $param['cliente_id'] );
                 TTransaction::close();

                 if($oCliente){

                    $pageParam = ['key' => $oCliente->id]; // ex.: = ['key' => 10]
                    TApplication::loadPage('ClienteForm', 'onEdit', $pageParam);

                 }

            }else{

                TToast::show("error", "Sem Código de Cliente Informado", "topRight", "");

            }

        }
        catch (Exception $e) 
        {
            new TMessage('error', $e->getMessage());    
        }
    }

    public function onFechar($param = null) 
    {
        try 
        {
            TWindow::closeWindow(parent::getId());

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

            $object = new Atendimento(); // create an empty object 

            $data = $this->form->getData(); // get form data as array
            $object->fromArray( (array) $data); // load the object with data

            if($object->atendimento_tipo->editar == "N"){

                new TMessage('error', "Tipo de Atendimento não pode ser alterado");
                TTransaction::close();
                TWindow::closeWindow(parent::getId());
                return;
            }

            if(isset($object->atendimento_tipo->nota_saida_id)){
                new TMessage('error', "Tipo de Atendimento não pode ser alterado");
                TTransaction::close();
                TWindow::closeWindow(parent::getId());
                return;
            }

            $object->store(); // save the object 

            $this->fireEvents($object);

            $messageAction = new TAction(['AtendimentoCalendarFormView', 'onReload']);
            $messageAction->setParameter('view', $data->view);
            $messageAction->setParameter('date', explode(' ', $data->horario_inicial)[0]);

            // get the generated {PRIMARY_KEY}
            $data->id = $object->id; 

            $this->form->setData($data); // fill form data
            TTransaction::close(); // close the transaction

            new TMessage('info', "Registro salvo", $messageAction); 

                TWindow::closeWindow(parent::getId()); 

        }
        catch (Exception $e) // in case of exception
        {

            $this->fireEvents($this->form->getData());  

            new TMessage('error', $e->getMessage()); // shows the exception error message
            $this->form->setData( $this->form->getData() ); // keep form data
            TTransaction::rollback(); // undo all pending operations
        }
    }
    public function onOrcamento($param = null) 
    {
        try 
        {
            //code here

        }
        catch (Exception $e) 
        {
            new TMessage('error', $e->getMessage());    
        }
    }
    public function onVenda($param = null) 
    {
        try 
        {
            //code here

        }
        catch (Exception $e) 
        {
            new TMessage('error', $e->getMessage());    
        }
    }
    public function onDelete($param = null) 
    {
        if(isset($param['delete']) && $param['delete'] == 1)
        {
            try
            {
                $key = $param[self::$primaryKey];

                // open a transaction with database
                TTransaction::open(self::$database);

                $class = self::$activeRecord;

                // instantiates object
                $object = new $class($key, FALSE);

                // deletes the object from the database
                $object->delete();

                // close the transaction
                TTransaction::close();

                $messageAction = new TAction(array(__CLASS__.'View', 'onReload'));
                $messageAction->setParameter('view', $param['view']);
                $messageAction->setParameter('date', explode(' ',$param[self::$startDateField])[0]);

                // shows the success message
                new TMessage('info', AdiantiCoreTranslator::translate('Record deleted'), $messageAction);
            }
            catch (Exception $e) // in case of exception
            {
                // shows the exception error message
                new TMessage('error', $e->getMessage());
                // undo all pending operations
                TTransaction::rollback();
            }
        }
        else
        {
            $excluir = 'N';
            $key = $param[self::$primaryKey];

            if(isset($key)){
                TTransaction::open(self::$database);
                $class = self::$activeRecord;
                $object = new $class($key, FALSE);
                $excluir = $object->atendimento_tipo->excluir;
                TTransaction::close();

                if(isset($object->atendimento_tipo->nota_saida_id)){
                    $excluir = "N";
                }
            }

            if($excluir == "N"){

                new TMessage('error', "Tipo de Atendimento não pode ser Excluido.");
                TWindow::closeWindow(parent::getId());
                return;

            }else{
                // define the delete action
                $action = new TAction(array($this, 'onDelete'));
                $action->setParameters((array) $this->form->getData());
                $action->setParameter('delete', 1);
                // shows a dialog to the user
                new TQuestion(AdiantiCoreTranslator::translate('Do you really want to delete ?'), $action);   
            }
        }
    }
    public static function onNotaFiscal($param = null) 
    {
        try 
        {

            if(isset($param['nota_saida_id'])){
                $pageParam = ['key'=> $param['nota_saida_id'],'id'=> $param['nota_saida_id'],'atendimento'=> true];

                TApplication::loadPage('NotaSaidaFormView', 'onShow', $pageParam);
            }

        }
        catch (Exception $e) 
        {
            new TMessage('error', $e->getMessage());    
        }
    }

    public function onEdit( $param )
    {
        try
        {
            //TToast::show("error", "onEdit", "center", "fas:info");

            if (isset($param['key']))
            {
                $vendedor_id = TSession::getValue('vendedor_id' );

                if(isset($vendedor_id)){

                    TTransaction::open(self::$database);
                    $oVendedorAtendimento = VendedorAtendimento::where('vendedor_id', '=', $vendedor_id)->first();
                    TTransaction::close();

                    if($oVendedorAtendimento){
                        //"agendaWeek" = Semana //month = Mes //agendaDay = dia //listWeek = Agenda;
                        if ($oVendedorAtendimento->tipo == "S"){
                            $param['view'] = "agendaWeek";
                        }elseif ($oVendedorAtendimento->tipo == "M"){
                            $param['view'] = "month";
                        }elseif ($oVendedorAtendimento->tipo == "D"){
                            $param['view'] = "agendaDay";
                        }elseif ($oVendedorAtendimento->tipo == "A"){
                            $param['view'] = "listWeek";
                        }  
                    }

                }else{
                    TToast::show("error", "Usuário não é Vendedor.", "center", "fas:info");
                    return;
                }

                $key = $param['key'];  // get the parameter $key
                TTransaction::open(self::$database); // open a transaction

                $object = new Atendimento($key); // instantiates the Active Record 

                $oVendedor = Vendedor::find( $object->vendedor_id );

                if($oVendedor){

                    //parent::setTitle("Atendimento - ".$oVendedor->nome);

                }

                                $object->view = !empty($param['view']) ? $param['view'] : 'agendaWeek'; 

                $this->form->setData($object); // fill the form 

                if(isset($object->id)){

                    if($object->status == "E"){

                        TButton::disableField(self::$formName, 'btn_cliente');
                        TButton::disableField(self::$formName, 'btn_venda');
                        TButton::disableField(self::$formName, 'btn_excluir');
                        TButton::disableField(self::$formName, 'btn_orcamento');
                        TButton::disableField(self::$formName, 'btn_salvar');
                        $this->btn_orcamento->style = 'display:none';
                        $this->btn_venda->style   = 'display:none';
                        $this->btn_excluir->style = 'display:none';
                        $this->btn_salvar->style  = 'display:none';
                        $this->btn_cliente->style = 'display:none';
                        $this->form->setEditable(FALSE);

                    }else{
                        if($object->atendimento_tipo->editar == "N"){
                            TButton::disableField(self::$formName, 'btn_cliente');
                            TButton::disableField(self::$formName, 'btn_orcamento');
                            TButton::disableField(self::$formName, 'btn_excluir');
                            TButton::disableField(self::$formName, 'btn_salvar');
                            $this->btn_orcamento->style = 'display:none';
                            $this->btn_excluir->style = 'display:none';
                            $this->btn_salvar->style  = 'display:none';
                            $this->btn_cliente->style = 'display:none';
                            if(isset($object->atendimento_tipo->nota_saida_id)){

                            }else{
                                TButton::disableField(self::$formName, 'btn_salvar');
                                $this->btn_venda->style = 'display:none';
                            }
                            $this->form->setEditable(FALSE);
                        }

                        if($object->atendimento_tipo->excluir == "N"){
                            TButton::disableField(self::$formName, 'btn_excluir');
                            $this->btn_excluir->style = 'display:none';
                        }

                        if($object->atendimento_tipo->venda == "N"){
                            TButton::disableField(self::$formName, 'btn_orcamento');
                            $this->btn_orcamento->style = 'display:none';
                        }

                        if($object->atendimento_tipo->cadastro == "N"){
                            TButton::disableField(self::$formName, 'btn_cliente');
                            $this->btn_cliente->style = 'display:none';
                        }
                        if($object->atendimento_tipo->retorno == "N"){
                            TDateTime::disableField(self::$formName, 'retorno');
                        }else{
                            TDateTime::enableField(self::$formName, 'retorno');
                        }
                    }
                }else{
                    $this->btn_venda->style   = 'display:none';
                    $this->btn_excluir->style = 'display:none';
                    TButton::disableField(self::$formName, 'btn_venda');
                    TButton::disableField(self::$formName, 'btn_excluir');
                }

                if(isset($object->orcamento_id)){
                    $this->btn_excluir->style = 'display:none';
                    TButton::disableField(self::$formName, 'btn_excluir');
                }else{
                    $this->btn_orcamento->style = 'display:none';
                    TButton::disableField(self::$formName, 'btn_orcamento');
                }

                if(isset($object->nota_saida_id)){
                    $this->btn_excluir->style = 'display:none';
                    TButton::disableField(self::$formName, 'btn_excluir');
                }else{
                    TButton::disableField(self::$formName, 'btn_notafiscal');
                    $this->btn_notafiscal->style = 'display:none';
                }

                $this->fireEvents($object);

                TTransaction::close(); // close the transaction 
            }
            else
            {
                $this->btn_notafiscal->style   = 'display:none';
                $this->btn_orcamento->style   = 'display:none';
                $this->btn_venda->style   = 'display:none';
                $this->btn_excluir->style = 'display:none';
                TButton::disableField(self::$formName, 'btn_notafiscal');
                TButton::disableField(self::$formName, 'btn_excluir');
                TButton::disableField(self::$formName, 'btn_orcamento');
                TButton::disableField(self::$formName, 'btn_venda');
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

        TToast::show("error", "onClear", "center", "fas:info");

    }

    public function onShow($param = null)
    {

        //TToast::show("error", "onShow", "center", "fas:info");

        $vendedor_id = TSession::getValue('vendedor_id' );

        if(isset($vendedor_id)){
            TTransaction::open(self::$database);
            $oVendedor = Vendedor::find( $vendedor_id );
            TTransaction::close();

            if($oVendedor){
                if($oVendedor->vendedor == 'N'){
                    TToast::show("error", "Usuário não é Vendedor.", "center", "fas:info");
                    return;   
                }
            }else{
                TToast::show("error", "Usuário não é Vendedor.", "center", "fas:info");
                return;
            }

        }else{
            TToast::show("error", "Usuário não é Vendedor.", "center", "fas:info");
            return;
        }

    } 

    public function fireEvents( $object )
    {
        $obj = new stdClass;
        if(is_object($object) && get_class($object) == 'stdClass')
        {
            if(isset($object->codigo_cliente))
            {
                $value = $object->codigo_cliente;

                $obj->codigo_cliente = $value;
            }
        }
        elseif(is_object($object))
        {
            if(isset($object->codigo_cliente))
            {
                $value = $object->codigo_cliente;

                $obj->codigo_cliente = $value;
            }
        }
        TForm::sendData(self::$formName, $obj);
    }  

    public function onStartEdit($param)
    {

        $this->form->clear(true);

        $data = new stdClass;
        $data->view = $param['view'] ?? 'agendaWeek'; // calendar view
        $data->atendimento_tipo = new stdClass();
        $data->atendimento_tipo->cor = '#3a87ad';

        if (!empty($param['date']))
        {
            if(strlen($param['date']) == '10')
                $param['date'].= ' 09:00';

            $data->horario_inicial = str_replace('T', ' ', $param['date']);

            $horario_final = new DateTime($data->horario_inicial);
            $horario_final->add(new DateInterval('PT1H'));
            $data->horario_final = $horario_final->format('Y-m-d H:i:s');

            $horario_final->add(new DateInterval('PT30M'));
            $data->horario_final = $horario_final->format('Y-m-d H:i:s');
            //TToast::show("error", "onStartEdit", "center", "fas:info");

            $this->btn_notafiscal->style   = 'display:none';
            $this->btn_orcamento->style   = 'display:none';
            $this->btn_venda->style   = 'display:none';
            $this->btn_excluir->style = 'display:none';
            TButton::disableField(self::$formName, 'btn_notafiscal');
            TButton::disableField(self::$formName, 'btn_excluir');
            TButton::disableField(self::$formName, 'btn_orcamento');
            TButton::disableField(self::$formName, 'btn_venda');

        }

        $this->form->setData( $data );
    }

    public static function onUpdateEvent($param)
    {
        try
        {
            if (isset($param['id']))
            {
                TTransaction::open(self::$database);

                $class = self::$activeRecord;
                $object = new $class($param['id']);

                $object->horario_inicial = str_replace('T', ' ', $param['start_time']);
                $object->horario_final   = str_replace('T', ' ', $param['end_time']);

                if($object->atendimento_tipo->editar == "N"){//if($object->atendimento_tipo_id == AtendimentoTipo::VENDA){
                    //new TMessage('info', '<b>Informação</b> '.$object->atendimento_tipo->descricao.' não podem ser alteradas.' );
                    TToast::show("info", '<b>Informações:</b> Atendimento do tipo'.$object->atendimento_tipo->descricao.' não podem ser alterado.' , "topRight", "");
                    TTransaction::rollback();
                    $pageParam = []; // ex.: = ['key' => 10]
                    TApplication::loadPage(__CLASS__.'View', 'onReload', $pageParam);
                    return;

                    /*
                    const VENDA = '1';
                    const CADASTRO = '2';
                    const PROSPECT = '3';
                    const COBRANCA = '4';
                    const REATIVACAO = '5';
                    const CAMPANHA = '6';
                    const RETORNO = '7';                    
                    */

                }

                $object->store();

                // close the transaction
                TTransaction::close();
            }
        }
        catch (Exception $e) // in case of exception
        {
            new TMessage('error', '<b>Error</b> ' . $e->getMessage());
            TTransaction::rollback();
        }
    }

    public static function getFormName()
    {
        return self::$formName;
    }

}

