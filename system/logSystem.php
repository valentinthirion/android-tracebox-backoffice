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

	function connectUser($password)
	{
		if (isset($_SESSION['tracebox_user']))
		{
			if ($_SESSION['tracebox_user'] == "root")
				return true;
		}
		else
		{
			$result = mysql_query("SELECT password FROM " . DB_PREFIX . "settings WHERE id=1") or die(mysql_error());
			if (mysql_num_rows($result) > 0)
			{
				$pass = mysql_fetch_array($result);

				if (hash('sha512', $password) == $pass["password"])
				{
					$_SESSION['tracebox_user'] = "root";
					return true;
				}
				else
					return false;
			}
			return false;
		}
	}

	function disconnectUser()
	{
		return @session_destroy();	
	}

	function isAUserConnected()
	{
		if (isset($_SESSION['tracebox_user']))
			return true;
		else
			return false;
	}

	function setPassword($oldpassword, $newpassword)
	{
		$result = mysql_query("SELECT password FROM " . DB_PREFIX . "settings WHERE id=1") or die(mysql_error());
		$pass = mysql_fetch_array($result);

		if (hash('sha512', $oldpassword) == $pass["password"])
		{
			$newHash = hash('sha512', $newpassword);
			return mysql_query("UPDATE " . DB_PREFIX . "settings SET password='$newHash' WHERE id=1") or die(mysql_error());
			
		}
		return false;
	}

?>