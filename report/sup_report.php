<?php
//業務人員報表
	include('../model/php_model.php');
	header("Content-Type:text/html; charset=utf-8");
	header('Content-type:application/vnd.ms-excel');  //宣告網頁格式
    header('Content-Disposition: attachment; filename='.date('Y-m-d',time()).'銷售業績報表.xls');  //設定檔案名稱
	$member = new Member();
	$db = new WADB(SYSTEM_DBHOST, SYSTEM_DBNAME, SYSTEM_DBUSER, SYSTEM_DBPWD);
	//$sql = "SELECT a.pmSupPrice,a.pmPeriodAmnt,a.pmUpDate,b.proName,c.supName FROM `product_manage` a,`product` b, `supplier` c where a.pmUpDate BETWEEN '2018-01-01' AND now() and a.pmStatus = '1' and a.pmMainSup = '1' and a.proNo = b.proNo and a.supNo = c.supNo";
	
	$star = ($_GET['start'] != '') ?  $_GET['start']:'2018-02-01';
	$end = ($_GET['end'] != '') ?  $_GET['end']:'2018-04-31';
	
	$sql = "SELECT a.rcCaseNo, a.rcStatus,a.`rcRelateDataNo`,a.`rcBankTransferAmount`,a.`rcPeriodAmount`,a.`rcPeriodTotal`,a.`rcDate`,b.orNo, b.supNo, b.pmNo,c.proNo,b.orSupPrice,c.pmPeriodAmnt,d.proName,e.supName,f.catName from real_cases a,orders b,product_manage c,product d,supplier e,category f where a.rcDate BETWEEN '".$star."' and '".$end."' and a.`rcType` = '0' and a.rcStatus = '3' and a.rcRelateDataNo = b.orNo and b.pmNo = c.pmNo and c.proNo = d.proNo and b.supNo = e.supNo and d.catNo = f.catNo"; 
	$data = $db->selectRecords($sql);
 
?>
<style>  
td {mso-number-format: \@}  
</style>  
	<table border='1'>
		<thead>
			<th>案件編號</th>
			<th>商品類別</th>
			<th>商品名稱</th>
			<th>分期期數</th>
			<th>客戶應付總金額</th>
			<th>實際撥款金額</th>
			<th>供應商名稱</th>
			<th>供應價格</th>
			<th>分期基礎價</th>
			<th>下單日期</th>
		</thead>
		<tbody>
		<?php foreach($data as $key => $value){ ?>
		<tr>
			<td class="num"><?=$value['rcCaseNo'] ?></td>
			<td><?=$value['catName'] ?></td>
			<td><?=$value['proName'] ?></td>
			<td><?=$value['rcPeriodAmount'] ?></td>
			<td><?=$value['rcPeriodTotal'] ?></td>
			<td><?=$value['rcBankTransferAmount'] ?></td>
			<td><?=$value['supName'] ?></td>
			<td><?=$value['orSupPrice'] ?></td>
			<td><?=$value['pmPeriodAmnt'] ?></td>
			<td><?=$value['rcDate'] ?></td>
		</tr>
		<?php } ?>
		</tbody>
	</table>