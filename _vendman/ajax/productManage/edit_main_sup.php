<?php

	header ("content-type: text/HTML; charset=utf-8 ");
	require_once('../../model/require_login.php');
	
	$pm = new Product_Manage();
	
	$result = "";
	
	foreach($_POST as $key=>$value){
		$$key = $value;
	}
	
	$pmData = $pm->getAllByProName($proNo);
	$orData = $pm->getAllOrderByPm($proNo);
	
	
	//如果沒有錯誤訊息
	if($result == ""){
		foreach($pmData as $key=>$value){
			if($supNo == $value["supNo"]){
				$pm->updateMainSup(1, $value["pmNo"], $supNo);
				// foreach($orData as $keyIn=>$valueIn){
					// $pm->updatePmNoInOrder($value["pmNo"],$valueIn["orNo"],$supNo);
				// }
			}else{
				$pm->updateMainSup(0, $value["pmNo"], $value["supNo"]);
			}
		}
		$result="成功修改主要供應商！ ".$supNo;
	}
	
	echo $result;

?>