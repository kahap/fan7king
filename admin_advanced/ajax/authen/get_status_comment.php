<?php
header('Content-Type: text/html; charset=utf8');
require_once('../../model/require_ajax.php');

$sc = new API("status_comment");
$scr = new API("status_comment_records");

//案件狀態備註紀錄()
$scr->setWhereArray(array("rcNo"=>$_POST["rcNo"]));
$scrData = $scr->getWithConditions(true);
if($scrData != null){
	$scNoArr = json_decode($scrData[0]["scNo"]);
}

$result = array();

foreach($_POST as $key=>$value){
	$$key = $value;
}

$sc->setWhereArray(array("scStatusNo"=>$_POST["scStatusNo"],"rcNo"=>$_POST["rcNo"]));
$scData = $sc->getWithConditions(true);

if($scData != null){
	foreach($scData as $key=>&$value){
		if(isset($scNoArr) && in_array($value["scNo"],$scNoArr)){
			$value["isChecked"] = "1";
		}else{
			$value["isChecked"] = "0";
		}
	}
}

echo json_encode($scData,JSON_UNESCAPED_UNICODE);

?>