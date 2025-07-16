<?php
session_start();
include './includes/Connection.php';

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
  <link rel="stylesheet" href="assets/css/cadastrar_categoria.css">
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
</body>
</html> 