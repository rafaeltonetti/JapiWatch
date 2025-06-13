<?php

session_start();

include 'conexao.php';

if (!isset($_SESSION["usuario_nome"])){
    header("location: login.php");
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $_SESSION["usuario_nome"] ?></title>
</head>
<body>

</body>
</html>