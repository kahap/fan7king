<?php
	header('Content-type:application/vnd.ms-excel');  //宣告網頁格式
    header('Content-Disposition: attachment; filename=happyfan7.xls');  //設定檔案名稱
	include('../model/php_model.php');
	$orders = new Orders();
	$db = new WADB(SYSTEM_DBHOST, SYSTEM_DBNAME, SYSTEM_DBUSER, SYSTEM_DBPWD);

	$sql = "SELECT B.pmNo,A.proNo,A.proName FROM  `product` as A,`product_manage` as B  WHERE  A.`proName` LIKE  '%iPhone%' && B.proNo = A.proNo";
	$data = $db->selectRecords($sql);
	$i=0;
	foreach($data as $key => $value){
		$sql1 = "SELECT count(memNo) as number,orProSpec FROM `orders` where pmNo = '".$value['pmNo']."' && `orStatus` = '10'  group by orProSpec";
		$data1 = $db->selectRecords($sql1);
		foreach($data1 as $k => $v){
			if($v['number'] != '0'){
				$array[$i]['pmNo'] = $value['pmNo'];
				$array[$i]['proName']  = $value['proName'];
				$array[$i]['color'] = $v['orProSpec'];
				$array[$i]['number'] = $v['number'];
				$i++;
			}
		}
		
	}
	echo "<table border='1'><thead><th>編號</th><th>商品名稱</th><th>顏色</th><th>購買數量</th></thead><tbody>";
	foreach($array as $key => $value){
			echo "<tr><td>".$value['pmNo']."</td><td>".$value['proName']."</td><td>".$value['color']."</td><td>".$value['number']."</td></tr>";
		
	}
	echo "</tbody></table>";

	
	
	
	
?>