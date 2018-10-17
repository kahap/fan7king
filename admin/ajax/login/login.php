<?php
session_start();
header('Content-Type: text/html; charset=utf8');

	require_once('../../cls/WADB.cls.php');
	require_once('../../cls/Login.cls.php');
	require_once('../../cfg/cfg.inc.php');
	require_once('../../lib/function.php');
	require_once('../../cls/System_Manager.cls.php');
	
//NEW Login cls
$login = new Login();
$sm = new System_Manager();
foreach($_POST as $key => $value){
	$$key = trim(strip_tags($value));
}
//判斷是否POST 過來
if(count($_POST)>0)
{	
	if($login->validate($name,$pwd)){
		$_SESSION['userdata'] = $login->getuserdata();				
		$ip = get_client_ip();		
		date_default_timezone_set('Asia/Taipei');		
		$date = date('Y-m-d h:i:s', time());		
		$sm->updateIpAndTime($ip, $date, $_SESSION['userdata']["smNo"]);
		echo true;
	}else{
		echo false;
	}
}

?>