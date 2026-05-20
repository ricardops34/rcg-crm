<?php

class TituloReceber extends TRecord
{
    const TABLENAME  = 'titulo_receber';
    const PRIMARYKEY = 'id';
    const IDPOLICY   =  'serial'; // {max, serial}

    const CREATEDAT  = 'dt_inclusao';
    const UPDATEDAT  = 'dt_alteracao';

    private Cliente $cliente;
    private Vendedor $vendedor;
    private Filial $filial;
    private Pedido $pedido;
    private NotaSaida $nota_fiscal;

    /**
     * Constructor method
     */
    public function __construct($id = NULL, $callObjectLoad = TRUE)
    {
        parent::__construct($id, $callObjectLoad);
        parent::addAttribute('dt_alteracao');
        parent::addAttribute('filial_id');
        parent::addAttribute('dt_inclusao');
        parent::addAttribute('cliente_id');
        parent::addAttribute('vendedor_id');
        parent::addAttribute('natureza_id');
        parent::addAttribute('emissao');
        parent::addAttribute('numero');
        parent::addAttribute('parcela');
        parent::addAttribute('prefixo');
        parent::addAttribute('tipo');
        parent::addAttribute('saldo');
        parent::addAttribute('valor');
        parent::addAttribute('decrescimo');
        parent::addAttribute('acrescimo');
        parent::addAttribute('valor_juros');
        parent::addAttribute('perc_juros');
        parent::addAttribute('mora_dia');
        parent::addAttribute('taxa_multa');
        parent::addAttribute('dt_digitacao');
        parent::addAttribute('vencimento');
        parent::addAttribute('venc_real');
        parent::addAttribute('venc_orig');
        parent::addAttribute('pedido_id');
        parent::addAttribute('banco');
        parent::addAttribute('agencia');
        parent::addAttribute('conta');
        parent::addAttribute('nosso_numero');
        parent::addAttribute('id_cnab');
        parent::addAttribute('cod_barras');
        parent::addAttribute('lin_digitavel');
        parent::addAttribute('forma_pgto');
        parent::addAttribute('controle_bco');
        parent::addAttribute('dig_nosso_numero');
        parent::addAttribute('impresso');
        parent::addAttribute('origem');
        parent::addAttribute('historico');
        parent::addAttribute('usr_inclusao');
        parent::addAttribute('usr_alteracao');
        parent::addAttribute('reg_ativo');
        parent::addAttribute('baixa');
        parent::addAttribute('system_unit_id');
        parent::addAttribute('e1_recno');
        parent::addAttribute('nota_fiscal_id');
        parent::addAttribute('vias');
        parent::addAttribute('situacao');
    
    
        // situacao => 0=Carteira;1=Cob.Simples;2=Descontada;3=Caucionada;4=Vinculada;5=Advogado;6=Judicial

    
    
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
     * Method set_nota_saida
     * Sample of usage: $var->nota_saida = $object;
     * @param $object Instance of NotaSaida
     */
    public function set_nota_fiscal(NotaSaida $object)
    {
        $this->nota_fiscal = $object;
        $this->nota_fiscal_id = $object->id;
    }

    /**
     * Method get_nota_fiscal
     * Sample of usage: $var->nota_fiscal->attribute;
     * @returns NotaSaida instance
     */
    public function get_nota_fiscal()
    {
    
        // loads the associated object
        if (empty($this->nota_fiscal))
            $this->nota_fiscal = new NotaSaida($this->nota_fiscal_id);
    
        // returns the associated object
        return $this->nota_fiscal;
    }

    /**
     * Method getNegociacaoTituloRecebers
     */
    public function getNegociacaoTituloRecebers()
    {
        $criteria = new TCriteria;
        $criteria->add(new TFilter('titulo_receber_id', '=', $this->id));
        return NegociacaoTituloReceber::getObjects( $criteria );
    }

    public function set_negociacao_titulo_receber_negociacao_to_string($negociacao_titulo_receber_negociacao_to_string)
    {
        if(is_array($negociacao_titulo_receber_negociacao_to_string))
        {
            $values = Negociacao::where('id', 'in', $negociacao_titulo_receber_negociacao_to_string)->getIndexedArray('id', 'id');
            $this->negociacao_titulo_receber_negociacao_to_string = implode(', ', $values);
        }
        else
        {
            $this->negociacao_titulo_receber_negociacao_to_string = $negociacao_titulo_receber_negociacao_to_string;
        }

        $this->vdata['negociacao_titulo_receber_negociacao_to_string'] = $this->negociacao_titulo_receber_negociacao_to_string;
    }

    public function get_negociacao_titulo_receber_negociacao_to_string()
    {
        if(!empty($this->negociacao_titulo_receber_negociacao_to_string))
        {
            return $this->negociacao_titulo_receber_negociacao_to_string;
        }
    
        $values = NegociacaoTituloReceber::where('titulo_receber_id', '=', $this->id)->getIndexedArray('negociacao_id','{negociacao->id}');
        return implode(', ', $values);
    }

    public function set_negociacao_titulo_receber_titulo_receber_to_string($negociacao_titulo_receber_titulo_receber_to_string)
    {
        if(is_array($negociacao_titulo_receber_titulo_receber_to_string))
        {
            $values = TituloReceber::where('id', 'in', $negociacao_titulo_receber_titulo_receber_to_string)->getIndexedArray('numero', 'numero');
            $this->negociacao_titulo_receber_titulo_receber_to_string = implode(', ', $values);
        }
        else
        {
            $this->negociacao_titulo_receber_titulo_receber_to_string = $negociacao_titulo_receber_titulo_receber_to_string;
        }

        $this->vdata['negociacao_titulo_receber_titulo_receber_to_string'] = $this->negociacao_titulo_receber_titulo_receber_to_string;
    }

    public function get_negociacao_titulo_receber_titulo_receber_to_string()
    {
        if(!empty($this->negociacao_titulo_receber_titulo_receber_to_string))
        {
            return $this->negociacao_titulo_receber_titulo_receber_to_string;
        }
    
        $values = NegociacaoTituloReceber::where('titulo_receber_id', '=', $this->id)->getIndexedArray('titulo_receber_id','{titulo_receber->numero}');
        return implode(', ', $values);
    }

    public function get_portador()
    {
        $cPortador = 'Carteira';
        if ($this->situacao == '0' ){
            $cPortador = 'Carteira';
        }elseif ($this->situacao == '1' ){
            $cPortador = 'Cobrança Simples';
        }elseif ($this->situacao == '2' ){
            $cPortador = 'Cobrança Descontada';
        }elseif ($this->situacao == '3' ){
            $cPortador = 'Cobrança Caucionada';
        }elseif ($this->situacao == '4' ){
            $cPortador = 'Cobrança Vinculada';
        }elseif ($this->situacao == '5' ){
            $cPortador = 'Cobrança Advogado';
        }elseif ($this->situacao == '6' ){
            $cPortador = 'Cobrança Judicial';
        }
        // situacao => 0=Carteira;1=Cob.Simples;2=Descontada;3=Caucionada;4=Vinculada;5=Advogado;6=Judicial
        return $cPortador;
    }

}

