<?php

class WhatsAppRestService extends AdiantiRecordService
{
    const DATABASE      = 'erp_online';
    const ACTIVE_RECORD = 'Cliente';
    const ATTRIBUTES    = ['bairro','carteira','celular','celular2','cep','cliente','cnpj_cpf','cod_erp','complemento','condicao_pagamento_id','contato','contribuinte','data_cadastro','destaca_ie','dt_alteracao','dt_bloqueio','dt_inclusao','dt_reativacao','dt_revisao','email','endereco','fantasia','fax','filial_cadastro','filial_id','id','ie','im','latitude','limite','log_int','longitude','motivo_bloqueio','motivo_bloqueio_id','municipio','municipio_id','nascimento','obs','obs_bloqueio','obs_reativacao','primeira_compra','razao','reg_ativo','regiao_cliente_id','rg','risco','seguimento_id','sinc','site','situacao_cadastral_id','status','system_unit_id','tabela_preco_id','telefone1','telefone2','tipo','tipo_cliente_id','uf','ultima_compra','ultima_visita','ultimo_atendimento','vencimento_limite','vendedor_id'];
    /*
    public function telefone($request)
    {
        $database     = static::DATABASE;
        $activeRecord = static::ACTIVE_RECORD;
        
        $aReturn = array();
        $aRequest = (array)$request;
        $cTel = $aRequest['telefone'];

        if(isset($cTel)){

            TTransaction::open($database);
            $cPais = substr($cTel, 0 ,1); 
            $cDdd  = substr($cTel, 2 ,2);;
            $cCel  = substr($cTel, 4 ,strlen(trim($cTel)));
            
            $posarr = strpos( $cCel, '@' );

            if($posarr === false){
            }else{
                $cCel = substr($cCel, 0,$posarr);
            }

            if(strlen(trim($cCel)) < 9){
                $cCel = '9'.$cCel;
            }

            $oCliente = Cliente::where('celular', '=', $cCel)
            ->where('status', '=', 'A')
            ->first();

            try 
            {
                if($oCliente){

                    $cTipo = trim($oCliente->tipo);
                    $cCnpj = trim($oCliente->cnpj_cpf);
                    
                    if($cTipo == 'F'){
                        $nome = trim($oCliente->razao);
                        $pos = strpos($nome, ' ' );
                        $fantasia = trim(substr($nome, 0,$pos));
                    }else{ //19.654.062/0001-45
                        $nome = trim($oCliente->razao);
                        $fantasia = trim($oCliente->fantasia);
                    }
                    $aReturn['status']  = 'success';
                    $aReturn['id']  = $oCliente->id;
                    $aReturn['codigo']  = $oCliente->cod_erp;                    
                    $aReturn['nome']= $nome;
                    $aReturn['fantasia']= $fantasia;
                    $aReturn['tipo'] = $cTipo;
                    $aReturn['doc'] = $cCnpj;                    
                    $aReturn['telefone']= $oCliente->celular;
                    $aReturn['email']= $oCliente->email;

                    $aReturn['titulos']  = "Não";
                    $aReturn['vencidos'] = "Não";
                    $aReturn['vencendo'] = "Não";
                    
                    $aReturn['valorVencidos'] = 0;
                    $aReturn['valorVencendo'] = 0;
                    $aReturn['dataCompra'] = $oCliente->ultima_compra;                    

                    $criteria = new TCriteria;
                    
                    //filtros
                    $criteria->add(new TFilter('cliente_id', '=', $oCliente->id)); 
                    $criteria->add(new TFilter('reg_ativo', '=', 'S')); 
                    $criteria->add(new TFilter('saldo', '>', '0')); 

                    // define criteria properties
                    $criteria->setProperty('limit' , 10);
                    $criteria->setProperty('offset', 0);
                    $criteria->setProperty('order' , 'id');

                    // load using repository
                    $repository = new TRepository('TituloReceber'); 
                    $oTitulos = $repository->load($criteria);   

                    $aTitulos = array();
                    $nTit = 0;
                    foreach ($oTitulos as $oTitulo) {
                        $nTit += 1;
                        //const ATTRIBUTES    = ['acrescimo','agencia','banco','cliente_id','cod_barras','conta','controle_bco','decrescimo','dig_nosso_numero','dt_alteracao','dt_digitacao','dt_inclusao','emissao','filial_id','forma_pgto','historico','id','id_cnab','impresso','lin_digitavel','mora_dia','natureza_id','nosso_numero','numero','origem','parcela','pedido_id','perc_juros','prefixo','reg_ativo','saldo','taxa_multa','tipo','usr_alteracao','usr_inclusao','valor','valor_juros','vencimento','venc_orig','venc_real','vendedor_id','baixa','system_unit_id','e1_recno'];
                        $aTitulo = array();                        
                        $aTitulo['id'] = $oTitulo->id;//$oTitulo->toArray();
                        $aTitulo['numero'] = $oTitulo->numero;
                        $aTitulo['emissao'] = $oTitulo->emissao;
                        $aTitulo['vencimento'] = $oTitulo->venc_real;
                        $aTitulo['valor'] = round($oTitulo->valor,2);
                        $aTitulo['saldo'] = round($oTitulo->saldo,2);
                        $aTitulo['mora'] = round($oTitulo->mora_dia,2);
                        $aTitulo['multa'] = round($oTitulo->taxa_multa,2);
                        $aTitulo['juros'] = round($oTitulo->perc_juros,3);
                        $aTitulo['linhaDigitavel'] = $oTitulo->lin_digitavel;
                        $aTitulos[] = $aTitulo;

                        $aReturn['valorTitulos'] += Round($oTitulo->saldo,2);

                        if($oTitulo->vencimento < date('Y-m-d')){
                            $aReturn['valorVencidos'] += Round($oTitulo->saldo,2);
                        }else{
                            $aReturn['valorVencendo'] += Round($oTitulo->saldo,2);                        
                        }
                    }

                    if($aReturn['valorTitulos'] > 0){
                        $aReturn['titulos'] = "Sim";
                    }

                    if($aReturn['valorVencidos'] > 0){
                        $aReturn['vencidos'] = "Sim";
                    }

                    if($aReturn['valorVencendo'] > 0){
                        $aReturn['vencendo'] = "Sim";
                    }                    
                    $aReturn['numeroTitulos'] = $nTit;
                    $aReturn['titulos'] = $aTitulos;
                
                }else{
                    $aReturn['status']  = 'error';
                    $aReturn['message'] = 'Telefone não encontrado';
                    $aReturn['telefone']= $cCel;
                }
                
                unset($oBusca);
            }
            
            catch (Exception $e) 
            {
                $aReturn['status']  = 'error';
                $aReturn['message'] = $e->getMessage(); //new TMessage('error', $e->getMessage());  
            }

            TTransaction::close();  
        }else{

            $aReturn['status']  = 'error';
            $aReturn['message'] = 'telefone não informado';
        }

        return $aReturn;
        
    }    
    */
    private function buscarTitulo($param = null)
    {
        $database     = static::DATABASE;
        $activeRecord = static::ACTIVE_RECORD;
        $resposta = array();
        try {
            // Obtém o telefone do parâmetro da requisição
            $titulo = isset($_GET['titulo']) ? $_GET['titulo'] : null; //isset($param['celular']) ? $param['celular'] : null;
            
            // Valida se o telefone foi informado
            if (empty($titulo)) {
                throw new Exception('Titulo não informado');
            }

            TTransaction::open($database); 
            $titulos = TituloReceber::where('numero', '=', $titulo)
                         ->where('reg_ativo', '=', 'S')
                         ->orderBy('vencimento')
                         ->load();

            $resposta = [];
            if ($titulos) {
                foreach ($titulos as $titulo) {
                    $resposta[] = [
                        'id' => $titulo->id,
                        'numero' => $titulo->numero,
                        'parcela' => $titulo->parcela,
                        'dataVencimento' => $titulo->venc_real,
                        'dataEmissao' => $titulo->emissao,
                        'valor' => number_format($titulo->saldo,2),//number_format($titulo->saldo, 2, ',', '.'),
                        'linhaDigitavel'=> $titulo->lin_digitavel,
                        'diasAtraso' => $this->calcularDiasAtraso($titulo->venc_real)
                    ];
                }
            }
            TTransaction::close();            

            
        } catch (Exception $e) {
            TTransaction::rollback();
            
            $resposta = [
                'success' => false,
                'message' => $e->getMessage()
            ];
            
            //echo json_encode($resposta);
        }

        return $resposta;
    }

    public function buscar($param = null)
    {
        $database     = static::DATABASE;
        $activeRecord = static::ACTIVE_RECORD;
        $resposta = array();
        try {
            TTransaction::open($database); // Substitua pelo nome da sua conexão
            
            // Obtém o telefone do parâmetro da requisição
            $telefone = isset($_GET['celular']) ? $_GET['celular'] : null; //isset($param['celular']) ? $param['celular'] : null;
            
            // Valida se o telefone foi informado
            if (empty($telefone)) {
                throw new Exception('Telefone não informado');
            }
            
            // Formata o telefone (remove caracteres não numéricos)
            $telefone = preg_replace('/[^0-9]/', '', $telefone);
            
            if(strlen($telefone) == 8 and substr($telefone, 0, 1) == "9"){
                $telefone = "9".$telefone;
            }

            // Busca o cliente pelo telefone
            $cliente = Cliente::where('celular', '=', $telefone)->first();
            
            if (!$cliente) {
                throw new Exception('Cliente não encontrado');
            }
            
            $cTipo = trim($cliente->tipo);
            $cCnpj = trim($cliente->cnpj_cpf);
            $fantasia = '';
            if($cTipo == 'F'){
                $nome = trim($cliente->razao);
                $pos = strpos($nome, ' ' );
                $fantasia = trim(substr($nome, 0,$pos));
            }else{ //19.654.062/0001-45
                $nome = trim($cliente->razao);
                $fantasia = trim($cliente->fantasia);
            }            

            // Busca informações adicionais
            $ultimaCompra = $this->buscarUltimaCompra($cliente->id);
            $vendedor = $this->buscarVendedor($cliente->vendedor_id ?? null);
            
            if($cTipo == 'F'){
                $notasFiscais = $this->buscarUltimasNotasFiscais($cliente->id, 15);
                $titulosAbertos = $this->buscarTitulosAbertos($cliente->id);
            }
            // Monta o array de resposta
            $resposta = [
                'success' => true,
                'data' => [
                    'codigo' => $cliente->cod_erp,
                    'nome' => $nome,
                    'fantasia' => $fantasia,
                    'tipo' => $cTipo,
                    'cpf' => $cCnpj,
                    'telefone' => $cliente->telefone1,
                    'celular' => $cliente->celular,
                    'dataUltimaCompra' => isset($ultimaCompra->dt_emissao) ? 
                                          $ultimaCompra->dt_emissao : null,
                    'vendedor' => isset($vendedor->nome) ? $vendedor->nome : null,
                    'notasFiscais' => $notasFiscais,
                    'titulos' => $titulosAbertos
                ]
            ];
            
            TTransaction::close();
            
            // Retorna os dados em formato JSON
            //echo json_encode($resposta);
            
            
        } catch (Exception $e) {
            TTransaction::rollback();
            
            $resposta = [
                'success' => false,
                'message' => $e->getMessage()
            ];
            
            //echo json_encode($resposta);
        }

        return $resposta;
    }
    
    /**
     * Busca a última compra do cliente
     */
    private function buscarUltimaCompra($clienteId)
    {
        // Supondo que exista uma tabela 'vendas' ou 'pedidos'
        return NotaSaida::where('cliente_id', '=', $clienteId)
                    ->where('reg_ativo', '=', 'S')
                    ->orderBy('dt_emissao', 'desc')
                    ->first();
    }
    
    /**
     * Busca informações do vendedor
     */
    private function buscarVendedor($vendedorId)
    {
        if (!$vendedorId) {
            return null;
        }
        
        return Vendedor::find($vendedorId);
    }
    
    /**
     * Busca as últimas notas fiscais do cliente
     */
    private function buscarUltimasNotasFiscais($clienteId, $limite = 10)
    {
        
        $criteria = new TCriteria;
        /*$criteria->add(new TFilter('cliente_id','=', $clienteId )); 
        $criteria->add(new TFilter('reg_ativo','=', 'S' )); 
        // define criteria properties
        $criteria->setProperty('limit' , $limite);
        //$criteria->setProperty('offset', 20);
        $criteria->setProperty('order' , 'nota_fiscal');
        $criteria->setProperty('direction','ASC');

        $repository = new TRepository('NotaSaida');
        $notasFiscais = $repository->load($criteria);
        */
        $notaSaida = NotaSaida::where('cliente_id', '=', $clienteId)
                        ->where('reg_ativo', '=', 'S')
                        ->orderBy('nota_fiscal', 'desc')
                        ->take($limite)
                        ->load();

        $retorno = array();//= [];
        $itens = array();//= [];
        if ($notaSaida) {
            foreach ($notaSaida as $nota) {

                $itens = array();//= [];
                /*
                $notaSaidaItem = NotaSaidaItem::where('nota_saida_id', '=', $nota->id)
                        ->where('reg_ativo', '=', 'S')
                        ->orderBy('item', 'desc')
                        ->load();    
                
                if ($notaSaidaItem) {
                    
                    foreach ($notaSaidaItem as $item) {
                            
                            $produto = Produto::find( $item->produto_id );

                            $itens[] = [
                                'item' => $item->item,
                                'produto' => $produto->cod_erp,
                                'descricao' => $produto->descricao,
                                'quantidade' => number_format($item->qtd,2),
                                'valorUnitario' => number_format($item->vlr_unitario,2),
                                'valorTotal' => number_format($item->vlr_bruto,2)
                            ];
                    }
                }
                */
                $retorno[] = [
                    'id' => $nota->id,
                    'numero' => $nota->nota_fiscal,
                    'dtEmissao' => $nota->dt_emissao,
                    'valorTotal' => number_format($nota->vlr_mercadoria,2),//number_format($nota->vlr_mercadoria, 2, ',', '.'),
                    'chaveNfe' => $nota->chave_nfe,
                    'itens' => $itens
                ];
            }
        }
        
        return $retorno;
    }
    
    /**
     * Busca os títulos em aberto do cliente
     */
    private function buscarTitulosAbertos($clienteId)
    {
        $titulos = TituloReceber::where('cliente_id', '=', $clienteId)
                         ->where('reg_ativo', '=', 'S')
                         ->where('saldo', '>', 0)
                         ->orderBy('vencimento')
                         ->load();
        
        $resultado = [];
        if ($titulos) {
            foreach ($titulos as $titulo) {
                $resultado[] = [
                    'id' => $titulo->id,
                    'numero' => $titulo->numero,
                    'parcela' => $titulo->parcela,
                    'dataVencimento' => $titulo->venc_real,
                    'dataEmissao' => $titulo->emissao,
                    'valor' => number_format($titulo->saldo,2),//number_format($titulo->saldo, 2, ',', '.'),
                    'linhaDigitavel'=> $titulo->lin_digitavel,
                    'diasAtraso' => $this->calcularDiasAtraso($titulo->venc_real)
                ];
            }
        }
        
        return $resultado;
    }
    
    /**
     * Calcula os dias em atraso
     */
    private function calcularDiasAtraso($dataVencimento)
    {
        $dataAtual = new DateTime();
        $dataVenc = new DateTime($dataVencimento);
        
        if ($dataVenc < $dataAtual) {
            $diferenca = $dataVenc->diff($dataAtual);
            return $diferenca->days;
        }
        
        return 0;
    }



    /**
     * load($param)
     *
     * Load an Active Records by its ID
     * 
     * @return The Active Record as associative array
     * @param $param['id'] Object ID
     */
    
    
    /**
     * delete($param)
     *
     * Delete an Active Records by its ID
     * 
     * @return The Operation result
     * @param $param['id'] Object ID
     */
    
    
    /**
     * store($param)
     *
     * Save an Active Records
     * 
     * @return The Operation result
     * @param $param['data'] Associative array with object data
     */
    
    
    /**
     * loadAll($param)
     *
     * List the Active Records by the filter
     * 
     * @return Array of records
     * @param $param['offset']    Query offset
     *        $param['limit']     Query limit
     *        $param['order']     Query order by
     *        $param['direction'] Query order direction (asc, desc)
     *        $param['filters']   Query filters (array with field,operator,field)
     */
    
    
    /**
     * deleteAll($param)
     *
     * Delete the Active Records by the filter
     * 
     * @return Array of records
     * @param $param['filters']   Query filters (array with field,operator,field)
     */
}
