<?php
header('Content-Type: text/html; charset=utf8');
require_once('../../model/require_ajax.php');

// print_r($_POST);
session_start();

$nad = new API("note_applier_details");
$ncd = new API("note_contact_details");
$nl = new API("note_list");
$ndc = new API("note_default_comment");
$or = new API("real_cases");
$rcData = $or->getOne($_POST['rcNo']);
foreach($_POST as $key=>$value){
	$$key = $value;
}

date_default_timezone_set('Asia/Taipei');
$date = date('Y-m-d H:i:s', time());

if($_SESSION["adminUserData"]["aauNo"] == ""){
	$errMsg[] = "請重新登入，才能處理案件";
}

if($_POST['ass_No'] != ""){
	$asus = new API("assure");
	foreach($_POST['ass_No'] as $k => $v){
		$columnsAss = array("assAppApplierName"=>$_POST["assAppApplierName"][$v],"assAppApplierRelation"=>$_POST["assAppApplierRelation"][$v],"assAppApplierIdNum"=>$_POST["assAppApplierIdNum"][$v],"assAppApplierBday"=>$_POST["assAppApplierBday"][$v],"assAppApplierBirthPhone"=>$_POST["assAppApplierBirthPhone"][$v],"assAppApplierCurPhone"=>$_POST["assAppApplierCurPhone"][$v],"assAppApplierCompanyName"=>$_POST["assAppApplierCompanyName"][$v],"assAppApplierCompanyPhone"=>$_POST["assAppApplierCompanyPhone"][$v],"assAppApplierCell"=>$_POST["assAppApplierCell"][$v],"assAppApplierCurAddr"=>$_POST["assAppApplierCurAddr"][$v]);
		$asus->update($columnsAss,$v);
		//保證人專區
		//一般單選欄位+備註
		foreach($applierNdcNoAss[$v] as $key=>$value){
			$inputDataNadAss = array();
			$inputDataNadAss["nlNo"] = $applierNlNo_Ass[$key];
			$inputDataNadAss["ndcNo"] = $value;
			$inputDataNadAss["nadExtraInfo"] = $applierNadExtraInfo_Ass[$v][$key];
			$inputDataNadAss["rcNo"] = $rcNo;
			$inputDataNadAss["ass_No"] = $v;
			$inputDataNadAss["nadDate"] = $date;
			if(isset($nadNo_Ass[$key])){
				$nadData = $nad->getOne($nadNo_Ass[$key]);
				//如果選項不一樣或者備註不一樣就新增
				if($nadData[0]["ndcNo"] != $value || $nadData[0]["nadExtraInfo"] != $applierNadExtraInfo_Ass[$v][$key]){
					$nad->insert($inputDataNadAss);
				}
			}else{
				$nad->insert($inputDataNadAss);
			}
		}
	}
}

//更新聯絡人資訊
$nameArr = $_POST["rcContactName"];
$relationArr = $_POST["rcContactRelation"];
$phoneArr = $_POST["rcContactPhone"];
$cellArr = $_POST["rcContactCell"];
if(!empty(array_filter($nameArr))){
	$or->update(array(
		"rcContactName"=>json_encode($nameArr,JSON_UNESCAPED_UNICODE),
		"rcContactRelation"=>json_encode($relationArr,JSON_UNESCAPED_UNICODE),
		"rcContactPhone"=>json_encode($phoneArr,JSON_UNESCAPED_UNICODE),
		"rcContactCell"=>json_encode($cellArr,JSON_UNESCAPED_UNICODE)
	),$rcNo);
	if($rcData[0]['rcType'] == 0){
		$ord = new API("orders");
		$data = Array("orAppContactFrdName"=>json_encode($nameArr,JSON_UNESCAPED_UNICODE),
				"orAppContactFrdRelation"=>json_encode($relationArr,JSON_UNESCAPED_UNICODE),
				"orAppContactFrdPhone"=>json_encode($phoneArr,JSON_UNESCAPED_UNICODE),
				"orAppContactFrdCell"=>json_encode($cellArr,JSON_UNESCAPED_UNICODE));	
		$ord->update($data,$rcData[0]['rcRelateDataNo']);
	}else{
		$moto = new API("motorbike_cellphone_orders");
		$data = Array("mcoContactName"=>json_encode($nameArr,JSON_UNESCAPED_UNICODE),
				"mcoContactRelation"=>json_encode($relationArr,JSON_UNESCAPED_UNICODE),
				"mcoContactPhone"=>json_encode($phoneArr,JSON_UNESCAPED_UNICODE),
				"mcoContactCell"=>json_encode($cellArr,JSON_UNESCAPED_UNICODE));
		$moto->update($data,$rcData[0]['rcRelateDataNo']);
	}
}

//申請人區
//驗證
foreach($applierNdcNo as $key=>$value){
	$ndcData = $ndc->getOne($value);
	$nlData = $nl->getOne($ndcData[0]["nlNo"]);
	if($ndcData[0]["ndcOption"] == "請選擇"){
		$errMsg[] = "請選擇 申請人 照會選項：".$nlData[0]["nlName"];
	}
}
//一般單選欄位+備註
if(!isset($errMsg)){
	foreach($applierNdcNo as $key=>$value){
		$inputDataNad = array();
		$inputDataNad["nlNo"] = $applierNlNo[$key];
		$inputDataNad["ndcNo"] = $value;
		$inputDataNad["nadExtraInfo"] = $applierNadExtraInfo[$key];
		$inputDataNad["rcNo"] = $rcNo;
		$inputDataNad["nadDate"] = $date;
		if(isset($nadNo[$key])){
			$nadData = $nad->getOne($nadNo[$key]);
			//如果選項不一樣或者備註不一樣就新增
			if($nadData[0]["ndcNo"] != $value || $nadData[0]["nadExtraInfo"] != $applierNadExtraInfo[$key]){
				$nad->insert($inputDataNad);
			}
		}else{
			$nad->insert($inputDataNad);
		}
	}
	//抓出可多重選擇的欄位
	$nl->setWhereArray(array("npNo"=>"1"));
	$nlApplierData = $nl->getWithConditions();
	foreach($nlApplierData as $key=>$value){
		if($value["nlIfMultiple"] == "1"){
			$inputDataNad = array();
			$keyName = "noteList".$value["nlNo"];
			$nlNo = "nlNo".$value["nlNo"];
			$nadNo = "nadNo".$value["nlNo"];
			$comment = "nadComment".$value["nlNo"];
			if(isset($$keyName)){
				$inputDataNad["nlNo"] = $$nlNo;
				$inputDataNad["ndcNo"] = json_encode($$keyName,true);
				$inputDataNad["nadExtraInfo"] = $$comment;
				$inputDataNad["rcNo"] = $rcNo;
				$inputDataNad["nadDate"] = $date;
				if(isset($$nadNo)){
					$nad->update($inputDataNad,$$nadNo);
				}else{
					$nad->insert($inputDataNad);
				}
			}else if(!isset($$keyName) && isset($$nadNo)){
				$inputDataNad["nlNo"] = $$nlNo;
				$inputDataNad["ndcNo"] = '[""]';
				$inputDataNad["nadExtraInfo"] = $$comment;
				$inputDataNad["rcNo"] = $rcNo;
				$inputDataNad["nadDate"] = $date;
				$nad->update($inputDataNad,$$nadNo);
			}else{
				$inputDataNad["nlNo"] = $$nlNo;
				$inputDataNad["ndcNo"] = '[""]';
				$inputDataNad["nadExtraInfo"] = $$comment;
				$inputDataNad["rcNo"] = $rcNo;
				$inputDataNad["nadDate"] = $date;
				$nad->insert($inputDataNad);
			}
		}
	}
}


//聯絡人區
$ncd->setWhereArray(array("rcNo"=>$rcNo));
$ncdData = $ncd->getWithConditions();
if($ncdData != null){
	foreach($contactNdcNo as $key=>$value){
		$ndcData = $ndc->getOne($value);
		$nlData = $nl->getOne($ndcData[0]["nlNo"]);
		if($ndcData[0]["ndcOption"] == "請選擇"){
			$errMsg[] = "請選擇 聯絡人 照會選項：".$nlData[0]["nlName"];
		}
	}
	if(isset($contactNewNdcNo)){
		foreach($contactNewNdcNo as $key=>$value){
			$ndcData = $ndc->getOne($value);
			$nlData = $nl->getOne($ndcData[0]["nlNo"]);
			if($ndcData[0]["ndcOption"] == "請選擇"){
				$errMsg[] = "請選擇 聯絡人 照會選項：".$nlData[0]["nlName"];
			}
		}
	}
	if(!isset($errMsg)){
		foreach($contactNdcNo as $key=>$value){
			$inputDataNcd = array();
			$inputDataNcd["nlNo"] = $contactNlNo[$key];
			$inputDataNcd["ncdKey"] = $contactKey[$key];
			$inputDataNcd["ndcNo"] = $contactNdcNo[$key];
			$inputDataNcd["ncdExtraInfo"] = $contactNcdExtraInfo[$key];
			$inputDataNcd["rcNo"] = $rcNo;
			$inputDataNcd["ncdDate"] = $date;
			$ncdData = $ncd->getOne($contactNcdNo[$key]);
			//如果選項不一樣或者備註不一樣就新增
			if($ncdData[0]["ndcNo"] != $contactNdcNo[$key] || $ncdData[0]["ncdExtraInfo"] != $contactNcdExtraInfo[$key]){
				$ncd->insert($inputDataNcd);
			}
		}
		if(isset($contactNewNdcNo)){
			foreach($contactNewNdcNo as $key=>$value){
				$inputDataNcdNew = array();
				$inputDataNcdNew["nlNo"] = $contactNewNlNo[$key];
				$inputDataNcdNew["ncdKey"] = $contactNewKey[$key];
				$inputDataNcdNew["ndcNo"] = $contactNewNdcNo[$key];
				$inputDataNcdNew["ncdExtraInfo"] = $contactNewNcdExtraInfo[$key];
				$inputDataNcdNew["rcNo"] = $rcNo;
				$inputDataNcdNew["ncdDate"] = $date;
				$ncd->insert($inputDataNcdNew);
			}
		}
	}
}else{
	foreach($contactNdcNo as $key=>$value){
		$ndcData = $ndc->getOne($value);
		$nlData = $nl->getOne($ndcData[0]["nlNo"]);
		if($ndcData[0]["ndcOption"] == "請選擇"){
			$errMsg[] = "請選擇聯絡人照會選項：".$nlData[0]["nlName"];
		}
	}
	if(!isset($errMsg)){
		foreach($contactNdcNo as $key=>$value){
			$inputDataNcd = array();
			$inputDataNcd["nlNo"] = $contactNlNo[$key];
			$inputDataNcd["ncdKey"] = $contactKey[$key];
			$inputDataNcd["ndcNo"] = $contactNdcNo[$key];
			$inputDataNcd["ncdExtraInfo"] = $contactNcdExtraInfo[$key];
			$inputDataNcd["rcNo"] = $rcNo;
			$inputDataNcd["ncdDate"] = $date;
			$ncd->insert($inputDataNcd);
		}
	}
}
	//人員操作紀錄
	$apiSd = new API("service_record");
	$SdDataInput = array(
					"rcNo"=>$rcNo,
					"aauNoService"=>$_SESSION['adminUserData']['aauNo'],
					"content"=>"儲存並進入授信", 
					"time"=>date('Y-m-d H:i:s',time())); 
	$apiSd->insert($SdDataInput);
$or->update(array("rcCreditComment"=>$rcCreditComment), $rcNo);

if(!isset($errMsg)){
	$or->update(array("rcIfCredit"=>1),$rcNo);
	echo "OK";
}else{
	echo json_encode($errMsg,JSON_UNESCAPED_UNICODE);
}

?>