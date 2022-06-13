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

    <div class = "body">
      <div class="signup-form">
        <h2>Log In</h2>
        <div class="signup-form-form">
          <form action="funcoes/login_func.php" method="post">
            <input type="text" name="uid" placeholder="Usuário ou Email...">
            <input type="password" name="pwd" placeholder="Sua Senha...">
            <button type="submit" name="submit">Sign up</button>
          </form>
        </div>
      </div>
    <div>
    
    <div>
      <?php

        if (isset($_GET["error"])) {
          if ($_GET["error"] == "emptyinput") {
            echo "<p>Ops! Parece que você esqueceu de preencher algum campo!</p>";
            echo "<p>Tente novamente!</p>";
          }
          else if ($_GET["error"] == "wronglogin") {
            echo "<p>Ops! Parece que você errou seu login ou senha!</p>";
            echo "<p>Tente novamente!</p>";
          }
        }
      ?>
    </div>

    </body>
    <footer>
      <p>Desenvolvido por Rodrigo Roggia</p> 
    </footer>

</html>

<script src="js/script.js"></script>