<?php
$or = new Orders();

$memColumns = $or->getAllColumnNames("member");
foreach($memColumns as $key => $value){
	$memColumnsArr[$value['COLUMN_NAME']] = $value['COLUMN_COMMENT'];
}

switch($type){
	case "regist_student":
		$mustFill = array("memClass","memName","memIdNum");
		$columnArray = array("memClass","memName","memIdNum","memRecommCode");
		$_POST["memClass"] = "0";
		break;
	case "regist_nonstudent":
		$mustFill = array("memClass","memName","memIdNum","memCompanyName","memSalary","memYearWorked");
		$columnArray = array("memClass","memName","memIdNum","memCompanyName","memSalary","memYearWorked","memRecommCode");
		$_POST["memClass"] = "4";
		break;
	case "main":
		if($_POST["memClass"] == "0"){
			$mustFill = array("memClass","memName","memGender","memIdNum","memBday","memAddr","memPhone","memCell","memSubEmail");
		}else{
			$mustFill = array("memClass","memName","memGender","memIdNum","memBday","memAddr","memPhone","memCell","memSubEmail");
		}
		$columnArray = array("memClass","memName","memGender","memIdNum","memBday","memAddr","memPhone","memCell","memSubEmail","memLineId");
		break;
}

foreach($mustFill as $key=>$value){
	if(!isset($_POST[$value])){
		$errMsg[] .= "Missing ".$memColumnsArr[$value];
	}
}

// 測試推薦人是否有效
if(isset($_POST["memRecommCode"]) && $_POST["memRecommCode"] != ""){
	$apiCheckRecomm = new API("member");
	$apiCheckRecomm->getOne($_POST["memRecommCode"]);
	$checkData = $apiCheckRecomm->getData();
	if($checkData == null){
		$errMsg = "無效的推薦碼。";
	}else{
		if(isset($_POST["adTokenId"]) && trim($_POST["adTokenId"]) != ""){
			$memNo = getMemberNo();
			if($memNo == $_POST["memRecommCode"]){
				$errMsg = "推薦碼不可為自己。";
			}
		}else{
			$errMsg = "請帶入該會員之登入TokenId。";
		}
	}
}

// 若沒錯誤的話預設其他欄位
if(!isset($errMsg)){
	if(isset($_POST["adTokenId"]) && trim($_POST["adTokenId"]) != ""){
        if(getIdNUmUsed()){
            $memNo = getMemberNo();
            foreach($_POST as $key=>$value){
                if(!in_array($key,$columnArray)){
                    unset($_POST[$key]);
                }
            }
            $api->update($_POST, $memNo);
        }else{
            $api->setInformation(false, 0, 0, "此身份證號已有人使用。");
        }
	}else{
		$api->setInformation(false, 0, 0, "請帶入該會員之登入TokenId。");
	}
}else{
	$api->setInformation(false, 0, 0, $errMsg);
}
$api->setResult();


?>