<?php

class Negociacao extends TRecord
{
    const TABLENAME  = 'negociacao';
    const PRIMARYKEY = 'id';
    const IDPOLICY   =  'serial'; // {max, serial}

    const CREATEDAT  = 'dt_inclusao';
    const UPDATEDAT  = 'dt_alteracao';

    private Cliente $cliente;
    private Vendedor $vendedor;
    private Atendimento $atendimento;
    private AtendimentoTipo $atendimento_tipo;

    

    /**
     * Constructor method
     */
    public function __construct($id = NULL, $callObjectLoad = TRUE)
    {
        parent::__construct($id, $callObjectLoad);
        parent::addAttribute('filial_id');
        parent::addAttribute('cod_erp');
        parent::addAttribute('dt_alteracao');
        parent::addAttribute('dt_inclusao');
        parent::addAttribute('cliente_id');
        parent::addAttribute('vendedor_id');
        parent::addAttribute('atendimento_tipo_id');
        parent::addAttribute('observacao');
        parent::addAttribute('system_unit_id');
        parent::addAttribute('system_users_id');
        parent::addAttribute('tipo');
        parent::addAttribute('status');
        parent::addAttribute('atendimento_id');
            
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
     * Method set_atendimento
     * Sample of usage: $var->atendimento = $object;
     * @param $object Instance of Atendimento
     */
    public function set_atendimento(Atendimento $object)
    {
        $this->atendimento = $object;
        $this->atendimento_id = $object->id;
    }

    /**
     * Method get_atendimento
     * Sample of usage: $var->atendimento->attribute;
     * @returns Atendimento instance
     */
    public function get_atendimento()
    {
    
        // loads the associated object
        if (empty($this->atendimento))
            $this->atendimento = new Atendimento($this->atendimento_id);
    
        // returns the associated object
        return $this->atendimento;
    }
    /**
     * Method set_atendimento_tipo
     * Sample of usage: $var->atendimento_tipo = $object;
     * @param $object Instance of AtendimentoTipo
     */
    public function set_atendimento_tipo(AtendimentoTipo $object)
    {
        $this->atendimento_tipo = $object;
        $this->atendimento_tipo_id = $object->id;
    }

    /**
     * Method get_atendimento_tipo
     * Sample of usage: $var->atendimento_tipo->attribute;
     * @returns AtendimentoTipo instance
     */
    public function get_atendimento_tipo()
    {
    
        // loads the associated object
        if (empty($this->atendimento_tipo))
            $this->atendimento_tipo = new AtendimentoTipo($this->atendimento_tipo_id);
    
        // returns the associated object
        return $this->atendimento_tipo;
    }

    /**
     * Method getNegociacaoTituloRecebers
     */
    public function getNegociacaoTituloRecebers()
    {
        $criteria = new TCriteria;
        $criteria->add(new TFilter('negociacao_id', '=', $this->id));
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
    
        $values = NegociacaoTituloReceber::where('negociacao_id', '=', $this->id)->getIndexedArray('negociacao_id','{negociacao->id}');
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
    
        $values = NegociacaoTituloReceber::where('negociacao_id', '=', $this->id)->getIndexedArray('titulo_receber_id','{titulo_receber->numero}');
        return implode(', ', $values);
    }

    
}

