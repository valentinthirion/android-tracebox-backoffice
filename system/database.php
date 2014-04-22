<?php

	connectDatabase();

	function connectMysql()
	{
	   /* DEV SERVER
	  MySQL: 
	  Serveur        : 
	  Utilisateur    : 
	  Nom de la base : 
	  Mot de passe   : 
	   */

	   $serveur_sql = 'mysql51-30.perso';
	   $sql_user = 'medineodatab';
	   $sql_pass= '8rKm3Smf';
	   
		/* Connexion SQL */
		mysql_connect($serveur_sql, $sql_user, $sql_pass);
	}

	/*
		This functions will connect to the database
	
		It returns true if no problem occured
		Else it returns false
	*/
	function connectDatabase()
	{
		connectMysql();
		$databaseName = "medineodatab";

		return mysql_select_db($databaseName);
	}

	

	
?>