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

if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    header("Location: feed.php");
    exit();
}

$post_id = intval($_GET['id']);
$usuario_logado = isset($_SESSION['ID_Usuario']);

$stmt_post = $conn->prepare("
    SELECT p.*, u.Nome_Completo AS autor 
    FROM postagem p
    JOIN usuario u ON p.ID_Categoria = u.ID_Usuario
    WHERE p.ID_Postagem = ?
");
$stmt_post->bind_param("i", $post_id);
$stmt_post->execute();
$postagem = $stmt_post->get_result()->fetch_assoc();

if (!$postagem) {
    header("Location: feed.php");
    exit();
}

$stmt_comentarios = $conn->prepare("
    SELECT c.*, u.Nome_Completo AS autor 
    FROM comentarios c
    JOIN usuario u ON c.ID_Categoria = u.ID_Usuario
    WHERE c.ID_Postagem = ?
    ORDER BY c.Data_Comentario DESC
");
$stmt_comentarios->bind_param("i", $post_id);
$stmt_comentarios->execute();
$comentarios = $stmt_comentarios->get_result();

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['comentario'])) {
    if (!$usuario_logado) {
        header("Location: login.php?redirect=detalhes.php?id=".$post_id);
        exit();
    }

    $comentario = trim($_POST['comentario']);
    if (!empty($comentario)) {
        $stmt_insert = $conn->prepare("
            INSERT INTO comentarios 
            (Conteudo_Comentario, ID_Categoria, Categoria, ID_Postagem) 
            VALUES (?, ?, 'Usuario', ?)
        ");
        $stmt_insert->bind_param("sii", $comentario, $_SESSION['ID_Usuario'], $post_id);
        $stmt_insert->execute();
        header("Location: detalhes.php?id=".$post_id);
        exit();
    }
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($postagem['Titulo_Postagem']) ?> | JapiWatch</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <style>
        .post-header {
            padding-bottom: 1.5rem;
        }
        .post-image-container {
            margin-bottom: 0px;
        }
        .post-image {
            width: 650px;
            height: 400px;
            border-radius: 8px;
            object-fit: cover;
            min-width: 70%;
            margin-right: 30px
        }
        .comment-section {
            background: #fff;
            border-radius: 8px;
            padding: 2.5rem;
            margin-top: 3rem;
            box-shadow: 0 2px 10px rgba(0,0,0,0.05);
        }
        .comment {
            padding: 1.5rem;
            margin-bottom: 1.5rem;
            background: #f8f9fa;
            border-radius: 8px;
            border-left: 4px solid #0d6efd;
        }
        .comment-form {
            background: #fff;
            padding: 2rem;
            border-radius: 8px;
            box-shadow: 0 2px 12px rgba(0,0,0,0.08);
            margin-bottom: 2.5rem;
        }
        .author-info {
            display: flex;
            align-items: center;
            margin-bottom: 1.5rem;
        }
        .content-box {
            background: #fff;
            border-radius: 8px;
            padding: 2rem;
            box-shadow: 0 2px 10px rgba(0,0,0,0.05);
            margin-bottom: 2rem;
        }
    </style>
</head>
<body>
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
                    <a class="nav-link" href="#">Galeria</a>
                    </li>
                    <li class="nav-item">
                    <a class="nav-link" href="#">Sobre</a>
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
        <div class="content-box">

            <div class="d-flex">

                <div class="post-image-container">
                    <img src="<?= htmlspecialchars($postagem['Foto']) ?>" class="post-image" alt="<?= htmlspecialchars($postagem['Titulo_Postagem']) ?>">
                </div>

                <div>
                    <div class="post-header">
                        <h1 class="display-5 fw-bold mb-3"><?= htmlspecialchars($postagem['Titulo_Postagem']) ?></h1>
                        <div class="author-info">
                            <div class="me-3">
                                <i class="bi bi-person-circle fs-4 text-primary"></i>
                            </div>
                            <div>
                                <p class="mb-0 fw-medium"><?= htmlspecialchars($postagem['autor']) ?></p>
                                <small class="text-muted">
                                    <i class="bi bi-clock"></i> <?= date('d/m/Y \à\s H:i', strtotime($postagem['Data_Postagem'])) ?>
                                </small>
                            </div>
                        </div>
                    </div>
                    <div class="post-content">
                        <p class="lead mb-4"><?= nl2br(htmlspecialchars($postagem['Descricao_Postagem'])) ?></p>
                        <p class="text-muted">
                            <i class="bi bi-geo-alt-fill"></i> <?= htmlspecialchars($postagem['Localizacao_Postagem']) ?>
                        </p>
                    </div>
                </div>

            </div>
            
            
            
        </div>

        <div class="comment-section">
            <h3 class="mb-4 d-flex align-items-center">
                <i class="bi bi-chat-left-text me-2"></i> Comentários
                <span class="ms-2">(<?= $comentarios->num_rows ?>)</span>
            </h3>

            <?php if($usuario_logado): ?>
            <div class="comment-form">
                <h5 class="mb-3">Deixe seu comentário</h5>
                <form method="POST">
                    <div class="mb-3">
                        <textarea name="comentario" class="form-control" rows="4" 
                                placeholder="Escreva aqui seu comentário..." required></textarea>
                    </div>
                    <div class="d-flex justify-content-end">
                        <button type="submit" class="btn btn-primary px-4">
                            <i class="bi bi-send-fill me-2"></i>Enviar
                        </button>
                    </div>
                </form>
            </div>
            <?php else: ?>
            <div class="alert alert-info">
                <a href="login.php?redirect=detalhes.php?id=<?= $post_id ?>" class="alert-link fw-medium">
                    <i class="bi bi-box-arrow-in-right me-2"></i>Faça login para comentar
                </a>
            </div>
            <?php endif; ?>

            <div class="comments-list mt-4">
                <?php if($comentarios->num_rows > 0): ?>
                    <?php while($comentario = $comentarios->fetch_assoc()): ?>
                    <div class="comment">
                        <div class="d-flex justify-content-between mb-3">
                            <div class="d-flex align-items-center">
                                <i class="bi bi-person-circle me-2 text-secondary"></i>
                                <strong><?= htmlspecialchars($comentario['autor']) ?></strong>
                            </div>
                            <small class="text-muted">
                                <i class="bi bi-clock"></i> <?= date('d/m/Y H:i', strtotime($comentario['Data_Comentario'])) ?>
                            </small>
                        </div>
                        <p class="mb-0"><?= nl2br(htmlspecialchars($comentario['Conteudo_Comentario'])) ?></p>
                    </div>
                    <?php endwhile; ?>
                <?php else: ?>
                    <div class="text-center py-4">
                        <i class="bi bi-chat-square-text fs-1 text-muted mb-3"></i>
                        <p class="text-muted">Nenhum comentário ainda. Seja o primeiro a comentar!</p>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <footer class="bg-dark text-white pt-5 pb-4">
    <div class="container">
        <div class="row">
            <div class="col-md-5 mb-4">
                <h5 class="text-uppercase mb-4">Sobre o JapiWatch</h5>
                <p>
                    Plataforma colaborativa para monitoramento da biodiversidade na Serra do Japi. 
                    Junte-se a nós nessa missão de preservação!
                </p>
                <div class="mt-3">
                    <a href="#" class="text-white me-2"><i class="bi bi-facebook"></i></a>
                    <a href="#" class="text-white me-2"><i class="bi bi-instagram"></i></a>
                    <a href="#" class="text-white me-2"><i class="bi bi-twitter-x"></i></a>
                </div>
            </div>

            <div class="col-md-3 mb-4">
                <h5 class="text-uppercase mb-4">Links Rápidos</h5>
                <ul class="list-unstyled">
                    <li class="mb-2"><a href="#" class="text-white text-decoration-none">Galeria</a></li>
                    <li class="mb-2"><a href="form-img.php" class="text-white text-decoration-none">Registrar Espécie</a></li>
                    <li class="mb-2"><a href="#" class="text-white text-decoration-none">Sobre o Projeto</a></li>
                    <li class="mb-2"><a href="#" class="text-white text-decoration-none">Termos de Uso</a></li>
                </ul>
            </div>
            <div class="col-md-3 mb-4">
                <h5 class="text-uppercase mb-4">Contato</h5>
                <ul class="list-unstyled">
                    <li class="mb-2"><i class="bi bi-envelope me-2"></i> contato@japiwatch.com.br</li>
                    <li class="mb-2"><i class="bi bi-telephone me-2"></i> (11) 1234-5678</li>
                    <li><i class="bi bi-geo-alt me-2"></i> Jundiaí - SP, Brasil</li>
                </ul>
            </div>
        </div>

        <hr class="my-4 bg-light">

        <div class="row">
            <div class="col-md-12 text-center">
                <p class="mb-0">&copy; <?= date('Y') ?> JapiWatch. Todos os direitos reservados.</p>
            </div>
        </div>
    </div>
</footer>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>