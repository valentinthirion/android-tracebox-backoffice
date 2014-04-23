<?php

	function addNewRouter($probe_id, $address, $ttl)
	{
		
		mysql_query("INSERT INTO android_tracebox_routers (probe_id, address, ttl)
										VALUES ($probe_id, '$address', $ttl)")
										or die (mysql_error());

		return mysql_insert_id();
	}

    function getRoutersCountForProbeID($id)
    {
        $d = mysql_query("SELECT COUNT(*) as count FROM android_tracebox_routers WHERE probe_id=$id") or die (mysql_error());
        $d = mysql_fetch_array($d);

        return $d['count'];
    }

    function getRoutersForProbeID($id)
    {
        $d = mysql_query("SELECT * FROM android_tracebox_routers WHERE probe_id='$id' ORDER BY ttl ASC") or die (mysql_error());

        return $d;
    }

?>