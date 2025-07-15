<?php
session_start();
include './Connection.php';

// Verifica se o usuário está logado
if (!isset($_SESSION['usuario_id'])) {
    header("Location: ./dashboard.php?sucesso=0&erro=Deslogado");
    exit();
}

$usuarioId = $_SESSION['usuario_id']; // ID do usuário logado
$despesaId = $_POST['id'] ?? null; // ID da despesa a ser editada, vindo do form

// verifica se o usuario passou pelo form 
if (empty($despesaId)) {
    //se os valores estiverem vazios
    header("Location: ./dashboard.php?sucesso=0&erro=campos_vazios");
    exit();
}

// 1. Busca os dados atuais da despesa no banco de dados
// Selecionamos todos os campos que podem ser editados, mais o ID do usuário para segurança.
$sql = "SELECT * FROM despesas WHERE id = :despesa_id AND usuario_id = :usuario_id";
$stmt = $pdo->prepare($sql);
$stmt->execute([':despesa_id' => $despesaId, ':usuario_id' => $usuarioId]);
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
        $updateSql = "UPDATE despesas SET categoria_id = :categoria_id, valor = :valor, data = :data, descricao = :descricao WHERE id = :despesa_id";
        $updateStmt = $pdo->prepare($updateSql);

        try {
            $updateStmt->execute([
                'categoria_id' => $novaCategoriaId,
                'valor' => $novoValor,
                'data' => $novaData,
                'descricao' => $novaDescricao,
                'despesa_id' => $despesaId,
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