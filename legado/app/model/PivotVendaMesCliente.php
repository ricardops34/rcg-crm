<?php

class PivotVendaMesCliente extends TRecord
{
    const TABLENAME  = 'pivot_venda_mes_cliente';
    const PRIMARYKEY = 'cliente_id';
    const IDPOLICY   =  'max'; // {max, serial}

    private Cliente $cliente;
    private Vendedor $vendedor;

                    

    /**
     * Constructor method
     */
    public function __construct($id = NULL, $callObjectLoad = TRUE)
    {
        parent::__construct($id, $callObjectLoad);
        parent::addAttribute('cliente_vendedor_id');
        parent::addAttribute('nota_saida_vendedor_id');
        parent::addAttribute('cliente_nome');
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
    
    }

    public function set_cliente(Cliente $object)
    {
        $this->cliente = $object;
        $this->cliente_id = $object->cliente_id;
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

    public function set_vendedor(Vendedor $object)
    {
        $this->vendedor = $object;
        $this->cliente_vendedor_id = $object->id;
    }
    public function get_vendedor()
    {
    
        // loads the associated object
        if (empty($this->vendedor))
            $this->vendedor = new Vendedor($this->cliente_vendedor_id);
    
        // returns the associated object
        return $this->vendedor;
    }
    public function get_dias_compra(){
    
        $ndias = 9999;
    
        $oCliente = new Cliente($this->cliente_id);
    
        if(isset($oCliente->ultima_compra)){
            $data_inicio = new DateTime($oCliente->ultima_compra); 
    
            $data_fim = new DateTime();

            $dateInterval = $data_inicio->diff($data_fim);
            //$this->dias_compra = $dateInterval->days;
    
            $ndias = $dateInterval->days;
        }elseif(isset($oCliente->data_cadastro)){
        
            $data_inicio = new DateTime($oCliente->data_cadastro);
    
            $data_fim = new DateTime();

            $dateInterval = $data_inicio->diff($data_fim);
            //$this->dias_compra = $dateInterval->days;
    
            $ndias = $dateInterval->days;
        }
        return $ndias;
    
    }

    public function get_dias_visita(){
    
        $ndias = 9999;
    
        $oCliente = new Cliente($this->cliente_id);
    
        if(isset($oCliente->ultima_visita)){
            $data_inicio = new DateTime($oCliente->ultima_visita); 
    
            $data_fim = new DateTime();

            $dateInterval = $data_inicio->diff($data_fim);
            //$this->dias_compra = $dateInterval->days;
    
            $ndias = $dateInterval->days;
        }elseif(isset($oCliente->data_cadastro)){
        
            $data_inicio = new DateTime($oCliente->data_cadastro);
    
            $data_fim = new DateTime();

            $dateInterval = $data_inicio->diff($data_fim);
            //$this->dias_compra = $dateInterval->days;
    
            $ndias = $dateInterval->days;
        }
        return $ndias;
    
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

                        
}

