<?php
session_start();
include '../../includes/Connection.php';

// Valores recebidos do form
$nome = $_POST['nome'] ?? '';
$email = $_POST['email'] ?? '';
$senha = $_POST['senha'] ?? '';
$senhaCriptografada = password_hash($senha, PASSWORD_DEFAULT); //senha com hash

// verifica se o usuario passou pelo form 
if (empty($email) || empty($senha)) {
    //$email e $senha estão vazios
    header("Location: ../../index.php?sucesso=0&erro=campos_vazios");
    exit();
}

try {
// Verificando o email
    $sqlVerifica = 'SELECT id FROM usuarios WHERE email = :email';
    $stmtVerifica = $pdo->prepare($sqlVerifica);
    $stmtVerifica->execute([':email' => $email]);

    if ($stmtVerifica->fetch()) {
        // email já existe
        header("Location: ../../index.php?sucesso=0&erro=email_em_uso");
        exit();
    }

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        // formato do email é invalido
        header("Location: ../../index.php?sucesso=0&erro=email_invalido");
        exit();
    }

    $dominio = substr(strrchr($email, "@"), 1);

    if (!checkdnsrr($dominio, "MX")) {
        // dominio do email é invalido
        header("Location: ../../index.php?sucesso=0&erro=dominio_invalido");
        exit();
    }

// Inserindo o novo usuário
    $sql = 'INSERT INTO usuarios (nome, email, senha ) VALUES (:nome, :email, :senha)';
    $stmt = $pdo->prepare($sql);
    $stmt->execute([
        ':nome' => $nome,
        ':email' => $email,
        ':senha' => $senhaCriptografada
    ]);

// setando a session com o usuario cadastrado
    $usuario_id = $pdo->lastInsertId();
    $_SESSION['usuario_id'] = $usuario_id;

    header("Location: ../../pages/dashboard.php?sucesso=1");
    exit();
} catch (PDOException $e) {
    error_log("Erro no cadastro: " . $e->getMessage());
    header("Location: ../../index.php?sucesso=0&erro=erro_interno");
    exit();
}


// alterar os valores de destino dos headers!!!!
