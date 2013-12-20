<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" 
"http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">

<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="it" lang="it" dir="ltr">
	<head>	
		<title>Android2Chrome - Autenticazione</title>
		<link rel="shortcut icon" href="../favicon.ico"/>
		<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1"/>
	</head>

	<body>
		
		<div id="container">
			<div id="header"></div>
			
			<div id="contentConf">
				<h3>Inserire le credenziali per la connessione al Database</h3>
				
				<div id="conError">
					<?php
						if (isset($_POST["host"], $_POST["user"], $_POST["pw"], $_POST["db"]))
						{
							if (strcmp($_POST["host"], "") && strcmp($_POST["user"], "") && strcmp($_POST["db"], "")) // se i campi host e user non sono vuoti, la pw pu&ograve essere facoltativa
							{
								$file_php = fopen("configurazione.php","w");
								fwrite($file_php, "<?php\n"
											."\t$"."_CONF = array(\"host\" => \"".$_POST["host"]."\", \"user\" => \"".$_POST["user"]."\", \"password\" => \"".$_POST["pw"]."\", \"db\" => \"".$_POST["db"]."\");\n"
											."?>");
								fclose($file_php);
								
								echo "<meta http-equiv='refresh' content=\"0; url='../'\"/>";
							}else 
							{
								echo "I dati non sono stati inseriti correttamente";
							}
						}
					?>
				</div>
					   
				<form action="conf.php" method="post" name="conf" id="conf">
								
					<fieldset>
						<table>    
							<tr><td>Host: </td><td><input type="text" name="host" <?php if (isset($_POST["host"])) echo "value=\"".$_POST["host"]."\""; ?>></td></tr>
							<tr><td>Username: </td><td><input type="text" name="user" <?php if (isset($_POST["user"])) echo "value=\"".$_POST["user"]."\""; ?>></td></tr>
							<tr><td>Password: </td><td><input type="password" name="pw" <?php if (isset($_POST["pw"])) echo "value=\"".$_POST["pw"]."\""; ?>></td></tr>
                            <tr><td>DataBase: </td><td><input type="text" name="db" <?php if (isset($_POST["db"])) echo "value=\"".$_POST["db"]."\""; 
																					else echo "value=\"android2chrome\"";
																					?>></td></tr>
						</table>
												
						<div id="conf_sub"><button name="conferma" value="conferma" type="submit">Conferma</button></div>
						
					</fieldset>
				</form>
			</div>
				
			<div id="footer">
			</div>  
		</div>
	</body>
</html> 
