<?php

class DashboardRegiao extends TPage
{
    protected $form;
    private $formFields = [];
    private static $database = '';
    private static $activeRecord = '';
    private static $primaryKey = '';
    private static $formName = 'form_DashboardRegiao';

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

        $criteria_regiao = new TCriteria();
        $criteria_total_regiao = new TCriteria();

        $this->supervisor = TSession::getValue("supervisor");
        $this->vendedor_id= TSession::getValue("vendedor_id");

        if($this->supervisor == 'S'){
        }else{
            TToast::show("error", "Usuário não é Supervisor.", "center", "fas:info");
            return;
        }

        $mes = new TCombo('mes');
        $ano = new TCombo('ano');
        $regiao = new TDBCombo('regiao', 'erp_online', 'RegiaoCliente', 'id', '{descricao}','descricao asc' , $criteria_regiao );
        $button_buscar = new TButton('button_buscar');
        $total_regiao = new BTableChart('total_regiao');
        $atualizacao = new TDateTime('atualizacao');

        $mes->setChangeAction(new TAction([$this,'onChangeMes']));

        $mes->addValidation("Mês", new TRequiredValidator()); 
        $ano->addValidation("Ano", new TRequiredValidator()); 

        $button_buscar->setAction(new TAction(['DashboardRegiao', 'onShow']), "Buscar");
        $button_buscar->addStyleClass('btn-success');
        $button_buscar->setImage('fas:search #FFFFFF');
        $atualizacao->setEditable(false);
        $atualizacao->setMask('dd/mm/yyyy hh:ii');
        $atualizacao->setDatabaseMask('yyyy-mm-dd hh:ii');
        $ano->addItems(TempoService::getAnos());
        $mes->addItems(TempoService::getMeses());

        $mes->setValue($param['mes'] ?? date('m'));
        $ano->setValue($param['ano'] ?? date('Y'));
        $atualizacao->setValue(SisFunction::GetParm('sis_update',' ',null));

        $mes->enableSearch();
        $ano->enableSearch();
        $regiao->enableSearch();

        $mes->setSize('100%');
        $ano->setSize('100%');
        $regiao->setSize('100%');
        $atualizacao->setSize('100%');

        $total_regiao_column_regiao_descricao = new BTableColumnChart('regiao_descricao', "Vendedor", 'left','30%');
        $total_regiao_column_mes = new BTableColumnChart('mes', "Região", 'left','20%');
        $total_regiao_column_ano = new BTableColumnChart('ano', "Mês ", 'left','5%');
        $total_regiao_column_vlr_liquido = new BTableColumnChart('vlr_liquido', "Realizado", 'right','10%');
        $total_regiao_column_vlr_liquido->setTotal('sum', function($value, $object, $row)
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
        $total_regiao_column_vlr_liquido->setTransformer(function($value, $object, $row)
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

        $total_regiao->setDatabase('erp_online');
        $total_regiao->setModel('ViewVendaRegiaoMes');
        $total_regiao->setTitle("");
        $total_regiao->setSize('100%', 280);
        $total_regiao->setColumns([$total_regiao_column_regiao_descricao,$total_regiao_column_mes,$total_regiao_column_ano,$total_regiao_column_vlr_liquido]);
        $total_regiao->setCriteria($criteria_total_regiao);

        $total_regiao->setRowColorOdd('#F9F9F9');
        $total_regiao->setRowColorEven('#FFFFFF');
        $total_regiao->setFontRowColorOdd('#333333');
        $total_regiao->setFontRowColorEven('#333333');
        $total_regiao->setBorderColor('#DDDDDD');
        $total_regiao->setTableHeaderColor('#FFFFFF');
        $total_regiao->setTableHeaderFontColor('#333333');
        $total_regiao->setTableFooterColor('#FFFFFF');
        $total_regiao->setTableFooterFontColor('#333333');

        /*
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
        */

        $row1 = $this->form->addFields([new TLabel("Mês:", '#E91E63', '14px', null, '100%'),$mes],[new TLabel("Ano:", '#E91E63', '14px', null, '100%'),$ano],[new TLabel("Região:", '#E91E63', '14px', null, '100%'),$regiao],[new TLabel("  ", null, '14px', null, '100%'),$button_buscar]);
        $row1->layout = ['col-6 col-sm-6 col-md-2','col-6 col-sm-6 col-md-2',' col-12 col-sm-6 col-md-6','col-12 col-sm-6 col-md-2'];

        $row2 = $this->form->addFields([$total_regiao]);
        $row2->layout = ['col-12 col-sm-12'];

        $row3 = $this->form->addFields([],[new TLabel("Atualizado em:", null, '14px', null, '100%'),$atualizacao]);
        $row3->layout = [' col-sm-9','col-3 col-sm-3 col-md-3'];

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

        $filterVar = $searchData->mes;
        if($filterVar)
        {
            $criteria_total_regiao->add(new TFilter('view_venda_regiao_mes.mes', '=', $filterVar)); 
        }
        $filterVar = $searchData->ano;
        if($filterVar)
        {
            $criteria_total_regiao->add(new TFilter('view_venda_regiao_mes.ano', '=', $filterVar)); 
        }
        $filterVar = $searchData->regiao;
        if($filterVar)
        {
            $criteria_total_regiao->add(new TFilter('view_venda_regiao_mes.regiao_id', '=', $filterVar)); 
        }

        BChart::generate($total_regiao);

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

        if($this->supervisor == "S"){

        }else{
            TToast::show("error", "Usuário não é supervisor.", "center", "fas:info");
            return;
        }        

        TForm::sendData(self::$formName, $object);                

    } 

}

