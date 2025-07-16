<?php
session_start();
include './Connection.php';

// Verifica se o usuário está logado
if (!isset($_SESSION['usuario_id'])) {
    header("Location: ./login.php?sucesso=0&erro=nao_logado");
    exit();
}

$usuario_id = $_SESSION['usuario_id'];
$despesa_id = $_GET['id'] ?? null;

if (!$despesa_id) {
    header("Location: ./dashboard.php?sucesso=0&erro=despesa_nao_encontrada");
    exit();
}

// Busca a despesa específica do usuário
try {
    $sql = 'SELECT d.*, c.nome as categoria_nome 
            FROM despesas d 
            LEFT JOIN categorias c ON d.categoria_id = c.id 
            WHERE d.id = :despesa_id AND d.usuario_id = :usuario_id';
    $stmt = $pdo->prepare($sql);
    $stmt->execute([':despesa_id' => $despesa_id, ':usuario_id' => $usuario_id]);
    $despesa = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$despesa) {
        header("Location: ./dashboard.php?sucesso=0&erro=despesa_nao_encontrada");
        exit();
    }
} catch (PDOException $e) {
    header("Location: ./dashboard.php?sucesso=0&erro=erro_interno");
    exit();
}

// Busca categorias disponíveis
try {
    $sql = 'SELECT id, nome FROM categorias ORDER BY nome';
    $stmt = $pdo->prepare($sql);
    $stmt->execute();
    $categorias = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    error_log("Erro ao carregar categorias: " . $e->getMessage());
    $categorias = [];
}
?>

<!DOCTYPE html>
<html lang="pt-br">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Editar Despesa - Finanças que Salvam</title>
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;700&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="assets/css/index.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
  <link rel="stylesheet" href="assets/css/forms.css">
  <link rel="stylesheet" href="assets/css/buttons.css">
  <link rel="stylesheet" href="assets/css/alerts.css">
  <link rel="stylesheet" href="assets/css/utilities.css">
  <style>
    .editar-container {
      min-height: 100vh;
      background: linear-gradient(to bottom, #a8cdfc 0%, #dceefc 60%, #ffffff 100%);
      padding: 20px;
    }
    
    .editar-card {
      max-width: 600px;
      margin: 50px auto;
      background: white;
      border-radius: 16px;
      padding: 40px;
      box-shadow: 0 8px 32px rgba(0, 0, 0, 0.1);
    }
    
    .editar-header {
      text-align: center;
      margin-bottom: 30px;
    }
    
    .editar-header h1 {
      color: #1E90FF;
      font-size: 2rem;
      margin-bottom: 10px;
    }
    
    .editar-header p {
      color: #666;
      font-size: 1rem;
    }
    
    .form-row {
      display: grid;
      grid-template-columns: 1fr 1fr;
      gap: 20px;
      margin-bottom: 20px;
    }
    
    .form-group {
      margin-bottom: 20px;
    }
    
    .form-group.full-width {
      grid-column: 1 / -1;
    }
    
    .form-group label {
      display: block;
      margin-bottom: 8px;
      color: #333;
      font-weight: 500;
      font-size: 0.9rem;
    }
    
    .form-group input,
    .form-group select {
      width: 100%;
      padding: 12px 16px;
      border: 2px solid #e1e5e9;
      border-radius: 8px;
      font-size: 16px;
      transition: all 0.3s ease;
      box-sizing: border-box;
    }
    
    .form-group input:focus,
    .form-group select:focus {
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
    
    .btn-salvar {
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
    
    .btn-salvar:hover {
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
    
    @media (max-width: 768px) {
      .form-row {
        grid-template-columns: 1fr;
      }
      
      .editar-card {
        margin: 20px auto;
        padding: 20px;
      }
      
      .btn-container {
        flex-direction: column;
        gap: 10px;
      }
      
      .btn-salvar,
      .btn-cancelar {
        width: 100%;
        text-align: center;
        justify-content: center;
      }
    }
    
    @media (max-width: 480px) {
      .editar-container {
        padding: 10px;
      }
      
      .editar-card {
        margin: 10px auto;
        padding: 15px;
      }
      
      .editar-header h1 {
        font-size: 1.5rem;
      }
      
      .form-group input,
      .form-group select {
        font-size: 16px; /* Evita zoom no iOS */
      }
      
      .voltar-link {
        top: 10px;
        left: 10px;
        font-size: 0.9rem;
      }
    }
    
    @media (max-width: 360px) {
      .editar-card {
        padding: 10px;
      }
      
      .editar-header h1 {
        font-size: 1.3rem;
      }
      
      .form-group {
        margin-bottom: 15px;
      }
    }
  </style>
</head>

<body>
  <a href="dashboard.php" class="voltar-link">
    <i class="fas fa-arrow-left"></i>
    Voltar ao Dashboard
  </a>

  <div class="editar-container">
    <div class="editar-card">
      <div class="editar-header">
        <h1><i class="fas fa-edit"></i> Editar Despesa</h1>
        <p>Atualize os dados da sua despesa</p>
      </div>

      <?php if (isset($_GET['sucesso'])): ?>
        <?php if ($_GET['sucesso'] == '1'): ?>
          <div class="alert sucesso">
            <i class="fas fa-check-circle"></i> Despesa atualizada com sucesso!
          </div>
        <?php else: ?>
          <div class="alert erro">
            <i class="fas fa-exclamation-triangle"></i> 
            <?php 
              $erro = 'Erro ao atualizar despesa.';
              if (isset($_GET['erro'])) {
                switch ($_GET['erro']) {
                  case 'campos_vazios':
                    $erro = 'Por favor, preencha todos os campos obrigatórios.';
                    break;
                  case 'valor_invalido':
                    $erro = 'Por favor, insira um valor válido para a despesa.';
                    break;
                  case 'data_invalida':
                    $erro = 'Por favor, insira uma data válida.';
                    break;
                  case 'categoria_invalida':
                    $erro = 'Por favor, selecione uma categoria válida.';
                    break;
                  default:
                    $erro = 'Erro ao atualizar despesa. Tente novamente.';
                }
              }
              echo htmlspecialchars($erro);
            ?>
          </div>
        <?php endif; ?>
      <?php endif; ?>

      <form action="EditarDespesa.php" method="POST" id="editarForm">
        <input type="hidden" name="id" value="<?php echo $despesa['id']; ?>">
        
        <div class="form-row">
          <div class="form-group">
            <label for="categoria">Categoria *</label>
            <select id="categoria" name="categoria" required>
              <option value="">Selecione uma categoria</option>
              <?php foreach ($categorias as $categoria): ?>
                <option value="<?php echo $categoria['id']; ?>" 
                        <?php echo ($categoria['id'] == $despesa['categoria_id']) ? 'selected' : ''; ?>>
                  <?php echo htmlspecialchars($categoria['nome']); ?>
                </option>
              <?php endforeach; ?>
            </select>
            <div class="info-text">
              <i class="fas fa-info-circle"></i> 
              Selecione a categoria que melhor representa esta despesa
            </div>
          </div>

          <div class="form-group">
            <label for="valor">Valor (R$) *</label>
            <input 
              type="number" 
              id="valor" 
              name="valor" 
              step="0.01" 
              min="0.01" 
              value="<?php echo $despesa['valor']; ?>"
              placeholder="0,00"
              required
            />
            <div class="info-text">
              <i class="fas fa-info-circle"></i> 
              Use vírgula para centavos (ex: 15,50)
            </div>
          </div>
        </div>

        <div class="form-row">
          <div class="form-group">
            <label for="data">Data *</label>
            <input 
              type="date" 
              id="data" 
              name="data" 
              value="<?php echo $despesa['data']; ?>"
              required
            />
            <div class="info-text">
              <i class="fas fa-info-circle"></i> 
              Data em que a despesa foi realizada
            </div>
          </div>

          <div class="form-group">
            <label for="descricao">Descrição *</label>
            <input 
              type="text" 
              id="descricao" 
              name="descricao" 
              value="<?php echo htmlspecialchars($despesa['descricao']); ?>"
              placeholder="Ex: Almoço no shopping"
              maxlength="255"
              required
            />
            <div class="info-text">
              <i class="fas fa-info-circle"></i> 
              Breve descrição da despesa
            </div>
          </div>
        </div>

        <div class="btn-container">
          <a href="dashboard.php" class="btn-cancelar">
            <i class="fas fa-times"></i> Cancelar
          </a>
          <button type="submit" class="btn-salvar">
            <i class="fas fa-save"></i> Salvar Alterações
          </button>
        </div>
      </form>
    </div>
  </div>

  <script>
    // Validação do formulário
    document.getElementById('editarForm').addEventListener('submit', function(e) {
      const categoria = document.getElementById('categoria').value;
      const valor = document.getElementById('valor').value;
      const data = document.getElementById('data').value;
      const descricao = document.getElementById('descricao').value.trim();
      
      if (!categoria || !valor || !data || !descricao) {
        e.preventDefault();
        alert('Por favor, preencha todos os campos obrigatórios.');
        return false;
      }
      
      if (parseFloat(valor) <= 0) {
        e.preventDefault();
        alert('Por favor, insira um valor válido maior que zero.');
        return false;
      }
      
      if (descricao.length < 3) {
        e.preventDefault();
        alert('A descrição deve ter pelo menos 3 caracteres.');
        return false;
      }
    });

    // Formatação automática do valor
    document.getElementById('valor').addEventListener('input', function(e) {
      let value = e.target.value.replace(/[^\d,]/g, '');
      value = value.replace(',', '.');
      if (value.includes('.')) {
        const parts = value.split('.');
        if (parts[1].length > 2) {
          parts[1] = parts[1].substring(0, 2);
        }
        value = parts[0] + '.' + parts[1];
      }
      e.target.value = value;
    });

    // Foco automático no primeiro campo
    document.addEventListener('DOMContentLoaded', function() {
      document.getElementById('categoria').focus();
    });
  </script>
</body>

</html> 