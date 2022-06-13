<?php
  session_start();
  include_once 'funcoes/funcoes.php';
  require_once 'funcoes/db_connect.php';
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
        <?php 
            include_once 'funcoes/funcoes.php';
            echo "<h2>Bem vindo de volta! Este é o seu perfil e aqui você encontra suas últimas recomendações!</h2>";
                      
            getVideos($conn, $_SESSION["useruid"]);
            getAudios($conn, $_SESSION["useruid"]);
            getBooks($conn, $_SESSION["useruid"]);
        ?>
    </div>

    </body>
    <footer>
        <p>Desenvolvido por Rodrigo Roggia</p> 
    </footer>

</html>

<script src="js/script.js"></script>