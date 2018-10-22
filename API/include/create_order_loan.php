<?php
//第一部訂單填寫
$mem = new Member();
$or = new Orders();
$forMem = new API("member");
$orderContact = new API("orderContact");


//先取得所有欄位的中文
$memColumnsArr = array();
$memColumns = $or->getAllColumnNames("member");
foreach($memColumns as $key => $value){
	$memColumnsArr[$value['COLUMN_NAME']] = $value['COLUMN_COMMENT'];
}
$orColumnssArr = array();
$orColumns = $or->getAllColumnNames("motorbike_cellphone_orders");
foreach($orColumns as $key => $value){
	$orColumnssArr[$value['COLUMN_NAME']] = $value['COLUMN_COMMENT'];
}

//其餘
$restMustFill = array("adTokenId","gpsLat","gpsLong");
//確認必填
foreach($restMustFill as $key=>$value){
	if(!isset($_POST[$value])){
		$errMsg[] = "Missing ".$value.".";
	}
}

//member table區
$memMustFill = array("memName","memIdNum","memBday","memClass","memAddr","memPhone","memCell","memSubEmail");
if($_POST["memClass"] == '學生'){
	$memMustFill[] = "memAccount";
	$memMustFill[] = "memSchool";
	$memColumnsCheck = array("memName","memIdNum","memBday","memCompanyName","memSalary","memYearWorked","memClass","memSubEmail","memPostCode","memAddr","memPhone","memCell","memAccount","memSchool");
}else if($_POST["memClass"] == '非學生'){
	$memColumnsCheck = array("memName","memIdNum","memBday","memCompanyName","memSalary","memYearWorked","memClass","memSubEmail","memPostCode","memAddr","memPhone","memCell");
}else{
	$errMsg[] = "沒有填入身分別。";
}

$memDataInputArr = array();
//確認必填
foreach($memMustFill as $key=>$value){
	if(!isset($_POST[$value])){
		$errMsg[] = "Missing ".$memColumnsArr[$value].".";
	}
}

//orders table區
if(!empty($_POST["mcoType"])){
	if($_POST["mcoType"] == "1"){
		//手機必填
		$orMustFill = array(
			"mcoCellBrand","mcoCellphoneSpec",
			"mcoMinMonthlyTotal","mcoMaxMonthlyTotal",
			"mcoIfSecret","mcoBirthAddr","mcoBirthPhone","mcoLivingOwnership","mcoPeriodAmount",
			"mcoContactName","mcoContactRelation","mcoContactCell"
		);
	}else{
		//機車必填
		$orMustFill = array(
			"mcoMotorBrand","mcoCcNumber","mcoMotorbikeSpec","mcoCarNum","mcoYear",
			"mcoMinMonthlyTotal","mcoMaxMonthlyTotal",
			"mcoIfSecret","mcoBirthAddr","mcoBirthPhone","mcoLivingOwnership","mcoPeriodAmount",
			"mcoContactName","mcoContactRelation","mcoContactCell"
		);
	}
	$orColumnsCheck = array(
			"memClass","mcoType","mcoCellBrand","mcoCellphoneSpec","mcoPeriodTotal",
			"mcoMotorBrand","mcoCcNumber","mcoMotorbikeSpec","mcoCarNum","mcoYear",
			"mcoMinMonthlyTotal","mcoMaxMonthlyTotal","mcoDailyInterest",
			"mcoPeriodAmount","mcoIfSecret","mcoBirthPostCode","mcoBirthAddr","mcoBirthPhone","mcoContactName",
			"mcoContactRelation","mcoContactPhone","mcoContactCell","mcoCompanyPhone","mcoCompanyPhoneExt",
			"mcoCreditNum","mcoCreditSecurityNum","mcoCreditIssueBank",
			"mcoCreditDueDate","mcoLivingOwnership","mcoApplyPurpose",
			"mcoAvailTime","mcoExtraInfo"
	);
	
	$orDataInputArr = array();
	//確認必填
	foreach($orMustFill as $key=>$value){
		if(!isset($_POST[$value])){
			$errMsg[] = "Missing ".$orColumnssArr[$value].".";
		}
	}
}else{
	$errMsg[] = "請輸入案件類型";
}

if(!isset($errMsg)){
	//member篩選
	foreach($_POST as $key=>$value){
		if(in_array($key,$memColumnsCheck)){
			$memDataInputArr[$key] = $value;
		}
	}
	//orders篩選
	foreach($_POST as $key=>$value){
		if(in_array($key,$orColumnsCheck)){
			$orDataInputArr[$key] = $value;
		}
	}
	
	//取得會員編號
	$memNo = getMemberNo();
	$memberData = $mem->getOneMemberByNo($memNo);
	
	//初次下單式需登錄GPS
	$forGps = new API("gps");
	$forGps->insert(array(
		"memNo"=>$memNo,
		"gpsLat"=>$_POST["gpsLat"],
		"gpsLong"=>$_POST["gpsLong"]
	));
	
	//member驗證
	foreach($memDataInputArr as $key=>&$value){
		if(trim($value) != ""){
			switch($key){
				//生日未滿18
				case "memBday":
					$bdayArr = explode("-",$value);
					$year = 1911+$bdayArr[0];
					$curYear = date("Y",time());
					$curMonth = date("m",time());
					$curDay = date("d",time());
					if(($curYear - $year) < 18){
						$errMsg[] = "未滿18歲不能申請";
					}else if(($curYear - $year) == 18){
						if($curMonth < $bdayArr[1] || ($curMonth == $bdayArr[1] && $curDay < $bdayArr[2])){
							$errMsg[] = "未滿18歲不能申請";
						}
					}
					break;
				//帳號或常用EMAIL
				case "memSubEmail":
					if(!preg_match("/^[_a-z0-9-]+(\.[_a-z0-9-]+)*(\+[a-z0-9-]+)?@[a-z0-9-]+(\.[a-z0-9-]+)*$/i", $value)){
						$errMsg[] = $memColumnsArr[$key].": "."不正確的Email格式。";
					}
					break;
				case "memCell":
					if(!is_numeric($value)){
						$errMsg[] = $memColumnsArr[$key].": "."必須為數字。";
					}
					break;
				case "memIdNum":
					if(!preg_match("/^[a-zA-Z]{1}[0-9]{9}$/", $value)){
						$errMsg[] = $memColumnsArr[$key].": "."不正確的身分證字號格式。";
					}else{
						if($memberData[0]["memIdNum"] != ""){
							if(strtoupper($memberData[0]["memIdNum"]) != strtoupper($value) && $mem->check_id($value)){
								$errMsg[] = $memColumnsArr[$key].": "."身分證字號已被註冊過。";
							}
						}
					}
					break;
				case "memClass":
					if($value == "學生"){
						$value = "0";
						$orDataInputArr["memClass"] = "0";
					}else{
						$value = "4";
						$orDataInputArr["memClass"] = "4";
					}
					break;
			}
		}
		//認證EMAIL
		if($memDataInputArr['memAccount'] != ""){
			if($memDataInputArr['memClass'] == '0'){
				If(strrpos($memDataInputArr['memAccount'],'@') == false){
					$errMsg[] = $memColumnsArr[$key].": "."認證Email不是確實的Email ";
				}
				$school = trim($memDataInputArr['memSchool']);
				If($school==''){
					$errMsg[] = $memColumnsArr[$key].": "."請填寫學校系級";
				}
			}
		}else{
			if($memberData[0]["memFBtoken"] != "" && $memDataInputArr['memClass'] == '0'){
				$errMsg[] = $memColumnsArr[$key].": "."認證Email不能為空白";
			}
		}
		
		//上班族
		$class_1 = array('orAppApplierCompanyName','orAppApplierYearExperience','orAppApplierMonthSalary','orAppApplierCompanyPhone');
		if($memDataInputArr['memClass'] == '1'){
			foreach($class_1 as $key => $value){
				if($orDataInputArr[$value] == ''){
					$errMsg[] = $orColumnssArr[$value].": "."若身分別為上班族，欄位不得空白。";
				}
			}
		}
		
	}
	
	//若沒錯誤的話預設其他欄位
	if(!isset($errMsg)){
		$memDataInputArr["memIdNum"] = strtoupper($memDataInputArr["memIdNum"]);
		$forMem->update($memDataInputArr, $memNo);
		if(isset($memDataInputArr["memCompanyName"]) && $memDataInputArr["memCompanyName"] != ""){
			$orDataInputArr['mcoCompanyStatus'] = '1';
		}else{
			$orDataInputArr['mcoCompanyStatus'] = '0';
		}
		//親屬欄位
		$orDataInputArr['mcoContactName'] = json_encode($orDataInputArr['mcoContactName'],JSON_UNESCAPED_UNICODE);
		$orDataInputArr['mcoContactRelation'] = json_encode($orDataInputArr['mcoContactRelation'],JSON_UNESCAPED_UNICODE);
		$orDataInputArr['mcoContactPhone'] = json_encode($orDataInputArr['mcoContactPhone'],JSON_UNESCAPED_UNICODE);
		$orDataInputArr['mcoContactCell'] = json_encode($orDataInputArr['mcoContactCell'],JSON_UNESCAPED_UNICODE);
		
		//新增
		if(!isset($_POST["mcoNo"]) || $_POST["mcoNo"] == ""){
			$orDataInputArr['mcoCaseNo'] = $or->product_number('1');
			// $orDataInputArr['orRealCaseNo'] = $or->getRealCaseNo();
			$orDataInputArr['memNo'] = $memNo;
			$orDataInputArr["mcoIfEditable"] = '0';
			$orDataInputArr['mcoStatus'] = '110';
			
			// 新增訂單
			$api->insert($orDataInputArr);
			$mcoNo = $api->db->getIdForInsertedRecord();
			
			//新增聯絡人
			$rc = new API("real_cases");			
			
			$rc->setWhereArray(array("rcRelateDataNo"=>$mcoNo,"rcType"=>$_POST["mcoType"]));
			$rc->getWithWhereAndJoinClause();
			$rcData = $rc->getData();
			if($rcData != null){
				$rcNo = $rcData[0]["rcNo"];
				$orderContact->setWhereArray(array("rcNo"=>$no));
				$orderContact->setOrderArray(array("ContactSort"=>false));
				$ocData=$orderContact->getWithConditions();
				$arrayName = json_decode($orDataInputArr['mcoContactName']);
				$arrayRelation = json_decode($orDataInputArr['mcoContactRelation']);
				$arrayPhone = json_decode($orDataInputArr['mcoContactPhone']);
				$arrayCell = json_decode($orDataInputArr['mcoContactCell']);

				for ($i=0; $i < count($arrayName); $i++) {
					if (count($ocData)<=($i+1) && count($ocData)>0) {
						$sql = "UPDATE orderContact 
								SET rcContactName='".$arrayName[$i]."'
								,rcContactRelation='".$arrayRelation[$i]."'
								,rcContactPhone='".$arrayPhone[$i]."'
								,rcContactCell='".$arrayCell[$i]."'
								WHERE rcno='".$rcNo."' and ContactSort='".($i+1)."'";
						$orderContact->customSql($sql);
					}else{
						$sql = array(
							"rcNo"=>$rcNo,
							"ContactSort"=>($i+1),
							"rcContactName"=>$arrayName[$i],
							"rcContactRelation"=>$arrayRelation[$i],
							"rcContactPhone"=>$arrayPhone[$i],
							"rcContactCell"=>$arrayCell[$i]
						);
						$orderContact->insert($sql);
					}					
				}
			}
			
			$api->setInformation(array("mcoNo"=>$mcoNo), 1, 1, "訂單基本資料填寫成功。");
		//編輯
		}else{
			$api->update($orDataInputArr,$_POST["mcoNo"]);
			
			//新增聯絡人
			$rc = new API("real_cases");
			
			$rc->setWhereArray(array("rcRelateDataNo"=>$_POST["mcoNo"],"rcType"=>($_POST["mcoType"] == "1" ? "2" : "1")));
			$rc->getWithWhereAndJoinClause();
			$rcData = $rc->getData();
			if($rcData != null){
				$rcNo = $rcData[0]["rcNo"];
				
				$orderContact->setWhereArray(array("rcNo"=>$no));
				$orderContact->setOrderArray(array("ContactSort"=>false));
				$ocData=$orderContact->getWithConditions();
				$arrayName = json_decode($orDataInputArr['mcoContactName']);
				$arrayRelation = json_decode($orDataInputArr['mcoContactRelation']);
				$arrayPhone = json_decode($orDataInputArr['mcoContactPhone']);
				$arrayCell = json_decode($orDataInputArr['mcoContactCell']);

				for ($i=0; $i < count($arrayName); $i++) {
					if (count($ocData)<=($i+1) && count($ocData)>0) {
						$sql = "UPDATE orderContact 
								SET rcContactName='".$arrayName[$i]."'
								,rcContactRelation='".$arrayRelation[$i]."'
								,rcContactPhone='".$arrayPhone[$i]."'
								,rcContactCell='".$arrayCell[$i]."'
								WHERE rcno='".$rcNo."' and ContactSort='".($i+1)."'";
						$orderContact->customSql($sql);
					}else{
						$sql = array(
							"rcNo"=>$rcNo,
							"ContactSort"=>($i+1),
							"rcContactName"=>$arrayName[$i],
							"rcContactRelation"=>$arrayRelation[$i],
							"rcContactPhone"=>$arrayPhone[$i],
							"rcContactCell"=>$arrayCell[$i]
						);
						$orderContact->insert($sql);
					}					
				}
			}
			
			$api->setInformation(array("mcoNo"=>$_POST["mcoNo"]), 1, 1, "訂單基本資料修改成功。");
		}
	}else{
		$api->setInformation(false, 0, 0, $errMsg);
	}
}else{
	$api->setInformation(false, 0, 0, $errMsg);
}
$api->setResult();


?>