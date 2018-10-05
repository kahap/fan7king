<?php
//業務人員報表
	include('../model/php_model.php');
	header("Content-Type:text/html; charset=utf-8");
	header('Content-type:application/vnd.ms-excel');  //宣告網頁格式
    header('Content-Disposition: attachment; filename='.date('Y-m-d',time()).'已註冊已下單會員表.xls');  //設定檔案名稱
	$member = new Member();
	$db = new WADB(SYSTEM_DBHOST, SYSTEM_DBNAME, SYSTEM_DBUSER, SYSTEM_DBPWD);
	//$sql = "SELECT a.pmSupPrice,a.pmPeriodAmnt,a.pmUpDate,b.proName,c.supName FROM `product_manage` a,`product` b, `supplier` c where a.pmUpDate BETWEEN '2018-01-01' AND now() and a.pmStatus = '1' and a.pmMainSup = '1' and a.proNo = b.proNo and a.supNo = c.supNo";
	
	$star = ($_GET['start'] != '') ?  $_GET['start']:'2018-02-01';
	$end = ($_GET['end'] != '') ?  $_GET['end']:'2018-04-31';
	
	$sql = "SELECT a.memNo, a.memName, a.memSubEmail,b.rcType,b.rcStatus,a.memRegistDate,b.rcDate,b.rcStatus3Time,b.rcStatus4Time,c.adDeviceType as '下單方式' FROM `member` a, real_cases b left JOIN (SELECT adDeviceType,memNo  FROM `app_data`) as c on b.memNo = c.memNo  where memRegistDate between '".$star."' and '".$end."' && a.memNo = b.memNo  group by a.memNo";
	$data = $db->selectRecords($sql);
	$statusArr = array("未完成下單","未進件","審查中","核准","婉拒",
				"待補","補件","取消訂單","出貨中","已收貨","已完成",
				"換貨中","退貨中","完成退貨");
	$type = array("3C案件","手機案件","機車案件");			
?>
<style>  
td {mso-number-format: \@}  
</style>  
	<table border='1'>
		<thead>
			<th>會員編號</th>
			<th>會員名稱</th>
			<th>聯絡Email</th>
			<th>註冊日期</th>
			<th>案件種類</th>
			<th>下單日期</th>			
			<th>案件狀態</th>
			<th>核准日期</th>
			<th>婉拒日期</th>
			<th>下單方式</th>
		</thead>
		<tbody>
		<?php foreach($data as $key => $value){ ?>
		<tr>
			<td><?=$value['memNo'] ?></td>
			<td><?=$value['memName'] ?></td>
			<td><?=$value['memSubEmail'] ?></td>
			<td><?=$value['memRegistDate'] ?></td>
			<td><?=$type[$value['rcType']] ?></td>
			<td><?=$value['rcDate'] ?></td>
			<td><?=$statusArr[$value['rcStatus']] ?></td>
			<td><?=$value['rcStatus3Time'] ?></td>
			<td><?=$value['rcStatus4Time'] ?></td>
			<td><? echo ($value['下單方式'] != '') ? $value['下單方式']:'網站';?></td>
		</tr>
		<?php } ?>
		</tbody>
	</table>