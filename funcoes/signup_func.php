<?php

if (isset($_POST["submit"])) {

  $name = $_POST["name"];
  $email = $_POST["email"];
  $username = $_POST["uid"];
  $pwd = $_POST["pwd"];
  $pwdRepeat = $_POST["pwdrepeat"];


  require_once "db_connect.php";
  require_once 'funcoes.php';


  if (emptyInputSignup($name, $email, $username, $pwd, $pwdRepeat) !== false) { //VERIFICA INPUT VAZIO PARA SIGNUP
    header("location: ../signup.php?error=inputvazio");
		exit();
  }

  if (invalidUid($uid) !== false) { ////VERIFICA INPUT VAZIO PARA SIGNUP
    header("location: ../signup.php?error=usuarioinvalido");
		exit();
  }

  if (invalidEmail($email) !== false) {
    header("location: ../signup.php?error=emailinvalido");
		exit();
  }

  if (pwdMatch($pwd, $pwdRepeat) !== false) {
    header("location: ../signup.php?error=senhasnaobatem");
		exit();
  }

  if (uidExists($conn, $username) !== false) {
    header("location: ../signup.php?error=usuariojautilizado");
		exit();
  }

  createUser($conn, $name, $email, $username, $pwd);

} else {
	header("location: ../signup.php");
    exit();
}
