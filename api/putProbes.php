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
	    	$destination = $probe['address'];
	    	$starttime = $probe['destination'];
	    	$endtime = $probe['endtime'];
	    	$connectivity = $probe['connectivityType'];
	    	$location = $probe['location'];

			// Instert the probe in database
			$probe_id = addNewProbe($destination, $starttime, $endtime, $connectivity, $location);

			foreach ($probe as $router)
			{
				// New Router
				$address = $router['address'];
				$ttl = $router['ttl'];

				// Inster the router in database
				$router_id = addNewRouter($probe_id, $address, $ttl);

				foreach ($router as $packetmodification)
				{
					// New Packet modification
					$layer = $packetmodification['layer'];
					$field = $packetmodification['field'];

					$mod_id = addNewPacketModification($router_id, $layer, $field);
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