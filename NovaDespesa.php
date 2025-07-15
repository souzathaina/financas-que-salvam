<?php
session_start();
include './Connection.php';

// Valores recebidos do form
$categoria = $_POST['categoria'] ?? '';
$valor = $_POST['valor'] ?? '';
$data = $_POST['data'] ?? '';
$descricao = $_POST['descricao'] ?? '';

// verifica se o usuario passou pelo form 
if (empty($categoria) || empty($valor) || empty($data) || empty($descricao)) {
    //se os valores estiverem vazios
    header("Location: ./dashboard.php?sucesso=0&erro=campos_vazios");
    exit();
}

try {
// Inserindo o nova despesa
    $sql = 'INSERT INTO despesas (categoria,valor,data,descricao) VALUES (:categoria, :valor, :data, :descricao)';
    $stmt = $pdo->prepare($sql);
    $stmt->execute([
        ':categoria' => $categoria,
        ':valor' => $valor,
        ':data' => $data,
        ':descricao' => $descricao
    ]);

    header("Location: ./dashboard.php?sucesso=1");
    exit();
} catch (PDOException $e) {
    error_log("Erro no cadastro: " . $e->getMessage());
    header("Location: ./dashboard.php?sucesso=0&erro=erro_interno");
    exit();
}


// alterar os valores de destino dos headers!!!!
