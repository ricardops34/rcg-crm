<?php

class NotaSaidaXMLRestService extends AdiantiRecordService
{
    const DATABASE      = 'erp_online';
    const ACTIVE_RECORD = 'NotasaidaXml';
    const ATTRIBUTES    = ['chave','data_nfe','destinatario','email','hora_nfe','id','modelo','nota_fiscal','nota_saida_id','protocolo','remetente','serie_fiscal','situcao','situcao_cancelamento','situcao_email','xml_cancelamento','xml_sig','xml_tss'];
    
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
                
                $aGrava = (array)$aItem; 
                
                TTransaction::open($database);

                if(isset($aItem['chave'])){
                    $oBusca = NotaSaida::where('chave_nfe', '=', $aItem['chave'])
                    ->first(); 
                    $aGrava['nota_saida_id'] = $oBusca->id;
                }

                if(isset($aGrava['nota_saida_id'])){
                    $oBusca = NotasaidaXml::where('nota_saida_id', '=',$aGrava['nota_saida_id'])
                    ->first(); 
                    $aGrava['id'] = $oBusca->id;
                }
                
                try 
                {
                    $object = new $activeRecord;
                    $object->fromArray( (array) $aGrava);
                    $object->store();

                    $aLog['status']  = 'OK';
                    $aLog['id']      = $object->id;
                    $aLog['nota'] = $aItem['chave'];
                    $aReturn [] = $aLog;
                    
                    unset($aLog);
                    unset($object);
                    unset($aItem);
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
