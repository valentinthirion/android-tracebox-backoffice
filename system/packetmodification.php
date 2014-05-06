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
		
		mysql_query("INSERT INTO " . DB_PREFIX . "packetmodifications (router_id, layer, field)
										VALUES ($router_id, '$layer', '$field')")
										or die (mysql_error());

		return mysql_insert_id();
	}

    function getPacketModificationsForRouterID($id)
    {
        $d = mysql_query("SELECT * FROM " . DB_PREFIX . "packetmodifications WHERE router_id='$id'") or die (mysql_error());

        return $d;
    }

	function getPacketModificationsCountForRouterID($id)
	{
		$d = mysql_query("SELECT COUNT(*) as count FROM " . DB_PREFIX . "packetmodifications WHERE router_id=$id") or die (mysql_error());
        $d = mysql_fetch_array($d);

        return $d['count'];
	}

?>