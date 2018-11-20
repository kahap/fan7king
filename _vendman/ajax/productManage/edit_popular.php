<?php

	header ("content-type: text/HTML; charset=utf-8 ");
	require_once('../../model/require_login.php');
	
	$pm = new Product_Manage();
	
	$pmPopu = $_POST["pmPopular"];
	
	$result = "";
	
	foreach($_POST as $key=>$value){
		$$key = $value;
	}
	
	$pmData = $pm->getAllByProName($proNo);
	
	if($pmPopu == ""){
		$pmPopu = 0;
	}else{
		if(!is_numeric($pmPopu)){
			$result = "只可以輸入數字";
		}
	}
	
	//如果沒有錯誤訊息
	if($result == ""){
		foreach($pmData as $key=>$value){
			$pm->updatePopular($pmPopu, $value["pmNo"]);
		}
		$result="更新成功！ ".$pmPopu;
	}
	
	echo $result;

?>