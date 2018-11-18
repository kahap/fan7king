<?php
header('Content-Type: text/html; charset=utf8');
require_once('../../model/require_ajax.php');
require_once('../../../admin/cls/Excel/reader.php');
date_default_timezone_set('Asia/Taipei');

$api = new API("real_cases");
$apiMem = new API("member");
$db = new WADB(SYSTEM_DBHOST, SYSTEM_DBNAME, SYSTEM_DBUSER, SYSTEM_DBPWD);

if($_FILES["uploadcussup"]['error'] == 4) {
	$errMsg = "請選擇檔案";
}else{
	$tmp = explode(".", $_FILES["uploadcussup"]["name"]);
	$extension = end($tmp);
	if($_FILES["uploadcussup"]['type'] != "application/vnd.ms-excel" || $extension != "xls"){
		$errMsg = "必須上傳xls檔";
	}
}

if(!isset($errMsg)){
	$data = new Spreadsheet_Excel_Reader();
	$data->setOutputEncoding('UTF-8');
	$data->read($_FILES["uploadcussup"]["tmp_name"]);
	for ($i = 2; $i <= $data->sheets[0]['numRows']; $i++) {
		//print_r($data->sheets[0]['cells'][$i][64]);
		$api->setWhereArray(array("rcInvoiceNumber"=>$data->sheets[0]['cells'][$i][6]));
		$rcData = $api->getWithConditions(true);
		$memData = $apiMem->getOne($rcData[0]["memNo"]);
		$sql = "UPDATE `real_cases` SET `receiptNumber`='".$data->sheets[0]['cells'][$i][64]."',`receiptDate`='".$data->sheets[0]['cells'][$i][4]."' where `rcNo` = '".$rcData[0]['rcNo']."'";
		$db->updateRecords($sql);
	
		if($rcData[0]["rcType"] == "0"){
			
		
			$pro = new API("product");
			$pm = new API("product_manage");
			$sql1 = "select orCaseNo,orDate,orProSpec,pmNo from `orders` where orNo='".$rcData[0]["rcRelateDataNo"]."'";
			$orData = $db->selectRecords($sql1);
			$pmData = $pm->getOne($orData[0]["pmNo"]);
			$proData = $pro->getOne($pmData[0]["proNo"]);	
		
		
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
									<p>NoWait網站通知您購買訂單編號<span style="color:red;">'.$orData[0]["orCaseNo"].'</span>的發票已開立完成！</p>
									<p>
										您此次訂購的商品明細如下：<br>
										訂購日期：'.$orData[0]["orDate"].'<br>
										訂購商品：'.$proData[0]["proName"].'<br>
										商品規格：'.$orData[0]["orProSpec"].'<br>
										發票號碼：'.$data->sheets[0]['cells'][$i][64].'<br>
										發票日期：'.$data->sheets[0]['cells'][$i][4].'<br>
									</p>
									<p>
										您可至<a href="https://www.einvoice.nat.gov.tw/APMEMBERVAN/PublicAudit/PublicAudit" target="_blank">財政部電子發票整合服務平台</a>輸入<span style="color:red;">發票號碼</span>及<span style="color:red;">發票日期</span>查看發票開立狀況，謝謝。
									</p>
									<p>
										感謝您的支持，如有疑問歡迎到 <a href="https://nowait.shop/?item=fmContactService" target="_blank">聯絡客服</a> 反應，NoWait將會為您處理。如需訂購其他商品請至 <a href="https://nowait.shop/" target="_blank">NoWait購物網</a>選購。
									</p>
								</td>
							</tr>
						</tbody>
					</table>
					';
				$email = new Email();
				$send = $email->SendEmail_smtp($emailAddr,$memData[0]['memName'], "happyfan7@21-finance.com", "NoWait", $title, $content);
		}
		
	}
	$apiRR = new API("receipt_record");
	$target_file = '../../file/receipt/'.date("Ymd",time()).".".$tmp[1];
	move_uploaded_file($_FILES["uploadcussup"]["tmp_name"], $target_file);
	$receiptData = array("path"=>$target_file);
	$apiRR->insert($receiptData);
	echo "OK";
}else{
	echo $errMsg;
}