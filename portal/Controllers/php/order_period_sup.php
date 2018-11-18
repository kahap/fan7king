<?php
	session_start();
	include('../model/php_model.php');
	$member = new Member();
	$pm = new Product_Manage();
	$ps = new Period_Setting();
	$pp = new Product_Period();
	$memberData = $member->getOneMemberByNo($_SESSION['user']['memNo']);
	$pm_detail = $pm->getAllByProName_sup($_POST['pro']);
	//利率待修改區-START
	$month = $ps->getAllPS();
	$ppData = $pp->getPPByProduct($pm_detail[0]["proNo"]);
	$followDefault = true;
	if($ppData != null){
		foreach($ppData as $key=>$value){
			if($value["ppPercent"] != ""){
				$followDefault = false;
				break;
			}
		}
	}   

	if($followDefault){   
		foreach($month as $k => $v){
			$price[$v['psMonthNum']] = ceil($v['psRatio']*$pm_detail[0]['pmPeriodAmnt']/$v['psMonthNum']);
		}
	}else{  
		foreach($ppData as $k=>$v){      
			if($v["ppPercent"] != ""){
				$price[$v['ppPeriodAmount']] = ceil($v['ppPercent']*$pm_detail[0]['pmPeriodAmnt']/$v['ppPeriodAmount']);
			}
		}
	}
	//利率待修改區-END
	if($_SESSION['user']['memNo'] != ""){
		if($price[$_POST['period']] !=""){
			if($memberData[0]['memFBtoken'] == '' && $memberData[0]['memEmailAuthen'] == '0'){
				echo "您尚未完成Email驗證,請您先至註冊時填寫之帳號(Email)收信並點選驗證網址,才能進行後續購物,如您仍有問題,請聯絡客服人員,NoWait將為您服務,謝謝!!";
			}else{
				$_SESSION['shopping_user'] = $memberData;
				$_SESSION['shopping_product'] = $pm_detail;
				$_SESSION['shopping_spec'] = $_POST['spec'];
				$_SESSION['shopping_period'] = $_POST['period'];
				$_SESSION['shopping_price'] = $price[$_POST['period']];
				echo "1";
			}
		}else{
			echo "沒有選擇分期!!".$me;
		}
	}else{
		echo "請先登入後再購物!!";
	}
	
?>