<?php

	/*
		Tracebox for Android has been developed by Valentin THIRION
		in the context of his Master Thesis
		at the University of Liege (ULg) in Belgium in june 2014.
		This work has been partially funded by the
		European Commission funded mPlane ICT-318627 project
		(http://www.ict-mplane.eu).

		All information, copyrights and code about
		this project can be found at: www.androidtracebox.com
	*/

	function getDatabaseSettings()
	{
		if (file_exists("system_config.php"))
		{
			$ini_array = parse_ini_file("system_config.php");
			return $ini_array;
		}
		$ini_array = parse_ini_file("../system_config.php");
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
		

		$file = fopen("system_config.php", "w");
		fwrite($file, $text);
		fclose($file);

		return true;
	}	

	

	
?>