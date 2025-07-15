<!DOCTYPE html>
<html lang="pt-BR">
<head>
  <meta charset="UTF-8">
  <title>Escolha de Gráfico com Chart.js</title>
  <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>
<body>

  <h2>Escolha o tipo de gráfico:</h2>
  <label><input type="radio" name="chartType" value="doughnut" checked> Rosquinha</label>
  <label><input type="radio" name="chartType" value="bar"> Barras</label>

  <div style="width: 500px; height: 500px;">
    <canvas id="myChart"></canvas>
  </div>

  <script src="./assets/js/graficos.js"></script>
</body>
</html>
