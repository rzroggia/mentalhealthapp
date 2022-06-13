<?php
  session_start();
  include_once 'funcoes/funcoes.php';
?>

<!DOCTYPE html>
<html lang="pt-br">
  <head>
    <meta charset="utf-8">
    <title>Mental Health Assitant</title>    
    <link rel="stylesheet" href="css/estilo2.css">
  </head>
  <body>

    <nav>
      <div class="barra">
        <ul>
          <li><a href="index.php">Home</a></li>
          <li><a href="sobre.php">Sobre Nós</a></li>
          <?php
            if (isset($_SESSION["useruid"])) {
              echo "<li><a href='profile.php'>Profile Page</a></li>";
              echo "<li><a href='logout.php'>Logout</a></li>";
            }
            else {
              echo "<li><a href='signup.php'>Sign up</a></li>";
              echo "<li><a href='login.php'>Log in</a></li>";
            }
          ?>
        </ul>
      </div>
    </nav>

    <div class="body">
      <h2>Mental Health Assistant</h2>            
      <p>Olá, seja bem vindo ao seu assistente para saúde mental! O meu propósito é te ajudar a sair daqui se sentindo um pouco melhor. Só preciso que você me diga como está se sentindo, o restante é comigo.</p>
      <h2>Então me diga, como você está se sentindo hoje?</h2>
      <div class="sentimento-form">
        <form action="retorno.php" method="post">
          <input type="text" name="sentimento" placeholder="Um Sentimento...">
          <button type="submit" name="submit">Enviar</button>
        </form>
        <?php
          if (isset($_GET["error"])) {
            if ($_GET["error"] == "emptyinput") {
              echo "<p>Ops! Parece que você não colocou nenhum sentimento!</p>";
              echo "<p>Tente novamente!</p>";
            }
          }
          ?>
      </div>
    </div>

  </body>

  <footer>
    <p>Desenvolvido por Rodrigo Roggia</p>
  </footer>

</html>

<script src="js/script.js"></script>