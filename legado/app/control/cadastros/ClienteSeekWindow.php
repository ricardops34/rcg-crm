<?php

class ClienteSeekWindow extends TWindow
{
    private $form; // form
    private $datagrid; // listing
    private $pageNavigation;
    private $loaded;
    private $filter_criteria;
    private static $database = 'erp_online';
    private static $activeRecord = 'Cliente';
    private static $primaryKey = 'id';
    private static $formName = 'form_ClienteSeekWindow';
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
        parent::setTitle("Consulta  Cliente");
        parent::setProperty('class', 'window_modal');

        $param['_seek_window_id'] = $this->id;
        // creates the form
        $this->form = new BootstrapFormBuilder(self::$formName);

        $this->limit = 20;

        // define the form title
        $this->form->setFormTitle("Consulta  Cliente");

        $cod_erp = new TEntry('cod_erp');
        $razao = new TEntry('razao');
        $fantasia = new TEntry('fantasia');
        $cnpj_cpf = new TEntry('cnpj_cpf');

        $razao->setSize('100%');
        $cod_erp->setSize('100%');
        $fantasia->setSize('100%');
        $cnpj_cpf->setSize('100%');

        $razao->setMaxLength(100);
        $cod_erp->setMaxLength(10);
        $fantasia->setMaxLength(50);
        $cnpj_cpf->setMaxLength(20);

        $row1 = $this->form->addFields([new TLabel("Código:", null, '14px', null, '100%'),$cod_erp],[new TLabel("Razão Social:", null, '14px', null, '100%'),$razao],[new TLabel("Nome de Fantasia:", null, '14px', null, '100%'),$fantasia],[new TLabel("CNPJ/CPF:", null, '14px', null, '100%'),$cnpj_cpf]);
        $row1->layout = [' col-sm-3',' col-sm-3',' col-sm-3',' col-sm-3'];

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

        $this->datagrid->style = 'width: 100%';
        $this->datagrid->setHeight(320);
        $this->datagrid->enablePopover("", "Endereço: {endereco} - {complemento}<br>Bairro: {bairro}<br>
Municipio: {municipio->descricao} <br>
Estado: {uf}<br>");

        $column_cod_erp = new TDataGridColumn('cod_erp', "Código", 'left');
        $column_razao = new TDataGridColumn('razao', "Razão Social", 'left');
        $column_fantasia = new TDataGridColumn('fantasia', "Nome de Fantasia", 'left');
        $column_uf = new TDataGridColumn('uf', "UF", 'left');
        $column_status_transformed = new TDataGridColumn('status', "Situacão", 'left');

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

        $this->datagrid->addColumn($column_cod_erp);
        $this->datagrid->addColumn($column_razao);
        $this->datagrid->addColumn($column_fantasia);
        $this->datagrid->addColumn($column_uf);
        $this->datagrid->addColumn($column_status_transformed);

        $action_onSelect = new TDataGridAction(array('ClienteSeekWindow', 'onSelect'));
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

        if (isset($data->cod_erp) AND ( (is_scalar($data->cod_erp) AND $data->cod_erp !== '') OR (is_array($data->cod_erp) AND (!empty($data->cod_erp)) )) )
        {

            $filters[] = new TFilter('cod_erp', 'like', "%{$data->cod_erp}%");// create the filter 
        }

        if (isset($data->razao) AND ( (is_scalar($data->razao) AND $data->razao !== '') OR (is_array($data->razao) AND (!empty($data->razao)) )) )
        {

            $filters[] = new TFilter('razao', 'like', "%{$data->razao}%");// create the filter 
        }

        if (isset($data->fantasia) AND ( (is_scalar($data->fantasia) AND $data->fantasia !== '') OR (is_array($data->fantasia) AND (!empty($data->fantasia)) )) )
        {

            $filters[] = new TFilter('fantasia', 'like', "%{$data->fantasia}%");// create the filter 
        }

        if (isset($data->cnpj_cpf) AND ( (is_scalar($data->cnpj_cpf) AND $data->cnpj_cpf !== '') OR (is_array($data->cnpj_cpf) AND (!empty($data->cnpj_cpf)) )) )
        {

            $filters[] = new TFilter('cnpj_cpf', 'like', "%{$data->cnpj_cpf}%");// create the filter 
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

