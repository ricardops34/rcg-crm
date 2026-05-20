<?php

class TitulosClienteNegociacaoSimpleList extends TPage
{

    private $datagrid; // listing
    private $pageNavigation;
    private $loaded;
    private static $database = 'erp_online';
    private static $activeRecord = 'ViewTituloCliente';
    private static $primaryKey = 'id';
    private static $formName = 'formList_ViewTituloCliente';
    private $limit = 20;

    use BuilderDatagridTrait;

    public function __construct($param = null)
    {
        parent::__construct();

        if(!empty($param['target_container']))
        {
            $this->adianti_target_container = $param['target_container'];
        }

        $this->limit = 0;

        // Cria uma coluna para a lista
        //$column_check = new TDataGridColumn('check', 'Check', 'center');

        // creates a Datagrid
        $this->datagrid = new TDataGrid;
        $this->datagrid->enableUserProperties('fa fa-cog', 'btn btn-default', new TAction([$this, 'setDatagridProperties']));
        $this->datagrid->setId(__CLASS__.'_datagrid');

        $this->datagrid_form = new TForm('datagrid_'.self::$formName);
        $this->datagrid_form->onsubmit = 'return false';

        $this->datagrid = new BootstrapDatagridWrapper($this->datagrid);

        $this->datagrid->disableDefaultClick();
        $this->datagrid->style = 'width: 100%';
        $this->datagrid->setHeight(320);

        $column_id = new TDataGridColumn('id', "Id", 'center' , '70px');
        $column_prefixo = new TDataGridColumn('prefixo', "Prefixo", 'left');
        $column_tipo = new TDataGridColumn('tipo', "Tipo", 'left');
        $column_numero = new TDataGridColumn('numero', "Numero", 'left');
        $column_parcela = new TDataGridColumn('parcela', "Parcela", 'left');
        $column_emissao_transformed = new TDataGridColumn('emissao', "Emissao", 'left');
        $column_vencimento_transformed = new TDataGridColumn('vencimento', "Vencimento", 'left');
        $column_saldo_transformed = new TDataGridColumn('saldo', "Saldo", 'right');
        $column_valor_transformed = new TDataGridColumn('valor', "Valor", 'right');
        $column_dias_transformed = new TDataGridColumn('dias', "Atraso", 'center');

        $column_saldo_transformed->setTotalFunction( function($values) { 
            return array_sum((array) $values); 
        }); 

        $column_emissao_transformed->setTransformer(function($value, $object, $row, $cell = null, $last_row = null)
        {
            if(!empty(trim((string) $value)))
            {
                try
                {
                    $date = new DateTime($value);
                    return $date->format('d/m/Y');
                }
                catch (Exception $e)
                {
                    return $value;
                }
            }
        });

        $column_vencimento_transformed->setTransformer(function($value, $object, $row, $cell = null, $last_row = null)
        {
            if(!empty(trim((string) $value)))
            {
                try
                {
                    $date = new DateTime($value);
                    return $date->format('d/m/Y');
                }
                catch (Exception $e)
                {
                    return $value;
                }
            }
        });

        $column_saldo_transformed->setTransformer(function($value, $object, $row, $cell = null, $last_row = null)
        {
            if(!$value)
            {
                $value = 0;
            }

            if(is_numeric($value))
            {
                return "R$ " . number_format($value, 2, ",", ".");
            }
            else
            {
                return $value;
            }
        });

        $column_valor_transformed->setTransformer(function($value, $object, $row, $cell = null, $last_row = null)
        {
            if(!$value)
            {
                $value = 0;
            }

            if(is_numeric($value))
            {
                return "R$ " . number_format($value, 2, ",", ".");
            }
            else
            {
                return $value;
            }
        });

        $column_dias_transformed->setTransformer(function($value, $object, $row, $cell = null, $last_row = null)
        {
            $dias = $value;
            $label = $dias;
            $cor = 'Red';

            if($dias > 1 ){
                $row->style = "background: #FFF9A7";
                $icone = new TElement('i');
                $icone->style="; color: red;"; 
                $icone->class="fas fa-dollar-sign";
            }elseif($dias <= 1){
                $icone = new TElement('i');
                $icone->style="; color: green;"; 
                $icone->class="fas fa-dollar-sign";
            }

            if($dias >= 60 ){
                $class = 'danger';
            }elseif($dias >= 01 and $dias < 60){
                $class = 'warning';
            }elseif($dias <= 00){
                $class = 'success';
                $label = "Aberto";
            }

            $div = new TElement('span');
            $div->class="label label-{$class}";
            $div->style="width:120px; text-shadow:none; font-size:12px; font-weight:lighter";
            //$div->add($icone);
            $div->add($label);
            return $div;

        });        

        $order_id = new TAction(array($this, 'onReload'));
        $order_id->setParameter('order', 'id');
        $column_id->setAction($order_id);
        $order_prefixo = new TAction(array($this, 'onReload'));
        $order_prefixo->setParameter('order', 'prefixo');
        $column_prefixo->setAction($order_prefixo);
        $order_tipo = new TAction(array($this, 'onReload'));
        $order_tipo->setParameter('order', 'tipo');
        $column_tipo->setAction($order_tipo);
        $order_numero = new TAction(array($this, 'onReload'));
        $order_numero->setParameter('order', 'numero');
        $column_numero->setAction($order_numero);
        $order_emissao_transformed = new TAction(array($this, 'onReload'));
        $order_emissao_transformed->setParameter('order', 'emissao');
        $column_emissao_transformed->setAction($order_emissao_transformed);
        $order_vencimento_transformed = new TAction(array($this, 'onReload'));
        $order_vencimento_transformed->setParameter('order', 'vencimento');
        $column_vencimento_transformed->setAction($order_vencimento_transformed);

        //$column_cliente_id = new THidden('cliente_id'); //new TDataGridColumn('cliente_id', "Cliente id", 'left');        

        $this->builder_datagrid_check_all = new TCheckButton('builder_datagrid_check_all');
        $this->builder_datagrid_check_all->setIndexValue('on');
        $this->builder_datagrid_check_all->onclick = "Builder.checkAll(this)";
        $this->builder_datagrid_check_all->style = 'cursor:pointer';
        $this->builder_datagrid_check_all->setProperty('class', 'filled-in');
        $this->builder_datagrid_check_all->id = 'builder_datagrid_check_all';

        $label = new TLabel('');
        $label->style = 'margin:0';
        $label->class = 'checklist-label';
        $this->builder_datagrid_check_all->after($label);
        $label->for = 'builder_datagrid_check_all';

        $this->builder_datagrid_check = $this->datagrid->addColumn( new TDataGridColumn('builder_datagrid_check', $this->builder_datagrid_check_all, 'center',  '1%') );

        $this->datagrid->addColumn($column_id);
        $this->datagrid->addColumn($column_prefixo);
        $this->datagrid->addColumn($column_tipo);
        $this->datagrid->addColumn($column_numero);
        $this->datagrid->addColumn($column_parcela);
        $this->datagrid->addColumn($column_emissao_transformed);
        $this->datagrid->addColumn($column_vencimento_transformed);
        $this->datagrid->addColumn($column_saldo_transformed);
        $this->datagrid->addColumn($column_valor_transformed);
        $this->datagrid->addColumn($column_dias_transformed);

        //$this->datagrid->addColumn($column_cliente_id);
        $this->datagrid->disableDefaultClick();

        $action_onView = new TDataGridAction(array('TituloReceberForm', 'onView'));
        $action_onView->setUseButton(true);
        $action_onView->setButtonClass('btn btn-default btn-sm');
        $action_onView->setLabel("Visualizar");
        $action_onView->setImage('fas:search #03A9F4');
        $action_onView->setField(self::$primaryKey);

        $action_onView->setParameter('key', '{id}');

        $this->datagrid->addAction($action_onView);

        $this->applyDatagridProperties();

        // create the datagrid model
        $this->datagrid->createModel();

        $panel = new TPanelGroup();
        $panel->datagrid = 'datagrid-container';
        $this->datagridPanel = $panel;
        $this->datagrid_form->add($this->datagrid);
        $panel->add($this->datagrid_form);

        $panel->getBody()->class .= ' table-responsive';

        $headerActions = new TElement('div');
        $headerActions->class = ' datagrid-header-actions ';
        $headerActions->style = 'justify-content: space-between;';

        $head_left_actions = new TElement('div');
        $head_left_actions->class = ' datagrid-header-actions-left-actions ';

        $head_right_actions = new TElement('div');
        $head_right_actions->class = ' datagrid-header-actions-left-actions ';

        $headerActions->add($head_left_actions);
        $headerActions->add($head_right_actions);

        $panel->getBody()->insert(0, $headerActions);

        $button_gerar_cobranca = new TButton('button_button_gerar_cobranca');
        $button_gerar_cobranca->setAction(new TAction(['TitulosClienteNegociacaoSimpleList', 'onGerar']), "Gerar Cobrança");
        $button_gerar_cobranca->addStyleClass('btn-success');
        $button_gerar_cobranca->setImage('fas:play #FFFFFF');
        if(!empty($param["cliente_id"]))
        {
            $button_gerar_cobranca->getAction()->setParameter("cliente_id", $param["cliente_id"]);
        }

        $this->datagrid_form->addField($button_gerar_cobranca);

        $head_left_actions->add($button_gerar_cobranca);

        parent::setTargetContainer('adianti_right_panel');

        $btnClose = new TButton('closeCurtain');
        $btnClose->class = 'btn btn-sm btn-default';
        $btnClose->style = 'margin-right:10px;';
        $btnClose->onClick = "Template.closeRightPanel();";
        $btnClose->setLabel("Fechar");
        $btnClose->setImage('fas:times');

        $form = new BootstrapFormBuilder(self::$formName);
        $form->setTagName('div');
        $form->setFormTitle('&nbsp;');
        $form->addContent([$panel]);
        $form->addHeaderWidget($btnClose);

        // $btnClose->style = 'display:none';
        /*
        // Criar um novo formulário para receber a lista
        $this->formgrid = new TForm;
        $this->formgrid->add($this->datagrid);

        $button = TButton::create('acao_manipulacao', [$this, 'manipularSelecao'], 'Proximo', 'fa:check green');
        $this->formgrid->addField($button);
        $this->formgrid->addField($btnClose);

        $panel->getBody()->clearChildren();
        $panel->add($this->formgrid);

        $form->addHeaderWidget($button);
        //$panel->add($btnFechar);
        //$panel->add($button);

        $tstep = new TStep();
        $tstep->addItem('Titulos' , true , false);
        $tstep->addItem('Mensagem', false, false);
        $tstep->addItem('Envio'   , false, false);
        parent::add($tstep);
        */

        parent::add($form);

        $style = new TStyle('right-panel > .container-part[page-name=TitulosClienteNegociacaoSimpleList]');
        $style->width = '70% !important';   
        $style->show(true);

    }

    public function onGerar($param = null) 
    {
        try 
        {

            $lGerar = False;
            $nTitulo = 0;
            $cliente_id = $param['cliente_id'];
            $aNeg['cliente_id'] = $cliente_id;     

            if(isset($param['builder_datagrid_check'])){

                $aTitulos = $param['builder_datagrid_check'];
                //echo $cliente_id  . "<br>";
                if(isset($aTitulos)){
                    foreach ($aTitulos as $aTitulo) 
                    {
                        $nTitulo += 1;
                        $aNeg['titulos'][] = (int) $aTitulo;
                    }
                }

                //var_dump($aNeg);

                if($nTitulo > 0){
                    $lGerar = True;
                }

                if($nTitulo == 0){
                    new TAlert('warning', 'Para gerar cobrança é necessário selecionar pelo menos os títulos vencidos.');
                }else{

                    TTransaction::open('erp_online');

                    //echo $aTitulo . "\n";

                    $oTitulos = TituloReceber::where('cliente_id', '=', $cliente_id)
                        ->where('saldo'     , '>' , 0)
                        ->where('venc_real' , '<' , date('Y-m-d'))                    
                        ->where('reg_ativo' , '=' , 'S')
                        ->where('id'        , 'not in', $aNeg['titulos'])
                        ->first();

                    //var_dump($oTitulos);

                    if($oTitulos){
                        $lGerar = False;
                        foreach ($oTitulos as $oTitulo) {

                        }
                    }

                    TTransaction::close();

                    if($lGerar){
                        // Código gerado pelo snippet: "Questionamento"
                        new TQuestion("Confirmar a gravação da cobrança para os títulos selecionados?", new TAction([__CLASS__, 'onYes'], $aNeg), new TAction([__CLASS__, 'onNo'], $param));
                        // -----
                    }else{
                        new TMessage('warning', "Para gerar cobrança é necessário selecionar todos os títulos vencidos.");
                    }
                }
            }else{
                new TMessage('warning', 'Para gerar cobrança é necessário selecionar pelo menos os títulos vencidos.');
            }

            //</autoCode>
        }
        catch (Exception $e) 
        {
            new TMessage('error', $e->getMessage());    
        }
    }

    /**
     * Load the datagrid with data
     */
    public function onReload($param = NULL)
    {
        try
        {
            // open a transaction with database 'erp_online'
            TTransaction::open(self::$database);

            // creates a repository for ViewTituloCliente
            $repository = new TRepository(self::$activeRecord);
            // creates a criteria
            $criteria = new TCriteria;

            if(!empty($param["cliente_id"] ?? ""))
        {
            TSession::setValue(__CLASS__.'load_filter_cliente_id', $param["cliente_id"] ?? "");
        }
        $filterVar = TSession::getValue(__CLASS__.'load_filter_cliente_id');
            $criteria->add(new TFilter('cliente_id', '=', $filterVar));
            $filterVar = 0;
            $criteria->add(new TFilter('saldo', '>', $filterVar));

            if (empty($param['order']))
            {
                $param['order'] = 'dias';    
            }
            if (empty($param['direction']))
            {
                $param['direction'] = 'desc';
            }

            $criteria->setProperties($param); // order, offset
            $criteria->setProperty('limit', $this->limit);

            if($filters = TSession::getValue(__CLASS__.'_filters'))
            {
                foreach ($filters as $filter) 
                {
                    $criteria->add($filter);       
                }
            }

            // load the objects according to criteria
            $objects = $repository->load($criteria, FALSE);

            $this->datagrid->clear();
            if ($objects)
            {
                // iterate the collection of active records
                foreach ($objects as $object)
                {
                    $check = new TCheckButton('builder_datagrid_check[]');
                    $check->setIndexValue($object->id);
                    $check->onclick = 'event.stopPropagation();';
                    $object->builder_datagrid_check = $check;

                    //$object->check = new TCheckButton('check_' . $object->id);
                    //$object->check->setIndexValue('on');

                    $row = $this->datagrid->addItem($object);
                    $row->id = "row_{$object->id}";

                    //$this->formgrid->addField($object->check);

                }
            }

            // reset the criteria for record count
            $criteria->resetProperties();
            $count= $repository->count($criteria);

            // close the transaction
            TTransaction::close();
            $this->loaded = true;

            return $objects;
        }
        catch (Exception $e) // in case of exception
        {
            // shows the exception error message
            new TMessage('error', $e->getMessage());
            // undo all pending operations
            TTransaction::rollback();
        }
    }

    public function onShow($param = null)
    {

        //$this->btnClose->style = 'display:none';

    }

    /**
     * method show()
     * Shows the page
     */
    public function show()
    {
        // check if the datagrid is already loaded
        if (!$this->loaded AND (!isset($_GET['method']) OR !(in_array($_GET['method'],  array('onReload', 'onSearch')))) )
        {
            if (func_num_args() > 0)
            {
                $this->onReload( func_get_arg(0) );
            }
            else
            {
                $this->onReload();
            }
        }
        parent::show();
    }

    public static function onYes($param = null) 
    {
        try 
        {
            //var_dump($param);
            if(isset($param)){
                $Cliente_id = (int) $param['cliente_id'];//code here
                $aTitulos = $param['titulos'];//code here

                $oNegociacao = new Negociacao();
                $oNegociacao->cliente_id = $Cliente_id;
                $oNegociacao->atendimento_tipo_id = AtendimentoTipo::COBRANCA;
                $oNegociacao->observacao = ""; 
                $oNegociacao->system_unit_id = TSession::getValue('userunitid');
                $oNegociacao->system_users_id = TSession::getValue('userid');
                $oNegociacao->tipo = "";
                $oNegociacao->status = 'G';
                //$oNegociacao->filial_id
                $oNegociacao->store();

                foreach ($aTitulos as $aTitulo) 
                {

                    $oNegociacaoTitulo = new NegociacaoTituloReceber();
                    $oNegociacaoTitulo->campo = 'valor';
                    $oNegociacaoTitulo->store();

                }
            }

            //PosisaoClienteFormView&method=onShow&key=5954&id=5954
            //TScript::create("Template.closeRightPanel();setTimeout(__adianti_load_page, 300, 'index.php?class=PosisaoClienteFormView&method=onShow&key=5954&id=5954');");

        }
        catch (Exception $e) 
        {
            new TMessage('error', $e->getMessage());    
        }
    }

    public static function onNo($param = null) 
    {
        try 
        {

            TScript::create("Template.closeRightPanel()");
            //code here
        }
        catch (Exception $e) 
        {
            new TMessage('error', $e->getMessage());    
        }
    }

    public static function manageRow($id, $param = [])
    {
        $list = new self($param);

        $openTransaction = TTransaction::getDatabase() != self::$database ? true : false;

        if($openTransaction)
        {
            TTransaction::open(self::$database);    
        }

        $object = new ViewTituloCliente($id);

        $check = new TCheckButton('builder_datagrid_check[]');
        $check->setIndexValue($object->id);
        $check->onclick = 'event.stopPropagation();';
        $object->builder_datagrid_check = $check;

        $row = $list->datagrid->addItem($object);
        $row->id = "row_{$object->id}";

        if($openTransaction)
        {
            TTransaction::close();    
        }

        TDataGrid::replaceRowById(__CLASS__.'_datagrid', $row->id, $row);
    }

    public function manipularSelecao($param)
    {
        // Obtém as informação do formulário da listagem (itens selecionados)
        $data = $this->formgrid->getData();

        if (empty($data))
        {
            return false;
        }

        // Mantém os registros selecionados
        $this->formgrid->setData($data);

        $selected = array();

        // Obtém os IDs dos registros
        foreach ($data as $index => $check) //builder_datagrid_check
        {
            if ($check == 'on')
            {
                $selected[] = substr($index,6);
            }
        }

        //var_dump( $selected);
    }

}

