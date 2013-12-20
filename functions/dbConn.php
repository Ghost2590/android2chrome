<?php
	function dbConnect(){
		include 'configurazione.php';
		
		$db = mysql_connect($_CONF['host'],$_CONF['user'],$_CONF['password']);
		
		if(!$db){
			echo "<meta http-equiv='refresh' content=\"0; url='functions/conf.php'\"/>";
		}
		mysql_select_db($_CONF['db'],$db);// or die('Cannot select the DB');
		return $db;
	}
?>
