<?php
session_start();
include 'conexao.php';

$mensagem = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (!isset($_POST['email']) || !isset($_POST['senha'])) {
        $mensagem = '<div class="alert alert-danger">Preencha todos os campos!</div>';
    } else {
        $email = trim($_POST['email']);
        $senha = trim($_POST['senha']);

        if (empty($email) || empty($senha)) {
            $mensagem = '<div class="alert alert-danger">Preencha todos os campos!</div>';
        } else {
            $stmt = $conn->prepare("SELECT ID_Usuario, Nome_Completo, Senha FROM usuario WHERE Email = ?");
            $stmt->bind_param("s", $email);
            $stmt->execute();
            $resultado = $stmt->get_result();

            if ($resultado->num_rows > 0) {
                $usuario = $resultado->fetch_assoc();
                
                if (password_verify($senha, $usuario['Senha'])) {
                    $_SESSION['ID_Usuario'] = $usuario['ID_Usuario'];
                    $_SESSION['Nome_Completo'] = $usuario['Nome_Completo'];
                    header("Location: index.php");
                    exit();
                } else {
                    $mensagem = '<div class="alert alert-danger">Email ou senha incorretos!</div>';
                }
            } else {
                $mensagem = '<div class="alert alert-danger">Email ou senha incorretos!</div>';
            }
            $stmt->close();
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>JapiWatch - Login</title>
    <link rel="stylesheet" href="style.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-4Q6Gf2aSP4eDXB8Miphtr37CMZZQ5oXLH2yaXMJ2w8e2ZtHTl7GptT4jmndRuHDT" crossorigin="anonymous">
</head>
<body class="body">
    <div class="bloco">
        <div class="d-flex justify-content-center align-items-center">
            <div class="container">
                <center>
                    <h1 class="display-1">JapiWatch</h1>
                    <h5>Login</h5>
                </center>
                <?= $mensagem ?>
                <form method="post">
                    <div class="mb-3">
                        <label for="exampleFormControlInput1" class="form-label">Endereço de email</label>
                        <input type="email" name="email" class="form-control" id="exampleFormControlInput1" placeholder="nome@email.com">
                    </div>
                    <div class="mb-3">
                        <label for="exampleFormControlInput1" class="form-label">Senha</label>
                        <input type="password" name="senha" class="form-control" id="exampleFormControlInput1" placeholder="Digite sua senha">
                    </div>
                    <div class="d-grid gap-2">
                        <button type="submit" class="btn btn-primary col-12">Login</button>
                        <label class="col text-center">Ainda não tem uma conta? <a href="registro.php" class="link-light link-offset-1 link-underline-opacity-100 link-underline-opacity-100-hover"> Cadastre-se</a></label>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/js/bootstrap.bundle.min.js" integrity="sha384-j1CDi7MgGQ12Z7Qab0qlWQ/Qqz24Gc6BM0thvEMVjHnfYGF0rmFCozFSxQBxwHKO" crossorigin="anonymous"></script>
</body>
</html>


