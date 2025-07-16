<?php
session_start();
include './includes/conexao.php'; // Certifique-se de que este caminho está correto e que $pdo é definido aqui.

// Valores recebidos do form
$nome = $_POST['nome'] ?? '';
$email = $_POST['email'] ?? '';
$senha = $_POST['senha'] ?? '';
$senhaCriptografada = password_hash($senha, PASSWORD_DEFAULT); // Senha com hash

// Verifica se o usuário passou pelo form e se os campos essenciais estão preenchidos
// O 'nome' também é essencial para o cadastro, então incluí aqui.
if (empty($nome) || empty($email) || empty($senha)) {
    // Redireciona para index.php com status de erro e mensagem
    header("Location: index.php?status=erro&msg=campos_vazios");
    exit();
}

try {
    // 1. Verificando se o e-mail já existe
    $sqlVerifica = 'SELECT id FROM usuarios WHERE email = :email';
    $stmtVerifica = $pdo->prepare($sqlVerifica);
    $stmtVerifica->execute([':email' => $email]);

    if ($stmtVerifica->fetch()) {
        // E-mail já existe, redireciona para index.php com erro
        header("Location: index.php?status=erro&msg=email_em_uso");
        exit();
    }

    // 2. Validando o formato do e-mail
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        // Formato do e-mail é inválido, redireciona para index.php com erro
        header("Location: index.php?status=erro&msg=email_invalido");
        exit();
    }

    // 3. Validando o domínio do e-mail (MX record) - opcional, pode causar atrasos ou falhas
    // Recomendo ter certeza que a função checkdnsrr está habilitada no seu servidor PHP
    $dominio = substr(strrchr($email, "@"), 1);
    if (!checkdnsrr($dominio, "MX")) {
        // Domínio do e-mail é inválido, redireciona para index.php com erro
        header("Location: index.php?status=erro&msg=dominio_invalido");
        exit();
    }

    // 4. Inserindo o novo usuário no banco de dados
    $sql = 'INSERT INTO usuarios (nome, email, senha) VALUES (:nome, :email, :senha)';
    $stmt = $pdo->prepare($sql);
    $stmt->execute([
        ':nome' => $nome,
        ':email' => $email,
        ':senha' => $senhaCriptografada // Inserindo a senha criptografada
    ]);

    // 5. Setando a sessão com o usuário cadastrado
    $usuario_id = $pdo->lastInsertId();
    $_SESSION['id'] = $usuario_id; // Use 'id' para consistência com o dashboard
    $_SESSION['nome'] = $nome; // Armazena o nome para o dashboard

    // Redireciona para o dashboard.php em caso de sucesso
    header("Location: dashboard.php?status=sucesso_cadastro"); // Adicionei 'status=sucesso_cadastro' para feedback
    exit();

} catch (PDOException $e) {
    // Em caso de qualquer erro no banco de dados
    error_log("Erro no cadastro de usuário: " . $e->getMessage()); // Loga o erro para depuração
    // Redireciona para index.php com status de erro e mensagem
    header("Location: index.php?status=erro&msg=erro_interno");
    exit();
}
?>