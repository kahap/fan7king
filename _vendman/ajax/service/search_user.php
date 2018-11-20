<?php
	header('Content-Type: text/html; charset=utf8');
	require_once('../../model/require_login.php');
	
	$seachKey = $_POST["search"];
	
	$member = new Member();
	$all = $member->getAllMember();
	
	$searchData = array();
	$no = array();
	$email = array();
	$name = array();
	$which = 0;
	
	if($seachKey != ""){
		foreach ($all as $key=>$value){
			if(strrpos($value["memNo"],$seachKey) !== false){
				$searchData[$which]["memNo"] = $value["memNo"];
				$searchData[$which]["memName"] = $value["memName"];
				if($value["memSubEmail"] != ""){
					$searchData[$which]["memAccount"] = $value["memSubEmail"];
				}else{
					$searchData[$which]["memAccount"] = $value["memAccount"];
				}
			}else if(strrpos($value["memAccount"],$seachKey) !== false){
				$searchData[$which]["memNo"] = $value["memNo"];
				$searchData[$which]["memName"] = $value["memName"];
				if($value["memSubEmail"] != ""){
					$searchData[$which]["memAccount"] = $value["memSubEmail"];
				}else{
					$searchData[$which]["memAccount"] = $value["memAccount"];
				}
			}else if(strrpos($value["memName"],$seachKey) !== false){
				$searchData[$which]["memNo"] = $value["memNo"];
				$searchData[$which]["memName"] = $value["memName"];
				if($value["memSubEmail"] != ""){
					$searchData[$which]["memAccount"] = $value["memSubEmail"];
				}else{
					$searchData[$which]["memAccount"] = $value["memAccount"];
				}
			}
			$which++;
		}
	}else{
		foreach ($all as $key=>$value){
			$searchData[$which]["memNo"] = $value["memNo"];
			$searchData[$which]["memName"] = $value["memName"];
			if($value["memSubEmail"] != ""){
				$searchData[$which]["memAccount"] = $value["memSubEmail"];
			}else{
				$searchData[$which]["memAccount"] = $value["memAccount"];
			}
			$which++;
		}
	}
		
	echo json_encode($searchData);

?>