<?php
	header ("content-type: text/HTML; charset=utf-8");
	require_once('../../model/require_login.php');
	
	$ts = new Other_Setting();
	
	$ts->updateTextSwitch($_POST["textSwitch"]);
	
	echo "更新成功";

?>
