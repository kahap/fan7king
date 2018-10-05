<?php
//業務人員報表
	include('../model/php_model.php');
	header("Content-Type:text/html; charset=utf-8");
	header('Content-type:application/vnd.ms-excel');  //宣告網頁格式
    header('Content-Disposition: attachment; filename='.date('Y-m-d',time()).'商品報表.xls');  //設定檔案名稱
	$member = new Member();
	$db = new WADB(SYSTEM_DBHOST, SYSTEM_DBNAME, SYSTEM_DBUSER, SYSTEM_DBPWD);
	//$sql = "SELECT a.pmSupPrice,a.pmPeriodAmnt,a.pmUpDate,b.proName,c.supName FROM `product_manage` a,`product` b, `supplier` c where a.pmUpDate BETWEEN '2018-01-01' AND now() and a.pmStatus = '1' and a.pmMainSup = '1' and a.proNo = b.proNo and a.supNo = c.supNo";
	
	$star = ($_GET['start'] != '') ?  $_GET['start']:'2018-02-01';
	$end = ($_GET['end'] != '') ?  $_GET['end']:'2018-04-31';
	
	$sql = "SELECT a.supNo,a.pmStatus,a.pmSupPrice,a.pmPeriodAmnt,a.pmBuyAmnt,a.pmMainSup,a.pmUpDate,a.proNo,b.catNo,b.proName,c.supName,d.catName FROM `product_manage` a,product b ,supplier c,category d where a.pmUpDate BETWEEN '".$star."' and '".$end."' and a.proNo = b.proNo and a.supNo = c.supNo and b.catNo = d.catNo order by a.proNo";
	$data = $db->selectRecords($sql);
	$status = array('下架中','上架中','缺貨中');
?>
<style>  
td {mso-number-format: \@}  
</style>  
	<table border='1'>
		<thead>
			<th>商品類別</th>
			<th>商品名稱</th>
			<th>是否為主要供應商</th>
			<th>供應商名稱</th>
			<th>供應價格</th>
			<th>分期基礎價</th>
			<th>上架日期</th>
			<th>狀態</th>
			<th>實際購買人數</th>
		</thead>
		<tbody>
		<?php foreach($data as $key => $value){ ?>
		<tr>
			<td><?=$value['catName'] ?></td>
			<td><?=$value['proName'] ?></td>
			<td><?=(($value['pmMainSup'] == "1") ? "是":"否");?></td>
			<td><?=$value['supName'] ?></td>
			<td><?=$value['pmSupPrice'] ?></td>
			<td><?=$value['pmPeriodAmnt'] ?></td>
			<td><?=$value['pmUpDate'] ?></td>
			<td><?=$status[$value['pmStatus']] ?></td>
			<td><?=$value['pmBuyAmnt'] ?></td>
		</tr>
		<?php } ?>
		</tbody>
	</table>