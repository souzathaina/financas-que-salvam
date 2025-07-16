<?php
session_start();
include './Connection.php';

// Processamento do login
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'] ?? '';
    $senha = $_POST['senha'] ?? '';

    if (empty($email) || empty($senha)) {
        header('Location: Login.php?sucesso=0&erro=campos_vazios');
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
                header('Location: dashboard.php');
                exit();
            }
        }
        // Credenciais inválidas
        header('Location: Login.php?sucesso=0&erro=credenciais_invalidas');
        exit();
    } catch (PDOException $e) {
        error_log('Erro no login: ' . $e->getMessage());
        header('Location: Login.php?sucesso=0&erro=erro_interno');
        exit();
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
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
  <link rel="stylesheet" href="assets/css/forms.css">
  <link rel="stylesheet" href="assets/css/buttons.css">
  <link rel="stylesheet" href="assets/css/alerts.css">
  <link rel="stylesheet" href="assets/css/utilities.css">
  <link rel="stylesheet" href="assets/css/login.css">
</head>

<body>
  <a href="index.php" class="voltar-link">
    <i class="fas fa-arrow-left"></i>
    Voltar ao Início
  </a>

  <div class="login-container">
    <div class="login-card">
      <div class="login-header">
        <h1><i class="fas fa-lock"></i> Login</h1>
        <p>Entre com suas credenciais para acessar sua conta</p>
      </div>

      <?php
      // Verifica se há mensagens de erro ou sucesso
      if (isset($_GET['sucesso'])) {
        if ($_GET['sucesso'] == '0') {
          $erro = '';
          if (isset($_GET['erro'])) {
            switch ($_GET['erro']) {
              case 'campos_vazios':
                $erro = 'Por favor, preencha todos os campos.';
                break;
              case 'credenciais_invalidas':
                $erro = 'Email ou senha incorretos. Tente novamente.';
                break;
              case 'erro_interno':
                $erro = 'Erro interno do servidor. Tente novamente.';
                break;
              default:
                $erro = 'Ocorreu um erro. Tente novamente.';
            }
          }
          echo '<div class="alert erro"><i class="fas fa-exclamation-triangle"></i> ' . htmlspecialchars($erro) . '</div>';
        }
      }
      ?>

      <form action="Login.php" method="POST" id="loginForm">
        <div class="form-group">
          <label for="email">Email</label>
          <div class="input-icon">
            <i class="fas fa-envelope"></i>
            <input 
              type="email" 
              id="email" 
              name="email" 
              placeholder="seu@email.com"
              required
              autocomplete="email"
            />
          </div>
        </div>

        <div class="form-group">
          <label for="senha">Senha</label>
          <div class="input-icon">
            <i class="fas fa-lock"></i>
            <input 
              type="password" 
              id="senha" 
              name="senha" 
              placeholder="Sua senha"
              required
              autocomplete="current-password"
            />
            <button type="button" class="password-toggle" onclick="togglePassword()">
              <i class="fas fa-eye" id="eyeIcon"></i>
            </button>
          </div>
        </div>

        <button type="submit" class="btn-login">
          <i class="fas fa-sign-in-alt"></i>
          Entrar
        </button>
      </form>

      <div class="login-footer">
        <p>Não tem uma conta?</p>
        <a href="cadastrar.php" class="btn-cadastrar">
          <i class="fas fa-user-plus"></i>
          Criar Conta
        </a>
      </div>
    </div>
  </div>
  <script src="assets/js/login.js" defer></script>
</body>

</html>