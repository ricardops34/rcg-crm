<?php

use NFePHP\DA\NFe\Danfe;

class DanfeErp extends TPage
{
    public function __construct($param)
    {
        parent::__construct();
        
        TTransaction::open('portal_erp');

        //var_dump($param);

        if(isset($param['chave']))
        {
            $cChave = $param['chave'];
            $orienta = $param['orientacao'];
            //echo $cChave;
            $oDanfe = NotasaidaXml::where('chave', '=', $cChave)->first();//NotafiscalXmlErp::where('chave', '=', $cChave)->first();
            
            if($oDanfe)
            {
                $xml_erp =  $oDanfe->xml_sig;
                //$xml_sig =  $oDanfe->xml_assinado;
                //$xml_prot = $oDanfe->protocolo;
                $chave = $oDanfe->chave;
                $protocolo =$oDanfe->protocolo;
                $data = new DateTime($oDanfe->data_nfe);//$objeto->date_nfe;
                $hora = $oDanfe->data_nfe;
                
                /*
                $xml = '<?xml version="1.0" encoding="UTF-8" ?> <nfeProc versao="4.00" xmlns="http://www.portalfiscal.inf.br/nfe">';
                $xml .= preg_replace('/[\x00-\x1F\x7F]/u', '', $xml_sig);//.$xml_prot;
                $xml .= preg_replace('/[\x00-\x1F\x7F]/u', '',$xml_prot);//.$xml_prot;
                $xml .= '</nfeProc>';
                */
                
                file_put_contents('app/output/'.$chave.'.xml', $xml);

                $danfepdf = new Danfe($xml);
                //$danfepdf->debugMode(false);
                //$danfepdf->nfeProc = $xml_prot;
                //$danfepdf->creditsIntegratorFooter('WEBNFe Sistemas - http://www.webenf.com.br');
                //$danfepdf->obsContShow(true);
                //$danfepdf->epec('891180004131899', '14/08/2018 11:24:45');
                //$danfepdf->epec($protocolo, $data->format('d/m/Y').' '.$hora);
                //$danfepdf->depecNumber($protocolo);
                // Caso queira mudar a configuracao padrao de impressao
                //  $this->printParameters( $orientacao = '', $papel = 'A4', $margSup = 2, $margEsq = 2 ); 
                
                
                //$danfepdf->printParameters( $orientacao = '', $papel = 'A4', $margSup = 2, $margEsq = 2 );
                $danfepdf->printParameters( $orientacao = $orienta, $papel = 'A4', $margSup = 2, $margEsq = 2 );
                
                // Caso queira sempre ocultar a unidade tributável
                //  $this->setOcultarUnidadeTributavel(true); 
                //Informe o numero DPEC
                //  $danfepdf->depecNumber('123456789'); 

                $logo = '../danfe/rcg_danfe.png';//'data://text/plain;base64,'. base64_encode(file_get_contents(realpath(__DIR__ . '/../../templates/bellenzier/images/user.png')));
                //Configura a posicao da logo
                //  $danfepdf->logoParameters($logo, 'C', false);  
                //Gera o PDF
                $pdf = $danfepdf->render($logo);
                file_put_contents('app/output/'.$chave.'.pdf',$pdf);
                
                /*
                $window = TWindow::create('DANFE - '.$chave, 0.8, 0.8);
                $oPdf = new TElement('object');
                $oPdf->data  = 'app/output/'.$chave.'.pdf';//'http://www.adianti.com.br/framework_files/adianti_framework.pdf';
                $oPdf->type  = 'application/pdf';
                $oPdf->style = "width: 100%; height:calc(100% - 10px)";
                
                $window->add($oPdf);
                $window->show();
                */
                TPage::openFile('app/output/'.$chave.'.pdf');

                $pageParam = []; // ex.: = ['key' => 10]
                
                TApplication::loadPage('NotaFiscalErpList', 'onShow', $pageParam);

            }else{
                new TMessage('info', "Nota Fiscal não Localizada.");
            }
        }else{
            new TMessage('info', "Nota Fiscal não Localizada.");
        }
        TTransaction::close();

    }
    
    // função executa ao clicar no item de menu
    public function onShow($param = null)
    {
        
    }
}
