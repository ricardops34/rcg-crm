<?php

class CategoriaRestService extends AdiantiRecordService
{
    const DATABASE      = 'erp_online';
    const ACTIVE_RECORD = 'Categoria';
    const ATTRIBUTES    = ['cod_erp','descricao','dt_alteracao','dt_inclusao','filial_id','id','status'];
    
    public function StoreArray($request)
    {
        $database     = static::DATABASE;
        $activeRecord = static::ACTIVE_RECORD;
        
        $aReturn = array();
        $aItens  = (array)$request['conteudo'];
    
        foreach ($aItens as $aItem) {   
            $aGrava = (array)$aItem;    
            TTransaction::open($database);

            if(isset($aGrava['filial'])){
                $oBusca = Filial::where('cod_erp', '=', $aGrava['filial'])
                ->first(); 
                $aGrava['filial_id'] = $oBusca->id;
            }
            
            if(isset($aGrava['cod_erp'])){
                $oBusca = Categoria::where('cod_erp', '=', $aGrava['cod_erp'])
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
