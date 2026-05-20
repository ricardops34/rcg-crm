<?php

class CustomAuthenticationService
{
    public static function authenticate($login, $password)
    {
        $user = SystemUsers::authenticate( $login, $password );
        
        
        TTransaction::open('erp_online');
        
        $oVendedor = Vendedor::where('status', '=', 'A')->where('system_users_id', '=', $user->id)->first();
        
        
        if($oVendedor)
        {
            TSession::setValue('supervisor_id'  , $oVendedor->supervisor_id);
            TSession::setValue('vendedor_id'    , $oVendedor->id);
            TSession::setValue('supervisor'     , $oVendedor->supervisor);
            TSession::setValue('vendedor'       , $oVendedor->vendedor);

        }else{
            TSession::setValue('supervisor_id'  , 0);
            TSession::setValue('vendedor_id'    , 0);
            TSession::setValue('supervisor'     , 'N');
            TSession::setValue('vendedor'       , 'N');
        }
        
        TTransaction::close();
    }
}