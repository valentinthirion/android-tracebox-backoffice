<?php

	function getDatabaseSettings()
	{
		if (file_exists("system_config.ini"))
		{
			$ini_array = parse_ini_file("system_config.ini");
			return $ini_array;
		}
		$ini_array = parse_ini_file("../system_config.ini");
		return $ini_array;
	}

	function connectMysql()
	{
	   $databaseSettings = getDatabaseSettings();
	   
		/* Connexion SQL */
		if (($databaseSettings['sql_server'] != "") && ($databaseSettings['sql_username'] != "") && ($databaseSettings['sql_pass'] != "") && ($databaseSettings['sql_database'] != ""))
			return mysql_connect($databaseSettings['sql_server'], $databaseSettings['sql_username'], $databaseSettings['sql_pass']) && mysql_select_db($databaseSettings['sql_database']);
		else
			return false;
	}

	function setDatabaseSettings($server, $database, $user, $pass, $prefix)
	{
		//$serveur_sql = 'mysql51-30.perso';
		 // db = 'medineodatab'
		//$sql_user = 'medineodatab';
		//$sql_pass= '8rKm3Smf';

		$server = htmlspecialchars($server);
		$database = htmlspecialchars($database);
		$user = htmlspecialchars($user);
		$pass = htmlspecialchars($pass);
		$prefix = htmlspecialchars($prefix);

		$text = "";
		$text .= "sql_server='" . $server . "'\n";
		$text .= "sql_database='" . $database . "'\n";
		$text .= "sql_username='" . $user . "'\n";
		$text .= "sql_pass='" . $pass . "'\n";
		$text .= "sql_prefix='" . $prefix . "'\n";
		

		$file = fopen("system_config.ini", "w");
		fwrite($file, $text);
		fclose($file);

		return true;
	}	

	

	
?>