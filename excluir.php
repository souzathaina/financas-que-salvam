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
$despesaId = $_REQUEST['id'] ?? null; // ID da despesa a ser excluída (pode vir de GET ou POST)

// Verifica se o ID da despesa foi fornecido
if (!$despesaId) {
    echo "ID da despesa não especificado.";
    // Redireciona para a lista de despesas após um breve momento
    header("Refresh: 3; URL=lista_despesas.php");
    exit;
}

$mensagem = '';
$erro = '';
$despesa = null; // Variável para armazenar os detalhes da despesa

// Verifica se o formulário de confirmação foi enviado (requisição POST)
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Processa a exclusão
    try {
        // Prepara e executa a query de exclusão
        // IMPORTANTE: Adicione 'AND usuario_id = :usuario_id' para segurança!
        $sql = "DELETE FROM despesas WHERE id = :despesa_id AND usuario_id = :usuario_id";
        $stmt = $pdo->prepare($sql);
        $stmt->execute(['despesa_id' => $despesaId, 'usuario_id' => $usuarioId]);

        // Verifica se alguma linha foi afetada (se a despesa foi realmente excluída)
        if ($stmt->rowCount() > 0) {
            $mensagem = "Despesa excluída com sucesso!";
            // Redireciona para a lista de despesas após um breve atraso
            header("Refresh: 3; URL=lista_despesas.php?status=sucesso_exclusao");
            exit; // Importante para parar a execução após o redirecionamento
        } else {
            $erro = "Despesa não encontrada ou você não tem permissão para excluí-la.";
        }

    } catch (PDOException $e) {
        $erro = "Erro ao excluir a despesa: " . $e->getMessage();
    }
} else {
    // Requisição GET ou primeira carga da página: busca os detalhes para confirmação
    $sql = "SELECT d.descricao, d.valor, d.data, c.nome AS categoria_nome
            FROM despesas d
            JOIN categorias c ON d.categoria_id = c.id
            WHERE d.id = :despesa_id AND d.usuario_id = :usuario_id";
    $stmt = $pdo->prepare($sql);
    $stmt->execute(['despesa_id' => $despesaId, 'usuario_id' => $usuarioId]);
    $despesa = $stmt->fetch(PDO::FETCH_ASSOC);

    // Se a despesa não for encontrada ou não pertencer ao usuário
    if (!$despesa) {
        echo "Despesa não encontrada ou você não tem permissão para visualizá-la.";
        header("Refresh: 3; URL=lista_despesas.php"); // Redireciona automaticamente
        exit;
    }
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Excluir Despesa</title>
</head>
<body>
    <h1>Excluir Despesa</h1>

    <?php if (!empty($erro)): ?>
        <p style="color: red;"><?= htmlspecialchars($erro) ?></p>
        <p>Você será redirecionado em instantes...</p>
    <?php elseif (!empty($mensagem)): ?>
        <p style="color: green;"><?= htmlspecialchars($mensagem) ?></p>
        <p>Você será redirecionado em instantes...</p>
    <?php else: // Exibe a confirmação apenas se não houve erro ou sucesso ainda ?>
        <p>Você tem certeza que deseja excluir a seguinte despesa?</p>

        <ul>
            <li><strong>Descrição:</strong> <?= htmlspecialchars($despesa['descricao']) ?></li>
            <li><strong>Valor:</strong> R$ <?= number_format($despesa['valor'], 2, ',', '.') ?></li>
            <li><strong>Data:</strong> <?= htmlspecialchars(date('d/m/Y', strtotime($despesa['data']))) ?></li>
            <li><strong>Categoria:</strong> <?= htmlspecialchars($despesa['categoria_nome']) ?></li>
        </ul>

        <form action="excluir.php" method="post">
            <input type="hidden" name="id" value="<?= htmlspecialchars($despesaId) ?>">
            <button type="submit">Sim, Excluir Despesa</button>
            <a href="lista_despesas.php">Não, Voltar para a Lista</a>
        </form>
    <?php endif; ?>

    <br><br>
    <a href="logout.php">Sair</a>
</body>
</html>