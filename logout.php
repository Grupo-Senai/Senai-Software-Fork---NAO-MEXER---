<?php 
session_start(); // Inicie a sessão, se ainda não estiver iniciada

/* Verifique se o parâmetro de erro é definido e se é igual a '1001'
if (isset($_GET['error']) && $_GET['error'] == '1001') {
  echo "<div class='failed' style='text-align: center; font-size:20px; font-weight:600;'>Login ou senha inválidos.</div>";
}
*/
// Limpe todas as variáveis de sessão
session_destroy();
if (isset($_GET['error']) && $_GET['error'] == '1001') {
  echo "<div class='failed' style='text-align: center; font-size:20px; font-weight:600;'>Login ou senha inválidos.</div>";
}
header("Location: login.html");
exit();
?>
