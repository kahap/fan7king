<?php 

$api = new API("real_cases");
$apiMem = new API("member");
$mco = new API("motorbike_cellphone_orders");
$lg = new API("loyal_guest");

$lgData = $lg->getAll();
$lgArr = array();
foreach($lgData as $key=>$value){
	$lgArr[] = $value["lgIdNum"];
}

$pageTitle = "進件作業 - 未進件案件列表(機車/手機貸款)";
$cardTitle = "未進件案件";
$data = $api->customSql("select * from real_cases where rcStatus = 1 and (rcType = 1 or rcType = 2)");

?>
<main class="mn-inner">
	<div class="row">
		<div class="col s12">
			<div class="page-title"><?php echo $pageTitle; ?></div>
		</div>
		<div class="col s12 m12 l12">
			<div class="card">
				<div class="card-content">
					<span class="card-title"><?php echo $cardTitle; ?></span>
   					<table id="example" class="display responsive-table datatable-example">
						<thead>
     						<tr>
								<th>編號</th>
								<th>案件編號</th>
								<th>訂單編號</th>
								<th>申請人姓名</th>
								<th>身分別</th>
								<th>身分證字號</th>
								<th>老客戶</th>
								<th>案件類別</th>
								<th>下單日期</th>
     						</tr>
						</thead>
     					<tbody>
     					<?php
     					if($data != null){
     						foreach($data as $key=>$value){
     							$memData = $apiMem->getOne($value["memNo"]);
								
								//未滿20歲註記
								$mark = (AgeOver20($memData[0]["memBday"]) < '20') ? '*':'';
								if($value["rcType"] == "0"){
									$orData = $or->getOne($value["rcRelateDataNo"]);
									$caseNo = $orData[0]["orCaseNo"];
									$pmData = $pm->getOne($orData[0]["pmNo"]);
									$proData = $pro->getOne($pmData[0]["proNo"]);
								}else if($value["rcType"] == "1" || $value["rcType"] == "2"){
									$mcoData = $mco->getOne($value["rcRelateDataNo"]);
									$caseNo = $mcoData[0]["mcoCaseNo"];
								}
     					?>
     						<tr>
     							<td><?php echo $key+1; ?></td>
     							<td><?php echo $value["rcCaseNo"]; ?></td>
     							<td><a href="?page=case&type=edit&no=<?php echo $value["rcNo"]; ?>"><?php echo $caseNo ?></a></td>
								<td><?php echo $memData[0]["memName"]; ?><font style="color:red"><?php echo $mark;?></font></td>
								<td><?php echo $api->memClassArr[$memData[0]["memClass"]]; ?></td>
     							<td><?php echo $memData[0]["memIdNum"]; ?></td>
     							<td><?php echo in_array($memData[0]["memIdNum"],$lgArr) ? "是" : "否"; ?></td>
     							<td><?php echo $api->category[$value["rcType"]]; ?></td>
     							<td><?php echo $value["rcDate"]; ?></td>
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