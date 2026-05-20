<?php

class EstoqueRestService extends AdiantiRecordService
{
    const DATABASE      = 'erp_online';
    const ACTIVE_RECORD = 'Estoque';
    const ATTRIBUTES    = ['armazem_id','id','produto_id','reserva','saldo','system_unit_id','ult_compra','ult_preco','custo'];
    
    public function StoreArray($request)
    {
        $database     = static::DATABASE;
        $activeRecord = static::ACTIVE_RECORD;
       
        $aReturn = array();
        $aItens  = (array)$request['conteudo'];
    
        foreach ($aItens as $aItem) {    
        
            $aGrava = (array)$aItem;    
            
            TTransaction::open($database);
            
            if(isset($aGrava['produto'])){
                $oBusca = Produto::where('cod_erp', '=', ltrim(rtrim($aGrava['produto'])))
                ->first(); 
                
                if($oBusca){
                    $aGrava['produto_id'] = $oBusca->id;
                }
            }
    

            if(isset($aGrava['filial'])){
                $oBusca = Filial::where('cod_erp', '=', $aGrava['filial'])
                ->first(); 
                if($oBusca){
                    $aGrava['filial_id'] = $oBusca->id;
                }
            }
          

            if(isset($aGrava['armazem'])){
                $oBusca = Armazem::where('cod_erp', '=', $aGrava['armazem'])
                ->first(); 
                if($oBusca){
                    $aGrava['armazem_id'] = $oBusca->id;
                }
                
            }    

            if(isset($aGrava['armazem']) and isset($aGrava['produto'])){
                $oBusca = Estoque::where('produto_id', '=', $aGrava['produto_id'])
                        ->where('armazem_id', '=', $aGrava['armazem_id'])
                        ->first(); 
                if($oBusca){
                    $aGrava['id'] = $oBusca->id;
                }
                
            } 

            try 
            {
                $object = new $activeRecord;
                $object->fromArray( (array) $aGrava);
                $object->store();
                $aLog['status']  = 'OK';
                $aLog['id']      = $object->id;
                $aLog['produto'] = $aGrava['produto'];
                //$aReturn [] = $object->toArray();
                $aReturn [] = $aLog;
                unset($aLog);
                unset($object);
                unset($aGrava);
            }
            catch (Exception $e) 
            {
                $aLog['message'] = $e->getMessage(); //new TMessage('error', $e->getMessage());    
                $aReturn ['erro'] = $aLog;
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
