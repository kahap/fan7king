<?php 

$api = new API("real_cases");
$apiMem = new API("member");

if($_GET['searchDate'] != ""){
	$datetime = date('Y/m/d',strtotime($_GET['searchDate']));
	$str = "DATE_FORMAT('".$datetime."','%Y-%m')";
}else{
	$str = "DATE_FORMAT(NOW(),'%Y-%m')";
}
$sql = "SELECT
DATE_FORMAT(申請日期,'%Y-%m-%d %W') AS 申請日期,
身分別,

註冊方式,
COUNT(姓名) AS 人數
FROM
(SELECT
CASE memClass WHEN 0 THEN '學生' ELSE '非學生' END AS 身分別,
CASE WHEN memDeviceToken IS NULL THEN '網頁註冊' ELSE '手機註冊' END AS 註冊方式,
memName AS 姓名,
CASE memGender WHEN 1 THEN '男' ELSE '女' END AS 性別,
DATE_FORMAT(memRegistDate,'%Y-%m-%d') AS 申請日期
FROM
member) AS mem
WHERE
DATE_FORMAT(申請日期,'%Y-%m') = ".$str."
GROUP BY
申請日期,身分別,註冊方式
ORDER BY
申請日期 DESC,身分別";

$data = $apiMem->customSql($sql);
//print_r($data);
$sql1 = "SELECT count(memName) AS 人數, DATE_FORMAT(memRegistDate,'%Y-%m') as aa FROM member where DATE_FORMAT(memRegistDate,'%Y-%m') = ".$str." group by aa";
$total = $apiMem->customSql($sql1);

$sql2 = "select count(memNo) as total from member";
$member_total = $apiMem->customSql($sql2);
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
							<label>選擇查詢月份</label>
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
					<span class="card-title" style="font-size: 18px;">系統總註冊人數：<?php echo $member_total['0']['total'];?> /人</span>
					<span class="card-title" style="font-size: 18px;">該月份總註冊人數：<?php echo $total['0']['人數'];?> /人</span>
   					<div style="padding-bottom:20px;">
						<a class="waves-effect waves-light btn green m-b-xs export">匯出Excel</a>
					</div>
					<table id="example" class="display responsive-table datatable-example">
						<thead>
     						<tr>
								<th>申請日期</th>
								<th>身分別</th>
								<th>註冊方式</th>
								<th>人數</th>
     						</tr>
						</thead>
     					<tbody>
     					<?php
						
     					if($data != null){
     						foreach($data as $key=>$value){								
     					?>
     						<tr>
     							<td><?php echo $value['申請日期']; ?></td>
     							<td><?php echo $value['身分別']; ?></td>
     							<td><?php echo $value['註冊方式']; ?></td>
     							<td><?php echo $value['人數']; ?></td>
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