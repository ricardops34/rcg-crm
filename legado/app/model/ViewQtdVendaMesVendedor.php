<?php

class ViewQtdVendaMesVendedor extends TRecord
{
    const TABLENAME  = 'view_qtd_venda_mes_vendedor';
    const PRIMARYKEY = 'vendedor1_id';
    const IDPOLICY   =  'max'; // {max, serial}

    /**
     * Constructor method
     */
    public function __construct($id = NULL, $callObjectLoad = TRUE)
    {
        parent::__construct($id, $callObjectLoad);
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

  

    public function get_carteira()
    {
        $nCliente = 0 ;
        $oClientes = Cliente::where('vendedor_id',  '=', $this->vendedor1_id)
                    ->where('carteira', '=', 'S')
                    ->where('status', '=', 'A')
                    ->orderBy('id')
                    ->load();    

        if ($oClientes){
            foreach($oClientes  as $oCliente )
            {
                 $nCliente += 1;
            }
        }

        return $nCliente;
    }  

    public function get_avulso()
    {
        $nCliente = 0 ;
        $oClientes = Cliente::where('vendedor_id',  '=', $this->vendedor1_id)
                    ->where('carteira', '=', 'N')
                    ->where('status', '=', 'A')
                    ->orderBy('id')
                    ->load();    

        if ($oClientes){
            foreach($oClientes  as $oCliente )
            {
                 $nCliente += 1;
            }
        }

        return $nCliente;
    } 

    public function get_bloqueado()
    {
        $nCliente = 0 ;
        $oClientes = Cliente::where('vendedor_id',  '=', $this->vendedor1_id)
                    ->where('status', '=', 'B')
                    ->orderBy('id')
                    ->load();    

        if ($oClientes){
            foreach($oClientes  as $oCliente )
            {
                 $nCliente += 1;
            }
        }

        return $nCliente;
    } 

    public function get_nome_status(){
    
        $id = $this->vendedor1_id;
        $cReturn = "";
    
        $oVendedor = Vendedor::find( $id );
        if($oVendedor){    
            $cNome = rtrim(ltrim($oVendedor->nome_reduzido));
            if(empty($oVendedor->nome_reduzido)){
                $cNome =  rtrim(ltrim($oVendedor->nome));
            }

            $cReturn = '<b>'.$cNome.'</b>';;
            if($oVendedor->status == 'B'){ //fas fa-lock
                $icone = new TElement('i');
                $icone->class="fas fa-lock";
                $cReturn = $icone.'<s>'.$cNome.'</s>';
            }
        }
        return $cReturn;
    }

    //vlr_comodato

            
}

