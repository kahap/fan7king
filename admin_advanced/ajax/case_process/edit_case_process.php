<?php
header('Content-Type: text/html; charset=utf8');
require_once('../../model/require_ajax.php');

session_start();

// print_r($_POST);

$apiMem = new API("member");
$apiOr = new API("real_cases");
$or = new API("orders");
$moto = new API("motorbike_cellphone_orders");
$rcData = $apiOr->getOne($_POST["rcNo"]);
$memData = $apiMem->getOne($rcData[0]["memNo"]);

$memColumns = array("memNo","memName","memSchool","memGender","memBday","memIdNum","memAccount","memPhone","memAddr","memPostCode");
$memInputData = array();

$columnsOr = array("rcNo","rcBirthAddr","rcStatus","rcDocProvideReason","rcDocProvideComment","rcBirthAddrPostCode","rcBirthPhone","rcPosition");
$inputDataOr = array();

$columnsOrders = array("orReceiveName","orReceiveAddr","orReceivePhone","orReceiveCell","orReceiveComment");
$inputDataOrders = array();

$columnsMoc = array("mcoApplyPurpose","mcoMotorBrand","mcoCcNumber","mcoMotorbikeSpec","mcoCarNum","mcoYear","mcoCellBrand","mcoCellphoneSpec","mcoPeriodTotal","mcoPeriodAmount","mcoMinMonthlyTotal");
$inputDataMoc = array();

//學校系及分開
if($memData[0]["memClass"] == "0"){
	$mustFill = array("memName","school","major","year","memGender","memBday","memIdNum");
}else{
	$mustFill = array("memName","memGender","memBday","memIdNum");
}

//檢查必填
foreach($mustFill as $key=>$value){
	if(!isset($_POST[$value]) || $_POST[$value] == ""){
		$errMsg[] = "請確認已填寫所有欄位。";
	}
}

if($_SESSION["adminUserData"]["aauNo"] == ""){
	$errMsg[] = "請重新登入，才能處理案件";
}

if(!isset($errMsg)){
	$_POST["memSchool"] = $_POST["school"]."#".$_POST["major"]."#".$_POST["year"];
	
	//篩選資料
	foreach($_POST as $key=>$value){
		if(in_array($key,$memColumns)){
			$memInputData[$key] = $value;
		}
	}
	foreach($_POST as $key=>$value){
		if(in_array($key,$columnsOr)){
			$inputDataOr[$key] = $value;
		}
	}
	
	switch($rcData[0]["rcType"]){
		case "0":
			foreach($_POST as $key=>$value){
			if(in_array($key,$columnsOrders)){
					$inputDataOrders[$key] = $value;
				}
			}
			$strDate = explode("-",$_POST['rcIdIssueDate']);
			$inputDataOrders['orIdIssueYear'] = $strDate[0];
			$inputDataOrders['orIdIssueMonth'] = $strDate[1];
			$inputDataOrders['orIdIssueDay'] = $strDate[2];
			$inputDataOrders['orIdIssuePlace'] = $_POST['rcIdIssuePlace'];
			$inputDataOrders['orIdIssueType'] = $_POST['rcIdIssueType'];
			$inputDataOrders['orAppApplierBirthPhone'] = $_POST['rcBirthPhone'];
			$inputDataOrders['orAppApplierBirthAddr'] = $_POST['rcBirthAddr']; //更新訂單內的戶籍地址
			$inputDataOrders['orAppApplierBirthAddrPostCode'] = $_POST['rcBirthAddrPostCode']; //更新訂單內的戶籍地址
			$or->update($inputDataOrders, $rcData[0]["rcRelateDataNo"]);
		break;
		
		case "1":
		case "2":
			foreach($_POST as $key=>$value){
			if(in_array($key,$columnsMoc)){
					$inputDataMoc[$key] = $value;
				}
			}
			$strDate = explode("-",$_POST['rcIdIssueDate']);
			$inputDataMoc['mcoIdIssueYear'] = $strDate[0];
			$inputDataMoc['mcoIdIssueMonth'] = $strDate[1];
			$inputDataMoc['mcoIdIssueDay'] = $strDate[2];
			$inputDataMoc['mcoIdIssuePlace'] = $_POST['rcIdIssuePlace'];
			$inputDataMoc['mcoIdIssueType'] = $_POST['rcIdIssueType'];
			$inputDataMoc['mcoBirthPhone'] = $_POST['rcBirthPhone'];
			$inputDataMoc['mcoBirthAddr'] = $_POST['rcBirthAddr']; //更新訂單內的戶籍地址
			$inputDataMoc['mcoBirthPostCode'] = $_POST['rcBirthAddrPostCode']; //更新訂單內的戶籍地址
			$moto->update($inputDataMoc, $rcData[0]["rcRelateDataNo"]);
			$inputDataOr["rcPeriodTotal"] = $_POST['mcoPeriodTotal'];
			$inputDataOr["rcPeriodAmount"] = $_POST['mcoPeriodAmount'];
			
		break;
	}
	
	$rcStatus_already_2 = false;
	if(@$rcData[0]["rcStatus"]==2){
		$rcStatus_already_2 = true;
	}
	if($rcStatus_already_2){
		$message = array (
				"error",
				"本件已由其他人員進件了~" 
		);
		echo json_encode ( $message, JSON_UNESCAPED_UNICODE );
		exit ();
	}
	
	$inputDataOr["rcStatus"] = "2";
	
	if($rcStatus == "1"){
		$inputDataOr["aauNoCredit"] = "";
	}
	$inputDataOr["rcIfCredit"] = "0";
	$inputDataOr["rcIfAuthen"] = "0";
	$inputDataOr["aauNoAuthen"] = ""; 
	if(empty($rcData[0]["rcCaseNo"])){
		if($rcData[0]["rcType"] == "0" || $rcData[0]["rcType"] == "1"){
			$inputDataOr["rcCaseNo"] = $apiOr->getCaseNo(1);
		}else{
			$inputDataOr["rcCaseNo"] = $apiOr->getCaseNo(2);
		}
	}
	//進到PROCESS直接改2
	$rcStatus = "2";
	
	date_default_timezone_set('Asia/Taipei');
	$date = date('Y-m-d H:i:s', time());
	
	$apiMem->update($memInputData,$memInputData["memNo"]);
	$apiOr->update($inputDataOr,$inputDataOr["rcNo"]);
	//NoWait若補件
	if($rcStatus == "5"){
		if($rcData[0]["rcType"] == "0"){
			$or = new API("orders");
			$or->update(array("orIfEditable"=>"0"),$rcData[0]["rcRelateDataNo"]);
			$sf = new API("servicefixed");
			$str = ($_POST['rcDocProvideReason'] == '1') ? $_POST['rcDocProvideComment']:$sf->reasonArr[$_POST['rcDocProvideReason']];
			$sfDataInput = array(
						"rcNo"=>$_POST["rcNo"],
						"orDocProvideReason"=>$str,
						"aauNoService"=>$_SESSION['adminUserData']['aauNo']); 
			$sf->insert($sfDataInput); 
			$app_str = ($_POST['rcDocProvideReason'] == '1') ? "，".$_POST['rcDocProvideComment']:"，".$sf->reasonArr[$_POST['rcDocProvideReason']];
		}else{
			$moc = new API("motorbike_cellphone_orders");
			$moc->update(array("mcoIfEditable"=>"0"),$rcData[0]["rcRelateDataNo"]);
			$sf = new API("servicefixed");
			$str = ($_POST['rcDocProvideReason'] == '1') ? $_POST['rcDocProvideComment']:$sf->reasonArr[$_POST['rcDocProvideReason']];
			$sfDataInput = array(
						"rcNo"=>$_POST["rcNo"],
						"orDocProvideReason"=>$str,
						"aauNoService"=>$_SESSION['adminUserData']['aauNo']); 
			$sf->insert($sfDataInput);
		}
	}
	
	//推播+EMAIL
	if($rcStatus != "1"){
		//NoWait
		if($rcData[0]["rcType"] == "0"){
			//EMAIL
			$or = new API("orders");
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
			$msg[0] = "您訂購的商品 - ".$proData[0]["proName"]."，".$or->statusArr[$rcStatus];
			$otherInfo[0] = $rcData[0]["rcRelateDataNo"];
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
			//push_android($androidRigistIdArr,$msg[0],$pushTitle,$otherInfo[0]);
			//$push = push("happy.pem",'happy',$deviceTokenArr,$msg,$otherInfo);
		}else{
			$ad = new API("app_data");
			$adData = $ad->getUniqueDeviceToken($rcData[0]["memNo"]);
			//推播
			$msg[0] = "您所申請的 ".$apiOr->caseTypeArr[$rcData[0]["rcType"]]." - ".$rcData[0]["rcCaseNo"]."，".$apiOr->statusArr[$rcStatus];
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
		}
	}
	//人員操作紀錄
	$apiSd = new API("service_record");
	$SdDataInput = array(
						"rcNo"=>$_POST["rcNo"],
						"aauNoService"=>$_SESSION['adminUserData']['aauNo'],
						"content"=>"儲存案件並進入徵信", 
						"time"=>date('Y-m-d H:i:s',time())); 
	$apiSd->insert($SdDataInput);
	
	
	
	//狀態紀錄
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
	$scArr["scrDate"] = date('Y-m-d H:i:s',time()); 
	$scr->insert($scArr);
	
	//回傳案件編號
	$rcCaseNo = ($inputDataOr["rcCaseNo"] != "") ? $inputDataOr["rcCaseNo"]:$rcData[0]["rcCaseNo"];
	$message = array("message"=>"OK","rcCaseNo"=>$rcCaseNo);
	echo json_encode($message,JSON_UNESCAPED_UNICODE); 
}else{
	echo json_encode($errMsg,JSON_UNESCAPED_UNICODE);
}


?>