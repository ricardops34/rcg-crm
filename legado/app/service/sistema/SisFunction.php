<?php

class SisFunction
{

    private static $database = 'erp_online';
    public static function NoAcento($texto)
    {
        $str = $texto;
        $str = preg_replace('/[áàãâä]/ui', 'a', $str);
        $str = preg_replace('/[ÁÀÃÂÄ]/ui', 'A', $str);
        $str = preg_replace('/[éèêë]/ui', 'e', $str);
        $str = preg_replace('/[ÉÈÊË]/ui', 'E', $str);
        $str = preg_replace('/[íìîï]/ui', 'i', $str);
        $str = preg_replace('/[ÍÌÎÏ]/ui', 'I', $str);
        $str = preg_replace('/[óòõôö]/ui', 'o', $str);
        $str = preg_replace('/[ÓÒÕÔÖ]/ui', 'O', $str);
        $str = preg_replace('/[úùûü]/ui', 'u', $str);
        $str = preg_replace('/[ÚÙÛÜ]/ui', 'U', $str);
        $str = preg_replace('/[ç]/ui', 'c', $str);
        $str = preg_replace('/[Ç]/ui', 'C', $str);
        $str = preg_replace('/[ñ]/ui', 'n', $str);
        $str = preg_replace('/[Ñ]/ui', 'N', $str);
        $str = strtoupper($str);
        //$str = preg_replace('/[,(),;:|!"#$%&/=?~^><ªº-]/', '_', $str);
        //$str = preg_replace('/[^a-z0-9]/i', '_', $str);
        //$str = preg_replace('/_+/', '_', $str); // ideia do Bacco :)
        return $str;
        
    }

    public static function VendedorId()//SisFunction::VendedorId()
    {
        
        $cUsuario = TSession::getValue('userid');

        TTransaction::open(self::$database);
        $oVendedor = Vendedor::where('system_users_id', '=', $cUsuario)->first();
        TTransaction::close();
        
        if($oVendedor){ 
            return $oVendedor->id;
        }else{
            return null;
        }
    }

    public static function GetParm($cVar,$cConteudo,$cFil)
    {
        try 
        {
            $ret = null; 
            $Fil = isset($cFil) ? $cFil : null ;
            $Mens = "";
            if(isset($cVar)){
                TTransaction::open(self::$database);

                if($Fil){
                    $oParam = Parametro::where('parametro', '=', $cVar)
                        ->where('filial_id' , '=', $Fil)
                        ->first();
                }else{
                    $oParam = Parametro::where('parametro', '=', $cVar)
                        ->first();
                }

                TTransaction::close();    
                
                if($oParam){
                    $ret = $oParam->conteudo;
                }else{
                    if(isset($cConteudo)){
                        $ret = $cConteudo;
                    } 
                }  
            }else{
                
                $Mens = "A Função 'GetParm' tem com paramento obrigatorio a 'variavel' e 'conteudo padrão'. Ex: 'SisFunction::GetParm(variavel,conteudo)'  ";
                throw new Exception($mensagem); //SisFunction::GetParm('sis_update',' ') ?? null
            }
            
            return $ret;
        }
        catch (Exception $e) 
        {
            new TMessage('error', $e->getMessage());    
        }
    }
    
    public static function CarregaUF()//MUNICIPIOService::getESTADO()
    {
        try 
        {
            TTransaction::open(self::$database);

            $repository = new TRepository('Estado'); 
            $count = $repository->count();
            if($count == 0){

                //MUNICIPIOService::get('MS');
                $cUrl = "https://brasilapi.com.br/api/ibge/uf/v1";
                $dados = BuilderHttpClientService::get($cUrl);

                foreach ($dados as $dado)
                {
                    $estado = Estado::where('sigla', '=', $dado->sigla)->first();

                    if(!$estado){
                        $estado = new Estado;
                        $estado->cod_erp = strval($dado->id);
                        $estado->sigla = $dado->sigla;
                        $estado->descricao = SisFunction::NoAcento($dado->nome);//preg_replace(array("/(á|à|ã|â|ä)/","/(Á|À|Ã|Â|Ä)/","/(é|è|ê|ë)/","/(É|È|Ê|Ë)/","/(í|ì|î|ï)/","/(Í|Ì|Î|Ï)/","/(ó|ò|õ|ô|ö)/","/(Ó|Ò|Õ|Ô|Ö)/","/(ú|ù|û|ü)/","/(Ú|Ù|Û|Ü)/","/(ñ)/","/(Ñ)/"),explode(" ","a A e E i I o O u U n N"),$dadosCep->estado);
                        $estado->codigo_ibge = strval($dado->id);
                        $estado->store();
                    }
                }
            }
            TTransaction::close();
        } 
        catch (Exception $e) 
        {

            $apiError = new ApiError();
            $apiError->url = "https://brasilapi.com.br/api/ibge/uf/v1";
            $apiError->error_message = $e->getMessage();
            $apiError->store();

            return null;
        }
        
        return true;
    }
    
    public static function TotalVendas($vendedor,$cliente,$ano,$mes,$dia,$tipo)//MUNICIPIOService::getESTADO()
    {
        try 
        {
            $nReturn = 0;
            $vendedor_id = isset($vendedor) ? $vendedor : null ;
            $cliente_id = isset($cliente) ? $cliente : null ;
            $cAno = isset($ano) ? $ano : date("Y") ;
            $cMes = isset($mes) ? $mes : date("m") ;
            $cDia = isset($dia) ? $dia : date("d") ;
            $cTipo = isset($tipo) ? $tipo : 'M' ;
            $supervisor = TSession::getValue("supervisor");
            
            if($supervisor){
                
            }else{
                $vendedor_id = TSession::getValue("vendedor_id");
            }
            
            if(isset($vendedor_id) and isset($cliente_id) ){
                
                //TTransaction::open(self::$database);
                if($tipo == 'M'){

                    /*$nReturn = ViewBaseVenda::where('ano', '=', $cAno)
                    ->where('mes', '=', $cMes)
                    ->where('vendedor_id', '=', $vendedor_id)
                    ->where('cliente_id', '=', $cliente_id)
                    ->groupBy('vendedor_id')
                    ->groupBy('cliente_id')
                    ->sumBy('vlr_total');
                    */
                    /*
                    $criteria = new TCriteria; 
                    $criteria->add(new TFilter('ano', '=', $cAno)); 
                    $criteria->add(new TFilter('mes', '=', $cMes)); 
                    $criteria->add(new TFilter('vendedor_id', '=', $vendedor_id)); 
                    $criteria->add(new TFilter('cliente_id', '=', $cliente_id)); 
                    
                    // load using repository
                    $repository = new TRepository('ViewBaseVenda'); 
                    $vendas = $repository->load($criteria); 
                    
                    foreach ($vendas as $venda) 
                    { 
                        $nReturn += $venda->vlr_total; 
                    }
                    */
                    
                }elseif($tipo == 'D'){
                    
                }
                
                //TTransaction::close();
                
            }else{
                $Mens = "A Função 'TotalVendas' tem com paramentos obrigatórios a 'vendedor' e 'cliente'. Ex: 'SisFunction::TotalVendas(vendedor,cliente,ano,mes,dia,tipo)'  ";
                throw new Exception($mensagem); //SisFunction::GetParm('sis_update',' ') ?? null
            }
            return $nReturn;
        }
        catch (Exception $e) 
        {
            new TMessage('error', $e->getMessage());    
        }            

    }
    
    public static function CarregaMunicipio()//MUNICIPIOService::getESTADO()
    {
        try 
        {
            TTransaction::open(self::$database);
            $repository = new TRepository('Estado'); 
            $count = $repository->count();
            if($count == 0){
                
                self::CarregaUF();
                
            }else{    
                $cUrl = "https://brasilapi.com.br/api/ibge/uf/v1";
                $dados = BuilderHttpClientService::get($cUrl);

                foreach ($dados as $dado)
                {
                    $estado = Estado::where('sigla', '=', $dado->sigla)->first();

                    if(!$estado){
                        $estado = new Estado;
                        $estado->cod_erp = strval($dado->id);
                        $estado->sigla = $dado->sigla;
                        $estado->descricao = SisFunction::NoAcento($dado->nome);//preg_replace(array("/(á|à|ã|â|ä)/","/(Á|À|Ã|Â|Ä)/","/(é|è|ê|ë)/","/(É|È|Ê|Ë)/","/(í|ì|î|ï)/","/(Í|Ì|Î|Ï)/","/(ó|ò|õ|ô|ö)/","/(Ó|Ò|Õ|Ô|Ö)/","/(ú|ù|û|ü)/","/(Ú|Ù|Û|Ü)/","/(ñ)/","/(Ñ)/"),explode(" ","a A e E i I o O u U n N"),$dadosCep->estado);
                        $estado->codigo_ibge = strval($dado->id);
                        $estado->store();
                    }
                }
            }
            
            $repository = new TRepository('Estado'); 
            $Estados = $repository->load(); 
            
            foreach ($Estados as $Estado) 
            { 
                
                $cUrl = "https://brasilapi.com.br/api/ibge/municipios/v1/".$Estado->sigla;
                $dados = BuilderHttpClientService::get($cUrl);

                foreach ($dados as $dado)
                {
                    $cidade = Municipio::where('codigo_ibge', '=', $dado->codigo_ibge)->first();

                    if(!$cidade){
                        $cidade = new Municipio;
                        $cidade->cod_erp = $dado->codigo_ibge;
                        $cidade->descricao = SisFunction::NoAcento($dado->nome);
                        $cidade->codigo_ibge = $dado->codigo_ibge;
                        $cidade->estado_id = $Estado->id;
                        $cidade->store();
                    }
                }
            }
            TTransaction::close();
        } 
        catch (Exception $e) 
        {

            $apiError = new ApiError();
            $apiError->url = "https://brasilapi.com.br/api/ibge/municipios/v1/";
            $apiError->error_message = $e->getMessage();
            $apiError->store();

            return null;
        }
        
        return true;
    }
        
    public static function calcularPascoa($ano) {
        // Algoritmo para calcular a data da Páscoa
        $a = $ano % 19;
        $b = intval($ano / 100);
        $c = $ano % 100;
        $d = intval($b / 4);
        $e = $b % 4;
        $f = intval(($b + 8) / 25);
        $g = intval(($b - $f + 1) / 3);
        $h = (19 * $a + $b - $d - $g + 15) % 30;
        $i = intval($c / 4);
        $k = $c % 4;
        $l = (32 + 2 * $e + 2 * $i - $h - $k) % 7;
        $m = intval(($a + 11 * $h + 22 * $l) / 451);
        $mes = intval(($h + $l - 7 * $m + 114) / 31);
        $dia = (($h + $l - 7 * $m + 114) % 31) + 1;
        
        return mktime(0, 0, 0, $mes, $dia, $ano);
    }
    
    public static function getFeriados($ano) {
        $feriados = [];
        
        // Feriados fixos
        $feriadosFixos = [
            '01-01', // Ano Novo
            '04-21', // Tiradentes
            '05-01', // Dia do Trabalhador
            '09-07', // Independência
            '10-12', // Nossa Senhora Aparecida
            '11-02', // Finados
            '11-15', // Proclamação da República
            '12-25'  // Natal
        ];
        
        foreach ($feriadosFixos as $data) {
            $feriados[] = $ano . '-' . $data;
        }
        
        // Feriados móveis baseados na Páscoa
        $pascoa = self::calcularPascoa($ano);
        $feriados[] = date('Y-m-d', strtotime('-47 days', $pascoa)); // Carnaval (terça)
        $feriados[] = date('Y-m-d', strtotime('-2 days', $pascoa));  // Sexta-feira Santa
        $feriados[] = date('Y-m-d', $pascoa);                        // Páscoa
        $feriados[] = date('Y-m-d', strtotime('+60 days', $pascoa)); // Corpus Christi
        
        return $feriados;
    }
    
    //SisFunction::diasUteisNoMes($param['mes'],$param['ano'])
    public static function diasUteisNoMes($mes = null, $ano = null) {
        if ($mes === null) $mes = date('n');
        if ($ano === null) $ano = date('Y');
        
        $feriados = self::getFeriados($ano);
        $primeiroDia = mktime(0, 0, 0, $mes, 1, $ano);
        $ultimoDia = mktime(0, 0, 0, $mes + 1, 0, $ano);
        
        $diasUteis = 0;
        
        for ($timestamp = $primeiroDia; $timestamp <= $ultimoDia; $timestamp += 86400) {
            $diaSemana = date('N', $timestamp);
            $dataAtual = date('Y-m-d', $timestamp);
            
            // Pula fins de semana
            if ($diaSemana >= 6) continue;
            
            // Pula feriados
            if (in_array($dataAtual, $feriados)) continue;
            
            $diasUteis++;
        }
        
        return $diasUteis;
    }    
    
    /**
     * Retorna array com os dias úteis do mês
     * @param int $mes Mês (1-12)
     * @param int $ano Ano
     * @param string $formato Formato da data de retorno ('Y-m-d', 'd', 'j', etc.)
     * Formato Y-m-d
     * Formato 'd' (apenas dia com zero)
     * Formato 'j' (apenas dia sem zero)
     * @return array Array com os dias úteis
     */
    //SisFunction::arrayDiasUteisNoMes($param['mes'],$param['ano'],'d')
    public static function arrayDiasUteisNoMes($mes = null, $ano = null, $formato = 'Y-m-d') {
        if ($mes === null) $mes = date('n');
        if ($ano === null) $ano = date('Y');
        
        $feriados = self::getFeriados($ano);
        $primeiroDia = mktime(0, 0, 0, $mes, 1, $ano);
        $ultimoDia = mktime(0, 0, 0, $mes + 1, 0, $ano);
        
        $diasUteis = [];
        
        for ($timestamp = $primeiroDia; $timestamp <= $ultimoDia; $timestamp += 86400) {
            $diaSemana = date('N', $timestamp);
            $dataAtual = date('Y-m-d', $timestamp);
            
            // Pula fins de semana
            if ($diaSemana >= 6) continue;
            
            // Pula feriados
            if (in_array($dataAtual, $feriados)) continue;
            
            // Adiciona o dia útil no formato desejado
            $diasUteis[] = date($formato, $timestamp);
        }
        
        return $diasUteis;
    }
    
    /**
     * Versão que retorna array com mais informações sobre cada dia útil
     */
    public static function arrayDiasUteisDetalhado($mes = null, $ano = null) {
        if ($mes === null) $mes = date('n');
        if ($ano === null) $ano = date('Y');
        
        $feriados = self::getFeriados($ano);
        $primeiroDia = mktime(0, 0, 0, $mes, 1, $ano);
        $ultimoDia = mktime(0, 0, 0, $mes + 1, 0, $ano);
        
        $diasUteis = [];
        $contador = 1;
        
        for ($timestamp = $primeiroDia; $timestamp <= $ultimoDia; $timestamp += 86400) {
            $diaSemana = date('N', $timestamp);
            $dataAtual = date('Y-m-d', $timestamp);
            
            // Pula fins de semana
            if ($diaSemana >= 6) continue;
            
            // Pula feriados
            if (in_array($dataAtual, $feriados)) continue;
            
            // Adiciona informações detalhadas
            $diasUteis[] = [
                'data' => $dataAtual,
                'dia' => date('d', $timestamp),
                'dia_semana' => date('w', $timestamp), // 0=domingo, 6=sábado
                'nome_dia' => date('l', $timestamp),   // Nome do dia em inglês
                'nome_dia_pt' => self::getNomeDia(date('w', $timestamp)),
                'dia_util_numero' => $contador,
                'timestamp' => $timestamp
            ];
            
            $contador++;
        }
        
        return $diasUteis;
    }
    
    private static function getNomeDia($numeroDia) {
        $dias = [
            0 => 'Domingo',
            1 => 'Segunda-feira',
            2 => 'Terça-feira',
            3 => 'Quarta-feira',
            4 => 'Quinta-feira',
            5 => 'Sexta-feira',
            6 => 'Sábado'
        ];
        
        return $dias[$numeroDia];

    }

    /**
    * Retorna array com todos os dias do mês formatados com HTML
    * Dias úteis em azul, finais de semana e feriados em vermelho
    * Dia atual em bold
    * @param int $mes Mês (1-12)
    * @param int $ano Ano
    * @param bool $incluirZero Se deve incluir zero à esquerda nos dias
    * @return array Array com dias formatados em HTML
    */
    public static function arrayDiasFormatadosHTML($mes = null, $ano = null, $incluirZero = true) {
        if ($mes === null) $mes = date('n');
        if ($ano === null) $ano = date('Y');
        
        $feriados = self::getFeriados($ano);
        $diasUteis = self::arrayDiasUteisNoMes($mes, $ano, 'Y-m-d');
        $dataAtual = date('Y-m-d'); // Data de hoje
        
        $primeiroDia = mktime(0, 0, 0, $mes, 1, $ano);
        $ultimoDia = mktime(0, 0, 0, $mes + 1, 0, $ano);
        
        $diasFormatados = [];
        
        for ($timestamp = $primeiroDia; $timestamp <= $ultimoDia; $timestamp += 86400) {
            $diaSemana = date('N', $timestamp); // 1=segunda, 7=domingo
            $dataIteracao = date('Y-m-d', $timestamp);
            $diaNumero = $incluirZero ? date('d', $timestamp) : date('j', $timestamp);
            
            // Verifica se é o dia atual
            $ehDiaAtual = ($dataIteracao === $dataAtual);
            
            // Verifica se é dia útil
            $ehDiaUtil = in_array($dataIteracao, $diasUteis);
            
            // Define a cor
            $cor = $ehDiaUtil ? 'blue' : 'red';
            
            // Define o peso da fonte (bold apenas para o dia atual)
            $peso = $ehDiaAtual ? 'bold' : 'normal';
            
            // Aplica formatação HTML
            $diasFormatados[] = '<span style="color: ' . $cor . '; font-weight: ' . $peso . ';">' . $diaNumero . '</span>';
        }
        
        return $diasFormatados;
    }

}

