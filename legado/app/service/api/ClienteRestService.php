<?php

class ClienteRestService extends AdiantiRecordService
{
    const DATABASE      = 'erp_online';
    const ACTIVE_RECORD = 'Cliente';
    const ATTRIBUTES    = ['bairro','carteira','celular','celular2','cep','cliente','cnpj_cpf','cod_erp','complemento','condicao_pagamento_id','contato','contribuinte','data_cadastro','destaca_ie','dt_alteracao','dt_bloqueio','dt_inclusao','dt_reativacao','email','endereco','fantasia','fax','filial_cadastro','filial_id','id','ie','im','latitude','limite','log_int','longitude','motivo_bloqueio','motivo_bloqueio_id','municipio','municipio_id','nascimento','obs','obs_bloqueio','obs_reativacao','primeira_compra','razao','reg_ativo','regiao_cliente_id','rg','risco','seguimento_id','sinc','site','status','system_unit_id','tabela_preco_id','telefone1','telefone2','tipo','tipo_cliente_id','uf','ultima_compra','ultima_visita','ultimo_atendimento','vencimento_limite','vendedor_id','situacao_cadastral_id'];
    
    public function StoreArray($request)
    {
        $database     = static::DATABASE;
        $activeRecord = static::ACTIVE_RECORD;
       
        $aReturn = array();
        $aLog = array();
        $aItens = array();
        $aRequest = (array)$request;
        
        if( isset( $aRequest['conteudo'] ) ){
            $aItens = $aRequest['conteudo'];
            
            foreach ($aItens as $aItem) {
                
                $aGrava = (array)$aItem;    
                TTransaction::open($database);
    
                    
                if(isset($aGrava['cod_erp'])){
                    $oBusca = Cliente::where('cod_erp', '=', $aGrava['cod_erp'])
                    ->first(); 
                    $aGrava['id'] = $oBusca->id;
                }
                
                if(isset($aGrava['cliente'])){
                    $oBusca = TipoCliente::where('cod_erp', '=', $aGrava['cliente'])
                    ->first(); 
                    $aGrava['tipo_cliente_id'] = $oBusca->id;
                }
                
                if(isset($aGrava['tabela_preco'])){
                    $oBusca = TabelaPreco::where('cod_erp', '=', $aGrava['tabela_preco'])
                    ->first(); 
                    $aGrava['tabela_preco_id'] = $oBusca->id;
                }                    
                
                if(isset($aGrava['cond_pgto'])){
                    $oBusca = CondicaoPagamento::where('cod_erp', '=', $aGrava['cond_pgto'])
                    ->first(); 
                    $aGrava['condicao_pagamento_id'] = $oBusca->id;
                }                    
                
                if(isset($aGrava['seguimento'])){
                    $oBusca = Segmento::where('cod_erp', '=', $aGrava['seguimento'])
                    ->first(); 
                    if($oBusca){
                        $aGrava['seguimento_id'] = $oBusca->id;
                    }
                }
                if(isset($aGrava['situacao_cadastral'])){
                    if(empty($aGrava['situacao_cadastral'])){
                        $aGrava['situacao_cadastral'] = "Ativa";
                    }
                }else{
                    $aGrava['situacao_cadastral'] = "Ativa";
                }
                
                if(isset($aGrava['situacao_cadastral']) and !empty($aGrava['situacao_cadastral'])){
                    
                    $oBusca = SituacaoCadastral::where('descricao', '=', $aGrava['situacao_cadastral'])
                    ->first(); 
                    if($oBusca){
                        $aGrava['situacao_cadastral_id'] = $oBusca->id;
                    }else{
                        $oBusca = new SituacaoCadastral();
                        $oBusca->descricao = $aGrava['situacao_cadastral'];
                        $oBusca->store();
                        if($oBusca){
                            $aGrava['situacao_cadastral_id'] = $oBusca->id;
                        }                        
                    }
                }
               
                if(isset($aGrava['vendedor'])){
                    $oBusca = Vendedor::where('cod_erp', '=', $aGrava['vendedor'])
                    ->first(); 
                    if($oBusca){
                        $aGrava['vendedor_id'] = $oBusca->id;
                    }
                }                    
                //
                if(isset($aGrava['regiao'])){
                    $oBusca = RegiaoCliente::where('cod_erp', '=', $aGrava['regiao'])
                    ->first(); 
                    if($oBusca){
                        $aGrava['regiao_cliente_id'] = $oBusca->id;
                    }
                }  
                
                if(isset($aGrava['motivo_bloqueio'])){
                    $oBusca = MotivoBloqueio::where('cod_erp', '=', $aGrava['motivo_bloqueio'])
                    ->first(); 
                    if($oBusca){
                        $aGrava['motivo_bloqueio_id'] = $oBusca->id;
                    }
                } 
                
                if(isset($aGrava['uf'])){
                    $oUF = Estado::where('sigla', '=', strtoupper($aGrava['uf']))
                        ->first();

                    $oMunicipio = Municipio::where('cod_erp', '=', ltrim(rtrim($oUF->codigo_ibge)).ltrim(rtrim($aGrava['cod_ibge'])))
                        ->first();
                        
                    if($oMunicipio){
                        $aGrava['municipio_id'] = $oMunicipio->id;
                    }
                    
                }

                $aGrava['sinc'] = "S";

                if(isset($aGrava['origem'])){
                    $aGrava['log_int'] = $aGrava['origem'];
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
    
    //date_default_timezone_set("UTC");
    public function Sinc($request)
    {    
        $database     = static::DATABASE;
        $activeRecord = static::ACTIVE_RECORD;
       
        $aParam = (array)$request;
        $aRetorno = array();

        TTransaction::open($database);
        
        $oClientes = Cliente::where('sinc', '=', 'N')
                     ->orderBy('id')
                     ->load();
        //print_r($oCliente->toJson());
        
        if($oClientes){
            foreach ($oClientes as $oCliente) {
                $aRetorno['conteudo'][] = $oCliente->id;
            }
            //$cRetorno = $oCliente->toJson(); 
        }

        TTransaction::close();
        
        return $aRetorno;
        
    }
    /*
    public function Cliente($request)
    {    
        $database     = static::DATABASE;
        $activeRecord = static::ACTIVE_RECORD;
       
        $aParam = (array)$request;
        $id =  $aParam['id'];

        TTransaction::open($database);
        
        $oCliente = new Cliente($id);

        if($oCliente){

            //['bairro','celular','celular2','cep','cnpj_cpf','cod_erp','complemento','cond_pgto_id','contato','contribuinte','data_cadastro','data_vendedor','destaca_ie','dt_alteracao','dt_inclusao','email','emal_vendedor','empresa_id','endereco','fantasia','fax','filial_cadastro','filial_id','id','ie','im','midia_id','municipio','nascimento','obs_vendedor','primeira_compra','razao','rg','seguimento_id','sinc','site','status','tabela_preco_id','telefone1','telefone2','tipo','uf','ultima_visita','vendedor_id', 'municipio_id','estado_id'];

            $aRetorno['conteudo']['bairro'] = $oCliente->bairro;
            $aRetorno['conteudo']['celular'] = $oCliente->celular;
            $aRetorno['conteudo']['celular2'] = $oCliente->celular2;
            $aRetorno['conteudo']['cep'] = $oCliente->cep;
            $aRetorno['conteudo']['cnpj_cpf'] = $oCliente->cnpj_cpf;
            $aRetorno['conteudo']['cod_erp'] = $oCliente->cod_erp;
            $aRetorno['conteudo']['complemento'] = $oCliente->complemento;
            $aRetorno['conteudo']['contato'] = $oCliente->contato;
            $aRetorno['conteudo']['cliente'] = $oCliente->cliente;
            $aRetorno['conteudo']['contribuinte'] = $oCliente->contribuinte;
            
            //echo $oCliente->data_cadastro;

            $aRetorno['conteudo']['data_cadastro'] = $oCliente->data_cadastro;
            $aRetorno['conteudo']['data_vendedor'] = $oCliente->data_vendedor;
            $aRetorno['conteudo']['ultima_visita'] = $oCliente->ultima_visita;
            $aRetorno['conteudo']['primeira_compra'] = $oCliente->primeira_compra;
            $aRetorno['conteudo']['nascimento'] = $oCliente->nascimento;


            $aRetorno['conteudo']['destaca_ie'] = $oCliente->destaca_ie;
            //$aRetorno['conteudo']['dt_alteracao'] = $oCliente->dt_alteracao;
            //$aRetorno['conteudo']['dt_inclusao'] = $oCliente->dt_inclusao;
            $aRetorno['conteudo']['email'] = $oCliente->email;
            $aRetorno['conteudo']['emal_vendedor'] = $oCliente->emal_vendedor;
            $aRetorno['conteudo']['endereco'] = $oCliente->endereco;
            
            $aRetorno['conteudo']['razao'] = substr($oCliente->razao,0,39);
            
            if(empty($oCliente->fantasia)){
                $aRetorno['conteudo']['fantasia'] = substr($oCliente->razao,0,39);
            }else{
                $aRetorno['conteudo']['fantasia'] = substr($oCliente->fantasia,0,39);
            }
            $aRetorno['conteudo']['fax'] = $oCliente->fax;

            if(!empty( $oCliente->filial_cadastro)){
                $oFilial = new Filial($oCliente->filial_cadastro);
                $aRetorno['conteudo']['filial_cadastro'] = $oFilial->cod_erp;
            }
            
            $aRetorno['conteudo']['filial'] = $oCliente->filial->cod_erp;
            $aRetorno['conteudo']['id'] = $oCliente->id;
            $aRetorno['conteudo']['ie'] = $oCliente->ie;
            $aRetorno['conteudo']['im'] = $oCliente->im;
            $aRetorno['conteudo']['obs_vendedor'] = $oCliente->obs_vendedor;
            $aRetorno['conteudo']['rg'] = $oCliente->rg;
            $aRetorno['conteudo']['sinc'] = $oCliente->sinc;
            $aRetorno['conteudo']['site'] = $oCliente->site;
            $aRetorno['conteudo']['status'] = $oCliente->status;
            $aRetorno['conteudo']['telefone1'] = $oCliente->telefone1;
            $aRetorno['conteudo']['telefone2'] = $oCliente->telefone2;
            $aRetorno['conteudo']['tipo'] = $oCliente->tipo;
            //$aRetorno['conteudo']['uf'] = $oCliente->uf;
            $aRetorno['conteudo']['estado'] = $oCliente->estado->sigla;
            $aRetorno['conteudo']['municipio'] = $oCliente->municipio->cod_erp;
            if(!empty( $oCliente->cond_pgto_id)){
                $oCond_pgto = new CndicaoPagamento($oCliente->cond_pgto_id);
                $aRetorno['conteudo']['cond_pgto'] = $oCond_pgto->cod_erp;
            }
            
            if(!empty( $oCliente->tabela_preco_id)){
                $oTabelaPreco = new TabelaPreco($oCliente->tabela_preco_id);
                $aRetorno['conteudo']['tabela_preco'] = $oTabelaPreco->cod_erp;
            }
            
            $aRetorno['conteudo']['seguimento'] = $oCliente->seguimento->cod_erp;
            $aRetorno['conteudo']['midia'] = $oCliente->midia->cod_erp;
            //$aRetorno['conteudo']['empresa_id'] = $oCliente->empresa->cod_erp;
            if(!empty( $oCliente->vendedor_id)){
                $oVendedor = new Vendedor($oCliente->vendedor_id);
                $aRetorno['conteudo']['vendedor'] = $oVendedor->cod_erp;
            }
            
            $aRetorno['conteudo']['limite'] = $oCliente->limite;
            $aRetorno['conteudo']['vencimento_limite'] = $oCliente->vencimento_limite;
            $aRetorno['conteudo']['risco'] = $oCliente->risco;
            $aRetorno['conteudo']['obs_fin'] = $oCliente->obs_fin;

        }
        TTransaction::close();
        
        return $aRetorno;
        
    }
    /*

    /*
    $parsed = date_parse_from_format('M d Y H:i:s:B', $object->nascimento); nascimento
    $data = mktime($parsed['hour'], $parsed['minute'], $parsed['second'], $parsed['month'], $parsed['day'], $parsed['year']);
    $object->nascimento=date('Y-m-d', $data);
    */

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
