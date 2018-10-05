<?php
header('Content-Type: text/html; charset=utf8');
require_once('../../model/require_ajax.php');

$nad = new API("note_applier_details");
$ncd = new API("note_contact_details");
$ndc = new API("note_default_comment");

foreach($_POST as $key=>$value){
	$$key = $value;
}

if($type == "applier"){
	$nad->setWhereArray(array("nlNo"=>$nlNo,"rcNo"=>$rcNo,"ass_No"=>$ass_No));
	$nadData = $nad->getWithConditions();
	$output = array();
	if($nadData != null){
		foreach($nadData as $key=>&$value){
			$ndcData = $ndc->getOne($value["ndcNo"]);
			$value["ndcNo"] = $ndcData[0]["ndcOption"];
			if($value["ndcNo"] != "請選擇" || trim($value["nadExtraInfo"]) != ""){
				foreach($value as $keyIn=>$valueIn){
					$output[$key][$keyIn] = $valueIn;
				}
			}
		}
		echo json_encode($output,JSON_UNESCAPED_UNICODE);
	}else{
		echo "empty";
	}
}else{
	$ncd->setWhereArray(array("nlNo"=>$nlNo,"rcNo"=>$rcNo,"ncdKey"=>$ncdKey));
	$ncdData = $ncd->getWithConditions();
	$output = array();
	if($ncdData != null){
		foreach($ncdData as $key=>&$value){
			$ndcData = $ndc->getOne($value["ndcNo"]);
			$value["ndcNo"] = $ndcData[0]["ndcOption"];
			if($value["ndcNo"] != "請選擇" || trim($value["ncdExtraInfo"]) != ""){
				foreach($value as $keyIn=>$valueIn){
					$output[$key][$keyIn] = $valueIn;
				}
			}
		}
		echo json_encode($output,JSON_UNESCAPED_UNICODE);
	}else{
		echo "empty";
	}
}

?>