<?php
session_start();

// Destroi a sessão
session_destroy();

// Remove todas as variáveis de sessão
$_SESSION = array();

// Se desejar destruir completamente a sessão, apague também o cookie de sessão
if (ini_get("session.use_cookies")) {
    $params = session_get_cookie_params();
    setcookie(session_name(), '', time() - 42000,
        $params["path"], $params["domain"],
        $params["secure"], $params["httponly"]
    );
}

// Redireciona para a página inicial
header("Location: ../index.php?msg=logout_sucesso");
exit();