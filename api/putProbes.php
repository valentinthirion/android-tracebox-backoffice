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

	include_once("../system/config.php");
	connectMysql();

	// Test the input
	if (isset($_POST['probeData']))
	{
		// Set the data and save the xml file
		$data = str_replace('\\', "", $_POST['probeData']);
		$fp = fopen('probe.xml', 'w');
		fwrite($fp, $data);
		fclose($fp);

        if (!file_exists("probe.xml"))
        {
            echo "-1";
            return 0;
        }

		// Parse the XML
		$xml = simplexml_load_file("probe.xml");
	    foreach ($xml as $probe)
	    {
	    	// New Probe
			// Instert the probe in database
			$probe_id = addNewProbe($probe['address'], $probe['starttime'], $probe['endtime'], $probe['connectivityType'], $probe['location'], $probe['carrierName'], $probe['carrierType'], $probe['BatteryDifference']);

			foreach ($probe as $router)
			{
				// New Router
				// Inster the router in database
				$router_id = addNewRouter($probe_id, $router['address'], $router['ttl']);

				foreach ($router as $packetmodification)
				{
					// New Packet modification
					addNewPacketModification($router_id, $packetmodification['layer'], $packetmodification['field']);
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