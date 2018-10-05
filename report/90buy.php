<?php
//業務人員報表
	include('../model/php_model.php');
	header("Content-Type:text/html; charset=utf-8");
	header('Content-type:application/vnd.ms-excel');  //宣告網頁格式
    header('Content-Disposition: attachment; filename='.$_GET['strday'].'區間買回明細.xls');  //設定檔案名稱*/
	$db = new WADB(SYSTEM_DBHOST, SYSTEM_DBNAME, SYSTEM_DBUSER, SYSTEM_DBPWD);	
	$sql = "SELECT rcNo,tpiOverdueDays,tpiSupposeDate,tpiPeriod,tpiPeriodTotal FROM `tpi` where datediff('".$_GET['strday']."',tpiSupposeDate) >= 90 && tpiActualDate = '' group by rcNo";
	$data = $db->selectRecords($sql);
	$bank = array('CMC','CMC','彰銀');
	$finishStatusArr = array(0=>"審核中",1=>"提前結清",2=>"到期結清",3=>"一般結清",4=>"代償",5=>"未結清");
	
	$urrsql = "SELECT aauName,aauNo FROM `admin_advanced_user`";
	$urr = $db->selectRecords($urrsql);
	foreach($urr as $key => $value){
		$urrUser[$value['aauNo']] = $value['aauName'];
	}
?>
<style>  
td {mso-number-format: \@}  
</style>  
	<table border='1'>
		<thead>
			<th>應繳款日期</th>
			<th>案件編號</th>
			<th>案件狀態</th>
			<th>逾期天數</th>
			<th>第一次繳款日</th>
			<th>身分證字號</th>
			<th>申請人</th>
			<th>期付款</th>
			<th>期數</th>
			<th>申請總金額</th>
			<th>區間</th>
			<th>催收人員</th>
			<th>派件時間</th>
			<th>銀行</th>
			<th>買回金額</th>
		</thead>
		<tbody>
		<?php foreach($data as $key => $value){
							$dayNum = datediff($value['tpiSupposeDate'],$_GET['endday']);
							if($dayNum <= "120"){
							$sql1 = "SELECT urrCurMValue,uuNo,urrDate FROM `urge_request_records` where rcNo = '".$value['rcNo']."' && urrCurPeriod = '".$value['tpiPeriod']."'";
							$data1 = $db->selectRecords($sql1);
							
							$sql2 = "select memNo,rcCaseNo,rcFirstPayDate,rcPeriodAmount,rcPeriodTotal,tbNo,rcFinishStatus from real_cases where rcNo = '".$value['rcNo']."'";
							$data2 = $db->selectRecords($sql2);
							
							$sql3 = "select memIdNum,memName from member where memNo = '".$data2['0']['memNo']."'";
							$data3 = $db->selectRecords($sql3);
						?>
						<tr>
							<td class="num"><?=$value['tpiSupposeDate'] ?></td>
							<td><?=$data2['0']['rcCaseNo'] ?></td>
							<td><?=$finishStatusArr[$data2['0']['rcFinishStatus']] ?></td>
							<td><?=$value['tpiOverdueDays'] ?></td>
							<td><?=$data2['0']['rcFirstPayDate'] ?></td>
							<td><?=$data3['0']['memIdNum'] ?></td>
							<td><?=$data3['0']['memName'] ?></td>
							<td><?=$value['tpiPeriodTotal'] ?></td>
							<td><?=$data2['0']['rcPeriodAmount'] ?></td>
							<td><?=$data2['0']['rcPeriodTotal'] ?></td>
							
							<td><?=$data1['0']['urrCurMValue'] ?></td>
							<td><?=$urrUser[$data1['0']['uuNo']] ?></td>
							<td><?=$data1['0']['urrDate'] ?></td>
							
							<td><?=$bank[$data2['0']['tbNo']] ?></td>
							<td><?=($data2['0']['rcPeriodAmount']-$value['tpiPeriod']+1)*$value['tpiPeriodTotal'] ?></td>
							
						</tr>
						<?php }
						}
						?>
		</tbody>
	</table>
<?php
	function datediff($star,$end){
		$start = strtotime($star);
		$end = strtotime($end);
		$timeDiff = $end - $start;
		return floor($timeDiff / (60 * 60 * 24));
	}
?>	