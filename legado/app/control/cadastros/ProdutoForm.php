<?php

class ProdutoForm extends TWindow
{
    protected BootstrapFormBuilder $form;
    private $formFields = [];
    private static $database = 'erp_online';
    private static $activeRecord = 'Produto';
    private static $primaryKey = 'id';
    private static $formName = 'form_ProdutoForm';

    /**
     * Form constructor
     * @param $param Request
     */
    public function __construct( $param )
    {
        parent::__construct();
        parent::setSize(0.8, null);
        parent::setTitle("Cadastro de produto");
        parent::setProperty('class', 'window_modal');

        if(!empty($param['target_container']))
        {
            $this->adianti_target_container = $param['target_container'];
        }

        // creates the form
        $this->form = new BootstrapFormBuilder(self::$formName);
        // define the form title
        $this->form->setFormTitle("Cadastro de produto");

        $criteria_filial_id = new TCriteria();
        $criteria_categoria_id = new TCriteria();
        $criteria_sub_categoria_id = new TCriteria();
        $criteria_fabricante_id = new TCriteria();
        $criteria_armazem_id = new TCriteria();

        $id = new THidden('id');
        $filial_id = new TDBCombo('filial_id', 'erp_online', 'Filial', 'id', '{apelido}','apelido asc' , $criteria_filial_id );
        $cod_erp = new TEntry('cod_erp');
        $status = new TCombo('status');
        $tipo = new TEntry('tipo');
        $descricao = new TEntry('descricao');
        $marca = new TEntry('marca');
        $um = new TEntry('um');
        $categoria_id = new TDBCombo('categoria_id', 'erp_online', 'Categoria', 'id', '{descricao}','descricao asc' , $criteria_categoria_id );
        $sub_categoria_id = new TDBCombo('sub_categoria_id', 'erp_online', 'SubCategoria', 'id', '{descricao}','descricao asc' , $criteria_sub_categoria_id );
        $ncm = new TEntry('ncm');
        $fabricante_id = new TDBCombo('fabricante_id', 'erp_online', 'Fabricante', 'id', '{descricao}','descricao asc' , $criteria_fabricante_id );
        $codigo_fabricante = new TEntry('codigo_fabricante');
        $armazem_id = new TDBCombo('armazem_id', 'erp_online', 'Armazem', 'id', '{descricao}','descricao asc' , $criteria_armazem_id );
        $ts_id = new TEntry('ts_id');
        $te_id = new TEntry('te_id');
        $peso_bruto = new TNumeric('peso_bruto', '2', ',', '.' );
        $peso = new TNumeric('peso', '2', ',', '.' );
        $qtd_embalagem = new TNumeric('qtd_embalagem', '2', ',', '.' );
        $ponto_pedido = new TNumeric('ponto_pedido', '2', ',', '.' );
        $estoque_seguranca = new TNumeric('estoque_seguranca', '2', ',', '.' );
        $saldo_estoque = new TNumeric('saldo_estoque', '2', ',', '.' );
        $observacao = new TText('observacao');
        $foto = new TImage('/produto/');

        $cod_erp->addValidation("Código", new TRequiredValidator()); 
        $descricao->addValidation("Descrição", new TRequiredValidator()); 

        $status->addItems(["S"=>"Ativo","N"=>"Bloqueado"]);
        $ponto_pedido->setEditable(false);
        $saldo_estoque->setEditable(false);
        $estoque_seguranca->setEditable(false);

        $status->enableSearch();
        $filial_id->enableSearch();
        $armazem_id->enableSearch();
        $categoria_id->enableSearch();
        $fabricante_id->enableSearch();
        $sub_categoria_id->enableSearch();

        $um->setMaxLength(2);
        $tipo->setMaxLength(2);
        $ncm->setMaxLength(20);
        $marca->setMaxLength(20);
        $cod_erp->setMaxLength(30);
        $descricao->setMaxLength(100);
        $codigo_fabricante->setMaxLength(60);

        $id->setSize(200);
        $um->setSize('100%');
        $ncm->setSize('100%');
        $tipo->setSize('100%');
        $peso->setSize('100%');
        $marca->setSize('100%');
        $ts_id->setSize('100%');
        $te_id->setSize('100%');
        $status->setSize('100%');
        $cod_erp->setSize('100%');
        $filial_id->setSize('100%');
        $descricao->setSize('100%');
        $armazem_id->setSize('100%');
        $peso_bruto->setSize('100%');
        $categoria_id->setSize('100%');
        $ponto_pedido->setSize('100%');
        $fabricante_id->setSize('100%');
        $qtd_embalagem->setSize('100%');
        $saldo_estoque->setSize('100%');
        $observacao->setSize('100%', 70);
        $sub_categoria_id->setSize('100%');
        $codigo_fabricante->setSize('100%');
        $estoque_seguranca->setSize('100%');

        $foto->width = '200px';
        $foto->height = '200px';

        $this->foto = $foto;

        /*
        if(isset($param['cod_erp'])){
            $filename = '/produto/'.ltrim(rtrim($param['cod_erp'])).'.png';
            echo $filename;

            if (file_exists($filename)) {
                $foto = new TImage($filename);
            } else {
                $foto = new TImage('/produto/semimagem.png');
            }
            $this->foto = $foto;
        }
        */

        $row1 = $this->form->addFields([new TLabel("Filial:", null, '14px', null, '100%'),$id,$filial_id],[new TLabel("Código:", '#ff0000', '14px', null, '100%'),$cod_erp],[new TLabel("Situação:", null, '14px', null, '100%'),$status],[new TLabel("Tipo:", null, '14px', null, '100%'),$tipo]);
        $row1->layout = [' col-sm-3',' col-sm-3',' col-sm-3',' col-sm-3'];

        $row2 = $this->form->addFields([new TLabel("Descrição:", '#ff0000', '14px', null, '100%'),$descricao],[new TLabel("Marca:", null, '14px', null, '100%'),$marca]);
        $row2->layout = ['col-sm-6','col-sm-6'];

        $row3 = $this->form->addFields([new TLabel("Unid. Medida:", null, '14px', null, '100%'),$um],[new TLabel("Categoria:", null, '14px', null, '100%'),$categoria_id],[new TLabel("Sub categoria:", null, '14px', null, '100%'),$sub_categoria_id],[new TLabel("NCM:", null, '14px', null, '100%'),$ncm]);
        $row3->layout = [' col-sm-3',' col-sm-3',' col-sm-3',' col-sm-3'];

        $row4 = $this->form->addFields([new TLabel("Fabricante:", null, '14px', null, '100%'),$fabricante_id],[new TLabel("Código Fabricante:", null, '14px', null, '100%'),$codigo_fabricante],[new TLabel("Armazem:", null, '14px', null, '100%'),$armazem_id],[new TLabel("Tipo de Saida:", null, '14px', null, '100%'),$ts_id],[new TLabel("Tipo de Entrada:", null, '14px', null, '100%'),$te_id]);
        $row4->layout = ['col-sm-3','col-sm-3',' col-sm-2',' col-sm-2',' col-sm-2'];

        $row5 = $this->form->addFields([new TLabel("Peso bruto:", null, '14px', null, '100%'),$peso_bruto],[new TLabel("Peso Liquido:", null, '14px', null, '100%'),$peso],[new TLabel("Qtd. Embalagem:", null, '14px', null, '100%'),$qtd_embalagem],[new TLabel("Ponto de Pedido:", null, '14px', null, '100%'),$ponto_pedido],[new TLabel("Estoque de Segurança:", null, '14px', null, '100%'),$estoque_seguranca],[new TLabel("Saldo de Estoque:", null, '14px', null, '100%'),$saldo_estoque]);
        $row5->layout = [' col-sm-2',' col-sm-2',' col-sm-2','col-sm-2','col-sm-2','col-sm-2'];

        $row6 = $this->form->addFields([new TLabel("Observação:", null, '14px', null, '100%'),$observacao],[$foto]);
        $row6->layout = [' col-sm-8',' col-sm-4 control-label'];

        // create the form actions
        $btn_onsave = $this->form->addAction("Salvar", new TAction([$this, 'onSave']), 'fas:save #ffffff');
        $this->btn_onsave = $btn_onsave;
        $btn_onsave->addStyleClass('btn-primary'); 

        $btn_onclear = $this->form->addAction("Limpar formulário", new TAction([$this, 'onClear']), 'fas:eraser #dd5a43');
        $this->btn_onclear = $btn_onclear;

        $btn_onshow = $this->form->addAction("Voltar", new TAction(['ProdutoList', 'onShow']), 'fas:arrow-left #000000');
        $this->btn_onshow = $btn_onshow;

        parent::add($this->form);

    }

    public function onSave($param = null) 
    {
        try
        {
            TTransaction::open(self::$database); // open a transaction

            $messageAction = null;

            $this->form->validate(); // validate form data

            $object = new Produto(); // create an empty object 

            $data = $this->form->getData(); // get form data as array
            $object->fromArray( (array) $data); // load the object with data

            $object->store(); // save the object 

            $loadPageParam = [];

            if(!empty($param['target_container']))
            {
                $loadPageParam['target_container'] = $param['target_container'];
            }

            // get the generated {PRIMARY_KEY}
            $data->id = $object->id; 

            $this->form->setData($data); // fill form data
            TTransaction::close(); // close the transaction

            TToast::show('success', "Registro salvo", 'topRight', 'far:check-circle');
            TApplication::loadPage('ProdutoList', 'onShow', $loadPageParam); 

                TWindow::closeWindow(parent::getId()); 

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

                $object = new Produto($key); // instantiates the Active Record 

                if(isset($object->cod_erp)){

                    $filename = '/produto/'.ltrim(rtrim($object->cod_erp)).'.PNG';

                    echo $filename;

                    if (file_exists($filename)) {
                        $object->foto = $filename;
                    } else {
                        $object->foto ='/produto/semimagem.png';
                    }

                    var_dump($object->foto);

                }

                $this->form->setData($object); // fill the form 

                TTransaction::close(); // close the transaction 
                if(TSession::getValue('login') == 'admin'){

                }else{
                    $this->form->setEditable(FALSE);
                    TScript::create('document.getElementById("tbutton_btn_salvar").remove();');
                    TScript::create('document.getElementById("tbutton_btn_limpar_formulário").remove();');   
                }

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

    public static function getFormName()
    {
        return self::$formName;
    }

}

