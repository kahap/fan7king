<?php
//業務人員報表
	include('../model/php_model.php');
	header("Content-Type:text/html; charset=utf-8");
	header('Content-type:application/vnd.ms-excel');  //宣告網頁格式
    header('Content-Disposition: attachment; filename='.date('Y-m-d',time()).'本息表最後統計案件.xls');  //設定檔案名稱*/
	$member = new Member();
	$db = new WADB(SYSTEM_DBHOST, SYSTEM_DBNAME, SYSTEM_DBUSER, SYSTEM_DBPWD);
	//$sql = "SELECT a.pmSupPrice,a.pmPeriodAmnt,a.pmUpDate,b.proName,c.supName FROM `product_manage` a,`product` b, `supplier` c where a.pmUpDate BETWEEN '2018-01-01' AND now() and a.pmStatus = '1' and a.pmMainSup = '1' and a.proNo = b.proNo and a.supNo = c.supNo";
	//date("d",time())
	$sql = "SELECT a.rcNo,b.rcCaseNo,b.rcType,max(a.tpiPeriod) as lastperiod,max(a.tpiSupposeDate) as lastdate,a.tpiPeriodTotal FROM `tpi` a,`real_cases` b where a.tpiActualDate = '' && a.rcNo = b.rcNo group by a.rcNo";
	$data = $db->selectRecords($sql);
	$type = array("3C案件","機車案件","手機案件");
?>
<style>  
td {mso-number-format: \@}  
</style>  
	<table border='1'>
		<thead>
			<th>案件編號</th>
			<th>案件種類</th>
			<th>期數</th>
			<th>最後繳款日期</th>
			<th>期金</th>
		</thead>
		<tbody>
		<?php foreach($data as $key => $value){ ?>
		<tr>
			<td class="num"><?=$value['rcCaseNo'] ?></td>
			<td><? echo $type[$value['rcType']]; ?></td>
			<td><?=$value['lastperiod'] ?></td>
			<td><?=$value['lastdate'] ?></td>
			<td><?=$value['tpiPeriodTotal'] ?></td>
		</tr>
		<?php } ?>
		</tbody>
	</table>