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
  <style>
    .login-container {
      min-height: 100vh;
      display: flex;
      align-items: center;
      justify-content: center;
      background: linear-gradient(to bottom, #a8cdfc 0%, #dceefc 60%, #ffffff 100%);
      padding: 20px;
    }
    
    .login-card {
      background: white;
      border-radius: 16px;
      padding: 40px;
      box-shadow: 0 8px 32px rgba(0, 0, 0, 0.1);
      width: 100%;
      max-width: 400px;
      text-align: center;
    }
    
    .login-header {
      margin-bottom: 30px;
    }
    
    .login-header h1 {
      color: #1E90FF;
      font-size: 2rem;
      margin-bottom: 10px;
    }
    
    .login-header p {
      color: #666;
      font-size: 1rem;
    }
    
    .form-group {
      margin-bottom: 20px;
      text-align: left;
    }
    
    .form-group label {
      display: block;
      margin-bottom: 8px;
      color: #333;
      font-weight: 500;
      font-size: 0.9rem;
    }
    
    .form-group input {
      width: 100%;
      padding: 12px 16px;
      border: 2px solid #e1e5e9;
      border-radius: 8px;
      font-size: 16px;
      transition: all 0.3s ease;
      box-sizing: border-box;
    }
    
    .form-group input:focus {
      outline: none;
      border-color: #1E90FF;
      box-shadow: 0 0 0 3px rgba(30, 144, 255, 0.1);
    }
    
    .form-group .input-icon {
      position: relative;
    }
    
    .form-group .input-icon i {
      position: absolute;
      left: 16px;
      top: 50%;
      transform: translateY(-50%);
      color: #999;
    }
    
    .form-group .input-icon input {
      padding-left: 45px;
    }
    
    .btn-login {
      width: 100%;
      background: linear-gradient(135deg, #1E90FF 0%, #3B82F6 100%);
      color: white;
      padding: 14px;
      border: none;
      border-radius: 8px;
      font-size: 16px;
      font-weight: 600;
      cursor: pointer;
      transition: all 0.3s ease;
      margin-top: 10px;
    }
    
    .btn-login:hover {
      transform: translateY(-2px);
      box-shadow: 0 4px 12px rgba(30, 144, 255, 0.3);
    }
    
    .btn-login:active {
      transform: translateY(0);
    }
    
    .login-footer {
      margin-top: 30px;
      padding-top: 20px;
      border-top: 1px solid #e1e5e9;
    }
    
    .login-footer p {
      color: #666;
      margin-bottom: 15px;
    }
    
    .btn-cadastrar {
      background-color: #2ecc71;
      color: white;
      padding: 12px 24px;
      border-radius: 8px;
      text-decoration: none;
      font-weight: 600;
      transition: background 0.3s ease;
      display: inline-block;
    }
    
    .btn-cadastrar:hover {
      background-color: #27ae60;
    }
    
    .alert {
      padding: 12px 16px;
      border-radius: 8px;
      margin-bottom: 20px;
      font-size: 0.9rem;
    }
    
    .alert.erro {
      background-color: #f8d7da;
      color: #721c24;
      border: 1px solid #f5c6cb;
    }
    
    .alert.sucesso {
      background-color: #d4edda;
      color: #155724;
      border: 1px solid #c3e6cb;
    }
    
    .voltar-link {
      position: absolute;
      top: 20px;
      left: 20px;
      color: #1E90FF;
      text-decoration: none;
      font-weight: 500;
      display: flex;
      align-items: center;
      gap: 8px;
      transition: color 0.3s ease;
    }
    
    .voltar-link:hover {
      color: #3B82F6;
    }
    
    .password-toggle {
      position: absolute;
      right: 16px;
      top: 50%;
      transform: translateY(-50%);
      background: none;
      border: none;
      color: #999;
      cursor: pointer;
      font-size: 16px;
    }
    
    .password-toggle:hover {
      color: #666;
    }

    @media (max-width: 768px) {
      .login-container {
        padding: 15px;
      }
      
      .login-card {
        padding: 30px 20px;
        margin: 20px auto;
      }
      
      .login-header h1 {
        font-size: 1.8rem;
      }
      
      .btn-container {
        flex-direction: column;
        gap: 10px;
      }
      
      .btn-login,
      .btn-cadastrar {
        width: 100%;
        text-align: center;
        justify-content: center;
      }
    }
    
    @media (max-width: 480px) {
      .login-container {
        padding: 10px;
      }
      
      .login-card {
        padding: 20px 15px;
        margin: 10px auto;
      }
      
      .login-header h1 {
        font-size: 1.5rem;
      }
      
      .form-group input {
        font-size: 16px; /* Evita zoom no iOS */
      }
      
      .voltar-link {
        top: 10px;
        left: 10px;
        font-size: 0.9rem;
      }
    }
    
    @media (max-width: 360px) {
      .login-card {
        padding: 15px 10px;
      }
      
      .login-header h1 {
        font-size: 1.3rem;
      }
      
      .form-group {
        margin-bottom: 15px;
      }
    }
  </style>
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

  <script>
    // Função para mostrar/ocultar senha
    function togglePassword() {
      const senhaInput = document.getElementById('senha');
      const eyeIcon = document.getElementById('eyeIcon');
      
      if (senhaInput.type === 'password') {
        senhaInput.type = 'text';
        eyeIcon.classList.remove('fa-eye');
        eyeIcon.classList.add('fa-eye-slash');
      } else {
        senhaInput.type = 'password';
        eyeIcon.classList.remove('fa-eye-slash');
        eyeIcon.classList.add('fa-eye');
      }
    }

    // Validação do formulário
    document.getElementById('loginForm').addEventListener('submit', function(e) {
      const email = document.getElementById('email').value.trim();
      const senha = document.getElementById('senha').value.trim();
      
      if (!email || !senha) {
        e.preventDefault();
        alert('Por favor, preencha todos os campos.');
        return false;
      }
      
      // Validação básica de email
      const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
      if (!emailRegex.test(email)) {
        e.preventDefault();
        alert('Por favor, insira um email válido.');
        return false;
      }
    });

    // Foco automático no primeiro campo
    document.addEventListener('DOMContentLoaded', function() {
      document.getElementById('email').focus();
    });

    // Suporte a Enter para navegar entre campos
    document.getElementById('email').addEventListener('keypress', function(e) {
      if (e.key === 'Enter') {
        document.getElementById('senha').focus();
      }
    });

    document.getElementById('senha').addEventListener('keypress', function(e) {
      if (e.key === 'Enter') {
        document.getElementById('loginForm').submit();
      }
    });
  </script>
</body>

</html>