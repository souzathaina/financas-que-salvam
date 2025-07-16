<?php
// Inicia a sessão para poder acessar e definir variáveis de sessão.
session_start();

// Assegure-se de que, se o usuário já estiver logado, ele seja redirecionado para o dashboard.
// Isso evita que um usuário logado acesse a página de login novamente.
if (isset($_SESSION['id'])) { // Verifica se a variável de sessão 'id' (do usuário logado) existe.
    header("Location: dashboard.php"); // Redireciona para o dashboard
    exit(); // Encerra o script para garantir o redirecionamento.
}

// Variáveis para armazenar a mensagem de erro a ser exibida.
$mensagemErro = '';
$mostrarMensagemErro = false; // Flag para controlar a exibição da div de mensagem.

// Verifica se há parâmetros de 'status' e 'msg' na URL (vindos do backend de login).
if (isset($_GET['status']) && $_GET['status'] === 'erro') {
    $mostrarMensagemErro = true; // Define a flag para mostrar a mensagem de erro.
    $codigoErro = $_GET['msg'] ?? ''; // Pega o código da mensagem de erro.

    // Com base no código do erro, define a mensagem amigável para o usuário.
    switch ($codigoErro) {
        case 'campos_vazios':
            $mensagemErro = 'Por favor, preencha todos os campos (e-mail e senha).';
            break;
        case 'credenciais_invalidas':
            $mensagemErro = 'E-mail ou senha inválidos. Verifique suas informações e tente novamente.';
            break;
        case 'erro_interno':
            $mensagemErro = 'Ocorreu um erro inesperado no servidor. Tente novamente mais tarde.';
            break;
        default:
            $mensagemErro = 'Ocorreu um erro desconhecido. Por favor, tente novamente.';
            break;
    }
}
?>

<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Login - Finanças que Salvam</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="css/index.css" />
    <style>
        /* Estilos específicos para o formulário de login */
        .formulario-login {
            background-color: #fff;
            padding: 40px;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            max-width: 400px;
            margin: 50px auto; /* Centraliza o formulário na página */
            text-align: center;
        }
        .formulario-login .titulo-cadastro {
            margin-bottom: 30px;
            color: #333;
            font-size: 2em; /* Tamanho maior para o título */
        }
        .campo-formulario {
            margin-bottom: 20px;
            text-align: left;
        }
        .campo-formulario label {
            display: block;
            margin-bottom: 8px;
            font-weight: bold;
            color: #555;
        }
        .campo-formulario input[type="email"],
        .campo-formulario input[type="password"] {
            width: calc(100% - 20px); /* Ajuste para incluir o padding na largura total */
            padding: 12px 10px;
            border: 1px solid #ddd;
            border-radius: 6px;
            font-size: 1em;
            box-sizing: border-box; /* Garante que padding e borda não aumentem a largura */
        }
        .btn-entrar {
            background-color: #007bff; /* Azul vibrante para o botão de entrar */
            color: white;
            padding: 12px 25px;
            border: none;
            border-radius: 6px;
            font-size: 1.1em;
            cursor: pointer;
            transition: background-color 0.3s ease; /* Transição suave ao passar o mouse */
            width: 100%;
        }
        .btn-entrar:hover {
            background-color: #0056b3; /* Tom de azul mais escuro no hover */
        }
        .mensagem-erro {
            color: #dc3545; /* Vermelho mais forte para erros */
            background-color: #ffe6e6; /* Fundo suave para a mensagem de erro */
            border: 1px solid #dc3545;
            padding: 10px;
            border-radius: 5px;
            margin-bottom: 20px;
            text-align: center;
        }
        .link-cadastro {
            display: block;
            margin-top: 20px;
            color: #007bff;
            text-decoration: none;
        }
        .link-cadastro:hover {
            text-decoration: underline;
        }
    </style>
</head>

<body>

    <header class="header">
        <div class="container">
            <h1>Finanças que Salvam</h1>
            <div class="acoes-header">
                <a href="index.html" class="link-entrar">Voltar</a>
            </div>
        </div>
    </header>

    <section class="dashboard centro">
        <main class="main-content formulario-wrapper">
            <h1 class="titulo-cadastro">Acesse sua conta</h1>

            <?php if ($mostrarMensagemErro): ?>
                <div class="mensagem-erro">
                    <?= htmlspecialchars($mensagemErro) ?>
                </div>
            <?php endif; ?>

            <form action="./loginBack.php" method="post" class="formulario-login">

                <div class="campo-formulario">
                    <label for="email">E-mail:</label>
                    <input type="email" id="email" name="email" placeholder="seu@email.com" required autofocus />
                </div>

                <div class="campo-formulario">
                    <label for="senha">Senha:</label>
                    <input type="password" id="senha" name="senha" placeholder="Sua senha" required />
                </div>

                <div class="btn-container">
                    <button type="submit" class="btn-entrar">Entrar</button>
                </div>

                <a href="cadastrarUsuForm.php" class="link-cadastro">Ainda não tem conta? Cadastre-se aqui!</a>

            </form>

        </main>
    </section>

</body>

</html>