<?php
session_start();
require_once 'includes/conexao.php'; // Inclui seu arquivo de conexão com o banco de dados
require_once 'includes/funcoes.php'; // Inclui seu arquivo de funções, que deve ter usuarioLogado()

// --- 1. VERIFICAÇÃO DE SESSÃO ---
// Redireciona para a página de login se o usuário não estiver logado
if (!usuarioLogado()) {
    header("Location: login.php");
    exit;
}

// Pega o ID e nome do usuário da sessão
$usuarioId = $_SESSION['id'];
$usuarioNome = $_SESSION['nome']; // Assumindo que o nome do usuário está armazenado na sessão

// --- 2. DADOS DAS DESPESAS DO USUÁRIO ---
$despesas = []; // Array para armazenar as despesas do usuário
try {
    // Consulta para buscar as despesas do usuário logado
    // Fazendo um JOIN com a tabela 'categorias' para pegar o nome da categoria
    $sqlDespesas = "SELECT d.id, d.data, c.nome AS categoria_nome, d.descricao, d.valor
                    FROM despesas d
                    JOIN categorias c ON d.categoria_id = c.id
                    WHERE d.usuario_id = :usuario_id
                    ORDER BY d.data DESC, d.criado_em DESC"; // Ordena as despesas por data e depois por criação
    $stmtDespesas = $pdo->prepare($sqlDespesas);
    $stmtDespesas->execute(['usuario_id' => $usuarioId]);
    $despesas = $stmtDespesas->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    // Em caso de erro ao carregar despesas
    error_log("Erro ao carregar despesas: " . $e->getMessage());
    $erro_despesas = "Não foi possível carregar suas despesas. Tente novamente mais tarde.";
}

// --- Cálculo do Total Gasto no Mês (Exemplo Simples) ---
// Você pode adaptar isso para o mês atual, ou um período específico
$totalGastoMes = 0;
foreach ($despesas as $despesa) {
    // Aqui você pode adicionar lógica para filtrar pelo mês atual se quiser
    // Por exemplo: if (date('Y-m', strtotime($despesa['data'])) === date('Y-m')) { ... }
    $totalGastoMes += $despesa['valor'];
}

// --- Mensagens de Status (se vierem de outras páginas, como exclusão) ---
$statusMensagem = '';
if (isset($_GET['status'])) {
    if ($_GET['status'] === 'sucesso_exclusao') {
        $statusMensagem = '<p style="color: green; text-align: center;">Despesa excluída com sucesso!</p>';
    } elseif ($_GET['status'] === 'erro_exclusao') {
        $statusMensagem = '<p style="color: red; text-align: center;">Erro ao excluir despesa.</p>';
        if (isset($_GET['msg'])) {
            $statusMensagem .= '<p style="color: red; text-align: center;">Detalhes: ' . htmlspecialchars($_GET['msg']) . '</p>';
        }
    }
}
?>

<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Dashboard - Finanças que Salvam</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="css/index.css">
    <script src="https://kit.fontawesome.com/a2d9d3f09f.js" crossorigin="anonymous"></script>
   
</head>

<body>
    <header class="header">
        <div class="container">
            <h1>Finanças que Salvam</h1>
            <div class="acoes-header">
                <span class="nome-usuario"><?= htmlspecialchars($usuarioNome ?? 'Usuário') ?></span>
                <a href="./logout.php" class="btn-azul">Sair</a>
            </div>
        </div>
    </header>

    <section class="dashboard" id="dashboard">
        <main class="main-content">
            <h1>Olá, <?= htmlspecialchars($usuarioNome ?? 'jovem consciente') ?>!</h1>
            <p>Veja como seus gastos afetam seu bolso e o planeta.</p>

            <?php if (!empty($statusMensagem)): ?>
                <?= $statusMensagem ?>
            <?php endif; ?>

            <div class="cards">
                <div class="card">
                    <h3>Total gasto no mês</h3>
                    <p class="valor">R$ <?= number_format($totalGastoMes, 2, ',', '.') ?></p>
                </div>
                <div class="card destaque">
                    <h3>Impacto evitado</h3>
                    <p class="valor">🌱 23 kg de CO₂</p>
                    <small>Ao economizar em transporte e plástico</small>
                </div>
            </div>

            <h2 style="margin-top: 40px; text-align: center;">Minhas Despesas</h2>

            <div style="margin-top: 20px; display: flex; justify-content: center;">
                <a href="cadastrar.php" class="btn-verde">Cadastrar Nova Despesa</a>
            </div>


            <?php if (!empty($erro_despesas)): ?>
                <p style="color: red; text-align: center;"><?= htmlspecialchars($erro_despesas) ?></p>
            <?php elseif (empty($despesas)): ?>
                <p style="text-align: center; margin-top: 20px;">Você ainda não tem despesas cadastradas. Comece adicionando
                    uma!</p>
            <?php else: ?>
                <table class="tabela-despesas">
                    <thead>
                        <tr>
                            <th>Data</th>
                            <th>Categoria</th>
                            <th>Descrição</th>
                            <th>Valor</th>
                            <th>Ações</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($despesas as $despesa): ?>
                            <tr>
                                <td><?= htmlspecialchars(date('d/m/Y', strtotime($despesa['data']))) ?></td>
                                <td><?= htmlspecialchars($despesa['categoria_nome']) ?></td>
                                <td><?= htmlspecialchars($despesa['descricao']) ?></td>
                                <td>R$ <?= number_format($despesa['valor'], 2, ',', '.') ?></td>
                                <td>
                                    <a href="editar_despesa.php?id=<?= htmlspecialchars($despesa['id']) ?>" class="btn-editar"
                                        title="Editar Despesa">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <a href="excluir.php?id=<?= htmlspecialchars($despesa['id']) ?>" class="btn-excluir"
                                        title="Excluir Despesa">
                                        <i class="fas fa-trash-alt"></i>
                                    </a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            <?php endif; ?>
        </main>
    </section>
</body>

</html>