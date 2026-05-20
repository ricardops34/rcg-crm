<?php

class ClienteList extends TPage
{
    private $form; // form
    private $datagrid; // listing
    private $pageNavigation;
    private $loaded;
    private $filter_criteria;
    private static $database = 'erp_online';
    private static $activeRecord = 'Cliente';
    private static $primaryKey = 'id';
    private static $formName = 'form_ClienteList';
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
        $this->form->setFormTitle("Listagem de cadastro de clientes");
        $this->limit = 20;

        $criteria_situacao_cadastral_id = new TCriteria();

        $cod_erp = new TEntry('cod_erp');
        $cnpj_cpf = new TEntry('cnpj_cpf');
        $fitrasitucao = new TRadioGroup('fitrasitucao');
        $razao = new TEntry('razao');
        $fantasia = new TEntry('fantasia');
        $situacao_cadastral_id = new TDBCombo('situacao_cadastral_id', 'erp_online', 'SituacaoCadastral', 'id', '{descricao}','descricao asc' , $criteria_situacao_cadastral_id );


        $fitrasitucao->addItems(["ativo"=>"Ativo","bloqueado"=>"Bloqueado","pendente"=>"Pendente","erro"=>"Erro"]);
        $fitrasitucao->setLayout('horizontal');
        $fitrasitucao->setUseButton();
        $fitrasitucao->setBreakItems(2);
        $situacao_cadastral_id->enableSearch();
        $razao->setMaxLength(100);
        $cod_erp->setMaxLength(10);
        $cnpj_cpf->setMaxLength(20);
        $fantasia->setMaxLength(50);

        $razao->setSize('100%');
        $cod_erp->setSize('100%');
        $cnpj_cpf->setSize('100%');
        $fitrasitucao->setSize(80);
        $fantasia->setSize('100%');
        $situacao_cadastral_id->setSize('100%');

        $row1 = $this->form->addFields([new TLabel("Código:", null, '14px', null, '100%'),$cod_erp],[new TLabel("CNPJ/CPF:", null, '14px', null, '100%'),$cnpj_cpf],[$fitrasitucao]);
        $row1->layout = ['col-sm-3','col-sm-3',' col-sm-6'];

        $row2 = $this->form->addFields([new TLabel("Razão Social:", null, '14px', null, '100%'),$razao],[new TLabel("Nome de Fantasia:", null, '14px', null, '100%'),$fantasia]);
        $row2->layout = [' col-sm-6',' col-sm-6'];

        $row3 = $this->form->addFields([new TLabel("Situação RFB:", null, '14px', null, '100%'),$situacao_cadastral_id],[]);
        $row3->layout = [' col-sm-6',' col-sm-6'];

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

        $column_cod_erp = new TDataGridColumn('cod_erp', "Código", 'left');
        $column_razao = new TDataGridColumn('razao', "Razão Social", 'left');
        $column_fantasia = new TDataGridColumn('fantasia', "Nome de Fantasia", 'left');
        $column_uf = new TDataGridColumn('uf', "UF", 'left');
        $column_status_transformed = new TDataGridColumn('status', "Ativo", 'left');
        $column_situacao_cadastral_descricao = new TDataGridColumn('situacao_cadastral->descricao', "Situação RFB ", 'left');
        $column_dt_revisao_transformed = new TDataGridColumn('dt_revisao', "Consulta RFB", 'left');

        $column_status_transformed->setTransformer(function($value, $object, $row, $cell = null, $last_row = null)
        {

            $class = ($value=='B') ? 'warning'      : 'success';
            $label = ($value=='B') ? 'Bloqueado'    : 'Ativo';
            $div = new TElement('span');
            $div->class="label label-{$class}";
            $div->style="width:120px; text-shadow:none; font-size:12px; font-weight:lighter";
            $div->add($label);
            return $div;

        });

        $column_dt_revisao_transformed->setTransformer(function($value, $object, $row, $cell = null, $last_row = null)
        {
            if(!empty(trim((string) $value)))
            {
                try
                {
                    $date = new DateTime($value);
                    return $date->format('d/m/Y H:i');
                }
                catch (Exception $e)
                {
                    return $value;
                }
            }
        });        

        $order_dt_revisao_transformed = new TAction(array($this, 'onReload'));
        $order_dt_revisao_transformed->setParameter('order', 'dt_revisao');
        $column_dt_revisao_transformed->setAction($order_dt_revisao_transformed);

        if(TSession::getValue("vendedor") == "S"){
            $filterVar = TSession::getValue("vendedor_id");
            $this->filter_criteria->add(new TFilter('vendedor_id', '=', $filterVar));
        }

        $this->datagrid->addColumn($column_cod_erp);
        $this->datagrid->addColumn($column_razao);
        $this->datagrid->addColumn($column_fantasia);
        $this->datagrid->addColumn($column_uf);
        $this->datagrid->addColumn($column_status_transformed);
        $this->datagrid->addColumn($column_situacao_cadastral_descricao);
        $this->datagrid->addColumn($column_dt_revisao_transformed);

        $action_onShow = new TDataGridAction(array('PosisaoClienteFormView', 'onShow'));
        $action_onShow->setUseButton(false);
        $action_onShow->setButtonClass('btn btn-default btn-sm');
        $action_onShow->setLabel("Posição Cliente");
        $action_onShow->setImage('fas:address-card #000000');
        $action_onShow->setField(self::$primaryKey);

        $action_onShow->setParameter('key', '{id}');

        $this->datagrid->addAction($action_onShow);

        $action_onView = new TDataGridAction(array('ClienteForm', 'onView'));
        $action_onView->setUseButton(false);
        $action_onView->setButtonClass('btn btn-default btn-sm');
        $action_onView->setLabel("Visualizar");
        $action_onView->setImage('fas:eye #000000');
        $action_onView->setField(self::$primaryKey);

        $action_onView->setParameter('key', '{id}');

        $this->datagrid->addAction($action_onView);

        $action_onEdit = new TDataGridAction(array('ClienteForm', 'onEdit'));
        $action_onEdit->setUseButton(false);
        $action_onEdit->setButtonClass('btn btn-default btn-sm');
        $action_onEdit->setLabel("Editar");
        $action_onEdit->setImage('far:edit #478fca');
        $action_onEdit->setField(self::$primaryKey);
        $action_onEdit->setDisplayCondition('ClienteList::PodeEditar');

        $this->datagrid->addAction($action_onEdit);

        $action_onDelete = new TDataGridAction(array('ClienteList', 'onDelete'));
        $action_onDelete->setUseButton(false);
        $action_onDelete->setButtonClass('btn btn-default btn-sm');
        $action_onDelete->setLabel("Excluir");
        $action_onDelete->setImage('fas:trash-alt #dd5a43');
        $action_onDelete->setField(self::$primaryKey);
        $action_onDelete->setDisplayCondition('ClienteList::PodeExcluir');

        $this->datagrid->addAction($action_onDelete);

        $action_ClienteSenhaForm_onEdit = new TDataGridAction(array('ClienteSenhaForm', 'onEdit'));
        $action_ClienteSenhaForm_onEdit->setUseButton(false);
        $action_ClienteSenhaForm_onEdit->setButtonClass('btn btn-default btn-sm');
        $action_ClienteSenhaForm_onEdit->setLabel("Senha");
        $action_ClienteSenhaForm_onEdit->setImage('fas:unlock-alt #FF9800');
        $action_ClienteSenhaForm_onEdit->setField(self::$primaryKey);
        $action_ClienteSenhaForm_onEdit->setDisplayCondition('ClienteList::PodeSenha');
        $action_ClienteSenhaForm_onEdit->setParameter('key', '{id}');

        $this->datagrid->addAction($action_ClienteSenhaForm_onEdit);

        // create the datagrid model
        $this->datagrid->createModel();

        // creates the page navigation
        $this->pageNavigation = new TPageNavigation;
        $this->pageNavigation->enableCounters();
        $this->pageNavigation->setAction(new TAction(array($this, 'onReload')));
        $this->pageNavigation->setWidth($this->datagrid->getWidth());

        $panel = new TPanelGroup("Listagem de cadastro de clientes");
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
        $button_cadastrar->setAction(new TAction(['ClienteForm', 'onShow']), "Cadastrar");
        $button_cadastrar->addStyleClass('btn-default');
        $button_cadastrar->setImage('fas:plus #69aa46');

        $this->datagrid_form->addField($button_cadastrar);

        $btnShowCurtainFilters = new TButton('button_btnShowCurtainFilters');
        $btnShowCurtainFilters->setAction(new TAction(['ClienteList', 'onShowCurtainFilters']), "Filtros");
        $btnShowCurtainFilters->addStyleClass('btn-default');
        $btnShowCurtainFilters->setImage('fas:filter #000000');

        $this->datagrid_form->addField($btnShowCurtainFilters);

        $button_atualizar = new TButton('button_button_atualizar');
        $button_atualizar->setAction(new TAction(['ClienteList', 'onRefresh']), "Atualizar");
        $button_atualizar->addStyleClass('btn-default');
        $button_atualizar->setImage('fas:sync-alt #03a9f4');

        $this->datagrid_form->addField($button_atualizar);

        $button_limpar_filtros = new TButton('button_button_limpar_filtros');
        $button_limpar_filtros->setAction(new TAction(['ClienteList', 'onClearFilters']), "Limpar filtros");
        $button_limpar_filtros->addStyleClass('btn-default');
        $button_limpar_filtros->setImage('fas:eraser #f44336');

        $this->datagrid_form->addField($button_limpar_filtros);

        $button_consulta_rfb = new TButton('button_button_consulta_rfb');
        $button_consulta_rfb->setAction(new TAction(['AtualizaCliente', 'onShow']), "Consulta RFB");
        $button_consulta_rfb->addStyleClass('btn-success');
        $button_consulta_rfb->setImage('fas:cloud-download-alt #FFFFFF');

        $this->datagrid_form->addField($button_consulta_rfb);

        $head_left_actions->add($button_cadastrar);
        $head_left_actions->add($btnShowCurtainFilters);
        $head_left_actions->add($button_atualizar);
        $head_left_actions->add($button_limpar_filtros);
        $head_left_actions->add($button_consulta_rfb);

        $this->datagrid_form->add($this->datagrid);

        $this->btnShowCurtainFilters = $btnShowCurtainFilters;

        // vertical box container
        $container = new TVBox;
        $container->style = 'width: 100%';
        if(empty($param['target_container']))
        {
            $container->add(TBreadCrumb::create(["Vendedor","Cadastro de Clientes"]));
        }

        $container->add($panel);

        parent::add($container);

    }

    public static function PodeEditar($object)
    {
        try 
        {
            if($object)
            {   
                if(TSession::getValue('userid') == 1){
                    return true;
                }
            }

            return false;
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
                // get the paramseter $key
                $key = $param['key'];
                // open a transaction with database
                TTransaction::open(self::$database);

                // instantiates object
                $object = new Cliente($key, FALSE); 

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
    public static function PodeExcluir($object)
    {
        try 
        {
            if($object)
            {
                //return true;
            }

            return false;
        }
        catch (Exception $e) 
        {
            new TMessage('error', $e->getMessage());    
        }
    }
    public static function PodeSenha($object)
    {
        try 
        {
            if($object)
            {
                //return true;
            }

            return false;
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
            $page->setProperty('page-name', 'ClienteListSearch');
            $page->setProperty('page_name', 'ClienteListSearch');
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
    public function onRefresh($param = null) 
    {
        $this->onReload([]);
    }
    public function onClearFilters($param = null) 
    {
        TSession::setValue(__CLASS__.'_filter_data', NULL);
        TSession::setValue(__CLASS__.'_filters', NULL);

        $this->onReload(['offset' => 0, 'first_page' => 1]);
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

        if (isset($data->cod_erp) AND ( (is_scalar($data->cod_erp) AND $data->cod_erp !== '') OR (is_array($data->cod_erp) AND (!empty($data->cod_erp)) )) )
        {

            $filters[] = new TFilter('cod_erp', 'like', "%{$data->cod_erp}%");// create the filter 
        }

        if (isset($data->cnpj_cpf) AND ( (is_scalar($data->cnpj_cpf) AND $data->cnpj_cpf !== '') OR (is_array($data->cnpj_cpf) AND (!empty($data->cnpj_cpf)) )) )
        {

            $filters[] = new TFilter('cnpj_cpf', 'like', "%{$data->cnpj_cpf}%");// create the filter 
        }

        if (isset($data->razao) AND ( (is_scalar($data->razao) AND $data->razao !== '') OR (is_array($data->razao) AND (!empty($data->razao)) )) )
        {

            $filters[] = new TFilter('razao', 'like', "%{$data->razao}%");// create the filter 
        }

        if (isset($data->fantasia) AND ( (is_scalar($data->fantasia) AND $data->fantasia !== '') OR (is_array($data->fantasia) AND (!empty($data->fantasia)) )) )
        {

            $filters[] = new TFilter('fantasia', 'like', "%{$data->fantasia}%");// create the filter 
        }

        if (isset($data->situacao_cadastral_id) AND ( (is_scalar($data->situacao_cadastral_id) AND $data->situacao_cadastral_id !== '') OR (is_array($data->situacao_cadastral_id) AND (!empty($data->situacao_cadastral_id)) )) )
        {

            $filters[] = new TFilter('situacao_cadastral_id', '=', $data->situacao_cadastral_id);// create the filter 
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

            // creates a repository for Cliente
            $repository = new TRepository(self::$activeRecord);

            $criteria = clone $this->filter_criteria;

            if (empty($param['order']))
            {
                $param['order'] = 'cod_erp';    
            }

            if (empty($param['direction']))
            {
                $param['direction'] = 'asc';
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

    public static function manageRow($id, $param = [])
    {
        $list = new self($param);

        $openTransaction = TTransaction::getDatabase() != self::$database ? true : false;

        if($openTransaction)
        {
            TTransaction::open(self::$database);    
        }

        $object = new Cliente($id);

        $row = $list->datagrid->addItem($object);
        $row->id = "row_{$object->id}";

        if($openTransaction)
        {
            TTransaction::close();    
        }

        TDataGrid::replaceRowById(__CLASS__.'_datagrid', $row->id, $row);
    }

}

