<?php

	include_once("../system/config.php");

	// Test the input
	if (isset($_POST['probeData']))
	{
		// Set the data and save the xml file
		$data = str_replace('\\', "", $_POST['probeData']);
		$fp = fopen('probe.xml', 'w');
		fwrite($fp, $data);
		fclose($fp);

		// Parse the XML
		$xml = simplexml_load_file("probe.xml");
	    foreach ($xml as $probe)
	    {
	    	// New Probe
			// Instert the probe in database
			$probe_id = addNewProbe($probe['address'], $probe['starttime'], $probe['endtime'], $probe['connectivityType'], $probe['location']);

			foreach ($probe as $router)
			{
				// New Router
				// Inster the router in database
				$router_id = addNewRouter($probe_id, $router['address'], $router['ttl']);

				foreach ($router as $packetmodification)
				{
					// New Packet modification
					$mod_id = addNewPacketModification($router_id, $packetmodification['layer'], $packetmodification['field']);
				}
			}
	    }

		echo "1";
		return 1;
	}
	else
	{
		echo "No input data given";
		return 0;
	}
	

?>