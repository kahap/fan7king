<?php
header('Content-Type: text/html; charset=utf8');
require_once('../../model/require_ajax.php');

date_default_timezone_set('Asia/Taipei');
$date = date('Y-m-d H:i:s', time());

$apiOr = new API("real_cases");

$inputData = array();

if(isset($_POST["rcNo"])){
	foreach($_POST["rcNo"] as $noEach){
		$apiOr->update(array("rcApproStatus"=>1,"rcGetApproDate"=>$date), $noEach);
	}
}else{
	$errMsg = "請選擇欲確認撥款之案件。";
}

if(!isset($errMsg)){
	echo "OK";
}else{
	echo $errMsg;
}

?>