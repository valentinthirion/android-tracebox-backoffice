<?php

	function addNewProbe($destination, $starttime, $endtime, $connectivity, $location)
	{
		$starttime = time();
		$endtime = time();
		mysql_query("INSERT INTO android_tracebox_probes 	(destination, starttime, endtime, location, connectivityMode)
															VALUES 	('$destination', $starttime, $endtime, '$location', $connectivity)")
															or die (mysql_error());															

		return mysql_insert_id();
	}

?>