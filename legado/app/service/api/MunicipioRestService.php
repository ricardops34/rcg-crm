<?php

class MunicipioRestService extends AdiantiRecordService
{
    const DATABASE      = 'erp_online';
    const ACTIVE_RECORD = 'Municipio';
    const ATTRIBUTES    = ['cod_erp','descricao','estado_id','codigo_ibge','id'];
    
    public function StoreArray($request)
    {
        $database     = static::DATABASE;
        $activeRecord = static::ACTIVE_RECORD;
       
        $aReturn = array();
        $aLog = array();
        $aItens = (array)$request['conteudo'];
        
        //echo '<pre>';
        //print_r($aItens);
        
        foreach ($aItens as $aItem) {
            
            $aGrava = (array)$aItem;    
            
            TTransaction::open($database);

            $oEstado = Estado::where('sigla',  '=', $aGrava['uf'])
                      ->first();    

            if ($oEstado)
            {
            
                if(isset($aGrava['cod_erp'])){
                    $oBusca = Municipio::where('cod_erp', '=', $aGrava['cod_erp'])
                        ->where('estado_id', '=', $oEstado->id)
                        ->first(); 
                        
                    $aGrava['id'] = $oBusca->id;
                }else{
                        $aGrava['estado_id'] = $oEstado->id;
                        $aGrava['codigo_ibge'] = ltrim(rtrim($oEstado->codigo_ibge)).ltrim(rtrim($aGrava['cod_erp']));
                        $aGrava['cod_erp'] = ltrim(rtrim($oEstado->codigo_ibge)).ltrim(rtrim($aGrava['cod_erp']));
                }   
                
                try 
                {
                    $object = new $activeRecord;
                    $object->fromArray( (array) $aGrava);
                    $object->store();
                    $aReturn [] = $object->toArray();
                }
                catch (Exception $e) 
                {
                    $aLog['status']  = 'error';
                    $aLog['message'] = $e->getMessage(); //new TMessage('error', $e->getMessage());  
                    $aReturn [] = $aLog;
                }

            }                

            TTransaction::close();

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
