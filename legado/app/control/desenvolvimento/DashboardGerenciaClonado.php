<?php

class DashboardGerenciaClonado extends TPage
{
    protected $form;
    private $formFields = [];
    private static $database = '';
    private static $activeRecord = '';
    private static $primaryKey = '';
    private static $formName = 'form_DashboardGerenciaClonado';

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
        $this->form->setFormTitle("Evolução de Vendas");

        $criteria_vendedor = new TCriteria();
        $criteria_quantidade_notas = new TCriteria();
        $criteria_notas_rcg = new TCriteria();
        $criteria_faturamento_servico = new TCriteria();
        $criteria_comodato = new TCriteria();
        $criteria_devolucao = new TCriteria();
        $criteria_cliente_positivado = new TCriteria();
        $criteria_venda_mes = new TCriteria();
        $criteria_tabela_vendas = new TCriteria();

        $filterVar = 'SPED';
        $criteria_quantidade_notas->add(new TFilter('vendas_vendedor_mes.especie_fiscal', '=', $filterVar)); 
        $filterVar = 'S';
        $criteria_quantidade_notas->add(new TFilter('vendas_vendedor_mes.reg_ativo', '=', $filterVar)); 
        $filterVar = 'SPED';
        $criteria_notas_rcg->add(new TFilter('vendas_vendedor_mes.especie_fiscal', '=', $filterVar)); 
        $filterVar = 'SPED';
        $criteria_faturamento_servico->add(new TFilter('vendas_vendedor_mes.especie_fiscal', '<>', $filterVar)); 
        $filterVar = 'SPED';
        $criteria_comodato->add(new TFilter('vendas_vendedor_mes.especie_fiscal', '=', $filterVar)); 
        $filterVar = 'SPED';
        $criteria_devolucao->add(new TFilter('vendas_vendedor_mes.especie_fiscal', '=', $filterVar)); 
        $filterVar = 'SPED';
        $criteria_venda_mes->add(new TFilter('vendas_vendedor_mes.especie_fiscal', '=', $filterVar)); 
        $filterVar = 0;
        $criteria_venda_mes->add(new TFilter('vendas_vendedor_mes.vlr_liquido', '>', $filterVar)); 
        $filterVar = 'S';
        $criteria_venda_mes->add(new TFilter('vendas_vendedor_mes.reg_ativo', '=', $filterVar)); 
        $filterVar = 'SPED';
        $criteria_tabela_vendas->add(new TFilter('vendas_vendedor_mes.especie_fiscal', '=', $filterVar)); 
        $filterVar = 0;
        $criteria_tabela_vendas->add(new TFilter('vendas_vendedor_mes.vlr_liquido', '>', $filterVar)); 
        $filterVar = 'S';
        $criteria_tabela_vendas->add(new TFilter('vendas_vendedor_mes.reg_ativo', '=', $filterVar)); 

        $criteria_tabela_vendas->add(new TFilter('vendas_vendedor_mes.vendedor1_id', 'in', "(SELECT id FROM vendedor WHERE vendedor = 'S')")); 
        $criteria_tabela_vendas->setProperty('order' , 'vendas_vendedor_mes.vlr_liquido');

        $criteria_quantidade_notas->add(new TFilter('vendas_vendedor_mes.vendedor1_id', 'in', "(SELECT id FROM vendedor WHERE vendedor = 'S')")); 
        $criteria_notas_rcg->add(new TFilter('vendas_vendedor_mes.vendedor1_id', 'in', "(SELECT id FROM vendedor WHERE vendedor = 'N' )")); 

        $criteria_comodato->add(new TFilter('vendas_vendedor_mes.vendedor1_id', 'in', "(SELECT id FROM vendedor WHERE vendedor = 'S')")); 
        $criteria_devolucao->add(new TFilter('vendas_vendedor_mes.vendedor1_id', 'in', "(SELECT id FROM vendedor WHERE vendedor = 'S')")); 
        $criteria_venda_mes->add(new TFilter('vendas_vendedor_mes.vendedor1_id', 'in', "(SELECT id FROM vendedor WHERE vendedor = 'S')")); 

        $mes = new TCombo('mes');
        $ano = new TCombo('ano');
        $vendedor = new TDBUniqueSearch('vendedor', 'erp_online', 'Vendedor', 'id', 'nome_reduzido','cod_erp asc' , $criteria_vendedor );
        $button_buscar = new TButton('button_buscar');
        $quantidade_notas = new BIndicator('quantidade_notas');
        $notas_rcg = new BIndicator('notas_rcg');
        $faturamento_servico = new BIndicator('faturamento_servico');
        $comodato = new BIndicator('comodato');
        $devolucao = new BIndicator('devolucao');
        $cliente_positivado = new BIndicator('cliente_positivado');
        $venda_mes = new BBarChart('venda_mes');
        $tabela_vendas = new BTableChart('tabela_vendas');
        $atualizacao = new TDateTime('atualizacao');

        $mes->setChangeAction(new TAction([$this,'onChangeMes']));

        $vendedor->setMinLength(2);
        $vendedor->setFilterColumns(["cod_erp","nome","nome_reduzido"]);
        $button_buscar->setAction(new TAction(['DashboardGerenciaClonado', 'onShow']), "Buscar");
        $button_buscar->addStyleClass('btn-success');
        $button_buscar->setImage('fas:search #FFFFFF');
        $atualizacao->setEditable(false);
        $atualizacao->setDatabaseMask('yyyy-mm-dd hh:ii');
        $ano->addItems(TempoService::getAnos());
        $mes->addItems(TempoService::getMeses());

        $mes->enableSearch();
        $ano->enableSearch();

        $vendedor->setMask('{nome_status}');
        $atualizacao->setMask('dd/mm/yyyy hh:ii');

        $mes->setValue($param['mes'] ?? date('m'));
        $ano->setValue($param['ano'] ?? date('Y'));
        $atualizacao->setValue(SisFunction::GetParm('sis_update',' ',null));

        $mes->setSize('50%');
        $ano->setSize('40%');
        $atualizacao->setSize('100%');
        $vendedor->setSize('calc(100% - 210px)');

        $quantidade_notas->setDatabase('erp_online');
        $quantidade_notas->setFieldValue("vendas_vendedor_mes.vlr_liquido");
        $quantidade_notas->setModel('VendasVendedorMes');
        $quantidade_notas->setTransformerValue(function($value)
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
        $quantidade_notas->setTotal('sum');
        $quantidade_notas->setColors('#2980B9', '#FFFFFF', '#3498DB', '#FFFFFF');
        $quantidade_notas->setTitle("Equipe de Vendas", '#FFFFFF', '20', '');
        $quantidade_notas->setDescription("Nota Fiscal", '20');
        $quantidade_notas->setCriteria($criteria_quantidade_notas);
        $quantidade_notas->setIcon(new TImage('fas:money-bill #FFFFFF'));
        $quantidade_notas->setValueSize("20");
        $quantidade_notas->setValueColor("#FFFFFF", 'B');
        $quantidade_notas->setSize('100%', 95);
        $quantidade_notas->setLayout('horizontal', 'left');

        $notas_rcg->setDatabase('erp_online');
        $notas_rcg->setFieldValue("vendas_vendedor_mes.vlr_liquido");
        $notas_rcg->setModel('VendasVendedorMes');
        $notas_rcg->setTransformerValue(function($value)
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
        $notas_rcg->setTotal('sum');
        $notas_rcg->setColors('#00CEC9', '#FFFFFF', '#81ECEC', '#FFFFFF');
        $notas_rcg->setTitle("RCG", '#FFFFFF', '20', '');
        $notas_rcg->setDescription("Nota Fiscal", '20');
        $notas_rcg->setCriteria($criteria_notas_rcg);
        $notas_rcg->setIcon(new TImage('fas:industry #FFFFFF'));
        $notas_rcg->setValueSize("20");
        $notas_rcg->setValueColor("#FFFFFF", 'B');
        $notas_rcg->setSize('100%', 95);
        $notas_rcg->setLayout('horizontal', 'left');

        $faturamento_servico->setDatabase('erp_online');
        $faturamento_servico->setFieldValue("vendas_vendedor_mes.vlr_liquido");
        $faturamento_servico->setModel('VendasVendedorMes');
        $faturamento_servico->setTransformerValue(function($value)
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
        $faturamento_servico->setTotal('sum');
        $faturamento_servico->setColors('#F39C12', '#FFFFFF', '#F1C40F', '#FFFFFF');
        $faturamento_servico->setTitle("Serviço", '#FFFFFF', '20', '');
        $faturamento_servico->setDescription("Nota Fiscal", '20');
        $faturamento_servico->setCriteria($criteria_faturamento_servico);
        $faturamento_servico->setIcon(new TImage('far:money-bill-alt #FFFFFF'));
        $faturamento_servico->setValueSize("20");
        $faturamento_servico->setValueColor("#FFFFFF", 'B');
        $faturamento_servico->setSize('100%', 95);
        $faturamento_servico->setLayout('horizontal', 'left');

        $comodato->setDatabase('erp_online');
        $comodato->setFieldValue("vendas_vendedor_mes.vlr_comodato");
        $comodato->setModel('VendasVendedorMes');
        $comodato->setTransformerValue(function($value)
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
        $comodato->setTotal('sum');
        $comodato->setColors('#F368E0', '#333333', '#FF9FF3', '#333333');
        $comodato->setTitle("Comodato", '#333333', '20', '');
        $comodato->setDescription("Nota Fiscal", '20');
        $comodato->setCriteria($criteria_comodato);
        $comodato->setIcon(new TImage('far:money-bill-alt #333333'));
        $comodato->setValueSize("20");
        $comodato->setValueColor("#333333", 'B');
        $comodato->setSize('100%', 95);
        $comodato->setLayout('horizontal', 'left');

        $devolucao->setDatabase('erp_online');
        $devolucao->setFieldValue("vendas_vendedor_mes.vlr_devolucao");
        $devolucao->setModel('VendasVendedorMes');
        $devolucao->setTransformerValue(function($value)
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
        $devolucao->setTotal('sum');
        $devolucao->setColors('#BDC3C7', '#333333', '#ECF0F1', '#333333');
        $devolucao->setTitle("Devolução", '#333333', '20', '');
        $devolucao->setDescription("Nota Fiscal", '20');
        $devolucao->setCriteria($criteria_devolucao);
        $devolucao->setIcon(new TImage('far:money-bill-alt #333333'));
        $devolucao->setValueSize("20");
        $devolucao->setValueColor("#333333", 'B');
        $devolucao->setSize('100%', 95);
        $devolucao->setLayout('horizontal', 'left');

        $cliente_positivado->setDatabase('erp_online');
        $cliente_positivado->setFieldValue("cliente_positivado.cliente_id");
        $cliente_positivado->setModel('ClientePositivado');
        $cliente_positivado->setTotal('count');
        $cliente_positivado->setColors('#2ECC71', '#FFFFFF', '#27AE60', '#FFFFFF');
        $cliente_positivado->setTitle("Clientes", '#FFFFFF', '20', '');
        $cliente_positivado->setDescription("Positivado", '20');
        $cliente_positivado->setCriteria($criteria_cliente_positivado);
        $cliente_positivado->setIcon(new TImage('fas:user #FFFFFF'));
        $cliente_positivado->setValueSize("20");
        $cliente_positivado->setValueColor("#FFFFFF", 'B');
        $cliente_positivado->setSize('100%', 95);
        $cliente_positivado->setLayout('horizontal', 'left');

        $venda_mes->setDatabase('erp_online');
        $venda_mes->setFieldValue("vendas_vendedor_mes.vlr_liquido");
        $venda_mes->setFieldGroup(["vendas_vendedor_mes.mes", "vendas_vendedor_mes.vendedor1_id"]);
        $venda_mes->setModel('VendasVendedorMes');
        $venda_mes->setTitle("Vendas Mês");
        $venda_mes->setTransformerLegend(function($value, $row, $data)
            {

                $mes_extenso =TempoService::getMeses();
                //$object=>mes;
                if(isset($value)){
                    return  $value .'-'.$mes_extenso[$value];
                }else{
                    return '';
                }
            });
        $venda_mes->setTransformerSubLegend(function($value, $row, $data)
            {

                    TTransaction::open('erp_online');

                    $oVendedor = Vendedor::where('id', '=', $value)->first();

                    TTransaction::close();

                    if($oVendedor){ 
                        return $oVendedor->nome_reduzido;
                    }else{
                        return $value;
                    }

            });
        $venda_mes->setTransformerValue(function($value, $row, $data)
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
        $venda_mes->setLayout('vertical');
        $venda_mes->setTotal('sum');
        $venda_mes->showLegend(true);
        $venda_mes->setCriteria($criteria_venda_mes);
        $venda_mes->setSize('100%', 280);
        $venda_mes->disableZoom();

        $tabela_vendas_column_vendedor1_id = new BTableColumnChart('vendedor1_id', "Vendedor", 'left','40%');
        $tabela_vendas_column_vlr_bruto = new BTableColumnChart('vlr_bruto', "Valor Bruto", 'right');
        $tabela_vendas_column_vlr_devolucao = new BTableColumnChart('vlr_devolucao', "Devolução", 'right','15%');
        $tabela_vendas_column_vlr_liquido = new BTableColumnChart('vlr_liquido', "Valor Liquido", 'right','15%');
        $tabela_vendas_column_vlr_bruto->setTotal('sum', function($value, $object, $row)
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
        $tabela_vendas_column_vlr_devolucao->setTotal('sum', function($value, $object, $row)
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
        $tabela_vendas_column_vlr_liquido->setTotal('sum', function($value, $object, $row)
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
        $tabela_vendas_column_vendedor1_id->setTransformer(function($value, $object, $row)
        {

                TTransaction::open('erp_online');

                $oVendedor = Vendedor::where('id', '=', $value)->first();

                TTransaction::close();

                if($oVendedor){ 
                    return ltrim(rtrim($oVendedor->nome_reduzido));
                }else{
                    return $value;
                }

        });
        $tabela_vendas_column_vlr_bruto->setTransformer(function($value, $object, $row)
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
        $tabela_vendas_column_vlr_devolucao->setTransformer(function($value, $object, $row)
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
        $tabela_vendas_column_vlr_liquido->setTransformer(function($value, $object, $row)
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

        $tabela_vendas->setDatabase('erp_online');
        $tabela_vendas->setModel('VendasVendedorMes');
        $tabela_vendas->setTitle("Vendas Vendedor");
        $tabela_vendas->setSize('100%', 280);
        $tabela_vendas->setColumns([$tabela_vendas_column_vendedor1_id,$tabela_vendas_column_vlr_bruto,$tabela_vendas_column_vlr_devolucao,$tabela_vendas_column_vlr_liquido]);
        $tabela_vendas->setCriteria($criteria_tabela_vendas);

        $tabela_vendas->setRowColorOdd('#F9F9F9');
        $tabela_vendas->setRowColorEven('#FFFFFF');
        $tabela_vendas->setFontRowColorOdd('#333333');
        $tabela_vendas->setFontRowColorEven('#333333');
        $tabela_vendas->setBorderColor('#DDDDDD');
        $tabela_vendas->setTableHeaderColor('#FFFFFF');
        $tabela_vendas->setTableHeaderFontColor('#333333');
        $tabela_vendas->setTableFooterColor('#FFFFFF');
        $tabela_vendas->setTableFooterFontColor('#333333');


        $nPerCliente = 0;
        $nPerFaturamento = 0;
        $ArrayCriteria = array();
        $Criteria_Metas = new TCriteria; 

        if((isset($param['mes']) and !empty($param['mes'])) OR $mes->getValue())
        {
            if(isset($param['mes']) and !empty($param['mes'])){
                $ArrayCriteria['mes'] = $param['mes'];
            }else{
                $ArrayCriteria['mes'] = $mes->getValue();
            }    
            $Criteria_Metas->add(new TFilter('mes', '=', $ArrayCriteria['mes'])); 

        }
        if((isset($param['ano']) and !empty($param['ano'])) OR $ano->getValue())
        {
            if(isset($param['ano']) and !empty($param['ano'])){
                $ArrayCriteria['ano'] = $param['ano'];
            }else{
                $ArrayCriteria['ano'] = $ano->getValue();
            }    
            $Criteria_Metas->add(new TFilter('ano', '=', $ArrayCriteria['ano'])); 

        }
        if((isset($param['vendedor']) and !empty($param['vendedor'])) OR $vendedor->getValue())
        {
            if(isset($param['vendedor']) and !empty($param['vendedor'])){
                $ArrayCriteria['vendedor'] = $param['vendedor'];
            }else{
                $ArrayCriteria['vendedor'] = $vendedor->getValue();
            }    
            $Criteria_Metas->add(new TFilter('vendedor_id', '=', $ArrayCriteria['vendedor'])); 

        }

        TTransaction::open('erp_online');

        $repository = new TRepository('MetaVendedorMes'); 
        $oMetaClientes = $repository->load($Criteria_Metas);

        if ($oMetaClientes)
        {
            foreach ($oMetaClientes as $oMetaCliente)
            {
                $nPerCliente += $oMetaCliente->numero_cliente;
                $nPerFaturamento += $oMetaCliente->valor;
            }
        }

        TTransaction::close();

        if ($nPerCliente > 0){
            $cliente_positivado->setTarget($nPerCliente, '#ffffff'
                , function($percentage, $target ){
                    return "{$percentage}% de ".$target;
                }
            );
        }

        if ($nPerFaturamento > 0){
            $quantidade_notas->setTarget($nPerFaturamento, '#ffffff'
                , function($percentage, $target ){
                    return "{$percentage}% de "."R$ " . number_format($target, 2, ",", ".");
                }
            );
        }

        $row1 = $this->form->addFields([new TLabel("Mes/Ano:", null, '14px', null, '100%'),$mes,$ano],[new TLabel("Vendedor:", null, '14px', null, '100%'),$vendedor,$button_buscar]);
        $row1->layout = [' col-12 col-sm-3',' col-12 col-sm-9'];

        $row2 = $this->form->addFields([$quantidade_notas],[$notas_rcg],[$faturamento_servico]);
        $row2->layout = [' col-sm-4',' col-sm-4',' col-sm-4'];

        $row3 = $this->form->addFields([$comodato],[$devolucao],[$cliente_positivado]);
        $row3->layout = [' col-sm-4',' col-sm-4',' col-sm-4'];

        $row4 = $this->form->addFields([$venda_mes]);
        $row4->layout = [' col-sm-12'];

        $row5 = $this->form->addFields([$tabela_vendas]);
        $row5->layout = [' col-sm-12'];

        $row6 = $this->form->addFields([],[],[],[new TLabel("Atualizado em:", null, '14px', null, '100%'),$atualizacao]);
        $row6->layout = ['col-sm-3','col-sm-3',' col-sm-3',' col-3 col-sm-3 col-md-3'];

        if(!isset($param['mes']) && $mes->getValue())
        {
            $_POST['mes'] = $mes->getValue();
        }
        if(!isset($param['ano']) && $ano->getValue())
        {
            $_POST['ano'] = $ano->getValue();
        }
        if(!isset($param['atualizacao']) && $atualizacao->getValue())
        {
            $_POST['atualizacao'] = $atualizacao->getValue();
        }

        $searchData = $this->form->getData();
        $this->form->setData($searchData);

        $filterVar = $searchData->vendedor;
        if($filterVar)
        {
            $criteria_quantidade_notas->add(new TFilter('vendas_vendedor_mes.vendedor1_id', '=', $filterVar)); 
        }
        $filterVar = $searchData->mes;
        if($filterVar)
        {
            $criteria_quantidade_notas->add(new TFilter('vendas_vendedor_mes.mes', '=', $filterVar)); 
        }
        $filterVar = $searchData->ano;
        if($filterVar)
        {
            $criteria_quantidade_notas->add(new TFilter('vendas_vendedor_mes.ano', '=', $filterVar)); 
        }
        $filterVar = $searchData->mes;
        if($filterVar)
        {
            $criteria_notas_rcg->add(new TFilter('vendas_vendedor_mes.mes', '=', $filterVar)); 
        }
        $filterVar = $searchData->ano;
        if($filterVar)
        {
            $criteria_notas_rcg->add(new TFilter('vendas_vendedor_mes.ano', '=', $filterVar)); 
        }
        $filterVar = $searchData->mes;
        if($filterVar)
        {
            $criteria_faturamento_servico->add(new TFilter('vendas_vendedor_mes.mes', '=', $filterVar)); 
        }
        $filterVar = $searchData->ano;
        if($filterVar)
        {
            $criteria_faturamento_servico->add(new TFilter('vendas_vendedor_mes.ano', '=', $filterVar)); 
        }
        $filterVar = $searchData->mes;
        if($filterVar)
        {
            $criteria_comodato->add(new TFilter('vendas_vendedor_mes.mes', '=', $filterVar)); 
        }
        $filterVar = $searchData->ano;
        if($filterVar)
        {
            $criteria_comodato->add(new TFilter('vendas_vendedor_mes.ano', '=', $filterVar)); 
        }
        $filterVar = $searchData->mes;
        if($filterVar)
        {
            $criteria_devolucao->add(new TFilter('vendas_vendedor_mes.mes', '=', $filterVar)); 
        }
        $filterVar = $searchData->ano;
        if($filterVar)
        {
            $criteria_devolucao->add(new TFilter('vendas_vendedor_mes.ano', '=', $filterVar)); 
        }
        $filterVar = $searchData->vendedor;
        if($filterVar)
        {
            $criteria_cliente_positivado->add(new TFilter('cliente_positivado.vendedor_id', '=', $filterVar)); 
        }
        $filterVar = $searchData->mes;
        if($filterVar)
        {
            $criteria_cliente_positivado->add(new TFilter('cliente_positivado.mes', '=', $filterVar)); 
        }
        $filterVar = $searchData->ano;
        if($filterVar)
        {
            $criteria_cliente_positivado->add(new TFilter('cliente_positivado.ano', '=', $filterVar)); 
        }
        $filterVar = $searchData->vendedor;
        if($filterVar)
        {
            $criteria_venda_mes->add(new TFilter('vendas_vendedor_mes.vendedor1_id', '=', $filterVar)); 
        }
        $filterVar = $searchData->mes;
        if($filterVar)
        {
            $criteria_venda_mes->add(new TFilter('vendas_vendedor_mes.mes', '=', $filterVar)); 
        }
        $filterVar = $searchData->ano;
        if($filterVar)
        {
            $criteria_venda_mes->add(new TFilter('vendas_vendedor_mes.ano', '=', $filterVar)); 
        }
        $filterVar = $searchData->mes;
        if($filterVar)
        {
            $criteria_tabela_vendas->add(new TFilter('vendas_vendedor_mes.mes', '=', $filterVar)); 
        }
        $filterVar = $searchData->ano;
        if($filterVar)
        {
            $criteria_tabela_vendas->add(new TFilter('vendas_vendedor_mes.ano', '=', $filterVar)); 
        }
        $filterVar = $searchData->vendedor;
        if($filterVar)
        {
            $criteria_tabela_vendas->add(new TFilter('vendas_vendedor_mes.vendedor1_id', '=', $filterVar)); 
        }

        BChart::generate($quantidade_notas, $notas_rcg, $faturamento_servico, $comodato, $devolucao, $cliente_positivado, $venda_mes, $tabela_vendas);

        // create the form actions

        // vertical box container
        $container = new TVBox;
        $container->style = 'width: 100%';
        $container->class = 'form-container';
        if(empty($param['target_container']))
        {
            $container->add(TBreadCrumb::create(["Desenvolvimento","Dashboard Gerencia(Clonado)"]));
        }
        $container->add($this->form);

        parent::add($container);

    }

    public static function onChangeMes($param = null) 
    {
        try 
        {

        }
        catch (Exception $e) 
        {
            new TMessage('error', $e->getMessage());    
        }
    }

    public function onShow($param = null)
    {               

        if(isset($param['vendedor'])){

            TDBUniqueSearch::disableField(self::$formName, 'vendedor');

        }else{
            TDBUniqueSearch::enableField(self::$formName, 'vendedor');
        }

        /*
        $cUsuario = TSession::getValue('userid');

        TTransaction::open('erp_online');

        $oVendedor = Vendedor::where('system_users_id', '=', $cUsuario)->first();
        //$dt_inicio = date("Y-m-01");
        //$dt_fim    = date("Y-m-t");

        $object = new stdClass();

        if(isset($param['vendedor'])){

            $object->vendedor = $param['vendedor'];

        }elseif($oVendedor){

            TDBUniqueSearch::disableField(self::$formName, 'vendedor');
            $object->vendedor = $oVendedor->id;

        }
        //$object->dt_inicio = $dt_inicio;
        //$object->dt_fim    = $dt_fim;
        TForm::sendData(self::$formName, $object);

        TTransaction::close();
        */
    } 

}

