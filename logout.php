<!DOCTYPE html>
<html lang="pt-br">
  <head>
    <meta charset="utf-8">
    <title>Mental Health Assitant</title>    
    <link rel="stylesheet" href="css/estilo2.css">
  </head>
  <body>
    <nav>
      <div class="wrapper">
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
      </div class="wrapper">
    </nav>

        <?php

        session_start();
        session_unset();
        session_destroy();

        header("location: index.php");
        exit();
        ?>

  <footer>
        <p>Desenvolvido por Rodrigo Roggia</p> 
  </footer>

  </body>

</html>

<script src="js/script.js"></script>