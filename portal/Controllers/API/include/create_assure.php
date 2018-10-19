<?php
$or = new Orders();
$assColumnssArr = array();
$assColumns = $or->getAllColumnNames("assure");
foreach($assColumns as $key => $value){
	$assColumnssArr[$value['COLUMN_NAME']] = $value['COLUMN_COMMENT'];
}

$restMustFill = array("orNo");
foreach($restMustFill as $key=>$value){
	if(!isset($_POST[$value])){
		$errMsg[] = "Missing ".$value.".";
	}
}


$assMustFill = array(
	"assAppApplierName","assAppApplierCell","assAppApplierIdNum","assAppApplierBday",
	"assAppApplierGender","assAppApplierCurPhone","assAppApplierCurAddr",
	"assAppApplierBirthAddr","assAppApplierBirthPhone","assAppApplierLivingOwnership",
	"assAppContactRelaName","assAppContactRelaRelation","assAppContactRelaCell","assAppContactFrdName",
	"assAppContactFrdRelation","assAppContactFrdCell"
);
$assColumnsCheck = array(
	"assAppApplierName","assAppApplierCell","assAppApplierIdNum","assAppApplierBday",
	"assAppApplierGender","assAppApplierCurPhone","assAppApplierCurAddr",
	"assAppApplierBirthAddr","assAppApplierBirthPhone","assAppApplierLivingOwnership",
	"assAppContactRelaName","assAppContactRelaRelation","assAppContactRelaCell","assAppContactRelaPhone",
	"assAppContactFrdName","assAppContactFrdRelation","assAppContactFrdCell","assAppContactFrdPhone",
	"assAppApplierCompanyName","assAppApplierYearExperience","assAppApplierMonthSalary",
	"assAppApplierCompanyPhone","assAppApplierCompanyPhoneExt","assAppApplierCreditNum",
	"assAppApplierCreditSecurityNum","assAppApplierCreditIssueBank","assAppApplierCreditDueDate"
);
$assDataInputArr = array();
//確認又沒有
foreach($assMustFill as $key=>$value){
	if(!isset($_POST[$value])){
		$errMsg[] = "Missing ".$assColumnssArr[$value].".";
	}
}

if(!isset($errMsg)){
	//篩選
	foreach($_POST as $key=>$value){
		if(in_array($key,$assColumnsCheck)){
			$assDataInputArr[$key] = $value;
		}
	}
	
	
	foreach($assDataInputArr as $key=>$value){
		if($value != ""){
			switch($key){
				case "assAppApplierCell":
					if(!is_numeric($value)){
						$errMsg[] = $memColumnsArr[$key].": "."必須為數字。";
					}
					break;
				case "assAppApplierIdNum":
					if(!preg_match("/^[A-Z]{1}[0-9]{9}$/", $value)){
						$errMsg[] = $memColumnsArr[$key].": "."不正確的身分證字號格式。";
					}
					break;
				case "assAppApplierCurPhone":
					if(!preg_match("/^[0-9]+-{1}[0-9]+$/", $value)){
						$errMsg[] = $memColumnsArr[$key].": "."電話格式為:區碼-號碼。(ex:02-22898788)";
					}
					break;
			}
		}
	}
	
	
	if(!isset($errMsg)){
		//親屬欄位
		$assAppContactRelaName = array($_POST['assAppContactRelaName']);
		foreach($assAppContactRelaName as $n => $v){
			$assAppContactRelaName1[] = urlencode($v);
		}
		$assAppContactRelaRelation = array($_POST['assAppContactRelaRelation']);
		foreach($assAppContactRelaRelation as $n => $v){
			$assAppContactRelaRelation1[] = urlencode($v);
		}
		$assAppContactRelaPhone = array($_POST['assAppContactRelaPhone']);
		foreach($assAppContactRelaPhone as $n => $v){
			$assAppContactRelaPhone1[] = urlencode($v);
		}
		$assAppContactRelaCell = array($_POST['assAppContactRelaCell']);
		foreach($assAppContactRelaCell as $n => $v){
			$assAppContactRelaCell1[] = urlencode($v);
		}
		
		$assAppContactFrdName = array($_POST['assAppContactFrdName']);
		foreach($assAppContactFrdName as $n => $v){
			$assAppContactFrdName1[] = urlencode($v);
		}
		$assAppContactFrdRelation = array($_POST['assAppContactFrdRelation']);
		foreach($assAppContactFrdRelation as $n => $v){
			$assAppContactFrdRelation1[] = urlencode($v);
		}
		$assAppContactFrdPhone = array($_POST['assAppContactFrdPhone']);
		foreach($assAppContactFrdPhone as $n => $v){
			$assAppContactFrdPhone1[] = urlencode($v);
		}
		
		$assAppContactFrdCell = array($_POST['assAppContactFrdCell']);
		foreach($assAppContactFrdCell as $n => $v){
			$assAppContactFrdCell1[] = urlencode($v);
		}
		
		$assDataInputArr['assAppContactRelaName'] = urldecode(json_encode($assAppContactRelaName1));
		$assDataInputArr['assAppContactRelaRelation'] = urldecode(json_encode($assAppContactRelaRelation1));
		$assDataInputArr['assAppContactRelaPhone'] = urldecode(json_encode($assAppContactRelaPhone1));
		$assDataInputArr['assAppContactRelaCell'] = urldecode(json_encode($assAppContactRelaCell1));
		$assDataInputArr['assAppContactFrdName'] = urldecode(json_encode($assAppContactFrdName1));
		$assDataInputArr['assAppContactFrdRelation'] = urldecode(json_encode($assAppContactFrdRelation1));
		$assDataInputArr['assAppContactFrdPhone'] = urldecode(json_encode($assAppContactFrdPhone1));
		$assDataInputArr['assAppContactFrdCell'] = urldecode(json_encode($assAppContactFrdCell1));
		
		$api->insert($assDataInputArr);
		$assNo = $api->db->getIdForInsertedRecord();
		$api->setInformation(true, 1, 1, "保人基本資料填寫成功。");
		
	}else{
		$api->setInformation(false, 0, 0, $errMsg);
	}
}else{
	$api->setInformation(false, 0, 0, $errMsg);
}
$api->setResult();


?>