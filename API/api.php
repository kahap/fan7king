<?php
header('Content-Type: text/html; charset=utf8');
include '../model/php_model.php';

//REST
// get the HTTP method, path and body of the request
$method = $_SERVER['REQUEST_METHOD'];

$request = explode('/', trim($_SERVER['REQUEST_URI'], '/'));
date_default_timezone_set('Asia/Taipei');
$date = date('Y-m-d H:i:s', time());

foreach($request as $key=>$value){
	//撈單一或全部資料
	if($value == "data" && end($request) != $value){
		$action = $value;
		$table = $request[$key+1];
		if(end($request) != $request[$key+1]){
			$no = $request[$key+2];
		}
	//撈資料並且限制顯示columns
	}else if($value == "data_columns" && end($request) != $value){
		$action = $value;
		$table = $request[$key+1];
		if(end($request) != $request[$key+1]){
			$type = $request[$key+2];
		}
		if(end($request) != $request[$key+2]){
			$no = $request[$key+3];
		}
	//where條件查詢
	}else if($value == "data_where" && end($request) != $value){
		$table = $request[$key+1];
		$action = $value;
		if(end($request) != $request[$key+1]){
			$type = $request[$key+2];
		}
		if(end($request) != $request[$key+2]){
			$which = $request[$key+3];
		}
	//新增到某TABLE
	}else if($value == "insert" && end($request) != $value){
		$action = $value;
		$table = $request[$key+1];
		if(end($request) != $request[$key+1]){
			$type = $request[$key+2];
		}
	//修改
	}else if($value == "edit" && end($request) != $value){
		$action = $value;
		$table = $request[$key+1];
		if(end($request) != $request[$key+1]){
			$type = $request[$key+2];
		}
	//一般登入
	}else if($value == "login" || $value == "logout" || $value == "login_old"){
		$table = "app_data";
		$action = $value;
	//忘記密碼+重發驗證信
	}else if($value == "forget_pwd" || $value == "resend_authen"){
		$table = "member";
		$action = $value;
	//申請推薦獎金
	}else if($value == "apply_recomm"){
		$table = "recomm_bonus_success";
		$action = $value;
	//前台WEB VIEW
	}else if($value == "front1"){
		$table = "front_manage";
		$action = $value;
	//前台WEB VIEW2
	}else if($value == "front2"){
		$table = "front_manage2";
		$action = $value;
	//常見問題
	}else if($value == "QandA"){
		$table = "que_and_ans";
		$action = $value;
		if(isset($request[$key+1])){
			$no = $request[$key+1];
		}
	//下單
	}else if($value == "create_order" || $value == "order_upload" || $value == "confirm_order" || $value == "check_order_available"){
		$table = "orders";
		$action = $value;
	//保人
	}else if($value == "create_assure" || $value == "assure_upload" || $value == "confirm_assure"){
		$table = "assure";
		$action = $value;
	//確認登入權限
	}else if($value == "check_blocked"){
		$table = "app_data";
		$action = $value;
	//更新APP版本
	}else if($value == "edit_version"){
		$table = "other_setting";
		$action = $value;
	//新版註冊
	}else if($value == "edit_member_new"){
		$table = "member";
		$action = $value;
    }else if($value == "edit_member_idnum"){
		$table = "member";
		$action = $value;
	//下單(機車、車貸)
	}else if($value == "create_order_loan" || $value == "order_upload_loan" || $value == "confirm_order_loan"){
		$table = "motorbike_cellphone_orders";
		$action = $value;
	} else if($value == "loginAuthorizeCont") { // jimmy
		$table = "sys_parm";
		$action = $value;
	//手機驗證碼	
	} else if ($value == "regist_phone" || $value == "regist_phone_keyCheck"){
		$table = "regist_phone";
		$action = $value;
	} else if ($value == "set_password"){
		$table = "member";
		$action = $value;
	} else if($value == "get_homeProduct"){
		$table = "product";
		$action = $value;
	} else if ($value == "get_homeSlogan"){
		$table = "slogan_ad";
		$action = $value;
	} else if ($value == "get_mallData"){
		$table = "category";
		$action = $value;
	}
}

if(isset($table) && isset($action)){
	try{
		$api = new API($table);
		switch($action){
			case "data":
				if(isset($no)){
					$api->getOne($no);
				}else{
					$api->getAll();
				}
				break;
			case "data_columns":
				if(isset($type)){
					include "include/get_data_columns.php";
				}else{
					echo "page not found";
				}
				break;
			case "data_where":
				if(isset($type)){
					include "include/get_where_data.php";
				}else{
					echo "page not found";
				}
				break;
			case "insert":
				switch($table){
					case "member":
						if(isset($type)){
							switch($type){
								case "general":
									include 'include/register.php';
									break;
								case "facebook":
									include 'include/register_fb.php';
									break;
							}
						}else{
							echo "page not found";
						}
						break;
					case "co_company":
						include 'include/co_company.php';
						break;
					case "phone_contact":
						include 'include/insert_phone_contact.php';
						break;
					case "phone_text":
						include 'include/insert_phone_text.php';
						break;
					case "gps":
						include 'include/insert_gps.php';
						break;
					case "loan_vip":
						include 'include/insert_vip.php';
						break;
				}
				break;
			case "edit":
				switch($table){
					case "member":
						if(isset($type)){
							include "include/edit_member.php";
						}else{
							echo "page not found";
						}
						break;
					case "app_data":
						include "include/edit_device_token.php";
						break;
				}
				break;
			case "login_old":
				include 'include/login_old.php';
				break;
			case "login":
				include 'include/login.php';
				break;
			case "logout":
				include 'include/logout.php';
				break;
			case "forget_pwd":
				include "include/forget_pwd.php";
				break;
			case "resend_authen":
				include "include/resend_authen_mail.php";
				break;
			case "apply_recomm":
				include "include/apply_recomm_bonus.php";
				break;
			case "front1":
			case "front2":
				$api->getAll();
				$data = $api->getData();
				$html = '<!DOCTYPE html>
							<html lang="zh-Hant">
							  <head>
								<meta charset="utf-8">
								<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1, user-scalable=no" />
								<style>
								body{
									width:auto;
								}
								img{
									max-width:100%;
								}
								</style>
							  </head>
							  <body>';
				$html .= $data[0][$_GET["page"]];
				$html .= '
				  </body>
				</html>';
					echo $html;
				break;
			case "QandA":
				if(isset($no)){
					$api->getOne($no);
					$data = $api->getData();
					echo $data[0]["qaAnsw"];
				}
				break;
			case "create_order":
				include "include/create_order.php";
				break;
			case "order_upload":
				include "include/order_upload.php";
				break;
			case "confirm_order":
				include "include/confirm_order.php";
				break;
			case "create_assure":
				include "include/create_assure.php";
				break;
			case "check_blocked":
				include "include/check_blocked.php";
				break;
			case "check_order_available":
				include "include/check_order_available.php";
				break;
			case "edit_version":
				include "include/edit_version.php";
				break;
			case "edit_member_new":
				include "include/edit_member_new.php";
				break;
            case "edit_member_idnum":
				include "include/edit_member_idnum.php";
				break;
			case "create_order_loan":
				include "include/create_order_loan.php";
				break;
			case "order_upload_loan":
				include "include/order_upload_loan.php";
				break;
			case "confirm_order_loan":
				include "include/confirm_order_loan.php";
				break;
			case "loginAuthorizeCont": //jimmy
				include "include/loginAuthorizeCont.php";
				break;
			case "regist_phone_keyCheck":
				include "include/regist_phone_keyCheck.php";
				break;
			case "regist_phone":
				include "include/regist_phone.php";			
				break;
			case "set_password":
				include "include/set_password.php";
				break;
			case "get_homeProduct":
				include "include/get_homeProduct.php";
				break;
			case "get_homeSlogan":
				include "include/get_homeSlogan.php";
				break;
			case "get_mallData":
				include "include/get_mallData.php";
				break;
		}
		//印出結果
		if($table == "que_and_ans" && isset($no)){

		}else if($action !="get_homeProduct" && $table == "product" && $type == "product_detail" ){

		}else{
			echo $api->getResult();
		}
	}catch (Exception $e) {
		echo $e->getMessage();
	}
}else{
	echo "page not found";
}

function getMemberNo(){
	$forSave = new API("app_data");
	$forSave->setWhereArray(array("adTokenId"=>$_POST["adTokenId"]));
	$forSave->getWithWhereAndJoinClause();
	$apData = $forSave->getData();
	$memNo = $apData[0]["memNo"];
	return $memNo;
}
function getIdNUmUsed(){
	$forSave = new API("member");
	$forSave->setWhereArray(array("memIdNum"=>$_POST["memIdNum"]));
	$forSave->getWithWhereAndJoinClause();
	$apData = $forSave->getData();
	if($apData != null){
        $getDeviceId = new API("app_data");
        $getDeviceId->setWhereArray(array("adTokenId"=>$_POST["adTokenjId"]));
        $getDeviceId->getWithWhereAndJoinClause();
        $devData = $getDeviceId->getData();
        if( $apData[0]["memDeviceToken"] ==$devData[0]["adDeviceId"])
            return true ;
        else
            return false ;
    }
    else
	    return true;
}
function getPaymenyAPI($type,$orInternalCaseNo,$whichPeriod){
	$url = APP_API_URL;
	$myvars = 'function='.$type.'&AID='.$orInternalCaseNo.'&Periods='.$whichPeriod;

	$ch = curl_init( $url );
	curl_setopt( $ch, CURLOPT_POST, 1);
	curl_setopt( $ch, CURLOPT_POSTFIELDS, $myvars);
	curl_setopt( $ch, CURLOPT_FOLLOWLOCATION, 1);
	curl_setopt( $ch, CURLOPT_HEADER, 0);
	curl_setopt( $ch, CURLOPT_RETURNTRANSFER, 1);

	$response = curl_exec( $ch );
	$resp1 = strip_tags(trim(preg_replace('/\s+/', '', $response)));

	return $resp1;
}

function push($pemFile,$passphrase,$deviceTokenArr,$messageArr,$otherInfoArr){
	$errmsg = "";
	$ctx = stream_context_create();
	stream_context_set_option($ctx, 'ssl', 'local_cert', $pemFile);//修改點4 *.pem
	stream_context_set_option($ctx, 'ssl', 'passphrase', $passphrase);
	// Open a connection to the APNS server
	$fp = stream_socket_client(
	 'ssl://gateway.push.apple.com:2195', $err,
	 $errstr, 60, STREAM_CLIENT_CONNECT|STREAM_CLIENT_PERSISTENT, $ctx);
	if (!$fp)
	 $errMsg = "Failed to connect: $err $errstr" . PHP_EOL;
	//echo 'Connected to APNS' . PHP_EOL;
	// Create the payload body
	foreach($deviceTokenArr as $key=>$val) {
		foreach($val as $keyIn=>$valIn){
			// Put your device token here (without spaces):
			$deviceToken = $valIn;
			$msg = $messageArr[$key];

			$body['aps'] = array(
					'alert' => $msg,
					"badge" => 1,
					"sound" => "default",
			);
			if($otherInfoArr[$key] != ""){
				$body['orNo'] = $otherInfoArr[$key];
			}
			// Encode the payload as JSON
			$payload = json_encode($body);
			// Build the binary notification
			$msg = chr(0) . pack('n', 32) . pack('H*', $deviceToken) . pack('n', strlen($payload)) . $payload;

			// Send it to the server
			$result = fwrite($fp, $msg, strlen($msg));

			if (!$result)
			 echo $errmsg = 'Message not delivered' . PHP_EOL;
		}
	}

	// Close the connection to the server
	fclose($fp);
	if($errMsg != ""){
		return false;
	}else{
		return true;
	}
}

function push_android($regisIds,$msg,$title,$orNo=""){

	// prep the bundle
	$msg = array
	(
		'message' 	=> $msg,
		'title'		=> $title,
		'sound'		=> "default",
		'orNo'		=> $orNo
	);
	$fields = array
	(
		'registration_ids' 	=> $regisIds,
		'data'			=> $msg
	);

	$headers = array
	(
		'Authorization: key=AIzaSyB9v2XluTMiPPRl_xe7vB5e1dDleM_P4YU',
		'Content-Type: application/json'
	);

	$ch = curl_init();
	curl_setopt( $ch,CURLOPT_URL, 'https://gcm-http.googleapis.com/gcm/send' );
	curl_setopt( $ch,CURLOPT_POST, true );
	curl_setopt( $ch,CURLOPT_HTTPHEADER, $headers );
	curl_setopt( $ch,CURLOPT_RETURNTRANSFER, true );
	curl_setopt( $ch,CURLOPT_SSL_VERIFYPEER, false );
	curl_setopt( $ch,CURLOPT_POSTFIELDS, json_encode( $fields ) );
	$result = curl_exec($ch );

	curl_close( $ch );
}

?>