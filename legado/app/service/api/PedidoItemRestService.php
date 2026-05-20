<?php

class PedidoItemRestService extends AdiantiRecordService
{
    const DATABASE      = 'erp_online';
    const ACTIVE_RECORD = 'PedidoItem';
    const ATTRIBUTES    = ['armazem_id','dt_alteracao','dt_inclusao','id','item','pedido_id','per_desconto','produto_id','quantidade','tipo_movimentacao_id','vlr_acrescimo','vlr_desconto','vlr_item','vlr_total','vlr_unitario'];
    
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
            $aItens = $aRequest['conteudo'];
            
            //var_dump($request);
            
            foreach ($aItens as $aItem) {
                
                TTransaction::open($database);

                if(isset($aItem['cod_erp'])){
                    $oBusca = Pedido::where('cod_erp', '=', $aItem['cod_erp'])
                        ->first(); 
                    
                    if($oBusca){
                        $aItem['pedido_id'] = $oBusca->id;
                        if(isset($aItem['item'])){
                            $oBusca = PedidoItem::where('pedido_id', '=',$aItem['pedido_id'])
                                    ->where('item', '=', $aItem['item'])
                                    ->first(); 
                            if($oBusca){
                                $aItem['id'] = $oBusca->id; 
                            }
                        }
                    }
                    
                    $oBusca = Produto::where('cod_erp', '=', $aItem['produto'])
                        ->first(); 
                    
                    if($oBusca){
                        $aItem['produto_id'] = $oBusca->id;                            
                    }
                    /*
                    $oBusca = TipoMovimentacao::where('cod_erp', '=', $aItem['tipo_movimentacao'])
                        ->first(); 
                    
                    if($oBusca){
                        $aItem['tipo_movimentacao_id'] = $oBusca->id; 
                    }
                    */

                }
                try 
                {
                    $object = new $activeRecord;
                    $object->fromArray( (array) $aItem);
                    $object->store();
                    
                    if($object){
                        $aLog['status']  = 'OK';
                        $aLog['id']      = $object->id;
                        $aLog['cod_erp'] = $aItem['cod_erp'];
                        $aReturn [] = $aLog;
                    
                        unset($aLog);
                        unset($object);
                        unset($aPedido);
                    }
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
