<?php
    session_start();
    // 关闭错误报告
    error_reporting(0);
	include('../../model/php_model.php');

	$or = new Orders();
	$member = new Member();
	$memberData = $member->getOneMemberByNo($_SESSION['user']['memNo']);

	if($_SESSION['shopping_user'][0]['memNo'] != ""){
		$data = $or->getOneOrderByNo($_SESSION['ord_code']);
		if(empty($data[0]['orAppAuthenIdImgTop']))  $errg[] = '請上傳身分證正面照片';
		if(empty($data[0]['orAppAuthenIdImgBot']))  $errg[] = '請上傳身分證反面照片';
		if($memberData[0]['memClass'] == '0'){
			if(empty($data[0]['orAppAuthenStudentIdImgTop'])) $errg[] ='請上傳學生證正面照片';
			if(empty($data[0]['orAppAuthenStudentIdImgBot'])) $errg[] = '請上傳學生證反面照片';
		}
		if(empty($data[0]['orAppAuthenProvement']))  $errg[] = '請確認本票簽名';
		if(empty($data[0]['orAppAuthenPromiseLetter']))  $errg[] = '請確認申請人簽名';
		$msg = implode(',',$errg);

		if($errg == ''){
			echo 1;
		}else{
			echo $msg;
		}
	}else{
		echo false;
	}
	
?>