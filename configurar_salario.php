<?php
session_start();
include './Connection.php';

// Verifica se o usuário está logado
if (!isset($_SESSION['usuario_id'])) {
    header("Location: ./index.php?sucesso=0&erro=nao_logado");
    exit();
}

$usuario_id = $_SESSION['usuario_id'];

// Processa o formulário
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $salario = filter_input(INPUT_POST, 'salario', FILTER_VALIDATE_FLOAT);
    
    if ($salario !== false && $salario > 0) {
        try {
            $sql = 'UPDATE usuarios SET salario = :salario WHERE id = :usuario_id';
            $stmt = $pdo->prepare($sql);
            $stmt->execute([
                ':salario' => $salario,
                ':usuario_id' => $usuario_id
            ]);
            
            header("Location: ./dashboard.php?sucesso=1&msg=salario_atualizado");
            exit();
        } catch (PDOException $e) {
            error_log("Erro ao atualizar salário: " . $e->getMessage());
            $erro = "Erro ao salvar o salário. Tente novamente.";
        }
    } else {
        $erro = "Por favor, insira um valor válido para o salário.";
    }
}

// Busca o salário atual
try {
    $sql = 'SELECT salario FROM usuarios WHERE id = :usuario_id';
    $stmt = $pdo->prepare($sql);
    $stmt->execute([':usuario_id' => $usuario_id]);
    $usuario = $stmt->fetch(PDO::FETCH_ASSOC);
    $salarioAtual = $usuario['salario'] ?? 0;
} catch (PDOException $e) {
    $salarioAtual = 0;
}
?>

<!DOCTYPE html>
<html lang="pt-br">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Configurar Salário - Finanças que Salvam</title>
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;700&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
  <link rel="stylesheet" href="assets/css/forms.css">
  <link rel="stylesheet" href="assets/css/buttons.css">
  <link rel="stylesheet" href="assets/css/alerts.css">
  <link rel="stylesheet" href="assets/css/utilities.css">
  <style>
    .config-container {
      max-width: 600px;
      margin: 50px auto;
      padding: 40px;
      background: white;
      border-radius: 12px;
      box-shadow: 0 4px 12px rgba(0,0,0,0.1);
    }
    
    .config-container h1 {
      color: #1E90FF;
      text-align: center;
      margin-bottom: 30px;
    }
    
    .form-group {
      margin-bottom: 25px;
    }
    
    .form-group label {
      display: block;
      margin-bottom: 8px;
      color: #333;
      font-weight: 500;
    }
    
    .form-group input {
      width: 100%;
      padding: 12px;
      border: 2px solid #e1e5e9;
      border-radius: 8px;
      font-size: 16px;
      transition: border-color 0.3s ease;
    }
    
    .form-group input:focus {
      outline: none;
      border-color: #1E90FF;
    }
    
    .btn-container {
      display: flex;
      gap: 15px;
      justify-content: center;
      margin-top: 30px;
    }
    
    .btn-cancelar {
      background-color: #6c757d;
      color: white;
      padding: 12px 24px;
      border-radius: 8px;
      text-decoration: none;
      font-weight: 600;
      transition: background 0.3s ease;
    }
    
    .btn-cancelar:hover {
      background-color: #5a6268;
    }
    
    .alert {
      padding: 12px;
      border-radius: 8px;
      margin-bottom: 20px;
    }
    
    .alert.erro {
      background-color: #f8d7da;
      color: #721c24;
      border: 1px solid #f5c6cb;
    }
    
    .info-text {
      color: #666;
      font-size: 0.9rem;
      margin-top: 8px;
    }

    @media (max-width: 768px) {
      .config-container {
        margin: 20px auto;
        padding: 20px;
      }
      
      .btn-container {
        flex-direction: column;
        gap: 10px;
      }
      
      .btn-verde,
      .btn-cancelar {
        width: 100%;
        text-align: center;
        justify-content: center;
      }
    }
    
    @media (max-width: 480px) {
      .config-container {
        margin: 10px auto;
        padding: 15px;
      }
      
      .config-container h1 {
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
      .config-container {
        padding: 10px;
      }
      
      .config-container h1 {
        font-size: 1.3rem;
      }
      
      .form-group {
        margin-bottom: 15px;
      }
    }
  </style>
</head>

<body>
  <!-- HEADER -->
  <header class="header">
    <div class="container">
      <h1>Finanças que Salvam</h1>
      <div class="acoes-header">
        <a href="dashboard.php" class="link-entrar">Voltar ao Dashboard</a>
      </div>
    </div>
  </header>

  <div class="config-container">
    <h1><i class="fas fa-cog"></i> Configurar Salário Mensal</h1>
    
    <?php if (isset($erro)): ?>
      <div class="alert erro">
        <i class="fas fa-exclamation-triangle"></i> <?php echo htmlspecialchars($erro); ?>
      </div>
    <?php endif; ?>
    
    <form method="POST">
      <div class="form-group">
        <label for="salario">Salário Mensal (R$)</label>
        <input 
          type="number" 
          id="salario" 
          name="salario" 
          step="0.01" 
          min="0" 
          value="<?php echo $salarioAtual; ?>"
          placeholder="Ex: 3000.00"
          required
        />
        <div class="info-text">
          <i class="fas fa-info-circle"></i> 
          Esta informação será usada para calcular o percentual de gastos em relação ao seu salário.
        </div>
      </div>
      
      <div class="btn-container">
        <a href="dashboard.php" class="btn-cancelar">
          <i class="fas fa-times"></i> Cancelar
        </a>
        <button type="submit" class="btn-verde">
          <i class="fas fa-save"></i> Salvar Salário
        </button>
      </div>
    </form>
  </div>
</body>

</html> 