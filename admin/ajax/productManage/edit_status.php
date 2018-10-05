<?php

	header ("content-type: text/HTML; charset=utf-8");
	require_once('../../model/require_login.php');
	
	$pm = new Product_Manage();
	
	$result = "";
	
	foreach($_POST as $key=>$value){
		$$key = $value;
	}
	
	
	$pmData = $pm->getAllByProName($proNo);

	if(isset($_POST["pmNewest"])){
		foreach($pmData as $key=>$value){
			$pm->updateNewest($_POST["pmNewest"], $value["pmNo"]);
		}
	}else if(isset($_POST["pmHot"])){
		foreach($pmData as $key=>$value){
			$pm->updateHot($_POST["pmHot"], $value["pmNo"]);
		}
	}else if(isset($_POST["pmSpecial"])){
		foreach($pmData as $key=>$value){
			$pm->updateSpecial($_POST["pmSpecial"], $value["pmNo"]);
		}
	}
	
	$result="更新成功！";
	
	echo $result;

?>