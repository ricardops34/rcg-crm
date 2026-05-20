<?php

class Cliente extends TRecord
{
    const TABLENAME  = 'cliente';
    const PRIMARYKEY = 'id';
    const IDPOLICY   =  'serial'; // {max, serial}

    const CREATEDAT  = 'dt_inclusao';
    const UPDATEDAT  = 'dt_alteracao';

    private Filial $filial;
    private Municipio $municipio;
    private Vendedor $vendedor;
    private TipoCliente $tipo_cliente;
    private Segmento $seguimento;
    private CondicaoPagamento $condicao_pagamento;
    private TabelaPreco $tabela_preco;
    private MotivoBloqueio $motivo_bloqueio;
    private RegiaoCliente $regiao_cliente;
    private SituacaoCadastral $situacao_cadastral;

    use SystemChangeLogTrait;
    /**
     * Constructor method
     */
    public function __construct($id = NULL, $callObjectLoad = TRUE)
    {
        parent::__construct($id, $callObjectLoad);
        parent::addAttribute('filial_id');
        parent::addAttribute('cod_erp');
        parent::addAttribute('status');
        parent::addAttribute('tipo');
        parent::addAttribute('razao');
        parent::addAttribute('tipo_cliente_id');
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
        parent::addAttribute('celular2');
        parent::addAttribute('contato');
        parent::addAttribute('cnpj_cpf');
        parent::addAttribute('ie');
        parent::addAttribute('im');
        parent::addAttribute('contribuinte');
        parent::addAttribute('rg');
        parent::addAttribute('nascimento');
        parent::addAttribute('email');
        parent::addAttribute('vendedor_id');
        parent::addAttribute('condicao_pagamento_id');
        parent::addAttribute('tabela_preco_id');
        parent::addAttribute('primeira_compra');
        parent::addAttribute('ultima_compra');
        parent::addAttribute('data_cadastro');
        parent::addAttribute('dt_alteracao');
        parent::addAttribute('sinc');
        parent::addAttribute('dt_inclusao');
        parent::addAttribute('destaca_ie');
        parent::addAttribute('seguimento_id');
        parent::addAttribute('site');
        parent::addAttribute('obs');
        parent::addAttribute('filial_cadastro');
        parent::addAttribute('cliente');
        parent::addAttribute('latitude');
        parent::addAttribute('log_int');
        parent::addAttribute('longitude');
        parent::addAttribute('limite');
        parent::addAttribute('vencimento_limite');
        parent::addAttribute('risco');
        parent::addAttribute('system_unit_id');
        parent::addAttribute('carteira');
        parent::addAttribute('obs_bloqueio');
        parent::addAttribute('dt_bloqueio');
        parent::addAttribute('motivo_bloqueio');
        parent::addAttribute('motivo_bloqueio_id');
        parent::addAttribute('dt_reativacao');
        parent::addAttribute('obs_reativacao');
        parent::addAttribute('ultima_visita');
        parent::addAttribute('ultimo_atendimento');
        parent::addAttribute('regiao_cliente_id');
        parent::addAttribute('reg_ativo');
        parent::addAttribute('dt_revisao');
        parent::addAttribute('situacao_cadastral_id');
        parent::addAttribute('data_rfb');
    
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
     * Method set_municipio
     * Sample of usage: $var->municipio = $object;
     * @param $object Instance of Municipio
     */
    public function set_municipio(Municipio $object)
    {
        $this->municipio = $object;
        $this->municipio_id = $object->id;
    }

    /**
     * Method get_municipio
     * Sample of usage: $var->municipio->attribute;
     * @returns Municipio instance
     */
    public function get_municipio()
    {
    
        // loads the associated object
        if (empty($this->municipio))
            $this->municipio = new Municipio($this->municipio_id);
    
        // returns the associated object
        return $this->municipio;
    }
    /**
     * Method set_vendedor
     * Sample of usage: $var->vendedor = $object;
     * @param $object Instance of Vendedor
     */
    public function set_vendedor(Vendedor $object)
    {
        $this->vendedor = $object;
        $this->vendedor_id = $object->id;
    }

    /**
     * Method get_vendedor
     * Sample of usage: $var->vendedor->attribute;
     * @returns Vendedor instance
     */
    public function get_vendedor()
    {
    
        // loads the associated object
        if (empty($this->vendedor))
            $this->vendedor = new Vendedor($this->vendedor_id);
    
        // returns the associated object
        return $this->vendedor;
    }
    /**
     * Method set_tipo_cliente
     * Sample of usage: $var->tipo_cliente = $object;
     * @param $object Instance of TipoCliente
     */
    public function set_tipo_cliente(TipoCliente $object)
    {
        $this->tipo_cliente = $object;
        $this->tipo_cliente_id = $object->id;
    }

    /**
     * Method get_tipo_cliente
     * Sample of usage: $var->tipo_cliente->attribute;
     * @returns TipoCliente instance
     */
    public function get_tipo_cliente()
    {
    
        // loads the associated object
        if (empty($this->tipo_cliente))
            $this->tipo_cliente = new TipoCliente($this->tipo_cliente_id);
    
        // returns the associated object
        return $this->tipo_cliente;
    }
    /**
     * Method set_segmento
     * Sample of usage: $var->segmento = $object;
     * @param $object Instance of Segmento
     */
    public function set_seguimento(Segmento $object)
    {
        $this->seguimento = $object;
        $this->seguimento_id = $object->id;
    }

    /**
     * Method get_seguimento
     * Sample of usage: $var->seguimento->attribute;
     * @returns Segmento instance
     */
    public function get_seguimento()
    {
    
        // loads the associated object
        if (empty($this->seguimento))
            $this->seguimento = new Segmento($this->seguimento_id);
    
        // returns the associated object
        return $this->seguimento;
    }
    /**
     * Method set_condicao_pagamento
     * Sample of usage: $var->condicao_pagamento = $object;
     * @param $object Instance of CondicaoPagamento
     */
    public function set_condicao_pagamento(CondicaoPagamento $object)
    {
        $this->condicao_pagamento = $object;
        $this->condicao_pagamento_id = $object->id;
    }

    /**
     * Method get_condicao_pagamento
     * Sample of usage: $var->condicao_pagamento->attribute;
     * @returns CondicaoPagamento instance
     */
    public function get_condicao_pagamento()
    {
    
        // loads the associated object
        if (empty($this->condicao_pagamento))
            $this->condicao_pagamento = new CondicaoPagamento($this->condicao_pagamento_id);
    
        // returns the associated object
        return $this->condicao_pagamento;
    }
    /**
     * Method set_tabela_preco
     * Sample of usage: $var->tabela_preco = $object;
     * @param $object Instance of TabelaPreco
     */
    public function set_tabela_preco(TabelaPreco $object)
    {
        $this->tabela_preco = $object;
        $this->tabela_preco_id = $object->id;
    }

    /**
     * Method get_tabela_preco
     * Sample of usage: $var->tabela_preco->attribute;
     * @returns TabelaPreco instance
     */
    public function get_tabela_preco()
    {
    
        // loads the associated object
        if (empty($this->tabela_preco))
            $this->tabela_preco = new TabelaPreco($this->tabela_preco_id);
    
        // returns the associated object
        return $this->tabela_preco;
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
     * Method set_regiao_cliente
     * Sample of usage: $var->regiao_cliente = $object;
     * @param $object Instance of RegiaoCliente
     */
    public function set_regiao_cliente(RegiaoCliente $object)
    {
        $this->regiao_cliente = $object;
        $this->regiao_cliente_id = $object->id;
    }

    /**
     * Method get_regiao_cliente
     * Sample of usage: $var->regiao_cliente->attribute;
     * @returns RegiaoCliente instance
     */
    public function get_regiao_cliente()
    {
    
        // loads the associated object
        if (empty($this->regiao_cliente))
            $this->regiao_cliente = new RegiaoCliente($this->regiao_cliente_id);
    
        // returns the associated object
        return $this->regiao_cliente;
    }
    /**
     * Method set_situacao_cadastral
     * Sample of usage: $var->situacao_cadastral = $object;
     * @param $object Instance of SituacaoCadastral
     */
    public function set_situacao_cadastral(SituacaoCadastral $object)
    {
        $this->situacao_cadastral = $object;
        $this->situacao_cadastral_id = $object->id;
    }

    /**
     * Method get_situacao_cadastral
     * Sample of usage: $var->situacao_cadastral->attribute;
     * @returns SituacaoCadastral instance
     */
    public function get_situacao_cadastral()
    {
    
        // loads the associated object
        if (empty($this->situacao_cadastral))
            $this->situacao_cadastral = new SituacaoCadastral($this->situacao_cadastral_id);
    
        // returns the associated object
        return $this->situacao_cadastral;
    }

    /**
     * Method getNotaSaidas
     */
    public function getNotaSaidas()
    {
        $criteria = new TCriteria;
        $criteria->add(new TFilter('cliente_id', '=', $this->id));
        return NotaSaida::getObjects( $criteria );
    }
    /**
     * Method getPedidos
     */
    public function getPedidosByClientes()
    {
        $criteria = new TCriteria;
        $criteria->add(new TFilter('cliente_id', '=', $this->id));
        return Pedido::getObjects( $criteria );
    }
    /**
     * Method getPedidos
     */
    public function getPedidosByClienteEntregas()
    {
        $criteria = new TCriteria;
        $criteria->add(new TFilter('cliente_entrega_id', '=', $this->id));
        return Pedido::getObjects( $criteria );
    }
    /**
     * Method getOrcamentos
     */
    public function getOrcamentos()
    {
        $criteria = new TCriteria;
        $criteria->add(new TFilter('cliente_id', '=', $this->id));
        return Orcamento::getObjects( $criteria );
    }
    /**
     * Method getNegociacaos
     */
    public function getNegociacaos()
    {
        $criteria = new TCriteria;
        $criteria->add(new TFilter('cliente_id', '=', $this->id));
        return Negociacao::getObjects( $criteria );
    }
    /**
     * Method getClienteCondicaos
     */
    public function getClienteCondicaos()
    {
        $criteria = new TCriteria;
        $criteria->add(new TFilter('cliente_id', '=', $this->id));
        return ClienteCondicao::getObjects( $criteria );
    }
    /**
     * Method getClienteAcessos
     */
    public function getClienteAcessos()
    {
        $criteria = new TCriteria;
        $criteria->add(new TFilter('cliente_id', '=', $this->id));
        return ClienteAcesso::getObjects( $criteria );
    }
    /**
     * Method getTituloRecebers
     */
    public function getTituloRecebers()
    {
        $criteria = new TCriteria;
        $criteria->add(new TFilter('cliente_id', '=', $this->id));
        return TituloReceber::getObjects( $criteria );
    }
    /**
     * Method getVendaMesClientes
     */
    public function getVendaMesClientes()
    {
        $criteria = new TCriteria;
        $criteria->add(new TFilter('cliente_id', '=', $this->id));
        return VendaMesCliente::getObjects( $criteria );
    }
    /**
     * Method getVendedorClientes
     */
    public function getVendedorClientes()
    {
        $criteria = new TCriteria;
        $criteria->add(new TFilter('cliente_id', '=', $this->id));
        return VendedorCliente::getObjects( $criteria );
    }
    /**
     * Method getNotaSaidaItems
     */
    public function getNotaSaidaItems()
    {
        $criteria = new TCriteria;
        $criteria->add(new TFilter('cliente_id', '=', $this->id));
        return NotaSaidaItem::getObjects( $criteria );
    }
    /**
     * Method getClienteAtualizacaos
     */
    public function getClienteAtualizacaos()
    {
        $criteria = new TCriteria;
        $criteria->add(new TFilter('cliente_id', '=', $this->id));
        return ClienteAtualizacao::getObjects( $criteria );
    }
    /**
     * Method getAtendimentos
     */
    public function getAtendimentos()
    {
        $criteria = new TCriteria;
        $criteria->add(new TFilter('cliente_id', '=', $this->id));
        return Atendimento::getObjects( $criteria );
    }
    /**
     * Method getNotaEntradas
     */
    public function getNotaEntradas()
    {
        $criteria = new TCriteria;
        $criteria->add(new TFilter('cliente_id', '=', $this->id));
        return NotaEntrada::getObjects( $criteria );
    }
    /**
     * Method getClienteContatos
     */
    public function getClienteContatos()
    {
        $criteria = new TCriteria;
        $criteria->add(new TFilter('cliente_id', '=', $this->id));
        return ClienteContato::getObjects( $criteria );
    }
    /**
     * Method getClienteAtendimentos
     */
    public function getClienteAtendimentos()
    {
        $criteria = new TCriteria;
        $criteria->add(new TFilter('cliente_id', '=', $this->id));
        return ClienteAtendimento::getObjects( $criteria );
    }
    /**
     * Method getClienteHistoricos
     */
    public function getClienteHistoricos()
    {
        $criteria = new TCriteria;
        $criteria->add(new TFilter('cliente_id', '=', $this->id));
        return ClienteHistorico::getObjects( $criteria );
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
    
        $values = NotaSaida::where('cliente_id', '=', $this->id)->getIndexedArray('filial_id','{filial->apelido}');
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
    
        $values = NotaSaida::where('cliente_id', '=', $this->id)->getIndexedArray('cliente_id','{cliente->razao}');
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
    
        $values = NotaSaida::where('cliente_id', '=', $this->id)->getIndexedArray('fornecedor_id','{fornecedor->id}');
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
    
        $values = NotaSaida::where('cliente_id', '=', $this->id)->getIndexedArray('vendedor1_id','{vendedor1->nome}');
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
    
        $values = NotaSaida::where('cliente_id', '=', $this->id)->getIndexedArray('vendedor2_id','{vendedor2->nome}');
        return implode(', ', $values);
    }

    public function set_pedido_pedido_estado_to_string($pedido_pedido_estado_to_string)
    {
        if(is_array($pedido_pedido_estado_to_string))
        {
            $values = PedidoEstado::where('id', 'in', $pedido_pedido_estado_to_string)->getIndexedArray('id', 'id');
            $this->pedido_pedido_estado_to_string = implode(', ', $values);
        }
        else
        {
            $this->pedido_pedido_estado_to_string = $pedido_pedido_estado_to_string;
        }

        $this->vdata['pedido_pedido_estado_to_string'] = $this->pedido_pedido_estado_to_string;
    }

    public function get_pedido_pedido_estado_to_string()
    {
        if(!empty($this->pedido_pedido_estado_to_string))
        {
            return $this->pedido_pedido_estado_to_string;
        }
    
        $values = Pedido::where('cliente_entrega_id', '=', $this->id)->getIndexedArray('pedido_estado_id','{pedido_estado->id}');
        return implode(', ', $values);
    }

    public function set_pedido_cliente_to_string($pedido_cliente_to_string)
    {
        if(is_array($pedido_cliente_to_string))
        {
            $values = Cliente::where('id', 'in', $pedido_cliente_to_string)->getIndexedArray('razao', 'razao');
            $this->pedido_cliente_to_string = implode(', ', $values);
        }
        else
        {
            $this->pedido_cliente_to_string = $pedido_cliente_to_string;
        }

        $this->vdata['pedido_cliente_to_string'] = $this->pedido_cliente_to_string;
    }

    public function get_pedido_cliente_to_string()
    {
        if(!empty($this->pedido_cliente_to_string))
        {
            return $this->pedido_cliente_to_string;
        }
    
        $values = Pedido::where('cliente_entrega_id', '=', $this->id)->getIndexedArray('cliente_id','{cliente->razao}');
        return implode(', ', $values);
    }

    public function set_pedido_cliente_entrega_to_string($pedido_cliente_entrega_to_string)
    {
        if(is_array($pedido_cliente_entrega_to_string))
        {
            $values = Cliente::where('id', 'in', $pedido_cliente_entrega_to_string)->getIndexedArray('razao', 'razao');
            $this->pedido_cliente_entrega_to_string = implode(', ', $values);
        }
        else
        {
            $this->pedido_cliente_entrega_to_string = $pedido_cliente_entrega_to_string;
        }

        $this->vdata['pedido_cliente_entrega_to_string'] = $this->pedido_cliente_entrega_to_string;
    }

    public function get_pedido_cliente_entrega_to_string()
    {
        if(!empty($this->pedido_cliente_entrega_to_string))
        {
            return $this->pedido_cliente_entrega_to_string;
        }
    
        $values = Pedido::where('cliente_entrega_id', '=', $this->id)->getIndexedArray('cliente_entrega_id','{cliente_entrega->razao}');
        return implode(', ', $values);
    }

    public function set_pedido_vendedor1_to_string($pedido_vendedor1_to_string)
    {
        if(is_array($pedido_vendedor1_to_string))
        {
            $values = Vendedor::where('id', 'in', $pedido_vendedor1_to_string)->getIndexedArray('nome', 'nome');
            $this->pedido_vendedor1_to_string = implode(', ', $values);
        }
        else
        {
            $this->pedido_vendedor1_to_string = $pedido_vendedor1_to_string;
        }

        $this->vdata['pedido_vendedor1_to_string'] = $this->pedido_vendedor1_to_string;
    }

    public function get_pedido_vendedor1_to_string()
    {
        if(!empty($this->pedido_vendedor1_to_string))
        {
            return $this->pedido_vendedor1_to_string;
        }
    
        $values = Pedido::where('cliente_entrega_id', '=', $this->id)->getIndexedArray('vendedor1_id','{vendedor1->nome}');
        return implode(', ', $values);
    }

    public function set_pedido_vendedor2_to_string($pedido_vendedor2_to_string)
    {
        if(is_array($pedido_vendedor2_to_string))
        {
            $values = Vendedor::where('id', 'in', $pedido_vendedor2_to_string)->getIndexedArray('nome', 'nome');
            $this->pedido_vendedor2_to_string = implode(', ', $values);
        }
        else
        {
            $this->pedido_vendedor2_to_string = $pedido_vendedor2_to_string;
        }

        $this->vdata['pedido_vendedor2_to_string'] = $this->pedido_vendedor2_to_string;
    }

    public function get_pedido_vendedor2_to_string()
    {
        if(!empty($this->pedido_vendedor2_to_string))
        {
            return $this->pedido_vendedor2_to_string;
        }
    
        $values = Pedido::where('cliente_entrega_id', '=', $this->id)->getIndexedArray('vendedor2_id','{vendedor2->nome}');
        return implode(', ', $values);
    }

    public function set_pedido_transportadora_to_string($pedido_transportadora_to_string)
    {
        if(is_array($pedido_transportadora_to_string))
        {
            $values = Transportadora::where('id', 'in', $pedido_transportadora_to_string)->getIndexedArray('id', 'id');
            $this->pedido_transportadora_to_string = implode(', ', $values);
        }
        else
        {
            $this->pedido_transportadora_to_string = $pedido_transportadora_to_string;
        }

        $this->vdata['pedido_transportadora_to_string'] = $this->pedido_transportadora_to_string;
    }

    public function get_pedido_transportadora_to_string()
    {
        if(!empty($this->pedido_transportadora_to_string))
        {
            return $this->pedido_transportadora_to_string;
        }
    
        $values = Pedido::where('cliente_entrega_id', '=', $this->id)->getIndexedArray('transportadora_id','{transportadora->id}');
        return implode(', ', $values);
    }

    public function set_pedido_condicao_pagamento_to_string($pedido_condicao_pagamento_to_string)
    {
        if(is_array($pedido_condicao_pagamento_to_string))
        {
            $values = CondicaoPagamento::where('id', 'in', $pedido_condicao_pagamento_to_string)->getIndexedArray('descricao', 'descricao');
            $this->pedido_condicao_pagamento_to_string = implode(', ', $values);
        }
        else
        {
            $this->pedido_condicao_pagamento_to_string = $pedido_condicao_pagamento_to_string;
        }

        $this->vdata['pedido_condicao_pagamento_to_string'] = $this->pedido_condicao_pagamento_to_string;
    }

    public function get_pedido_condicao_pagamento_to_string()
    {
        if(!empty($this->pedido_condicao_pagamento_to_string))
        {
            return $this->pedido_condicao_pagamento_to_string;
        }
    
        $values = Pedido::where('cliente_entrega_id', '=', $this->id)->getIndexedArray('condicao_pagamento_id','{condicao_pagamento->descricao}');
        return implode(', ', $values);
    }

    public function set_pedido_orcamento_to_string($pedido_orcamento_to_string)
    {
        if(is_array($pedido_orcamento_to_string))
        {
            $values = Orcamento::where('id', 'in', $pedido_orcamento_to_string)->getIndexedArray('dt_faturamento', 'dt_faturamento');
            $this->pedido_orcamento_to_string = implode(', ', $values);
        }
        else
        {
            $this->pedido_orcamento_to_string = $pedido_orcamento_to_string;
        }

        $this->vdata['pedido_orcamento_to_string'] = $this->pedido_orcamento_to_string;
    }

    public function get_pedido_orcamento_to_string()
    {
        if(!empty($this->pedido_orcamento_to_string))
        {
            return $this->pedido_orcamento_to_string;
        }
    
        $values = Pedido::where('cliente_entrega_id', '=', $this->id)->getIndexedArray('orcamento_id','{orcamento->dt_faturamento}');
        return implode(', ', $values);
    }

    public function set_pedido_nota_saida_to_string($pedido_nota_saida_to_string)
    {
        if(is_array($pedido_nota_saida_to_string))
        {
            $values = NotaSaida::where('id', 'in', $pedido_nota_saida_to_string)->getIndexedArray('id', 'id');
            $this->pedido_nota_saida_to_string = implode(', ', $values);
        }
        else
        {
            $this->pedido_nota_saida_to_string = $pedido_nota_saida_to_string;
        }

        $this->vdata['pedido_nota_saida_to_string'] = $this->pedido_nota_saida_to_string;
    }

    public function get_pedido_nota_saida_to_string()
    {
        if(!empty($this->pedido_nota_saida_to_string))
        {
            return $this->pedido_nota_saida_to_string;
        }
    
        $values = Pedido::where('cliente_entrega_id', '=', $this->id)->getIndexedArray('nota_saida_id','{nota_saida->id}');
        return implode(', ', $values);
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
    
        $values = Orcamento::where('cliente_id', '=', $this->id)->getIndexedArray('cliente_id','{cliente->razao}');
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
    
        $values = Orcamento::where('cliente_id', '=', $this->id)->getIndexedArray('tabela_preco_id','{tabela_preco->descricao}');
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
    
        $values = Orcamento::where('cliente_id', '=', $this->id)->getIndexedArray('condicao_pagamento_id','{condicao_pagamento->descricao}');
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
    
        $values = Orcamento::where('cliente_id', '=', $this->id)->getIndexedArray('pedido_id','{pedido->id}');
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
    
        $values = Orcamento::where('cliente_id', '=', $this->id)->getIndexedArray('vendedor_id','{vendedor->nome}');
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
    
        $values = Orcamento::where('cliente_id', '=', $this->id)->getIndexedArray('estado_id','{estado->sigla}');
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
    
        $values = Orcamento::where('cliente_id', '=', $this->id)->getIndexedArray('municipio_id','{municipio->cod_erp}');
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
    
        $values = Orcamento::where('cliente_id', '=', $this->id)->getIndexedArray('orcamento_estado_id','{orcamento_estado->descricao}');
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
    
        $values = Negociacao::where('cliente_id', '=', $this->id)->getIndexedArray('cliente_id','{cliente->razao}');
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
    
        $values = Negociacao::where('cliente_id', '=', $this->id)->getIndexedArray('vendedor_id','{vendedor->nome}');
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
    
        $values = Negociacao::where('cliente_id', '=', $this->id)->getIndexedArray('atendimento_tipo_id','{atendimento_tipo->id}');
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
    
        $values = Negociacao::where('cliente_id', '=', $this->id)->getIndexedArray('atendimento_id','{atendimento->id}');
        return implode(', ', $values);
    }

    public function set_cliente_condicao_condicao_pagamento_to_string($cliente_condicao_condicao_pagamento_to_string)
    {
        if(is_array($cliente_condicao_condicao_pagamento_to_string))
        {
            $values = CondicaoPagamento::where('id', 'in', $cliente_condicao_condicao_pagamento_to_string)->getIndexedArray('descricao', 'descricao');
            $this->cliente_condicao_condicao_pagamento_to_string = implode(', ', $values);
        }
        else
        {
            $this->cliente_condicao_condicao_pagamento_to_string = $cliente_condicao_condicao_pagamento_to_string;
        }

        $this->vdata['cliente_condicao_condicao_pagamento_to_string'] = $this->cliente_condicao_condicao_pagamento_to_string;
    }

    public function get_cliente_condicao_condicao_pagamento_to_string()
    {
        if(!empty($this->cliente_condicao_condicao_pagamento_to_string))
        {
            return $this->cliente_condicao_condicao_pagamento_to_string;
        }
    
        $values = ClienteCondicao::where('cliente_id', '=', $this->id)->getIndexedArray('condicao_pagamento_id','{condicao_pagamento->descricao}');
        return implode(', ', $values);
    }

    public function set_cliente_condicao_cliente_to_string($cliente_condicao_cliente_to_string)
    {
        if(is_array($cliente_condicao_cliente_to_string))
        {
            $values = Cliente::where('id', 'in', $cliente_condicao_cliente_to_string)->getIndexedArray('razao', 'razao');
            $this->cliente_condicao_cliente_to_string = implode(', ', $values);
        }
        else
        {
            $this->cliente_condicao_cliente_to_string = $cliente_condicao_cliente_to_string;
        }

        $this->vdata['cliente_condicao_cliente_to_string'] = $this->cliente_condicao_cliente_to_string;
    }

    public function get_cliente_condicao_cliente_to_string()
    {
        if(!empty($this->cliente_condicao_cliente_to_string))
        {
            return $this->cliente_condicao_cliente_to_string;
        }
    
        $values = ClienteCondicao::where('cliente_id', '=', $this->id)->getIndexedArray('cliente_id','{cliente->razao}');
        return implode(', ', $values);
    }

    public function set_cliente_acesso_cliente_to_string($cliente_acesso_cliente_to_string)
    {
        if(is_array($cliente_acesso_cliente_to_string))
        {
            $values = Cliente::where('id', 'in', $cliente_acesso_cliente_to_string)->getIndexedArray('razao', 'razao');
            $this->cliente_acesso_cliente_to_string = implode(', ', $values);
        }
        else
        {
            $this->cliente_acesso_cliente_to_string = $cliente_acesso_cliente_to_string;
        }

        $this->vdata['cliente_acesso_cliente_to_string'] = $this->cliente_acesso_cliente_to_string;
    }

    public function get_cliente_acesso_cliente_to_string()
    {
        if(!empty($this->cliente_acesso_cliente_to_string))
        {
            return $this->cliente_acesso_cliente_to_string;
        }
    
        $values = ClienteAcesso::where('cliente_id', '=', $this->id)->getIndexedArray('cliente_id','{cliente->razao}');
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
    
        $values = TituloReceber::where('cliente_id', '=', $this->id)->getIndexedArray('filial_id','{filial->apelido}');
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
    
        $values = TituloReceber::where('cliente_id', '=', $this->id)->getIndexedArray('cliente_id','{cliente->razao}');
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
    
        $values = TituloReceber::where('cliente_id', '=', $this->id)->getIndexedArray('vendedor_id','{vendedor->nome}');
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
    
        $values = TituloReceber::where('cliente_id', '=', $this->id)->getIndexedArray('pedido_id','{pedido->id}');
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
    
        $values = TituloReceber::where('cliente_id', '=', $this->id)->getIndexedArray('nota_fiscal_id','{nota_fiscal->id}');
        return implode(', ', $values);
    }

    public function set_venda_mes_cliente_cliente_to_string($venda_mes_cliente_cliente_to_string)
    {
        if(is_array($venda_mes_cliente_cliente_to_string))
        {
            $values = Cliente::where('id', 'in', $venda_mes_cliente_cliente_to_string)->getIndexedArray('razao', 'razao');
            $this->venda_mes_cliente_cliente_to_string = implode(', ', $values);
        }
        else
        {
            $this->venda_mes_cliente_cliente_to_string = $venda_mes_cliente_cliente_to_string;
        }

        $this->vdata['venda_mes_cliente_cliente_to_string'] = $this->venda_mes_cliente_cliente_to_string;
    }

    public function get_venda_mes_cliente_cliente_to_string()
    {
        if(!empty($this->venda_mes_cliente_cliente_to_string))
        {
            return $this->venda_mes_cliente_cliente_to_string;
        }
    
        $values = VendaMesCliente::where('cliente_id', '=', $this->id)->getIndexedArray('cliente_id','{cliente->razao}');
        return implode(', ', $values);
    }

    public function set_vendedor_cliente_vendedor_to_string($vendedor_cliente_vendedor_to_string)
    {
        if(is_array($vendedor_cliente_vendedor_to_string))
        {
            $values = Vendedor::where('id', 'in', $vendedor_cliente_vendedor_to_string)->getIndexedArray('nome', 'nome');
            $this->vendedor_cliente_vendedor_to_string = implode(', ', $values);
        }
        else
        {
            $this->vendedor_cliente_vendedor_to_string = $vendedor_cliente_vendedor_to_string;
        }

        $this->vdata['vendedor_cliente_vendedor_to_string'] = $this->vendedor_cliente_vendedor_to_string;
    }

    public function get_vendedor_cliente_vendedor_to_string()
    {
        if(!empty($this->vendedor_cliente_vendedor_to_string))
        {
            return $this->vendedor_cliente_vendedor_to_string;
        }
    
        $values = VendedorCliente::where('cliente_id', '=', $this->id)->getIndexedArray('vendedor_id','{vendedor->nome}');
        return implode(', ', $values);
    }

    public function set_vendedor_cliente_cliente_to_string($vendedor_cliente_cliente_to_string)
    {
        if(is_array($vendedor_cliente_cliente_to_string))
        {
            $values = Cliente::where('id', 'in', $vendedor_cliente_cliente_to_string)->getIndexedArray('razao', 'razao');
            $this->vendedor_cliente_cliente_to_string = implode(', ', $values);
        }
        else
        {
            $this->vendedor_cliente_cliente_to_string = $vendedor_cliente_cliente_to_string;
        }

        $this->vdata['vendedor_cliente_cliente_to_string'] = $this->vendedor_cliente_cliente_to_string;
    }

    public function get_vendedor_cliente_cliente_to_string()
    {
        if(!empty($this->vendedor_cliente_cliente_to_string))
        {
            return $this->vendedor_cliente_cliente_to_string;
        }
    
        $values = VendedorCliente::where('cliente_id', '=', $this->id)->getIndexedArray('cliente_id','{cliente->razao}');
        return implode(', ', $values);
    }

    public function set_nota_saida_item_nota_saida_to_string($nota_saida_item_nota_saida_to_string)
    {
        if(is_array($nota_saida_item_nota_saida_to_string))
        {
            $values = NotaSaida::where('id', 'in', $nota_saida_item_nota_saida_to_string)->getIndexedArray('id', 'id');
            $this->nota_saida_item_nota_saida_to_string = implode(', ', $values);
        }
        else
        {
            $this->nota_saida_item_nota_saida_to_string = $nota_saida_item_nota_saida_to_string;
        }

        $this->vdata['nota_saida_item_nota_saida_to_string'] = $this->nota_saida_item_nota_saida_to_string;
    }

    public function get_nota_saida_item_nota_saida_to_string()
    {
        if(!empty($this->nota_saida_item_nota_saida_to_string))
        {
            return $this->nota_saida_item_nota_saida_to_string;
        }
    
        $values = NotaSaidaItem::where('cliente_id', '=', $this->id)->getIndexedArray('nota_saida_id','{nota_saida->id}');
        return implode(', ', $values);
    }

    public function set_nota_saida_item_produto_to_string($nota_saida_item_produto_to_string)
    {
        if(is_array($nota_saida_item_produto_to_string))
        {
            $values = Produto::where('id', 'in', $nota_saida_item_produto_to_string)->getIndexedArray('descricao', 'descricao');
            $this->nota_saida_item_produto_to_string = implode(', ', $values);
        }
        else
        {
            $this->nota_saida_item_produto_to_string = $nota_saida_item_produto_to_string;
        }

        $this->vdata['nota_saida_item_produto_to_string'] = $this->nota_saida_item_produto_to_string;
    }

    public function get_nota_saida_item_produto_to_string()
    {
        if(!empty($this->nota_saida_item_produto_to_string))
        {
            return $this->nota_saida_item_produto_to_string;
        }
    
        $values = NotaSaidaItem::where('cliente_id', '=', $this->id)->getIndexedArray('produto_id','{produto->descricao}');
        return implode(', ', $values);
    }

    public function set_nota_saida_item_tes_to_string($nota_saida_item_tes_to_string)
    {
        if(is_array($nota_saida_item_tes_to_string))
        {
            $values = TipoEntradaSaida::where('id', 'in', $nota_saida_item_tes_to_string)->getIndexedArray('id', 'id');
            $this->nota_saida_item_tes_to_string = implode(', ', $values);
        }
        else
        {
            $this->nota_saida_item_tes_to_string = $nota_saida_item_tes_to_string;
        }

        $this->vdata['nota_saida_item_tes_to_string'] = $this->nota_saida_item_tes_to_string;
    }

    public function get_nota_saida_item_tes_to_string()
    {
        if(!empty($this->nota_saida_item_tes_to_string))
        {
            return $this->nota_saida_item_tes_to_string;
        }
    
        $values = NotaSaidaItem::where('cliente_id', '=', $this->id)->getIndexedArray('tes_id','{tes->id}');
        return implode(', ', $values);
    }

    public function set_nota_saida_item_cliente_to_string($nota_saida_item_cliente_to_string)
    {
        if(is_array($nota_saida_item_cliente_to_string))
        {
            $values = Cliente::where('id', 'in', $nota_saida_item_cliente_to_string)->getIndexedArray('razao', 'razao');
            $this->nota_saida_item_cliente_to_string = implode(', ', $values);
        }
        else
        {
            $this->nota_saida_item_cliente_to_string = $nota_saida_item_cliente_to_string;
        }

        $this->vdata['nota_saida_item_cliente_to_string'] = $this->nota_saida_item_cliente_to_string;
    }

    public function get_nota_saida_item_cliente_to_string()
    {
        if(!empty($this->nota_saida_item_cliente_to_string))
        {
            return $this->nota_saida_item_cliente_to_string;
        }
    
        $values = NotaSaidaItem::where('cliente_id', '=', $this->id)->getIndexedArray('cliente_id','{cliente->razao}');
        return implode(', ', $values);
    }

    public function set_nota_saida_item_vendedor1_to_string($nota_saida_item_vendedor1_to_string)
    {
        if(is_array($nota_saida_item_vendedor1_to_string))
        {
            $values = Vendedor::where('id', 'in', $nota_saida_item_vendedor1_to_string)->getIndexedArray('nome', 'nome');
            $this->nota_saida_item_vendedor1_to_string = implode(', ', $values);
        }
        else
        {
            $this->nota_saida_item_vendedor1_to_string = $nota_saida_item_vendedor1_to_string;
        }

        $this->vdata['nota_saida_item_vendedor1_to_string'] = $this->nota_saida_item_vendedor1_to_string;
    }

    public function get_nota_saida_item_vendedor1_to_string()
    {
        if(!empty($this->nota_saida_item_vendedor1_to_string))
        {
            return $this->nota_saida_item_vendedor1_to_string;
        }
    
        $values = NotaSaidaItem::where('cliente_id', '=', $this->id)->getIndexedArray('vendedor1_id','{vendedor1->nome}');
        return implode(', ', $values);
    }

    public function set_nota_saida_item_vendedor2_to_string($nota_saida_item_vendedor2_to_string)
    {
        if(is_array($nota_saida_item_vendedor2_to_string))
        {
            $values = Vendedor::where('id', 'in', $nota_saida_item_vendedor2_to_string)->getIndexedArray('nome', 'nome');
            $this->nota_saida_item_vendedor2_to_string = implode(', ', $values);
        }
        else
        {
            $this->nota_saida_item_vendedor2_to_string = $nota_saida_item_vendedor2_to_string;
        }

        $this->vdata['nota_saida_item_vendedor2_to_string'] = $this->nota_saida_item_vendedor2_to_string;
    }

    public function get_nota_saida_item_vendedor2_to_string()
    {
        if(!empty($this->nota_saida_item_vendedor2_to_string))
        {
            return $this->nota_saida_item_vendedor2_to_string;
        }
    
        $values = NotaSaidaItem::where('cliente_id', '=', $this->id)->getIndexedArray('vendedor2_id','{vendedor2->nome}');
        return implode(', ', $values);
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
    
        $values = ClienteAtualizacao::where('cliente_id', '=', $this->id)->getIndexedArray('cliente_id','{cliente->razao}');
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
    
        $values = ClienteAtualizacao::where('cliente_id', '=', $this->id)->getIndexedArray('situacao_cadastral_id','{situacao_cadastral->descricao}');
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
    
        $values = ClienteAtualizacao::where('cliente_id', '=', $this->id)->getIndexedArray('atividade_principal_id','{atividade_principal->id}');
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
    
        $values = ClienteAtualizacao::where('cliente_id', '=', $this->id)->getIndexedArray('pais_id','{pais->id}');
        return implode(', ', $values);
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
    
        $values = Atendimento::where('cliente_id', '=', $this->id)->getIndexedArray('atendimento_tipo_id','{atendimento_tipo->id}');
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
    
        $values = Atendimento::where('cliente_id', '=', $this->id)->getIndexedArray('vendedor_id','{vendedor->nome}');
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
    
        $values = Atendimento::where('cliente_id', '=', $this->id)->getIndexedArray('cliente_id','{cliente->razao}');
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
    
        $values = NotaEntrada::where('cliente_id', '=', $this->id)->getIndexedArray('cliente_id','{cliente->razao}');
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
    
        $values = NotaEntrada::where('cliente_id', '=', $this->id)->getIndexedArray('fornecedor_id','{fornecedor->id}');
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
    
        $values = NotaEntrada::where('cliente_id', '=', $this->id)->getIndexedArray('vendedor1_id','{vendedor1->nome}');
        return implode(', ', $values);
    }

    public function set_cliente_contato_cliente_to_string($cliente_contato_cliente_to_string)
    {
        if(is_array($cliente_contato_cliente_to_string))
        {
            $values = Cliente::where('id', 'in', $cliente_contato_cliente_to_string)->getIndexedArray('razao', 'razao');
            $this->cliente_contato_cliente_to_string = implode(', ', $values);
        }
        else
        {
            $this->cliente_contato_cliente_to_string = $cliente_contato_cliente_to_string;
        }

        $this->vdata['cliente_contato_cliente_to_string'] = $this->cliente_contato_cliente_to_string;
    }

    public function get_cliente_contato_cliente_to_string()
    {
        if(!empty($this->cliente_contato_cliente_to_string))
        {
            return $this->cliente_contato_cliente_to_string;
        }
    
        $values = ClienteContato::where('cliente_id', '=', $this->id)->getIndexedArray('cliente_id','{cliente->razao}');
        return implode(', ', $values);
    }

    public function set_cliente_contato_tipo_contato_to_string($cliente_contato_tipo_contato_to_string)
    {
        if(is_array($cliente_contato_tipo_contato_to_string))
        {
            $values = TipoContato::where('id', 'in', $cliente_contato_tipo_contato_to_string)->getIndexedArray('id', 'id');
            $this->cliente_contato_tipo_contato_to_string = implode(', ', $values);
        }
        else
        {
            $this->cliente_contato_tipo_contato_to_string = $cliente_contato_tipo_contato_to_string;
        }

        $this->vdata['cliente_contato_tipo_contato_to_string'] = $this->cliente_contato_tipo_contato_to_string;
    }

    public function get_cliente_contato_tipo_contato_to_string()
    {
        if(!empty($this->cliente_contato_tipo_contato_to_string))
        {
            return $this->cliente_contato_tipo_contato_to_string;
        }
    
        $values = ClienteContato::where('cliente_id', '=', $this->id)->getIndexedArray('tipo_contato_id','{tipo_contato->id}');
        return implode(', ', $values);
    }

    public function set_cliente_atendimento_cliente_to_string($cliente_atendimento_cliente_to_string)
    {
        if(is_array($cliente_atendimento_cliente_to_string))
        {
            $values = Cliente::where('id', 'in', $cliente_atendimento_cliente_to_string)->getIndexedArray('razao', 'razao');
            $this->cliente_atendimento_cliente_to_string = implode(', ', $values);
        }
        else
        {
            $this->cliente_atendimento_cliente_to_string = $cliente_atendimento_cliente_to_string;
        }

        $this->vdata['cliente_atendimento_cliente_to_string'] = $this->cliente_atendimento_cliente_to_string;
    }

    public function get_cliente_atendimento_cliente_to_string()
    {
        if(!empty($this->cliente_atendimento_cliente_to_string))
        {
            return $this->cliente_atendimento_cliente_to_string;
        }
    
        $values = ClienteAtendimento::where('cliente_id', '=', $this->id)->getIndexedArray('cliente_id','{cliente->razao}');
        return implode(', ', $values);
    }

    public function set_cliente_atendimento_vendedor_to_string($cliente_atendimento_vendedor_to_string)
    {
        if(is_array($cliente_atendimento_vendedor_to_string))
        {
            $values = Vendedor::where('id', 'in', $cliente_atendimento_vendedor_to_string)->getIndexedArray('nome', 'nome');
            $this->cliente_atendimento_vendedor_to_string = implode(', ', $values);
        }
        else
        {
            $this->cliente_atendimento_vendedor_to_string = $cliente_atendimento_vendedor_to_string;
        }

        $this->vdata['cliente_atendimento_vendedor_to_string'] = $this->cliente_atendimento_vendedor_to_string;
    }

    public function get_cliente_atendimento_vendedor_to_string()
    {
        if(!empty($this->cliente_atendimento_vendedor_to_string))
        {
            return $this->cliente_atendimento_vendedor_to_string;
        }
    
        $values = ClienteAtendimento::where('cliente_id', '=', $this->id)->getIndexedArray('vendedor_id','{vendedor->nome}');
        return implode(', ', $values);
    }

    public function set_cliente_historico_cliente_to_string($cliente_historico_cliente_to_string)
    {
        if(is_array($cliente_historico_cliente_to_string))
        {
            $values = Cliente::where('id', 'in', $cliente_historico_cliente_to_string)->getIndexedArray('razao', 'razao');
            $this->cliente_historico_cliente_to_string = implode(', ', $values);
        }
        else
        {
            $this->cliente_historico_cliente_to_string = $cliente_historico_cliente_to_string;
        }

        $this->vdata['cliente_historico_cliente_to_string'] = $this->cliente_historico_cliente_to_string;
    }

    public function get_cliente_historico_cliente_to_string()
    {
        if(!empty($this->cliente_historico_cliente_to_string))
        {
            return $this->cliente_historico_cliente_to_string;
        }
    
        $values = ClienteHistorico::where('cliente_id', '=', $this->id)->getIndexedArray('cliente_id','{cliente->razao}');
        return implode(', ', $values);
    }

  
    public function getTituloReceber()
    {
        $criteria = new TCriteria;
        $criteria->add(new TFilter('cliente_id', '=', $this->id));
        $criteria->add(new TFilter('saldo', '>', 0 ));
        return TituloReceber::getObjects( $criteria );
    }
  
    public function get_saldo_titulo()
    {
        if (empty($this->saldo_titulo)){
            $this->saldo_titulo = 0;
            $criteria = new TCriteria;
            $criteria->add(new TFilter('cliente_id', '=', $this->id));
            $criteria->add(new TFilter('saldo', '>', 0 ));
            $repository = new TRepository('TituloReceber'); 
            $oTitulos = $repository->load($criteria); 
        
            foreach ($oTitulos as $oTitulo)
            {
                $this->saldo_titulo+= $oTitulo->saldo;
            }
        }
    }

    public function get_saldo_vencido()
    {
        if (empty($this->saldo_vencido)){
            $this->saldo_vencido = 0;
        
            $criteria = new TCriteria;
            $criteria->add(new TFilter('cliente_id', '=', $this->id));
            $criteria->add(new TFilter('saldo', '>', 0 ));
            $criteria->add(new TFilter('vencimento','<', date('Y-m-d') ));
            $repository = new TRepository('TituloReceber'); 
            $oTitulos = $repository->load($criteria); 
        
            foreach ($oTitulos as $oTitulo)
            {
                $this->saldo_vencido+= $oTitulo->saldo;
            }
        }
    }

    public function get_dias_compra(){
    
        $ndias = 9999;
        if(isset($this->ultima_compra)){
            $data_inicio = new DateTime($this->ultima_compra);
    
            $data_fim = new DateTime();

            $dateInterval = $data_inicio->diff($data_fim);
            //$this->dias_compra = $dateInterval->days;
    
            $ndias = $dateInterval->days;
        }elseif(isset($this->data_cadastro)){
        
            $data_inicio = new DateTime($this->data_cadastro);
    
            $data_fim = new DateTime();

            $dateInterval = $data_inicio->diff($data_fim);
            //$this->dias_compra = $dateInterval->days;
    
            $ndias = $dateInterval->days;
        }
        return $ndias;
    
    }

    public function get_dias_visita(){
    
        $ndias = 9999;
        if(isset($this->ultima_visita)){
            $data_inicio = new DateTime($this->ultima_visita);
    
            $data_fim = new DateTime();

            $dateInterval = $data_inicio->diff($data_fim);
            //$this->dias_compra = $dateInterval->days;
    
            $ndias = $dateInterval->days;
        }elseif(isset($this->data_cadastro)){
        
            $data_inicio = new DateTime($this->data_cadastro);
    
            $data_fim = new DateTime();

            $dateInterval = $data_inicio->diff($data_fim);
            //$this->dias_compra = $dateInterval->days;
    
            $ndias = $dateInterval->days;
        }
        return $ndias;
    
    }
    
    //,datediff(curdate(), cliente.ultima_compra) as dias
    public function get_nome_status(){
    
        $cNome = ltrim(rtrim(ltrim($this->fantasia)));//.'('. rtrim(ltrim($this->razao)).')');
        $cReturn = "";
        if($this->tipo == 'F'){
            if(!empty($this->razao)){
                $cNome = rtrim(ltrim($this->razao));
            }
        }

        $cReturn = '<b>'.$cNome.'</b>';;
        if($this->status == 'B'){ //fas fa-lock
            $icone = new TElement('i');
            $icone->class="fas fa-lock";
            $cReturn = $icone.'<s>'.$cNome.'</s>';
        }
    
        return $cReturn;
    }

    public function get_comodato(){
    
        $nComodato = 0 ;
        $oNotas = NotaSaida::where('cliente_id',  '=', $this->id)
                    ->where('reg_ativo ', '=', 'S')
                    ->where('tipo ', '=', 'N')
                    ->where('vlr_comodato ', '>', 0)
                    ->orderBy('id')
                    ->load();    

        if ($oNotas){
            foreach($oNotas  as $oNota )
            {
                 $nComodato += $oNota->vlr_comodato - $oNota->vlr_devolucao;
            }
        }
    
        if($nComodato < 0){
            $nComodato = 0;
        }
    
        return $nComodato;
    }
    /*
    public function onAfterStore($object){

        $data = new DateTime($this->data_cadastro);
        $ano_ini = $data->format('Y');
        $ano_fim = date("Y");      
        $id = $this->id;

        for ($y = $ano_ini; $y <= $ano_fim ; $y++) {

            $oBusca = VendaMesCliente::where('cliente_id', '=', $id)
                    ->where('ano', '=', $y)
                    ->first();
            if($oBusca){
            
            }else{
                $cNome = ltrim(rtrim(ltrim($this->fantasia)));
                if($this->tipo == 'F'){
                   if(!empty($this->razao)){
                        $cNome = rtrim(ltrim($this->razao));
                    }
                }

                $oNovo = new VendaMesCliente();
                $oNovo->cliente_id = $id;
                $oNovo->cliente_nome = $cNome;
                $oNovo->ano = $y ;
                $oNovo->janeiro = 0 ;
                $oNovo->fevereiro = 0 ;
                $oNovo->marco = 0 ;
                $oNovo->abril = 0 ;
                $oNovo->maio = 0 ;
                $oNovo->junho = 0 ;
                $oNovo->julho = 0 ;
                $oNovo->agosto = 0 ;
                $oNovo->setembro = 0 ;
                $oNovo->outubro = 0 ;
                $oNovo->novembro = 0 ;
                $oNovo->dezembro = 0 ;
                $oNovo->store();
            }
        }
    }
    */
  
                                                            
}

