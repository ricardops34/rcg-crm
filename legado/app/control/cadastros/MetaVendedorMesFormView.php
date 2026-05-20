<?php

class MetaVendedorMesFormView extends TWindow
{
    protected $form; // form
    private static $database = 'erp_online';
    private static $activeRecord = 'MetaVendedorMes';
    private static $primaryKey = 'id';
    private static $formName = 'formView_MetaVendedorMes';

    /**
     * Form constructor
     * @param $param Request
     */
    public function __construct( $param )
    {
        parent::__construct();

        parent::setSize(0.8, null);
        parent::setTitle("MetaVendedorConsulta");
        parent::setProperty('class', 'window_modal');

        if(!empty($param['target_container']))
        {
            $this->adianti_target_container = $param['target_container'];
        }

        TTransaction::open(self::$database);
        // creates the form
        $this->form = new BootstrapFormBuilder(self::$formName);
        $this->form->setTagName('div');

        $meta_vendedor_mes = new MetaVendedorMes($param['key']);
        // define the form title
        $this->form->setFormTitle("MetaVendedorConsulta");

        $label2 = new TLabel("Vendedor id:", '', '12px', '', '100%');
        $text2 = new TTextDisplay($meta_vendedor_mes->vendedor->nome, '', '12px', '');
        $label3 = new TLabel("Mes:", '', '12px', '', '100%');
        $text3 = new TTextDisplay($meta_vendedor_mes->mes, '', '12px', '');
        $label4 = new TLabel("Ano:", '', '12px', '', '100%');
        $text4 = new TTextDisplay($meta_vendedor_mes->ano, '', '12px', '');
        $label6 = new TLabel("Valor:", '', '12px', '', '100%');
        $text6 = new TTextDisplay(number_format((double)$meta_vendedor_mes->valor, '2', ',', '.'), '', '12px', '');
        $label7 = new TLabel("Positivação:", '', '12px', '', '100%');
        $text7 = new TTextDisplay(number_format((double)$meta_vendedor_mes->numero_cliente, '2', ',', '.'), '', '12px', '');


        $row1 = $this->form->addFields([$label2,$text2],[$label3,$text3],[$label4,$text4],[$label6,$text6],[$label7,$text7]);
        $row1->layout = [' col-sm-4','col-sm-2','col-sm-2','col-sm-2','col-sm-2'];

        $this->meta_vendedor_categoria_meta_vendedor_mes_id_list = new TQuickGrid;
        $this->meta_vendedor_categoria_meta_vendedor_mes_id_list->style = 'width:100%';
        $this->meta_vendedor_categoria_meta_vendedor_mes_id_list->disableDefaultClick();

        $column_cod_erp = $this->meta_vendedor_categoria_meta_vendedor_mes_id_list->addQuickColumn("Código", 'cod_erp', 'left' , '10%');
        $column_descricao = $this->meta_vendedor_categoria_meta_vendedor_mes_id_list->addQuickColumn("Descrição", 'descricao', 'left' , '60%');
        $column_valor_transformed = $this->meta_vendedor_categoria_meta_vendedor_mes_id_list->addQuickColumn("Valor", 'valor', 'right' , '30%');

        $column_valor_transformed->setTransformer(function($value, $object, $row, $cell = null, $last_row = null)
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

        $this->meta_vendedor_categoria_meta_vendedor_mes_id_list->createModel();

        $criteria_meta_vendedor_categoria_meta_vendedor_mes_id = new TCriteria();
        $criteria_meta_vendedor_categoria_meta_vendedor_mes_id->add(new TFilter('meta_vendedor_mes_id', '=', $meta_vendedor_mes->id));

        $criteria_meta_vendedor_categoria_meta_vendedor_mes_id->setProperty('order', 'cod_erp desc');

        $meta_vendedor_categoria_meta_vendedor_mes_id_items = MetaVendedorCategoria::getObjects($criteria_meta_vendedor_categoria_meta_vendedor_mes_id);

        $this->meta_vendedor_categoria_meta_vendedor_mes_id_list->addItems($meta_vendedor_categoria_meta_vendedor_mes_id_items);

        $panel = new TElement('div');
        $panel->class = 'formView-detail';
        $panel->add(new BootstrapDatagridWrapper($this->meta_vendedor_categoria_meta_vendedor_mes_id_list));

        $this->form->addContent([$panel]);


        TTransaction::close();
        parent::add($this->form);

    }

    public function onShow($param = null)
    {     

    }

}

