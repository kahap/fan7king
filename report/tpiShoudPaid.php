<?php
//業務人員報表
	include('../model/php_model.php');
	header("Content-Type:text/html; charset=utf-8");
	header('Content-type:application/vnd.ms-excel');  //宣告網頁格式
    header('Content-Disposition: attachment; filename=2017-12-31債權餘額表.xls');  //設定檔案名稱*/
	$member = new Member();
	$db = new WADB(SYSTEM_DBHOST, SYSTEM_DBNAME, SYSTEM_DBUSER, SYSTEM_DBPWD);
	//$sql = "SELECT a.pmSupPrice,a.pmPeriodAmnt,a.pmUpDate,b.proName,c.supName FROM `product_manage` a,`product` b, `supplier` c where a.pmUpDate BETWEEN '2018-01-01' AND now() and a.pmStatus = '1' and a.pmMainSup = '1' and a.proNo = b.proNo and a.supNo = c.supNo";
	//date("d",time())
	$sql = "select a.rcNo,c.rcCaseNo,a.tpiPeriodTotal,c.rcPeriodAmount,d.memName,sum(a.tpiPeriodTotal) as ShouldPaid, b.PaidTotal from tpi a inner join (SELECT rcNo,sum(tpiPeriodTotal) as PaidTotal FROM `tpi` where tpiActualDate <= '2017-12-31' && tpiActualDate != '' group by rcNo) as b on a.rcNo = b.rcNo,real_cases c,member d where a.tpiDate < '2017-12-31' && a.rcNo = c.rcNo && c.memNo = d.memNo group by a.rcNo";
	$data = $db->selectRecords($sql);
	$type = array("3C案件","機車案件","手機案件");
?>
<style>  
td {mso-number-format: \@}  
</style>  
	<table border='1'>
		<thead>
			<th>案件編號</th>
			<th>姓名</th>
			<th>期數</th>
			<th>期金</th>
			<th>應繳金額</th>
			<th>已繳金額</th>
			<th>剩餘債權餘額</th>
		</thead>
		<tbody>
		<?php foreach($data as $key => $value){ ?>
		<tr>
			<td class="num"><?=$value['rcCaseNo'] ?></td>
			<td><?=$value['memName']; ?></td>
			<td><?=$value['rcPeriodAmount'] ?></td>
			<td><?=$value['tpiPeriodTotal'] ?></td>
			<td><?=$value['ShouldPaid'] ?></td>
			<td><?=$value['PaidTotal'] ?></td>
			<td><?=($value['ShouldPaid']-$value['PaidTotal']) ?></td>
		</tr>
		<?php } ?>
		</tbody>
	</table>