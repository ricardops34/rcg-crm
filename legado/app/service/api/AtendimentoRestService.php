<?php

class AtendimentoRestService extends AdiantiRecordService
{
    const DATABASE      = 'erp_online';
    const ACTIVE_RECORD = 'Atendimento';
    const ATTRIBUTES    = ['atendimento_tipo_id','cliente_id','codigo_cliente','cor','dt_alteracao','dt_delete','dt_inclusao','email','horario_final','horario_inicial','id','nome','observacao','retorno','status','telefone','titulo','user_id_create','user_id_delete','user_id_update','vendedor_id','nota_saida_id','orcamento_id'];
    
    public function StoreArray($request)
    {
        $database     = static::DATABASE;
        $activeRecord = static::ACTIVE_RECORD;
        $apagar_agenda = false;
        $aReturn = array();
        $aItens  = (array)$request['conteudo'];
    
        foreach ($aItens as $aItem) {   
            $aGrava = (array)$aItem;    
            TTransaction::open($database);

            if(isset($aGrava['cliente'])){
                $oBusca = Cliente::where('cod_erp', '=', $aGrava['cliente'])
                ->first(); 
                
                if($oBusca){
                    $aGrava['cliente_id'] = $oBusca->id;
                    $aGrava['codigo_cliente'] = $oBusca->cod_erp;
                    $aGrava['nome'] = $oBusca->razao;
                    $aGrava['telefone'] = $oBusca->telefone1;
                    $aGrava['email'] = $oBusca->email;
                
                    if(isset($aGrava['vendedor'])){
                        $oBusca = Vendedor::where('cod_erp', '=', $aGrava['vendedor'])
                        ->first(); 
                
                        if($oBusca){
                            $aGrava['vendedor_id'] = $oBusca->id;

                            if(isset($aGrava['atendimento_tipo'])){
                                $oBusca = AtendimentoTipo::where('cod_erp', '=', $aGrava['atendimento_tipo'])
                                ->first(); 
                                if($oBusca){
                                    $aGrava['atendimento_tipo_id'] = $oBusca->id;
                                    $aGrava['cor'] = $oBusca->AtendimentoTipo->cor;

                                    if(isset($aItem['nota_fiscal'])){
                                        $oBusca = NotaSaida::where('nota_fiscal', '=', $aItem['nota_fiscal'])
                                        ->where('serie_fiscal', '=', $aItem['serie_fiscal'])
                                        ->first(); 
                                        $aGrava['nota_saida_id'] = $oBusca->id;
                                    }
                
                                    $oBusca = Atendimento::where('cliente_id', '=', $aGrava['cliente_id'])
                                        ->where('vendedor_id', '=', $aGrava['vendedor_id'])
                                        ->where('atendimento_tipo_id', '=', $aGrava['atendimento_tipo_id'])
                                        ->where('horario_inicial', '=', $aGrava['horario_inicial'])
                                        ->first(); 
                                    
                                    if($oBusca){
                                        $aGrava['id'] = $oBusca->id;
                                        if($aItem['reg_ativo']=='N'){
                                            $apagar_agenda = true;
                                        }
                                        
                                        
                                    }else{
                                        
                                        if ($aGrava['atendimento_tipo_id'] == AtendimentoTipo::VENDA){
                                            $aGrava['observacao'] = 'Venda ERP.';
                                            if(isset($aGrava['usuario'])){
                                               $aGrava['observacao'] .= ' Nota Fiscal Incluida por:'.$aGrava['usuario'];
                                            }
                                        }
                                        
                                        if ($aGrava['atendimento_tipo_id'] == AtendimentoTipo::COBRANCA){
                                            $aGrava['observacao'] = '2 Via de boleto.';
                                            if(isset($aGrava['usuario'])){
                                               $aGrava['observacao'] .= ' Gerado por :'.$aGrava['usuario'];
                                            }
                                        }                                        
                                        
                                        if ($aGrava['atendimento_tipo_id'] == AtendimentoTipo::CADASTRO){
                                            $aGrava['observacao'] = 'Atualização de Cadastro.';
                                            if(isset($aGrava['usuario'])){
                                               $aGrava['observacao'] .= ' Gerado por :'.$aGrava['usuario'];
                                            }
                                        }                                          
                                    }
                        
                                    if( isset($aGrava['horario_inicial']) and isset($aGrava['horario_final'])){
                                        $horario_final = new DateTime($aGrava['horario_inicial'] );
                                        $horario_final->add(new DateInterval('PT30M'));
                                        
                                        if ($aGrava['horario_final'] <> $horario_final->format('Y-m-d H:i:s')){

                                            $aGrava['horario_final'] = $horario_final->format('Y-m-d H:i:s');
                                        }
                                    }
                                    
                                    if(isset($aGrava['status'])){
                                        
                                    }else{
                                        $aGrava['status'] = "A";
                                    }
                                    try 
                                    {
                                        if ($apagar_agenda ){
                                            
                                            if(isset($aGrava['id'])){
                                                $objeto = Atendimento::find( $aGrava['id'] );
                                                if($objeto)
                                                {
                                                    $objeto->delete();    
                                                }
                                            }
                                        
                                        }else{
                                            $object = new $activeRecord;
                                            $object->fromArray( (array) $aGrava);
                                            $object->store();
                        
                                            $aLog['status']  = 'OK';
                                            $aLog['id']      = $object->id;
                                            $aLog['cod_erp'] = $object->cliente->cod_erp;
                                            $aLog['razap'] = $object->cliente->razao;
                                            $aLog['atendimento'] = $object->AtendimentoTipo->descricao;
                                            $aReturn [] = $aLog;
                                        }
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
                                }
                            }
                        }
                    }
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
