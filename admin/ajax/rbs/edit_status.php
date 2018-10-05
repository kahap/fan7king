<?php

	header ("content-type: text/HTML; charset=utf-8");
	require_once('../../model/require_login.php');
	
	$rbs = new Recomm_Bonus_Success();
	
	$result = "";
	
	foreach($_POST as $key=>$value){
		$$key = $value;
	}
	
	$rbs->updateStatus($rbsStatus, $rbsNo);
	
	$result="更新成功！";
	
	echo $result;

?>