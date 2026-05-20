<?php

class Supervisor extends TRecord
{
    const TABLENAME  = 'supervisor';
    const PRIMARYKEY = 'id';
    const IDPOLICY   =  'serial'; // {max, serial}

    

    /**
     * Constructor method
     */
    public function __construct($id = NULL, $callObjectLoad = TRUE)
    {
        parent::__construct($id, $callObjectLoad);
        parent::addAttribute('filial_id');
        parent::addAttribute('cod_erp');
        parent::addAttribute('system_users_id');
        parent::addAttribute('nome');
        parent::addAttribute('nome_reduzido');
        parent::addAttribute('ddd');
        parent::addAttribute('telefone');
        parent::addAttribute('celular');
        parent::addAttribute('email');
        parent::addAttribute('status');
        parent::addAttribute('vendedor');
        parent::addAttribute('dashboard');
        parent::addAttribute('dt_alteracao');
        parent::addAttribute('dt_inclusao');
        parent::addAttribute('dt_nascmento');
        parent::addAttribute('system_unit_id');
        parent::addAttribute('desligado');
            
    }

    /**
     * Method getSupervisorVendedors
     */
    public function getSupervisorVendedors()
    {
        $criteria = new TCriteria;
        $criteria->add(new TFilter('supervisor_id', '=', $this->id));
        return SupervisorVendedor::getObjects( $criteria );
    }

    public function set_supervisor_vendedor_vendedor_to_string($supervisor_vendedor_vendedor_to_string)
    {
        if(is_array($supervisor_vendedor_vendedor_to_string))
        {
            $values = Vendedor::where('id', 'in', $supervisor_vendedor_vendedor_to_string)->getIndexedArray('nome', 'nome');
            $this->supervisor_vendedor_vendedor_to_string = implode(', ', $values);
        }
        else
        {
            $this->supervisor_vendedor_vendedor_to_string = $supervisor_vendedor_vendedor_to_string;
        }

        $this->vdata['supervisor_vendedor_vendedor_to_string'] = $this->supervisor_vendedor_vendedor_to_string;
    }

    public function get_supervisor_vendedor_vendedor_to_string()
    {
        if(!empty($this->supervisor_vendedor_vendedor_to_string))
        {
            return $this->supervisor_vendedor_vendedor_to_string;
        }
    
        $values = SupervisorVendedor::where('supervisor_id', '=', $this->id)->getIndexedArray('vendedor_id','{vendedor->nome}');
        return implode(', ', $values);
    }

    public function set_supervisor_vendedor_supervisor_to_string($supervisor_vendedor_supervisor_to_string)
    {
        if(is_array($supervisor_vendedor_supervisor_to_string))
        {
            $values = Supervisor::where('id', 'in', $supervisor_vendedor_supervisor_to_string)->getIndexedArray('nome_reduzido', 'nome_reduzido');
            $this->supervisor_vendedor_supervisor_to_string = implode(', ', $values);
        }
        else
        {
            $this->supervisor_vendedor_supervisor_to_string = $supervisor_vendedor_supervisor_to_string;
        }

        $this->vdata['supervisor_vendedor_supervisor_to_string'] = $this->supervisor_vendedor_supervisor_to_string;
    }

    public function get_supervisor_vendedor_supervisor_to_string()
    {
        if(!empty($this->supervisor_vendedor_supervisor_to_string))
        {
            return $this->supervisor_vendedor_supervisor_to_string;
        }
    
        $values = SupervisorVendedor::where('supervisor_id', '=', $this->id)->getIndexedArray('supervisor_id','{supervisor->nome_reduzido}');
        return implode(', ', $values);
    }

    
}

