<?php
	//擷取出網域名稱
	function get_domain_name($ip)
	{
		preg_match('/http:\/\/(.*?)\//is',$ip,$match);
		if(strpos($match[1],DOMAIN) !== false){
			return DOMAIN;
		}else{
			return 'error';
		}
		//return $match[1];
		//return 'newa1-3.com';
	}
	
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
	
	
	//取得所有資料夾裡面的圖片
	function getAllImgs(){
		$result = array();
		if(file_exists(iconv("utf-8","utf-8","images/product/"))){
// 		if(file_exists(iconv("utf-8","big5","images/product/"))){
			$arr = scandir("images/product/");
			
			foreach($arr as &$value){
				if($value != "." && $value != ".."){
					$imgArr = array();
					$arrIn = @scandir("images/product/".$value);
					if ($arrIn)foreach($arrIn as &$valueIn){
						if($valueIn != "." && $valueIn != ".."){
							array_push($imgArr,$valueIn);
						}
					}
                    $value = mb_convert_encoding($value, "utf-8", "utf-8");
//					$value = iconv("utf-8", "utf-8", $value);
// 					$value = iconv("big5", "utf-8", $value);
					$result[$value] = $imgArr;
				}
			}
		}
		return $result;
	}


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
		$imagePath = "../../images/".$folderName."/";
		
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
// 					if (!file_exists(iconv("utf-8","big5",$imagePath))) {
// 						mkdir(iconv("utf-8","big5",$imagePath),0777,true);
// 					}
// 					if(move_uploaded_file($imagetemp, $imagePath.$imagename.".".$extension)) {
// 						$_POST[$columnName] = "images/".$folderName."/".$imagename.".".$extension;
// 					}else{
// 						$errMsg = "上傳失敗";
// 					}
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
	
	//上傳多張時
	function uploadMultipleImg($editOrInsert,$oldData,$isAllowNull,$folderName,$columnName){
		$total = count($_FILES[$columnName]['name']);
		$finalData = array();
		$errMsg = "";
		
		for($i=0; $i<$total; $i++) {
			//Stores the filename as it was on the client computer.
			date_default_timezone_set('Asia/Taipei');
			$imagename = date("Ymdhis",time())."-".$i;
			//Stores the filetype e.g image/jpeg
			$imagetype = $_FILES[$columnName]['type'][$i];
			//Stores the tempname as it is given by the host when uploaded.
			$imagetemp = $_FILES[$columnName]['tmp_name'][$i];
			//The path you wish to upload the image to
			$imagePath = "../../images/".$folderName."/";
			
			//判斷是否有檔案
			if($_FILES[$columnName]['error'][$i] != 4) {
				$allowedExts = array("jpeg", "jpg", "png","svg","JPEG","JPG","PNG","SVG");
				$tmp = explode(".", $_FILES[$columnName]["name"][$i]);
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
// 						if (!file_exists(iconv("utf-8","big5",$imagePath))) {
// 							mkdir(iconv("utf-8","big5",$imagePath),0777,true);
// 						}
// 						if(move_uploaded_file($imagetemp, iconv("utf-8","big5",$imagePath.$imagename.".".$extension))) {
// 							array_push($finalData,"images/".$folderName."/".$imagename.".".$extension);
// 						}else{
// 							$errMsg = "上傳失敗";
// 						}
						if (!file_exists(iconv( 'UTF-8', 'UTF-8',$imagePath))) {
							mkdir(iconv( 'UTF-8', 'UTF-8',$imagePath),0777,true);
						}
						if(move_uploaded_file($imagetemp, iconv('UTF-8', 'UTF-8',$imagePath.$imagename.".".$extension))) {
							array_push($finalData,"images/".$folderName."/".$imagename.".".$extension);
						}else{
							$errMsg = "上傳失敗->".$imagePath.$imagename.".".$extension;
						}
					}else{
						$errMsg = "上傳失敗";
					}
				}
			}else{
				if($editOrInsert == "edit"){
					if (isset($oldData))foreach(json_decode($oldData) as $value){
						array_push($finalData,$value);
					}
				}else{
					if($isAllowNull){
						array_push($finalData,"");
					}else{
						$errMsg = "必須上傳圖片";
					}
				}
			}
		}
		$_POST[$columnName] = json_encode($finalData,JSON_UNESCAPED_UNICODE);
		return $errMsg;
	}
	
	function sendEmailForStatChange($orStatusTo,$orOldData,$memData,$pmData,$proData,$email){	
		$emailAddr = "";
		$title = "";
		$content = "";
		
		if(trim($memData[0]["memSubEmail"]) != ""){
			$emailAddr = $memData[0]["memSubEmail"];
			$receiverNameAndEmails = Array($memData[0]["memSubEmail"]=>$memData[0]['memName']);
		}else{
			$emailAddr = $memData[0]["memAccount"];
			$receiverNameAndEmails = Array($memData[0]["memAccount"]=>$memData[0]['memName']);
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
								<p style="color:red;">下單當日請注意手機！！平日若超過2天未接獲電話，麻煩請洽客服人員並告知申請人可接電話時間。
國定例假日下單案件較多，若無接到照會電話屬正常，若連假後仍無接獲電話亦可主動與客服聯絡，感謝您！</p> 
							</td>
						</tr>
						<tr>
							<td style="font-weight:bold;background-color:#F5F3F1;">
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
								<p>親愛的顧客您好,NoWait網站通知您購買訂單編號'.$orOldData[0]["orCaseNo"].'，本公司審核已核准通過，您的商品將在「3-7個工作天送達（不含預購商品）」，後續出貨狀態請至 <span style="color:red;">[會員中心]</span>查詢。</p>
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
								<p style="color:#ff0000">										本公司都是電腦評分，客服無法得知婉拒原因，如有需要請您增補資料，我們在婉拒後的2個工作天內會有專人跟您聯絡，謝謝！如果可以補資料會再主動和您連絡，如果沒有辦法補就不會再和您連絡，一般【婉拒】六個月後才能再下單，謝謝！
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
				$or = new Orders();
				$title = "【NoWait購物網】您訂購的商品分期申請須補件";
				if(is_numeric($orOldData[0]["orDocProvideReason"]) && $orOldData[0]["orDocProvideReason"] != "0" && $orOldData[0]["orDocProvideReason"] != "1"){
					$reason = $or->reasonArr[$orOldData[0]["orDocProvideReason"]]."。".$orOldData[0]["orDocProvideComment"];
				}else{
					$reason = $orOldData[0]["orDocProvideComment"];
				}
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
				$title = "【NoWait購物網】您訂購的商品備貨中(訂單編號: ".$orOldData[0]["orCaseNo"].")";
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
									您所訂購的商品已在備貨中，您此次訂購的商品明細如下：
								</p>
								<p>
									您此次訂購的商品明細如下：<br>
									訂單編號：'.$orOldData[0]["orCaseNo"].'<br>
									訂購日期：'.$orOldData[0]["orDate"].'<br>
									訂購商品：'.$proData[0]["proName"].'<br>
									商品規格：'.$orOldData[0]["orProSpec"].'<br>
								</p>
								<p>
									您的訂單狀態現在為【備貨中】，約1-3天會變成【出貨中】，當變成【出貨中】時，您就可以點【訂單編號】進入訂單查詢，裡面就會顯示目前配送的【宅配公司】和【宅配單號】，您就可以上物流公司網站進行查詢運送狀況。
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
		
		//$send = $email->SendEmail_smtp($emailAddr,$memData[0]['memName'], "happyfan7@21-finance.com", "NoWait", $title, $content);
		$send = $email->SendBCCEmail_smtp($receiverNameAndEmails, "service@nowait.shop", "NoWait", $title, $content);	
		return $send;
	}
	
?>