<?php
/*
require_once './session.php';

// Verificar se há uma sessão de usuário ou superusuário 
if (!(isset($_SESSION['usuario']) || isset($_SESSION['superusuario']))) { 
    // Redirecionar para a página de login 
    header("Location: login.html"); 
    exit;
}
*/
session_start();
$host = '127.0.0.1';
$dbname = 'biblioteca';
$username = 'root';
$password = '';
/*
echo '<script src="https://apis.google.com/js/api.js"></script>';
echo '<script src="./config/assets/js/gmail api.js"></script>';
echo '<script src="./config/assets/js/destruirSessao.js"></script>';
*/

// Conectar ao banco de dados usando PDO
try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8mb4", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    /*Resto do seu código...
    $loginAdmin = "admin"; // Substitua pelo valor desejado
    $senhaAdmin = 'fixfixfix'; // Substitua 'senha_admin' pela senha do superusuário

    // Gerar o hash da senha
    $senhaHash = password_hash($senhaAdmin, PASSWORD_DEFAULT);

    // Armazenar o hash da senha no banco de dados
    $stmt = $pdo->prepare("INSERT INTO superusuario (login, senha) VALUES (:login, :senha)");
    $stmt->bindValue(':login', $loginAdmin);
    $stmt->bindValue(':senha', $senhaHash);
    $stmt->execute();
*/

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $nome = $_POST['nome'];
        $codigo = $_POST['codigo'];
        $email = $_POST['email'];
        $senha = $_POST['senha'];
        $csenha = $_POST['confirmarsenha'];

        // Verificar se as informações já existem nas tabelas users e superusuario
        $stmt = $pdo->prepare("SELECT * FROM users WHERE codigo = :codigo OR email = :email");
        $stmt->bindValue(':codigo', $codigo);
        $stmt->bindValue(':email', $email);
        $stmt->execute();
        $users = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $stmt = $pdo->prepare("SELECT * FROM superusuario WHERE codigo = :codigo OR email = :email");
        $stmt->bindValue(':codigo', $codigo);
        $stmt->bindValue(':email', $email);
        $stmt->execute();
        $superusuario = $stmt->fetchAll(PDO::FETCH_ASSOC);

        // Verificar se as informações já existem em alguma tabela
        if (!empty($users) || !empty($superusuario)) {
            foreach ($users as $user) {
                if (strtolower($user['codigo']) == strtolower($codigo)) {
                    echo "<p>O código de acesso já está em uso. Por favor, insira um código diferente.</p>";
                    return;
                }
                if (strtolower($user['email']) == strtolower($email)) {
                    echo "<p>O email já está em uso. Por favor, insira um email diferente.</p>";
                    return;
                }
            }

            foreach ($superusuario as $su) {
                if (strtolower($su['codigo']) == strtolower($codigo)) {
                    echo "<p>O código de acesso já está em uso. Por favor, insira um código diferente.</p>";
                    header('Location : cadastro.html');
                    return;
                }
                if (strtolower($su['email']) == strtolower($email)) {
                    echo "<p>O email já está em uso. Por favor, insira um email diferente.</p>";
                    header('Location : cadastro.html');
                    return;
                }
            }
        } else {
            // Inserir informações na tabela cadastro
            $stmt = $pdo->prepare("INSERT INTO cadastro (nome, codigo, email, senha, checksenha) VALUES (:nome, :codigo, :email, :senha, :csenha)");
            $stmt->execute([
                ':nome' => $nome,
                ':codigo' => $codigo,
                ':email' => $email,
                ':senha' => $senha,
                ':csenha' => $csenha
            ]);

            // Obter o ID do último registro inserido na tabela cadastro
            $cadastroId = $pdo->lastInsertId();

            // Inserir informações na tabela users com a foreign key para a tabela cadastro
            $stmt = $pdo->prepare("INSERT INTO users (id, login, codigo, email, senha) VALUES (:cadastroId, :login, :codigo, :email, :senha)");
            $stmt->execute([
                ':cadastroId' => $cadastroId,
                ':login' => $nome,
                ':codigo' => $codigo,
                ':email' => $email,
                ':senha' => $senha
            ]);

            echo "<div class='Cadastro-sucesso' style='color: black; text-align: center; font-size:60px; font-weight:600; margin: 1rem;'>Cadastro realizado com sucesso!</div>";
            /* OUTRA MANEIRA DE REDIRECIONAR PARA A PÁGINA DE LOGIN USANDO HTML
            // Inclua essa linha no seu código PHP para informar ao navegador o tipo de codificação de caracteres que está sendo usado
            header('Content-Type: text/html; charset=utf-8');

            // Abra o documento HTML e inicie a seção "head"
            echo '<!DOCTYPE html>';
            echo '<html lang="pt-BR">';
            echo '<head>';
            echo '<meta charset="utf-8">';
            echo '<meta name="viewport" content="width=device-width, initial-scale=1.0">';
            echo '<meta http-equiv="refresh" content="5;url=login.html">';
            // Adicione outras meta tags aqui...
            echo '</head>';

            // Feche a seção "head" e inicie o corpo do documento
            echo '<body>';
            // Adicione o conteúdo da página aqui...
            echo '</body>';
            echo '</html>';
            */
            // OUTRA MANEIRA DE REDIRECIONAR PARA A PÁGINA DE LOGIN USANDO JAVASCRIPT
            echo "<script>window.location.href = 'login.html';
        </script>";
            header('Location : login.html');
        }
        /*
        users
        $stmt = $pdo->prepare("SELECT  * FROM cadastro WHERE nome = :nome AND senha = :senha");
        $stmt->bindValue(':nome', $nome);
        $stmt->bindValue(':codigo', $codigo);
        $stmt->bindValue(':email', $email);
        $stmt->bindValue(':senha', $senha);
        $stmt->bindValue(':csenha', $csenha);

        $stmt->execute();
        $usuario = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($usuario) {
            // É um usuário comum, armazenar os dados na sessão
            $_SESSION['usuario'] = $usuario;
            // É um usuário comum, redirecionar para a página principal
            header('Location: menu.php');
            exit;
        }
        // Verificar se é um usuário comum
        $consultauser = "SELECT * FROM users WHERE login = :login";
        $stmt = $pdo->prepare($consultauser);
        $stmt->bindParam(':login', $login);
        $stmt->execute();
        $resconsultauser = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($resconsultauser && password_verify($senha, $resconsultauser['senha'])) {
            // É um usuário comum, armazenar os dados na sessão
            $_SESSION['usuario'] = $resconsultauser;
            // Redirecionar para a página principal
            header('Location: menu.php');
            exit;
        }

        // Credenciais inválidas, redirecionar para login.html com mensagem de erro
        header('Location: Login.html?error=1001');
        exit;
    }*/
    }
    session_destroy();
} catch (PDOException $e) {
    // Tratar exceções de conexão com o banco de dados aqui, se necessário
    //  echo "Erro de conexão com o banco de dados: " . $e->getMessage();
    if ($e->errorInfo[1] == 1062) {
        // Checa se o erro é código 1062 (Duplicate entry)
        echo "Erro: Por favor, digite outra senha!";
        echo "<script>alert('Erro: Por favor, digite outra senha.');</script>";
    } else {
        // Caso contrário, exibe a mensagem de erro padrão
        echo "Erro: " . $e->getMessage();
    }
    exit;
}
