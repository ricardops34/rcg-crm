<?php

class AtendimentoTipo extends TRecord
{
    const TABLENAME  = 'atendimento_tipo';
    const PRIMARYKEY = 'id';
    const IDPOLICY   =  'serial'; // {max, serial}

    const CREATEDAT  = 'dt_inclusao';
    const UPDATEDAT  = 'dt_alteracao';

    const VENDA = '1';
    const CADASTRO = '2';
    const PROSPECT = '3';
    const COBRANCA = '4';
    const REATIVACAO = '5';
    const CAMPANHA = '6';
    const RETORNO = '7';

    

    /**
     * Constructor method
     */
    public function __construct($id = NULL, $callObjectLoad = TRUE)
    {
        parent::__construct($id, $callObjectLoad);
        parent::addAttribute('cod_erp');
        parent::addAttribute('descricao');
        parent::addAttribute('cor');
        parent::addAttribute('icone');
        parent::addAttribute('dt_alteracao');
        parent::addAttribute('dt_inclusao');
        parent::addAttribute('retorno');
        parent::addAttribute('editar');
        parent::addAttribute('excluir');
        parent::addAttribute('atendimento');
        parent::addAttribute('venda');
        parent::addAttribute('cadastro');
        parent::addAttribute('cobranca');
            
    }

    /**
     * Method getAtendimentos
     */
    public function getAtendimentos()
    {
        $criteria = new TCriteria;
        $criteria->add(new TFilter('atendimento_tipo_id', '=', $this->id));
        return Atendimento::getObjects( $criteria );
    }
    /**
     * Method getNegociacaos
     */
    public function getNegociacaos()
    {
        $criteria = new TCriteria;
        $criteria->add(new TFilter('atendimento_tipo_id', '=', $this->id));
        return Negociacao::getObjects( $criteria );
    }

    public function set_atendimento_atendimento_tipo_to_string($atendimento_atendimento_tipo_to_string)
    {
        if(is_array($atendimento_atendimento_tipo_to_string))
        {
            $values = AtendimentoTipo::where('id', 'in', $atendimento_atendimento_tipo_to_string)->getIndexedArray('id', 'id');
            $this->atendimento_atendimento_tipo_to_string = implode(', ', $values);
        }
        else
        {
            $this->atendimento_atendimento_tipo_to_string = $atendimento_atendimento_tipo_to_string;
        }

        $this->vdata['atendimento_atendimento_tipo_to_string'] = $this->atendimento_atendimento_tipo_to_string;
    }

    public function get_atendimento_atendimento_tipo_to_string()
    {
        if(!empty($this->atendimento_atendimento_tipo_to_string))
        {
            return $this->atendimento_atendimento_tipo_to_string;
        }
    
        $values = Atendimento::where('atendimento_tipo_id', '=', $this->id)->getIndexedArray('atendimento_tipo_id','{atendimento_tipo->id}');
        return implode(', ', $values);
    }

    public function set_atendimento_vendedor_to_string($atendimento_vendedor_to_string)
    {
        if(is_array($atendimento_vendedor_to_string))
        {
            $values = Vendedor::where('id', 'in', $atendimento_vendedor_to_string)->getIndexedArray('nome', 'nome');
            $this->atendimento_vendedor_to_string = implode(', ', $values);
        }
        else
        {
            $this->atendimento_vendedor_to_string = $atendimento_vendedor_to_string;
        }

        $this->vdata['atendimento_vendedor_to_string'] = $this->atendimento_vendedor_to_string;
    }

    public function get_atendimento_vendedor_to_string()
    {
        if(!empty($this->atendimento_vendedor_to_string))
        {
            return $this->atendimento_vendedor_to_string;
        }
    
        $values = Atendimento::where('atendimento_tipo_id', '=', $this->id)->getIndexedArray('vendedor_id','{vendedor->nome}');
        return implode(', ', $values);
    }

    public function set_atendimento_cliente_to_string($atendimento_cliente_to_string)
    {
        if(is_array($atendimento_cliente_to_string))
        {
            $values = Cliente::where('id', 'in', $atendimento_cliente_to_string)->getIndexedArray('razao', 'razao');
            $this->atendimento_cliente_to_string = implode(', ', $values);
        }
        else
        {
            $this->atendimento_cliente_to_string = $atendimento_cliente_to_string;
        }

        $this->vdata['atendimento_cliente_to_string'] = $this->atendimento_cliente_to_string;
    }

    public function get_atendimento_cliente_to_string()
    {
        if(!empty($this->atendimento_cliente_to_string))
        {
            return $this->atendimento_cliente_to_string;
        }
    
        $values = Atendimento::where('atendimento_tipo_id', '=', $this->id)->getIndexedArray('cliente_id','{cliente->razao}');
        return implode(', ', $values);
    }

    public function set_negociacao_cliente_to_string($negociacao_cliente_to_string)
    {
        if(is_array($negociacao_cliente_to_string))
        {
            $values = Cliente::where('id', 'in', $negociacao_cliente_to_string)->getIndexedArray('razao', 'razao');
            $this->negociacao_cliente_to_string = implode(', ', $values);
        }
        else
        {
            $this->negociacao_cliente_to_string = $negociacao_cliente_to_string;
        }

        $this->vdata['negociacao_cliente_to_string'] = $this->negociacao_cliente_to_string;
    }

    public function get_negociacao_cliente_to_string()
    {
        if(!empty($this->negociacao_cliente_to_string))
        {
            return $this->negociacao_cliente_to_string;
        }
    
        $values = Negociacao::where('atendimento_tipo_id', '=', $this->id)->getIndexedArray('cliente_id','{cliente->razao}');
        return implode(', ', $values);
    }

    public function set_negociacao_vendedor_to_string($negociacao_vendedor_to_string)
    {
        if(is_array($negociacao_vendedor_to_string))
        {
            $values = Vendedor::where('id', 'in', $negociacao_vendedor_to_string)->getIndexedArray('nome', 'nome');
            $this->negociacao_vendedor_to_string = implode(', ', $values);
        }
        else
        {
            $this->negociacao_vendedor_to_string = $negociacao_vendedor_to_string;
        }

        $this->vdata['negociacao_vendedor_to_string'] = $this->negociacao_vendedor_to_string;
    }

    public function get_negociacao_vendedor_to_string()
    {
        if(!empty($this->negociacao_vendedor_to_string))
        {
            return $this->negociacao_vendedor_to_string;
        }
    
        $values = Negociacao::where('atendimento_tipo_id', '=', $this->id)->getIndexedArray('vendedor_id','{vendedor->nome}');
        return implode(', ', $values);
    }

    public function set_negociacao_atendimento_tipo_to_string($negociacao_atendimento_tipo_to_string)
    {
        if(is_array($negociacao_atendimento_tipo_to_string))
        {
            $values = AtendimentoTipo::where('id', 'in', $negociacao_atendimento_tipo_to_string)->getIndexedArray('id', 'id');
            $this->negociacao_atendimento_tipo_to_string = implode(', ', $values);
        }
        else
        {
            $this->negociacao_atendimento_tipo_to_string = $negociacao_atendimento_tipo_to_string;
        }

        $this->vdata['negociacao_atendimento_tipo_to_string'] = $this->negociacao_atendimento_tipo_to_string;
    }

    public function get_negociacao_atendimento_tipo_to_string()
    {
        if(!empty($this->negociacao_atendimento_tipo_to_string))
        {
            return $this->negociacao_atendimento_tipo_to_string;
        }
    
        $values = Negociacao::where('atendimento_tipo_id', '=', $this->id)->getIndexedArray('atendimento_tipo_id','{atendimento_tipo->id}');
        return implode(', ', $values);
    }

    public function set_negociacao_atendimento_to_string($negociacao_atendimento_to_string)
    {
        if(is_array($negociacao_atendimento_to_string))
        {
            $values = Atendimento::where('id', 'in', $negociacao_atendimento_to_string)->getIndexedArray('id', 'id');
            $this->negociacao_atendimento_to_string = implode(', ', $values);
        }
        else
        {
            $this->negociacao_atendimento_to_string = $negociacao_atendimento_to_string;
        }

        $this->vdata['negociacao_atendimento_to_string'] = $this->negociacao_atendimento_to_string;
    }

    public function get_negociacao_atendimento_to_string()
    {
        if(!empty($this->negociacao_atendimento_to_string))
        {
            return $this->negociacao_atendimento_to_string;
        }
    
        $values = Negociacao::where('atendimento_tipo_id', '=', $this->id)->getIndexedArray('atendimento_id','{atendimento->id}');
        return implode(', ', $values);
    }

    /**
     * Method onBeforeDelete
     */
    public function onBeforeDelete()
    {
            

        if(Atendimento::where('atendimento_tipo_id', '=', $this->id)->first())
        {
            throw new Exception("Não é possível deletar este registro pois ele está sendo utilizado em outra parte do sistema");
        }
    
        if(Negociacao::where('atendimento_tipo_id', '=', $this->id)->first())
        {
            throw new Exception("Não é possível deletar este registro pois ele está sendo utilizado em outra parte do sistema");
        }
    
    }

    
}

