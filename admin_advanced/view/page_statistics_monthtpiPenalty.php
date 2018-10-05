<?php 

$api = new API("real_cases");
$apiMem = new API("member");

if($_GET['searchDate'] != ""){
	$datetime = substr($_GET['searchDate'],0,8);
	$str = "BETWEEN CONCAT('".$datetime."','01') AND last_day('".$_GET['searchDate']."')";
	$str1 = "CONCAT('".$datetime."','01')";
	$day = $_GET['searchDate'];
}else{
	$str = "BETWEEN CONCAT(SUBSTRING(DATE(NOW()),1,8),'01') AND NOW() ";
	$str1 = "CONCAT(SUBSTRING(DATE(NOW()),1,8),'01')";
	$day = date("Y-m-d",time());
}
$system  = "SELECT * FROM `other_setting_advanced`";
$system_data = $apiMem->customSql($system);


$aaa = "SELECT aa.rcNo,aa.tpiActualDate,aa.tpiSupposeDate,bb.rcType FROM `tpi` aa, real_cases bb where bb.rcType = '0' and aa.rcNo = bb.rcNo and aa.tpiActualDate ".$str;

$aaData = $apiMem->customSql($aaa);
foreach($aaData as $k => $v){
	$bbb ="select sum(prActualPay) as CMC from `pay_records` where prDate ".$str." and prActualPay = '".$system_data[0]['penaltyAmount']."' and rcNo = '".$v['rcNo']."'";
	$bbData = $apiMem->customSql($bbb);
	if( $bbData != ""){
		$monthnum = getMonthNum($v['tpiSupposeDate'],$v['tpiActualDate']);
		if($monthnum >= '1'){
			$CMC_data['0']['other_CMC'] += $bbData['0']['CMC'];
		}else{
			$CMC_data['0']['CMC'] += $bbData['0']['CMC'];
		}
	}
}



$sql = "select bb.rcCaseNo,bb.rcPeriodTotal,bb.rcPeriodAmount,aa.`rcNo`,aa.`tpiPeriod`,aa.`tpiSupposeDate`,aa.`tpiActualDate`,aa.`tpiPeriodTotal`,aa.`tpiPenalty`,aa.`tpiPaidTotal`,aa.`tpiOverdueDays`,aa.`tpiIfCancelPenalty` FROM `tpi` aa, `real_cases` bb WHERE aa.`tpiSupposeDate` ".$str." and aa.`tpiPenalty` != 0 and aa.rcNo = bb.rcNo";
$data = $apiMem->customSql($sql);


$sql1 = "select sum(tpiPenalty) from `tpi` where tpiSupposeDate ".$str." and tpiActualDate ".$str." and tpiPenalty != 0 and `tpiPeriodTotal` + `tpiPenalty` = `tpiPaidTotal`";
$data1 = $apiMem->customSql($sql1);


/*$sql11 = "select rcNo as CMC from `pay_records` where prDate ".$str." and prActualPay = '".$system_data[0]['penaltyAmount']."'";
$CMC_data = $apiMem->customSql($sql11);
echo count($CMC_data)." || ";
foreach($CMC_data as $k => $v){
	if(!in_array($v['CMC'],$array)){
		echo $v['CMC']." ";
		$i++;
	}
}
echo " ".$i;*/

$sql2 = "select sum(tpiPenalty) as otherhad from `tpi` where tpiSupposeDate < ".$str1." and tpiActualDate ".$str." and tpiPenalty != 0 and `tpiPeriodTotal` + `tpiPenalty`= `tpiPaidTotal`";
$data2 = $apiMem->customSql($sql2);


$sql3 = "SELECT bb.rcCaseNo,bb.rcPeriodTotal,bb.rcPeriodAmount,count(aa.`rcNo`) as number, aa.`tpiPenalty`,aa.`rcNo`,aa.`tpiPeriodTotal`,sum(case when aa.`tpiPeriodTotal` + aa.`tpiPenalty` - aa.`tpiPaidTotal` = 0 then 1 else 0 end) as haspaid FROM `tpi` aa, real_cases bb WHERE aa.`tpiPenalty` != '' and aa.rcNo = bb.rcNo group by aa.`rcNo`";
$data3 = $apiMem->customSql($sql3);


foreach($data as $key => $value){
	$count_number += 1;
	$shouldPaid += $value['tpiPenalty'];
	//$hasPaid += ($value['tpiPeriodTotal']+$value['tpiPenalty']-$value['tpiPaidTotal'] == 0) ? $value['tpiPenalty']:'0';
}

foreach($data3  as $key => $value){
	$total += ($value['number']*$value['tpiPenalty']);
}



function getMonthNum( $date1, $date2, $tags='-' ){
$date1 = explode($tags,$date1);
$date2 = explode($tags,$date2);
return abs($date1[0] - $date2[0]) * 12 + abs($date1[1] - $date2[1]);
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

						<a>本月應收滯納金：</a><?php echo number_format($shouldPaid); ?><br>
						<a>已收滯納金：本月應收滯納金收款 </a><?php echo number_format($data1[0]['sum(tpiPenalty)']); ?><a> + 其他月份應收在本月收款 </a><?php echo number_format($data2[0]['otherhad']); ?><a> + CMC滯納金本月收入 </a><?php echo number_format($CMC_data[0]['CMC']);?> <a> + CMC滯納金其他月份收入</a><?php echo number_format($CMC_data['0']['other_CMC']); ?> <a>   = </a><?php echo number_format($data1[0]['sum(tpiPenalty)']+$data2[0]['otherhad']+$CMC_data[0]['CMC']+$CMC_data['0']['other_CMC']); ?><br>
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
								<th>期金</th>
								<th>逾期天數</th>
								<th>滯納金</th>
								<th>應繳日期</th>
								<th>已繳日期</th>
								<th>已繳金額</th>
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
								<td><?php echo $value['tpiPeriodTotal']; ?></td>
								<td><?php echo $value['tpiOverdueDays']; ?></td>
								<td><?php echo $value['tpiPenalty']; ?></td>
     							<td><?php echo $value['tpiSupposeDate']; ?></td>
								<td><?php echo $value['tpiActualDate']; ?></td>
								<td><?php echo $value['tpiPaidTotal']; ?></td>
     						</tr>	
						<?php
								
							}
     					}
     					?>
						<?php
						
     					if($data1 != null){
     						foreach($data1 as $key=>$value){								
     					?>
     						<tr>
     							<td><?php echo $value['rcCaseNo']; ?></td>
     							<td><?php echo number_format($value['rcPeriodTotal']); ?></td>
     							<td><?php echo $value['rcPeriodAmount']; ?></td>
								<td><?php echo $value['tpiPeriodTotal']; ?></td>
								<td><?php echo $value['tpiOverdueDays']; ?></td>
								<td><?php echo $value['tpiPenalty']; ?></td>
     							<td><?php echo $value['tpiSupposeDate']; ?></td>
								<td><?php echo $value['tpiActualDate']; ?></td>
								<td><?php echo $value['tpiPaidTotal']; ?></td>
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
			location.href = "?page=statistics&type=monthtpiPenalty&searchDate=" + $("#searchDate").val();
		}
	});
	
	$(".export").click(function(){
		window.open("view/print_excel_statistics_regist.php?searchDate=<?php echo $_GET["searchDate"];?>");
	});
});
</script>