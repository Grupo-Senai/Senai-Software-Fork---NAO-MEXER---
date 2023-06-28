<?php
//require_once './session.php';

// ! IMPORTANTE !
// ! IMPORTANTE !

// ABAIXO SEGUE O SCRIPT QUE SOMENTE AUTORIZA O ACESSO À PÁGINA SE O USUÁRIO FAZER LOGIN NOVAMENTE
// DEIXAR COMENTADO POR ENQUANTO

/*// Verificar se há uma sessão de usuário ou superusuário
if (!(isset($_SESSION['usuario']) || isset($_SESSION['superusuario']))) {
    // Se a sessão não estiver ativa, exibe uma mensagem de aviso com JavaScript
    echo "<script> alert('Você precisa fazer login novamente para acessar esta página.');
    window.location.href = 'login.html?skip_message=1';
    </script>";
    exit; // Termina a execução do script
} else {
    echo " window.location.href = 'cancelar.php?skip_message=1';";
  }
*/



// ! IMPORTANTE !
// ! IMPORTANTE !
?>
<!DOCTYPE html>
<html lang="PT-BR">

<head>
    <!-- Importante deixarmos a codificação dos caracteres e o título no início de <head> para otimização e procura da página -->
    <meta charset="UTF-8">
    <title>Cancelar</title>

    <!-- meta tags -->
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="robots" content="index, nofollow">
    <meta name="googlebot" content="index, nofollow">
    <meta name="googlebot" content="notranslate">
    <meta name="theme-color" content="#FFFFFF">
    <meta name="description" content="Cancelar biblioteca SENAI">
    <meta name="keywords" content="SENAI, Biblioteca, cancelar">
    <meta name="apple-mobile-web-app-status-bar-style" content="black-translucent">
    <meta name="author" content="SENAI">

    <!-- link tags -->
    <link rel="stylesheet" href="./config/assets/estilos/cancelar.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Dancing+Script:wght@700&family=Fira+Sans:ital,wght@1,200&family=Montserrat:wght@200&family=Source+Sans+Pro&display=swap" rel="stylesheet">
    <link rel="icon" href="./config/assets/img/linguicao.ico" type="image/x-icon">
</head>

<body>
    <header>
        <nav class="navbar">
            <div class="navbar-container">
                <a href="./menu.php">
                    <img src="./config/assets/img/senailogo1.png" class="logo">
                </a>
                <ul class="navbar-menu">
                    <li><a href="./agendar.php">Agendar</a></li>
                    <li><a href="./cancelamentos.php">Cancelamentos</a></li>
                    <li><a href="./Agendamentos.php">Agendamentos</a></li>
                    <li><a href="./menu.php">Menu</a></li>
                    <li><a href="./logout.php">Sair</a></li>
                </ul>
            </div>
            <!-- <div class="navbar-toggle">
        <span class="navbar-toggle-icon"></span>
      </div>
      </div>-->
        </nav>
    </header>
    <a href="./logout.php" class="sair">Sair</a>

    <div id="app">
        <form method="post" onsubmit="exibirAlerta(event)">
            <h1>CANCELAMENTO</h1>
            <label for="nome">Nome</label>
            <input type="text" id="nome" name="nome" required placeholder="Digite seu nome"><br><br>

            <label for="id">ID</label>
            <input type="text" id="id" name="id" required placeholder="Digite seu ID"><br><br>


            <label for="motivo">Motivo</label>
            <select id="motivo" name="motivo" required>
                <option value="" disabled selected hidden>Escolha uma das opções abaixo</option>
                <option value="Condições climáticas extremas.">Condições climáticas extremas.</option>
                <option value="Tortuguita">Foi oferecido tortuguitas.</option>
                <option value="Emergências e/ou urgências médicas.">Emergências e/ou urgências médicas.</option>
                <option value="Estruturais (elétrica, internet, hidráulica, etc.).">Estruturais (elétrica, internet, hidráulica, etc.).</option>
                <option value="Feriados ou eventos não previstos anteriormente.">Feriados ou eventos não previstos anteriormente.</option>
                <option value="Mudança de plano de aula">Mudança de plano de aula</option>
                <option value="Nenhuma das opções">Nenhuma das opções</option>
            </select>

            <strong>Estou ciente de que ao cancelar meu agendamento, estarei disponibilizando a data/horário para outros
                professores.<br>Eu concordo e estou ciente.</strong>
            <span><input type="checkbox" id="concordo" name="concordo" required></span>


            <button type="submit" value="CANCELAR">CANCELAR</button>
            <button type="button" onclick="limparFormulario()">LIMPAR</button>


        </form>

        <script src="./config/assets/js/destruirSessao.js"></script>
        <script>
            function limparFormulario() {
                document.getElementById("nome").value = "";
                document.getElementById("id").value = "";
                document.getElementById("motivo").value = "";
                document.getElementById("concordo").checked = false;
            }
        </script>
        <?php
        // Tenta criar uma conexão com o banco de dados
        try {
            $pdo = new PDO('mysql:host=localhost;dbname=biblioteca', 'root', '');
            $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (PDOException $e) {
            echo 'Error: ' . $e->getMessage();
            die();
        }
        // Se o formulário foi enviado
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // Obtém os dados do formulário
            $nome = $_POST['nome'];
            $id = $_POST['id'];
            $motivo = $_POST['motivo'];
            // Insere os dados na tabela "cancelamentos"
            $stmt = $pdo->prepare("INSERT INTO cancelamentos (id, nome, motivo) VALUES (:id, :nome, :motivo)");
            $stmt->bindValue(':id', $id);
            $stmt->bindValue(':nome', $nome);
            $stmt->bindValue(':motivo', $motivo);
            $stmt->execute();
            // Exibe uma mensagem de sucesso
            echo "<div class='success-message' style='text-align: center; color:green; font-size:20px; margin: 1rem;'>Cancelamento realizado com sucesso!</div>";
            //session_destroy();
        }
        ?>
        <div class="content-wrapper">

        </div>
        <script src="./config/assets/js/destruirSessao.js"></script>
        <script src="./config/assets/js/default.js"></script>
        <footer>
            <div class="rodape">
                <strong>&copy; 2023 UAIBook. Todos os direitos reservados.</strong>
                <strong>Curso de Desenvolvimento em Sistemas. Trilhas do Futuro II. SENAI. Uberaba/MG.</strong>
            </div>
        </footer>


    </div>

</body>

</html>