<?php

class VendedorClienteRestService extends AdiantiRecordService
{
    const DATABASE      = 'erp_online';
    const ACTIVE_RECORD = 'VendedorCliente';
    const ATTRIBUTES    = ['cliente_id','id','status','vendedor_id'];
    
        public function StoreArray($request)
    {
        $database     = static::DATABASE;
        $activeRecord = static::ACTIVE_RECORD;
       
        //echo 'ClienteRestService->StoreArray';
        //var_dump((array)$request['conteudo']);
        
        //var_dump($request);
            
        //die;        
        
        $aReturn = array();
        $aLog = array();
        $aItens = array();
        $aRequest = (array)$request;
        
        //var_dump($request);
            
        //die;
        
        if( isset( $aRequest['conteudo'] ) ){
            $aItens = $aRequest['conteudo'];
            
            //var_dump($request);
            
            foreach ($aItens as $aItem) {
                
                $aGrava = (array)$aItem;    
                TTransaction::open($database);
    
                    
                if(isset($aGrava['cliente'])){
                    $oBusca = Cliente::where('cod_erp', '=', $aGrava['cliente'])
                    ->first(); 
                    $aGrava['cliente_id'] = $oBusca->id;
                }
                
                if(isset($aGrava['vendedor'])){
                    $oBusca = Vendedor::where('cod_erp', '=', $aGrava['vendedor'])
                    ->first(); 
                    $aGrava['vendedor_id'] = $oBusca->id;
                }
                /*
                if(isset($aGrava['uf']) and isset($aGrava['municipio'])){
                    
                    $oUF = Estado::where('sigla', '=', strtoupper($aGrava['uf']))
                    ->first(); 
                
                    $oMunicipio = Municipio::where('descricao', '=', strtoupper(ltrim(rtrim($aGrava['municipio']))))
                    ->where('estado_id', '=', $oUF->estado_id)
                    ->first();
                    
                    if($oMunicipio){    
                        $aGrava['municipio_id'] = $oBusca->id;
                    }

                    //return $aGrava['uf'].''.$aGrava['municipio'];
                    //die ;
                } 
                */

                try 
                {
                    $object = new $activeRecord;
                    $object->fromArray( (array) $aGrava);
                    $object->store();

                    $aLog['status']  = 'OK';
                    $aLog['id']      = $object->id;
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
