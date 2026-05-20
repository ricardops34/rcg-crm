<?php

use NFePHP\DA\NFe\Danfe;

class NotaSaidaFormView extends TPage
{
    protected $form; // form
    private static $database = 'erp_online';
    private static $activeRecord = 'NotaSaida';
    private static $primaryKey = 'id';
    private static $formName = 'formView_NotaSaida';

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

        $nota_saida = new NotaSaida($param['key']);
        // define the form title
        $this->form->setFormTitle("Nota Fiscal");

        $transformed_nota_saida_condicao_id = call_user_func(function($value, $object, $row)
        {

                TTransaction::open('erp_online');

                $oCondicao= CondicaoPagamento::where('id', '=', $value)->first();

                TTransaction::close();

                if($oCondicao){ 
                    return ltrim(rtrim($oCondicao->descricao));
                }else{
                    if(empty($value)){
                        return "Sem Pagamento";
                    }
                    return $value;
                }

        }, $nota_saida->condicao_id, $nota_saida, null);    

        $transformed_nota_saida_vendedor1_id = call_user_func(function($value, $object, $row)
        {

                TTransaction::open('erp_online');

                $oVendedor = Vendedor::where('id', '=', $value)->first();

                TTransaction::close();

                if($oVendedor){ 
                    return ltrim(rtrim($oVendedor->nome_reduzido));
                }else{
                    return $value;
                }

        }, $nota_saida->vendedor1_id, $nota_saida, null);

        $this->form->setFormTitle("Nota Fiscal: ".$nota_saida->nota_fiscal);

        $label3 = new TLabel("Cliente:", '', '14px', 'B', '100%');
        $text3 = new TTextDisplay($nota_saida->cliente->razao, '', '12px', '');
        $label5 = new TLabel("Nota Fiscal:", '', '14px', 'B', '100%');
        $text5 = new TTextDisplay($nota_saida->nota_fiscal, '', '12px', '');
        $label6 = new TLabel("Serie:", '', '14px', 'B', '100%');
        $text6 = new TTextDisplay($nota_saida->serie_fiscal, '', '12px', '');
        $label7 = new TLabel("Especie Fiscal:", '', '14px', 'B', '100%');
        $text7 = new TTextDisplay($nota_saida->especie_fiscal, '', '12px', '');
        $label11 = new TLabel("Emissão:", '', '14px', 'B', '100%');
        $text11 = new TTextDisplay(TDate::convertToMask($nota_saida->dt_emissao, 'yyyy-mm-dd', 'dd/mm/yyyy'), '', '12px', '');
        $label4 = new TLabel("Id:", '', '12px', 'B', '100%');
        $text_id = new TTextDisplay($nota_saida->id, '', '12px', '');
        $label8 = new TLabel("Condicao:", '', '14px', 'B', '100%');
        $text8 = new TTextDisplay($transformed_nota_saida_condicao_id, '', '12px', '');
        $label26 = new TLabel("Vendedor:", '', '14px', 'B', '100%');
        $text26 = new TTextDisplay($transformed_nota_saida_vendedor1_id, '', '12px', '');
        $label23 = new TLabel("Transportadora:", '', '14px', 'B', '100%');
        $text23 = new TTextDisplay($nota_saida->transportadora, '', '12px', '');
        $label28 = new TLabel("Chave:", '', '14px', 'B', '100%');
        $text28 = new TTextDisplay($nota_saida->chave_nfe, '', '12px', '');
        $label31 = new TLabel("Observações:", '', '14px', 'B', '100%');
        $text31 = new TTextDisplay($nota_saida->mensagem_nf, '', '12px', '');
        $total = new TLabel("Total:", '', '14px', 'B', '100%');
        $monetarytext17 = new TTextDisplay(number_format((double)$nota_saida->vlr_itens, '2', ',', '.'), '', '12px', '');
        $devolucao = new TLabel("Devolução:", '', '14px', 'B', '100%');
        $monetarytext13 = new TTextDisplay(number_format((double)$nota_saida->vlr_devolucao, '2', ',', '.'), '', '12px', '');
        $comodato = new TLabel("Comodato:", '', '14px', 'B', '100%');
        $monetarytext15 = new TTextDisplay(number_format((double)$nota_saida->vlr_comodato, '2', ',', '.'), '', '12px', '');

        //$tbutton2->setAction(new TAction([$this, 'onGeraDanfe'],['key' => $nota_saida->id]), "Danfe");

        $row1 = $this->form->addFields([$label3,$text3],[$label5,$text5],[$label6,$text6],[$label7,$text7],[$label11,$text11],[$label4,$text_id]);
        $row1->layout = [' col-sm-3',' col-sm-2',' col-sm-2','col-sm-2','col-sm-2',' col-sm-1'];

        $row2 = $this->form->addFields([$label8,$text8],[$label26,$text26],[$label23,$text23],[$label28,$text28]);
        $row2->layout = [' col-sm-2',' col-sm-2','col-sm-2',' col-sm-6'];

        $row3 = $this->form->addFields([$label31,$text31],[$total,$monetarytext17],[$devolucao,$monetarytext13],[$comodato,$monetarytext15]);
        $row3->layout = [' col-sm-6','col-sm-2','col-sm-2','col-sm-2'];

        $this->nota_saida_item_nota_saida_id_list = new TQuickGrid;
        $this->nota_saida_item_nota_saida_id_list->style = 'width:100%';
        $this->nota_saida_item_nota_saida_id_list->disableDefaultClick();

        $column_item = $this->nota_saida_item_nota_saida_id_list->addQuickColumn("Item", 'item', 'left' , '5%');
        $column_produto_descricao = $this->nota_saida_item_nota_saida_id_list->addQuickColumn("Produto", 'produto->descricao', 'left' , '25%');
        $column_vlr_tabela_transformed = $this->nota_saida_item_nota_saida_id_list->addQuickColumn("Tabela", 'vlr_tabela', 'right');
        $column_perc_desconto_transformed = $this->nota_saida_item_nota_saida_id_list->addQuickColumn("%", 'perc_desconto', 'right');
        $column_vlr_desconto_transformed = $this->nota_saida_item_nota_saida_id_list->addQuickColumn("Desconto", 'vlr_desconto', 'right');
        $column_vlr_unitario_transformed = $this->nota_saida_item_nota_saida_id_list->addQuickColumn("Unitario", 'vlr_unitario', 'right');
        $column_qtd_transformed = $this->nota_saida_item_nota_saida_id_list->addQuickColumn("Quantidade", 'qtd', 'right');
        $column_vlr_total_transformed = $this->nota_saida_item_nota_saida_id_list->addQuickColumn("Total", 'vlr_total', 'right');

        $column_vlr_total_transformed->setTotalFunction( function($values) { 
            return array_sum((array) $values); 
        }); 

        $column_vlr_tabela_transformed->setTransformer(function($value, $object, $row, $cell = null, $last_row = null)
        {
            //code here
            if(!$value)
            {
                $value = 0;
            }

            if(is_numeric($value))
            {
                return number_format($value, 2, ",", ".");
            }
            else
            {
                return $value;
            }

        });

        $column_perc_desconto_transformed->setTransformer(function($value, $object, $row, $cell = null, $last_row = null)
        {
            //code here
            if(!$value)
            {
                $value = 0;
            }

            if(is_numeric($value))
            {
                return number_format($value, 2, ",", ".");
            }
            else
            {
                return $value;
            }

        });

        $column_vlr_desconto_transformed->setTransformer(function($value, $object, $row, $cell = null, $last_row = null)
        {
            //code here
            if(!$value)
            {
                $value = 0;
            }

            if(is_numeric($value))
            {
                return number_format($value, 2, ",", ".");
            }
            else
            {
                return $value;
            }

        });

        $column_vlr_unitario_transformed->setTransformer(function($value, $object, $row, $cell = null, $last_row = null)
        {
            //code here
            if(!$value)
            {
                $value = 0;
            }

            if(is_numeric($value))
            {
                return number_format($value, 2, ",", ".");
            }
            else
            {
                return $value;
            }

        });

        $column_qtd_transformed->setTransformer(function($value, $object, $row, $cell = null, $last_row = null)
        {
            //code here
            if(!$value)
            {
                $value = 0;
            }

            if(is_numeric($value))
            {
                return number_format($value, 2, ",", ".");
            }
            else
            {
                return $value;
            }

        });

        $column_vlr_total_transformed->setTransformer(function($value, $object, $row, $cell = null, $last_row = null)
        {
            //code here
            if(!$value)
            {
                $value = 0;
            }

            if(is_numeric($value))
            {
                return number_format($value, 2, ",", ".");
            }
            else
            {
                return $value;
            }

        });

        $this->nota_saida_item_nota_saida_id_list->createModel();

        $criteria_nota_saida_item_nota_saida_id = new TCriteria();
        $criteria_nota_saida_item_nota_saida_id->add(new TFilter('nota_saida_id', '=', $nota_saida->id));

        $criteria_nota_saida_item_nota_saida_id->setProperty('order', 'item asc');

        if(!empty($param["id"] ?? ""))
        {
            TSession::setValue(__CLASS__.'load_filter_nota_saida_id', $param["id"] ?? "");
        }
        $filterVar = TSession::getValue(__CLASS__.'load_filter_nota_saida_id');
        $criteria_nota_saida_item_nota_saida_id->add(new TFilter('nota_saida_id', '=', $filterVar));

        $nota_saida_item_nota_saida_id_items = NotaSaidaItem::getObjects($criteria_nota_saida_item_nota_saida_id);

        $this->nota_saida_item_nota_saida_id_list->addItems($nota_saida_item_nota_saida_id_items);

        $panel = new TElement('div');
        $panel->class = 'formView-detail';
        $panel->add(new BootstrapDatagridWrapper($this->nota_saida_item_nota_saida_id_list));

        $this->form->addContent([$panel]);

        parent::setTargetContainer('adianti_right_panel');

        $btnClose = new TButton('closeCurtain');
        $btnClose->class = 'btn btn-sm btn-default';
        $btnClose->style = 'margin-right:10px;';
        $btnClose->onClick = "Template.closeRightPanel();";
        $btnClose->setLabel("Fechar");
        $btnClose->setImage('fas:times');

        $this->form->addHeaderWidget($btnClose);

        TTransaction::close();
        parent::add($this->form);

        $style = new TStyle('right-panel > .container-part[page-name=NotaSaidaFormView]');
        $style->width = '60% !important';   
        $style->show(true);

    }

    public function onShow($param = null)
    {     

    }

}

