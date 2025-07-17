<?php
session_start();
include '../../includes/Connection.php';

// Verifica se o usuário está logado
if (!isset($_SESSION['usuario_id'])) {
  header("Location: ../Usuarios/login.php?sucesso=0&erro=nao_logado");
  exit();
}

$categoria_id = $_GET['id'] ?? null;

if (empty($categoria_id)) {
  header("Location: ../dashboard.php?sucesso=0&erro=categoria_nao_encontrada");
  exit();
}

// Busca a categoria
try {
  $sql = 'SELECT id, c.nome as categoria_nome 
            FROM categorias c 
            WHERE c.id = :categoria_id';
  $stmt = $pdo->prepare($sql);
  $stmt->execute([':categoria_id' => $categoria_id,]);
  $categoria = $stmt->fetch(PDO::FETCH_ASSOC);

  if (empty($categoria)) {
    header("Location: ../dashboard.php?sucesso=0&erro=categoria_nao_encontrada");
    exit();
  }
} catch (PDOException $e) {
  header("Location: ../dashboard.php?sucesso=0&erro=erro_interno");
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
  <link rel="stylesheet" href="../../assets/css/index.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
  <link rel="stylesheet" href="../../assets/css/forms.css">
  <link rel="stylesheet" href="../../assets/css/buttons.css">
  <link rel="stylesheet" href="../../assets/css/alerts.css">
  <link rel="stylesheet" href="../../assets/css/utilities.css">
  <link rel="stylesheet" href="../../assets/css/editar_despesas.css">
</head>

<body>
  <a href="../dashboard.php" class="voltar-link">
    <i class="fas fa-arrow-left"></i>
    Voltar ao Dashboard
  </a>

  <div class="editar-container">
    <div class="editar-card">
      <div class="editar-header">
        <h1><i class="fas fa-edit"></i> Editar Categoria</h1>
        <p>Atualize os dados da sua categoria</p>
      </div>

      <?php if (isset($_GET['sucesso'])): ?>
        <?php if ($_GET['sucesso'] == '1'): ?>
          <div class="alert sucesso">
            <i class="fas fa-check-circle"></i> Categoria atualizada com sucesso!
          </div>
        <?php endif; ?>
      <?php else: ?>
        <div class="alert erro">
          <i class="fas fa-exclamation-triangle"></i>
          <?php
          $erro = 'Erro ao atualizar categoria.';
          if (isset($_GET['erro'])) {
            switch ($_GET['erro']) {
              case 'campos_vazios':
                $erro = 'Por favor, preencha todos os campos obrigatórios.';
                break;
              default:
                $erro = 'Erro ao atualizar despesa. Tente novamente.';
            }
          }
          echo htmlspecialchars($erro);
          ?>
        </div>
      <?php endif; ?>

      <form action="../../scripts/Update/EditarCategoria.php" method="POST" id="editarForm">
        <input type="hidden" name="id" value="<?php echo $categoria['id']; ?>">

        <div class="form-row">
          <div class="form-group">
            <label for="nome">Nome *</label>
            <input
              type="text"
              id="nome"
              name="nome"
              value="<?php echo htmlspecialchars($categoria['categoria_nome']); ?>"
              placeholder="Ex: Almoço no shopping"
              maxlength="50"
              required />
            <div class="info-text">
              <i class="fas fa-info-circle"></i>
              Nome da Categoria
            </div>
          </div>
        </div>

        <div class="btn-container">
          <a href="../dashboard.php" class="btn-cancelar">
            <i class="fas fa-times"></i> Cancelar
          </a>
          <button type="submit" class="btn-salvar">
            <i class="fas fa-save"></i> Salvar Alterações
          </button>
        </div>
      </form>
    </div>
  </div>
  <script src="../../assets/js/editar_despesas.js" defer></script>
</body>

</html>