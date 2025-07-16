<?php
session_start();
include './includes/Connection.php';
include './includes/usuarioDashboard.php';

try {
  $sqlDespesas = '
    SELECT d.valor as valor_despesa, c.nome AS nome_categoria
    FROM despesas AS d
    LEFT JOIN categorias AS c ON c.id = d.categoria_id
    WHERE d.usuario_id = :usuario_id
      AND MONTH(d.data) = MONTH(CURDATE())
      AND YEAR(d.data) = YEAR(CURDATE())
    ORDER BY d.valor DESC
    LIMIT 1;';

  $stmtDespesas = $pdo->prepare($sqlDespesas);
  $stmtDespesas->execute([
    ':usuario_id' => $_SESSION['usuario_id']
  ]);
  $despesa = $stmtDespesas->fetch(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
  echo "<script> alert('Erro ao carregar despesas: $e')</script>";
}

try {
  $sqlsalario = 'SELECT salario FROM usuarios WHERE usuario_id = :usuario_id';
  $stmtsalario = $pdo->prepare($sqlsalario);
  $stmtsalario->execute([
    ':usuario_id' => $_SESSION['usuario_id']
  ]);
  $dadosSalario  = $stmtsalario->fetch(PDO::FETCH_ASSOC);
  $salario = $dadosSalario['salario'];
} catch (PDOException $e) {
  echo "<script> alert('Erro ao carregar salario: $e')</script>";
}
$categoriaMaisGasta = $despesa['nome_categoria'] ?? '';
$valorCategoriaMaisGasta = $despesa['valor_despesa'] ?? '';

function sugestaoAlternativa($valor)
{
  if ($valor >= 5000) return "uma moto usada 🛵";
  if ($valor >= 2500) return "uma TV 4K de 55\" 📺";
  if ($valor >= 1000) return "um celular intermediário 📱";
  if ($valor >= 500) return "uma assinatura anual de streaming, internet e academia 💪";
  if ($valor >= 200) return "um final de semana em um hotel fazenda 🏕️";
  if ($valor >= 100) return "vários jantares em restaurante 🍽️";
  if ($valor >= 50) return "um bom livro e um jantar 🍷📖";
  return "vários lanches no iFood 🍔";
}

$itemSugestao = sugestaoAlternativa($valorCategoriaMaisGasta);

$mensagens = [
  'Alimentação' => [
    "Você gastou bastante com alimentação! Que tal preparar mais refeições em casa? É mais barato e saudável.",
    "Com esse valor em comida, dava para comprar $itemSugestao!",
    "Já pensou em trocar delivery por marmita caseira? Economia garantida!"
  ],
  'Transporte' => [
    "Economizando com transporte, você ajuda o meio ambiente e ainda sobra mais no bolso!",
    "Dava pra comprar $itemSugestao... Que tal experimentar transporte público ou bicicleta?",
    "Esse valor gasto com transporte poderia virar uma viagem de fim de semana se fosse economizado!"
  ],
  'Lazer' => [
    "Com o que você gastou em lazer, dava pra comprar $itemSugestao!",
    "Aproveitar é bom, mas que tal um lazer mais em conta? Um piquenique no parque talvez!",
    "Muito bem! Mas lembre-se: equilibrar diversão e economia é o segredo."
  ],
  'Moradia' => [
    "Você pode reduzir os custos de moradia com pequenas atitudes: economizar luz, água e gás faz diferença!",
    "Já pensou em revisar contratos ou renegociar tarifas? Pode reduzir bastante sua conta!",
    "Com esse valor, dava pra comprar $itemSugestao!"
  ],
  'Educação' => [
    "Investir em educação é ótimo! Mas sempre vale comparar preços entre cursos e plataformas gratuitas.",
    "Esse gasto com educação pode ser valioso se for bem direcionado. Planeje bem!",
    "Já tentou bolsas ou cursos gratuitos online? Pode aprender muito sem gastar tanto."
  ],
  'Saúde' => [
    "Saúde é essencial, mas pesquisar opções com melhor custo-benefício é fundamental!",
    "Esse valor daria para comprar $itemSugestao!",
    "Considere clínicas populares ou programas do governo para serviços mais acessíveis."
  ],
  'Outros' => [
    "Tente priorizar gastos que tragam retorno a longo prazo. Os 'outros' podem estar te sabotando.",
    "Gastos diversos merecem atenção. Está tudo mesmo valendo o que custou?",
    "Organize os 'outros' gastos para entender onde está o furo no orçamento."
  ],
  'Contas' => [
    "Contas são necessárias, mas será que não dá pra renegociar ou economizar em alguma?",
    "Revise suas contas fixas. Pequenas economias mensais viram grandes valores no ano.",
    "As contas pagam o funcionamento da vida, mas cuide para não viver só pra pagá-las."
  ],
  'Vestuário' => [
    "Estar na moda é legal, mas seu bolso precisa acompanhar o estilo também! 👗💸",
    "Será que precisava mesmo daquela 5ª camiseta preta? Reflita antes da próxima compra! 🛍️",
    "Investir em roupas é válido, mas um guarda-roupa inteligente vale mais que um lotado. 👚🧠"
  ]
];

$mensagemImpacto = '';

if ($valorCategoriaMaisGasta > $salario / 2) {
  if (array_key_exists($categoriaMaisGasta, $mensagens)) {
    $mensagemImpacto = $mensagens[$categoriaMaisGasta][array_rand($mensagens[$categoriaMaisGasta])];
  }
}
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
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" integrity="sha512-Zz6s+dFJh+1Ep9J3JYrDgAGru+gN0TADxVONMdx7FMXwqGlBSx1cAOUvfdPhcRQZaDP47JjeetPjqDnL7Axgdg==" crossorigin="anonymous" referrerpolicy="no-referrer" />
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
      <br>
      <?php if (!empty($categoriaMaisGasta)): ?>
        <p>Você mais gastou esse mes em: <?php echo $categoriaMaisGasta ?>! </p>
        <h1> <?php echo $mensagemImpacto ?> </h1>
      <?php endif; ?>
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
                '01' => 'Janeiro',
                '02' => 'Fevereiro',
                '03' => 'Março',
                '04' => 'Abril',
                '05' => 'Maio',
                '06' => 'Junho',
                '07' => 'Julho',
                '08' => 'Agosto',
                '09' => 'Setembro',
                '10' => 'Outubro',
                '11' => 'Novembro',
                '12' => 'Dezembro'
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