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
              echo "<li><a href='profile.php'>Perfil</a></li>";
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
      <h3>Eis aqui algumas sugestões:</h3>

      <?php
        require_once 'funcoes/funcoes.php';

        if (isset($_POST["submit"])) {
          
          $sentimento = $_POST["sentimento"];
          
          if (empty($sentimento) !== false) {
            header("location: index.php?error=emptyinput");
            exit();
          }
          if (isset($_SESSION["useruid"])) {
            
            $videos = searchyoutube($sentimento);
            presentYoutube($videos);
            saveYoutube($videos, $_SESSION["useruid"], $conn);

            $audios = searchspotify($sentimento);
            presentSpotify($audios);
            saveSpotify($audios, $_SESSION["useruid"], $conn);
            
            $books = searchgoogle($sentimento);
            presentGoogle($books);
            saveGoogle($books, $_SESSION["useruid"], $conn);
          } else {
            $videos = searchyoutube($sentimento);
            presentYoutube($videos);
            echo "<h3>Se voce se cadastrar e fizer login, eu tenho mais recomendações para você!</h3>";
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