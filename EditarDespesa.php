<?php
session_start();
include './includes/Connection.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = $_POST['id'] ?? null;
    $categoria_id = $_POST['categoria'] ?? null;
    $valor = $_POST['valor'] ?? null;
    $data = $_POST['data'] ?? null;
    $descricao = $_POST['descricao'] ?? null;

    // Validações básicas
    if (!$id || !$categoria_id || !$valor || !$data || !$descricao) {
        header("Location: EditarDespesa.php?id=$id&sucesso=0&erro=campos_vazios");
        exit();
    }

    if (!is_numeric($valor) || $valor <= 0) {
        header("Location: EditarDespesa.php?id=$id&sucesso=0&erro=valor_invalido");
        exit();
    }

    if (!preg_match('/^\d{4}-\d{2}-\d{2}$/', $data)) {
        header("Location: EditarDespesa.php?id=$id&sucesso=0&erro=data_invalida");
        exit();
    }

    try {
        $sql = "UPDATE despesas 
                SET categoria_id = :categoria_id, valor = :valor, data = :data, descricao = :descricao 
                WHERE id = :id AND usuario_id = :usuario_id";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([
            ':categoria_id' => $categoria_id,
            ':valor' => $valor,
            ':data' => $data,
            ':descricao' => $descricao,
            ':id' => $id,
            ':usuario_id' => $_SESSION['usuario_id']
        ]);

        header("Location: editar_despesa.php?id=$id&sucesso=1");
        exit();
    } catch (PDOException $e) {
        error_log("Erro ao atualizar despesa: " . $e->getMessage());
        header("Location: editar_despesa.php?id=$id&sucesso=0&erro=erro_interno");
        exit();
    }
}
