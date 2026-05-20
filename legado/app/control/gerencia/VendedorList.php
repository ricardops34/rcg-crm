<?php

class VendedorList extends TPage
{
    private $form; // form
    private $datagrid; // listing
    private $pageNavigation;
    private $loaded;
    private $filter_criteria;
    private static $database = 'erp_online';
    private static $activeRecord = 'Vendedor';
    private static $primaryKey = 'id';
    private static $formName = 'form_VendedorList';
    private $showMethods = ['onReload', 'onSearch', 'onRefresh', 'onClearFilters', 'onGlobalSearch'];
    private $limit = 20;

    private $filtrarativo = false;
    private $filtrarbloqueado = false;

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
        $this->form->setFormTitle("Listagem de vendedores");
        $this->limit = 20;

        $nome = new TEntry('nome');
        $filtros_rapidos = new TRadioGroup('filtros_rapidos');


        $nome->setMaxLength(100);
        $filtros_rapidos->addItems(["ativo"=>"Ativo","bloqueado"=>"Bloqueados"]);
        $filtros_rapidos->setLayout('horizontal');
        $filtros_rapidos->setUseButton();
        $filtros_rapidos->setBreakItems(2);
        $nome->setSize('100%');
        $filtros_rapidos->setSize(120);

        $row1 = $this->form->addFields([new TLabel("Nome:", null, '14px', null, '100%'),$nome],[new TLabel("Situação:", null, '14px', null, '100%'),$filtros_rapidos]);
        $row1->layout = ['col-sm-6','col-sm-6'];

        // keep the form filled during navigation with session data
        $this->form->setData( TSession::getValue(__CLASS__.'_filter_data') );

        $btn_onsearch = $this->form->addAction("Buscar", new TAction([$this, 'onSearch']), 'fas:search #ffffff');
        $this->btn_onsearch = $btn_onsearch;
        $btn_onsearch->addStyleClass('btn-warning'); 

        // creates a Datagrid
        $this->datagrid = new TDataGrid;
        $this->datagrid->setId(__CLASS__.'_datagrid');

        $this->datagrid_form = new TForm('datagrid_'.self::$formName);
        $this->datagrid_form->onsubmit = 'return false';

        $this->datagrid = new BootstrapDatagridWrapper($this->datagrid);
        $this->filter_criteria = new TCriteria;

        $this->datagrid->style = 'width: 100%';
        $this->datagrid->setHeight(250);

        $column_cod_erp = new TDataGridColumn('cod_erp', "Código ERP", 'left');
        $column_nome_reduzido = new TDataGridColumn('nome_reduzido', "Nome Reduzido", 'left');
        $column_nome = new TDataGridColumn('nome', "Nome", 'left');
        $column_status_transformed = new TDataGridColumn('status', "Situação", 'center');

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
        $order_nome_reduzido = new TAction(array($this, 'onReload'));
        $order_nome_reduzido->setParameter('order', 'nome_reduzido');
        $column_nome_reduzido->setAction($order_nome_reduzido);
        $order_nome = new TAction(array($this, 'onReload'));
        $order_nome->setParameter('order', 'nome');
        $column_nome->setAction($order_nome);

        $this->datagrid->addColumn($column_cod_erp);
        $this->datagrid->addColumn($column_nome_reduzido);
        $this->datagrid->addColumn($column_nome);
        $this->datagrid->addColumn($column_status_transformed);

        $action_onDelete = new TDataGridAction(array('VendedorList', 'onDelete'));
        $action_onDelete->setUseButton(false);
        $action_onDelete->setButtonClass('btn btn-default btn-sm');
        $action_onDelete->setLabel("Excluir");
        $action_onDelete->setImage('fas:trash-alt #dd5a43');
        $action_onDelete->setField(self::$primaryKey);
        $action_onDelete->setDisplayCondition('VendedorList::PodeExcluir');

        $this->datagrid->addAction($action_onDelete);

        $action_onEdit = new TDataGridAction(array('VendedorForm', 'onEdit'));
        $action_onEdit->setUseButton(false);
        $action_onEdit->setButtonClass('btn btn-default btn-sm');
        $action_onEdit->setLabel("Editar");
        $action_onEdit->setImage('far:edit #478fca');
        $action_onEdit->setField(self::$primaryKey);
        $action_onEdit->setDisplayCondition('VendedorList::PodeEditar');

        $this->datagrid->addAction($action_onEdit);

        $action_GerarUsuario = new TDataGridAction(array('VendedorList', 'GerarUsuario'));
        $action_GerarUsuario->setUseButton(false);
        $action_GerarUsuario->setButtonClass('btn btn-default btn-sm');
        $action_GerarUsuario->setLabel("Usuário");
        $action_GerarUsuario->setImage('fas:user-check #000000');
        $action_GerarUsuario->setField(self::$primaryKey);
        $action_GerarUsuario->setDisplayCondition('VendedorList::PodeGerarUsuario');

        $this->datagrid->addAction($action_GerarUsuario);

        $action_Personificar = new TDataGridAction(array('VendedorList', 'Personificar'));
        $action_Personificar->setUseButton(false);
        $action_Personificar->setButtonClass('btn btn-default btn-sm');
        $action_Personificar->setLabel("Personificar");
        $action_Personificar->setImage('fas:user-lock #4CAF50');
        $action_Personificar->setField(self::$primaryKey);
        $action_Personificar->setDisplayCondition('VendedorList::PodePersonificar');
        $action_Personificar->setParameter('login', '{email}');

        $this->datagrid->addAction($action_Personificar);

        $action_onGerarSenha = new TDataGridAction(array('VendedorList', 'onGerarSenha'));
        $action_onGerarSenha->setUseButton(false);
        $action_onGerarSenha->setButtonClass('btn btn-default btn-sm');
        $action_onGerarSenha->setLabel("Gerar Senha");
        $action_onGerarSenha->setImage('fas:key #673AB7');
        $action_onGerarSenha->setField(self::$primaryKey);
        $action_onGerarSenha->setDisplayCondition('VendedorList::PodeSenha');

        $this->datagrid->addAction($action_onGerarSenha);

        $action_VendedorAtendimentoForm_onEdit = new TDataGridAction(array('VendedorAtendimentoForm', 'onEdit'));
        $action_VendedorAtendimentoForm_onEdit->setUseButton(false);
        $action_VendedorAtendimentoForm_onEdit->setButtonClass('btn btn-default btn-sm');
        $action_VendedorAtendimentoForm_onEdit->setLabel("Configurações");
        $action_VendedorAtendimentoForm_onEdit->setImage('fas:tools #000000');
        $action_VendedorAtendimentoForm_onEdit->setField(self::$primaryKey);
        $action_VendedorAtendimentoForm_onEdit->setDisplayCondition('VendedorList::PodeConfigurar');
        $action_VendedorAtendimentoForm_onEdit->setParameter('vendedor_id', '{id}');

        $this->datagrid->addAction($action_VendedorAtendimentoForm_onEdit);

        // create the datagrid model
        $this->datagrid->createModel();

        // creates the page navigation
        $this->pageNavigation = new TPageNavigation;
        $this->pageNavigation->enableCounters();
        $this->pageNavigation->setAction(new TAction(array($this, 'onReload')));
        $this->pageNavigation->setWidth($this->datagrid->getWidth());

        $panel = new TPanelGroup("Listagem de vendedores");
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

        $button_cadastrar = new TButton('button_button_cadastrar');
        $button_cadastrar->setAction(new TAction(['VendedorForm', 'onShow']), "Cadastrar");
        $button_cadastrar->addStyleClass('btn-default');
        $button_cadastrar->setImage('fas:plus #69aa46');

        $this->datagrid_form->addField($button_cadastrar);

        $btnShowCurtainFilters = new TButton('button_btnShowCurtainFilters');
        $btnShowCurtainFilters->setAction(new TAction(['VendedorList', 'onShowCurtainFilters']), "Filtros");
        $btnShowCurtainFilters->addStyleClass('btn-default');
        $btnShowCurtainFilters->setImage('fas:filter #000000');

        $this->datagrid_form->addField($btnShowCurtainFilters);

        $button_limpar_filtros = new TButton('button_button_limpar_filtros');
        $button_limpar_filtros->setAction(new TAction(['VendedorList', 'onClearFilters']), "Limpar filtros");
        $button_limpar_filtros->addStyleClass('btn-default');
        $button_limpar_filtros->setImage('fas:eraser #f44336');

        $this->datagrid_form->addField($button_limpar_filtros);

        $button_atualizar = new TButton('button_button_atualizar');
        $button_atualizar->setAction(new TAction(['VendedorList', 'onRefresh']), "Atualizar");
        $button_atualizar->addStyleClass('btn-default');
        $button_atualizar->setImage('fas:sync-alt #03a9f4');

        $this->datagrid_form->addField($button_atualizar);

        $button_ativos = new TButton('button_button_ativos');
        $button_ativos->setAction(new TAction(['VendedorList', 'onFiltrarAtivo']), "Ativos");
        $button_ativos->addStyleClass('btn-default');
        $button_ativos->setImage('fas:lock-open #009688');

        $this->datagrid_form->addField($button_ativos);

        $button_bloqueados = new TButton('button_button_bloqueados');
        $button_bloqueados->setAction(new TAction(['VendedorList', 'onFiltrarBloqueados']), "Bloqueados");
        $button_bloqueados->addStyleClass('btn-default');
        $button_bloqueados->setImage('fas:lock #F44336');

        $this->datagrid_form->addField($button_bloqueados);

        $head_left_actions->add($button_cadastrar);
        $head_left_actions->add($btnShowCurtainFilters);
        $head_left_actions->add($button_limpar_filtros);
        $head_left_actions->add($button_atualizar);
        $head_left_actions->add($button_ativos);
        $head_left_actions->add($button_bloqueados);

        $this->datagrid_form->add($this->datagrid);

        $this->btnShowCurtainFilters = $btnShowCurtainFilters;

        //$button_cadastrar->setDisplayCondition('VendedorList::PodeEditar');
        //$dropdown_button_exportar->setDisplayCondition('VendedorList::PodeExportar');

        // vertical box container
        $container = new TVBox;
        $container->style = 'width: 100%';
        if(empty($param['target_container']))
        {
            $container->add(TBreadCrumb::create(["Gerencia","Vendedores"]));
        }

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
                $object = new Vendedor($key, FALSE); 

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
                if( TSession::getValue('login') == 'admin'){
                    return true;  
                }
            }

            return false;
        }
        catch (Exception $e) 
        {
            new TMessage('error', $e->getMessage());    
        }
    }
    public static function PodeEditar($object)
    {
        try 
        {
            if($object)
            {
                return true;
            }

            return false;
        }
        catch (Exception $e) 
        {
            new TMessage('error', $e->getMessage());    
        }
    }
    public function GerarUsuario($param = null) 
    {
        try 
        {
            if($param['id']){
                $id = $param['id'];
                $user_id = -1;
                TTransaction::open(self::$database);
                $oVendedor = Vendedor::find( $id );
                TTransaction::close();
                if($oVendedor){
                    if($oVendedor->status == "A"){
                        if(!empty($oVendedor->email) ){

                            $cNome  = ltrim(rtrim($oVendedor->nome_reduzido));
                            $cEmail = strtolower(ltrim(rtrim($oVendedor->email)));
                            $nSenha = md5('Senha@'+date('Y')+date('m'));
                            if(!empty($cNome) and !empty($cEmail)){

                                TTransaction::open('permission');

                                $oBusca = SystemUsers::where('login', '=',  $cEmail )
                                ->first(); 

                                if($oBusca){

                                }else{

                                    $oBusca = SystemUsers::where('login', '=', 'vendedor_modelo' )
                                    ->first(); 

                                    if($oBusca){

                                        $oNewuser = new SystemUsers($oBusca->id);
                                        $oNewuser->cloneUser();
                                        $oNewuser->store();
                                        $user_id = $oNewuser->id;
                                        if($oNewuser){
                                            $oUsuario = new SystemUsers($oNewuser->id);
                                            $oUsuario->login = $cEmail;
                                            $oUsuario->password = $nSenha;
                                            $oUsuario->name  = $cNome;
                                            $oUsuario->email = $cEmail;
                                            $oUsuario->active = 'Y';
                                            $oUsuario->store();

                                        }
                                    }
                                }

                                TTransaction::close();

                            }

                        }else{
                            echo 'erro usuario';
                        }
                    }else{
                       TToast::show("error", "Vendedor bloqueado não tem acesso ao sistema", "center", "fas:info"); 
                    }
                }

                if($user_id > 0 ){
                    TTransaction::open(self::$database);
                    $oVendedor = Vendedor::find( $id );
                    if($oVendedor)
                    {
                        $oVendedor->system_users_id = $user_id;
                        $oVendedor->store();    
                    }
                    TTransaction::close();
                }
            }
            $this->onReload($param);
            //</autoCode>
        }
        catch (Exception $e) 
        {
            new TMessage('error', $e->getMessage());    
        }
    }
    public static function PodeGerarUsuario($object)
    {
        try 
        {
            if($object)
            {
                TTransaction::open(self::$database);
                $oVendedor = Vendedor::find( $object->id );
                TTransaction::close();
                if($oVendedor){
                    if($oVendedor->status <> 'B' and empty($oVendedor->system_users_id)){
                        //var_dump($oVendedor->system_users_id);
                        return true;
                    }
                }
            }        
            return false;
        }
        catch (Exception $e) 
        {
            new TMessage('error', $e->getMessage());    
        }
    }
    public function Personificar($param = null) 
    {
        try 
        {

            $login_impersonated = TSession::getValue('login');

            TTransaction::open('erp_online');
            $oVendedor = Vendedor::where('status', '=', 'A')->where('email', '=', $param['login'])->first();
            TTransaction::close();

            if($oVendedor)
            {

                TTransaction::open('permission');
                TSession::regenerate();
                $user = SystemUsers::validate( $param['login'] );
                ApplicationAuthenticationService::loadSessionVars($user);
                SystemAccessLogService::registerLogin(true, $login_impersonated);

                TTransaction::close();

                TSession::setValue('supervisor_id'  , $oVendedor->supervisor_id);
                TSession::setValue('vendedor_id'    , $oVendedor->id);
                TSession::setValue('supervisor'     , $oVendedor->supervisor);
                TSession::setValue('vendedor'       , $oVendedor->vendedor);

                AdiantiCoreApplication::gotoPage('EmptyPage');

            }else{

                new TMessage('info', "Cadastro do vendedor incompleto.");
            }

            //</autoCode>
        }
        catch (Exception $e) 
        {
            new TMessage('error', $e->getMessage());    
        }
    }
    public static function PodePersonificar($object)
    {
        try 
        {
            if($object)
            {
                TTransaction::open(self::$database);
                $oVendedor = Vendedor::find( $object->id );
                TTransaction::close();
                if($oVendedor){
                    if($oVendedor->status == 'A' and $oVendedor->system_users_id > 0){
                        return true;
                    }
                }
            }

            return false;
        }
        catch (Exception $e) 
        {
            new TMessage('error', $e->getMessage());    
        }
    }
    public function onGerarSenha($param = null) 
    {

        if(isset($param['key'])){
            $id = $param['key'];
            $envia = false;
            if(isset($param['gerasenha']) && $param['gerasenha'] == 1)
            {
                try 
                {
                    TTransaction::open(self::$database);
                    $oVendedor = Vendedor::find( $id);
                    TTransaction::close();
                    if($oVendedor){
                        if(empty($oVendedor->email)){
                            TToast::show("error", "Vendedor [".$oVendedor->nome_reduzido. "] com e-mail inválido.", "topCenter", "");
                        }else{
                            $caracteres_q_farao_parte = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ@#$abcdefghijklmnopqrstuvwxyz0123456789';
                            $password = substr( str_shuffle($caracteres_q_farao_parte), 0, 6 );     

                            TTransaction::open('permission');
                            $oUsuario = new SystemUsers($oVendedor->system_users_id);
                            if($oUsuario){
                                $oUsuario->password = md5($password);
                                $oUsuario->store();
                                $envia = true;
                            }
                            TTransaction::close(); 

                            if($envia){
                                $type = 'html'; // ou = 'text'
                                $mensagem = "";
                                $mensagem .= "<h2>Ola ".$oVendedor->nome_reduzido.'</h2>';
                                $mensagem .= "<br>";
                                $mensagem .= "<br>";
                                $mensagem .= "<br>";
                                $mensagem .= "Seu usuário é: <b>".$oVendedor->email."</b>";
                                $mensagem .= "<br>";
                                $mensagem .= "<br>";
                                $mensagem .= "Sua nova senha é: <b>".$password."</b>";
                                $mensagem .= "<br>";
                                $mensagem .= "<br>";
                                $mensagem .= "<br>";
                                $mensagem .= "acesse link para o <a href='https://rcgdist.com.br/sistema/index.php'>sistema</a> ou pelo endereço: https://rcgdist.com.br/sistema/";
                                $mensagem .= "<br>";
                                $mensagem .= "<br>";
                                $mensagem .= "<b>È recomendado que troque esta senha no primero acesso ao sistema.</b>";
                                $mensagem .= "<br>";
                                $mensagem .= "<br>";
                                $mensagem .= "Em caso de duvidas entre em contado com seu gerente ou supervisor direto.<br>";
                                $mensagem .= "Esta alteração foi solicitada por: ".TSession::getValue('username')." em ".date('d-m-Y H:i');

                                $assunto = "Solicitação de Acesso ao Sistema RCG";

                                MailService::send([$oVendedor->email,'naoresponta@rcgdist.com.br'], $assunto, $mensagem, $type);
                            }
                        }
                    }
            //</autoCode>

                }
                catch (Exception $e) 
                {
                    new TMessage('error', $e->getMessage());    
                }

            }else{
                // define the delete action
                $action = new TAction(array($this, 'onGerarSenha'));
                $action->setParameters($param); // pass the key paramseter ahead
                $action->setParameter('gerasenha', 1);
                // shows a dialog to the user
                new TQuestion("Deseja redefinir a senha do Usuário?", $action);   
            }
        }

    }
    public static function PodeSenha($object)
    {
        try 
        {
            if($object)
            {
                TTransaction::open(self::$database);
                $oVendedor = Vendedor::find( $object->id );
                TTransaction::close();
                if($oVendedor){
                    if($oVendedor->status == 'A' and $oVendedor->system_users_id > 0){
                        return true;
                    }
                }
            }

            return false;
        }
        catch (Exception $e) 
        {
            new TMessage('error', $e->getMessage());    
        }
    }
    public static function PodeConfigurar($object)
    {
        try 
        {
            if($object)
            {
                TTransaction::open(self::$database);
                $oVendedor = Vendedor::find( $object->id );
                TTransaction::close();
                if($oVendedor){
                    if($oVendedor->status == 'A' and $oVendedor->system_users_id > 0){
                        return true;
                    }
                }
            }

            return false;
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
            $page->setProperty('page-name', 'VendedorListSearch');
            $page->setProperty('page_name', 'VendedorListSearch');
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
    public function onFiltrarAtivo($param = null) 
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
    public function onFiltrarBloqueados($param = null) 
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

    /**
     * Register the filter in the session
     */
    public function onSearch($param = null)
    {
        $data = $this->form->getData();
        $filters = [];

        TSession::setValue(__CLASS__.'_filter_data', NULL);
        TSession::setValue(__CLASS__.'_filters', NULL);

        if (isset($data->nome) AND ( (is_scalar($data->nome) AND $data->nome !== '') OR (is_array($data->nome) AND (!empty($data->nome)) )) )
        {

            $filters[] = new TFilter('nome', 'like', "%{$data->nome}%");// create the filter 
        }

        if($this->filtrarativo or $data->filtros_rapidos == "ativo")
        {
            $data->filtros_rapidos = "ativo";
            $filters[] = new TFilter('status', '=', "A");// create the filter 
        }

        if($this->filtrarbloqueado or $data->filtros_rapidos == "bloqueado")
        {
            $data->filtros_rapidos = "bloqueado";
            $filters[] = new TFilter('status', '=', "B");// create the filter 
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

            // creates a repository for Vendedor
            $repository = new TRepository(self::$activeRecord);

            $criteria = clone $this->filter_criteria;

            if (empty($param['order']))
            {
                $param['order'] = 'nome_reduzido';    
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

        TEntry::disableField(self::$formName, 'Exportar');
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

        $object = new Vendedor($id);

        $row = $list->datagrid->addItem($object);
        $row->id = "row_{$object->id}";

        if($openTransaction)
        {
            TTransaction::close();    
        }

        TDataGrid::replaceRowById(__CLASS__.'_datagrid', $row->id, $row);
    }

    public static function PodeExportar($object) 
    {
        try 
        {
            //if($object)
            //{
                //return true;
            //}

            return false;
        }
        catch (Exception $e) 
        {
            new TMessage('error', $e->getMessage());    
        }
    }

}

