<?php
/*
usado para sempre verificar a sessão do usuário
require_once './session.php';

// Verificar se há uma sessão de usuário ou superusuário 
if (!(isset($_SESSION['usuario']) || isset($_SESSION['superusuario']))) { 
    // Redirecionar para a página de login 
    header("Location: login.html"); 
    exit;
}
*/
?>
<!DOCTYPE html>
<html>

<head>
    <!-- Importante deixarmos a codificação dos caracteres e o título no início de <head> para otimização e procura da página -->
    <meta charset="UTF-8">
    <title>Lista de Agendamentos</title>

    <!-- meta tags -->
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="robots" content="index, nofollow">
    <meta name="googlebot" content="index, nofollow">
    <meta name="googlebot" content="notranslate">
    <meta name="theme-color" content="#FFFFFF">
    <meta name="description" content="Consulta biblioteca SENAI">
    <meta name="keywords" content="SENAI, Biblioteca, agendamentos">
    <meta name="apple-mobile-web-app-status-bar-style" content="black-translucent">
    <meta name="author" content="SENAI">
    
    <!-- link tags -->
    <link rel="stylesheet" href="./config/assets/estilos/consulta.css">
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
                    <li><a href="./cancelar.php">Cancelar</a></li>
                    <li><a href="./cancelamentos.php">Cancelamentos</a></li>
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
            <h1>Lista de Agendamentos</h1>
            <?php
            // Definir as informações de conexão
            $host = 'localhost';
            $banco = 'biblioteca';
            $usuario = 'root';
            $senha = '';

            // Conectar ao banco de dados usando mysqli

            try {
                $pdo = new PDO("mysql:host=$host;dbname=$banco;charset=utf8mb4", $usuario, $senha);
                $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            } catch (PDOException $e) {
                echo "Conexão falhou" . $e->getMessage();
            }

            // buscar agendamentos no banco de dados
            $query = "SELECT * FROM agendamentos";
            $busca = $pdo->query($query);
            $agendamentos = $busca->fetchAll(PDO::FETCH_ASSOC);
            ?>
            <table>
                <thead>
                    <tr>

                        <th>Nome</th>
                        <th>Data</th>
                        <th>Hora de Início</th>
                        <th>Hora de Término</th>
                        <th>Quantidade de Alunos</th>
                        <th>Curso</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($agendamentos as $agendamento) : ?>
                        <tr>
                            <td><?php echo $agendamento['nome']; ?></td>
                            <td><?php echo $agendamento['data']; ?></td>
                            <td><?php echo $agendamento['hora_inicio']; ?></td>
                            <td><?php echo $agendamento['hora_termino']; ?></td>
                            <td><?php echo $agendamento['quantidade_alunos']; ?></td>
                            <td><?php echo $agendamento['curso']; ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
    </div>
    <script src="./config/assets/js/destruirSessao.js"></script>
    <footer>
        <div class="rodape">
            <p>&copy;2023 UAIBook. Todos os direitos reservados.</p>
            <p>Curso de Desenvolvimento em Sistemas.Trilhas do Futuro II. SENAI. Uberaba/MG.</p>
        </div>
    </footer>
</body>


</html>