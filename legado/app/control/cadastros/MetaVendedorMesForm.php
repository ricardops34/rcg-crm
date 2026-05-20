<?php

class MetaVendedorMesForm extends TPage
{
    protected BootstrapFormBuilder $form;
    private $formFields = [];
    private static $database = 'erp_online';
    private static $activeRecord = 'MetaVendedorMes';
    private static $primaryKey = 'id';
    private static $formName = 'form_MetaVendedorMesForm';

    use BuilderMasterDetailFieldListTrait;

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
        $this->form->setFormTitle("Objetivo Vendedor Mes");

        $criteria_vendedor_id = new TCriteria();
        $criteria_meta_vendedor_categoria_meta_vendedor_mes_categoria_id = new TCriteria();

        $filterVar = "A";
        $criteria_vendedor_id->add(new TFilter('status', '=', $filterVar)); 
        $filterVar = 'S';
        $criteria_meta_vendedor_categoria_meta_vendedor_mes_categoria_id->add(new TFilter('usado', '=', $filterVar)); 

        $vendedor_id = new TDBCombo('vendedor_id', 'erp_online', 'Vendedor', 'id', '{cod_erp} - {nome_reduzido}','cod_erp asc' , $criteria_vendedor_id );
        $id = new THidden('id');
        $tipo = new TCombo('tipo');
        $numero_cliente = new TNumeric('numero_cliente', '0', ',', '.' );
        $novo_cliente = new TNumeric('novo_cliente', '2', ',', '.' );
        $mes = new TCombo('mes');
        $ano = new TCombo('ano');
        $valor = new TNumeric('valor', '2', ',', '.' );
        $carcateg = new TButton('carcateg');
        $LimpCate = new TButton('LimpCate');
        $meta_vendedor_categoria_meta_vendedor_mes_id = new THidden('meta_vendedor_categoria_meta_vendedor_mes_id[]');
        $meta_vendedor_categoria_meta_vendedor_mes___row__id = new THidden('meta_vendedor_categoria_meta_vendedor_mes___row__id[]');
        $meta_vendedor_categoria_meta_vendedor_mes___row__data = new THidden('meta_vendedor_categoria_meta_vendedor_mes___row__data[]');
        $meta_vendedor_categoria_meta_vendedor_mes_cod_erp = new THidden('meta_vendedor_categoria_meta_vendedor_mes_cod_erp[]');
        $meta_vendedor_categoria_meta_vendedor_mes_descricao = new THidden('meta_vendedor_categoria_meta_vendedor_mes_descricao[]');
        $meta_vendedor_categoria_meta_vendedor_mes_categoria_id = new TDBCombo('meta_vendedor_categoria_meta_vendedor_mes_categoria_id[]', 'erp_online', 'Categoria', 'id', '{cod_erp} - {descricao}','descricao asc' , $criteria_meta_vendedor_categoria_meta_vendedor_mes_categoria_id );
        $meta_vendedor_categoria_meta_vendedor_mes_valor = new TNumeric('meta_vendedor_categoria_meta_vendedor_mes_valor[]', '2', ',', '.' );
        $this->categorias = new TFieldList();

        $this->categorias->addField(null, $meta_vendedor_categoria_meta_vendedor_mes_id, []);
        $this->categorias->addField(null, $meta_vendedor_categoria_meta_vendedor_mes___row__id, ['uniqid' => true]);
        $this->categorias->addField(null, $meta_vendedor_categoria_meta_vendedor_mes___row__data, []);
        $this->categorias->addField(new TLabel("", null, '14px', null), $meta_vendedor_categoria_meta_vendedor_mes_cod_erp, ['width' => '10%']);
        $this->categorias->addField(new TLabel("", null, '14px', null), $meta_vendedor_categoria_meta_vendedor_mes_descricao, ['width' => '10%']);
        $this->categorias->addField(new TLabel("Categoria", null, '14px', null), $meta_vendedor_categoria_meta_vendedor_mes_categoria_id, ['width' => '50%']);
        $this->categorias->addField(new TLabel("Valor", null, '14px', null), $meta_vendedor_categoria_meta_vendedor_mes_valor, ['width' => '30%']);

        $this->categorias->width = '100%';
        $this->categorias->setFieldPrefix('meta_vendedor_categoria_meta_vendedor_mes');
        $this->categorias->name = 'categorias';

        $this->criteria_categorias = new TCriteria();
        $this->default_item_categorias = new stdClass();

        $this->form->addField($meta_vendedor_categoria_meta_vendedor_mes_id);
        $this->form->addField($meta_vendedor_categoria_meta_vendedor_mes___row__id);
        $this->form->addField($meta_vendedor_categoria_meta_vendedor_mes___row__data);
        $this->form->addField($meta_vendedor_categoria_meta_vendedor_mes_cod_erp);
        $this->form->addField($meta_vendedor_categoria_meta_vendedor_mes_descricao);
        $this->form->addField($meta_vendedor_categoria_meta_vendedor_mes_categoria_id);
        $this->form->addField($meta_vendedor_categoria_meta_vendedor_mes_valor);

        $this->categorias->setRemoveAction(null, 'fas:times #dd5a43', "Excluír");

        $tipo->setChangeAction(new TAction([$this,'onChangeTipo']));

        $vendedor_id->addValidation("Vendedor id", new TRequiredValidator()); 
        $tipo->addValidation("Selecione o Tipo", new TRequiredValidator()); 
        $mes->addValidation("Mes", new TRequiredValidator()); 
        $ano->addValidation("Ano", new TRequiredValidator()); 
        $valor->addValidation("Valor", new TRequiredValidator()); 

        $novo_cliente->setEditable(false);
        $LimpCate->setAction(new TAction([$this, 'onLimpa']), "Limpar ");
        $carcateg->setAction(new TAction([$this, 'onCarregar']), "Carregar");

        $LimpCate->addStyleClass('btn-danger');
        $carcateg->addStyleClass('btn-success');

        $LimpCate->setImage('far:trash-alt #FFFFFF');
        $carcateg->setImage('fas:sort-alpha-down #FFFFFF');

        $mes->addItems(TempoService::getMeses());
        $tipo->addItems(["G"=>"Geral","C"=>"Categoria"]);
        $ano->addItems(["2024"=>"2024","2025"=>"2025","2026"=>"2026","2027"=>"2027"]);

        $tipo->setValue('C');
        $valor->setValue('0');
        $ano->setValue(date('Y'));
        $mes->setValue( date('m'));

        $mes->enableSearch();
        $ano->enableSearch();
        $tipo->enableSearch();
        $vendedor_id->enableSearch();
        $meta_vendedor_categoria_meta_vendedor_mes_categoria_id->enableSearch();

        $id->setSize(200);
        $mes->setSize('100%');
        $ano->setSize('100%');
        $tipo->setSize('100%');
        $vendedor_id->setSize('100%');
        $novo_cliente->setSize('100%');
        $numero_cliente->setSize('100%');
        $valor->setSize('calc(50% - 10px)');
        $meta_vendedor_categoria_meta_vendedor_mes_cod_erp->setSize(200);
        $meta_vendedor_categoria_meta_vendedor_mes_valor->setSize('100%');
        $meta_vendedor_categoria_meta_vendedor_mes_descricao->setSize(200);
        $meta_vendedor_categoria_meta_vendedor_mes_categoria_id->setSize('100%');

        $carcateg->id = 'CarCatg';

        $row1 = $this->form->addFields([new TLabel("Vendedor:", '#ff0000', '14px', null, '100%'),$vendedor_id,$id],[new TLabel("Tipo Meta:", '#FF0000', '14px', null, '100%'),$tipo],[new TLabel("Positivação Cliente:", '#000000', '14px', null, '100%'),$numero_cliente],[new TLabel("Cliente Novo:", null, '14px', null, '100%'),$novo_cliente]);
        $row1->layout = [' col-sm-6','col-sm-2',' col-sm-2','col-sm-2'];

        $row2 = $this->form->addFields([new TLabel("Mes:", '#ff0000', '14px', null, '100%'),$mes],[new TLabel("Ano:", '#ff0000', '14px', null, '100%'),$ano],[new TLabel("Valor:", '#ff0000', '14px', null, '100%'),$valor,$carcateg,$LimpCate]);
        $row2->layout = [' col-sm-2',' col-sm-2',' col-sm-8'];

        $tab_categoria = new BootstrapFormBuilder('tab_categoria');
        $this->tab_categoria = $tab_categoria;
        $tab_categoria->setProperty('style', 'border:none; box-shadow:none;');

        $tab_categoria->appendPage("Categorias");

        $tab_categoria->addFields([new THidden('current_tab_tab_categoria')]);
        $tab_categoria->setTabFunction("$('[name=current_tab_tab_categoria]').val($(this).attr('data-current_page'));");

        $row3 = $tab_categoria->addFields([$this->categorias]);
        $row3->layout = [' col-sm-12'];

        $row4 = $this->form->addFields([$tab_categoria]);
        $row4->layout = [' col-sm-12'];

        // create the form actions
        $btn_onsave = $this->form->addAction("Salvar", new TAction([$this, 'onSave'],['static' => 1]), 'fas:save #ffffff');
        $this->btn_onsave = $btn_onsave;
        $btn_onsave->addStyleClass('btn-primary'); 

        $btn_onclear = $this->form->addAction("Limpar formulário", new TAction([$this, 'onClear']), 'fas:eraser #dd5a43');
        $this->btn_onclear = $btn_onclear;

        $btn_onshow = $this->form->addAction("Voltar", new TAction(['MetaVendedorMesList', 'onShow']), 'fas:arrow-left #000000');
        $this->btn_onshow = $btn_onshow;

        // vertical box container
        $container = new TVBox;
        $container->style = 'width: 100%';
        $container->class = 'form-container';
        if(empty($param['target_container']))
        {
            $container->add(TBreadCrumb::create(["Cadastros","Meta Vendedor Mes"]));
        }
        $container->add($this->form);

        parent::add($container);

    }

    public static function onChangeTipo($param = null) 
    {
        try 
        {
            if(isset($param['tipo'])){
                if($param['tipo'] == "G"){
                    TNumeric::enableField(self::$formName, 'valor');
                    TButton::disableField(self::$formName, 'carcateg');
                    TButton::disableField(self::$formName, 'LimpCate');
                    TFieldList::clearRows('categorias');
                    TFieldList::disableField('categorias');
                }elseif($param['tipo'] == "C"){
                    TNumeric::disableField(self::$formName, 'valor');
                    TButton::enableField(self::$formName, 'carcateg');
                    TButton::enableField(self::$formName, 'LimpCate');                    
                    TFieldList::enableField('categorias');
                }else{

                }
            }

        }
        catch (Exception $e) 
        {
            new TMessage('error', $e->getMessage());    
        }
    }

    public static function onCarregar($param = null) 
    {
        try 
        {
            TTransaction::open('erp_online');

            $oCategorias = Categoria::where('usado', '=', 'S')
                        ->orderBy('cod_erp')
                        ->load();

            if($oCategorias){
                $data = new stdClass();
                $data->meta_vendedor_categoria_meta_vendedor_mes_categoria_id = [];
                $data->meta_vendedor_categoria_meta_vendedor_mes_valor = [];

                // limpa o TFieldList
                TFieldList::clearRows('categorias');
                $i = 0;
                $nTime = 50;

                foreach ($oCategorias as $oCategoria) {
                    //echo $i. ' - '.$oCategoria->descricao.'<br>';
                    $data->meta_vendedor_categoria_meta_vendedor_mes_categoria_id[] = $oCategoria->id;
                    $data->meta_vendedor_categoria_meta_vendedor_mes_cod_erp[] = $oCategoria->cod_erp;
                    $data->meta_vendedor_categoria_meta_vendedor_mes_descricao[] = $oCategoria->descricao;
                    $data->meta_vendedor_categoria_meta_vendedor_mes_valor[] = 0;
                    $i++;
                }

                // adicionamos as linhas novas
                TFieldList::addRows('categorias', $i - 1);

                // enviando os dados para o field list
                TForm::sendData(self::$formName, $data, false, true,$i*$nTime);//, 1000);
            }

            TTransaction::close();

        }
        catch (Exception $e) 
        {
            new TMessage('error', $e->getMessage());    
        }
    }

    public static function onLimpa($param = null) 
    {
        try 
        {
            TFieldList::clearRows('categorias');

        }
        catch (Exception $e) 
        {
            new TMessage('error', $e->getMessage());    
        }
    }

    public function onSave($param = null) 
    {
        try
        {
            TTransaction::open(self::$database); // open a transaction

            $messageAction = null;

            $this->form->validate(); // validate form data

            $object = new MetaVendedorMes(); // create an empty object 

            $data = $this->form->getData(); // get form data as array
            $object->fromArray( (array) $data); // load the object with data

            $object->store(); // save the object 

            $loadPageParam = [];

            if(!empty($param['target_container']))
            {
                $loadPageParam['target_container'] = $param['target_container'];
            }

            if($object->tipo == "G"){

            }else{

            $object->valor = 0;

//<generatedAutoCode>
            $this->criteria_categorias->setProperty('order', 'cod_erp desc');
//</generatedAutoCode>
            $meta_vendedor_categoria_meta_vendedor_mes_items = $this->storeItems('MetaVendedorCategoria', 'meta_vendedor_mes_id', $object, $this->categorias, function($masterObject, $detailObject){ 

                $masterObject->valor += $detailObject->valor;

            }, $this->criteria_categorias); 
            }

            $object->store();

            // get the generated {PRIMARY_KEY}
            $data->id = $object->id; 

            $this->form->setData($data); // fill form data
            TTransaction::close(); // close the transaction

            TToast::show('success', "Registro salvo", 'topRight', 'far:check-circle');
            TApplication::loadPage('MetaVendedorMesList', 'onShow', $loadPageParam); 

            TForm::sendData(self::$formName, (object)['id' => $object->id]);

        }
        catch (Exception $e) // in case of exception
        {

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

                $object = new MetaVendedorMes($key); // instantiates the Active Record 

                if(isset($object->tipo)){
                    if($object->tipo == "G"){
                        TNumeric::enableField(self::$formName, 'valor');
                        TButton::disableField(self::$formName, 'carcateg');
                        TButton::disableField(self::$formName, 'LimpCate');
                        TFieldList::clearRows('categorias');
                        TFieldList::disableField('categorias');
                    }elseif($object->tipo == "C"){
                        TNumeric::disableField(self::$formName, 'valor');
                        TButton::enableField(self::$formName, 'carcateg');
                        TButton::enableField(self::$formName, 'LimpCate');                    
                        TFieldList::enableField('categorias');
                    }else{

                    }
                }else{
                    TNumeric::enableField(self::$formName, 'valor');
                    TButton::disableField(self::$formName, 'carcateg');
                    TButton::disableField(self::$formName, 'LimpCate');
                    TFieldList::clearRows('categorias');
                    TFieldList::disableField('categorias');
                }

                $this->criteria_categorias->setProperty('order', 'categoria_id asc');
                $this->categorias_items = $this->loadItems('MetaVendedorCategoria', 'meta_vendedor_mes_id', $object, $this->categorias, function($masterObject, $detailObject, $objectItems){ 

                    //code here

                }, $this->criteria_categorias); 

                $this->form->setData($object); // fill the form 

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

        $this->categorias->addHeader();
        $this->categorias->addDetail($this->default_item_categorias);

        $this->categorias->addCloneAction(null, 'fas:plus #69aa46', "Clonar");

    }

    public function onShow($param = null)
    {
        $this->categorias->addHeader();
        $this->categorias->addDetail($this->default_item_categorias);

        $this->categorias->addCloneAction(null, 'fas:plus #69aa46', "Clonar");

    } 

    public static function getFormName()
    {
        return self::$formName;
    }

    public  function onView($param = null) 
    {
        try 
        {
            if (isset($param['key']))
            {

                $key = $param['key'];  
                TTransaction::open(self::$database); 

                $object = new MetaVendedorMes($key); 

                TNumeric::disableField(self::$formName, 'valor');
                TButton::disableField(self::$formName, 'carcateg');
                TButton::disableField(self::$formName, 'LimpCate');
                TFieldList::disableField(self::$formName,'categorias');

                TButton::disableField(self::$formName, 'btn_onsave');
                TButton::disableField(self::$formName, 'btn_onclear');

                $this->criteria_categorias->setProperty('order', 'categoria_id asc');
                $this->categorias_items = $this->loadItems('MetaVendedorCategoria', 'meta_vendedor_mes_id', $object, $this->categorias, function($masterObject, $detailObject, $objectItems){ 
                }, $this->criteria_categorias); 

                $this->form->setData($object); 

                $this->btn_onsave->style = 'display:none';
                $this->btn_onclear->style = 'display:none';
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
        }
    }

}

