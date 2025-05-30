<?php
include 'conexao.php';

$mensagem = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nome = $_POST['nome'];
    $email = $_POST['email'];
    $senha = password_hash($_POST['senha'], PASSWORD_DEFAULT);

    try {
        // Verifica se o e-mail já existe ANTES de tentar inserir
        $stmt_verifica = $conn->prepare("SELECT email FROM usuarios WHERE email = ?");
        $stmt_verifica->bind_param("s", $email);
        $stmt_verifica->execute();
        $resultado = $stmt_verifica->get_result();

        if ($resultado->num_rows > 0) {
            $mensagem = '<div class="alert alert-danger">Este e-mail já está cadastrado!</div>';
        } else {
            // Se não existir, procede com o cadastro
            $stmt = $conn->prepare("INSERT INTO usuarios (nome, email, senha) VALUES (?, ?, ?)");
            $stmt->bind_param("sss", $nome, $email, $senha);

            if ($stmt->execute()) {
                $mensagem = '<div class="alert alert-success">Cadastro realizado com sucesso!</div>';
                header("Location: login.php");
            }
            $stmt->close();
        }
        $stmt_verifica->close();
    } catch (mysqli_sql_exception $e) {
        // Fallback para capturar erros inesperados
        if ($e->getCode() == 1062) { // Código do erro "Duplicate entry"
            $mensagem = '<div class="alert alert-danger">Este e-mail já está em uso.</div>';
        } else {
            $mensagem = '<div class="alert alert-warning">Erro no cadastro: ' . htmlspecialchars($e->getMessage()) . '</div>';
        }
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
                    <a class="navbar-brand" href="index.php">
                        <img src="img/logo-login.png" alt="Logo" class="d-inline-block align-text-top logo">
                    </a>
                    <h5>Cadastro</h5>
                </center>
                <?= $mensagem ?>
                
                <form method="post">
                    <div class="mb-3">
                        <label for="nome" class="form-label">Nome</label>
                        <input type="text" class="form-control" name="nome" required placeholder="Digite seu nome">
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
                    <label class="col text-center">Já tem uma conta? <a href="login.php" class="col"> Login</a></label> 
                    </div>
                </form>
            </div>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>