<?php

class OrcamentoForm extends TPage
{
    protected BootstrapFormBuilder $form;
    private $formFields = [];
    private static $database = 'erp_online';
    private static $activeRecord = 'Orcamento';
    private static $primaryKey = 'id';
    private static $formName = 'form_OrcamentoForm';

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
        $this->form->setFormTitle("Cadastro de orcamento");

        $criteria_orcamento_estado_id = new TCriteria();
        $criteria_tabela_preco_id = new TCriteria();
        $criteria_condicao_pagamento_id = new TCriteria();
        $criteria_vendedor_id = new TCriteria();

        $filterVar = 'S';
        $criteria_tabela_preco_id->add(new TFilter('status', '=', $filterVar)); 

        $cliente_id = new TEntry('cliente_id');
        $id = new TEntry('id');
        $emissao = new TDate('emissao');
        $retorno = new TDate('retorno');
        $orcamento_estado_id = new TDBCombo('orcamento_estado_id', 'erp_online', 'OrcamentoEstado', 'id', '{descricao}','descricao asc' , $criteria_orcamento_estado_id );
        $codigo_cliente = new TSeekButton('codigo_cliente');
        $nome = new TEntry('nome');
        $btn_view = new TButton('btn_view');
        $telefone = new TEntry('telefone');
        $email = new TEntry('email');
        $tabela_preco_id = new TDBCombo('tabela_preco_id', 'erp_online', 'TabelaPreco', 'id', '{descricao}','descricao asc' , $criteria_tabela_preco_id );
        $condicao_pagamento_id = new TDBCombo('condicao_pagamento_id', 'erp_online', 'CondicaoPagamento', 'id', '{descricao}','descricao asc' , $criteria_condicao_pagamento_id );
        $vendedor_id = new TDBCombo('vendedor_id', 'erp_online', 'Vendedor', 'id', '{nome}','nome asc' , $criteria_vendedor_id );
        $observacao = new TEntry('observacao');
        $orcamento_item_orcamento_codigo_produto = new TSeekButton('orcamento_item_orcamento_codigo_produto');
        $produto_descricao = new TEntry('produto_descricao');
        $orcamento_item_orcamento_id = new THidden('orcamento_item_orcamento_id');
        $orcamento_item_orcamento_produto_id = new THidden('orcamento_item_orcamento_produto_id');
        $orcamento_item_orcamento_quantidade = new TNumeric('orcamento_item_orcamento_quantidade', '2', ',', '.' );
        $orcamento_item_orcamento_preco_unit = new TNumeric('orcamento_item_orcamento_preco_unit', '2', ',', '.' );
        $orcamento_item_orcamento_preco_tabela = new TNumeric('orcamento_item_orcamento_preco_tabela', '2', ',', '.' );
        $orcamento_item_orcamento_desconto = new TNumeric('orcamento_item_orcamento_desconto', '2', ',', '.' );
        $orcamento_item_orcamento_acrescimo = new TNumeric('orcamento_item_orcamento_acrescimo', '2', ',', '.' );
        $orcamento_item_orcamento_preco_total = new TNumeric('orcamento_item_orcamento_preco_total', '2', ',', '.' );
        $button__orcamento_item_orcamento = new TButton('button__orcamento_item_orcamento');

        $tabela_preco_id->setChangeAction(new TAction([$this,'onChangeTabeladePreco']));

        $codigo_cliente->setExitAction(new TAction([$this,'onExitCodigoCliente']));
        $orcamento_item_orcamento_codigo_produto->setExitAction(new TAction([$this,'onExitProduto']));

        $telefone->addValidation("Telefone", new TRequiredValidator()); 

        $emissao->setValue(date('d/m/Y'));
        $emissao->setMask('dd/mm/yyyy');
        $retorno->setMask('dd/mm/yyyy');

        $emissao->setDatabaseMask('yyyy-mm-dd');
        $retorno->setDatabaseMask('yyyy-mm-dd');

        $btn_view->setAction(new TAction([$this, 'ViewCliente']), "");
        $button__orcamento_item_orcamento->setAction(new TAction([$this, 'onAddDetailOrcamentoItemOrcamento'],['static' => 1]), "");

        $btn_view->addStyleClass('btn-info');
        $button__orcamento_item_orcamento->addStyleClass('btn-default');

        $btn_view->setImage('fas:eye #FFFFFF');
        $button__orcamento_item_orcamento->setImage('fas:plus #2ecc71');

        $email->setMaxLength(100);
        $telefone->setMaxLength(50);

        $vendedor_id->enableSearch();
        $tabela_preco_id->enableSearch();
        $orcamento_estado_id->enableSearch();
        $condicao_pagamento_id->enableSearch();

        $id->setEditable(false);
        $nome->setEditable(false);
        $emissao->setEditable(false);
        $cliente_id->setEditable(false);
        $produto_descricao->setEditable(false);
        $orcamento_estado_id->setEditable(false);
        $orcamento_item_orcamento_preco_total->setEditable(false);
        $orcamento_item_orcamento_preco_tabela->setEditable(false);

        $id->setSize(100);
        $email->setSize('100%');
        $cliente_id->setSize(100);
        $emissao->setSize('100%');
        $retorno->setSize('100%');
        $telefone->setSize('100%');
        $observacao->setSize('100%');
        $codigo_cliente->setSize(150);
        $vendedor_id->setSize('100%');
        $tabela_preco_id->setSize('100%');
        $nome->setSize('calc(100% - 230px)');
        $orcamento_estado_id->setSize('100%');
        $condicao_pagamento_id->setSize('100%');
        $orcamento_item_orcamento_id->setSize(200);
        $produto_descricao->setSize('calc(100% - 210px)');
        $orcamento_item_orcamento_produto_id->setSize(200);
        $orcamento_item_orcamento_desconto->setSize('100%');
        $orcamento_item_orcamento_acrescimo->setSize('100%');
        $orcamento_item_orcamento_quantidade->setSize('100%');
        $orcamento_item_orcamento_preco_unit->setSize('100%');
        $orcamento_item_orcamento_codigo_produto->setSize(190);
        $orcamento_item_orcamento_preco_tabela->setSize('100%');
        $orcamento_item_orcamento_preco_total->setSize('calc(100% - 50px)');

        $button__orcamento_item_orcamento->id = '65297d0b4bf1b';

        $seed = AdiantiApplicationConfig::get()['general']['seed'];
        $codigo_cliente_seekAction = new TAction(['ClienteSeekWindow', 'onShow']);
        $seekFilters = [];
        $seekFields = base64_encode(serialize([
            ['name'=> 'codigo_cliente', 'column'=>'{cod_erp}'],
            ['name'=> 'cliente_id', 'column'=>'{id}'],
            ['name'=> 'nome', 'column'=>'{razao}'],
            ['name'=> 'telefone', 'column'=>'{celular}'],
            ['name'=> 'email', 'column'=>'{email}'],
            ['name'=> 'tabela_preco_id', 'column'=>'{tabela_preco_id}'],
            ['name'=> 'condicao_pagamento_id', 'column'=>'{condicao_pagamento_id}'],
            ['name'=> 'vendedor_id', 'column'=>'{vendedor_id}']
        ]));

        $seekFilters = base64_encode(serialize($seekFilters));
        $codigo_cliente_seekAction->setParameter('_seek_filter_column', 'cod_erp');
        $codigo_cliente_seekAction->setParameter('_seek_fields', $seekFields);
        $codigo_cliente_seekAction->setParameter('_seek_filters', $seekFilters);
        $codigo_cliente_seekAction->setParameter('_seek_hash', md5($seed.$seekFields.$seekFilters));
        $codigo_cliente->setAction($codigo_cliente_seekAction);

        $seed = AdiantiApplicationConfig::get()['general']['seed'];
        $orcamento_item_orcamento_codigo_produto_seekAction = new TAction(['ViewProdutoOrcamentoSeekWindow', 'onShow']);
        $seekFilters = [];
        $seekFields = base64_encode(serialize([
            ['name'=> 'orcamento_item_orcamento_codigo_produto', 'column'=>'{produto_cod_erp}'],
            ['name'=> 'orcamento_item_orcamento_produto_id', 'column'=>'{produto_id}'],
            ['name'=> 'produto_descricao', 'column'=>'{produto_descricao}']
        ]));

        $seekFilters = base64_encode(serialize($seekFilters));
        $orcamento_item_orcamento_codigo_produto_seekAction->setParameter('_seek_fields', $seekFields);
        $orcamento_item_orcamento_codigo_produto_seekAction->setParameter('_seek_filters', $seekFilters);
        $orcamento_item_orcamento_codigo_produto_seekAction->setParameter('_seek_hash', md5($seed.$seekFields.$seekFilters));
        $orcamento_item_orcamento_codigo_produto->setAction($orcamento_item_orcamento_codigo_produto_seekAction);

        //$orcamento_item_orcamento_codigo_produto->disableIdSearch();
        //$orcamento_item_orcamento_codigo_produto->enableIdTextualSearch();

        $row1 = $this->form->addFields([new TLabel("Id:", null, '14px', null, '100%'),$cliente_id,$id],[new TLabel("Emissao:", null, '14px', null, '100%'),$emissao],[new TLabel("Retornar em:", null, '14px', null, '100%'),$retorno],[new TLabel("Situação:", null, '14px', null, '100%'),$orcamento_estado_id]);
        $row1->layout = ['col-sm-3','col-sm-3','col-sm-3','col-sm-3'];

        $row2 = $this->form->addFields([new TLabel("Cliente:", null, '14px', null, '100%'),$codigo_cliente,$nome,$btn_view],[new TLabel("Telefone:", '#ff0000', '14px', null, '100%'),$telefone]);
        $row2->layout = [' col-sm-8',' col-sm-4'];

        $row3 = $this->form->addFields([new TLabel("E-Mail:", null, '14px', null, '100%'),$email],[new TLabel("Tabela de Preços:", null, '14px', null, '100%'),$tabela_preco_id],[new TLabel("Condicao Pagamento:", null, '14px', null, '100%'),$condicao_pagamento_id],[new TLabel("Vendedor:", null, '14px', null, '100%'),$vendedor_id]);
        $row3->layout = [' col-sm-3',' col-sm-3',' col-sm-3',' col-sm-3'];

        $row4 = $this->form->addFields([new TLabel("Observação:", null, '14px', null, '100%'),$observacao]);
        $row4->layout = [' col-sm-12'];

        $bcontainer_itens = new BContainer('bcontainer_itens');
        $this->bcontainer_itens = $bcontainer_itens;

        $bcontainer_itens->setTitle("Itens", '#4CAF50', '18px', '', '#fff');
        $bcontainer_itens->setBorderColor('#4CAF50');

        $this->detailFormOrcamentoItemOrcamento = new BootstrapFormBuilder('detailFormOrcamentoItemOrcamento');
        $this->detailFormOrcamentoItemOrcamento->setProperty('style', 'border:none; box-shadow:none; width:100%;');

        $this->detailFormOrcamentoItemOrcamento->setProperty('class', 'form-horizontal builder-detail-form');

        $row5 = $this->detailFormOrcamentoItemOrcamento->addFields([new TLabel("Produto:", '#FF0000', '14px', null, '100%'),$orcamento_item_orcamento_codigo_produto,$produto_descricao],[new TLabel("Ultima Venda:", null, '14px', null, '100%')],[new TLabel("Ultimo Preço:", null, '14px', null, '100%'),$orcamento_item_orcamento_id],[new TLabel("Desconto:", null, '14px', null, '100%'),$orcamento_item_orcamento_produto_id]);
        $row5->layout = [' col-sm-6','col-sm-2','col-sm-2','col-sm-2'];

        $row6 = $this->detailFormOrcamentoItemOrcamento->addFields([new TLabel("Quantidade:", null, '14px', null, '100%'),$orcamento_item_orcamento_quantidade],[new TLabel("Valor Unitario:", null, '14px', null, '100%'),$orcamento_item_orcamento_preco_unit],[new TLabel("Valor Tabela:", null, '14px', null, '100%'),$orcamento_item_orcamento_preco_tabela],[new TLabel("Desconto:", null, '14px', null, '100%'),$orcamento_item_orcamento_desconto],[new TLabel("Acrescimo:", null, '14px', null, '100%'),$orcamento_item_orcamento_acrescimo],[new TLabel("Valor Total:", null, '14px', null, '100%'),$orcamento_item_orcamento_preco_total,$button__orcamento_item_orcamento]);
        $row6->layout = [' col-sm-2','col-sm-2','col-sm-2','col-sm-2','col-sm-2','col-sm-2'];

        $row7 = $this->detailFormOrcamentoItemOrcamento->addFields([new THidden('orcamento_item_orcamento__row__id')]);
        $this->orcamento_item_orcamento_criteria = new TCriteria();

        $this->orcamento_item_orcamento_list = new BootstrapDatagridWrapper(new TDataGrid);
        $this->orcamento_item_orcamento_list->generateHiddenFields();
        $this->orcamento_item_orcamento_list->setId('orcamento_item_orcamento_list');

        $this->orcamento_item_orcamento_list->style = 'width:100%';
        $this->orcamento_item_orcamento_list->class .= ' table-bordered';

        $column_orcamento_item_orcamento_produto_fabricante_cod_erp_orcamento_item_orcamento_produto_fabricante_descricao = new TDataGridColumn('{produto->fabricante->cod_erp} {produto->fabricante->descricao}', "Produto", 'left');
        $column_orcamento_item_orcamento_quantidade = new TDataGridColumn('quantidade', "Quantidade", 'left');
        $column_orcamento_item_orcamento_preco_unit = new TDataGridColumn('preco_unit', "Unitario", 'left');
        $column_orcamento_item_orcamento_desconto = new TDataGridColumn('desconto', "Desconto", 'left');
        $column_orcamento_item_orcamento_acrescimo = new TDataGridColumn('acrescimo', "Acrescimo", 'left');
        $column_orcamento_item_orcamento_preco_total = new TDataGridColumn('preco_total', "Total", 'left');

        $column_orcamento_item_orcamento__row__data = new TDataGridColumn('__row__data', '', 'center');
        $column_orcamento_item_orcamento__row__data->setVisibility(false);

        $action_onEditDetailOrcamentoItem = new TDataGridAction(array('OrcamentoForm', 'onEditDetailOrcamentoItem'));
        $action_onEditDetailOrcamentoItem->setUseButton(false);
        $action_onEditDetailOrcamentoItem->setButtonClass('btn btn-default btn-sm');
        $action_onEditDetailOrcamentoItem->setLabel("Editar");
        $action_onEditDetailOrcamentoItem->setImage('far:edit #478fca');
        $action_onEditDetailOrcamentoItem->setFields(['__row__id', '__row__data']);

        $this->orcamento_item_orcamento_list->addAction($action_onEditDetailOrcamentoItem);
        $action_onDeleteDetailOrcamentoItem = new TDataGridAction(array('OrcamentoForm', 'onDeleteDetailOrcamentoItem'));
        $action_onDeleteDetailOrcamentoItem->setUseButton(false);
        $action_onDeleteDetailOrcamentoItem->setButtonClass('btn btn-default btn-sm');
        $action_onDeleteDetailOrcamentoItem->setLabel("Excluir");
        $action_onDeleteDetailOrcamentoItem->setImage('fas:trash-alt #dd5a43');
        $action_onDeleteDetailOrcamentoItem->setFields(['__row__id', '__row__data']);

        $this->orcamento_item_orcamento_list->addAction($action_onDeleteDetailOrcamentoItem);

        $this->orcamento_item_orcamento_list->addColumn($column_orcamento_item_orcamento_produto_fabricante_cod_erp_orcamento_item_orcamento_produto_fabricante_descricao);
        $this->orcamento_item_orcamento_list->addColumn($column_orcamento_item_orcamento_quantidade);
        $this->orcamento_item_orcamento_list->addColumn($column_orcamento_item_orcamento_preco_unit);
        $this->orcamento_item_orcamento_list->addColumn($column_orcamento_item_orcamento_desconto);
        $this->orcamento_item_orcamento_list->addColumn($column_orcamento_item_orcamento_acrescimo);
        $this->orcamento_item_orcamento_list->addColumn($column_orcamento_item_orcamento_preco_total);

        $this->orcamento_item_orcamento_list->addColumn($column_orcamento_item_orcamento__row__data);

        $this->orcamento_item_orcamento_list->createModel();
        $tableResponsiveDiv = new TElement('div');
        $tableResponsiveDiv->class = 'table-responsive';
        $tableResponsiveDiv->add($this->orcamento_item_orcamento_list);
        $this->detailFormOrcamentoItemOrcamento->addContent([$tableResponsiveDiv]);
        $row8 = $bcontainer_itens->addFields([$this->detailFormOrcamentoItemOrcamento]);
        $row8->layout = [' col-sm-12'];

        $row9 = $this->form->addFields([$bcontainer_itens]);
        $row9->layout = [' col-sm-12'];

        // create the form actions
        $btn_onsave = $this->form->addAction("Salvar", new TAction([$this, 'onSave'],['static' => 1]), 'fas:save #ffffff');
        $this->btn_onsave = $btn_onsave;
        $btn_onsave->addStyleClass('btn-primary'); 

        $btn_onclear = $this->form->addAction("Limpar formulário", new TAction([$this, 'onClear']), 'fas:eraser #dd5a43');
        $this->btn_onclear = $btn_onclear;

        $btn_onshow = $this->form->addAction("Voltar", new TAction(['OrcamentoList', 'onShow']), 'fas:arrow-left #000000');
        $this->btn_onshow = $btn_onshow;

        // vertical box container
        $container = new TVBox;
        $container->style = 'width: 100%';
        $container->class = 'form-container';
        if(empty($param['target_container']))
        {
            $container->add(TBreadCrumb::create(["Desenvolvimento","Cadastro de orcamento"]));
        }
        $container->add($this->form);

        parent::add($container);

    }

    public static function onExitCodigoCliente($param = null) 
    {
        try 
        {

            /*
            TSession::setValue('OrcamentoForm_cliente_id', null);
            if(isset($param['cliente_id'])){

                TTransaction::open(self::$database);
                $oCliente = Cliente::where('cod_erp', '=', $param['cliente_id'])->first();
                if($oCliente){
                    TSession::setValue('OrcamentoForm_cliente_id', $oCliente->id);
                }
                TTransaction::close();
            }
            */
            /*
            $object = new stdClass();

            if(isset($param['codigo_cliente']) and strlen($param['codigo_cliente']) > 7 ){

                echo strlen($param['codigo_cliente']).'<br>';

                TTransaction::open(self::$database);

                $oCliente = Cliente::where('cod_erp', '=', $param['codigo_cliente'])->first();

                if($oCliente){

                    $object->cliente_id = $oCliente->id;
                    $object->codigo_cliente = $oCliente->cod_erp;

                }

                TTransaction::close();

            }else{

                $object->cliente_id = null;
                $object->codigo_cliente = null;
            }

            TForm::sendData(self::$formName, $object);
            */

        }
        catch (Exception $e) 
        {
            new TMessage('error', $e->getMessage());    
        }
    }

    public static function onExitProduto($param = null) 
    {
        try 
        {
            if(isset($param['orcamento_item_orcamento_produto_id'])){

            }

        }
        catch (Exception $e) 
        {
            new TMessage('error', $e->getMessage());    
        }
    }

    public static function onChangeTabeladePreco($param = null) 
    {
        try 
        {
            TSession::setValue('orcamento_tabela_preco_id', null);

            if(isset($param['tabela_preco_id'])){
                TSession::setValue('orcamento_tabela_preco_id', $param['tabela_preco_id']);
            }

        }
        catch (Exception $e) 
        {
            new TMessage('error', $e->getMessage());    
        }
    }

    public static function ViewCliente($param = null) 
    {
        try 
        {
            if(isset($param['cliente_id'])){
                TTransaction::open(self::$database);

                $pageParam = ['key' => $param['cliente_id']]; 
                TApplication::loadPage('PosisaoClienteFormView', 'onShow', $pageParam);                

                TTransaction::close();
            }else{
                TToast::show('error', "Selecione um Cliente", 'topRight', 'far:check-circle');
            }

        }
        catch (Exception $e) 
        {
            new TMessage('error', $e->getMessage());    
        }
    }

    public  function onAddDetailOrcamentoItemOrcamento($param = null) 
    {
        try
        {
            $data = $this->form->getData();

            $__row__id = !empty($data->orcamento_item_orcamento__row__id) ? $data->orcamento_item_orcamento__row__id : 'b'.uniqid();

            TTransaction::open(self::$database);

            $grid_data = new OrcamentoItem();
            $grid_data->__row__id = $__row__id;
            $grid_data->codigo_produto = $data->orcamento_item_orcamento_codigo_produto;
            $grid_data->produto_descricao = $data->produto_descricao;
            $grid_data->id = $data->orcamento_item_orcamento_id;
            $grid_data->produto_id = $data->orcamento_item_orcamento_produto_id;
            $grid_data->quantidade = $data->orcamento_item_orcamento_quantidade;
            $grid_data->preco_unit = $data->orcamento_item_orcamento_preco_unit;
            $grid_data->preco_tabela = $data->orcamento_item_orcamento_preco_tabela;
            $grid_data->desconto = $data->orcamento_item_orcamento_desconto;
            $grid_data->acrescimo = $data->orcamento_item_orcamento_acrescimo;
            $grid_data->preco_total = $data->orcamento_item_orcamento_preco_total;

            $__row__data = array_merge($grid_data->toArray(), (array)$grid_data->getVirtualData());
            $__row__data['__row__id'] = $__row__id;
            $__row__data['__display__']['codigo_produto'] =  $param['orcamento_item_orcamento_codigo_produto'] ?? null;
            $__row__data['__display__']['produto_descricao'] =  $param['produto_descricao'] ?? null;
            $__row__data['__display__']['id'] =  $param['orcamento_item_orcamento_id'] ?? null;
            $__row__data['__display__']['produto_id'] =  $param['orcamento_item_orcamento_produto_id'] ?? null;
            $__row__data['__display__']['quantidade'] =  $param['orcamento_item_orcamento_quantidade'] ?? null;
            $__row__data['__display__']['preco_unit'] =  $param['orcamento_item_orcamento_preco_unit'] ?? null;
            $__row__data['__display__']['preco_tabela'] =  $param['orcamento_item_orcamento_preco_tabela'] ?? null;
            $__row__data['__display__']['desconto'] =  $param['orcamento_item_orcamento_desconto'] ?? null;
            $__row__data['__display__']['acrescimo'] =  $param['orcamento_item_orcamento_acrescimo'] ?? null;
            $__row__data['__display__']['preco_total'] =  $param['orcamento_item_orcamento_preco_total'] ?? null;

            $grid_data->__row__data = base64_encode(serialize((object)$__row__data));
            $row = $this->orcamento_item_orcamento_list->addItem($grid_data);
            $row->id = $grid_data->__row__id;

            TDataGrid::replaceRowById('orcamento_item_orcamento_list', $grid_data->__row__id, $row);

            TTransaction::close();

            $data = new stdClass;
            $data->orcamento_item_orcamento_codigo_produto = '';
            $data->produto_descricao = '';
            $data->orcamento_item_orcamento_id = '';
            $data->orcamento_item_orcamento_produto_id = '';
            $data->orcamento_item_orcamento_quantidade = '';
            $data->orcamento_item_orcamento_preco_unit = '';
            $data->orcamento_item_orcamento_preco_tabela = '';
            $data->orcamento_item_orcamento_desconto = '';
            $data->orcamento_item_orcamento_acrescimo = '';
            $data->orcamento_item_orcamento_preco_total = '';
            $data->orcamento_item_orcamento__row__id = '';

            TForm::sendData(self::$formName, $data);
            TScript::create("
               var element = $('#65297d0b4bf1b');
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

    public static function onEditDetailOrcamentoItem($param = null) 
    {
        try
        {

            $__row__data = unserialize(base64_decode($param['__row__data']));
            $__row__data->__display__ = is_array($__row__data->__display__) ? (object) $__row__data->__display__ : $__row__data->__display__;
            $fireEvents = true;
            $aggregate = false;

            $data = new stdClass;
            $data->orcamento_item_orcamento_codigo_produto = $__row__data->__display__->codigo_produto ?? null;
            $data->produto_descricao = $__row__data->__display__->produto_descricao ?? null;
            $data->orcamento_item_orcamento_id = $__row__data->__display__->id ?? null;
            $data->orcamento_item_orcamento_produto_id = $__row__data->__display__->produto_id ?? null;
            $data->orcamento_item_orcamento_quantidade = $__row__data->__display__->quantidade ?? null;
            $data->orcamento_item_orcamento_preco_unit = $__row__data->__display__->preco_unit ?? null;
            $data->orcamento_item_orcamento_preco_tabela = $__row__data->__display__->preco_tabela ?? null;
            $data->orcamento_item_orcamento_desconto = $__row__data->__display__->desconto ?? null;
            $data->orcamento_item_orcamento_acrescimo = $__row__data->__display__->acrescimo ?? null;
            $data->orcamento_item_orcamento_preco_total = $__row__data->__display__->preco_total ?? null;
            $data->orcamento_item_orcamento__row__id = $__row__data->__row__id;

            TForm::sendData(self::$formName, $data, $aggregate, $fireEvents);
            TScript::create("
               var element = $('#65297d0b4bf1b');
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
    public static function onDeleteDetailOrcamentoItem($param = null) 
    {
        try
        {

            $__row__data = unserialize(base64_decode($param['__row__data']));

            $data = new stdClass;
            $data->orcamento_item_orcamento_codigo_produto = '';
            $data->produto_descricao = '';
            $data->orcamento_item_orcamento_id = '';
            $data->orcamento_item_orcamento_produto_id = '';
            $data->orcamento_item_orcamento_quantidade = '';
            $data->orcamento_item_orcamento_preco_unit = '';
            $data->orcamento_item_orcamento_preco_tabela = '';
            $data->orcamento_item_orcamento_desconto = '';
            $data->orcamento_item_orcamento_acrescimo = '';
            $data->orcamento_item_orcamento_preco_total = '';
            $data->orcamento_item_orcamento__row__id = '';

            TForm::sendData(self::$formName, $data);

            TDataGrid::removeRowById('orcamento_item_orcamento_list', $__row__data->__row__id);
            TScript::create("
               var element = $('#65297d0b4bf1b');
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

            $object = new Orcamento(); // create an empty object 

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

            $orcamento_item_orcamento_items = $this->storeMasterDetailItems('OrcamentoItem', 'orcamento_id', 'orcamento_item_orcamento', $object, $param['orcamento_item_orcamento_list___row__data'] ?? [], $this->form, $this->orcamento_item_orcamento_list, function($masterObject, $detailObject){ 

                //code here

            }, $this->orcamento_item_orcamento_criteria); 

            // get the generated {PRIMARY_KEY}
            $data->id = $object->id; 

            $this->form->setData($data); // fill form data
            TTransaction::close(); // close the transaction

            TToast::show('success', "Registro salvo", 'topRight', 'far:check-circle');
            TApplication::loadPage('OrcamentoList', 'onShow', $loadPageParam); 

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

                $object = new Orcamento($key); // instantiates the Active Record 

                TSession::setValue('OrcamentoForm_cliente_id', null);
                TSession::setValue('orcamento_tabela_preco_id', null);

                if(isset($object->cliente_id)){
                    TSession::setValue('OrcamentoForm_cliente_id', $object->cliente_id);
                    $object->codigo_cliente = $object->cliente->cod_erp;
                }

                if(isset($object->tabela_preco_id)){
                   TSession::setValue('orcamento_tabela_preco_id', $object->tabela_preco_id);
                }

                $orcamento_item_orcamento_items = $this->loadMasterDetailItems('OrcamentoItem', 'orcamento_id', 'orcamento_item_orcamento', $object, $this->form, $this->orcamento_item_orcamento_list, $this->orcamento_item_orcamento_criteria, function($masterObject, $detailObject, $objectItems){ 

                    //code here

                    $objectItems->orcamento_item_orcamento_codigo_produto = null;
                    if(isset($detailObject->codigo_produto) && $detailObject->codigo_produto)
                    {
                        $objectItems->__display__->codigo_produto = $detailObject->codigo_produto;
                    }

                    $objectItems->orcamento_item_orcamento_codigo_produto = null;
                    if(isset($detailObject->codigo_produto) && $detailObject->codigo_produto)
                    {
                        $objectItems->__display__->codigo_produto = $detailObject->codigo_produto;
                    }

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
            if(isset($object->codigo_cliente))
            {
                $value = $object->codigo_cliente;

                $obj->codigo_cliente = $value;
            }
            if(isset($object->orcamento_item_orcamento_codigo_produto))
            {
                $value = $object->orcamento_item_orcamento_codigo_produto;

                $obj->orcamento_item_orcamento_codigo_produto = $value;
            }
        }
        elseif(is_object($object))
        {
            if(isset($object->codigo_cliente))
            {
                $value = $object->codigo_cliente;

                $obj->codigo_cliente = $value;
            }
            if(isset($object->codigo_produto))
            {
                $value = $object->codigo_produto;

                $obj->orcamento_item_orcamento_codigo_produto = $value;
            }
        }
        TForm::sendData(self::$formName, $obj);
    }  

    public static function getFormName()
    {
        return self::$formName;
    }

}

