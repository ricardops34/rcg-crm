<?php

class ComodatoNotafiscalSimpleList extends TPage
{

    private $datagrid; // listing
    private $pageNavigation;
    private $loaded;
    private static $database = 'erp_online';
    private static $activeRecord = 'ClienteNotafiscal';
    private static $primaryKey = 'id';
    private static $formName = 'formList_ClienteNotafiscal';
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

        $column_nota_fiscal = new TDataGridColumn('nota_fiscal', "Nota fiscal", 'left');
        $column_serie_fiscal = new TDataGridColumn('serie_fiscal', "Serie", 'left');
        $column_especie_fiscal = new TDataGridColumn('especie_fiscal', "Especie", 'left');
        $column_condicao_id_transformed = new TDataGridColumn('condicao_id', "Condicao", 'left');
        $column_dt_emissao_transformed = new TDataGridColumn('dt_emissao', "Emissão", 'left');
        $column_vlr_comodato_transformed = new TDataGridColumn('vlr_comodato', "Valor NF", 'right');

        $column_vlr_comodato_transformed->setTotalFunction( function($values) { 
            return array_sum((array) $values); 
        }); 

        $column_condicao_id_transformed->setTransformer(function($value, $object, $row, $cell = null, $last_row = null)
        {

                TTransaction::open('erp_online');

                $oCondicao= CondicaoPagamento::where('id', '=', $value)->first();

                TTransaction::close();

                if($oCondicao){ 
                    return ltrim(rtrim($oCondicao->descricao));
                }else{
                    if(empty($value)){
                        return "Sem Pagamento";
                    }
                    return $value;
                }

        });

        $column_dt_emissao_transformed->setTransformer(function($value, $object, $row, $cell = null, $last_row = null)
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

        $column_vlr_comodato_transformed->setTransformer(function($value, $object, $row, $cell = null, $last_row = null)
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

        $column_id = new TDataGridColumn('id', "Id", 'center' , '70px');

        $column_nota_fiscal->setTransformer(function($value, $object, $row, $cell = null, $last_row = null)
        {

            $action_nota_fiscal = new TAction( ['NotaSaidaFormView', 'onShow' ] );
            $action_nota_fiscal->setParameter('key', $object->id);
            $action_nota_fiscal->setParameter('id', $object->id);

            $link_nota_fiscal = new TActionLink($value, $action_nota_fiscal,'blue');//, 'blue', 12, 'biu');
            return $link_nota_fiscal;//"R$ " . number_format($value, 2, ",", ".");
        });

        $this->datagrid->addColumn($column_nota_fiscal);
        $this->datagrid->addColumn($column_serie_fiscal);
        $this->datagrid->addColumn($column_especie_fiscal);
        $this->datagrid->addColumn($column_condicao_id_transformed);
        $this->datagrid->addColumn($column_dt_emissao_transformed);
        $this->datagrid->addColumn($column_vlr_comodato_transformed);

        // create the datagrid model
        $this->datagrid->createModel();

        $panel = new TPanelGroup();
        $panel->datagrid = 'datagrid-container';
        $this->datagridPanel = $panel;
        $this->datagrid_form->add($this->datagrid);
        $panel->add($this->datagrid_form);

        $panel->getBody()->class .= ' table-responsive';

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

        parent::add($form);

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

            // creates a repository for ClienteNotafiscal
            $repository = new TRepository(self::$activeRecord);
            // creates a criteria
            $criteria = new TCriteria;

            if(!empty($param["cliente_id"] ?? ""))
        {
            TSession::setValue(__CLASS__.'load_filter_cliente_id', $param["cliente_id"] ?? "");
        }
        $filterVar = TSession::getValue(__CLASS__.'load_filter_cliente_id');
            $criteria->add(new TFilter('cliente_id', '=', $filterVar));
            $filterVar = 'S';
            $criteria->add(new TFilter('comodato', '=', $filterVar));

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

            $criteria->add(new TFilter('vlr_comodato - vlr_devolucao ', '>', 0));

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

        $object = new ClienteNotafiscal($id);

        $row = $list->datagrid->addItem($object);
        $row->id = "row_{$object->id}";

        if($openTransaction)
        {
            TTransaction::close();    
        }

        TDataGrid::replaceRowById(__CLASS__.'_datagrid', $row->id, $row);
    }

}

