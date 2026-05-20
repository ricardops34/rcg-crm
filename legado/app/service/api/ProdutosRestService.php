<?php

class ProdutosRestService extends AdiantiRecordService
{
    const DATABASE      = 'erp_online';
    const ACTIVE_RECORD = 'Produto';
    const ATTRIBUTES    = ['armazem_id','categoria_id','cod_erp','codigo_barras','codigo_fabricante','dados_tecnicos','descricao','dt_alteracao','dt_inclusao','dt_ult_compra','estoque_seguranca','fabricante_id','filial_id','foto','id','informacoes_tecnicas','marca','ncm','observacao','origem','peso','peso_bruto','ponto_pedido','prc_ult_preco','qtd_embalagem','sinc','status','sub_categoria_id','te_id','tipo','ts_id','um'];
    
    public function StoreArray($request)
    {
        $database     = static::DATABASE;
        $activeRecord = static::ACTIVE_RECORD;
       
        $aReturn = array();
        $aLog = array();
        $aItens = array();
        
        //var_dump($request);
            
        //die;
        
        $aRequest = (array)$request;

        if( isset( $aRequest['conteudo'] ) ){
            
            $aItens = $aRequest['conteudo'];
            
            foreach ($aItens as $aItem) { 
                
                $aGrava = (array)$aItem;  

                TTransaction::open($database);

                /*if(isset($aGrava['filial'])){
                    $oBusca = Filial::where('cod_erp', '=', $aGrava['filial'])
                    ->first(); 
                    if($oBusca){
                        $aGrava['filial_id'] = $oBusca->id;
    
                        if(isset($aGrava['cod_erp'])){
                            $oBusca = Produto::where('cod_erp', '=', ltrim(rtrim($aGrava['cod_erp'])))
                                            ->where('filial_id', '=', $aGrava['filial_id'])
                            ->first(); 
                        
                            if($oBusca){
                                $aGrava['id'] = $oBusca->id;
                            }
                        }
                    }
    
                }else{*/

                    if(isset($aGrava['cod_erp'])){
                        $oBusca = Produto::where('cod_erp', '=', ltrim(rtrim($aGrava['cod_erp'])))
                        ->first(); 
                        
                        if($oBusca){
                            $aGrava['id'] = $oBusca->id;
                        }
                    }
                //}
              
                if(isset($aGrava['categoria'])){
                    $oBusca = Categoria::where('cod_erp', '=', $aGrava['categoria'])
                    ->first(); 
                    if($oBusca){
                        $aGrava['categoria_id'] = $oBusca->id;
                    }
                }    
    
                if(isset($aGrava['subcategoria'])){
                    $oBusca = SubCategoria::where('cod_erp', '=', $aGrava['subcategoria'])
                    ->first(); 
                    if($oBusca){
                        $aGrava['sub_categoria_id'] = $oBusca->id;
                    }
                }    
    
                if(isset($aGrava['armazem'])){
                    $oBusca = Armazem::where('cod_erp', '=', $aGrava['armazem'])
                    ->first(); 
                    if($oBusca){
                        $aGrava['armazem_id'] = $oBusca->id;
                    }
                    
                }    
    
                if(isset($aGrava['fabricante'])){
                    $oBusca = Fabricante::where('cod_erp', '=', $aGrava['fabricante'])
                    ->first(); 
                    if($oBusca){
                        $aGrava['fabricante_id'] = $oBusca->id;
                    }
                }    
    
                $aGrava['sinc'] = "S";
                
                try 
                {
                    $object = new $activeRecord;
                    $object->fromArray( (array) $aGrava);
                    $object->store();
                    $aLog['status']  = 'OK';
                    $aLog['id']      = $object->id;
                    $aLog['cod_erp'] = $aGrava['cod_erp'];
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
            
        }

        if(count($aReturn) > 0){
            
        }else{
            $aReturn ['erro'] = 'sem dados';
            $aReturn ['conteudo'] = $request;
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
