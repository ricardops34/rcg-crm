<?php

class Orcamento extends TRecord
{
    const TABLENAME  = 'orcamento';
    const PRIMARYKEY = 'id';
    const IDPOLICY   =  'serial'; // {max, serial}

    const CREATEDAT  = 'dt_inclusao';
    const UPDATEDAT  = 'dt_alteracao';

    private Cliente $cliente;
    private TabelaPreco $tabela_preco;
    private Pedido $pedido;
    private Estado $estado;
    private CondicaoPagamento $condicao_pagamento;
    private OrcamentoEstado $orcamento_estado;
    private Municipio $municipio;
    private Vendedor $vendedor;

    /**
     * Constructor method
     */
    public function __construct($id = NULL, $callObjectLoad = TRUE)
    {
        parent::__construct($id, $callObjectLoad);
        parent::addAttribute('emissao');
        parent::addAttribute('retorno');
        parent::addAttribute('observacao');
        parent::addAttribute('cliente_id');
        parent::addAttribute('tabela_preco_id');
        parent::addAttribute('condicao_pagamento_id');
        parent::addAttribute('pedido_id');
        parent::addAttribute('vendedor_id');
        parent::addAttribute('estado_id');
        parent::addAttribute('municipio_id');
        parent::addAttribute('codigo_cliente');
        parent::addAttribute('telefone');
        parent::addAttribute('nome');
        parent::addAttribute('email');
        parent::addAttribute('orcamento_estado_id');
        parent::addAttribute('dt_cancelamento');
        parent::addAttribute('dt_faturamento');
        parent::addAttribute('latitude');
        parent::addAttribute('longitude');
        parent::addAttribute('valor_total');
        parent::addAttribute('sinc');
        parent::addAttribute('log_int');
        parent::addAttribute('dt_inclusao');
        parent::addAttribute('system_unit_id');
        parent::addAttribute('dt_alteracao');
        parent::addAttribute('system_user_id');
    
    }

    /**
     * Method set_cliente
     * Sample of usage: $var->cliente = $object;
     * @param $object Instance of Cliente
     */
    public function set_cliente(Cliente $object)
    {
        $this->cliente = $object;
        $this->cliente_id = $object->id;
    }

    /**
     * Method get_cliente
     * Sample of usage: $var->cliente->attribute;
     * @returns Cliente instance
     */
    public function get_cliente()
    {
    
        // loads the associated object
        if (empty($this->cliente))
            $this->cliente = new Cliente($this->cliente_id);
    
        // returns the associated object
        return $this->cliente;
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
     * Method set_pedido
     * Sample of usage: $var->pedido = $object;
     * @param $object Instance of Pedido
     */
    public function set_pedido(Pedido $object)
    {
        $this->pedido = $object;
        $this->pedido_id = $object->id;
    }

    /**
     * Method get_pedido
     * Sample of usage: $var->pedido->attribute;
     * @returns Pedido instance
     */
    public function get_pedido()
    {
    
        // loads the associated object
        if (empty($this->pedido))
            $this->pedido = new Pedido($this->pedido_id);
    
        // returns the associated object
        return $this->pedido;
    }
    /**
     * Method set_estado
     * Sample of usage: $var->estado = $object;
     * @param $object Instance of Estado
     */
    public function set_estado(Estado $object)
    {
        $this->estado = $object;
        $this->estado_id = $object->id;
    }

    /**
     * Method get_estado
     * Sample of usage: $var->estado->attribute;
     * @returns Estado instance
     */
    public function get_estado()
    {
    
        // loads the associated object
        if (empty($this->estado))
            $this->estado = new Estado($this->estado_id);
    
        // returns the associated object
        return $this->estado;
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
     * Method set_orcamento_estado
     * Sample of usage: $var->orcamento_estado = $object;
     * @param $object Instance of OrcamentoEstado
     */
    public function set_orcamento_estado(OrcamentoEstado $object)
    {
        $this->orcamento_estado = $object;
        $this->orcamento_estado_id = $object->id;
    }

    /**
     * Method get_orcamento_estado
     * Sample of usage: $var->orcamento_estado->attribute;
     * @returns OrcamentoEstado instance
     */
    public function get_orcamento_estado()
    {
    
        // loads the associated object
        if (empty($this->orcamento_estado))
            $this->orcamento_estado = new OrcamentoEstado($this->orcamento_estado_id);
    
        // returns the associated object
        return $this->orcamento_estado;
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
     * Method getOrcamentoItems
     */
    public function getOrcamentoItems()
    {
        $criteria = new TCriteria;
        $criteria->add(new TFilter('orcamento_id', '=', $this->id));
        return OrcamentoItem::getObjects( $criteria );
    }
    /**
     * Method getOrcamentoHistoricos
     */
    public function getOrcamentoHistoricos()
    {
        $criteria = new TCriteria;
        $criteria->add(new TFilter('orcamento_id', '=', $this->id));
        return OrcamentoHistorico::getObjects( $criteria );
    }
    /**
     * Method getPedidos
     */
    public function getPedidos()
    {
        $criteria = new TCriteria;
        $criteria->add(new TFilter('orcamento_id', '=', $this->id));
        return Pedido::getObjects( $criteria );
    }
    /**
     * Method getCalendarioOrcamentos
     */
    public function getCalendarioOrcamentos()
    {
        $criteria = new TCriteria;
        $criteria->add(new TFilter('orcamento_id', '=', $this->id));
        return CalendarioOrcamento::getObjects( $criteria );
    }

    public function set_orcamento_item_orcamento_to_string($orcamento_item_orcamento_to_string)
    {
        if(is_array($orcamento_item_orcamento_to_string))
        {
            $values = Orcamento::where('id', 'in', $orcamento_item_orcamento_to_string)->getIndexedArray('dt_faturamento', 'dt_faturamento');
            $this->orcamento_item_orcamento_to_string = implode(', ', $values);
        }
        else
        {
            $this->orcamento_item_orcamento_to_string = $orcamento_item_orcamento_to_string;
        }

        $this->vdata['orcamento_item_orcamento_to_string'] = $this->orcamento_item_orcamento_to_string;
    }

    public function get_orcamento_item_orcamento_to_string()
    {
        if(!empty($this->orcamento_item_orcamento_to_string))
        {
            return $this->orcamento_item_orcamento_to_string;
        }
    
        $values = OrcamentoItem::where('orcamento_id', '=', $this->id)->getIndexedArray('orcamento_id','{orcamento->dt_faturamento}');
        return implode(', ', $values);
    }

    public function set_orcamento_item_produto_to_string($orcamento_item_produto_to_string)
    {
        if(is_array($orcamento_item_produto_to_string))
        {
            $values = Produto::where('id', 'in', $orcamento_item_produto_to_string)->getIndexedArray('descricao', 'descricao');
            $this->orcamento_item_produto_to_string = implode(', ', $values);
        }
        else
        {
            $this->orcamento_item_produto_to_string = $orcamento_item_produto_to_string;
        }

        $this->vdata['orcamento_item_produto_to_string'] = $this->orcamento_item_produto_to_string;
    }

    public function get_orcamento_item_produto_to_string()
    {
        if(!empty($this->orcamento_item_produto_to_string))
        {
            return $this->orcamento_item_produto_to_string;
        }
    
        $values = OrcamentoItem::where('orcamento_id', '=', $this->id)->getIndexedArray('produto_id','{produto->descricao}');
        return implode(', ', $values);
    }

    public function set_orcamento_historico_orcamento_to_string($orcamento_historico_orcamento_to_string)
    {
        if(is_array($orcamento_historico_orcamento_to_string))
        {
            $values = Orcamento::where('id', 'in', $orcamento_historico_orcamento_to_string)->getIndexedArray('dt_faturamento', 'dt_faturamento');
            $this->orcamento_historico_orcamento_to_string = implode(', ', $values);
        }
        else
        {
            $this->orcamento_historico_orcamento_to_string = $orcamento_historico_orcamento_to_string;
        }

        $this->vdata['orcamento_historico_orcamento_to_string'] = $this->orcamento_historico_orcamento_to_string;
    }

    public function get_orcamento_historico_orcamento_to_string()
    {
        if(!empty($this->orcamento_historico_orcamento_to_string))
        {
            return $this->orcamento_historico_orcamento_to_string;
        }
    
        $values = OrcamentoHistorico::where('orcamento_id', '=', $this->id)->getIndexedArray('orcamento_id','{orcamento->dt_faturamento}');
        return implode(', ', $values);
    }

    public function set_orcamento_historico_orcamento_estado_to_string($orcamento_historico_orcamento_estado_to_string)
    {
        if(is_array($orcamento_historico_orcamento_estado_to_string))
        {
            $values = OrcamentoEstado::where('id', 'in', $orcamento_historico_orcamento_estado_to_string)->getIndexedArray('descricao', 'descricao');
            $this->orcamento_historico_orcamento_estado_to_string = implode(', ', $values);
        }
        else
        {
            $this->orcamento_historico_orcamento_estado_to_string = $orcamento_historico_orcamento_estado_to_string;
        }

        $this->vdata['orcamento_historico_orcamento_estado_to_string'] = $this->orcamento_historico_orcamento_estado_to_string;
    }

    public function get_orcamento_historico_orcamento_estado_to_string()
    {
        if(!empty($this->orcamento_historico_orcamento_estado_to_string))
        {
            return $this->orcamento_historico_orcamento_estado_to_string;
        }
    
        $values = OrcamentoHistorico::where('orcamento_id', '=', $this->id)->getIndexedArray('orcamento_estado_id','{orcamento_estado->descricao}');
        return implode(', ', $values);
    }

    public function set_orcamento_historico_orcamento_proximo_estado_to_string($orcamento_historico_orcamento_proximo_estado_to_string)
    {
        if(is_array($orcamento_historico_orcamento_proximo_estado_to_string))
        {
            $values = OrcamentoEstado::where('id', 'in', $orcamento_historico_orcamento_proximo_estado_to_string)->getIndexedArray('descricao', 'descricao');
            $this->orcamento_historico_orcamento_proximo_estado_to_string = implode(', ', $values);
        }
        else
        {
            $this->orcamento_historico_orcamento_proximo_estado_to_string = $orcamento_historico_orcamento_proximo_estado_to_string;
        }

        $this->vdata['orcamento_historico_orcamento_proximo_estado_to_string'] = $this->orcamento_historico_orcamento_proximo_estado_to_string;
    }

    public function get_orcamento_historico_orcamento_proximo_estado_to_string()
    {
        if(!empty($this->orcamento_historico_orcamento_proximo_estado_to_string))
        {
            return $this->orcamento_historico_orcamento_proximo_estado_to_string;
        }
    
        $values = OrcamentoHistorico::where('orcamento_id', '=', $this->id)->getIndexedArray('orcamento_proximo_estado_id','{orcamento_proximo_estado->descricao}');
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
    
        $values = Pedido::where('orcamento_id', '=', $this->id)->getIndexedArray('pedido_estado_id','{pedido_estado->id}');
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
    
        $values = Pedido::where('orcamento_id', '=', $this->id)->getIndexedArray('cliente_id','{cliente->razao}');
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
    
        $values = Pedido::where('orcamento_id', '=', $this->id)->getIndexedArray('cliente_entrega_id','{cliente_entrega->razao}');
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
    
        $values = Pedido::where('orcamento_id', '=', $this->id)->getIndexedArray('vendedor1_id','{vendedor1->nome}');
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
    
        $values = Pedido::where('orcamento_id', '=', $this->id)->getIndexedArray('vendedor2_id','{vendedor2->nome}');
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
    
        $values = Pedido::where('orcamento_id', '=', $this->id)->getIndexedArray('transportadora_id','{transportadora->id}');
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
    
        $values = Pedido::where('orcamento_id', '=', $this->id)->getIndexedArray('condicao_pagamento_id','{condicao_pagamento->descricao}');
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
    
        $values = Pedido::where('orcamento_id', '=', $this->id)->getIndexedArray('orcamento_id','{orcamento->dt_faturamento}');
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
    
        $values = Pedido::where('orcamento_id', '=', $this->id)->getIndexedArray('nota_saida_id','{nota_saida->id}');
        return implode(', ', $values);
    }

    public function set_calendario_orcamento_orcamento_to_string($calendario_orcamento_orcamento_to_string)
    {
        if(is_array($calendario_orcamento_orcamento_to_string))
        {
            $values = Orcamento::where('id', 'in', $calendario_orcamento_orcamento_to_string)->getIndexedArray('dt_faturamento', 'dt_faturamento');
            $this->calendario_orcamento_orcamento_to_string = implode(', ', $values);
        }
        else
        {
            $this->calendario_orcamento_orcamento_to_string = $calendario_orcamento_orcamento_to_string;
        }

        $this->vdata['calendario_orcamento_orcamento_to_string'] = $this->calendario_orcamento_orcamento_to_string;
    }

    public function get_calendario_orcamento_orcamento_to_string()
    {
        if(!empty($this->calendario_orcamento_orcamento_to_string))
        {
            return $this->calendario_orcamento_orcamento_to_string;
        }
    
        $values = CalendarioOrcamento::where('orcamento_id', '=', $this->id)->getIndexedArray('orcamento_id','{orcamento->dt_faturamento}');
        return implode(', ', $values);
    }

    public function delete($id = NULL)
    {
        $id = isset($id) ? $id: $this->id;
   
        parent::deleteComposite('OrcamentoItem', 'orcamento_id', $id);
        parent::deleteComposite('OrcamentoHistorico', 'orcamento_id', $id);
        parent::delete( $id );
    
    }

    public function get_cor()
    {

        if($this->sinc == "X"){
            $oOrcamento = OrcamentoEstado::where('id', '=', OrcamentoEstado::ERRO)->first();
        }else{
            if($this->vendedor_id == TSession::getValue("userid")){
                $oOrcamento = OrcamentoEstado::where('id', '=', $this->orcamento_estado_id)->first();
            }else{
                $oOrcamento = OrcamentoEstado::where('id', '=', OrcamentoEstado::OUTROS)->first();
            }
        }
        return $oOrcamento->cor;

    }

    public function get_icone_titulo_formatado()
    {

        if($this->sinc == "X"){
            $oOrcamento = OrcamentoEstado::where('id', '=', OrcamentoEstado::ERRO)->first();
            return "<i class='{$oOrcamento->icone}'></i> <b>Erro de Integração</b>";
        }else{
            if($this->vendedor_id == TSession::getValue("userid")){
                $oOrcamento = OrcamentoEstado::where('id', '=', $this->orcamento_estado_id)->first();
                return "<i class='{$oOrcamento->icone}'></i><b> {$this->titulo}</b><br>{$oOrcamento->descricao}";
            }else{
                $oOrcamento = OrcamentoEstado::where('id', '=', OrcamentoEstado::OUTROS)->first();
                $oOrcBase = OrcamentoEstado::where('id', '=', $this->orcamento_estado_id)->first();
                return "<i class='{$oOrcamento->icone}'></i> {$this->titulo}<br>{$oOrcBase->descricao}";
            }
        }

    }
        
}

