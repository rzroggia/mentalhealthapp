<?php

if (isset($_POST["submit"])) {


  $username = $_POST["uid"];
  $pwd = $_POST["pwd"];

  require_once "db_connect.php";
  require_once 'funcoes.php';


  if (emptyInputLogin($username, $pwd) === true) {
    header("location: ../login.php?error=emptyinput");
		exit();
  }

  loginUser($conn, $username, $pwd);

} else {
	header("location: ../login.php");
    exit();
}
