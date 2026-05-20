<?php

class Fornecedor extends TRecord
{
    const TABLENAME  = 'fornecedor';
    const PRIMARYKEY = 'id';
    const IDPOLICY   =  'serial'; // {max, serial}

    private Filial $filial;

    

    /**
     * Constructor method
     */
    public function __construct($id = NULL, $callObjectLoad = TRUE)
    {
        parent::__construct($id, $callObjectLoad);
        parent::addAttribute('filial_id');
        parent::addAttribute('cod_erp');
        parent::addAttribute('status');
        parent::addAttribute('razao');
        parent::addAttribute('tipo');
        parent::addAttribute('fantasia');
        parent::addAttribute('endereco');
        parent::addAttribute('complemento');
        parent::addAttribute('bairro');
        parent::addAttribute('uf');
        parent::addAttribute('municipio');
        parent::addAttribute('municipio_id');
        parent::addAttribute('cep');
        parent::addAttribute('telefone1');
        parent::addAttribute('telefone2');
        parent::addAttribute('fax');
        parent::addAttribute('celular');
        parent::addAttribute('contato');
        parent::addAttribute('cnpj_cpf');
        parent::addAttribute('ie');
        parent::addAttribute('im');
        parent::addAttribute('contribuinte');
        parent::addAttribute('rg');
        parent::addAttribute('nascimento');
        parent::addAttribute('email');
        parent::addAttribute('condicao_pagamento_id');
        parent::addAttribute('primeira_compra');
        parent::addAttribute('ultima_visita');
        parent::addAttribute('sinc');
        parent::addAttribute('data_cadastro');
        parent::addAttribute('dt_alteracao');
        parent::addAttribute('dt_inclusao');
        parent::addAttribute('destaca_ie');
        parent::addAttribute('seguimento_id');
        parent::addAttribute('site');
        parent::addAttribute('obs');
        parent::addAttribute('filial_cadastro');
        parent::addAttribute('latitude');
        parent::addAttribute('log_int');
        parent::addAttribute('longitude');
        parent::addAttribute('system_unit_id');
            
    }

    /**
     * Method set_filial
     * Sample of usage: $var->filial = $object;
     * @param $object Instance of Filial
     */
    public function set_filial(Filial $object)
    {
        $this->filial = $object;
        $this->filial_id = $object->id;
    }

    /**
     * Method get_filial
     * Sample of usage: $var->filial->attribute;
     * @returns Filial instance
     */
    public function get_filial()
    {
    
        // loads the associated object
        if (empty($this->filial))
            $this->filial = new Filial($this->filial_id);
    
        // returns the associated object
        return $this->filial;
    }

    /**
     * Method getNotaSaidas
     */
    public function getNotaSaidas()
    {
        $criteria = new TCriteria;
        $criteria->add(new TFilter('fornecedor_id', '=', $this->id));
        return NotaSaida::getObjects( $criteria );
    }
    /**
     * Method getNotaEntradas
     */
    public function getNotaEntradas()
    {
        $criteria = new TCriteria;
        $criteria->add(new TFilter('fornecedor_id', '=', $this->id));
        return NotaEntrada::getObjects( $criteria );
    }

    public function set_nota_saida_filial_to_string($nota_saida_filial_to_string)
    {
        if(is_array($nota_saida_filial_to_string))
        {
            $values = Filial::where('id', 'in', $nota_saida_filial_to_string)->getIndexedArray('apelido', 'apelido');
            $this->nota_saida_filial_to_string = implode(', ', $values);
        }
        else
        {
            $this->nota_saida_filial_to_string = $nota_saida_filial_to_string;
        }

        $this->vdata['nota_saida_filial_to_string'] = $this->nota_saida_filial_to_string;
    }

    public function get_nota_saida_filial_to_string()
    {
        if(!empty($this->nota_saida_filial_to_string))
        {
            return $this->nota_saida_filial_to_string;
        }
    
        $values = NotaSaida::where('fornecedor_id', '=', $this->id)->getIndexedArray('filial_id','{filial->apelido}');
        return implode(', ', $values);
    }

    public function set_nota_saida_cliente_to_string($nota_saida_cliente_to_string)
    {
        if(is_array($nota_saida_cliente_to_string))
        {
            $values = Cliente::where('id', 'in', $nota_saida_cliente_to_string)->getIndexedArray('razao', 'razao');
            $this->nota_saida_cliente_to_string = implode(', ', $values);
        }
        else
        {
            $this->nota_saida_cliente_to_string = $nota_saida_cliente_to_string;
        }

        $this->vdata['nota_saida_cliente_to_string'] = $this->nota_saida_cliente_to_string;
    }

    public function get_nota_saida_cliente_to_string()
    {
        if(!empty($this->nota_saida_cliente_to_string))
        {
            return $this->nota_saida_cliente_to_string;
        }
    
        $values = NotaSaida::where('fornecedor_id', '=', $this->id)->getIndexedArray('cliente_id','{cliente->razao}');
        return implode(', ', $values);
    }

    public function set_nota_saida_fornecedor_to_string($nota_saida_fornecedor_to_string)
    {
        if(is_array($nota_saida_fornecedor_to_string))
        {
            $values = Fornecedor::where('id', 'in', $nota_saida_fornecedor_to_string)->getIndexedArray('id', 'id');
            $this->nota_saida_fornecedor_to_string = implode(', ', $values);
        }
        else
        {
            $this->nota_saida_fornecedor_to_string = $nota_saida_fornecedor_to_string;
        }

        $this->vdata['nota_saida_fornecedor_to_string'] = $this->nota_saida_fornecedor_to_string;
    }

    public function get_nota_saida_fornecedor_to_string()
    {
        if(!empty($this->nota_saida_fornecedor_to_string))
        {
            return $this->nota_saida_fornecedor_to_string;
        }
    
        $values = NotaSaida::where('fornecedor_id', '=', $this->id)->getIndexedArray('fornecedor_id','{fornecedor->id}');
        return implode(', ', $values);
    }

    public function set_nota_saida_vendedor1_to_string($nota_saida_vendedor1_to_string)
    {
        if(is_array($nota_saida_vendedor1_to_string))
        {
            $values = Vendedor::where('id', 'in', $nota_saida_vendedor1_to_string)->getIndexedArray('nome', 'nome');
            $this->nota_saida_vendedor1_to_string = implode(', ', $values);
        }
        else
        {
            $this->nota_saida_vendedor1_to_string = $nota_saida_vendedor1_to_string;
        }

        $this->vdata['nota_saida_vendedor1_to_string'] = $this->nota_saida_vendedor1_to_string;
    }

    public function get_nota_saida_vendedor1_to_string()
    {
        if(!empty($this->nota_saida_vendedor1_to_string))
        {
            return $this->nota_saida_vendedor1_to_string;
        }
    
        $values = NotaSaida::where('fornecedor_id', '=', $this->id)->getIndexedArray('vendedor1_id','{vendedor1->nome}');
        return implode(', ', $values);
    }

    public function set_nota_saida_vendedor2_to_string($nota_saida_vendedor2_to_string)
    {
        if(is_array($nota_saida_vendedor2_to_string))
        {
            $values = Vendedor::where('id', 'in', $nota_saida_vendedor2_to_string)->getIndexedArray('nome', 'nome');
            $this->nota_saida_vendedor2_to_string = implode(', ', $values);
        }
        else
        {
            $this->nota_saida_vendedor2_to_string = $nota_saida_vendedor2_to_string;
        }

        $this->vdata['nota_saida_vendedor2_to_string'] = $this->nota_saida_vendedor2_to_string;
    }

    public function get_nota_saida_vendedor2_to_string()
    {
        if(!empty($this->nota_saida_vendedor2_to_string))
        {
            return $this->nota_saida_vendedor2_to_string;
        }
    
        $values = NotaSaida::where('fornecedor_id', '=', $this->id)->getIndexedArray('vendedor2_id','{vendedor2->nome}');
        return implode(', ', $values);
    }

    public function set_nota_entrada_cliente_to_string($nota_entrada_cliente_to_string)
    {
        if(is_array($nota_entrada_cliente_to_string))
        {
            $values = Cliente::where('id', 'in', $nota_entrada_cliente_to_string)->getIndexedArray('razao', 'razao');
            $this->nota_entrada_cliente_to_string = implode(', ', $values);
        }
        else
        {
            $this->nota_entrada_cliente_to_string = $nota_entrada_cliente_to_string;
        }

        $this->vdata['nota_entrada_cliente_to_string'] = $this->nota_entrada_cliente_to_string;
    }

    public function get_nota_entrada_cliente_to_string()
    {
        if(!empty($this->nota_entrada_cliente_to_string))
        {
            return $this->nota_entrada_cliente_to_string;
        }
    
        $values = NotaEntrada::where('fornecedor_id', '=', $this->id)->getIndexedArray('cliente_id','{cliente->razao}');
        return implode(', ', $values);
    }

    public function set_nota_entrada_fornecedor_to_string($nota_entrada_fornecedor_to_string)
    {
        if(is_array($nota_entrada_fornecedor_to_string))
        {
            $values = Fornecedor::where('id', 'in', $nota_entrada_fornecedor_to_string)->getIndexedArray('id', 'id');
            $this->nota_entrada_fornecedor_to_string = implode(', ', $values);
        }
        else
        {
            $this->nota_entrada_fornecedor_to_string = $nota_entrada_fornecedor_to_string;
        }

        $this->vdata['nota_entrada_fornecedor_to_string'] = $this->nota_entrada_fornecedor_to_string;
    }

    public function get_nota_entrada_fornecedor_to_string()
    {
        if(!empty($this->nota_entrada_fornecedor_to_string))
        {
            return $this->nota_entrada_fornecedor_to_string;
        }
    
        $values = NotaEntrada::where('fornecedor_id', '=', $this->id)->getIndexedArray('fornecedor_id','{fornecedor->id}');
        return implode(', ', $values);
    }

    public function set_nota_entrada_vendedor1_to_string($nota_entrada_vendedor1_to_string)
    {
        if(is_array($nota_entrada_vendedor1_to_string))
        {
            $values = Vendedor::where('id', 'in', $nota_entrada_vendedor1_to_string)->getIndexedArray('nome', 'nome');
            $this->nota_entrada_vendedor1_to_string = implode(', ', $values);
        }
        else
        {
            $this->nota_entrada_vendedor1_to_string = $nota_entrada_vendedor1_to_string;
        }

        $this->vdata['nota_entrada_vendedor1_to_string'] = $this->nota_entrada_vendedor1_to_string;
    }

    public function get_nota_entrada_vendedor1_to_string()
    {
        if(!empty($this->nota_entrada_vendedor1_to_string))
        {
            return $this->nota_entrada_vendedor1_to_string;
        }
    
        $values = NotaEntrada::where('fornecedor_id', '=', $this->id)->getIndexedArray('vendedor1_id','{vendedor1->nome}');
        return implode(', ', $values);
    }

    
}

