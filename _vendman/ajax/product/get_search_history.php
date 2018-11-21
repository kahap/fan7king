<?php
	header ("content-type: text/HTML; charset=utf-8 ");
	require_once('../../model/require_login.php');
	
	$pro = new Product();
	$query = $_POST["query"];
	
	$proData = $pro->getAllProByLikeName($query);
	
	$imgArr = array();
	
	if($proData != null){
		foreach($proData as $key=>$value){
			array_push($imgArr, json_decode($value["proImage"]));
		}
	}else{
		array_push($imgArr,"empty");
	}
	
	echo json_encode($imgArr,JSON_UNESCAPED_UNICODE);
	
?>