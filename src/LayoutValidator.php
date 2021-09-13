<?php

class LayoutValidator
{
    private $vidas = [];
    private const TAMANHO_CODIGO_CONTRATO = 4;

    public function __construct($vidas)
    {
        $this->vidas = $vidas;
    }
    
    public function checkContrato($codigoContrato)
    {
        return strlen($codigoContrato) == self::TAMANHO_CODIGO_CONTRATO;
    }
}
