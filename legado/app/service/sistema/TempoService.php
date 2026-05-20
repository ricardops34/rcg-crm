<?php

class TempoService
{
    public function __construct($param)
    {
        
    }
    
    public static function getMes()
    {
        $aMeses = array();
        //$param['mes_base'] ?? date('m')
        //$resultado = $nota1 > $nota2 ? true : false;

        $aMeses['01'] = 'Janeiro';
        $aMeses['02'] = 'Fevereiro';
        $aMeses['03'] = 'Março';
        $aMeses['04'] = 'Abril';
        $aMeses['05'] = 'Maio';
        $aMeses['06'] = 'Junho';
        $aMeses['07'] = 'Julho';
        $aMeses['08'] = 'Agosto';
        $aMeses['09'] = 'Setembro';
        $aMeses['10'] = 'Outubro';
        $aMeses['11'] = 'Novembro';
        $aMeses['12'] = 'Dezembro';

        $aMeses[date('m')] = $aMeses[date('m')];
        
        return $aMeses;
    }
    
    public static function getMeses()
    {
        $aMeses = array();
        //$param['mes_base'] ?? date('m')
        //$resultado = $nota1 > $nota2 ? true : false;

        $aMeses['01'] = 'Janeiro';
        $aMeses['02'] = 'Fevereiro';
        $aMeses['03'] = 'Março';
        $aMeses['04'] = 'Abril';
        $aMeses['05'] = 'Maio';
        $aMeses['06'] = 'Junho';
        $aMeses['07'] = 'Julho';
        $aMeses['08'] = 'Agosto';
        $aMeses['09'] = 'Setembro';
        $aMeses['10'] = 'Outubro';
        $aMeses['11'] = 'Novembro';
        $aMeses['12'] = 'Dezembro';

        $aMeses[date('m')] = '<b>'.$aMeses[date('m')].'<b>';
        
        return $aMeses;
    }

    public static function getAnos()
    {
        
        /*
        TTransaction::open('erp_online');

        //$oMeses = new TRepository('VendaMesCliente');
        
        $Anoini = NotaSaida::minBy('ano');
        
        TTransaction::close();
        */

        $aAnos = array();
        //$Anoini = 2010;
        $AnoAtual = date("Y");
        $Anoini = $AnoAtual - 5;
        
        for ($AnoAtual ; $AnoAtual <= $Anoini; $AnoAtual--) {

           $aAnos[$AnoAtual] = $AnoAtual;

        }
        
        //$aAnos[date('Y')] = '<b>'.$aAnos[date('Y')].'<b>';
        
        //rsort($aAnos);
        
        var_dump($aAnos);
        
        return $aAnos;          
    }
    
    public static function getAno()
    {
        
        $aAnos = array();
        //$Anoini = 2023;
        $AnoAtual = date("Y")+1;
        $Anoini = $AnoAtual - 3;
        
        for ($Anoini ; $Anoini <= $AnoAtual; $Anoini++) {

           $aAnos[$Anoini] = $Anoini;

        }
        
        $aAnos[date('Y')] = $aAnos[date('Y')];

        return $aAnos;          
    }
}
