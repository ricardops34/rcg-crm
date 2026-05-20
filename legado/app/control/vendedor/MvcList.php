<?php

class MvcList extends TPage
{
    private $form; // form
    private $datagrid; // listing
    private $pageNavigation;
    private $loaded;
    private $filter_criteria;
    private static $database = 'erp_online';
    private static $activeRecord = 'Mvc';
    private static $primaryKey = 'id';
    private static $formName = 'form_MvcList';
    private $showMethods = ['onReload', 'onSearch', 'onRefresh', 'onClearFilters', 'onGlobalSearch'];
    private $limit = 20;

    private $limitAnt = 20;
    private $diasde = 0;
    private $diasate = 99999;
    private $filtradias = false;
    private $filtrarativo = false;
    private $filtrarbloqueado = false;
    private $supervisor  = 'N'; //TSession::getValue("supervisor");
    private $vendedor_id = 0 ;//TSession::getValue("vendedor_id");

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
        $this->form->setFormTitle("Manutenção Carteira de Cliente");
        $this->limit = 20;

        $criteria_estado_id = new TCriteria();

        $this->supervisor  = TSession::getValue("supervisor");
        $this->vendedor_id = TSession::getValue("vendedor_id");

        $razao = new TEntry('razao');
        $fantasia = new TEntry('fantasia');
        $estado_id = new TDBCombo('estado_id', 'erp_online', 'Estado', 'id', '{sigla} - {descricao}','sigla asc' , $criteria_estado_id );
        $municipio_id = new TCombo('municipio_id');
        $dias = new TNumeric('dias', '0', ',', '.' );
        $situacao = new TCombo('situacao');

        $estado_id->setChangeAction(new TAction([$this,'onChangeestado_id']));

        $estado_id->setValue(Estado::MS);
        $situacao->addItems(["A"=>"Ativo","B"=>"Bloqueado"]);
        $situacao->enableSearch();
        $estado_id->enableSearch();
        $municipio_id->enableSearch();

        $dias->setSize('100%');
        $razao->setSize('100%');
        $fantasia->setSize('100%');
        $situacao->setSize('100%');
        $estado_id->setSize('100%');
        $municipio_id->setSize('100%');

        //$dias = new TNumeric('dias', '0', ',', '.' );
        //$dias->setSize('100%');
        if($this->supervisor == 'S'){
            $criteria_vendedor_id = new TCriteria();
            $criteria_vendedor_id->add(new TFilter('status', '=', "A")); 
            //$vendedor_id = new TDBCombo('vendedor_id', 'erp_online', 'Vendedor', 'id', '{nome_status}','cod_erp asc' , $criteria_vendedor_id ); 
            $vendedor_id = new TDBCombo('vendedor_id', 'erp_online', 'Vendedor', 'id', '{cod_erp} - {nome_reduzido}','nome_reduzido asc' , $criteria_vendedor_id );
            $vendedor_id->setSize('100%');

        }

        $row1 = $this->form->addFields([new TLabel("Razao:", null, '14px', null, '100%'),$razao],[new TLabel("Fantasia:", null, '14px', null, '100%'),$fantasia]);
        $row1->layout = ['col-sm-6',' col-sm-6'];

        $row2 = $this->form->addFields([new TLabel("Estado:", null, '14px', null, '100%'),$estado_id],[new TLabel("Municipio:", null, '14px', null, '100%'),$municipio_id]);
        $row2->layout = [' col-sm-6',' col-sm-6'];

        $row3 = $this->form->addFields([new TLabel("Dias:", null, '14px', null, '100%'),$dias],[new TLabel("Situação:", null, '14px', null, '100%'),$situacao]);
        $row3->layout = ['col-sm-6','col-sm-6'];

        if($this->supervisor == 'S'){
            $row4 = $this->form->addFields([new TLabel("Vendedor:", null, '14px', null, '100%'),$vendedor_id],[]);
            $row4->layout = [' col-sm-6','col-sm-6'];
        }

        // keep the form filled during navigation with session data
        $this->form->setData( TSession::getValue(__CLASS__.'_filter_data') );
        $this->fireEvents( TSession::getValue(__CLASS__.'_filter_data') );

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

        $this->datagrid->disableDefaultClick();
        $this->datagrid->style = 'width: 100%';
        $this->datagrid->setHeight(250);

        $column_id_transformed = new TDataGridColumn('id', "", 'center' , '5%');
        $column_situacao_transformed = new TDataGridColumn('situacao', "Situacao", 'left' , '10%');
        $column_codigo_transformed = new TDataGridColumn('codigo', "Código", 'left' , '8%');
        $column_ultima_compra_transformed = new TDataGridColumn('ultima_compra', "Última Compra", 'left' , '8%');
        $column_razao = new TDataGridColumn('razao', "Razão Social", 'left');
        $column_municipio_descricao = new TDataGridColumn('municipio_descricao', "Cidade", 'left' , '10%');
        $column_dif_media_transformed = new TDataGridColumn('dif_media', "Dif. mês e média", 'right' , '7%');
        $column_venda_mes_transformed = new TDataGridColumn('venda_mes', "Venda Últimos 30 dias", 'right' , '7%');
        $column_venda_media_tres_transformed = new TDataGridColumn('venda_media_tres', "Venda Média Últimos 90 dias", 'right' , '7%');
        $column_dias_transformed = new TDataGridColumn('dias', "Dias", 'right' , '5%');
        $column_id = new TDataGridColumn('id', "Comodato", 'center' , '7%');

        $column_situacao_transformed->enableAutoHide('600');
        $column_codigo_transformed->enableAutoHide('600');
        $column_ultima_compra_transformed->enableAutoHide('600');
        $column_municipio_descricao->enableAutoHide('600');
        $column_dias_transformed->enableAutoHide('600');
        $column_id->enableAutoHide('600');

        $column_id_transformed->setTransformer(function($value, $object, $row, $cell = null, $last_row = null)
        {
            $icone = "";
            $nTitVenc = 0;
            $nTitAVen = 0;

            TTransaction::open('erp_online');

            $oCliente = Cliente::find( $value );

            if($oCliente){
                $criteria = new TCriteria;
                $criteria->add(new TFilter('cliente_id', '=', $oCliente->id));
                $criteria->add(new TFilter('saldo', '>', 0 ));
                $criteria->add(new TFilter('reg_ativo', '=', 'S' ));
                //$criteria->add(new TFilter('vencimento','<', date('Y-m-d') ));
                $repository = new TRepository('TituloReceber'); 
                $oTitulos = $repository->load($criteria); 

                foreach ($oTitulos as $oTitulo)
                {
                    if($oTitulo->vencimento < date('Y-m-d')){
                        $nTitVenc += $oTitulo->saldo;
                    }else{
                        $nTitAVen += $oTitulo->saldo;
                    }
                }

                if($nTitVenc > 0){
                    $icone = new TElement('i');
                    $icone->style="; color: red;"; 
                    $icone->class="fas fa-dollar-sign";
                }else{
                    if($nTitAVen > 0){
                        $icone = new TElement('i');
                        $icone->style="; color: blue;"; //; 
                        $icone->class="fas fa-dollar-sign";
                    }
                }
            }

            TTransaction::close();

            return $icone;

        });

        $column_situacao_transformed->setTransformer(function($value, $object, $row, $cell = null, $last_row = null)
        {

            $class = ($value=='B') ? 'warning'      : 'success';
            $label = ($value=='B') ? 'Bloqueado'    : 'Ativo';
            $div = new TElement('span');
            $div->class="label label-{$class}";
            $div->style="width:120px; text-shadow:none; font-size:12px; font-weight:lighter";
            $div->add($label);
            return $div;

        });

        $column_codigo_transformed->setTransformer(function($value, $object, $row, $cell = null, $last_row = null)
        {

            $return = $value;
            TTransaction::open('erp_online');

            $oCliente= Cliente::find( $value );

            if($oCliente){
                $return = substr($oCliente->cod_erp, 0, 6).'-'.substr($oCliente->cod_erp, 6, 2);
            }

            TTransaction::close();
            return $return;

        });

        $column_ultima_compra_transformed->setTransformer(function($value, $object, $row, $cell = null, $last_row = null)
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

        $column_dif_media_transformed->setTransformer(function($value, $object, $row, $cell = null, $last_row = null)
        {
            if(!$value)
            {
                $value = 0;
            }

            if(is_numeric($value))
            {

                if($value > 0){
                    return "<span style='color:blue'>R$ ".number_format($value, 2, ",", ".")."</span>";
                }elseif($value == 0){
                    return "R$ ".number_format($value, 2, ",", ".");
                }else{
                    $row->style = "background: #FFF9A7";
                    return "<span style='color:red'>R$ ".number_format($value, 2, ",", ".")."</span>";
                }
            }
            else
            {
                return "R$ ".number_format(0, 2, ",", ".");
            }

        });

        $column_venda_mes_transformed->setTransformer(function($value, $object, $row, $cell = null, $last_row = null)
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

        $column_venda_media_tres_transformed->setTransformer(function($value, $object, $row, $cell = null, $last_row = null)
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

        $column_dias_transformed->setTransformer(function($value, $object, $row, $cell = null, $last_row = null)
        {
            //code here

            $number = $value;
            $cor = 'Red';

            if($number > 180){
                $cor = 'Red';
            }elseif($number >= 90){
                $cor = 'OrangeRed';
            }elseif($number >= 60){
                $cor = 'orange';
            }elseif($number >= 30){
                $cor = 'Green';
            }else{//if($number >= 0){
                $cor = 'Blue';
            }

            return "<span style='color:$cor'>$number</span>";

        });        

        $order_situacao_transformed = new TAction(array($this, 'onReload'));
        $order_situacao_transformed->setParameter('order', 'situacao');
        $column_situacao_transformed->setAction($order_situacao_transformed);
        $order_codigo_transformed = new TAction(array($this, 'onReload'));
        $order_codigo_transformed->setParameter('order', 'codigo');
        $column_codigo_transformed->setAction($order_codigo_transformed);
        $order_ultima_compra_transformed = new TAction(array($this, 'onReload'));
        $order_ultima_compra_transformed->setParameter('order', 'ultima_compra');
        $column_ultima_compra_transformed->setAction($order_ultima_compra_transformed);
        $order_razao = new TAction(array($this, 'onReload'));
        $order_razao->setParameter('order', 'razao');
        $column_razao->setAction($order_razao);
        $order_municipio_descricao = new TAction(array($this, 'onReload'));
        $order_municipio_descricao->setParameter('order', 'municipio_descricao');
        $column_municipio_descricao->setAction($order_municipio_descricao);
        $order_dias_transformed = new TAction(array($this, 'onReload'));
        $order_dias_transformed->setParameter('order', 'dias');
        $column_dias_transformed->setAction($order_dias_transformed);

        $column_id->setTransformer(function($value, $object, $row, $cell = null, $last_row = null)
        {
            //code here
            $cRetorno = 'Não';
            $nComodato = 0 ;
            $oNotas = NotaSaida::where('cliente_id',  '=', $object->id)
                        ->where('reg_ativo ', '=', 'S')
                        ->where('tipo ', '=', 'N')
                        ->where('vlr_comodato ', '>', 'vlr_devolucao')
                        ->where('comodato ', '=', 'S')
                        ->orderBy('id')
                        ->load();        

            if ($oNotas){
                foreach($oNotas  as $oNota )
                {
                     $nComodato += $oNota->vlr_comodato - $oNota->vlr_devolucao;
                }
            }

            if($nComodato < 0){
                $nComodato = 0;
            }

            if($nComodato > 0 ){

                $action_Comodato = new TAction( ['ComodatoNotafiscalSimpleList', 'onShow' ] );
                $action_Comodato->setParameter('cliente_id', $object->id);
                //$action_Comodato->setParameter('vendedor_id', $object->vendedor_id);
                //$action_Comodato->setParameter('ano', $object->ano);
                //$action_Comodato->setParameter('mes', '12');
                $link_Comodato = new TActionLink("Sim", $action_Comodato,'blue');//, 'blue', 12, 'biu');

                return $link_Comodato;//"R$ " . number_format($value, 2, ",", ".");
                //$cRetorno = 'Sim';
            }

            return $cRetorno;

        });

        if($this->supervisor == 'S'){
            $column_vendedor_reduzido = new TDataGridColumn('vendedor_reduzido', "Vendedor", 'left');
            $column_vendedor_reduzido->enableAutoHide('900');

            $order_vendedor_reduzido = new TAction(array($this, 'onReload'));
            $order_vendedor_reduzido->setParameter('order', 'vendedor_reduzido');
            $column_vendedor_reduzido->setAction($order_vendedor_reduzido);
        }

        $this->datagrid->addColumn($column_id_transformed);
        $this->datagrid->addColumn($column_situacao_transformed);
        $this->datagrid->addColumn($column_codigo_transformed);
        $this->datagrid->addColumn($column_ultima_compra_transformed);
        $this->datagrid->addColumn($column_razao);
        $this->datagrid->addColumn($column_municipio_descricao);
        $this->datagrid->addColumn($column_dif_media_transformed);
        $this->datagrid->addColumn($column_venda_mes_transformed);
        $this->datagrid->addColumn($column_venda_media_tres_transformed);
        $this->datagrid->addColumn($column_dias_transformed);
        $this->datagrid->addColumn($column_id);

        if($this->supervisor == 'S'){
            $this->datagrid->addColumn($column_vendedor_reduzido);
        }

        $action_onShow = new TDataGridAction(array('PosisaoClienteFormView', 'onShow'));
        $action_onShow->setUseButton(false);
        $action_onShow->setButtonClass('btn btn-default btn-sm');
        $action_onShow->setLabel("Cliente");
        $action_onShow->setImage('fas:address-card #000000');
        $action_onShow->setField(self::$primaryKey);

        $action_onShow->setParameter('key', '{id}');

        $this->datagrid->addAction($action_onShow);

        $this->applyDatagridProperties();
        // create the datagrid model
        $this->datagrid->createModel();

        // creates the page navigation
        $this->pageNavigation = new TPageNavigation;
        $this->pageNavigation->enableCounters();
        $this->pageNavigation->setAction(new TAction(array($this, 'onReload')));
        $this->pageNavigation->setWidth($this->datagrid->getWidth());
        $this->pageNavigation->keepLastPagination(__CLASS__);

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

        $btnShowCurtainFilters = new TButton('button_btnShowCurtainFilters');
        $btnShowCurtainFilters->setAction(new TAction(['MvcList', 'onShowCurtainFilters']), "Filtros");
        $btnShowCurtainFilters->addStyleClass('btn-default');
        $btnShowCurtainFilters->setImage('fas:filter #000000');

        $this->datagrid_form->addField($btnShowCurtainFilters);

        $button_limpar = new TButton('button_button_limpar');
        $button_limpar->setAction(new TAction(['MvcList', 'onClearFilters']), "Limpar");
        $button_limpar->addStyleClass('btn-default');
        $button_limpar->setImage('fas:eraser #f44336');

        $this->datagrid_form->addField($button_limpar);

        $button_atualizar = new TButton('button_button_atualizar');
        $button_atualizar->setAction(new TAction(['MvcList', 'onRefresh']), "Atualizar");
        $button_atualizar->addStyleClass('btn-default');
        $button_atualizar->setImage('fas:sync-alt #03a9f4');

        $this->datagrid_form->addField($button_atualizar);

        $button_xls = new TButton('button_button_xls');
        $button_xls->setAction(new TAction(['MvcList', 'onExportXls']), "XLS");
        $button_xls->addStyleClass('btn-default');
        $button_xls->setImage('fas:file-excel #4CAF50');

        $this->datagrid_form->addField($button_xls);

        $button_pdf = new TButton('button_button_pdf');
        $button_pdf->setAction(new TAction(['MvcList', 'onExportPdf']), "PDF");
        $button_pdf->addStyleClass('btn-default');
        $button_pdf->setImage('far:file-pdf #e74c3c');

        $this->datagrid_form->addField($button_pdf);

        $dropdown_button_filtros_rapidos = new TDropDown("Filtros Rapidos", 'fas:filter #000000');
        $dropdown_button_filtros_rapidos->setPullSide('right');
        $dropdown_button_filtros_rapidos->setButtonClass('btn btn-default waves-effect dropdown-toggle');
        $dropdown_button_filtros_rapidos->addPostAction( "120 Dias", new TAction(['MvcList', 'maiscemdias']), 'datagrid_'.self::$formName, 'fas:plus #000000' );
        $dropdown_button_filtros_rapidos->addPostAction( " 90 Dias", new TAction(['MvcList', 'noventadias']), 'datagrid_'.self::$formName, 'fas:plus #E91E63' );
        $dropdown_button_filtros_rapidos->addPostAction( "60 Dias", new TAction(['MvcList', 'sessentadias']), 'datagrid_'.self::$formName, 'fas:plus #FF5722' );
        $dropdown_button_filtros_rapidos->addPostAction( "30 Dias", new TAction(['MvcList', 'trintadias']), 'datagrid_'.self::$formName, 'fas:plus #FFC107' );
        $dropdown_button_filtros_rapidos->addPostAction( "15 Dias", new TAction(['MvcList', 'quinzedias']), 'datagrid_'.self::$formName, 'fas:plus #2196F3' );
        $dropdown_button_filtros_rapidos->addPostAction( "Bloqueados", new TAction(['MvcList', 'bloqueado']), 'datagrid_'.self::$formName, 'fas:lock #FF5722' );
        $dropdown_button_filtros_rapidos->addPostAction( "Ativo", new TAction(['MvcList', 'ativo']), 'datagrid_'.self::$formName, 'fas:lock-open #009688' );

        $head_left_actions->add($btnShowCurtainFilters);
        $head_left_actions->add($button_limpar);
        $head_left_actions->add($button_atualizar);
        $head_left_actions->add($button_xls);
        $head_left_actions->add($button_pdf);

        $head_right_actions->add($dropdown_button_filtros_rapidos);

        $this->datagrid_form->add($this->datagrid);

        $this->btnShowCurtainFilters = $btnShowCurtainFilters;

        /*
        if($this->supervisor == 'S'){

            $button_xls = new TButton('button_button_xls');
            $button_xls->setAction(new TAction(['MvcList', 'onExcel']), "XLS");
            $button_xls->addStyleClass('btn-default');
            $button_xls->setImage('fas:file-excel #4CAF50');

            $this->datagrid_form->addField($button_xls);

            $head_left_actions->add($button_xls);
        }
        */

        // vertical box container
        $container = new TVBox;
        $container->style = 'width: 100%';
        if(empty($param['target_container']))
        {
            $container->add(TBreadCrumb::create(["Vendedor","MCV"]));
        }

        $container->add($panel);

        parent::add($container);

    }

    public static function onChangeestado_id($param)
    {
        try
        {

            if (isset($param['estado_id']) && $param['estado_id'])
            { 
                $criteria = TCriteria::create(['estado_id' => $param['estado_id']]);
                TDBCombo::reloadFromModel(self::$formName, 'municipio_id', 'erp_online', 'Municipio', 'id', '{descricao}', 'cod_erp asc', $criteria, TRUE); 
            } 
            else 
            { 
                TCombo::clearField(self::$formName, 'municipio_id'); 
            }  

        }
        catch (Exception $e)
        {
            new TMessage('error', $e->getMessage());
        }
    } 

    public function maiscemdias($param = null) 
    {
        try 
        {
            $this->filtrarativo = false;
            $this->filtrarbloqueado = false;
            $this->diasde = 121;
            $this->diasate = 99999;
            $this->filtradias = true;
            $this->onSearch([]);
            $this->onReload([]);

            //</autoCode>
        }
        catch (Exception $e) 
        {
            new TMessage('error', $e->getMessage());    
        }
    }
    public function noventadias($param = null) 
    {
        try 
        {
            $this->filtrarativo = false;
            $this->filtrarbloqueado = false;
            $this->diasde = 91;
            $this->diasate = 120;
            $this->filtradias = true;
            $this->onSearch([]);
            $this->onReload([]);

            //</autoCode>
        }
        catch (Exception $e) 
        {
            new TMessage('error', $e->getMessage());    
        }
    }
    public function sessentadias($param = null) 
    {
        try 
        {
            $this->filtrarativo = false;
            $this->filtrarbloqueado = false;
            $this->diasde = 61;
            $this->diasate = 90;
            $this->filtradias = true;
            $this->onSearch([]);
            $this->onReload([]);

            //</autoCode>
        }
        catch (Exception $e) 
        {
            new TMessage('error', $e->getMessage());    
        }
    }
    public function trintadias($param = null) 
    {
        try 
        {
            $this->filtrarativo = false;
            $this->filtrarbloqueado = false;
            $this->diasde = 31;
            $this->diasate = 60;
            $this->filtradias = true;
            $this->onSearch([]);
            $this->onReload([]);

            //</autoCode>
        }
        catch (Exception $e) 
        {
            new TMessage('error', $e->getMessage());    
        }
    }
    public function quinzedias($param = null) 
    {
        try 
        {
            $this->filtrarativo = false;
            $this->filtrarbloqueado = false;
            $this->diasde = 16;
            $this->diasate = 30;
            $this->filtradias = true;
            $this->onSearch([]);
            $this->onReload([]);
            //</autoCode>
        }
        catch (Exception $e) 
        {
            new TMessage('error', $e->getMessage());    
        }
    }
    public function bloqueado($param = null) 
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
    public function ativo($param = null) 
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
            $page->setProperty('page-name', 'MvcListSearch');
            $page->setProperty('page_name', 'MvcListSearch');
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

                $this->limitAnt = $this->limit;
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
                            /*
                            $transformer = $column->getTransformer();
                            if ($transformer)
                            {
                                $value = strip_tags((string)call_user_func($transformer, $value, $object, null));
                            }

                            $table->addCell($value, 'center', 'data');
                            */
                        }
                    }
                }
                $table->save($output);
                TTransaction::close();
                $this->limit = $this->limitAnt;
                TPage::openFile($output);
            }
            else
            {
                throw new Exception(_t('Permission denied') . ': ' . $output);
            }
        }
        catch (Exception $e) // in case of exception
        {
            new TMessage('error','aqui->'. $e->getMessage()); // shows the exception error message
        }
    }
    public function onExportPdf($param = null) 
    {
        try
        {
            $output = 'app/output/'.uniqid().'.pdf';

            if ( (!file_exists($output) && is_writable(dirname($output))) OR is_writable($output))
            {
                $this->limitAnt = $this->limit;
                $this->limit = 0;
                $this->datagrid->prepareForPrinting();
                $this->onReload();

                $html = clone $this->datagrid;
                $contents = file_get_contents('app/resources/styles-print.html') . $html->getContents();

                $options = new Dompdf\Options();
                $options->setIsRemoteEnabled(true);
                $options->setChroot(getcwd());
                //$options->set('dpi', '128');

                $dompdf = new \Dompdf\Dompdf($options);
                $dompdf->loadHtml($contents);
                $dompdf->setPaper('A4', 'portrait');
                $dompdf->render();

                file_put_contents($output, $dompdf->output());

                $window = TWindow::create('PDF', 0.8, 0.8);
                $object = new TElement('iframe');
                $object->src  = $output;
                $object->type  = 'application/pdf';
                $object->style = "width: 100%; height:calc(100% - 10px)";

                $this->limit = $this->limitAnt;
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

    public function fireEvents( $object )
    {
        $obj = new stdClass;
        if(is_object($object) && get_class($object) == 'stdClass')
        {
            if(isset($object->estado_id))
            {
                $value = $object->estado_id;

                $obj->estado_id = $value;
            }
            if(isset($object->municipio_id))
            {
                $value = $object->municipio_id;

                $obj->municipio_id = $value;
            }
        }
        elseif(is_object($object))
        {
            if(isset($object->estado_id))
            {
                $value = $object->estado_id;

                $obj->estado_id = $value;
            }
            if(isset($object->municipio_id))
            {
                $value = $object->municipio_id;

                $obj->municipio_id = $value;
            }
        }
        TForm::sendData(self::$formName, $obj);
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

        if (isset($data->razao) AND ( (is_scalar($data->razao) AND $data->razao !== '') OR (is_array($data->razao) AND (!empty($data->razao)) )) )
        {

            $filters[] = new TFilter('razao', 'like', "%{$data->razao}%");// create the filter 
        }

        if (isset($data->fantasia) AND ( (is_scalar($data->fantasia) AND $data->fantasia !== '') OR (is_array($data->fantasia) AND (!empty($data->fantasia)) )) )
        {

            $filters[] = new TFilter('fantasia', 'like', "%{$data->fantasia}%");// create the filter 
        }

        if (isset($data->estado_id) AND ( (is_scalar($data->estado_id) AND $data->estado_id !== '') OR (is_array($data->estado_id) AND (!empty($data->estado_id)) )) )
        {

            $filters[] = new TFilter('estado_id', '=', $data->estado_id);// create the filter 
        }

        if (isset($data->municipio_id) AND ( (is_scalar($data->municipio_id) AND $data->municipio_id !== '') OR (is_array($data->municipio_id) AND (!empty($data->municipio_id)) )) )
        {

            $filters[] = new TFilter('municipio_id', '=', $data->municipio_id);// create the filter 
        }

        if (isset($data->dias) AND ( (is_scalar($data->dias) AND $data->dias !== '') OR (is_array($data->dias) AND (!empty($data->dias)) )) )
        {

            $filters[] = new TFilter('dias', '>=', $data->dias);// create the filter 
        }

        if (isset($data->situacao) AND ( (is_scalar($data->situacao) AND $data->situacao !== '') OR (is_array($data->situacao) AND (!empty($data->situacao)) )) )
        {

            $filters[] = new TFilter('situacao', '=', $data->situacao);// create the filter 
        }

        $this->fireEvents($data);

        if($this->supervisor == 'S'){

            if (isset($data->vendedor_id) AND ( (is_scalar($data->vendedor_id) AND $data->vendedor_id !== '') OR (is_array($data->vendedor_id) AND (!empty($data->vendedor_id)) )) )
            {

                $filters[] = new TFilter('vendedor_id', '=', $data->vendedor_id);// create the filter 
            } 
        }else{
             $filters[] = new TFilter('vendedor_id', '=', $this->vendedor_id);
        }

        if($this->filtrarativo )
        {
            $data->situacao = "A";
            $filters[] = new TFilter('situacao', '=', "A");
        }

        if($this->filtrarbloqueado )
        {
            $data->situacao = "B";
            $filters[] = new TFilter('situacao', '=', "B"); 
        }

        if($this->filtradias )
        {
            //$data->filtros_rapidos = "bloqueado";
            $data->dias = $this->diasde;
            $filters[] = new TFilter('dias', '>=', $this->diasde); 
            $filters[] = new TFilter('dias', '<=', $this->diasate); 
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

            // creates a repository for Mvc
            $repository = new TRepository(self::$activeRecord);

            $criteria = clone $this->filter_criteria;

            if (empty($param['order']))
            {
                $param['order'] = 'dias';    
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

            if($this->supervisor == 'S'){

            }else{
                $vendedor_id = $this->vendedor_id;
                if($vendedor_id > 0){
                    $criteria->add(new TFilter('vendedor_id', '=', $vendedor_id));
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

        $this->supervisor  = TSession::getValue("supervisor");
        $this->vendedor_id = TSession::getValue("vendedor_id");

        $object = new stdClass();
        $object->estado_id = Estado::MS;
        TForm::sendData(self::$formName, $object);

        $criteria = TCriteria::create(['estado_id' => Estado::MS]);
        TDBCombo::reloadFromModel(self::$formName, 'municipio_id', 'erp_online', 'Municipio', 'id', '{sigla} - {descricao}', 'cod_erp asc', $criteria, TRUE); 

        $this->filtrarativo = true;
        $this->filtrarbloqueado = false;
        $this->onSearch([]);
        $this->onReload([]);

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

        $object = new Mvc($id);

        $row = $list->datagrid->addItem($object);
        $row->id = "row_{$object->id}";

        if($openTransaction)
        {
            TTransaction::close();    
        }

        TDataGrid::replaceRowById(__CLASS__.'_datagrid', $row->id, $row);
    }

}

