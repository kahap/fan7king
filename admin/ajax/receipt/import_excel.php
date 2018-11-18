<?php
header ("content-type: text/HTML; charset=utf-8");
require_once('../../model/require_login.php');
require_once '../../cls/Excel/reader.php';

$or = new Orders();
$mem = new Member();
$pm = new Product_Manage();
$pro = new Product();

$orNoArr = array();
$orCaseNoArr = array();
$orInternalCaseNoArr = array();
$receiptNumArr = array();
$receiptDateArr = array();
$memNameArr = array();

$errMsg = "";

if($_FILES["upload"]['error'] == 4) {
	$errMsg = "請選擇檔案";
}else{
	$tmp = explode(".", $_FILES["upload"]["name"]);
	$extension = end($tmp);
	if($_FILES["upload"]['type'] != "application/vnd.ms-excel" || $extension != "xls"){
		$errMsg = "必須上傳xls檔";
	}
}


if($errMsg == ""){
	// ExcelFile($filename, $encoding);
	$data = new Spreadsheet_Excel_Reader();
	// Set output Encoding.
	$data->setOutputEncoding('UTF-8');
	
	$data->read($_FILES["upload"]["tmp_name"]);
	
	for ($i = 2; $i <= $data->sheets[0]['numRows']; $i++) {
		$orData = $or->getOrByInternalCase($data->sheets[0]['cells'][$i][1]);
		if($orData != null){
			if($orData[0]["orIfSetReceipt"] != 1){
				$or->updateIfSetReceipt(1, $orData[0]["orNo"]);
				//EMAIL
				$email = new Email();
				$memData = $mem->getOneMemberByNo($orData[0]["memNo"]);
				$pmData = $pm->getOnePMByNo($orData[0]["pmNo"]);
				$proData = $pro->getOneProByNo($pmData[0]["proNo"]);
				
				$receiptDate = $data->sheets[0]['cells'][$i][6];
				$receiptDateJson = explode("/",$receiptDate);
				
				$emailAddr = "";
				if(trim($memData[0]["memSubEmail"]) != ""){
					$emailAddr = $memData[0]["memSubEmail"];
				}else{
					$emailAddr = $memData[0]["memAccount"];
				}
				
				$title = "【NoWait購物網】您所購買的商品已完成發票開立！";
				
				$content = '
					<table width="660" align="center" cellpadding="10" cellspacing="1" style="border:3px solid #999;">
						<tbody>
							<tr>
								<td style="text-align:center;">
									<img src="https://happyfan7.com/assets/images/logo_2.png" />
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
									<p>NoWait網站通知您購買訂單編號<span style="color:red;">'.$orData[0]["orCaseNo"].'</span>的發票已開立完成！</p>
									<p>
										您此次訂購的商品明細如下：<br>
										訂購日期：'.$orData[0]["orDate"].'<br>
										訂購商品：'.$proData[0]["proName"].'<br>
										商品規格：'.$orData[0]["orProSpec"].'<br>
										發票號碼：'.$data->sheets[0]['cells'][$i][5].'<br>
										發票日期：'.($receiptDateJson[0]-1911).'/'.$receiptDateJson[1].'/'.$receiptDateJson[2].'<br>
									</p>
									<p>
										您可至<a href="https://www.einvoice.nat.gov.tw/APMEMBERVAN/PublicAudit/PublicAudit" target="_blank">財政部電子發票整合服務平台</a>輸入<span style="color:red;">發票號碼</span>及<span style="color:red;">發票日期</span>查看發票開立狀況，謝謝。
									</p>
									<p>
										感謝您的支持，如有疑問歡迎到 <a href="https://happyfan7.com/?item=fmContactService" target="_blank">聯絡客服</a> 反應，NoWait將會為您處理。如需訂購其他商品請至 <a href="https://happyfan7.com/" target="_blank">NoWait購物網</a>選購。
									</p>
								</td>
							</tr>
						</tbody>
					</table>
					';
				$send = $email->SendEmail_smtp($emailAddr,$memData[0]['memName'], "happyfan7@21-finance.com", "NoWait", $title, $content);
				array_push($orNoArr, $orData[0]["orNo"]);
				array_push($orCaseNoArr, $orData[0]["orCaseNo"]);
				array_push($orInternalCaseNoArr, $orData[0]["orInternalCaseNo"]);
				array_push($memNameArr, $memData[0]["memName"]);
				array_push($receiptNumArr, $data->sheets[0]['cells'][$i][5]);
				array_push($receiptDateArr, $data->sheets[0]['cells'][$i][6]);
			}
		}
	}
	
	if(!empty($orNoArr)){
		$results = array("orCaseNo"=>$orCaseNoArr,"orInternalCaseNo"=>$orInternalCaseNoArr,"memName"=>$memNameArr,"receiptNo"=>$receiptNumArr,"receiptDate"=>$receiptDateArr);
		echo json_encode($results,true);
	}else{
		$errMsg = "檔案上傳不正確，或者全部發票已開立完成";
		echo $errMsg;
	}
}else{
	echo $errMsg;
}




?>