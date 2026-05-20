<?php

use Mad\Rest\Request;
use Mad\Rest\Response;
use Mad\Rest\JSONResponse;



class ApiClienteController extends ApiResourceController
{
    protected $model = 'Cliente';
    protected $database = 'erp_online';
    protected $primaryKey = 'id';
    protected $perPage = 15;

    protected $sortable = [];

    protected $searchable = ['celular'];

    protected $showFields = ['id' => '{id}','filial_id' => '{filial_id}','cod_erp' => '{cod_erp}','status' => '{status}','tipo' => '{tipo}','razao' => '{razao}','tipo_cliente_id' => '{tipo_cliente_id}','fantasia' => '{fantasia}','endereco' => '{endereco}','complemento' => '{complemento}','bairro' => '{bairro}','uf' => '{uf}','municipio_id' => '{municipio_id}','cep' => '{cep}','telefone1' => '{telefone1}','telefone2' => '{telefone2}','fax' => '{fax}','celular' => '{celular}','celular2' => '{celular2}','contato' => '{contato}','cnpj_cpf' => '{cnpj_cpf}','ie' => '{ie}','im' => '{im}','contribuinte' => '{contribuinte}','rg' => '{rg}','nascimento' => '{nascimento}','email' => '{email}','vendedor_id' => '{vendedor_id}','condicao_pagamento_id' => '{condicao_pagamento_id}','tabela_preco_id' => '{tabela_preco_id}','primeira_compra' => '{primeira_compra}','ultima_compra' => '{ultima_compra}','sinc' => '{sinc}','data_cadastro' => '{data_cadastro}','dt_alteracao' => '{dt_alteracao}','dt_inclusao' => '{dt_inclusao}','destaca_ie' => '{destaca_ie}','seguimento_id' => '{seguimento_id}','site' => '{site}','filial_cadastro' => '{filial_cadastro}','cliente' => '{cliente}','log_int' => '{log_int}','limite' => '{limite}','vencimento_limite' => '{vencimento_limite}','risco' => '{risco}','obs' => '{obs}','latitude' => '{latitude}','longitude' => '{longitude}','system_unit_id' => '{system_unit_id}','carteira' => '{carteira}','obs_bloqueio' => '{obs_bloqueio}','dt_bloqueio' => '{dt_bloqueio}','dt_reativacao' => '{dt_reativacao}','obs_reativacao' => '{obs_reativacao}','ultima_visita' => '{ultima_visita}','ultimo_atendimento' => '{ultimo_atendimento}','reg_ativo' => '{reg_ativo}','dt_revisao' => '{dt_revisao}','data_rfb' => '{data_rfb}','motivo_bloqueio_id' => '{motivo_bloqueio_id}'];
    
    protected $indexFields = ['id' => '{id}','filial_id' => '{filial_id}','cod_erp' => '{cod_erp}','status' => '{status}','tipo' => '{tipo}','razao' => '{razao}','tipo_cliente_id' => '{tipo_cliente_id}','fantasia' => '{fantasia}','endereco' => '{endereco}','complemento' => '{complemento}','bairro' => '{bairro}','uf' => '{uf}','municipio' => '{municipio}','municipio_id' => '{municipio_id}','cep' => '{cep}','telefone1' => '{telefone1}','telefone2' => '{telefone2}','fax' => '{fax}','celular' => '{celular}','celular2' => '{celular2}','contato' => '{contato}','cnpj_cpf' => '{cnpj_cpf}','ie' => '{ie}','im' => '{im}','contribuinte' => '{contribuinte}','rg' => '{rg}','nascimento' => '{nascimento}','email' => '{email}','vendedor_id' => '{vendedor_id}','condicao_pagamento_id' => '{condicao_pagamento_id}','tabela_preco_id' => '{tabela_preco_id}','primeira_compra' => '{primeira_compra}','ultima_compra' => '{ultima_compra}','sinc' => '{sinc}','data_cadastro' => '{data_cadastro}','dt_alteracao' => '{dt_alteracao}','dt_inclusao' => '{dt_inclusao}','destaca_ie' => '{destaca_ie}','seguimento_id' => '{seguimento_id}','site' => '{site}','filial_cadastro' => '{filial_cadastro}','cliente' => '{cliente}','log_int' => '{log_int}','limite' => '{limite}','vencimento_limite' => '{vencimento_limite}','risco' => '{risco}','obs' => '{obs}','latitude' => '{latitude}','longitude' => '{longitude}','system_unit_id' => '{system_unit_id}','carteira' => '{carteira}','obs_bloqueio' => '{obs_bloqueio}','dt_bloqueio' => '{dt_bloqueio}','motivo_bloqueio' => '{motivo_bloqueio}','dt_reativacao' => '{dt_reativacao}','obs_reativacao' => '{obs_reativacao}','ultima_visita' => '{ultima_visita}','ultimo_atendimento' => '{ultimo_atendimento}','regiao_cliente_id' => '{regiao_cliente_id}','reg_ativo' => '{reg_ativo}','motivo_bloqueio_id' => '{motivo_bloqueio_id}','dt_revisao' => '{dt_revisao}','situacao_cadastral_id' => '{situacao_cadastral_id}','data_rfb' => '{data_rfb}'];

    protected $requiredFields = [];

    protected $detailModel = '';

    protected $detailForeignKey = '';

    protected $detailProperty = '';

    protected $requiredDetailFields = [];

    protected $detailIndexFields = [];
    
    public function __construct()
    {
        parent::__construct();

        



        
    }

    public function beforeStoreDetail($master, $detail, $detailData) 
    {
        
        

    }
    
    public function afterStoreDetail($master, $detail, $detailData) 
    {
        
        

    }
    
    public function afterStoreDetails($master, $storedDetails) 
    {
        
        
    }

    public function beforeStore($object, $masterData) 
    {
        
        
        
    }
    
    public function afterStore($object, $masterData) 
    {
        
        
        
    }

    

}
