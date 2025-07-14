<?php
session_start();
include './Connection.php';

// Valores recebidos do form
$nome = $_POST['nome'] ?? '';

// verifica se o usuario esta logado
if (!isset($_SESSION['usuario_id']))

// verifica se o usuario passou pelo form 
if (empty($nome)) {
    //se os valores estiverem vazios
    header("Location: ./" . "pagina do dashboard?" . ".php?sucesso=0&erro=campos_vazios");
    exit();
}

try {
// Inserindo o nova categoria
    $sql = 'INSERT INTO categorias (nome) VALUES (:nome)';
    $stmt = $pdo->prepare($sql);
    $stmt->execute([
        ':nome' => $nome,
    ]);

    header("Location: /" . "pagina do dashboard?" . ".php?sucesso=1");
    exit();
} catch (PDOException $e) {
    error_log("Erro no cadastro: " . $e->getMessage());
    header("Location: /" . "pagina do dashboard?" . ".php?sucesso=0&erro=erro_interno");
    exit();
}


// alterar os valores de destino dos headers!!!!
