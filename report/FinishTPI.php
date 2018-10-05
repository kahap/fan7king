<?php
//業務人員報表
	include('../model/php_model.php');
	header("Content-Type:text/html; charset=utf-8");
	header('Content-type:application/vnd.ms-excel');  //宣告網頁格式
    header('Content-Disposition: attachment; filename='.date('Y-m-d',time()).'3C_手機分期結清案件.xls');  //設定檔案名稱*/
	$member = new Member();
	$db = new WADB(SYSTEM_DBHOST, SYSTEM_DBNAME, SYSTEM_DBUSER, SYSTEM_DBPWD);
	//$sql = "SELECT a.pmSupPrice,a.pmPeriodAmnt,a.pmUpDate,b.proName,c.supName FROM `product_manage` a,`product` b, `supplier` c where a.pmUpDate BETWEEN '2018-01-01' AND now() and a.pmStatus = '1' and a.pmMainSup = '1' and a.proNo = b.proNo and a.supNo = c.supNo";
	//date("d",time())
	$sql = "SELECT a.rcCaseNo,a.memNo, a.rcType, a.rcRelateDataNo, a.rcPeriodAmount, b.tpiSupposeDate, b.tpiPeriodTotal,b.tpiActualDate, c.memName, c.memCell, d.mcoMotorBrand FROM `real_cases` a, tpi b,member c, motorbike_cellphone_orders d where a.rcNo = b.rcNo && a.rcPeriodAmount = b.tpiPeriod && b.tpiActualDate != '' && a.memNo = c.memNo && a.rcRelateDataNo = d.mcoNo && a.rcType = '1' group by a.rcNo";
	$data = $db->selectRecords($sql);
	
	$sql1 = "SELECT a.rcCaseNo,a.memNo, a.rcType, a.rcRelateDataNo, a.rcPeriodAmount, b.tpiSupposeDate, b.tpiPeriodTotal,b.tpiActualDate, c.memName, c.memCell, d.pmNo,f.proName FROM `real_cases` a, tpi b,member c,orders d,product_manage e,product f where a.rcNo = b.rcNo && a.rcPeriodAmount = b.tpiPeriod && b.tpiActualDate != '' && a.memNo = c.memNo && a.rcRelateDataNo = d.orNo && d.pmNo = e.pmNo && e.proNo = f.proNo && a.rcType = '0' group by a.rcNo";
	$data1 = $db->selectRecords($sql1);
	$type = array("3C案件","手機案件","機車案件");
?>
<style>  
td {mso-number-format: \@}  
</style>  
	<table border='1'>
		<thead>
			<th>案件編號</th>
			<th>會員姓名</th>
			<th>產品種類</th>
			<th>手機</th>
			<th>期金</th>
			<th>期數</th>
			<th>最後應繳日期</th>
			<th>最後繳款日期</th>
		</thead>
		<tbody>
		<?php foreach($data1 as $key => $value){ ?>
		<tr>
			<td class="num"><?=$value['rcCaseNo'] ?></td>
			<td><?=$value['memName'] ?></td>
			<td><?=$value['proName']; ?></td>
			<td><?=$value['memCell'] ?></td>
			<td><?=$value['tpiPeriodTotal'] ?></td>
			<td><?=$value['rcPeriodAmount'] ?></td>
			<td><?=$value['tpiSupposeDate'] ?></td>
			<td><?=$value['tpiActualDate'] ?></td>
		</tr>
		<?php } ?>
		<?php foreach($data as $key => $value){ ?>
		<tr>
			<td class="num"><?=$value['rcCaseNo'] ?></td>
			<td><?=$value['memName'] ?></td>
			<td><?=$value['mcoMotorBrand']; ?></td>
			<td><?=$value['memCell'] ?></td>
			<td><?=$value['tpiPeriodTotal'] ?></td>
			<td><?=$value['rcPeriodAmount'] ?></td>
			<td><?=$value['tpiSupposeDate'] ?></td>
			<td><?=$value['tpiActualDate'] ?></td>
		</tr>
		<?php } ?>
		</tbody>
	</table>