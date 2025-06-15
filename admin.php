<?php
session_start();
include 'conexao.php';

if (!isset($_SESSION['admin_id'])) {
    header("Location: login-admin.php");
    exit();
}

$posts = $conn->query("SELECT * FROM postagem ORDER BY Data_Postagem DESC");
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Painel Admin - Publicações</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <style>
        .card-img-admin {
            height: 150px;
            object-fit: cover;
        }
    </style>
</head>
<body>
    <nav class="navbar navbar-dark bg-dark">
        <div class="container-fluid">
            <a class="navbar-brand" href="#">
                <i class="bi bi-shield-lock"></i> Painel Admin
            </a>
            <div class="d-flex">
                <span class="navbar-text text-white me-3">
                    Olá, <?= $_SESSION['admin_nome'] ?>
                </span>
                <a href="logout-admin.php" class="btn btn-outline-light btn-sm">
                    <i class="bi bi-box-arrow-left"></i> Sair
                </a>
            </div>
        </div>
    </nav>

    <div class="container py-4">
        <h2 class="mb-4"><i class="bi bi-images"></i> Publicações</h2>
        
        <div class="row row-cols-1 row-cols-md-2 row-cols-lg-4 g-4">
            <?php while($post = $posts->fetch_assoc()): ?>
            <div class="col">
                <div class="card h-100">
                    <img src="<?= $post['Foto'] ?>" class="card-img-top card-img-admin" alt="...">
                    <div class="card-body">
                        <h5 class="card-title"><?= htmlspecialchars($post['Titulo_Postagem']) ?></h5>
                        <p class="card-text text-muted">
                            <small>
                                <?= date('d/m/Y H:i', strtotime($post['Data_Postagem'])) ?>
                            </small>
                        </p>
                        <p class="card-text">
                            <?= substr(htmlspecialchars($post['Descricao_Postagem']), 0, 100) ?>...
                        </p>
                    </div>
                    <div class="card-footer bg-white">
                        <a href="detalhes-admin.php?id=<?= $post['ID_Postagem'] ?>" class="btn btn-primary w-100">
                            <i class="bi bi-gear"></i> Gerenciar
                        </a>
                    </div>
                </div>
            </div>
            <?php endwhile; ?>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>