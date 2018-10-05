<?php
header('Content-Type: text/html; charset=utf8');
require_once('../../model/require_ajax.php');

$apiOr = new API("real_cases");
$rcData = $apiOr->getOne($_POST['rcNo']);

$asus = new API("assure");
$nad = new API("note_applier_details");
$ncd = new API("note_contact_details");
$nl = new API("note_list");
$ndc = new API("note_default_comment");

date_default_timezone_set('Asia/Taipei');
$date = date('Y-m-d H:i:s', time());

foreach($_POST as $key=>$value){
	$$key = $value;
}

$columnsAss = array("rcNo","assAppApplierName","assAppApplierRelation","assAppApplierIdNum","assAppApplierBday","assAppApplierBirthPhone","assAppApplierCurPhone","assAppApplierCompanyName","assAppApplierCompanyPhone","assAppApplierCell","assAppApplierCurAddr");
$inputDataAss = array();
	foreach($_POST as $key=>$value){
		if(in_array($key,$columnsAss)){
			$inputDataAss[$key] = $value;
		}
	}
$assNo = $asus->insert($inputDataAss,$_POST['rcNo']);


//申請人區
//一般單選欄位+備註
foreach($applierNdcNo as $key=>$value){
	$inputDataNad = array();
	$inputDataNad["nlNo"] = $applierNlNo[$key];
	$inputDataNad["ndcNo"] = $value;
	$inputDataNad["nadExtraInfo"] = $applierNadExtraInfo[$key];
	$inputDataNad["rcNo"] = $rcNo;
	$inputDataNad["ass_No"] = $assNo;
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
			$inputDataNad["ass_No"] = $assNo;
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
			$inputDataNad["ass_No"] = $assNo;
			$inputDataNad["nadDate"] = $date;
			$nad->update($inputDataNad,$$nadNo);
		}else{
			$inputDataNad["nlNo"] = $$nlNo;
			$inputDataNad["ndcNo"] = '[""]';
			$inputDataNad["nadExtraInfo"] = $$comment;
			$inputDataNad["rcNo"] = $rcNo;
			$inputDataNad["ass_No"] = $assNo;
			$inputDataNad["nadDate"] = $date;
			$nad->insert($inputDataNad);
		}
	}
}
echo "OK";
?>