<!DOCTYPE html>
<html lang="PT-BR">

<head>
  <meta charset="UTF-8">
  <!--<link rel="icon" type="image/png" href="/img/joao.png">-->
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="./config/assets/estilos/login.css">
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Dancing+Script:wght@700&family=Fira+Sans:ital,wght@1,200&family=Montserrat:wght@200&family=Source+Sans+Pro&display=swap" rel="stylesheet">
  <title>Login</title>
</head>

<body>
  <div id="app">
    <form id="forms" action="./conexaologin.php" method="post" onsubmit="exibirAlerta(event)">
      <h1>LOGIN</h1>
      <label for="nome">E-mail / CPF / Usuário</label>
      <input type="text" maxlength="42" id="nome" name="usuario" required><br>

      <label for="curso">Senha</label>
      <input type="password" maxlength="30" id="senha" name="senha" required>

      <div class="button-container">
        <button type="submit" value="ACESSAR">ACESSAR</button>
        <button type="button" id="botao_limpar" onclick="limparFormulario()" class="limpar-button">LIMPAR
          FORMULÁRIO</button>
      </div>
    </form>
    <?php
    if (isset($_GET['error']) && $_GET['error'] == '1001') {
      echo "<div class='failed' style='text-align: center; margin-top:1%; font-size:20px; font-weight:1000;'>Login ou senha inválidos.</div>";
    }
    ?>

  </div>
  <script>
    function limparFormulario() {
      document.getElementById("forms").reset();

      // Limpar manualmente os campos de texto
      var dados = document.querySelectorAll('#forms input[type="text"]');
      for (var i = 0; i < dados.length; i++) {
        dados[i].value = '';
      }
    }
  </script>
  <script src="./config/assets/js/default.js"></script>
  <script src="./config/assets/js/destruirSessao.js"></script>
  <footer>
  <div class="rodape">
    <p>&copy; 2023 UAIBook. Todos os direitos reservados.</p>
    <p>Curso de Desenvolvimento de Sistemas, Uberaba/MG, SENAI</p>
  </div>
</footer>
</body>


</html>