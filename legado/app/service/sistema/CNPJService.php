<?php

class CNPJService
{
    public static function get($cnpj)
    {
        try 
        {
            $dadosCnpj = BuilderCNPJService::get($cnpj);

            if($dadosCnpj->cep)
            {
                $dadosCep = CEPService::get($dadosCnpj->cep);    

                $dadosCnpj->estado_id = $dadosCep->estado_id;
                $dadosCnpj->cidade_id = $dadosCep->cidade_id;
            }
        } 
        
        catch (Exception $e) 
        {
            $apiError = new ApiError();
            $apiError->url = BuilderCNPJService::getUrl($cnpj);
            $apiError->error_message = $e->getMessage();
            $apiError->store();

            return null;
        }

        return $dadosCnpj;
    }
    
    public static function getCNPJ($cCNPJ)
    {
        try 
        {
            if(isset($cCNPJ)){
    	        $cBusca = $cCNPJ;
    	        $cBusca = preg_replace("/[^0-9]/","",$cBusca);
                if(strlen($cBusca)==14) 
    			{
                    $cUrl = "https://www.receitaws.com.br/v1/cnpj/".$cBusca;
                    $dadosCnpj = BuilderHttpClientService::get($cUrl);
                    
                    if($dadosCnpj->cep)
                    {
                        $dadosCep = CEPService::get($dadosCnpj->cep);    
        
                        $dadosCnpj->estado_id = $dadosCep->estado_id;
                        $dadosCnpj->cidade_id = $dadosCep->cidade_id;
                    }
    			}
            }
        } 
        catch (Exception $e) 
        {
            $cBusca = $cCNPJ;
    	    $cBusca = preg_replace("/[^0-9]/","",$cBusca);
            
            $apiError = new ApiError();
            $apiError->url = "https://www.receitaws.com.br/v1/cnpj/".$cBusca;
            $apiError->error_message = $e->getMessage();
            $apiError->store();

            return null;
        }
        
        return $dadosCnpj;
    }
}


/*
class CNPJService extends AdiantiRecordService
{

    public static function getCNPJ($cCNPJ)
    {
        try
        {				        				
	        if( isset($cCNPJ) )
	        {
		        $cBusca = $cCNPJ;
		        $cBusca = preg_replace("/[^0-9]/","",$cBusca);
                if(strlen($cBusca)==14) 
				{
                    $cUrl = "https://www.receitaws.com.br/v1/cnpj/".$cBusca;
				    $retorno = file_get_contents($cUrl,false,stream_context_create(["ssl"=>["verify_peer"=>false,"verify_peer_name"=>false]]));
                    $retorno = SisFunction::NoAcento($retorno);//preg_replace(array("/(á|à|ã|â|ä)/","/(Á|À|Ã|Â|Ä)/","/(é|è|ê|ë)/","/(É|È|Ê|Ë)/","/(í|ì|î|ï)/","/(Í|Ì|Î|Ï)/","/(ó|ò|õ|ô|ö)/","/(Ó|Ò|Õ|Ô|Ö)/","/(ú|ù|û|ü)/","/(Ú|Ù|Û|Ü)/","/(ñ)/","/(Ñ)/"),explode(" ","a A e E i I o O u U n N"),$retorno);                    
                    $retorno = json_decode($retorno);				
                    if($retorno)
                    {
        				if( $retorno->status == "ERROR")
        				{
        					TToast::show("error", $retorno['message'], "topRight", "fas fa-bug");
        					unset($retorno);
        				}
        				
        				return $retorno;
    	            }
				}
			}
		}
		catch (Exception $e)
		{
		    echo 'Aviso: ' . $e->getMessage();
		}
    }
    
    function tirarAcentos($string){
        return preg_replace(array("/(á|à|ã|â|ä)/","/(Á|À|Ã|Â|Ä)/","/(é|è|ê|ë)/","/(É|È|Ê|Ë)/","/(í|ì|î|ï)/","/(Í|Ì|Î|Ï)/","/(ó|ò|õ|ô|ö)/","/(Ó|Ò|Õ|Ô|Ö)/","/(ú|ù|û|ü)/","/(Ú|Ù|Û|Ü)/","/(ñ)/","/(Ñ)/"),explode(" ","a A e E i I o O u U n N"),$string);
    }
}
*/