<?php
session_start();
// O caminho do 'conexao.php' está correto se ele estiver dentro de 'includes/'
include './includes/conexao.php'; 

// Redireciona para o login se o usuário não estiver logado.
// É essencial proteger esta página para que apenas usuários autenticados possam acessá-la.
if (!isset($_SESSION['id'])) {
    header("Location: login.php");
    exit();
}

$categorias = []; // Inicializa a variável como um array vazio.

try {
    // Busca as categorias no banco de dados para popular o <select>
    $sql = 'SELECT id, nome FROM categorias ORDER BY nome';
    $stmt = $pdo->prepare($sql);
    $stmt->execute();
    $categorias = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    // Loga o erro para depuração (não é exibido para o usuário final)
    error_log("Erro ao carregar categorias em cadastrar.php: " . $e->getMessage());
    $erroCarregarCategorias = "Não foi possível carregar as categorias. Tente recarregar a página.";
}

// --- Lógica para exibir mensagens de status (sucesso ou erro) ---
$mensagemStatus = '';
$tipoMensagem = ''; // Define a classe CSS ('sucesso' ou 'erro')

if (isset($_GET['status'])) {
    if ($_GET['status'] === 'sucesso') {
        $tipoMensagem = 'sucesso';
        if (isset($_GET['msg']) && $_GET['msg'] === 'despesa_cadastrada') {
            $mensagemStatus = 'Despesa cadastrada com sucesso!';
        }
    } elseif ($_GET['status'] === 'erro') {
        $tipoMensagem = 'erro';
        if (isset($_GET['msg'])) {
            switch ($_GET['msg']) {
                case 'campos_vazios':
                    $mensagemStatus = 'Por favor, preencha todos os campos obrigatórios.';
                    break;
                case 'valor_invalido':
                    $mensagemStatus = 'O valor da despesa é inválido. Use apenas números (ex: 75.50).';
                    break;
                case 'erro_interno':
                    $mensagemStatus = 'Ocorreu um erro ao cadastrar a despesa. Tente novamente mais tarde.';
                    break;
                default:
                    $mensagemStatus = 'Ocorreu um erro desconhecido.';
                    break;
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Cadastrar Despesas</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="css/index.css" /> 
    <link rel="stylesheet" href="./css/cadastrar_despesas.css" />
    
</head>

<body>

    <header class="header">
        <div class="container">
            <h1>Finanças que Salvam</h1>
            <div class="acoes-header">
                <a href="dashboard.php" class="link-entrar">Voltar</a>
            </div>
        </div>
    </header>

    <section class="dashboard centro">
        <main class="main-content formulario-wrapper">
            <h1 class="titulo-cadastro">Cadastrar Nova Despesa</h1>

            <?php if (!empty($mensagemStatus)): ?>
                <div class="mensagem-status <?= htmlspecialchars($tipoMensagem) ?>">
                    <?= htmlspecialchars($mensagemStatus) ?>
                </div>
            <?php endif; ?>

            <?php if (isset($erroCarregarCategorias)): ?>
                <p style="color: red; text-align: center;"><?= htmlspecialchars($erroCarregarCategorias) ?></p>
            <?php endif; ?>

            <form action="./cadastrarBack.php" method="post" class="formulario-despesa">
                
                <input type="hidden" name="usuario_id" value="<?= htmlspecialchars($_SESSION['id']) ?>">

                <div class="campo-formulario">
                    <label for="valor">Valor (R$)</label>
                    <input type="text" id="valor" name="valor" placeholder="Ex: 75.50" required />
                </div>

                <div class="campo-formulario">
                    <label for="categorias">Categorias</label>
                    <select id="categorias" name="categorias" required>
                        <option value="">Selecione</option>

                        <?php
                        if (!empty($categorias)) {
                            foreach ($categorias as $row) {
                                echo '<option value="' . htmlspecialchars($row['id']) . '">' . htmlspecialchars($row['nome']) . '</option>';
                            }
                        } else {
                            echo '<option value="" disabled>Nenhuma categoria encontrada</option>';
                        }
                        ?>
                    </select>
                </div>

                <div class="campo-formulario">
                    <label for="data">Data</label>
                    <input type="date" id="data" name="data" required />
                </div>

                <div class="campo-formulario">
                    <label for="descricao">Descrição</label>
                    <textarea id="descricao" name="descricao" rows="3" placeholder="Descreva sua despesa..." required></textarea>
                </div>

                <div class="btn-container">
                    <button type="submit" class="btn-verde">Cadastrar Despesa</button>
                </div>

            </form>

        </main>
    </section>

</body>

</html>