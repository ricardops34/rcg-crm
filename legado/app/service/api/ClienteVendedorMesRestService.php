<?php

class ClienteVendedorMesRestService extends AdiantiRecordService
{
    const DATABASE      = 'erp_online';
    const ACTIVE_RECORD = 'ClienteVendedorMes';
    const ATTRIBUTES    = ['abril','agosto','ano','cliente_id','dezembro','empresa_id','fevereiro','filial_id','id','janeiro','julho','junho','maio','marco','novembro','outubro','setembro','total_avulso','total_carteira','total_rcg'];
    
    
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
        
            foreach ($aItens as $aItem) {
    
                $aGrava = (array)$aItem;    

                if($aGrava['ano'] <> '0' and isset($aGrava['ano'])){
    
                    TTransaction::open($database);
                    if(isset($aGrava['cod_erp'])){
                        $oBusca = Vendedor::where('cod_erp', '=', $aGrava['cod_erp'])
                        ->first(); 
                        $aGrava['vendedor_id'] = $oBusca->id;
                        $id= $oBusca->id;
                        if(isset($aGrava['ano'])){
                            $oBusca = ClienteVendedorMes::where('vendedor_id', '=', $aGrava['vendedor_id'])
                                ->where('ano', '=', $aGrava['ano'])
                                ->first(); 
                            
                            $aGrava['id'] = $oBusca->id;
                            
                        }else{
                            $oNovo = new ClienteVendedorMes();
                            $oNovo->vendedor_id = $id;
                            $oNovo->ano = $aGrava['ano'] ;
                            $oNovo->store();
    
                            $aGrava['id'] = $oNovo->id;
                        }
                        
                    
                        try 
                        {
                            $object = new $activeRecord;
                            $object->fromArray( (array) $aGrava);
                            $object->store();
        
                            $aLog['status']  = 'OK';
                            $aLog['id']      = $object->id;
                            $aLog['cod_erp'] = $aGrava['cod_erp'];
                            $aLog['ano'] = $aGrava['ano'];
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
                    }else{
                        $aLog['status']  = 'error';
                        $aLog['message'] = "cod_erp"; //new TMessage('error', $e->getMessage());  
                        $aReturn [] = $aLog;
                    }
                    
                    TTransaction::close();
                }
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
