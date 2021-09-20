<?php

class LayoutValidator
{
    private $vidas = [];
    private const TAMANHO_CODIGO_CONTRATO = 4;
    private const QUANTIDADE_CAMPOS = 60;

    public function __construct($vidas)
    {
        $this->vidas = $vidas;
    }


    private function checkQtdCampos($index)
    {
        return count($this->vidas[$index]) == self::QUANTIDADE_CAMPOS;
    }

    private function checkContrato($index, $campo)
    {
        return strlen($this->vidas[$index][$campo]) == self::TAMANHO_CODIGO_CONTRATO;
    }

    private function checkFamilia($index, $campo)
    {
        return strlen($this->vidas[$index][$campo]) > 0;
    }

    private function checkDependencia($index, $campo)
    {
        $dependencia = $this->vidas[$index][$campo];
        $dep_valida = intval($dependencia) < 100 && intval($dependencia) >= 0;
        return is_numeric($dependencia) ? $dep_valida : false;
    }

    private function checkNome($index, $campo)
    {
        $nome = $this->vidas[$index][$campo];
        $conteudo = preg_replace('/[áàãâä]/ui', 'a', $nome);
        $conteudo = preg_replace('/[éèêë]/ui', 'e', $conteudo);
        $conteudo = preg_replace('/[íìîï]/ui', 'i', $conteudo);
        $conteudo = preg_replace('/[óòõôö]/ui', 'o', $conteudo);
        $conteudo = preg_replace('/[úùûü]/ui', 'u', $conteudo);
        $conteudo = preg_replace('/[ç]/ui', 'c', $conteudo);
        return $conteudo == $nome;
    }

    private function checkData($index, $campo)
    {
        $data = $this->vidas[$index][$campo];
        $dia = substr($data, 0, 1);
        $mes = substr($data, 2, 3);
        $ano = substr($data, 4, 7);
        $dataValida = checkdate($mes, $dia, $ano);
        return strlen($data) > 0 ? $dataValida : false;
    }

    private function checkSexo($index, $campo)
    {
        $sexo = $this->vidas[$index][$campo];
        return strlen($sexo) > 0;
    }

    private function checkCPF($index, $campo)
    {

        $cpf = $this->vidas[$index][$campo];
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

    private function checkRG($index, $campo)
    {
        //RG está preenchido e os campos 09, 34, 35, 49 também. Caso o RG não conste, os campos 09, 34, 35, 49 também estarão vazios.
        $rg = $this->vidas[$index][$campo];
        return strlen($rg) > 0;
    }

    private function checkUfRG($index, $campo)
    {
        $uf_rg = $this->vidas[$index][$campo];
        return strlen($uf_rg) > 0;
    }

    private function checkOrgEmissorRG($index, $campo)
    {
        $orgEmissor = $this->vidas[$index][$campo];
        return strlen($orgEmissor) > 0;
    }

    private function checkPaisEmissorRG($index, $campo)
    {
        $paisEmissor = $this->vidas[$index][$campo];
        return strlen($paisEmissor) == 3;
    }

    public function validadar()
    {
        $qtd_vidas = count($this->vidas);
        $erros = [];
        $qtdErros = 0;

        for ($i = 0; $i < $qtd_vidas; $i++) {
            $linha = $i + 1;
            $qtd_campos = $this->checkQtdCampos($i);
            $errosInicio = count($erros);
            if(!$qtd_campos){
                array_push($erros, "Linha ".$linha.": Quantidade de campos diferente do esperado!\n");
                continue;
            }

            $contrato = $this->checkContrato($i, 0);
            $familia = $this->checkFamilia($i, 1);
            $dependencia = $this->checkDependencia($i, 2);
            $nome = $this->checkNome($i, 3);
            $dataNascimeto = $this->checkData($i, 4);
            $sexo = $this->checkSexo($i, 5);
            $cpf = $this->checkCPF($i, 6);
            $rg = $this->checkRG($i, 7);
            //validacoes RG
            $ufRG = $this->checkUfRG($i, 8);
            $orgEmissorRG = $this->checkOrgEmissorRG($i, 33);
            $paisEmissor = $this->checkPaisEmissorRG($i, 34);
            $dataExpedicaoRG = $this->checkData($i, 48);

            $contrato ? null : array_push($erros, "Linha ".$linha.": Contrato inválido.\n");
            $familia ? null : array_push($erros, "Linha ".$linha.": O número da família deve ser informado.\n");
            $dependencia ? null : array_push($erros, "Linha ".$linha.": A dependência informada não existe.\n");
            $nome ? null : array_push($erros, "Linha ".$linha.": O nome do beneficiário não deve conter acentos e/ou caracteres especiais.\n");
            $dataNascimeto ? null : array_push($erros, "Linha ".$linha.": Data de nascimento informada não segue o padrão DDMMAAA.\n"); //TODO: Melhorar validação para pegar os casos de data sem o primeiro erro
            $sexo ? null : array_push($erros, "Linha ".$linha.": O sexo deve ser informado.\n"); // TODO: Melhorar validação para verificar se recebeu apenas 1 caractere e se este é M ou F.
            $cpf ? null : array_push($erros, "Linha ".$linha.": O CPF informado é inválido.\n");
            
            //RG, Testa 09, 34, 35 e 49 junto
            if($rg){
                $ufRG ? null : array_push($erros, "Linha ".$linha.": UF do RG não informado.\n");
                $orgEmissorRG ? null : array_push($erros, "Linha ".$linha.": Orgão Emissor do RG não informado.\n");
                $paisEmissor ? null : array_push($erros, "Linha ".$linha.": País Emissor do RG não informado.\n");
                $dataExpedicaoRG ? null : array_push($erros, "Linha ".$linha.": Data de Expedição do RG Inválida.\n");
            } else{
                array_push($erros, "Linha ".$linha.": Número do RG não informado.\n");
                $ufRG ? null : array_push($erros, "Linha ".$linha.": UF do RG preenchido, mas RG não informado.\n");
                $orgEmissorRG ? null : array_push($erros, "Linha ".$linha.": Orgão Emissor preenchido, mas RG não informado.\n");
                $paisEmissor ? null : array_push($erros, "Linha ".$linha.": País Emissor do RG preenchido, mas RG não informado..\n");
                $dataExpedicaoRG ? null : array_push($erros, "Linha ".$linha.": Data de Expedição do RG preenchida, mas RG não informado.\n");
            }
            $qtdErros = $qtdErros + (count($erros) - $errosInicio);
        }

        return [ "qtdErros" => $qtdErros, "erros" => $erros];
    }
}
