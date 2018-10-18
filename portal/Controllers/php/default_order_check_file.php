<?php
	session_start();
	include('../model/php_model.php');
	$or = new Orders();
	$member = new Member();
	$memberData = $member->getOneMemberByNo($_SESSION['user']['memNo']);
	if($_SESSION['shopping_user'][0]['memNo'] != ""){
		$data = $or->getOneOrderByNo($_SESSION['ord_code']);
		$or_data = $or->getTwinOrByMemberAndMethod($_SESSION['user']['memNo'],'1');
		$or_dataDealFinish = $or->getDealFinish($_SESSION['user']['memNo'],'1');
		
		
		$array['orAppAuthenIdImgTop'] = (@$data[0]['orAppAuthenIdImgTop'] == '') ? ($or_data[1]['orAppAuthenIdImgTop'] == '') ? $or_dataDealFinish[0]['orAppAuthenIdImgTop']:$or_data[1]['orAppAuthenIdImgTop']:$data[0]['orAppAuthenIdImgTop'];
		
		$or->updateorAppAuthenIdImgTop($array['orAppAuthenIdImgTop'],$_SESSION['ord_code']);
		
		

		$array['orAppAuthenIdImgBot'] = (@$data[0]['orAppAuthenIdImgBot'] == '') ? ($or_data[1]['orAppAuthenIdImgBot'] == '') ? $or_dataDealFinish[0]['orAppAuthenIdImgBot']:$or_data[1]['orAppAuthenIdImgBot']:$data[0]['orAppAuthenIdImgBot'];
		
		
		$or->updateorAppAuthenIdImgBot($array['orAppAuthenIdImgBot'],$_SESSION['ord_code']);
		
	
		$array['orAppAuthenStudentIdImgTop'] = (@$data[0]['orAppAuthenStudentIdImgTop'] == '') ? ($or_data[1]['orAppAuthenStudentIdImgTop'] == '') ? $or_dataDealFinish[0]['orAppAuthenStudentIdImgTop']:$or_data[1]['orAppAuthenStudentIdImgTop']:$data[0]['orAppAuthenStudentIdImgTop'];
		$or->updateorAppAuthenStudentIdImgTop($array['orAppAuthenStudentIdImgTop'],$_SESSION['ord_code']);
		
		$array['orAppAuthenStudentIdImgBot'] = (@$data[0]['orAppAuthenStudentIdImgBot'] == '') ? ($or_data[1]['orAppAuthenStudentIdImgBot'] == '') ? $or_dataDealFinish[0]['orAppAuthenStudentIdImgBot']:$or_data[1]['orAppAuthenStudentIdImgBot']:$data[0]['orAppAuthenStudentIdImgBot'];
		$or->updateorAppAuthenStudentIdImgBot($array['orAppAuthenStudentIdImgBot'],$_SESSION['ord_code']);
		
		if($_POST['orIdIssueYear'] == "" && $_POST['orIdIssueMonth'] == "" && $_POST['orIdIssueDay'] == "")  $errg[] = '請請填寫身分證發證日期';
		if($_POST['orIdIssuePlace'] == "")  $errg[] = '請請填寫身分證發證地點';
		if($_POST['orIdIssueType'] == "")  $errg[] = '請請填寫身分證發證類別';
		if(empty($array['orAppAuthenIdImgTop']))  $errg[] = '請上傳身分證正面照片';
		if(empty($array['orAppAuthenIdImgBot']))  $errg[] = '請上傳身分證反面照片';
		if($memberData[0]['memClass'] == '0'){
			if(empty($array['orAppAuthenStudentIdImgTop'])) $errg[] ='請上傳學生證正面照片';
			if(empty($array['orAppAuthenStudentIdImgBot'])) $errg[] = '請上傳學生證反面照片';
		}
		if(empty($data[0]['orAppAuthenProvement']))  $errg[] = '請確認本票簽名';
		if(empty($data[0]['orAppAuthenPromiseLetter']))  $errg[] = '請確認申請人簽名';
		$msg = implode(',',$errg);
		if($errg == ''){
			$orData['orIdIssueYear'] = $_POST['orIdIssueYear'];
			$orData['orIdIssueMonth'] = $_POST['orIdIssueMonth'];
			$orData['orIdIssueDay'] = $_POST['orIdIssueDay'];
			$orData['orIdIssuePlace'] = $_POST['orIdIssuePlace'];
			$orData['orIdIssueType'] = $_POST['orIdIssueType'];
			$or->updateForId($orData,$_SESSION['ord_code']);
			echo 1;
		}else{
			echo $msg;
		}
	}else{
		echo false;
	}
	
?>