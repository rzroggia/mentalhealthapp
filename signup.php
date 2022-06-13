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
        <h2>Sign Up</h2>
        <div class="signup-form-form">
          <form action="funcoes/signup_func.php" method="post">
            <input type="text" name="name" placeholder="Digite o seu nome">
            <input type="text" name="email" placeholder="Digite o seu email">
            <input type="text" name="uid" placeholder="Digite o seu usuário">
            <input type="password" name="pwd" placeholder="Digite a sua senha">
            <input type="password" name="pwdrepeat" placeholder="Repita a sua senha">
            <button type="submit" name="submit">Sign up</button>
          </form>
        </div>
      </div>
      <?php
        if (isset($_GET["error"])) {
          if ($_GET["error"] == "inputvazio") {
            echo "<p>Ops! Parece que você esqueceu de preencher algum campo!</p>";
            echo "<p>Tente novamente mas dessa vez com todos os campos!</p>";
          }
          else if ($_GET["error"] == "usuarioinvalido") {
            echo "<p>Ops! Parece que o seu usuário é inválido</p>";
            echo "<p>Tente novamente mas dessa vez com outro nome!</p>";
          }
          else if ($_GET["error"] == "emailinvalido") {
            echo "<p>Ops! Parece que seu email está incorreto!</p>";
            echo "<p>Tente novamente mas dessa vez com o email correto!</p>";
          }
          else if ($_GET["error"] == "senhasnaobatem") {
            echo "<p>Ops! As suas senhas não são iguais!</p>";
            echo "<p>Tente novamente garantindo que as senhas são iguais!</p>";
          }
          else if ($_GET["error"] == "algodeuerrado") {
            echo "<p>Algo deu errado! Você pode tentar novamente?</p>";
          }
          else if ($_GET["error"] == "usuariojautilizado") {
            echo "<p>Ops! Este usuário já está utilizado!</p>";
            echo "<p>Por favor, tente com outro!</p>";
          }
          else if ($_GET["error"] == "tudocerto") {
            echo "<p>Muito obrigado, você agora está cadastrado em nosso iste!</p>";
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