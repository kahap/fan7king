<?php
header('Content-Type: text/html; charset=utf8');
require_once('../../model/require_ajax.php');

session_start();

foreach($_POST as $key=>$value){
	$$key = $value;
}

// print_r($_POST);
$apiOr = new API("real_cases");
$api = new API("member");
$or = new API("orders");
$moto = new API("motorbike_cellphone_orders");

$rcData = $apiOr->getOne($_POST["rcNo"]);
$memData = $api->getOne($rcData[0]["memNo"]);

$columns = array("memNo","memName","memSchool","memGender","memBday","memIdNum","memAccount","memPhone","memAddr","memPostCode");
$inputData = array();

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
		if(in_array($key,$columns)){
			$inputData[$key] = $value;
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
	};
	
	
	
	$api->update($inputData,$inputData["memNo"]);
	$apiOr->update($inputDataOr, $inputDataOr["rcNo"]);
	
	//若取消訂單
	if($rcStatus == "7" || $rcStatus == "701"){
		if($rcData[0]["rcType"] == "0"){
			$or = new API("orders");
			$or->update(array("orStatus"=>$rcStatus),$rcData[0]["rcRelateDataNo"]);
		}else{
			$moc = new API("motorbike_cellphone_orders");
			$moc->update(array("mcoStatus"=>$rcStatus),$rcData[0]["rcRelateDataNo"]);
		}
	}
	
	//NoWait若補件
	if($rcStatus == "5"){
		if($rcData[0]["rcType"] == "0"){
			$or = new API("orders");
			$or->update(array("orIfEditable"=>"0","orDocProvideReason"=>$_POST['rcDocProvideReason'],"orDocProvideComment"=>$_POST['rcDocProvideComment']),$rcData[0]["rcRelateDataNo"]);
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
	
	//更新購買規格
	if($rcData[0]["rcType"] == "0"){	
		$or = new API("orders");
		$or->update(array('orProSpec'=>$_POST['orProSpec']),$rcData[0]["rcRelateDataNo"]);
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
			$msg[0] = "您訂購的商品 - ".$proData[0]["proName"]."，".$or->statusArr[$rcStatus].$app_str;
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
			push_android($androidRigistIdArr,$msg[0],$pushTitle,$otherInfo[0]);
			$push = push("happy.pem",'happy',$deviceTokenArr,$msg,$otherInfo);
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
						"content"=>"儲存案件", 
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
	
	echo "OK";
}else{
	echo json_encode($errMsg,JSON_UNESCAPED_UNICODE);
}


?>