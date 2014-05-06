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
	//error_reporting(E_ERROR | E_WARNING | E_PARSE | E_NOTICE);

	include_once("database.php");
	include_once("logSystem.php");
	include_once("settings.php");

    include_once("destination.php");
	include_once("probe.php");
	include_once("router.php");
	include_once("packetmodification.php");

	// DB Prefix
	$settings = getDatabaseSettings();
	define("DB_PREFIX", $settings['sql_prefix']);

?>