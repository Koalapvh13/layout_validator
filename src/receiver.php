<?php

require_once('LayoutValidator.php');

function validaInput($file){
    return ($file['type'] == 'text/plain' && $file['size'] <= 2097152);
}


function txtToArray($file_path)
{
    $vidas = [];

    $content = file_get_contents($file_path);
    $texts = explode("\n", $content);
    //var_dump($content);
    foreach ($texts as $row) {
        if ($row != '') {
            array_push($vidas, explode(",", $row));
        }
    }

    return $vidas;
    
}

$file = $_FILES['sendtxt'];
$path = $file['tmp_name'];
$is_valid = validaInput($file);


if ($is_valid) {
    $arrVidas = txtToArray($path);
    $validador = new LayoutValidator($arrVidas);
    $validacao = $validador->validar();
    
    echo "<h1>".$validacao["qtdErros"]." Erros encontrados!</h1>";
    echo "<ul>";
    foreach ($validacao["erros"] as $erro) {
        echo "<li>".$erro."</li>";
    }
    echo "</ul>";
    //header('Location: /layout_validator/?err=5');
} else {
    //header('Location: /erro.php');
}
