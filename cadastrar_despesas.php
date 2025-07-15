<?php
session_start();
include './Connection.php';

$sql = 'SELECT * FROM categorias';
$stmt = $pdo->prepare($sql);
$stmt->execute();
$categoria = $stmt->fetch(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="pt-br">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Cadastrar Despesas</title>
  <link rel="stylesheet" href="index.css" />
  <link rel="stylesheet" href="assets/cadastrar_despesas.css" />
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;700&display=swap" rel="stylesheet">
</head>

<body>

  <header class="header">
    <div class="container">
      <h1>Finanças que Salvam</h1>
      <div class="acoes-header">
        <a href="index.html" class="link-entrar">Voltar</a>
      </div>
    </div>
  </header>

  <section class="dashboard centro">
    <main class="main-content formulario-wrapper">
      <h1 class="titulo-cadastro">Cadastrar Nova Despesa</h1>

        <form action="./NovaDespesa.php" method="post" class="formulario-despesa">

          <div class="campo-formulario">
            <label for="valor">Valor (R$)</label>
            <input type="number" id="valor" name="valor" placeholder="Ex: 75.50" required />
          </div>

          <div class="campo-formulario">
            <label for="categoria">Categoria</label>
            <select id="categoria" name="categoria" required>
              <option value="">Selecione</option>

              <?php
              $stmt->execute();
              $categorias = $stmt->fetchAll(PDO::FETCH_ASSOC);
              
              foreach ($categorias as $categoria) {
                echo '<option value="' . $categoria['id'] . '">' . $categoria['nome'] . '</option>';
              }
              ?>

            </select>
          </div>

          <div class="campo-formulario">
            <label for="data">Data</label>
            <input type="date" id="data" name="data" required />
          </div>

          <div class="campo-formulario">
            <label for="descricao">Descrição</label>
            <textarea id="descricao" name="descricao" rows="3" placeholder="Descreva sua despesa..." required></textarea>
          </div>

          <div class="btn-container">
            <button type="submit" class="btn-verde">Cadastrar Despesa</button>
          </div>

        </form>

    </main>
  </section>

</body>

</html>