<?php

class Estado extends TRecord
{
    const TABLENAME  = 'estado';
    const PRIMARYKEY = 'id';
    const IDPOLICY   =  'serial'; // {max, serial}

    const RO = '1';
    const AC = '2';
    const AM = '3';
    const RR = '4';
    const PA = '5';
    const AP = '6';
    const TO = '7';
    const MA = '8';
    const PI = '9';
    const CE = '10';
    const RN = '11';
    const PB = '12';
    const PE = '13';
    const AL = '14';
    const SE = '15';
    const MG = '17';
    const ES = '18';
    const RJ = '19';
    const SP = '20';
    const PR = '21';
    const SC = '22';
    const RS = '23';
    const MS = '24';
    const MT = '25';
    const GO = '26';
    const DF = '27';

    

    /**
     * Constructor method
     */
    public function __construct($id = NULL, $callObjectLoad = TRUE)
    {
        parent::__construct($id, $callObjectLoad);
        parent::addAttribute('cod_erp');
        parent::addAttribute('sigla');
        parent::addAttribute('descricao');
        parent::addAttribute('codigo_ibge');
            
    }

    /**
     * Method getOrcamentos
     */
    public function getOrcamentos()
    {
        $criteria = new TCriteria;
        $criteria->add(new TFilter('estado_id', '=', $this->id));
        return Orcamento::getObjects( $criteria );
    }
    /**
     * Method getMunicipios
     */
    public function getMunicipios()
    {
        $criteria = new TCriteria;
        $criteria->add(new TFilter('estado_id', '=', $this->id));
        return Municipio::getObjects( $criteria );
    }

    public function set_orcamento_cliente_to_string($orcamento_cliente_to_string)
    {
        if(is_array($orcamento_cliente_to_string))
        {
            $values = Cliente::where('id', 'in', $orcamento_cliente_to_string)->getIndexedArray('razao', 'razao');
            $this->orcamento_cliente_to_string = implode(', ', $values);
        }
        else
        {
            $this->orcamento_cliente_to_string = $orcamento_cliente_to_string;
        }

        $this->vdata['orcamento_cliente_to_string'] = $this->orcamento_cliente_to_string;
    }

    public function get_orcamento_cliente_to_string()
    {
        if(!empty($this->orcamento_cliente_to_string))
        {
            return $this->orcamento_cliente_to_string;
        }
    
        $values = Orcamento::where('estado_id', '=', $this->id)->getIndexedArray('cliente_id','{cliente->razao}');
        return implode(', ', $values);
    }

    public function set_orcamento_tabela_preco_to_string($orcamento_tabela_preco_to_string)
    {
        if(is_array($orcamento_tabela_preco_to_string))
        {
            $values = TabelaPreco::where('id', 'in', $orcamento_tabela_preco_to_string)->getIndexedArray('descricao', 'descricao');
            $this->orcamento_tabela_preco_to_string = implode(', ', $values);
        }
        else
        {
            $this->orcamento_tabela_preco_to_string = $orcamento_tabela_preco_to_string;
        }

        $this->vdata['orcamento_tabela_preco_to_string'] = $this->orcamento_tabela_preco_to_string;
    }

    public function get_orcamento_tabela_preco_to_string()
    {
        if(!empty($this->orcamento_tabela_preco_to_string))
        {
            return $this->orcamento_tabela_preco_to_string;
        }
    
        $values = Orcamento::where('estado_id', '=', $this->id)->getIndexedArray('tabela_preco_id','{tabela_preco->descricao}');
        return implode(', ', $values);
    }

    public function set_orcamento_condicao_pagamento_to_string($orcamento_condicao_pagamento_to_string)
    {
        if(is_array($orcamento_condicao_pagamento_to_string))
        {
            $values = CondicaoPagamento::where('id', 'in', $orcamento_condicao_pagamento_to_string)->getIndexedArray('descricao', 'descricao');
            $this->orcamento_condicao_pagamento_to_string = implode(', ', $values);
        }
        else
        {
            $this->orcamento_condicao_pagamento_to_string = $orcamento_condicao_pagamento_to_string;
        }

        $this->vdata['orcamento_condicao_pagamento_to_string'] = $this->orcamento_condicao_pagamento_to_string;
    }

    public function get_orcamento_condicao_pagamento_to_string()
    {
        if(!empty($this->orcamento_condicao_pagamento_to_string))
        {
            return $this->orcamento_condicao_pagamento_to_string;
        }
    
        $values = Orcamento::where('estado_id', '=', $this->id)->getIndexedArray('condicao_pagamento_id','{condicao_pagamento->descricao}');
        return implode(', ', $values);
    }

    public function set_orcamento_pedido_to_string($orcamento_pedido_to_string)
    {
        if(is_array($orcamento_pedido_to_string))
        {
            $values = Pedido::where('id', 'in', $orcamento_pedido_to_string)->getIndexedArray('id', 'id');
            $this->orcamento_pedido_to_string = implode(', ', $values);
        }
        else
        {
            $this->orcamento_pedido_to_string = $orcamento_pedido_to_string;
        }

        $this->vdata['orcamento_pedido_to_string'] = $this->orcamento_pedido_to_string;
    }

    public function get_orcamento_pedido_to_string()
    {
        if(!empty($this->orcamento_pedido_to_string))
        {
            return $this->orcamento_pedido_to_string;
        }
    
        $values = Orcamento::where('estado_id', '=', $this->id)->getIndexedArray('pedido_id','{pedido->id}');
        return implode(', ', $values);
    }

    public function set_orcamento_vendedor_to_string($orcamento_vendedor_to_string)
    {
        if(is_array($orcamento_vendedor_to_string))
        {
            $values = Vendedor::where('id', 'in', $orcamento_vendedor_to_string)->getIndexedArray('nome', 'nome');
            $this->orcamento_vendedor_to_string = implode(', ', $values);
        }
        else
        {
            $this->orcamento_vendedor_to_string = $orcamento_vendedor_to_string;
        }

        $this->vdata['orcamento_vendedor_to_string'] = $this->orcamento_vendedor_to_string;
    }

    public function get_orcamento_vendedor_to_string()
    {
        if(!empty($this->orcamento_vendedor_to_string))
        {
            return $this->orcamento_vendedor_to_string;
        }
    
        $values = Orcamento::where('estado_id', '=', $this->id)->getIndexedArray('vendedor_id','{vendedor->nome}');
        return implode(', ', $values);
    }

    public function set_orcamento_estado_to_string($orcamento_estado_to_string)
    {
        if(is_array($orcamento_estado_to_string))
        {
            $values = Estado::where('id', 'in', $orcamento_estado_to_string)->getIndexedArray('sigla', 'sigla');
            $this->orcamento_estado_to_string = implode(', ', $values);
        }
        else
        {
            $this->orcamento_estado_to_string = $orcamento_estado_to_string;
        }

        $this->vdata['orcamento_estado_to_string'] = $this->orcamento_estado_to_string;
    }

    public function get_orcamento_estado_to_string()
    {
        if(!empty($this->orcamento_estado_to_string))
        {
            return $this->orcamento_estado_to_string;
        }
    
        $values = Orcamento::where('estado_id', '=', $this->id)->getIndexedArray('estado_id','{estado->sigla}');
        return implode(', ', $values);
    }

    public function set_orcamento_municipio_to_string($orcamento_municipio_to_string)
    {
        if(is_array($orcamento_municipio_to_string))
        {
            $values = Municipio::where('id', 'in', $orcamento_municipio_to_string)->getIndexedArray('cod_erp', 'cod_erp');
            $this->orcamento_municipio_to_string = implode(', ', $values);
        }
        else
        {
            $this->orcamento_municipio_to_string = $orcamento_municipio_to_string;
        }

        $this->vdata['orcamento_municipio_to_string'] = $this->orcamento_municipio_to_string;
    }

    public function get_orcamento_municipio_to_string()
    {
        if(!empty($this->orcamento_municipio_to_string))
        {
            return $this->orcamento_municipio_to_string;
        }
    
        $values = Orcamento::where('estado_id', '=', $this->id)->getIndexedArray('municipio_id','{municipio->cod_erp}');
        return implode(', ', $values);
    }

    public function set_orcamento_orcamento_estado_to_string($orcamento_orcamento_estado_to_string)
    {
        if(is_array($orcamento_orcamento_estado_to_string))
        {
            $values = OrcamentoEstado::where('id', 'in', $orcamento_orcamento_estado_to_string)->getIndexedArray('descricao', 'descricao');
            $this->orcamento_orcamento_estado_to_string = implode(', ', $values);
        }
        else
        {
            $this->orcamento_orcamento_estado_to_string = $orcamento_orcamento_estado_to_string;
        }

        $this->vdata['orcamento_orcamento_estado_to_string'] = $this->orcamento_orcamento_estado_to_string;
    }

    public function get_orcamento_orcamento_estado_to_string()
    {
        if(!empty($this->orcamento_orcamento_estado_to_string))
        {
            return $this->orcamento_orcamento_estado_to_string;
        }
    
        $values = Orcamento::where('estado_id', '=', $this->id)->getIndexedArray('orcamento_estado_id','{orcamento_estado->descricao}');
        return implode(', ', $values);
    }

    public function set_municipio_estado_to_string($municipio_estado_to_string)
    {
        if(is_array($municipio_estado_to_string))
        {
            $values = Estado::where('id', 'in', $municipio_estado_to_string)->getIndexedArray('sigla', 'sigla');
            $this->municipio_estado_to_string = implode(', ', $values);
        }
        else
        {
            $this->municipio_estado_to_string = $municipio_estado_to_string;
        }

        $this->vdata['municipio_estado_to_string'] = $this->municipio_estado_to_string;
    }

    public function get_municipio_estado_to_string()
    {
        if(!empty($this->municipio_estado_to_string))
        {
            return $this->municipio_estado_to_string;
        }
    
        $values = Municipio::where('estado_id', '=', $this->id)->getIndexedArray('estado_id','{estado->sigla}');
        return implode(', ', $values);
    }

    
}

