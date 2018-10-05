<?php
header (" content-type: text/HTML; charset=utf-8 ");
require_once('../../model/require_login.php');
require_once '../../cls/Excel/reader.php';

$sch = new School();
$maj = new Major();

$errMsg = "";

if($_FILES["upload"]['error'] == 4) {
	$errMsg = "plz select";
}else{
	$tmp = explode(".", $_FILES["upload"]["name"]);
	$extension = end($tmp);
	if($_FILES["upload"]['type'] != "application/vnd.ms-excel" || $extension != "xls"){
		$errMsg = "upload xls only";
	}
}


if($errMsg == ""){
	// ExcelFile($filename, $encoding);
	$data = new Spreadsheet_Excel_Reader();
	// Set output Encoding.
	$data->setOutputEncoding('UTF-8');
	
	$data->read($_FILES["upload"]["tmp_name"]);
	
	for ($i = 1; $i <= $data->sheets[0]['numRows']; $i++) {
		$schoolData = array();
		if($i != 1){
			if($data->sheets[0]['cells'][$i][1] != $data->sheets[0]['cells'][$i-1][1]){
				$schoolData["schName"] = trim($data->sheets[0]['cells'][$i][1]);
				$schNo = $sch->insert($schoolData);
				$majorData["majName"] = trim($data->sheets[0]['cells'][$i][2]);
				$majorData["schNo"] = $schNo;
				$maj->insert($majorData);
			}else{
				$schData = $sch->getWithName($data->sheets[0]['cells'][$i][1]);
				$majorData["majName"] = trim($data->sheets[0]['cells'][$i][2]);
				$majorData["schNo"] = $schData[0]["schNo"];
				$maj->insert($majorData);
			}
		}else{
			$schoolData["schName"] = trim($data->sheets[0]['cells'][$i][1]);
			$schNo = $sch->insert($schoolData);
			$majorData["majName"] = trim($data->sheets[0]['cells'][$i][2]);
			$majorData["schNo"] = $schNo;
			$maj->insert($majorData);
		}
	}
}else{
	echo $errMsg;
}




?>