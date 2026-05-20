<?php

class DashboardVendedor extends TPage
{
    protected $form;
    private $formFields = [];
    private static $database = '';
    private static $activeRecord = '';
    private static $primaryKey = '';
    private static $formName = 'form_DashboardVendedor';

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
        $criteria_vendas_categoria = new TCriteria();

        $filterVar = 'A';
        $criteria_vendedor->add(new TFilter('status', '=', $filterVar)); 
        $filterVar = 'S';
        $criteria_vendedor->add(new TFilter('dashboard', '=', $filterVar)); 

        $this->supervisor = TSession::getValue("supervisor");
        $this->vendedor_id= TSession::getValue("vendedor_id");

        if($this->supervisor == 'S'){
            TDBCombo::enableField(self::$formName, 'vendedor');
        }else{
            TDBCombo::disableField(self::$formName, 'vendedor');
            if($this->vendedor_id > 0){

                $object = new stdClass();
                $object->vendedor = $this->vendedor_id;
                TForm::sendData(self::$formName, $object);                
            }else{
                TToast::show("error", "Usuário não é Vendedor.", "center", "fas:info");
                return;
            }            
        }

        $mes = new TCombo('mes');
        $ano = new TCombo('ano');
        $vendedor = new TDBCombo('vendedor', 'erp_online', 'Vendedor', 'id', '{nome_status}','nome_reduzido asc' , $criteria_vendedor );
        $button_buscar = new TButton('button_buscar');
        $quantidade_notas = new BIndicator('quantidade_notas');
        $cliente_positivado = new BIndicator('cliente_positivado');
        $devolucao = new BIndicator('devolucao');
        $cliente_nao_atendido = new BIndicator('cliente_nao_atendido');
        $vendas_categoria = new BTableChart('vendas_categoria');
        $atualizacao = new TDateTime('atualizacao');

        $mes->setChangeAction(new TAction([$this,'onChangeMes']));

        $mes->addValidation("Mês", new TRequiredValidator()); 
        $ano->addValidation("Ano", new TRequiredValidator()); 
        $vendedor->addValidation("Vendedor", new TRequiredValidator()); 

        $button_buscar->setAction(new TAction(['DashboardVendedor', 'onShow']), "Buscar");
        $button_buscar->addStyleClass('btn-success');
        $button_buscar->setImage('fas:search #FFFFFF');
        $atualizacao->setEditable(false);
        $atualizacao->setMask('dd/mm/yyyy hh:ii');
        $atualizacao->setDatabaseMask('yyyy-mm-dd hh:ii');
        $mes->addItems(TempoService::getMeses());
        $ano->addItems(["2024"=>"2024","2025"=>"2025","2026"=>"2026"]);

        $mes->enableSearch();
        $ano->enableSearch();
        $vendedor->enableSearch();

        $mes->setSize('100%');
        $ano->setSize('100%');
        $vendedor->setSize('100%');
        $atualizacao->setSize('100%');

        $mes->setValue($param['mes'] ?? date('m'));
        $ano->setValue($param['ano'] ?? date('Y'));
        $vendedor->setValue(TSession::getValue("vendedor_id"));
        $atualizacao->setValue(SisFunction::GetParm('sis_update',' ',null));

        $quantidade_notas->setDatabase('erp_online');
        $quantidade_notas->setFieldValue("view_total_catogoria_mes.vlr_liquido");
        $quantidade_notas->setModel('ViewTotalCatogoriaMes');
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
        $quantidade_notas->setColors('#74B9FF', '#FFFFFF', '#0984E3', '#FFFFFF');
        $quantidade_notas->setTitle("Sugestão de Venda", '#FFFFFF', '20', '');
        $quantidade_notas->setDescription("Nota Fiscal", '20');
        $quantidade_notas->setCriteria($criteria_quantidade_notas);
        $quantidade_notas->setIcon(new TImage('fas:money-bill-wave #FFFFFF'));
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
        $cliente_positivado->setIcon(new TImage('fas:user #FFFFFF'));
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
        $devolucao->setColors('#A29BFE', '#FFFFFF', '#6C5CE7', '#FFFFFF');
        $devolucao->setTitle("Devolução", '#FFFFFF', '20', '');
        $devolucao->setDescription("Total", '20');
        $devolucao->setCriteria($criteria_devolucao);
        $devolucao->setIcon(new TImage('far:stop-circle #FFFFFF'));
        $devolucao->setValueSize("20");
        $devolucao->setValueColor("#FFFFFF", 'B');
        $devolucao->setSize('100%', 95);
        $devolucao->setLayout('horizontal', 'left');

        $cliente_nao_atendido->setDatabase('erp_online');
        $cliente_nao_atendido->setFieldValue("view_base_cliente_mes.cliente_id");
        $cliente_nao_atendido->setModel('ViewBaseClienteMes');
        $cliente_nao_atendido->setTotal('count');
        $cliente_nao_atendido->setColors('#3498DB', '#FFFFFF', '#2980B9', '#FFFFFF');
        $cliente_nao_atendido->setTitle("Base", '#FFFFFF', '20', '');
        $cliente_nao_atendido->setDescription("Não Atendido", '20');
        $cliente_nao_atendido->setCriteria($criteria_cliente_nao_atendido);
        $cliente_nao_atendido->setIcon(new TImage('fas:battery-three-quarters #FFFFFF'));
        $cliente_nao_atendido->setValueSize("20");
        $cliente_nao_atendido->setValueColor("#FFFFFF", 'B');
        $cliente_nao_atendido->setSize('100%', 95);
        $cliente_nao_atendido->setLayout('horizontal', 'left');

        $vendas_categoria_column_cod_erp = new BTableColumnChart('cod_erp', "Código", 'left','10%','asc');
        $vendas_categoria_column_categoria = new BTableColumnChart('categoria', "Categoria", 'left','30%');
        $vendas_categoria_column_vlr_objetivo = new BTableColumnChart('vlr_objetivo', "Objetivo", 'right','20%');
        $vendas_categoria_column_vlr_liquido = new BTableColumnChart('vlr_liquido', "Realizado", 'right','20%');
        $vendas_categoria_column_perc_liquido = new BTableColumnChart('perc_liquido', "%", 'center','20%');
        $vendas_categoria_column_vlr_objetivo->setTotal('sum', function($value, $object, $row)
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
        $vendas_categoria_column_vlr_liquido->setTotal('sum', function($value, $object, $row)
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
        $vendas_categoria_column_vlr_objetivo->setTransformer(function($value, $object, $row)
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
        $vendas_categoria_column_vlr_liquido->setTransformer(function($value, $object, $row)
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
        $vendas_categoria_column_perc_liquido->setTransformer(function($value, $object, $row)
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

        $vendas_categoria->setDatabase('erp_online');
        $vendas_categoria->setModel('ViewTotalCatogoriaMes');
        $vendas_categoria->setTitle("Vendas Categoria");
        $vendas_categoria->setSize('100%', 280);
        $vendas_categoria->setColumns([$vendas_categoria_column_cod_erp,$vendas_categoria_column_categoria,$vendas_categoria_column_vlr_objetivo,$vendas_categoria_column_vlr_liquido,$vendas_categoria_column_perc_liquido]);
        $vendas_categoria->setCriteria($criteria_vendas_categoria);

        $vendas_categoria->setRowColorOdd('#F9F9F9');
        $vendas_categoria->setRowColorEven('#FFFFFF');
        $vendas_categoria->setFontRowColorOdd('#333333');
        $vendas_categoria->setFontRowColorEven('#333333');
        $vendas_categoria->setBorderColor('#DDDDDD');
        $vendas_categoria->setTableHeaderColor('#FFFFFF');
        $vendas_categoria->setTableHeaderFontColor('#333333');
        $vendas_categoria->setTableFooterColor('#FFFFFF');
        $vendas_categoria->setTableFooterFontColor('#333333');

        $vendas_categoria->hidePanel();

        //$this->vendas_categorias_mes->setHeight = true;
        //$this->vendas_categorias_mes->makeScrollable = 250;

        //$this->vendas_categorias_mes->makeScrollable = '50';

        if($this->supervisor == "S"){
            TDBCombo::enableField(self::$formName, 'vendedor');
        }else{

            TDBCombo::disableField(self::$formName, 'vendedor');

            if($this->vendedor_id > 0){
                $object = new stdClass();
                $object->vendedor = $this->vendedor_id;
                TForm::sendData(self::$formName, $object);                
            }else{
                TToast::show("error", "Usuário não é Vendedor.", "center", "fas:info");
                return;
            }            

            $vendedor->setEditable(false);

        }

        $cTipoMeta = "G";
        $nPerCliente = 0;
        $nPerFaturamento = 0;
        $nClienteMes = 0;
        $ArrayCriteria = array();
        $Criteria_ClientesMes = new TCriteria; 
        $Criteria_Metas = new TCriteria; 

	    $nClienteAtivo = 0;
	    $Criteria_CliAtivo = new TCriteria; 

	    $Criteria_CliAtivo->add(new TFilter('cliente_status', '=', 'A')); 

        if((isset($param['mes']) and !empty($param['mes'])) OR $mes->getValue())
        {
            if(isset($param['mes']) and !empty($param['mes'])){
                $ArrayCriteria['mes'] = $param['mes'];
            }else{
                $ArrayCriteria['mes'] = $mes->getValue();
            }    
            $Criteria_Metas->add(new TFilter('mes', '=', $ArrayCriteria['mes'])); 
            $Criteria_ClientesMes->add(new TFilter('mes', '=', $ArrayCriteria['mes'])); 

        }
        if((isset($param['ano']) and !empty($param['ano'])) OR $ano->getValue())
        {
            if(isset($param['ano']) and !empty($param['ano'])){
                $ArrayCriteria['ano'] = $param['ano'];
            }else{
                $ArrayCriteria['ano'] = $ano->getValue();
            }    
            $Criteria_Metas->add(new TFilter('ano', '=', $ArrayCriteria['ano'])); 
            $Criteria_ClientesMes->add(new TFilter('ano', '=', $ArrayCriteria['ano'])); 

        }
        if((isset($param['vendedor']) and !empty($param['vendedor'])) OR $vendedor->getValue())
        {
            if(isset($param['vendedor']) and !empty($param['vendedor'])){
                $ArrayCriteria['vendedor'] = $param['vendedor'];
            }else{
                $ArrayCriteria['vendedor'] = $vendedor->getValue();
            }    

            $Criteria_ClientesMes->add(new TFilter('vendedor_id', '=', $ArrayCriteria['vendedor'])); 
            $Criteria_Metas->add(new TFilter('vendedor_id', '=', $ArrayCriteria['vendedor'])); 
            $Criteria_CliAtivo->add(new TFilter('vendedor_id', '=', $ArrayCriteria['vendedor'])); 

        }

        TTransaction::open('erp_online');

        $repository = new TRepository('ViewBaseClienteMes'); 
        $oClientesMes = $repository->load($Criteria_ClientesMes);

        $repository = new TRepository('MetaVendedorMes'); 
        $oMetaClientes = $repository->load($Criteria_Metas);

        $oCli_Repo = new TRepository('ViewVendedorClienteStatus'); 
        $oClienteAtivo = $oCli_Repo->load($Criteria_CliAtivo);

        if ($oClientesMes)
        {
            $nClienteMes = 0;
            foreach ($oClientesMes as $oClienteMes)
            {
                $nClienteMes += 1;//$oClienteMes->cliente_id;
            }
        }

        if ($oClienteAtivo)
        {
            foreach ($oClienteAtivo as $oCliente)
            {
                $nClienteAtivo += $oCliente->quantidade;
            }
        }

        if ($oMetaClientes)
        {
            foreach ($oMetaClientes as $oMetaCliente)
            {
                $nPerCliente += $oMetaCliente->numero_cliente;
                $nPerFaturamento += $oMetaCliente->valor;
                $cTipoMeta = $oMetaCliente->tipo;
            }
        }

        if ($nClienteAtivo > 0){

            $nCobert =  round(($nClienteMes * 100)/$nClienteAtivo,2);

            if($nCobert >= 0 and $nCobert < 25 ){

                $cliente_nao_atendido->setColors('#FBB6B1', '#FFFFFF', '#F83000', '#FFFFFF');
                $cliente_nao_atendido->setIcon(new TImage('fas:battery-empty #FFFFFF'));

            }elseif($nCobert >= 25 and $nCobert < 50 ){
                $cliente_nao_atendido->setColors('#F1C40F', '#FFFFFF', '#F39C12', '#FFFFFF');
                $cliente_nao_atendido->setIcon(new TImage('fas:battery-quarter #FFFFFF'));

            }elseif($nCobert >= 50 and $nCobert < 75 ){
                $cliente_nao_atendido->setColors('#55EFC4', '#FFFFFF', '#00B894', '#FFFFFF');
                $cliente_nao_atendido->setIcon(new TImage('fas:battery-half #FFFFFF'));

            }elseif($nCobert >= 75 and $nCobert <= 100 ){
                $cliente_nao_atendido->setColors('#27AE60', '#FFFFFF', '#2ECC71', '#FFFFFF');
                $cliente_nao_atendido->setIcon(new TImage('fas:battery-three-quarters #FFFFFF'));

            }elseif($nCobert >= 100 ){
                $cliente_nao_atendido->setIcon(new TImage('fas:battery-full #FFFFFF'));
            }

            $cliente_nao_atendido->setTarget($nClienteAtivo, '#000000'
                , function($percentage, $target ){
                    return "{$percentage}% de ".$target;
                }
            );

        }         

        TTransaction::close();

        if ($nPerCliente > 0){
            $cliente_positivado->setTarget($nPerCliente, '#000000'
                , function($percentage, $target ){
                    return "{$percentage}% de ".$target;
                }
            );
        }

        if ($nPerFaturamento > 0){
            $quantidade_notas->setTarget($nPerFaturamento, '#000000'
                , function($percentage, $target ){
                    return "{$percentage}% de "."R$ " . number_format($target, 2, ",", ".");
                }
            );
        }

        if ($cTipoMeta == 'G'){

            unset($vendas_categoria);
            unset($vendas_categoria_column_cod_erp);
            unset($vendas_categoria_column_categoria);
            unset($vendas_categoria_column_vlr_liquido);

            $vendas_categoria = new BTableChart('vendas_categoria');
            $vendas_categoria_column_cod_erp = new BTableColumnChart('cod_erp', "Código", 'left','10%','asc');
            $vendas_categoria_column_categoria = new BTableColumnChart('categoria', "Categoria", 'left','30%');
            $vendas_categoria_column_vlr_liquido = new BTableColumnChart('vlr_liquido', "Realizado", 'right','20%');

            $vendas_categoria_column_vlr_liquido->setTransformer(function($value, $object, $row)
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

            $vendas_categoria_column_vlr_liquido->setTotal('sum', function($value, $object, $row)
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

            $vendas_categoria->setDatabase('erp_online');
            $vendas_categoria->setModel('ViewTotalCatogoriaMes');
            $vendas_categoria->setTitle("Vendas Categoria");
            $vendas_categoria->setSize('100%', 280);
            //$vendas_categoria->setColumns([$vendas_categoria_column_cod_erp,$vendas_categoria_column_categoria,$vendas_categoria_column_vlr_objetivo,$vendas_categoria_column_vlr_liquido,$vendas_categoria_column_perc_liquido]);
            $vendas_categoria->setColumns([$vendas_categoria_column_cod_erp,$vendas_categoria_column_categoria,$vendas_categoria_column_vlr_liquido]);
            $vendas_categoria->setCriteria($criteria_vendas_categoria);

            $vendas_categoria->setRowColorOdd('#F9F9F9');
            $vendas_categoria->setRowColorEven('#FFFFFF');
            $vendas_categoria->setFontRowColorOdd('#333333');
            $vendas_categoria->setFontRowColorEven('#333333');
            $vendas_categoria->setBorderColor('#DDDDDD');
            $vendas_categoria->setTableHeaderColor('#FFFFFF');
            $vendas_categoria->setTableHeaderFontColor('#333333');
            $vendas_categoria->setTableFooterColor('#FFFFFF');
            $vendas_categoria->setTableFooterFontColor('#333333');

        }

        $row1 = $this->form->addFields([new TLabel("Mês:", '#E91E63', '14px', null, '100%'),$mes],[new TLabel("Ano:", '#E91E63', '14px', null, '100%'),$ano],[new TLabel("Vendedor:", '#E91E63', '14px', null, '100%'),$vendedor],[new TLabel("  ", null, '14px', null, '100%'),$button_buscar]);
        $row1->layout = [' col-6 col-sm-6 col-md-2',' col-6 col-sm-6 col-md-2',' col-12 col-sm-6 col-md-6',' col-12 col-sm-4 col-md-2'];

        $row2 = $this->form->addFields([$quantidade_notas],[$cliente_positivado],[$devolucao],[$cliente_nao_atendido]);
        $row2->layout = [' col-12 col-sm-12 col-lg-3 col-md-12',' col-12 col-sm-12 col-lg-3 col-md-12',' col-12 col-sm-12 col-lg-3 col-md-12',' col-12 col-sm-12 col-lg-3 col-md-12'];

        $row3 = $this->form->addFields([$vendas_categoria]);
        $row3->layout = ['col-12 col-sm-12'];

        $row4 = $this->form->addFields([],[new TLabel("Atualizado em.:", null, '14px', null, '100%'),$atualizacao]);
        $row4->layout = [' col-12 col-sm-6 col-md-9',' col-12 col-sm-6 col-md-3'];

        if(!isset($param['mes']) && $mes->getValue())
        {
            $_POST['mes'] = $mes->getValue();
        }
        if(!isset($param['ano']) && $ano->getValue())
        {
            $_POST['ano'] = $ano->getValue();
        }
        if(!isset($param['vendedor']) && $vendedor->getValue())
        {
            $_POST['vendedor'] = $vendedor->getValue();
        }
        if(!isset($param['atualizacao']) && $atualizacao->getValue())
        {
            $_POST['atualizacao'] = $atualizacao->getValue();
        }

        $searchData = $this->form->getData();
        $this->form->setData($searchData);

        $filterVar = $searchData->vendedor;
        if($filterVar)
        {
            $criteria_quantidade_notas->add(new TFilter('view_total_catogoria_mes.vendedor_id', '=', $filterVar)); 
        }
        $filterVar = $searchData->mes;
        if($filterVar)
        {
            $criteria_quantidade_notas->add(new TFilter('view_total_catogoria_mes.mes', '=', $filterVar)); 
        }
        $filterVar = $searchData->ano;
        if($filterVar)
        {
            $criteria_quantidade_notas->add(new TFilter('view_total_catogoria_mes.ano', '=', $filterVar)); 
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
        $filterVar = $searchData->vendedor;
        if($filterVar)
        {
            $criteria_cliente_nao_atendido->add(new TFilter('view_base_cliente_mes.vendedor_id', '=', $filterVar)); 
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
            $criteria_vendas_categoria->add(new TFilter('view_total_catogoria_mes.vendedor_id', '=', $filterVar)); 
        }
        $filterVar = $searchData->ano;
        if($filterVar)
        {
            $criteria_vendas_categoria->add(new TFilter('view_total_catogoria_mes.ano', '=', $filterVar)); 
        }
        $filterVar = $searchData->mes;
        if($filterVar)
        {
            $criteria_vendas_categoria->add(new TFilter('view_total_catogoria_mes.mes', '=', $filterVar)); 
        }

        BChart::generate($quantidade_notas, $cliente_positivado, $devolucao, $cliente_nao_atendido, $vendas_categoria);

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

        if(isset($param['vendedor'])){
            $object->vendedor = $param['vendedor'];
        }else{
            $object->vendedor = $this->vendedor_id;
            $param['vendedor']= $this->vendedor_id;
        }

        if($this->supervisor == "S"){
            TDBCombo::enableField(self::$formName, 'vendedor');
        }else{
            TDBCombo::disableField(self::$formName, 'vendedor');
            if($this->vendedor_id > 0){
                $object->vendedor = $this->vendedor_id;
            }else{
                TToast::show("error", "Usuário não é Vendedor.", "center", "fas:info");
                return;
            }            
        }        

        TForm::sendData(self::$formName, $object);                

    } 

}

