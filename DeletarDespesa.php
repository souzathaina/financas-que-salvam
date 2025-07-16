<?php
session_start();
include './includes/Connection.php';

// Verifica se o usuário está logado
if (!isset($_SESSION['usuario_id'])) {
    header("Location: ./dashboard.php?sucesso=0&erro=-1");
    exit();
}

$usuarioId = $_SESSION['usuario_id']; // ID do usuário logado
$despesaId = $_POST['id'] ?? null; // ID da despesa a ser editada

// verifica se o usuario passou pelo form 
if (empty($despesaId)) {
    //se os valores estiverem vazios
    header("Location: ./dashboard.php?sucesso=0&erro=0");
    exit();
}


$sql = "SELECT * FROM despesas WHERE id = :despesa_id AND usuario_id = :usuario_id";
$stmt = $pdo->prepare($sql);
$stmt->execute([':despesa_id' => $despesaId, ':usuario_id' => $usuarioId]);
$despesa = $stmt->fetch(PDO::FETCH_ASSOC);

// Verifica se a despesa pertence ao usuário logado
if (empty($despesa)) {
    header("Location: ./dashboard.php?sucesso=0&erro=1");
    exit();
}

// Deleta a despesa
try {
    $sqlDelete = "DELETE FROM despesas WHERE id = :despesa_id AND usuario_id = :usuario_id";
    $stmtDelete = $pdo->prepare($sqlDelete);
    $stmtDelete->execute([
        ':despesa_id' => $despesaId,
        ':usuario_id' => $usuarioId
    ]);

    if ($stmtDelete->rowCount() > 0) {
        header("Location: ./dashboard.php?sucesso=1&msg=despesa_deletada");
        exit();
    } else {
        header("Location: ./dashboard.php?sucesso=0&erro=despesa_nao_encontrada");
        exit();
    }
} catch (PDOException $e) {
    error_log("Erro ao deletar despesa: " . $e->getMessage());
    header("Location: ./dashboard.php?sucesso=0&erro=erro_interno");
    exit();
}
?>