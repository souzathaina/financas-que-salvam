<?php
session_start();

// --- 1. Verificação de Usuário Logado ---
// É fundamental garantir que apenas usuários autenticados possam cadastrar despesas.
// Se o ID do usuário não estiver na sessão, redireciona para a página de login.
if (!isset($_SESSION['id'])) {
    header("Location: login.php");
    exit();
}

// **PASSO 2: Inclua o arquivo de conexão com o banco de dados.**
// O caminho './includes/conexao.php' é o mais provável para seu projeto.
// ATENÇÃO: Verifique e AJUSTE este caminho se o seu arquivo 'conexao.php' (ou 'Connection.php')
// estiver em um local diferente. Erros aqui impedem a conexão com o BD!
require_once 'includes/conexao.php'; 

// Pega o ID do usuário logado da sessão.
// Este é o 'usuario_id' que será salvo na tabela 'despesas'.
$usuarioId = $_SESSION['id'];

// --- 3. Recebendo os Dados do Formulário via POST ---
// Usamos o operador '??' (null coalescing operator) para pegar o valor do POST.
// Se a variável POST não existir (ex: campo não enviado), ela será uma string vazia,
// o que ajuda a evitar "Undefined index" warnings.
$categoriaId = $_POST['categorias'] ?? ''; // O `name` do select no form é 'categorias'
$valorBruto = $_POST['valor'] ?? '';      // Pega o valor como string (pode vir com vírgula)
$data = $_POST['data'] ?? '';
$descricao = $_POST['descricao'] ?? '';

// --- 4. Validação Básica dos Campos Obrigatórios ---
// Verifica se TODOS os campos essenciais foram preenchidos.
if (empty($categoriaId) || empty($valorBruto) || empty($data) || empty($descricao)) {
    // Redireciona de volta para o formulário (cadastrar.php) com uma mensagem de erro.
    header("Location: cadastrar.php?status=erro&msg=campos_vazios");
    exit();
}

// --- 5. Tratamento e Validação do Valor ---
// Substitui vírgula por ponto no valor para garantir que ele seja um número decimal válido para o PHP.
// Ex: "75,50" vira "75.50"
$valor = str_replace(',', '.', $valorBruto); 

// Verifica se o valor é numérico e se é maior que zero.
if (!is_numeric($valor) || $valor <= 0) {
    header("Location: cadastrar.php?status=erro&msg=valor_invalido");
    exit();
}
$valor = (float) $valor; // Converte a string do valor para um número decimal (float).

try {
    // --- 6. Preparando e Executando a Inserção no Banco de Dados ---
    // ATENÇÃO: É CRUCIAL que os nomes das colunas na sua tabela `despesas` correspondam EXATAMENTE a:
    // `usuario_id`, `categoria_id`, `valor`, `data`, `descricao`.
    // Se seus nomes de coluna forem diferentes (ex: `id_usuario`, `id_categoria`),
    // você DEVE ajustar a query SQL e os nomes das chaves no array `execute()`.
    $sql = 'INSERT INTO despesas (usuario_id, categoria_id, valor, data, descricao) 
            VALUES (:usuario_id, :categoria_id, :valor, :data, :descricao)';
    
    $stmt = $pdo->prepare($sql); // Prepara a declaração SQL para evitar injeção de SQL.
    $stmt->execute([
        ':usuario_id'   => $usuarioId,    // ID do usuário logado (vindo da sessão)
        ':categoria_id' => $categoriaId,  // ID da categoria selecionada (vindo do formulário)
        ':valor'        => $valor,        // Valor da despesa (tratado e validado)
        ':data'         => $data,         // Data da despesa
        ':descricao'    => $descricao     // Descrição da despesa
    ]);

    // --- 7. Redirecionamento em Caso de Sucesso ---
    // Após o cadastro ser concluído com sucesso, redireciona o usuário para o dashboard
    // com uma mensagem de sucesso na URL.
    header("Location: dashboard.php?status=sucesso&msg=despesa_cadastrada");
    exit(); // Sempre chame exit() após um redirecionamento para garantir que o script pare.

} catch (PDOException $e) {
    // --- 8. Tratamento de Erros do Banco de Dados ---
    // Em caso de qualquer erro na execução da query SQL (ex: problema de conexão com o BD,
    // erro de sintaxe SQL, ou falha de chave estrangeira).
    // `error_log()` é usado para registrar o erro REAL no log do servidor.
    // Isso é vital para depuração e NÃO deve ser exibido ao usuário final.
    error_log("Erro no cadastro de despesa em cadastrarBack.php: " . $e->getMessage());

    // Redireciona o usuário de volta para o formulário de cadastro com uma mensagem de erro genérica.
    header("Location: cadastrar.php?status=erro&msg=erro_interno");
    exit();
}