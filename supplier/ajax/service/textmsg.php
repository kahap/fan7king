<?php

	$sendAll = "";
	$receiverCell = array();
	$errMsg = "";
	
	foreach($_POST as $key=>$value){
		$$key=$value;
	}
	
	if(isset($_POST["sendAll"]) || isset($_POST["receiverMail"]) || isset($_POST["receiverName"])){
		if(isset($_POST["sendAll"])){
			$sendAll = $_POST["sendAll"];
			$member = new Member();
			$allMemData = $member->getAllMember();
			foreach ($allMemData as $key=>$value){
				array_push($receiverCell, $value["memName"]);
			}
		}else if(isset($_POST["receiverMail"])){
			$receiverCell = $_POST["receiverMail"];
		}
	}else{
		$errMsg .= "*請填入使用者\n";
	}
	if(trim($title) == ""){
		$errMsg .= "*請填入簡訊主旨\n";
	}
	if(trim($content) == ""){
		$errMsg .= "*請填入簡訊內容\n";
	}
	
	$receiverStr = "";
	foreach($receiverCell as $value){
		$receiverStr .= "<MSISDN>".$value."</MSISDN>";
	}
	
	/* 填入 API 指令的網址 */
	
	if($errMsg == ""){
		$fp = fsockopen("xsms.aptg.com.tw", 80, $errno, $errstr, 30);
		if (!$fp){
			echo 'Could not open connection.';
		}else{
			$xmlpacket ='<soap-env:Envelope xmlns:soap-env="http://schemas.xmlsoap.org/soap/envelope/">
			 <soap-env:Header/>
			 <soap-env:Body>
			 <Request>
			 <MDN>0977235561</MDN>
			 <UID>0977235561</UID>
			 <UPASS>0mH54E</UPASS>
			 <Subject>'.$title.'</Subject>
			 <Retry>Y</Retry>
			 <Message>'.$content.'</Message>
			 <MDNList>'.$receiverStr.'</MDNList>
			 </Request>
			 </soap-env:Body>
			</soap-env:Envelope>'; 
			$contentlength = strlen($xmlpacket);
			$out = "POST /XSMSAP/api/APIRTFastRequest HTTP/1.1\r\n";
			$out .= "Host: 210.200.64.111\r\n";
			$out .= "Connection: close\r\n";
			$out .= "Content-type: text/xml;charset=utf-8\r\n";
			$out .= "Content-length: $contentlength\r\n\r\n";
			$out .= "$xmlpacket";
			fwrite($fp, $out);
			
			$theOutput = "";
			
			while (!feof($fp)){
				$theOutput .= fgets($fp, 128);
			}
			
			fclose($fp);
			if(strrpos($theOutput,"成功") !== false){
				echo "發送成功！";
			}else{
				echo "發送失敗！";
			}
			
			// echo $theOutput is the response returned from the remote script
		}
	}else{
		echo $errMsg;
	}
?>
