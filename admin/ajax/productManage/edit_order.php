<?php

	header ("content-type: text/HTML; charset=utf-8");
	require_once('../../model/require_login.php');
	
	$pm = new Product_Manage();
	
	$result = "更新失敗";
	
	foreach($_POST as $key=>$value){
		$$key = $value;
	}
	
	foreach($proNo as $key=>$value){
		$pmData = $pm->getAllByProName($value,0,9999999);
		foreach($pmData as $keyIn=>$valueIn){
			if(isset($pmNewestOrder)){
				$pm->updateOrder("pmNewestOrder", $pmNewestOrder[$key], $valueIn["pmNo"]);
				$pm->updateNewest($pmNewest[$key], $valueIn["pmNo"]);
			}else if(isset($pmHotOrder)){
				$pm->updateOrder("pmHotOrder", $pmHotOrder[$key], $valueIn["pmNo"]);
				$pm->updateHot($pmHot[$key], $valueIn["pmNo"]);
			}else if(isset($pmSpecialOrder)){
				$pm->updateOrder("pmSpecialOrder", $pmSpecialOrder[$key], $valueIn["pmNo"]);
				$pm->updateSpecial($pmSpecial[$key], $valueIn["pmNo"]);
			}
		}
		$result = "更新成功";
	}
	
	
	echo $result;

?>