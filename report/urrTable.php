<?php
//業務人員報表
	include('../model/php_model.php');
	header("Content-Type:text/html; charset=utf-8");
	header('Content-type:application/vnd.ms-excel');  //宣告網頁格式
    header('Content-Disposition: attachment; filename='.date("Y-m-d",time()).'催收總表.xls');  //設定檔案名稱*/
	$db = new WADB(SYSTEM_DBHOST, SYSTEM_DBNAME, SYSTEM_DBUSER, SYSTEM_DBPWD);	
	$sql = "SELECT a.rcNo,b.rcRelateDataNo,b.rcType,a.tpiOverdueDays,a.tpiPeriod,a.tpiSupposeDate,a.tpiPeriodTotal,b.rcCaseNo,b.rcPeriodAmount,b.rcFirstPayDate,b.tbNo,c.memName,c.memIdNum,d.uuNo,d.urrCurMValue,d.urrCurPeriod,d.urrDate FROM `tpi` a inner join (select rcNo,uuNo,urrCurMValue,urrCurPeriod,urrDate from urge_request_records order by urrNo desc) as d on a.rcNo = d.rcNo,real_cases b,member c where a.tpiOverdueDays >= '8' && a.tpiActualDate = '' && a.rcNo = b.rcNo && b.memNo = c.memNo group by a.rcNo order by a.tpiOverdueDays desc";
	$data = $db->selectRecords($sql);
	$bank = array('CMC','CMC','彰銀');
	
	$sql1 = "SELECT aauNo,aauName FROM `admin_advanced_user`";
	$data1 = $db->selectRecords($sql1);
	foreach($data1 as $key => $value){
		$user[$value['aauNo']] = $value['aauName'];
	}
?>
<style>  
td {mso-number-format: \@}  
</style>  
	<table border='1'>
		<thead>
			<th>逾期天數</th>
			<th>契約起始日</th>
			<th>本月應繳日期</th>
			<th>未繳款日期</th>
			<th>最後付款日期</th>
			<th>上期逾期天數</th>
			<th>案件編號</th>
			<th>客戶身分證</th>
			<th>客戶</th>
			<th>期金</th>
			<th>期數</th>
			<th>本次應繳期數</th>
			<th>應繳金額</th>
			<th>已繳金額</th>
			<th>剩餘金額</th>
			<th>派件前剩餘金額</th>
			<th>分案日期</th>
			<th>派件逾期天數</th>
			<th>已派件天數</th>
			<th>催收人員</th>
			<th>銀行</th>
			<th>購買商品</th>
		</thead>
		<tbody>
		<?php foreach($data as $key => $value){
				$sql2 = "SELECT * FROM `tpi` where rcNo = '".$value['rcNo']."' && tpiActualDate = '' && tpiPenalty > 0 order by tpiNo desc limit 1";
				$data2 = $db->selectRecords($sql2);
				
				$sql3 = "SELECT * FROM `tpi` where rcNo = '".$value['rcNo']."' && tpiActualDate != '' order by tpiNo desc limit 1";
				$data3 = $db->selectRecords($sql3);
				
				$sql4 = "SELECT * FROM `tpi` where rcNo = '".$value['rcNo']."' && tpiActualDate != '' && tpiPenalty > 0 order by tpiNo desc limit 1";
				$data4 = $db->selectRecords($sql4);
				
				$sql4 = "SELECT * FROM `tpi` where rcNo = '".$value['rcNo']."' && tpiActualDate != '' && tpiPenalty > 0 order by tpiNo desc limit 1";
				$data4 = $db->selectRecords($sql4);
				if($value['rcType'] == '0'){
					$sql5 = "SELECT c.proName FROM `orders` a,product_manage b,product c where a.orNo = '".$value['rcRelateDataNo']."' && a.pmNo = b.pmNo && b.proNo = c.proNo";
					$data5 = $db->selectRecords($sql5);
					$data5['proName'] = $data5['0']['proName'];
					
				}else{
					$data5['proName'] = ($value['rcType'] == '1') ? '手機貸款':'機車貸款';
				}
				
		?>
		<tr>
			<td><?=$value['tpiOverdueDays'];?></td>
			<td><?=$value['rcFirstPayDate'];?></td>
			<td><?=$data2['0']['tpiSupposeDate'];?></td>
			<td><?=$value['tpiSupposeDate'];?></td>
			<td><?=$data3['0']['tpiActualDate']?></td>
			<td><?=$data4['0']['tpiOverdueDays']?></td>
			<td><?=$value['rcCaseNo'];?></td>
			<td><?=$value['memIdNum'];?></td>
			<td><?=$value['memName'];?></td>
			<td><?=$value['tpiPeriodTotal'];?></td>
			<td><?=$value['rcPeriodAmount'];?></td>
			<td><?=$value['tpiPeriod'];?></td>
			<td><?=($value['rcPeriodAmount']*$value['tpiPeriodTotal']);?></td>
			<td><?=(($value['tpiPeriod']-1)*$value['tpiPeriodTotal']);?></td>
			<td><?=($value['rcPeriodAmount']*$value['tpiPeriodTotal'])-(($value['tpiPeriod']-1)*$value['tpiPeriodTotal']);?></td>
			<td><?=($value['rcPeriodAmount']*$value['tpiPeriodTotal'])-(($value['tpiPeriod']-1)*$value['tpiPeriodTotal']);?></td>
			<td><?=date('Y-m-d',strtotime($value['urrDate']));?></td>
			<td><?=datediff($value['urrDate'],date('Ymd'));?></td>
			<td><?=datediff($value['urrDate'],date('Ymd'));?></td>
			<td><?=$user[$value['uuNo']]?></td>
			<td><?=$bank[$value['tbNo']];?></td>
			<td><?=$data5['proName'];?></td>
							
		</tr>
		<?php }
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