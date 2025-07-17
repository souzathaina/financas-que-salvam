<?php
session_start();
include '../../includes/Connection.php';

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
  <link rel="stylesheet" href="../../assets/css/index.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
  <link rel="stylesheet" href="../../assets/css/forms.css">
  <link rel="stylesheet" href="../../assets/css/buttons.css">
  <link rel="stylesheet" href="../../assets/css/alerts.css">
  <link rel="stylesheet" href="../../assets/css/utilities.css">
  <link rel="stylesheet" href="../../assets/css/cadastrar_categoria.css">
  <link rel="stylesheet" href="../../assets/css/tables.css">
</head>

<body>
  <a href="../dashboard.php" class="voltar-link">
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
        <?php elseif ($_GET['sucesso'] == '2'): ?>
          <div class="alert sucesso">
            <i class="fas fa-check-circle"></i> Categoria deletada com sucesso!
          </div>
        <?php endif; ?>
      <?php else: ?>
        <?php if (empty($erro)): ?>
          <div class="alert erro" style="display: none;">
            <i class="fas fa-exclamation-triangle"></i>
          <?php else: ?>
            <div class="alert erro">
              <i class="fas fa-exclamation-triangle"></i>
            <?php endif; ?>
            <?php
            $erro = '';
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

          <form action="../../scripts/Insert/NovaCategoria.php" method="POST" id="categoriaForm">
            <div class="form-group">
              <label for="nome">Nome da Categoria *</label>
              <input
                type="text"
                id="nome"
                name="nome"
                placeholder="Ex: Alimentação, Transporte, Lazer..."
                maxlength="50"
                required />
              <div class="info-text">
                <i class="fas fa-info-circle"></i>
                Digite um nome descritivo para a categoria
              </div>
            </div>

            <div class="btn-container">
              <a href="../dashboard.php" class="btn-cancelar">
                <i class="fas fa-times"></i> Cancelar
              </a>
              <button type="submit" class="btn-cadastrar">
                <i class="fas fa-save"></i> Criar Categoria
              </button>
            </div>
          </form>

          <div class="categorias-existentes">
            <h3><i class="fas fa-list"></i> Categorias Existentes</h3>

            <?php if (empty($categorias)): ?>
              <p>Nenhuma categoria criada</p>
            <?php else: ?>
              <table class="tabela-categorias" border="1" cellpadding="8" cellspacing="0">
                <thead>
                  <tr>
                    <th>Nome da Categoria</th>
                    <th>Ações</th>
                  </tr>
                </thead>
                <tbody>
                  <?php foreach ($categorias as $categoria): ?>
                    <tr>
                      <td><?php echo htmlspecialchars($categoria['nome']); ?></td>
                      <td>
                        <button class="btn-editar" onclick="editarCategoria(<?php echo htmlspecialchars($categoria['id']); ?>)">
                          <i class="fas fa-edit"></i>
                        </button>
                        <button class="btn-excluir" onclick="deletarCategoria(<?php echo htmlspecialchars($categoria['id']); ?>)">
                          <i class="fas fa-trash-alt"></i>
                        </button>
                      </td>
                    </tr>
                  <?php endforeach; ?>
                </tbody>
              </table>
            <?php endif; ?>
          </div>
          </div>
    </div>

    <script src="../../assets/js/dashboard.js"></script>
</body>

</html>