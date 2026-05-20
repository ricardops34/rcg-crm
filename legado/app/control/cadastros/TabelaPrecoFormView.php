<?php

class TabelaPrecoFormView extends TPage
{
    protected $form; // form
    private static $database = 'erp_online';
    private static $activeRecord = 'TabelaPreco';
    private static $primaryKey = 'id';
    private static $formName = 'formView_TabelaPreco';

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

        TTransaction::open(self::$database);
        // creates the form
        $this->form = new BootstrapFormBuilder(self::$formName);
        $this->form->setTagName('div');

        $tabela_preco = new TabelaPreco($param['key']);
        // define the form title
        $this->form->setFormTitle("TabelaPrecoFormView");

        $label4 = new TLabel("Código:", '', '17px', '', '100%');
        $text4 = new TTextDisplay($tabela_preco->cod_erp, '', '12px', 'B');
        $label5 = new TLabel("Descricao:", '', '17px', '', '100%');
        $text5 = new TTextDisplay($tabela_preco->descricao, '', '12px', 'B');
        $label6 = new TLabel("Tabela:", '', '17px', '', '100%');
        $text6 = new TTextDisplay($tabela_preco->status, '', '12px', 'B');


        $text6 = new TTextDisplay('Bloqueado', '', '12px', '');
        if($tabela_preco->status == 'S'){
            $text6 = new TTextDisplay('Ativo', '', '12px', '');
        }

        $row1 = $this->form->addFields([$label4,$text4],[$label5,$text5],[$label6,$text6]);
        $row1->layout = [' col-sm-3',' col-sm-7','col-sm-2'];

        $this->tabela_preco_item_tabela_preco_id_list = new TQuickGrid;
        $this->tabela_preco_item_tabela_preco_id_list->style = 'width:100%';
        $this->tabela_preco_item_tabela_preco_id_list->disableDefaultClick();

        $column_item_transformed = $this->tabela_preco_item_tabela_preco_id_list->addQuickColumn("Item", 'item', 'left' , '5%');
        $column_produto_cod_erp_produto_descricao = $this->tabela_preco_item_tabela_preco_id_list->addQuickColumn("Produto", '{produto->cod_erp} - {produto->descricao}', 'left' , '55%');
        $column_preco_transformed = $this->tabela_preco_item_tabela_preco_id_list->addQuickColumn("Preco", 'preco', 'left' , '30%');
        $column_produto_status_transformed = $this->tabela_preco_item_tabela_preco_id_list->addQuickColumn("Situação", 'produto->status', 'center' , '10%');

        $column_item_transformed->setTransformer(function($value, $object, $row, $cell = null, $last_row = null)
        {

            if(!$value)
            {
                $value = 0;
            }

            if(is_numeric($value))
            {
                return str_pad($value , 4 , '0' , STR_PAD_LEFT);
            }
            else
            {
                return $value;
            }

        });

        $column_preco_transformed->setTransformer(function($value, $object, $row, $cell = null, $last_row = null)
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

        $column_produto_status_transformed->setTransformer(function($value, $object, $row, $cell = null, $last_row = null)
        {

            $class = ($value=='B') ? 'warning'      : 'success';
            $label = ($value=='B') ? 'Bloqueado'    : 'Ativo';
            $div = new TElement('span');
            $div->class="label label-{$class}";
            $div->style="width:120px; text-shadow:none; font-size:12px; font-weight:lighter";
            $div->add($label);
            return $div;

        });

        $this->tabela_preco_item_tabela_preco_id_list->createModel();

        $criteria_tabela_preco_item_tabela_preco_id = new TCriteria();
        $criteria_tabela_preco_item_tabela_preco_id->add(new TFilter('tabela_preco_id', '=', $tabela_preco->id));

        $criteria_tabela_preco_item_tabela_preco_id->setProperty('order', 'item asc');

        $filterVar = 'S';
        $criteria_tabela_preco_item_tabela_preco_id->add(new TFilter('status', '=', $filterVar));

        $tabela_preco_item_tabela_preco_id_items = TabelaPrecoItem::getObjects($criteria_tabela_preco_item_tabela_preco_id);

        $this->tabela_preco_item_tabela_preco_id_list->addItems($tabela_preco_item_tabela_preco_id_items);

        $panel = new TElement('div');
        $panel->class = 'formView-detail';
        $panel->add(new BootstrapDatagridWrapper($this->tabela_preco_item_tabela_preco_id_list));

        $this->form->addContent([$panel]);

        $this->tabela_preco_item_tabela_preco_id_list->setHeight(300);
        $this->tabela_preco_item_tabela_preco_id_list->makeScrollable();

        $btnTabelaPrecoListOnShowAction = new TAction(['TabelaPrecoList', 'onShow']);
        $btnTabelaPrecoListOnShowLabel = new TLabel("Voltar");

        $btnTabelaPrecoListOnShow = $this->form->addHeaderAction($btnTabelaPrecoListOnShowLabel, $btnTabelaPrecoListOnShowAction, 'fas:backward #FFFFFF'); 
        $btnTabelaPrecoListOnShow->addStyleClass('btn-success'); 
        $btnTabelaPrecoListOnShowLabel->setFontSize('12px'); 
        $btnTabelaPrecoListOnShowLabel->setFontColor('#FFFFFF'); 
        $btnTabelaPrecoListOnShowLabel->setFontStyle('B'); 

        // vertical box container
        $container = new TVBox;
        $container->style = 'width: 100%';
        $container->class = 'form-container';
        if(empty($param['target_container']))
        {
            $container->add(TBreadCrumb::create(["Cadastros","TabelaPrecoFormView"]));
        }
        $container->add($this->form);

        TTransaction::close();
        parent::add($container);

    }

    public function onShow($param = null)
    {     

    }

}

