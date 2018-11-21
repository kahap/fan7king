<?php
	header (" content-type: text/HTML; charset=utf-8 ");
	require_once('../../model/require_login.php');
	
	$or = new Orders();
	
	date_default_timezone_set('Asia/Taipei');
	$date = date('Y-m-d h:i:s', time());
	
	$orNoArr = $_POST["orNo"];
	$ifVal = $_POST["ifSet"];

	if($ifVal == 1){
		foreach($orNoArr as $key=>$value){
			$or->updateProcessTime($date, $value);
			$or->updateIfProcess(1, $value);
		}
		echo $date;
	}else{
		foreach($orNoArr as $key=>$value){
			$or->updateProcessTime("", $value);
			$or->updateIfProcess(0, $value);
		}
		echo "";
	}
	
	

?>
