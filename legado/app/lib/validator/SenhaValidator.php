<?php

class SenhaValidator extends TFieldValidator
{
    public function validate($label, $value, $parameters = NULL)
    {
        if(!$value)
        {
            throw new Exception("O campo {$label} é obrigatório");
        }  
    }
}
