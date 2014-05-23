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
		
		mysql_query("			INSERT
								INTO " . DB_PREFIX . "routers
								(probe_id, address, ttl)
								VALUES ($probe_id, '$address', $ttl)")
								or die (mysql_error());

		return mysql_insert_id();
	}

    function getRoutersCountForProbeID($id)
    {
        $d = mysql_query("		SELECT COUNT(*) as count
        						FROM " . DB_PREFIX . "routers
        						WHERE probe_id=$id") or die (mysql_error());
        $d = mysql_fetch_array($d);

        return $d['count'];
    }

    function getRoutersForProbeID($id)
    {
        $d = mysql_query("		SELECT *
        						FROM " . DB_PREFIX . "routers
        						WHERE probe_id='$id'
        						ORDER BY ttl ASC")
        						or die (mysql_error());

        return $d;
    }

	function getPacketModificationsCountForProbeID($id)
	{
		$d = mysql_query("	SELECT COUNT(*) as count
							FROM " . DB_PREFIX . "routers
							INNER JOIN " . DB_PREFIX . "packetmodifications
							ON " . DB_PREFIX . "routers.id=" . DB_PREFIX . "packetmodifications.router_id
							WHERE " . DB_PREFIX . "routers.probe_id=$id")
							or die (mysql_error());

		$c = mysql_fetch_array($d);
		return $c['count'];
	}

    function getModificationsForProbe($id)
    {
        $d = mysql_query("  SELECT DISTINCT layer, field
							FROM " . DB_PREFIX . "packetmodifications
							INNER JOIN " . DB_PREFIX . "routers
							ON " . DB_PREFIX . "routers.id=" . DB_PREFIX . "packetmodifications.router_id
							WHERE " . DB_PREFIX . "routers.probe_id=$id")
							or die (mysql_error());

        return $data;
    }

    function getDistinctRouters()
    {
        $d = mysql_query("  SELECT DISTINCT address
                            FROM " . DB_PREFIX . "routers
                            INNER JOIN " . DB_PREFIX . "probes
                            ON " . DB_PREFIX . "probes.id=" . DB_PREFIX . "routers.probe_id")
                            or die (mysql_error());

        return $d;
    }

?>