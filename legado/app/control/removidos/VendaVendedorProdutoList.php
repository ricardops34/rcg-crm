<?php

class VendaVendedorProdutoList extends TPage
{
    private $form; // form
    private $datagrid; // listing
    private $pageNavigation;
    private $loaded;
    private $filter_criteria;
    private static $database = 'erp_online';
    private static $activeRecord = 'VendaVendedorProduto';
    private static $primaryKey = 'id';
    private static $formName = 'form_VendaVendedorProdutoList';
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
        $this->form->setFormTitle("Venda Vendedor Produto");
        $this->limit = 20;

        $criteria_cliente_id = new TCriteria();
        $criteria_cliente_municipio = new TCriteria();
        $criteria_vendedor_cod = new TCriteria();
        $criteria_produto_codigo = new TCriteria();
        $criteria_produto_categoria = new TCriteria();
        $criteria_produto_sub_categoria = new TCriteria();

        $filterVar = 'MS';
        $criteria_cliente_municipio->add(new TFilter('estado_id', 'in', "(SELECT id FROM estado WHERE sigla = '{$filterVar}')")); 

        $cliente_id = new TDBUniqueSearch('cliente_id', 'erp_online', 'Cliente', 'id', 'razao','razao asc' , $criteria_cliente_id );
        $mes = new TCombo('mes');
        $ano = new TCombo('ano');
        $nota_fiscal = new TEntry('nota_fiscal');
        $cliente_municipio = new TDBCombo('cliente_municipio', 'erp_online', 'Municipio', 'descricao', '{descricao}','descricao asc' , $criteria_cliente_municipio );
        $vendedor_cod = new TDBCombo('vendedor_cod', 'erp_online', 'Vendedor', 'cod_erp', '{nome_status}','nome asc' , $criteria_vendedor_cod );
        $produto_codigo = new TDBUniqueSearch('produto_codigo', 'erp_online', 'Produto', 'cod_erp', 'descricao','descricao asc' , $criteria_produto_codigo );
        $produto_categoria = new TDBCombo('produto_categoria', 'erp_online', 'Categoria', 'cod_erp', '{cod_erp} - {descricao}','descricao asc' , $criteria_produto_categoria );
        $produto_sub_categoria = new TDBCombo('produto_sub_categoria', 'erp_online', 'SubCategoria', 'cod_erp', '{descricao}','descricao asc' , $criteria_produto_sub_categoria );


        $cliente_id->setMinLength(2);
        $produto_codigo->setMinLength(2);

        $cliente_id->setMask('{nome_status}');
        $produto_codigo->setMask('{descricao}');

        $cliente_id->setFilterColumns(["cnpj_cpf","cod_erp","fantasia","razao"]);
        $produto_codigo->setFilterColumns(["cod_erp","dados_tecnicos","descricao"]);

        $ano->addItems(TempoService::getAnos());
        $mes->addItems(TempoService::getMeses());

        $mes->setValue($param['mes'] ?? date('m'));
        $ano->setValue($param['ano'] ?? date('Y'));

        $mes->enableSearch();
        $ano->enableSearch();
        $vendedor_cod->enableSearch();
        $cliente_municipio->enableSearch();
        $produto_categoria->enableSearch();
        $produto_sub_categoria->enableSearch();

        $mes->setSize('100%');
        $ano->setSize('100%');
        $cliente_id->setSize('100%');
        $nota_fiscal->setSize('100%');
        $vendedor_cod->setSize('100%');
        $produto_codigo->setSize('100%');
        $cliente_municipio->setSize('100%');
        $produto_categoria->setSize('100%');
        $produto_sub_categoria->setSize('100%');

        $row1 = $this->form->addFields([new TLabel("Cliente:", null, '14px', null, '100%'),$cliente_id],[new TLabel("Mes:", null, '14px', null, '100%'),$mes],[new TLabel("Ano:", null, '14px', null, '100%'),$ano],[new TLabel("Nota Fiscal:", null, '14px', null, '100%'),$nota_fiscal],[new TLabel("Municipio:", null, '14px', null, '100%'),$cliente_municipio]);
        $row1->layout = [' col-sm-3',' col-sm-2',' col-sm-2',' col-sm-2','col-sm-3'];

        $row2 = $this->form->addFields([new TLabel("Vendedor:", null, '14px', null, '100%'),$vendedor_cod],[new TLabel("Produto:", null, '14px', null, '100%'),$produto_codigo],[new TLabel("Categoria:", null, '14px', null, '100%'),$produto_categoria],[new TLabel("Sub Categoria:", null, '14px', null, '100%'),$produto_sub_categoria]);
        $row2->layout = ['col-sm-3',' col-sm-3',' col-sm-3',' col-sm-3'];

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

        $this->datagrid->disableDefaultClick();
        $this->datagrid->style = 'width: 100%';
        $this->datagrid->setHeight(250);

        $column_nota_fiscal = new TDataGridColumn('nota_fiscal', "Nota Fiscal", 'left');
        $column_mes = new TDataGridColumn('mes', "Mes", 'left');
        $column_ano = new TDataGridColumn('ano', "Ano", 'left');
        $column_dt_emissao_transformed = new TDataGridColumn('dt_emissao', "Emissão", 'left');
        $column_cliente_codigo_transformed = new TDataGridColumn('cliente_codigo', "Codigo", 'left');
        $column_cliente_codigo_transformed1 = new TDataGridColumn('cliente_codigo', "Loja", 'left');
        $column_cliente_fantasia = new TDataGridColumn('cliente_fantasia', "Cliente", 'left');
        $column_cliente_estado = new TDataGridColumn('cliente_estado', "UF", 'left');
        $column_vendedor_nome = new TDataGridColumn('vendedor_nome', "Vendedor", 'left');
        $column_cliente_municipio = new TDataGridColumn('cliente_municipio', "Municipio", 'left');
        $column_produto_codigo = new TDataGridColumn('produto_codigo', "Codigo", 'left');
        $column_produto_descricao = new TDataGridColumn('produto_descricao', "Descrição", 'left');
        $column_vlr_tabela_transformed = new TDataGridColumn('vlr_tabela', "Vlr tabela", 'right');
        $column_vlr_unitario_transformed = new TDataGridColumn('vlr_unitario', "Vlr unitario", 'right');
        $column_quantidade_transformed = new TDataGridColumn('quantidade', "Quantidade", 'right');
        $column_vlr_desconto_transformed = new TDataGridColumn('vlr_desconto', "Desconto", 'right');
        $column_vlr_total_transformed = new TDataGridColumn('vlr_total', "Vlr total", 'right');

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

        $column_cliente_codigo_transformed->setTransformer(function($value, $object, $row, $cell = null, $last_row = null)
        {
            //code here
            return substr($value, 0, 6);
        });

        $column_cliente_codigo_transformed1->setTransformer(function($value, $object, $row, $cell = null, $last_row = null)
        {
            //code here
            return substr($value, 6, 2);

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

        $column_vlr_desconto_transformed->setTransformer(function($value, $object, $row, $cell = null, $last_row = null)
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
        $order_dt_emissao_transformed = new TAction(array($this, 'onReload'));
        $order_dt_emissao_transformed->setParameter('order', 'dt_emissao');
        $column_dt_emissao_transformed->setAction($order_dt_emissao_transformed);
        $order_cliente_fantasia = new TAction(array($this, 'onReload'));
        $order_cliente_fantasia->setParameter('order', 'cliente_fantasia');
        $column_cliente_fantasia->setAction($order_cliente_fantasia);
        $order_vendedor_nome = new TAction(array($this, 'onReload'));
        $order_vendedor_nome->setParameter('order', 'vendedor_nome');
        $column_vendedor_nome->setAction($order_vendedor_nome);
        $order_produto_codigo = new TAction(array($this, 'onReload'));
        $order_produto_codigo->setParameter('order', 'produto_codigo');
        $column_produto_codigo->setAction($order_produto_codigo);
        $order_produto_descricao = new TAction(array($this, 'onReload'));
        $order_produto_descricao->setParameter('order', 'produto_descricao');
        $column_produto_descricao->setAction($order_produto_descricao);

        $this->datagrid->addColumn($column_nota_fiscal);
        $this->datagrid->addColumn($column_mes);
        $this->datagrid->addColumn($column_ano);
        $this->datagrid->addColumn($column_dt_emissao_transformed);
        $this->datagrid->addColumn($column_cliente_codigo_transformed);
        $this->datagrid->addColumn($column_cliente_codigo_transformed1);
        $this->datagrid->addColumn($column_cliente_fantasia);
        $this->datagrid->addColumn($column_cliente_estado);
        $this->datagrid->addColumn($column_vendedor_nome);
        $this->datagrid->addColumn($column_cliente_municipio);
        $this->datagrid->addColumn($column_produto_codigo);
        $this->datagrid->addColumn($column_produto_descricao);
        $this->datagrid->addColumn($column_vlr_tabela_transformed);
        $this->datagrid->addColumn($column_vlr_unitario_transformed);
        $this->datagrid->addColumn($column_quantidade_transformed);
        $this->datagrid->addColumn($column_vlr_desconto_transformed);
        $this->datagrid->addColumn($column_vlr_total_transformed);

        $action_onDelete = new TDataGridAction(array('VendaVendedorProdutoList', 'onDelete'));
        $action_onDelete->setUseButton(false);
        $action_onDelete->setButtonClass('btn btn-default btn-sm');
        $action_onDelete->setLabel("Excluir");
        $action_onDelete->setImage('fas:trash-alt #dd5a43');
        $action_onDelete->setField(self::$primaryKey);
        $action_onDelete->setDisplayCondition('VendaVendedorProdutoList::PodeExcluir');

        $this->datagrid->addAction($action_onDelete);

        // create the datagrid model
        $this->datagrid->createModel();

        // creates the page navigation
        $this->pageNavigation = new TPageNavigation;
        $this->pageNavigation->enableCounters();
        $this->pageNavigation->setAction(new TAction(array($this, 'onReload')));
        $this->pageNavigation->setWidth($this->datagrid->getWidth());

        $panel = new TPanelGroup();
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

        $dropdown_button_exportar = new TDropDown("Exportar", 'fas:file-export #2d3436');
        $dropdown_button_exportar->setPullSide('right');
        $dropdown_button_exportar->setButtonClass('btn btn-default waves-effect dropdown-toggle');
        $dropdown_button_exportar->addPostAction( "CSV", new TAction(['VendaVendedorProdutoList', 'onExportCsv'],['static' => 1]), 'datagrid_'.self::$formName, 'fas:file-csv #00b894' );
        $dropdown_button_exportar->addPostAction( "XLS", new TAction(['VendaVendedorProdutoList', 'onExportXls'],['static' => 1]), 'datagrid_'.self::$formName, 'fas:file-excel #4CAF50' );
        $dropdown_button_exportar->addPostAction( "PDF", new TAction(['VendaVendedorProdutoList', 'onExportPdf'],['static' => 1]), 'datagrid_'.self::$formName, 'far:file-pdf #e74c3c' );
        $dropdown_button_exportar->addPostAction( "XML", new TAction(['VendaVendedorProdutoList', 'onExportXml'],['static' => 1]), 'datagrid_'.self::$formName, 'far:file-code #95a5a6' );

        $head_right_actions->add($dropdown_button_exportar);

        $this->datagrid_form->add($this->datagrid);

        // vertical box container
        $container = new TVBox;
        $container->style = 'width: 100%';
        if(empty($param['target_container']))
        {
            $container->add(TBreadCrumb::create(["Removidos","Venda Vendedor Produto"]));
        }
        $container->add($this->form);
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
                $object = new VendaVendedorProduto($key, FALSE); 

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
    public function onExportCsv($param = null) 
    {
        try
        {
            $output = 'app/output/'.uniqid().'.csv';

            if ( (!file_exists($output) && is_writable(dirname($output))) OR is_writable($output))
            {
                $this->limit = 0;
                $objects = $this->onReload();

                if ($objects)
                {
                    $handler = fopen($output, 'w');
                    TTransaction::open(self::$database);

                    foreach ($objects as $object)
                    {
                        $row = [];
                        foreach ($this->datagrid->getColumns() as $column)
                        {
                            $column_name = $column->getName();

                            if (isset($object->$column_name))
                            {
                                $row[] = is_scalar($object->$column_name) ? $object->$column_name : '';
                            }
                            else if (method_exists($object, 'render'))
                            {
                                $column_name = (strpos($column_name, '{') === FALSE) ? ( '{' . $column_name . '}') : $column_name;
                                $row[] = $object->render($column_name);
                            }
                        }

                        fputcsv($handler, $row);
                    }

                    fclose($handler);
                    TTransaction::close();
                }
                else
                {
                    throw new Exception(_t('No records found'));
                }

                TPage::openFile($output);
            }
            else
            {
                throw new Exception(_t('Permission denied') . ': ' . $output);
            }
        }
        catch (Exception $e) // in case of exception
        {
            new TMessage('error', $e->getMessage()); // shows the exception error message
        }
    }
    public function onExportXls($param = null) 
    {
        try
        {
            $output = 'app/output/'.uniqid().'.xls';

            if ( (!file_exists($output) && is_writable(dirname($output))) OR is_writable($output))
            {
                $widths = [];
                $titles = [];

                foreach ($this->datagrid->getColumns() as $column)
                {
                    $titles[] = $column->getLabel();
                    $width    = 100;

                    if (is_null($column->getWidth()))
                    {
                        $width = 100;
                    }
                    else if (strpos($column->getWidth(), '%') !== false)
                    {
                        $width = ((int) $column->getWidth()) * 5;
                    }
                    else if (is_numeric($column->getWidth()))
                    {
                        $width = $column->getWidth();
                    }

                    $widths[] = $width;
                }

                $table = new \TTableWriterXLS($widths);
                $table->addStyle('title',  'Helvetica', '10', 'B', '#ffffff', '#617FC3');
                $table->addStyle('data',   'Helvetica', '10', '',  '#000000', '#FFFFFF', 'LR');

                $table->addRow();

                foreach ($titles as $title)
                {
                    $table->addCell($title, 'center', 'title');
                }

                $this->limit = 0;
                $objects = $this->onReload();

                TTransaction::open(self::$database);
                if ($objects)
                {
                    foreach ($objects as $object)
                    {
                        $table->addRow();
                        foreach ($this->datagrid->getColumns() as $column)
                        {
                            $column_name = $column->getName();
                            $value = '';
                            if (isset($object->$column_name))
                            {
                                $value = is_scalar($object->$column_name) ? $object->$column_name : '';
                            }
                            else if (method_exists($object, 'render'))
                            {
                                $column_name = (strpos($column_name, '{') === FALSE) ? ( '{' . $column_name . '}') : $column_name;
                                $value = $object->render($column_name);
                            }

                            $transformer = $column->getTransformer();
                            if ($transformer)
                            {
                                $value = strip_tags(call_user_func($transformer, $value, $object, null));
                            }

                            $table->addCell($value, 'center', 'data');
                        }
                    }
                }
                $table->save($output);
                TTransaction::close();

                TPage::openFile($output);
            }
            else
            {
                throw new Exception(_t('Permission denied') . ': ' . $output);
            }
        }
        catch (Exception $e) // in case of exception
        {
            new TMessage('error', $e->getMessage()); // shows the exception error message
        }
    }
    public function onExportPdf($param = null) 
    {
        try
        {
            $output = 'app/output/'.uniqid().'.pdf';

            if ( (!file_exists($output) && is_writable(dirname($output))) OR is_writable($output))
            {
                $this->limit = 0;
                $this->datagrid->prepareForPrinting();
                $this->onReload();

                $html = clone $this->datagrid;
                $contents = file_get_contents('app/resources/styles-print.html') . $html->getContents();

                $dompdf = new \Dompdf\Dompdf;
                $dompdf->loadHtml($contents);
                $dompdf->setPaper('A4', 'portrait');
                $dompdf->render();

                file_put_contents($output, $dompdf->output());

                $window = TWindow::create('PDF', 0.8, 0.8);
                $object = new TElement('iframe');
                $object->src  = $output;
                $object->type  = 'application/pdf';
                $object->style = "width: 100%; height:calc(100% - 10px)";

                $window->add($object);
                $window->show();
            }
            else
            {
                throw new Exception(_t('Permission denied') . ': ' . $output);
            }
        }
        catch (Exception $e) // in case of exception
        {
            new TMessage('error', $e->getMessage()); // shows the exception error message
        }
    }
    public function onExportXml($param = null) 
    {
        try
        {
            $output = 'app/output/'.uniqid().'.xml';

            if ( (!file_exists($output) && is_writable(dirname($output))) OR is_writable($output))
            {
                $this->limit = 0;
                $objects = $this->onReload();

                if ($objects)
                {
                    TTransaction::open(self::$database);

                    $dom = new DOMDocument('1.0', 'UTF-8');
                    $dom->{'formatOutput'} = true;
                    $dataset = $dom->appendChild( $dom->createElement('dataset') );

                    foreach ($objects as $object)
                    {
                        $row = $dataset->appendChild( $dom->createElement( self::$activeRecord ) );

                        foreach ($this->datagrid->getColumns() as $column)
                        {
                            $column_name = $column->getName();
                            $column_name_raw = str_replace(['(','{','->', '-','>','}',')', ' '], ['','','_','','','','','_'], $column_name);

                            if (isset($object->$column_name))
                            {
                                $value = is_scalar($object->$column_name) ? $object->$column_name : '';
                                $row->appendChild($dom->createElement($column_name_raw, $value)); 
                            }
                            else if (method_exists($object, 'render'))
                            {
                                $column_name = (strpos($column_name, '{') === FALSE) ? ( '{' . $column_name . '}') : $column_name;
                                $value = $object->render($column_name);
                                $row->appendChild($dom->createElement($column_name_raw, $value));
                            }
                        }
                    }

                    $dom->save($output);

                    TTransaction::close();
                }
                else
                {
                    throw new Exception(_t('No records found'));
                }

                TPage::openFile($output);
            }
            else
            {
                throw new Exception(_t('Permission denied') . ': ' . $output);
            }
        }
        catch (Exception $e) // in case of exception
        {
            new TMessage('error', $e->getMessage()); // shows the exception error message
            TTransaction::rollback(); // undo all pending operations
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

        if (isset($data->cliente_id) AND ( (is_scalar($data->cliente_id) AND $data->cliente_id !== '') OR (is_array($data->cliente_id) AND (!empty($data->cliente_id)) )) )
        {

            $filters[] = new TFilter('cliente_id', '=', $data->cliente_id);// create the filter 
        }

        if (isset($data->mes) AND ( (is_scalar($data->mes) AND $data->mes !== '') OR (is_array($data->mes) AND (!empty($data->mes)) )) )
        {

            $filters[] = new TFilter('mes', '=', $data->mes);// create the filter 
        }

        if (isset($data->ano) AND ( (is_scalar($data->ano) AND $data->ano !== '') OR (is_array($data->ano) AND (!empty($data->ano)) )) )
        {

            $filters[] = new TFilter('ano', '=', $data->ano);// create the filter 
        }

        if (isset($data->nota_fiscal) AND ( (is_scalar($data->nota_fiscal) AND $data->nota_fiscal !== '') OR (is_array($data->nota_fiscal) AND (!empty($data->nota_fiscal)) )) )
        {

            $filters[] = new TFilter('nota_fiscal', 'like', "%{$data->nota_fiscal}%");// create the filter 
        }

        if (isset($data->cliente_municipio) AND ( (is_scalar($data->cliente_municipio) AND $data->cliente_municipio !== '') OR (is_array($data->cliente_municipio) AND (!empty($data->cliente_municipio)) )) )
        {

            $filters[] = new TFilter('cliente_municipio', '=', $data->cliente_municipio);// create the filter 
        }

        if (isset($data->vendedor_cod) AND ( (is_scalar($data->vendedor_cod) AND $data->vendedor_cod !== '') OR (is_array($data->vendedor_cod) AND (!empty($data->vendedor_cod)) )) )
        {

            $filters[] = new TFilter('vendedor_cod', '=', $data->vendedor_cod);// create the filter 
        }

        if (isset($data->produto_codigo) AND ( (is_scalar($data->produto_codigo) AND $data->produto_codigo !== '') OR (is_array($data->produto_codigo) AND (!empty($data->produto_codigo)) )) )
        {

            $filters[] = new TFilter('produto_codigo', '=', $data->produto_codigo);// create the filter 
        }

        if (isset($data->produto_categoria) AND ( (is_scalar($data->produto_categoria) AND $data->produto_categoria !== '') OR (is_array($data->produto_categoria) AND (!empty($data->produto_categoria)) )) )
        {

            $filters[] = new TFilter('produto_categoria', '=', $data->produto_categoria);// create the filter 
        }

        if (isset($data->produto_sub_categoria) AND ( (is_scalar($data->produto_sub_categoria) AND $data->produto_sub_categoria !== '') OR (is_array($data->produto_sub_categoria) AND (!empty($data->produto_sub_categoria)) )) )
        {

            $filters[] = new TFilter('produto_sub_categoria', '=', $data->produto_sub_categoria);// create the filter 
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
            if (empty($_REQUEST['method']) || ($_REQUEST['method'] == 'onShow'))
            {
                return;
            }
            // open a transaction with database 'erp_online'
            TTransaction::open(self::$database);

            // creates a repository for VendaVendedorProduto
            $repository = new TRepository(self::$activeRecord);

            $criteria = clone $this->filter_criteria;

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

