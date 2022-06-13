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

        <section class="index-categories">
            <h2>Quem somos?</h2>
            <p>Na verdade, quem sou. Esta plataforma foi desenvolvida para o Trabalho de Conclusão de Curso do Tecnológo em Sistemas para Internet da Universidade Ulbra. Meu própisto com o desenvolvimento da plataforma é de ajudar quando muitas vezes não conseguimos identificar o que sentimos e como podemos mudar.</p>
        </section>

    </body>
    <footer>
        <p>Desenvolvido por Rodrigo Roggia</p> 
    </footer>
</html>

<script src="js/script.js"></script>