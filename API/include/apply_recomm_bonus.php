<?php
//一般註冊
$mem = new Member();
$or = new Orders();

$rbsColumns = $or->getAllColumnNames("recomm_bonus_success");
foreach($rbsColumns as $key => $value){
	$rbsColumnsArr[$value['COLUMN_NAME']] = $value['COLUMN_COMMENT'];
}

//需填寫欄位
$columnArr = array("rbsBankName","rbsBankBranchName","rbsBankAcc","rbsBankAccName","rbsBankAcc","rbsIdTopImg","rbsIdBotImg","rbsBankBookImg","rbaNo","rbsStatus","memNo","rbsTotal","rbsDate","rbsIdNum");

//必填欄位
$mustFill = array(
		// "rbsBankName","rbsBankBranchName","rbsBankAcc","rbsBankAccName","rbsBankAcc","rbsIdTopImg","rbsIdBotImg","rbsBankBookImg"
);
//圖片的欄位
$imgType = array(
		"rbsIdTopImg","rbsIdBotImg","rbsBankBookImg"
);

//檢查是否有空白
foreach($mustFill as $key=>$value){
	if(!isset($_POST[$value])){
		$errMsg[$value] = "必須填寫".$rbsColumnsArr[$value];
	}
}
foreach($_POST as $key=>$value){
	$$key = $value;
	if(in_array($key, $mustFill)){
		if(trim($value) == ""){
			$errMsg[$key] = "必須填寫".$rbsColumnsArr[$key];
		}
	}
}

//若沒錯誤的話預設其他欄位
if(!isset($errMsg)){
	//抓會員編號
	$memNo = getMemberNo();
	$memberData = $mem->getOneMemberByNo($memNo);
	$_POST["memNo"] = $memNo;
	$_POST["rbsStatus"] = 0;
	$_POST["rbsDate"] = date('Y-m-d H:i:s', time());
	
	//抓取可以的推薦獎金申請
	$rba = new Recomm_Bonus_Apply();
	$allRbaData = $rba->getRBAByMemNo($memNo);
	$orders = new Orders();
	$re = new Recomm_Setting();
	$re_data = $re->getSetting();
	$MakeMoney = 0;
	foreach($allRbaData as $key=>$value){
		if($value['rbaRecMemNo'] == $memNo && $value['rbaExtract'] != '1'){
			$ord_member = $orders->getOneOrderByNo($value['orNo']);
			if($orders->status($ord_member[0]['orStatus']) == "我要繳款" && strtotime($ord_member[0]['orReportPeriod10Date'])+(86400*$re_data[0]['rsDaysLimit']) < time()){
				$MakeMoney += $re_data[0]['rsTotalPerOrder'];
				$rbaNoForApply[] = $value["rbaNo"];
			}
		}
	}
	if($MakeMoney >= $re_data[0]['rsMinimum']){
		$_POST["rbsTotal"] = $MakeMoney;
	}else{
		$errMsg["rbsTotal"] = "目前累積獎金尚未超過最低申請門檻。";
	}
	if(isset($rbaNoForApply) && is_array($rbaNoForApply)){
		$_POST["rbaNo"] = json_encode($rbaNoForApply);
	}else{
		$errMsg["rbaNo"] = "尚未有任何推薦獎金可領取。";
	}
	
	
	//檔案上傳部分
	//檢查有無資料夾並放置檔案
	if(!is_dir('../admin/file/'.$memNo)){
		mkdir('../admin/file/'.$memNo);
		chmod('../admin/file/'.$memNo,0777);
	}
	//base64轉換
	foreach($imgType as $key=>$value){
		$data = base64_decode($_POST[$value]);
		file_put_contents('../admin/file/'.$memNo.'/'.date("YmdHis").'-'.$key.'.jpg', $data);
		$_POST[$value] = 'admin/file/'.$memNo.'/'.date("YmdHis").'-'.$key.'.jpg';
	}
	//若沒錯誤的話
	if(!isset($errMsg)){
		foreach($_POST as $keyCheck=>$valCheck){
			if(!in_array($keyCheck,$columnArr)){
				unset($_POST[$keyCheck]);
			}
		}
		$api->insert($_POST);
		$rbsNo = $api->db->getIdForInsertedRecord();
		//發送EMAIL
		$email = new Email();
		$title = "【樂分期購物網】 ".$memberData[0]['memName']."  先生/小姐，申請推薦碼獎金".$_POST["rbsTotal"]."元";
		$content = '<table width="660" align="center" cellpadding="10" cellspacing="1" style="border:3px solid #999;">
							<tbody>
								<tr>
									<td style="text-align:center;">
										<img src="http://happyfan7.com/assets/images/logo_2.png" />
									</td>
								</tr>
								<tr>
									<td style="color:#FF3333;font-weight:bold;text-align:center;">此為系統自動通知信，請勿直接回信！</td>
								</tr>
								<tr>
									<td style="color:black;font-weight:bold;">
										<p>顧客姓名：<span style="color:#FF9900;">'.$memberData[0]['memName'].' </span> 先生/小姐</p>
									</td>
								</tr>
								<tr>
									<td style="font-weight:bold;background-color:#F5F3F1;">
										系統編號：'.$rbsNo.'<br>
									</td>
								</tr>
								<tr>
									管理員可至連結至後台>會員管理>推薦碼獎金撥款，進行後續撥款作業。
								</tr>
							</tbody>
						</table>';
		$send = $email->SendEmail_smtp('service@happyfan7.com',"EC部", "happyfan7@21-finance.com", "樂分期", $title, $content);
		//並對原資料做更改
		$forRba = new API("recomm_bonus_apply");
		$rbaNoArr = json_decode($rbaNoForApply);
		foreach($allRbaData as $key=>$value){
			if($value['rbaRecMemNo'] == $memNo && $value['rbaExtract'] != '1'){
				$forRba->update(array("rbaExtract"=>'1'), $value["rbaNo"]);
			}
		}
		$api->setInformation(true, 1, 0, "推驗獎金申請成功");
	}else{
		$api->setInformation(false, 0, 0, $errMsg);
	}
}else{
	$api->setInformation(false, 0, 0, $errMsg);
}
$api->setResult();

function base64_url_encode($input) {
	return strtr(base64_encode($input), '+/=', '-_,');
}

function base64_url_decode($input) {
	return base64_decode(strtr($input, '-_,', '+/='));
}


?>