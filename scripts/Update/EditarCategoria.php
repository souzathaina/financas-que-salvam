<?php
session_start();
include '../../includes/Connection.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = $_POST['id'] ?? null;
    $nome = $_POST['nome'] ?? null;

    // Validações básicas
    if (!$id || !$nome) {
        header("Location: ../../pages/Categorias/editar_categoria.php?id=$id&sucesso=0&erro=campos_vazios");
        exit(); 
    }

    try {
        $sql = "UPDATE categorias 
                SET nome = :nome 
                where id = :id";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([
            ':id' => $id,
            ':nome' => $nome
        ]);

        header("Location: ../../pages/Categorias/editar_categoria.php?id=$id&sucesso=1");
        exit();
    } catch (PDOException $e) {
        error_log("Erro ao atualizar despesa: " . $e->getMessage());
        header("Location: ../../pages/Categorias/editar_categoria.php?id=$id&sucesso=0&erro=erro_interno");
        exit();
    }
}
