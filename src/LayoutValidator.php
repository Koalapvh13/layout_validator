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
        $dep_valida = intval($dependencia) < 100 && intval($dependencia) >= 0;
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
        $data = $this->vidas[$index];
        $dia = substr($data, 0, 1);
        $mes = substr($data, 2, 3);
        $ano = substr($data, 4, 7);
        $dataValida = checkdate($mes, $dia, $ano);
        return strlen($data) > 0 ? $dataValida : false;
    }

    private function checkSexo($index)
    {
        $sexo = $this->vidas[$index];
        return strlen($sexo) > 0;
    }

    private function checkCPF($index)
    {

        $cpf = $this->vidas[$index];
        // Extrai somente os números
        $cpf = preg_replace('/[^0-9]/is', '', $cpf);

        // Verifica se foi informado todos os digitos corretamente
        if (strlen($cpf) != 11) {
            return false;
        }

        // Verifica se foi informada uma sequência de digitos repetidos. Ex: 111.111.111-11
        if (preg_match('/(\d)\1{10}/', $cpf)) {
            return false;
        }

        // Faz o calculo para validar o CPF
        for ($t = 9; $t < 11; $t++) {
            for ($d = 0, $c = 0; $c < $t; $c++) {
                $d += $cpf[$c] * (($t + 1) - $c);
            }
            $d = ((10 * $d) % 11) % 10;
            if ($cpf[$c] != $d) {
                return false;
            }
        }
        return true;
    }

    private function checkRG($index)
    {
        //RG está preenchido e os campos 09, 34, 35, 49 também. Caso o RG não conste, os campos 09, 34, 35, 49 também estarão vazios.
        $rg = $this->vidas[$index];
        return strlen($rg) > 0;
    }

    private function checkUfRG($index)
    {
        $uf_rg = $this->vidas[$index];
        return strlen($uf_rg) > 0;
    }

    private function checkOrgEmissorRG($index)
    {
        $orgEmissor = $this->vidas[$index];
        return strlen($orgEmissor) > 0;
    }

    private function checkPaisEmissorRG($index)
    {
        $paisEmissor = $this->vidas[$index];
        return strlen($paisEmissor) == 3;
    }

    public function validadar()
    {
        $qtd_vidas = count($this->vidas);

        for ($i = 0; $i < $qtd_vidas; $i++) {
            $qtd_campos = $this->checkQtdCampos($i);
            echo $qtd_campos ? null : "Quantidade de campos diferente do esperado!";

            //RG, Testa 09, 34, 35 e 49 junto
        }
    }
}
