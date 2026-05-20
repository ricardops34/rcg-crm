<?php

class SupervisorRestService extends AdiantiRecordService
{
    const DATABASE      = 'erp_online';
    const ACTIVE_RECORD = 'Supervisor';
    const ATTRIBUTES    = ['celular','cod_erp','dashboard','ddd','desligado','dt_alteracao','dt_inclusao','dt_nascmento','email','filial_id','id','nome','nome_reduzido','status','system_unit_id','system_users_id','telefone','vendedor'];

    public function StoreArray($request)
    {
        $database     = static::DATABASE;
        $activeRecord = static::ACTIVE_RECORD;

        $aReturn = array();
        $aItens  = (array)$request['conteudo'];
        $aLog   = array();  
        $aVends = array();  
        //var_dump($request);
    
        foreach ($aItens as $aItem) {
        
            $aGrava = (array)$aItem;   
            $userId = 0;
            //var_dump($aGrava);
            
            if(isset($aGrava['cod_erp'])){
        
                TTransaction::open($database);

                if(isset($aGrava['cod_erp'])){
                    $oBusca = Supervisor::where('cod_erp', '=', ltrim(rtrim($aGrava['cod_erp'])))
                    ->first(); 
                    
                    if($oBusca){
                        $aGrava['id'] = $oBusca->id;
                    }
                }
                
                if(isset($aGrava['empresa'])){
                    $oBusca = Empresa::where('cod_erp', '=', $aGrava['empresa'])
                    ->first(); 
                    if($oBusca){
                        $aGrava['empresa_id'] = $oBusca->id;
                    }
                }
    
                if(isset($aGrava['filial'])){
                    $oBusca = Filial::where('cod_erp', '=', $aGrava['filial'])
                    ->first(); 
                    if($oBusca){
                        $aGrava['filial_id'] = $oBusca->id;
                    }
                }
                
                if(isset($aGrava['email'])){
                    $aGrava['email'] = ltrim(rtrim(strtolower($aGrava['email'])));
                }
            
                try 
                {
                    $object = new $activeRecord;
                    $object->fromArray( (array) $aGrava);
                    $object->store();
                    $aReturn [] = $object->toArray();
                    $aVends  [] = $object->toArray();
                }
                catch (Exception $e) 
                {
                    $aLog['status']  = 'error';
                    $aLog['message'] = $e->getMessage(); //new TMessage('error', $e->getMessage());  
                    $aReturn [] = $aLog;
                }
        
                TTransaction::close();
    
                unset($aLog);
                unset($object);
                unset($aGrava);
                unset($oUsuario);
                unset($oNewuser);
                
            }else{
            
                $aLog['status']  = 'error';
                $aLog['message'] = 'cod_erp é obrigatorio'; //new TMessage('error', $e->getMessage());  
                $aReturn [] = $aLog;
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
