<?php
session_start();
include '../../includes/Connection.php';

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

      <form action="../../scripts/Update/EditarDespesa.php" method="POST" id="editarForm">
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
              type="text" 
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
  <script src="assets/js/editar_despesas.js" defer></script>
</body>

</html> 