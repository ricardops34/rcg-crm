<?php

class SituacaoCadastral extends TRecord
{
    const TABLENAME  = 'situacao_cadastral';
    const PRIMARYKEY = 'id';
    const IDPOLICY   =  'serial'; // {max, serial}

    const CREATEDAT  = 'dt_inclusao';
    const UPDATEDAT  = 'dt_alteracao';

    private MotivoBloqueio $motivo_bloqueio;

    

    /**
     * Constructor method
     */
    public function __construct($id = NULL, $callObjectLoad = TRUE)
    {
        parent::__construct($id, $callObjectLoad);
        parent::addAttribute('descricao');
        parent::addAttribute('motivo_bloqueio_id');
        parent::addAttribute('dt_alteracao');
        parent::addAttribute('dt_inclusao');
            
    }

    /**
     * Method set_motivo_bloqueio
     * Sample of usage: $var->motivo_bloqueio = $object;
     * @param $object Instance of MotivoBloqueio
     */
    public function set_motivo_bloqueio(MotivoBloqueio $object)
    {
        $this->motivo_bloqueio = $object;
        $this->motivo_bloqueio_id = $object->id;
    }

    /**
     * Method get_motivo_bloqueio
     * Sample of usage: $var->motivo_bloqueio->attribute;
     * @returns MotivoBloqueio instance
     */
    public function get_motivo_bloqueio()
    {
    
        // loads the associated object
        if (empty($this->motivo_bloqueio))
            $this->motivo_bloqueio = new MotivoBloqueio($this->motivo_bloqueio_id);
    
        // returns the associated object
        return $this->motivo_bloqueio;
    }

    /**
     * Method getClienteAtualizacaos
     */
    public function getClienteAtualizacaos()
    {
        $criteria = new TCriteria;
        $criteria->add(new TFilter('situacao_cadastral_id', '=', $this->id));
        return ClienteAtualizacao::getObjects( $criteria );
    }
    /**
     * Method getClientes
     */
    public function getClientes()
    {
        $criteria = new TCriteria;
        $criteria->add(new TFilter('situacao_cadastral_id', '=', $this->id));
        return Cliente::getObjects( $criteria );
    }

    public function set_cliente_atualizacao_cliente_to_string($cliente_atualizacao_cliente_to_string)
    {
        if(is_array($cliente_atualizacao_cliente_to_string))
        {
            $values = Cliente::where('id', 'in', $cliente_atualizacao_cliente_to_string)->getIndexedArray('razao', 'razao');
            $this->cliente_atualizacao_cliente_to_string = implode(', ', $values);
        }
        else
        {
            $this->cliente_atualizacao_cliente_to_string = $cliente_atualizacao_cliente_to_string;
        }

        $this->vdata['cliente_atualizacao_cliente_to_string'] = $this->cliente_atualizacao_cliente_to_string;
    }

    public function get_cliente_atualizacao_cliente_to_string()
    {
        if(!empty($this->cliente_atualizacao_cliente_to_string))
        {
            return $this->cliente_atualizacao_cliente_to_string;
        }
    
        $values = ClienteAtualizacao::where('situacao_cadastral_id', '=', $this->id)->getIndexedArray('cliente_id','{cliente->razao}');
        return implode(', ', $values);
    }

    public function set_cliente_atualizacao_situacao_cadastral_to_string($cliente_atualizacao_situacao_cadastral_to_string)
    {
        if(is_array($cliente_atualizacao_situacao_cadastral_to_string))
        {
            $values = SituacaoCadastral::where('id', 'in', $cliente_atualizacao_situacao_cadastral_to_string)->getIndexedArray('descricao', 'descricao');
            $this->cliente_atualizacao_situacao_cadastral_to_string = implode(', ', $values);
        }
        else
        {
            $this->cliente_atualizacao_situacao_cadastral_to_string = $cliente_atualizacao_situacao_cadastral_to_string;
        }

        $this->vdata['cliente_atualizacao_situacao_cadastral_to_string'] = $this->cliente_atualizacao_situacao_cadastral_to_string;
    }

    public function get_cliente_atualizacao_situacao_cadastral_to_string()
    {
        if(!empty($this->cliente_atualizacao_situacao_cadastral_to_string))
        {
            return $this->cliente_atualizacao_situacao_cadastral_to_string;
        }
    
        $values = ClienteAtualizacao::where('situacao_cadastral_id', '=', $this->id)->getIndexedArray('situacao_cadastral_id','{situacao_cadastral->descricao}');
        return implode(', ', $values);
    }

    public function set_cliente_atualizacao_atividade_principal_to_string($cliente_atualizacao_atividade_principal_to_string)
    {
        if(is_array($cliente_atualizacao_atividade_principal_to_string))
        {
            $values = Cnae::where('id', 'in', $cliente_atualizacao_atividade_principal_to_string)->getIndexedArray('id', 'id');
            $this->cliente_atualizacao_atividade_principal_to_string = implode(', ', $values);
        }
        else
        {
            $this->cliente_atualizacao_atividade_principal_to_string = $cliente_atualizacao_atividade_principal_to_string;
        }

        $this->vdata['cliente_atualizacao_atividade_principal_to_string'] = $this->cliente_atualizacao_atividade_principal_to_string;
    }

    public function get_cliente_atualizacao_atividade_principal_to_string()
    {
        if(!empty($this->cliente_atualizacao_atividade_principal_to_string))
        {
            return $this->cliente_atualizacao_atividade_principal_to_string;
        }
    
        $values = ClienteAtualizacao::where('situacao_cadastral_id', '=', $this->id)->getIndexedArray('atividade_principal_id','{atividade_principal->id}');
        return implode(', ', $values);
    }

    public function set_cliente_atualizacao_pais_to_string($cliente_atualizacao_pais_to_string)
    {
        if(is_array($cliente_atualizacao_pais_to_string))
        {
            $values = Pais::where('id', 'in', $cliente_atualizacao_pais_to_string)->getIndexedArray('id', 'id');
            $this->cliente_atualizacao_pais_to_string = implode(', ', $values);
        }
        else
        {
            $this->cliente_atualizacao_pais_to_string = $cliente_atualizacao_pais_to_string;
        }

        $this->vdata['cliente_atualizacao_pais_to_string'] = $this->cliente_atualizacao_pais_to_string;
    }

    public function get_cliente_atualizacao_pais_to_string()
    {
        if(!empty($this->cliente_atualizacao_pais_to_string))
        {
            return $this->cliente_atualizacao_pais_to_string;
        }
    
        $values = ClienteAtualizacao::where('situacao_cadastral_id', '=', $this->id)->getIndexedArray('pais_id','{pais->id}');
        return implode(', ', $values);
    }

    public function set_cliente_filial_to_string($cliente_filial_to_string)
    {
        if(is_array($cliente_filial_to_string))
        {
            $values = Filial::where('id', 'in', $cliente_filial_to_string)->getIndexedArray('apelido', 'apelido');
            $this->cliente_filial_to_string = implode(', ', $values);
        }
        else
        {
            $this->cliente_filial_to_string = $cliente_filial_to_string;
        }

        $this->vdata['cliente_filial_to_string'] = $this->cliente_filial_to_string;
    }

    public function get_cliente_filial_to_string()
    {
        if(!empty($this->cliente_filial_to_string))
        {
            return $this->cliente_filial_to_string;
        }
    
        $values = Cliente::where('situacao_cadastral_id', '=', $this->id)->getIndexedArray('filial_id','{filial->apelido}');
        return implode(', ', $values);
    }

    public function set_cliente_tipo_cliente_to_string($cliente_tipo_cliente_to_string)
    {
        if(is_array($cliente_tipo_cliente_to_string))
        {
            $values = TipoCliente::where('id', 'in', $cliente_tipo_cliente_to_string)->getIndexedArray('descricao', 'descricao');
            $this->cliente_tipo_cliente_to_string = implode(', ', $values);
        }
        else
        {
            $this->cliente_tipo_cliente_to_string = $cliente_tipo_cliente_to_string;
        }

        $this->vdata['cliente_tipo_cliente_to_string'] = $this->cliente_tipo_cliente_to_string;
    }

    public function get_cliente_tipo_cliente_to_string()
    {
        if(!empty($this->cliente_tipo_cliente_to_string))
        {
            return $this->cliente_tipo_cliente_to_string;
        }
    
        $values = Cliente::where('situacao_cadastral_id', '=', $this->id)->getIndexedArray('tipo_cliente_id','{tipo_cliente->descricao}');
        return implode(', ', $values);
    }

    public function set_cliente_municipio_to_string($cliente_municipio_to_string)
    {
        if(is_array($cliente_municipio_to_string))
        {
            $values = Municipio::where('id', 'in', $cliente_municipio_to_string)->getIndexedArray('cod_erp', 'cod_erp');
            $this->cliente_municipio_to_string = implode(', ', $values);
        }
        else
        {
            $this->cliente_municipio_to_string = $cliente_municipio_to_string;
        }

        $this->vdata['cliente_municipio_to_string'] = $this->cliente_municipio_to_string;
    }

    public function get_cliente_municipio_to_string()
    {
        if(!empty($this->cliente_municipio_to_string))
        {
            return $this->cliente_municipio_to_string;
        }
    
        $values = Cliente::where('situacao_cadastral_id', '=', $this->id)->getIndexedArray('municipio_id','{municipio->cod_erp}');
        return implode(', ', $values);
    }

    public function set_cliente_vendedor_to_string($cliente_vendedor_to_string)
    {
        if(is_array($cliente_vendedor_to_string))
        {
            $values = Vendedor::where('id', 'in', $cliente_vendedor_to_string)->getIndexedArray('nome', 'nome');
            $this->cliente_vendedor_to_string = implode(', ', $values);
        }
        else
        {
            $this->cliente_vendedor_to_string = $cliente_vendedor_to_string;
        }

        $this->vdata['cliente_vendedor_to_string'] = $this->cliente_vendedor_to_string;
    }

    public function get_cliente_vendedor_to_string()
    {
        if(!empty($this->cliente_vendedor_to_string))
        {
            return $this->cliente_vendedor_to_string;
        }
    
        $values = Cliente::where('situacao_cadastral_id', '=', $this->id)->getIndexedArray('vendedor_id','{vendedor->nome}');
        return implode(', ', $values);
    }

    public function set_cliente_condicao_pagamento_to_string($cliente_condicao_pagamento_to_string)
    {
        if(is_array($cliente_condicao_pagamento_to_string))
        {
            $values = CondicaoPagamento::where('id', 'in', $cliente_condicao_pagamento_to_string)->getIndexedArray('descricao', 'descricao');
            $this->cliente_condicao_pagamento_to_string = implode(', ', $values);
        }
        else
        {
            $this->cliente_condicao_pagamento_to_string = $cliente_condicao_pagamento_to_string;
        }

        $this->vdata['cliente_condicao_pagamento_to_string'] = $this->cliente_condicao_pagamento_to_string;
    }

    public function get_cliente_condicao_pagamento_to_string()
    {
        if(!empty($this->cliente_condicao_pagamento_to_string))
        {
            return $this->cliente_condicao_pagamento_to_string;
        }
    
        $values = Cliente::where('situacao_cadastral_id', '=', $this->id)->getIndexedArray('condicao_pagamento_id','{condicao_pagamento->descricao}');
        return implode(', ', $values);
    }

    public function set_cliente_tabela_preco_to_string($cliente_tabela_preco_to_string)
    {
        if(is_array($cliente_tabela_preco_to_string))
        {
            $values = TabelaPreco::where('id', 'in', $cliente_tabela_preco_to_string)->getIndexedArray('descricao', 'descricao');
            $this->cliente_tabela_preco_to_string = implode(', ', $values);
        }
        else
        {
            $this->cliente_tabela_preco_to_string = $cliente_tabela_preco_to_string;
        }

        $this->vdata['cliente_tabela_preco_to_string'] = $this->cliente_tabela_preco_to_string;
    }

    public function get_cliente_tabela_preco_to_string()
    {
        if(!empty($this->cliente_tabela_preco_to_string))
        {
            return $this->cliente_tabela_preco_to_string;
        }
    
        $values = Cliente::where('situacao_cadastral_id', '=', $this->id)->getIndexedArray('tabela_preco_id','{tabela_preco->descricao}');
        return implode(', ', $values);
    }

    public function set_cliente_seguimento_to_string($cliente_seguimento_to_string)
    {
        if(is_array($cliente_seguimento_to_string))
        {
            $values = Segmento::where('id', 'in', $cliente_seguimento_to_string)->getIndexedArray('descricao', 'descricao');
            $this->cliente_seguimento_to_string = implode(', ', $values);
        }
        else
        {
            $this->cliente_seguimento_to_string = $cliente_seguimento_to_string;
        }

        $this->vdata['cliente_seguimento_to_string'] = $this->cliente_seguimento_to_string;
    }

    public function get_cliente_seguimento_to_string()
    {
        if(!empty($this->cliente_seguimento_to_string))
        {
            return $this->cliente_seguimento_to_string;
        }
    
        $values = Cliente::where('situacao_cadastral_id', '=', $this->id)->getIndexedArray('seguimento_id','{seguimento->descricao}');
        return implode(', ', $values);
    }

    public function set_cliente_motivo_bloqueio_to_string($cliente_motivo_bloqueio_to_string)
    {
        if(is_array($cliente_motivo_bloqueio_to_string))
        {
            $values = MotivoBloqueio::where('id', 'in', $cliente_motivo_bloqueio_to_string)->getIndexedArray('id', 'id');
            $this->cliente_motivo_bloqueio_to_string = implode(', ', $values);
        }
        else
        {
            $this->cliente_motivo_bloqueio_to_string = $cliente_motivo_bloqueio_to_string;
        }

        $this->vdata['cliente_motivo_bloqueio_to_string'] = $this->cliente_motivo_bloqueio_to_string;
    }

    public function get_cliente_motivo_bloqueio_to_string()
    {
        if(!empty($this->cliente_motivo_bloqueio_to_string))
        {
            return $this->cliente_motivo_bloqueio_to_string;
        }
    
        $values = Cliente::where('situacao_cadastral_id', '=', $this->id)->getIndexedArray('motivo_bloqueio_id','{motivo_bloqueio->id}');
        return implode(', ', $values);
    }

    public function set_cliente_regiao_cliente_to_string($cliente_regiao_cliente_to_string)
    {
        if(is_array($cliente_regiao_cliente_to_string))
        {
            $values = RegiaoCliente::where('id', 'in', $cliente_regiao_cliente_to_string)->getIndexedArray('id', 'id');
            $this->cliente_regiao_cliente_to_string = implode(', ', $values);
        }
        else
        {
            $this->cliente_regiao_cliente_to_string = $cliente_regiao_cliente_to_string;
        }

        $this->vdata['cliente_regiao_cliente_to_string'] = $this->cliente_regiao_cliente_to_string;
    }

    public function get_cliente_regiao_cliente_to_string()
    {
        if(!empty($this->cliente_regiao_cliente_to_string))
        {
            return $this->cliente_regiao_cliente_to_string;
        }
    
        $values = Cliente::where('situacao_cadastral_id', '=', $this->id)->getIndexedArray('regiao_cliente_id','{regiao_cliente->id}');
        return implode(', ', $values);
    }

    public function set_cliente_situacao_cadastral_to_string($cliente_situacao_cadastral_to_string)
    {
        if(is_array($cliente_situacao_cadastral_to_string))
        {
            $values = SituacaoCadastral::where('id', 'in', $cliente_situacao_cadastral_to_string)->getIndexedArray('descricao', 'descricao');
            $this->cliente_situacao_cadastral_to_string = implode(', ', $values);
        }
        else
        {
            $this->cliente_situacao_cadastral_to_string = $cliente_situacao_cadastral_to_string;
        }

        $this->vdata['cliente_situacao_cadastral_to_string'] = $this->cliente_situacao_cadastral_to_string;
    }

    public function get_cliente_situacao_cadastral_to_string()
    {
        if(!empty($this->cliente_situacao_cadastral_to_string))
        {
            return $this->cliente_situacao_cadastral_to_string;
        }
    
        $values = Cliente::where('situacao_cadastral_id', '=', $this->id)->getIndexedArray('situacao_cadastral_id','{situacao_cadastral->descricao}');
        return implode(', ', $values);
    }

    /**
     * Method onBeforeDelete
     */
    public function onBeforeDelete()
    {
            

        if(ClienteAtualizacao::where('situacao_cadastral_id', '=', $this->id)->first())
        {
            throw new Exception("Não é possível deletar este registro pois ele está sendo utilizado em outra parte do sistema");
        }
    
        if(Cliente::where('situacao_cadastral_id', '=', $this->id)->first())
        {
            throw new Exception("Não é possível deletar este registro pois ele está sendo utilizado em outra parte do sistema");
        }
    
    }

    
}

