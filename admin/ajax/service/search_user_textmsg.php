<?php
	header('Content-Type: text/html; charset=utf8');
	require_once('../../model/require_login.php');
	
	$seachKey = $_POST["search"];
	
	$member = new Member();
	$all = $member->getAllMember();
	
	$searchData = array();
	$no = array();
	$cell = array();
	$name = array();
	$which = 0;
	
	if($seachKey != ""){
		foreach ($all as $key=>$value){
			if(strrpos($value["memNo"],$seachKey) !== false){
				$searchData[$which]["memNo"] = $value["memNo"];
				$searchData[$which]["memName"] = $value["memName"];
				$searchData[$which]["memCell"] = $value["memCell"];
			}else if(strrpos($value["memCell"],$seachKey) !== false){
				$searchData[$which]["memNo"] = $value["memNo"];
				$searchData[$which]["memName"] = $value["memName"];
				$searchData[$which]["memCell"] = $value["memCell"];
			}else if(strrpos($value["memName"],$seachKey) !== false){
				$searchData[$which]["memNo"] = $value["memNo"];
				$searchData[$which]["memName"] = $value["memName"];
				$searchData[$which]["memCell"] = $value["memCell"];
			}
			$which++;
		}
	}else{
		foreach ($all as $key=>$value){
			$searchData[$which]["memNo"] = $value["memNo"];
			$searchData[$which]["memName"] = $value["memName"];
			$searchData[$which]["memCell"] = $value["memCell"];
			$which++;
		}
	}
		
	echo json_encode($searchData,true);

?>