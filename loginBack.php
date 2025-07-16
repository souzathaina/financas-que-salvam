<?php
session_start();

// Assegura que, se o usuário já estiver logado, ele seja redirecionado para o dashboard.
// Isso evita que um usuário logado acesse a página de login novamente.
if (isset($_SESSION['id'])) { // Verifica se a variável de sessão 'id' (do usuário logado) existe.
    header("Location: dashboard.php"); // Redireciona para o dashboard.
    exit(); // Encerra o script para garantir o redirecionamento.
}

// **PASSO 1: Inclua o arquivo de conexão com o banco de dados.**
// Com base nas nossas conversas e nos seus exemplos, o caminho mais provável é './includes/conexao.php'.
// SE o nome do seu arquivo de conexão for 'Connection.php' e estiver dentro da pasta 'includes/', use:
// include './includes/Connection.php';
// SE o seu arquivo de conexão estiver na MESMA pasta que este 'loginBack.php', use apenas:
// include 'conexao.php'; // ou 'Connection.php';
include './includes/conexao.php'; // <- VERIFIQUE E AJUSTE ESTE CAMINHO conforme a localização REAL do seu arquivo de conexão!

// Valores recebidos do formulário de login via método POST.
$email = $_POST['email'] ?? ''; // Usa o operador '??' para garantir que a variável exista, mesmo que não seja enviada pelo formulário.
$senha = $_POST['senha'] ?? '';

// **PASSO 2: Validação inicial dos campos.**
// Verifica se o campo de e-mail OU o campo de senha estão vazios.
if (empty($email) || empty($senha)) {
    // Redireciona de volta para a página de login (login.php) com uma mensagem de erro específica.
    header("Location: login.php?status=erro&msg=campos_vazios");
    exit(); // É crucial chamar exit() após um redirecionamento para parar a execução do script.
}

try {
    // **PASSO 3: Consulta o banco de dados para obter os dados do usuário.**
    // É essencial buscar o `id`, `nome` e o **hash da senha** (`senha`) armazenado no banco.
    // A coluna `senha` na sua tabela `usuarios` deve ser do tipo VARCHAR(255) para armazenar o hash.
    $sql = 'SELECT id, nome, senha FROM usuarios WHERE email = :email';
    $stmt = $pdo->prepare($sql); // `$pdo` deve estar disponível aqui após o `include` do arquivo de conexão.
    $stmt->execute([':email' => $email]);

    $usuario = $stmt->fetch(PDO::FETCH_ASSOC); // Pega a linha do usuário como um array associativo.

    // **PASSO 4: Verifica as credenciais de forma segura usando `password_verify()`.**
    // Verifica se um usuário com o e-mail fornecido foi encontrado ($usuario não é nulo/falso)
    // E se a senha digitada pelo usuário (em texto puro) corresponde ao hash da senha armazenado no banco de dados.
    if ($usuario && password_verify($senha, $usuario['senha'])) {
        // Login bem-sucedido! Define as variáveis de sessão para manter o usuário logado.
        $_SESSION['id'] = $usuario['id'];     // Armazena o ID do usuário na sessão (usado para verificar login).
        $_SESSION['nome'] = $usuario['nome']; // Armazena o nome do usuário na sessão (para personalizar o dashboard).

        // **Redireciona para a página do dashboard em caso de sucesso.**
        header("Location: dashboard.php");
        exit();
    } else {
        // Credenciais inválidas: ou o e-mail não foi encontrado, ou a senha não conferiu.
        // Redireciona de volta para a página de login com uma mensagem de erro genérica para segurança (não diz se é e-mail ou senha).
        header("Location: login.php?status=erro&msg=credenciais_invalidas");
        exit();
    }
} catch (PDOException $e) {
    // **PASSO 5: Lida com erros internos do banco de dados.**
    // Em caso de qualquer erro relacionado ao banco de dados (ex: problema de conexão, query SQL inválida).
    // O `error_log()` registra o erro no log do servidor (útil para depuração, mas não visível ao usuário).
    error_log("Erro no login: " . $e->getMessage());

    // Redireciona de volta para a página de login com uma mensagem de erro genérica de problema interno.
    header("Location: login.php?status=erro&msg=erro_interno");
    exit();
}