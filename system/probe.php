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

	function addNewProbe($destination, $starttime, $endtime, $connectivity, $location, $carrierName, $carrierType, $batteryUsage)
	{
		$starttime = time();
		$endtime = time();
		mysql_query("INSERT INTO " . DB_PREFIX . "probes 	(destination, starttime, endtime, location, connectivityMode, carrierName, carrierType, batteryUsage)
															VALUES 	('$destination', $starttime, $endtime, '$location', $connectivity, '$carrierName', '$carrierType', $batteryUsage)")
															or die (mysql_error());															

		return mysql_insert_id();
	}

    function getProbes($max)
    {
        $d = mysql_query("SELECT * FROM " . DB_PREFIX . "probes ORDER BY starttime DESC LIMIT 0, $max") or die (mysql_error());

        return $d;
    }

    function getProbeForID($id)
    {
        $id = mysql_real_escape_string(htmlspecialchars($id));

        $d = mysql_query("SELECT * FROM " . DB_PREFIX . "probes WHERE id='$id'") or die (mysql_error());

        return mysql_fetch_array($d);
    }

    function deleteProbe($id)
	{											
        $id = mysql_real_escape_string(htmlspecialchars($id));
        if (!is_numeric($id))
            return false;

        return mysql_query("DELETE FROM " . DB_PREFIX . "probes WHERE id=$id") or die (mysql_error());
	}

	 function getProbesForDestination($IP)
    {
        $IP = mysql_real_escape_string(htmlspecialchars($IP));

        $d = mysql_query("SELECT * FROM " . DB_PREFIX . "probes WHERE destination='$IP'") or die (mysql_error());

        return $d;
    }

	function deleteAllProbes()
	{
		return mysql_query("DELETE FROM " . DB_PREFIX . "probes") or die (mysql_error());
	}

	function getProbesCount()
    {
        $d = mysql_query("SELECT COUNT(*) as count FROM " . DB_PREFIX . "probes") or die (mysql_error());
        $d = mysql_fetch_array($d);

        return $d['count'];
    }

	function createXMLWithProbes()
	{
		$text = "";
		$text .= "<xml version='1.0' encoding='UTF-8'>";
		$text .= "<probes>";
		
		$probes = getProbes(getProbesCount());
		while ($p = mysql_fetch_array($probes))
		{
			$text .= "<probe address=\"" . $p['address']. "\" "
			. "starttime=\"" . $p['starttime'] . "\" endtime=\"" . $p['endtime'] . "\" "
			. "connectivityMode=\"" . $p['connectivityMode'] . "\" "
			. "carrierName=\"" . $p['carrierName'] . "\" " . "carrierType=\"" . $p['carrierType'] . "\" "
			. "location=\"" . $p['location'] . "\" >";

			$routers = getRoutersForProbeID($p['id']);
			while ($router = mysql_fetch_array($routers))
			{
				$text .= "<router address=\"" . $router['address'] . "\" ttl=\"" . $router['ttl'] . "\">";
				$modifications = getPacketModificationsForRouterID($router['id']);
				while ($m = mysql_fetch_array($modifications))
				{
					$text .= "<packetmodification layer=\"" . $m['layer'] . "\" field=\"" . $m['field'] . "\" />";
				}
				$text .= "</router>";
			}

			$text .= "</probe>";
			
		}

		$text .= "</probes>";
		$text .= "</xml>";

		$file = fopen("./probes.xml", "w");
		fwrite($file, $text);
		fclose($file);
	}

?>