<?php

	function addNewPacketModification($router_id, $layer, $field)
	{
		
		mysql_query("INSERT INTO android_tracebox_packetmodifications (router_id, layer, field)
										VALUES ($router_id, '$layer', '$field')")
										or die (mysql_error());

		return mysql_insert_id();
	}

    function getPacketModificationsForRoiterID($id)
    {
        $d = mysql_query("SELECT * FROM android_tracebox_packetmodifications WHERE router_id='$id'") or die (mysql_error());

        return $d;
    }

?>