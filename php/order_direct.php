<?php
	session_start();
	include('../model/php_model.php');
	$member = new Member();
	$pm = new Product_Manage();
	$memberData = $member->getOneMemberByNo($_SESSION['user']['memNo']);
	$pm_detail = $pm->getAllByProName($_POST['pro']);

	if($_SESSION['user']['memNo'] != ""){
			if($memberData[0]['memFBtoken'] == '' && $memberData[0]['memEmailAuthen'] == '0'){
				echo "您尚未完成Email驗證,請您先至註冊時填寫之帳號(Email)收信並點選驗證網址,才能進行後續購物,如您仍有問題,請聯絡客服人員,NoWait將為您服務,謝謝!!";
			}else{
				$_SESSION['shopping_user'] = $memberData;
				$_SESSION['shopping_product'] = $pm_detail;
				$_SESSION['shopping_spec'] = $_POST['spec'];
				$_SESSION['shopping_model'] = $_POST['model'];
				echo "1";
			}
	}else{
		echo "請先登入後再購物!!";
	}
	
?>