<?php

class NegociacaoList extends TPage
{
    private $form; // form
    private $datagrid; // listing
    private $pageNavigation;
    private $loaded;
    private $filter_criteria;
    private static $database = 'erp_online';
    private static $activeRecord = 'ViewClienteSaldoTitulo';
    private static $primaryKey = 'id';
    private static $formName = 'form_NegociacaoList';
    private $showMethods = ['onReload', 'onSearch', 'onRefresh', 'onClearFilters', 'onGlobalSearch'];
    private $limit = 20;

    private $filtrarativo = true;
    private $filtrarbloqueado = false;
    private $filtraraberto  = false;
    private $filtrarvencido = true;

    use BuilderDatagridTrait;

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
        $this->form->setFormTitle("Negociação");
        $this->limit = 20;

        $criteria_vendedor_id = new TCriteria();
        $criteria_situacao_id = new TCriteria();

        $filterVar = 'A';
        $criteria_vendedor_id->add(new TFilter('status', '=', $filterVar)); 

        $cod_erp = new TEntry('cod_erp');
        $razao = new TEntry('razao');
        $fantasia = new TEntry('fantasia');
        $vendedor_id = new TDBCombo('vendedor_id', 'erp_online', 'Vendedor', 'id', '{nome_status}','nome_reduzido asc' , $criteria_vendedor_id );
        $status = new TCombo('status');
        $tipo = new TRadioGroup('tipo');
        $situacao_id = new TDBCombo('situacao_id', 'erp_online', 'SituacaoCadastral', 'id', '{descricao}','descricao asc' , $criteria_situacao_id );


        $tipo->setLayout('horizontal');
        $tipo->setUseButton();
        $tipo->setBreakItems(2);
        $status->addItems(["A"=>"Ativo","B"=>"Bloqueado"]);
        $tipo->addItems(["A"=>"Aberto","V"=>"Vencido","T"=>"Todos"]);

        $status->enableSearch();
        $vendedor_id->enableSearch();
        $situacao_id->enableSearch();

        $tipo->setSize(80);
        $razao->setSize('100%');
        $status->setSize('100%');
        $cod_erp->setSize('100%');
        $fantasia->setSize('100%');
        $vendedor_id->setSize('100%');
        $situacao_id->setSize('100%');

        $row1 = $this->form->addFields([new TLabel("Código:", null, '14px', null, '100%'),$cod_erp],[new TLabel("Razao:", null, '14px', null, '100%'),$razao],[new TLabel("Fantasia:", null, '14px', null, '100%'),$fantasia]);
        $row1->layout = [' col-sm-2',' col-sm-5',' col-sm-5'];

        $row2 = $this->form->addFields([new TLabel("Vendedor:", null, '14px', null, '100%'),$vendedor_id],[new TLabel("Situação:", null, '14px', null, '100%'),$status],[new TLabel("Tipo:", null, '14px', null, '100%'),$tipo],[new TLabel("RFB:", null, '14px', null, '100%'),$situacao_id]);
        $row2->layout = [' col-sm-3',' col-sm-3',' col-sm-3',' col-sm-3'];

        // keep the form filled during navigation with session data
        $this->form->setData( TSession::getValue(__CLASS__.'_filter_data') );

        $btn_onsearch = $this->form->addAction("Buscar", new TAction([$this, 'onSearch']), 'fas:search #ffffff');
        $this->btn_onsearch = $btn_onsearch;
        $btn_onsearch->addStyleClass('btn-primary'); 

        // creates a Datagrid
        $this->datagrid = new TDataGrid;
        $this->datagrid->enableUserProperties('fa fa-cog', 'btn btn-default', new TAction([$this, 'setDatagridProperties']));
        $this->datagrid->setId(__CLASS__.'_datagrid');

        $this->datagrid_form = new TForm('datagrid_'.self::$formName);
        $this->datagrid_form->onsubmit = 'return false';

        $this->datagrid = new BootstrapDatagridWrapper($this->datagrid);
        $this->filter_criteria = new TCriteria;

        $filterVar = 0;
        $this->filter_criteria->add(new TFilter('saldo', '>', $filterVar));

        $this->datagrid->style = 'width: 100%';
        $this->datagrid->setHeight(250);

        $column_cod_erp = new TDataGridColumn('cod_erp', "Código", 'left');
        $column_razao = new TDataGridColumn('razao', "Razão Social", 'left');
        $column_fantasia = new TDataGridColumn('fantasia', "Nome Fantasia", 'left');
        $column_vendedor_id_transformed = new TDataGridColumn('vendedor_id', "Vendedor", 'left');
        $column_aberto_transformed = new TDataGridColumn('aberto', "Saldo Aberto", 'right');
        $column_vencido_transformed = new TDataGridColumn('vencido', "Saldo Vencido", 'right');
        $column_MaiorAtraso = new TDataGridColumn('MaiorAtraso', "Maior Atraso", 'left');
        $column_quantidade = new TDataGridColumn('quantidade', "Qtd", 'left');
        $column_situacao_id_transformed = new TDataGridColumn('situacao_id', "RFB", 'right');
        $column_status_transformed = new TDataGridColumn('status', "Situação", 'right');

        $column_vendedor_id_transformed->setTransformer(function($value, $object, $row, $cell = null, $last_row = null)
        {

                TTransaction::open('erp_online');

                $oVendedor = Vendedor::where('id', '=', $value)->first();

                TTransaction::close();

                if($oVendedor){ 
                    return ltrim(rtrim($oVendedor->nome_reduzido));
                }else{
                    return $value;
                }

        });

        $column_aberto_transformed->setTransformer(function($value, $object, $row, $cell = null, $last_row = null)
        {
            if(!$value)
            {
                $value = 0;
            }

            if(is_numeric($value))
            {
                return "<span style='color:green'>R$ " .number_format($value, 2, ",", ".")."</span>";
            }
            else
            {
                return $value;
            }

        });

        $column_vencido_transformed->setTransformer(function($value, $object, $row, $cell = null, $last_row = null)
        {
            if(!$value)
            {
                $value = 0;
            }

            if(is_numeric($value))
            {   
                if($value > 0){
                    $row->style = "background: #FFF9A7";
                }
                return "<span style='color:red'>R$ " .number_format($value, 2, ",", ".")."</span>";
            }
            else
            {
                return $value;
            }

        });

        $column_situacao_id_transformed->setTransformer(function($value, $object, $row, $cell = null, $last_row = null)
        {
            //code here
            $oSituacao = SituacaoCadastral::find( $value );

            return $oSituacao->descricao;

        });

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

        $order_cod_erp = new TAction(array($this, 'onReload'));
        $order_cod_erp->setParameter('order', 'cod_erp');
        $column_cod_erp->setAction($order_cod_erp);
        $order_razao = new TAction(array($this, 'onReload'));
        $order_razao->setParameter('order', 'razao');
        $column_razao->setAction($order_razao);
        $order_fantasia = new TAction(array($this, 'onReload'));
        $order_fantasia->setParameter('order', 'fantasia');
        $column_fantasia->setAction($order_fantasia);
        $order_aberto_transformed = new TAction(array($this, 'onReload'));
        $order_aberto_transformed->setParameter('order', 'aberto');
        $column_aberto_transformed->setAction($order_aberto_transformed);
        $order_vencido_transformed = new TAction(array($this, 'onReload'));
        $order_vencido_transformed->setParameter('order', 'vencido');
        $column_vencido_transformed->setAction($order_vencido_transformed);
        $order_quantidade = new TAction(array($this, 'onReload'));
        $order_quantidade->setParameter('order', 'quantidade');
        $column_quantidade->setAction($order_quantidade);

        $this->datagrid->addColumn($column_cod_erp);
        $this->datagrid->addColumn($column_razao);
        $this->datagrid->addColumn($column_fantasia);
        $this->datagrid->addColumn($column_vendedor_id_transformed);
        $this->datagrid->addColumn($column_aberto_transformed);
        $this->datagrid->addColumn($column_vencido_transformed);
        $this->datagrid->addColumn($column_MaiorAtraso);
        $this->datagrid->addColumn($column_quantidade);
        $this->datagrid->addColumn($column_situacao_id_transformed);
        $this->datagrid->addColumn($column_status_transformed);

        $action_onShow = new TDataGridAction(array('PosisaoClienteFormView', 'onShow'));
        $action_onShow->setUseButton(false);
        $action_onShow->setButtonClass('btn btn-default btn-sm');
        $action_onShow->setLabel("Posição Cliente");
        $action_onShow->setImage('fas:address-card #03A9F4');
        $action_onShow->setField(self::$primaryKey);

        $action_onShow->setParameter('key', '{id}');

        $this->datagrid->addAction($action_onShow);

        $action_TitulosClienteNegociacaoSimpleList_onShow = new TDataGridAction(array('TitulosClienteNegociacaoSimpleList', 'onShow'));
        $action_TitulosClienteNegociacaoSimpleList_onShow->setUseButton(false);
        $action_TitulosClienteNegociacaoSimpleList_onShow->setButtonClass('btn btn-default btn-sm');
        $action_TitulosClienteNegociacaoSimpleList_onShow->setLabel("Titulos");
        $action_TitulosClienteNegociacaoSimpleList_onShow->setImage('fas:file-invoice-dollar #000000');
        $action_TitulosClienteNegociacaoSimpleList_onShow->setField(self::$primaryKey);

        $action_TitulosClienteNegociacaoSimpleList_onShow->setParameter('cliente_id', '{id}');

        $this->datagrid->addAction($action_TitulosClienteNegociacaoSimpleList_onShow);

        $this->applyDatagridProperties();
        // create the datagrid model
        $this->datagrid->createModel();

        // creates the page navigation
        $this->pageNavigation = new TPageNavigation;
        $this->pageNavigation->enableCounters();
        $this->pageNavigation->setAction(new TAction(array($this, 'onReload')));
        $this->pageNavigation->setWidth($this->datagrid->getWidth());

        $panel = new TPanelGroup("Negociação");
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

        $btnShowCurtainFilters = new TButton('button_btnShowCurtainFilters');
        $btnShowCurtainFilters->setAction(new TAction(['NegociacaoList', 'onShowCurtainFilters']), "Filtros");
        $btnShowCurtainFilters->addStyleClass('btn-default');
        $btnShowCurtainFilters->setImage('fas:filter #000000');

        $this->datagrid_form->addField($btnShowCurtainFilters);

        $button_limpar_filtros = new TButton('button_button_limpar_filtros');
        $button_limpar_filtros->setAction(new TAction(['NegociacaoList', 'onClearFilters']), "Limpar filtros");
        $button_limpar_filtros->addStyleClass('btn-default');
        $button_limpar_filtros->setImage('fas:eraser #f44336');

        $this->datagrid_form->addField($button_limpar_filtros);

        $button_atualizar = new TButton('button_button_atualizar');
        $button_atualizar->setAction(new TAction(['NegociacaoList', 'onRefresh']), "Atualizar");
        $button_atualizar->addStyleClass('btn-default');
        $button_atualizar->setImage('fas:sync-alt #03a9f4');

        $this->datagrid_form->addField($button_atualizar);

        $dropdown_button_exportar = new TDropDown("Exportar", 'fas:file-export #2d3436');
        $dropdown_button_exportar->setPullSide('right');
        $dropdown_button_exportar->setButtonClass('btn btn-default waves-effect dropdown-toggle');
        $dropdown_button_exportar->addPostAction( "CSV", new TAction(['NegociacaoList', 'onExportCsv'],['static' => 1]), 'datagrid_'.self::$formName, 'fas:file-csv #00b894' );
        $dropdown_button_exportar->addPostAction( "XLS", new TAction(['NegociacaoList', 'onExportXls'],['static' => 1]), 'datagrid_'.self::$formName, 'fas:file-excel #4CAF50' );
        $dropdown_button_exportar->addPostAction( "PDF", new TAction(['NegociacaoList', 'onExportPdf'],['static' => 1]), 'datagrid_'.self::$formName, 'far:file-pdf #e74c3c' );
        $dropdown_button_exportar->addPostAction( "XML", new TAction(['NegociacaoList', 'onExportXml'],['static' => 1]), 'datagrid_'.self::$formName, 'far:file-code #95a5a6' );
        $dropdown_button_fitros_rapidos = new TDropDown("Fitros Rapidos", 'fas:filter #2196F3');
        $dropdown_button_fitros_rapidos->setPullSide('right');
        $dropdown_button_fitros_rapidos->setButtonClass('btn btn-default waves-effect dropdown-toggle');
        $dropdown_button_fitros_rapidos->addPostAction( "Ativo", new TAction(['NegociacaoList', 'clienteAtivo']), 'datagrid_'.self::$formName, 'fas:lock-open #4CAF50' );
        $dropdown_button_fitros_rapidos->addPostAction( "Bloqueado", new TAction(['NegociacaoList', 'clienteBloqueado']), 'datagrid_'.self::$formName, 'fas:lock #E91E63' );
        $dropdown_button_fitros_rapidos->addPostAction( "Vencido", new TAction(['NegociacaoList', 'saldoVencido']), 'datagrid_'.self::$formName, 'fas:thumbs-down #F44336' );
        $dropdown_button_fitros_rapidos->addPostAction( "Aberto", new TAction(['NegociacaoList', 'saldoAberto']), 'datagrid_'.self::$formName, 'fas:thumbs-up #03A9F4' );

        $head_left_actions->add($dropdown_button_fitros_rapidos);
        $head_left_actions->add($btnShowCurtainFilters);
        $head_left_actions->add($button_limpar_filtros);
        $head_left_actions->add($button_atualizar);

        $head_right_actions->add($dropdown_button_exportar);

        $this->datagrid_form->add($this->datagrid);

        $this->btnShowCurtainFilters = $btnShowCurtainFilters;

        // vertical box container
        $container = new TVBox;
        $container->style = 'width: 100%';
        if(empty($param['target_container']))
        {
            $container->add(TBreadCrumb::create(["Cobrança","Negociação"]));
        }

        $container->add($panel);

        parent::add($container);

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
                                $column_name = (strpos((string)$column_name, '{') === FALSE) ? ( '{' . $column_name . '}') : $column_name;
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
                    else if (strpos((string)$column->getWidth(), '%') !== false)
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
                                $column_name = (strpos((string)$column_name, '{') === FALSE) ? ( '{' . $column_name . '}') : $column_name;
                                $value = $object->render($column_name);
                            }

                            $transformer = $column->getTransformer();
                            if ($transformer)
                            {
                                $value = strip_tags((string)call_user_func($transformer, $value, $object, null));
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
                                $column_name = (strpos((string)$column_name, '{') === FALSE) ? ( '{' . $column_name . '}') : $column_name;
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
    public function clienteAtivo($param = null) 
    {
        try 
        {
            $this->filtrarativo = true;
            $this->filtrarbloqueado = false;
            $this->onSearch([]);
            $this->onReload([]);

            //</autoCode>
        }
        catch (Exception $e) 
        {
            new TMessage('error', $e->getMessage());    
        }
    }
    public function clienteBloqueado($param = null) 
    {
        try 
        {
            $this->filtrarativo = false;
            $this->filtrarbloqueado = true;
            $this->onSearch([]);
            $this->onReload([]);

            //</autoCode>
        }
        catch (Exception $e) 
        {
            new TMessage('error', $e->getMessage());    
        }
    }
    public function saldoVencido($param = null) 
    {
        try 
        {

            $this->filtraraberto = false;
            $this->filtrarvencido = true;
            $this->onSearch([]);
            $this->onReload([]);

            //</autoCode>
        }
        catch (Exception $e) 
        {
            new TMessage('error', $e->getMessage());    
        }
    }
    public function saldoAberto($param = null) 
    {
        try 
        {

            $this->filtraraberto = true;
            $this->filtrarvencido = false;
            $this->onSearch([]);
            $this->onReload([]);

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
            $page->setProperty('page-name', 'NegociacaoListSearch');
            $page->setProperty('page_name', 'NegociacaoListSearch');
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

        if (isset($data->razao) AND ( (is_scalar($data->razao) AND $data->razao !== '') OR (is_array($data->razao) AND (!empty($data->razao)) )) )
        {

            $filters[] = new TFilter('razao', 'like', "%{$data->razao}%");// create the filter 
        }

        if (isset($data->fantasia) AND ( (is_scalar($data->fantasia) AND $data->fantasia !== '') OR (is_array($data->fantasia) AND (!empty($data->fantasia)) )) )
        {

            $filters[] = new TFilter('fantasia', 'like', "%{$data->fantasia}%");// create the filter 
        }

        if (isset($data->vendedor_id) AND ( (is_scalar($data->vendedor_id) AND $data->vendedor_id !== '') OR (is_array($data->vendedor_id) AND (!empty($data->vendedor_id)) )) )
        {

            $filters[] = new TFilter('vendedor_id', '=', $data->vendedor_id);// create the filter 
        }

        if (isset($data->status) AND ( (is_scalar($data->status) AND $data->status !== '') OR (is_array($data->status) AND (!empty($data->status)) )) )
        {

            $filters[] = new TFilter('status', '=', $data->status);// create the filter 
        }

        if (isset($data->situacao_id) AND ( (is_scalar($data->situacao_id) AND $data->situacao_id !== '') OR (is_array($data->situacao_id) AND (!empty($data->situacao_id)) )) )
        {

            $filters[] = new TFilter('situacao_id', '=', $data->situacao_id);// create the filter 
        }

        if (isset($data->tipo) AND ( (is_scalar($data->tipo) AND $data->tipo !== '') OR (is_array($data->tipo) AND (!empty($data->tipo)) )) )
        {
            if($data->tipo == 'A' ){
                $this->filtraraberto = true;
                $this->filtrarvencido = false;                
            }elseif($data->tipo == 'V' ){
                $this->filtraraberto = false;
                $this->filtrarvencido = true;                
            }elseif($data->tipo == 'T' ){
                $this->filtraraberto = false;
                $this->filtrarvencido = false;
            }
        }

        if($this->filtrarativo){
            $data->status = "A";
            $filters[] = new TFilter('status', '=', "A");
        }

        if($this->filtrarbloqueado){
            $data->status = "B";
            $filters[] = new TFilter('status', '=', "B");
        }

        if($this->filtraraberto )
        {
            $data->tipo = "A";
            $filters[] = new TFilter('aberto', '>', 0);
        }

        if($this->filtrarvencido )
        {
            $data->tipo = "V";
            $filters[] = new TFilter('vencido', '>', 0);
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

            // creates a repository for ViewClienteSaldoTitulo
            $repository = new TRepository(self::$activeRecord);

            $criteria = clone $this->filter_criteria;

            if (empty($param['order']))
            {
                $param['order'] = 'vencido';    
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

    public static function manageRow($id, $param = [])
    {
        $list = new self($param);

        $openTransaction = TTransaction::getDatabase() != self::$database ? true : false;

        if($openTransaction)
        {
            TTransaction::open(self::$database);    
        }

        $object = new ViewClienteSaldoTitulo($id);

        $row = $list->datagrid->addItem($object);
        $row->id = "row_{$object->id}";

        if($openTransaction)
        {
            TTransaction::close();    
        }

        TDataGrid::replaceRowById(__CLASS__.'_datagrid', $row->id, $row);
    }

}

