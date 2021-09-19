<?php

class LayoutValidator
{
    private $vidas = [];
    private const TAMANHO_CODIGO_CONTRATO = 4;
    private const QUANTIDADE_CAMPOS = 6;

    public function __construct($vidas)
    {
        $this->vidas = $vidas;
    }
    

    private function checkQtdCampos($index)
    {
        return count($this->vidas[$index]) == self::QUANTIDADE_CAMPOS;
    }

    private function checkContrato($codigoContrato)
    {
        return strlen($codigoContrato) == self::TAMANHO_CODIGO_CONTRATO;
    }

    private function checkFamilia($index)
    {
        return count($this->vidas[$index]) > 0;
    }

    private function checkDependencia($index)
    {
        $dependencia = $this->vidas[$index];
        return is_numeric($dependencia);
    }

    public function validadar()
    {
        $qtd_vidas = count($this->vidas);
        
        for($i =0; $i < $qtd_vidas; $i++){
            $qtd_campos = $this->checkQtdCampos($i);
            echo $qtd_campos ? null: "Quantidade de campos diferente do esperado!";

        }
    }
}
