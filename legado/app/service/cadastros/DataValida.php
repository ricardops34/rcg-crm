<?php

class DataValida
{
    public function __construct($param)
    {
        
    }
    
    public static function Data($data)
    {
        $retorno = '';
        $dia = date("d", $data);//$param['dia'];
        $mes = date("m", $data);
        $ano = date("Y", $data);
        
        TTransaction::open('portal_erp');

        $oFeriados = Municipio::where('dia',  '=', $dia)
             ->where('mes', '=',  $mes)
             ->where('ano', '=',  $ano)
             ->first(); 

        if($oFeriados){
            //$retorno
        }else{

            $oFeriados = Municipio::where('dia',  '=', $dia)
                 ->where('mes', '=',  $mes)
                  ->first(); 
            //$retorno
        }
        

        TTransaction::close();
        return $retorno;
    }
}
