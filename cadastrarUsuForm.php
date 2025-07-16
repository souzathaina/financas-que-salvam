<?php
session_start();
// Não precisamos do 'Connection.php' aqui, pois ele só vai exibir HTML
// e o processamento é feito pelo seu 'cadastrarUsu.php'.

// Variáveis para mensagens de erro ou sucesso
$mensagemStatus = '';
$tipoMensagem = ''; // 'sucesso' ou 'erro'

if (isset($_GET['status'])) {
    if ($_GET['status'] === 'sucesso') {
        $mensagemStatus = 'Usuário cadastrado com sucesso! Você já pode fazer login.';
        $tipoMensagem = 'sucesso';
    } elseif ($_GET['status'] === 'erro') {
        $tipoMensagem = 'erro';
        if (isset($_GET['msg'])) {
            switch ($_GET['msg']) {
                case 'campos_vazios':
                    $mensagemStatus = 'Por favor, preencha todos os campos.';
                    break;
                case 'email_existente':
                    $mensagemStatus = 'Este e-mail já está cadastrado. Tente outro ou faça login.';
                    break;
                case 'erro_banco':
                    $mensagemStatus = 'Erro ao cadastrar usuário. Tente novamente mais tarde.';
                    break;
                default:
                    $mensagemStatus = 'Ocorreu um erro desconhecido ao cadastrar. Tente novamente.';
                    break;
            }
        } else {
            $mensagemStatus = 'Ocorreu um erro ao cadastrar. Tente novamente.';
        }
    }
}
?>

<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Cadastro de Usuário - Finanças que Salvam</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="css/index.css" />

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
            <h1 class="titulo-cadastro">Crie sua conta</h1>

            <?php if (!empty($mensagemStatus)): ?>
                <div class="mensagem-status <?= htmlspecialchars($tipoMensagem) ?>">
                    <?= htmlspecialchars($mensagemStatus) ?>
                </div>
            <?php endif; ?>

            <form action="./cadastrarUsu.php" method="post" class="formulario-cadastro">

                <div class="campo-formulario">
                    <label for="nome">Nome Completo:</label>
                    <input type="text" id="nome" name="nome" placeholder="Seu nome" required autofocus />
                </div>

                <div class="campo-formulario">
                    <label for="email">E-mail:</label>
                    <input type="email" id="email" name="email" placeholder="seu@email.com" required />
                </div>

                <div class="campo-formulario">
                    <label for="senha">Senha:</label>
                    <input type="password" id="senha" name="senha" placeholder="Crie uma senha" required />
                </div>

                <div class="btn-container">
                    <button type="submit" class="btn-cadastrar">Cadastrar</button>
                </div>

                <a href="login.php" class="link-login">Já tem conta? Faça login aqui!</a>

            </form>

        </main>
    </section>

</body>

</html>