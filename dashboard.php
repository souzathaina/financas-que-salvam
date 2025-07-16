<?php
session_start();
include './Connection.php';
include './includes/usuarioDashboard.php';
?>

<!DOCTYPE html>
<html lang="pt-br">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Dashboard - Finanças que Salvam</title>
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;700&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="assets/css/index.css">
  <link rel="stylesheet" href="assets/css/dashboard.css">
  <link rel="stylesheet" href="assets/css/tables.css">
  <link rel="stylesheet" href="assets/css/charts.css">
  <link rel="stylesheet" href="assets/css/buttons.css">
  <link rel="stylesheet" href="assets/css/alerts.css">
  <link rel="stylesheet" href="assets/css/utilities.css">

  <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

</head>

<body>
  <!-- HEADER -->
  <header class="header">
    <div class="container">
      <h1>Finanças que Salvam</h1>
      <div class="acoes-header">
        <span>Olá, <?php echo htmlspecialchars($usuario['nome']); ?>!</span>
        <a href="logout.php" class="link-entrar">Sair</a>
      </div>
    </div>
  </header>

  <div class="dashboard-container">
    <?php if (isset($_GET['sucesso']) && $_GET['sucesso'] == '1' && isset($_GET['msg']) && $_GET['msg'] == 'salario_atualizado'): ?>
      <div class="alert alert-success">
        <i class="fas fa-check-circle"></i> Salário atualizado com sucesso!
      </div>
    <?php endif; ?>
    
    <?php if (isset($_GET['sucesso']) && $_GET['sucesso'] == '1' && isset($_GET['msg']) && $_GET['msg'] == 'despesa_atualizada'): ?>
      <div class="alert alert-success">
        <i class="fas fa-check-circle"></i> Despesa atualizada com sucesso!
      </div>
    <?php endif; ?>
    
    <?php if (isset($_GET['sucesso']) && $_GET['sucesso'] == '1' && isset($_GET['msg']) && $_GET['msg'] == 'perfil_atualizado'): ?>
      <div class="alert alert-success">
        <i class="fas fa-check-circle"></i> Perfil atualizado com sucesso!
      </div>
    <?php endif; ?>
    
    <!-- SEÇÃO DE BOAS-VINDAS -->
    <section class="welcome-section">
      <h1>Bem-vindo ao seu Dashboard Financeiro!</h1>
      <p>Monitore seus gastos e mantenha suas finanças em ordem.</p>
    </section>

    <!-- AÇÕES RÁPIDAS -->
    <section class="acoes-dashboard">
      <a href="cadastrar_despesas.php" class="btn-dashboard verde">
        <i class="fas fa-plus"></i>
        Nova Despesa
      </a>
      <a href="cadastrar_categoria.php" class="btn-dashboard">
        <i class="fas fa-tags"></i>
        Nova Categoria
      </a>
      <a href="configurar_salario.php" class="btn-dashboard">
        <i class="fas fa-cog"></i>
        Configurar Salário
      </a>
      <a href="editar_usuario.php" class="btn-dashboard">
        <i class="fas fa-user-edit"></i>
        Editar Perfil
      </a>
    </section>

    <!-- ESTATÍSTICAS RÁPIDAS -->
    <section class="stats-grid">
      <div class="stat-card">
        <h4>Total Gasto</h4>
        <div class="stat-value">R$ <?php echo number_format($totalGasto, 2, ',', '.'); ?></div>
      </div>
      
      <div class="stat-card">
        <h4>Salário Mensal</h4>
        <div class="stat-value">R$ <?php echo number_format($salario, 2, ',', '.'); ?></div>
      </div>
      
      <div class="stat-card <?php echo $percentualGasto > 80 ? 'alerta' : 'destaque'; ?>">
        <h4>Percentual Gasto</h4>
        <div class="stat-value"><?php echo number_format($percentualGasto, 1); ?>%</div>
      </div>
      
      <div class="stat-card destaque">
        <h4>Saldo Restante</h4>
        <div class="stat-value">R$ <?php echo number_format($saldoRestante, 2, ',', '.'); ?></div>
      </div>
    </section>

    <!-- GRÁFICOS -->
    <section class="graficos-section">
      <div class="grafico-card">
        <h3>Despesas por Categoria</h3>
        <div class="chart-container">
          <canvas id="myChart"></canvas>
        </div>
        <div class="chart-controls">
          <label><input type="radio" name="chartType" value="doughnut" checked> Rosquinha</label>
          <label><input type="radio" name="chartType" value="bar"> Barra</label>
          <label><input type="radio" name="chartType" value="pie"> Pizza</label>
        </div>
      </div>
      
      <div class="grafico-card">
        <h3>Gastos vs Salário</h3>
        <!-- Formulário de filtro para o gráfico Gastos vs Salário -->
        <form id="form-grafico-pesquisa" class="form-filtro-grafico">
          <div class="form-group">
            <label for="mes-pesquisa">Mês:</label>
            <select name="mes" id="mes-pesquisa" class="input-filtro">
              <option value="">Todos</option>
              <?php
                $meses = [
                  '01' => 'Janeiro', '02' => 'Fevereiro', '03' => 'Março', '04' => 'Abril',
                  '05' => 'Maio', '06' => 'Junho', '07' => 'Julho', '08' => 'Agosto',
                  '09' => 'Setembro', '10' => 'Outubro', '11' => 'Novembro', '12' => 'Dezembro'
                ];
                foreach ($meses as $num => $nome): ?>
                  <option value="<?= $num ?>"><?= $nome ?></option>
              <?php endforeach; ?>
            </select>
          </div>
          <div class="form-group">
            <label for="ano-pesquisa">Ano:</label>
            <select name="ano" id="ano-pesquisa" class="input-filtro">
              <option value="">Todos</option>
              <?php $anoAtual = date('Y');
                for ($i = $anoAtual; $i >= $anoAtual - 5; $i--): ?>
                  <option value="<?= $i ?>"><?= $i ?></option>
              <?php endfor; ?>
            </select>
          </div>
          <div class="form-group">
            <label for="categoria-pesquisa">Categoria:</label>
            <select name="categoria_id" id="categoria-pesquisa" class="input-filtro">
              <option value="">Todas</option>
              <?php
                // Buscar categorias para o filtro
                try {
                  $sqlCat = 'SELECT id, nome FROM categorias ORDER BY nome';
                  $stmtCat = $pdo->prepare($sqlCat);
                  $stmtCat->execute();
                  $categoriasFiltro = $stmtCat->fetchAll(PDO::FETCH_ASSOC);
                } catch (PDOException $e) {
                  $categoriasFiltro = [];
                }
                foreach ($categoriasFiltro as $categoria): ?>
                  <option value="<?= htmlspecialchars($categoria['id']) ?>"><?= htmlspecialchars($categoria['nome']) ?></option>
              <?php endforeach; ?>
            </select>
          </div>
          <button type="submit" class="btn-dashboard azul">Filtrar</button>
        </form>
        <div class="chart-container">
          <canvas id="chartPesquisa"></canvas>
        </div>
      </div>
    </section>

    <!-- TABELA DE DESPESAS -->
    <section>
      <h2 style="margin-bottom: 20px; color: #1E90FF;">Minhas Despesas Recentes</h2>
      
      <?php if (empty($despesas)): ?>
        <div style="text-align: center; padding: 40px; background: white; border-radius: 12px; box-shadow: 0 2px 8px rgba(0,0,0,0.1);">
          <i class="fas fa-receipt" style="font-size: 3rem; color: #ccc; margin-bottom: 20px;"></i>
          <h3 style="color: #666; margin-bottom: 10px;">Nenhuma despesa registrada</h3>
          <p style="color: #999;">Comece registrando sua primeira despesa!</p>
          <a href="cadastrar_despesas.php" class="btn-verde" style="margin-top: 20px;">Cadastrar Primeira Despesa</a>
        </div>
      <?php else: ?>
        <div class="tabela-container">
          <table class="tabela-despesas">
            <thead>
            <tr>
              <th>Data</th>
              <th>Categoria</th>
              <th>Descrição</th>
              <th>Valor</th>
              <th>Ações</th>
            </tr>
          </thead>
          <tbody>
            <?php foreach ($despesas as $despesa): ?>
            <tr>
              <td><?php echo date('d/m/Y', strtotime($despesa['data'])); ?></td>
              <td><?php echo htmlspecialchars($despesa['categoria_nome'] ?? 'Sem categoria'); ?></td>
              <td><?php echo htmlspecialchars($despesa['descricao']); ?></td>
              <td>R$ <?php echo number_format($despesa['valor'], 2, ',', '.'); ?></td>
              <td>
                <button class="btn-editar" onclick="editarDespesa(<?php echo $despesa['id']; ?>)">
                  <i class="fas fa-edit"></i>
                </button>
                <button class="btn-excluir" onclick="deletarDespesa(<?php echo $despesa['id']; ?>)">
                  <i class="fas fa-trash-alt"></i>
                </button>
              </td>
            </tr>
            <?php endforeach; ?>
                      </tbody>
          </table>
        </div>
        <?php endif; ?>
    </section>
  </div>

  <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
  <script>
    // Dados vindos do PHP
    window.graficoCategorias = {
      labels: <?php echo json_encode(array_column($categorias, 'nome')); ?>,
      data: <?php echo json_encode(array_map('floatval', array_column($categorias, 'total_categoria'))); ?>
    };
  </script>
  <script src="assets/js/graficos.js"></script>
  <script src="assets/js/graficoPesquisa.js" defer></script>
  <script src="assets/js/dashboard.js" defer></script>
</body>

</html>
