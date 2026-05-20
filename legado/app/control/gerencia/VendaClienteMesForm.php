<?php

class VendaClienteMesForm extends TPage
{
    protected $form;
    private $formFields = [];
    private static $database = '';
    private static $activeRecord = '';
    private static $primaryKey = '';
    private static $formName = 'form_VendaClienteMesForm';

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
        $this->form->setFormTitle("Venda Cliente");

        //var_dump($param);

        $Cliente = new TEntry('Cliente');
        $cliente_id = new THidden('cliente_id');
        $mes = new THidden('mes');
        $Vendedor = new TEntry('Vendedor');
        $ano = new THidden('ano');
        $vendedor_id = new THidden('vendedor_id');
        $vendas = new BPageContainer();


        $vendas->setAction(new TAction(['ClienteNotafiscalSimpleList', 'onShow'], $param));
        $vendas->setId('notas_cliente');
        $Cliente->setEditable(false);
        $Vendedor->setEditable(false);

        $mes->setSize(200);
        $ano->setSize(200);
        $vendas->setSize('100%');
        $Cliente->setSize('100%');
        $cliente_id->setSize(200);
        $Vendedor->setSize('100%');
        $vendedor_id->setSize(200);

        $loadingContainer = new TElement('div');
        $loadingContainer->style = 'text-align:center; padding:50px';

        $icon = new TElement('i');
        $icon->class = 'fas fa-spinner fa-spin fa-3x';

        $loadingContainer->add($icon);
        $loadingContainer->add('<br>Carregando');

        $vendas->add($loadingContainer);

        $this->vendas = $vendas;

        $this->form->appendPage("Base");

        $this->form->addFields([new THidden('current_tab')]);
        $this->form->setTabFunction("$('[name=current_tab]').val($(this).attr('data-current_page'));");

        $row1 = $this->form->addFields([new TLabel("Cliente:", null, '14px', null, '100%'),$Cliente,$cliente_id,$mes],[new TLabel("Vendedor:", null, '14px', null),$Vendedor,$ano,$vendedor_id]);
        $row1->layout = ['col-sm-6','col-sm-6'];

        $row2 = $this->form->addFields([new TFormSeparator("Notas Fiscais", '#333', '18', '#eee'),$vendas]);
        $row2->layout = [' col-sm-12'];

        // create the form actions

        parent::setTargetContainer('adianti_right_panel');

        $btnClose = new TButton('closeCurtain');
        $btnClose->class = 'btn btn-sm btn-default';
        $btnClose->style = 'margin-right:10px;';
        $btnClose->onClick = "Template.closeRightPanel();";
        $btnClose->setLabel("Fechar");
        $btnClose->setImage('fas:times');

        $this->form->addHeaderWidget($btnClose);

        parent::add($this->form);

        $style = new TStyle('right-panel > .container-part[page-name=VendaClienteMesForm]');
        $style->width = '40% !important';   
        $style->show(true);

    }

    public function onShow($param = null)
    {               

        $oTela = new stdClass();

        //var_dump($param['cliente_id']);$
        $oTela->cliente_id = $param['cliente_id'];
        $oTela->mes = $param['mes'];
        $oTela->ano = $param['ano'];
        /*
        $pageNotas = [
                "target_container" => 'container_titulos',
                "cliente_id" => $param['cliente_id'],
                "ano" => $param['ano'],
                "mes" => $param['mes'],
                "register_state" => 'false'
            ];

        TApplication::loadPage('VendaVendedorProdutoSimpleList', 'onShow', $pageNotas);
        */
        TTransaction::open('erp_online');

        if(isset($param['cliente_id'])){
            $oCliente = Cliente::find( $param['cliente_id'] );
            if($oCliente){

                $cCodigo = substr($oCliente->cod_erp, 0, 6);
                $cLoja = substr($oCliente->cod_erp, 6, 2);
                $cNome = rtrim(ltrim($oCliente->fantasia));//.'('. rtrim(ltrim($oCliente->razao)).')';
                $cCliente = "";
                if($oCliente->tipo == 'F'){
                    if(!empty($oCliente->razao)){
                        $cNome =  rtrim(ltrim($oCliente->razao));
                    }
                }

                $this->form->setFormTitle('Cliente: '.$cNome.' - '.$param['mes'].'/'.$param['ano']);

                $cCliente = $cCodigo.' '.$cLoja.'-'.$cNome;
                $oTela->Cliente  = $cCliente;//$oCliente->cod_erp.' - '.$oCliente->razao;
                $oTela->vendedor_id = $oCliente->vendedor->id;
                $oTela->Vendedor = $oCliente->vendedor->nome_reduzido;//$oCliente->cod_erp.' - '.$oCliente->razao;
            }
        }else{
            $oTela->Cliente = 'Cliente';
        }

        if(isset($param['vendedor_id'])){
            $oVendedor = Vendedor::find( $param['vendedor_id'] );
            if($oVendedor){
                $cNome = rtrim(ltrim($oVendedor->nome_reduzido));
                $cVendedor = "";
                if(empty($oVendedor->nome_reduzido)){
                    $cNome =  rtrim(ltrim($oVendedor->nome));
                }

                $cVendedor = $cNome;
                $oTela->vendedor_id = $oVendedor->id;
                $oTela->Vendedor = $cVendedor;
            }
        }

        TTransaction::close();
        TForm::sendData(self::$formName, $oTela);

    } 

}

