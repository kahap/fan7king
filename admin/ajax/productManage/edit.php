<?php
	header ("content-type: text/HTML; charset=utf-8");
	require_once('../../model/require_login.php');
	
	$pm = new Product_Manage();
	
	//原始資料
	$origData = $pm->getAllByProNameAndGroup($_POST["proNo"]);
	
	$origDataNoGroup = $pm->getAllByProName($_POST["proNo"]);
	
	//若空白就輸入原始資料
	foreach($_POST as $key=>$value){
// 		if(trim($value) == ""){
// 			$_POST[$key] = $origData[0][$key];
// 		}
		$$key = $value;
	}
	
	//錯誤訊息
	$errMsg = array();
	//成功訊息
	$success="";
	
	if($pmPeriodAmnt != "" && !is_numeric($pmPeriodAmnt)){
		$errMsg["pmPeriodAmntErr"] = "請填入數字";
	}
	
	if($pmDirectAmnt != "" && !is_numeric($pmDirectAmnt)){
		$errMsg["pmDirectAmntErr"] = "請填入數字";
	}
	
	if($pmPopular != "" && !is_numeric($pmPopular)){
		$errMsg["pmPopularErr"] = "請填入數字";
	}
	
	if($pmUpDate != "" && !preg_match('/(\d{4})-(\d{2})-(\d{2}) (\d{2}):(\d{2}):(\d{2})/',$pmUpDate)){
		$errMsg["pmUpDateErr"] = "日期格式錯誤";
	}
	if($pmUpDate != $origData[0]["pmUpDate"]){
		$_POST["newDate"] = $pmUpDate;
	}

	if($ppIntroText == ""){
		$_POST["ppIntroText"] = null;
	}


//商品利率
	$pp = new Product_Period();
	$ppPeriodAmountArr = $_POST["ppPeriodAmount"];
	$ppPercentArr = $_POST["ppPercent"];
	$ppIntroTextArr = isset($_POST["ppIntroText"])?$_POST["ppIntroText"]:[];
	$proNo = $_POST["proNo"];
	
	$ppData = $pp->getPPByProduct($proNo);
	if($ppData != null){
		foreach($ppPeriodAmountArr as $key=>$value){
			$ppDataEach = $pp->getPPByProductAndPeriodsAmount($proNo,$value);
			$dataInsert = array();
			$dataInsert["ppNo"] = $ppDataEach[0]["ppNo"];
			$dataInsert["ppPeriodAmount"] = $value;
			$dataInsert["ppPercent"] = $ppPercentArr[$key];
			$dataInsert["ppIntroText"] = $ppIntroTextArr[$key];
			$pp->update($dataInsert);
		}
	}else{
		foreach($ppPeriodAmountArr as $key=>$value){
			$dataInsert = array();
			$dataInsert["proNo"] = $proNo;
			$dataInsert["ppPeriodAmount"] = $value;
			$dataInsert["ppPercent"] = $ppPercentArr[$key];
			$dataInsert["ppIntroText"] = $ppIntroTextArr[$key];
			$pp->insert($dataInsert);
		}
	}
	
	//如果沒有錯誤訊息
	if(empty(array_filter($errMsg))){
		unset($_POST["ppPeriodAmount"]);
		unset($_POST["ppPercent"]);
		$update = $pm->update($_POST,$_POST["proNo"]);
		$success="更新成功";
	}
	
	$result = array("success"=>$success,"errMsg"=>$errMsg);
	echo json_encode($result,true);

?>
