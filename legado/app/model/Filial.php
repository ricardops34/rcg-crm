<?php

class Filial extends TRecord
{
    const TABLENAME  = 'filial';
    const PRIMARYKEY = 'id';
    const IDPOLICY   =  'serial'; // {max, serial}

    const CREATEDAT  = 'dt_inclusao';
    const UPDATEDAT  = 'dt_alteracao';

    private SystemUnit $system_unit;

    

    use SystemChangeLogTrait;
    /**
     * Constructor method
     */
    public function __construct($id = NULL, $callObjectLoad = TRUE)
    {
        parent::__construct($id, $callObjectLoad);
        parent::addAttribute('cod_emp');
        parent::addAttribute('cod_erp');
        parent::addAttribute('system_unit_id');
        parent::addAttribute('apelido');
        parent::addAttribute('matriz');
        parent::addAttribute('nome');
        parent::addAttribute('fantasia');
        parent::addAttribute('tipo');
        parent::addAttribute('cnpj');
        parent::addAttribute('cpf');
        parent::addAttribute('cep');
        parent::addAttribute('endereco');
        parent::addAttribute('complemento');
        parent::addAttribute('bairro');
        parent::addAttribute('municipio');
        parent::addAttribute('uf');
        parent::addAttribute('email');
        parent::addAttribute('status');
        parent::addAttribute('dt_alteracao');
        parent::addAttribute('dt_inclusao');
            
    }

    /**
     * Method set_system_unit
     * Sample of usage: $var->system_unit = $object;
     * @param $object Instance of SystemUnit
     */
    public function set_system_unit(SystemUnit $object)
    {
        $this->system_unit = $object;
        $this->system_unit_id = $object->id;
    }

    /**
     * Method get_system_unit
     * Sample of usage: $var->system_unit->attribute;
     * @returns SystemUnit instance
     */
    public function get_system_unit()
    {
        try{
        TTransaction::openFake('permission');
        // loads the associated object
        if (empty($this->system_unit))
            $this->system_unit = new SystemUnit($this->system_unit_id);
        TTransaction::close();
        }catch(Exception $e){
            TTransaction::close();
        }
        // returns the associated object
        return $this->system_unit;
    }

    /**
     * Method getClientes
     */
    public function getClientes()
    {
        $criteria = new TCriteria;
        $criteria->add(new TFilter('filial_id', '=', $this->id));
        return Cliente::getObjects( $criteria );
    }
    /**
     * Method getParametros
     */
    public function getParametros()
    {
        $criteria = new TCriteria;
        $criteria->add(new TFilter('filial_id', '=', $this->id));
        return Parametro::getObjects( $criteria );
    }
    /**
     * Method getCategorias
     */
    public function getCategorias()
    {
        $criteria = new TCriteria;
        $criteria->add(new TFilter('filial_id', '=', $this->id));
        return Categoria::getObjects( $criteria );
    }
    /**
     * Method getVendedors
     */
    public function getVendedors()
    {
        $criteria = new TCriteria;
        $criteria->add(new TFilter('filial_id', '=', $this->id));
        return Vendedor::getObjects( $criteria );
    }
    /**
     * Method getProdutos
     */
    public function getProdutos()
    {
        $criteria = new TCriteria;
        $criteria->add(new TFilter('filial_id', '=', $this->id));
        return Produto::getObjects( $criteria );
    }
    /**
     * Method getNotaSaidas
     */
    public function getNotaSaidas()
    {
        $criteria = new TCriteria;
        $criteria->add(new TFilter('filial_id', '=', $this->id));
        return NotaSaida::getObjects( $criteria );
    }
    /**
     * Method getEstoques
     */
    public function getEstoques()
    {
        $criteria = new TCriteria;
        $criteria->add(new TFilter('filial_id', '=', $this->id));
        return Estoque::getObjects( $criteria );
    }
    /**
     * Method getFornecedors
     */
    public function getFornecedors()
    {
        $criteria = new TCriteria;
        $criteria->add(new TFilter('filial_id', '=', $this->id));
        return Fornecedor::getObjects( $criteria );
    }
    /**
     * Method getTituloRecebers
     */
    public function getTituloRecebers()
    {
        $criteria = new TCriteria;
        $criteria->add(new TFilter('filial_id', '=', $this->id));
        return TituloReceber::getObjects( $criteria );
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
    
        $values = Cliente::where('filial_id', '=', $this->id)->getIndexedArray('filial_id','{filial->apelido}');
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
    
        $values = Cliente::where('filial_id', '=', $this->id)->getIndexedArray('tipo_cliente_id','{tipo_cliente->descricao}');
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
    
        $values = Cliente::where('filial_id', '=', $this->id)->getIndexedArray('municipio_id','{municipio->cod_erp}');
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
    
        $values = Cliente::where('filial_id', '=', $this->id)->getIndexedArray('vendedor_id','{vendedor->nome}');
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
    
        $values = Cliente::where('filial_id', '=', $this->id)->getIndexedArray('condicao_pagamento_id','{condicao_pagamento->descricao}');
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
    
        $values = Cliente::where('filial_id', '=', $this->id)->getIndexedArray('tabela_preco_id','{tabela_preco->descricao}');
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
    
        $values = Cliente::where('filial_id', '=', $this->id)->getIndexedArray('seguimento_id','{seguimento->descricao}');
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
    
        $values = Cliente::where('filial_id', '=', $this->id)->getIndexedArray('motivo_bloqueio_id','{motivo_bloqueio->id}');
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
    
        $values = Cliente::where('filial_id', '=', $this->id)->getIndexedArray('regiao_cliente_id','{regiao_cliente->id}');
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
    
        $values = Cliente::where('filial_id', '=', $this->id)->getIndexedArray('situacao_cadastral_id','{situacao_cadastral->descricao}');
        return implode(', ', $values);
    }

    public function set_parametro_filial_to_string($parametro_filial_to_string)
    {
        if(is_array($parametro_filial_to_string))
        {
            $values = Filial::where('id', 'in', $parametro_filial_to_string)->getIndexedArray('apelido', 'apelido');
            $this->parametro_filial_to_string = implode(', ', $values);
        }
        else
        {
            $this->parametro_filial_to_string = $parametro_filial_to_string;
        }

        $this->vdata['parametro_filial_to_string'] = $this->parametro_filial_to_string;
    }

    public function get_parametro_filial_to_string()
    {
        if(!empty($this->parametro_filial_to_string))
        {
            return $this->parametro_filial_to_string;
        }
    
        $values = Parametro::where('filial_id', '=', $this->id)->getIndexedArray('filial_id','{filial->apelido}');
        return implode(', ', $values);
    }

    public function set_categoria_filial_to_string($categoria_filial_to_string)
    {
        if(is_array($categoria_filial_to_string))
        {
            $values = Filial::where('id', 'in', $categoria_filial_to_string)->getIndexedArray('apelido', 'apelido');
            $this->categoria_filial_to_string = implode(', ', $values);
        }
        else
        {
            $this->categoria_filial_to_string = $categoria_filial_to_string;
        }

        $this->vdata['categoria_filial_to_string'] = $this->categoria_filial_to_string;
    }

    public function get_categoria_filial_to_string()
    {
        if(!empty($this->categoria_filial_to_string))
        {
            return $this->categoria_filial_to_string;
        }
    
        $values = Categoria::where('filial_id', '=', $this->id)->getIndexedArray('filial_id','{filial->apelido}');
        return implode(', ', $values);
    }

    public function set_vendedor_filial_to_string($vendedor_filial_to_string)
    {
        if(is_array($vendedor_filial_to_string))
        {
            $values = Filial::where('id', 'in', $vendedor_filial_to_string)->getIndexedArray('apelido', 'apelido');
            $this->vendedor_filial_to_string = implode(', ', $values);
        }
        else
        {
            $this->vendedor_filial_to_string = $vendedor_filial_to_string;
        }

        $this->vdata['vendedor_filial_to_string'] = $this->vendedor_filial_to_string;
    }

    public function get_vendedor_filial_to_string()
    {
        if(!empty($this->vendedor_filial_to_string))
        {
            return $this->vendedor_filial_to_string;
        }
    
        $values = Vendedor::where('filial_id', '=', $this->id)->getIndexedArray('filial_id','{filial->apelido}');
        return implode(', ', $values);
    }

    public function set_produto_filial_to_string($produto_filial_to_string)
    {
        if(is_array($produto_filial_to_string))
        {
            $values = Filial::where('id', 'in', $produto_filial_to_string)->getIndexedArray('apelido', 'apelido');
            $this->produto_filial_to_string = implode(', ', $values);
        }
        else
        {
            $this->produto_filial_to_string = $produto_filial_to_string;
        }

        $this->vdata['produto_filial_to_string'] = $this->produto_filial_to_string;
    }

    public function get_produto_filial_to_string()
    {
        if(!empty($this->produto_filial_to_string))
        {
            return $this->produto_filial_to_string;
        }
    
        $values = Produto::where('filial_id', '=', $this->id)->getIndexedArray('filial_id','{filial->apelido}');
        return implode(', ', $values);
    }

    public function set_produto_categoria_to_string($produto_categoria_to_string)
    {
        if(is_array($produto_categoria_to_string))
        {
            $values = Categoria::where('id', 'in', $produto_categoria_to_string)->getIndexedArray('descricao', 'descricao');
            $this->produto_categoria_to_string = implode(', ', $values);
        }
        else
        {
            $this->produto_categoria_to_string = $produto_categoria_to_string;
        }

        $this->vdata['produto_categoria_to_string'] = $this->produto_categoria_to_string;
    }

    public function get_produto_categoria_to_string()
    {
        if(!empty($this->produto_categoria_to_string))
        {
            return $this->produto_categoria_to_string;
        }
    
        $values = Produto::where('filial_id', '=', $this->id)->getIndexedArray('categoria_id','{categoria->descricao}');
        return implode(', ', $values);
    }

    public function set_produto_sub_categoria_to_string($produto_sub_categoria_to_string)
    {
        if(is_array($produto_sub_categoria_to_string))
        {
            $values = SubCategoria::where('id', 'in', $produto_sub_categoria_to_string)->getIndexedArray('descricao', 'descricao');
            $this->produto_sub_categoria_to_string = implode(', ', $values);
        }
        else
        {
            $this->produto_sub_categoria_to_string = $produto_sub_categoria_to_string;
        }

        $this->vdata['produto_sub_categoria_to_string'] = $this->produto_sub_categoria_to_string;
    }

    public function get_produto_sub_categoria_to_string()
    {
        if(!empty($this->produto_sub_categoria_to_string))
        {
            return $this->produto_sub_categoria_to_string;
        }
    
        $values = Produto::where('filial_id', '=', $this->id)->getIndexedArray('sub_categoria_id','{sub_categoria->descricao}');
        return implode(', ', $values);
    }

    public function set_produto_fabricante_to_string($produto_fabricante_to_string)
    {
        if(is_array($produto_fabricante_to_string))
        {
            $values = Fabricante::where('id', 'in', $produto_fabricante_to_string)->getIndexedArray('descricao', 'descricao');
            $this->produto_fabricante_to_string = implode(', ', $values);
        }
        else
        {
            $this->produto_fabricante_to_string = $produto_fabricante_to_string;
        }

        $this->vdata['produto_fabricante_to_string'] = $this->produto_fabricante_to_string;
    }

    public function get_produto_fabricante_to_string()
    {
        if(!empty($this->produto_fabricante_to_string))
        {
            return $this->produto_fabricante_to_string;
        }
    
        $values = Produto::where('filial_id', '=', $this->id)->getIndexedArray('fabricante_id','{fabricante->descricao}');
        return implode(', ', $values);
    }

    public function set_produto_armazem_to_string($produto_armazem_to_string)
    {
        if(is_array($produto_armazem_to_string))
        {
            $values = Armazem::where('id', 'in', $produto_armazem_to_string)->getIndexedArray('descricao', 'descricao');
            $this->produto_armazem_to_string = implode(', ', $values);
        }
        else
        {
            $this->produto_armazem_to_string = $produto_armazem_to_string;
        }

        $this->vdata['produto_armazem_to_string'] = $this->produto_armazem_to_string;
    }

    public function get_produto_armazem_to_string()
    {
        if(!empty($this->produto_armazem_to_string))
        {
            return $this->produto_armazem_to_string;
        }
    
        $values = Produto::where('filial_id', '=', $this->id)->getIndexedArray('armazem_id','{armazem->descricao}');
        return implode(', ', $values);
    }

    public function set_produto_te_to_string($produto_te_to_string)
    {
        if(is_array($produto_te_to_string))
        {
            $values = TipoEntradaSaida::where('id', 'in', $produto_te_to_string)->getIndexedArray('id', 'id');
            $this->produto_te_to_string = implode(', ', $values);
        }
        else
        {
            $this->produto_te_to_string = $produto_te_to_string;
        }

        $this->vdata['produto_te_to_string'] = $this->produto_te_to_string;
    }

    public function get_produto_te_to_string()
    {
        if(!empty($this->produto_te_to_string))
        {
            return $this->produto_te_to_string;
        }
    
        $values = Produto::where('filial_id', '=', $this->id)->getIndexedArray('te_id','{te->id}');
        return implode(', ', $values);
    }

    public function set_produto_ts_to_string($produto_ts_to_string)
    {
        if(is_array($produto_ts_to_string))
        {
            $values = TipoEntradaSaida::where('id', 'in', $produto_ts_to_string)->getIndexedArray('id', 'id');
            $this->produto_ts_to_string = implode(', ', $values);
        }
        else
        {
            $this->produto_ts_to_string = $produto_ts_to_string;
        }

        $this->vdata['produto_ts_to_string'] = $this->produto_ts_to_string;
    }

    public function get_produto_ts_to_string()
    {
        if(!empty($this->produto_ts_to_string))
        {
            return $this->produto_ts_to_string;
        }
    
        $values = Produto::where('filial_id', '=', $this->id)->getIndexedArray('ts_id','{ts->id}');
        return implode(', ', $values);
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
    
        $values = NotaSaida::where('filial_id', '=', $this->id)->getIndexedArray('filial_id','{filial->apelido}');
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
    
        $values = NotaSaida::where('filial_id', '=', $this->id)->getIndexedArray('cliente_id','{cliente->razao}');
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
    
        $values = NotaSaida::where('filial_id', '=', $this->id)->getIndexedArray('fornecedor_id','{fornecedor->id}');
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
    
        $values = NotaSaida::where('filial_id', '=', $this->id)->getIndexedArray('vendedor1_id','{vendedor1->nome}');
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
    
        $values = NotaSaida::where('filial_id', '=', $this->id)->getIndexedArray('vendedor2_id','{vendedor2->nome}');
        return implode(', ', $values);
    }

    public function set_estoque_filial_to_string($estoque_filial_to_string)
    {
        if(is_array($estoque_filial_to_string))
        {
            $values = Filial::where('id', 'in', $estoque_filial_to_string)->getIndexedArray('apelido', 'apelido');
            $this->estoque_filial_to_string = implode(', ', $values);
        }
        else
        {
            $this->estoque_filial_to_string = $estoque_filial_to_string;
        }

        $this->vdata['estoque_filial_to_string'] = $this->estoque_filial_to_string;
    }

    public function get_estoque_filial_to_string()
    {
        if(!empty($this->estoque_filial_to_string))
        {
            return $this->estoque_filial_to_string;
        }
    
        $values = Estoque::where('filial_id', '=', $this->id)->getIndexedArray('filial_id','{filial->apelido}');
        return implode(', ', $values);
    }

    public function set_estoque_produto_to_string($estoque_produto_to_string)
    {
        if(is_array($estoque_produto_to_string))
        {
            $values = Produto::where('id', 'in', $estoque_produto_to_string)->getIndexedArray('descricao', 'descricao');
            $this->estoque_produto_to_string = implode(', ', $values);
        }
        else
        {
            $this->estoque_produto_to_string = $estoque_produto_to_string;
        }

        $this->vdata['estoque_produto_to_string'] = $this->estoque_produto_to_string;
    }

    public function get_estoque_produto_to_string()
    {
        if(!empty($this->estoque_produto_to_string))
        {
            return $this->estoque_produto_to_string;
        }
    
        $values = Estoque::where('filial_id', '=', $this->id)->getIndexedArray('produto_id','{produto->descricao}');
        return implode(', ', $values);
    }

    public function set_estoque_armazem_to_string($estoque_armazem_to_string)
    {
        if(is_array($estoque_armazem_to_string))
        {
            $values = Armazem::where('id', 'in', $estoque_armazem_to_string)->getIndexedArray('descricao', 'descricao');
            $this->estoque_armazem_to_string = implode(', ', $values);
        }
        else
        {
            $this->estoque_armazem_to_string = $estoque_armazem_to_string;
        }

        $this->vdata['estoque_armazem_to_string'] = $this->estoque_armazem_to_string;
    }

    public function get_estoque_armazem_to_string()
    {
        if(!empty($this->estoque_armazem_to_string))
        {
            return $this->estoque_armazem_to_string;
        }
    
        $values = Estoque::where('filial_id', '=', $this->id)->getIndexedArray('armazem_id','{armazem->descricao}');
        return implode(', ', $values);
    }

    public function set_fornecedor_filial_to_string($fornecedor_filial_to_string)
    {
        if(is_array($fornecedor_filial_to_string))
        {
            $values = Filial::where('id', 'in', $fornecedor_filial_to_string)->getIndexedArray('apelido', 'apelido');
            $this->fornecedor_filial_to_string = implode(', ', $values);
        }
        else
        {
            $this->fornecedor_filial_to_string = $fornecedor_filial_to_string;
        }

        $this->vdata['fornecedor_filial_to_string'] = $this->fornecedor_filial_to_string;
    }

    public function get_fornecedor_filial_to_string()
    {
        if(!empty($this->fornecedor_filial_to_string))
        {
            return $this->fornecedor_filial_to_string;
        }
    
        $values = Fornecedor::where('filial_id', '=', $this->id)->getIndexedArray('filial_id','{filial->apelido}');
        return implode(', ', $values);
    }

    public function set_titulo_receber_filial_to_string($titulo_receber_filial_to_string)
    {
        if(is_array($titulo_receber_filial_to_string))
        {
            $values = Filial::where('id', 'in', $titulo_receber_filial_to_string)->getIndexedArray('apelido', 'apelido');
            $this->titulo_receber_filial_to_string = implode(', ', $values);
        }
        else
        {
            $this->titulo_receber_filial_to_string = $titulo_receber_filial_to_string;
        }

        $this->vdata['titulo_receber_filial_to_string'] = $this->titulo_receber_filial_to_string;
    }

    public function get_titulo_receber_filial_to_string()
    {
        if(!empty($this->titulo_receber_filial_to_string))
        {
            return $this->titulo_receber_filial_to_string;
        }
    
        $values = TituloReceber::where('filial_id', '=', $this->id)->getIndexedArray('filial_id','{filial->apelido}');
        return implode(', ', $values);
    }

    public function set_titulo_receber_cliente_to_string($titulo_receber_cliente_to_string)
    {
        if(is_array($titulo_receber_cliente_to_string))
        {
            $values = Cliente::where('id', 'in', $titulo_receber_cliente_to_string)->getIndexedArray('razao', 'razao');
            $this->titulo_receber_cliente_to_string = implode(', ', $values);
        }
        else
        {
            $this->titulo_receber_cliente_to_string = $titulo_receber_cliente_to_string;
        }

        $this->vdata['titulo_receber_cliente_to_string'] = $this->titulo_receber_cliente_to_string;
    }

    public function get_titulo_receber_cliente_to_string()
    {
        if(!empty($this->titulo_receber_cliente_to_string))
        {
            return $this->titulo_receber_cliente_to_string;
        }
    
        $values = TituloReceber::where('filial_id', '=', $this->id)->getIndexedArray('cliente_id','{cliente->razao}');
        return implode(', ', $values);
    }

    public function set_titulo_receber_vendedor_to_string($titulo_receber_vendedor_to_string)
    {
        if(is_array($titulo_receber_vendedor_to_string))
        {
            $values = Vendedor::where('id', 'in', $titulo_receber_vendedor_to_string)->getIndexedArray('nome', 'nome');
            $this->titulo_receber_vendedor_to_string = implode(', ', $values);
        }
        else
        {
            $this->titulo_receber_vendedor_to_string = $titulo_receber_vendedor_to_string;
        }

        $this->vdata['titulo_receber_vendedor_to_string'] = $this->titulo_receber_vendedor_to_string;
    }

    public function get_titulo_receber_vendedor_to_string()
    {
        if(!empty($this->titulo_receber_vendedor_to_string))
        {
            return $this->titulo_receber_vendedor_to_string;
        }
    
        $values = TituloReceber::where('filial_id', '=', $this->id)->getIndexedArray('vendedor_id','{vendedor->nome}');
        return implode(', ', $values);
    }

    public function set_titulo_receber_pedido_to_string($titulo_receber_pedido_to_string)
    {
        if(is_array($titulo_receber_pedido_to_string))
        {
            $values = Pedido::where('id', 'in', $titulo_receber_pedido_to_string)->getIndexedArray('id', 'id');
            $this->titulo_receber_pedido_to_string = implode(', ', $values);
        }
        else
        {
            $this->titulo_receber_pedido_to_string = $titulo_receber_pedido_to_string;
        }

        $this->vdata['titulo_receber_pedido_to_string'] = $this->titulo_receber_pedido_to_string;
    }

    public function get_titulo_receber_pedido_to_string()
    {
        if(!empty($this->titulo_receber_pedido_to_string))
        {
            return $this->titulo_receber_pedido_to_string;
        }
    
        $values = TituloReceber::where('filial_id', '=', $this->id)->getIndexedArray('pedido_id','{pedido->id}');
        return implode(', ', $values);
    }

    public function set_titulo_receber_nota_fiscal_to_string($titulo_receber_nota_fiscal_to_string)
    {
        if(is_array($titulo_receber_nota_fiscal_to_string))
        {
            $values = NotaSaida::where('id', 'in', $titulo_receber_nota_fiscal_to_string)->getIndexedArray('id', 'id');
            $this->titulo_receber_nota_fiscal_to_string = implode(', ', $values);
        }
        else
        {
            $this->titulo_receber_nota_fiscal_to_string = $titulo_receber_nota_fiscal_to_string;
        }

        $this->vdata['titulo_receber_nota_fiscal_to_string'] = $this->titulo_receber_nota_fiscal_to_string;
    }

    public function get_titulo_receber_nota_fiscal_to_string()
    {
        if(!empty($this->titulo_receber_nota_fiscal_to_string))
        {
            return $this->titulo_receber_nota_fiscal_to_string;
        }
    
        $values = TituloReceber::where('filial_id', '=', $this->id)->getIndexedArray('nota_fiscal_id','{nota_fiscal->id}');
        return implode(', ', $values);
    }

    
}

