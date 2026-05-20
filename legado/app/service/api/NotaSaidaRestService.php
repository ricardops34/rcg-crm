<?php

class NotaSaidaRestService extends AdiantiRecordService
{
    const DATABASE      = 'erp_online';
    const ACTIVE_RECORD = 'NotaSaida';
    const ATTRIBUTES    = ['ano','base_icms','base_ipi','cancelamento_danfe','chave_nfe','cliente_id','condicao_id','dt_alteracao','dt_emissao','dt_inclusao','dt_nfe','especie_fiscal','filial_id','hr_nfe','id','intermediador','mensagem_nf','mes','nota_fiscal','numero_origem','numero_titulo','prefixo_titulo','reg_ativo','serie_fiscal','serie_origem','tipo','tp_frete','transportadora','vendedor1_id','vendedor2_id','vlr_bruto','vlr_comodato','vlr_desconto','vlr_devolucao','vlr_frete','vlr_icms','vlr_ipi','vlr_itens','vlr_mercadoria','fornecedor_id'];
    
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

                //$aItem['comodato'] = 'N'; 
                
                if(isset($aItem['nota_fiscal'])){
                    $oBusca = NotaSaida::where('nota_fiscal', '=', $aItem['nota_fiscal'])
                    ->where('serie_fiscal', '=', $aItem['serie_fiscal'])
                    ->first(); 
                    $aGrava['id'] = $oBusca->id;
                }

                if(isset($aItem['filial'])){
                    $oBusca = Filial::where('cod_erp', '=', $aItem['filial'])
                    ->first(); 
                    $aGrava['filial_id'] = $oBusca->id;
                }
                
                if(isset($aItem['cliente'])){
                    $oBusca = Cliente::where('cod_erp', '=', $aItem['cliente'])
                    ->first(); 
                    $aGrava['cliente_id'] = $oBusca->id;
                }

                if(isset($aItem['fornecedor'])){
                    $oBusca = Fornecedor::where('cod_erp', '=', $aItem['fornecedor'])
                    ->first(); 
                    $aGrava['fornecedor_id'] = $oBusca->id;
                }
            

                if(isset($aItem['vendedor1'])){
                    $oBusca = Vendedor::where('cod_erp', '=', $aItem['vendedor1'])
                    ->first(); 
                    $aGrava['vendedor1_id'] = $oBusca->id;
                }
                
                if(isset($aItem['vendedor2'])){
                    $oBusca = Vendedor::where('cod_erp', '=', $aItem['vendedor2'])
                    ->first(); 
                    $aGrava['vendedor2_id'] = $oBusca->id;
                }
                
                /*
                if(isset($aItem['transportadora'])){
                    $oBusca = Transportadora::where('cod_erp', '=', $aItem['transportadora'])
                    ->first(); 
                    $aGrava['transportadora_id'] = $oBusca->id;
                }
                */
                /*if(isset($aItem['vlr_comodato'])){
                    if($aItem['vlr_comodato'] > 0 and ($aItem['vlr_comodato'] - $aItem['vlr_devolucao']) > 0 ){
                        $aItem['comodato'] = 'S';
                    }
                }
                */
                if(isset($aItem['condicao'])){
                    $oBusca = CondicaoPagamento::where('cod_erp', '=', $aItem['condicao'])
                    ->first(); 
                    $aGrava['condicao_id'] = $oBusca->id;
                }                 

                try 
                {
                    $object = new $activeRecord;
                    $object->fromArray( (array) $aGrava);
                    $object->store();

                    $aLog['status']  = 'OK';
                    $aLog['id']      = $object->id;
                    $aLog['nota'] = $aItem['nota_fiscal'];
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
