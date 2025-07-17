<?php
session_start();
include '../../includes/Connection.php';

// Verifica se o usuário está logado
if (!isset($_SESSION['usuario_id'])) {
    header("Location: ./index.php?sucesso=0&erro=nao_logado");
    exit();
}

$usuario_id = $_SESSION['usuario_id'];

// Processa o formulário
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nome = filter_input(INPUT_POST, 'nome', FILTER_SANITIZE_STRING);
    $email = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL);
    $senha_atual = $_POST['senha_atual'] ?? '';
    $nova_senha = $_POST['nova_senha'] ?? '';
    $confirmar_senha = $_POST['confirmar_senha'] ?? '';
    
    $erros = [];
    
    // Validações
    if (empty($nome)) {
        $erros[] = "Nome é obrigatório.";
    }
    
    if (empty($email)) {
        $erros[] = "Email é obrigatório.";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $erros[] = "Email inválido.";
    }
    
    // Verifica se o email já existe (exceto para o usuário atual)
    if (!empty($email)) {
        try {
            $sql = 'SELECT id FROM usuarios WHERE email = :email AND id != :usuario_id';
            $stmt = $pdo->prepare($sql);
            $stmt->execute([':email' => $email, ':usuario_id' => $usuario_id]);
            if ($stmt->fetch()) {
                $erros[] = "Este email já está em uso.";
            }
        } catch (PDOException $e) {
            $erros[] = "Erro ao verificar email.";
        }
    }
    
    // Se está alterando a senha
    if (!empty($nova_senha)) {
        if (empty($senha_atual)) {
            $erros[] = "Senha atual é obrigatória para alterar a senha.";
        } else {
            // Verifica a senha atual
            try {
                $sql = 'SELECT senha FROM usuarios WHERE id = :usuario_id';
                $stmt = $pdo->prepare($sql);
                $stmt->execute([':usuario_id' => $usuario_id]);
                $usuario = $stmt->fetch(PDO::FETCH_ASSOC);
                
                if (!$usuario) {
                    $erros[] = "Usuário não encontrado.";
                } else {
                    $senha_hash = $usuario['senha'];
                    
                    // Verifica se a senha atual está correta
                    if (!password_verify($senha_atual, $senha_hash) && $senha_atual !== $senha_hash) {
                        $erros[] = "Senha atual incorreta.";
                    }
                }
            } catch (PDOException $e) {
                $erros[] = "Erro ao verificar senha atual.";
            }
        }
        
        if (strlen($nova_senha) < 6) {
            $erros[] = "Nova senha deve ter pelo menos 6 caracteres.";
        }
        
        if ($nova_senha !== $confirmar_senha) {
            $erros[] = "Nova senha e confirmação não coincidem.";
        }
    }
    
    // Se não há erros, atualiza o usuário
    if (empty($erros)) {
        try {
            if (!empty($nova_senha)) {
                // Atualiza com nova senha
                $nova_senha_hash = password_hash($nova_senha, PASSWORD_DEFAULT);
                $sql = 'UPDATE usuarios SET nome = :nome, email = :email, senha = :senha WHERE id = :usuario_id';
                $stmt = $pdo->prepare($sql);
                $stmt->execute([
                    ':nome' => $nome,
                    ':email' => $email,
                    ':senha' => $nova_senha_hash,
                    ':usuario_id' => $usuario_id
                ]);
            } else {
                // Atualiza sem alterar a senha
                $sql = 'UPDATE usuarios SET nome = :nome, email = :email WHERE id = :usuario_id';
                $stmt = $pdo->prepare($sql);
                $stmt->execute([
                    ':nome' => $nome,
                    ':email' => $email,
                    ':usuario_id' => $usuario_id
                ]);
            }
            
            header("Location: ./dashboard.php?sucesso=1&msg=perfil_atualizado");
            exit();
        } catch (PDOException $e) {
            error_log("Erro ao atualizar usuário: " . $e->getMessage());
            $erros[] = "Erro ao salvar as alterações. Tente novamente.";
        }
    }
}

// Busca os dados atuais do usuário
try {
    $sql = 'SELECT nome, email FROM usuarios WHERE id = :usuario_id';
    $stmt = $pdo->prepare($sql);
    $stmt->execute([':usuario_id' => $usuario_id]);
    $usuario = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if (!$usuario) {
        header("Location: ./dashboard.php?sucesso=0&erro=usuario_nao_encontrado");
        exit();
    }
} catch (PDOException $e) {
    header("Location: ./dashboard.php?sucesso=0&erro=erro_busca");
    exit();
}
?>

<!DOCTYPE html>
<html lang="pt-br">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Editar Perfil - Finanças que Salvam</title>
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;700&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
  <link rel="stylesheet" href="../../assets/css/header.css">
  <link rel="stylesheet" href="../../assets/css/forms.css">
  <link rel="stylesheet" href="../../assets/css/buttons.css">
  <link rel="stylesheet" href="../../assets/css/alerts.css">
  <link rel="stylesheet" href="../../assets/css/utilities.css">
  <link rel="stylesheet" href="../../assets/css/editar_usuario.css">
</head>

<body>
  <!-- HEADER -->
  <header class="header">
    <div class="container">
      <h1>Finanças que Salvam</h1>
      <div class="acoes-header">
        <span>Olá, <?php echo htmlspecialchars($usuario['nome']); ?>!</span>
        <a href="../../scripts/logout.php" class="link-entrar">Sair</a>
      </div>
    </div>
  </header>

  <div class="editar-container">
    <a href="../dashboard.php" class="voltar-link">
      <i class="fas fa-arrow-left"></i>
      Voltar ao Dashboard
    </a>
    
    <div class="editar-card">
      <div class="editar-header">
        <h1>Editar Perfil</h1>
        <p>Atualize suas informações pessoais</p>
      </div>
      
      <?php if (!empty($erros)): ?>
        <div class="alert alert-error">
          <i class="fas fa-exclamation-triangle"></i>
          <div>
            <strong>Erro:</strong>
            <ul style="margin: 5px 0 0 20px; padding: 0;">
              <?php foreach ($erros as $erro): ?>
                <li><?php echo htmlspecialchars($erro); ?></li>
              <?php endforeach; ?>
            </ul>
          </div>
        </div>
      <?php endif; ?>
      
      <form method="POST" action="">
        <!-- Informações Básicas -->
        <div class="form-section">
          <h3><i class="fas fa-user"></i> Informações Básicas</h3>
          
          <div class="form-group">
            <label for="nome">Nome Completo *</label>
            <input type="text" id="nome" name="nome" value="<?php echo htmlspecialchars($usuario['nome']); ?>" required>
          </div>
          
          <div class="form-group">
            <label for="email">Email *</label>
            <input type="email" id="email" name="email" value="<?php echo htmlspecialchars($usuario['email']); ?>" required>
          </div>
        </div>
        
        <!-- Alteração de Senha -->
        <div class="form-section">
          <h3><i class="fas fa-lock"></i> Alterar Senha</h3>
          
          <div class="senha-info">
            <i class="fas fa-info-circle"></i>
            Deixe os campos de senha em branco se não quiser alterar sua senha atual.
          </div>
          
          <div class="form-group">
            <label for="senha_atual">Senha Atual</label>
            <input type="password" id="senha_atual" name="senha_atual" placeholder="Digite sua senha atual">
          </div>
          
          <div class="form-group">
            <label for="nova_senha">Nova Senha</label>
            <input type="password" id="nova_senha" name="nova_senha" placeholder="Digite a nova senha (mín. 6 caracteres)">
          </div>
          
          <div class="form-group">
            <label for="confirmar_senha">Confirmar Nova Senha</label>
            <input type="password" id="confirmar_senha" name="confirmar_senha" placeholder="Confirme a nova senha">
          </div>
        </div>
        
        <div class="btn-container">
          <button type="submit" class="btn btn-primary">
            <i class="fas fa-save"></i>
            Salvar Alterações
          </button>
          <a href="../dashboard.php" class="btn btn-secondary">
            <i class="fas fa-times"></i>
            Cancelar
          </a>
        </div>
      </form>
    </div>
  </div>
</body>

</html> 