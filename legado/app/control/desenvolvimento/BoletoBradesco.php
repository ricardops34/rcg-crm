<?php

use OpenBoleto\Banco\Bradesco;
use OpenBoleto\Agente;

class BoletoBradesco extends TPage
{
    public function __construct($param)
    {
        parent::__construct();
        TTransaction::open('erp_online');
        
        $oTitulo= TituloReceber::find( $param['key'] );
        if($oTitulo){
            
            $sacado = new Agente($oTitulo->cliente->razao, $oTitulo->cliente->cnpj_cpf, $oTitulo->cliente->endereco, $oTitulo->cliente->cep, $oTitulo->cliente->municipio->descricao, $oTitulo->cliente->municipio->estado->descricao);
            $cedente = new Agente('Empresa de cosméticos LTDA', '02.123.123/0001-11', 'CLS 403 Lj 23', '71000-000', 'Brasília', 'DF');
            
            $boleto = new Bradesco(array(
                // Parâmetros obrigatórios
                'dataVencimento' => new DateTime('2013-01-01'),
                'valor' => 10.50,
                'sequencial' => 123456789, // Até 11 dígitos
                'sacado' => $sacado,
                'cedente' => $cedente,
                'agencia' => 1172, // Até 4 dígitos
                'carteira' => 6, // 3, 6 ou 9
                'conta' => 0403005, // Até 7 dígitos
            
                // Parâmetros recomendáveis
                //'logoPath' => 'http://empresa.com.br/logo.jpg', // Logo da sua empresa
                'contaDv' => 2,
                'agenciaDv' => 1,
                'carteiraDv' => 1,
                'descricaoDemonstrativo' => array( // Até 5
                    'Compra de materiais cosméticos',
                    'Compra de alicate',
                ),
                'instrucoes' => array( // Até 8
                    'Após o dia 30/11 cobrar 2% de mora e 1% de juros ao dia.',
                    'Não receber após o vencimento.',
                ),
            
                // Parâmetros opcionais
                //'resourcePath' => '../resources',
                //'cip' => '000', // Apenas para o Bradesco
                //'moeda' => Bradesco::MOEDA_REAL,
                //'dataDocumento' => new DateTime(),
                //'dataProcessamento' => new DateTime(),
                //'contraApresentacao' => true,
                //'pagamentoMinimo' => 23.00,
                //'aceite' => 'N',
                //'especieDoc' => 'ABC',
                //'numeroDocumento' => '123.456.789',
                //'usoBanco' => 'Uso banco',
                //'layout' => 'layout.phtml',
                //'logoPath' => 'http://boletophp.com.br/img/opensource-55x48-t.png',
                //'sacadorAvalista' => new Agente('Antônio da Silva', '02.123.123/0001-11'),
                //'descontosAbatimentos' => 123.12,
                //'moraMulta' => 123.12,
                //'outrasDeducoes' => 123.12,
                //'outrosAcrescimos' => 123.12,
                //'valorCobrado' => 123.12,
                //'valorUnitario' => 123.12,
                //'quantidade' => 1,
            ));
            
            $html = $boleto->getOutput();      
            
            file_put_contents('app/output/boleto.html', $html);
            TPage::openFile('app/output/boleto.html');
        
        }
        TTransaction::close();
        
    }
    
    // função executa ao clicar no item de menu
    public function onShow($param = null)
    {
        
    }
}
