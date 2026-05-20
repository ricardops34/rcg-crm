<?php

class MetaVendedorMesList extends TPage
{
    private $form; // form
    private $datagrid; // listing
    private $pageNavigation;
    private $loaded;
    private $filter_criteria;
    private static $database = 'erp_online';
    private static $activeRecord = 'MetaVendedorMes';
    private static $primaryKey = 'id';
    private static $formName = 'form_MetaVendedorMesList';
    private $showMethods = ['onReload', 'onSearch', 'onRefresh', 'onClearFilters', 'onGlobalSearch'];
    private $limit = 20;

    /**
     * Class constructor
     * Creates the page, the form and the listing
     */
    public function __construct($param = null)
    {
        parent::__construct();

        if(!empty($param['target_container']))
        {
            $this->adianti_target_container = $param['target_container'];
        }

        // creates the form
        $this->form = new BootstrapFormBuilder(self::$formName);

        // define the form title
        $this->form->setFormTitle("Objetivo Vendedor Mes");
        $this->limit = 20;

        $criteria_vendedor_id = new TCriteria();

        $filterVar = 'A';
        $criteria_vendedor_id->add(new TFilter('status', '=', $filterVar)); 

        $vendedor_id = new TDBCombo('vendedor_id', 'erp_online', 'Vendedor', 'id', '{nome}','nome asc' , $criteria_vendedor_id );
        $mes = new TCombo('mes');
        $ano = new TCombo('ano');


        $ano->addItems(["2024"=>"2024","2025"=>"2025","2026"=>"2026","2027"=>"2027"]);
        $mes->addItems(["01"=>"Janeiro","02"=>"Fevereiro","03"=>"Março","04"=>"Abril","05"=>"Maio","06"=>"Junho","07"=>"Julho","08"=>"Agosto","09"=>"Setembro","10"=>"Outubro","11"=>"Novembro","12"=>"Dezembro"]);

        $mes->setValue(date('m'));
        $ano->setValue( date("Y"));

        $mes->setSize('100%');
        $ano->setSize('100%');
        $vendedor_id->setSize('100%');

        $mes->enableSearch();
        $ano->enableSearch();
        $vendedor_id->enableSearch();

        $row1 = $this->form->addFields([new TLabel("Vendedor:", null, '14px', null, '100%'),$vendedor_id],[new TLabel("Mes:", null, '14px', null, '100%'),$mes],[new TLabel("Ano:", null, '14px', null, '100%'),$ano]);
        $row1->layout = ['col-sm-6',' col-sm-3',' col-sm-3'];

        // keep the form filled during navigation with session data
        $this->form->setData( TSession::getValue(__CLASS__.'_filter_data') );

        $btn_onsearch = $this->form->addAction("Buscar", new TAction([$this, 'onSearch']), 'fas:search #ffffff');
        $this->btn_onsearch = $btn_onsearch;
        $btn_onsearch->addStyleClass('btn-primary'); 

        // creates a Datagrid
        $this->datagrid = new TDataGrid;
        $this->datagrid->setId(__CLASS__.'_datagrid');

        $this->datagrid_form = new TForm('datagrid_'.self::$formName);
        $this->datagrid_form->onsubmit = 'return false';

        $this->datagrid = new BootstrapDatagridWrapper($this->datagrid);
        $this->filter_criteria = new TCriteria;

        $this->datagrid->style = 'width: 100%';
        $this->datagrid->setHeight(250);

        $column_vendedor_nome = new TDataGridColumn('vendedor->nome', "Vendedor", 'left');
        $column_mes = new TDataGridColumn('mes', "Mes", 'left');
        $column_ano = new TDataGridColumn('ano', "Ano", 'left');
        $column_valor_transformed = new TDataGridColumn('valor', "Valor", 'left');
        $column_numero_cliente = new TDataGridColumn('numero_cliente', "Positivação", 'left');

        $column_valor_transformed->setTotalFunction( function($values) { 
            return array_sum((array) $values); 
        }); 

        $column_numero_cliente->setTotalFunction( function($values) { 
            return array_sum((array) $values); 
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

        $order_mes = new TAction(array($this, 'onReload'));
        $order_mes->setParameter('order', 'mes');
        $column_mes->setAction($order_mes);
        $order_ano = new TAction(array($this, 'onReload'));
        $order_ano->setParameter('order', 'ano');
        $column_ano->setAction($order_ano);

        $this->datagrid->addColumn($column_vendedor_nome);
        $this->datagrid->addColumn($column_mes);
        $this->datagrid->addColumn($column_ano);
        $this->datagrid->addColumn($column_valor_transformed);
        $this->datagrid->addColumn($column_numero_cliente);

        $action_onShow = new TDataGridAction(array('MetaVendedorMesFormView', 'onShow'));
        $action_onShow->setUseButton(false);
        $action_onShow->setButtonClass('btn btn-default btn-sm');
        $action_onShow->setLabel("Visualizar");
        $action_onShow->setImage('fas:print #000000');
        $action_onShow->setField(self::$primaryKey);

        $action_onShow->setParameter('key', '{id}');

        $this->datagrid->addAction($action_onShow);

        $action_onEdit = new TDataGridAction(array('MetaVendedorMesForm', 'onEdit'));
        $action_onEdit->setUseButton(false);
        $action_onEdit->setButtonClass('btn btn-default btn-sm');
        $action_onEdit->setLabel("Editar");
        $action_onEdit->setImage('far:edit #478fca');
        $action_onEdit->setField(self::$primaryKey);

        $this->datagrid->addAction($action_onEdit);

        $action_onDelete = new TDataGridAction(array('MetaVendedorMesList', 'onDelete'));
        $action_onDelete->setUseButton(false);
        $action_onDelete->setButtonClass('btn btn-default btn-sm');
        $action_onDelete->setLabel("Excluir");
        $action_onDelete->setImage('fas:trash-alt #dd5a43');
        $action_onDelete->setField(self::$primaryKey);

        $this->datagrid->addAction($action_onDelete);

        $action_onCopiar = new TDataGridAction(array('MetaVendedorMesList', 'onCopiar'));
        $action_onCopiar->setUseButton(false);
        $action_onCopiar->setButtonClass('btn btn-default btn-sm');
        $action_onCopiar->setLabel("Copiar");
        $action_onCopiar->setImage('far:copy #009688');
        $action_onCopiar->setField(self::$primaryKey);

        $this->datagrid->addAction($action_onCopiar);

        // create the datagrid model
        $this->datagrid->createModel();

        // creates the page navigation
        $this->pageNavigation = new TPageNavigation;
        $this->pageNavigation->enableCounters();
        $this->pageNavigation->setAction(new TAction(array($this, 'onReload')));
        $this->pageNavigation->setWidth($this->datagrid->getWidth());

        $panel = new TPanelGroup("Listagem de meta vendedor mes");
        $panel->datagrid = 'datagrid-container';
        $this->datagridPanel = $panel;

        $panel->add($this->datagrid_form);

        $panel->getBody()->class .= ' table-responsive';

        $panel->addFooter($this->pageNavigation);

        $headerActions = new TElement('div');
        $headerActions->class = ' datagrid-header-actions ';
        $headerActions->style = 'justify-content: space-between;';

        $head_left_actions = new TElement('div');
        $head_left_actions->class = ' datagrid-header-actions-left-actions ';

        $head_right_actions = new TElement('div');
        $head_right_actions->class = ' datagrid-header-actions-left-actions ';

        $headerActions->add($head_left_actions);
        $headerActions->add($head_right_actions);

        $this->datagrid_form->add($headerActions);

        $button_cadastrar = new TButton('button_button_cadastrar');
        $button_cadastrar->setAction(new TAction(['MetaVendedorMesForm', 'onShow']), "Cadastrar");
        $button_cadastrar->addStyleClass('btn-default');
        $button_cadastrar->setImage('fas:plus #69aa46');

        $this->datagrid_form->addField($button_cadastrar);

        $btnShowCurtainFilters = new TButton('button_btnShowCurtainFilters');
        $btnShowCurtainFilters->setAction(new TAction(['MetaVendedorMesList', 'onShowCurtainFilters']), "Filtros");
        $btnShowCurtainFilters->addStyleClass('btn-default');
        $btnShowCurtainFilters->setImage('fas:filter #000000');

        $this->datagrid_form->addField($btnShowCurtainFilters);

        $button_limpar_filtros = new TButton('button_button_limpar_filtros');
        $button_limpar_filtros->setAction(new TAction(['MetaVendedorMesList', 'onClearFilters']), "Limpar filtros");
        $button_limpar_filtros->addStyleClass('btn-default');
        $button_limpar_filtros->setImage('fas:eraser #f44336');

        $this->datagrid_form->addField($button_limpar_filtros);

        $button_atualizar = new TButton('button_button_atualizar');
        $button_atualizar->setAction(new TAction(['MetaVendedorMesList', 'onRefresh']), "Atualizar");
        $button_atualizar->addStyleClass('btn-default');
        $button_atualizar->setImage('fas:sync-alt #03a9f4');

        $this->datagrid_form->addField($button_atualizar);

        $button_auto = new TButton('button_button_auto');
        $button_auto->setAction(new TAction(['MetaVendedorMesList', 'onGeraAuto']), "Auto");
        $button_auto->addStyleClass('btn-default');
        $button_auto->setImage('fas:robot #4CAF50');

        $this->datagrid_form->addField($button_auto);

        $head_left_actions->add($button_cadastrar);
        $head_left_actions->add($btnShowCurtainFilters);
        $head_left_actions->add($button_limpar_filtros);
        $head_left_actions->add($button_atualizar);
        $head_left_actions->add($button_auto);

        $this->datagrid_form->add($this->datagrid);

        $this->btnShowCurtainFilters = $btnShowCurtainFilters;

        // vertical box container
        $container = new TVBox;
        $container->style = 'width: 100%';
        if(empty($param['target_container']))
        {
            $container->add(TBreadCrumb::create(["Gerencia","Objetivo Mes"]));
        }

        $container->add($panel);

        parent::add($container);

    }

    public function onDelete($param = null) 
    { 
        if(isset($param['delete']) && $param['delete'] == 1)
        {
            try
            {
                // get the paramseter $key
                $key = $param['key'];
                // open a transaction with database
                TTransaction::open(self::$database);

                // instantiates object
                $object = new MetaVendedorMes($key, FALSE); 

                MetaVendedorCategoria::where('meta_vendedor_mes_id', '=', $object->id)
                ->delete();

                // deletes the object from the database
                $object->delete();

                // close the transaction
                TTransaction::close();

                // reload the listing
                $this->onReload( $param );
                // shows the success message
                new TMessage('info', AdiantiCoreTranslator::translate('Record deleted'));
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
            // define the delete action
            $action = new TAction(array($this, 'onDelete'));
            $action->setParameters($param); // pass the key paramseter ahead
            $action->setParameter('delete', 1);
            // shows a dialog to the user
            new TQuestion(AdiantiCoreTranslator::translate('Do you really want to delete ?'), $action);   
        }
    }
    public function onCopiar($param = null) 
    {
        try 
        {
            if(isset($param['id'])){
                new TQuestion("<h4>Deseja copiar o Registro?</h4>", new TAction([__CLASS__, 'onCopiarOnYes'], $param), new TAction([__CLASS__, 'onCopiarOnNo'], $param));
            }

            //</autoCode>
        }
        catch (Exception $e) 
        {
            new TMessage('error', $e->getMessage());    
        }
    }
    public static function onShowCurtainFilters($param = null) 
    {
        try 
        {
            //code here

                        $filter = new self([]);

            $btnClose = new TButton('closeCurtain');
            $btnClose->class = 'btn btn-sm btn-default';
            $btnClose->style = 'margin-right:10px;';
            $btnClose->onClick = "Template.closeRightPanel();";
            $btnClose->setLabel("Fechar");
            $btnClose->setImage('fas:times');

            $filter->form->addHeaderWidget($btnClose);

            $page = new TPage();
            $page->setTargetContainer('adianti_right_panel');
            $page->setProperty('page-name', 'MetaVendedorMesListSearch');
            $page->setProperty('page_name', 'MetaVendedorMesListSearch');
            $page->adianti_target_container = 'adianti_right_panel';
            $page->target_container = 'adianti_right_panel';
            $page->add($filter->form);
            $page->setIsWrapped(true);
            $page->show();

            //</autoCode>
        }
        catch (Exception $e) 
        {
            new TMessage('error', $e->getMessage());    
        }
    }
    public function onClearFilters($param = null) 
    {
        TSession::setValue(__CLASS__.'_filter_data', NULL);
        TSession::setValue(__CLASS__.'_filters', NULL);

        $this->onReload(['offset' => 0, 'first_page' => 1]);
    }
    public function onRefresh($param = null) 
    {
        $this->onReload([]);
    }
    public function onGeraAuto($param = null) 
    {
        try 
        {
            if(isset($param['id'])){
                new TQuestion("<h4>Deseja Gerar Objetivo de Forma Automatica??</h4>", new TAction([__CLASS__, 'onAutoOnYes'], $param), new TAction([__CLASS__, 'onAutoOnNo'], $param));
            }

            //</autoCode>
        }
        catch (Exception $e) 
        {
            new TMessage('error', $e->getMessage());    
        }
    }

    /**
     * Register the filter in the session
     */
    public function onSearch($param = null)
    {
        $data = $this->form->getData();
        $filters = [];

        TSession::setValue(__CLASS__.'_filter_data', NULL);
        TSession::setValue(__CLASS__.'_filters', NULL);

        if (isset($data->vendedor_id) AND ( (is_scalar($data->vendedor_id) AND $data->vendedor_id !== '') OR (is_array($data->vendedor_id) AND (!empty($data->vendedor_id)) )) )
        {

            $filters[] = new TFilter('vendedor_id', '=', $data->vendedor_id);// create the filter 
        }

        if (isset($data->mes) AND ( (is_scalar($data->mes) AND $data->mes !== '') OR (is_array($data->mes) AND (!empty($data->mes)) )) )
        {

            $filters[] = new TFilter('mes', 'like', "%{$data->mes}%");// create the filter 
        }

        if (isset($data->ano) AND ( (is_scalar($data->ano) AND $data->ano !== '') OR (is_array($data->ano) AND (!empty($data->ano)) )) )
        {

            $filters[] = new TFilter('ano', 'like', "%{$data->ano}%");// create the filter 
        }

        // fill the form with data again
        $this->form->setData($data);

        // keep the search data in the session
        TSession::setValue(__CLASS__.'_filter_data', $data);
        TSession::setValue(__CLASS__.'_filters', $filters);

        $this->onReload(['offset' => 0, 'first_page' => 1]);
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

            // creates a repository for MetaVendedorMes
            $repository = new TRepository(self::$activeRecord);

            $criteria = clone $this->filter_criteria;

            if (empty($param['order']))
            {
                $param['order'] = 'vendedor_id';    
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

            //</blockLine><btnShowCurtainFiltersAutoCode>
            if(!empty($this->btnShowCurtainFilters) && empty($this->btnShowCurtainFiltersAdjusted))
            {
                $this->btnShowCurtainFiltersAdjusted = true;
                $this->btnShowCurtainFilters->style = 'position: relative';
                $countFilters = count($filters ?? []);
                $this->btnShowCurtainFilters->setLabel($this->btnShowCurtainFilters->getLabel(). "<span class='badge badge-success' style='position: absolute'>{$countFilters}<span>");
            }
            //</blockLine></btnShowCurtainFiltersAutoCode>

            // load the objects according to criteria
            $objects = $repository->load($criteria, FALSE);

            $this->datagrid->clear();
            if ($objects)
            {
                // iterate the collection of active records
                foreach ($objects as $object)
                {

                    $row = $this->datagrid->addItem($object);
                    $row->id = "row_{$object->id}";

                }
            }

            // reset the criteria for record count
            $criteria->resetProperties();
            $count= $repository->count($criteria);

            $this->pageNavigation->setCount($count); // count of records
            $this->pageNavigation->setProperties($param); // order, page
            $this->pageNavigation->setLimit($this->limit); // limit

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

    }

    /**
     * method show()
     * Shows the page
     */
    public function show()
    {
        // check if the datagrid is already loaded
        if (!$this->loaded AND (!isset($_GET['method']) OR !(in_array($_GET['method'],  $this->showMethods))) )
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

    public static function onCopiarOnYes($param = null) 
    {
        try 
        {
            $key = $param['key'];
            $form2 = new TQuickForm('form_copiaobjetivo');//new TQuickForm('input_form');
            $form2->style = 'padding:20px';
            $mes_novo = new TCombo('mes');
            $ano_novo = new TCombo('ano');

            $mes_novo->addValidation("Mes", new TRequiredValidator()); 
            $ano_novo->addValidation("Ano", new TRequiredValidator()); 

            $mes_novo->addItems(TempoService::getMes());
            $ano_novo->addItems(TempoService::getAno());

            $data = new DateTime();
            $data->modify("last day of this month");//"next month");
            $data->modify('+1 day');

            $mes_novo->setValue($data->format('m'));//date('m'));
            $ano_novo->setValue($data->format('Y'));//date('Y'));

            $form2->addQuickField('Mes', $mes_novo);
            $form2->addQuickField('Ano', $ano_novo);
            $action  = new TAction(array(__CLASS__ , 'onConfirma'));
            //$action->setParameters((array) $this->form->getData());
            $action->setParameter('key' , $key);
            $action->setParameter('mes', $mes_novo);
            $action->setParameter('ano', $ano_novo);
            $form2->addQuickAction('Confirmar', $action, 'ico_save.png');
            // show the input dialog
            new TInputDialog('Copiar Objetivo para ?', $form2);
        }
        catch (Exception $e) 
        {
            new TMessage('error', $e->getMessage());    
        }
    }

    public static function onCopiarOnNo($param = null) 
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

    public  function onConfirma($param = null) 
    {
        try 
        {
            if (isset($param['key']))
            {   
                TTransaction::open(self::$database);
                $id = $param['key'];
                $oMetaVendedorMes = MetaVendedorMes::find( $id );//new Orcamento();

                if($oMetaVendedorMes){
                    $oMetaVendedorMesCopia = clone $oMetaVendedorMes;
                    unset($oMetaVendedorMesCopia->id);
                    $oMetaVendedorMesCopia->mes = $param['mes'];
                    $oMetaVendedorMesCopia->ano = $param['ano'];
                    $oMetaVendedorMesCopia->store();

                    $criteria = new TCriteria; 
                    $criteria->add(new TFilter('meta_vendedor_mes_id', '=', $id)); 
                    //$criteria->add(new TFilter('dt_delete', '=', is null)); 

                    $repository = new TRepository('MetaVendedorCategoria'); 
                    $oItens = $repository->load($criteria);      

                    foreach ($oItens as $oItem)
                    {           
                        unset($oItem->id);
                        unset($oItem->dt_inclusao);
                        unset($oItem->dt_alteracao);
                        unset($oItem->meta_vendedor_mes_id);
                        $oItem->meta_vendedor_mes_id = $oMetaVendedorMesCopia->id;
                        $oItem->store();
                    }

                    TToast::show("info", "Objetivo Copiado", "topRight", "");
                }else{
                    TToast::show("erro", "Não foi possivel realizar a copia", "topRight", "");
                }
                TTransaction::close();

                $this->onReload();
            }
        }
        catch (Exception $e) 
        {
            new TMessage('error', $e->getMessage());    
        }
    }

    public static function onAutoOnYes($param = null) 
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

    public static function onAutoOnNo($param = null) 
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

    public static function manageRow($id, $param = [])
    {
        $list = new self($param);

        $openTransaction = TTransaction::getDatabase() != self::$database ? true : false;

        if($openTransaction)
        {
            TTransaction::open(self::$database);    
        }

        $object = new MetaVendedorMes($id);

        $row = $list->datagrid->addItem($object);
        $row->id = "row_{$object->id}";

        if($openTransaction)
        {
            TTransaction::close();    
        }

        TDataGrid::replaceRowById(__CLASS__.'_datagrid', $row->id, $row);
    }

}

