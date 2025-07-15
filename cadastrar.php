<?php
session_start();
include './conexao.php';

// Valores recebidos do form
$usuario = $_POST['usuario'] ?? '';
$categoria = $_POST['categoria'] ?? '';
$valor = $_POST['valor'] ?? '';
$data = $_POST['data'] ?? '';
$descricao = $_POST['descricao'] ?? '';

// verifica se o usuario passou pelo form 
if (empty($usuario) || empty($categorio) || empty($valor) || empty($data) || empty($desc)) {
    //se os valores estiverem vazios
    header("Location: ./" . "pagina do dashboard?" . ".php?sucesso=0&erro=campos_vazios");
    exit();
}

try {
// Inserindo o nova despesa
    $sql = 'INSERT INTO despesas (usuario,categoria,valor,data,descricao) VALUES (:usuario, :categoria, :valor, :data, :descricao)';
    $stmt = $pdo->prepare($sql);
    $stmt->execute([
        ':usuario' => $usuario,
        ':categoria' => $categoria,
        ':valor' => $valor,
        ':data' => $data,
        ':descricao' => $descricao
    ]);

    header("Location: /" . "pagina do dashboard?" . ".php?sucesso=1");
    exit();
} catch (PDOException $e) {
    error_log("Erro no cadastro: " . $e->getMessage());
    header("Location: /" . "pagina do dashboard?" . ".php?sucesso=0&erro=erro_interno");
    exit();
}


// alterar os valores de destino dos headers!!!!