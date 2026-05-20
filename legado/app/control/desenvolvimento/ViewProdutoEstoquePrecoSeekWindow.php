<?php

class ViewProdutoEstoquePrecoSeekWindow extends TWindow
{
    private $form; // form
    private $datagrid; // listing
    private $pageNavigation;
    private $loaded;
    private $filter_criteria;
    private static $database = 'erp_online';
    private static $activeRecord = 'ViewProdutoEstoquePreco';
    private static $primaryKey = 'produto_id';
    private static $formName = 'form_ViewProdutoEstoquePrecoSeekWindow';
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
        parent::setTitle("Produto_orcamento");
        parent::setProperty('class', 'window_modal');

        $param['_seek_window_id'] = $this->id;
        // creates the form
        $this->form = new BootstrapFormBuilder(self::$formName);

        $this->limit = 20;

        // define the form title
        $this->form->setFormTitle("Produto_orcamento");

        $produto_cod_erp = new TEntry('produto_cod_erp');
        $produto_descricao = new TEntry('produto_descricao');

        $produto_cod_erp->setSize('100%');
        $produto_descricao->setSize('100%');

        $row1 = $this->form->addFields([new TLabel("Produto:", null, '14px', null, '100%'),$produto_cod_erp],[new TLabel("Descricao:", null, '14px', null, '100%'),$produto_descricao]);
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

        $filterVar = 0;
        $this->filter_criteria->add(new TFilter('preco', '>', $filterVar));
        $filterVar = 0;
        $this->filter_criteria->add(new TFilter('produto_saldo', '>', $filterVar));
        $filterVar = TSession::getValue('orcamento_tabela_preco_id');
        $this->filter_criteria->add(new TFilter('tabela_preco_id', '=', $filterVar));
        $filterVar = '01';
        $this->filter_criteria->add(new TFilter('armazem_cod_erp', '=', $filterVar));

        $this->datagrid->style = 'width: 100%';
        $this->datagrid->setHeight(320);

        $column_produto_cod_erp = new TDataGridColumn('produto_cod_erp', "Código", 'left');
        $column_produto_descricao = new TDataGridColumn('produto_descricao', "Descrição", 'left');
        $column_armazem_id_transformed = new TDataGridColumn('armazem_id', "Armazem", 'left');
        $column_tabela_preco_id = new TDataGridColumn('tabela_preco_id', "Precos id", 'left');
        $column_precos_descricao = new TDataGridColumn('precos_descricao', "Tabela", 'left');
        $column_preco_transformed = new TDataGridColumn('preco', "Preco", 'right');
        $column_produto_saldo_transformed = new TDataGridColumn('produto_saldo', "Saldo", 'right');

        $column_armazem_id_transformed->setTransformer(function($value, $object, $row, $cell = null, $last_row = null)
        {
            TTransaction::open('erp_online');

            $value = $object->armazem_descricao;
            $oArmazem = Armazem::find( $object->armazem_id );

            if($oArmazem){
                $value = $oArmazem->cod_erp.' - '.$oArmazem->descricao;
            }

            TTransaction::close();
            return $value;

        });

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

        $this->datagrid->addColumn($column_produto_cod_erp);
        $this->datagrid->addColumn($column_produto_descricao);
        $this->datagrid->addColumn($column_armazem_id_transformed);
        $this->datagrid->addColumn($column_tabela_preco_id);
        $this->datagrid->addColumn($column_precos_descricao);
        $this->datagrid->addColumn($column_preco_transformed);
        $this->datagrid->addColumn($column_produto_saldo_transformed);

        $action_onSelect = new TDataGridAction(array('ViewProdutoEstoquePrecoSeekWindow', 'onSelect'));
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

                //$filterVar = TSession::getValue('orcamento_tabela_preco_id');
                //$criteria->add(new TFilter('tabela_preco_id', '=', $filterVar));

                //$oArmazem = Armazem::where('cod_erp', '=', "01")->first();
                //if($oArmazem){
                    //$criteria->add(new TFilter('armazem_id', '=', $oArmazem->id));
                //}

            $filterVar = 0;
            $criteria->add(new TFilter('preco', '>', $filterVar));

            $filterVar = 0;
            $criteria->add(new TFilter('produto_saldo', '>', $filterVar));

            $filterVar = TSession::getValue('orcamento_tabela_preco_id');
            $criteria->add(new TFilter('tabela_preco_id', '=', $filterVar));

            $filterVar = '01';
            $criteria->add(new TFilter('armazem_cod_erp', '=', $filterVar));

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

            // creates a repository for ViewProdutoEstoquePreco
            $repository = new TRepository(self::$activeRecord);

            $criteria = clone $this->filter_criteria;

            if (empty($param['order']))
            {
                $param['order'] = 'produto_cod_erp';    
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

