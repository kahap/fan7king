<?php 

$api = new API("real_cases");
$apiMem = new API("member");

if($_GET['searchDate'] != ""){
	$datetime = substr($_GET['searchDate'],0,8);
	$str = "BETWEEN CONCAT('".$datetime."','01') AND last_day('".$_GET['searchDate']."')";
	$day = $_GET['searchDate'];
}else{
	$str = "BETWEEN CONCAT(SUBSTRING(DATE(NOW()),1,8),'01') AND NOW() ";
	$day = date("Y-m-d",time());
}
$sql = "SELECT
rcCaseNo AS 案件編號,
rcPeriodAmount AS 期數,
rcBankTransferAmount AS 撥款金額,
CASE rcType WHEN 0 THEN rcPeriodTotal ELSE rcPeriodAmount * t.tpiPeriodTotal END AS 應繳金額,
rcPredictGetDate AS 撥款日期,
CASE WHEN tbNo = 1 THEN 'CMC' ELSE '彰銀' END AS 撥款銀行,
CASE rcType WHEN 0 THEN '3C' WHEN 1 THEN '手機' WHEN 2 THEN '機車' END AS 產品別
FROM
real_cases
LEFT JOIN (SELECT rcNo,tpiPeriodTotal FROM tpi GROUP BY rcNo) AS t ON real_cases.rcNo = t.rcNo 
WHERE
rcStatus = '3' AND 
rcPredictGetDate ".$str." AND
t.tpiPeriodTotal != '' and
rcApproStatus = '4'
ORDER BY
rcPredictGetDate,rcCaseNo";
$data = $apiMem->customSql($sql);
//print_r($data); 

$sql1 = "SELECT
sum(rcBankTransferAmount) AS '撥款金額',tbNo,rcType
FROM
real_cases
WHERE
rcStatus = '3' AND 
rcApproStatus = '4' and
rcPredictGetDate ".$str."  
group by tbNo,rcType";
$total = $apiMem->customSql($sql1);

$sql2 = "SELECT
SUM(rcBankTransferAmount) AS 今日撥款金額
FROM
real_cases
WHERE
rcStatus = '3' AND
rcApproStatus = '4' and
TO_DAYS(rcPredictGetDate) = TO_DAYS('".$day."')";
$day_total = $apiMem->customSql($sql2);

?>
<main class="mn-inner">
	<div class="row">
		<div class="col s12">
			<div class="page-title"><?php echo $pageTitle; ?></div>
		</div>
		<div class="col s12 m12 l12">
			<div class="card">
				<div class="card-content">
					<div class="row">
						<div class="input-field col s4">
							<input type="text" class="datepicker" id="searchDate" value="<?php echo ($_GET['searchDate'] != "") ? $_GET['searchDate']:""; ?>">
							<label>選擇查詢日期</label>
						</div>
					</div>
					<div style="padding-bottom:20px;">
						<a class="waves-effect waves-light btn green m-b-xs confirm-save">查詢</a>
					</div>
				</div>
			</div>
		</div>
		<div class="col s12 m12 l12">
			<div class="card">
				<div class="card-content">
   					<span class="card-title" style="font-size: 18px;">
						<a>本月撥款清單：總金額-NT <? echo number_format($total[0]['撥款金額']+$total[1]['撥款金額']+$total[2]['撥款金額']+$total[3]['撥款金額']+$total[4]['撥款金額']) ?> /元</a><br>
						<?php 
							foreach($total as $key => $value){
								if($value['撥款金額'] > '36000'){
									if($key == '4'){
										echo ($value['tbNo'] == '1' ? "CMC":"彰銀")." ".$apiMem->caseTypeArr[$value['rcType']]." 總金額：".number_format($value['撥款金額']+$total[0]['撥款金額'])."/元<br>";
									}elseif($key == '0'){
										echo ($value['tbNo'] == '1' ? "CMC":"彰銀")." ".$apiMem->caseTypeArr[$value['rcType']]." 總金額：".number_format($value['撥款金額'])."/元<br>";
									}else{
										echo ($value['tbNo'] == '1' ? "CMC":"彰銀")." ".$apiMem->caseTypeArr[$value['rcType']]." 總金額：".number_format($value['撥款金額'])."/元<br>";
									}
								}
							}
						
						?>
						<a>本日<?php echo ($_GET['searchDate'] != "") ? $_GET['searchDate']:date("Y-m-d",time()); ?>撥款金額：</a><?php echo number_format($day_total['0']['今日撥款金額'])." /元"; ?><br>
					</span>
					<div style="padding-bottom:20px;">
						<a class="waves-effect waves-light btn green m-b-xs export">匯出Excel</a>
					</div>
					<table id="example" class="display responsive-table datatable-example">
						<thead>
     						<tr>
								<th>案件編號</th>
								<th>期數</th>
								<th>撥款金額</th>
								<th>應繳金額</th>
								<th>撥款日期</th>
								<th>撥款銀行</th>
								<th>產品別</th>
     						</tr>
						</thead>
     					<tbody>
     					<?php
						
     					if($data != null){
     						foreach($data as $key=>$value){								
     					?>
     						<tr>
     							<td><?php echo $value['案件編號']; ?></td>
     							<td><?php echo $value['期數']; ?></td>
     							<td><?php echo $value['撥款金額']; ?></td>
     							<td><?php echo $value['應繳金額']; ?></td>
								<td><?php echo $value['撥款日期']; ?></td>
								<td><?php echo $value['撥款銀行']; ?></td>
								<td><?php echo $value['產品別']; ?></td>
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
			location.href = "?page=statistics&type=pay&searchDate=" + $("#searchDate").val();
		}
	});
	
	$(".export").click(function(){
		window.open("view/print_excel_statistics_pay.php?searchDate=<?php echo $_GET["searchDate"];?>");
	});
});
</script>