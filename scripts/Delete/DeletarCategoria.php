<?php
session_start();
include '../../includes/Connection.php';

// Verifica se o usuário está logado
if (!isset($_SESSION['usuario_id'])) {
    header("Location: ../../pages/dashboard.php?sucesso=0&erro=-1");
    exit();
}

$categoriaId = $_POST['id'] ?? null; // ID da despesa a ser editada

// verifica se o usuario passou pelo form 
if (empty($categoriaId)) {
    //se os valores estiverem vazios
    header("Location: ../../pages/Categorias/cadastrar_categoria.php?sucesso=0&erro=0");
    exit();
}


$sql = "SELECT * FROM categorias WHERE id = :categoria_id";
$stmt = $pdo->prepare($sql);
$stmt->execute([':categoria_id' => $categoriaId]);
$categoria = $stmt->fetch(PDO::FETCH_ASSOC);

if (empty($categoria)) {
    header("Location: ../../pages/Categorias/cadastrar_categoria.php?sucesso=0&erro=1");
    exit();
}

// Deleta a despesa
try {
    $sqlDelete = "DELETE FROM categorias WHERE id = :categoria_id";
    $stmtDelete = $pdo->prepare($sqlDelete);
    $stmtDelete->execute([
        ':categoria_id' => $categoriaId,
    ]);

    if ($stmtDelete->rowCount() > 0) {
        header("Location: ../../pages/Categorias/cadastrar_categoria.php?sucesso=2");
        exit();
    } else {
        header("Location: ../../pages/Categorias/cadastrar_categoria.php?sucesso=0&erro=despesa_nao_encontrada");
        exit();
    }
} catch (PDOException $e) {
    error_log("Erro ao deletar despesa: " . $e->getMessage());
    header("Location: ../../pages/Categorias/cadastrar_categoria.php?sucesso=0&erro=erro_interno");
    exit();
}
?>