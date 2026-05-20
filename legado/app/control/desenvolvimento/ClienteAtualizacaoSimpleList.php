<?php

class ClienteAtualizacaoSimpleList extends TPage
{

    private $datagrid; // listing
    private $pageNavigation;
    private $loaded;
    private static $database = 'erp_online';
    private static $activeRecord = 'ClienteAtualizacao';
    private static $primaryKey = 'id';
    private static $formName = 'formList_ClienteAtualizacao';
    private $limit = 20;

    public function __construct($param = null)
    {
        parent::__construct();

        if(!empty($param['target_container']))
        {
            $this->adianti_target_container = $param['target_container'];
        }

        $this->limit = 10;

        // creates a Datagrid
        $this->datagrid = new TDataGrid;
        $this->datagrid->setId(__CLASS__.'_datagrid');

        $this->datagrid_form = new TForm('datagrid_'.self::$formName);
        $this->datagrid_form->onsubmit = 'return false';

        $this->datagrid = new BootstrapDatagridWrapper($this->datagrid);

        $this->datagrid->disableDefaultClick();
        $this->datagrid->style = 'width: 100%';
        $this->datagrid->setHeight(320);

        $column_razao = new TDataGridColumn('razao', "Razão Social", 'left');
        $column_dt_inclusao_transformed = new TDataGridColumn('dt_inclusao', "Data Consulta", 'left');

        $column_dt_inclusao_transformed->setTransformer(function($value, $object, $row, $cell = null, $last_row = null)
        {
            if(!empty(trim((string) $value)))
            {
                try
                {
                    $date = new DateTime($value);
                    return $date->format('d/m/Y H:i');
                }
                catch (Exception $e)
                {
                    return $value;
                }
            }
        });        

        $this->datagrid->addColumn($column_razao);
        $this->datagrid->addColumn($column_dt_inclusao_transformed);

        $action_onShow = new TDataGridAction(array('ClienteAtualizacaoFormView', 'onShow'));
        $action_onShow->setUseButton(true);
        $action_onShow->setButtonClass('btn btn-default btn-sm');
        $action_onShow->setLabel("Visualizar");
        $action_onShow->setImage('fas:print #000000');
        $action_onShow->setField(self::$primaryKey);

        $action_onShow->setParameter('key', '{id}');

        $this->datagrid->addAction($action_onShow);

        // create the datagrid model
        $this->datagrid->createModel();

        $panel = new TPanelGroup();
        $panel->datagrid = 'datagrid-container';
        $this->datagridPanel = $panel;
        $this->datagrid_form->add($this->datagrid);
        $panel->add($this->datagrid_form);

        $panel->getBody()->class .= ' table-responsive';

        $headerActions = new TElement('div');
        $headerActions->class = ' datagrid-header-actions ';
        $headerActions->style = 'justify-content: space-between;';

        $head_left_actions = new TElement('div');
        $head_left_actions->class = ' datagrid-header-actions-left-actions ';

        $head_right_actions = new TElement('div');
        $head_right_actions->class = ' datagrid-header-actions-left-actions ';

        $headerActions->add($head_left_actions);
        $headerActions->add($head_right_actions);

        $panel->getBody()->insert(0, $headerActions);

        $button_atualizar = new TButton('button_button_atualizar');
        $button_atualizar->setAction(new TAction(['ClienteAtualizacaoSimpleList', 'onRefresh']), "Atualizar");
        $button_atualizar->addStyleClass('btn-default');
        $button_atualizar->setImage('fas:sync-alt #03a9f4');

        $this->datagrid_form->addField($button_atualizar);

        $button_consulta_dados_receita_federal = new TButton('button_button_consulta_dados_receita_federal');
        $button_consulta_dados_receita_federal->setAction(new TAction(['ClienteAtualizacaoSimpleList', 'ConsultaRFB']), "Consulta Dados Receita Federal");
        $button_consulta_dados_receita_federal->addStyleClass('btn-success');
        $button_consulta_dados_receita_federal->setImage('fas:cloud-download-alt #FFFFFF');
        $button_consulta_dados_receita_federal->getAction()->setParameter("cliente_id", $param["cliente_id"] ?? "");

        $this->datagrid_form->addField($button_consulta_dados_receita_federal);

        $head_left_actions->add($button_atualizar);
        $head_left_actions->add($button_consulta_dados_receita_federal);

        // vertical box container
        $container = new TVBox;
        $container->style = 'width: 100%';
        if(empty($param['target_container']))
        {
            $container->add(TBreadCrumb::create(["Desenvolvimento","ClienteRFB"]));
        }

        $container->add($panel);

        parent::add($container);

    }

    public function onRefresh($param = null) 
    {
        $this->onReload([]);
    }
    public function ConsultaRFB($param = null) 
    {
        try 
        {
            TTransaction::open('erp_online');
            if(isset($param['cliente_id'])){

                $oCliente = Cliente::find( $param['cliente_id'] );

                if($oCliente){
                    if($oCliente->tipo == "F"){

                         TToast::show("info", "Somente Pessoa Jurídica pode ser consultada na RFB!", 'topRight', 'far:check-circle');

                    }elseif($oCliente->tipo == "J"){

                        if(isset($oCliente->cnpj_cpf)){
                            $cnpj = $oCliente->cnpj_cpf;
                            $dadosCnpj = BuilderCNPJService::getFull($cnpj);

                            if($dadosCnpj)
                            {

                                //var_dump($oSimples);

                                $oEstabelecimento = $dadosCnpj->estabelecimento;

                                //$oAtividades  = $oEstabelecimento->atividades_secundarias;
                                $oAtividades = $oEstabelecimento->atividade_principal;

                                $oIEs = $oEstabelecimento->inscricoes_estaduais;

                                $oSituacao = SituacaoCadastral::where('descricao', '=', $oEstabelecimento->situacao_cadastral)
                                            ->first();

                                if($oSituacao){

                                }else{
                                    $oSituacao = new SituacaoCadastral();
                                    $oSituacao->descricao = $oEstabelecimento->situacao_cadastral;
                                    $oSituacao->store();
                                }

                                $dt_atu  = date('Y-m-d H:i:s',strtotime($dadosCnpj->estabelecimento->atualizado_em));
                                $oClienteAtualizacao = ClienteAtualizacao::where('cnpj_cpf', '=', $cnpj)
                                                    ->where('atualizado_em', '=', $dt_atu)
                                                    ->first();

                                if($oClienteAtualizacao){

                                    TToast::show("info", "Os dados já estão Atualizados. API BuilderCNPJService", 'topRight', 'far:check-circle');

                                }else{

                                    $oCnae = Cnae::where('cod_erp', '=', $oAtividades->id)
                                        ->first();

                                    if($oCnae){

                                    }else{
                                        $oCnae = new Cnae();
                                        $oCnae->cod_erp = $oAtividades->id;
                                        $oCnae->secao   = $oAtividades->secao;
                                        $oCnae->divisao = $oAtividades->divisao;
                                        $oCnae->grupo   = $oAtividades->grupo;
                                        $oCnae->classe  = $oAtividades->classe;
                                        $oCnae->subclasse = $oAtividades->subclasse;
                                        $oCnae->descricao = $oAtividades->descricao;
                                        $oCnae->store();
                                    }

                                    $oClienteAtualizacao = new ClienteAtualizacao();
                                    $oClienteAtualizacao->cliente_id = $oCliente->id;
                                    $oClienteAtualizacao->situacao_cadastral_id = $oSituacao->id;

                                    $oClienteAtualizacao->atividade_principal_id = $oCnae->id;
                                    $oClienteAtualizacao->tipo = $oEstabelecimento->tipo;
                                    $oClienteAtualizacao->capital_social = (double) $dadosCnpj->capital_social;

                                    $oSimples = $dadosCnpj->simples;

                                    if($oSimples){
                                        //var_dump($oSimples->simples);
                                        $oClienteAtualizacao->simples = $oSimples->simples; 
                                        $oClienteAtualizacao->mei = $oSimples->mei; 
                                    }                                

                                    $oPorte = $dadosCnpj->porte;
                                    if($oPorte){
                                        $oClienteAtualizacao->porte = $oPorte->descricao;
                                    }

                                    $oNatureza = $dadosCnpj->natureza_juridica;
                                    if($oNatureza){
                                        $oClienteAtualizacao->natureza_juridica = $oNatureza->descricao;
                                    }

                                    $oClienteAtualizacao->razao = $dadosCnpj->razao_social;
                                    $oClienteAtualizacao->fantasia = $dadosCnpj->nome_fantasia;
                                    $oClienteAtualizacao->cnpj_cpf = $oEstabelecimento->cnpj;
                                    $oClienteAtualizacao->tipo_logradouro = $oEstabelecimento->tipo_logradouro;
                                    $oClienteAtualizacao->logradouro = $oEstabelecimento->logradouro;
                                    $oClienteAtualizacao->numero = $oEstabelecimento->numero;
                                    $oClienteAtualizacao->complemento = $oEstabelecimento->complemento;
                                    $oClienteAtualizacao->bairro = $oEstabelecimento->bairro;
                                    $oClienteAtualizacao->cep = $oEstabelecimento->cep;

                                    $oPais = Pais::where('cod_erp', '=', $oEstabelecimento->pais->id)
                                            ->first();

                                    if($oPais){

                                    }else{

                                        $oPais = new Pais();
                                        $oPais->cod_erp = $oEstabelecimento->pais->id;
                                        $oPais->nome = $oEstabelecimento->pais->nome;
                                        $oPais->sigla = $oEstabelecimento->pais->iso2;
                                        $oPais->comex_id = $oEstabelecimento->pais->comex_id;
                                        $oPais->store();
                                    }                  

                                    $oClienteAtualizacao->pais_id = $oPais->id ?? null;

                                    $oMunicipio = Municipio::where('cod_erp', '=', $oEstabelecimento->cidade->ibge_id)
                                            ->first();
                                    //$oClienteAtualizacao->municipio_id = $oEstabelecimento->municipio_id;
                                    if($oMunicipio){
                                        $oClienteAtualizacao->municipio_id = $oMunicipio->id ?? null;
                                    }

                                    $oClienteAtualizacao->telefone1 = $oEstabelecimento->ddd1.$oEstabelecimento->telefone1;
                                    $oClienteAtualizacao->telefone2 = $oEstabelecimento->ddd2.$oEstabelecimento->telefone2;
                                    $oClienteAtualizacao->fax = $oEstabelecimento->ddd_fax.$oEstabelecimento->fax;
                                    $oClienteAtualizacao->email = $oEstabelecimento->email ?? null ;

                                    if($oIEs){
                                        foreach ($oIEs as $oIE) {
                                            if($oIE->ativo = true){
                                                $oClienteAtualizacao->ie = $oIE->inscricao_estadual ?? null ;
                                            }
                                        }
                                    }

                                    $oClienteAtualizacao->data_situacao_especial = $oEstabelecimento->data_situacao_especial ?? null ;
                                    $oClienteAtualizacao->situacao_especial = $oEstabelecimento->situacao_especial ?? null ;
                                    $oClienteAtualizacao->data_situacao_cadastral = $oEstabelecimento->data_situacao_cadastral ?? null ; 
                                    $oClienteAtualizacao->atualizado_em = $dt_atu;
                                    $oClienteAtualizacao->store();

                                    $oSecundarias = $oEstabelecimento->atividades_secundarias;

                                    if($oSecundarias){
                                        foreach ($oSecundarias as $oSecundaria) {

                                            $oCnae = Cnae::where('cod_erp', '=', $oSecundaria->id)
                                                ->first();

                                            if($oCnae){

                                            }else{
                                                $oCnae = new Cnae();
                                                $oCnae->cod_erp = $oSecundaria->id;
                                                $oCnae->secao   = $oSecundaria->secao;
                                                $oCnae->divisao = $oSecundaria->divisao;
                                                $oCnae->grupo   = $oSecundaria->grupo;
                                                $oCnae->classe  = $oSecundaria->classe;
                                                $oCnae->subclasse = $oSecundaria->subclasse;
                                                $oCnae->descricao = $oSecundaria->descricao;
                                                $oCnae->store();
                                            }

                                            if($oCnae){
                                                $oClienteCnae = ClienteCnae::where('cliente_id', '=', $oCliente->id)
                                                    ->where('cnae_id', '=', $oCnae->id)
                                                    ->first();

                                                if($oClienteCnae){

                                                }else{
                                                    $oClienteCnae = new ClienteCnae();
                                                    $oClienteCnae->cliente_id = $oCliente->id;
                                                    $oClienteCnae->cnae_id = $oCnae->id;
                                                    $oClienteCnae->store();
                                                }
                                            }
                                        }
                                    }

                                    $oSocios  = $dadosCnpj->socios;
                                    if($oSocios){
                                        foreach ($oSocios as $oSocio) {
                                            $oBusca = ClienteSocios::where('cliente_id', '=', $oCliente->id)
                                                ->where('nome', '=', $oSocio->nome)
                                                ->first();
                                            if($oBusca){

                                            }else{
                                                $oQSA = new ClienteSocios();
                                                $oQSA->cliente_id = $oCliente->id;
                                                $oQSA->nome = $oSocio->nome;
                                                $oQSA->tipo = $oSocio->tipo;
                                                $oQSA->data_entrada = $oSocio->data_entrada;
                                                $oQSA->faixa_etaria = $oSocio->faixa_etaria;
                                                $oQSA->qualificacao_socio = $oSocio->qualificacao_socio->descricao;
                                                $oQSA->descricao = $oSocio->descricao;
                                                $oQSA->cpf_cnpj_socio = $oSocio->cpf_cnpj_socio;
                                                $oQSA->store();
                                            }

                                        }
                                    }

                                    $objeto = Cliente::find( $oCliente->id );
                                    if($objeto)
                                    {
                                        $objeto->dt_revisao = date('Y-m-d H:i');
                                        $objeto->situacao_cadastral_id = $oSituacao->id;
                                        $objeto->store();    
                                    }

                                }
                            }
                        }else{
                            TToast::show("info", "Cliente sem CNPJ", 'topRight', 'far:check-circle');
                        }
                    }
                }
            }

            TTransaction::close();

            $this->onReload([]);

            //</autoCode>
        }
        catch (Exception $e) 
        {
            new TMessage('error', $e->getMessage());    
        }
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

            // creates a repository for ClienteAtualizacao
            $repository = new TRepository(self::$activeRecord);
            // creates a criteria
            $criteria = new TCriteria;

            if(!empty($param["cliente_id"] ?? ""))
        {
            TSession::setValue(__CLASS__.'load_filter_cliente_id', $param["cliente_id"] ?? "");
        }
        $filterVar = TSession::getValue(__CLASS__.'load_filter_cliente_id');
            $criteria->add(new TFilter('cliente_id', '=', $filterVar));

            if (empty($param['order']))
            {
                $param['order'] = 'dt_inclusao';    
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

        //var_dump($param);

    }

    /**
     * method show()
     * Shows the page
     */
    public function show()
    {
        // check if the datagrid is already loaded
        if (!$this->loaded AND (!isset($_GET['method']) OR !(in_array($_GET['method'],  array('onReload', 'onSearch')))) )
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

        $object = new ClienteAtualizacao($id);

        $row = $list->datagrid->addItem($object);
        $row->id = "row_{$object->id}";

        if($openTransaction)
        {
            TTransaction::close();    
        }

        TDataGrid::replaceRowById(__CLASS__.'_datagrid', $row->id, $row);
    }

}

