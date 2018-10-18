<?php
	session_start();
	include('../model/php_model.php');
	$or = new Orders();
	$pm = new Product_Manage();
	$pro = new Product();
	$or_data = $or->getOneOrderByNo($_POST['orno']);
	
	$pmData = $pm->getOnePMByNo($or_data[0]["pmNo"]);
	$proData = $pro->getOneProByNo($pmData[0]["proNo"]);
	
	$member = new Member();
	$lg = new Loyal_Guest();
	$memberData = $member->getOneMemberByNo($_SESSION['user']['memNo']);
	
	$iforder = $or->getOrderhistory($_SESSION['user']['memNo']);
	$columnName = $or->getAllColumnNames("orders");
	foreach($columnName as $key => $value){
		$colum[$value['COLUMN_NAME']] = $value['COLUMN_COMMENT'];
	}
	$notbing = array('orAppApplierCompanyName','orAppApplierCompanyPhone','orAppApplierCompanyPhoneExt','orBusinessNumTitle','orAppApplierCreditNum_1','orAppApplierCreditNum_2','orAppApplierCreditNum_3','orAppApplierCreditNum_4','orAppApplierCreditSecurityNum','orAppApplierCreditIssueBank','orAppApplierCreditDueDate','orAppExtraInfo','orAppExtraAvailTime','orAppApplierYearExperience','orBusinessNumNumber','orAppApplierMonthSalary','orAppApplierCreditDueDate_1','orAppApplierCreditDueDate_2','memOther','orReceiveComment','orAppApplierCreditstatus','orAppApplierCompanystatus','orAppContactFrdPhone','orReceivePhone','orAppContactRelaPhone','memAccount','memClass');
	$notbing1 = array('orAppApplierCompanyName','orAppApplierCompanyPhone','orAppApplierCompanyPhoneExt','orBusinessNumTitle','orAppApplierCreditNum_1','orAppApplierCreditNum_2','orAppApplierCreditNum_3','orAppApplierCreditNum_4','orAppApplierCreditSecurityNum','orAppApplierCreditIssueBank','orAppApplierCreditDueDate','orAppExtraInfo','orAppExtraAvailTime','orAppApplierYearExperience','orBusinessNumNumber','orAppApplierMonthSalary','orAppApplierCreditDueDate_1','orAppApplierCreditDueDate_2','memOther','orReceiveComment','orAppApplierCreditstatus','orAppApplierCompanystatus','orAppContactFrdPhone','orReceivePhone','orAppContactRelaPhone','memAccount','memSchool','memAccount','memClass','school','department','classyear');
	
	$class_1 = array('orAppApplierCompanyName','orAppApplierYearExperience','orAppApplierMonthSalary','orAppApplierCompanyPhone');
	if($_SESSION['user']['memNo'] != ""){
		foreach($_POST as $key =>$value){
			if($value == ""){
				if($_POST['memClass'] == 0){
					if (!in_array($key, $notbing)){
						$errg[] = $colum[$key]."欄位填寫有誤，請確實檢查";
					}
				}else{
					if (!in_array($key, $notbing1)){
						$errg[] = $colum[$key]."欄位填寫有誤，請確實檢查";
					}
				}
			}
		}
	}
	
	if(!empty($_SERVER['HTTP_CLIENT_IP'])){
	   $myip = $_SERVER['HTTP_CLIENT_IP'];
	}else if(!empty($_SERVER['HTTP_X_FORWARDED_FOR'])){
	   $myip = $_SERVER['HTTP_X_FORWARDED_FOR'];
	}else{
	   $myip= $_SERVER['REMOTE_ADDR'];
	}
	
	if($_POST['memAccount'] != ""){
		if($_POST['memClass'] == 0){			
			If(strrpos($_POST['memAccount'],'@') == false){
				$errg[] = "認證Email不是確實的Email ";
			}
			if(strrpos($_POST['memAccount'],'edu') == false){
				$errg[] = "請填寫學校Email做為認證 ";
			}
			$school = trim($_POST['school']);
			If($school==''){
				$errg[] = "請填寫學校";
			}
			
			if($school != ''){
				foreach($_POST['department'] as $key => $value){
					if($value != ""){
						$department_data = $value;
					}
				}
			}
			
			if($department_data == ""){
				$errg[] = "請選擇系所";
			}
		}
	}else{
		if($memberData[0]["memFBtoken"] != "" && $_POST['memClass'] == 0){
			$errg[] = "認證Email不能為空";
		}
	}
	
	If(strrpos($_POST['memSubEmail'],'@') == false){
		$errg[] = "常用聯絡Email不是確實的Email ";
	}
	
	if($_POST['memClass'] == 'x'){
		$errg[] = "請選擇身分別";
	}
	
	if($_POST['memClass'] == '4'){
		foreach($class_1 as $key => $value){
			if($_POST[$value] == ''){
				$errg[] = ($colum[$value] != '') ? "請填寫".$colum[$value]." ":"請填寫".$colum1[$value]." ";
			}
		}
	}
	
	if($_POST['memIdNum'] != ""){
		$sql = "SELECT count(aa.orStatus) as number,aa.orStatus, aa.memNo, bb.memIdNum FROM `orders` aa, member bb WHERE aa.memNo = bb.memNo && bb.memIdNum='".$_POST['memIdNum']."' group by aa.orStatus,aa.memNo";
		/*$data = $or->customsql($sql);
		$score = 0;
		foreach($data as $key => $value){
			if($value['orStatus'] == '4'){
				$score += 1;
			}elseif($value['orStatus'] != '110'){
				$score -= 1;
			}
		}
		if($score >0){
			$errg[] = "曾下單審查不通過之用戶在婉拒後6個月內將無法再次申請分期，如有疑問請洽客服。";
		}*/ 
	}
	
	$msg = implode(',',$errg);
	
	if($msg == ""){
		$_POST['orIpAddress'] = $myip;
		
		$_POST['orAppApplierCreditNum'] = ($_POST['orAppApplierCreditNum_1'] != '') ? $_POST['orAppApplierCreditNum_1']."-".$_POST['orAppApplierCreditNum_2']."-".$_POST['orAppApplierCreditNum_3']."-".$_POST['orAppApplierCreditNum_4']:'';
		$_POST['orAppApplierCreditDueDate'] = ($_POST['orAppApplierCreditDueDate_1'] != "") ? $_POST['orAppApplierCreditDueDate_1']."/".$_POST['orAppApplierCreditDueDate_2']:"";
		
		$orAppContactRelaName = array($_POST['orAppContactRelaName']);
		foreach($orAppContactRelaName as $n => $v){
			$orAppContactRelaName1[] = urlencode($v);
		}
		$orAppContactRelaRelation = array($_POST['orAppContactRelaRelation']);
		foreach($orAppContactRelaRelation as $n => $v){
			$orAppContactRelaRelation1[] = urlencode($v);
		}
		$orAppContactRelaPhone = array($_POST['orAppContactRelaPhone']);
		foreach($orAppContactRelaPhone as $n => $v){
			$orAppContactRelaPhone1[] = urlencode($v);
		}
		$orAppContactRelaCell = array($_POST['orAppContactRelaCell']);
		foreach($orAppContactRelaCell as $n => $v){
			$orAppContactRelaCell1[] = urlencode($v);
		}
		$orAppContactFrdName = array($_POST['orAppContactFrdName']);
		foreach($orAppContactFrdName as $n => $v){
			$orAppContactFrdName1[] = urlencode($v);
		}
		$orAppContactFrdRelation = array($_POST['orAppContactFrdRelation']);
		foreach($orAppContactFrdRelation as $n => $v){
			$orAppContactFrdRelation1[] = urlencode($v);
		}
		$orAppContactFrdPhone = array($_POST['orAppContactFrdPhone']);
		foreach($orAppContactFrdPhone as $n => $v){
			$orAppContactFrdPhone1[] = urlencode($v);
		}
		
		$orAppContactFrdCell = array($_POST['orAppContactFrdCell']);
		foreach($orAppContactFrdCell as $n => $v){
			$orAppContactFrdCell1[] = urlencode($v);
		}
		
		$_POST['orAppContactRelaName'] = urldecode(json_encode($orAppContactRelaName1));
		$_POST['orAppContactRelaRelation'] = urldecode(json_encode($orAppContactRelaRelation1));
		$_POST['orAppContactRelaPhone'] = urldecode(json_encode($orAppContactRelaPhone1));
		$_POST['orAppContactRelaCell'] = urldecode(json_encode($orAppContactRelaCell1));
		$_POST['orAppContactFrdName'] = urldecode(json_encode($orAppContactFrdName1));
		$_POST['orAppContactFrdRelation'] = urldecode(json_encode($orAppContactFrdRelation1));
		$_POST['orAppContactFrdPhone'] = urldecode(json_encode($orAppContactFrdPhone1));
		$_POST['orAppContactFrdCell'] = urldecode(json_encode($orAppContactFrdCell1));
		$_array['memBday'] = $_POST['year']."-".$_POST['month']."-".$_POST['date'];
		$aa = $or->updateorder($_POST,$or_data[0]['orNo']);
		
		//新增聯絡人
		$rc = new API('real_cases');
		
		/*$rc->setWhereArray(array("rcRelateDataNo"=>$or_data[0]['orNo']));
		$rc->getWithWhereAndJoinClause();
		$rcData = $rc->getData();
		if($rcData != null){
			$rcNo = $rcData[0]["rcNo"];
			
			$finalRelaName = array_merge($orAppContactRelaName1,$orAppContactFrdName1);
			$finalRelaRealtion = array_merge($orAppContactRelaRelation1,$orAppContactFrdRelation1);
			$finalRelaPhone = array_merge($orAppContactRelaPhone1,$orAppContactFrdPhone1);
			$finalRelaCell = array_merge($orAppContactRelaCell1,$orAppContactFrdCell1);
			
			$rcDataInput = array(
				"rcContactName"=>urldecode(json_encode($finalRelaName)),
				"rcContactRelation"=>urldecode(json_encode($finalRelaRealtion)),
				"rcContactPhone"=>urldecode(json_encode($finalRelaPhone)),
				"rcContactCell"=>urldecode(json_encode($finalRelaCell)),
			);
			
			$rc->update($rcDataInput,$rcNo);
		}*/
		$sql = "UPDATE `real_cases` SET `rcBirthAddrPostCode`='".$_POST['orAppApplierBirthAddrPostCode']."',
										`rcBirthAddr` = '".$_POST['orAppApplierBirthAddr']."' where `rcRelateDataNo`='".$or_data[0]['orNo']."'";
		$member->updaterealcasePostcode($sql);
		
		
		if($memberData[0]['memFBtoken'] != "" && $_POST['memAccount'] != ""){
			if($_POST['memAccount'] != $memberData[0]['memAccount']){
				$array_0['memAccount'] = $_POST['memAccount'];
				$member->updatememAddr($array_0,$_SESSION['user']['memNo']);
			}			
		}
		if($_POST['memPostCode'] != $memberData[0]['memPostCode']){
			$array['memPostCode'] = $_POST['memPostCode'];
			$member->updatememAddr($array,$_SESSION['user']['memNo']);
		}		
		if($_POST['memAddr'] != $memberData[0]['memAddr']){
			$array_1['memAddr'] = $_POST['memAddr'];
			$member->updatememAddr($array_1,$_SESSION['user']['memNo']);
		}
		if($_POST['memCell'] != $memberData[0]['memCell']){
			$array_2['memCell'] = $_POST['memCell'];
			$member->updatememAddr($array_2,$_SESSION['user']['memNo']);
		}
		if($_POST['memPhone'] != $memberData[0]['memPhone']){
			$array_3['memPhone'] = $_POST['memPhone'];
			$member->updatememAddr($array_3,$_SESSION['user']['memNo']);
		}
		if($_POST['memSubEmail'] != $memberData[0]['memSubEmail']){
			$array_4['memSubEmail'] = $_POST['memSubEmail'];
			$member->updatememAddr($array_4,$_SESSION['user']['memNo']);
		}
		if($iforder == ''){
			$array_5['memIdNum'] = $_POST['memIdNum'];
			$member->updatememAddr($array_5,$_SESSION['user']['memNo']);
		}
		if($iforder == ''){
			$array_6['memName'] = $_POST['memName'];
			$member->updatememAddr($array_6,$_SESSION['user']['memNo']);
		}
		if($_POST['memClass'] != $memberData[0]['memClass']){
			$array_7['memClass'] = $_POST['memClass'];
			$member->updatememAddr($array_7,$_SESSION['user']['memNo']);
		}
		if($_POST['school'] != ''){
			$school = new School();
			$schoolName = $school->getOne($_POST['school']);
			$memSchool = array($schoolName['0']['schName'],$department_data,$_POST['classyear']);
			$_POST['memSchool'] = implode('#',$memSchool);
			$array_8['memSchool'] = $_POST['memSchool'];
			$member->updatememAddr($array_8,$_SESSION['user']['memNo']);
		}
		
		if($iforder == ''){
			$array_9['memBday'] = $_array['memBday'];
			$member->updatememAddr($array_9,$_SESSION['user']['memNo']);
		}
		$_SESSION['ord_code'] = $or_data[0]['orNo'];
		if($_POST['agree'] != ''){
			$or->updateorIfSecret('1',$_SESSION['ord_code']);
		}else{
			$or->updateorIfSecret('NULL',$_SESSION['ord_code']);
		}
		
		if($_POST['memClass'] == '0' && $_POST['memAccount'] != ''){
			if($_POST['memAccount'] != $memberData[0]['memAccount']){
				$array_10['memAccount'] = $_POST['memAccount'];
				$member->updatememAddr($array_10,$_SESSION['user']['memNo']);
			}
		}
		
		if($_POST['memClass'] != $memberData[0]['memClass']){
			$array_11['memClass'] = $_POST['memClass'];
			$member->updatememAddr($array_11,$_SESSION['user']['memNo']);
		}
		
		$_SESSION['shopping_product'][0]['proName'] = $proData[0]["proName"];
		if($memberData[0]['memEmailAuthen'] == '0'){
			$_SESSION['shopping_user'][0]['memNo'] = $memberData[0]['memNo'];
			echo "2";
		}else{
			$_SESSION['shopping_user'][0]['memNo'] = $memberData[0]['memNo'];
			echo "2";
		}
	}else{
		echo $msg;
	}

function validEmail($email) {
	$regexp = "^([_a-z0-9-]+)(\.[_a-z0-9-]+)*@([a-z0-9-]+)(\.[a-z0-9-]+)*(\.[a-z]{2,4})$";
	if (eregi($regexp, $email)) {
		return true;
	}else{
		return false;
	}
}	
?>