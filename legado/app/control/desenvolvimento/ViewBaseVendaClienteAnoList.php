<?php

class ViewBaseVendaClienteAnoList extends TPage
{
    private $form; // form
    private $datagrid; // listing
    private $pageNavigation;
    private $loaded;
    private $filter_criteria;
    private static $database = 'erp_online';
    private static $activeRecord = 'ViewBaseVendaClienteAno';
    private static $primaryKey = 'cliente_id';
    private static $formName = 'form_ViewBaseVendaClienteAnoList';
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
        $this->form->setFormTitle("Venda Cliente");
        $this->limit = 20;

        $criteria_vendedor_id = new TCriteria();

        $cliente_id = new TEntry('cliente_id');
        $vendedor_id = new TDBUniqueSearch('vendedor_id', 'erp_online', 'Vendedor', 'id', 'nome','nome asc' , $criteria_vendedor_id );
        $mes_base = new TCombo('mes_base');
        $ano = new TCombo('ano');
        $tipo = new TRadioGroup('tipo');

        $vendedor_id->addValidation("Vendedor", new TRequiredValidator()); 
        $mes_base->addValidation("Mês", new TRequiredValidator()); 
        $ano->addValidation("Ano", new TRequiredValidator()); 

        $vendedor_id->setMinLength(2);
        $vendedor_id->setMask('{nome_reduzido}');
        $vendedor_id->setFilterColumns(["cod_erp","nome_reduzido","nome"]);
        $tipo->setLayout('horizontal');
        $tipo->setUseButton();
        $tipo->setBreakItems(3);
        $ano->setValue($param['ano'] ?? date('Y'));
        $mes_base->setValue($param['mes_base'] ?? date('m'));

        $ano->enableSearch();
        $mes_base->enableSearch();

        $ano->addItems(TempoService::getAnos());
        $mes_base->addItems(TempoService::getMeses());
        $tipo->addItems(["T"=>"Todos","C"=>"Com Venda","S"=>"Sem Venda"]);

        $tipo->setSize(80);
        $ano->setSize('100%');
        $mes_base->setSize('100%');
        $cliente_id->setSize('100%');
        $vendedor_id->setSize('100%');

        $row1 = $this->form->addFields([new TLabel("Cliente:", null, '14px', null, '100%'),$cliente_id],[new TLabel("Vendedor:", '#F44336', '14px', null, '100%'),$vendedor_id]);
        $row1->layout = [' col-sm-6',' col-sm-6'];

        $row2 = $this->form->addFields([new TLabel("Mês:", '#F44336', '14px', null, '100%'),$mes_base],[new TLabel("Ano:", '#F44336', '14px', null, '100%'),$ano],[$tipo]);
        $row2->layout = [' col-sm-3',' col-sm-3','col-sm-6'];

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

        $column_cliente_id_transformed = new TDataGridColumn('cliente_id', "Cliente", 'center' , '70px');
        $column_ano = new TDataGridColumn('ano', "Ano", 'left');
        $column_janeiro = new TDataGridColumn('janeiro', "Janeiro", 'left');
        $column_fevereiro = new TDataGridColumn('fevereiro', "Fevereiro", 'left');
        $column_marco = new TDataGridColumn('marco', "Marco", 'left');
        $column_abril = new TDataGridColumn('abril', "Abril", 'left');
        $column_maio = new TDataGridColumn('maio', "Maio", 'left');
        $column_junho = new TDataGridColumn('junho', "Junho", 'left');
        $column_julho = new TDataGridColumn('julho', "Julho", 'left');
        $column_agosto = new TDataGridColumn('agosto', "Agosto", 'left');
        $column_setembro = new TDataGridColumn('setembro', "Setembro", 'left');
        $column_outubro = new TDataGridColumn('outubro', "Outubro", 'left');
        $column_novembro = new TDataGridColumn('novembro', "Novembro", 'left');
        $column_dezembro = new TDataGridColumn('dezembro', "Dezembro", 'left');

        $column_janeiro->setTotalFunction( function($values) { 
            return array_sum((array) $values); 
        }); 

        $column_fevereiro->setTotalFunction( function($values) { 
            return array_sum((array) $values); 
        }); 

        $column_cliente_id_transformed->setTransformer(function($value, $object, $row, $cell = null, $last_row = null)
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

        $order_cliente_id_transformed = new TAction(array($this, 'onReload'));
        $order_cliente_id_transformed->setParameter('order', 'cliente_id');
        $column_cliente_id_transformed->setAction($order_cliente_id_transformed);

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

        $column_janeiro->setTransformer(function($value, $object, $row, $cell = null, $last_row = null)
        {
            $mes_base = TSession::getValue('mes_ref');
            if($mes_base == '01'){
                $cell->style = 'background: #FFF9A7;';
            }

            if(!$value)
            {
                $value = 0;
            }

            if(is_numeric($value))
            {
                $valor = number_format($value, 2, ",", "."); //"R$ " . 

                if($value > 0){
                    $action_JANEIRO = new TAction( ['VendaClienteMesForm', 'onShow' ] );
                    $action_JANEIRO->setParameter('cliente_id', $object->cliente_id);
                    $action_JANEIRO->setParameter('vendedor_id', $object->vendedor_id);
                    $action_JANEIRO->setParameter('ano', $object->ano);
                    $action_JANEIRO->setParameter('mes', '01');

                    $link_JANEIRO = new TActionLink($valor, $action_JANEIRO,'blue');//, 'blue', 12, 'biu');

                    return $link_JANEIRO;//"R$ " . number_format($value, 2, ",", ".");
                    //$cell->style = "background: #FFF9A7";
                }else{
                    return $valor;
                }
            }
            else
            {
                return $value;
            }
        });

        $column_fevereiro->setTransformer(function($value, $object, $row, $cell = null, $last_row = null)
        {
            $mes_base = TSession::getValue('mes_ref');
            if($mes_base == '02'){
                $cell->style = 'background: #FFF9A7;';
            }

            if(!$value)
            {
                $value = 0;
            }

            if(is_numeric($value))
            {
                $valor = number_format($value, 2, ",", "."); //"R$ " . 

                if($value > 0){
                    $action_FEVEREIRO = new TAction( ['VendaClienteMesForm', 'onShow' ] );
                    $action_FEVEREIRO->setParameter('cliente_id', $object->cliente_id);
                    $action_FEVEREIRO->setParameter('vendedor_id', $object->vendedor_id);
                    $action_FEVEREIRO->setParameter('ano', $object->ano);
                    $action_FEVEREIRO->setParameter('mes', '02');

                    $link_FEVEREIRO = new TActionLink($valor, $action_FEVEREIRO,'blue');//, 'blue', 12, 'biu');

                    return $link_FEVEREIRO;//"R$ " . number_format($value, 2, ",", ".");
                }else{
                    return $valor;
                }
            }
            else
            {
                return $value;
            }
        });

        $column_marco->setTransformer(function($value, $object, $row, $cell = null, $last_row = null)
        {
            $mes_base = TSession::getValue('mes_ref');
            if($mes_base == '03'){
                $cell->style = 'background: #FFF9A7;';
            }

            if(!$value)
            {
                $value = 0;
            }

            if(is_numeric($value))
            {
                $valor = number_format($value, 2, ",", "."); //"R$ " . 

                if($value > 0){
                    $action_MARCO = new TAction( ['VendaClienteMesForm', 'onShow' ] );
                    $action_MARCO->setParameter('cliente_id', $object->cliente_id);
                    $action_MARCO->setParameter('vendedor_id', $object->vendedor_id);
                    $action_MARCO->setParameter('ano', $object->ano);
                    $action_MARCO->setParameter('mes', '03');

                    $link_MARCO = new TActionLink($valor, $action_MARCO,'blue');//, 'blue', 12, 'biu');

                    return $link_MARCO;//"R$ " . number_format($value, 2, ",", ".");
                }else{
                    return $valor;
                }
            }
            else
            {
                return $value;
            }
        });       

        $column_abril->setTransformer(function($value, $object, $row, $cell = null, $last_row = null)
        {
            $mes_base = TSession::getValue('mes_ref');
            if($mes_base == '04'){
                $cell->style = 'background: #FFF9A7;';
            }

            if(!$value)
            {
                $value = 0;
            }

            if(is_numeric($value))
            {
                $valor = number_format($value, 2, ",", "."); //"R$ " . 

                if($value > 0){
                    $action_ABRIL = new TAction( ['VendaClienteMesForm', 'onShow' ] );
                    $action_ABRIL->setParameter('cliente_id', $object->cliente_id);
                    $action_ABRIL->setParameter('vendedor_id', $object->vendedor_id);
                    $action_ABRIL->setParameter('ano', $object->ano);
                    $action_ABRIL->setParameter('mes', '04');

                    $link_ABRIL = new TActionLink($valor, $action_ABRIL,'blue');//, 'blue', 12, 'biu');

                    return $link_ABRIL;//"R$ " . number_format($value, 2, ",", ".");
                }else{
                    return $valor;
                }
            }
            else
            {
                return $value;
            }
        });               

        $column_maio->setTransformer(function($value, $object, $row, $cell = null, $last_row = null)
        {
            $mes_base = TSession::getValue('mes_ref');
            if($mes_base == '05'){
                $cell->style = 'background: #FFF9A7;';
            }

            if(!$value)
            {
                $value = 0;
            }

            if(is_numeric($value))
            {
                $valor = number_format($value, 2, ",", "."); //"R$ " . 

                if($value > 0){
                    $action_MAIO = new TAction( ['VendaClienteMesForm', 'onShow' ] );
                    $action_MAIO->setParameter('cliente_id', $object->cliente_id);
                    $action_MAIO->setParameter('vendedor_id', $object->vendedor_id);
                    $action_MAIO->setParameter('ano', $object->ano);
                    $action_MAIO->setParameter('mes', '05');

                    $link_MAIO = new TActionLink($valor, $action_MAIO,'blue');//, 'blue', 12, 'biu');

                    return $link_MAIO;//"R$ " . number_format($value, 2, ",", ".");
                }else{
                    return $valor;
                }
            }
            else
            {
                return $value;
            }
        });

        $column_junho->setTransformer(function($value, $object, $row, $cell = null, $last_row = null)
        {
            $mes_base = TSession::getValue('mes_ref');
            if($mes_base == '06'){
                $cell->style = 'background: #FFF9A7;';
            }

            if(!$value)
            {
                $value = 0;
            }

            if(is_numeric($value))
            {
                $valor = number_format($value, 2, ",", "."); //"R$ " . 

                if($value > 0){
                    $action_JUNHO = new TAction( ['VendaClienteMesForm', 'onShow' ] );
                    $action_JUNHO->setParameter('cliente_id', $object->cliente_id);
                    $action_JUNHO->setParameter('vendedor_id', $object->vendedor_id);
                    $action_JUNHO->setParameter('ano', $object->ano);
                    $action_JUNHO->setParameter('mes', '06');

                    $link_JUNHO = new TActionLink($valor, $action_JUNHO,'blue');//, 'blue', 12, 'biu');
                    return $link_JUNHO;//"R$ " . number_format($value, 2, ",", ".");
                }else{
                    return $valor;
                }
            }
            else
            {
                return $value;
            }
        });

        $column_julho->setTransformer(function($value, $object, $row, $cell = null, $last_row = null)
        {
            $mes_base = TSession::getValue('mes_ref');
            if($mes_base == '07'){
                $cell->style = 'background: #FFF9A7;';
            }

            if(!$value)
            {
                $value = 0;
            }

            if(is_numeric($value))
            {
                $valor = number_format($value, 2, ",", "."); //"R$ " . 

                if($value > 0){
                    $action_JULHO = new TAction( ['VendaClienteMesForm', 'onShow' ] );
                    $action_JULHO->setParameter('cliente_id', $object->cliente_id);
                    $action_JULHO->setParameter('vendedor_id', $object->vendedor_id);
                    $action_JULHO->setParameter('ano', $object->ano);
                    $action_JULHO->setParameter('mes', '07');

                    $link_JULHO = new TActionLink($valor, $action_JULHO,'blue');//, 'blue', 12, 'biu');
                    return $link_JULHO;//"R$ " . number_format($value, 2, ",", ".");
                }else{
                    return $valor;
                }
            }
            else
            {
                return $value;
            }
        });

        $column_agosto->setTransformer(function($value, $object, $row, $cell = null, $last_row = null)
        {
            $mes_base = TSession::getValue('mes_ref');
            if($mes_base == '08'){
                $cell->style = 'background: #FFF9A7;';
            }

            if(!$value)
            {
                $value = 0;
            }

            if(is_numeric($value))
            {
                $valor = number_format($value, 2, ",", "."); //"R$ " . 

                if($value > 0){
                    $action_AGOSTO = new TAction( ['VendaClienteMesForm', 'onShow' ] );
                    $action_AGOSTO->setParameter('cliente_id', $object->cliente_id);
                    $action_AGOSTO->setParameter('vendedor_id', $object->vendedor_id);
                    $action_AGOSTO->setParameter('ano', $object->ano);
                    $action_AGOSTO->setParameter('mes', '08');

                    $link_AGOSTO = new TActionLink($valor, $action_AGOSTO,'blue');//, 'blue', 12, 'biu');
                    return $link_AGOSTO;//"R$ " . number_format($value, 2, ",", ".");
                }else{
                    return $valor;
                }
            }
            else
            {
                return $value;
            }
        });

        $column_setembro->setTransformer(function($value, $object, $row, $cell = null, $last_row = null)
        {
            $mes_base = TSession::getValue('mes_ref');
            if($mes_base == '09'){
                $cell->style = 'background: #FFF9A7;';
            }

            if(!$value)
            {
                $value = 0;
            }

            if(is_numeric($value))
            {
                $valor = number_format($value, 2, ",", "."); //"R$ " . 

                if($value > 0){
                    $action_SETEMBRO = new TAction( ['VendaClienteMesForm', 'onShow' ] );
                    $action_SETEMBRO->setParameter('cliente_id', $object->cliente_id);
                    $action_SETEMBRO->setParameter('vendedor_id', $object->vendedor_id);
                    $action_SETEMBRO->setParameter('ano', $object->ano);
                    $action_SETEMBRO->setParameter('mes', '09');

                    $link_SETEMBRO = new TActionLink($valor, $action_SETEMBRO,'blue');//, 'blue', 12, 'biu');

                    return $link_SETEMBRO;//"R$ " . number_format($value, 2, ",", ".");
                }else{
                    return $valor;
                }
            }
            else
            {
                return $value;
            }
        });                

        $column_outubro->setTransformer(function($value, $object, $row, $cell = null, $last_row = null)
        {
            $mes_base = TSession::getValue('mes_ref');
            if($mes_base == '10'){
                $cell->style = 'background: #FFF9A7;';
            }

            if(!$value)
            {
                $value = 0;
            }

            if(is_numeric($value))
            {
                $valor = number_format($value, 2, ",", "."); //"R$ " . 

                if($value > 0){
                    $action_OUTUBRO = new TAction( ['VendaClienteMesForm', 'onShow' ] );
                    $action_OUTUBRO->setParameter('cliente_id', $object->cliente_id);
                    $action_OUTUBRO->setParameter('vendedor_id', $object->vendedor_id);
                    $action_OUTUBRO->setParameter('ano', $object->ano);
                    $action_OUTUBRO->setParameter('mes', '10');

                    $link_OUTUBRO = new TActionLink($valor, $action_OUTUBRO,'blue');//, 'blue', 12, 'biu');

                    return $link_OUTUBRO;//"R$ " . number_format($value, 2, ",", ".");
                }else{
                    return $valor;
                }
            }
            else
            {
                return $value;
            }
        });        

        $column_novembro->setTransformer(function($value, $object, $row, $cell = null, $last_row = null)
        {
            $mes_base = TSession::getValue('mes_ref');
            if($mes_base == '11'){
                $cell->style = 'background: #FFF9A7;';
            }

            if(!$value)
            {
                $value = 0;
            }

            if(is_numeric($value))
            {
                $valor = number_format($value, 2, ",", "."); //"R$ " . 

                if($value > 0){
                    $action_NOVEMBRO = new TAction( ['VendaClienteMesForm', 'onShow' ] );
                    $action_NOVEMBRO->setParameter('cliente_id', $object->cliente_id);
                    $action_NOVEMBRO->setParameter('vendedor_id', $object->vendedor_id);
                    $action_NOVEMBRO->setParameter('ano', $object->ano);
                    $action_NOVEMBRO->setParameter('mes', '11');

                    $link_NOVEMBRO = new TActionLink($valor, $action_NOVEMBRO,'blue');//, 'blue', 12, 'biu');
                    return $link_NOVEMBRO;//"R$ " . number_format($value, 2, ",", ".");
                }else{
                    return $valor;
                }
            }
            else
            {
                return $value;
            }
        });  

        $column_dezembro->setTransformer(function($value, $object, $row, $cell = null, $last_row = null)
        {
            $mes_base = TSession::getValue('mes_ref');
            if($mes_base == '12'){
                $cell->style = 'background: #FFF9A7;';
            }

            if(!$value)
            {
                $value = 0;
            }

            if(is_numeric($value))
            {
                $valor = number_format($value, 2, ",", "."); //"R$ " . 

                if($value > 0){
                    $action_DEZEMBRO = new TAction( ['VendaClienteMesForm', 'onShow' ] );
                    $action_DEZEMBRO->setParameter('cliente_id', $object->cliente_id);
                    $action_DEZEMBRO->setParameter('vendedor_id', $object->vendedor_id);
                    $action_DEZEMBRO->setParameter('ano', $object->ano);
                    $action_DEZEMBRO->setParameter('mes', '12');

                    $link_DEZEMBRO = new TActionLink($valor, $action_DEZEMBRO,'blue');//, 'blue', 12, 'biu');

                    return $link_DEZEMBRO;//"R$ " . number_format($value, 2, ",", ".");
                }else{
                    return $valor;
                }
            }
            else
            {
                return $value;
            }
        }); 
    /*
        $column_cliente_id->setTransformer(function($value, $object, $row, $cell = null, $last_row = null)
        {
            //code here
            $cRetorno = 'Não';
            $nComodato = 0 ;
            $oNotas = NotaSaida::where('cliente_id',  '=', $object->cliente_id)
                        ->where('reg_ativo ', '=', 'S')
                        ->where('tipo ', '=', 'N')
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
                $action_Comodato->setParameter('cliente_id', $object->cliente_id);
                //$action_Comodato->setParameter('vendedor_id', $object->vendedor_id);
                //$action_Comodato->setParameter('ano', $object->ano);
                //$action_Comodato->setParameter('mes', '12');
                $link_Comodato = new TActionLink("Sim", $action_Comodato,'blue');//, 'blue', 12, 'biu');

                return $link_Comodato;//"R$ " . number_format($value, 2, ",", ".");
                //$cRetorno = 'Sim';
            }

            return $cRetorno;

        });
    */

        $this->datagrid->addColumn($column_cliente_id_transformed);
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

        // create the datagrid model
        $this->datagrid->createModel();

        // creates the page navigation
        $this->pageNavigation = new TPageNavigation;
        $this->pageNavigation->enableCounters();
        $this->pageNavigation->setAction(new TAction(array($this, 'onReload')));
        $this->pageNavigation->setWidth($this->datagrid->getWidth());

        $panel = new TPanelGroup("Venda Cliente");
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
        $btnShowCurtainFilters->setAction(new TAction(['ViewBaseVendaClienteAnoList', 'onShowCurtainFilters']), "Filtros");
        $btnShowCurtainFilters->addStyleClass('btn-default');
        $btnShowCurtainFilters->setImage('fas:filter #000000');

        $this->datagrid_form->addField($btnShowCurtainFilters);

        $button_atualizar = new TButton('button_button_atualizar');
        $button_atualizar->setAction(new TAction(['ViewBaseVendaClienteAnoList', 'onRefresh']), "Atualizar");
        $button_atualizar->addStyleClass('btn-default');
        $button_atualizar->setImage('fas:sync-alt #03a9f4');

        $this->datagrid_form->addField($button_atualizar);

        $button_limpar_filtros = new TButton('button_button_limpar_filtros');
        $button_limpar_filtros->setAction(new TAction(['ViewBaseVendaClienteAnoList', 'onClearFilters']), "Limpar filtros");
        $button_limpar_filtros->addStyleClass('btn-default');
        $button_limpar_filtros->setImage('fas:eraser #f44336');

        $this->datagrid_form->addField($button_limpar_filtros);

        $dropdown_button_exportar = new TDropDown("Exportar", 'fas:file-export #2d3436');
        $dropdown_button_exportar->setPullSide('right');
        $dropdown_button_exportar->setButtonClass('btn btn-default waves-effect dropdown-toggle');
        $dropdown_button_exportar->addPostAction( "CSV", new TAction(['ViewBaseVendaClienteAnoList', 'onExportCsv'],['static' => 1]), 'datagrid_'.self::$formName, 'fas:file-csv #00b894' );
        $dropdown_button_exportar->addPostAction( "XLS", new TAction(['ViewBaseVendaClienteAnoList', 'onExportXls'],['static' => 1]), 'datagrid_'.self::$formName, 'fas:file-excel #4CAF50' );
        $dropdown_button_exportar->addPostAction( "PDF", new TAction(['ViewBaseVendaClienteAnoList', 'onExportPdf'],['static' => 1]), 'datagrid_'.self::$formName, 'far:file-pdf #e74c3c' );
        $dropdown_button_exportar->addPostAction( "XML", new TAction(['ViewBaseVendaClienteAnoList', 'onExportXml'],['static' => 1]), 'datagrid_'.self::$formName, 'far:file-code #95a5a6' );

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
            $container->add(TBreadCrumb::create(["Desenvolvimento","Venda Cliente"]));
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
            $page->setProperty('page-name', 'ViewBaseVendaClienteAnoListSearch');
            $page->setProperty('page_name', 'ViewBaseVendaClienteAnoListSearch');
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

        if (isset($data->cliente_id) AND ( (is_scalar($data->cliente_id) AND $data->cliente_id !== '') OR (is_array($data->cliente_id) AND (!empty($data->cliente_id)) )) )
        {

            $filters[] = new TFilter('cliente_id', '=', $data->cliente_id);// create the filter 
        }

        if (isset($data->vendedor_id) AND ( (is_scalar($data->vendedor_id) AND $data->vendedor_id !== '') OR (is_array($data->vendedor_id) AND (!empty($data->vendedor_id)) )) )
        {

            $filters[] = new TFilter('vendedor_id', '=', $data->vendedor_id);// create the filter 
        }

        if (isset($data->ano) AND ( (is_scalar($data->ano) AND $data->ano !== '') OR (is_array($data->ano) AND (!empty($data->ano)) )) )
        {

            $filters[] = new TFilter('ano', 'like', "%{$data->ano}%");// create the filter 
        }

        if (isset($data->tipo) AND ( (is_scalar($data->tipo) AND $data->tipo !== '') OR (is_array($data->tipo) AND (!empty($data->tipo)) )) )
        {

            if($data->tipo !== 'T')
            {
                if (isset($data->mes_base) AND ( (is_scalar($data->mes_base) AND $data->mes_base !== '') OR (is_array($data->mes_base) AND (!empty($data->mes_base)) )) )
                {
                    if($data->tipo == 'C'){
                        $cOperador = '>';
                    }else{
                        $cOperador = '<=';
                    }

                    if($data->mes_base == '01'){
                        $filters[] = new TFilter('janeiro'  , $cOperador , 0);
                    }elseif($data->mes_base == '02'){
                        $filters[] = new TFilter('fevereiro', $cOperador , 0);
                    }elseif($data->mes_base == '03'){
                        $filters[] = new TFilter('marco'    , $cOperador , 0);
                    }elseif($data->mes_base == '04'){
                        $filters[] = new TFilter('abril'    , $cOperador , 0);
                    }elseif($data->mes_base == '05'){
                        $filters[] = new TFilter('maio'     , $cOperador , 0);
                    }elseif($data->mes_base == '06'){
                        $filters[] = new TFilter('junho'    , $cOperador , 0);
                    }elseif($data->mes_base == '07'){
                        $filters[] = new TFilter('julho'    , $cOperador , 0);
                    }elseif($data->mes_base == '08'){
                        $filters[] = new TFilter('agosto'   , $cOperador , 0);
                    }elseif($data->mes_base == '09'){
                        $filters[] = new TFilter('setembro' , $cOperador , 0);
                    }elseif($data->mes_base == '10'){
                        $filters[] = new TFilter('outubro'  , $cOperador , 0);
                    }elseif($data->mes_base == '11'){
                        $filters[] = new TFilter('novembro' , $cOperador , 0);
                    }elseif($data->mes_base == '12'){
                        $filters[] = new TFilter('dezembro' , $cOperador , 0);
                    }

                    //$this->mes_ref = $data->mes_base;
                    $valor = TSession::setValue('mes_ref', $data->mes_base);

                }
            }
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

            // creates a repository for ViewBaseVendaClienteAno
            $repository = new TRepository(self::$activeRecord);

            $criteria = clone $this->filter_criteria;

            if (empty($param['order']))
            {
                $param['order'] = 'cliente_id';    
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
                    $row->id = "row_{$object->cliente_id}";

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

        $object = new ViewBaseVendaClienteAno($id);

        $row = $list->datagrid->addItem($object);
        $row->id = "row_{$object->cliente_id}";

        if($openTransaction)
        {
            TTransaction::close();    
        }

        TDataGrid::replaceRowById(__CLASS__.'_datagrid', $row->id, $row);
    }

}

