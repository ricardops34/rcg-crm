<?php

class VendaMesProdutoRestService extends AdiantiRecordService
{
    const DATABASE      = 'erp_online';
    const ACTIVE_RECORD = 'VendaMesProduto';
    const ATTRIBUTES    = ['abril','agosto','ano','dezembro','empresa_id','fevereiro','filial_id','id','janeiro','julho','junho','maio','marco','novembro','outubro','produto_id','produto_nome','setembro'];
    
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

                TTransaction::open($database);
                
                if(isset($aGrava['produto'])){
                    $oProduto = Produto::where('cod_erp', '=', $aGrava['produto'])
                        ->first(); 
                        
                    if($oProduto){
                        $aGrava['produto_id'] = $oProduto->id;
                        $id = $oProduto->id;
                        
                        if(isset($aGrava['ano'])){
                            $oBusca = VendaMesProduto::where('produto_id', '=', $id)
                                ->where('ano', '=', $aGrava['ano'])
                                ->first(); 
                            
                            $aGrava['id'] = $oBusca->id;
                            
                        }else{
                            $oNovo = new VendaMesProduto();
                            $oNovo->produto_id = $id;
                            $oNovo->ano = $aGrava['ano'] ;
                            $oNovo->janeiro = 0 ;
                            $oNovo->fevereiro = 0 ;
                            $oNovo->marco = 0 ;
                            $oNovo->abril = 0 ;
                            $oNovo->maio = 0 ;
                            $oNovo->junho = 0 ;
                            $oNovo->julho = 0 ;
                            $oNovo->agosto = 0 ;
                            $oNovo->setembro = 0 ;
                            $oNovo->outubro = 0 ;
                            $oNovo->novembro = 0 ;
                            $oNovo->dezembro = 0 ;
                            $oNovo->produto_nome = $oProduto->descricao;
                            $oNovo->store();
    
                            $aGrava['id'] = $oNovo->id;
                        }
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
