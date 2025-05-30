 <?php
// Conectar ao banco de dados
$conn = new mysqli('localhost', 'root', '', 'sistema_login');

// Verificar conexão
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Diretório onde as imagens serão salvas
$diretorioUpload = "uploads/";

// Verificar se o diretório existe, se não, criar
if (!file_exists($diretorioUpload)) {
    mkdir($diretorioUpload, 0777, true);
}

// Processar o upload
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $descricao = $_POST['descricao'];
    $especie = $_POST['especie'];
    $nomeArquivo = basename($_FILES['imagem']['name']);
    $caminhoCompleto = $diretorioUpload . $nomeArquivo;
    $extensao = strtolower(pathinfo($caminhoCompleto, PATHINFO_EXTENSION));
    
    // Verificar se é realmente uma imagem
    $check = getimagesize($_FILES['imagem']['tmp_name']);
    if ($check === false) {
        $msg = "O arquivo não é uma imagem.";
        $style = "danger";
        $text_botao = "Tentar novamente";
    }
    
    // Verificar extensões permitidas
    $extensoesPermitidas = ['jpg', 'jpeg', 'png'];
    if (!in_array($extensao, $extensoesPermitidas)) {
        $msg = "Apenas arquivos JPG, JPEG e PNG são permitidos.";
        $style = "danger";
        $text_botao = "Tentar novamente";
    }
    
    // Mover o arquivo para o diretório de uploads
    if (move_uploaded_file($_FILES['imagem']['tmp_name'], $caminhoCompleto)) {
        // Inserir no banco de dados
        $stmt = $conn->prepare("INSERT INTO imagens (descricao, especie, caminho_imagem) VALUES (?, ?, ?)");
        $stmt->bind_param("sss", $descricao, $especie, $caminhoCompleto);
        
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