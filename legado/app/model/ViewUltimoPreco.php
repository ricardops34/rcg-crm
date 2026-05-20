<?php

class ViewUltimoPreco extends TRecord
{
    const TABLENAME  = 'view_ultimo_preco';
    const PRIMARYKEY = 'id';
    const IDPOLICY   =  'max'; // {max, serial}

    /**
     * Constructor method
     */
    public function __construct($id = NULL, $callObjectLoad = TRUE)
    {
        parent::__construct($id, $callObjectLoad);
        parent::addAttribute('produto_id');
        parent::addAttribute('cliente_id');
        parent::addAttribute('nota_saida_id');
        parent::addAttribute('nota_fiscal');
        parent::addAttribute('serie_fiscal');
        parent::addAttribute('especie_fiscal');
        parent::addAttribute('quantidade');
        parent::addAttribute('vlr_unitario');
        parent::addAttribute('vlr_tabela');
        parent::addAttribute('vlr_desconto');
        parent::addAttribute('financeiro');
        parent::addAttribute('estoque');
        parent::addAttribute('vendedor_id');
        parent::addAttribute('tes');
        parent::addAttribute('dt_emissao');
    
    }

    public function get_saldo_estoque()
    {
        $nSaldo = 0 ;

        $oSaldos = Estoque::where('produto_id',  '=', $this->id)
                     ->orderBy('id')
                     ->load();    

        if ($oSaldos)
            {
                foreach($oSaldos  as $oSaldo )
                {
                     $nSaldo += $oSaldo->saldo;
                }
            }
        return $nSaldo;
    }

}

