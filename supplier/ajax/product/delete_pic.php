<?php
	require_once('../../model/require_login.php');
	
	$pro = new Product();
	
	$array = array();
	
	foreach($_POST as $key=>$value){
		$$key = $value;
	}
	
	$data = $pro->getOneProByNo($proNo);
	$imgArr = json_decode($data[0]["proImage"]);
	
	if(($key = array_search($index, $imgArr)) !== false) {
		unset($imgArr[$key]);
	}
	
	foreach($imgArr as $value){
		array_push($array, $value);
	}
	
	$pro->updateImg(json_encode($array,JSON_UNESCAPED_UNICODE), $proNo);
	echo "刪除成功";
	
	
?>