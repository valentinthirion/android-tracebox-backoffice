<?php

	function getAPIPrefix()
	{
		$result = mysql_query("SELECT api_prefix FROM android_tracebox_settings WHERE id=1") or die(mysql_error());
		$data = mysql_fetch_array($result);
		return $data['api_prefix'];
	}

	function setAPIPrefix($newPrefix)
	{
		$newPrefix = mysql_real_escape_string(htmlspecialchars($newPrefix));
		return mysql_query("UPDATE android_tracebox_settings SET api_prefix='$newPrefix' WHERE id=1") or die(mysql_error());
	}
?>