<?php
session_start();
include 'conexao.php';

if (!isset($_SESSION['admin_id'])) {
    header("Location: login-admin.php");
    exit();
}

$post_id = intval($_GET['id'] ?? 0);

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['excluir_post'])) {
        $post_id = intval($_POST['post_id']);
        
        try {
            $conn->begin_transaction();
            
            // Primeiro exclui os relacionamentos
            $stmt1 = $conn->prepare("DELETE FROM postagem_contem_especie WHERE ID_Postagem = ?");
            $stmt1->bind_param("i", $post_id);
            $stmt1->execute();
            
            // Depois os comentários
            $stmt2 = $conn->prepare("DELETE FROM comentarios WHERE ID_Postagem = ?");
            $stmt2->bind_param("i", $post_id);
            $stmt2->execute();
            
            // Finalmente a postagem
            $stmt3 = $conn->prepare("DELETE FROM postagem WHERE ID_Postagem = ?");
            $stmt3->bind_param("i", $post_id);
            $stmt3->execute();
            
            $conn->commit();
            header("Location: admin.php?success=1");
            exit();
            
        } catch (mysqli_sql_exception $e) {
            $conn->rollback();
            $mensagem = '<div class="alert alert-danger">Falha na exclusão: '.$e->getMessage().'</div>';
        }
    }
    
    if (isset($_POST['excluir_comentario'])) {
        $comentario_id = intval($_POST['comentario_id']);
        $stmt = $conn->prepare("DELETE FROM comentarios WHERE ID_Comentario = ?");
        $stmt->bind_param("i", $comentario_id);
        $stmt->execute();
    }
}

// Use prepared statements também para as consultas de seleção
$stmt_post = $conn->prepare("SELECT * FROM postagem WHERE ID_Postagem = ?");
$stmt_post->bind_param("i", $post_id);
$stmt_post->execute();
$post = $stmt_post->get_result()->fetch_assoc();

$stmt_comentarios = $conn->prepare("SELECT * FROM comentarios WHERE ID_Postagem = ? ORDER BY Data_Comentario DESC");
$stmt_comentarios->bind_param("i", $post_id);
$stmt_comentarios->execute();
$comentarios = $stmt_comentarios->get_result();

if (!$post) {
    header("Location: admin.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gerenciar Publicação</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <style>
        .post-img {
            max-height: 400px;
            object-fit: contain;
        }
    </style>
</head>
<body>
    <nav class="navbar navbar-dark bg-dark">
        <div class="container-fluid">
            <a class="navbar-brand" href="admin.php">
                <i class="bi bi-arrow-left"></i> Voltar
            </a>
            <span class="navbar-text text-white">
                Gerenciando Publicação #<?= $post_id ?>
            </span>
        </div>
    </nav>

    <div class="container py-4">
        <div class="card mb-5">
            <img src="<?= $post['Foto'] ?>" class="card-img-top post-img" alt="...">
            <div class="card-body">
                <h2 class="card-title"><?= htmlspecialchars($post['Titulo_Postagem']) ?></h2>
                <p class="text-muted">
                    <i class="bi bi-geo-alt"></i> <?= htmlspecialchars($post['Localizacao_Postagem']) ?>
                    <span class="ms-3">
                        <i class="bi bi-clock"></i> <?= date('d/m/Y H:i', strtotime($post['Data_Postagem'])) ?>
                    </span>
                </p>
                <p class="card-text"><?= nl2br(htmlspecialchars($post['Descricao_Postagem'])) ?></p>
                
                <form method="POST" class="mt-4">
                    <input type="hidden" name="post_id" value="<?= $post_id ?>">
                    <button type="submit" name="excluir_post" class="btn btn-danger"
                            onclick="return confirm('Tem certeza que deseja excluir esta publicação permanentemente?')">
                        <i class="bi bi-trash"></i> Excluir Publicação
                    </button>
                </form>
            </div>
        </div>

        <h3 class="mb-4"><i class="bi bi-chat-left-text"></i> Comentários</h3>
        
        <?php if ($comentarios->num_rows > 0): ?>
            <div class="list-group mb-4">
                <?php while($comentario = $comentarios->fetch_assoc()): ?>
                    <div class="list-group-item">
                        <div class="d-flex justify-content-between">
                            <div>
                                <p class="mb-1"><?= htmlspecialchars($comentario['Conteudo_Comentario']) ?></p>
                                <small class="text-muted">
                                    <?= date('d/m/Y H:i', strtotime($comentario['Data_Comentario'])) ?>
                                </small>
                            </div>
                            <form method="POST">
                                <input type="hidden" name="comentario_id" value="<?= $comentario['ID_Comentario'] ?>">
                                <button type="submit" name="excluir_comentario" class="btn btn-sm btn-outline-danger"
                                        onclick="return confirm('Excluir este comentário?')">
                                    <i class="bi bi-trash"></i>
                                </button>
                            </form>
                        </div>
                    </div>
                <?php endwhile; ?>
            </div>
        <?php else: ?>
            <div class="alert alert-info">Nenhum comentário encontrado</div>
        <?php endif; ?>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>