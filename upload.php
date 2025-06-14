 <?php
session_start();

$conn = new mysqli('localhost', 'root', '', 'japiwatch');

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$diretorioUpload = "uploads/";

if (!file_exists($diretorioUpload)) {
    mkdir($diretorioUpload, 0777, true);
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $descricao = $_POST['descricao'];
    $especie = $_POST['especie'];
    $nomeArquivo = basename($_FILES['imagem']['name']);
    $caminhoCompleto = $diretorioUpload . $nomeArquivo;
    $extensao = strtolower(pathinfo($caminhoCompleto, PATHINFO_EXTENSION));
    
    $check = getimagesize($_FILES['imagem']['tmp_name']);
    if ($check === false) {
        $msg = "O arquivo não é uma imagem.";
        $style = "danger";
        $text_botao = "Tentar novamente";
    }
    
    $extensoesPermitidas = ['jpg', 'jpeg', 'png'];
    if (!in_array($extensao, $extensoesPermitidas)) {
        $msg = "Apenas arquivos JPG, JPEG e PNG são permitidos.";
        $style = "danger";
        $text_botao = "Tentar novamente";
    }
    
    if (move_uploaded_file($_FILES['imagem']['tmp_name'], $caminhoCompleto)) {
    // Verifica se o usuário está logado
        if (!isset($_SESSION['ID_Usuario'])) {
            die("Erro: Usuário não autenticado");
        }

        $stmt = $conn->prepare("INSERT INTO postagem 
                            (Foto, Descricao_Postagem, Titulo_Postagem, Localizacao_Postagem, ID_Categoria, Categoria) 
                            VALUES (?, ?, ?, ?, ?, '1')");
        $stmt->bind_param("ssssi", 
            $caminhoCompleto,
            $_POST['descricao'],
            $_POST['especie'],
            $_POST['localizacao'],
            $_SESSION['ID_Usuario'] // Garanta que está pegando o ID do usuário logado
        );
        
        if ($stmt->execute()) {
            $msg = "Imagem enviada com sucesso!";
            $style = "success";
            $text_botao = "Fazer outro registro";
        } else {
            $msg = "Erro ao salvar no banco de dados: " . $stmt->error;
            $style = "danger";
            $text_botao = "Tentar novamente";
        }
    } else {
        $_msg = "Erro ao fazer upload da imagem.";
        $_style = "danger";
        $text_botao = "Tentar novamente";
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="style.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-4Q6Gf2aSP4eDXB8Miphtr37CMZZQ5oXLH2yaXMJ2w8e2ZtHTl7GptT4jmndRuHDT" crossorigin="anonymous">
</head>
<body class="body2">
    <div class="teste">
        <div class="d-flex justify-content-center align-items-center">
            <div class="container">
                <div class="alert alert-<?= $style ?> text-center" role="alert">
                    <?= $msg ?>
                </div>
                <div class="d-flex gap-2 justify-content-center">
                    <a class="btn btn-success flex-grow-1" href="index.php">Voltar ao início</a>
                    <a class="btn btn-success flex-grow-1" href="form-img.php"><?= $text_botao ?></a>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/js/bootstrap.bundle.min.js" integrity="sha384-j1CDi7MgGQ12Z7Qab0qlWQ/Qqz24Gc6BM0thvEMVjHnfYGF0rmFCozFSxQBxwHKO" crossorigin="anonymous"></script>
</body>
</html>