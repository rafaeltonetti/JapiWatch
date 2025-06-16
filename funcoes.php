<?php
function criarNotificacao($id_usuario, $tipo, $conteudo, $id_referencia = null) {
    global $conn;
    
    try {
        $stmt = $conn->prepare("INSERT INTO notificacoes 
                              (ID_Usuario, Tipo, Conteudo, ID_Referencia) 
                              VALUES (?, ?, ?, ?)");
        $stmt->bind_param("issi", $id_usuario, $tipo, $conteudo, $id_referencia);
        $stmt->execute();
        return true;
    } catch (mysqli_sql_exception $e) {
        error_log("Erro ao criar notificação: " . $e->getMessage());
        return false;
    }
}

function marcarNotificacaoComoLida($id_notificacao, $id_usuario) {
    global $conn;
    
    try {
        $stmt = $conn->prepare("UPDATE notificacoes SET Lida = 1 
                               WHERE ID_Notificacao = ? AND ID_Usuario = ?");
        $stmt->bind_param("ii", $id_notificacao, $id_usuario);
        $stmt->execute();
        return $stmt->affected_rows > 0;
    } catch (mysqli_sql_exception $e) {
        error_log("Erro ao marcar notificação como lida: " . $e->getMessage());
        return false;
    }
}

function excluirNotificacao($id_notificacao, $id_usuario) {
    global $conn;
    
    try {
        $stmt = $conn->prepare("DELETE FROM notificacoes 
                               WHERE ID_Notificacao = ? AND ID_Usuario = ?");
        $stmt->bind_param("ii", $id_notificacao, $id_usuario);
        $stmt->execute();
        return $stmt->affected_rows > 0;
    } catch (mysqli_sql_exception $e) {
        error_log("Erro ao excluir notificação: " . $e->getMessage());
        return false;
    }
}
?>