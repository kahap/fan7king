<?php 

$api = new API("real_cases");
$apiMem = new API("member");

if($_GET['searchDate'] != ""){
	$datetime = date('Y/m/d',strtotime($_GET['searchDate']));
	$str = "DATE_FORMAT('".$datetime."','%Y-%m')";
}else{
	$str = "DATE_FORMAT(NOW(),'%Y-%m')";
}
$sql = "SELECT bb.rcCaseNo,bb.rcPeriodTotal,bb.rcPeriodAmount,count(aa.`rcNo`) as number, aa.`tpiPenalty`,aa.`rcNo`,aa.`tpiPeriodTotal`,sum(case when aa.`tpiPeriodTotal` + aa.`tpiPenalty` - aa.`tpiPaidTotal` = 0 then 1 else 0 end) as haspaid FROM `tpi` aa, real_cases bb WHERE aa.`tpiPenalty` != '' and aa.rcNo = bb.rcNo group by aa.`rcNo`";
$data = $apiMem->customSql($sql);
//print_r($data);
$sql1 = "SELECT count(memName) AS 人數, DATE_FORMAT(memRegistDate,'%Y-%m') as aa FROM member where DATE_FORMAT(memRegistDate,'%Y-%m') = ".$str." group by aa";
$total = $apiMem->customSql($sql1);

foreach($data as $key => $value){
	$count_number += $value['number'];
	$shouldPaid += ($value['number']*$value['tpiPenalty']);
	$hasPaid += ($value['haspaid']*$value['tpiPenalty']);
}
?>
<main class="mn-inner">
	<div class="row">
		<div class="col s12">
			<div class="page-title"><?php echo $pageTitle; ?></div>
		</div>
		<div class="col s12 m12 l12">
			<div class="card">
				<div class="card-content">
					<span class="card-title" style="font-size: 18px;">
						<a>滯納金總筆數：</a><?php echo $count_number; ?><br>
						<a>應收滯納金：</a><?php echo number_format($shouldPaid); ?><br>
						<a>已收滯納金：</a><?php echo number_format($hasPaid); ?><br>
					</span>
   					<!--<div style="padding-bottom:20px;">
						<a class="waves-effect waves-light btn green m-b-xs export">匯出Excel</a>
					</div>-->
					<table id="example" class="display responsive-table datatable-example">
						<thead>
     						<tr>
								<th>案件編號</th>
								<th>分期總金額</th>
								<th>期數</th>
								<th>滯納金</th>
								<th>逾期次數</th>
								<th>已繳滯納金次數</th>
								<th>累積滯納金金額</th>
     						</tr>
						</thead>
     					<tbody>
     					<?php
						
     					if($data != null){
     						foreach($data as $key=>$value){								
     					?>
     						<tr>
     							<td><?php echo $value['rcCaseNo']; ?></td>
     							<td><?php echo number_format($value['rcPeriodTotal']); ?></td>
     							<td><?php echo $value['rcPeriodAmount']; ?></td>
								<td><?php echo $value['tpiPenalty']; ?></td>
     							<td><?php echo $value['number']; ?></td>
								<td><?php echo $value['haspaid']; ?></td>
								<td><?php echo ($value['number']-$value['haspaid'])*$value['tpiPenalty']; ?></td>
     						</tr>	
						<?php
								
							}
     					}
     					?>
     					</tbody>
					</table>
				</div>
			</div>
		</div>
	</div>
</main>

<script src="assets/plugins/datatables/js/jquery.dataTables.min.js"></script>
<script src="assets/js/pages/form_elements.js"></script>

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
        "order": [[ 1, "desc" ]],
		"iDisplayLength": 100
    });
    $('.dataTables_length select').addClass('browser-default');
});


</script>
<script>
$(function(){
	$(".confirm-save").click(function(){
		if($("#searchDate").val() == ''){
			alert('必須選擇起始日期');
		}else{
			location.href = "?page=statistics&type=regist&searchDate=" + $("#searchDate").val();
		}
	});
	
	$(".export").click(function(){
		window.open("view/print_excel_statistics_regist.php?searchDate=<?php echo $_GET["searchDate"];?>");
	});
});
</script>