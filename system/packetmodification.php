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

	function addNewPacketModification($router_id, $layer, $field)
	{
		if ($layer == "IP")
		{
    		if ($field == "Checksum")
    		{
				return true;
    		}
    		if ($field == "TTL")
    		{
    			return true;
    		}
		}

		return mysql_query("	INSERT
						INTO " . DB_PREFIX . "packetmodifications
						(router_id, layer, field)
						VALUES ($router_id, '$layer', '$field')")
						or die (mysql_error());
	}

    function getPacketModificationsForRouterID($id)
    {
        $d = mysql_query("	SELECT *
        					FROM " . DB_PREFIX . "packetmodifications
        					WHERE router_id='$id'")
        					or die (mysql_error());

        return $d;
    }

	function getPacketModificationsCountForRouterID($id)
	{
    	$d = mysql_query("  SELECT COUNT(*) as count
	                   		FROM " . DB_PREFIX . "packetmodifications
					   		WHERE router_id=$id")
					   		or die (mysql_error());
        $d = mysql_fetch_array($d);

        return $d['count'];
	}


    function getPacketModificationsDistribution()
    {
        $d = mysql_query("  SELECT layer, field, COUNT(*) as count
                            FROM " . DB_PREFIX . "packetmodifications
                            GROUP BY layer, field
                            ORDER BY layer, count")
                            or die (mysql_error());

        return $d;
    }

     function getPacketModificationsDistributionByNetworkMode()
     {
        $d = mysql_query("  SELECT connectivityMode, layer, field, COUNT(*) as count
                            FROM " . DB_PREFIX . "packetmodifications
                            INNER JOIN " . DB_PREFIX . "routers
                            ON " . DB_PREFIX . "routers.id=" . DB_PREFIX . "packetmodifications.router_id
                            INNER JOIN " . DB_PREFIX . "probes
                            ON " . DB_PREFIX . "probes.id=" . DB_PREFIX . "routers.probe_id
                            GROUP BY " . DB_PREFIX . "probes.connectivityMode, layer, field
                            ORDER BY " . DB_PREFIX . "probes.connectivityMode, layer, field")
                            or die (mysql_error());

        return $d;
    }

?>