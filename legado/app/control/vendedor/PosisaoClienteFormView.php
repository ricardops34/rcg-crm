<?php

class PosisaoClienteFormView extends TPage
{
    protected $form; // form
    private static $database = 'erp_online';
    private static $activeRecord = 'Cliente';
    private static $primaryKey = 'id';
    private static $formName = 'formView_Cliente';

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

        $cliente = new Cliente($param['key']);
        // define the form title
        $this->form->setFormTitle("Posisao Cliente");

        $transformed_cliente_status = call_user_func(function($value, $object, $row)
        {

            $class = ($value=='B') ? 'warning'      : 'success';
            $label = ($value=='B') ? 'Bloqueado'    : 'Ativo';
            $div = new TElement('span');
            $div->class="label label-{$class}";
            $div->style="width:120px; text-shadow:none; font-size:12px; font-weight:lighter";
            $div->add($label);
            return $div;

        }, $cliente->status, $cliente, null);    

        $transformed_cliente_cod_erp = call_user_func(function($value, $object, $row)
        {

            $return = $value;
            TTransaction::open('erp_online');

            $oCliente= Cliente::find( $value );

            if($oCliente){
                $return = substr($oCliente->cod_erp, 0, 6).'-'.substr($oCliente->cod_erp, 6, 2);
            }

            TTransaction::close();
            return $return;

        }, $cliente->cod_erp, $cliente, null);    

        $transformed_cliente_fantasia = call_user_func(function($value, $object, $row)
        {

                $return = $value;
                TTransaction::open('erp_online');

                $oCliente= Cliente::find( $object->cliente_id );

                if($oCliente){
                    //$return = substr($oCliente->cod_erp, 0, 6).'-'.substr($oCliente->cod_erp, 6, 2);

                    $cNome = ltrim(rtrim(ltrim($oCliente->fantasia)));//.'('. rtrim(ltrim($this->razao)).')');
                    if($this->tipo == 'F'){
                        if(!empty($oCliente->razao)){
                            $cNome = rtrim(ltrim($oCliente->razao));
                        }
                    }

                    $return = '<b>'.$cNome.'</b>';;
                    if($oCliente->status == 'B'){ //fas fa-lock
                        $icone = new TElement('i');
                        $icone->class="fas fa-lock";
                        $return = $icone.'<s>'.$cNome.'</s>';
                    }

                }

                TTransaction::close();

                return $return;//code here

        }, $cliente->fantasia, $cliente, null);

        $label4 = new TLabel("Situação:", '', '12px', 'B', '100%');
        $text4 = new TTextDisplay($transformed_cliente_status, '', '12px', '');
        $label3 = new TLabel("Código:", '', '12px', 'B', '100%');
        $text3 = new TTextDisplay($transformed_cliente_cod_erp, '', '12px', '');
        $label5 = new TLabel("Tipo Pessoa:", '', '12px', 'B', '100%');
        $text5 = new TTextDisplay($cliente->tipo, '', '12px', '');
        $label2 = new TLabel("CNPJ:", '', '12px', 'B', '100%');
        $cnpj_cpf = new TTextDisplay($cliente->cnpj_cpf, '', '12px', '');
        $label8 = new TLabel("Nome de Fantasia:", '', '12px', 'B', '100%');
        $text8 = new TTextDisplay($transformed_cliente_fantasia, '', '12px', '');
        $label6 = new TLabel("Razão Social:", '', '12px', 'B', '100%');
        $text6 = new TTextDisplay($cliente->razao, '', '12px', '');
        $label_condicao = new TLabel("Condição de Pagamento:", '', '12px', 'B', '100%');
        $text18 = new TTextDisplay($cliente->condicao_pagamento->descricao, '', '12px', '');
        $label_tabela_preco = new TLabel("Tabela de Preço:", '', '12px', 'B', '100%');
        $tabela_preco = new TTextDisplay($cliente->tabela_preco->descricao, '', '12px', '');
        $label_email = new TLabel("Email:", '', '12px', 'B', '100%');
        $text22 = new TTextDisplay(new TImage('fas:mail-bulk #000000').$cliente->email, '', '12px', '');
        $label9 = new TLabel("Endereço:", '', '12px', 'B', '100%');
        $text9 = new TTextDisplay($cliente->endereco, '', '12px', '');
        $label10 = new TLabel("Complemento:", '', '12px', 'B', '100%');
        $text10 = new TTextDisplay($cliente->complemento, '', '12px', '');
        $label11 = new TLabel("Bairro:", '', '12px', 'B', '100%');
        $text11 = new TTextDisplay($cliente->bairro, '', '12px', '');
        $label13 = new TLabel("Municipio:", '', '12px', 'B', '100%');
        $text13 = new TTextDisplay($cliente->municipio->descricao, '', '12px', '');
        $label12 = new TLabel("UF:", '', '12px', 'B', '100%');
        $text12 = new TTextDisplay($cliente->uf, '', '12px', '');
        $label15 = new TLabel("CEP:", '', '12px', 'B', '100%');
        $text15 = new TTextDisplay($cliente->cep, '', '12px', '');
        $label19 = new TLabel("Telefone:", '', '12px', 'B', '100%');
        $text19 = new TTextDisplay($cliente->telefone1, '', '12px', '');
        $label20 = new TLabel("Telefone Celular:", '', '12px', 'B', '100%');
        $text20 = new TTextDisplay($cliente->celular, '', '12px', '');
        $label28 = new TLabel("Vendedor:", '', '12px', '', '100%');
        $text28 = new TTextDisplay($cliente->vendedor->nome_reduzido, '', '12px', '');
        $label29 = new TLabel("Vendedor:", '', '12px', 'B', '100%');
        $text29 = new TTextDisplay($cliente->vendedor->nome, '', '12px', '');
        $dias = new TLabel("Ultima Compra:", '', '12px', 'B', '100%');
        $datetext1 = new TTextDisplay(TDate::convertToMask($cliente->ultima_compra, 'yyyy-mm-dd', 'dd/mm/yyyy'), '', '12px', '');
        $monetarytext2 = new TTextDisplay(number_format((double)$cliente->id, '0', ',', '.'), '', '12px', '');
        $bpagecontainer2 = new BPageContainer();
        $notafiscal = new BPageContainer();
        $AddAtendimento = new TButton('AddAtendimento');
        $AtendimentoLinha = new BPageContainer();

        $AddAtendimento->addStyleClass('btn-default');
        $AddAtendimento->setImage('fas:plus #009688');
        $notafiscal->setSize('100%');
        $bpagecontainer2->setSize('100%');
        $AtendimentoLinha->setSize('100%');

        $notafiscal->setId('b664cf62759771');
        $bpagecontainer2->setId('b65538e22c47eb');
        $AtendimentoLinha->setId('b67e6fbd54e72a');

        $AddAtendimento->setAction(new TAction(['AtendimentoForm', 'onShow']), "Adicionar");
        $bpagecontainer2->setAction(new TAction(['NotaSaidaItemList', 'onShow'], ['key' => $cliente->id]));
        $notafiscal->setAction(new TAction(['ClienteNotafiscalList', 'onShow'], ['cliente_id' => $cliente->id]));
        $AtendimentoLinha->setAction(new TAction(['AtendimentoLinha', 'onShow'], ['cliente_id' => $cliente->id]));

        $loadingContainer = new TElement('div');
        $loadingContainer->style = 'text-align:center; padding:50px';

        $icon = new TElement('i');
        $icon->class = 'fas fa-spinner fa-spin fa-3x';

        $loadingContainer->add($icon);
        $loadingContainer->add('<br>Carregando');

        $bpagecontainer2->add($loadingContainer);
        $loadingContainer = new TElement('div');
        $loadingContainer->style = 'text-align:center; padding:50px';

        $icon = new TElement('i');
        $icon->class = 'fas fa-spinner fa-spin fa-3x';

        $loadingContainer->add($icon);
        $loadingContainer->add('<br>Carregando');

        $notafiscal->add($loadingContainer);
        $loadingContainer = new TElement('div');
        $loadingContainer->style = 'text-align:center; padding:50px';

        $icon = new TElement('i');
        $icon->class = 'fas fa-spinner fa-spin fa-3x';

        $loadingContainer->add($icon);
        $loadingContainer->add('<br>Carregando');

        $AtendimentoLinha->add($loadingContainer);

        if($cliente->tipo == "F"){
            $text5 = new TTextDisplay("Pessoa Física"   , '', '12px', '');
            $label2 = new TLabel("CPF:", '', '12px', 'B', '100%');
        }else{
            $text5 = new TTextDisplay("Pessoa Juridica" , '', '12px', '');
            $label2 = new TLabel("CNPJ:", '', '12px', 'B', '100%');
        }

        $this->form->appendPage("Cadastro");

        $this->form->addFields([new THidden('current_tab')]);
        $this->form->setTabFunction("$('[name=current_tab]').val($(this).attr('data-current_page'));");

        $row1 = $this->form->addFields([$label4,$text4],[$label3,$text3],[$label5,$text5],[$label2,$cnpj_cpf],[]);
        $row1->layout = [' col-sm-2','col-sm-2','col-sm-2',' col-sm-4','col-sm-2'];

        $row2 = $this->form->addFields([$label8,$text8],[$label6,$text6]);
        $row2->layout = ['col-sm-6','col-sm-6'];

        $row3 = $this->form->addFields([$label_condicao,$text18],[$label_tabela_preco,$tabela_preco],[$label_email,$text22]);
        $row3->layout = ['col-sm-3','col-sm-3','col-sm-6'];

        $row4 = $this->form->addFields([$label9,$text9],[$label10,$text10]);
        $row4->layout = ['col-sm-6','col-sm-6'];

        $row5 = $this->form->addFields([$label11,$text11],[$label13,$text13],[$label12,$text12],[$label15,$text15]);
        $row5->layout = [' col-sm-3',' col-sm-3',' col-sm-3',' col-sm-3'];

        $row6 = $this->form->addFields([$label19,$text19],[$label20,$text20],[$label28,$text28]);
        $row6->layout = [' col-sm-4',' col-sm-4',' col-sm-4'];

        $row7 = $this->form->addFields([$label29,$text29],[$dias,$datetext1],[$monetarytext2]);
        $row7->layout = [' col-sm-4',' col-sm-4',' col-sm-4'];

        $this->form->appendPage("Comodato");

        $this->nota_saida_cliente_id_list = new TQuickGrid;
        $this->nota_saida_cliente_id_list->style = 'width:100%';
        $this->nota_saida_cliente_id_list->disableDefaultClick();

        $column_id = $this->nota_saida_cliente_id_list->addQuickColumn("", 'id', 'left' , '5%');
        $column_nota_fiscal_transformed = $this->nota_saida_cliente_id_list->addQuickColumn("Nota fiscal", 'nota_fiscal', 'left');
        $column_serie_fiscal = $this->nota_saida_cliente_id_list->addQuickColumn("Serie fiscal", 'serie_fiscal', 'left');
        $column_dt_emissao_transformed = $this->nota_saida_cliente_id_list->addQuickColumn("Emissão", 'dt_emissao', 'left');
        $column_vlr_mercadoria_transformed = $this->nota_saida_cliente_id_list->addQuickColumn("Valor", 'vlr_mercadoria', 'right');

        $column_vlr_mercadoria_transformed->setTotalFunction( function($values) { 
            return array_sum((array) $values); 
        }); 

        $column_nota_fiscal_transformed->setTransformer(function($value, $object, $row, $cell = null, $last_row = null)
        {
            //code here
            $action_nota_fiscal = new TAction( ['NotaSaidaFormView', 'onShow' ] );
            $action_nota_fiscal->setParameter('key', $object->id);
            $action_nota_fiscal->setParameter('id', $object->id);

            $link_nota_fiscal = new TActionLink($value, $action_nota_fiscal,'blue');//, 'blue', 12, 'biu');
            return $link_nota_fiscal;//"R$ " . number_format($value, 2, ",", ".");

        });

        $column_dt_emissao_transformed->setTransformer(function($value, $object, $row, $cell = null, $last_row = null)
        {
            if(!empty(trim((string) $value)))
            {
                try
                {
                    $date = new DateTime($value);
                    return $date->format('d/m/Y');
                }
                catch (Exception $e)
                {
                    return $value;
                }
            }
        });

        $column_vlr_mercadoria_transformed->setTransformer(function($value, $object, $row, $cell = null, $last_row = null)
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

        $this->nota_saida_cliente_id_list->createModel();

        $criteria_nota_saida_cliente_id = new TCriteria();
        $criteria_nota_saida_cliente_id->add(new TFilter('cliente_id', '=', $cliente->id));

        $criteria_nota_saida_cliente_id->setProperty('order', 'dt_emissao desc');

        $filterVar = 'S';
        $criteria_nota_saida_cliente_id->add(new TFilter('comodato', '=', $filterVar));
        if(!empty($param["key"] ?? ""))
        {
            TSession::setValue(__CLASS__.'load_filter_cliente_id', $param["key"] ?? "");
        }
        $filterVar = TSession::getValue(__CLASS__.'load_filter_cliente_id');
        $criteria_nota_saida_cliente_id->add(new TFilter('cliente_id', '=', $filterVar));

        $nota_saida_cliente_id_items = NotaSaida::getObjects($criteria_nota_saida_cliente_id);

        $this->nota_saida_cliente_id_list->addItems($nota_saida_cliente_id_items);

        $icon = new TImage('fas:toolbox #FFFFFF');
        $title = new TTextDisplay("{$icon} Comodato", '#FFFFFF', '12px', '{$fontStyle}');

        $panel = new TPanelGroup($title, '#03A9F4');
        $panel->class = 'panel panel-default formView-detail';
        $tableResponsiveDiv = new TElement('div');
        $tableResponsiveDiv->class = 'table-responsive';
        $tableResponsiveDiv->add(new BootstrapDatagridWrapper($this->nota_saida_cliente_id_list));
        $panel->add($tableResponsiveDiv);

        $this->form->addContent([$panel]);

        $this->form->appendPage("MIX");
        $row8 = $this->form->addFields([$bpagecontainer2]);
        $row8->layout = [' col-sm-12'];

        $this->form->appendPage("Contas a Receber");

        $this->titulo_receber_cliente_id_list = new TQuickGrid;
        $this->titulo_receber_cliente_id_list->datatable = 'true';
        $this->titulo_receber_cliente_id_list->style = 'width:100%';
        $this->titulo_receber_cliente_id_list->disableDefaultClick();

        $column_id_transformed = $this->titulo_receber_cliente_id_list->addQuickColumn("", 'id', 'left' , '5%');
        $column_numero = $this->titulo_receber_cliente_id_list->addQuickColumn("Numero", 'numero', 'left' , '10%');
        $column_parcela = $this->titulo_receber_cliente_id_list->addQuickColumn("Parcela", 'parcela', 'left' , '5%');
        $column_prefixo = $this->titulo_receber_cliente_id_list->addQuickColumn("Prefixo", 'prefixo', 'left' , '10%');
        $column_emissao_transformed = $this->titulo_receber_cliente_id_list->addQuickColumn("Emissão", 'emissao', 'left' , '10%');
        $column_venc_real_transformed = $this->titulo_receber_cliente_id_list->addQuickColumn("Vencimento", 'venc_real', 'left' , '10%');
        $column_baixa_transformed = $this->titulo_receber_cliente_id_list->addQuickColumn("Baixa", 'baixa', 'left');
        $column_saldo_transformed = $this->titulo_receber_cliente_id_list->addQuickColumn("Saldo", 'saldo', 'right' , '10%');
        $column_valor_transformed = $this->titulo_receber_cliente_id_list->addQuickColumn("Valor", 'valor', 'right' , '10%');

        $column_saldo_transformed->setTotalFunction( function($values) { 
            return array_sum((array) $values); 
        }); 

        $column_id_transformed->setTransformer(function($value, $object, $row, $cell = null, $last_row = null)
        {
                $div = "";
                if ($object->vencimento < date('Y-m-d'))
                {
                    $div = new TElement('span');
                    $div->class="label label-warning";
                    $div->style="width:120px; text-shadow:none; font-size:12px; font-weight:lighter";
                    $div->add('Vencido');
                    $row->style = "background: #FFF9A7";
                }elseif ($object->vencimento == date('Y-m-d')){
                    $div = new TElement('span');
                    $div->class="label label-info";
                    $div->style="width:120px; text-shadow:none; font-size:12px; font-weight:lighter";
                    $div->add('Vencendo');
                    $row->style = "background: #FFF9A7";
                }else{
                    $div = "";
                }
                return $div;

        });

        $column_emissao_transformed->setTransformer(function($value, $object, $row, $cell = null, $last_row = null)
        {
            if(!empty(trim((string) $value)))
            {
                try
                {
                    $date = new DateTime($value);
                    return $date->format('d/m/Y');
                }
                catch (Exception $e)
                {
                    return $value;
                }
            }
        });

        $column_venc_real_transformed->setTransformer(function($value, $object, $row, $cell = null, $last_row = null)
        {
            if(!empty(trim((string) $value)))
            {
                try
                {
                    $date = new DateTime($value);
                    return $date->format('d/m/Y');
                }
                catch (Exception $e)
                {
                    return $value;
                }
            }
        });

        $column_baixa_transformed->setTransformer(function($value, $object, $row, $cell = null, $last_row = null)
        {
            if(!empty(trim((string) $value)))
            {
                try
                {
                    $date = new DateTime($value);
                    return $date->format('d/m/Y');
                }
                catch (Exception $e)
                {
                    return $value;
                }
            }
        });

        $column_saldo_transformed->setTransformer(function($value, $object, $row, $cell = null, $last_row = null)
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

        $this->titulo_receber_cliente_id_list->createModel();

        $criteria_titulo_receber_cliente_id = new TCriteria();
        $criteria_titulo_receber_cliente_id->add(new TFilter('cliente_id', '=', $cliente->id));

        $criteria_titulo_receber_cliente_id->setProperty('order', 'numero desc');

        $filterVar = 0;
        $criteria_titulo_receber_cliente_id->add(new TFilter('saldo', '>', $filterVar));
        $filterVar = 'S';
        $criteria_titulo_receber_cliente_id->add(new TFilter('reg_ativo', '=', $filterVar));

        $titulo_receber_cliente_id_items = TituloReceber::getObjects($criteria_titulo_receber_cliente_id);

        $this->titulo_receber_cliente_id_list->addItems($titulo_receber_cliente_id_items);

        $icon = new TImage('fas:money-bill-alt #FFFFFF');
        $title = new TTextDisplay("{$icon} Título Em Aberto", '#FFFFFF', '12px', '{$fontStyle}');

        $panel = new TPanelGroup($title, '#03A9F4');
        $panel->class = 'panel panel-default formView-detail';
        $panel->add(new BootstrapDatagridWrapper($this->titulo_receber_cliente_id_list));

        $this->form->addContent([$panel]);

        $this->form->appendPage("Nota Fiscal");
        $row9 = $this->form->addFields([$notafiscal]);
        $row9->layout = [' col-sm-12'];

        $this->form->appendPage("Atendimentos");
        $row10 = $this->form->addFields([$AddAtendimento],[],[]);
        $row10->layout = ['col-sm-3','col-sm-3','col-sm-6'];

        $row11 = $this->form->addFields([$AtendimentoLinha]);
        $row11->layout = [' col-xs-3 col-sm-12 col-lg-12 col-md-3'];

        $this->form->appendPage("Cobrança");
        $row12 = $this->form->addFields([new TLabel("Rótulo:", null, '12px', null)],[]);

        if(!empty($param['current_tab']))
        {
            $this->form->setCurrentPage($param['current_tab']);
        }

        //$this->nota_saida_item_cliente_id_list->setHeight(300);
        //$this->nota_saida_item_cliente_id_list->makeScrollable();

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

        $style = new TStyle('right-panel > .container-part[page-name=PosisaoClienteFormView]');
        $style->width = '80% !important';   
        $style->show(true);

    }

    public function onShow($param = null)
    {     

    }

}

