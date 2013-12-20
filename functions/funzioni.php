<?php

	require_once("dbConn.php");

	function creaDB()
	{
		$db = dbConnect();
		
		include "configurazione.php";
		
		$queryCreaDb = "CREATE DATABASE IF NOT EXISTS ".$_CONF["db"].";";
						
		mysql_query($queryCreaDb, $db);
		mysql_select_db($_CONF['db'],$db);
		
		$creaUsers = "CREATE TABLE IF NOT EXISTS users (
						id int(11) PRIMARY KEY AUTO_INCREMENT,
						email VARCHAR(50) UNIQUE,
						password VARCHAR(32),
						logged TINYINT(1) DEFAULT 1
						);";		
						
		$creaSended = "CREATE TABLE IF NOT EXISTS sended (
						id int(11) PRIMARY KEY AUTO_INCREMENT,
						email VARCHAR(50),
						url VARCHAR(255),						
						readed TINYINT(1) DEFAULT 0,
						data DATE,
						ora time,
						FOREIGN KEY(email) REFERENCES users(email)
							ON DELETE CASCADE
							ON UPDATE CASCADE
						);";		
						
		$check = "SHOW TABLES;";

		$q = mysql_query($check, $db);	
		
		if (@mysql_num_rows($q) == 0)  // se non esistono tabelle
		{
			$filename = "android2chrome.sql";
			if (file_exists($filename))
			{
				// Temporary variable, used to store current query
				$templine = '';
				// Read in entire file
				$lines = file($filename);
				// Loop through each line
				foreach ($lines as $line)
				{
					// Skip it if it's a comment
					if (substr($line, 0, 2) == '--' || $line == '')
						continue;
				 
					// Add this line to the current segment
					$templine .= $line;
					// If it has a semicolon at the end, it's the end of the query
					if (substr(trim($line), -1, 1) == ';')
					{
						// Perform the query
						mysql_query($templine) or print('Error performing query \'<strong>' . $templine . '\': ' . mysql_error() . '<br /><br />');
						// Reset temp variable to empty
						$templine = '';
					}
				}
			}
			else
			{
				mysql_query($creaUsers, $db) or die("users: ".mysql_error());
				mysql_query($creaSended, $db) or die("sended: ".mysql_error());

//                mysql_query($sql, $db);
			}
		}
		mysql_close($db);
	}
