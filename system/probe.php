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
		$starttime = strtotime($starttime);
		$endtime = strtotime($endtime);
		mysql_query("INSERT INTO " . DB_PREFIX . "probes 	(destination, starttime, endtime, location, connectivityMode, carrierName, carrierType, batteryUsage)
															VALUES 	('$destination', $starttime, $endtime, '$location', $connectivity, '$carrierName', '$carrierType', $batteryUsage)")
															or die (mysql_error());															

		return mysql_insert_id();
	}

    function getProbes($max)
    {
    	$d;
    	if ($max != "")
	        $d = mysql_query("SELECT * FROM " . DB_PREFIX . "probes ORDER BY starttime DESC LIMIT 0, $max") or die (mysql_error());
	    else
	    	$d = mysql_query("SELECT * FROM " . DB_PREFIX . "probes ORDER BY starttime DESC") or die (mysql_error());

        return $d;
    }

    function getProbesWithNumberOfRouters($max)
    {
        $d;
    	if ($max != "")
	        $d = mysql_query("  SELECT *, count(" . DB_PREFIX . "routers.id) as rNumber, " . DB_PREFIX . "probes.id as pID
                                FROM " . DB_PREFIX . "probes
                                INNER JOIN
                                " . DB_PREFIX . "routers
                                ON " . DB_PREFIX . "probes.id=" . DB_PREFIX . "routers.probe_id
                                GROUP BY " . DB_PREFIX . "probes.id
                                ORDER BY rNumber DESC
                                LIMIT 0, $max")
                                or die (mysql_error());
	    else
	    	$d = mysql_query("  SELECT *, count(" . DB_PREFIX . "routers.id) as rNumber, " . DB_PREFIX . "probes.id as pID
                                FROM " . DB_PREFIX . "probes
                                INNER JOIN
                                " . DB_PREFIX . "routers
                                ON " . DB_PREFIX . "probes.id=" . DB_PREFIX . "routers.probe_id
                                GROUP BY " . DB_PREFIX . "probes.id
                                ORDER BY rNumber DESC")
                                or die (mysql_error());

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

	function deleteAllEmptyProbes()
	{
		$probes = getProbes("");
		while ($probe = mysql_fetch_array($probes))
		{
			$nbOfRouters = getRoutersCountForProbeID($probe['id']);
			if ($nbOfRouters == 0)
				deleteProbe($probe['id']);
		}
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

    function getProbesWithNumberOfHops()
    {
        $d = mysql_query("  SELECT destination, connectivityMode, COUNT(*) as nb_of_routers
                            FROM " . DB_PREFIX . "routers
                            INNER JOIN " . DB_PREFIX . "probes
                            ON " . DB_PREFIX . "probes.id=" . DB_PREFIX . "routers.probe_id
                            GROUP BY " . DB_PREFIX . "routers.probe_id
                            ORDER BY connectivityMode, " . DB_PREFIX . "probes.starttime")
                            or die (mysql_error());

        return $d;
    }

    function getNumberOfProbesWithSameNumberOfRouters()
    {
        $d = mysql_query("  SELECT COUNT(" . DB_PREFIX . "routers.id) as nbRouters, connectivityMode, " . DB_PREFIX . "probes.id as id
                            FROM " . DB_PREFIX . "routers
                            LEFT JOIN " . DB_PREFIX . "probes
                            ON " . DB_PREFIX . "probes.id = " . DB_PREFIX . "routers.probe_id
                            GROUP By " . DB_PREFIX . "probes.id, connectivityMode
                            ORDER BY connectivityMode
                        ")
                        or die (mysql_error());
        
        return $d;
    }

    function getRawProbeResult()
    {
        $d = mysql_query("  SELECT
        						" . DB_PREFIX . "probes.id as pID,
                                destination,
                                location,
                                starttime,
                                endtime,
                                connectivityMode,
                                batteryUsage,
                                carrierType,
                                carrierName,
                                ttl,
                                address,
                                layer,
                                field
                            FROM " . DB_PREFIX . "probes
                            RIGHT JOIN
                                (SELECT
                                    " . DB_PREFIX . "routers.probe_id as pID,
                                    ttl,
                                    address,
                                    layer,
                                    field                            
                                FROM " . DB_PREFIX . "packetmodifications
                                INNER JOIN " . DB_PREFIX . "routers
                                ON " . DB_PREFIX . "routers.id= " . DB_PREFIX . "packetmodifications.router_id
                                ) r1
                            ON
                            " . DB_PREFIX . "probes.id = r1.pID")
                            or die (mysql_error());
		
		return $d;
    }

	function getCarriersAndTypes()
	{
		$d = mysql_query("	SELECT
								carrierType, carrierName, count(id) as count
							FROM " . DB_PREFIX . "probes
							GROUP BY carrierType, carrierName
						")
						or die (mysql_error());

		return $d;
	}

    function getProbesLocation()
    {
        $d = mysql_query("	SELECT DISTINCT location, count(id) as count, id
                            FROM " . DB_PREFIX . "probes
                            WHERE location!='0.0/0.0'
                            GROUP BY location
						")
						or die (mysql_error());

		return $d;
    }

    function converCoordinatesToLocale($coord)
    {
        sleep(0.1);
        $c = split("/", $coord);            
        $lat = $c[0];
        $long = $c[1];
        $url = 'http://maps.google.com/maps/api/geocode/json?latlng='. $lat . ',' . $long . '&sensor=false';
        $geocode = file_get_contents($url);
        $output = json_decode($geocode);
        $address = $output->results[0]->formatted_address;//address_components[0]->country;

        return $address;
    }

    function getConnetionModes()
    {
        $d = mysql_query("	SELECT DISTINCT connectivityMode, count(id) as count
                            FROM " . DB_PREFIX . "probes
                            GROUP BY connectivityMode
						")
						or die (mysql_error());

		return $d;
    }
?>