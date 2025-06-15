<?php
session_start();
include 'conexao.php';

$mobile = FALSE;
$user_agents = array("iPhone","iPad","Android","webOS","BlackBerry","iPod","Symbian","IsGeneric");
foreach($user_agents as $user_agent){
    if (strpos($_SERVER['HTTP_USER_AGENT'], $user_agent) !== FALSE) {
        if (isset($_SESSION["Nome_Completo"])) {
            $text_lado = $_SESSION["Nome_Completo"];
            $botao = "<a class='nav-link' href='logout.php'>Sair</a>";
        } else {
            $text_lado = "";
            $botao = "<a class='nav-link' href='login.php'>Login</a>";
        }
    } else {
        if (isset($_SESSION["Nome_Completo"])) {
            $text_lado = $_SESSION["Nome_Completo"];
            $botao = "<a class='btn btn-outline-danger' href='logout.php'>Sair</a>";
        } else {
            $text_lado = "";
            $botao = "<a class='btn btn-outline-success' href='login.php'>Login</a>";
        }
    }
}

if (!isset($_SESSION['ID_Usuario'])) {
    header("Location: login.php");
    exit();
}

// Buscar dados do usuário
$usuario_id = $_SESSION['ID_Usuario'];
$usuario = $conn->query("SELECT * FROM usuario WHERE ID_Usuario = $usuario_id")->fetch_assoc();

// Processar atualizações
$mensagem = '';
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Atualizar perfil
    if (isset($_POST['atualizar_perfil'])) {
        $novo_username = trim($_POST['username']);
        $novo_nome = trim($_POST['nome']);
        
        $conn->query("UPDATE usuario SET 
                     Username = '$novo_username', 
                     Nome_Completo = '$novo_nome' 
                     WHERE ID_Usuario = $usuario_id");
        $mensagem = '<div class="alert alert-success">Perfil atualizado com sucesso!</div>';
        $_SESSION['Nome_Completo'] = $novo_nome;
    }
    
    // Atualizar senha
    if (isset($_POST['atualizar_senha'])) {
        $senha_atual = trim($_POST['senha_atual']);
        $nova_senha = trim($_POST['nova_senha']);
        $confirma_senha = trim($_POST['confirma_senha']);
        
        if (password_verify($senha_atual, $usuario['Senha'])) {
            if ($nova_senha === $confirma_senha) {
                $hash_senha = password_hash($nova_senha, PASSWORD_DEFAULT);
                $conn->query("UPDATE usuario SET Senha = '$hash_senha' WHERE ID_Usuario = $usuario_id");
                $mensagem = '<div class="alert alert-success">Senha alterada com sucesso!</div>';
            } else {
                $mensagem = '<div class="alert alert-danger">As novas senhas não coincidem</div>';
            }
        } else {
            $mensagem = '<div class="alert alert-danger">Senha atual incorreta</div>';
        }
    }
    
    // Atualizar postagem
    if (isset($_POST['atualizar_post'])) {
        $post_id = intval($_POST['post_id']);
        $nova_descricao = $conn->real_escape_string(trim($_POST['descricao']));
        $nova_localizacao = $conn->real_escape_string(trim($_POST['localizacao']));
        
        $conn->query("UPDATE postagem SET 
                     Descricao_Postagem = '$nova_descricao',
                     Localizacao_Postagem = '$nova_localizacao'
                     WHERE ID_Postagem = $post_id AND ID_Categoria = $usuario_id AND Categoria = 'Usuario'");
        $mensagem = '<div class="alert alert-success">Postagem atualizada!</div>';
    }
    
    // Excluir postagem
    if (isset($_POST['excluir_post'])) {
        $post_id = intval($_POST['post_id']);
        $conn->query("DELETE FROM postagem WHERE ID_Postagem = $post_id AND ID_Categoria = $usuario_id AND Categoria = 'Usuario'");
        $mensagem = '<div class="alert alert-success">Postagem excluída!</div>';
    }
}

// Buscar postagens do usuário
$postagens = $conn->query("SELECT * FROM postagem WHERE ID_Categoria = $usuario_id AND Categoria = 'Usuario' ORDER BY Data_Postagem DESC");
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Meu Perfil - JapiWatch</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <style>
        .nav-pills .nav-link.active {
            background-color: #0d6efd;
        }
        .post-img {
            max-height: 300px;
            object-fit: cover;
        }
    </style>
</head>
<body class="bg-light">
    <nav class="navbar navbar-dark navbar-expand-lg bg-dark">
        <div class="container-fluid gap-3">
            <a class="navbar-brand" style="margin-left: 30px" href="#">
                <img src="img/logo.png" alt="Logo" width="120" height="55" class="d-inline-block align-text-top">
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarSupportedContent">
                <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                    <li class="nav-item active">
                    <a class="nav-link" href="index.php">Home</a>
                    </li>
                    <li class="nav-item">
                    <a class="nav-link" href="feed.php">Galeria</a>
                    </li>
                    <li class="nav-item">
                    <a class="nav-link" href="sobre.html">Sobre</a>
                    </li>
                </ul>
                <div class="d-flex align-items-center">
                    <span class="navbar-text me-3"><?= $text_lado ?></span>
                    <?= $botao ?>
                </div>
            </div>
        </div>
    </nav>

    <div class="container py-5">
        <div class="row">
            <div class="col-lg-3 mb-4">
                <div class="card shadow-sm">
                    <div class="card-body text-center">
                        <h4><?= htmlspecialchars($usuario['Nome_Completo']) ?></h4>
                        <p class="text-muted mb-1">@<?= htmlspecialchars($usuario['Username']) ?></p>
                        <small class="text-muted">Membro desde <?= date('d/m/Y', strtotime($usuario['Data_Cadastro'] ?? 'now')) ?></small>
                    </div>
                    <ul class="nav nav-pills flex-column mb-auto">
                        <li class="nav-item">
                            <a class="nav-link active" href="#perfil" data-bs-toggle="tab">
                                <i class="bi bi-person-fill me-2"></i>Meu Perfil
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="#senha" data-bs-toggle="tab">
                                <i class="bi bi-lock-fill me-2"></i>Alterar Senha
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="#postagens" data-bs-toggle="tab">
                                <i class="bi bi-images me-2"></i>Minhas Postagens
                            </a>
                        </li>
                    </ul>
                </div>
            </div>

            <!-- Conteúdo Principal -->
            <div class="col-lg-9">
                <div class="card shadow-sm">
                    <div class="card-body">
                        <?= $mensagem ?>
                        <div class="tab-content">
                            <!-- Aba: Perfil -->
                            <div class="tab-pane fade show active" id="perfil">
                                <h4 class="mb-4"><i class="bi bi-person-fill me-2"></i>Meu Perfil</h4>
                                <form method="post">
                                    <div class="row mb-3">
                                        <div class="col-md-6">
                                            <label class="form-label">Nome Completo</label>
                                            <input type="text" name="nome" class="form-control" 
                                                   value="<?= htmlspecialchars($usuario['Nome_Completo']) ?>" required>
                                        </div>
                                        <div class="col-md-6">
                                            <label class="form-label">Nome de Usuário</label>
                                            <input type="text" name="username" class="form-control" 
                                                   value="<?= htmlspecialchars($usuario['Username']) ?>" required>
                                        </div>
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label">Email</label>
                                        <input type="email" class="form-control" 
                                               value="<?= htmlspecialchars($usuario['Email']) ?>" readonly>
                                    </div>
                                    <button type="submit" name="atualizar_perfil" class="btn btn-primary">
                                        Atualizar Perfil
                                    </button>
                                </form>
                            </div>

                            <!-- Aba: Senha -->
                            <div class="tab-pane fade" id="senha">
                                <h4 class="mb-4"><i class="bi bi-lock-fill me-2"></i>Alterar Senha</h4>
                                <form method="post">
                                    <div class="mb-3">
                                        <label class="form-label">Senha Atual</label>
                                        <input type="password" name="senha_atual" class="form-control" required>
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label">Nova Senha</label>
                                        <input type="password" name="nova_senha" class="form-control" required>
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label">Confirmar Nova Senha</label>
                                        <input type="password" name="confirma_senha" class="form-control" required>
                                    </div>
                                    <button type="submit" name="atualizar_senha" class="btn btn-primary">
                                        Alterar Senha
                                    </button>
                                </form>
                            </div>

                            <!-- Aba: Postagens -->
                            <div class="tab-pane fade" id="postagens">
                                <h4 class="mb-4"><i class="bi bi-images me-2"></i>Minhas Postagens</h4>
                                
                                <?php if ($postagens->num_rows > 0): ?>
                                    <div class="row row-cols-1 g-4">
                                        <?php while($post = $postagens->fetch_assoc()): ?>
                                        <div class="col">
                                            <div class="card">
                                                <div class="row g-0">
                                                    <div class="col-md-4">
                                                        <img src="<?= $post['Foto'] ?>" class="img-fluid rounded-start post-img h-100" alt="...">
                                                    </div>
                                                    <div class="col-md-8">
                                                        <div class="card-body">
                                                            <form method="post">
                                                                <input type="hidden" name="post_id" value="<?= $post['ID_Postagem'] ?>">
                                                                <div class="mb-3">
                                                                    <label class="form-label">Localização</label>
                                                                    <input type="text" name="localizacao" class="form-control" 
                                                                           value="<?= htmlspecialchars($post['Localizacao_Postagem']) ?>" required>
                                                                </div>
                                                                <div class="mb-3">
                                                                    <label class="form-label">Descrição</label>
                                                                    <textarea class="form-control" name="descricao" rows="3" required><?= 
                                                                        htmlspecialchars($post['Descricao_Postagem']) 
                                                                    ?></textarea>
                                                                </div>
                                                                <div class="d-flex justify-content-between">
                                                                    <button type="submit" name="atualizar_post" class="btn btn-primary">
                                                                        <i class="bi bi-save"></i> Salvar
                                                                    </button>
                                                                    <button type="submit" name="excluir_post" class="btn btn-danger"
                                                                            onclick="return confirm('Tem certeza que deseja excluir esta postagem?')">
                                                                        <i class="bi bi-trash"></i> Excluir
                                                                    </button>
                                                                </div>
                                                            </form>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <?php endwhile; ?>
                                    </div>
                                <?php else: ?>
                                    <div class="alert alert-info">
                                        Você ainda não fez nenhuma postagem. 
                                        <a href="form-img.php" class="alert-link">Clique aqui</a> para criar sua primeira postagem!
                                    </div>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Ativa a aba correta quando há âncora na URL
        window.addEventListener('DOMContentLoaded', () => {
            if (window.location.hash) {
                const tabTrigger = new bootstrap.Tab(document.querySelector(`a[href="${window.location.hash}"]`));
                tabTrigger.show();
            }
        });
    </script>
</body>
</html>