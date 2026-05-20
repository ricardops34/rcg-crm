<?php
/**
 * AtendimentoCalendarForm Form
 * @author  <your name here>
 */
class AtendimentoCalendarFormView extends TPage
{
    private $fc;

    /**
     * Page constructor
     */
    public function __construct($param = null)
    {
        parent::__construct();

        $this->fc = new TFullCalendar(date('Y-m-d'), 'month');
        $this->fc->enableDays([1,2,3,4,5,6]);
        $this->fc->setReloadAction(new TAction(array($this, 'getEvents'), $param));
        $this->fc->setDayClickAction(new TAction(array('AtendimentoCalendarForm', 'onStartEdit')));
        $this->fc->setEventClickAction(new TAction(array('AtendimentoCalendarForm', 'onEdit')));
        $this->fc->setEventUpdateAction(new TAction(array('AtendimentoCalendarForm', 'onUpdateEvent')));
        $this->fc->setCurrentView('agendaWeek');
        $this->fc->setTimeRange('07:00', '19:00');
        $this->fc->enablePopover('{atendimento_tipo->descricao} ', "Cliente: {nome} <br>
Vendedor: {vendedor->nome_reduzido} 
{valor_nota} 
");
        $this->fc->setOption('slotTime', "00:15:00");
        $this->fc->setOption('slotDuration', "00:15:00");
        $this->fc->setOption('slotLabelInterval', 15);

        $this->fc->id = 'atendimento';
        //TToast::show("error", "__construct", "center", "fas:info");

        $vendedor_id = TSession::getValue('vendedor_id' );

        if(isset($vendedor_id)){

            TTransaction::open(MAIN_DATABASE);
            $oVendedor = Vendedor::find( $vendedor_id );
            $oVendedorAtendimento = VendedorAtendimento::where('vendedor_id', '=', $vendedor_id)->first();

            TTransaction::close();

            if($oVendedor){

            }else{

                TToast::show("error", "Usuário não é Vendedor.", "center", "fas:info");
                return;
            }
            if($oVendedorAtendimento){
                $aDias = explode(",",$oVendedorAtendimento->dias_semana);

                //$this->fc->setTimeRange($oVendedorAtendimento->inicial, $oVendedorAtendimento->final);
                $this->fc->enableDays($aDias);
                //"agendaWeek" = Semana //month = Mes //agendaDay = dia //listWeek = Agenda;
                if ($oVendedorAtendimento->tipo == "S"){
                    $this->fc->setCurrentView("agendaWeek");
                }elseif ($oVendedorAtendimento->tipo == "M"){
                    $this->fc->setCurrentView("month");
                }elseif ($oVendedorAtendimento->tipo == "D"){
                    $this->fc->setCurrentView("agendaDay");
                }elseif ($oVendedorAtendimento->tipo == "A"){
                    $this->fc->setCurrentView("listWeek");
                }  
            }

        }else{
            TToast::show("error", "Usuário não é Vendedor.", "center", "fas:info");
            return;
        }

        $codigo = new TEntry('codigo');
        $codigo->setMaxLength(100);
        $codigo->setSize('100%');

        $razao = new TEntry('razao');
        $razao->setMaxLength(100);
        $razao->setSize('100%');

        $criteria_atendimento_tipo_id = new TCriteria();
        $criteria_atendimento_tipo_id->add(new TFilter('atendimento', '=', 'S'));

        $atendimento_tipo_id = new TDBCombo('atendimento_tipo_id', 'erp_online', 'AtendimentoTipo', 'id', '{descricao}','id asc' , $criteria_atendimento_tipo_id );
        $atendimento_tipo_id->addValidation("Atendimento tipo id", new TRequiredValidator()); 
	    $atendimento_tipo_id->enableSearch();
	    $atendimento_tipo_id->setSize('100%');

        $onFiltrar = new TButton('buscar');
        $onFiltrar->setAction(new TAction([$this, 'onFiltrar']), 'Buscar');
        $onFiltrar->addStyleClass('btn-default');
        $onFiltrar->setImage('fas:search #000000');

        $formfil = new BootstrapFormBuilder('meuForm');
        $formfil->addFields([new TLabel("Codigo:", null, '14px', null, '100%'),$codigo],[new TLabel("Razão", null, '14px', null, '100%'),$razao],[new TLabel("Tipo", null, '14px', null, '100%'),$atendimento_tipo_id],[new TLabel(" ", null, '14px', null, '100%'),$onFiltrar]);
        $formfil->layout = [' col-sm-2',' col-sm-4',' col-sm-4',' col-sm-2']; 

        parent::add($formfil);

        parent::add( $this->fc );
    }

    /**
     * Output events as an json
     */
    public static function getEvents($param=NULL)
    {
        $return = array();
        try
        {
            TTransaction::open('erp_online');

            $criteria = new TCriteria(); 

            $criteria->add(new TFilter('horario_inicial', '<=', substr($param['end'], 0, 10).' 23:59:59'));
            $criteria->add(new TFilter('horario_final', '>=', substr($param['start'], 0, 10).' 00:00:00'));

            if(TSession::getValue('filtro_calendario_codigo'))
            {
                if(!empty(TSession::getValue('filtro_calendario_codigo'))){
                    $codigo_cliente = TSession::getValue('filtro_calendario_codigo');
                    $criteria->add(new TFilter('cliente_id', '=', "(SELECT id FROM cliente WHERE cod_erp in ({$codigo_cliente}))" ));
                }
            }

            if(TSession::getValue('filtro_calendario_razao'))
            {
                if(!empty(TSession::getValue('filtro_calendario_razao'))){
                    $razao_cliente = TSession::getValue('filtro_calendario_razao');
                    //$criteria->add(new TFilter('nome', 'in', "(SELECT razao FROM cliente WHERE razao like '%".ltrim(rtrim($razao_cliente))."%')" ));
                    $criteria->add(new TFilter('nome', 'like', '%'.ltrim(rtrim($razao_cliente)).'%' ));

                }
            }

            if(TSession::getValue('filtro_calendario_atendimento_tipo_id'))
            {
                if(!empty(TSession::getValue('filtro_calendario_atendimento_tipo_id'))){
                    $atendimento_tipo_id = TSession::getValue('filtro_calendario_atendimento_tipo_id');
                    $criteria->add(new TFilter('atendimento_tipo_id', '=', $atendimento_tipo_id ));
                }
            }

            $vendedor_id = TSession::getValue('vendedor_id');
            $oVendedor = Vendedor::find( $vendedor_id );
            if($oVendedor){
                if($oVendedor->supervisor == 'N'){
                    $criteria->add(new TFilter('vendedor_id', '=', $vendedor_id));
                }
            }

            $events = Atendimento::getObjects($criteria);

            if ($events)
            {
                foreach ($events as $event)
                {
                    $event_array = $event->toArray();
                    $event_array['start'] = str_replace( ' ', 'T', $event_array['horario_inicial']);
                    $event_array['end'] = str_replace( ' ', 'T', $event_array['horario_final']);
                    $event_array['id'] = $event->id;
                    $event_array['color'] = $event->render("{atendimento_tipo->cor}");
                    $event_array['title'] = TFullCalendar::renderPopover($event->render("{atendimento_tipo->descricao}"), $event->render("{atendimento_tipo->descricao} "), $event->render("Cliente: {nome} <br>
Vendedor: {vendedor->nome_reduzido} 
{valor_nota} 
"));

                    if($event_array['vendedor_id'] == $vendedor_id){

                    }else{

                        //$event_array['color'] = '#808080';

                    }
                    //var_dump($event);

                    $return[] = $event_array;
                }
            }
            TTransaction::close();
            echo json_encode($return);
        }
        catch (Exception $e)
        {
            new TMessage('error', $e->getMessage());
        }
    }

    /**
     * Reconfigure the callendar
     */
    public function onReload($param = null)
    {
        if (isset($param['view']))
        {
            $this->fc->setCurrentView($param['view']);
        }

        if (isset($param['date']))
        {
            $this->fc->setCurrentDate($param['date']);
        }
    }

    public function onFiltrar($param = null) //static 
    {
        TSession::delValue('filtro_calendario_codigo', null);
        TSession::delValue('filtro_calendario_razao', null);
        TSession::delValue('filtro_calendario_atendimento_tipo_id', null);

        if(!empty($param['codigo']))
        {
            TSession::setValue('filtro_calendario_codigo', $param['codigo']);
        }

        if(!empty($param['razao']))
        {
            TSession::setValue('filtro_calendario_razao', $param['razao']);
        }

        if(!empty($param['atendimento_tipo_id']))
        {
            TSession::setValue('filtro_calendario_atendimento_tipo_id', $param['atendimento_tipo_id']);
        }

        TScript::create(" $('#orcamento').fullCalendar('refetchEvents'); ");

        //$pageParam = [];

        $this->onReload();
        //TToast::show('success', "Integração Cancelada", 'topRight', 'far:check-circle');
        //TApplication::loadPage('AtendimentoCalendarForm', 'onReload', $pageParam);

    }

}

