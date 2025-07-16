<?php
session_start();
header('Content-Type: application/json');
include './includes/Connection.php';

if (!isset($_SESSION['usuario_id'])) {
    echo json_encode(['salario' => 0, 'gasto' => 0]);
    exit;
}

$usuario_id = $_SESSION['usuario_id'];
$mes = $_GET['mes'] ?? '';
$ano = $_GET['ano'] ?? '';
$categoria_id = $_GET['categoria_id'] ?? '';

$where = 'usuario_id = :usuario_id';
$params = [':usuario_id' => $usuario_id];
if ($mes) {
    $where .= ' AND MONTH(data) = :mes';
    $params[':mes'] = $mes;
}
if ($ano) {
    $where .= ' AND YEAR(data) = :ano';
    $params[':ano'] = $ano;
}
if ($categoria_id) {
    $where .= ' AND categoria_id = :categoria_id';
    $params[':categoria_id'] = $categoria_id;
}

try {
    // Buscar salário
    $sqlSalario = 'SELECT salario FROM usuarios WHERE id = :usuario_id';
    $stmtSalario = $pdo->prepare($sqlSalario);
    $stmtSalario->execute([':usuario_id' => $usuario_id]);
    $usuario = $stmtSalario->fetch(PDO::FETCH_ASSOC);
    $salario = $usuario ? (float)$usuario['salario'] : 0;

    // Buscar total gasto filtrado
    $sqlGasto = "SELECT SUM(valor) as total FROM despesas WHERE $where";
    $stmtGasto = $pdo->prepare($sqlGasto);
    $stmtGasto->execute($params);
    $gasto = $stmtGasto->fetch(PDO::FETCH_ASSOC);
    $totalGasto = $gasto && $gasto['total'] !== null ? (float)$gasto['total'] : 0;

    echo json_encode(['salario' => $salario, 'gasto' => $totalGasto]);
} catch (PDOException $e) {
    echo json_encode(['salario' => 0, 'gasto' => 0]);
} 