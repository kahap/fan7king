<html>
<head>
	<meta charset="UTF-8">

 
	<!-- DO NOT MODIFY -->
  
	<!-- End Facebook Pixel Code -->
</head>
<body>
<!-- google ad -->
<script>
	<!--google Ad-->
	var google_conversion_id = 872321587;
	var google_conversion_label = "YWw3CPy7hWwQs6T6nwM";
	var google_remarketing_only = false;
</script>
<script type="text/javascript" src="//www.googleadservices.com/pagead/conversion.js"></script>
<noscript>
<div style="display:inline;">
<img height="1" width="1" style="border-style:none;" alt=""  
src="//www.googleadservices.com/pagead/conversion/872321587/?label=YWw3CPy7hWwQs6T6nwM&amp;guid=ON&amp;script=0"/>
</div>
</noscript>

<!-- Facebook Pixel Code -->
<script>
!function(f,b,e,v,n,t,s)
{if(f.fbq)return;n=f.fbq=function(){n.callMethod?
n.callMethod.apply(n,arguments):n.queue.push(arguments)};
if(!f._fbq)f._fbq=n;n.push=n;n.loaded=!0;n.version='2.0';
n.queue=[];t=b.createElement(e);t.async=!0;
t.src=v;s=b.getElementsByTagName(e)[0];
s.parentNode.insertBefore(t,s)}(window,document,'script',
'https://connect.facebook.net/en_US/fbevents.js');
 fbq('init', '1786588334914903'); 
fbq('track', 'PageView');
</script>
<noscript>
 <img height="1" width="1" 
src="https://www.facebook.com/tr?id=1786588334914903&ev=PageView
&noscript=1"/>
</noscript>
<!-- End Facebook Pixel Code -->
<?php
	include('../model/php_model.php');
	$co_company = new co_company();
	$email = new Email();
	if(!empty($_POST)){
		foreach($_POST as $key =>$value){
			if($key != "message"){
				$state[] = ($value != "") ? 1:0;
			}
		}
			if(!in_array('0',$state)){
				
				$receiverNameAndEmails = Array('service@nowait.shop'=>"EC部","happyfan7@21-finance.com"=>"客服部","sinlenlin@gmail.com"=>"林青嵐","achappyfan7@gmail.com"=>"Allan",'21car7751@gmail.com'=>'客服1','aa22760676@gmail.com'=>'客服人員D','elsa0822@gmail.com'=>'客服人員E');
				$title = "【NoWait-貸款VIP通知】".$_POST['name']."先生/小姐";
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
										<p>姓名：<span style="color:#FF9900;">'.$_POST['name'].' </span> 先生/小姐</p>
									</td>
								</tr>
								<tr>
									<td style="font-weight:bold;background-color:#F5F3F1;">
										【NoWait-貸款通知】<br>
										貸款類別：'.$_POST['category'].'<br>
										車號：'.$_POST['email'].'<br>
										電話：'.$_POST['phone'].'<br>
										身分別：'.$_POST['class'].'<br>
										備註：'.$_POST['message'].'<br>
									</td>
								</tr>
							</tbody>
						</table>';
				$send = $email->SendBCCEmail_smtp($receiverNameAndEmails, "service@nowait.shop", "NoWait", $title, $content);
				$co_company->inser_loanVIP($_POST);
				echo "<script>fbq('track', 'InitiateCheckout');<script>";
				echo "<script>gtag('event', 'conversion', {'send_to': 'AW-872321587/YWw3CPy7hWwQs6T6nwM'});</script>";
				alert_message('../index.php?item=loan_vip','需求已送出,專人會在上班時間與您聯絡!');
			}else{
				echo "<script>alert('請填寫完整')</script>";
				echo "<script>history.go(-1)</script>";
			}
	}else{
		alert_message('../index.php?item=loan_vip','請填寫完整');
	}
?>
</body>
</html>
