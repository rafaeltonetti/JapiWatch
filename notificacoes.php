<?php
session_start();
include 'conexao.php';
include 'funcoes.php';

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

$usuario_id = $_SESSION['ID_Usuario'];

// Processar ações
if (isset($_GET['ler'])) {
    $id = intval($_GET['ler']);
    marcarNotificacaoComoLida($id, $usuario_id);
}

if (isset($_GET['excluir'])) {
    $id = intval($_GET['excluir']);
    excluirNotificacao($id, $usuario_id);
}

if (isset($_GET['marcar_todas'])) {
    $conn->query("UPDATE notificacoes SET Lida = 1 WHERE ID_Usuario = $usuario_id");
}

// Buscar notificações
$notificacoes = $conn->query("SELECT * FROM notificacoes 
                             WHERE ID_Usuario = $usuario_id
                             ORDER BY Data_Notificacao DESC");
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Notificações - JapiWatch</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <style>
        .notificacao-nao-lida {
            background-color: #f8f9fa;
            border-left: 4px solid #0d6efd;
        }
        .notificacao-link:hover {
            background-color: #e9ecef;
        }
        .badge-notificacao {
            font-size: 0.7rem;
        }
        .tipo-notificacao {
            font-size: 0.8rem;
            color: #6c757d;
        }
    </style>
</head>
<body>
    <nav class="navbar navbar-dark navbar-expand-lg bg-dark">
        <div class="container-fluid gap-3">
            <a class="navbar-brand" style="margin-left: 30px" href="index.php">
                <img src="img/logo.png" alt="Logo" width="120" height="55" class="d-inline-block align-text-top">
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarSupportedContent">
                <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                    <li class="nav-item active">
                    <a class="nav-link" href="feed.php">Galeria</a>
                    </li>
                    <li class="nav-item">
                    <a class="nav-link" href="sobre.php">Sobre</a>
                    </li>
                    <li class="nav-item">
                    <a class="nav-link" href="user.php">Meu Perfil</a>
                    </li>
                </ul>
                <div class="d-flex align-items-center">
                    <span class="navbar-text me-3"><?= $text_lado ?></span>
                    <?= $botao ?>
                </div>
            </div>
        </div>
    </nav>

    <div class="container py-4">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2><i class="bi bi-bell-fill"></i> Notificações</h2>
            <div>
                <a href="notificacoes.php?marcar_todas=1" class="btn btn-outline-secondary me-2">
                    <i class="bi bi-check-all"></i> Marcar todas como lidas
                </a>
                <a href="notificacoes.php" class="btn btn-outline-primary">
                    <i class="bi bi-arrow-clockwise"></i> Atualizar
                </a>
            </div>
        </div>

        <div class="list-group">
            <?php if ($notificacoes->num_rows > 0): ?>
                <?php while($notif = $notificacoes->fetch_assoc()): ?>
                <div class="list-group-item <?= $notif['Lida'] ? '' : 'notificacao-nao-lida' ?>">
                    <div class="d-flex justify-content-between align-items-start">
                        <div class="flex-grow-1">
                            <div class="d-flex justify-content-between align-items-start mb-1">
                                <span class="tipo-notificacao">
                                    <i class="bi bi-<?= 
                                        $notif['Tipo'] === 'novo_comentario' ? 'chat-square-text' : 
                                        ($notif['Tipo'] === 'exclusao_post' ? 'trash' : 
                                        ($notif['Tipo'] === 'exclusao_comentario' ? 'chat-square-text-fill' : 'bell-fill'))
                                    ?>"></i>
                                    <?= ucfirst(str_replace('_', ' ', $notif['Tipo'])) ?>
                                </span>
                                <small class="text-muted">
                                    <?= date('d/m/Y H:i', strtotime($notif['Data_Notificacao'])) ?>
                                </small>
                            </div>
                            <p class="mb-1"><?= htmlspecialchars($notif['Conteudo']) ?></p>
                            
                            <?php if ($notif['ID_Referencia']): ?>
                                <?php 
                                $link = '';
                                $texto_link = '';
                                
                                if ($notif['Tipo'] === 'novo_comentario' || $notif['Tipo'] === 'exclusao_post') {
                                    $link = "detalhes.php?id=".$notif['ID_Referencia'];
                                    $texto_link = 'Ver postagem';
                                } elseif ($notif['Tipo'] === 'exclusao_comentario') {
                                    $link = "feed.php";
                                    $texto_link = 'Ver feed';
                                }
                                ?>
                                
                                <?php if ($link): ?>
                                <div class="mt-2">
                                    <a href="<?= $link ?>" class="btn btn-sm btn-outline-primary">
                                        <?= $texto_link ?>
                                    </a>
                                </div>
                                <?php endif; ?>
                            <?php endif; ?>
                        </div>
                        <div class="ms-2">
                            <a href="notificacoes.php?ler=<?= $notif['ID_Notificacao'] ?>" 
                               class="btn btn-sm btn-outline-success" title="Marcar como lida">
                                <i class="bi bi-check2"></i>
                            </a>
                            <a href="notificacoes.php?excluir=<?= $notif['ID_Notificacao'] ?>" 
                               class="btn btn-sm btn-outline-danger" title="Excluir"
                               onclick="return confirm('Tem certeza que deseja excluir esta notificação?')">
                                <i class="bi bi-trash"></i>
                            </a>
                        </div>
                    </div>
                </div>
                <?php endwhile; ?>
            <?php else: ?>
                <div class="alert alert-info text-center py-4">
                    <i class="bi bi-bell-slash fs-1"></i>
                    <h4 class="mt-3">Nenhuma notificação encontrada</h4>
                    <p class="mb-0">Quando você tiver novas notificações, elas aparecerão aqui</p>
                </div>
            <?php endif; ?>
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
                        <li class="mb-2"><a href="feed.php" class="text-white text-decoration-none">Galeria</a></li>
                        <li class="mb-2"><a href="form-img.php" class="text-white text-decoration-none">Registrar Espécie</a></li>
                        <li class="mb-2"><a href="sobre.php" class="text-white text-decoration-none">Sobre o Projeto</a></li>
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