<?php
session_start();
include 'conexao.php';

$mensagem = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = trim($_POST['username']);
    $senha = trim($_POST['senha']);

    if (!empty($username) && !empty($senha)) {
        $stmt = $conn->prepare("SELECT ID_Administrador, Nome_Completo, Senha FROM administrador WHERE Username = ? LIMIT 1");
        $stmt->bind_param("s", $username);
        
        if ($stmt->execute()) {
            $resultado = $stmt->get_result();
            
            if ($resultado->num_rows === 1) {
                $admin = $resultado->fetch_assoc();
                
                // Verificação simples de senha (sem hash neste exemplo)
                if ($senha === $admin['Senha']) {
                    $_SESSION['admin_id'] = $admin['ID_Administrador'];
                    $_SESSION['admin_nome'] = $admin['Nome_Completo'];
                    header("Location: admin.php");
                    exit();
                } else {
                    $mensagem = '<div class="alert alert-danger">Credenciais inválidas</div>';
                }
            } else {
                $mensagem = '<div class="alert alert-danger">Administrador não encontrado</div>';
            }
        }
        $stmt->close();
    } else {
        $mensagem = '<div class="alert alert-warning">Preencha todos os campos</div>';
    }
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Administrativo</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .login-container {
            max-width: 400px;
            margin: 5rem auto;
            padding: 2rem;
            border-radius: 10px;
            box-shadow: 0 0 20px rgba(0,0,0,0.1);
        }
        .login-header {
            text-align: center;
            margin-bottom: 2rem;
        }
    </style>
</head>
<body class="bg-light">
    <div class="container">
        <div class="login-container bg-white">
            <div class="login-header">
                <h2><i class="bi bi-shield-lock"></i> Área Administrativa</h2>
                <p class="text-muted">Acesso restrito</p>
            </div>
            
            <?= $mensagem ?>
            
            <form method="POST">
                <div class="mb-3">
                    <label for="username" class="form-label">Usuário Admin</label>
                    <input type="text" class="form-control" name="username" required>
                </div>
                <div class="mb-3">
                    <label for="senha" class="form-label">Senha</label>
                    <input type="password" class="form-control" name="senha" required>
                </div>
                <button type="submit" class="btn btn-primary w-100">Entrar</button>
            </form>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>