<?php

	function addNewProbe($destination, $starttime, $endtime, $connectivity, $location)
	{
		$starttime = time();
		$endtime = time();
		mysql_query("INSERT INTO android_tracebox_probes 	(destination, starttime, endtime, location, connectivityMode)
															VALUES 	('$destination', $starttime, $endtime, '$location', $connectivity)")
															or die (mysql_error());															

		return mysql_insert_id();
	}

    function getProbes()
    {
        $d = mysql_query("SELECT * FROM android_tracebox_probes ORDER BY starttime DESC") or die (mysql_error());

        return $d;
    }

    function getProbeForID($id)
    {
        $id = mysql_real_escape_string(htmlspecialchars($id));

        $d = mysql_query("SELECT * FROM android_tracebox_probes WHERE id='$id'") or die (mysql_error());

        return mysql_fetch_array($d);
    }

    function deleteProbe($id)
	{											
        $id = mysql_real_escape_string(htmlspecialchars($id));
        if (!is_numeric($id))
            return false;

        return mysql_query("DELETE FROM android_tracebox_probes WHERE id=$id") or die (mysql_error());
	}

?>