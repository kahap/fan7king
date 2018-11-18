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
$orColumns = $or->getAllColumnNames("orders");
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
$memMustFill = array("memName","memIdNum","memBday","memClass","memAddr","memPhone","memCell");
if($_POST["memClass"] == '學生'){
	$memMustFill[] = "memAccount";
	$memMustFill[] = "memSchool";
	$memColumnsCheck = array("memName","memIdNum","memBday","memCompanyName","memSalary","memYearWorked","memClass","memPostCode","memAddr","memPhone","memCell","memAccount","memSchool");
}else if($_POST["memClass"] == '非學生'){
	$memMustFill[] = "memSubEmail";
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
$orMustFill = array(
	"orIfSecret","orAppApplierBirthAddr","orAppApplierBirthPhone","orAppApplierLivingOwnership",
	"orReceiveName","orReceiveAddr","orReceiveCell","orReceivePhone","orProSpec","orPeriodAmnt","pmNo",
	"orBusinessNumIfNeed","orAppContactRelaName","orAppContactRelaRelation","orAppContactRelaCell","orAppContactFrdName",
	"orAppContactFrdRelation","orAppContactFrdCell"
);
$orColumnsCheck = array(
	"orIfSecret","orAppApplierBirthAddr","orAppApplierBirthPhone","orAppApplierLivingOwnership","orAppApplierBirthAddrPostCode",
	"orReceiveName","orReceiveAddr","orReceiveCell","orProSpec","orPeriodAmnt","pmNo",
	"orBusinessNumIfNeed","orAppContactRelaName","orAppContactRelaRelation","orAppContactRelaCell","orAppContactFrdName",
	"orAppContactFrdRelation","orAppContactFrdCell","memClass",
	"orAppApplierCompanyPhone","orAppApplierCompanyPhoneExt","orAppApplierCreditNum","orAppApplierCreditSecurityNum","orAppApplierCreditIssueBank",
	"orAppApplierCreditDueDate","orReceivePhone","orReceiveComment","orBusinessNumNumber","orBusinessNumTitle",
	"orAppContactRelaPhone","orAppContactFrdPhone","orAppExtraAvailTime","orAppExtraInfo",
	"orIdIssueYear","orIdIssueMonth","orIdIssueDay","orIdIssuePlace","orIdIssueType"
);
$orDataInputArr = array();
//確認必填
foreach($orMustFill as $key=>$value){
	if(!isset($_POST[$value])){
		$errMsg[] = "Missing ".$orColumnssArr[$value].".";
	}
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
			$orDataInputArr['orAppApplierCompanystatus'] = '1';
		}else{
			$orDataInputArr['orAppApplierCompanystatus'] = '0';
		}
		//親屬欄位
		if(is_array($orDataInputArr['orAppContactRelaName'])){
			$orAppContactRelaName = $orDataInputArr['orAppContactRelaName'];
		}else{
			$orAppContactRelaName = array($orDataInputArr['orAppContactRelaName']);
		}
		foreach($orAppContactRelaName as $n => $v){
			if($n == (count($orAppContactRelaName)-2) or $n == (count($orAppContactRelaName)-1)){
				$orAppContactRelaName1[] = urlencode($v);
			}
		}
		if(is_array($orDataInputArr['orAppContactRelaRelation'])){
			$orAppContactRelaRelation = $orDataInputArr['orAppContactRelaRelation'];
		}else{
			$orAppContactRelaRelation = array($orDataInputArr['orAppContactRelaRelation']);
		}
		foreach($orAppContactRelaRelation as $n => $v){
			if($n == (count($orAppContactRelaRelation)-2) or $n == (count($orAppContactRelaRelation)-1)){
				$orAppContactRelaRelation1[] = urlencode($v);
			}
		}
		if(is_array($orDataInputArr['orAppContactRelaPhone'])){
			$orAppContactRelaPhone = $orDataInputArr['orAppContactRelaPhone'];
		}else{
			$orAppContactRelaPhone = array($orDataInputArr['orAppContactRelaPhone']);
		}
		foreach($orAppContactRelaPhone as $n => $v){
			if($n == (count($orAppContactRelaPhone)-2) or $n == (count($orAppContactRelaPhone)-1)){
				$orAppContactRelaPhone1[] = urlencode($v);
			}
		}
		if(is_array($orDataInputArr['orAppContactRelaCell'])){
			$orAppContactRelaCell = $orDataInputArr['orAppContactRelaCell'];
		}else{
			$orAppContactRelaCell = array($orDataInputArr['orAppContactRelaCell']);
		}
		foreach($orAppContactRelaCell as $n => $v){
			if($n == (count($orAppContactRelaCell)-2) or $n == (count($orAppContactRelaCell)-1)){
				$orAppContactRelaCell1[] = urlencode($v);
			}
		}
		
		if(is_array($orDataInputArr['orAppContactFrdName'])){
			$orAppContactFrdName = $orDataInputArr['orAppContactFrdName'];
		}else{
			$orAppContactFrdName = array($orDataInputArr['orAppContactFrdName']);
		}
		foreach($orAppContactFrdName as $n => $v){
			if($n == (count($orAppContactFrdName)-2) or $n == (count($orAppContactFrdName)-1)){
				$orAppContactFrdName1[] = urlencode($v);
			}
		}
		if(is_array($orDataInputArr['orAppContactFrdRelation'])){
			$orAppContactFrdRelation = $orDataInputArr['orAppContactFrdRelation'];
		}else{
			$orAppContactFrdRelation = array($orDataInputArr['orAppContactFrdRelation']);
		}
		foreach($orAppContactFrdRelation as $n => $v){
			if($n == (count($orAppContactFrdRelation)-2) or $n == (count($orAppContactFrdRelation)-1)){
				$orAppContactFrdRelation1[] = urlencode($v);
			}
		}
		if(is_array($orDataInputArr['orAppContactFrdPhone'])){
			$orAppContactFrdPhone = $orDataInputArr['orAppContactFrdPhone'];
		}else{
			$orAppContactFrdPhone = array($orDataInputArr['orAppContactFrdPhone']);
		}
		foreach($orAppContactFrdPhone as $n => $v){
			if($n == (count($orAppContactFrdPhone)-2) or $n == (count($orAppContactFrdPhone)-1)){
				$orAppContactFrdPhone1[] = urlencode($v);
			}
		}
		
		if(is_array($orDataInputArr['orAppContactFrdCell'])){
			$orAppContactFrdCell = $orDataInputArr['orAppContactFrdCell'];
		}else{
			$orAppContactFrdCell = array($orDataInputArr['orAppContactFrdCell']);
		}
		foreach($orAppContactFrdCell as $n => $v){
			if($n == (count($orAppContactFrdCell)-2) or $n == (count($orAppContactFrdCell)-1)){
				$orAppContactFrdCell1[] = urlencode($v);
			}
		}
		
		$orDataInputArr['orAppContactRelaName'] = urldecode(json_encode($orAppContactRelaName1));
		$orDataInputArr['orAppContactRelaRelation'] = urldecode(json_encode($orAppContactRelaRelation1));
		$orDataInputArr['orAppContactRelaPhone'] = urldecode(json_encode($orAppContactRelaPhone1));
		$orDataInputArr['orAppContactRelaCell'] = urldecode(json_encode($orAppContactRelaCell1));
		$orDataInputArr['orAppContactFrdName'] = urldecode(json_encode($orAppContactFrdName1));
		$orDataInputArr['orAppContactFrdRelation'] = urldecode(json_encode($orAppContactFrdRelation1));
		$orDataInputArr['orAppContactFrdPhone'] = urldecode(json_encode($orAppContactFrdPhone1));
		$orDataInputArr['orAppContactFrdCell'] = urldecode(json_encode($orAppContactFrdCell1));
		
		//新增
		if(!isset($_POST["orNo"]) || $_POST["orNo"] == ""){
			//預設訂單其他欄位
			$pm = new Product_Manage();
			$ps = new Period_Setting();
			$pp = new Product_Period();
			$psData = $ps->getAllPS();
			//利率待修改區-START
			$pmData = $pm->getOnePMByNo($_POST["pmNo"]);
			$ppData = $pp->getPPByProduct($pmData[0]["proNo"]);
			$followDefault = true;
			if($ppData != null){
				foreach($ppData as $key=>$value){
					if($value["ppPercent"] != "" && $value["ppPeriodAmount"] == $_POST["orPeriodAmnt"]){
						$followDefault = false;
					}
				}
			}
			if($followDefault){
				foreach($psData as $key=>$value){
					if($value["psMonthNum"] == $_POST["orPeriodAmnt"]){
						$ratio = $value["psRatio"];
					}
				}
			}else{
				foreach($ppData as $key=>$value){
					if($value["ppPeriodAmount"] == $_POST["orPeriodAmnt"]){
						$ratio = $value["ppPercent"];
					}
				}
			}
			$orderTotal = ceil(ceil(ceil($pmData[0]['pmPeriodAmnt']*$ratio)/$_POST["orPeriodAmnt"])*$_POST["orPeriodAmnt"]);	
			//利率待修改區-END
			$orDataInputArr['orCaseNo'] = $or->product_number('1');
			// $orDataInputArr['orRealCaseNo'] = $or->getRealCaseNo();
			$orDataInputArr['memNo'] = $memNo;
			$orDataInputArr['orSupPrice'] = $pmData[0]['pmSupPrice'];
			$orDataInputArr['orMethod'] = '1';
			$orDataInputArr["orIfEditable"] = '0';
			$orDataInputArr['orPeriodTotal'] = $orderTotal;
			$orDataInputArr['orIpAddress'] = $myip;
			$orDataInputArr['orStatus'] = '110';
			$orDataInputArr['orReportPeriod110Date'] =  date('Y-m-d H:i:s',time());
			$orDataInputArr['supNo'] = $pmData[0]["supNo"];
			$orDataInputArr['orDate'] = date('Y-m-d H:i:s',time());
			$orDataInputArr['orAmount'] = '1';
			
			// 新增訂單
			$api->insert($orDataInputArr);
			$orNo = $api->db->getIdForInsertedRecord();
			
			//新增聯絡人
			$rc = new API("real_cases");
			
			$rc->setWhereArray(array("rcRelateDataNo"=>$orNo,"rcType"=>"0"));
			$rc->getWithWhereAndJoinClause();
			$rcData = $rc->getData();
			if($rcData != null){
				$rcNo = $rcData[0]["rcNo"];
				
				if(empty(array_filter($orAppContactRelaName1))){
					$finalRelaName = $orAppContactFrdName1;
					$finalRelaRealtion = $orAppContactFrdRelation;
					$finalRelaPhone = $orAppContactFrdPhone1;
					$finalRelaCell = $orAppContactFrdCell1;
				}else if(empty(array_filter($orAppContactFrdName1))){
					$finalRelaName = $orAppContactRelaName1;
					$finalRelaRealtion = $orAppContactRelaRelation1;
					$finalRelaPhone = $orAppContactRelaPhone1;
					$finalRelaCell = $orAppContactRelaCell1;
				}/*else{
					$finalRelaName = array_merge($orAppContactRelaName1,$orAppContactFrdName1);
					$finalRelaRealtion = array_merge($orAppContactRelaRelation1,$orAppContactFrdRelation1);
					$finalRelaPhone = array_merge($orAppContactRelaPhone1,$orAppContactFrdPhone1);
					$finalRelaCell = array_merge($orAppContactRelaCell1,$orAppContactFrdCell1);
				}*/
				$orderContact->setWhereArray(array("rcNo"=>$no));
				$orderContact->setOrderArray(array("ContactSort"=>false));
				$ocData=$orderContact->getWithConditions();
				$arrayName = urldecode($finalRelaName);
				$arrayRelation = urldecode($finalRelaRealtion);
				$arrayPhone = urldecode($finalRelaPhone);
				$arrayCell = urldecode($finalRelaCell);
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
			
			$api->setInformation(array("orNo"=>$orNo), 1, 1, "訂單基本資料填寫成功。");
		//編輯
		}else{
			$api->update($orDataInputArr,$_POST["orNo"]);
			
			//新增聯絡人
			$rc = new API("real_cases");
			
			$rc->setWhereArray(array("rcRelateDataNo"=>$_POST["orNo"],"rcType"=>"0"));
			$rc->getWithWhereAndJoinClause();
			$rcData = $rc->getData();
			if($rcData != null){
				$rcNo = $rcData[0]["rcNo"];
				
				/*$finalRelaName = array_merge($orAppContactRelaName1,$orAppContactFrdName1);
				$finalRelaRealtion = array_merge($orAppContactRelaRelation1,$orAppContactFrdRelation1);
				$finalRelaPhone = array_merge($orAppContactRelaPhone1,$orAppContactFrdPhone1);
				$finalRelaCell = array_merge($orAppContactRelaCell1,$orAppContactFrdCell1);*/
				$finalRelaName =$orAppContactFrdName1;
				$finalRelaRealtion = $orAppContactFrdRelation1;
				$finalRelaPhone = $orAppContactFrdPhone1;
				$finalRelaCell = $orAppContactFrdCell1;

				$orderContact->setWhereArray(array("rcNo"=>$no));
				$orderContact->setOrderArray(array("ContactSort"=>false));
				$ocData=$orderContact->getWithConditions();
				$arrayName = urldecode($finalRelaName);
				$arrayRelation = urldecode($finalRelaRealtion);
				$arrayPhone = urldecode($finalRelaPhone);
				$arrayCell = urldecode($finalRelaCell);
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
			
			$api->setInformation(array("orNo"=>$_POST["orNo"]), 1, 1, "訂單基本資料修改成功。");
		}
	}else{
		$api->setInformation(false, 0, 0, $errMsg);
	}
}else{
	$api->setInformation(false, 0, 0, $errMsg);
}
$api->setResult();


?>