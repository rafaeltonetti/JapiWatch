<?php
session_start();

include 'conexao.php';

$novas_notificacoes = 0;
if (isset($_SESSION['ID_Usuario'])) {
    $usuario_id = $_SESSION['ID_Usuario'];
    $novas_notificacoes = $conn->query(
        "SELECT COUNT(*) as total FROM notificacoes 
         WHERE ID_Usuario = $usuario_id AND Lida = FALSE"
    )->fetch_assoc()['total'];
}

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
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>JapiWatch</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-4Q6Gf2aSP4eDXB8Miphtr37CMZZQ5oXLH2yaXMJ2w8e2ZtHTl7GptT4jmndRuHDT" crossorigin="anonymous">
    <link rel="stylesheet" href="style.css">

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
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
                    <a class="nav-link" href="feed.php">Galeria</a>
                    </li>
                    <li class="nav-item">
                    <a class="nav-link" href="sobre.php">Sobre</a>
                    </li>
                    <li class="nav-item">
                    <a class="nav-link" href="user.php">Meu Perfil</a>
                    <?php if (isset($_SESSION['ID_Usuario'])): ?>
                    <li class="nav-item dropdown">
                        <a class="nav-link position-relative" href="#" id="notificacoesDropdown" role="button" data-bs-toggle="dropdown">
                            <i class="bi bi-bell"></i>
                            <?php 
                            if ($novas_notificacoes > 0): ?>
                            <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger">
                                <?= $novas_notificacoes ?>
                            </span>
                            <?php endif; ?>
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end p-0" aria-labelledby="notificacoesDropdown" style="width: 300px;">
                            <li><h6 class="dropdown-header">Notificações</h6></li>
                            <?php 
                            $ultimas = $conn->query(
                                "SELECT * FROM notificacoes 
                                WHERE ID_Usuario = {$_SESSION['ID_Usuario']}
                                ORDER BY Data_Notificacao DESC LIMIT 3"
                            );
                            
                            if ($ultimas->num_rows > 0): ?>
                                <?php while($notif = $ultimas->fetch_assoc()): ?>
                                <li>
                                    <a class="dropdown-item small <?= $notif['Lida'] ? '' : 'fw-bold' ?>" 
                                    href="notificacoes.php?ler=<?= $notif['ID_Notificacao'] ?>">
                                    <div class="d-flex justify-content-between">
                                        <span><?= substr(htmlspecialchars($notif['Conteudo']), 0, 30) ?>...</span>
                                        <?php if (!$notif['Lida']): ?>
                                        <span class="badge bg-primary badge-notificacao">Nova</span>
                                        <?php endif; ?>
                                    </div>
                                    <small class="text-muted"><?= date('H:i', strtotime($notif['Data_Notificacao'])) ?></small>
                                    </a>
                                </li>
                                <?php endwhile; ?>
                            <?php else: ?>
                                <li><a class="dropdown-item small text-muted" href="#">Nenhuma notificação recente</a></li>
                            <?php endif; ?>
                            <li><hr class="dropdown-divider"></li>
                            <li><a class="dropdown-item text-center small" href="notificacoes.php">Ver todas as notificações</a></li>
                        </ul>
                    </li>
                    <?php endif; ?>
                </ul>
                <div class="d-flex align-items-center">
                    <span class="navbar-text me-3"><?= $text_lado ?></span>
                    <?= $botao ?>
                </div>
            </div>
        </div>
    </nav>

    <section class="hero">
        <div class="content">
            <div class="container col-md-5 col-sm-12 text-center">
                <h1>Bem vindo ao JapiWatch!</h1>
                <p>Explore e proteja a biodiversidade da Serra do Japi com o JapiWatch! Nosso projeto de ciência cidadã convida você a registrar espécies da fauna e flora, contribuindo para pesquisas científicas enquanto descobre a riqueza natural dessa reserva.</p>
                <div class="d-flex gap-2 justify-content-center">
                    <a class="btn btn-success" href="feed.php">Explorar Galeria</a>
                    <a class="btn btn-success" href="form-img.php">Registrar Espécie</a>
                </div>
            </div>
        </div>
    </section>

    
    <div class="container">
        <h2 class="mb-4 mt-4">Últimos registros:</h2>
        <div class="row justify-content-center g-3 mb-4">
            <?php
                $sql = "SELECT p.Titulo_Postagem AS especie, p.Descricao_Postagem AS descricao, p.Foto AS caminho_imagem, p.Localizacao_Postagem AS localizacao, p.ID_Postagem AS id FROM postagem p ORDER BY p.Data_Postagem DESC LIMIT 4";
                $result = $conn->query($sql);
                while ($row = $result->fetch_assoc()) {
                    echo '<div class="col-lg-3 col-md-6 col-sm-6">';
                    echo '    <div class="card h-100">';
                    echo '        <img src="' . $row['caminho_imagem'] . '" class="card-img-top img-card" style="object-fit: cover; height: 200px;">';
                    echo '        <div class="card-body d-flex flex-column">';
                    echo '            <h5 class="card-title">' . $row['especie'] . '</h5>';
                    echo '            <p class="card-text">' . $row['descricao'] . '</p>';
                    echo '            <small class="text-muted mb-2">' . $row['localizacao'] . '</small>';
                    echo '            <a href="detalhes.php?id=' . $row['id'] . '" class="btn btn-success mt-auto">Ver</a>';
                    echo '        </div>';
                    echo '    </div>';
                    echo '</div>';
                }
            ?>
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

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/js/bootstrap.bundle.min.js" integrity="sha384-j1CDi7MgGQ12Z7Qab0qlWQ/Qqz24Gc6BM0thvEMVjHnfYGF0rmFCozFSxQBxwHKO" crossorigin="anonymous"></script>
</body>
</html>