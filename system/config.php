<?php
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