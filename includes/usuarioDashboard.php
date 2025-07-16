<?php
// Verifica se o usuário está logado
if (!isset($_SESSION['usuario_id'])) {
    header("Location: ./index.php?sucesso=0&erro=nao_logado");
    exit();
}

$usuario_id = $_SESSION['usuario_id'];

try {
    // Busca as despesas do usuário
    $sql = 'SELECT d.*, c.nome as categoria_nome 
            FROM despesas d 
            LEFT JOIN categorias c ON d.categoria_id = c.id 
            WHERE d.usuario_id = :usuario_id 
            ORDER BY d.data DESC';
    $stmt = $pdo->prepare($sql);
    $stmt->execute([':usuario_id' => $usuario_id]);
    $despesas = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Calcula o total gasto
    $sqlTotal = 'SELECT SUM(valor) as total FROM despesas WHERE usuario_id = :usuario_id';
    $stmtTotal = $pdo->prepare($sqlTotal);
    $stmtTotal->execute([':usuario_id' => $usuario_id]);
    $total = $stmtTotal->fetch(PDO::FETCH_ASSOC);

    // Busca dados do usuário (incluindo salário)
    $sqlUsuario = 'SELECT nome, email, salario FROM usuarios WHERE id = :usuario_id';
    $stmtUsuario = $pdo->prepare($sqlUsuario);
    $stmtUsuario->execute([':usuario_id' => $usuario_id]);
    $usuario = $stmtUsuario->fetch(PDO::FETCH_ASSOC);

    // Calcula estatísticas por categoria - CORRIGIDA
    $sqlCategorias = 'SELECT c.nome, COALESCE(SUM(d.valor), 0) as total_categoria
                      FROM categorias c 
                      LEFT JOIN despesas d ON c.id = d.categoria_id AND d.usuario_id = :usuario_id
                      GROUP BY c.id, c.nome 
                      HAVING total_categoria > 0
                      ORDER BY total_categoria DESC';
    $stmtCategorias = $pdo->prepare($sqlCategorias);
    $stmtCategorias->execute([':usuario_id' => $usuario_id]);
    $categorias = $stmtCategorias->fetchAll(PDO::FETCH_ASSOC);

} catch (PDOException $e) {
    error_log("Erro ao carregar dashboard: " . $e->getMessage());
    $despesas = [];
    $total = ['total' => 0];
    $usuario = ['nome' => 'Usuário', 'email' => '', 'salario' => 0];
    $categorias = [];
}

// Calcula percentual gasto do salário
$salario = $usuario['salario'] ?? 0;
$totalGasto = $total['total'] ?? 0;
$percentualGasto = $salario > 0 ? ($totalGasto / $salario) * 100 : 0;
$saldoRestante = $salario - $totalGasto;
?>