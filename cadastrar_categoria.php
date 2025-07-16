<?php
session_start();
include './Connection.php';

// Verifica se o usuário está logado
if (!isset($_SESSION['usuario_id'])) {
    header("Location: ./login.php?sucesso=0&erro=nao_logado");
    exit();
}

$usuario_id = $_SESSION['usuario_id'];

// Busca categorias existentes para mostrar
try {
    $sql = 'SELECT id, nome FROM categorias ORDER BY nome';
    $stmt = $pdo->prepare($sql);
    $stmt->execute();
    $categorias = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    error_log("Erro ao carregar categorias: " . $e->getMessage());
    $categorias = [];
}

// Busca dados do usuário
try {
    $sql = 'SELECT nome FROM usuarios WHERE id = :usuario_id';
    $stmt = $pdo->prepare($sql);
    $stmt->execute([':usuario_id' => $usuario_id]);
    $usuario = $stmt->fetch(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    $usuario = ['nome' => 'Usuário'];
}
?>

<!DOCTYPE html>
<html lang="pt-br">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Nova Categoria - Finanças que Salvam</title>
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;700&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="assets/css/index.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
  <link rel="stylesheet" href="assets/css/forms.css">
  <link rel="stylesheet" href="assets/css/buttons.css">
  <link rel="stylesheet" href="assets/css/alerts.css">
  <link rel="stylesheet" href="assets/css/utilities.css">
  <style>
    .categoria-container {
      min-height: 100vh;
      background: linear-gradient(to bottom, #a8cdfc 0%, #dceefc 60%, #ffffff 100%);
      padding: 20px;
    }
    
    .categoria-card {
      max-width: 600px;
      margin: 50px auto;
      background: white;
      border-radius: 16px;
      padding: 40px;
      box-shadow: 0 8px 32px rgba(0, 0, 0, 0.1);
    }
    
    .categoria-header {
      text-align: center;
      margin-bottom: 30px;
    }
    
    .categoria-header h1 {
      color: #1E90FF;
      font-size: 2rem;
      margin-bottom: 10px;
    }
    
    .categoria-header p {
      color: #666;
      font-size: 1rem;
    }
    
    .form-group {
      margin-bottom: 25px;
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
    
    .btn-cadastrar {
      background: linear-gradient(135deg, #1E90FF 0%, #3B82F6 100%);
      color: white;
      padding: 12px 24px;
      border: none;
      border-radius: 8px;
      font-size: 16px;
      font-weight: 600;
      cursor: pointer;
      transition: all 0.3s ease;
    }
    
    .btn-cadastrar:hover {
      transform: translateY(-2px);
      box-shadow: 0 4px 12px rgba(30, 144, 255, 0.3);
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
    
    .info-text {
      color: #666;
      font-size: 0.8rem;
      margin-top: 5px;
    }
    
    .categorias-existentes {
      margin-top: 30px;
      padding-top: 20px;
      border-top: 1px solid #e1e5e9;
    }
    
    .categorias-existentes h3 {
      color: #1E90FF;
      margin-bottom: 15px;
      font-size: 1.1rem;
    }
    
    .categorias-grid {
      display: grid;
      grid-template-columns: repeat(auto-fill, minmax(150px, 1fr));
      gap: 10px;
    }
    
    .categoria-item {
      background: #f8f9fa;
      padding: 10px;
      border-radius: 8px;
      text-align: center;
      font-size: 0.9rem;
      color: #666;
      border: 1px solid #e1e5e9;
    }
    
    @media (max-width: 768px) {
      .categoria-card {
        margin: 20px auto;
        padding: 20px;
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
      
      .categorias-grid {
        grid-template-columns: repeat(auto-fill, minmax(120px, 1fr));
      }
    }
    
    @media (max-width: 480px) {
      .categoria-container {
        padding: 10px;
      }
      
      .categoria-card {
        margin: 10px auto;
        padding: 15px;
      }
      
      .categoria-header h1 {
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
      
      .categorias-grid {
        grid-template-columns: repeat(auto-fill, minmax(100px, 1fr));
        gap: 8px;
      }
      
      .categoria-item {
        padding: 8px;
        font-size: 0.8rem;
      }
    }
    
    @media (max-width: 360px) {
      .categoria-card {
        padding: 10px;
      }
      
      .categoria-header h1 {
        font-size: 1.3rem;
      }
      
      .form-group {
        margin-bottom: 15px;
      }
      
      .categorias-grid {
        grid-template-columns: repeat(auto-fill, minmax(80px, 1fr));
      }
    }
  </style>
</head>

<body>
  <a href="dashboard.php" class="voltar-link">
    <i class="fas fa-arrow-left"></i>
    Voltar ao Dashboard
  </a>

  <div class="categoria-container">
    <div class="categoria-card">
      <div class="categoria-header">
        <h1><i class="fas fa-tags"></i> Nova Categoria</h1>
        <p>Crie uma nova categoria para organizar suas despesas</p>
      </div>

      <?php if (isset($_GET['sucesso'])): ?>
        <?php if ($_GET['sucesso'] == '1'): ?>
          <div class="alert sucesso">
            <i class="fas fa-check-circle"></i> Categoria criada com sucesso!
          </div>
        <?php else: ?>
          <div class="alert erro">
            <i class="fas fa-exclamation-triangle"></i> 
            <?php 
              $erro = 'Erro ao criar categoria.';
              if (isset($_GET['erro'])) {
                switch ($_GET['erro']) {
                  case 'campos_vazios':
                    $erro = 'Por favor, insira o nome da categoria.';
                    break;
                  case 'categoria_existe':
                    $erro = 'Esta categoria já existe.';
                    break;
                  case 'nome_muito_curto':
                    $erro = 'O nome da categoria deve ter pelo menos 3 caracteres.';
                    break;
                  default:
                    $erro = 'Erro ao criar categoria. Tente novamente.';
                }
              }
              echo htmlspecialchars($erro);
            ?>
          </div>
        <?php endif; ?>
      <?php endif; ?>

      <form action="NovaCategoria.php" method="POST" id="categoriaForm">
        <div class="form-group">
          <label for="nome">Nome da Categoria *</label>
          <input 
            type="text" 
            id="nome" 
            name="nome" 
            placeholder="Ex: Alimentação, Transporte, Lazer..."
            maxlength="50"
            required
          />
          <div class="info-text">
            <i class="fas fa-info-circle"></i> 
            Digite um nome descritivo para a categoria
          </div>
        </div>

        <div class="btn-container">
          <a href="dashboard.php" class="btn-cancelar">
            <i class="fas fa-times"></i> Cancelar
          </a>
          <button type="submit" class="btn-cadastrar">
            <i class="fas fa-save"></i> Criar Categoria
          </button>
        </div>
      </form>

      <div class="categorias-existentes">
        <h3><i class="fas fa-list"></i> Categorias Existentes</h3>
        <div class="categorias-grid">
          <?php if (empty($categorias)): ?>
            <div class="categoria-item">Nenhuma categoria criada</div>
          <?php else: ?>
            <?php foreach ($categorias as $categoria): ?>
              <div class="categoria-item">
                <?php echo htmlspecialchars($categoria['nome']); ?>
              </div>
            <?php endforeach; ?>
          <?php endif; ?>
        </div>
      </div>
    </div>
  </div>

  <script>
    // Validação do formulário
    document.getElementById('categoriaForm').addEventListener('submit', function(e) {
      const nome = document.getElementById('nome').value.trim();
      
      if (!nome) {
        e.preventDefault();
        alert('Por favor, insira o nome da categoria.');
        return false;
      }
      
      if (nome.length < 3) {
        e.preventDefault();
        alert('O nome da categoria deve ter pelo menos 3 caracteres.');
        return false;
      }
      
      if (nome.length > 50) {
        e.preventDefault();
        alert('O nome da categoria deve ter no máximo 50 caracteres.');
        return false;
      }
    });

    // Foco automático no campo
    document.addEventListener('DOMContentLoaded', function() {
      document.getElementById('nome').focus();
    });
  </script>
</body>

</html> 