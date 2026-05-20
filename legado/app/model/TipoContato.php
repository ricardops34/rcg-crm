<?php

class TipoContato extends TRecord
{
    const TABLENAME  = 'tipo_contato';
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
        parent::addAttribute('descricao');
        parent::addAttribute('dt_alteracao');
        parent::addAttribute('dt_inclusao');
            
    }

    /**
     * Method getClienteContatos
     */
    public function getClienteContatos()
    {
        $criteria = new TCriteria;
        $criteria->add(new TFilter('tipo_contato_id', '=', $this->id));
        return ClienteContato::getObjects( $criteria );
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
    
        $values = ClienteContato::where('tipo_contato_id', '=', $this->id)->getIndexedArray('cliente_id','{cliente->razao}');
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
    
        $values = ClienteContato::where('tipo_contato_id', '=', $this->id)->getIndexedArray('tipo_contato_id','{tipo_contato->id}');
        return implode(', ', $values);
    }

    /**
     * Method onBeforeDelete
     */
    public function onBeforeDelete()
    {
            

        if(ClienteContato::where('tipo_contato_id', '=', $this->id)->first())
        {
            throw new Exception("Não é possível deletar este registro pois ele está sendo utilizado em outra parte do sistema");
        }
    
    }

    
}

