<?php

class NotaSaidatemRestService extends AdiantiRecordService
{
    const DATABASE      = 'erp_online';
    const ACTIVE_RECORD = 'NotaSaidaItem';
    const ATTRIBUTES    = ['aliq_icms','aliq_ipi','base_icms','base_ipi','id','item','nota_saida_id','pedido_item_id','perc_desconto','peso','produto_id','qtd','qtd_dev','reg_ativo','tipo_entrada_id','vlr_bruto','vlr_desconto','vlr_dev','vlr_icms','vlr_ipi','vlr_tabela','vlr_total','vlr_unitario','estoque','duplicata','comodato','perc_comissao','comissao','vendedor1_id','vendedor2_id'];
  
    public function StoreArray($request)
    {
        
        $database     = static::DATABASE;
        $activeRecord = static::ACTIVE_RECORD;
        //$activeRecItm = static::ACTIVE_REC_IT;
        $aRequest = (array)$request;
        $aLog = array();
        $aReturn = array();
        
   
        if(isset( $aRequest['conteudo'] ) ){
            $aItens = $aRequest['conteudo'];
            
            foreach ($aItens as $aItem) {

                //var_dump($aItem);
                //die;     
                
                TTransaction::open($database);
                
                if(isset($aItem['nota_fiscal']) and isset($aItem['serie_fiscal'])){
                    
                    $oBusca = NotaSaida::where('nota_fiscal', '=', $aItem['nota_fiscal'])
                        ->where('serie_fiscal', '=', $aItem['serie_fiscal'])
                        ->first(); 

                    if($oBusca){
                        
                        $aItem['nota_saida_id'] = $oBusca->id;
                        $aItem['cliente_id'] = $oBusca->cliente_id;
                        $aItem['vendedor1_id'] = $oBusca->vendedor1_id;

                        if(isset($aItem['item'])){

                            $oBusca = NotaSaidaItem::where('nota_saida_id', '=', $aItem['nota_saida_id'])
                                ->where('item', '=', $aItem['item'])
                                ->first(); 
                        
                            if($oBusca){
                                $aItem['id'] = $oBusca->id; 
                            }
                            $oBusca = Produto::where('cod_erp', '=', $aItem['produto'])
                                ->first(); 
                            
                            if($oBusca){
                                $aItem['produto_id'] = $oBusca->id;                            
                            }

                            if(isset($aItem['cliente'])){
                                $oBusca = Cliente::where('cod_erp', '=', $aItem['cliente'])
                                ->first(); 
                                $aGrava['cliente_id'] = $oBusca->id;
                            }
                        
                            if(isset($aItem['vendedor1'])){
                                $oBusca = Vendedor::where('cod_erp', '=', $aItem['vendedor1'])
                                ->first(); 
                                if($oBusca){
                                    $aGrava['vendedor1_id'] = $oBusca->id;
                                }
                            }
                            if(isset($aItem['vendedor2'])){
                                $oBusca = Vendedor::where('cod_erp', '=', $aItem['vendedor2'])
                                ->first(); 
                                if($oBusca){
                                    $aGrava['vendedor2_id'] = $oBusca->id;
                                }
                            }
                                
                            $oBusca = TipoEntradaSaida::where('cod_erp', '=', $aItem['tes'])
                                ->first(); 
                            
                            if($oBusca){
                                $aItem['tes_id'] = $oBusca->id;                            
                            }
        
                            try 
                            {
                                $object = new $activeRecord;
                                $object->fromArray( (array) $aItem);
                                $object->store();
                                
                                if($object){
                                    $aLog['status']  = 'OK';
                                    $aLog['id']      = $object->id;
                                    $aLog['nota_fiscal_id'] = $aItem['nota_saida_id'];
                                    $aLog['nota_fiscal'] = $aItem['nota_fiscal'];
                                    $aLog['vendedor1'] = $aItem['vendedor1_id'];
                                    $aReturn [] = $aLog;
                                
                                    unset($aLog);
                                    unset($object);
                                    unset($oBusca);
                                    unset($aPedido);
                                }
                            }
                            catch (Exception $e) 
                            {
                                $aLog['status']  = 'error';
                                $aLog['message'] = $e->getMessage(); //new TMessage('error', $e->getMessage());  
                                $aReturn [] = $aLog;
                            }
                            
                        }else{
                            $aLog['status']  = 'error';
                            $aLog['message'] = "Nota Fiscal nao localizada"; //new TMessage('error', $e->getMessage());  
                            $aLog['json'] = $aItem;
                            $aReturn [] = $aLog;                        
                        }
                        
                    }else{
                        $aLog['status']  = 'error';
                        $aLog['message'] = "Nota Fiscal nao localizada"; //new TMessage('error', $e->getMessage());  
                        $aLog['json'] = $aItem;
                        $aReturn [] = $aLog;                        
                    }

                }else{
                    $aLog['status']  = 'error';
                    $aLog['message'] = "documento nao localizado"; //new TMessage('error', $e->getMessage());  
                    $aLog['json'] = $aItem;
                    $aReturn [] = $aLog;
                }

                TTransaction::close();
            }
            
        }else{
            $aLog['status']  = 'error';
            $aLog['message'] = "conteudo nao localizado"; //new TMessage('error', $e->getMessage());  
            $aLog['json'] = $aItem;
            $aReturn [] = $aLog;
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
