<?php
	session_start();
	header ("content-type: text/HTML; charset=utf-8");
	require_once('../../model/require_login.php');

	$or = new Orders();
	$pm = new Product_Manage();
	$pro = new Product();
	
	//原始資料
	$origData = $or->getOneOrderByNo($_POST["orNo"]);
	
	
	//若空白就輸入原始資料
// 	foreach($_POST as $key=>$value){
// 		if($value == ""){
// 			$_POST[$key] = $origData[0][$key];
// 		}
// 	}

	if($_POST["orStatus"] != $origData[0]["orStatus"]){
		$or->updateStatusTime($_POST["orStatus"],$origData[0]["orNo"]);
		$or->updateIfProcess(0, $origData[0]["orNo"]);
		$or->updateProcessTime("", $origData[0]["orNo"]);
	}
	
	if($_POST["orStatus"] == 5){
		$or->updateIfEditable(0, $origData[0]["orNo"]);
	}else{
		$or->updateIfEditable(1, $origData[0]["orNo"]);
	}
	
	//如果不需要統一發票
	if($_POST["orBusinessNumIfNeed"] != 1){
		$_POST["orBusinessNumNumber"] = "";
		$_POST["orBusinessNumTitle"] = "";
	}
	
	$updateBirth = $or->updateBirth($_POST["orAppApplierBirthAddr"],$_POST["orAppApplierBirthPhone"],$origData[0]["memNo"]);
	$updateCurrent = $or->updateCurrent($_POST["memAddr"],$_POST["memPhone"],$origData[0]["memNo"]);
	$update = $or->update($_POST, $_POST["orNo"]);
	
	if($_POST["supNo"] != $origData[0]["supNo"]){
		$or->updaterealcase($_POST["supNo"], $_POST["orNo"]);
	}
	//同步real_case->rcBankTransferAmount
	$or->updaterealcaseBankTransferAmount($_POST["orSupPrice"], $_POST["orNo"]);

	
	$sql = "select rcNo from real_cases where rcRelateDataNo = '".$_POST["orNo"]."' && rcType = '0' ";
	$data = $or->getSql($sql);
	$Record = "insert into service_record (rcNo,aauNoService,content,time) value('".$data['0']['rcNo']."','".$_SESSION['userdata']['smName']."','使用Edit狀態由".($or->statusArr[$origData[0]["orStatus"]])."變成".($or->statusArr[$_POST["orStatus"]])."','".date('Y-m-d H:i:s',time())."')";
	$or->getSql($Record);
	echo "更新成功";
	
?>
