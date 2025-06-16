<?php
include 'conexao.php';

$mensagem = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nome = $_POST['nome'];
    $email = $_POST['email'];
    $username = $_POST['username'];
    $senha = password_hash($_POST['senha'], PASSWORD_DEFAULT);

    try {
        $stmt_verifica = $conn->prepare("SELECT Email, Username FROM usuario WHERE Email = ? OR Username = ?");
        $stmt_verifica->bind_param("ss", $email, $username);
        $stmt_verifica->execute();
        $resultado = $stmt_verifica->get_result();

        if ($resultado->num_rows > 0) {
            $usuario_existente = $resultado->fetch_assoc();
            if ($usuario_existente['Email'] === $email) {
                $mensagem = '<div class="alert alert-danger">Este e-mail já está cadastrado!</div>';
            } else {
                $mensagem = '<div class="alert alert-danger">Este nome de usuário já está em uso!</div>';
            }
        } else {
            // Se não existir, cadastra
            $stmt = $conn->prepare("INSERT INTO usuario (Nome_Completo, Email, Senha, Username) VALUES (?, ?, ?, ?)");
            $stmt->bind_param("ssss", $nome, $email, $senha, $username);

            if ($stmt->execute()) {
                $_SESSION['mensagem'] = '<div class="alert alert-success">Cadastro realizado com sucesso!</div>';
                header("Location: login.php");
                exit();
            }
        }
    } catch (mysqli_sql_exception $e) {
        $mensagem = '<div class="alert alert-danger">Erro: ' . htmlspecialchars($e->getMessage()) . '</div>';
    }
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cadastro</title>
    <link rel="stylesheet" href="style.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="body">
    <div class="bloco">
        <div class="d-flex justify-content-center align-items-center">
            <div class="container">
                <center>
                    <h1 class="display-1">JapiWatch</h1>
                    <h5>Cadastro</h5>
                </center>
                <?= $mensagem ?>
                
                <form method="post">
                    <div class="mb-3">
                        <label for="nome" class="form-label">Nome completo</label>
                        <input type="text" class="form-control" name="nome" required placeholder="Digite seu nome">
                    </div>
                    <div class="mb-3">
                        <label for="username" class="form-label">Nome de Usuário</label>
                        <input type="text" class="form-control" name="username" required 
                            placeholder="Ex: japiExplorer23"
                            pattern="[a-zA-Z0-9_]{4,20}" 
                            title="4-20 caracteres (letras, números ou _)">
                        <small class="text-muted-dark">Será seu identificador público</small>
                    </div>
                    <div class="mb-3">
                        <label for="email" class="form-label">Endereço de email</label>
                        <input type="email" class="form-control" name="email" required placeholder="nome@email.com">
                    </div>
                    <div class="mb-3">
                        <label for="senha" class="form-label">Senha</label>
                        <input type="password" class="form-control" name="senha" required placeholder="Crie uma senha">
                    </div>
                    <div class="d-grid gap-2">
                    <button type="submit" class="btn btn-primary col">Cadastrar</button>
                    <label class="col text-center">Já tem uma conta? <a href="login.php" class="link-light link-offset-1 link-underline-opacity-100 link-underline-opacity-100-hover"> Login</a></label> 
                    </div>
                </form>
            </div>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>