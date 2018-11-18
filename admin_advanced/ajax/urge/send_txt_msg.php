<?php
header('Content-Type: text/html; charset=utf8');
require_once('../../model/require_ajax.php');

session_start();
date_default_timezone_set('Asia/Taipei');

$utr = new API("urge_txt_records");

foreach($_POST as $key=>$value){
	$$key = $value;
}

if(!preg_match('/^[0-9]{10}$/', $utrNumber)){
	$errMsg = "請輸入正確的手機號碼。";
}

if(!isset($errMsg)){
	$fp = fsockopen("xsms.aptg.com.tw", 80, $errno, $errstr, 30);
	if (!$fp){
		echo '簡訊發送服務終止中';
	}else{
		$xmlpacket ='<soap-env:Envelope xmlns:soap-env="http://schemas.xmlsoap.org/soap/envelope/">
				 <soap-env:Header/>
				 <soap-env:Body>
				 <Request>
				 <MDN>0906815981</MDN>
				 <UID>0906815981</UID>
				 <UPASS>123456</UPASS>
				 <Subject>NoWait</Subject>
				 <Retry>Y</Retry>
				 <Message>'.$utrDetails.'</Message>
				 <MDNList><MSISDN>'.$utrNumber.'</MSISDN></MDNList>
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
			$_POST["utrStatus"] = 1;
			echo "OK";
		}else{
			$_POST["utrStatus"] = 0;
// 			echo "發送失敗";
			echo $theOutput;
		}
		$utr->insert($_POST);
		// echo $theOutput is the response returned from the remote script
	}
}else{
	echo $errMsg;
}

?>