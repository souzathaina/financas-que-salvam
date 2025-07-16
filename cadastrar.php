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
  <link rel="stylesheet" href="assets/css/cadastrar.css">
  <script src="https://kit.fontawesome.com/a2d9d3f09f.js" crossorigin="anonymous"></script>
  <?php
  include './includes/Connection.php';
  ?>
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
  <script src="assets/js/cadastrar.js" defer></script>
</body>

</html>
