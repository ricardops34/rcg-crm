<?php

class VendaVendedorProdutoSimpleList extends TPage
{

    private $datagrid; // listing
    private $pageNavigation;
    private $loaded;
    private static $database = 'erp_online';
    private static $activeRecord = 'VendaVendedorProduto';
    private static $primaryKey = 'id';
    private static $formName = 'formList_VendaVendedorProduto';
    private $limit = 20;

    public function __construct($param = null)
    {
        parent::__construct();

        if(!empty($param['target_container']))
        {
            $this->adianti_target_container = $param['target_container'];
        }

        $this->limit = 0;

        //var_dump($param);

        // creates a Datagrid
        $this->datagrid = new TDataGrid;
        $this->datagrid->setId(__CLASS__.'_datagrid');

        $this->datagrid_form = new TForm('datagrid_'.self::$formName);
        $this->datagrid_form->onsubmit = 'return false';

        $this->datagrid = new BootstrapDatagridWrapper($this->datagrid);

        $this->datagrid->style = 'width: 100%';
        $this->datagrid->setHeight(320);

        $column_nota_fiscal = new TDataGridColumn('nota_fiscal', "Nota Fiscal", 'left');
        $column_serie_fiscal = new TDataGridColumn('serie_fiscal', "Serie fiscal", 'left');
        $column_dt_emissao = new TDataGridColumn('dt_emissao', "Emissão", 'left');
        $column_produto_descricao = new TDataGridColumn('produto_descricao', "Produto", 'left' , '25%');
        $column_vlr_tabela_transformed = new TDataGridColumn('vlr_tabela', "Tabela", 'right');
        $column_quantidade_transformed = new TDataGridColumn('quantidade', "Quantidade", 'right');
        $column_vlr_unitario_transformed = new TDataGridColumn('vlr_unitario', "Unitario", 'right');
        $column_vlr_desconto_transformed = new TDataGridColumn('vlr_desconto', "Desconto", 'right');
        $column_vlr_total_transformed = new TDataGridColumn('vlr_total', "Total", 'right');

        $column_vlr_total_transformed->setTotalFunction( function($values) { 
            return array_sum((array) $values); 
        }); 

        $column_vlr_tabela_transformed->setTransformer(function($value, $object, $row, $cell = null, $last_row = null)
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

        $column_quantidade_transformed->setTransformer(function($value, $object, $row, $cell = null, $last_row = null)
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

        $column_vlr_unitario_transformed->setTransformer(function($value, $object, $row, $cell = null, $last_row = null)
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

        $column_vlr_desconto_transformed->setTransformer(function($value, $object, $row, $cell = null, $last_row = null)
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

        $column_vlr_total_transformed->setTransformer(function($value, $object, $row, $cell = null, $last_row = null)
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

        $order_nota_fiscal = new TAction(array($this, 'onReload'));
        $order_nota_fiscal->setParameter('order', 'nota_fiscal');
        $column_nota_fiscal->setAction($order_nota_fiscal);
        $order_produto_descricao = new TAction(array($this, 'onReload'));
        $order_produto_descricao->setParameter('order', 'produto_descricao');
        $column_produto_descricao->setAction($order_produto_descricao);

        $this->datagrid->addColumn($column_nota_fiscal);
        $this->datagrid->addColumn($column_serie_fiscal);
        $this->datagrid->addColumn($column_dt_emissao);
        $this->datagrid->addColumn($column_produto_descricao);
        $this->datagrid->addColumn($column_vlr_tabela_transformed);
        $this->datagrid->addColumn($column_quantidade_transformed);
        $this->datagrid->addColumn($column_vlr_unitario_transformed);
        $this->datagrid->addColumn($column_vlr_desconto_transformed);
        $this->datagrid->addColumn($column_vlr_total_transformed);

        // create the datagrid model
        $this->datagrid->createModel();

        $panel = new TPanelGroup();
        $panel->datagrid = 'datagrid-container';
        $this->datagridPanel = $panel;
        $this->datagrid_form->add($this->datagrid);
        $panel->add($this->datagrid_form);

        $panel->getBody()->class .= ' table-responsive';

        // vertical box container
        $container = new TVBox;
        $container->style = 'width: 100%';
        if(empty($param['target_container']))
        {
            $container->add(TBreadCrumb::create(["Gerencia","VendaVendedorProdutoSimpleList"]));
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

            // creates a repository for VendaVendedorProduto
            $repository = new TRepository(self::$activeRecord);
            // creates a criteria
            $criteria = new TCriteria;

            if(!empty($param["cliente_id"] ?? ""))
        {
            TSession::setValue(__CLASS__.'load_filter_cliente_id', $param["cliente_id"] ?? "");
        }
        $filterVar = TSession::getValue(__CLASS__.'load_filter_cliente_id');
            $criteria->add(new TFilter('cliente_id', '=', $filterVar));
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

            if (empty($param['order']))
            {
                $param['order'] = 'nota_fiscal';    
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

        /*$cParam = TSession::getValue('VendasClienteMes');

        var_dump($cParam);

        $param["cliente_id"] = 3175;
        $param["mes"] = '01';
        $param["ano"] = '2023';
        */
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

        $object = new VendaVendedorProduto($id);

        $row = $list->datagrid->addItem($object);
        $row->id = "row_{$object->id}";

        if($openTransaction)
        {
            TTransaction::close();    
        }

        TDataGrid::replaceRowById(__CLASS__.'_datagrid', $row->id, $row);
    }

}

