<?php
header('Content-Type: text/html; charset=utf8');
require_once('../../model/require_ajax.php');

$sc = new API("status_comment");

$result = array();

foreach($_POST as $key=>$value){
	$$key = $value;
}


if(empty($_POST["scComment"])){
	$result["error"] = "請輸入備註內容。";
}

if(!isset($result["error"])){
	$result["error"] = "";
	$sc->insert($_POST);
	$sc->setWhereArray(array("scStatusNo"=>$scStatusNo,"rcNo"=>$rcNo));
	$scData = $sc->getWithConditions(true);
	$result["data"] = $scData;
}

echo json_encode($result,JSON_UNESCAPED_UNICODE);

?>