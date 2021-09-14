<?php
function txtToArray($file)
{
    $vidas = [];

    if ($file['type'] == 'text/plain' && $file['size'] <= 2097152) {

        $content = file_get_contents($file['tmp_name']);
        $texts = explode("\n", $content);
        //var_dump($content);
        foreach ($texts as $row) {
            if ($row != '') {
                array_push($vidas, explode(",", $row));
            }
        }

        return $vidas;
    } else {
        return null;
    }
}

$file = $_FILES['sendtxt'];

$arrVidas = txtToArray($file);

if($arrVidas != null){
    header('Location: /layout_validator/?err=5');
} else {
    header('Location: /erro.php');
}