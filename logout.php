<?php
// Inicia a sessão. É crucial chamar session_start() em todas as páginas que manipulam sessões,
// mesmo que seja apenas para destruí-las.
session_start();

// Destroi todas as variáveis de sessão.
// Isso remove todos os dados armazenados na sessão atual, como o 'id' e 'nome' do usuário.
$_SESSION = array();

// Se você usa cookies de sessão (a maioria das instalações PHP faz isso por padrão),
// também é uma boa prática remover o cookie de sessão.
// Nota: Isso fará com que a sessão anterior seja "esquecida" pelo navegador.
if (ini_get("session.use_cookies")) {
    $params = session_get_cookie_params();
    setcookie(session_name(), '', time() - 42000,
        $params["path"], $params["domain"],
        $params["secure"], $params["httponly"]
    );
}

// Finalmente, destrói a sessão.
// Isso remove o arquivo de sessão do servidor, liberando os recursos.
session_destroy();

// Redireciona o usuário para a página inicial (index.php) após o logout.
header("Location: index.php");
exit(); // É crucial chamar exit() após um redirecionamento para garantir que nenhum outro código seja executado.
?>