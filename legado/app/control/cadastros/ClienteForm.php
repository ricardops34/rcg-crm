<?php

class ClienteForm extends TPage
{
    protected BootstrapFormBuilder $form;
    private $formFields = [];
    private static $database = 'erp_online';
    private static $activeRecord = 'Cliente';
    private static $primaryKey = 'id';
    private static $formName = 'form_ClienteForm';

    use BuilderMasterDetailTrait;

    /**
     * Form constructor
     * @param $param Request
     */
    public function __construct( $param )
    {
        parent::__construct();

        if(!empty($param['target_container']))
        {
            $this->adianti_target_container = $param['target_container'];
        }

        // creates the form
        $this->form = new BootstrapFormBuilder(self::$formName);
        // define the form title
        $this->form->setFormTitle("Cadastro de cliente");

        $criteria_tipo_cliente_id = new TCriteria();
        $criteria_uf = new TCriteria();
        $criteria_vendedor_id = new TCriteria();
        $criteria_condicao_pagamento_id = new TCriteria();
        $criteria_seguimento_id = new TCriteria();
        $criteria_cliente_contato_cliente_tipo_contato_id = new TCriteria();
        $criteria_motivo_bloqueio_id = new TCriteria();

        $id = new TEntry('id');
        $motivo_bloqueio_descricao = new TEntry('motivo_bloqueio_descricao');
        $tipo = new TCombo('tipo');
        $cnpj_cpf = new TEntry('cnpj_cpf');
        $cod_erp = new TEntry('cod_erp');
        $filial_id = new THidden('filial_id');
        $tipo_cliente_id = new TDBCombo('tipo_cliente_id', 'erp_online', 'TipoCliente', 'id', '{descricao}','descricao asc' , $criteria_tipo_cliente_id );
        $razao = new TEntry('razao');
        $fantasia = new TEntry('fantasia');
        $endereco = new TEntry('endereco');
        $complemento = new TEntry('complemento');
        $cep = new TEntry('cep');
        $bairro = new TEntry('bairro');
        $uf = new TDBCombo('uf', 'erp_online', 'Estado', 'id', '{sigla}','sigla asc' , $criteria_uf );
        $municipio_id = new TCombo('municipio_id');
        $contato = new TEntry('contato');
        $ie = new TEntry('ie');
        $im = new TEntry('im');
        $destaca_ie = new TEntry('destaca_ie');
        $contribuinte = new TEntry('contribuinte');
        $rg = new TEntry('rg');
        $nascimento = new TDate('nascimento');
        $email = new TEntry('email');
        $obs = new TEntry('obs');
        $site = new TEntry('site');
        $telefone1 = new TEntry('telefone1');
        $telefone2 = new TEntry('telefone2');
        $celular = new TEntry('celular');
        $celular2 = new TEntry('celular2');
        $vendedor_id = new TDBCombo('vendedor_id', 'erp_online', 'Vendedor', 'id', '{nome}','nome asc' , $criteria_vendedor_id );
        $condicao_pagamento_id = new TDBCombo('condicao_pagamento_id', 'erp_online', 'CondicaoPagamento', 'id', '{descricao}','descricao asc' , $criteria_condicao_pagamento_id );
        $tabela_preco_id = new TEntry('tabela_preco_id');
        $seguimento_id = new TDBCombo('seguimento_id', 'erp_online', 'Segmento', 'id', '{descricao}','descricao asc' , $criteria_seguimento_id );
        $primeira_compra = new TDate('primeira_compra');
        $ultima_compra = new TDate('ultima_compra');
        $data_cadastro = new TDate('data_cadastro');
        $risco = new TEntry('risco');
        $vencimento_limite = new TDate('vencimento_limite');
        $limite = new TNumeric('limite', '2', ',', '.' );
        $cliente_contato_cliente_tipo_contato_id = new TDBCombo('cliente_contato_cliente_tipo_contato_id', 'erp_online', 'TipoContato', 'id', '{descricao}','id asc' , $criteria_cliente_contato_cliente_tipo_contato_id );
        $cliente_contato_cliente_id = new THidden('cliente_contato_cliente_id');
        $cliente_contato_cliente_nome = new TEntry('cliente_contato_cliente_nome');
        $cliente_contato_cliente_situacao = new TCombo('cliente_contato_cliente_situacao');
        $cliente_contato_cliente_telefone = new TEntry('cliente_contato_cliente_telefone');
        $cliente_contato_cliente_email = new TEntry('cliente_contato_cliente_email');
        $button_adicionar_cliente_contato_cliente = new TButton('button_adicionar_cliente_contato_cliente');
        $status = new TCombo('status');
        $motivo_bloqueio_id = new TDBCombo('motivo_bloqueio_id', 'erp_online', 'MotivoBloqueio', 'id', '{descricao}','descricao asc' , $criteria_motivo_bloqueio_id );
        $obs_bloqueio = new TText('obs_bloqueio');
        $cadastro_atualizado = new BPageContainer();

        $uf->setChangeAction(new TAction([$this,'onChangeuf']));

        $cnpj_cpf->setExitAction(new TAction([$this,'onExitCPF']));

        $razao->addValidation("Razão Social", new TRequiredValidator()); 

        $cliente_contato_cliente_situacao->setValue('A');
        $button_adicionar_cliente_contato_cliente->addStyleClass('btn-default');
        $button_adicionar_cliente_contato_cliente->setImage('fas:plus #2ecc71');
        $cadastro_atualizado->setId('b662a92b771f08');
        $cadastro_atualizado->hide();
        $cadastro_atualizado->setAction(new TAction(['ClienteAtualizacaoSimpleList', 'onShow'], $param));
        $button_adicionar_cliente_contato_cliente->setAction(new TAction([$this, 'onAddDetailClienteContatoCliente'],['static' => 1]), "Adicionar");

        $tipo->addItems(["F"=>"Fisica","J"=>"Juridica"]);
        $status->addItems(["A"=>"Ativo","B"=>"Bloqueado"]);
        $cliente_contato_cliente_situacao->addItems(["A"=>"Ativo","B"=>"Bloqueado"]);

        $nascimento->setMask('dd/mm/yyyy');
        $ultima_compra->setMask('dd/mm/yyyy');
        $data_cadastro->setMask('dd/mm/yyyy');
        $primeira_compra->setMask('dd/mm/yyyy');
        $vencimento_limite->setMask('dd/mm/yyyy');

        $nascimento->setDatabaseMask('yyyy-mm-dd');
        $ultima_compra->setDatabaseMask('yyyy-mm-dd');
        $data_cadastro->setDatabaseMask('yyyy-mm-dd');
        $primeira_compra->setDatabaseMask('yyyy-mm-dd');
        $vencimento_limite->setDatabaseMask('yyyy-mm-dd');

        $risco->setEditable(false);
        $limite->setEditable(false);
        $ultima_compra->setEditable(false);
        $data_cadastro->setEditable(false);
        $primeira_compra->setEditable(false);
        $vencimento_limite->setEditable(false);

        $uf->enableSearch();
        $tipo->enableSearch();
        $status->enableSearch();
        $vendedor_id->enableSearch();
        $municipio_id->enableSearch();
        $seguimento_id->enableSearch();
        $tipo_cliente_id->enableSearch();
        $motivo_bloqueio_id->enableSearch();
        $condicao_pagamento_id->enableSearch();
        $cliente_contato_cliente_situacao->enableSearch();
        $cliente_contato_cliente_tipo_contato_id->enableSearch();

        $cep->setMaxLength(8);
        $ie->setMaxLength(20);
        $im->setMaxLength(20);
        $rg->setMaxLength(20);
        $risco->setMaxLength(1);
        $site->setMaxLength(100);
        $razao->setMaxLength(100);
        $bairro->setMaxLength(50);
        $email->setMaxLength(100);
        $cod_erp->setMaxLength(10);
        $contato->setMaxLength(30);
        $celular->setMaxLength(20);
        $cnpj_cpf->setMaxLength(20);
        $fantasia->setMaxLength(50);
        $celular2->setMaxLength(20);
        $endereco->setMaxLength(100);
        $destaca_ie->setMaxLength(1);
        $telefone1->setMaxLength(20);
        $telefone2->setMaxLength(20);
        $complemento->setMaxLength(50);
        $contribuinte->setMaxLength(1);
        $cliente_contato_cliente_nome->setMaxLength(100);
        $cliente_contato_cliente_email->setMaxLength(100);
        $cliente_contato_cliente_telefone->setMaxLength(9);

        $id->setSize('100%');
        $uf->setSize('100%');
        $ie->setSize('100%');
        $im->setSize('100%');
        $rg->setSize('100%');
        $cep->setSize('100%');
        $obs->setSize('100%');
        $tipo->setSize('100%');
        $site->setSize('100%');
        $razao->setSize('100%');
        $email->setSize('100%');
        $risco->setSize('100%');
        $filial_id->setSize(200);
        $bairro->setSize('100%');
        $limite->setSize('100%');
        $status->setSize('100%');
        $cod_erp->setSize('100%');
        $contato->setSize('100%');
        $celular->setSize('100%');
        $cnpj_cpf->setSize('100%');
        $fantasia->setSize('100%');
        $endereco->setSize('100%');
        $celular2->setSize('100%');
        $telefone1->setSize('100%');
        $telefone2->setSize('100%');
        $destaca_ie->setSize('100%');
        $nascimento->setSize('100%');
        $complemento->setSize('100%');
        $vendedor_id->setSize('100%');
        $municipio_id->setSize('100%');
        $contribuinte->setSize('100%');
        $seguimento_id->setSize('100%');
        $ultima_compra->setSize('100%');
        $data_cadastro->setSize('100%');
        $tipo_cliente_id->setSize('100%');
        $tabela_preco_id->setSize('100%');
        $primeira_compra->setSize('100%');
        $obs_bloqueio->setSize('100%', 70);
        $vencimento_limite->setSize('100%');
        $motivo_bloqueio_id->setSize('100%');
        $cadastro_atualizado->setSize('100%');
        $condicao_pagamento_id->setSize('100%');
        $cliente_contato_cliente_id->setSize(200);
        $motivo_bloqueio_descricao->setSize('100%');
        $cliente_contato_cliente_nome->setSize('100%');
        $cliente_contato_cliente_email->setSize('100%');
        $cliente_contato_cliente_situacao->setSize('100%');
        $cliente_contato_cliente_telefone->setSize('100%');
        $cliente_contato_cliente_tipo_contato_id->setSize('100%');

        $button_adicionar_cliente_contato_cliente->id = '6629c19a0ea62';

        $loadingContainer = new TElement('div');
        $loadingContainer->style = 'text-align:center; padding:50px';

        $icon = new TElement('i');
        $icon->class = 'fas fa-spinner fa-spin fa-3x';

        $loadingContainer->add($icon);
        $loadingContainer->add('<br>Carregando');

        $cadastro_atualizado->add($loadingContainer);

        $this->cadastro_atualizado = $cadastro_atualizado;

        $this->form->appendPage("Cadastro");

        $this->form->addFields([new THidden('current_tab')]);
        $this->form->setTabFunction("$('[name=current_tab]').val($(this).attr('data-current_page'));");

        $row1 = $this->form->addFields([$id],[new TLabel("Situação RFB:", null, '14px', null, '100%'),$motivo_bloqueio_descricao],[new TLabel("Tipo Pessoa:", null, '14px', null, '100%'),$tipo],[new TLabel("CNPJ/CPF:", null, '14px', null, '100%'),$cnpj_cpf]);
        $row1->layout = [' col-sm-3',' col-sm-3','col-sm-3','col-sm-3'];

        $row2 = $this->form->addFields([new TLabel("Código:", null, '14px', null, '100%'),$cod_erp,$filial_id],[new TLabel("Tipo Cliente:", null, '14px', null, '100%'),$tipo_cliente_id],[new TLabel("Razão Social:", '#ff0000', '14px', null, '100%'),$razao]);
        $row2->layout = [' col-sm-3',' col-sm-3','col-sm-6'];

        $row3 = $this->form->addFields([new TLabel("Nome de Fantasia:", null, '14px', null, '100%'),$fantasia],[new TLabel("Endereço:", null, '14px', null, '100%'),$endereco]);
        $row3->layout = ['col-sm-6','col-sm-6'];

        $row4 = $this->form->addFields([new TLabel("Complemento:", null, '14px', null, '100%'),$complemento],[new TLabel("CEP:", null, '14px', null, '100%'),$cep],[new TLabel("Bairro:", null, '14px', null, '100%'),$bairro]);
        $row4->layout = ['col-sm-6','col-sm-3','col-sm-3'];

        $row5 = $this->form->addFields([new TLabel("UF:", null, '14px', null, '100%'),$uf],[new TLabel("Municipio:", null, '14px', null, '100%'),$municipio_id],[new TLabel("Contato:", null, '14px', null, '100%'),$contato]);
        $row5->layout = ['col-sm-3',' col-sm-5','col-sm-4'];

        $row6 = $this->form->addFields([new TLabel("Inscrição Estadual:", null, '14px', null, '100%'),$ie],[new TLabel("Inscrição Municipal:", null, '14px', null, '100%'),$im],[new TLabel("Destaca IE:", null, '14px', null, '100%'),$destaca_ie]);
        $row6->layout = ['col-sm-4','col-sm-4',' col-sm-4'];

        $row7 = $this->form->addFields([new TLabel("Contribuinte:", null, '14px', null, '100%'),$contribuinte],[new TLabel("RG:", null, '14px', null, '100%'),$rg],[new TLabel("Data Nascimento/Abertura:", null, '14px', null, '100%'),$nascimento]);
        $row7->layout = [' col-sm-4',' col-sm-4',' col-sm-4'];

        $row8 = $this->form->addFields([new TLabel("E-Mail:", null, '14px', null, '100%'),$email],[new TLabel("Obs. Financeiro:", null, '14px', null, '100%'),$obs],[new TLabel("Site:", null, '14px', null, '100%'),$site]);
        $row8->layout = [' col-sm-4',' col-sm-4',' col-sm-4'];

        $this->form->appendPage("Telefones");
        $row9 = $this->form->addFields([new TLabel("Telefone 1:", null, '14px', null, '100%'),$telefone1],[new TLabel("Telefone 2:", null, '14px', null, '100%'),$telefone2],[new TLabel("Telefone Celular:", null, '14px', null, '100%'),$celular],[new TLabel("Telefone Celular 1:", null, '14px', null, '100%'),$celular2]);
        $row9->layout = [' col-sm-3',' col-sm-3',' col-sm-3',' col-sm-3'];

        $this->form->appendPage("Venda");
        $row10 = $this->form->addFields([new TLabel("Vendedor:", null, '14px', null, '100%'),$vendedor_id],[new TLabel("Condição Pagamento:", null, '14px', null, '100%'),$condicao_pagamento_id],[new TLabel("Tabela de Preço:", null, '14px', null, '100%'),$tabela_preco_id],[new TLabel("Seguimento:", null, '14px', null, '100%'),$seguimento_id]);
        $row10->layout = [' col-sm-3',' col-sm-3',' col-sm-3',' col-sm-3'];

        $row11 = $this->form->addFields([new TLabel("Primeira Compra:", null, '14px', null, '100%'),$primeira_compra],[new TLabel("Ultima Visita:", null, '14px', null, '100%'),$ultima_compra],[new TLabel("Data de Cadastro:", null, '14px', null, '100%'),$data_cadastro]);
        $row11->layout = [' col-sm-4',' col-sm-4',' col-sm-4'];

        $row12 = $this->form->addFields([new TLabel("Risco:", null, '14px', null, '100%'),$risco],[new TLabel("Vencimento Limite de Credito:", null, '14px', null, '100%'),$vencimento_limite],[new TLabel("Limite de Credito:", null, '14px', null, '100%'),$limite]);
        $row12->layout = [' col-sm-4',' col-sm-4',' col-sm-4'];

        $this->form->appendPage("Contatos");

        $this->detailFormClienteContatoCliente = new BootstrapFormBuilder('detailFormClienteContatoCliente');
        $this->detailFormClienteContatoCliente->setProperty('style', 'border:none; box-shadow:none; width:100%;');

        $this->detailFormClienteContatoCliente->setProperty('class', 'form-horizontal builder-detail-form');

        $row13 = $this->detailFormClienteContatoCliente->addFields([new TLabel("Tipo contato:", '#ff0000', '14px', null, '100%'),$cliente_contato_cliente_tipo_contato_id,$cliente_contato_cliente_id],[new TLabel("Nome:", '#FF0000', '14px', null, '100%'),$cliente_contato_cliente_nome],[new TLabel("Situação:", '#FF0000', '14px', null, '100%'),$cliente_contato_cliente_situacao]);
        $row13->layout = [' col-sm-3','col-sm-6',' col-sm-3'];

        $row14 = $this->detailFormClienteContatoCliente->addFields([new TLabel("Telefone:", '#FF0000', '14px', null, '100%'),$cliente_contato_cliente_telefone],[new TLabel("Email:", null, '14px', null, '100%'),$cliente_contato_cliente_email],[new TLabel(" ", null, '14px', null, '100%'),$button_adicionar_cliente_contato_cliente]);
        $row14->layout = [' col-sm-3','col-sm-6',' col-sm-3'];

        $row15 = $this->detailFormClienteContatoCliente->addFields([new THidden('cliente_contato_cliente__row__id')]);
        $this->cliente_contato_cliente_criteria = new TCriteria();

        $this->cliente_contato_cliente_list = new BootstrapDatagridWrapper(new TDataGrid);
        $this->cliente_contato_cliente_list->generateHiddenFields();
        $this->cliente_contato_cliente_list->setId('cliente_contato_cliente_list');

        $this->cliente_contato_cliente_list->style = 'width:100%';
        $this->cliente_contato_cliente_list->class .= ' table-bordered';

        $column_cliente_contato_cliente_tipo_contato_descricao = new TDataGridColumn('tipo_contato->descricao', "Tipo", 'left');
        $column_cliente_contato_cliente_nome = new TDataGridColumn('nome', "Nome", 'left');
        $column_cliente_contato_cliente_telefone = new TDataGridColumn('telefone', "Telefone", 'left');
        $column_cliente_contato_cliente_email = new TDataGridColumn('email', "Email", 'left');

        $column_cliente_contato_cliente__row__data = new TDataGridColumn('__row__data', '', 'center');
        $column_cliente_contato_cliente__row__data->setVisibility(false);

        $action_onEditDetailClienteContato = new TDataGridAction(array('ClienteForm', 'onEditDetailClienteContato'));
        $action_onEditDetailClienteContato->setUseButton(false);
        $action_onEditDetailClienteContato->setButtonClass('btn btn-default btn-sm');
        $action_onEditDetailClienteContato->setLabel("Editar");
        $action_onEditDetailClienteContato->setImage('far:edit #478fca');
        $action_onEditDetailClienteContato->setFields(['__row__id', '__row__data']);

        $this->cliente_contato_cliente_list->addAction($action_onEditDetailClienteContato);
        $action_onDeleteDetailClienteContato = new TDataGridAction(array('ClienteForm', 'onDeleteDetailClienteContato'));
        $action_onDeleteDetailClienteContato->setUseButton(false);
        $action_onDeleteDetailClienteContato->setButtonClass('btn btn-default btn-sm');
        $action_onDeleteDetailClienteContato->setLabel("Excluir");
        $action_onDeleteDetailClienteContato->setImage('fas:trash-alt #dd5a43');
        $action_onDeleteDetailClienteContato->setFields(['__row__id', '__row__data']);

        $this->cliente_contato_cliente_list->addAction($action_onDeleteDetailClienteContato);

        $this->cliente_contato_cliente_list->addColumn($column_cliente_contato_cliente_tipo_contato_descricao);
        $this->cliente_contato_cliente_list->addColumn($column_cliente_contato_cliente_nome);
        $this->cliente_contato_cliente_list->addColumn($column_cliente_contato_cliente_telefone);
        $this->cliente_contato_cliente_list->addColumn($column_cliente_contato_cliente_email);

        $this->cliente_contato_cliente_list->addColumn($column_cliente_contato_cliente__row__data);

        $this->cliente_contato_cliente_list->createModel();
        $tableResponsiveDiv = new TElement('div');
        $tableResponsiveDiv->class = 'table-responsive';
        $tableResponsiveDiv->add($this->cliente_contato_cliente_list);
        $this->detailFormClienteContatoCliente->addContent([$tableResponsiveDiv]);
        $row16 = $this->form->addFields([$this->detailFormClienteContatoCliente]);
        $row16->layout = [' col-sm-12'];

        $this->form->appendPage("Bloqueio");
        $row17 = $this->form->addFields([new TLabel("Situação:", null, '14px', null, '100%'),$status],[new TLabel("Motivo Bloqueio:", null, '14px', null, '100%'),$motivo_bloqueio_id],[$obs_bloqueio]);
        $row17->layout = ['col-sm-3','col-sm-3','col-sm-6'];

        $this->form->appendPage("Receita Federal");
        $row18 = $this->form->addFields([$cadastro_atualizado]);
        $row18->layout = [' col-12 col-sm-12 col-md-12'];

        // create the form actions
        $btnSalvar = $this->form->addAction("Salvar", new TAction([$this, 'onSave'],['static' => 1]), 'fas:save #ffffff');
        $this->btnSalvar = $btnSalvar;
        $btnSalvar->addStyleClass('btn-primary'); 

        $btn_onshow = $this->form->addAction("Voltar", new TAction(['ClienteList', 'onShow']), 'fas:arrow-left #000000');
        $this->btn_onshow = $btn_onshow;

        parent::setTargetContainer('adianti_right_panel');

        $btnClose = new TButton('closeCurtain');
        $btnClose->class = 'btn btn-sm btn-default';
        $btnClose->style = 'margin-right:10px;';
        $btnClose->onClick = "Template.closeRightPanel();";
        $btnClose->setLabel("Fechar");
        $btnClose->setImage('fas:times');

        $this->form->addHeaderWidget($btnClose);

        parent::add($this->form);

        $style = new TStyle('right-panel > .container-part[page-name=ClienteForm]');
        $style->width = '80% !important';   
        $style->show(true);

    }

    public static function onChangeuf($param)
    {
        try
        {

            if (isset($param['uf']) && $param['uf'])
            { 
                $criteria = TCriteria::create(['estado_id' => $param['uf']]);
                TDBCombo::reloadFromModel(self::$formName, 'municipio_id', 'erp_online', 'Municipio', 'id', '{cod_erp}', 'cod_erp asc', $criteria, TRUE); 
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

    public static function onExitCPF($param = null) 
    {
        try 
        {
            $cpf = $param[''];

        	// Verifica se um número foi informado
        	if(empty($cpf)) {

        	}else{

            	$cpf = preg_replace("/[^0-9]/", "", $cpf);
            	//$cpf = str_pad($cpf, 11, '0', STR_PAD_LEFT);

            	if (strlen($cpf) == 11) {

                	if ($cpf == '00000000000' || 
                		$cpf == '11111111111' || 
                		$cpf == '22222222222' || 
                		$cpf == '33333333333' || 
                		$cpf == '44444444444' || 
                		$cpf == '55555555555' || 
                		$cpf == '66666666666' || 
                		$cpf == '77777777777' || 
                		$cpf == '88888888888' || 
                		$cpf == '99999999999') {
                		//return false;
                	 // Calcula os digitos verificadores para verificar se o
                	 // CPF é válido
                	 } else {   

                		for ($t = 9; $t < 11; $t++) {

                			for ($d = 0, $c = 0; $c < $t; $c++) {
                				$d += $cpf{$c} * (($t + 1) - $c);
                			}
                			$d = ((10 * $d) % 11) % 10;
                			if ($cpf{$c} != $d) {
                				//return false;
                			}
                		}

        		    //return true;
        	        }
            	}
        	}

        }
        catch (Exception $e) 
        {
            new TMessage('error', $e->getMessage());    
        }
    }

    public  function onAddDetailClienteContatoCliente($param = null) 
    {
        try
        {
            $data = $this->form->getData();

            $errors = [];
            $requiredFields = [];
            $requiredFields[] = ['label'=>"Tipo contato id", 'name'=>"cliente_contato_cliente_tipo_contato_id", 'class'=>'TRequiredValidator', 'value'=>[]];
            $requiredFields[] = ['label'=>"Nome Contato", 'name'=>"cliente_contato_cliente_nome", 'class'=>'TRequiredValidator', 'value'=>[]];
            $requiredFields[] = ['label'=>"Situação", 'name'=>"cliente_contato_cliente_situacao", 'class'=>'TRequiredValidator', 'value'=>[]];
            $requiredFields[] = ['label'=>"Telefone Contato", 'name'=>"cliente_contato_cliente_telefone", 'class'=>'TRequiredValidator', 'value'=>[]];
            foreach($requiredFields as $requiredField)
            {
                try
                {
                    (new $requiredField['class'])->validate($requiredField['label'], $data->{$requiredField['name']}, $requiredField['value']);
                }
                catch(Exception $e)
                {
                    $errors[] = $e->getMessage() . '.';
                }
             }
             if(count($errors) > 0)
             {
                 throw new Exception(implode('<br>', $errors));
             }

            $__row__id = !empty($data->cliente_contato_cliente__row__id) ? $data->cliente_contato_cliente__row__id : 'b'.uniqid();

            TTransaction::open(self::$database);

            $grid_data = new ClienteContato();
            $grid_data->__row__id = $__row__id;
            $grid_data->tipo_contato_id = $data->cliente_contato_cliente_tipo_contato_id;
            $grid_data->id = $data->cliente_contato_cliente_id;
            $grid_data->nome = $data->cliente_contato_cliente_nome;
            $grid_data->situacao = $data->cliente_contato_cliente_situacao;
            $grid_data->telefone = $data->cliente_contato_cliente_telefone;
            $grid_data->email = $data->cliente_contato_cliente_email;

            $__row__data = array_merge($grid_data->toArray(), (array)$grid_data->getVirtualData());
            $__row__data['__row__id'] = $__row__id;
            $__row__data['__display__']['tipo_contato_id'] =  $param['cliente_contato_cliente_tipo_contato_id'] ?? null;
            $__row__data['__display__']['id'] =  $param['cliente_contato_cliente_id'] ?? null;
            $__row__data['__display__']['nome'] =  $param['cliente_contato_cliente_nome'] ?? null;
            $__row__data['__display__']['situacao'] =  $param['cliente_contato_cliente_situacao'] ?? null;
            $__row__data['__display__']['telefone'] =  $param['cliente_contato_cliente_telefone'] ?? null;
            $__row__data['__display__']['email'] =  $param['cliente_contato_cliente_email'] ?? null;

            $grid_data->__row__data = base64_encode(serialize((object)$__row__data));
            $row = $this->cliente_contato_cliente_list->addItem($grid_data);
            $row->id = $grid_data->__row__id;

            TDataGrid::replaceRowById('cliente_contato_cliente_list', $grid_data->__row__id, $row);

            TTransaction::close();

            $data = new stdClass;
            $data->cliente_contato_cliente_tipo_contato_id = '';
            $data->cliente_contato_cliente_id = '';
            $data->cliente_contato_cliente_nome = '';
            $data->cliente_contato_cliente_situacao = 'A';
            $data->cliente_contato_cliente_telefone = '';
            $data->cliente_contato_cliente_email = '';
            $data->cliente_contato_cliente__row__id = '';

            TForm::sendData(self::$formName, $data);
            TScript::create("
               var element = $('#6629c19a0ea62');
               if(typeof element.attr('add') != 'undefined')
               {
                   element.html(base64_decode(element.attr('add')));
               }
            ");

        }
        catch (Exception $e) // in case of exception
        {
            new TMessage('error', $e->getMessage()); // shows the exception error message
        }
    }

    public static function onEditDetailClienteContato($param = null) 
    {
        try
        {

            $__row__data = unserialize(base64_decode($param['__row__data']));
            $__row__data->__display__ = is_array($__row__data->__display__) ? (object) $__row__data->__display__ : $__row__data->__display__;
            $fireEvents = true;
            $aggregate = false;

            $data = new stdClass;
            $data->cliente_contato_cliente_tipo_contato_id = $__row__data->__display__->tipo_contato_id ?? null;
            $data->cliente_contato_cliente_id = $__row__data->__display__->id ?? null;
            $data->cliente_contato_cliente_nome = $__row__data->__display__->nome ?? null;
            $data->cliente_contato_cliente_situacao = $__row__data->__display__->situacao ?? null;
            $data->cliente_contato_cliente_telefone = $__row__data->__display__->telefone ?? null;
            $data->cliente_contato_cliente_email = $__row__data->__display__->email ?? null;
            $data->cliente_contato_cliente__row__id = $__row__data->__row__id;

            TForm::sendData(self::$formName, $data, $aggregate, $fireEvents);
            TScript::create("
               var element = $('#6629c19a0ea62');
               if(!element.attr('add')){
                   element.attr('add', base64_encode(element.html()));
               }
               element.html(\"<span><i class='far fa-edit' style='color:#478fca;padding-right:4px;'></i>Editar</span>\");
               if(!element.attr('edit')){
                   element.attr('edit', base64_encode(element.html()));
               }
            ");

        }
        catch (Exception $e) // in case of exception
        {
            new TMessage('error', $e->getMessage()); // shows the exception error message
        }
    }
    public static function onDeleteDetailClienteContato($param = null) 
    {
        try
        {

            $__row__data = unserialize(base64_decode($param['__row__data']));

            $data = new stdClass;
            $data->cliente_contato_cliente_tipo_contato_id = '';
            $data->cliente_contato_cliente_id = '';
            $data->cliente_contato_cliente_nome = '';
            $data->cliente_contato_cliente_situacao = '';
            $data->cliente_contato_cliente_telefone = '';
            $data->cliente_contato_cliente_email = '';
            $data->cliente_contato_cliente__row__id = '';

            TForm::sendData(self::$formName, $data);

            TDataGrid::removeRowById('cliente_contato_cliente_list', $__row__data->__row__id);
            TScript::create("
               var element = $('#6629c19a0ea62');
               if(typeof element.attr('add') != 'undefined')
               {
                   element.html(base64_decode(element.attr('add')));
               }
            ");

        }
        catch (Exception $e) // in case of exception
        {
            new TMessage('error', $e->getMessage()); // shows the exception error message
        }
    }
    public function onSave($param = null) 
    {
        try
        {
            TTransaction::open(self::$database); // open a transaction

            $messageAction = null;

            $this->form->validate(); // validate form data

            $object = new Cliente(); // create an empty object 

            $data = $this->form->getData(); // get form data as array
            $object->fromArray( (array) $data); // load the object with data

            $object->store(); // save the object 

            $this->fireEvents($object);

            TForm::sendData(self::$formName, (object)['id' => $object->id]);

            $loadPageParam = [];

            if(!empty($param['target_container']))
            {
                $loadPageParam['target_container'] = $param['target_container'];
            }

            $cliente_contato_cliente_items = $this->storeMasterDetailItems('ClienteContato', 'cliente_id', 'cliente_contato_cliente', $object, $param['cliente_contato_cliente_list___row__data'] ?? [], $this->form, $this->cliente_contato_cliente_list, function($masterObject, $detailObject){ 

                //code here

            }, $this->cliente_contato_cliente_criteria); 

            // get the generated {PRIMARY_KEY}
            $data->id = $object->id; 

            $this->form->setData($data); // fill form data
            TTransaction::close(); // close the transaction

            TToast::show('success', "Registro salvo", 'topRight', 'far:check-circle');
            TApplication::loadPage('ClienteList', 'onShow', $loadPageParam); 

                        TScript::create("Template.closeRightPanel();"); 

        }
        catch (Exception $e) // in case of exception
        {

            $this->fireEvents($this->form->getData());  

            new TMessage('error', $e->getMessage()); // shows the exception error message
            $this->form->setData( $this->form->getData() ); // keep form data
            TTransaction::rollback(); // undo all pending operations
        }
    }

    public function onEdit( $param )
    {
        try
        {
            if (isset($param['key']))
            {
                $key = $param['key'];  // get the parameter $key
                TTransaction::open(self::$database); // open a transaction

                $object = new Cliente($key); // instantiates the Active Record 

                                $object->motivo_bloqueio_descricao = $object->motivo_bloqueio->descricao;
                $this->cadastro_atualizado->unhide();
                $this->cadastro_atualizado->setParameter('key', $object->id);

                $this->cadastro_atualizado->setParameter('cliente_id', $object->id);

                $cliente_contato_cliente_items = $this->loadMasterDetailItems('ClienteContato', 'cliente_id', 'cliente_contato_cliente', $object, $this->form, $this->cliente_contato_cliente_list, $this->cliente_contato_cliente_criteria, function($masterObject, $detailObject, $objectItems){ 

                    //code here

                }); 

                $this->form->setData($object); // fill the form 

                $this->fireEvents($object);

                TTransaction::close(); // close the transaction 
            }
            else
            {
                $this->form->clear();
            }
        }
        catch (Exception $e) // in case of exception
        {
            new TMessage('error', $e->getMessage()); // shows the exception error message
            TTransaction::rollback(); // undo all pending operations
        }
    }

    /**
     * Clear form data
     * @param $param Request
     */
    public function onClear( $param )
    {
        $this->form->clear(true);

    }

    public function onShow($param = null)
    {

    } 

    public function fireEvents( $object )
    {
        $obj = new stdClass;
        if(is_object($object) && get_class($object) == 'stdClass')
        {
            if(isset($object->uf))
            {
                $value = $object->uf;

                $obj->uf = $value;
            }
            if(isset($object->municipio_id))
            {
                $value = $object->municipio_id;

                $obj->municipio_id = $value;
            }
        }
        elseif(is_object($object))
        {
            if(isset($object->uf))
            {
                $value = $object->uf;

                $obj->uf = $value;
            }
            if(isset($object->municipio_id))
            {
                $value = $object->municipio_id;

                $obj->municipio_id = $value;
            }
        }
        TForm::sendData(self::$formName, $obj);
    }  

    public static function getFormName()
    {
        return self::$formName;
    }

    public function onView( $param )
    {
        try
        {
            if (isset($param['key']))
            {
                $key = $param['key'];  
                TTransaction::open(self::$database); 

                $object = new Cliente($key); 

                $this->form->setData($object); 

                $this->btnSalvar->style = 'display:none';
                $this->form->setEditable(FALSE);

                TTransaction::close();  
            }
            else
            {
                $this->form->clear();
            }
        }
        catch (Exception $e) 
        {
            new TMessage('error', $e->getMessage()); 
            TTransaction::rollback(); 
        }
    }

}

