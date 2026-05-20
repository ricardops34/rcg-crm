<?php

class ViewProdutoOrcamentoSeekWindow extends TWindow
{
    private $form; // form
    private $datagrid; // listing
    private $pageNavigation;
    private $loaded;
    private $filter_criteria;
    private static $database = 'erp_online';
    private static $activeRecord = 'ViewProdutoOrcamento';
    private static $primaryKey = 'produto_cod_erp';
    private static $formName = 'form_ViewProdutoOrcamentoSeekWindow';
    private $showMethods = ['onReload', 'onSearch'];
    private $limit = 20;

    use BuilderSeekWindowTrait;

    /**
     * Class constructor
     * Creates the page, the form and the listing
     */
    public function __construct($param = null)
    {
        parent::__construct();
        parent::setSize(0.8, null);
        parent::setTitle("ProdutoOrcamento");
        parent::setProperty('class', 'window_modal');

        $param['_seek_window_id'] = $this->id;
        // creates the form
        $this->form = new BootstrapFormBuilder(self::$formName);

        $this->limit = 20;

        // define the form title
        $this->form->setFormTitle("ProdutoOrcamento");

        $produto_cod_erp = new TEntry('produto_cod_erp');
        $produto_descricao = new TEntry('produto_descricao');

        $produto_cod_erp->setSize('100%');
        $produto_descricao->setSize('100%');

        $row1 = $this->form->addFields([new TLabel("Código:", null, '14px', null, '100%'),$produto_cod_erp],[new TLabel("Descrição:", null, '14px', null, '100%'),$produto_descricao]);
        $row1->layout = ['col-sm-6','col-sm-6'];

        // keep the form filled during navigation with session data
        $this->form->setData( TSession::getValue(__CLASS__.'_filter_data') );

        $btn_onsearch = $this->form->addAction("Buscar", new TAction([$this, 'onSearch']), 'fas:search #ffffff');
        $this->btn_onsearch = $btn_onsearch;
        $btn_onsearch->addStyleClass('btn-primary'); 

        $this->setSeekParameters($btn_onsearch->getAction(), $param);

        // creates a Datagrid
        $this->datagrid = new TDataGrid;
        $this->datagrid->setId(__CLASS__.'_datagrid');

        $this->datagrid_form = new TForm('datagrid_'.self::$formName);
        $this->datagrid_form->onsubmit = 'return false';

        $this->datagrid = new BootstrapDatagridWrapper($this->datagrid);
        $this->filter_criteria = $this->getSeekFiltersCriteria($param);

        $filterVar = 'A';
        $this->filter_criteria->add(new TFilter('situacao', '=', $filterVar));
        $filterVar = 0;
        $this->filter_criteria->add(new TFilter('preco', '>', $filterVar));
        $filterVar = TSession::getValue('orcamento_tabela_preco_id');
        $this->filter_criteria->add(new TFilter('tabela_preco_id', '=', $filterVar));
        $filterVar = 0;
        $this->filter_criteria->add(new TFilter('produto_saldo', '>', $filterVar));

        $this->datagrid->style = 'width: 100%';
        $this->datagrid->setHeight(320);

        $column_produto_cod_erp = new TDataGridColumn('produto_cod_erp', "Código", 'center' , '70px');
        $column_produto_descricao = new TDataGridColumn('produto_descricao', "Descrição", 'left');
        $column_situacao = new TDataGridColumn('situacao', "Situacao", 'left');
        $column_armazem_cod_erp = new TDataGridColumn('armazem_cod_erp', "Armazem", 'left');
        $column_precos_descricao = new TDataGridColumn('precos_descricao', "Tabela", 'left');
        $column_preco_transformed = new TDataGridColumn('preco', "Preco", 'right');
        $column_produto_saldo_transformed = new TDataGridColumn('produto_saldo', "Saldo", 'right');
        $column_ultima_venda_transformed = new TDataGridColumn('ultima_venda', "Ultima venda", 'right');

        $column_preco_transformed->setTransformer(function($value, $object, $row, $cell = null, $last_row = null)
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

        $column_produto_saldo_transformed->setTransformer(function($value, $object, $row, $cell = null, $last_row = null)
        {
            //code here
            if(!$value)
            {
                $value = 0;
            }

            if(is_numeric($value))
            {
                return number_format($value, 2, ",", ".");
            }
            else
            {
                return $value;
            }

        });

        $column_ultima_venda_transformed->setTransformer(function($value, $object, $row, $cell = null, $last_row = null)
        {

            $id_cliente = TSession::getValue('OrcamentoForm_cliente_id');
            $id_produto = $value;
            $ndias  = 0 ;
            $return = '';
            $rData  = '';
            $rValor = '';
            $cStyle = "background: #FFF9A7"; 

            TTransaction::open('erp_online');

            $oCliente = Cliente::find( $id_cliente );

            if($oCliente){

                $oVenda = ViewUltimoPreco::where('cliente_id', '=', $oCliente->id)
                    ->where('produto_id', '=', $id_produto)
                    ->first();

                if($oVenda){

                    $data_inicio = new DateTime($oVenda->dt_emissao);
                    $nValor = number_format($oVenda->vlr_unitario, 2, ',', '.'); 

                    $data_fim = new DateTime(date("Y-m-d"));
                    $dateInterval = $data_inicio->diff($data_fim);
                    $ndias = $dateInterval->days;
                    //$dEmissao = new DateTime($oVenda->dt_emissao);

                    $rData  = $data_inicio->format('d/m/Y');
                    $rValor = "R$ ".$nValor;

                    $return = "<span style='color:green'>".$rData.' - '.$rValor."</span>";
                    $cStyle = "background: #95d475"; 
                    if($ndias > 15 and $ndias <= 30){
                        $cStyle = "background: #72c3f4"; 
                        $return = "<span style='color:blue'>".$rData.' - '.$rValor."</span>";
                    }elseif($ndias > 30 ){
                        $cStyle = "background: #FFF9A7"; 
                        $return = "<span style='color:red'>".$rData.' - '.$rValor."</span>";
                    }

                    $row->style = $cStyle;

                }
            }

            TTransaction::close();    
            return $return;
        });        

        $order_produto_cod_erp = new TAction(array($this, 'onReload'));
        $order_produto_cod_erp->setParameter('order', 'produto_cod_erp');
        $this->setSeekParameters($order_produto_cod_erp, $param);
        $column_produto_cod_erp->setAction($order_produto_cod_erp);

        $this->datagrid->addColumn($column_produto_cod_erp);
        $this->datagrid->addColumn($column_produto_descricao);
        $this->datagrid->addColumn($column_situacao);
        $this->datagrid->addColumn($column_armazem_cod_erp);
        $this->datagrid->addColumn($column_precos_descricao);
        $this->datagrid->addColumn($column_preco_transformed);
        $this->datagrid->addColumn($column_produto_saldo_transformed);
        $this->datagrid->addColumn($column_ultima_venda_transformed);

        $action_onSelect = new TDataGridAction(array('ViewProdutoOrcamentoSeekWindow', 'onSelect'));
        $action_onSelect->setUseButton(true);
        $action_onSelect->setButtonClass('btn btn-default btn-sm');
        $action_onSelect->setLabel("Selecionar");
        $action_onSelect->setImage('far:hand-pointer #44bd32');
        $action_onSelect->setField(self::$primaryKey);
        $this->setSeekParameters($action_onSelect, $param);

        $this->datagrid->addAction($action_onSelect);

        // create the datagrid model
        $this->datagrid->createModel();

        // creates the page navigation
        $this->pageNavigation = new TPageNavigation;
        $this->pageNavigation->enableCounters();
        $navigationAction = new TAction(array($this, 'onReload'));
        $this->setSeekParameters($navigationAction, $param);
        $this->pageNavigation->setAction($navigationAction);
        $this->pageNavigation->setWidth($this->datagrid->getWidth());

        $panel = new TPanelGroup();
        $panel->datagrid = 'datagrid-container';
        $this->datagridPanel = $panel;
        $this->datagrid_form->add($this->datagrid);
        $panel->add($this->datagrid_form);

        $panel->getBody()->class .= ' table-responsive';

        $panel->addFooter($this->pageNavigation);

        parent::add($this->form);
        parent::add($panel);

    }

    public static function onSelect($param = null) 
    { 
        try 
        {   
            $seekFields = self::getSeekFields($param);
            $formData = new stdClass();

            if(!empty($param['key']))
            {
                TTransaction::open(self::$database);

                $repository = new TRepository(self::$activeRecord);

                $criteria = self::getSeekFiltersCriteria($param);

            $filterVar = 'A';
            $criteria->add(new TFilter('situacao', '=', $filterVar));

            $filterVar = 0;
            $criteria->add(new TFilter('preco', '>', $filterVar));

            $filterVar = TSession::getValue('orcamento_tabela_preco_id');
            $criteria->add(new TFilter('tabela_preco_id', '=', $filterVar));

            $filterVar = 0;
            $criteria->add(new TFilter('produto_saldo', '>', $filterVar));

                $criteria->add(new TFilter(self::$primaryKey, '=', $param['key']));
                $objects = $repository->load($criteria);

                if($objects)
                {
                    $object = $objects[0];
                    if($seekFields)
                    {
                        foreach ($seekFields as $seek_field) 
                        {

                            $formData->{"{$seek_field['name']}"} = $object->render("{$seek_field['column']}");
                        }
                    }
                }
                elseif($seekFields)
                {
                    foreach ($seekFields as $seek_field) 
                    {
                        $formData->{"{$seek_field['name']}"} = '';
                    }   
                }
                TTransaction::close();
            }
            else
            {
                if($seekFields)
                {
                    foreach ($seekFields as $seek_field) 
                    {
                        $formData->{"{$seek_field['name']}"} = '';
                    }   
                }
            }

            TForm::sendData($param['_form_name'], $formData);

            if(!empty($param['_seek_window_id']))
            {
                TWindow::closeWindow($param['_seek_window_id']);
            }
            else
            {
                TScript::create("Template.closeRightPanel();");
            }
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
        // get the search form data
        $data = $this->form->getData();
        $filters = [];

        TSession::setValue(__CLASS__.'_filter_data', NULL);
        TSession::setValue(__CLASS__.'_filters', NULL);

        if (isset($data->produto_cod_erp) AND ( (is_scalar($data->produto_cod_erp) AND $data->produto_cod_erp !== '') OR (is_array($data->produto_cod_erp) AND (!empty($data->produto_cod_erp)) )) )
        {

            $filters[] = new TFilter('produto_cod_erp', 'like', "%{$data->produto_cod_erp}%");// create the filter 
        }

        if (isset($data->produto_descricao) AND ( (is_scalar($data->produto_descricao) AND $data->produto_descricao !== '') OR (is_array($data->produto_descricao) AND (!empty($data->produto_descricao)) )) )
        {

            $filters[] = new TFilter('produto_descricao', 'like', "%{$data->produto_descricao}%");// create the filter 
        }

        // fill the form with data again
        $this->form->setData($data);

        // keep the search data in the session
        TSession::setValue(__CLASS__.'_filter_data', $data);
        TSession::setValue(__CLASS__.'_filters', $filters);

        if (isset($param['static']) && ($param['static'] == '1') )
        {
            $class = get_class($this);
            AdiantiCoreApplication::loadPage($class, 'onReload', ['offset' => 0, 'first_page' => 1]);
        }
        else
        {
            $this->onReload(['offset' => 0, 'first_page' => 1]);
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

            // creates a repository for ViewProdutoOrcamento
            $repository = new TRepository(self::$activeRecord);

            $criteria = clone $this->filter_criteria;

            if (empty($param['order']))
            {
                $param['order'] = 'produto_cod_erp';    
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
                    // add the object inside the datagrid

                    $this->datagrid->addItem($object);

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

}

