<?php

class VendaMesClienteRestService extends AdiantiRecordService
{
    const DATABASE      = 'erp_online';
    const ACTIVE_RECORD = 'VendaMesCliente';
    const ATTRIBUTES    = ['abril','agosto','ano','cliente_id','dezembro','empresa_id','fevereiro','filial_id','id','janeiro','julho','junho','maio','marco','novembro','outubro','setembro'];
    
    
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
                
                if(isset($aGrava['cod_erp'])){
                    $oBusca = Cliente::where('cod_erp', '=', $aGrava['cod_erp'])
                    ->first(); 
                    
                    $aGrava['cliente_id'] = $oBusca->id;
                    $id = $oBusca->id;
                    
                    if(isset($aGrava['ano'])){
                        $oBusca = VendaMesCliente::where('cliente_id', '=', $aGrava['cliente_id'])
                            ->where('ano', '=', $aGrava['ano'])
                            ->first(); 
                        
                        $aGrava['id'] = $oBusca->id;
                        
                    }else{
                        $oNovo = new VendaMesCliente();
                        $oNovo->cliente_id = $id;
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
                        $oNovo->store();

                        $aGrava['id'] = $oNovo->id;
                    }
                    
                }
                
                /*                    
                if(isset($aGrava['cod_erp'])){
                    $oBusca = Cliente::where('cod_erp', '=', $aGrava['cod_erp'])
                    ->first(); 
                    $aGrava['cliente_id'] = $oBusca->id;
                    
                    if(isset($aGrava['ano']) and isset($aGrava['mes']) ){
                        $oBusca = VendaMesCliente::where('cliente_id', '=', $aGrava['cliente_id'])
                            ->where('ano', '=', $aGrava['ano'])
                            ->where('mes', '=', $aGrava['mes'])
                            ->first(); 
                        
                        $aGrava['id'] = $oBusca->id;
                    
                        
                    }else{
                        
                        $ano_fin = date("Y");  
    
                        for ($m = 1; $m <= 12 ; $m++) {
                    
                            $mes = str_pad($m, 2, 0, STR_PAD_LEFT);//str_pad($m, 2, STR_PAD_LEFT);
 
                            $oNovo = new VendaMesCliente();
                            $oNovo->cliente_id = $id;
                            $oNovo->ano = $ano_fin ;
                            $oNovo->mes = $mes;  
                            $oNovo->vlr_bruto = 0 ;
                            $oNovo->vlr_mercadoria = 0 ;
                            $oNovo->store();
                            
                        }
                        $oBusca = VendaMesCliente::where('cliente_id', '=', $aGrava['cliente_id'])
                            ->where('ano', '=', $aGrava['ano'])
                            ->where('mes', '=', $aGrava['mes'])
                            ->first(); 
                        $aGrava['id'] = $oBusca->id;

                    }
                }
                */
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
    
    public function GerarMes($request)
    {
                
        $database     = static::DATABASE;
        $activeRecord = static::ACTIVE_RECORD;
        $return = [];

        TTransaction::open($database);        
        $maxId = Cliente::maxBy('id');
        $minId = Cliente::minBy('id');
        TTransaction::close();

        for ($id = $minId ; $c <= $maxId ; $c++) {
    
            TTransaction::open($database);        
            $oClientes = Cliente::where('id',  '>=', $id)
                         ->orderBy('id')
                         ->take(100)
                         ->load();
            TTransaction::close();

            foreach ($oClientes as $oCliente) {
    
                $id = $oCliente->id;
                $data = new DateTime($oCliente->data_cadastro);
                $ano_ini = $data->format('Y');
                $ano_fim = date("Y");            
                $return[$id]['ano_ini'] = $ano_ini;
                $return[$id]['ano_fim'] = $ano_fim;
                
                TTransaction::open($database);        
                for ($y = $ano_ini; $y <= $ano_fim ; $y++) {
    
                    $oBusca = VendaMesCliente::where('cliente_id', '=', $id)
                                ->where('ano', '=', $y)
                                ->first();
                    if($oBusca){
                        
                    }else{
                        $oCliente = Cliente::where('id', '=', $id)
                            ->first();
    
                        $oNovo = new VendaMesCliente();
                        $oNovo->cliente_id = $id;
                        $oNovo->ano = $y ;
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
        
                        $cNome = ltrim(rtrim(ltrim($oCliente->fantasia)));
                        if($oCliente->tipo == 'F'){
                            if(!empty($oCliente->razao)){
                                $cNome = rtrim(ltrim($oCliente->razao));
                            }
                        }        
                        $oNovo->cliente_nome = $cNome;
                        $oNovo->store();
                    }
                }
                TTransaction::close();
            }
        }
        return $return; 
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
