<?php
session_start();
include './Connection.php';

// Verifica se o usuário está logado
if (!isset($_SESSION['usuario_id'])) {
    header("Location: ./login.php?sucesso=0&erro=nao_logado");
    exit();
}

$usuario_id = $_SESSION['usuario_id'];
$despesa_id = $_POST['id'] ?? null;

if (!$despesa_id) {
    header("Location: ./dashboard.php?sucesso=0&erro=despesa_nao_encontrada");
    exit();
}

// Valores recebidos do form
$categoria = $_POST['categoria'] ?? '';
$valor = $_POST['valor'] ?? '';
$data = $_POST['data'] ?? '';
$descricao = $_POST['descricao'] ?? '';

// Validação dos campos obrigatórios
if (empty($categoria) || empty($valor) || empty($data) || empty($descricao)) {
    header("Location: ./editar_despesa.php?id=" . $despesa_id . "&sucesso=0&erro=campos_vazios");
    exit();
}

// Validação do valor
$valor = filter_var($valor, FILTER_VALIDATE_FLOAT);
if ($valor === false || $valor <= 0) {
    header("Location: ./editar_despesa.php?id=" . $despesa_id . "&sucesso=0&erro=valor_invalido");
    exit();
}

// Validação da data
$dataObj = DateTime::createFromFormat('Y-m-d', $data);
if (!$dataObj || $dataObj->format('Y-m-d') !== $data) {
    header("Location: ./editar_despesa.php?id=" . $despesa_id . "&sucesso=0&erro=data_invalida");
    exit();
}

// Validação da categoria
try {
    $sqlVerifica = 'SELECT id FROM categorias WHERE id = :categoria_id';
    $stmtVerifica = $pdo->prepare($sqlVerifica);
    $stmtVerifica->execute([':categoria_id' => $categoria]);
    
    if (!$stmtVerifica->fetch()) {
        header("Location: ./editar_despesa.php?id=" . $despesa_id . "&sucesso=0&erro=categoria_invalida");
        exit();
    }
} catch (PDOException $e) {
    header("Location: ./editar_despesa.php?id=" . $despesa_id . "&sucesso=0&erro=erro_interno");
    exit();
}

// Verifica se a despesa pertence ao usuário logado
try {
    $sqlVerificaDespesa = 'SELECT id FROM despesas WHERE id = :despesa_id AND usuario_id = :usuario_id';
    $stmtVerificaDespesa = $pdo->prepare($sqlVerificaDespesa);
    $stmtVerificaDespesa->execute([':despesa_id' => $despesa_id, ':usuario_id' => $usuario_id]);
    
    if (!$stmtVerificaDespesa->fetch()) {
        header("Location: ./dashboard.php?sucesso=0&erro=despesa_nao_encontrada");
        exit();
    }
} catch (PDOException $e) {
    header("Location: ./dashboard.php?sucesso=0&erro=erro_interno");
    exit();
}

try {
    // Atualiza a despesa
    $sql = 'UPDATE despesas 
            SET categoria_id = :categoria_id, valor = :valor, data = :data, descricao = :descricao 
            WHERE id = :despesa_id AND usuario_id = :usuario_id';
    $stmt = $pdo->prepare($sql);
    $stmt->execute([
        ':categoria_id' => $categoria,
        ':valor' => $valor,
        ':data' => $data,
        ':descricao' => $descricao,
        ':despesa_id' => $despesa_id,
        ':usuario_id' => $usuario_id
    ]);

    header("Location: ./dashboard.php?sucesso=1&msg=despesa_atualizada");
    exit();
} catch (PDOException $e) {
    error_log("Erro ao atualizar despesa: " . $e->getMessage());
    header("Location: ./editar_despesa.php?id=" . $despesa_id . "&sucesso=0&erro=erro_interno");
    exit();
}
?>