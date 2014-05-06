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

	function addNewRouter($probe_id, $address, $ttl)
	{
		
		mysql_query("INSERT INTO " . DB_PREFIX . "routers (probe_id, address, ttl)
										VALUES ($probe_id, '$address', $ttl)")
										or die (mysql_error());

		return mysql_insert_id();
	}

    function getRoutersCountForProbeID($id)
    {
        $d = mysql_query("SELECT COUNT(*) as count FROM " . DB_PREFIX . "routers WHERE probe_id=$id") or die (mysql_error());
        $d = mysql_fetch_array($d);

        return $d['count'];
    }

    function getRoutersForProbeID($id)
    {
        $d = mysql_query("SELECT * FROM " . DB_PREFIX . "routers WHERE probe_id='$id' ORDER BY ttl ASC") or die (mysql_error());

        return $d;
    }

	function getPacketModificationsCounteForProbeID($id)
	{
		$count = 0;
		$routers = getRoutersForProbeID($id);

		while ($r = mysql_fetch_array($routers))
		{
			$c = getPacketModificationsCountForRouterID($r['id']);
			$count += $c;
		}

		return $c;
		
	}

?>