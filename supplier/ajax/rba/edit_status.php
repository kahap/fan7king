<?php

	header (" content-type: text/HTML; charset=utf-8 ");
	require_once('../../model/require_login.php');
	
	$rba = new Recomm_Bonus_Apply();
	
	$result = "";
	
	foreach($_POST as $key=>$value){
		$$key = $value;
	}
	
	$rba->updateStatus($rbaStatus, $rbaNo);
	
	$curRbaData = $rba->getRBAByStatus(1);
	
	$result="更新成功！ ".sizeof($curRbaData);
	
	echo $result;

?>