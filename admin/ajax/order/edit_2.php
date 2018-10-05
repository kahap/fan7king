<?php
	session_start();
	header ("content-type: text/HTML; charset=utf-8");
	require_once('../../model/require_login.php');
	
	$or = new Orders();
	$pro = new Product();
	$pm = new Product_Manage();
	$mem = new Member();
	$os = new Other_Setting();

	$osData = $os->getAll();
	
	$errMsg = "";
	
	$orNoArr = $_POST["orNo"];
	$orStatusArr = $_POST["orStatus"];
	
	if($errMsg == ""){
		$deviceTokenArr = array();
		$androidRigistIdArr = array();
		$androidDataArr = array();
		$msgArr = array();
		$otherInfoArr = array();
		$count = 0;
		foreach($orNoArr as $key=>$value){
			$orOldData = $or->getOneOrderByNo($value);
			$or->updateStatus($orStatusArr[$key], $value);
			
			//讓使用者可編輯訂單
			if($orStatusArr[$key] == 5){
				$or->updateIfEditable(0, $value);
			}
			
			//增加審查時間紀錄
			if($orOldData[0]["orStatus"] != $orStatusArr[$key]){
				$or->updateStatusTime($orStatusArr[$key],$value);
				$or->updateIfProcess(0, $value);
				$or->updateProcessTime("", $value);
				
				//寄送EMAIL通知
				if($orStatusArr[$key] != 5){
					$memData = $mem->getOneMemberByNo($orOldData[0]["memNo"]);
					$pmData = $pm->getOnePMByNo($orOldData[0]["pmNo"]);
					$proData = $pro->getOneProByNo($pmData[0]["proNo"]);
					
					//推播
					$memNo = $orOldData[0]["memNo"];
					$api = new API("app_data");
					$api->getUniqueDeviceToken($memNo);
					$data = $api->getData();
					if($data != null){
						if($orStatusArr[$key] == 3){
							$msgArr[$count] = "您訂購的商品 - ".$proData[0]["proName"]."，已核准";
						}else if($orStatusArr[$key] == 4){
							$msgArr[$count] = "您訂購的商品 - ".$proData[0]["proName"]."，無法通過審核已婉拒";
						}else{
							$msgArr[$count] = "您訂購的商品 - ".$proData[0]["proName"]."，已取消訂單";
						}
						$otherInfoArr[$count] = $value;
						$pushTitle = "訂單狀態更新通知";
						$apiIc = new API("inform_center");
						$icDataInput = array(
							"memNo"=>$memNo,
							"orNo"=>$otherInfoArr[$count],
							"icTitle"=>$pushTitle,
							"icContent"=>$msgArr[$count],
							"icDate"=>date("Y-m-d H:i:s",time())
						);
						$apiIc->insert($icDataInput);
						foreach($data as $whichAd=>$valueAd){
							if($valueAd["adDeviceId"] != ""){
								if(strlen($valueAd["adDeviceId"]) <= 65){
									$deviceTokenArr[$count][] = $valueAd["adDeviceId"];
								}else{
									$androidRigistIdArr[$count][] = $valueAd["adDeviceId"];
								}
							}
						}
						push_android($androidRigistIdArr[$count],$msgArr[$count],$pushTitle,$otherInfoArr[$count]);
						$count++;
					}
					
					$email = new Email();
					$memData = $mem->getOneMemberByNo($orOldData[0]["memNo"]);
					$pmData = $pm->getOnePMByNo($orOldData[0]["pmNo"]);
					$proData = $pro->getOneProByNo($pmData[0]["proNo"]);
					sendEmailForStatChange($orStatusArr[$key],$orOldData,$memData,$pmData,$proData,$email);
				}
				
				//寄送簡訊通知
				if($orStatusArr[$key] == 3){
					if($osData[0]["textSwitch"] == 1){
						//簡訊
						$titleTxt = "【樂分期購物網】您訂購的商品分期已核准";
						$contentTxt = '【樂分期HappyFan7.com】通知您購買訂單編號'.$orOldData[0]["orCaseNo"].'，本公司審核已核准通過，後續出貨狀態請至[會員中心]查詢';
						
						$fp = fsockopen("xsms.aptg.com.tw", 80, $errno, $errstr, 30);
						if (!$fp){
							echo 'Could not open connection.';
						}else{
							$xmlpacket ='<soap-env:Envelope xmlns:soap-env="http://schemas.xmlsoap.org/soap/envelope/">
											 <soap-env:Header/>
											 <soap-env:Body>
											 <Request>
											 <MDN>0906815981</MDN>
											 <UID>0906815981</UID>
											 <UPASS>123456</UPASS>
											 <Subject>'.$titleTxt.'</Subject>
											 <Retry>Y</Retry>
											 <Message>'.$contentTxt.'</Message>
											 <MDNList><MSISDN>'.$memData[0]['memCell'].'</MSISDN></MDNList>
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
							// echo $theOutput is the response returned from the remote script
						}
					}
				}
			}
			
			
		}
		
		$sql = "select rcNo from real_cases where rcRelateDataNo = '".$_POST["orNo"]."' && rcType = '0' ";
		$data = $or->getSql($sql);
		$Record = "insert into service_record (rcNo,aauNoService,content,time) value('".$data['0']['rcNo']."','".$_SESSION['userdata']['smName']."','狀態由".($or->statusArr[$origData[0]["orStatus"]])."變成".($or->statusArr[$_POST["orStatus"]])."','".date('Y-m-d H:i:s',time())."')";
		$or->getSql($Record);
		$push = push("happy.pem",'happy',$deviceTokenArr,$msgArr,$otherInfoArr);
		echo "更新成功";
	}else{
		echo $errMsg;
	}
	
	

?>
