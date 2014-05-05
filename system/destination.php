<?php
    function addNewDestination($name, $address)
	{											
        $name = mysql_real_escape_string(htmlspecialchars($name));
		$address = mysql_real_escape_string(htmlspecialchars($address));

		return mysql_query("INSERT INTO " . DB_PREFIX . "destinations 	(name, address)
															    VALUES 	('$name', '$address')")
                                                                or die (mysql_error());	
	}

    function getDestinations()
    {
        $d = mysql_query("SELECT * FROM " . DB_PREFIX . "destinations") or die (mysql_error());

        return $d;
    }

    function deleteDestination($id)
	{											
        $id = mysql_real_escape_string(htmlspecialchars($id));
        if (!is_numeric($id))
            return false;

        return mysql_query("DELETE FROM " . DB_PREFIX . "destinations WHERE id=$id") or die (mysql_error());
	}

    function getDestinationForIP($IP)
    {
        $IP = mysql_real_escape_string(htmlspecialchars($IP));

        $d = mysql_query("SELECT * FROM " . DB_PREFIX . "destinations WHERE address='$IP'") or die (mysql_error());

        return mysql_fetch_array($d);
    }

	function getDestinationForID($ID)
    {
        $ID = mysql_real_escape_string(htmlspecialchars($ID));

        $d = mysql_query("SELECT * FROM " . DB_PREFIX . "destinations WHERE id='$ID'") or die (mysql_error());

        return mysql_fetch_array($d);
    }

	function deleteAllDestinations()
	{
		return mysql_query("DELETE FROM " . DB_PREFIX . "destinations") or die (mysql_error());
	}

	function createCSVWithAllDestinations()
	{
		$text = "";
		$destinations = getDestinations();
		while ($d = mysql_fetch_array($destinations))
		{
			$text .= $d['address'] . "," . $d['name'] . "\n";
		}

		$file = fopen("./destinations.csv", "w");
		fwrite($file, $text);
		fclose($file);
	}

	function createXMLWithAllDestinations()
	{
		$text = "";//"Content-type: text/xml";
		$text .= "<xml version='1.0' encoding='UTF-8'>";
		$text .= "<destinations>";
		
		$destinations = getDestinations();
		while ($d = mysql_fetch_array($destinations))
		{
			$text .= "<destination name=\"" . $d['name'] . "\" address=\"" . $d['address'] . "\"/>";
		}
		$text .= "</destinations>";
		$text .= "</xml>";

		$file = fopen("./destinations.xml", "w");
		fwrite($file, $text);
		fclose($file);
	}
	

?>