<?php
	//header('Content-type:application/vnd.ms-excel');  //宣告網頁格式
    //header('Content-Disposition: attachment; filename=happyfan7.xls');  //設定檔案名稱
	include('../model/php_model.php');
	$orders = new Orders();
	$db = new WADB(SYSTEM_DBHOST, SYSTEM_DBNAME, SYSTEM_DBUSER, SYSTEM_DBPWD);

	$sql = "SELECT * FROM `orders` where `orStatus` = '10' && orInternalCaseNo != '' order by orReportPeriod10Date asc limit 0,100";
	$data = $db->selectRecords($sql);
	$i=0;
	foreach($data as $key => $value){
		$sql1 = "SELECT * FROM `member` where memNo = '".$value['memNo']."'";
		$data1 = $db->selectRecords($sql1);
		foreach($data1 as $k => $v){
			$array[$i]['memNo'] = $v['memNo'];
			$array[$i]['memName']  = $v['memName'];
			$array[$i]['orAppAuthenIdImgTop'] = $value['orAppAuthenIdImgTop'];
			$array[$i]['orAppAuthenIdImgBot'] = $value['orAppAuthenIdImgBot'];
			$i++;
		}
		
	}
	echo "<table border='1'><thead><th>編號</th><th>姓名</th><th>身分證正面</th><th>身分證反面</th></thead><tbody>";
	foreach($array as $key => $value){
			echo "<tr><td>".$value['memNo']."</td><td>".$value['memName']."</td><td><img src='http://nowait.shop/".$value['orAppAuthenIdImgTop']."' width='320px' height='160px'></td><td><img src='http://nowait.shop/".$value['orAppAuthenIdImgBot']."' width='320px' height='160px'></td></tr>";
		
	}
	echo "</tbody></table>";

	
	
	
	
?>