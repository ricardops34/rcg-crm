<?php

class NotaEntradaItem extends TRecord
{
    const TABLENAME  = 'nota_entrada_item';
    const PRIMARYKEY = 'id';
    const IDPOLICY   =  'serial'; // {max, serial}

    private NotaEntrada $nota_entrada;

    

    /**
     * Constructor method
     */
    public function __construct($id = NULL, $callObjectLoad = TRUE)
    {
        parent::__construct($id, $callObjectLoad);
        parent::addAttribute('nota_entrada_id');
        parent::addAttribute('item');
        parent::addAttribute('produto_id');
        parent::addAttribute('qtd');
        parent::addAttribute('vlr_unitario');
        parent::addAttribute('tes_id');
        parent::addAttribute('vlr_tabela');
        parent::addAttribute('vlr_bruto');
        parent::addAttribute('base_icms');
        parent::addAttribute('aliq_icms');
        parent::addAttribute('vlr_icms');
        parent::addAttribute('base_ipi');
        parent::addAttribute('aliq_ipi');
        parent::addAttribute('vlr_ipi');
        parent::addAttribute('vlr_total');
        parent::addAttribute('vlr_dev');
        parent::addAttribute('qtd_dev');
        parent::addAttribute('perc_desconto');
        parent::addAttribute('vlr_desconto');
        parent::addAttribute('peso');
        parent::addAttribute('pedido_item_id');
        parent::addAttribute('reg_ativo');
        parent::addAttribute('tes');
        parent::addAttribute('estoque');
        parent::addAttribute('financeiro');
        parent::addAttribute('ano');
        parent::addAttribute('mes');
        parent::addAttribute('cliente_id');
        parent::addAttribute('vendedor1_id');
        parent::addAttribute('vendedor2_id');
        parent::addAttribute('dt_emissao');
        parent::addAttribute('cfop');
        parent::addAttribute('perc_comissao');
        parent::addAttribute('comissao');
        parent::addAttribute('comodato');
            
    }

    /**
     * Method set_nota_entrada
     * Sample of usage: $var->nota_entrada = $object;
     * @param $object Instance of NotaEntrada
     */
    public function set_nota_entrada(NotaEntrada $object)
    {
        $this->nota_entrada = $object;
        $this->nota_entrada_id = $object->id;
    }

    /**
     * Method get_nota_entrada
     * Sample of usage: $var->nota_entrada->attribute;
     * @returns NotaEntrada instance
     */
    public function get_nota_entrada()
    {
    
        // loads the associated object
        if (empty($this->nota_entrada))
            $this->nota_entrada = new NotaEntrada($this->nota_entrada_id);
    
        // returns the associated object
        return $this->nota_entrada;
    }

    
}

