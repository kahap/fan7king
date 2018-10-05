<?php
//業務人員報表
	include('../model/php_model.php');
	header("Content-Type:text/html; charset=utf-8");
	header('Content-type:application/vnd.ms-excel');  //宣告網頁格式
    header('Content-Disposition: attachment; filename='.date('Y-m-d',time()).'通訊行排名報表.xls');  //設定檔案名稱
	$member = new Member();
	$db = new WADB(SYSTEM_DBHOST, SYSTEM_DBNAME, SYSTEM_DBUSER, SYSTEM_DBPWD);	
	$sql = "SELECT e.supName,count(case a.rcStatus when '7' then '1' end) as '取消案件數',count(case a.rcStatus when '4' then '1' end) as '婉拒案件數',count(case a.rcStatus when '3' then '1' end) as '核准案件數',count(case b.orStatus when '10' then '1' end) as '核准並撥款案件數',count(case a.rcStatus when '701' then '1' end) as '自行撤件案件數',count(case when a.rcCaseNo !='' then '1' end) as '進件案件數',e.aauNo from real_cases a,orders b,product_manage c,product d,supplier e where a.rcDate BETWEEN CONCAT(SUBSTRING(DATE(NOW()),1,8),'01') AND NOW() and a.`rcType` = '0' and a.rcRelateDataNo = b.orNo and b.pmNo = c.pmNo and c.proNo = d.proNo and b.supNo = e.supNo group by e.supNo";
	$data = $db->selectRecords($sql);
	
	//CONCAT(SUBSTRING(DATE(NOW()),1,8),'01')
	usort($data, function($a, $b) {
		return ($a['進件案件數'] > $b['進件案件數'])? -1 : 1;
	});
	
	$sql1 = "SELECT aauNo,aauName FROM `admin_advanced_user`";
	$userList = $db->selectRecords($sql1);
	foreach($userList as $key => $value){
		$user[$value['aauNo']] = $value['aauName'];
	}
	$i = '1';
?>
<style>  
td {mso-number-format: \@}  
</style>  
	<table border='1'>
		<thead>
			<th>排名</th>
			<th>供應商名稱</th>
			<th>進件案件數</th>
			<th>自行撤件案件數</th>
			<th>取消案件數</th>
			<th>婉拒案件數</th>
			<th>核准案件數</th>
			<th>核准並撥款案件數</th>
			<th>業務員</th>
			
		</thead>
		<tbody>
		<?php foreach($data as $key => $value){ ?>
		<tr>
			<td><?=$i?></td>
			<td class="num"><?=$value['supName'] ?></td>
			<td><?=$value['進件案件數'] ?></td>
			<td><?=$value['自行撤件案件數'] ?></td>
			<td><?=$value['取消案件數'] ?></td>
			<td><?=$value['婉拒案件數'] ?></td>
			<td><?=$value['核准案件數'] ?></td>
			<td><?=$value['核准並撥款案件數'] ?></td>
			<td><?=$user[$value['aauNo']] ?></td>			
		</tr>
		<?php 
				$i++;
			} 
		?>
		</tbody>
	</table>