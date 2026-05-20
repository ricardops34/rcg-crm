<?php

class ClienteVendedorMes extends TRecord
{
    const TABLENAME  = 'cliente_vendedor_mes';
    const PRIMARYKEY = 'id';
    const IDPOLICY   =  'serial'; // {max, serial}

    private Vendedor $vendedor;

    /**
     * Constructor method
     */
    public function __construct($id = NULL, $callObjectLoad = TRUE)
    {
        parent::__construct($id, $callObjectLoad);
        parent::addAttribute('filial_id');
        parent::addAttribute('empresa_id');
        parent::addAttribute('vendedor_id');
        parent::addAttribute('ano');
        parent::addAttribute('janeiro');
        parent::addAttribute('fevereiro');
        parent::addAttribute('marco');
        parent::addAttribute('abril');
        parent::addAttribute('maio');
        parent::addAttribute('junho');
        parent::addAttribute('julho');
        parent::addAttribute('agosto');
        parent::addAttribute('setembro');
        parent::addAttribute('outubro');
        parent::addAttribute('novembro');
        parent::addAttribute('dezembro');
        parent::addAttribute('total_carteira');
        parent::addAttribute('total_rcg');
        parent::addAttribute('total_avulso');
        parent::addAttribute('total_bloqueado');
        parent::addAttribute('vendedor_nome');
    
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

      public function onBeforeStore($object)
    {
        $id = $object->vendedor_id;
        $oVendedor = Vendedor::where('id', '=', $id)
                ->first();
    
        if($oVendedor){
            $cNome = ltrim(rtrim(ltrim($oVendedor->nome_reduzido)));
            $object->vendedor_nome = $cNome;
        }
    
    }

}

