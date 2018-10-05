<?php 

$api = new API("real_cases");
$apiMem = new API("member");

if($_GET['searchDate'] != ""){
	$datetime = date('Y/m/d',strtotime($_GET['searchDate']));
	$str = "DATE_FORMAT('".$datetime."','%Y-%m')";
}else{
	$str = "DATE_FORMAT(NOW(),'%Y-%m')";
}
$sql = "SELECT bb.rcCaseNo,bb.rcPeriodTotal,bb.rcPeriodAmount,bb.tbNo,bb.rcBankTransferAmount,count(aa.`rcNo`) as number,aa.`rcNo`,sum(`tpiPaidTotal`) as total FROM `tpi` aa, real_cases bb WHERE aa.`tpiActualDate` = '' and aa.rcNo = bb.rcNo group by aa.`rcNo`";
$data = $apiMem->customSql($sql);

foreach($data as $key => $value){
	if($value['tbNo'] == '1'){
		$tbNo1 += $value['rcBankTransferAmount']*(1-(($value['rcPeriodAmount']-$value['number'])/$value['rcPeriodAmount']));
	}else{
		$tbNo2 += $value['rcBankTransferAmount']*(1-(($value['rcPeriodAmount']-$value['number'])/$value['rcPeriodAmount']));
	}
	$count_number += $value['rcBankTransferAmount']*(1-(($value['rcPeriodAmount']-$value['number'])/$value['rcPeriodAmount']));
	$count += 1;
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
						<a>剩餘總共債權餘額（本金）：</a><?php echo number_format($count_number); ?><br>
						<a>CMC剩餘債權餘額：</a><?php echo number_format($tbNo1); ?><br>
						<a>彰銀剩餘債權餘額：</a><?php echo number_format($tbNo2); ?><br>
						<a>分期中總筆數：</a><?php echo number_format($count); ?><br>
					</span>
   					<!--<div style="padding-bottom:20px;">
						<a class="waves-effect waves-light btn green m-b-xs export">匯出Excel</a>
					</div>-->
					<table id="example" class="display responsive-table datatable-example">
						<thead>
     						<tr>
								<th>案件編號</th>
								<th>分期總金額</th>
								<th>撥款金額</th>
								<th>期數</th>
								<th>已繳期數</th>
								<th>剩餘債權金額</th>
								<th>撥款銀行</th>
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
								<td><?php echo number_format($value['rcBankTransferAmount']); ?></td>
     							<td><?php echo $value['rcPeriodAmount']; ?></td>
								<td><?php echo ($value['rcPeriodAmount']-$value['number']); ?></td>
								<td><?php echo number_format($value['rcBankTransferAmount']*(1-(($value['rcPeriodAmount']-$value['number'])/$value['rcPeriodAmount'])));?></td>
								<td><?php echo ($value['tbNo'] == '1') ? "CMC":"彰銀"; ?></td>
								
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