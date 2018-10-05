<?php
session_start();
header('Content-Type: text/html; charset=utf8');
//載入cfg function
require_once('../../model/require_login.php');
//NEW Login cls
$login = new Login();
//驗證
foreach($_POST as $key=>$value){
		$$key = $value;
	}
//判斷是否POST 過來
if(count($_POST)>0)
{	
	if($login->validate($name,$pwd)){
		$_SESSION['supplieruserdata'] = $login->getuserdata();				
		$ip = get_client_ip();		
		date_default_timezone_set('Asia/Taipei');		
		$date = date('Y-m-d h:i:s', time());		
		echo true;
	}else{
		echo false;
	}
}

?>