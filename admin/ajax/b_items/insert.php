<?php
	header('Content-Type: text/html; charset=utf8');
	require_once('../../model/require_login.php');
	
	$items = new B_items();
	
	//錯誤訊息
	$errMsg = array();
	//成功訊息
	$success="";
	
	//最後一筆
	$itemsData = $items->getAllItemsDesc();
	if (isset($itemsData)) {
        if ($itemsData[0]["biNo"] < 9) {
            $itemsNo = "0" . ++$itemsData[0]["biNo"];
        } else {
            $itemsNo = ++$itemsData[0]["biNo"];
        }
    } else {
        $itemsNo = '01';
	}
	
	foreach($_POST as $key=>$value){
		$$key = $value;
	}
	
	//驗證
	if($biName == ""){
		$errMsg["biNameErr"] = "品牌名稱不可為空白。";
	}
	
	//結果輸出
	if(empty(array_filter($errMsg))){
		$insert = $items->insert($_POST,$itemsNo);
		$success = "新增成功";
	}
	
	$result = array("success"=>$success,"errMsg"=>$errMsg);
	echo json_encode($result,true);
?>
