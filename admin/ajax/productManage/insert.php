<?php
	header ("content-type: text/HTML; charset=utf-8");
	require_once('../../model/require_login.php');
	
	$pm = new Product_Manage();
	
	foreach($_POST as $key=>$value){
		$$key = $value;
	}
	
	//錯誤訊息
	$errMsg = array();
	//成功訊息
	$success="";
	
	if(!isset($_POST["proNo"]) || $proNo == ""){
		$errMsg["proNoErr"] = "請選擇上架商品";
	}
	
	if(!isset($_POST["supNo"]) || $supNo == ""){
		$errMsg["supNoErr"] = "請選擇商品供應商";
	}
	
	if(isset($pmSupPrice) && $pmSupPrice == ""){
		$_POST["pmSupPrice"] = 0;
	}
	
	if(isset($pmPeriodAmnt) && $pmPeriodAmnt == ""){
		$_POST["pmPeriodAmnt"] = 0;
	}
	if(isset($pmPeriodAmnt) && $pmPeriodAmnt != "" && !is_numeric($pmPeriodAmnt)){
		$errMsg["pmPeriodAmntErr"] = "請填入數字";
	}
	
	if(isset($pmDirectAmnt) && $pmDirectAmnt == ""){
		$_POST["pmDirectAmnt"] = 0;
	}
	if(isset($pmDirectAmnt) && $pmDirectAmnt != "" && !is_numeric($pmDirectAmnt)){
		$errMsg["pmDirectAmntErr"] = "請填入數字";
	}
	
	if(isset($pmPopular) && $pmPopular == ""){
		$_POST["pmPopular"] = 0;
	}
	if(isset($pmPopular) && $pmPopular != "" && !is_numeric($pmPopular)){
		$errMsg["pmPopularErr"] = "請填入數字";
	}
	
	if(isset($pmUpDate) && $pmUpDate != "" && !preg_match('/(\d{4})-(\d{2})-(\d{2}) (\d{2}):(\d{2}):(\d{2})/',$pmUpDate)){
		$errMsg["pmUpDateErr"] = "日期格式錯誤";
	}

	if(isset($ppIntroText) && $ppIntroText == ""){
		$_POST["ppIntroText"] = null;
	}

	
	//商品利率
	$pp = new Product_Period();
	$ppPeriodAmountArr = isset($_POST["ppPeriodAmount"])? $_POST["ppPeriodAmount"] : '';
	$ppPercentArr = isset($_POST["ppPercent"])? $_POST["ppPercent"]:'';
$ppIntroTextArr = isset($_POST["ppIntroText"])? $_POST["ppIntroText"]:'';
	$proNo = isset($_POST["proNo"])? $_POST["proNo"] : '';
	
	$ppData = $pp->getPPByProduct($proNo);
	if($ppData != null){
		if ($ppPeriodAmountArr)foreach($ppPeriodAmountArr as $key=>$value){
			$ppDataEach = $pp->getPPByProductAndPeriodsAmount($proNo,$value);
			$dataInsert = array();
			$dataInsert["ppNo"] = $ppDataEach[0]["ppNo"];
			$dataInsert["ppPeriodAmount"] = $value;
			$dataInsert["ppPercent"] = $ppPercentArr[$key];
//            $dataInsert["ppIntroText"] = $ppIntroTextArr;
			$pp->update($dataInsert);
		}
	}else{
		if ($ppPeriodAmountArr)foreach($ppPeriodAmountArr as $key=>$value){
			$dataInsert = array();
			$dataInsert["proNo"] = $proNo;
			$dataInsert["ppPeriodAmount"] = $value;
			$dataInsert["ppPercent"] = $ppPercentArr[$key];
//            $dataInsert["ppIntroText"] = $ppIntroTextArr;
			$pp->insert($dataInsert);
		}
	}
	
	//如果沒有錯誤訊息
	if(empty(array_filter($errMsg))){
		unset($_POST["ppPeriodAmount"]);
		unset($_POST["ppPercent"]);
		$update = $pm->insert($_POST);
		$success="新增成功";
	}else{
		$success="新增失敗";
	}
	
	$result = array("success"=>$success,"errMsg"=>$errMsg);
	echo json_encode($result,true);

?>
