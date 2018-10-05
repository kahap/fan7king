<?php
//業務人員報表
	include('../model/php_model.php');
	header("Content-Type:text/html; charset=utf-8");
	header('Content-type:application/vnd.ms-excel');  //宣告網頁格式
    header('Content-Disposition: attachment; filename='.date('Y-m-d',time()).'已簽約通訊行報表.xls');  //設定檔案名稱
	$member = new Member();
	$db = new WADB(SYSTEM_DBHOST, SYSTEM_DBNAME, SYSTEM_DBUSER, SYSTEM_DBPWD);

	
	$star = ($_GET['start'] != '') ?  $_GET['start']:'2018-02-01';
	$end = ($_GET['end'] != '') ?  $_GET['end']:'2018-04-31';
	
	$sql = "SELECT a.supDate,b.aauName,a.supPostCode,a.supNo,a.supName,a.supSerialNo,a.supAddr,a.supContactName,a.supPhone,a.supContactName,supCell FROM `supplier` a, admin_advanced_user b where a.aauNo = b.aauNo && a.aauNo != '' order by a.aauNo asc"; 
	$data = $db->selectRecords($sql);
 
?>
<style>  
td {mso-number-format: \@}  
</style>  
	<table border='1'>
		<thead>
			<th>建檔日期</th>
			<th>業務人員</th>
			<th>區域碼</th>
			<th>編號</th>
			<th>通訊行名稱</th>
			<th>統編</th>
			<th>營業地址</th>
			<th>負責人</th>
			<th>負責人電話</th>
			<th>聯絡人</th>
			<th>聯絡人電話</th>
		</thead>
		<tbody>
		<?php foreach($data as $key => $value){ ?>
		<tr>
			<td class="num"><?=$value['supDate'] ?></td>
			<td><?=$value['aauName'] ?></td>
			<td><?=$value['supPostCode'] ?></td>
			<td><?=$value['supNo'] ?></td>
			<td><?=$value['supName'] ?></td>
			<td><?=$value['supSerialNo'] ?></td>
			<td><?=$value['supAddr'] ?></td>
			<td><?=$value['supContactName'] ?></td>
			<td><?=$value['supPhone'] ?></td>
			<td><?=$value['supContactName'] ?></td>
			<td><?=$value['supCell'] ?></td>
		</tr>
		<?php } ?>
		</tbody>
	</table>