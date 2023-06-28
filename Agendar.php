<?php
/*

usado para sempre verificar a sessão do usuário
require_once './session.php';

// Verificar se há uma sessão de usuário ou superusuário 
if (!(isset($_SESSION['usuario']) || isset($_SESSION['superusuario']))) { 
    // Redirecionar para a página de login 
    header("Location: login.html"); 
    exit;
}*/
//require_once './session.php';

// Definir a mensagem na variável de sessão
$_SESSION['login_message'] = 'Para cancelar um agendamento, por favor, faça login novamente';

?>
<!DOCTYPE html>
<html lang="PT-BR">

<head>
    <!-- Importante deixarmos a codificação dos caracteres e o título no início de <head> para otimização e procura da página -->
    <meta charset="UTF-8">
    <title>Agendar</title>
    
    <!-- meta tags -->
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="robots" content="index, nofollow">
    <meta name="googlebot" content="index, nofollow">
    <meta name="googlebot" content="notranslate">
    <meta name="theme-color" content="#FFFFFF">
    <meta name="description" content="Agendar biblioteca SENAI">
    <meta name="keywords" content="SENAI, Biblioteca, agendar">
    <meta name="apple-mobile-web-app-status-bar-style" content="black-translucent">
    <meta name="author" content="SENAI">

    <!-- link tags -->
    <link rel="stylesheet" href="./config/assets/estilos/agendar.css">
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
                    <li><a href="./cancelar.php">Cancelar</a></li>
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

    <div class="text-container">

        <div class="calendario">
            <table title="Horários">
                <tr>
                    <th>Dia útil</th>
                    <th>Início</th>
                    <th>Término</th>
                </tr>
                <tr>
                    <td>Segunda-feira</td>
                    <td>13:00</td>
                    <td>21:00</td>
                </tr>
                <tr>
                    <td>Terça-feira</td>
                    <td>08:00</td>
                    <td>17:00</td>
                </tr>
                <tr>
                    <td>Quarta-feira</td>
                    <td>13:00</td>
                    <td>21:00</td>
                </tr>
                <tr>
                    <td>Quinta-feira</td>
                    <td>13:00</td>
                    <td>21:00</td>
                </tr>
                <tr>
                    <td>Sexta-feira</td>
                    <td>08:00</td>
                    <td>17:00</td>
                </tr>
            </table>
        </div>

        <form method="POST">
            <h1>AGENDAMENTO</h1>
            <label for="instrutor">Instrutor</label>
            <input type="text" id="instrutor" name="instrutor" required><br><br>

            <label for="curso">Curso</label>
            <input type="text" id="curso" name="curso" required><br><br>

            <label for="data">Data</label>
            <input type="date" id="data" name="data" required><br><br>

            <label for="hora_inicio">Início</label>
            <input type="time" id="hora_inicio" name="hora_inicio" required><br><br>

            <label for="hora_termino">Término</label>
            <input type="time" id="hora_termino" name="hora_termino" required><br><br>

            <label for="quantidade_alunos">Quantidade de Alunos</label>
            <input type="number" id="quantidade_alunos" name="quantidade_alunos" required><br><br>

            <button type="submit" value="AGENDAR">AGENDAR</button>
        </form>



    </div>

    <?php
    // Tenta criar uma conexão com o banco de dados
    try {
        $pdo = new PDO('mysql:host=localhost;dbname=biblioteca', 'root', '');
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    } catch (PDOException $e) {
        echo 'Error: ' . $e->getMessage();
        die();
    }
    /* datas */

    function isWeekend($date)
    {
        $timestamp = strtotime($date);
        $weekday = date('N', $timestamp);
        return ($weekday == 6 || $weekday == 7);
    }

    function isHoliday($date)
    {
        $apiUrl = "https://api.calendario.com.br/?json=true&ano=" . date('Y') . "&estado=SP&cidade=Sao_Paulo&token=SEU_TOKEN";
        // Substitua SEU_TOKEN pelo token fornecido pela API Calendário Brasileiro

        $response = file_get_contents($apiUrl);
        $holidays = json_decode($response, true);

        return isset($holidays[$date]);
    }

    function isValidTime($dayOfWeek, $time)
    {
        $validTimes = [
            1 => ['13:00', '21:00'], // Segunda-feira
            2 => ['08:00', '17:00'], // Terça-feira
            3 => ['13:00', '21:00'], // Quarta-feira
            4 => ['13:00', '21:00'], // Quinta-feira
            5 => ['08:00', '17:00'], // Sexta-feira
        ];

        $start = $validTimes[$dayOfWeek][0];
        $end = $validTimes[$dayOfWeek][1];

        return ($time >= $start && $time <= $end);
    }

    // Restante do código...

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $data = $_POST['data'];
        $hora_inicio = $_POST['hora_inicio'];
        $nome = $_POST['instrutor'];
        $curso = $_POST['curso'];
        $hora_termino = $_POST['hora_termino'];
        $quantidade_alunos = $_POST['quantidade_alunos'];

        // Verificar se já existe um agendamento para essa data e hora no banco de dados
        $stmt = $pdo->prepare("
        SELECT COUNT(*) FROM agendamentos
        WHERE data = :data AND (
        (hora_inicio <= :hora_inicio AND hora_termino > :hora_inicio) OR
        (hora_inicio < :hora_termino AND hora_termino >= :hora_termino)
        )
        ");
        $stmt->bindValue(':data', $data);
        $stmt->bindValue(':hora_inicio', $hora_inicio);
        $stmt->bindValue(':hora_termino', $hora_termino);
        $stmt->execute();
        $count = $stmt->fetchColumn();
        if ($count > 0) {
            /*$_SESSION['error_message'] = "Já existe um agendamento neste horário!";*/
            // Exibir uma mensagem de erro se já existe um agendamento para essa data e hora
            echo "<div class='error-message' style='color: yellow; text-align: center; font-size:20px; font-weight:600;margin: 1rem;'>Já existe um agendamento neste horário!</div>";

            // Insere os dados na tabela "agendamentos"
        }

        if (isWeekend($data) || isHoliday($data)) {
            echo "<div class='error-message' style='color: red; text-align: center; font-size:20px; font-weight:600; margin: 1rem;'>Não é possível agendar nos sábados e domingos.</div>";
        } else {
            // Verificar se é um dia útil e horário válido
            $dayOfWeek = date('N', strtotime($data));
            if (isValidTime($dayOfWeek, $hora_inicio)) {
                // Insere os dados na tabela "agendamentos"
                $stmt = $pdo->prepare("INSERT INTO agendamentos (nome, curso, data, hora_inicio, hora_termino, quantidade_alunos) VALUES (:nome, :curso, :data, :hora_inicio, :hora_termino, :quantidade_alunos)");
                $stmt->bindValue(':nome', $nome);
                $stmt->bindValue(':curso', $curso);
                $stmt->bindValue(':data', $data);
                $stmt->bindValue(':hora_inicio', $hora_inicio);
                $stmt->bindValue(':hora_termino', $hora_termino);
                $stmt->bindValue(':quantidade_alunos', $quantidade_alunos);
                $stmt->execute();
                $cadastroId = $pdo->lastInsertId();
                // Exibe uma mensagem de sucesso
                //echo "<div class='success-message' style='text-align: center; color: green; font-size:20px; margin: 1rem;'>Agendamento realizado com sucesso!</div>";
                //session_destroy();
                echo "<div class='success-message' style='color: green; text-align: center; font-size:20px; font-weight:600; margin: 1rem;'>Agendamento realizado com sucesso!</div> <p style='color: black; text-align: center; font-size:20px; font-weight:600;'>ID do agendamento: " . $cadastroId . "</p>";

                /*echo "<script> alert('Agendado com sucesso!') </script>";*/
                // Agendamento válido, continuar com o código existente para inserir no banco de dados
                // ...
            } else {
                echo "<div class='error-message' style='color: orange; text-align: center; font-size:20px; font-weight:600; margin: 1rem;'>Não foi possível agendar.</div>";
            }
        }
    }

    ?>
    <script src="./config/assets/js/destruirSessao.js"></script>
    <!--<script src="./config/assets/js/default.js"></script>-->

</body>

<footer>
    <div class="rodape">
        <p>&copy; 2023 UAIBook. Todos os direitos reservados.</p>
        <p>Curso de Desenvolvimento de Sistemas, Uberaba/MG, SENAI</p>
    </div>
</footer>

</html>