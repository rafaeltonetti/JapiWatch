<?php
$conn = new mysqli('localhost', 'root', '', 'sistema_login');

$sql = "SELECT id, descricao, especie, caminho_imagem FROM imagens ORDER BY data_upload DESC";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
        echo '<div class="imagem">';
        echo '<h3>' . htmlspecialchars($row['descricao']) . '</h3>';
        echo '<img src="' . $row['caminho_imagem'] . '" alt="' . htmlspecialchars($row['descricao']) . '">';
        echo '</div>';
    }
} else {
    echo "Nenhuma imagem encontrada.";
}

$conn->close();
?>