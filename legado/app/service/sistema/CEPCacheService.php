<?php

class CEPCacheService
{
    public static function get($cep)
    {
        return CepCache::where('cep', '=', $cep)->first();
    }

    public static function save($cepInfo)
    {
        $cepCache = new CepCache();

        $cepCache->codigo_ibge = $cepInfo->cidade_cod_ibge;
        $cepCache->rua = SisFunction::NoAcento($cepInfo->rua);
        $cepCache->cidade = SisFunction::NoAcento($cepInfo->cidade);
        $cepCache->bairro = SisFunction::NoAcento($cepInfo->bairro);
        $cepCache->uf = $cepInfo->uf;
        $cepCache->cep = $cepInfo->cep;
        $cepCache->cidade_id = $cepInfo->cidade_id;
        $cepCache->estado_id = $cepInfo->estado_id;

        $cepCache->store();
    }

}
