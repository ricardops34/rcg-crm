<?php

class Atendimento extends TRecord
{
    const TABLENAME  = 'atendimento';
    const PRIMARYKEY = 'id';
    const IDPOLICY   =  'serial'; // {max, serial}

    const CREATED_BY_USER_ID  = 'user_id_create';
    const UPDATED_BY_USER_ID  = 'user_id_update';
    const DELETED_BY_USER_ID  = 'user_id_delete';

    const DELETEDAT  = 'dt_delete';
    const CREATEDAT  = 'dt_inclusao';
    const UPDATEDAT  = 'dt_alteracao';

    private Vendedor $vendedor;
    private Cliente $cliente;
    private AtendimentoTipo $atendimento_tipo;

    /**
     * Constructor method
     */
    public function __construct($id = NULL, $callObjectLoad = TRUE)
    {
        parent::__construct($id, $callObjectLoad);
        parent::addAttribute('atendimento_tipo_id');
        parent::addAttribute('vendedor_id');
        parent::addAttribute('codigo_cliente');
        parent::addAttribute('cliente_id');
        parent::addAttribute('horario_inicial');
        parent::addAttribute('horario_final');
        parent::addAttribute('titulo');
        parent::addAttribute('cor');
        parent::addAttribute('retorno');
        parent::addAttribute('observacao');
        parent::addAttribute('dt_inclusao');
        parent::addAttribute('dt_alteracao');
        parent::addAttribute('dt_delete');
        parent::addAttribute('user_id_create');
        parent::addAttribute('user_id_update');
        parent::addAttribute('user_id_delete');
        parent::addAttribute('nome');
        parent::addAttribute('telefone');
        parent::addAttribute('email');
        parent::addAttribute('status');
        parent::addAttribute('orcamento_id');
        parent::addAttribute('nota_saida_id');
    
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
     * Method getNegociacaos
     */
    public function getNegociacaos()
    {
        $criteria = new TCriteria;
        $criteria->add(new TFilter('atendimento_id', '=', $this->id));
        return Negociacao::getObjects( $criteria );
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
    
        $values = Negociacao::where('atendimento_id', '=', $this->id)->getIndexedArray('cliente_id','{cliente->razao}');
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
    
        $values = Negociacao::where('atendimento_id', '=', $this->id)->getIndexedArray('vendedor_id','{vendedor->nome}');
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
    
        $values = Negociacao::where('atendimento_id', '=', $this->id)->getIndexedArray('atendimento_tipo_id','{atendimento_tipo->id}');
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
    
        $values = Negociacao::where('atendimento_id', '=', $this->id)->getIndexedArray('atendimento_id','{atendimento->id}');
        return implode(', ', $values);
    }

  
    public function get_valor_nota()
    {
    
        $cRetorno = "";
        // loads the associated object
        if (isset($this->nota_saida_id)){
            $oNota = new NotaSaida($this->nota_saida_id);
            if($oNota){
               $cRetorno = '<br><b>Nota Fiscal: '.$oNota->nota_fiscal.' R$ '.number_format($oNota->vlr_mercadoria, 2, ",", ".").'</b>';
            }
        }
        // returns the associated object
        return $cRetorno;
    }
  
    
}

