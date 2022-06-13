<?php

use LDAP\Result;

require_once 'db_connect.php';

function emptyInputSignup($name, $email, $username, $pwd, $pwdRepeat) { // VERIFICA SE NENHUM DOS CAMPOS PARA CRIAR UM USUÁRIO NÃO ESTÃO VAZIOS

	if (empty($name) || empty($email) || empty($username) || empty($pwd) || empty($pwdRepeat)) {
		$result = true;
	}
	else {
		$result = false;
	}
	return $result;
}

function invalidUid($username) { //VERIFICA SE O NOME DE USUÁRIO É VÁLIDO

	if (!preg_match("/^[a-zA-Z0-9]*$/", $username)) {
		$result = true;
	}
	else {
		$result = false;
	}
	return $result;
}

function invalidEmail($email) { //VERIFICA SE O EMAIL É VÁLIDO

	if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
		$result = true;
	}
	else {
		$result = false;
	}
	return $result;
}


function pwdMatch($pwd, $pwdrepeat) { //VERIFICA SE O PWD E PWD REPETIDO ESTÃO IGUAIS

	if ($pwd !== $pwdrepeat) {
		$result = true;
	}
	else {
		$result = false;
	}
	return $result;
}

 
function uidExists($conn, $username) { //VERIFICA SE O NOME DE USUARIO OU EMAIL PREENCHIDO JÁ NÃO EXISTEM NO DB
  $sql = "SELECT * FROM users WHERE usersUid = ? OR usersEmail = ?;";
	$stmt = mysqli_stmt_init($conn);
	if (!mysqli_stmt_prepare($stmt, $sql)) {
	 	header("location: ../signup.php?error=stmtfailed");
		exit();
	}

	mysqli_stmt_bind_param($stmt, "ss", $username, $username);
	mysqli_stmt_execute($stmt);


	$resultData = mysqli_stmt_get_result($stmt);

	if ($row = mysqli_fetch_assoc($resultData)) {
		return $row;
	}
	else {
		$result = false;
		return $result;
	}

	mysqli_stmt_close($stmt);
}


function createUser($conn, $name, $email, $username, $pwd) { //CRIA O O USUARIO EM CASO DE CONEXÃO BEM SUCEDIDA E SQL BEM FORMATADO NO DB
  $sql = "INSERT INTO users (usersName, usersEmail, usersUid, usersPwd) VALUES (?, ?, ?, ?);";

	$stmt = mysqli_stmt_init($conn);
	if (!mysqli_stmt_prepare($stmt, $sql)) {
	 	header("location: ../signup.php?error=stmtfailed");
		exit();
	}

	$hashedPwd = password_hash($pwd, PASSWORD_DEFAULT);

	mysqli_stmt_bind_param($stmt, "ssss", $name, $email, $username, $hashedPwd);
	mysqli_stmt_execute($stmt);
	mysqli_stmt_close($stmt);
	mysqli_close($conn);
	header("location: ../signup.php?error=none");
	exit();
}


function emptyInputLogin($username, $pwd) { //VERIFICA SE NÃO HÁ CAMPOS VAZIOS

	if (empty($username) || empty($pwd)) {
		$result = true;
	}
	else {
		$result = false;
	}
	return $result;
}


function loginUser($conn, $username, $pwd) { //VERIFICA SE O LOGIN ESTA CORRETO, SE A SENHA ESTA CORRETA E SE VERDADEIRO PARA AMBOS, FAZ LOGIN DO USUARIO
	$uidExists = uidExists($conn, $username);

	if ($uidExists === false) {
		header("location: ../login.php?error=wronglogin");
		exit();
	}

	$pwdHashed = $uidExists["usersPwd"];
	$checkPwd = password_verify($pwd, $pwdHashed);

	if ($checkPwd === false) {
		header("location: ../login.php?error=wronglogin");
		exit();
	}
	elseif ($checkPwd === true) {
		session_start();
		$_SESSION["userid"] = $uidExists["usersId"];
		$_SESSION["useruid"] = $uidExists["usersUid"];
		header("location: ../index.php?error=none");
		exit();
	}
}




/*FUNCOES YOUTUBE*/ 

function searchyoutube($sentimento) { //A FUNÇÃO FAZ UMA CHAMADA API COM SENTIMENTO PASSADO PELO USUARIO 
	$apikey = "AIzaSyCM_yMQyQB_qauLqimblibmA7jEHInFpYo";
	$apiurl = "https://www.googleapis.com/youtube/v3/";
	$maxresults = 3;
	$saudemental = "saude,mental";

	$search = $apiurl . "search?part=snippet" . "&key=" . $apikey . "&maxResults=" . $maxresults . "&q=" . $sentimento . "+" . $saudemental;
	
	$videos = json_decode(file_get_contents($search));
	return $videos;

}

function presentYoutube($videos){ //COM OS VALORES DA CHAMADA API, RETORNA O PLAYER DO YOUTUBE PARA CADA RECOMENDAÇÃO
	echo "<h3>Esses vídeos no Youtube podem te ajudar!</h3>";
	foreach($videos->items as $video) {
		echo '<iframe width="560" height="315" src="https://www.youtube.com/embed/' . $video->id->videoId . '" title="' . $video->snippet->title  . '" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>';
	}
}

function saveYoutube($videos, $name, $conn) { ////SALVA AS RECOMENDAÇÕES NA TABELA YOUTUBEVIDEOS NO DB
	$video_array = [];

	foreach($videos->items as $video) {
		array_push($video_array, $name, $video->id->videoId, $video->snippet->title);
	}
	$sql = "INSERT INTO youtubevideos (usersName, videoId, videoTitle, createDate) VALUES (?, ?, ?, ?), (?, ?, ?, ?), (?, ?, ?, ?);";
  
	$stmt = mysqli_stmt_init($conn);
	if (!mysqli_stmt_prepare($stmt, $sql)) {
		header("location: ../retorno.php?error=stmtfailed");
		exit();
	}

	$now = date("Y-m-d H:i:s");
	mysqli_stmt_bind_param($stmt, "ssssssssssss", $video_array[0], $video_array[1], $video_array[2], $now, $video_array[3], $video_array[4], $video_array[5], $now, $video_array[6], $video_array[7], $video_array[8], $now);
	mysqli_stmt_execute($stmt);
	mysqli_stmt_close($stmt);
}





/*FUNCOES SPOTIFY*/

function getToken() { //VAI BUSCAR O TOKEN DO SPOTIFY CONFORME DOCUMENTAÇÃO

	$client_id = "fef534fa30da4eafab343562ee8ab87d";
	$client_secret = "96aecbe7265f482b85ec6a337e9c35bf";
	$url_base = "https://accounts.spotify.com/api/token";
	$access_token = base64_decode("$client_id:$client_secret");
	
	$curl = curl_init();

	curl_setopt_array($curl, [
	  CURLOPT_URL => $url_base,
	  CURLOPT_RETURNTRANSFER => true,
	  CURLOPT_ENCODING => "",
	  CURLOPT_MAXREDIRS => 10,
	  CURLOPT_TIMEOUT => 30,
	  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
	  CURLOPT_CUSTOMREQUEST => "POST",
	  CURLOPT_POSTFIELDS => "grant_type=client_credentials&=",
	CURLOPT_COOKIE => "__Host-device_id=AQDJJI1ztlD17FDhPOP3-4DeE0a4VACAgkX4PzNVejJtv_QeZl6qcTfX5KuFIN7EsVH7L3jC_cJqdmQx2dj6TLXJ_W96fPldgUM; sp_tr=false",
	  CURLOPT_HTTPHEADER => [
		"Authorization: Basic ZmVmNTM0ZmEzMGRhNGVhZmFiMzQzNTYyZWU4YWI4N2Q6OTZhZWNiZTcyNjVmNDgyYjg1ZWM2YTMzN2U5YzM1YmY=",
		//"Authorization: Basic ZmVmNTM0ZmEzMGRhNGVhZmFiMzQzNTYyZWU4YWI4N2Q6OTZhZWNiZTcyNjVmNDgyYjg1ZWM2YTMzN2U5YzM1YmY=",
		"Content-Type: application/x-www-form-urlencoded"
	  ],
	]);
	
	$response = json_decode(curl_exec($curl));
	$err = curl_error($curl);
	
	curl_close($curl);
	
	if ($err) {
	  echo "cURL Error #:" . $err;
	} else {
	  return $response->access_token;

	}
}

function searchspotify($sentimento) { //COM O TOKEN DA ÚLTIMA FUNÇÃO, A FUNÇÃO FAZ UMA CHAMADA API COM SENTIMENTO PASSADO PELO USUARIO
	$curl = curl_init();
	$token = getToken();
	$type = 'track,episode';
	$limit = 3;
	$saudemental = "saude,mental";
	
	curl_setopt_array($curl, [
	CURLOPT_URL => "https://api.spotify.com/v1/search?q=" . $sentimento . "+" . $saudemental . "&type=" . $type . "&limit=" . $limit,
	CURLOPT_RETURNTRANSFER => true,
	CURLOPT_ENCODING => "",
	CURLOPT_MAXREDIRS => 10,
	CURLOPT_TIMEOUT => 30,
	CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
	CURLOPT_CUSTOMREQUEST => "GET",
	CURLOPT_POSTFIELDS => "",
	CURLOPT_HTTPHEADER => [
		"Authorization: Bearer " . $token
	],
	]);

	$audios = json_decode(curl_exec($curl));
	$err = curl_error($curl);

	curl_close($curl);

	if ($err) {
	echo "cURL Error #:" . $err;
	}

	return $audios;
}

function presentSpotify ($audios) { //COM OS VALORES DA CHAMADA API, RETORNA O PLAYER DO SPOTIFY PARA CADA RECOMENDAÇÃO
	echo "<h3>Essas músicas no Spotify podem te ajudar!</h3>";
	foreach($audios->tracks->items as $track) {	
		echo '<iframe style="border-radius:12px" src="https://open.spotify.com/embed/' . $track->type . '/' . $track->id . '" width="33%" height="190" frameBorder="0" allowfullscreen="" allow="autoplay; clipboard-write; encrypted-media; fullscreen; picture-in-picture"></iframe>';
	}
}


function saveSpotify($audios, $name, $conn) { //SALVA AS RECOMENDAÇÕES NA TABELA SPOTIFYAUDIO NO DB
	$audios_array = [];

	foreach($audios->tracks->items as $track) {
		array_push($audios_array, $name, $track->type, $track->id);
	}
	$sql = "INSERT INTO spotifyaudio (usersName, audioType, audioId, createDate) VALUES (?, ?, ?, ?), (?, ?, ?, ?), (?, ?, ?, ?);";
  
	$stmt = mysqli_stmt_init($conn);
	if (!mysqli_stmt_prepare($stmt, $sql)) {
		header("location: ../retorno.php?error=stmtfailed");
		exit();
	}

	$now = date("Y/m/d H:i:s");
	mysqli_stmt_bind_param($stmt, "ssssssssssss", $audios_array[0], $audios_array[1], $audios_array[2], $now, $audios_array[3], $audios_array[4], $audios_array[5], $now, $audios_array[6], $audios_array[7], $audios_array[8], $now);
	mysqli_stmt_execute($stmt);
	mysqli_stmt_close($stmt);
}





/*FUNCOES GOOGLE*/

function searchgoogle($sentimento) { //A FUNÇÃO FAZ UMA CHAMADA API DO GOOGLE COM O SENTIMENTO PASSADO PELO USUARIO
	$api_key = "AIzaSyCM_yMQyQB_qauLqimblibmA7jEHInFpYo";
	$search_id = "cf851c0666784b132";
	$base_url = "https://customsearch.googleapis.com/customsearch/v1?key=";
	$maxresults = 3;

	$curl = curl_init();

	curl_setopt_array($curl, [
	CURLOPT_URL => $base_url . $api_key . "&cx=" . $search_id . "&q=" . $sentimento . "&num=" . $maxresults,
	CURLOPT_RETURNTRANSFER => true,
	CURLOPT_ENCODING => "",
	CURLOPT_MAXREDIRS => 10,
	CURLOPT_TIMEOUT => 30,
	CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
	CURLOPT_CUSTOMREQUEST => "GET",
	CURLOPT_POSTFIELDS => "",
	]);

	$books = json_decode(curl_exec($curl));
	$err = curl_error($curl);

	curl_close($curl);
	if ($err) {
		echo "cURL Error #:" . $err;
		}
	return $books;
}


function presentGoogle($books) { //COM OS VALORES DA CHAMADA API, RETORNA O OS DADOS DOS LIVROS DO GOOGLE BOOKS PARA CADA RECOMENDAÇÃO
	echo "<h3>Esses livros podem te ajudar!</h3>";
	foreach($books->items as $book) {
		$title = $book->title;
		echo "<div class='titulos'><p>$title</p></div>";
		echo "</br>";
		$link = $book->link;
		echo "<div class='links'><a href='$link' target='_blank' rel='external'>Link</a></div>";
		echo "</br>";
		$img = $book->pagemap->cse_image[0]->src;
		echo "<img src=$img alt= 'Capa do livro" . $title . "'>";
		echo "</br>";	
	}
}

function saveGoogle($books, $name, $conn) { //SALVA AS RECOMENDAÇÕES NA TABELA GOOGLEBOOKS NO DB
	$books_array = [];

	foreach($books->items as $book) {
		array_push($books_array, $name, $book->title, $book->link, $book->pagemap->cse_image[0]->src);
	}
	$sql = "INSERT INTO googlebooks (usersName, bookName, bookLink, bookImage, createDate) VALUES (?, ?, ?, ?, ?), (?, ?, ?, ?, ?), (?, ?, ?, ?, ?);";
  
	$stmt = mysqli_stmt_init($conn);
	if (!mysqli_stmt_prepare($stmt, $sql)) {
		header("location: ../retorno.php?error=stmtfailed");
		exit();
	}

	$now = date("Y/m/d H:i:s");
	mysqli_stmt_bind_param($stmt, "sssssssssssssss", $books_array[0], $books_array[1], $books_array[2], $books_array[3], $now, $books_array[4], $books_array[5], $books_array[6], $books_array[7], $now, $books_array[8], $books_array[9], $books_array[10], $books_array[11], $now);
	mysqli_stmt_execute($stmt);
	mysqli_stmt_close($stmt);
	exit();

}

/* FUNCÕES BUSCA BANCO */

function getVideos($conn, $name) {
	$sql = "SELECT videoId, videoTitle FROM youtubevideos WHERE usersName = '$name' ORDER BY createDate DESC LIMIT 1;";


	$stmt = mysqli_stmt_init($conn);
	if (!mysqli_stmt_prepare($stmt, $sql)) {
		header("location: ../retorno.php?error=stmtfailed");
		exit();
	}
	$result = mysqli_query($conn, $sql);

	while($row = mysqli_fetch_assoc($result)) {
		echo '<iframe width="560" height="315" src="https://www.youtube.com/embed/' . $row['videoId'] . '" title="' . $row['videoTitle'] . '" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>';

	}
}

function getAudios($conn, $name) {
	$sql = "SELECT audioType, audioId FROM spotifyaudio WHERE usersName = '$name' ORDER BY createDate DESC LIMIT 1;";


	$stmt = mysqli_stmt_init($conn);
	if (!mysqli_stmt_prepare($stmt, $sql)) {
		header("location: ../retorno.php?error=stmtfailed");
		exit();
	}
	$result = mysqli_query($conn, $sql);

	while($row = mysqli_fetch_assoc($result)) {
		echo '<iframe style="border-radius:12px" src="https://open.spotify.com/embed/' . $row['audioType'] . '/' . $row['audioId'] . '" width="33%" height="190" frameBorder="0" allowfullscreen="" allow="autoplay; clipboard-write; encrypted-media; fullscreen; picture-in-picture"></iframe>';
	}

}


function getBooks($conn, $name) {

	$sql = "SELECT bookName, bookLink FROM googlebooks WHERE usersName = '$name' ORDER BY createDate DESC LIMIT 1;";


	$stmt = mysqli_stmt_init($conn);
	if (!mysqli_stmt_prepare($stmt, $sql)) {
		header("location: ../retorno.php?error=stmtfailed");
		exit();
	}
	$result = mysqli_query($conn, $sql);

	while($row = mysqli_fetch_assoc($result)) {
		$bookname = $row['bookName'];
		$booklink = $row['bookLink'];

		echo "<div><p>$bookname</p></div>";
		echo "</br>";
		echo "<div><a href=$booklink target='_blank' rel='external'>Link</a></div>";
		echo "</br>";

	}
}