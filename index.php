<!DOCTYPE html>
<html lang="pt-br">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Finanças que Salvam</title>
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;700&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="css/index.css">
  <script src="https://kit.fontawesome.com/a2d9d3f09f.js" crossorigin="anonymous"></script>
</head>

<body>
  <!-- HEADER -->
  <header class="header">
    <div class="container">
      <h1>Finanças que Salvam</h1>
      <div class="acoes-header">
        <a href="./login.php" class="link-entrar">Entrar</a>
        <a href="./cadastrarUsuForm.php" class="btn-azul">Comece Agora</a>
      </div>
    </div>
  </header>

  <!-- HERO -->
  <section class="hero" id="comecar">
    <div class="hero-content">
      <h2>Gaste com propósito. Economize com impacto.</h2>
      <h1>Finanças que Salvam</h1>
      <a href="./Login.php" class="btn-azul btn-hero">Quero mudar agora</a>
    </div>
  </section>

  <!-- DASHBOARD SIMPLES -->
  <section class="dashboard" id="dashboard">
   

   <main class="main-content">
  <h1>Olá, jovem consciente!</h1>
  <p>Veja como seus gastos afetam seu bolso e o planeta.</p>

  <div class="cards">
    <div class="card">
      <h3>Total gasto no mês</h3>
      <p class="valor">R$ 1.250,00</p>
    </div>
    <div class="card destaque">
      <h3>Impacto evitado</h3>
      <p class="valor">🌱 23 kg de CO₂</p>
      <small>Ao economizar em transporte e plástico</small>
    </div>
  </div>

<h2 style="margin-top: 40px; text-align: center;">Minhas Despesas</h2>



<!-- Botão verde abaixo da tabela -->
<div style="margin-top: 20px; display: flex; justify-content: center;">
  <a href="cadastrar.php" class="btn-verde">Cadastrar Despesas</a>
</div>


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
  <tr>
    <td>05/07/2025</td>
    <td>Alimentação</td>
    <td>Almoço caseiro (marmita)</td>
    <td>R$ 32,00</td>
    <td>
      <button class="btn-editar"><i class="fas fa-edit"></i></button>
      <button class="btn-excluir"><i class="fas fa-trash-alt"></i></button>
    </td>
  </tr>
  <tr>
    <td>07/07/2025</td>
    <td>Transporte</td>
    <td>Ônibus para o trabalho</td>
    <td>R$ 62,60</td>
    <td>
      <button class="btn-editar"><i class="fas fa-edit"></i></button>
      <button class="btn-excluir"><i class="fas fa-trash-alt"></i></button>
    </td>
  </tr>
  <tr>
    <td>10/07/2025</td>
    <td>Lazer</td>
    <td>Passeio no parque</td>
    <td>R$ 50,00</td>
    <td>
      <button class="btn-editar"><i class="fas fa-edit"></i></button>
      <button class="btn-excluir"><i class="fas fa-trash-alt"></i></button>
    </td>
  </tr>
</tbody>

  </table>
</main>

  </section>
</body>

</html>