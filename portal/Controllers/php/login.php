<?php
	session_start();
	include('../model/php_model.php');
	$member = new Member();
	$mem = array();
	$mem['memIdNum'] = (empty($_POST['emmail_login'])) ? '帳號密碼錯誤': $_POST['emmail_login'];
	$mem['memPwd'] = (empty($_POST['password_login'])) ? '帳號密碼錯誤': $_POST['password_login'];
	$member_data = $member->getMemberinformationNew($mem);
	if ($member_data != ""){
		if ($member_data['memAllowLogin'] == 1) {
            $_SESSION['user']['memName'] = $member_data['memName'];
            $_SESSION['user']['memNo'] = $member_data['memNo'];
            $_SESSION['user']['memIdNum'] = $member_data['memIdNum'];
            $_SESSION['user']['memClass'] = $member_data['memClass'];
            if ($member_data['memIdNum'] == null || $member_data['memIdNum'] == '' ) {
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