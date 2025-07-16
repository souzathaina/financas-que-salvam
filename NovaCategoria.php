<?php
session_start();
include './includes/Connection.php';

// Verifica se o usuário está logado
if (!isset($_SESSION['usuario_id'])) {
    header("Location: ./login.php?sucesso=0&erro=nao_logado");
    exit();
}

// Valores recebidos do form
$nome = trim($_POST['nome'] ?? '');

// Validação dos campos obrigatórios
if (empty($nome)) {
    header("Location: ./cadastrar_categoria.php?sucesso=0&erro=campos_vazios");
    exit();
}

// Validação do tamanho do nome
if (strlen($nome) < 3) {
    header("Location: ./cadastrar_categoria.php?sucesso=0&erro=nome_muito_curto");
    exit();
}

if (strlen($nome) > 50) {
    header("Location: ./cadastrar_categoria.php?sucesso=0&erro=nome_muito_longo");
    exit();
}

// Verifica se a categoria já existe
try {
    $sqlVerifica = 'SELECT id FROM categorias WHERE LOWER(nome) = LOWER(:nome)';
    $stmtVerifica = $pdo->prepare($sqlVerifica);
    $stmtVerifica->execute([':nome' => $nome]);
    
    if ($stmtVerifica->fetch()) {
        header("Location: ./cadastrar_categoria.php?sucesso=0&erro=categoria_existe");
        exit();
    }
} catch (PDOException $e) {
    header("Location: ./cadastrar_categoria.php?sucesso=0&erro=erro_interno");
    exit();
}

try {
    // Inserindo a nova categoria
    $sql = 'INSERT INTO categorias (nome) VALUES (:nome)';
    $stmt = $pdo->prepare($sql);
    $stmt->execute([':nome' => $nome]);

    header("Location: ./cadastrar_categoria.php?sucesso=1");
    exit();
} catch (PDOException $e) {
    error_log("Erro no cadastro de categoria: " . $e->getMessage());
    header("Location: ./cadastrar_categoria.php?sucesso=0&erro=erro_interno");
    exit();
}
?>
