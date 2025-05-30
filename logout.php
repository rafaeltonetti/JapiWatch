<?php
session_start();
$_SESSION['mensagem'] = "Você escolheu sair";
session_destroy();
header("Location: index.php");
exit();
