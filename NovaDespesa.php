<?php
session_start();
include './includes/Connection.php';

// Verifica se o usuário está logado
if (!isset($_SESSION['usuario_id'])) {
    header("Location: ./login.php?sucesso=0&erro=nao_logado");
    exit();
}

$usuario_id = $_SESSION['usuario_id'];

// Valores recebidos do form
$categoria = $_POST['categoria'] ?? '';
$valor = $_POST['valor'] ?? '';
$data = $_POST['data'] ?? '';
$descricao = $_POST['descricao'] ?? '';

// Validação dos campos obrigatórios
if (empty($categoria) || empty($valor) || empty($data) || empty($descricao)) {
    header("Location: ./cadastrar_despesas.php?sucesso=0&erro=campos_vazios");
    exit();
}

// Validação do valor
$valor = filter_var($valor, FILTER_VALIDATE_FLOAT);
if ($valor === false || $valor <= 0) {
    header("Location: ./cadastrar_despesas.php?sucesso=0&erro=valor_invalido");
    exit();
}

// Validação da data
$dataObj = DateTime::createFromFormat('Y-m-d', $data);
if (!$dataObj || $dataObj->format('Y-m-d') !== $data) {
    header("Location: ./cadastrar_despesas.php?sucesso=0&erro=data_invalida");
    exit();
}

// Validação da categoria
try {
    $sqlVerifica = 'SELECT id FROM categorias WHERE id = :categoria_id';
    $stmtVerifica = $pdo->prepare($sqlVerifica);
    $stmtVerifica->execute([':categoria_id' => $categoria]);
    
    if (!$stmtVerifica->fetch()) {
        header("Location: ./cadastrar_despesas.php?sucesso=0&erro=categoria_invalida");
        exit();
    }
} catch (PDOException $e) {
    header("Location: ./cadastrar_despesas.php?sucesso=0&erro=erro_interno");
    exit();
}

try {
    // Inserindo a nova despesa
    $sql = 'INSERT INTO despesas (usuario_id, categoria_id, valor, data, descricao) 
            VALUES (:usuario_id, :categoria_id, :valor, :data, :descricao)';
    $stmt = $pdo->prepare($sql);
    $stmt->execute([
        ':usuario_id' => $usuario_id,
        ':categoria_id' => $categoria,
        ':valor' => $valor,
        ':data' => $data,
        ':descricao' => $descricao
    ]);

    header("Location: ./cadastrar_despesas.php?sucesso=1");
    exit();
} catch (PDOException $e) {
    error_log("Erro no cadastro de despesa: " . $e->getMessage());
    header("Location: ./cadastrar_despesas.php?sucesso=0&erro=erro_interno");
    exit();
}
?>
