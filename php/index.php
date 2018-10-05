<?php

$access_token ='ASApqIJJRyuQA+m913Hj3gv/KSd3HArXQ10XFufWI9oLbo9nAm6fIbo4uO+uKqjMUP67mdjQzpHRpyh58NbSZOTcLNqjEeTOFOMUnEO94rAcvQtom3b/khcZ6yjH4zx/KFnXg4OT1+/I8vrRdLVs7wdB04t89/1O/w1cDnyilFU=';
//機器代碼陣列
$deviceArray = array('happyfan7','urge');
if($_GET["msg"] == ""){
	$json_obj = json_decode(file_get_contents("php://input"));
	$event = $json_obj->{"events"}[0];
	$message = $event->{"message"};
	
	$devMsg = $message->{"text"};

	
	if(in_array($devMsg,$deviceArray)){
		$userID = ($event->{"source"}->{"roomId"} != "") ? $event->{"source"}->{"roomId"}:($event->{"source"}->{"groupId"} != "") ? $event->{"source"}->{"groupId"}:$event->{"source"}->{"userId"};
		$deviceFilename = "file/".$devMsg.".txt";
		$aa = fopen($deviceFilename, "a+");
		while(!feof($aa)){
			$data[] = preg_replace('/\R/', '',fgets($aa));
		}
		if(!in_array($userID,$data)){
			fwrite($aa,$userID."\n"); 
		}
		fclose($aa);
		
		
		$reply_token = $event->{"replyToken"};
		$post_data = [
		  "replyToken" => $reply_token,
		  "messages" => [
			[
			  "type" => "text",
			  "text" => "感謝您的回覆，即刻起樂分期機器人將會隨時報告預警事件或是重大事件。"
			]
		  ]
		]; 
		$ch = curl_init("https://api.line.me/v2/bot/message/reply");
		curl_setopt($ch, CURLOPT_POST, true);
		curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'POST');
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
		curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($post_data));
		curl_setopt($ch, CURLOPT_HTTPHEADER, array(
			'Content-Type: application/json',
			'Authorization: Bearer '.$access_token
			//'Authorization: Bearer '. TOKEN
		));
		$result = curl_exec($ch); 
		/*fwrite($bb, $result." replay_status\n"); 
		@fclose($bb);*/
		fclose($file);
		curl_close($ch); 
	}
}else{
	//存LOG紀錄
	$log = "log/".date("Ymd",time()).".txt";
	$log_msg = date("Ymd H:i:s",time())."inst:".$_GET['inst']."encode:".$_GET['encode']."msg:".$_GET['msg']."\n";
	$aa = fopen($log, "a+");
	fwrite($aa,$log_msg); 
	

	//接收到資料發通知
	if(in_array($_GET['inst'],$deviceArray)){
		$deviceFilename = "file/".$_GET['inst'].".txt";
		$file = fopen($deviceFilename, "a+");
		while(!feof($file)){
			$userID = preg_replace('/\R/', '',fgets($file));
			switch($_GET['encode']){
				case "utf8":
					$str = str_replace('@@',', ',$_GET['msg']);    
				break;
				
				case "unicode":
					$remove_str = str_replace('@@',' ',$_GET['msg']); 
					$split  =  explode("||",$remove_str);
					foreach($split as $k => $v){
						$str .= utf8(intval($v, 0));
						echo $v."\n";
					}
				break;
				
				default:
					$str = str_replace('@@',', ',$_GET['msg']);    
				break;
			}
			
			
			$post_data = [
			  "to" => $userID,
			  "messages" => [
				[
				  "type" => "text",
				  "text" => $str
				]
			  ]
			];   
			$ch = curl_init("https://api.line.me/v2/bot/message/push");
			curl_setopt($ch, CURLOPT_POST, true);
			curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'POST');
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
			curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
			curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
			curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($post_data));
			curl_setopt($ch, CURLOPT_HTTPHEADER, array(
				'Content-Type: application/json',
				'Authorization: Bearer '.$access_token
				//'Authorization: Bearer '. TOKEN
			));
			$result = curl_exec($ch);
			fwrite($aa, $result."\n"); 
			fclose($aa);
			curl_close($ch);   
		}
	}
	
	
}

	function utf8($num)
	{
		if($num<=0x7F)       return chr($num);
		if($num<=0x7FF)      return chr(($num>>6)+192).chr(($num&63)+128);
		if($num<=0xFFFF)     return chr(($num>>12)+224).chr((($num>>6)&63)+128).chr(($num&63)+128);
		if($num<=0x1FFFFF)   return chr(($num>>18)+240).chr((($num>>12)&63)+128).chr((($num>>6)&63)+128).chr(($num&63)+128);
		return '';
	}
?>
