<?php

class ViewTituloCliente extends TRecord
{
    const TABLENAME  = 'view_titulo_cliente';
    const PRIMARYKEY = 'id';
    const IDPOLICY   =  'max'; // {max, serial}

    /**
     * Constructor method
     */
    public function __construct($id = NULL, $callObjectLoad = TRUE)
    {
        parent::__construct($id, $callObjectLoad);
        parent::addAttribute('cliente_id');
        parent::addAttribute('vendedor_id');
        parent::addAttribute('prefixo');
        parent::addAttribute('tipo');
        parent::addAttribute('numero');
        parent::addAttribute('parcela');
        parent::addAttribute('emissao');
        parent::addAttribute('vencimento');
        parent::addAttribute('saldo');
        parent::addAttribute('valor');
        parent::addAttribute('forma');
        parent::addAttribute('pedido_id');
        parent::addAttribute('nota_fiscal_id');
        parent::addAttribute('origem');
        parent::addAttribute('dias');
        parent::addAttribute('situacao');
        parent::addAttribute('mesVencimento');
        parent::addAttribute('anoVencimento');
        parent::addAttribute('mesEmissao');
        parent::addAttribute('anoEmissao');
        parent::addAttribute('portador');
    
    }

    public static function getValoresTotaisPorSituacao()
    {
        try
        {
            TTransaction::open('erp_online');
        
            /*
            $repository = new TRepository('TituloReceber');
        
            // Consulta que obtém os totais por situação
            $result = $repository->getSqlAssoc("
                SELECT situacao, SUM(saldo) as total 
                FROM titulos_receber 
                GROUP BY situacao
            ");
            */
            $result = TituloReceber::where('reg_ativo', '=', 'S')
                ->groupBy('situacao')
                ->sumBy('saldo');
            // Prepara o array de resultados
            $statistics = [];
        
            if ($result)
            {
                foreach ($result as $row)
                {
                    $statistics[$row->situacao] = (float) $row->saldo;
                }
            }
        
            TTransaction::close();
            return $statistics;
        }
        catch (Exception $e)
        {
            TTransaction::rollback();
            throw new Exception($e->getMessage());
        }
    }

    /**
     * Obtém valores a receber por mês de vencimento
     */
    public static function getValoresPorMesVencimento($ano)
    {
        try
        {
            TTransaction::open('erp_online');
        
            //$repository = new TRepository('TituloReceber');
            //$result = $repository->executeSql($sql, [':ano' => $ano]);
        
            // Consulta que obtém os totais mensais
            $sql = "SELECT mesVencimento as mes, SUM(saldo) as total 
                    FROM view_titulo_cliente 
                    WHERE anoVencimento = ".$ano." AND (situacao = 'A RECEBER' OR situacao = 'EM ATRASO')
                    GROUP BY mesVencimento 
                    ORDER BY mesVencimento";
        
            $conn = TTransaction::get(); // get PDO connection
        
            // run query
            $result = $conn->query($sql);
            // Executa a consulta com parâmetro
        
            // Inicializa array com zeros para todos os meses
            $monthlyData = array_fill(1, 12, 0);
        
            // Preenche com os dados obtidos
            if ($result)
            {
                foreach ($result as $row)
                {
                    $monthlyData[(int) $row->mes] = (float) $row->total;
                }
            }
        
            TTransaction::close();
            return $monthlyData;
        }
        catch (Exception $e)
        {
            TTransaction::rollback();
            throw new Exception($e->getMessage());
        }
    }

    /**
     * Obtém valores recebidos por mês de emissão
     */
    public static function getValoresRecebidosPorMes($ano)
    {
        try
        {
            TTransaction::open('erp_online');
        
            $repository = new TRepository('TituloReceber');
        
            // Consulta que obtém os totais mensais
            $sql = "SELECT mesEmissao as mes, SUM(valor) as total 
                    FROM view_titulo_cliente 
                    WHERE anoEmissao = ".$ano." AND situacao = 'RECEBIDO'
                    GROUP BY mesEmissao 
                    ORDER BY mesEmissao";
        
            $conn = TTransaction::get(); // get PDO connection
        
            // run query
            $result = $conn->query($sql);
            // Executa a consulta com parâmetro
            //$result = $repository->executeSql($sql, [':ano' => $ano]);
        
            // Inicializa array com zeros para todos os meses
            $monthlyData = array_fill(1, 12, 0);
        
            // Preenche com os dados obtidos
            if ($result)
            {
                foreach ($result as $row)
                {
                    $monthlyData[(int) $row->mes] = (float) $row->total;
                }
            }
        
            TTransaction::close();
            return $monthlyData;
        }
        catch (Exception $e)
        {
            TTransaction::rollback();
            throw new Exception($e->getMessage());
        }
    }

    /**
     * Obtém valores em atraso por dias
     */
    public static function getValoresEmAtrasoPorDias()
    {
        try
        {
            TTransaction::open('erp_online');
        
            //$repository = new TRepository('TituloReceber');
        
            $intervals = [
                '1-15' => 'AND dias BETWEEN 1 AND 15',
                '16-30' => 'AND dias BETWEEN 16 AND 30',
                '31-60' => 'AND dias BETWEEN 31 AND 60',
                '61-90' => 'AND dias BETWEEN 61 AND 90',
                '90+' => 'AND dias > 90'
            ];
        
            $result = [];
        
            foreach ($intervals as $key => $condition)
            {
                $sql = "SELECT SUM(saldo) as total 
                        FROM view_titulo_cliente 
                        WHERE situacao = 'EM ABERTO' ".$condition;
            
                //$data = $repository->executeSql($sql);
                //$conn = TTransaction::get(); // get PDO connection
        
            // run query
                //$result = $conn->query($sql);            
                //$result[$key] = isset($data->total) ? (float) $data->total : 0;
                $result[$key] = 0;
            }
        
            TTransaction::close();
            return $result;
        }
        catch (Exception $e)
        {
            TTransaction::rollback();
            throw new Exception($e->getMessage());
        }
    }
        
}

