<?php

class NotaSaidaItemList extends TPage
{
    private $form; // form
    private $datagrid; // listing
    private $pageNavigation;
    private $loaded;
    private $filter_criteria;
    private static $database = 'erp_online';
    private static $activeRecord = 'NotaSaidaItem';
    private static $primaryKey = 'id';
    private static $formName = 'form_NotaSaidaItemList';
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
        $this->form->setFormTitle("Itens Nota Saida");
        $this->limit = 0;

        $produto_cod_erp = new TEntry('produto_cod_erp');
        $produto_descricao = new TEntry('produto_descricao');


        $produto_cod_erp->setSize('100%');
        $produto_descricao->setSize('100%');

        $row1 = $this->form->addFields([new TLabel("Codigo:", null, '14px', null, '100%'),$produto_cod_erp],[new TLabel("Descrição:", null, '14px', null, '100%'),$produto_descricao],[]);
        $row1->layout = ['col-sm-4',' col-sm-6','col-sm-2'];

        // keep the form filled during navigation with session data
        $this->form->setData( TSession::getValue(__CLASS__.'_filter_data') );

        $btn_onsearch = $this->form->addHeaderAction("Buscar", new TAction([$this, 'onSearch']), 'fas:search #ffffff');
        $this->btn_onsearch = $btn_onsearch;
        $btn_onsearch->addStyleClass('btn-primary'); 

        // creates a Datagrid
        $this->datagrid = new TDataGrid;
        $this->datagrid->setId(__CLASS__.'_datagrid');

        $this->datagrid_form = new TForm('datagrid_'.self::$formName);
        $this->datagrid_form->onsubmit = 'return false';

        $this->datagrid = new BootstrapDatagridWrapper($this->datagrid);
        $this->filter_criteria = new TCriteria;

        if(!empty($param["key"] ?? ""))
        {
            TSession::setValue(__CLASS__.'load_filter_cliente_id', $param["key"] ?? "");
        }
        $filterVar = TSession::getValue(__CLASS__.'load_filter_cliente_id');
        $this->filter_criteria->add(new TFilter('cliente_id', '=', $filterVar));
        $filterVar = 'S';
        $this->filter_criteria->add(new TFilter('reg_ativo', '=', $filterVar));

        $this->datagrid->style = 'width: 100%';
        $this->datagrid->setHeight(250);

        $column_produto_id_transformed = new TDataGridColumn('produto_id', "Produto", 'left' , '30%');
        $column_nota_saida_nota_fiscal = new TDataGridColumn('nota_saida->nota_fiscal', "NF", 'left' , '8%');
        $column_qtd_transformed = new TDataGridColumn('qtd', "Qtd", 'left' , '5%');
        $column_vlr_unitario_transformed = new TDataGridColumn('vlr_unitario', "Vlr Venda", 'right' , '10%');
        $column_perc_desconto_transformed = new TDataGridColumn('perc_desconto', "Desconto", 'right' , '10%');
        $column_dt_emissao_transformed = new TDataGridColumn('dt_emissao', "Emissão", 'right' , '10%');
        $column_produto_id = new TDataGridColumn('produto_id', "Vlr Atual", 'right' , '10%');

        $column_produto_id_transformed->setTransformer(function($value, $object, $row, $cell = null, $last_row = null)
        {
                $return = $value;
                TTransaction::open('erp_online');

                $oProduto= Produto::find( $object->produto_id );

                if($oProduto){

                    $cNome = ltrim(rtrim($oProduto->cod_erp)).' - '.ltrim(rtrim($oProduto->descricao));//.'('. rtrim(ltrim($this->razao)).')');
                    $return = '<b>'.$cNome.'</b>';;
                    if($oProduto->status == 'B'){ //fas fa-lock
                        $icone = new TElement('i');
                        $icone->class="fas fa-lock";
                        $return = $icone.'<s>'.$cNome.'</s>';
                        $row->style = "background: #FFF9A7";
                    }

                }

                TTransaction::close();

                return $return;//code here

        });

        $column_qtd_transformed->setTransformer(function($value, $object, $row, $cell = null, $last_row = null)
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

        $column_perc_desconto_transformed->setTransformer(function($value, $object, $row, $cell = null, $last_row = null)
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

        $order_dt_emissao_transformed = new TAction(array($this, 'onReload'));
        $order_dt_emissao_transformed->setParameter('order', 'dt_emissao');
        $column_dt_emissao_transformed->setAction($order_dt_emissao_transformed);

        $column_produto_id->setTransformer(function($value, $object, $row, $cell = null, $last_row = null)
        {
                $return = '';
                TTransaction::open('erp_online');

                $oCliente= Cliente::find( $object->cliente_id );
                if($oCliente){
                    $oProduto= Produto::find( $object->produto_id );
                    if($oProduto){

                        if(isset($oCliente->tabela_preco_id)){
                            $oTabelaPreco = TabelaPrecoItem::where('tabela_preco_id', '=', $oCliente->tabela_preco_id)
                                    ->where('produto_id', '=', $oProduto->id)
                                    ->first();

                            if($oTabelaPreco){
                                $return ="R$ ".number_format($oTabelaPreco->preco, 2, ",", ".");
                            }

                        }else{
                            if($oCliente->municipio->descricao == "Campo Grande"){

                            }
                        }
                    }
                }
                TTransaction::close();

                return $return;

        });

        $this->datagrid->addColumn($column_produto_id_transformed);
        $this->datagrid->addColumn($column_nota_saida_nota_fiscal);
        $this->datagrid->addColumn($column_qtd_transformed);
        $this->datagrid->addColumn($column_vlr_unitario_transformed);
        $this->datagrid->addColumn($column_perc_desconto_transformed);
        $this->datagrid->addColumn($column_dt_emissao_transformed);
        $this->datagrid->addColumn($column_produto_id);


        $this->datagrid->setHeight(300);
        $this->datagrid->makeScrollable();

        // create the datagrid model
        $this->datagrid->createModel();

        $panel = new TPanelGroup();
        $panel->datagrid = 'datagrid-container';
        $this->datagridPanel = $panel;

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

        $this->datagrid_form->add($headerActions);

        $button_limpar_filtros = new TButton('button_button_limpar_filtros');
        $button_limpar_filtros->setAction(new TAction(['NotaSaidaItemList', 'onClearFilters']), "Limpar filtros");
        $button_limpar_filtros->addStyleClass('btn-default');
        $button_limpar_filtros->setImage('fas:eraser #f44336');

        $this->datagrid_form->addField($button_limpar_filtros);

        $button_atualizar = new TButton('button_button_atualizar');
        $button_atualizar->setAction(new TAction(['NotaSaidaItemList', 'onRefresh']), "Atualizar");
        $button_atualizar->addStyleClass('btn-default');
        $button_atualizar->setImage('fas:sync-alt #03a9f4');

        $this->datagrid_form->addField($button_atualizar);

        $head_left_actions->add($button_limpar_filtros);
        $head_left_actions->add($button_atualizar);

        $this->datagrid_form->add($this->datagrid);

        $button_buscar = new TButton('button_button_buscar');
        $button_buscar->setAction(new TAction([$this, 'onSearch']), "Buscar");
        $button_buscar->addStyleClass('btn-default');
        $button_buscar->setImage('fas:search #000000');
        $this->datagrid_form->addField($button_buscar);

        // vertical box container
        $container = new TVBox;
        $container->style = 'width: 100%';
        if(empty($param['target_container']))
        {
            // $container->add(new TXMLBreadCrumb('menu.xml', __CLASS__));
        }
        $container->add($this->form);

        $container->add($panel);

        parent::add($container);

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

    /**
     * Register the filter in the session
     */
    public function onSearch($param = null)
    {
        $data = $this->form->getData();
        $filters = [];

        TSession::setValue(__CLASS__.'_filter_data', NULL);
        TSession::setValue(__CLASS__.'_filters', NULL);

        if (isset($data->produto_cod_erp) AND ( (is_scalar($data->produto_cod_erp) AND $data->produto_cod_erp !== '') OR (is_array($data->produto_cod_erp) AND (!empty($data->produto_cod_erp)) )) )
        {

            $filters[] = new TFilter('produto_id', 'in', "(SELECT id FROM produto WHERE cod_erp like '%{$data->produto_cod_erp}%')");// create the filter 
        }

        if (isset($data->produto_descricao) AND ( (is_scalar($data->produto_descricao) AND $data->produto_descricao !== '') OR (is_array($data->produto_descricao) AND (!empty($data->produto_descricao)) )) )
        {

            $filters[] = new TFilter('produto_id', 'in', "(SELECT id FROM produto WHERE descricao like '%{$data->produto_descricao}%')");// create the filter 
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

            // creates a repository for NotaSaidaItem
            $repository = new TRepository(self::$activeRecord);

            $criteria = clone $this->filter_criteria;

            if (empty($param['order']))
            {
                $param['order'] = 'dt_emissao';    
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

        $object = new NotaSaidaItem($id);

        $row = $list->datagrid->addItem($object);
        $row->id = "row_{$object->id}";

        if($openTransaction)
        {
            TTransaction::close();    
        }

        TDataGrid::replaceRowById(__CLASS__.'_datagrid', $row->id, $row);
    }

}

