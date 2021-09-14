<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Validador de Layout INFOMED</title>
</head>
<body>
    <form action="src/receiver.php" method="post" enctype="multipart/form-data">
        <label for="sendtxt">Enviar layout para validação</label>
        <br><br>
        <input type="file" name="sendtxt" id="sendtxt"  accept=".txt">
        <br><br>
        <input type="submit" value="Enviar TXT">
    </form>
</body>
</html>