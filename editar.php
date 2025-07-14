<?php
session_start();
require_once 'includes/conexao.php'; // Garanta que este arquivo conecta ao seu banco e tem $pdo
require_once 'includes/funcoes.php'; // Garanta que este arquivo tem a função usuarioLogado()

// Verifica se o usuário está logado
if (!usuarioLogado()) {
    header("Location: login.php");
    exit;
}

$usuarioId = $_SESSION['id']; // ID do usuário logado
$despesaId = $_GET['id'] ?? null; // ID da despesa a ser editada, vindo da URL

// Verifica se o ID da despesa foi fornecido
if (!$despesaId) {
    echo "ID da despesa não especificado.";
    exit;
}

// 1. Busca os dados atuais da despesa no banco de dados
// Selecionamos todos os campos que podem ser editados, mais o ID do usuário para segurança.
$sql = "SELECT id, categoria_id, valor, data, descricao FROM despesas WHERE id = :despesa_id AND usuario_id = :usuario_id";
$stmt = $pdo->prepare($sql);
$stmt->execute(['despesa_id' => $despesaId, 'usuario_id' => $usuarioId]);
$despesa = $stmt->fetch(PDO::FETCH_ASSOC);

// Verifica se a despesa existe e pertence ao usuário logado
if (!$despesa) {
    echo "Despesa não encontrada ou você não tem permissão para editá-la.";
    exit;
}

// Além disso, precisamos carregar as categorias para o dropdown (SELECT)
$categorias = [];
try {
    $sqlCategorias = "SELECT id, nome FROM categorias ORDER BY nome";
    $stmtCategorias = $pdo->query($sqlCategorias);
    $categorias = $stmtCategorias->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    // Tratar erro se as categorias não puderem ser carregadas
    // Isso não deve impedir a edição da despesa, mas o dropdown pode ficar vazio
    $erro = "Erro ao carregar categorias: " . $e->getMessage();
}


$mensagem = '';
$erro = '';

// 2. Processa o formulário quando ele é enviado (dados novos para salvar)
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Pega os dados do formulário
    $novaCategoriaId = filter_input(INPUT_POST, 'categoria_id', FILTER_VALIDATE_INT);
    $novoValor = filter_input(INPUT_POST, 'valor', FILTER_VALIDATE_FLOAT);
    $novaData = trim($_POST['data']);
    $novaDescricao = trim($_POST['descricao']);

    // Validação básica dos dados
    if ($novaCategoriaId === false || empty($novaCategoriaId) || $novoValor === false || empty($novaData) || empty($novaDescricao)) {
        $erro = "Todos os campos são obrigatórios e o valor/categoria deve ser válido.";
    } else {
        // Prepara e executa a query de atualização
        $updateSql = "UPDATE despesas SET categoria_id = :categoria_id, valor = :valor, data = :data, descricao = :descricao WHERE id = :despesa_id AND usuario_id = :usuario_id";
        $updateStmt = $pdo->prepare($updateSql);

        try {
            $updateStmt->execute([
                'categoria_id' => $novaCategoriaId,
                'valor' => $novoValor,
                'data' => $novaData,
                'descricao' => $novaDescricao,
                'despesa_id' => $despesaId,
                'usuario_id' => $usuarioId
            ]);
            $mensagem = "Despesa atualizada com sucesso!";

            // Atualiza a variável $despesa para que os novos dados sejam exibidos no formulário
            $despesa['categoria_id'] = $novaCategoriaId;
            $despesa['valor'] = $novoValor;
            $despesa['data'] = $novaData;
            $despesa['descricao'] = $novaDescricao;

        } catch (PDOException $e) {
            $erro = "Erro ao salvar as alterações: " . $e->getMessage();
        }
    }
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Despesa</title>
</head>
<body>
    <h1>Editar Despesa</h1>

    <?php if (!empty($erro)): ?>
        <p style="color: red;"><?= htmlspecialchars($erro) ?></p>
    <?php elseif (!empty($mensagem)): ?>
        <p style="color: green;"><?= htmlspecialchars($mensagem) ?></p>
    <?php endif; ?>

    <form method="post">
        <label for="descricao">Descrição:</label><br>
        <textarea id="descricao" name="descricao" rows="4" cols="50" required><?= htmlspecialchars($despesa['descricao'] ?? '') ?></textarea><br><br>

        <label for="categoria_id">Categoria:</label><br>
        <select id="categoria_id" name="categoria_id" required>
            <?php foreach ($categorias as $categoria): ?>
                <option value="<?= htmlspecialchars($categoria['id']) ?>"
                    <?= ($categoria['id'] == $despesa['categoria_id']) ? 'selected' : '' ?>>
                    <?= htmlspecialchars($categoria['nome']) ?>
                </option>
            <?php endforeach; ?>
        </select><br><br>

        <label for="valor">Valor:</label><br>
        <input type="number" id="valor" name="valor" step="0.01" value="<?= htmlspecialchars($despesa['valor'] ?? '') ?>" required><br><br>

        <label for="data">Data:</label><br>
        <input type="date" id="data" name="data" value="<?= htmlspecialchars($despesa['data'] ?? '') ?>" required><br><br>

        <button type="submit">Salvar Alterações</button>
        <a href="confirmar_exclusao_despesa.php?id=<?= htmlspecialchars($despesa['id']) ?>">Excluir Despesa</a>
    </form>

    <br><br>
    <a href="lista_despesas.php">Voltar para a Lista de Despesas</a> |
    <a href="logout.php">Sair</a>
</body>
</html>