<?php

class ClienteNotafiscal extends TRecord
{
    const TABLENAME  = 'cliente_notafiscal';
    const PRIMARYKEY = 'id';
    const IDPOLICY   =  'max'; // {max, serial}

    private $cliente;
    private $condicao_pagamento;
    private $vendedor;

        

    /**
     * Constructor method
     */
    public function __construct($id = NULL, $callObjectLoad = TRUE)
    {
        parent::__construct($id, $callObjectLoad);
        parent::addAttribute('nota_fiscal');
        parent::addAttribute('serie_fiscal');
        parent::addAttribute('especie_fiscal');
        parent::addAttribute('condicao_id');
        parent::addAttribute('dt_emissao');
        parent::addAttribute('vlr_bruto');
        parent::addAttribute('vlr_mercadoria');
        parent::addAttribute('vlr_desconto');
        parent::addAttribute('vlr_comodato');
        parent::addAttribute('vlr_itens');
        parent::addAttribute('vlr_devolucao');
        parent::addAttribute('vlr_frete');
        parent::addAttribute('vendedor1_id');
        parent::addAttribute('cliente_id');
        parent::addAttribute('cod_erp');
        parent::addAttribute('razao');
        parent::addAttribute('fantasia');
        parent::addAttribute('vendedor_id');
        parent::addAttribute('cod_erp_vendedor');
        parent::addAttribute('nome');
        parent::addAttribute('nome_reduzido');
        parent::addAttribute('ano');
        parent::addAttribute('mes');
        parent::addAttribute('comodato');
        parent::addAttribute('dias');
    
    }

    public function get_condicao_pagamento() {
    
        if (empty($this->condicao_pagamento))
            $this->condicao_pagamento = new CondicaoPagamento($this->condicao_id);
    
        // returns the associated object
        return $this->condicao_pagamento;    

    }

    public function get_cliente() {
    
        if (empty($this->cliente))
            $this->cliente = new Cliente($this->cliente_id);
    
        // returns the associated object
        return $this->cliente;    

    }

    public function get_vendedor() {
    
        if (empty($this->vendedor))
            $this->vendedor = new Cliente($this->vendedor_id);
    
        // returns the associated object
        return $this->vendedor;    

    }

    public function get_mesano() {
    
        $mesano = $this->mes.'/'.$this->ano;
    
        // returns the associated object
        return $mesano;    

    }
        
}

