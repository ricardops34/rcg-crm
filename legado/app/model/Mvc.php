<?php

class Mvc extends TRecord
{
    const TABLENAME  = 'mvc';
    const PRIMARYKEY = 'id';
    const IDPOLICY   =  'max'; // {max, serial}

    /**
     * Constructor method
     */
    public function __construct($id = NULL, $callObjectLoad = TRUE)
    {
        parent::__construct($id, $callObjectLoad);
        parent::addAttribute('codigo');
        parent::addAttribute('situacao');
        parent::addAttribute('razao');
        parent::addAttribute('fantasia');
        parent::addAttribute('primeira_compra');
        parent::addAttribute('ultima_compra');
        parent::addAttribute('ultima_visita');
        parent::addAttribute('ultimo_atendimento');
        parent::addAttribute('dt_bloqueio');
        parent::addAttribute('motivo_bloqueio');
        parent::addAttribute('carteira');
        parent::addAttribute('vendedor_id');
        parent::addAttribute('municipio_descricao');
        parent::addAttribute('estado_sigla');
        parent::addAttribute('vendedor_nome');
        parent::addAttribute('vendedor_reduzido');
        parent::addAttribute('system_user_id');
        parent::addAttribute('regiao_codigo');
        parent::addAttribute('regiao_descricao');
        parent::addAttribute('dias');
        parent::addAttribute('municipio_id');
        parent::addAttribute('estado_id');
        parent::addAttribute('regiao_id');
        parent::addAttribute('mes');
        parent::addAttribute('ano');
        parent::addAttribute('tres');
        parent::addAttribute('filial');
    

        //parent::addAttribute('venda_mes');
        //parent::addAttribute('venda_media_tres');

                            
    }

    public function get_venda_mes()
    {
        $nVenda  = 0 ;
        $DataBase = date("Y-m-d",strtotime(date("Y-m-d")."-1 month"));
    
        $oVendas = ViewVendaCliente::where('nota_saida_dt_emissao' , '>=', $DataBase)
                     ->where('cliente_id'           ,  '=', $this->id)
                     ->where('cliente_vendedor_id'  ,  '=', $this->vendedor_id)
                     ->orderBy('cliente_id')
                     ->load();    
        /*
            where('nota_saida_item_ano' ,  '=', $this->ano)
            ->where('nota_saida_item_mes'  ,  '=', $this->mes)
        */

        if ($oVendas)
            {
                foreach($oVendas  as $oVenda )
                {
                     $nVenda += $oVenda->nota_saida_item_vlr_total;
                }
            }
        
        return $nVenda;

    }

    public function get_venda_media_tres()
    {
        $nMeses = 3;
        $nVendaTres = 0;
        $DataTres = date("Y-m-d",strtotime(date("Y-m-d")."-".$nMeses." month"));
        $oVendasTres = ViewVendaCliente::where('nota_saida_dt_emissao' , '>=', $DataTres)
                     ->where('cliente_id'           ,  '=', $this->id)
                     ->where('cliente_vendedor_id'  ,  '=', $this->vendedor_id)
                     ->orderBy('cliente_id')
                     ->load();    

        if ($oVendasTres)
            {
                foreach($oVendasTres  as $oVendaTres )
                {
                     $nVendaTres += $oVendaTres->nota_saida_item_vlr_total;
                }
            }
        
        if($nVendaTres > 0){
            $nVendaTres = round($nVendaTres/$nMeses,2); 
        }
    
        return $nVendaTres;
    }

    public function get_dif_media()
    {

        $nVendaMes  = 0 ;
        $nVendaTres = 0 ;
        $nVendaDifs = 0 ;
    
        $nMeses = 3;
    
        $DataBase = date("Y-m-d",strtotime(date("Y-m-d")."-1 month"));
        $DataTres = date("Y-m-d",strtotime(date("Y-m-d")."-".$nMeses." month"));
    
        $oVendasMes = ViewVendaCliente::where('nota_saida_dt_emissao' , '>=', $DataBase)
                     ->where('cliente_id'           ,  '=', $this->id)
                     ->where('cliente_vendedor_id'  ,  '=', $this->vendedor_id)
                     ->orderBy('cliente_id')
                     ->load();    

        if ($oVendasMes)
            {
                foreach($oVendasMes  as $oVendaMes )
                {
                     $nVendaMes += $oVendaMes->nota_saida_item_vlr_total;
                }
            }
                
        $oVendasTres = ViewVendaCliente::where('nota_saida_dt_emissao' , '>=', $DataTres)
                     ->where('cliente_id'           ,  '=', $this->id)
                     ->where('cliente_vendedor_id'  ,  '=', $this->vendedor_id)
                     ->orderBy('cliente_id')
                     ->load();    

        if ($oVendasTres)
            {
                foreach($oVendasTres  as $oVendaTres )
                {
                     $nVendaTres += $oVendaTres->nota_saida_item_vlr_total;
                }
            }
        
        if($nVendaTres > 0){
            $nVendaTres = round($nVendaTres/$nMeses,2); 
        }
    
        $nVendaDifs = $nVendaMes - $nVendaTres;
    
        return $nVendaDifs;
    }

                        
}

