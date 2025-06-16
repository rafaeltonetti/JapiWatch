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

$ordenacao = isset($_GET['ordenacao']) ? $_GET['ordenacao'] : 'recentes';
$pesquisa = isset($_GET['pesquisa']) ? trim($_GET['pesquisa']) : '';

$sql = "SELECT p.ID_Postagem, p.Titulo_Postagem AS especie, p.Descricao_Postagem AS descricao, 
               p.Foto AS caminho_imagem, p.Data_Postagem, u.Nome_Completo AS autor
        FROM postagem p
        JOIN usuario u ON p.ID_Categoria = u.ID_Usuario
        WHERE 1=1";

if (!empty($pesquisa)) {
    $sql .= " AND p.Titulo_Postagem LIKE ?";
    $param_pesquisa = "%$pesquisa%";
}

if ($ordenacao === 'recentes') {
    $sql .= " ORDER BY p.Data_Postagem DESC";
} else {
    $sql .= " ORDER BY p.Data_Postagem ASC";
}

$stmt = $conn->prepare($sql);

if (!empty($pesquisa)) {
    $stmt->bind_param("s", $param_pesquisa);
}

$stmt->execute();
$resultado = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Feed de Postagens | JapiWatch</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="style.css">
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
                    <a class="nav-link" href="index.php">Home</a>
                    </li>
                    <li class="nav-item ">
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

    <center>
        <br><br>
    <h1>Todas as publicações</h1>
    </center>

    <div class="container py-5">
        <div class="row mb-4">
            <div class="col-md-8">
                <form class="row g-2">
                    <div class="col-md-5">
                        <select name="ordenacao" class="form-select" onchange="this.form.submit()">
                            <option value="recentes" <?= $ordenacao === 'recentes' ? 'selected' : '' ?>>Mais recentes</option>
                            <option value="antigas" <?= $ordenacao === 'antigas' ? 'selected' : '' ?>>Mais antigas</option>
                        </select>
                    </div>
                    <div class="col-md-5">
                        <input type="text" name="pesquisa" class="form-control" placeholder="Pesquisar por espécie..." value="<?= htmlspecialchars($pesquisa) ?>">
                    </div>
                    <div class="col-md-2">
                        <button type="submit" class="btn btn-primary w-100">Filtrar</button>
                    </div>
                </form>
            </div>
        </div>

        <div class="row" id="feed-postagens">
            <?php if ($resultado->num_rows > 0): ?>
                <?php while ($post = $resultado->fetch_assoc()): ?>
                    <div class="col-lg-3 col-md-6 mb-4">
                        <div class="card h-100 shadow-sm">
                            <img src="<?= htmlspecialchars($post['caminho_imagem']) ?>" class="card-img-top img-fluid" style="height: 200px; object-fit: cover;" alt="<?= htmlspecialchars($post['especie']) ?>">
                            <div class="card-body d-flex flex-column">
                                <h5 class="card-title"><?= htmlspecialchars($post['especie']) ?></h5>
                                <p class="card-text text-muted small"><?= htmlspecialchars(substr($post['descricao'], 0, 100)) ?>...</p>
                                <div class="mt-auto">
                                    <small class="text-muted d-block mb-2">
                                        <i class="bi bi-calendar"></i> <?= date('d/m/Y', strtotime($post['Data_Postagem'])) ?>
                                    </small>
                                    <a href="detalhes.php?id=<?= $post['ID_Postagem'] ?>" class="btn btn-outline-primary btn-sm w-100">Ver detalhes</a>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php endwhile; ?>
            <?php else: ?>
                <div class="col-12">
                    <div class="alert alert-info text-center">
                        Nenhuma postagem encontrada. <?= !empty($pesquisa) ? 'Tente outra pesquisa.' : 'Seja o primeiro a postar!' ?>
                    </div>
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
    <script>
        // pesquisa
        document.querySelector('[name="pesquisa"]').addEventListener('keyup', function(e) {
            if (e.key === 'Enter') {
                this.form.submit();
            }
        });
    </script>
</body>
</html>
