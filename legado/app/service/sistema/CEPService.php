<?php

class CEPService
{
    public static function get($cep)
    {
        
        $cep = str_replace(['-','.'], ['', ''], $cep);

        $dadosCep = CEPCacheService::get($cep);

        if($dadosCep)
        {
            return $dadosCep;
        }

        try 
        {
            $dadosCep = BuilderCEPService::get($cep);
        } 
        catch (Exception $e) 
        {
            $apiError = new ApiError();
            $apiError->url = BuilderCEPService::getUrl($cep);
            $apiError->error_message = $e->getMessage();
            $apiError->store();

            return null;
        }

        $dadosCep->rua = $dadosCep->tipo_logradouro . ' ' .SisFunction::NoAcento($dadosCep->logradouro);
        $dadosCep->cep = $cep;

        $dadosCep->cidade = SisFunction::NoAcento($dadosCep->cidade);

        $cidade = Municipio::where('codigo_ibge', '=', $dadosCep->cidade_cod_ibge)->first();
        $estado = Estado::where('codigo_ibge', '=', $dadosCep->estado_cod_ibge)->first();

        if ($cidade)
        {
            $dadosCep->cidade_id = $cidade->id;
            $dadosCep->estado_id = $cidade->estado_id;
        }
        else // se não achar a cidade/estado aproveitamos para salvar
        {

            if (!$estado)
            {
                $estado = new Estado;
                $estado->cod_erp = $dadosCep->estado_cod_ibge;
                $estado->sigla = $dadosCep->uf;
                $estado->descricao = SisFunction::NoAcento($dadosCep->estado);//preg_replace(array("/(á|à|ã|â|ä)/","/(Á|À|Ã|Â|Ä)/","/(é|è|ê|ë)/","/(É|È|Ê|Ë)/","/(í|ì|î|ï)/","/(Í|Ì|Î|Ï)/","/(ó|ò|õ|ô|ö)/","/(Ó|Ò|Õ|Ô|Ö)/","/(ú|ù|û|ü)/","/(Ú|Ù|Û|Ü)/","/(ñ)/","/(Ñ)/"),explode(" ","a A e E i I o O u U n N"),$dadosCep->estado);
                $estado->codigo_ibge = $dadosCep->estado_cod_ibge;
                $estado->store();
            }

            $cidade = new Municipio;
            $cidade->cod_erp = $dadosCep->cidade_cod_ibge;
            $cidade->descricao = SisFunction::NoAcento($dadosCep->cidade);
            $cidade->codigo_ibge = $dadosCep->cidade_cod_ibge;
            $cidade->estado_id = $estado->id;
            $cidade->store();

            $dadosCep->cidade_id = $cidade->id;
            $dadosCep->estado_id = $cidade->estado_id;
        }

        CEPCacheService::save($dadosCep);

        return $dadosCep;
    }
    
    public static function getCEP($cep)
    {
        $cep = str_replace(['-','.'], ['', ''], $cep);
        
        if(!empty($cep)){
            $cUrl = "https://brasilapi.com.br/api/cep/v2/".$cep;
            $dado = BuilderHttpClientService::get($cUrl);
            
            //foreach ($dados as $dado)
            //{
            $dadosCep = Cep::where('cep', '=', $dado->cep)->first();

            if($dadosCep){
                
                //if($dadosCep->status){
                //    unset($dadosCep);
                //}
                
            }else{
                            
                $estado = Estado::where('sigla', '=', $dado->state)->first();

                $cidade = Municipio::where('descricao', '=', SisFunction::NoAcento($dado->city))
                    ->where('estado_id', '=', $estado->id)
                    ->first();
    
                if(empty($dado->neighborhood) or empty($dado->street)){
                    
                }else{
                    $dadosCep = new Cep;
                    $dadosCep->cep       = $dado->cep;
                    $dadosCep->estado_id = $estado->id;
                    $dadosCep->cidade_id = $cidade->id;
                    $dadosCep->bairro    = SisFunction::NoAcento($dado->neighborhood);
                    $dadosCep->endereco  = SisFunction::NoAcento($dado->street);
                    $dadosCep->service   = $dado->service;
                    $dadosCep->longitude = $dado->location->coordinates->longitude;
                    $dadosCep->latitude  = $dado->location->coordinates->latitude;
                    $dadosCep->store();
                }

            }
            //}
        }
        return $dadosCep;
        
    }

}