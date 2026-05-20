<?php

class TituloReceberRestService extends AdiantiRecordService
{
    const DATABASE      = 'erp_online';
    const ACTIVE_RECORD = 'TituloReceber';
    const ATTRIBUTES    = ['acrescimo','agencia','banco','cliente_id','cod_barras','conta','controle_bco','decrescimo','dig_nosso_numero','dt_alteracao','dt_digitacao','dt_inclusao','emissao','filial_id','forma_pgto','historico','id','id_cnab','impresso','lin_digitavel','mora_dia','natureza_id','nosso_numero','numero','origem','parcela','pedido_id','perc_juros','prefixo','reg_ativo','saldo','taxa_multa','tipo','usr_alteracao','usr_inclusao','valor','valor_juros','vencimento','venc_orig','venc_real','vendedor_id','baixa','system_unit_id','e1_recno'];

    public function StoreArray($request)
    {
        $database     = static::DATABASE;
        $activeRecord = static::ACTIVE_RECORD;
       
        $aReturn = array();
        $aLog = array();
        $aItens = array();
        $aRequest = (array)$request;
        
        if( isset( $aRequest['conteudo'] ) ){
            $aItens = $aRequest['conteudo'];
            
            foreach ($aItens as $aItem) {
                
                $aGrava = (array)$aItem;    
                TTransaction::open($database);
    
                if(isset($aGrava['e1_recno'])){
                    $oBusca = TituloReceber::where('e1_recno', '=', $aGrava['e1_recno'])
                            ->first();
                    
                    if($oBusca){
                        $aGrava['id'] = $oBusca->id;
                        
                    }elseif( isset($aGrava['numero'])  and isset($aGrava['tipo']) and isset($aGrava['prefixo']) and isset($aGrava['parcela'])){
                        $oBusca = TituloReceber::where('numero', '=', $aGrava['numero'])
                            ->where('parcela', '=', $aGrava['parcela'])
                            ->where('tipo'   , '=', $aGrava['tipo'])
                            ->where('prefixo', '=', $aGrava['prefixo'])
                            ->first();
                    
                        if($oBusca){
                            $aGrava['id'] = $oBusca->id;
                        }                            
                    }                            

                    if(isset($aGrava['filial'])){
                        $oBusca = Filial::where('cod_erp', '=', $aGrava['filial'])
                        ->first(); 
                        if($oBusca){
                            $aGrava['filial_id'] = $oBusca->id;
                        }
                    }
                        
                    if(isset($aGrava['cliente'])){
                        $oBusca = Cliente::where('cod_erp', '=', $aGrava['cliente'])
                        ->first(); 
                        if($oBusca){
                            $aGrava['cliente_id'] = $oBusca->id;
                        }
                    }
                    
                    if(isset($aGrava['vendedor'])){
                        $oBusca = Vendedor::where('cod_erp', '=', $aGrava['vendedor'])
                        ->first(); 
                        if($oBusca){
                            $aGrava['vendedor_id'] = $oBusca->id;
                        }else{
                            unset($aGrava['vendedor_id']);
                            unset($aGrava['vendedor']);
                        }
                    }                    

                    if(isset($aGrava['pedido'])){
                        $oBusca = Pedido::where('cod_erp', '=', $aGrava['pedido'])
                        ->first(); 
                        if($oBusca){
                            $aGrava['pedido_id'] = $oBusca->id;
                        }
                    } 
    
                    
                    if(isset($aGrava['natureza'])){
                        $oBusca = Natureza::where('cod_erp', '=', $aGrava['natureza'])
                        ->first(); 
                        if($oBusca){
                            $aGrava['natureza_id'] = $oBusca->id;
                        }else{
                            if(isset($aGrava['desc_nat'])){
                                
                                $objeto = new Natureza();
                                $objeto->cod_erp = $aGrava['natureza'];
                                $objeto->descricao = $aGrava['desc_nat'];
                                $objeto->store();
                                
                                $aGrava['natureza_id'] = $objeto->id;
                            }
                        }
                    } 
                    
                    if(isset($aGrava['origem'])){
                        if(trim($aGrava['origem']) == 'MATA460' ){
                            $oBusca = NotaSaida::where('nota_fiscal', '=', $aItem['numero'])
                            ->where('serie_fiscal', '=', $aItem['prefixo'])
                            ->first(); 
                            if($oBusca){
                                $aGrava['nota_fiscal_id'] = $oBusca->id;
                            }
                        }
                    }
                    /*
                    if(isset($aGrava['vias']) and isset($aGrava['cliente_id']) and isset($aGrava['vendedor_id'])){
                        if($aGrava['vias'] > 1){
                            
                            $oAtendimento = new Atendimento();
                            
                            $oAtendimentoTipo = AtendimentoTipo::find(AtendimentoTipo::COBRANCA); 
                            if($oAtendimentoTipo){
                                $oAtendimento->atendimento_tipo_id = $oAtendimentoTipo->id;
                                $oAtendimento->cor = $oAtendimentoTipo->AtendimentoTipo->cor;                            
                            }
                            
                            if(isset($aGrava['cliente_id'])){
                                $oCliente =  Cliente::find( $aGrava['cliente_id'] );

                                if($oCliente){
                                    $oAtendimento->cliente_id = $oCliente->id;
                                    $oAtendimento->codigo_cliente = $oCliente->cod_erp;
                                    $oAtendimento->nome = $oCliente->razao;
                                    $oAtendimento->telefone = $oCliente->telefone1;
                                    $oAtendimento->email = $oCliente->email;
                                }
                            }
                            
                            if(isset($aGrava['vendedor_id'])){
                                $oVendedor = Vendedor::find($aGrava['vendedor_id']);
            
                                if($oVendedor){
                                    $oAtendimento->vendedor_id = $oVendedor->id;
                                }
                            }
                            $oAtendimento->horario_inicial = $aGrava['horario_inicial'];
                            $oAtendimento->horario_final = $aGrava['horario_final'];

                            if(isset($aGrava['usuario'])){
                                $oAtendimento->observacao = '2 Via de boleto gerado por :'.$aGrava['usuario'];
                            }                            

                            $oBusca = Atendimento::where('cliente_id', '=', $oAtendimento->cliente_id)
                                ->where('vendedor_id', '=', $oAtendimento->vendedor_id)
                                ->where('atendimento_tipo_id', '=', $oAtendimento->atendimento_tipo_id)
                                ->where('horario_inicial', '=', $oAtendimento->horario_inicial)
                                ->first(); 
                            
                            if($oBusca){
                                $oAtendimento->id = $oBusca->id;
                            }
                            
                            $oAtendimento->store();

                        }
                    }
                    */
                }
                    
                try 
                {
                    $object = new $activeRecord;
                    $object->fromArray( (array) $aGrava);
                    $object->store();
    
                    $aLog['status']  = 'OK';
                    $aLog['id']      = $object->id;
                    $aLog['numero']  = $aGrava['numero'];
                    $aReturn [] = $aLog;
                        
                    unset($aLog);
                    unset($object);
                    unset($aGrava);
                }
                catch (Exception $e) 
                {
                    $aLog['status']  = 'error';
                    $aLog['message'] = $e->getMessage(); //new TMessage('error', $e->getMessage());  
                    $aReturn [] = $aLog;
                    unset($aLog);
                }
                
                TTransaction::close();
            }
            
        }else{
            $aLog['status']  = 'error';
            $aLog['mensagem']= " json com erro. Sem array 'conteudo'";
    
            $aReturn [] = $aLog;
            unset($aLog);
        }
        
        return $aReturn;

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
