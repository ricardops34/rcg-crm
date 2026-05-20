<?php

class PedidoRestService extends AdiantiRecordService
{
    const DATABASE      = 'erp_online';
    const ACTIVE_RECORD = 'Pedido';
    const ATTRIBUTES    = ['ano','cliente_entrega_id','cliente_id','cod_erp','condicao_pagamento_id','dt_alteracao','dt_emissao','dt_inclusao','filial_id','id','intermediador_id','log_int','mensagem_nf','mes','nota_fiscal','pedido_estado_id','pedido_origem','presencial','serie','sinc','tabela_id','tipo','tp_frete','transportadora_id','user_id','vendedor1_id','vendedor2_id','vlr_comodato','vlr_frete','vlr_total'];
    
    public function StoreArray($request)
    {
        
        //return ['linha'=>76];
        
        $database     = static::DATABASE;
        $activeRecord = static::ACTIVE_RECORD;
        //$activeRecItm = static::ACTIVE_REC_IT;
        $aRequest = (array)$request;
        $aLog = array();
        $aReturn = array();
        
        if(isset( $aRequest['conteudo'] ) ){
            $aPedidos = $aRequest['conteudo'];
            
            //var_dump($request);
            //die;
            
            foreach ($aPedidos as $aPedido) {
                
                //return $aPedido;
                
                TTransaction::open($database);

                if(isset($aPedido['cod_erp'])){
                    $oBusca = Pedido::where('cod_erp', '=', $aPedido['cod_erp'])
                    ->first(); 
                    $aPedido['id'] = $oBusca->id;
                }

                if(isset($aPedido['empresa'])){
                    $oBusca = Empresa::where('cod_erp', '=', $aPedido['empresa'])
                    ->first(); 
                    if($oBusca){
                        $aPedido['empresa_id'] = $oBusca->id;
                    }
                }

                if(isset($aPedido['filial'])){
                    $oBusca = Filial::where('cod_erp', '=', $aPedido['filial'])
                    ->first(); 
                    if($oBusca){
                        $aPedido['filial_id'] = $oBusca->id;
                    }
                }
                
                if(isset($aPedido['pedido_estado'])){
                    $oBusca = PedidoEstado::where('cod_erp', '=', $aPedido['pedido_estado'])
                    ->first(); 
                    if($oBusca){
                        $aPedido['pedido_estado_id'] = $oBusca->id;
                    }
                }

                if(isset($aPedido['cliente'])){
                    $oBusca = Cliente::where('cod_erp', '=', $aPedido['cliente'])
                    ->first(); 
                    if($oBusca){
                        $aPedido['cliente_id'] = $oBusca->id;
                    }
                }
                
                if(isset($aPedido['cliente_entrega'])){
                    $oBusca = Cliente::where('cod_erp', '=', $aPedido['cliente_entrega'])
                    ->first(); 
                    if($oBusca){
                        $aPedido['cliente_entrega_id'] = $oBusca->id;
                    }
                }
                
                if(isset($aPedido['vendedor1'])){
                    $oBusca = Vendedor::where('cod_erp', '=', $aPedido['vendedor1'])
                    ->first(); 
                    if($oBusca){
                        $aPedido['vendedor1_id'] = $oBusca->id;
                    }
                }
                
                if(isset($aPedido['vendedor2'])){
                    $oBusca = Vendedor::where('cod_erp', '=', $aPedido['vendedor2'])
                    ->first(); 
                    if($oBusca){
                        $aPedido['vendedor2_id'] = $oBusca->id;
                    }
                }
                
                if(isset($aPedido['transportadora'])){
                    $oBusca = Transportadora::where('cod_erp', '=', $aPedido['transportadora'])
                    ->first(); 
                    if($oBusca){
                        $aPedido['transportadora_id'] = $oBusca->id;
                    }
                }
    
                if(isset($aPedido['tabela'])){
                    $oBusca = TabelaPreco::where('cod_erp', '=', $aPedido['tabela'])
                    ->first(); 
                    if($oBusca){
                        $aPedido['tabela_preco_id'] = $oBusca->id;
                    }
                }
                
                if(isset($aPedido['condicao'])){
                    $oBusca = CondicaoPagamento::where('cod_erp', '=', $aPedido['condicao'])
                    ->first(); 
                    if($oBusca){
                        $aPedido['condicao_pagamento_id'] = $oBusca->id;
                    }
                }                 
                /*
                $oOrcamento = Orcamento::where('cliente_id', '=', $aPedido['cliente_id'] )
                    ->where('vendedor_id', '=', $aPedido['vendedor1_id'] )
                    ->where('dt_emissao', '=', $aPedido['dt_emissao'] )
                    ->first(); 
                    
                if($oOrcamento){
                    $aPedido['orcamento_id'] = $oOrcamento->id;
                }else{
                    $oOrcamento = new Orcamento();
                    $oOrcamento->campo = 'valor';
                    $oOrcamento->store();
                }
                */
                try 
                {
                    $object = new $activeRecord;
                    $object->fromArray( (array) $aPedido);
                    $object->store();

                    $aLog['status']  = 'OK';
                    $aLog['id']      = $object->id;
                    $aLog['cod_erp'] = $aPedido['cod_erp'];
                    $aReturn [] = $aLog;
                    
                    unset($aLog);
                    unset($object);
                    unset($aPedido);
                }
                catch (Exception $e) 
                {
                    $aLog['status']  = 'error';
                    $aLog['message'] = $e->getMessage(); //new TMessage('error', $e->getMessage());  
                    $aReturn [] = $aLog;
                }
                TTransaction::close();
            }
            
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
