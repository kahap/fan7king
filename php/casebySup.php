<?php
//業務人員報表
	include('../model/php_model.php');
	header("Content-Type:text/html; charset=utf-8");
	header('Content-type:application/vnd.ms-excel');  //宣告網頁格式
    header('Content-Disposition: attachment; filename='.date('Y-m-d',time()).'供應商報表.xls');  //設定檔案名稱
	$member = new Member();
	$db = new WADB(SYSTEM_DBHOST, SYSTEM_DBNAME, SYSTEM_DBUSER, SYSTEM_DBPWD);
	//$sql = "SELECT a.pmSupPrice,a.pmPeriodAmnt,a.pmUpDate,b.proName,c.supName FROM `product_manage` a,`product` b, `supplier` c where a.pmUpDate BETWEEN '2018-01-01' AND now() and a.pmStatus = '1' and a.pmMainSup = '1' and a.proNo = b.proNo and a.supNo = c.supNo";
	
	$sql = "SELECT e.supName,count(case a.rcStatus when '7' then '1' end) as '取消案件數',count(case a.rcStatus when '4' then '1' end) as '婉拒案件數',count(case a.rcStatus when '3' then '1' end) as '核准案件數',count(case b.orStatus when '10' then '1' end) as '核准並撥款案件數',count(case a.rcStatus when '701' then '1' end) as '自行撤件案件數',count(case when a.rcCaseNo !='' then '1' end) as '進件案件數' from real_cases a,orders b,product_manage c,product d,supplier e where a.rcDate BETWEEN CONCAT(SUBSTRING(DATE(NOW()),1,8),'01') AND NOW() and a.`rcType` = '0' and a.rcRelateDataNo = b.orNo and b.pmNo = c.pmNo and c.proNo = d.proNo and b.supNo = e.supNo group by e.supNo";
	$data = $db->selectRecords($sql);

?>
<style>  
td {mso-number-format: \@}  
</style>  
	<table border='1'>
		<thead>
			<th>供應商名稱</th>
			<th>取消案件數</th>
			<th>婉拒案件數</th>
			<th>核准案件數</th>
			<th>核准並撥款案件數</th>
			<th>自行撤件案件數</th>
			<th>進件案件數</th>
		</thead>
		<tbody>
		<?php foreach($data as $key => $value){ ?>
		<tr>
			<td class="num"><?=$value['supName'] ?></td>
			<td><?=$value['取消案件數'] ?></td>
			<td><?=$value['婉拒案件數'] ?></td>
			<td><?=$value['核准案件數'] ?></td>
			<td><?=$value['核准並撥款案件數'] ?></td>
			<td><?=$value['自行撤件案件數'] ?></td>
			<td><?=$value['進件案件數'] ?></td>
		</tr>
		<?php } ?>
		</tbody>
	</table>