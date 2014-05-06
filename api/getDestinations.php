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

	include_once("../system/config.php");
	connectMysql();

	$result = mysql_query("SELECT * FROM android_tracebox_destinations ORDER BY id") or die (mysql_error());

	header('Content-type: text/xml');
	// Echo text
	echo "<xml version=\"1.0\" encoding=\"UTF-8\">";
		echo "<destinations>";
			while ($dest = mysql_fetch_array($result))
			{
				echo "<destination name=\"" . $dest['name'] . "\" address=\"" . $dest['address'] . "\"/>";
			}
		echo "</destinations>";
	echo "</xml>";

?>