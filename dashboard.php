<?php
session_start();
require_once 'includes/conexao.php'; // Inclui seu arquivo de conexão com o banco de dados
require_once 'includes/funcoes.php'; // Inclui seu arquivo de funções, que deve ter usuarioLogado()

// --- 1. VERIFICAÇÃO DE SESSÃO ---
if (!usuarioLogado()) {
    header("Location: login.php");
    exit;
}

$usuarioId = $_SESSION['id'];
$usuarioNome = $_SESSION['nome'];

// --- 2. Parâmetros de Filtro e Pesquisa ---
$termoPesquisa = $_GET['pesquisa'] ?? '';
$filtroMes = $_GET['mes'] ?? ''; // Mês selecionado (formato MM)
$filtroAno = $_GET['ano'] ?? date('Y'); // Ano selecionado (padrão: ano atual)
$filtroCategoriaId = $_GET['categoria_id'] ?? ''; // ID da categoria selecionada

// Array de meses para o dropdown
$meses = [
    '01' => 'Janeiro', '02' => 'Fevereiro', '03' => 'Março', '04' => 'Abril',
    '05' => 'Maio', '06' => 'Junho', '07' => 'Julho', '08' => 'Agosto',
    '09' => 'Setembro', '10' => 'Outubro', '11' => 'Novembro', '12' => 'Dezembro'
];

// Carregar categorias para o dropdown de filtro
$categoriasFiltro = [];
try {
    $sqlCategoriasFiltro = 'SELECT id, nome FROM categorias ORDER BY nome';
    $stmtCategoriasFiltro = $pdo->prepare($sqlCategoriasFiltro);
    $stmtCategoriasFiltro->execute();
    $categoriasFiltro = $stmtCategoriasFiltro->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    error_log("Erro ao carregar categorias para filtro: " . $e->getMessage());
    // Você pode definir uma mensagem de erro para exibir se as categorias não carregarem.
}


// --- 3. DADOS DAS DESPESAS DO USUÁRIO (com filtros) ---
$despesas = [];
try {
    $sqlDespesas = "SELECT d.id, d.data, c.nome AS categoria_nome, d.descricao, d.valor
                    FROM despesas d
                    JOIN categorias c ON d.categoria_id = c.id
                    WHERE d.usuario_id = :usuario_id";
    
    $params = ['usuario_id' => $usuarioId];

    // Adiciona filtro por mês e ano
    if (!empty($filtroMes) && !empty($filtroAno)) {
        // Para MySQL: MONTH(d.data) = :mes AND YEAR(d.data) = :ano
        // Para PostgreSQL: EXTRACT(MONTH FROM d.data) = :mes AND EXTRACT(YEAR FROM d.data) = :ano
        // Para SQLite: STRFTIME('%m', d.data) = :mes AND STRFTIME('%Y', d.data) = :ano
        
        // Exemplo para MySQL (mais comum com XAMPP/WAMP):
        $sqlDespesas .= " AND MONTH(d.data) = :mes AND YEAR(d.data) = :ano";
        $params[':mes'] = (int)$filtroMes;
        $params[':ano'] = (int)$filtroAno;
        
        // Se estiver usando PostgreSQL, substitua por:
        // $sqlDespesas .= " AND EXTRACT(MONTH FROM d.data) = :mes AND EXTRACT(YEAR FROM d.data) = :ano";
        // Se estiver usando SQLite, substitua por:
        // $sqlDespesas .= " AND STRFTIME('%m', d.data) = :mes AND STRFTIME('%Y', d.data) = :ano";
    }

    // Adiciona filtro por categoria
    if (!empty($filtroCategoriaId)) {
        $sqlDespesas .= " AND d.categoria_id = :categoria_id";
        $params[':categoria_id'] = (int)$filtroCategoriaId;
    }

    // Adiciona a condição de pesquisa textual (se houver)
    if (!empty($termoPesquisa)) {
        $sqlDespesas .= " AND (LOWER(d.descricao) LIKE LOWER(:termo_pesquisa) OR LOWER(c.nome) LIKE LOWER(:termo_pesquisa))";
        $params[':termo_pesquisa'] = '%' . $termoPesquisa . '%';
    }

    $sqlDespesas .= " ORDER BY d.data DESC, d.criado_em DESC";

    $stmtDespesas = $pdo->prepare($sqlDespesas);
    $stmtDespesas->execute($params);
    $despesas = $stmtDespesas->fetchAll(PDO::FETCH_ASSOC);

} catch (PDOException $e) {
    error_log("Erro ao carregar despesas: " . $e->getMessage());
    $erro_despesas = "Não foi possível carregar suas despesas. Tente novamente mais tarde.";
}

// --- Cálculo do Total Gasto no Mês (agora reflete os filtros) ---
$totalGastoMes = 0;
foreach ($despesas as $despesa) {
    $totalGastoMes += $despesa['valor'];
}

// --- Mensagens de Status ---
$statusMensagem = '';
$tipoMensagem = '';

if (isset($_GET['status'])) {
    if ($_GET['status'] === 'sucesso') {
        $tipoMensagem = 'sucesso';
        if (isset($_GET['msg'])) {
            switch ($_GET['msg']) {
                case 'despesa_cadastrada': $statusMensagem = 'Despesa cadastrada com sucesso!'; break;
            }
        }
    } elseif ($_GET['status'] === 'sucesso_exclusao') {
        $tipoMensagem = 'sucesso';
        $statusMensagem = 'Despesa excluída com sucesso!';
    } elseif ($_GET['status'] === 'erro_exclusao') {
        $tipoMensagem = 'erro';
        $statusMensagem = 'Erro ao excluir despesa.';
        if (isset($_GET['msg'])) {
            $statusMensagem .= ' Detalhes: ' . htmlspecialchars($_GET['msg']);
        }
    } elseif ($_GET['status'] === 'erro') {
        $tipoMensagem = 'erro';
        if (isset($_GET['msg'])) {
            switch ($_GET['msg']) {
                case 'campos_vazios': $statusMensagem = 'Por favor, preencha todos os campos obrigatórios.'; break;
                case 'valor_invalido': $statusMensagem = 'O valor da despesa é inválido.'; break;
                case 'erro_interno': $statusMensagem = 'Ocorreu um erro interno. Tente novamente mais tarde.'; break;
            }
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
                <div class="mensagem-status <?= htmlspecialchars($tipoMensagem) ?>">
                    <?= htmlspecialchars($statusMensagem) ?>
                </div>
            <?php endif; ?>

            <div class="cards">
                <div class="card">
                    <h3>Total gasto filtrado</h3>
                    <p class="valor">R$ <?= number_format($totalGastoMes, 2, ',', '.') ?></p>
                </div>
                <div class="card destaque">
                    <h3>Gráficos de gastos</h3>
                    <p class="valor"></p>
                    <small>Gráficos de comparação de Gastos/Ganhos</small>
                </div>
            </div>

            <h2 style="margin-top: 40px; text-align: center;">Minhas Despesas</h2>

            <div style="margin-top: 20px; display: flex; justify-content: center; gap: 10px;">
                <a href="cadastrar.php" class="btn-verde">Cadastrar Nova Despesa</a>
            </div>

            <div style="margin-top: 20px; text-align: center;">
                <form action="dashboard.php" method="GET" class="search-bar">
                    <input type="text" name="pesquisa" placeholder="Pesquisar por descrição ou categoria..." 
                           value="<?= htmlspecialchars($termoPesquisa) ?>">
                    <button type="submit">Pesquisar</button>
                    <?php if (!empty($termoPesquisa) || !empty($filtroMes) || !empty($filtroCategoriaId)): ?>
                        <a href="dashboard.php" class="btn-azul" style="text-decoration: none;">Limpar Filtros</a>
                    <?php endif; ?>
                </form>
            </div>

            <div style="margin-top: 20px; text-align: center;">
                <form action="dashboard.php" method="GET" class="filter-bar">
                    <?php if (!empty($termoPesquisa)): ?>
                        <input type="hidden" name="pesquisa" value="<?= htmlspecialchars($termoPesquisa) ?>">
                    <?php endif; ?>

                    <label for="mes">Mês:</label>
                    <select name="mes" id="mes">
                        <option value="">Todos</option>
                        <?php foreach ($meses as $num => $nome): ?>
                            <option value="<?= htmlspecialchars($num) ?>" 
                                <?= ($filtroMes === $num) ? 'selected' : '' ?>>
                                <?= htmlspecialchars($nome) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>

                    <label for="ano">Ano:</label>
                    <select name="ano" id="ano">
                        <?php 
                        $anoAtual = date('Y');
                        for ($i = $anoAtual; $i >= $anoAtual - 5; $i--): // Últimos 5 anos
                        ?>
                            <option value="<?= htmlspecialchars($i) ?>" 
                                <?= ((string)$filtroAno === (string)$i) ? 'selected' : '' ?>>
                                <?= htmlspecialchars($i) ?>
                            </option>
                        <?php endfor; ?>
                    </select>

                    <label for="categoria_id">Categoria:</label>
                    <select name="categoria_id" id="categoria_id">
                        <option value="">Todas</option>
                        <?php foreach ($categoriasFiltro as $categoria): ?>
                            <option value="<?= htmlspecialchars($categoria['id']) ?>" 
                                <?= ((string)$filtroCategoriaId === (string)$categoria['id']) ? 'selected' : '' ?>>
                                <?= htmlspecialchars($categoria['nome']) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                    
                    <button type="submit">Filtrar</button>
                </form>
            </div>

            <?php if (!empty($erro_despesas)): ?>
                <p style="color: red; text-align: center;"><?= htmlspecialchars($erro_despesas) ?></p>
            <?php elseif (empty($despesas)): ?>
                <p style="text-align: center; margin-top: 20px;">
                    <?php if (!empty($termoPesquisa) || !empty($filtroMes) || !empty($filtroCategoriaId)): ?>
                        Nenhuma despesa encontrada com os filtros e/ou pesquisa aplicados.
                    <?php else: ?>
                        Você ainda não tem despesas cadastradas. Comece adicionando uma!
                    <?php endif; ?>
                </p>
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