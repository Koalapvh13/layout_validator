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
        return strlen($this->vidas[$index]) > 0;
    }

    private function checkDependencia($index)
    {
        $dependencia = $this->vidas[$index];
        $dep_valida = intval($dependencia) < 100 && intval($dependencia) >=0;
        return is_numeric($dependencia) ? $dep_valida : false;
    }

    private function checkNome($index)
    {
        $nome = $this->vidas[$index];
        $conteudo = preg_replace('/[áàãâä]/ui', 'a', $nome); 
        $conteudo = preg_replace('/[éèêë]/ui', 'e', $conteudo);
        $conteudo = preg_replace('/[íìîï]/ui', 'i', $conteudo);
        $conteudo = preg_replace('/[óòõôö]/ui', 'o', $conteudo);
        $conteudo = preg_replace('/[úùûü]/ui', 'u', $conteudo);
        $conteudo = preg_replace('/[ç]/ui', 'c', $conteudo);
        return $conteudo == $nome;
    }

    private function checkData($index)
    {
        $data = $this->vidas[$index] ?? '00112222';
        $dia = substr($data,0,1);
        $mes = substr($data,2,3);
        $ano = substr($data,4,7);
        $dataValida = checkdate($mes, $dia, $ano);
        return strlen($data) > 0 ? $dataValida : false;
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
