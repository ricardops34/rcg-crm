<?php

class ViewQtdVendaMesVendedorList extends TPage
{
    private $form; // form
    private $datagrid; // listing
    private $pageNavigation;
    private $loaded;
    private $filter_criteria;
    private static $database = 'erp_online';
    private static $activeRecord = 'ViewQtdVendaMesVendedor';
    private static $primaryKey = 'vendedor1_id';
    private static $formName = 'form_ViewQtdVendaMesVendedorList';
    private $showMethods = ['onReload', 'onSearch', 'onRefresh', 'onClearFilters', 'onGlobalSearch'];
    private $limit = 20;

    private $contador = 0; 

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
        $this->form->setFormTitle("ClienteAtendidoMes");
        $this->limit = 20;

        $criteria_vendedor1_id = new TCriteria();
        $criteria_estado_id = new TCriteria();

        $vendedor1_id = new TDBCombo('vendedor1_id', 'erp_online', 'Vendedor', 'id', '{nome_status}','cod_erp asc' , $criteria_vendedor1_id );
        $situacao = new TCombo('situacao');
        $mes_base = new TCombo('mes_base');
        $ano = new TEntry('ano');
        $estado_id = new TDBCombo('estado_id', 'erp_online', 'Estado', 'id', '{sigla}','sigla asc' , $criteria_estado_id );


        $mes_base->addItems(TempoService::getMeses());
        $situacao->addItems(["A"=>"Ativo","B"=>"Bloqueado"]);

        $ano->setValue($param['ano'] ?? date('Y'));
        $situacao->setValue($param['situacao'] ?? 'A');
        $mes_base->setValue($param['mes_base'] ?? date('m'));

        $situacao->enableSearch();
        $mes_base->enableSearch();
        $estado_id->enableSearch();
        $vendedor1_id->enableSearch();

        $ano->setSize('100%');
        $situacao->setSize('100%');
        $mes_base->setSize('100%');
        $estado_id->setSize('100%');
        $vendedor1_id->setSize('100%');

        $row1 = $this->form->addFields([new TLabel("Vendedor:", null, '14px', null, '100%'),$vendedor1_id],[new TLabel("Situação Vendedor:", null, '14px', null, '100%'),$situacao]);
        $row1->layout = [' col-sm-6',' col-sm-6'];

        $row2 = $this->form->addFields([new TLabel("Mês Base:", null, '14px', null, '100%'),$mes_base],[new TLabel("Ano Base:", null, '14px', null, '100%'),$ano],[new TLabel("Estado:", null, '14px', null, '100%'),$estado_id],[new TLabel("Cidade:", null, '14px', null, '100%')]);
        $row2->layout = [' col-sm-3',' col-sm-3',' col-sm-3',' col-sm-3'];

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

        $column_id = new TDataGridColumn('id', "#", 'left');
        $column_nome_status_transformed = new TDataGridColumn('nome_status', "Vendedor", 'left' , '30%');
        $column_ano = new TDataGridColumn('ano', "Ano", 'left' , '5%');
        $column_janeiro = new TDataGridColumn('janeiro', "Janeiro", 'right');
        $column_fevereiro = new TDataGridColumn('fevereiro', "Fevereiro", 'right');
        $column_marco = new TDataGridColumn('marco', "Março", 'right');
        $column_abril = new TDataGridColumn('abril', "Abril", 'right');
        $column_maio = new TDataGridColumn('maio', "Maio", 'right');
        $column_junho = new TDataGridColumn('junho', "Junho", 'right');
        $column_julho = new TDataGridColumn('julho', "Julho", 'right');
        $column_agosto = new TDataGridColumn('agosto', "Agosto", 'right');
        $column_setembro = new TDataGridColumn('setembro', "Setembro", 'right');
        $column_outubro = new TDataGridColumn('outubro', "Outubro", 'right');
        $column_novembro = new TDataGridColumn('novembro', "Novembro", 'right');
        $column_dezembro = new TDataGridColumn('dezembro', "Dezembro", 'right');
        $column_carteira = new TDataGridColumn('carteira', "Carteira", 'right');
        $column_avulso = new TDataGridColumn('avulso', "Avulso", 'right');
        $column_bloqueado = new TDataGridColumn('bloqueado', "Bloqueado", 'right');

        $column_carteira->setTotalFunction( function($values) { 
            return array_sum((array) $values); 
        }); 

        $column_avulso->setTotalFunction( function($values) { 
            return array_sum((array) $values); 
        }); 

        $column_bloqueado->setTotalFunction( function($values) { 
            return array_sum((array) $values); 
        }); 

        $column_nome_status_transformed->setTransformer(function($value, $object, $row, $cell = null, $last_row = null)
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

        $order_ano = new TAction(array($this, 'onReload'));
        $order_ano->setParameter('order', 'ano');
        $column_ano->setAction($order_ano);
        $order_janeiro = new TAction(array($this, 'onReload'));
        $order_janeiro->setParameter('order', 'janeiro');
        $column_janeiro->setAction($order_janeiro);
        $order_fevereiro = new TAction(array($this, 'onReload'));
        $order_fevereiro->setParameter('order', 'fevereiro');
        $column_fevereiro->setAction($order_fevereiro);
        $order_marco = new TAction(array($this, 'onReload'));
        $order_marco->setParameter('order', 'marco');
        $column_marco->setAction($order_marco);
        $order_abril = new TAction(array($this, 'onReload'));
        $order_abril->setParameter('order', 'abril');
        $column_abril->setAction($order_abril);
        $order_maio = new TAction(array($this, 'onReload'));
        $order_maio->setParameter('order', 'maio');
        $column_maio->setAction($order_maio);
        $order_junho = new TAction(array($this, 'onReload'));
        $order_junho->setParameter('order', 'junho');
        $column_junho->setAction($order_junho);
        $order_julho = new TAction(array($this, 'onReload'));
        $order_julho->setParameter('order', 'julho');
        $column_julho->setAction($order_julho);
        $order_agosto = new TAction(array($this, 'onReload'));
        $order_agosto->setParameter('order', 'agosto');
        $column_agosto->setAction($order_agosto);
        $order_setembro = new TAction(array($this, 'onReload'));
        $order_setembro->setParameter('order', 'setembro');
        $column_setembro->setAction($order_setembro);
        $order_outubro = new TAction(array($this, 'onReload'));
        $order_outubro->setParameter('order', 'outubro');
        $column_outubro->setAction($order_outubro);
        $order_novembro = new TAction(array($this, 'onReload'));
        $order_novembro->setParameter('order', 'novembro');
        $column_novembro->setAction($order_novembro);
        $order_dezembro = new TAction(array($this, 'onReload'));
        $order_dezembro->setParameter('order', 'dezembro');
        $column_dezembro->setAction($order_dezembro);

        $mes_base = $param['mes_base'] ?? date('m');//TSession::getValue('mes_ref');//date('m');
        TSession::setValue('mes_ref',$mes_base);//date('m');
        $cor_base = 'background: #FFF9A7 ;border-color: #96D4D4; text-align:right; user-select:none; cursor:pointer;';
        /*
        var_dump($mes_base);

        $column_JANEIRO->style      = $cor_base;
        $column_FEVEREIRO->style    = $cor_base;
        $column_MARCO->style        = $cor_base;
        $column_ABRIL->style        = $cor_base;
        $column_MAIO->style         = $cor_base;
        $column_JUNHO->style        = $cor_base;
        $column_JULHO->style        = $cor_base;
        $column_AGOSTO->style       = $cor_base;
        $column_SETEMBRO->style     = $cor_base;
        $column_OUTUBRO->style      = $cor_base;
        $column_NOVEMBRO->style     = $cor_base;
        $column_DEZEMBRO->style     = $cor_base;
        */
        if($mes_base == '01'){
            $column_janeiro->style = $cor_base;
        }elseif($mes_base == '02'){
            $column_fevereiro->style = $cor_base;
        }elseif($mes_base == '03'){
            $column_marco->style = $cor_base;
        }elseif($mes_base == '04'){
            $column_abril->style = $cor_base;
        }elseif($mes_base == '05'){
            $column_maio->style = $cor_base;
        }elseif($mes_base == '06'){
            $column_junho->style = $cor_base;
        }elseif($mes_base == '07'){
            $column_julho->style = $cor_base;
        }elseif($mes_base == '08'){
            $column_agosto->style = $cor_base;
        }elseif($mes_base == '09'){
            $column_setembro->style = $cor_base;
        }elseif($mes_base == '10'){
            $column_outubro->style = $cor_base;
        }elseif($mes_base == '11'){
            $column_novembro->style = $cor_base;
        }elseif($mes_base == '12'){
            $column_dezembro->style = $cor_base;
        }

        //$stock->setTransformer(array($this, 'formatSalary'));

        $column_id->setTransformer(function($value, $object, $row, $cell = null, $last_row = null)
        {

            //ar_dump($object);
            $this->contador++;

            return str_pad($this->contador , 4 , '0' , STR_PAD_LEFT); 
        });

        $this->datagrid->addColumn($column_id);
        $this->datagrid->addColumn($column_nome_status_transformed);
        $this->datagrid->addColumn($column_ano);
        $this->datagrid->addColumn($column_janeiro);
        $this->datagrid->addColumn($column_fevereiro);
        $this->datagrid->addColumn($column_marco);
        $this->datagrid->addColumn($column_abril);
        $this->datagrid->addColumn($column_maio);
        $this->datagrid->addColumn($column_junho);
        $this->datagrid->addColumn($column_julho);
        $this->datagrid->addColumn($column_agosto);
        $this->datagrid->addColumn($column_setembro);
        $this->datagrid->addColumn($column_outubro);
        $this->datagrid->addColumn($column_novembro);
        $this->datagrid->addColumn($column_dezembro);
        $this->datagrid->addColumn($column_carteira);
        $this->datagrid->addColumn($column_avulso);
        $this->datagrid->addColumn($column_bloqueado);

        // create the datagrid model
        $this->datagrid->createModel();

        // creates the page navigation
        $this->pageNavigation = new TPageNavigation;
        $this->pageNavigation->enableCounters();
        $this->pageNavigation->setAction(new TAction(array($this, 'onReload')));
        $this->pageNavigation->setWidth($this->datagrid->getWidth());

        $panel = new TPanelGroup("ClienteAtendidoMes");
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
        $btnShowCurtainFilters->setAction(new TAction(['ViewQtdVendaMesVendedorList', 'onShowCurtainFilters']), "Filtros");
        $btnShowCurtainFilters->addStyleClass('btn-default');
        $btnShowCurtainFilters->setImage('fas:filter #000000');

        $this->datagrid_form->addField($btnShowCurtainFilters);

        $button_atualizar = new TButton('button_button_atualizar');
        $button_atualizar->setAction(new TAction(['ViewQtdVendaMesVendedorList', 'onRefresh']), "Atualizar");
        $button_atualizar->addStyleClass('btn-default');
        $button_atualizar->setImage('fas:sync-alt #03a9f4');

        $this->datagrid_form->addField($button_atualizar);

        $button_limpar_filtros = new TButton('button_button_limpar_filtros');
        $button_limpar_filtros->setAction(new TAction(['ViewQtdVendaMesVendedorList', 'onClearFilters']), "Limpar filtros");
        $button_limpar_filtros->addStyleClass('btn-default');
        $button_limpar_filtros->setImage('fas:eraser #f44336');

        $this->datagrid_form->addField($button_limpar_filtros);

        $dropdown_button_exportar = new TDropDown("Exportar", 'fas:file-export #2d3436');
        $dropdown_button_exportar->setPullSide('right');
        $dropdown_button_exportar->setButtonClass('btn btn-default waves-effect dropdown-toggle');
        $dropdown_button_exportar->addPostAction( "CSV", new TAction(['ViewQtdVendaMesVendedorList', 'onExportCsv'],['static' => 1]), 'datagrid_'.self::$formName, 'fas:file-csv #00b894' );
        $dropdown_button_exportar->addPostAction( "XLS", new TAction(['ViewQtdVendaMesVendedorList', 'onExportXls'],['static' => 1]), 'datagrid_'.self::$formName, 'fas:file-excel #4CAF50' );
        $dropdown_button_exportar->addPostAction( "PDF", new TAction(['ViewQtdVendaMesVendedorList', 'onExportPdf'],['static' => 1]), 'datagrid_'.self::$formName, 'far:file-pdf #e74c3c' );
        $dropdown_button_exportar->addPostAction( "XML", new TAction(['ViewQtdVendaMesVendedorList', 'onExportXml'],['static' => 1]), 'datagrid_'.self::$formName, 'far:file-code #95a5a6' );

        $head_left_actions->add($btnShowCurtainFilters);
        $head_left_actions->add($button_atualizar);
        $head_left_actions->add($button_limpar_filtros);

        $head_right_actions->add($dropdown_button_exportar);

        $this->datagrid_form->add($this->datagrid);

        $this->btnShowCurtainFilters = $btnShowCurtainFilters;

        // vertical box container
        $container = new TVBox;
        $container->style = 'width: 100%';
        if(empty($param['target_container']))
        {
            $container->add(TBreadCrumb::create(["Gerencia","Cliente Atendido Mes"]));
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
            $page->setProperty('page-name', 'ViewQtdVendaMesVendedorListSearch');
            $page->setProperty('page_name', 'ViewQtdVendaMesVendedorListSearch');
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

        if (isset($data->vendedor1_id) AND ( (is_scalar($data->vendedor1_id) AND $data->vendedor1_id !== '') OR (is_array($data->vendedor1_id) AND (!empty($data->vendedor1_id)) )) )
        {

            $filters[] = new TFilter('vendedor1_id', '=', $data->vendedor1_id);// create the filter 
        }

        if (isset($data->ano) AND ( (is_scalar($data->ano) AND $data->ano !== '') OR (is_array($data->ano) AND (!empty($data->ano)) )) )
        {

            $filters[] = new TFilter('ano', 'like', "%{$data->ano}%");// create the filter 
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

            // creates a repository for ViewQtdVendaMesVendedor
            $repository = new TRepository(self::$activeRecord);

            $criteria = clone $this->filter_criteria;

            if (empty($param['order']))
            {
                $param['order'] = 'vendedor1_id';    
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
                    $row->id = "row_{$object->vendedor1_id}";

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

        $object = new ViewQtdVendaMesVendedor($id);

        $row = $list->datagrid->addItem($object);
        $row->id = "row_{$object->vendedor1_id}";

        if($openTransaction)
        {
            TTransaction::close();    
        }

        TDataGrid::replaceRowById(__CLASS__.'_datagrid', $row->id, $row);
    }

}

