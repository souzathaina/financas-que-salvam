<?php
session_start();
include './Connection.php';

// valores recebidos do form
$email = $_POST['email'] ?? '';
$senha = $_POST['senha'] ?? '';

if (empty($email) || empty($senha)) {
    header("Location: ./"+/*pagina de login?*/+".php?sucesso=0&erro=campos_vazios");
    exit();
}

try {
    // Consulta com parâmetros protegidos
    $sql = 'SELECT id FROM usuarios WHERE email = :email AND senha = :senha';
    $stmt = $pdo->prepare($sql);
    $stmt->execute([
        ':email' => $email,
        ':senha' => $senha
    ]);

    $resultado = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($resultado) {
        $_SESSION['usuario_id'] = $resultado['id'];
        header("Location: /"+/*pagina do dashboard?*/+".php?sucesso=1");
        exit();
    } else {
        header("Location: /"+/*pagina de login?*/+".php?sucesso=0&erro=credenciais_invalidas");
        exit();
    }
} catch (PDOException $e) {
    error_log("Erro no login: " . $e->getMessage());

    header("Location: /"+/*pagina de login?*/+".php?sucesso=0&erro=erro_interno");
    exit();
}
