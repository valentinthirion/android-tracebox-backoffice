<?php

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