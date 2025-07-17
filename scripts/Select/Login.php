<?php
session_start();
include '../../includes/Connection.php';

// Processamento do login
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'] ?? '';
    $senha = $_POST['senha'] ?? '';

    if (empty($email) || empty($senha)) {
        header('Location: ../../pages/Usuarios/Login.php?sucesso=0&erro=campos_vazios');
        exit();
    }

    try {
        $sql = 'SELECT id, senha FROM usuarios WHERE email = :email';
        $stmt = $pdo->prepare($sql);
        $stmt->execute([':email' => $email]);
        $usuario = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($usuario) {
            // Verifica hash ou texto plano
            if (password_verify($senha, $usuario['senha']) || $senha === $usuario['senha']) {
                $_SESSION['usuario_id'] = $usuario['id'];
                header('Location: ../../pages/dashboard.php');
                exit();
            }
        }
        // Credenciais inválidas
        header('Location: ../../pages/Usuarios/Login.php?sucesso=0&erro=credenciais_invalidas');
        exit();
    } catch (PDOException $e) {
        error_log('Erro no login: ' . $e->getMessage());
        header('Location: ../../pages/Usuarios/Login.php?sucesso=0&erro=erro_interno');
        exit();
    }
}
?>