<?php

function get_client_ip() {
	$ipaddress = '';
	if (getenv('HTTP_CLIENT_IP'))
		$ipaddress = getenv('HTTP_CLIENT_IP');
	else if(getenv('HTTP_X_FORWARDED_FOR'))
		$ipaddress = getenv('HTTP_X_FORWARDED_FOR');
	else if(getenv('HTTP_X_FORWARDED'))
		$ipaddress = getenv('HTTP_X_FORWARDED');
	else if(getenv('HTTP_FORWARDED_FOR'))
		$ipaddress = getenv('HTTP_FORWARDED_FOR');
	else if(getenv('HTTP_FORWARDED'))
		$ipaddress = getenv('HTTP_FORWARDED');
	else if(getenv('REMOTE_ADDR'))
		$ipaddress = getenv('REMOTE_ADDR');
	else
		$ipaddress = 'UNKNOWN';
	return $ipaddress;
}

function AgeOver20($memBday){
	$birthDate = explode('-',$memBday);
	if(is_numeric($birthDate[1])){
		$age = (date("md", date("U", mktime(0, 0, 0, $birthDate[1], $birthDate[2], $birthDate[0]+1911))) > date("md")
																				? ((date("Y") - ($birthDate[0]+1911)) - 1)
																				: (date("Y") - ($birthDate[0]+1911)));
	} else{
		$age = 50;
	};
	
	return $age;

}

$rcPosition = array("職軍");

//圖片上傳+驗證
function uploadImg($editOrInsert,$oldData,$isAllowNull,$folderName,$columnName){
	//Stores the filename as it was on the client computer.
	date_default_timezone_set('Asia/Taipei');
	$errMsg = "";
	$imagename = date("Ymdhis",time());
	//Stores the filetype e.g image/jpeg
	$imagetype = $_FILES[$columnName]['type'];
	//Stores the tempname as it is given by the host when uploaded.
	$imagetemp = $_FILES[$columnName]['tmp_name'];
	//The path you wish to upload the image to
	$imagePath = "../../../admin/images/".$folderName."/";

	//判斷是否有檔案
	if($_FILES[$columnName]['error'] != 4) {
		$allowedExts = array("jpeg", "jpg", "png","svg");
		$tmp = explode(".", $_FILES[$columnName]["name"]);
		$extension = end($tmp);
		//判斷資料型態是否正確
		if ((($imagetype != "image/jpeg")
				&& ($imagetype != "image/jpg")
				&& ($imagetype != "image/png")
				&& ($imagetype != "image/svg+xml"))
				|| !in_array($extension, $allowedExts)){
			$errMsg = "圖片格式只接受jpg,jpeg,png,svg。";
		}else{
			if(is_uploaded_file($imagetemp)) {
// 				if (!file_exists(iconv("utf-8","big5",$imagePath))) {
// 					mkdir(iconv("utf-8","big5",$imagePath),0777,true);
// 				}
// 				if(move_uploaded_file($imagetemp, $imagePath.$imagename.".".$extension)) {
// 					$_POST[$columnName] = "images/".$folderName."/".$imagename.".".$extension;
// 				}else{
// 					$errMsg = "上傳失敗";
// 				}
				if (!file_exists(iconv("utf-8","utf-8",$imagePath))) {
					mkdir(iconv("utf-8","utf-8",$imagePath),0777,true);
				}
				if(move_uploaded_file($imagetemp, $imagePath.$imagename.".".$extension)) {
					$_POST[$columnName] = "images/".$folderName."/".$imagename.".".$extension;
				}else{
					$errMsg = "上傳失敗";
				}
			}else{
				$errMsg = "上傳失敗";
			}
		}
	}else{
		if($editOrInsert == "edit"){
			$_POST[$columnName] = $oldData;
		}else{
			if($isAllowNull){
				$_POST[$columnName] = "";
			}else{
				$errMsg = "必須上傳圖片";
			}
		}
	}
	return $errMsg;
}

//驗證
function authentication($array,$input,$maxLength=0){
	$msgArr = array();
	if(!empty($array)){
		if(isset($input) && !empty($input)){
			foreach($array as $value){
				switch($value){
					case "isNumber":
						if(!is_numeric(trim($input))){
							array_push($msgArr,"必须输入数字。");
						}
						break;
					case "isNumberAndAlpha":
						if (!preg_match('/^[a-zA-Z0-9]+$/', $input))  {
							array_push($msgArr,"只能输英文字母及数字。");
						}
						break;
					case "dateFormat":
						if (!preg_match("/^[0-9]{4}-(0[1-9]|1[0-2])-(0[1-9]|[1-2][0-9]|3[0-1])$/",trim($input))){
							array_push($msgArr,"请输入正确日期格式(ex:1980-05-20)。");
						}
						break;
					case "emailFormat":
						if(!filter_var(trim($input), FILTER_VALIDATE_EMAIL)){
							array_push($msgArr,"请输入正确的Email格式。");
						}
						break;
					case "phone":
						if(strlen($input) != 10){
							array_push($msgArr,"请输入正确的电话号码（10位）");
						}
						break;
					case "length":
						if(mb_strlen($input,"UTF-8") > $maxLength){
							array_push($msgArr,"长度不可超过".$maxLength."个字。");
						}
						break;
				}
			}
		}else{
			array_push($msgArr,"此栏位不可空白。");
		}
	}
	return $msgArr;
}

//錯誤頁面導向
function redirectErrorPage($variable,$errorPage,$correctPage){
	if(!isset($_POST[$variable])){
		include $errorPage;
	}else{
		include $correctPage;
	}
}

//是否2月有29天
function is_leap_year($year)
{
	return ((($year % 4) == 0) && ((($year % 100) != 0) || (($year %400) == 0)));
}
//當月月底日期
function getEndOfMonth($date){
	date_default_timezone_set('Asia/Taipei');
	$last_day_of_this_month = date("Y-m-t", strtotime($date));
	return $last_day_of_this_month;
}
//下月日期智能計算
function getNextMonthDate($date,$calEndOfMonth=false,$calExceptFeb=false){
	date_default_timezone_set('Asia/Taipei');
	$dateArr = explode("-",$date);
	if(count($dateArr) == 3){
		if($dateArr[1] != "12"){
			$dateArr[1] = str_pad($dateArr[1]+1, 2, 0, STR_PAD_LEFT);
		}else{
			$dateArr[0] = $dateArr[0]+1;
			$dateArr[1] = "01";
		}
		$nextMonth = explode("-", getEndOfMonth($dateArr[0]."-".$dateArr[1]."-01"));
		if($dateArr[2] <= $nextMonth[2]){
			if(is_numeric($calExceptFeb)){
				$dateArr[2] = $calExceptFeb;
			}
			$next = implode("-",$dateArr);
			$first = $next;
		}else{
			$next = $dateArr[0]."-".$dateArr[1]."-01";
			$first = getEndOfMonth($next);
		}
	}else{
		$first = "";
	}
	if($calEndOfMonth){
		return getEndOfMonth($first);
	}else{
		return $first;
	}
}

//實質利率
define('FINANCIAL_MAX_ITERATIONS', 128);
define('FINANCIAL_PRECISION', 1.0e-08);
function RATE($nper, $pmt, $pv, $fv = 0.0, $type = 0, $guess = 0.1) {

	$rate = $guess;
	if (abs($rate) < FINANCIAL_PRECISION) {
		$y = $pv * (1 + $nper * $rate) + $pmt * (1 + $rate * $type) * $nper + $fv;
	} else {
		$f = exp($nper * log(1 + $rate));
		$y = $pv * $f + $pmt * (1 / $rate + $type) * ($f - 1) + $fv;
	}
	$y0 = $pv + $pmt * $nper + $fv;
	$y1 = $pv * $f + $pmt * (1 / $rate + $type) * ($f - 1) + $fv;

	// find root by secant method
	$i  = $x0 = 0.0;
	$x1 = $rate;
	while ((abs($y0 - $y1) > FINANCIAL_PRECISION) && ($i < FINANCIAL_MAX_ITERATIONS)) {
		$rate = ($y1 * $x0 - $y0 * $x1) / ($y1 - $y0);
		$x0 = $x1;
		$x1 = $rate;

		if (abs($rate) < FINANCIAL_PRECISION) {
			$y = $pv * (1 + $nper * $rate) + $pmt * (1 + $rate * $type) * $nper + $fv;
		} else {
			$f = exp($nper * log(1 + $rate));
			$y = $pv * $f + $pmt * (1 / $rate + $type) * ($f - 1) + $fv;
		}

		$y0 = $y1;
		$y1 = $y;
		++$i;
	}
	return $rate;
}

function push($pemFile,$passphrase,$deviceTokenArr,$messageArr,$otherInfoArr,$typeArr=null){
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
				$body['type'] = $typeArr[$key];
			}
			// Encode the payload as JSON
			$payload = json_encode($body,true);
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

function push_android($regisIds,$msg,$title,$orNo="",$type=""){

	// prep the bundle
	$msg = array
	(
			'message' 	=> $msg,
			'title'		=> $title,
			'sound'		=> "default",
			'orNo'		=> $orNo,
			'type'		=> $type
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
	curl_setopt( $ch,CURLOPT_POSTFIELDS, json_encode( $fields,true) );
	$result = curl_exec($ch );

	curl_close( $ch );
}

function sendEmailForStatChange($orStatusTo,$orOldData,$memData,$pmData,$proData,$email){
	$emailAddr = "";
	$title = "";
	$content = "";

	if(trim($memData[0]["memSubEmail"]) != ""){
		$emailAddr = $memData[0]["memSubEmail"];
	}else{
		$emailAddr = $memData[0]["memAccount"];
	}

	switch($orStatusTo){
		case 2:
			$title = "【NoWait購物網】您訂購的商品審核中(訂單編號: ".$orOldData[0]["orCaseNo"].")";
			$content = '
				<table width="660" align="center" cellpadding="10" cellspacing="1" style="border:3px solid #999;">
					<tbody>
						<tr>
							<td style="text-align:center;">
								<img src="https://nowait.shop/assets/images/logo_2.png" />
							</td>
						</tr>
						<tr>
							<td style="color:#FF3333;font-weight:bold;text-align:center;">此為系統自動通知信，請勿直接回信！</td>
						</tr>
						<tr>
							<td style="color:black;font-weight:bold;">
								<p>親愛的<span style="color:#FF9900;">'.$memData[0]['memName'].' </span> 先生/小姐，您好：</p>
							</td>
						</tr>
						<tr>
							<td style="font-weight:bold;background-color:#F5F3F1;">
								<p><span style="color:red;">下單當日請注意手機！！平日若超過2天未接獲電話，麻煩請洽客服人員並告知申請人可接電話時間。
國定例假日下單案件較多，若無接到照會電話屬正常，若連假後仍無接獲電話亦可主動與客服聯絡，感謝您</span></p>
								<p>
									您所訂購的商品審核中，您此次訂購的商品明細如下：<br>
									訂單編號：'.$orOldData[0]["orCaseNo"].'<br>
									訂購日期：'.$orOldData[0]["orDate"].'<br>
									訂購商品：'.$proData[0]["proName"].'<br>
									商品規格：'.$orOldData[0]["orProSpec"].'<br>
								</p>
								<p>
									感謝您的支持，如有疑問歡迎到 <a href="https://nowait.shop/?item=fmContactService" target="_blank">聯絡客服</a> 反應，NoWait將會為您處理。如需訂購其他商品請至 <a href="https://nowait.shop/" target="_blank">NoWait購物網</a>選購。
								</p>
							</td>
						</tr>
					</tbody>
				</table>
				';
			break;
		case 3:
			$title = "【NoWait購物網】您訂購的商品分期已核准";
			$content = '
				<table width="660" align="center" cellpadding="10" cellspacing="1" style="border:3px solid #999;">
					<tbody>
						<tr>
							<td style="text-align:center;">
								<img src="https://nowait.shop/assets/images/logo_2.png" />
							</td>
						</tr>
						<tr>
							<td style="color:#FF3333;font-weight:bold;text-align:center;">此為系統自動通知信，請勿直接回信！</td>
						</tr>
						<tr>
							<td style="color:black;font-weight:bold;">
								<p>親愛的<span style="color:#FF9900;">'.$memData[0]['memName'].' </span> 先生/小姐，您好：</p>
							</td>
						</tr>
						<tr>
							<td style="font-weight:bold;background-color:#F5F3F1;">
								<p>親愛的顧客您好,NoWait網站通知您購買訂單編號'.$orOldData[0]["orCaseNo"].'，本公司審核已核准通過，您的商品將在2-5天送達，後續出貨狀態請至 <span style="color:red;">[會員中心]</span>查詢。</p>
								<p>
									您此次訂購的商品明細如下：<br>
									訂購日期：'.$orOldData[0]["orDate"].'<br>
									訂購商品：'.$proData[0]["proName"].'<br>
									商品規格：'.$orOldData[0]["orProSpec"].'<br>
								</p>
								<p>
									請確認收貨人姓名、地址是否有誤：<br>
									收貨人：'.$orOldData[0]["orReceiveName"].'<br>
									收貨地址：'.$orOldData[0]["orReceiveAddr"].'<br>
								</p>
								<p>
									感謝您的支持，如有疑問歡迎到 <a href="https://nowait.shop/?item=fmContactService" target="_blank">聯絡客服</a> 反應，NoWait將會為您處理。如需訂購其他商品請至 <a href="https://nowait.shop/" target="_blank">NoWait購物網</a>選購。
								</p>
							</td>
						</tr>
					</tbody>
				</table>
				';
			break;
		case 4:
			$title = "【NoWait購物網】您訂購的商品分期結果為婉拒無法通過";
			$content = '
				<table width="660" align="center" cellpadding="10" cellspacing="1" style="border:3px solid #999;">
					<tbody>
						<tr>
							<td style="text-align:center;">
								<img src="https://nowait.shop/assets/images/logo_2.png" />
							</td>
						</tr>
						<tr>
							<td style="color:#FF3333;font-weight:bold;text-align:center;">此為系統自動通知信，請勿直接回信！</td>
						</tr>
						<tr>
							<td style="color:black;font-weight:bold;">
								<p>親愛的<span style="color:#FF9900;">'.$memData[0]['memName'].' </span> 先生/小姐，您好：</p>
							</td>
						</tr>
						<tr>
							<td style="font-weight:bold;background-color:#F5F3F1;">
								<p>
									親愛的顧客您好,NoWait網站通知您購買訂單編號'.$orOldData[0]["orCaseNo"].'，依本公司審核結果無法通過，此筆訂單交易將不成立。
								</p>
								<p>
									您此次訂購的商品明細如下：<br>
									訂購日期：'.$orOldData[0]["orDate"].'<br>
									訂購商品：'.$proData[0]["proName"].'<br>
									商品規格：'.$orOldData[0]["orProSpec"].'<br>
								</p>
								<p>
									感謝您的支持，如有疑問歡迎到 <a href="https://nowait.shop/?item=fmContactService" target="_blank">聯絡客服</a> 反應，NoWait將會為您處理。如需訂購其他商品請至 <a href="https://nowait.shop/" target="_blank">NoWait購物網</a>選購。
								</p>
							</td>
						</tr>
					</tbody>
				</table>
				';
			break;
		case 5:
			$title = "【NoWait購物網】您訂購的商品分期申請須補件";
			$reason = $orOldData[0]["orDocProvideComment"];
			$content = '
				<table width="660" align="center" cellpadding="10" cellspacing="1" style="border:3px solid #999;">
					<tbody>
						<tr>
							<td style="text-align:center;">
								<img src="https://nowait.shop/assets/images/logo_2.png" />
							</td>
						</tr>
						<tr>
							<td style="color:#FF3333;font-weight:bold;text-align:center;">此為系統自動通知信，請勿直接回信！</td>
						</tr>
						<tr>
							<td style="color:black;font-weight:bold;">
								<p>親愛的<span style="color:#FF9900;">'.$memData[0]['memName'].' </span> 先生/小姐，您好：</p>
							</td>
						</tr>
						<tr>
							<td style="font-weight:bold;background-color:#F5F3F1;">
								<p>
									親愛的顧客您好,NoWait網站通知您購買訂單編號'.$orOldData[0]["orCaseNo"].'，
									'.$reason.'，請至<span style="color:red;">[會員中心>分期訂單查詢]</span>找到相對應的訂單編號，
									點選<b>修改</b>欄位下的[<span style="color:red;">編輯</span>]按鈕進行資料修改，
									並重新送出到【確認訂單資訊】點選[<span style="color:red;">完成</span>]即完成補件，審核時間約1個工作天，我們將再次與您聯絡，
									請您靜候通知。
								</p>
								<p>
									您此次訂購的商品明細如下：<br>
									訂購日期：'.$orOldData[0]["orDate"].'<br>
									訂購商品：'.$proData[0]["proName"].'<br>
									商品規格：'.$orOldData[0]["orProSpec"].'<br>
								</p>
								<p>
									感謝您的支持，如有疑問歡迎到 <a href="https://nowait.shop/?item=fmContactService" target="_blank">聯絡客服</a> 反應，NoWait將會為您處理。如需訂購其他商品請至 <a href="https://nowait.shop/" target="_blank">NoWait購物網</a>選購。
								</p>
							</td>
						</tr>
					</tbody>
				</table>
				';
			break;
		case 7:
			$title = "【NoWait購物網】您訂購的商品已取消訂單(訂單編號: ".$orOldData[0]["orCaseNo"].")";
			$content = '
				<table width="660" align="center" cellpadding="10" cellspacing="1" style="border:3px solid #999;">
					<tbody>
						<tr>
							<td style="text-align:center;">
								<img src="https://nowait.shop/assets/images/logo_2.png" />
							</td>
						</tr>
						<tr>
							<td style="color:#FF3333;font-weight:bold;text-align:center;">此為系統自動通知信，請勿直接回信！</td>
						</tr>
						<tr>
							<td style="color:black;font-weight:bold;">
								<p>親愛的<span style="color:#FF9900;">'.$memData[0]['memName'].' </span> 先生/小姐，您好：</p>
							</td>
						</tr>
						<tr>
							<td style="font-weight:bold;background-color:#F5F3F1;">
								<p>
									您所訂購的商品已取消訂單，您此次訂購的商品明細如下：
								</p>
								<p>
									您此次訂購的商品明細如下：<br>
									訂單編號：'.$orOldData[0]["orCaseNo"].'<br>
									訂購日期：'.$orOldData[0]["orDate"].'<br>
									訂購商品：'.$proData[0]["proName"].'<br>
									商品規格：'.$orOldData[0]["orProSpec"].'<br>
								</p>
								<p>
									感謝您的支持，如有疑問歡迎到 <a href="https://nowait.shop/?item=fmContactService" target="_blank">聯絡客服</a> 反應，NoWait將會為您處理。如需訂購其他商品請至 <a href="https://nowait.shop/" target="_blank">NoWait購物網</a>選購。
								</p>
							</td>
						</tr>
					</tbody>
				</table>
				';
			break;
		case 8:
			$title = "【NoWait購物網】您訂購的商品出貨中(訂單編號: ".$orOldData[0]["orCaseNo"].")";
			$content = '
				<table width="660" align="center" cellpadding="10" cellspacing="1" style="border:3px solid #999;">
					<tbody>
						<tr>
							<td style="text-align:center;">
								<img src="https://nowait.shop/assets/images/logo_2.png" />
							</td>
						</tr>
						<tr>
							<td style="color:#FF3333;font-weight:bold;text-align:center;">此為系統自動通知信，請勿直接回信！</td>
						</tr>
						<tr>
							<td style="color:black;font-weight:bold;">
								<p>親愛的<span style="color:#FF9900;">'.$memData[0]['memName'].' </span> 先生/小姐，您好：</p>
							</td>
						</tr>
						<tr>
							<td style="font-weight:bold;background-color:#F5F3F1;">
								<p>
									您所訂購的商品出貨中，您此次訂購的商品明細如下：
								</p>
								<p>
									您此次訂購的商品明細如下：<br>
									訂單編號：'.$orOldData[0]["orCaseNo"].'<br>
									訂購日期：'.$orOldData[0]["orDate"].'<br>
									訂購商品：'.$proData[0]["proName"].'<br>
									商品規格：'.$orOldData[0]["orProSpec"].'<br>
								</p>
								<p>
									感謝您的支持，如有疑問歡迎到 <a href="https://nowait.shop/?item=fmContactService" target="_blank">聯絡客服</a> 反應，NoWait將會為您處理。如需訂購其他商品請至 <a href="https://nowait.shop/" target="_blank">NoWait購物網</a>選購。
								</p>
							</td>
						</tr>
					</tbody>
				</table>
				';
			break;
		case 9:
			$title = "【NoWait購物網】您訂購的商品已收貨(訂單編號: ".$orOldData[0]["orCaseNo"].")";
			$content = '
				<table width="660" align="center" cellpadding="10" cellspacing="1" style="border:3px solid #999;">
					<tbody>
						<tr>
							<td style="text-align:center;">
								<img src="https://nowait.shop/assets/images/logo_2.png" />
							</td>
						</tr>
						<tr>
							<td style="color:#FF3333;font-weight:bold;text-align:center;">此為系統自動通知信，請勿直接回信！</td>
						</tr>
						<tr>
							<td style="color:black;font-weight:bold;">
								<p>親愛的<span style="color:#FF9900;">'.$memData[0]['memName'].' </span> 先生/小姐，您好：</p>
							</td>
						</tr>
						<tr>
							<td style="font-weight:bold;background-color:#F5F3F1;">
								<p>
									您所訂購的商品已收貨，您此次訂購的商品明細如下：
								</p>
								<p>
									您此次訂購的商品明細如下：<br>
									訂單編號：'.$orOldData[0]["orCaseNo"].'<br>
									訂購日期：'.$orOldData[0]["orDate"].'<br>
									訂購商品：'.$proData[0]["proName"].'<br>
									商品規格：'.$orOldData[0]["orProSpec"].'<br>
								</p>
								<p>
									感謝您的支持，如有疑問歡迎到 <a href="https://nowait.shop/?item=fmContactService" target="_blank">聯絡客服</a> 反應，NoWait將會為您處理。如需訂購其他商品請至 <a href="https://nowait.shop/" target="_blank">NoWait購物網</a>選購。
								</p>
							</td>
						</tr>
					</tbody>
				</table>
				';
			break;
		case 11:
			$title = "【NoWait購物網】您訂購的商品換貨中(訂單編號: ".$orOldData[0]["orCaseNo"].")";
			$content = '
				<table width="660" align="center" cellpadding="10" cellspacing="1" style="border:3px solid #999;">
					<tbody>
						<tr>
							<td style="text-align:center;">
								<img src="https://nowait.shop/assets/images/logo_2.png" />
							</td>
						</tr>
						<tr>
							<td style="color:#FF3333;font-weight:bold;text-align:center;">此為系統自動通知信，請勿直接回信！</td>
						</tr>
						<tr>
							<td style="color:black;font-weight:bold;">
								<p>親愛的<span style="color:#FF9900;">'.$memData[0]['memName'].' </span> 先生/小姐，您好：</p>
							</td>
						</tr>
						<tr>
							<td style="font-weight:bold;background-color:#F5F3F1;">
								<p>
									您所訂購的商品換貨中，您此次訂購的商品明細如下：
								</p>
								<p>
									您此次訂購的商品明細如下：<br>
									訂單編號：'.$orOldData[0]["orCaseNo"].'<br>
									訂購日期：'.$orOldData[0]["orDate"].'<br>
									訂購商品：'.$proData[0]["proName"].'<br>
									商品規格：'.$orOldData[0]["orProSpec"].'<br>
								</p>
								<p>
									感謝您的支持，如有疑問歡迎到 <a href="https://nowait.shop/?item=fmContactService" target="_blank">聯絡客服</a> 反應，NoWait將會為您處理。如需訂購其他商品請至 <a href="https://nowait.shop/" target="_blank">NoWait購物網</a>選購。
								</p>
							</td>
						</tr>
					</tbody>
				</table>
				';
			break;
		case 12:
			$title = "【NoWait購物網】您訂購的商品退貨中(訂單編號: ".$orOldData[0]["orCaseNo"].")";
			$content = '
				<table width="660" align="center" cellpadding="10" cellspacing="1" style="border:3px solid #999;">
					<tbody>
						<tr>
							<td style="text-align:center;">
								<img src="https://nowait.shop/assets/images/logo_2.png" />
							</td>
						</tr>
						<tr>
							<td style="color:#FF3333;font-weight:bold;text-align:center;">此為系統自動通知信，請勿直接回信！</td>
						</tr>
						<tr>
							<td style="color:black;font-weight:bold;">
								<p>親愛的<span style="color:#FF9900;">'.$memData[0]['memName'].' </span> 先生/小姐，您好：</p>
							</td>
						</tr>
						<tr>
							<td style="font-weight:bold;background-color:#F5F3F1;">
								<p>
									您所訂購的商品退貨中，您此次訂購的商品明細如下：
								</p>
								<p>
									您此次訂購的商品明細如下：<br>
									訂單編號：'.$orOldData[0]["orCaseNo"].'<br>
									訂購日期：'.$orOldData[0]["orDate"].'<br>
									訂購商品：'.$proData[0]["proName"].'<br>
									商品規格：'.$orOldData[0]["orProSpec"].'<br>
								</p>
								<p>
									感謝您的支持，如有疑問歡迎到 <a href="https://nowait.shop/?item=fmContactService" target="_blank">聯絡客服</a> 反應，NoWait將會為您處理。如需訂購其他商品請至 <a href="https://nowait.shop/" target="_blank">NoWait購物網</a>選購。
								</p>
							</td>
						</tr>
					</tbody>
				</table>
				';
			break;
				
	}

	$send = $email->SendEmail_smtp($emailAddr,$memData[0]['memName'], "happyfan7@21-finance.com", "NoWait", $title, $content);
}
?>