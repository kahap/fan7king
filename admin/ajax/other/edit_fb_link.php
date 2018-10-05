<?php
	header ("content-type: text/HTML; charset=utf-8");
	require_once('../../model/require_login.php');
	
	$ts = new Other_Setting();
	$origData = $ts->getAll();
	
// 	if(trim($_POST["fbLink"]) == ""){
// 		$_POST["fbLink"] = $origData[0]["fbLink"];
// 	}
	
	$ts->updateFbLink($_POST["fbLink"]);
	
	echo "更新成功";

?>
