<?php
	header('Content-type:application/vnd.ms-excel');  //宣告網頁格式
    header('Content-Disposition: attachment; filename=happyfan7.xls');  //設定檔案名稱
	include('../model/php_model.php');
	$member = new Member();
	$db = new WADB(SYSTEM_DBHOST, SYSTEM_DBNAME, SYSTEM_DBUSER, SYSTEM_DBPWD);
	
	/*一般會員繳費
	$sql = "SELECT `memNo`,`memSubEmail` FROM `member`  WHERE `memAccount` != '' && `memFBtoken` = '' && `memSubEmail` != 'tom200e@hotmail.com'";
	$data = $db->selectRecords($sql);
	
	
	
	$sql1 = "SELECT memNo FROM `orders` where orStatus = '10'";
	$data1 = $db->selectRecords($sql1);
	
	foreach($data1 as $k => $v){
		$not_order[] = $v['memNo'];
	}
	
	$i=1;
	echo "<table border='1'><thead><th>會員編號</th><th>Email</th></thead><tbody>";
	foreach($data as $key => $value){
		if(in_array($value['memNo'],$not_order)){
			
			echo "<tr><td>".$value['memNo']."</td><td>".$value['memSubEmail']."</td></tr>";
			$i++;
		}
		
	}
	SELECT * FROM member  WHERE `memRegistDate` between '2017-02-10' and '2017-03-12' && memClass = '0' && `memDeviceToken` != ''
	
	echo "</tbody></table>";
	*/ 
	$sql = "SELECT * FROM `app_data` a,member b WHERE  b.memNo = a.memNo && a.`time` between '2017-02-10' and '2017-03-12' && b.memClass = '0' && FLOOR(DATEDIFF (NOW(), b.`memBday`)/365) > 18 group by a.`memNo`";
	$data = $db->selectRecords($sql);
	echo "<tr><td>這是大於18歲 且是 學生 然後包含老用戶</td></tr>";
	echo "<table border='1'><thead><th>會員編號</th><th>Email</th><th>FB</th><th>姓名</th><th>電話</th><th>年齡</th></thead><tbody>";
	foreach($data as $key => $value){
			echo "<tr><td>".$value['memNo']."</td><td>".$value['memSubEmail']."</td><td>".$value['memFBtoken']."</td><td>".$value['memName']."</td><td>".$value['memCell']."</td><td>".$value['memBday']."</td></tr>";
	}
	echo "</tbody></table>";
	
	
	
	
	
?>