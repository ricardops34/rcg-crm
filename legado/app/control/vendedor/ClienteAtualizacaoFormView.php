<?php

class ClienteAtualizacaoFormView extends TPage
{
    protected $form; // form
    private static $database = 'erp_online';
    private static $activeRecord = 'ClienteAtualizacao';
    private static $primaryKey = 'id';
    private static $formName = 'formView_ClienteAtualizacao';

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

        $cliente_atualizacao = new ClienteAtualizacao($param['key']);
        // define the form title
        $this->form->setFormTitle("ClienteAtualizacaoFormView");

        $label1 = new TLabel("Id:", '', '12px', '', '100%');
        $text1 = new TTextDisplay($cliente_atualizacao->id, '', '12px', 'B');
        $label3 = new TLabel("Situacao cadastral:", '', '12px', '', '100%');
        $text3 = new TTextDisplay($cliente_atualizacao->situacao_cadastral->descricao, '', '14px', 'B');
        $label4 = new TLabel("Atividade principal:", '', '12px', '', '100%');
        $text4 = new TTextDisplay($cliente_atualizacao->atividade_principal_id, '', '14px', 'B');
        $label5 = new TLabel("Razão Social:", '', '12px', '', '100%');
        $text5 = new TTextDisplay($cliente_atualizacao->razao, '', '14px', 'B');
        $label6 = new TLabel("Nome de Fantasia:", '', '12px', '', '100%');
        $text6 = new TTextDisplay($cliente_atualizacao->fantasia, '', '14px', 'B');
        $label8 = new TLabel("Endereço:", '', '12px', '', '100%');
        $text7 = new TTextDisplay($cliente_atualizacao->tipo_logradouro, '', '14px', 'B');
        $text8 = new TTextDisplay($cliente_atualizacao->logradouro, '', '14px', 'B');
        $text9 = new TTextDisplay($cliente_atualizacao->numero, '', '14px', 'B');
        $label10 = new TLabel("Complemento:", '', '12px', '', '100%');
        $text10 = new TTextDisplay($cliente_atualizacao->complemento, '', '14px', 'B');
        $label11 = new TLabel("Bairro:", '', '12px', '', '100%');
        $text11 = new TTextDisplay($cliente_atualizacao->bairro, '', '12px', 'B');
        $label12 = new TLabel("Municipio id:", '', '12px', '', '100%');
        $text12 = new TTextDisplay($cliente_atualizacao->municipio_id, '', '14px', 'B');
        $label13 = new TLabel("CEP:", '', '12px', '', '100%');
        $text13 = new TTextDisplay($cliente_atualizacao->cep, '', '14px', 'B');
        $label14 = new TLabel("Telefone 1:", '', '12px', '', '100%');
        $text14 = new TTextDisplay($cliente_atualizacao->telefone1, '', '14px', 'B');
        $label15 = new TLabel("Telefone 2:", '', '12px', '', '100%');
        $text15 = new TTextDisplay($cliente_atualizacao->telefone2, '', '14px', 'B');
        $label16 = new TLabel("FAX:", '', '12px', '', '100%');
        $text16 = new TTextDisplay($cliente_atualizacao->fax, '', '14px', 'B');
        $label17 = new TLabel("Telefone Celular:", '', '12px', '', '100%');
        $text17 = new TTextDisplay($cliente_atualizacao->celular, '', '12px', 'B');
        $label18 = new TLabel("Telefone Celular 1:", '', '12px', '', '100%');
        $text18 = new TTextDisplay($cliente_atualizacao->celular2, '', '12px', 'B');
        $label19 = new TLabel("Contato:", '', '12px', '', '100%');
        $text19 = new TTextDisplay($cliente_atualizacao->contato, '', '12px', 'B');
        $label20 = new TLabel("CNPJ/CPF:", '', '12px', '', '100%');
        $text20 = new TTextDisplay($cliente_atualizacao->cnpj_cpf, '', '12px', 'B');
        $label21 = new TLabel("Inscrição Estadual:", '', '12px', '', '100%');
        $text21 = new TTextDisplay($cliente_atualizacao->ie, '', '12px', 'B');
        $label22 = new TLabel("Inscrição Municipal:", '', '12px', '', '100%');
        $text22 = new TTextDisplay($cliente_atualizacao->im, '', '12px', 'B');
        $label23 = new TLabel("E-Mail:", '', '12px', '', '100%');
        $text23 = new TTextDisplay($cliente_atualizacao->email, '', '12px', 'B');
        $label30 = new TLabel("Situação Especial:", '', '12px', '', '100%');
        $text30 = new TTextDisplay($cliente_atualizacao->situacao_especial, '', '12px', 'B');
        $text29 = new TTextDisplay(TDateTime::convertToMask($cliente_atualizacao->data_situacao_especial, 'yyyy-mm-dd hh:ii', 'dd/mm/yyyy hh:ii'), '', '14px', 'B');
        $label31 = new TLabel("Atualizado Em:", '', '12px', '', '100%');
        $text31 = new TTextDisplay(TDateTime::convertToMask($cliente_atualizacao->atualizado_em, 'yyyy-mm-dd hh:ii', 'dd/mm/yyyy hh:ii'), '', '12px', 'B');
        $label32 = new TLabel("Data Situação Cadastral:", '', '12px', '', '100%');
        $text32 = new TTextDisplay(TDateTime::convertToMask($cliente_atualizacao->data_situacao_cadastral, 'yyyy-mm-dd hh:ii', 'dd/mm/yyyy hh:ii'), '', '12px', 'B');
        $label33 = new TLabel("Simples:", '', '12px', '', '100%');
        $text33 = new TTextDisplay($cliente_atualizacao->simples, '', '12px', 'B');
        $label36 = new TLabel("Mei:", '', '12px', '', '100%');
        $text36 = new TTextDisplay($cliente_atualizacao->mei, '', '12px', 'B');
        $label34 = new TLabel("Tipo:", '', '12px', '', '100%');
        $text34 = new TTextDisplay($cliente_atualizacao->tipo, '', '12px', 'B');
        $label37 = new TLabel("Porte:", '', '12px', '', '100%');
        $text37 = new TTextDisplay($cliente_atualizacao->porte, '', '12px', 'B');
        $label38 = new TLabel("Natureza juridica:", '', '12px', '', '100%');
        $text38 = new TTextDisplay($cliente_atualizacao->natureza_juridica, '', '12px', 'B');
        $label2 = new TLabel("Capital Social:", '', '12px', '', '100%');
        $monetarytext2 = new TTextDisplay(number_format((double)$cliente_atualizacao->capital_social, '2', ',', '.'), '', '14px', 'B');
        $bpagecontainer4 = new BPageContainer();
        $bpagecontainer2 = new BPageContainer();

        $bpagecontainer4->setSize('100%');
        $bpagecontainer2->setSize('100%');

        $bpagecontainer2->setAction(new TAction(['ClienteCnaeSimpleList', 'onShow'], ['key' => $cliente_atualizacao->cliente_id]));
        $bpagecontainer4->setAction(new TAction(['ClienteSociosSimpleList', 'onShow'], ['key' => $cliente_atualizacao->cliente_id]));

        $bpagecontainer4->setId('b662bf13b28a97');
        $bpagecontainer2->setId('b662bf12b49ebc');

        $loadingContainer = new TElement('div');
        $loadingContainer->style = 'text-align:center; padding:50px';

        $icon = new TElement('i');
        $icon->class = 'fas fa-spinner fa-spin fa-3x';

        $loadingContainer->add($icon);
        $loadingContainer->add('<br>Carregando');

        $bpagecontainer4->add($loadingContainer);
        $loadingContainer = new TElement('div');
        $loadingContainer->style = 'text-align:center; padding:50px';

        $icon = new TElement('i');
        $icon->class = 'fas fa-spinner fa-spin fa-3x';

        $loadingContainer->add($icon);
        $loadingContainer->add('<br>Carregando');

        $bpagecontainer2->add($loadingContainer);


        $oCnae = Cnae::where('id', '=', $cliente_atualizacao->atividade_principal_id)
                    ->first();
        if($oCnae){                                        
            $text4 = new TTextDisplay($oCnae->descricao, '', '14px', 'B');
        }

        $this->form->appendPage("Cadastro");

        $this->form->addFields([new THidden('current_tab')]);
        $this->form->setTabFunction("$('[name=current_tab]').val($(this).attr('data-current_page'));");

        $row1 = $this->form->addFields([$label1,$text1],[$label3,$text3],[$label4,$text4]);
        $row1->layout = [' col-sm-2',' col-sm-5',' col-sm-5'];

        $row2 = $this->form->addFields([$label5,$text5],[$label6,$text6]);
        $row2->layout = ['col-sm-6','col-sm-6'];

        $row3 = $this->form->addFields([$label8,$text7,$text8,$text9],[$label10,$text10]);
        $row3->layout = ['col-sm-6',' col-sm-6'];

        $row4 = $this->form->addFields([$label11,$text11],[$label12,$text12]);
        $row4->layout = ['col-sm-6','col-sm-6'];

        $row5 = $this->form->addFields([$label13,$text13],[$label14,$text14],[$label15,$text15],[$label16,$text16]);
        $row5->layout = [' col-sm-3',' col-sm-3',' col-sm-3',' col-sm-3'];

        $row6 = $this->form->addFields([$label17,$text17],[$label18,$text18]);
        $row6->layout = ['col-sm-6','col-sm-6'];

        $row7 = $this->form->addFields([$label19,$text19],[$label20,$text20]);
        $row7->layout = ['col-sm-6','col-sm-6'];

        $row8 = $this->form->addFields([$label21,$text21],[$label22,$text22]);
        $row8->layout = ['col-sm-6','col-sm-6'];

        $row9 = $this->form->addFields([$label23,$text23],[$label30,$text30,$text29]);
        $row9->layout = ['col-sm-6',' col-sm-6'];

        $row10 = $this->form->addFields([$label31,$text31],[$label32,$text32]);
        $row10->layout = ['col-sm-6','col-sm-6'];

        $row11 = $this->form->addFields([$label33,$text33],[$label36,$text36],[$label34,$text34]);
        $row11->layout = [' col-sm-4',' col-sm-4',' col-sm-4'];

        $row12 = $this->form->addFields([$label37,$text37],[$label38,$text38],[$label2,$monetarytext2]);
        $row12->layout = [' col-sm-4',' col-sm-4',' col-sm-4'];

        $this->form->appendPage("Socios");
        $row13 = $this->form->addFields([$bpagecontainer4]);
        $row13->layout = [' col-sm-12'];

        $this->form->appendPage("Atividade Secundaria");
        $row14 = $this->form->addFields([$bpagecontainer2]);
        $row14->layout = [' col-sm-12'];

        if(!empty($param['current_tab']))
        {
            $this->form->setCurrentPage($param['current_tab']);
        }

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

        $style = new TStyle('right-panel > .container-part[page-name=ClienteAtualizacaoFormView]');
        $style->width = '60% !important';   
        $style->show(true);

    }

    public function onShow($param = null)
    {     

    }

}

