<?php

class DashboardGerencia extends TPage
{
    protected $form;
    private $formFields = [];
    private static $database = '';
    private static $activeRecord = '';
    private static $primaryKey = '';
    private static $formName = 'form_DashboardGerencia';

    private $supervisor  = 'N'; //TSession::getValue("supervisor");
    private $vendedor_id = 0 ;//TSession::getValue("vendedor_id");

    /**
     * Form constructor
     * @param $param Request
     */
    public function __construct( $param = null)
    {
        parent::__construct();

        if(!empty($param['target_container']))
        {
            $this->adianti_target_container = $param['target_container'];
        }

        // creates the form
        $this->form = new BootstrapFormBuilder(self::$formName);
        // define the form title
        $this->form->setFormTitle("Dashboard");

        $criteria_vendedor = new TCriteria();
        $criteria_quantidade_notas = new TCriteria();
        $criteria_cliente_positivado = new TCriteria();
        $criteria_devolucao = new TCriteria();
        $criteria_cliente_nao_atendido = new TCriteria();
        $criteria_nao_atendido = new TCriteria();
        $criteria_ticket_medio = new TCriteria();
        $criteria_total_vendedor = new TCriteria();

        $filterVar = 'A';
        $criteria_vendedor->add(new TFilter('status', '=', $filterVar)); 
        $filterVar = 'S';
        $criteria_vendedor->add(new TFilter('dashboard', '=', $filterVar)); 
        $filterVar = 'S';
        $criteria_nao_atendido->add(new TFilter('view_vendedor_cliente_status.vendedor_desligado', '=', $filterVar)); 
        $filterVar = 'A';
        $criteria_nao_atendido->add(new TFilter('view_vendedor_cliente_status.cliente_status', '=', $filterVar)); 

        $this->supervisor = TSession::getValue("supervisor");
        $this->vendedor_id= TSession::getValue("vendedor_id");

        if($this->supervisor == 'S'){
        }else{
            TToast::show("error", "Usuário não é Supervisor.", "center", "fas:info");
            return;
        }

        $dia = new TCombo('dia');
        $mes = new TCombo('mes');
        $ano = new TCombo('ano');
        $vendedor = new TDBCombo('vendedor', 'erp_online', 'Vendedor', 'id', '{nome_status}','nome_reduzido asc' , $criteria_vendedor );
        $button_buscar = new TButton('button_buscar');
        $quantidade_notas = new BIndicator('quantidade_notas');
        $cliente_positivado = new BIndicator('cliente_positivado');
        $devolucao = new BIndicator('devolucao');
        $cliente_nao_atendido = new BIndicator('cliente_nao_atendido');
        $nao_atendido = new BIndicator('nao_atendido');
        $ticket_medio = new BIndicator('ticket_medio');
        $total_vendedor = new BTableChart('total_vendedor');
        $atualizacao = new TDateTime('atualizacao');

        $mes->setChangeAction(new TAction([$this,'onChangeMes']));

        $mes->addValidation("Mês", new TRequiredValidator()); 
        $ano->addValidation("Ano", new TRequiredValidator()); 
        $vendedor->addValidation("Vendedor", new TRequiredValidator()); 

        $button_buscar->setAction(new TAction(['DashboardGerencia', 'onShow']), "Buscar");
        $button_buscar->addStyleClass('btn-success');
        $button_buscar->setImage('fas:search #FFFFFF');
        $atualizacao->setEditable(false);
        $atualizacao->setMask('dd/mm/yyyy hh:ii');
        $atualizacao->setDatabaseMask('yyyy-mm-dd hh:ii');
        $ano->addItems(["2024"=>"2024","2025"=>"2025","2026"=>"2026"]);
        $dia->addItems(SisFunction::arrayDiasFormatadosHTML($param['mes'],$param['ano']));
        $mes->addItems(["01"=>"Janeiro","02"=>"Fevereiro","03"=>"Março","04"=>"Abril","05"=>"Maio","06"=>"Junho","07"=>"Julho","08"=>"Agosto","09"=>"Setembro","10"=>"Outubro","11"=>"Novembro","12"=>"Dezembro"]);

        $dia->setValue($param['dia'] ?? date('d'));
        $mes->setValue($param['mes'] ?? date('m'));
        $ano->setValue($param['ano'] ?? date('Y'));
        $atualizacao->setValue(SisFunction::GetParm('sis_update',' ',null));

        $dia->enableSearch();
        $mes->enableSearch();
        $ano->enableSearch();
        $vendedor->enableSearch();

        $dia->setSize('100%');
        $mes->setSize('100%');
        $ano->setSize('100%');
        $vendedor->setSize('100%');
        $atualizacao->setSize('100%');

        $quantidade_notas->setDatabase('erp_online');
        $quantidade_notas->setFieldValue("view_vendedor_venda_mes.vlr_liquido");
        $quantidade_notas->setModel('ViewVendedorVendaMes');
        $quantidade_notas->setTransformerValue(function($value)
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
        $quantidade_notas->setTotal('sum');
        $quantidade_notas->setColors('#2980B9', '#FFFFFF', '#3498DB', '#FFFFFF');
        $quantidade_notas->setTitle("Sugestão de Venda", '#FFFFFF', '20', '');
        $quantidade_notas->setDescription("Nota Fiscal", '20');
        $quantidade_notas->setCriteria($criteria_quantidade_notas);
        $quantidade_notas->setValueSize("20");
        $quantidade_notas->setValueColor("#FFFFFF", 'B');
        $quantidade_notas->setSize('100%', 95);
        $quantidade_notas->setLayout('horizontal', 'left');

        $cliente_positivado->setDatabase('erp_online');
        $cliente_positivado->setFieldValue("view_base_cliente_mes.cliente_id");
        $cliente_positivado->setModel('ViewBaseClienteMes');
        $cliente_positivado->setTotal('count');
        $cliente_positivado->setColors('#2ECC71', '#FFFFFF', '#27AE60', '#FFFFFF');
        $cliente_positivado->setTitle("Clientes", '#FFFFFF', '20', '');
        $cliente_positivado->setDescription("Positivado", '20');
        $cliente_positivado->setCriteria($criteria_cliente_positivado);
        $cliente_positivado->setValueSize("20");
        $cliente_positivado->setValueColor("#FFFFFF", 'B');
        $cliente_positivado->setSize('100%', 95);
        $cliente_positivado->setLayout('horizontal', 'left');

        $devolucao->setDatabase('erp_online');
        $devolucao->setFieldValue("view_base_venda.vlr_dev");
        $devolucao->setModel('ViewBaseVenda');
        $devolucao->setTransformerValue(function($value)
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
        $devolucao->setTotal('sum');
        $devolucao->setColors('#C0392B', '#FFFFFF', '#E74C3C', '#FFFFFF');
        $devolucao->setTitle("Devolução", '#FFFFFF', '20', '');
        $devolucao->setDescription("Total", '20');
        $devolucao->setCriteria($criteria_devolucao);
        $devolucao->setValueSize("20");
        $devolucao->setValueColor("#FFFFFF", 'B');
        $devolucao->setSize('100%', 95);
        $devolucao->setLayout('horizontal', 'left');

        $cliente_nao_atendido->setDatabase('erp_online');
        $cliente_nao_atendido->setFieldValue("view_base_cliente_mes.cliente_id");
        $cliente_nao_atendido->setModel('ViewBaseClienteMes');
        $cliente_nao_atendido->setTotal('count');
        $cliente_nao_atendido->setColors('#F39C12', '#FFFFFF', '#F1C40F', '#FFFFFF');
        $cliente_nao_atendido->setTitle("Base", '#FFFFFF', '20', '');
        $cliente_nao_atendido->setDescription("Não Atendido", '20');
        $cliente_nao_atendido->setCriteria($criteria_cliente_nao_atendido);
        $cliente_nao_atendido->setValueSize("20");
        $cliente_nao_atendido->setValueColor("#FFFFFF", 'B');
        $cliente_nao_atendido->setSize('100%', 95);
        $cliente_nao_atendido->setLayout('horizontal', 'left');

        $nao_atendido->setDatabase('erp_online');
        $nao_atendido->setFieldValue("view_vendedor_cliente_status.quantidade");
        $nao_atendido->setModel('ViewVendedorClienteStatus');
        $nao_atendido->setTotal('max');
        $nao_atendido->setColors('#E67E22', '#FFFFFF', '#D35400', '#FFFFFF');
        $nao_atendido->setTitle("Clientes", '#FFFFFF', '20', '');
        $nao_atendido->setDescription("Sem Vendedor", '20');
        $nao_atendido->setCriteria($criteria_nao_atendido);
        $nao_atendido->setValueSize("20");
        $nao_atendido->setValueColor("#FFFFFF", 'B');
        $nao_atendido->setSize('100%', 95);
        $nao_atendido->setLayout('horizontal', 'left');

        $ticket_medio->setDatabase('erp_online');
        $ticket_medio->setFieldValue("view_total_catogoria_mes.vlr_liquido");
        $ticket_medio->setModel('ViewTotalCatogoriaMes');
        $ticket_medio->setTransformerValue(function($value)
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
        $ticket_medio->setTotal('avg');
        $ticket_medio->setColors('#F1C40F', '#FFFFFF', '#F39C12', '#FFFFFF');
        $ticket_medio->setTitle("Ticket", '#FFFFFF', '20', '');
        $ticket_medio->setDescription("Medio", '20');
        $ticket_medio->setCriteria($criteria_ticket_medio);
        $ticket_medio->setValueSize("20");
        $ticket_medio->setValueColor("#FFFFFF", 'B');
        $ticket_medio->setSize('100%', 95);
        $ticket_medio->setLayout('horizontal', 'left');

        $total_vendedor_column_vendedor_id = new BTableColumnChart('vendedor_id', "Id", 'left','5%');
        $total_vendedor_column_nome_reduzido = new BTableColumnChart('nome_reduzido', "Vendedor", 'left','25%','asc');
        $total_vendedor_column_mes = new BTableColumnChart('mes', "Mês ", 'center','5%');
        $total_vendedor_column_ano = new BTableColumnChart('ano', "Ano", 'center','5%');
        $total_vendedor_column_positivacao = new BTableColumnChart('positivacao', "Positivação", 'right','5%');
        $total_vendedor_column_vlr_objetivo = new BTableColumnChart('vlr_objetivo', "Objetivo", 'right','10%');
        $total_vendedor_column_vlr_liquido = new BTableColumnChart('vlr_liquido', "Realizado", 'right','10%');
        $total_vendedor_column_perc_liquido = new BTableColumnChart('perc_liquido', "%", 'center','20%');
        $total_vendedor_column_vendedor_id->setTotal('count');
        $total_vendedor_column_positivacao->setTotal('sum');
        $total_vendedor_column_vlr_objetivo->setTotal('sum', function($value, $object, $row)
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
        $total_vendedor_column_vlr_liquido->setTotal('sum', function($value, $object, $row)
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
        $total_vendedor_column_vlr_objetivo->setTransformer(function($value, $object, $row)
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
        $total_vendedor_column_vlr_liquido->setTransformer(function($value, $object, $row)
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
        $total_vendedor_column_perc_liquido->setTransformer(function($value, $object, $row)
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

        $total_vendedor->setDatabase('erp_online');
        $total_vendedor->setModel('ViewVendedorVendaMes');
        $total_vendedor->setTitle("");
        $total_vendedor->setSize('100%', 450);
        $total_vendedor->setColumns([$total_vendedor_column_vendedor_id,$total_vendedor_column_nome_reduzido,$total_vendedor_column_mes,$total_vendedor_column_ano,$total_vendedor_column_positivacao,$total_vendedor_column_vlr_objetivo,$total_vendedor_column_vlr_liquido,$total_vendedor_column_perc_liquido]);
        $total_vendedor->setCriteria($criteria_total_vendedor);

        $total_vendedor->setRowColorOdd('#F9F9F9');
        $total_vendedor->setRowColorEven('#FFFFFF');
        $total_vendedor->setFontRowColorOdd('#333333');
        $total_vendedor->setFontRowColorEven('#333333');
        $total_vendedor->setBorderColor('#DDDDDD');
        $total_vendedor->setTableHeaderColor('#FFFFFF');
        $total_vendedor->setTableHeaderFontColor('#333333');
        $total_vendedor->setTableFooterColor('#FFFFFF');
        $total_vendedor->setTableFooterFontColor('#333333');


        //$total_vendedor->setColumns([$total_vendedor_column_vendedor_id,$total_vendedor_column_mes,$total_vendedor_column_ano,$total_vendedor_column_positivacao,$total_vendedor_column_vlr_liquido,$total_vendedor_column_vlr_objetivo,$total_vendedor_column_perc_liquido]);

        $total_vendedor_column_vendedor_id->setTransformer(function($value, $object, $row, $cell = null, $last_row = null)
        {
                $return = $value;
                //TTransaction::open('erp_online');

                //$oVendedor= Vendedor::find( $object->vendedor_id );
                //if($oVendedor){
                    //$return = $object->vendedor_id//$oVendedor->nome_reduzido;

                    if($object->vlr_liquido > 0){

                        $action = new TAction( ['ViewVendedorVendaSimpleList', 'onShow' ] );
                        $action->setParameter('vendedor_id', $object->vendedor_id);
                        $action->setParameter('ano', $object->ano);
                        $action->setParameter('mes', $object->mes);

                        $return = new TActionLink($object->vendedor_id, $action,'blue');//, 'blue', 12, 'biu');

                        //return $return;//"R$ " . number_format($value, 2, ",", ".");
                        //$cell->style = "background: #FFF9A7";
                    }else{
                        return $return;
                    }

                //}
                //TTransaction::close();

                return $return;

        });        

        $nPerSemVendedor = 0;
        $nClienteAtivo = 0;
        $nPerCliente = 0;
        $nPerFaturamento = 0;
        $ArrayCriteria = array();
        $Criteria_Metas = new TCriteria; 
        $Criteria_CliAtivo = new TCriteria; 

        if((isset($param['mes']) and !empty($param['mes'])) OR $mes->getValue())
        {
            if(isset($param['mes']) and !empty($param['mes'])){
                $ArrayCriteria['mes'] = $param['mes'];
            }else{
                $ArrayCriteria['mes'] = $mes->getValue();
            }    
            $Criteria_Metas->add(new TFilter('mes', '=', $ArrayCriteria['mes'])); 

        }

        if((isset($param['ano']) and !empty($param['ano'])) OR $ano->getValue())
        {
            if(isset($param['ano']) and !empty($param['ano'])){
                $ArrayCriteria['ano'] = $param['ano'];
            }else{
                $ArrayCriteria['ano'] = $ano->getValue();
            }    
            $Criteria_Metas->add(new TFilter('ano', '=', $ArrayCriteria['ano'])); 

        }

        if((isset($param['vendedor']) and !empty($param['vendedor'])))
        {
            $ArrayCriteria['vendedor'] = $param['vendedor'];
            $Criteria_Metas->add(new TFilter('vendedor_id', '=', $ArrayCriteria['vendedor'])); 
            $Criteria_CliAtivo->add(new TFilter('vendedor_id', '=', $ArrayCriteria['vendedor'])); 

        }

        TTransaction::open('erp_online');

        $repository = new TRepository('MetaVendedorMes'); 
        $oMetaClientes = $repository->load($Criteria_Metas);

        if ($oMetaClientes)
        {
            foreach ($oMetaClientes as $oMetaCliente)
            {
                $nPerCliente += $oMetaCliente->numero_cliente;
                $nPerFaturamento += $oMetaCliente->valor;
            }
        }

        $Criteria_CliAtivo->add(new TFilter('cliente_status', '=', 'A')); 
        $oCli_Repo = new TRepository('ViewVendedorClienteStatus'); 
        $oClienteAtivo = $oCli_Repo->load($Criteria_CliAtivo);

        if ($oClienteAtivo)
        {
            foreach ($oClienteAtivo as $oCliente)
            {
                $nClienteAtivo += $oCliente->quantidade;
            }
        }

        TTransaction::close();

        if ($nPerCliente > 0){
            $cliente_positivado->setTarget($nPerCliente, '#ffffff'
                , function($percentage, $target ){
                    return "{$percentage}% de ".$target;
                }
            );
        }

        if ($nClienteAtivo > 0){
                    $cliente_nao_atendido->setTarget($nClienteAtivo, '#ffffff'
                , function($percentage, $target ){
                    return "{$percentage}% de ".$target;
                }
            );
        }

        if ($nPerFaturamento > 0){
            $quantidade_notas->setTarget($nPerFaturamento, '#ffffff'
                , function($percentage, $target ){
                    return "{$percentage}% de "."R$ " . number_format($target, 2, ",", ".");
                }
            );
        }

        //$nDias = SisFunction::diasUteisNoMes($param['mes'],$param['ano']);

        $row1 = $this->form->addFields([new TLabel("Dia:", '#E91E63', '14px', null, '100%'),$dia],[new TLabel("Mês:", '#E91E63', '14px', null, '100%'),$mes],[new TLabel("Ano:", '#E91E63', '14px', null, '100%'),$ano],[new TLabel("Vendedor:", '#E91E63', '14px', null, '100%'),$vendedor],[new TLabel("  ", null, '14px', null, '100%'),$button_buscar]);
        $row1->layout = [' col-2 col-sm-2 col-md-2',' col-2 col-sm-2 col-md-2',' col-2 col-sm-2 col-md-2',' col-4 col-sm-4 col-md-4','col-sm-2'];

        $row2 = $this->form->addFields([$quantidade_notas],[$cliente_positivado],[$devolucao],[$cliente_nao_atendido],[$nao_atendido],[$ticket_medio]);
        $row2->layout = [' col-6 col-sm-6 col-md-2',' col-6 col-sm-6 col-md-2',' col-6 col-sm-6 col-md-2',' col-6 col-sm-6 col-md-2',' col-6 col-sm-6 col-md-2',' col-6 col-sm-6 col-md-2'];

        $row3 = $this->form->addFields([$total_vendedor]);
        $row3->layout = ['col-12 col-sm-12'];

        $row4 = $this->form->addFields([],[new TLabel("Atualizado em:", null, '14px', null, '100%'),$atualizacao]);
        $row4->layout = [' col-sm-9','col-3 col-sm-3 col-md-3'];

        if(!isset($param['dia']) && $dia->getValue())
        {
            $_POST['dia'] = $dia->getValue();
        }
        if(!isset($param['mes']) && $mes->getValue())
        {
            $_POST['mes'] = $mes->getValue();
        }
        if(!isset($param['ano']) && $ano->getValue())
        {
            $_POST['ano'] = $ano->getValue();
        }
        if(!isset($param['atualizacao']) && $atualizacao->getValue())
        {
            $_POST['atualizacao'] = $atualizacao->getValue();
        }

        $searchData = $this->form->getData();
        $this->form->setData($searchData);

        $filterVar = $searchData->ano;
        if($filterVar)
        {
            $criteria_quantidade_notas->add(new TFilter('view_vendedor_venda_mes.ano', '=', $filterVar)); 
        }
        $filterVar = $searchData->mes;
        if($filterVar)
        {
            $criteria_quantidade_notas->add(new TFilter('view_vendedor_venda_mes.mes', '=', $filterVar)); 
        }
        $filterVar = $searchData->vendedor;
        if($filterVar)
        {
            $criteria_quantidade_notas->add(new TFilter('view_vendedor_venda_mes.vendedor_id', '=', $filterVar)); 
        }
        $filterVar = $searchData->vendedor;
        if($filterVar)
        {
            $criteria_cliente_positivado->add(new TFilter('view_base_cliente_mes.vendedor_id', '=', $filterVar)); 
        }
        $filterVar = $searchData->mes;
        if($filterVar)
        {
            $criteria_cliente_positivado->add(new TFilter('view_base_cliente_mes.mes', '=', $filterVar)); 
        }
        $filterVar = $searchData->ano;
        if($filterVar)
        {
            $criteria_cliente_positivado->add(new TFilter('view_base_cliente_mes.ano', '=', $filterVar)); 
        }
        $filterVar = $searchData->mes;
        if($filterVar)
        {
            $criteria_devolucao->add(new TFilter('view_base_venda.mes', '=', $filterVar)); 
        }
        $filterVar = $searchData->ano;
        if($filterVar)
        {
            $criteria_devolucao->add(new TFilter('view_base_venda.ano', '=', $filterVar)); 
        }
        $filterVar = $searchData->vendedor;
        if($filterVar)
        {
            $criteria_devolucao->add(new TFilter('view_base_venda.vendedor_id', '=', $filterVar)); 
        }
        $filterVar = $searchData->ano;
        if($filterVar)
        {
            $criteria_cliente_nao_atendido->add(new TFilter('view_base_cliente_mes.ano', '=', $filterVar)); 
        }
        $filterVar = $searchData->mes;
        if($filterVar)
        {
            $criteria_cliente_nao_atendido->add(new TFilter('view_base_cliente_mes.mes', '=', $filterVar)); 
        }
        $filterVar = $searchData->vendedor;
        if($filterVar)
        {
            $criteria_cliente_nao_atendido->add(new TFilter('view_base_cliente_mes.vendedor_id', '=', $filterVar)); 
        }
        $filterVar = $searchData->vendedor;
        if($filterVar)
        {
            $criteria_ticket_medio->add(new TFilter('view_total_catogoria_mes.vendedor_id', '=', $filterVar)); 
        }
        $filterVar = $searchData->ano;
        if($filterVar)
        {
            $criteria_ticket_medio->add(new TFilter('view_total_catogoria_mes.ano', '=', $filterVar)); 
        }
        $filterVar = $searchData->mes;
        if($filterVar)
        {
            $criteria_ticket_medio->add(new TFilter('view_total_catogoria_mes.mes', '=', $filterVar)); 
        }
        $filterVar = $searchData->mes;
        if($filterVar)
        {
            $criteria_total_vendedor->add(new TFilter('view_vendedor_venda_mes.mes', '=', $filterVar)); 
        }
        $filterVar = $searchData->ano;
        if($filterVar)
        {
            $criteria_total_vendedor->add(new TFilter('view_vendedor_venda_mes.ano', '=', $filterVar)); 
        }

        BChart::generate($quantidade_notas, $cliente_positivado, $devolucao, $cliente_nao_atendido, $nao_atendido, $ticket_medio, $total_vendedor);

        // create the form actions

        // vertical box container
        $container = new TVBox;
        $container->style = 'width: 100%';
        $container->class = 'form-container';
        if(empty($param['target_container']))
        {
            // $container->add(new TXMLBreadCrumb('menu.xml', __CLASS__));
        }
        $container->add($this->form);

        parent::add($container);

    }

    public static function onChangeMes($param = null) 
    {
        try 
        {

        }
        catch (Exception $e) 
        {
            new TMessage('error', $e->getMessage());    
        }
    }

    public function onShow($param = null)
    {               

        $this->supervisor = TSession::getValue("supervisor");
        $this->vendedor_id= TSession::getValue("vendedor_id");

        $object = new stdClass();

        if(isset($param['ano'])){
            $object->ano = $param['ano'];
        }else{
            $object->ano = date('Y');
            $param['ano']= date('Y');
        }

        if(isset($param['mes'])){
            $object->mes = $param['mes'];
        }else{
            $object->mes = date('m');
            $param['mes']= date('m');
        }

        if(isset($param['dia'])){
            $object->dia = $param['dia'];
        }else{
            $object->dia = date('d')-1;
            $param['dia']= date('d')-1;
        }

        if($this->supervisor == "S"){

        }else{
            TToast::show("error", "Usuário não é supervisor.", "center", "fas:info");
            return;
        }        

        //var_dump(SisFunction::diasDoMesFormatados($param['mes'],$param['ano']));

        TForm::sendData(self::$formName, $object);                

    } 

}

