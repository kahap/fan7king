<?php
header('Content-Type: text/html; charset=utf8');
require_once('../../model/require_ajax.php');


session_start();

$start = date("Ymd",strtotime($_POST['startday']))."0001";
$end = date("Ymd",strtotime($_POST['endday']))."9999";

$_POST['endday'] = $_POST['endday']." 23:59:59";

$api = new API("real_cases");
$sql = "SELECT e.supName,e.supNo,count(case a.rcStatus when '7' then '1' end) as '取消案件數',count(case a.rcStatus when '4' then '1' end) as '婉拒案件數',count(case a.rcStatus when '3' then '1' end) as '核准案件數',count(case b.orStatus when '10' then '1' end) as '核准並撥款案件數',count(case a.rcStatus when '701' then '1' end) as '自行撤件案件數',count(case when a.rcCaseNo !='' then '1' end) as '進件案件數',e.aauNo from real_cases a,orders b,product_manage c,product d,supplier e where a.rcDate BETWEEN '".$_POST['startday']."' AND '".$_POST['endday']."' and a.`rcType` = '0' and a.rcRelateDataNo = b.orNo and b.pmNo = c.pmNo and c.proNo = d.proNo and b.supNo = e.supNo and e.aauNo != '' group by e.supNo";
$data = $api->customSql($sql);

	//CONCAT(SUBSTRING(DATE(NOW()),1,8),'01')
	usort($data, function($a, $b) {
		return ($a['進件案件數'] > $b['進件案件數'])? -1 : 1;
	});
	
	$sql1 = "SELECT aauNo,aauName FROM `admin_advanced_user`";
	$userList = $api->customSql($sql1);
	foreach($userList as $key => $value){
		$user[$value['aauNo']] = $value['aauName'];
	}
	
	$sql3 = "SELECT b.supNo,b.aauNo,count(a.rcCaseNo) as '撥款件數',sum(a.rcBankTransferAmount) as '撥款總金額',AVG(rcPeriodTotal) as '平均單筆金額',AVG(rcPeriodAmount) as '平均期數' FROM `real_cases` a,supplier b where a.supNo = b.supNo && a.rcPredictGetDate  BETWEEN '".$_POST['startday']."' and '".$_POST['endday']."' && a.rcStatus = '3' && a.rcApproStatus = '4' && b.aauNo != '' group by b.aauNo";
	$MoneyData = $api->customSql($sql3);
	foreach($MoneyData as $key => $value){
		$supData[$value['supNo']] = $value['撥款總金額'];
		$supCount[$value['supNo']] = $value['撥款件數'];
	}
	
	$sql4 = "SELECT b.supNo,b.aauNo,count(a.rcCaseNo) as '撥款件數',sum(a.rcBankTransferAmount) as '撥款總金額',AVG(rcPeriodTotal) as '平均單筆金額',AVG(rcPeriodAmount) as '平均期數' FROM `real_cases` a,supplier b where a.supNo = b.supNo && a.rcPredictGetDate  BETWEEN '".$_POST['startday']."' and '".$_POST['endday']."' && a.rcStatus = '3' && a.rcApproStatus = '4' && b.aauNo != '' group by b.supNo";
	$i = '1';
?>

			<div class="card">
				<div class="card-content">
   					<table id="example" class="display responsive-table datatable-example">
						<thead>
							<th>排名</th>
							<th>業務員</th>
							<th>供應商名稱</th>
							<th>進件案件數</th>
							<th>自行撤件案件數</th>
							<th>取消案件數</th>
							<th>婉拒案件數</th>
							<th>核准案件數</th>
							<th>核准率</th>
							<th>核准並撥款案件數</th>
							<th>撥款金額</th>
						</thead>
     					<tbody>
						
     					<?php 
     					if($data != null){
     						foreach($data as $key=>$value){	
     					?>
     						<tr>
								<td><?=$i?></td>
								<td><?=$user[$value['aauNo']] ?></td>	
								<td class="num"><?=$value['supName'] ?></td>
								<td><?=$value['進件案件數'] ?></td>
								<td><?=$value['自行撤件案件數'] ?></td>
								<td><?=$value['取消案件數'] ?></td>
								<td><?=$value['婉拒案件數'] ?></td>
								<td><?=$value['核准案件數'] ?></td>
								<td><?=(round($value['核准案件數']/$value['進件案件數']*100,2)."%"); ?></td>
								<td><?=$supCount[$value['supNo']]; ?></td>
								<td><?=number_format($supData[$value['supNo']]); ?></td>
								
							</tr>
							
     					<?php
							$i++;
     						}
     					}
     					?>
     					</tbody>
					</table>
				</div>
			</div>
<script src="assets/plugins/datatables/js/jquery.dataTables.min.js"></script>
<script>
	$(document).ready(function() {
		$('#example').DataTable({
			language: {
				searchPlaceholder: '尋找關鍵字',
				sInfo: "從 _START_ 到 _END_ ，共 _TOTAL_ 筆",
				sSearch: '',
				sLengthMenu: '顯示數 _MENU_',
				sLength: 'dataTables_length',
				oPaginate: {
					sFirst: '<i class="material-icons">chevron_left</i>',
					sPrevious: '<i class="material-icons">chevron_left</i>',
					sNext: '<i class="material-icons">chevron_right</i>',
					sLast: '<i class="material-icons">chevron_right</i>' 
				}
			},
			"iDisplayLength": 300
		});
		$('.dataTables_length select').addClass('browser-default');
	});
</script>