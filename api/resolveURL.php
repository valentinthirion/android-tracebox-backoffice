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
	if (isset($_POST['url']))
	{
		// Resolve the given URL and get the IP back
		$IP = gethostbyname($_POST['url']);
		if ($IP == $_POST['url'])
		{
			echo "-1";
			return;
		}
		else
		{
			echo $IP;
			return 1;
		}
	}
	else
	{
		echo "-1";
		return 0;
	}

?>