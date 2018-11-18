<?php
header (" content-type: text/HTML; charset=utf-8 ");
require_once('../../model/require_login.php');

$or = new Orders();
$pro = new Product();
$pm = new Product_Manage();
$mem = new Member();

date_default_timezone_set('Asia/Taipei');
$deadlineYear = date('Y', strtotime("+3 days"));
$deadlineMonth = date('n', strtotime("+3 days"));
$deadlineDate = date('Ymd', strtotime("+3 days"));
$deadlineDateStr = date('Y/n/d', strtotime("+3 days"));

$orArr = json_decode(json_decode(file_get_contents("http://api.21-finance.com/api/Deadline/".$deadlineDate)));

foreach($orArr as $value){
	$orData = $or->getOrByInternalCase($value);
	if($orData != null){
		$email = new Email();
		$memData = $mem->getOneMemberByNo($orData[0]["memNo"]);
		$pmData = $pm->getOnePMByNo($orData[0]["pmNo"]);
		$proData = $pro->getOneProByNo($pmData[0]["proNo"]);
		
		$emailAddr = "";
		if(trim($memData[0]["memSubEmail"]) != ""){
			$emailAddr = $memData[0]["memSubEmail"];
		}else{
			$emailAddr = $memData[0]["memAccount"];
		}
		
		$title = "【NoWait購物網】分期繳款通知".$deadlineYear."年".$deadlineMonth."月";
		
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
							<td style="color:#FF3333;font-weight:bold;text-align:center;">**此封Email為系統統一發送，如您已繳款請不需理會，謝謝**</td>
						</tr>
						<tr>
							<td style="color:black;font-weight:bold;">
								<p>親愛的<span style="color:#FF9900;">'.$memData[0]['memName'].' </span> 先生/小姐，您好：</p>
							</td>
						</tr>
						<tr>
							<td style="font-weight:bold;background-color:#F5F3F1;">
								<p>提醒您於<a href="https://nowait.shop/" target="_blank">NoWait購物網</a>消費訂單編號之<span style="color:blue;">'.$orData[0]["orCaseNo"].'</span>訂單，共分 <b style="text-decoration:underline;">'.$orData[0]["orPeriodAmnt"].'</b> 期，最近一期繳款期限為<span style="color:red;">'.$deadlineDateStr.'</span>，應繳款金額為 <b style="text-decoration:underline;">'.number_format($orData[0]["orPeriodTotal"]/$orData[0]["orPeriodAmnt"]).'</b> 元。 </p>
								<p>
									請您點選<span style="color:blue;">會員中心</span>><span style="color:blue;">我要繳款</span>>對應訂單編號<span style="color:blue;">'.$orData[0]["orCaseNo"].'</span>點選
									<span style="color:red;">【我要繳款】</span>下之<span style="color:red;">【前往】</span>連結，畫面中找到<b>應繳款日</b><span style="color:red;">'.$deadlineDateStr.'</span>，
									再點選<span style="color:red;">【超商繳費】</span>下該列<span style="color:red;">【開啟】</span>連結，將跳出一新網頁，顯示您的<b>姓名、本期帳單應繳金額及超商繳款條碼</b>。此時，
									您將可攜帶您的行動裝置(手機或平板或筆電)，至超商繳款櫃台刷條碼繳納本期帳單應繳金額，謝謝！
								</p>
								<div style="margin:20px 0;border-top:1px solid #000;width:100%;height:1px;"></div>
								<p>
									<span style="color:red;">★註：請準時繳款以維護自身信用、超過繳款期限將依「<a href="https://nowait.shop/index.php?item=fmPeriodDeclare" target="_blank">分期付款約定書</a>」之約定條款，可能會導致遲繳違約金及滯納金的產生。</span>
								</p>
							</td>
						</tr>
					</tbody>
				</table>
				';
		$send = $email->SendEmail_smtp($emailAddr,$memData[0]['memName'], "service@nowait.shop", "NoWait", $title, $content);
	}
}

?>