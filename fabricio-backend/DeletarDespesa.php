<?php
session_start();
include './Connection.php';

// Verifica se o usuário está logado
if (!isset($_SESSION['usuario_id'])) {
    header("Location: ./" +/*pagina de login?*/ +".php?sucesso=0&erro=-1");
    exit();
}

$usuarioId = $_SESSION['usuario_id']; // ID do usuário logado
$despesaId = $_POST['id'] ?? null; // ID da despesa a ser editada

// verifica se o usuario passou pelo form 
if (empty($despesaId)) {
    //se os valores estiverem vazios
    header("Location: ./" . "pagina do dashboard?" . ".php?sucesso=0&erro=0");
    exit();
}


$sql = "SELECT * FROM despesas WHERE id = :despesa_id AND usuario_id = :usuario_id";
$stmt = $pdo->prepare($sql);
$stmt->execute([':despesa_id' => $despesaId, ':usuario_id' => $usuarioId]);
$despesa = $stmt->fetch(PDO::FETCH_ASSOC);

// Verifica se a despesa pertence ao usuário logado
if (empty($despesa)) {
    header("Location: ./" . "pagina do dashboard?" . ".php?sucesso=0&erro=1");
    exit();
}

// carrega as categorias para a nova despesa
$categorias = [];
try {
    $sqlCategorias = "SELECT id, nome FROM categorias ORDER BY nome";
    $stmtCategorias = $pdo->query($sqlCategorias);
    $categorias = $stmtCategorias->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    header("Location: ./" . "pagina do dashboard?" . ".php?sucesso=0&erro=2");
    exit();
}


// 2. Processa o formulário quando ele é enviado (dados novos para salvar)
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Pega os dados do formulário
    $novaCategoriaId = filter_input(INPUT_POST, 'categoria_id', FILTER_VALIDATE_INT);
    $novoValor = filter_input(INPUT_POST, 'valor', FILTER_VALIDATE_FLOAT);
    $novaData = trim($_POST['data']);
    $novaDescricao = trim($_POST['descricao']);

    // Validação básica dos dados
    if (empty($novaCategoriaId) || empty($novoValor) || empty($novaData) || empty($novaDescricao)) {
        echo "<script> alert(\"Todos os campos são obrigatórios e o valor/categoria deve ser válido.\")</script>";
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