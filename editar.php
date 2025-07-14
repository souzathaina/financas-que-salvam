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
$itemId = $_GET['id'] ?? null; // ID do item a ser editado, vindo da URL

// Verifica se o ID do item foi fornecido
if (!$itemId) {
    echo "ID do item não especificado.";
    exit;
}

// 1. Busca os dados atuais do item no banco de dados
// Assumindo a tabela 'itens' com colunas 'id', 'usuario_id', 'descricao', 'valor', 'data_compra'
$sql = "SELECT id, descricao, valor, data_compra FROM itens WHERE id = :item_id AND usuario_id = :usuario_id";
$stmt = $pdo->prepare($sql);
$stmt->execute(['item_id' => $itemId, 'usuario_id' => $usuarioId]);
$item = $stmt->fetch(PDO::FETCH_ASSOC);

// Verifica se o item existe e pertence ao usuário logado
if (!$item) {
    echo "Item não encontrado ou você não tem permissão para editá-lo.";
    exit;
}

$mensagem = '';
$erro = '';

// 2. Processa o formulário quando ele é enviado (dados novos para salvar)
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Pega os dados do formulário
    $novaDescricao = trim($_POST['descricao']);
    $novoValor = filter_input(INPUT_POST, 'valor', FILTER_VALIDATE_FLOAT);
    $novaDataCompra = trim($_POST['data_compra']);

    // Validação básica dos dados
    if (empty($novaDescricao) || $novoValor === false || empty($novaDataCompra)) {
        $erro = "Todos os campos são obrigatórios e o valor deve ser um número.";
    } else {
        // Prepara e executa a query de atualização
        $updateSql = "UPDATE itens SET descricao = :descricao, valor = :valor, data_compra = :data_compra WHERE id = :item_id AND usuario_id = :usuario_id";
        $updateStmt = $pdo->prepare($updateSql);

        try {
            $updateStmt->execute([
                'descricao' => $novaDescricao,
                'valor' => $novoValor,
                'data_compra' => $novaDataCompra,
                'item_id' => $itemId,
                'usuario_id' => $usuarioId
            ]);
            $mensagem = "Item atualizado com sucesso!";

            // Atualiza a variável $item para que os novos dados sejam exibidos no formulário
            $item['descricao'] = $novaDescricao;
            $item['valor'] = $novoValor;
            $item['data_compra'] = $novaDataCompra;

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
    <title>Editar Item</title>
</head>
<body>
    <h1>Editar Item</h1>

    <?php if (!empty($erro)): ?>
        <p style="color: red;"><?= htmlspecialchars($erro) ?></p>
    <?php elseif (!empty($mensagem)): ?>
        <p style="color: green;"><?= htmlspecialchars($mensagem) ?></p>
    <?php endif; ?>

    <form method="post">
        <label for="descricao">Descrição do Item:</label><br>
        <input type="text" id="descricao" name="descricao" value="<?= htmlspecialchars($item['descricao']) ?>" required><br><br>

        <label for="valor">Valor:</label><br>
        <input type="number" id="valor" name="valor" step="0.01" value="<?= htmlspecialchars($item['valor']) ?>" required><br><br>

        <label for="data_compra">Data da Compra:</label><br>
        <input type="date" id="data_compra" name="data_compra" value="<?= htmlspecialchars($item['data_compra']) ?>" required><br><br>

        <button type="submit">Salvar Alterações</button>
        <a href="confirmar_exclusao_item.php?id=<?= htmlspecialchars($item['id']) ?>">Excluir Item</a>
    </form>

    <br><br>
    <a href="lista_itens.php">Voltar para a Lista de Itens</a> |
    <a href="logout.php">Sair</a>
</body>
</html>