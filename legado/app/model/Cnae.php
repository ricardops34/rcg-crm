<?php

class Cnae extends TRecord
{
    const TABLENAME  = 'cnae';
    const PRIMARYKEY = 'id';
    const IDPOLICY   =  'serial'; // {max, serial}

    const CREATEDAT  = 'dt_inclusao';
    const UPDATEDAT  = 'dt_alteracao';

    

    /**
     * Constructor method
     */
    public function __construct($id = NULL, $callObjectLoad = TRUE)
    {
        parent::__construct($id, $callObjectLoad);
        parent::addAttribute('cod_erp');
        parent::addAttribute('secao');
        parent::addAttribute('divisao');
        parent::addAttribute('grupo');
        parent::addAttribute('classe');
        parent::addAttribute('subclasse');
        parent::addAttribute('descricao');
        parent::addAttribute('dt_inclusao');
        parent::addAttribute('dt_alteracao');
            
    }

    /**
     * Method getClienteAtualizacaos
     */
    public function getClienteAtualizacaos()
    {
        $criteria = new TCriteria;
        $criteria->add(new TFilter('atividade_principal_id', '=', $this->id));
        return ClienteAtualizacao::getObjects( $criteria );
    }
    /**
     * Method getClienteCnaes
     */
    public function getClienteCnaes()
    {
        $criteria = new TCriteria;
        $criteria->add(new TFilter('cnae_id', '=', $this->id));
        return ClienteCnae::getObjects( $criteria );
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
    
        $values = ClienteAtualizacao::where('atividade_principal_id', '=', $this->id)->getIndexedArray('cliente_id','{cliente->razao}');
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
    
        $values = ClienteAtualizacao::where('atividade_principal_id', '=', $this->id)->getIndexedArray('situacao_cadastral_id','{situacao_cadastral->descricao}');
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
    
        $values = ClienteAtualizacao::where('atividade_principal_id', '=', $this->id)->getIndexedArray('atividade_principal_id','{atividade_principal->id}');
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
    
        $values = ClienteAtualizacao::where('atividade_principal_id', '=', $this->id)->getIndexedArray('pais_id','{pais->id}');
        return implode(', ', $values);
    }

    public function set_cliente_cnae_cnae_to_string($cliente_cnae_cnae_to_string)
    {
        if(is_array($cliente_cnae_cnae_to_string))
        {
            $values = Cnae::where('id', 'in', $cliente_cnae_cnae_to_string)->getIndexedArray('id', 'id');
            $this->cliente_cnae_cnae_to_string = implode(', ', $values);
        }
        else
        {
            $this->cliente_cnae_cnae_to_string = $cliente_cnae_cnae_to_string;
        }

        $this->vdata['cliente_cnae_cnae_to_string'] = $this->cliente_cnae_cnae_to_string;
    }

    public function get_cliente_cnae_cnae_to_string()
    {
        if(!empty($this->cliente_cnae_cnae_to_string))
        {
            return $this->cliente_cnae_cnae_to_string;
        }
    
        $values = ClienteCnae::where('cnae_id', '=', $this->id)->getIndexedArray('cnae_id','{cnae->id}');
        return implode(', ', $values);
    }

    /**
     * Method onBeforeDelete
     */
    public function onBeforeDelete()
    {
            

        if(ClienteAtualizacao::where('atividade_principal_id', '=', $this->id)->first())
        {
            throw new Exception("Não é possível deletar este registro pois ele está sendo utilizado em outra parte do sistema");
        }
    
        if(ClienteCnae::where('cnae_id', '=', $this->id)->first())
        {
            throw new Exception("Não é possível deletar este registro pois ele está sendo utilizado em outra parte do sistema");
        }
    
    }

    
}

