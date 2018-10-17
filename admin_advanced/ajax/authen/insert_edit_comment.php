<?php
header('Content-Type: text/html; charset=utf8');
require_once('../../model/require_ajax.php');

// print_r($_POST);
session_start();

$rc = new API("real_cases");

foreach($_POST as $key=>$value){
	$$key = $value;
}

$columnsOrders = array("orReceiveName","orReceiveAddr","orReceivePhone","orReceiveCell","orReceiveComment","orProSpec");
$inputDataOrders = array();

$rcData = $rc->getOne($rcNo);

date_default_timezone_set('Asia/Taipei');
$date = date('Y-m-d H:i:s', time());

if(!is_numeric($rcJustInCaseRatio)){
	$_POST["rcJustInCaseRatio"] = "1";
}
if(!is_numeric($rcProbationRatio)){
	$_POST["rcProbationRatio"] = "1";
}
if(!is_numeric($rcBankRiskFeeMonth)){
	$_POST["rcBankRiskFeeMonth"] = "0";
}
if(!is_numeric($rcBankRiskFeeTotal)){
	$_POST["rcBankRiskFeeTotal"] = "0";
}


if($_SESSION["adminUserData"]["aauNo"] == ""){
	$errMsg[] = "請重新登入，才能處理案件";
}
// $_POST["rcPeriodTotal"] = $eachAmount*$rcPeriodAmount*$rcJustInCaseRatio*$rcProbationRatio;

if($rcData[0]["rcType"] == "0"){
	$_POST["rcPeriodTotal"] = ceil((($rcBankRiskFeeMonth*$rcPeriodAmount)+$rcBankRiskFeeTotal+($eachAmount*$rcPeriodAmount))/$rcPeriodAmount)*$rcPeriodAmount;
	if($rcBankTransferAmount > $_POST["rcPeriodTotal"]){
		$errMsg = "撥款金額不可大於申請總金額。";
	} 
}

foreach($_POST as $key=>$value){
	if(in_array($key,$columnsOrders)){
		$inputDataOrders[$key] = $value;
		unset($_POST[$key]);
	}
}

if(!isset($errMsg)){
	//樂分期若補件
	if($rcStatus == "5"){
		if($rcData[0]["rcType"] == "0"){
			$or = new API("orders");
			$inputDataOrders['orIfEditable'] = "0";
			$inputDataOrders['orPeriodAmnt'] = $_POST["rcPeriodAmount"];
			$inputDataOrders['orPeriodTotal'] = $_POST["rcPeriodTotal"];			
			$or->update($inputDataOrders, $rcData[0]["rcRelateDataNo"]);
			$sf = new API("servicefixed");
			$str = ($_POST['rcDocProvideReason'] == '1') ? $_POST['rcDocProvideComment']:$sf->reasonArr[$_POST['rcDocProvideReason']];
			$sfDataInput = array(
						"rcNo"=>$_POST["rcNo"],
						"orDocProvideReason"=>$str,
						"aauNoService"=>$_SESSION['adminUserData']['aauNo']); 
			$sf->insert($sfDataInput); 
			$app_str = ($_POST['rcDocProvideReason'] == '1') ? "，".$_POST['rcDocProvideComment']:"，".$sf->reasonArr[$_POST['rcDocProvideReason']];
		}else{
			$sf = new API("servicefixed");
			$str = ($_POST['rcDocProvideReason'] == '1') ? $_POST['rcDocProvideComment']:$sf->reasonArr[$_POST['rcDocProvideReason']];
			$sfDataInput = array(
						"rcNo"=>$_POST["rcNo"],
						"orDocProvideReason"=>$str,
						"aauNoService"=>$_SESSION['adminUserData']['aauNo']); 
			$sf->insert($sfDataInput); 
			$app_str = ($_POST['rcDocProvideReason'] == '1') ? "，".$_POST['rcDocProvideComment']:"，".$sf->reasonArr[$_POST['rcDocProvideReason']];
			$moc = new API("motorbike_cellphone_orders");
			$moc->update(array("mcoIfEditable"=>"0"),$rcData[0]["rcRelateDataNo"]);
		}
		$apiSd = new API("service_record");
		$SdDataInput = array(
							"rcNo"=>$rcNo,
							"aauNoService"=>$_SESSION['adminUserData']['aauNo'],
							"content"=>"案件儲存設定為補件" , 
							"time"=>date('Y-m-d H:i:s',time())); 
		$apiSd->insert($SdDataInput);
	}
	
	//若取消訂單
	if($rcStatus == "7" || $rcStatus == "701"){
		if($rcData[0]["rcType"] == "0"){
			$or = new API("orders");
			$or->update(array("orStatus"=>$rcStatus),$rcData[0]["rcRelateDataNo"]);
		}else{
			$moc = new API("motorbike_cellphone_orders");
			$moc->update(array("mcoStatus"=>$rcStatus),$rcData[0]["rcRelateDataNo"]);
		}
		$msg = "案件儲存設定為".$rc->statusArr[$rcStatus];
		$apiSd = new API("service_record");
		$SdDataInput = array(
							"rcNo"=>$rcNo,
							"aauNoService"=>$_SESSION['adminUserData']['aauNo'],
							"content"=>$msg, 
							"time"=>date('Y-m-d H:i:s',time())); 
		$apiSd->insert($SdDataInput);
	}
	
	
	
	/*if($rcStatus == "3"){
		if($rcData[0]["rcType"] == "0"){
			$or = new API("orders");
			$or->update(array("orStatus"=>'8',"orReportPeriod8Date"=>date("Y-m-d H:i:s",time())),$rcData[0]["rcRelateDataNo"]);
		}
	}*/
	
	
	//推播+EMAIL
	if($rcStatus != "2"){
		//樂分期
		if($rcData[0]["rcType"] == "0"){
			//EMAIL
			$or = new API("orders");
				$inputDataOrders['orPeriodAmnt'] = $_POST['rcPeriodAmount'];
				$inputDataOrders['orPeriodTotal'] = $_POST['rcPeriodAmount']*$_POST['eachAmount'];			
			$or->update($inputDataOrders, $rcData[0]["rcRelateDataNo"]);
			$pm = new API("product_manage");
			$pro = new API("product");
			$ad = new API("app_data");
			$adData = $ad->getUniqueDeviceToken($rcData[0]["memNo"]);

			$orData = $or->getOne($rcData[0]["rcRelateDataNo"]);
			$pmData = $pm->getOne($orData[0]["pmNo"]);
			$proData = $pro->getOne($pmData[0]["proNo"]);
			$email = new Email();
			sendEmailForStatChange($rcStatus,$orData,$memData,$pmData,$proData,$email);
			
			//推播
			$msg[0] = "您訂購的商品 - ".$proData[0]["proName"]."，".$or->statusArr[$rcStatus].$app_str;
			$otherInfo[0] = $rcData[0]["rcRelateDataNo"];
			$pushTitle = "訂單狀態更新通知";
			$deviceTokenArr = array();
			$androidRigistIdArr = array();
			if($adData != null){
				$apiIc = new API("inform_center");
				$icDataInput = array(
						"memNo"=>$rcData[0]["memNo"],
						"orNo"=>$otherInfo[0],
						"icTitle"=>$pushTitle,
						"icContent"=>$msg[0],
						"icDate"=>date("Y-m-d H:i:s",time())
				);
				$apiIc->insert($icDataInput);
				foreach($adData as $whichAd=>$valueAd){
					if($valueAd["adDeviceId"] != ""){
						if(strlen($valueAd["adDeviceId"]) <= 65){
							$deviceTokenArr[0][] = $valueAd["adDeviceId"];
						}else{
							$androidRigistIdArr[] = $valueAd["adDeviceId"];
						}
					}
				}
			}
			push_android($androidRigistIdArr,$msg[0],$pushTitle,$otherInfo[0]);
			$push = push("happy.pem",'happy',$deviceTokenArr,$msg,$otherInfo);
		}else{
			$ad = new API("app_data");
			$adData = $ad->getUniqueDeviceToken($rcData[0]["memNo"]);
			//推播
			$msg[0] = "您所申請的 ".$rc->caseTypeArr[$rcData[0]["rcType"]]." - ".$rcData[0]["rcCaseNo"]."，".$rc->statusArr[$rcStatus];
			$otherInfo[0] = $rcData[0]["rcNo"];
			$type[0] = $rcData[0]["rcType"];
			$pushTitle = "訂單狀態更新通知";
			$deviceTokenArr = array();
			$androidRigistIdArr = array();
			if($adData != null){
				$apiIc = new API("inform_center");
				$icDataInput = array(
						"memNo"=>$rcData[0]["memNo"],
						"orNo"=>$otherInfo[0],
						"icType"=>$type[0],
						"icTitle"=>$pushTitle,
						"icContent"=>$msg[0],
						"icDate"=>date("Y-m-d H:i:s",time())
				);
				$apiIc->insert($icDataInput);
				foreach($adData as $whichAd=>$valueAd){
					if($valueAd["adDeviceId"] != ""){
						if(strlen($valueAd["adDeviceId"]) <= 65){
							$deviceTokenArr[0][] = $valueAd["adDeviceId"];
						}else{
							$androidRigistIdArr[] = $valueAd["adDeviceId"];
						}
					}
				}
			}
			push_android($androidRigistIdArr,$msg[0],$pushTitle,$otherInfo[0],$type[0]);
			$push = push("happy.pem",'happy',$deviceTokenArr,$msg,$otherInfo,$type);
			$moto = new API("motorbike_cellphone_orders");
			$inputDataMoc['mcoPeriodTotal'] = $_POST["rcPeriodTotal"];
			$inputDataMoc['mcoMinMonthlyTotal'] = $_POST["mcoMinMonthlyTotal"];
			$inputDataMoc['mcoPeriodAmount'] = $_POST["rcPeriodAmount"];
			$inputDataMoc['mcoDocProvideReason'] = $_POST["rcDocProvideReason"];
			$inputDataMoc['mcoDocProvideComment'] = $_POST["rcDocProvideComment"];
			$moto->update($inputDataMoc, $rcData[0]["rcRelateDataNo"]);
		}
	}
	//人員操作紀錄
	if($rcStatus == "3"){
		$apiSd = new API("service_record");
		$SdDataInput = array(
						"rcNo"=>$rcNo,
						"aauNoService"=>$_SESSION['adminUserData']['aauNo'],
						"content"=>"核准授信儲存案件", 
						"time"=>date('Y-m-d H:i:s',time())); 
		$apiSd->insert($SdDataInput);
	}
	
	if($rcStatus == "2"){
		$apiSd = new API("service_record");
		$SdDataInput = array(
						"rcNo"=>$rcNo,
						"aauNoService"=>$_SESSION['adminUserData']['aauNo'],
						"content"=>"儲存授信案件", 
						"time"=>date('Y-m-d H:i:s',time())); 
		$apiSd->insert($SdDataInput);
	}
	
	if($rcStatus == "4"){
		$apiSd = new API("service_record");
		$msg = "案件儲存設定為".$rc->statusArr[$rcStatus];
		$SdDataInput = array(
						"rcNo"=>$rcNo,
						"aauNoService"=>$_SESSION['adminUserData']['aauNo'],
						"content"=>$msg, 
						"time"=>date('Y-m-d H:i:s',time())); 
		$apiSd->insert($SdDataInput);
	}
	
	
	
	//狀態變更
	$sc = new API("status_comment");
	$scr = new API("status_comment_records");

	$scArr = array();
	$records = $sc->statusArr[$rcStatus]."。";
	$scArr["rcNo"] = $rcNo;
	$scArr["aauNo"] = $_SESSION["adminUserData"]["aauNo"];
	if($scNo != ""){
		foreach($scNo as $key=>$value){
			$scData = $sc->getOne($value);
			$records .= "<br>【".($key+1)."】".$scData[0]["scComment"];
		}
		$scArr["scNo"] = json_encode($scNo,true);
	}
	$scArr["scrInfo"] = $records;
	$scr->insert($scArr);
	 
	unset($_POST["scNo"]);
	unset($_POST["eachAmount"]);
	unset($_POST["mcoMinMonthlyTotal"]);
	$rc->update($_POST, $rcNo);
	echo "OK";
}else{
	echo $errMsg;
}

?>