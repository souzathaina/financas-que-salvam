<!DOCTYPE html>
<html lang="pt-br">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Finanças que Salvam</title>
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;700&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="./assets/css/index.css">
  <link rel="stylesheet" href="./assets/css/tables.css">
  <link rel="stylesheet" href="./assets/css/alerts.css">
  <link rel="stylesheet" href="./assets/css/utilities.css">
  <link rel="stylesheet" href="./assets/css/footer.css">
  <link rel="stylesheet" href="./assets/css/sobre.css">
  <link rel="stylesheet" href="./assets/css/header.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
</head>

<body>

  <header class="header">
    <div class="container">
      <h1><a href="./index.php" style="color: inherit; text-decoration: none;">Finanças que Salvam</a></h1>
      <div class="acoes-header">
        <a href="./pages/Usuarios/Login.php" class="link-entrar">Entrar</a>
        <a href="./pages/Usuarios/cadastrar.php" class="btn-azul">Comece Agora</a>
      </div>
    </div>
  </header>

  <?php if (isset($_GET['msg']) && $_GET['msg'] == 'logout_sucesso'): ?>
    <div class="alert alert-success" style="margin: 20px;">
      <i class="fas fa-check-circle"></i> Logout realizado com sucesso!
    </div>
  <?php endif; ?>

  <!-- HERO -->
  <section class="hero" id="comecar">
    <div class="hero-content">
      <h2>Gaste com propósito. Economize com impacto.</h2>
      <h1>Finanças que Salvam</h1>
      <a href="#dashboard" class="btn-azul btn-hero">Quero mudar agora</a>
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
        <a href="./pages/Despesas/cadastrar_despesas.php" class="btn-verde">Cadastrar Despesas</a>
      </div>


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
      </div>
  </section>

  <!-- SEÇÃO SOBRE -->

  <section id="sobre" class="secao-sobre animar">
    <h2 id="titulo-principal" class="animar">👥 Quem Somos</h2>
    <p id="texto-principal" class="animar">
      Somos uma equipe apaixonada por transformar vidas por meio da <strong>educação financeira</strong>. Acreditamos
      que o conhecimento empodera e pode tirar pessoas do ciclo da dívida, oferecendo novas possibilidades para o
      futuro.
    </p>

    <div class="carrossel" id="carrossel">
      <div class="slides" id="slides">
        <img src="assets/img/carrossel1.png" alt="Equipe de educadores financeiros">
        <img src="assets/img/carrossel2.png" alt="Oficinas de educação financeira">
        <img src="assets/img/carrossel3.png" alt="Símbolo de crescimento e esperança">
      </div>
    </div>

    <div class="botoes" id="botoes">
      <button onclick="mudarSlide(0)" class="ativo" aria-label="Slide 1"></button>
      <button onclick="mudarSlide(1)" aria-label="Slide 2"></button>
      <button onclick="mudarSlide(2)" aria-label="Slide 3"></button>
    </div>
  </section>
  <?php include 'includes/footer.php'; ?>
  </main>
  <script src="assets/js/carrossel.js"></script>
</body>

</html>