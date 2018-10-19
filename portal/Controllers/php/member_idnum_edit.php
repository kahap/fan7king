<?php
	session_start();
	include('../model/php_model.php');
	$member = new Member();
	$memberData = $member->getOneMemberByNo($_SESSION['user']['memNo']);
    $errg ="" ;

    if($_POST['memName'] === ""){
		$errg = "請填寫姓名";
	}
	preg_match("/^[a-z0-9_x80-xff]+[^_]$/g",$_POST['memName']);
	if(!preg_match("/^[\x7f-\xff]+$/",$_POST['memName'])){
		$errg = '真實姓名只能输入中文';
	}

	if($_POST['memIdNum'] === ""){
		$errg = $errg."  請填寫身分證號";
	}

	if($member->check_id($_POST['memIdNum'])){
		$errg = $errg."  身分證號已有人使用 !!";
	}
	if($errg == ""){
			$member->update_Member_IdNum($_POST,$_SESSION['user']['memNo']);
            $_SESSION['user']['memIdNum'] = $_POST['memIdNum'];
			echo "1";
	}else{
		echo $errg;
	}

?>