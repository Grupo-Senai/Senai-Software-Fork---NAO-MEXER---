<?php
// Conexão com o banco de dados MySQL usando PDO
$host = '127.0.0.1';
$dbname = 'biblioteca';
$username = 'root';
$password = '';
try {
    $conn = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $conn->exec("SET FOREIGN_KEY_CHECKS=0");
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

// Verifica se os campos foram enviados via POST
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    // Obtém os dados do formulário
    $usuario = $_POST["usuario"];
    $novaSenha = $_POST["novaSenha"];
    $Csenha = $_POST["Csenha"];

    // Verifica se as senhas coincidem
    if ($novaSenha !== $Csenha) {
        die("As senhas não coincidem.");
    }

    // Atualiza a senha no banco de dados
    $hashedPassword = password_hash($novaSenha, PASSWORD_DEFAULT);
    try {
        /* bloco 1 atualiza senha*/
        $sql1 = "UPDATE cadastro SET senha = :hashedPassword WHERE email = :usuario || nome = :usuario;";
        $stmt = $conn->prepare($sql1);
        $stmt->bindParam(':hashedPassword', $hashedPassword);
        $stmt->bindParam(':usuario', $usuario);
        $stmt->bindParam(':Csenha', $Csenha);
        $stmt->execute();
    } catch (PDOException $e) {
        // Tratar exceções de conexão com o banco de dados aqui, se necessário
        //  echo "Erro de conexão com o banco de dados: " . $e->getMessage();
        if ($e->errorInfo[1] == 1062) {
            // Checa se o erro é código 1062 (Duplicate entry)
            echo "Erro: Por favor, utilize uma senha diferente da anterior. ";
            echo "<script>alert('Erro: Por favor, utilize uma senha diferente da anterior.');</script>";
        } else {
            // Caso contrário, exibe a mensagem de erro padrão
            echo "Erro: " . $e->getMessage();
        }
        exit;
    }
    try {
        /* bloco 2 atualiza checksenha*/
        $sql1 = "UPDATE cadastro SET checksenha = :hashedPassword WHERE email = :usuario || nome = :usuario;";
        $stmt = $conn->prepare($sql1);
        $stmt->bindParam(':hashedPassword', $hashedPassword);
        $stmt->bindParam(':usuario', $usuario);
        $stmt->bindParam(':Csenha', $Csenha);
        $stmt->execute();
    } catch (PDOException $e) {
        // Tratar exceções de conexão com o banco de dados aqui, se necessário
        //  echo "Erro de conexão com o banco de dados: " . $e->getMessage();
        if ($e->errorInfo[1] == 1062) {
            // Checa se o erro é código 1062 (Duplicate entry)
            echo "Erro: Por favor, utilize uma senha diferente da anterior. ";
            echo "<script>alert('Erro: Por favor, utilize uma senha diferente da anterior.');</script>";
        } else {
            // Caso contrário, exibe a mensagem de erro padrão
            echo "Erro: " . $e->getMessage();
        }
        exit;
    }
    try {
        /* bloco 3 atualiza users */
        $sql = "UPDATE users SET senha = :hashedPassword WHERE email = :usuario || login = :usuario;";
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':hashedPassword', $hashedPassword);
        $stmt->bindParam(':usuario', $usuario);
        $stmt->bindParam(':Csenha', $Csenha);
        $stmt->execute();
    } catch (PDOException $e) {
        // Tratar exceções de conexão com o banco de dados aqui, se necessário
        //  echo "Erro de conexão com o banco de dados: " . $e->getMessage();
        if ($e->errorInfo[1] == 1062) {
            // Checa se o erro é código 1062 (Duplicate entry)
            echo "Erro: Por favor, utilize uma senha diferente da anterior. ";
            echo "<script>alert('Erro: Por favor, utilize uma senha diferente da anterior.');</script>";
        } else {
            // Caso contrário, exibe a mensagem de erro padrão
            echo "Erro: " . $e->getMessage();
        }
        exit;
    }
    /*
    $sql = "UPDATE users SET senha = :hashedPassword WHERE email = :usuario || login = :usuario;";
    $stmt = $conn->prepare($sql);
*/
    try {
        $stmt->execute();
        echo "Senha atualizada com sucesso!";
        echo "<script> setTimeout(function() { window.location.href = 'login.html'; }, 5000); </script>";
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
}
$conn->exec("SET FOREIGN_KEY_CHECKS=0");
// Fecha a conexão com o banco de dados
$conn = null;
