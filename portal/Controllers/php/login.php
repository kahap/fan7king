<?php

	session_start();
	include('../../model/php_model.php');

	$member = new Member();

	$mem = array();
//	$mem['memIdNum'] = (empty($_POST['emmail_login'])) ? '帳號密碼錯誤': $_POST['emmail_login'];
    $mem['memCell'] = (empty($_POST['cell_login'])) ? '帳號密碼錯誤': $_POST['cell_login'];
	$mem['memPwd'] = (empty($_POST['password_login'])) ? '帳號密碼錯誤': $_POST['password_login'];

	$member_data = $member->getMemberinformationWithCell($mem);
	if ($member_data != ""){
		if ($member_data['memAllowLogin'] == 1) {
            $_SESSION['user']['memName'] = $member_data['memName'];
            $_SESSION['user']['memNo'] = $member_data['memNo'];
            $_SESSION['user']['memIdNum'] = $member_data['memIdNum'];       //身分證字號
            $_SESSION['user']['memClass'] = $member_data['memClass'];
			$_SESSION['user']['memCell'] = $member_data['memCell'];         //手機號碼
			$_SESSION['user']['fb_token'] = $member_data['fb_token'];         //手機號碼
//            if ($member_data['memIdNum'] == null || $member_data['memIdNum'] == '' ) {
            if ($member_data['memCell'] == null || $member_data['memCell'] == '' ) {
                echo 0;
            }
            else
            {
                echo 1;
            }
		} else {
			echo 2;
		}
	} else {
		echo 3;
	}

	function message ($message, $path) {
		return "<script>alert('" . $message . "');location.href='" . $path . "'</script>";
	}

?>