<?php
session_start();

if (!isset($_SESSION["Nome_Completo"])) {
    header("location: login.php?redirect=form-img.php"); // Adiciona o parâmetro redirect
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registrar</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-4Q6Gf2aSP4eDXB8Miphtr37CMZZQ5oXLH2yaXMJ2w8e2ZtHTl7GptT4jmndRuHDT" crossorigin="anonymous">
    <link rel="stylesheet" href="style.css">
</head>
<body class="body">
    <div class="bloco">
        <div class="d-flex justify-content-center align-items-center">
            <div class="container">
                <center>
                    <h5>Registrar uma Espécie</h5>
                </center>
                <form action="upload.php" method="post" enctype="multipart/form-data">
                    <div class="mb-3">
                        <label for="especie" class="form-label">Espécie:</label>
                        <input type="text" name="especie" id="especie" class="form-control"  required>
                    </div>
                    <div class="mb-3">
                        <label for="descricao" class="form-label">Descrição da Imagem:</label>
                        <textarea class="form-control" aria-label="With textarea" name="descricao" id="descricao" maxlength="255" rows=5></textarea>
                    </div>
                    <div class="mb-3">
                        <label for="localizacao" class="form-label">Localização:</label>
                        <input type="text" name="localizacao" id="localizacao" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label for="imagem" class="form-label">Selecione a imagem:</label>
                        <input type="file" name="imagem" id="imagem" accept="image/*" class="form-control" required>
                    </div>
                    <div class="d-grid gap-2">
                        <button type="submit" class="btn btn-primary col-12">Enviar Imagem</button>
                    </div>
                </form>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/js/bootstrap.bundle.min.js" integrity="sha384-j1CDi7MgGQ12Z7Qab0qlWQ/Qqz24Gc6BM0thvEMVjHnfYGF0rmFCozFSxQBxwHKO" crossorigin="anonymous"></script>
</body>
</html>