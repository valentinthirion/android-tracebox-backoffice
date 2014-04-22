<?php

	function addNewRouter($probe_id, $address, $ttl)
	{
		
		mysql_query("INSERT INTO android_tracebox_routers (probe_id, address, ttl)
										VALUES ($probe_id, '$address', $ttl)")
										or die (mysql_error());

		return mysql_insert_id();
	}

?>