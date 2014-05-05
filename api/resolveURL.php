<?php
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