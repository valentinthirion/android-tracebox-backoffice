<?php
    function addNewDestination($name, $address)
	{											
        $name = mysql_real_escape_string(htmlspecialchars($name));
		$address = mysql_real_escape_string(htmlspecialchars($address));

		return mysql_query("INSERT INTO android_tracebox_destinations 	(name, address)
															    VALUES 	('$name', '$address')")
                                                                or die (mysql_error());	
	}

    function getDestinations()
    {
        $d = mysql_query("SELECT * FROM android_tracebox_destinations") or die (mysql_error());

        return $d;
    }

    function deleteDestination($id)
	{											
        $id = mysql_real_escape_string(htmlspecialchars($id));
        if (!is_numeric($id))
            return false;

        return mysql_query("DELETE FROM android_tracebox_destinations WHERE id=$id") or die (mysql_error());
	}

    function getDestinationForIP($IP)
    {
        $IP = mysql_real_escape_string(htmlspecialchars($IP));

        $d = mysql_query("SELECT * FROM android_tracebox_destinations WHERE address='$IP'") or die (mysql_error());

        return mysql_fetch_array($d);
    }
	

?>