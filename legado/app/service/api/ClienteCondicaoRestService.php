<?php

class ClienteCondicaoRestService extends AdiantiRecordService
{
    const DATABASE      = 'erp_online';
    const ACTIVE_RECORD = 'ClienteCondicao';
    const ATTRIBUTES    = ['cliente_id','condicao_pagamento_id','dt_alteracao','dt_inclusao','id','padrao'];
    
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
                
                if(isset($aGrava['cliente'])){
                    $oBusca = Cliente::where('cod_erp', '=', $aGrava['cliente'])
                    ->first(); 
                    $aGrava['cliente_id'] = $oBusca->id;
                }                    
                                
                if(isset($aGrava['condicao_pagamento'])){
                    $oBusca = CondicaoPagamento::where('cod_erp', '=', $aGrava['condicao_pagamento'])
                    ->first(); 
                    $aGrava['condicao_pagamento_id'] = $oBusca->id;
                }                                   

                $oBusca = ClienteCondicao::where('cliente_id', '=', $aGrava['cliente_id'] )
                        ->where('condicao_pagamento_id', '=', $aGrava['condicao_pagamento_id'] )
                        ->first(); 
                
                if($oBusca){                        
                    $aGrava['condicao_pagamento_id'] = $oBusca->id;
                }

                try 
                {
                    $object = new $activeRecord;
                    $object->fromArray( (array) $aGrava);
                    $object->store();

                    $aLog['status']  = 'OK';
                    $aLog['id']      = $object->id;
                    $aLog['cod_erp'] = $aGrava['cod_erp'];
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
