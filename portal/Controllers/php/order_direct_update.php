<?php
	session_start();
	include('../model/php_model.php');
	$or = new Orders();
	$array = array('orReceiveComment','orAppExtraInfo','orBusinessNumNumber','orBusinessNumTitle','orBusinessNumIfNeed','sameofOrder');
	if($_SESSION['shopping_user'][0]['memName'] != ""){
		$errg = false;
		foreach($_POST as $key =>$value){
			if($value == ""){
				if(!in_array($key,$array)){
					$errg = "請將訂貨人資料欄位填妥";
				}
			}
		}
	}
	
	if($_SESSION['shopping_user'][0]['memName'] == ""){
		$errg = "系統錯誤，請重新選購";
	}
	if(!empty($_SERVER['HTTP_CLIENT_IP'])){
	   $myip = $_SERVER['HTTP_CLIENT_IP'];
	}else if(!empty($_SERVER['HTTP_X_FORWARDED_FOR'])){
	   $myip = $_SERVER['HTTP_X_FORWARDED_FOR'];
	}else{
	   $myip= $_SERVER['REMOTE_ADDR'];
	}
	
	
	
	if($errg == ""){
		$_POST['orCaseNo'] = $or->product_number('0');
		$_POST['memNo'] = $_SESSION['shopping_user'][0]['memNo'];
		$_POST['pmNo'] = $_SESSION['shopping_product'][0]['pmNo'];
		$_POST['orMethod'] = '0';
		$_POST['orProSpec'] = $_SESSION['shopping_spec'];
		$_POST['orIpAddress'] = $myip;
		$_POST['orReportDirect0Date'] = date('Y-m-d H:i:s',time());
		$_POST['supNo'] = $_SESSION['shopping_product'][0]['supNo'];
		$_POST['orDate'] = date('Y-m-d H:i:s',time());
		$_POST['orAmount'] = '1';
		$_POST['orPeriodAmnt'] = '1';
		$_POST['orPeriodTotal'] = $_SESSION['shopping_product'][0]['pmDirectAmnt'];
		$_POST['orPayBy'] = $_POST['pay'];
		$metho = ($_POST['pay'] != '2') ? 'ATM':'WebATM';
		$_POST['orChosemethod'] = $_POST[$metho];
		$or->updateorder($_POST,$_SESSION['ord_code']);
		echo "1";
	}else{
		echo $errg;
	}
	
?>