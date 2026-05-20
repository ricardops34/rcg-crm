<?php

class ViewTotalCatogoriaMesSimpleList extends TPage
{

    private $datagrid; // listing
    private $pageNavigation;
    private $loaded;
    private static $database = 'erp_online';
    private static $activeRecord = 'ViewTotalCatogoriaMes';
    private static $primaryKey = 'id';
    private static $formName = 'formList_ViewTotalCatogoriaMes';
    private $limit = 20;

    public function __construct($param = null)
    {
        parent::__construct();

        if(!empty($param['target_container']))
        {
            $this->adianti_target_container = $param['target_container'];
        }

        $this->limit = 0;

        if($param['vendedor'] == ''){

        }

        // creates a Datagrid
        $this->datagrid = new TDataGrid;
        $this->datagrid->setId(__CLASS__.'_datagrid');

        $this->datagrid_form = new TForm('datagrid_'.self::$formName);
        $this->datagrid_form->onsubmit = 'return false';

        $this->datagrid = new BootstrapDatagridWrapper($this->datagrid);

        $this->datagrid->disableDefaultClick();
        $this->datagrid->style = 'width: 100%';
        $this->datagrid->setHeight(320);

        $column_cod_erp = new TDataGridColumn('cod_erp', "Código", 'left' , '10%');
        $column_categoria = new TDataGridColumn('categoria', "Categoria", 'left' , '40%');
        $column_vlr_objetivo_transformed = new TDataGridColumn('vlr_objetivo', "Objetivo", 'right' , '15%');
        $column_vlr_liquido_transformed = new TDataGridColumn('vlr_liquido', "Realizado", 'right' , '15%');
        $column_perc_liquido_transformed = new TDataGridColumn('perc_liquido', "%", 'center' , '20%');

        $column_cod_erp->enableAutoHide('600');
        $column_perc_liquido_transformed->enableAutoHide('500');

        $column_cod_erp->setTotalFunction( function($values) { 
            return count((array) $values); 
        }); 

        $column_vlr_objetivo_transformed->setTotalFunction( function($values) { 
            return array_sum((array) $values); 
        }); 

        $column_vlr_liquido_transformed->setTotalFunction( function($values) { 
            return array_sum((array) $values); 
        }); 

        $column_vlr_objetivo_transformed->setTransformer(function($value, $object, $row, $cell = null, $last_row = null)
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

        $column_vlr_liquido_transformed->setTransformer(function($value, $object, $row, $cell = null, $last_row = null)
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

        $column_perc_liquido_transformed->setTransformer(function($value, $object, $row, $cell = null, $last_row = null)
        {

            $base = (double) $object->vlr_liquido;
            $objetivo = (double) $object->vlr_objetivo;
            $percent = 0;
            if($objetivo > 0){
                $percent = round($base * 100/$objetivo,2);
            }

            $bar = new TProgressBar;
            $bar->setMask('<b>'.number_format($percent, 2, ",", ".").' %</b>');
            $bar->setValue(round($percent,0));

            if ($percent >= 100) {
                $bar->setClass('progress-bar progress-bar-striped progress-bar-animated');
                //$bar->setClass('progress-bar-striped bg-success');
            }
            else if ($percent >= 75 and $percent < 100) {
                $bar->setClass('progress-bar progress-bar-striped progress-bar-animated bg-info');
                //$bar->setClass('info');
            }
            else if ($percent >= 50 and $percent < 75) {
                $bar->setClass('progress-bar progress-bar-striped progress-bar-animated bg-success');
                //$bar->setClass('progress-bar-striped bg-warning');
            }
            else if ($percent >= 25 and $percent < 50) {//
                $bar->setClass('progress-bar progress-bar-striped progress-bar-animated bg-warning');
                //$bar->setClass('progress-bar-striped bg-danger');
            }
            else if ($percent < 25) {
                $bar->setClass('progress-bar progress-bar-striped progress-bar-animated bg-danger');
            }

            return $bar;

            //return $object->vendedor_id;    

        });        

        $order_cod_erp = new TAction(array($this, 'onReload'));
        $order_cod_erp->setParameter('order', 'cod_erp');
        $column_cod_erp->setAction($order_cod_erp);
        $order_categoria = new TAction(array($this, 'onReload'));
        $order_categoria->setParameter('order', 'categoria');
        $column_categoria->setAction($order_categoria);
        $order_vlr_objetivo_transformed = new TAction(array($this, 'onReload'));
        $order_vlr_objetivo_transformed->setParameter('order', 'vlr_objetivo');
        $column_vlr_objetivo_transformed->setAction($order_vlr_objetivo_transformed);
        $order_vlr_liquido_transformed = new TAction(array($this, 'onReload'));
        $order_vlr_liquido_transformed->setParameter('order', 'vlr_liquido');
        $column_vlr_liquido_transformed->setAction($order_vlr_liquido_transformed);
        $order_perc_liquido_transformed = new TAction(array($this, 'onReload'));
        $order_perc_liquido_transformed->setParameter('order', 'perc_liquido');
        $column_perc_liquido_transformed->setAction($order_perc_liquido_transformed);

        $this->datagrid->addColumn($column_cod_erp);
        $this->datagrid->addColumn($column_categoria);
        $this->datagrid->addColumn($column_vlr_objetivo_transformed);
        $this->datagrid->addColumn($column_vlr_liquido_transformed);
        $this->datagrid->addColumn($column_perc_liquido_transformed);

        // create the datagrid model
        $this->datagrid->createModel();

        $panel = new TPanelGroup();
        $panel->datagrid = 'datagrid-container';
        $this->datagridPanel = $panel;
        $this->datagrid_form->add($this->datagrid);
        $panel->add($this->datagrid_form);

        $panel->getBody()->class .= ' table-responsive';
        $this->datagrid_form->class = ' table-fixed-header {$searchComponentsClass}';
        $this->datagrid_form->style = ' height:250px;';

        // vertical box container
        $container = new TVBox;
        $container->style = 'width: 100%';
        if(empty($param['target_container']))
        {
            // $container->add(new TXMLBreadCrumb('menu.xml', __CLASS__));
        }
        $container->add($panel);

        parent::add($container);

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

            // creates a repository for ViewTotalCatogoriaMes
            $repository = new TRepository(self::$activeRecord);
            // creates a criteria
            $criteria = new TCriteria;

            if(!empty($param["mes"] ?? ""))
        {
            TSession::setValue(__CLASS__.'load_filter_mes', $param["mes"] ?? "");
        }
        $filterVar = TSession::getValue(__CLASS__.'load_filter_mes');
            $criteria->add(new TFilter('mes', '=', $filterVar));
            if(!empty($param["ano"] ?? ""))
        {
            TSession::setValue(__CLASS__.'load_filter_ano', $param["ano"] ?? "");
        }
        $filterVar = TSession::getValue(__CLASS__.'load_filter_ano');
            $criteria->add(new TFilter('ano', '=', $filterVar));
            if(!empty($param["vendedor"] ?? ""))
        {
            TSession::setValue(__CLASS__.'load_filter_vendedor_id', $param["vendedor"] ?? "");
        }
        $filterVar = TSession::getValue(__CLASS__.'load_filter_vendedor_id');
            $criteria->add(new TFilter('vendedor_id', '=', $filterVar));

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

                    $row = $this->datagrid->addItem($object);
                    $row->id = "row_{$object->id}";

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

    public static function manageRow($id, $param = [])
    {
        $list = new self($param);

        $openTransaction = TTransaction::getDatabase() != self::$database ? true : false;

        if($openTransaction)
        {
            TTransaction::open(self::$database);    
        }

        $object = new ViewTotalCatogoriaMes($id);

        $row = $list->datagrid->addItem($object);
        $row->id = "row_{$object->id}";

        if($openTransaction)
        {
            TTransaction::close();    
        }

        TDataGrid::replaceRowById(__CLASS__.'_datagrid', $row->id, $row);
    }

}

