<?php

//require_once("functions/dbConn.php");
//require_once("functions/funzioni.php");
//
//creaDB();
//
//$db = dbConnect();
//
///* require the email as the parameter */
//if(isset($_POST['email']) && isset($_POST['url']) && count($_POST) == 2){	//inserimento nel db dell' url associato alla mail
//	$url = str_replace("///amp", "&", $_POST['url']);
//
//	$google = '://m.google';
//	$wiki = '.m.wikipedia';
//	$google = strpos($url, $google);
//	$pos = strpos($url, $wiki);
//	if ($wiki === false && $google === false){
//		/*tipi di inizio sito per mobile da modificare*/
//		$mobile = '://mobile.';
//		$m = '://m.';
//		$wap = '://wap.';
//
//		$match = false;
//
//		$pos = strpos($url, $mobile);
//		if ($pos !== false)	{
//			$match = $mobile;
//		}
//
//		$pos = strpos($url, $m);
//		if ($pos !== false){
//			$match = $m;
//		}
//
//		$pos = strpos($url, $wap);
//		if ($pos !== false){
//			$match = $wap;
//		}
//
//		if ($match !== false){
//			$url = str_replace($match, '://www.', $url);
//		}
//	}
//	else
//	{
//		$url = str_replace($wiki, '.wikipedia', $url);
//	}
//
//	$email = strtolower($_POST['email']);
//
//	$query = "SELECT id FROM users WHERE email = '$email';";
//	$result = mysql_query($query,$db) or die('Errant query:  '.$query);
//
//	$result = mysql_num_rows($result);
//
//	if ($result){
//	/* if there is an user with the email = $email */
//		$query = "INSERT INTO sended (email, url, data, ora) VALUES ('$email', '$url', now(), curtime());";
//		$result = mysql_query($query,$db) or die('Errant query:  '.$query);
//
//		echo $result;   //1 inserimento andato a buon fine.
//						//0 inserimento fallito.
//	}
//	else{
//		echo -1; //user non presente nel db.
//	}
//	/* disconnect from the db */
//	@mysql_close($db);
//}
//else if(isset($_POST['email']) && count($_POST) == 1) {	//richiesta delle url della mail
//
//
//	$email = strtolower($_POST['email']);
//
//	$query = "SELECT logged FROM users WHERE email = '$email';";
//	$result = mysql_query($query,$db) or die('Errant query:  '.$query);
//
//	$res = mysql_fetch_assoc($result);
//
//	if(mysql_num_rows($result) > 0 && $res['logged']) {
//
//		$query = "SELECT * FROM sended WHERE email = '$email' ORDER BY id DESC;";
//		$result = mysql_query($query,$db) or die('Errant query:  '.$query);
//
//		$url = array();
//
//		while($res = mysql_fetch_assoc($result)) {
//			$url[] = $res;
//		}
//
//		header('Content-type: application/json');
//		echo json_encode($url);
//	}
//	else if (mysql_num_rows($result) == 0){
//		echo -2; //utente non presente;
//	}
//	else if (!$res['logged']){
//		echo -1; //utente non loggato
//	}
//
//	/* disconnect from the db */
//	@mysql_close($db);
//
//}
//else if(isset($_POST['readed']) && count($_POST) == 1){	//segno l'url come letto
//
//	$id = strtolower($_POST['readed']);
//
//	$query = "UPDATE sended SET readed = 1, data = now(), ora = curtime() WHERE id = $id;";
//	$result = mysql_query($query,$db) or die('Errant query:  '.$query); //1 se è stato aggiornato correttamente
//
//	echo $result;
//
//	/* disconnect from the db */
//	@mysql_close($db);
//}
//else if(isset($_POST['ready'], $_POST['password']) && count($_POST) == 2){	//inserimento dell'utente nel db
//
//	$email = strtolower($_POST['ready']);
//	$password = $_POST['password'];
//
//	$query = "SELECT * FROM users WHERE email = '$email';";
//	$result = mysql_query($query,$db) or die('Errant query:  '.$query);
//
//	if(mysql_num_rows($result) == 0){
//
//		$query = "INSERT INTO users (email, password, logged) VALUES ('$email', '$password', 0);";
//		$result = mysql_query($query,$db) or die('Errant query:  '.$query); //1 se è stato inserito correttamente
//
//		echo $result;
//	}
//	else{
//		echo -2;
//	}
//
//	/* disconnect from the db */
//	@mysql_close($db);
//}
//else if(isset($_POST['login'], $_POST['password']) && count($_POST) == 2){	//login dell'utente
//
//	$email = strtolower($_POST['login']);
//	$password = $_POST['password'];
//
//	$query = "SELECT password FROM users WHERE email = '$email';";
//	$result = mysql_query($query,$db) or die('Errant query:  '.$query);
//
//	$pw = mysql_fetch_row($result);
//	if(mysql_num_rows($result) > 0 && !strcmp($pw[0], $password)){
//
//		$query = "UPDATE users SET logged = 1 WHERE email = '$email';";
//
//		$result = mysql_query($query,$db) or die('Errant query:  '.$query); //1 se è stato loggato correttamente
//
//		if ($result){
//			$query = "SELECT * FROM sended WHERE email = '$email' ORDER BY id DESC;";
//			$result = mysql_query($query,$db) or die('Errant query:  '.$query);
//
//			/* create one master array of the records */
//			$url = array();
//
//			while($res = mysql_fetch_assoc($result)) {
//				$url[] = $res;
//			}
//
//			header('Content-type: application/json');
//			echo json_encode($url);
//		}
//		else{ //errore nel login
//			echo $result;
//		}
//	}
//	else{
//		//email o password errata
//		echo -2;
//	}
//
//	/* disconnect from the db */
//	@mysql_close($db);
//}
//else if(isset($_POST['logout']) && count($_POST) == 1){	//logout dell'utente
//
//	$email = strtolower($_POST['logout']);
//
//	$query = "SELECT * FROM users WHERE email = '$email';";
//	$result = mysql_query($query,$db) or die('Errant query:  '.$query);
//
//	if(mysql_num_rows($result)) {
//		$query = "UPDATE users SET logged = 0 WHERE email = '$email';";
//
//		$result = mysql_query($query,$db) or die('Errant query:  '.$query); //1 se è stato fatto logout correttamente
//
//		echo $result;
//	}
//	else{
//		echo -1; //user non presente
//	}
//
//	/* disconnect from the db */
//	@mysql_close($db);
//}
//else if(isset($_POST['delete'], $_POST['password']) && count($_POST) == 2){	//elimina l'utente
//
//	$email = strtolower($_POST['delete']);
//	$password = $_POST['password'];
//
//	$query = "SELECT password FROM users WHERE email = '$email';";
//	$result = mysql_query($query,$db) or die('Errant query:  '.$query);
//
//	$pw = mysql_fetch_row($result);
//	if(mysql_num_rows($result) > 0 && !strcmp($pw[0], $password)){
//
//	//eventualmente ALTER TABLE  `users` AUTO_INCREMENT =id deleted
//		$query = "DELETE FROM users WHERE email = '$email';";
//		$result = mysql_query($query,$db) or die('Errant query:  '.$query);
//
//		echo $result;
//	}
//	else{
//		echo -2; //password errata
//	}
//
//	/* disconnect from the db */
//	@mysql_close($db);
//}
//else if(isset($_POST['delete_url'], $_POST['email']) && count($_POST) == 2){	//elimina l'url
//
//	$id = strtolower($_POST['delete_url']);
//	$email = strtolower($_POST['email']);
//
//	$query = "DELETE FROM sended WHERE id = '$id';";
//	$result = mysql_query($query,$db) or die('Errant query:  '.$query);
//
//	$query = "SELECT * FROM sended WHERE email = '$email' ORDER BY id DESC;";
//	$result = mysql_query($query,$db) or die('Errant query:  '.$query);
//
//	$url = array();
//
//	//if(mysql_num_rows($result)) {
//	while($res = mysql_fetch_assoc($result)) {
//		$url[] = $res;
//	}
//	header('Content-type: application/json');
//	echo json_encode($url);
//
//	/* disconnect from the db */
//	@mysql_close($db);
//}
//else if(isset($_POST['delete_history']) && count($_POST) == 1){	//elimina la storia
//
//	$email = strtolower($_POST['delete_history']);
//
//	$query = "DELETE FROM sended WHERE email = '$email';";
//	$result = mysql_query($query,$db) or die('Errant query:  '.$query);
//
//	echo $result;
//
//	/* disconnect from the db */
//	@mysql_close($db);
//}
//else {
//	@mysql_close($db);
//
//	echo "Invalid Request!!";
//}
//?>
