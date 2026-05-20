<?php

class AtualizaCliente extends TPage
{
    private static $database = 'erp_online';
    
    public function __construct($param)
    {
        parent::__construct();
        TTransaction::open(self::$database);
        
        $count = Cliente::where('tipo',  '=', 'J')
              ->where('situacao_cadastral_id',  'IS', NULL )
              ->count();
        
        //$count   = 1000;
        $nTam    = 10;
        $nPagina = 0;
        $nQtdPg  = round($count/$nTam,0)+1;
        $nLimite = 0;

        for ($i = 1; $i <= $nQtdPg ; $i++) {

            $nLimite++;

            $oClientes = Cliente::where('tipo',  '=', 'J')
                 ->where('situacao_cadastral_id',  'IS', NULL )
                 ->where('status','=','B')
                 ->orderBy('cod_erp')
                 ->take($nTam)
                 ->skip($nPagina)
                 ->load();

            if($oClientes){
                foreach ($oClientes as $oCliente) {
                    $cnpj = "";
                    if($oCliente){
                        if(isset($oCliente->cnpj_cpf)){

                            $cnpj = $oCliente->cnpj_cpf;
                            
                            if(strlen(trim($cnpj)) == 14){
                                $dadosCnpj = BuilderCNPJService::getFull($cnpj);
    
                                sleep(15);  
    
                                if($dadosCnpj)
                                {
    
                                    $oEstabelecimento = $dadosCnpj->estabelecimento;
    
                                    //$oEndereco = CEPService::getCEP($oEstabelecimento->cep); 
    
                                    $oAtividades = $oEstabelecimento->atividade_principal;
    
                                    $oIEs = $oEstabelecimento->inscricoes_estaduais;
    
                                    $oSituacao = SituacaoCadastral::where('descricao', '=', $oEstabelecimento->situacao_cadastral)
                                                ->first();
    
                                    if($oSituacao){
    
                                    }else{
                                        $oSituacao = new SituacaoCadastral();
                                        $oSituacao->descricao = $oEstabelecimento->situacao_cadastral;
                                        $oSituacao->store();
                                    }
    
                                    $dt_atu  = date('Y-m-d H:i:s',strtotime($dadosCnpj->estabelecimento->atualizado_em));
                                    $oClienteAtualizacao = ClienteAtualizacao::where('cnpj_cpf', '=', $cnpj)
                                                        ->where('atualizado_em', '=', $dt_atu)
                                                        ->first();
    
                                    if($oClienteAtualizacao){
    
                                        TToast::show("info", "Os dados já estão Atualizados. API BuilderCNPJService", 'topRight', 'far:check-circle');
    
                                    }else{
    
                                        $oCnae = Cnae::where('cod_erp', '=', $oAtividades->id)
                                            ->first();
    
                                        if($oCnae){
    
                                        }else{
                                            $oCnae = new Cnae();
                                            $oCnae->cod_erp = $oAtividades->id;
                                            $oCnae->secao   = $oAtividades->secao;
                                            $oCnae->divisao = $oAtividades->divisao;
                                            $oCnae->grupo   = $oAtividades->grupo;
                                            $oCnae->classe  = $oAtividades->classe;
                                            $oCnae->subclasse = $oAtividades->subclasse;
                                            $oCnae->descricao = $oAtividades->descricao;
                                            $oCnae->store();
                                        }
    
                                        $oClienteAtualizacao = new ClienteAtualizacao();
                                        $oClienteAtualizacao->cliente_id = $oCliente->id;
                                        $oClienteAtualizacao->situacao_cadastral_id = $oSituacao->id;
    
                                        $oClienteAtualizacao->atividade_principal_id = $oCnae->id;
                                        $oClienteAtualizacao->tipo = $oEstabelecimento->tipo;
                                        $oClienteAtualizacao->capital_social = (double) $dadosCnpj->capital_social;
    
                                        $oSimples = $dadosCnpj->simples;
    
                                        if($oSimples){
                                            //var_dump($oSimples->simples);
                                            $oClienteAtualizacao->simples = $oSimples->simples; 
                                            $oClienteAtualizacao->mei = $oSimples->mei; 
                                        }                                
    
                                        $oPorte = $dadosCnpj->porte;
                                        if($oPorte){
                                            $oClienteAtualizacao->porte = $oPorte->descricao;
                                        }
    
                                        $oNatureza = $dadosCnpj->natureza_juridica;
                                        if($oNatureza){
                                            $oClienteAtualizacao->natureza_juridica = $oNatureza->descricao;
                                        }
    
                                        $oClienteAtualizacao->razao = $dadosCnpj->razao_social;
                                        $oClienteAtualizacao->fantasia = $dadosCnpj->nome_fantasia;
                                        $oClienteAtualizacao->cnpj_cpf = $oEstabelecimento->cnpj;
                                        $oClienteAtualizacao->tipo_logradouro = $oEstabelecimento->tipo_logradouro;
                                        $oClienteAtualizacao->logradouro = $oEstabelecimento->logradouro;
                                        $oClienteAtualizacao->numero = $oEstabelecimento->numero;
                                        $oClienteAtualizacao->complemento = $oEstabelecimento->complemento;
                                        $oClienteAtualizacao->bairro = $oEstabelecimento->bairro;
                                        $oClienteAtualizacao->cep = $oEstabelecimento->cep;
    
                                        $oPais = Pais::where('cod_erp', '=', $oEstabelecimento->pais->id)
                                                ->first();
    
                                        if($oPais){
    
                                        }else{
    
                                            $oPais = new Pais();
                                            $oPais->cod_erp = $oEstabelecimento->pais->id;
                                            $oPais->nome = $oEstabelecimento->pais->nome;
                                            $oPais->sigla = $oEstabelecimento->pais->iso2;
                                            $oPais->comex_id = $oEstabelecimento->pais->comex_id;
                                            $oPais->store();
                                        }                  
    
                                        $oClienteAtualizacao->pais_id = $oPais->id ?? null;
    
                                        $oMunicipio = Municipio::where('cod_erp', '=', $oEstabelecimento->cidade->ibge_id)
                                                ->first();
                                        //$oClienteAtualizacao->municipio_id = $oEstabelecimento->municipio_id;
                                        if($oMunicipio){
                                            $oClienteAtualizacao->municipio_id = $oMunicipio->id ?? null;
                                        }
    
                                        $oClienteAtualizacao->telefone1 = $oEstabelecimento->ddd1.$oEstabelecimento->telefone1;
                                        $oClienteAtualizacao->telefone2 = $oEstabelecimento->ddd2.$oEstabelecimento->telefone2;
                                        $oClienteAtualizacao->fax = $oEstabelecimento->ddd_fax.$oEstabelecimento->fax;
                                        $oClienteAtualizacao->email = $oEstabelecimento->email ?? null ;
    
                                        if($oIEs){
                                            foreach ($oIEs as $oIE) {
                                                if($oIE->ativo = true){
                                                    $oClienteAtualizacao->ie = $oIE->inscricao_estadual ?? null ;
                                                }
                                            }
                                        }
    
                                        $oClienteAtualizacao->data_situacao_especial = $oEstabelecimento->data_situacao_especial ?? null ;
                                        $oClienteAtualizacao->situacao_especial = $oEstabelecimento->situacao_especial ?? null ;
                                        $oClienteAtualizacao->data_situacao_cadastral = $oEstabelecimento->data_situacao_cadastral ?? null ; 
                                        $oClienteAtualizacao->atualizado_em = $dt_atu;
                                        $oClienteAtualizacao->store();
    
                                        $oSecundarias = $oEstabelecimento->atividades_secundarias;
    
                                        if($oSecundarias){
                                            foreach ($oSecundarias as $oSecundaria) {
    
                                                $oCnae = Cnae::where('cod_erp', '=', $oSecundaria->id)
                                                    ->first();
    
                                                if($oCnae){
    
                                                }else{
                                                    $oCnae = new Cnae();
                                                    $oCnae->cod_erp = $oSecundaria->id;
                                                    $oCnae->secao   = $oSecundaria->secao;
                                                    $oCnae->divisao = $oSecundaria->divisao;
                                                    $oCnae->grupo   = $oSecundaria->grupo;
                                                    $oCnae->classe  = $oSecundaria->classe;
                                                    $oCnae->subclasse = $oSecundaria->subclasse;
                                                    $oCnae->descricao = $oSecundaria->descricao;
                                                    $oCnae->store();
                                                }
    
                                                if($oCnae){
                                                    $oClienteCnae = ClienteCnae::where('cliente_id', '=', $oCliente->id)
                                                        ->where('cnae_id', '=', $oCnae->id)
                                                        ->first();
    
                                                    if($oClienteCnae){
    
                                                    }else{
                                                        $oClienteCnae = new ClienteCnae();
                                                        $oClienteCnae->cliente_id = $oCliente->id;
                                                        $oClienteCnae->cnae_id = $oCnae->id;
                                                        $oClienteCnae->store();
                                                    }
                                                }
                                            }
                                        }
    
                                        $oSocios  = $dadosCnpj->socios;
                                        if($oSocios){
                                            foreach ($oSocios as $oSocio) {
                                                $oBusca = ClienteSocios::where('cliente_id', '=', $oCliente->id)
                                                    ->where('nome', '=', $oSocio->nome)
                                                    ->first();
                                                if($oBusca){
    
                                                }else{
                                                    $oQSA = new ClienteSocios();
                                                    $oQSA->cliente_id = $oCliente->id;
                                                    $oQSA->nome = $oSocio->nome;
                                                    $oQSA->tipo = $oSocio->tipo;
                                                    $oQSA->data_entrada = $oSocio->data_entrada;
                                                    $oQSA->faixa_etaria = $oSocio->faixa_etaria;
                                                    $oQSA->qualificacao_socio = $oSocio->qualificacao_socio->descricao;
                                                    $oQSA->descricao = $oSocio->descricao;
                                                    $oQSA->cpf_cnpj_socio = $oSocio->cpf_cnpj_socio;
                                                    $oQSA->store();
                                                }
                                            }
                                        }
    
                                        $objeto = Cliente::find( $oCliente->id );
                                        if($objeto)
                                        {
                                            $objeto->dt_revisao = date('Y-m-d H:i');
                                            $objeto->situacao_cadastral_id = $oSituacao->id;
                                            $objeto->store();    
                                        }
    
                                        }
                                    }
                            }
                        }else{
                            TToast::show("info", "Cliente sem CNPJ", 'topRight', 'far:check-circle');
                        }
                    }
                    echo $cnpj;
                    echo '<br>';
                }
            }

            echo $i.'  $nLimite'.$nLimite;
            echo '<br>';

            if($nLimite == 19){
                $nLimite = 0;
                //sleep(65);        
            }
            $nPagina +=$nTam;
        }
        TTransaction::close();        
    }
    
    // função executa ao clicar no item de menu
    public function onShow($param = null)
    {
        
    }
}
