<!DOCTYPE html>
<html lang="pt-br">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Cadastrar - Finanças que Salvam</title>
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;700&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
  <link rel="stylesheet" href="assets/css/forms.css">
  <link rel="stylesheet" href="assets/css/buttons.css">
  <link rel="stylesheet" href="assets/css/alerts.css">
  <link rel="stylesheet" href="assets/css/utilities.css">
  <script src="https://kit.fontawesome.com/a2d9d3f09f.js" crossorigin="anonymous"></script>
  <style>
    .cadastro-container {
      min-height: 100vh;
      display: flex;
      align-items: center;
      justify-content: center;
      background: linear-gradient(to bottom, #a8cdfc 0%, #dceefc 60%, #ffffff 100%);
      padding: 20px;
    }
    
    .cadastro-card {
      background: white;
      border-radius: 16px;
      padding: 40px;
      box-shadow: 0 8px 32px rgba(0, 0, 0, 0.1);
      width: 100%;
      max-width: 450px;
      text-align: center;
    }
    
    .cadastro-header {
      margin-bottom: 30px;
    }
    
    .cadastro-header h1 {
      color: #1E90FF;
      font-size: 2rem;
      margin-bottom: 10px;
    }
    
    .cadastro-header p {
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
    
    .btn-cadastrar {
      width: 100%;
      background: linear-gradient(135deg, #2ecc71 0%, #27ae60 100%);
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
    
    .btn-cadastrar:hover {
      transform: translateY(-2px);
      box-shadow: 0 4px 12px rgba(46, 204, 113, 0.3);
    }
    
    .btn-cadastrar:active {
      transform: translateY(0);
    }
    
    .cadastro-footer {
      margin-top: 30px;
      padding-top: 20px;
      border-top: 1px solid #e1e5e9;
    }
    
    .cadastro-footer p {
      color: #666;
      margin-bottom: 15px;
    }
    
    .btn-login {
      background-color: #1E90FF;
      color: white;
      padding: 12px 24px;
      border-radius: 8px;
      text-decoration: none;
      font-weight: 600;
      transition: background 0.3s ease;
      display: inline-block;
    }
    
    .btn-login:hover {
      background-color: #3B82F6;
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
    
    .password-strength {
      margin-top: 8px;
      font-size: 0.8rem;
      color: #666;
    }
    
    .strength-weak {
      color: #e74c3c;
    }
    
    .strength-medium {
      color: #f39c12;
    }
    
    .strength-strong {
      color: #27ae60;
    }

    @media (max-width: 768px) {
      .cadastro-container {
        padding: 15px;
      }
      
      .cadastro-card {
        padding: 30px 20px;
        margin: 20px auto;
      }
      
      .cadastro-header h1 {
        font-size: 1.8rem;
      }
      
      .form-row {
        grid-template-columns: 1fr;
      }
      
      .btn-container {
        flex-direction: column;
        gap: 10px;
      }
      
      .btn-cadastrar,
      .btn-cancelar {
        width: 100%;
        text-align: center;
        justify-content: center;
      }
    }
    
    @media (max-width: 480px) {
      .cadastro-container {
        padding: 10px;
      }
      
      .cadastro-card {
        padding: 20px 15px;
        margin: 10px auto;
      }
      
      .cadastro-header h1 {
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
      .cadastro-card {
        padding: 15px 10px;
      }
      
      .cadastro-header h1 {
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

  <div class="cadastro-container">
    <div class="cadastro-card">
      <div class="cadastro-header">
        <h1><i class="fas fa-user-plus"></i> Criar Conta</h1>
        <p>Cadastre-se para começar a controlar suas finanças</p>
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
              case 'email_em_uso':
                $erro = 'Este email já está sendo usado. Tente outro.';
                break;
              case 'email_invalido':
                $erro = 'Por favor, insira um email válido.';
                break;
              case 'dominio_invalido':
                $erro = 'O domínio do email parece ser inválido.';
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

      <form action="Signin.php" method="POST" id="cadastroForm">
        <div class="form-group">
          <label for="nome">Nome Completo</label>
          <div class="input-icon">
            <i class="fas fa-user"></i>
            <input 
              type="text" 
              id="nome" 
              name="nome" 
              placeholder="Seu nome completo"
              required
              autocomplete="name"
            />
          </div>
        </div>

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
              placeholder="Crie uma senha forte"
              required
              autocomplete="new-password"
              minlength="6"
            />
            <button type="button" class="password-toggle" onclick="togglePassword()">
              <i class="fas fa-eye" id="eyeIcon"></i>
            </button>
          </div>
          <div class="password-strength" id="passwordStrength"></div>
        </div>

        <div class="form-group">
          <label for="confirmarSenha">Confirmar Senha</label>
          <div class="input-icon">
            <i class="fas fa-lock"></i>
            <input 
              type="password" 
              id="confirmarSenha" 
              name="confirmarSenha" 
              placeholder="Confirme sua senha"
              required
              autocomplete="new-password"
            />
          </div>
        </div>

        <button type="submit" class="btn-cadastrar">
          <i class="fas fa-user-plus"></i>
          Criar Conta
        </button>
      </form>

      <div class="cadastro-footer">
        <p>Já tem uma conta?</p>
        <a href="login.php" class="btn-login">
          <i class="fas fa-sign-in-alt"></i>
          Fazer Login
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

    // Verificação de força da senha
    function checkPasswordStrength(password) {
      const strengthDiv = document.getElementById('passwordStrength');
      let strength = 0;
      let message = '';
      let className = '';

      if (password.length >= 6) strength++;
      if (password.match(/[a-z]/)) strength++;
      if (password.match(/[A-Z]/)) strength++;
      if (password.match(/[0-9]/)) strength++;
      if (password.match(/[^a-zA-Z0-9]/)) strength++;

      switch (strength) {
        case 0:
        case 1:
          message = 'Senha muito fraca';
          className = 'strength-weak';
          break;
        case 2:
          message = 'Senha fraca';
          className = 'strength-weak';
          break;
        case 3:
          message = 'Senha média';
          className = 'strength-medium';
          break;
        case 4:
          message = 'Senha boa';
          className = 'strength-strong';
          break;
        case 5:
          message = 'Senha muito forte';
          className = 'strength-strong';
          break;
      }

      strengthDiv.textContent = message;
      strengthDiv.className = 'password-strength ' + className;
    }

    // Validação do formulário
    document.getElementById('cadastroForm').addEventListener('submit', function(e) {
      const nome = document.getElementById('nome').value.trim();
      const email = document.getElementById('email').value.trim();
      const senha = document.getElementById('senha').value;
      const confirmarSenha = document.getElementById('confirmarSenha').value;
      
      if (!nome || !email || !senha || !confirmarSenha) {
        e.preventDefault();
        alert('Por favor, preencha todos os campos.');
        return false;
      }
      
      // Validação de email
      const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
      if (!emailRegex.test(email)) {
        e.preventDefault();
        alert('Por favor, insira um email válido.');
        return false;
      }
      
      // Validação de senha
      if (senha.length < 6) {
        e.preventDefault();
        alert('A senha deve ter pelo menos 6 caracteres.');
        return false;
      }
      
      // Validação de confirmação de senha
      if (senha !== confirmarSenha) {
        e.preventDefault();
        alert('As senhas não coincidem.');
        return false;
      }
    });

    // Event listener para verificar força da senha
    document.getElementById('senha').addEventListener('input', function() {
      checkPasswordStrength(this.value);
    });

    // Foco automático no primeiro campo
    document.addEventListener('DOMContentLoaded', function() {
      document.getElementById('nome').focus();
    });

    // Suporte a Enter para navegar entre campos
    document.getElementById('nome').addEventListener('keypress', function(e) {
      if (e.key === 'Enter') {
        document.getElementById('email').focus();
      }
    });

    document.getElementById('email').addEventListener('keypress', function(e) {
      if (e.key === 'Enter') {
        document.getElementById('senha').focus();
      }
    });

    document.getElementById('senha').addEventListener('keypress', function(e) {
      if (e.key === 'Enter') {
        document.getElementById('confirmarSenha').focus();
      }
    });

    document.getElementById('confirmarSenha').addEventListener('keypress', function(e) {
      if (e.key === 'Enter') {
        document.getElementById('cadastroForm').submit();
      }
    });
  </script>
</body>

</html>
