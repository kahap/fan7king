<?php
	session_start();
    // 关闭错误报告
    error_reporting(0);
	include('../../model/php_model.php');

	$or = new Orders();
	$member = new Member();
	$lg = new Loyal_Guest();
	$orderContact = new API("orderContact");


	$columnName = $or->getAllColumnNames("orders");
	foreach($columnName as $key => $value){
		$colum[$value['COLUMN_NAME']] = $value['COLUMN_COMMENT'];
	}
	$columnName1 = $or->getAllColumnNames("member");
	foreach($columnName1 as $key => $value){
		$colum1[$value['COLUMN_NAME']] = $value['COLUMN_COMMENT'];
	}

	$iforder = $or->getOrderhistory($_SESSION['user']['memNo']);
	$memberData = $member->getOneMemberByNo($_SESSION['user']['memNo']);
	$notbing = array(
	    'orAppApplierCompanyName','orAppApplierCompanyPhone','orAppApplierCompanyPhoneExt','orBusinessNumTitle',
        'orAppApplierCreditNum_1','orAppApplierCreditNum_2','orAppApplierCreditNum_3','orAppApplierCreditNum_4','orAppApplierCreditSecurityNum',
        'orAppApplierCreditIssueBank','orAppApplierCreditDueDate','orAppExtraInfo','orAppExtraAvailTime','orAppApplierYearExperience',
        'orBusinessNumNumber','orAppApplierMonthSalary','orAppApplierCreditDueDate_1','orAppApplierCreditDueDate_2','memOther',
        'orReceiveComment','orAppApplierCreditstatus','orAppApplierCompanystatus','orAppContactFrdPhone','orReceivePhone',
        'orAppContactRelaPhone','memAccount','memClass'
    );
	$notbing1 = array('orAppApplierCompanyName','orAppApplierCompanyPhone','orAppApplierCompanyPhoneExt','orBusinessNumTitle',
        'orAppApplierCreditNum_1','orAppApplierCreditNum_2','orAppApplierCreditNum_3','orAppApplierCreditNum_4','orAppApplierCreditSecurityNum',
        'orAppApplierCreditIssueBank','orAppApplierCreditDueDate','orAppExtraInfo','orAppExtraAvailTime','orAppApplierYearExperience',
        'orBusinessNumNumber','orAppApplierMonthSalary','orAppApplierCreditDueDate_1','orAppApplierCreditDueDate_2','memOther',
        'orReceiveComment','orAppApplierCreditstatus','orAppApplierCompanystatus','orAppContactFrdPhone','orReceivePhone',
        'orAppContactRelaPhone','memSchool','memAccount','memClass','school','department','classyear'
    );

	//公司名稱，年資，月薪，公司市話
	$class_1 = array('orAppApplierCompanyName','orAppApplierYearExperience','orAppApplierMonthSalary','orAppApplierCompanyPhone');


	//戶籍市話,戶籍地址郵遞區號,戶籍地址,親屬姓名,親屬關係,朋友姓名,朋友關係,
    //請填寫公司名稱 ,請填寫年資 ,請填寫月薪 ,請填寫公司市話
    $errg = array();
	if($_SESSION['user']['memNo'] != ""){
		$errg = false;
		foreach($_POST as $key =>$value){
		    if ($key=='orAppApplierCompanystatus' && $value==1) {   //選有工作，判斷工作資料有無填寫
                if ($value == "") {
                    if ($_POST['memClass'] == 0) {
                        if (!in_array($key, $notbing)) {
                            $errg[] = ($colum[$key] != '') ? $colum[$key] : $colum1[$key];
                        }
                    } else {
                        if (!in_array($key, $notbing1)) {
                            $errg[] = ($colum[$key] != '') ? $colum[$key] : $colum1[$key];
                        }
                    }
                }
            }
		}
	}

	if($_SESSION['shopping_user'][0]['memNo'] == ""){
		$errg[] = "錯誤的進入方式";
	}
	if(!empty($_SERVER['HTTP_CLIENT_IP'])){
	   $myip = $_SERVER['HTTP_CLIENT_IP'];
	}else if(!empty($_SERVER['HTTP_X_FORWARDED_FOR'])){
	   $myip = $_SERVER['HTTP_X_FORWARDED_FOR'];
	}else{
	   $myip= $_SERVER['REMOTE_ADDR'];
	}

	//如果有學校email身分又是學生
	if( $_POST['memAccount'] != ""){
		if($_POST['memClass'] == 0){
			If(strrpos($_POST['memAccount'],'@') == false){
				$errg[] = "認證Email不是確實的Email ";
			}
			if(strrpos($_POST['memAccount'],'edu') == false){
				$errg[] = "請填寫學校Email做為認證 ";
			}
//			$school = trim($_POST['school']);
//			If($school==''){
//				$errg[] = "請填寫學校";
//			}

//			if($school != ''){
//				foreach($_POST['department'] as $key => $value){
//					if($value != ""){
//						$department_data = $value;
//					}
//				}
//			}

//			if($department_data == ""){
//				$errg[] = "請選擇系所";
//			}
		}
	}else{
		if($memberData[0]["memFBtoken"] != "" && $_POST['memClass'] == 0){
			$errg[] = "認證Email不能為空";
		}
	}

//	if($_POST['memClass'] == 'x'){
//		$errg[] = "請選擇身分別";
//	}

    //判斷工作欄位有無填寫
	if($_POST['memClass'] == '4' && $_POST['orAppApplierCompanystatus']==1){
		foreach($class_1 as $key => $value){
			if($_POST[$value] == ''){
				$errg[] = ($colum[$value] != '') ? "請填寫".$colum[$value]." ":"請填寫".$colum1[$value]." ";
			}
		}
	}

	If(strrpos($_POST['memSubEmail'],'@') == false){
		$errg[] = "'常用聯絡Email不是確實的Email ";
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
	$msg = implode("," ,$errg);
	if($msg == ""){ //錯誤訊息等於空
		$_POST['orCaseNo'] = $or->product_number('1');
		//$_POST['orRealCaseNo'] = $or->getRealCaseNo();
		$_POST['memNo'] = $_SESSION['shopping_user'][0]['memNo'];
		$_POST['pmNo'] = $_SESSION['shopping_product'][0]['pmNo'];
		$_POST['orSupPrice'] = $_SESSION['shopping_product'][0]['pmSupPrice'];
		$_POST['pmPeriodAmnt'] = $_SESSION['shopping_product'][0]['pmPeriodAmnt'];
		$_POST['orMethod'] = '1';
		$_POST['orProSpec'] = $_SESSION['shopping_spec'];
		$_POST['orPeriodAmnt'] = $_SESSION['shopping_period'];
		$_POST['orPeriodTotal'] = $_SESSION['shopping_period']*$_SESSION['shopping_price'];
		$_POST['orIpAddress'] = $myip;

		$_POST['orStatus'] = '110';
		$_POST['orReportPeriod110Date'] =  date('Y-m-d H:i:s',time());

		$_POST['supNo'] = $_SESSION['shopping_product'][0]['supNo'];
		$_POST['orAppApplierCreditNum'] = $_POST['orAppApplierCreditNum_1']."-".$_POST['orAppApplierCreditNum_2']."-".$_POST['orAppApplierCreditNum_3']."-".$_POST['orAppApplierCreditNum_4'];
		$_POST['orDate'] = date('Y-m-d H:i:s',time());
		$_POST['orAmount'] = '1';
		$_POST['orAppApplierCreditDueDate'] = ($_POST['orAppApplierCreditDueDate_1'] != "") ? $_POST['orAppApplierCreditDueDate_1']."/".$_POST['orAppApplierCreditDueDate_2']:"";


		/*$orAppContactRelaName = array($_POST['orAppContactRelaName']);
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
		$_POST['orAppContactFrdCell'] = urldecode(json_encode($orAppContactFrdCell1));*/



		$_array['memBday'] = $_POST['year']."-".$_POST['month']."-".$_POST['date'];
		$_array['memSchool'] = $_POST['memSchool'];
		$_array['memClass'] = $_POST['memClass'];
		$_array['memOther'] = $_POST['memOther'];
		$_array['memName'] = $_POST['memName'];
		$_array['memIdNum'] = $_POST['memIdNum'];
		$_array['memGender'] = (substr($_POST['memIdNum'],1,1) == 1) ? '1':'0';
		$_array['memPhone'] = $_POST['memPhone'];
		$_array['memCell'] = $_POST['memCell'];
		$_array['memAddr'] = $_POST['memAddr'];
		$_array['memAccount'] = $_POST['memAccount'];
		$_array['memSubEmail'] = $_POST['memSubEmail'];



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

		/*if($_POST['school'] != ''){
			$school = new School();
			$schoolName = $school->getOne($_POST['school']);
			$memSchool = array($schoolName['0']['schName'],$department_data,$_POST['classyear']);
			$_POST['memSchool'] = implode('#',$memSchool);
			$array_8['memSchool'] = $_POST['memSchool'];
			$member->updatememAddr($array_8,$_SESSION['user']['memNo']);
		}*/



		if($iforder == ''){
			$array_9['memBday'] = $_array['memBday'];
			$member->updatememAddr($array_9,$_SESSION['user']['memNo']);
		}

		if($_POST['memClass'] == '0' && $_POST['memAccount'] != ''){
			if($_POST['memAccount'] != $memberData[0]['memAccount']){
				$array_10['memAccount'] = $_POST['memAccount'];
				$member->updatememAddr($array_10,$_SESSION['user']['memNo']);
			}
		}
		//jimmy
		$pm = new Product_Manage();
		$pmData = $pm->getOnePMByNo($_POST['pmNo']);
		$_POST['pmPeriodAmnt'] = $pmData[0]['pmPeriodAmnt'];
		//jimmy
		$orNo = $or->insertorder($_POST);

		//新增聯絡人
		$rc = new API("real_cases");

		$rc->setWhereArray(array("rcRelateDataNo"=>$orNo));
		$rc->getWithWhereAndJoinClause();
		$rcData = $rc->getData();
		if($rcData != null){
			$rcNo = $rcData[0]["rcNo"];


			/*if(empty(array_filter($orAppContactRelaName1))){
				$finalRelaName = $orAppContactFrdName1;
				$finalRelaRealtion = $orAppContactFrdRelation;
				$finalRelaPhone = $orAppContactFrdPhone1;
				$finalRelaCell = $orAppContactFrdCell1;
			}else if(empty(array_filter($orAppContactFrdName1))){
				$finalRelaName = $orAppContactRelaName1;
				$finalRelaRealtion = $orAppContactRelaRelation1;
				$finalRelaPhone = $orAppContactRelaPhone1;
				$finalRelaCell = $orAppContactRelaCell1;
			}else{
				$finalRelaName = array_merge($orAppContactRelaName1,$orAppContactFrdName1);
				$finalRelaRealtion = array_merge($orAppContactRelaRelation1,$orAppContactFrdRelation1);
				$finalRelaPhone = array_merge($orAppContactRelaPhone1,$orAppContactFrdPhone1);
				$finalRelaCell = array_merge($orAppContactRelaCell1,$orAppContactFrdCell1);
			}
			$orderContact->setWhereArray(array("rcNo"=>$rcNo));
			$orderContact->setOrderArray(array("ContactSort"=>false));
			$ocData=$orderContact->getWithConditions();
			$arrayName = urldecode($finalRelaName);
			$arrayRelation = urldecode($finalRelaRealtion);
			$arrayPhone = urldecode($finalRelaPhone);
			$arrayCell = urldecode($finalRelaCell);*/



            $arrayName = array($_POST['orAppContactRelaName'],$_POST['orAppContactFrdName']);
            $arrayRelation = array($_POST['orAppContactRelaRelation'],$_POST['orAppContactFrdRelation']);
            $arrayPhone = array($_POST['orAppContactRelaPhone'],$_POST['orAppContactFrdPhone']);
            $arrayCell = array( $_POST['orAppContactRelaCell'],$_POST['orAppContactFrdCell']);
            for ($i=0; $i < 2; $i++) {
//            for ($i=0; $i < count($arrayName); $i++) {
				if ($ocData && count($ocData)<=($i+1) && count($ocData)>0) {
					$sql = "UPDATE orderContact 
							SET rcContactName='".$arrayName[$i]."'
							,rcContactRelation='".$arrayRelation[$i]."'
							,rcContactPhone='".$arrayPhone[$i]."'
							,rcContactCell='".$arrayCell[$i]."'
							WHERE rcNo='".$rcNo."' and ContactSort='".($i+1)."'";
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
			$sql = "UPDATE `real_cases` SET `rcBirthAddrPostCode`='".$_POST['orAppApplierBirthAddrPostCode']."',
										`rcBirthAddr` = '".$_POST['orAppApplierBirthAddr']."' where `rcRelateDataNo`='".$orNo."'";
			$member->updaterealcasePostcode($sql);
		}

		$_SESSION['ord_code'] = $orNo;

		//郵政區號
		if($_POST['memPostCode'] != $memberData[0]['memPostCode']){
			$array['memPostCode'] = $_POST['memPostCode'];
			$member->updatememAddr($array,$_SESSION['user']['memNo']);
		}


        //申請案件如需保密請打勾（照會親友聯絡人時，不告知購買事由）
		if($_POST['agree'] != ''){
			$or->updateorIfSecret('1',$orNo);
		}else{
			$or->updateorIfSecret('NULL',$orNo);
		}

		if($_SESSION['shopping_user'][0]['memEmailAuthen'] == '0'){
			echo "2";
		}else{
			echo "2";
		}
	}else{
		echo $msg;
	}



//function validEmail($email) {
//	$regexp = "^([_a-z0-9-]+)(\.[_a-z0-9-]+)*@([a-z0-9-]+)(\.[a-z0-9-]+)*(\.[a-z]{2,4})$";
//	if (eregi($regexp, $email)) {
//		list($user, $domain) = split("@", $email);
//		//判斷該網址是否存在，建議使用checkdnsrr或getmxrr，但win32下無法使用
//		if (gethostbyname($domain) != $domain) { return true; }
//	}else{
//		return false;
//	}
//}
?>