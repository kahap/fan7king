<?php 

$api = new API("real_cases");
$apiMem = new API("member");
$apiTb = new API("transfer_bank");
$or = new API("orders");


$api->setWhereArray(array("rcStatus"=>3,"rcApproStatus"=>0));
$rcData = $api->getWithConditions();

$data = array();
//NoWait訂單狀態到已到貨才可開始撥款
if($rcData != null){
	foreach($rcData as $key=>$value){
		if($value["rcType"] == "0"){
			$orData = $or->getOne($value["rcRelateDataNo"]);
			if($orData[0]["orStatus"] == 9){
				$data[] = $value;
			}
		}else{
			$data[] = $value;
		}
	}
}
			
?>
<main class="mn-inner">
	<div class="row">
		<div class="col s12">
			<div class="page-title">待撥款案件列表</div>
		</div>
		<div class="col s12 m12 l12">
			<div class="card">
				<div class="card-content">
					<div style="padding-bottom:20px;">
						<a class="waves-effect waves-light btn green m-b-xs confirm-save">打勾選項特約商請款</a>
					</div>
   					<table id="example" class="display responsive-table datatable-example">
						<thead>
     						<tr>
     							<th style="text-align: center;">
     								<input id="check-all" type="checkbox" class="tableflat">
     							</th>
								<th>編號</th>
								<th>案件編號</th>
								<th>申請人姓名</th>
								<th>身分證字號</th>
								<th>訂單狀態</th>
								<th>經銷商</th>
								<th>撥款金額</th>
								<th>撥款銀行</th>
								<th>撥款狀態</th>
     						</tr>
						</thead>
     					<tbody>
     					<?php 
     					if($data != null){
     						foreach($data as $key=>$value){
     							$memData = $apiMem->getOne($value["memNo"]);
     							$tbData = $apiTb->getOne($value["tbNo"]);
								if($value["rcType"] == "0"){
									$sup = new API("supplier");
									$supData = $sup->getOne($value['supNo']);
								}
     					?>
     						<tr>
     							<td style="text-align: center;">
     								<input data-no="<?php echo $value["rcNo"]; ?>" type="checkbox" class="tableflat for-checked">
     							</td>
     							<td><?php echo $key+1; ?></td>
     							<td><a href="?page=orders_view&type=view&no=<?php echo $value["rcNo"]; ?>"><?php echo $value["rcCaseNo"]; ?></a></td>
     							<td><?php echo $memData[0]["memName"]; ?></td>
     							<td><?php echo $memData[0]["memIdNum"]; ?></td>
     							<td><?php echo $api->statusArr[$value["rcStatus"]]; ?></td>
								<td><?php echo ($value["rcType"] == "0") ? $supData[0]['supName']:'手機/機車貸款'; ?></td>
     							<td><?php echo $value["rcBankTransferAmount"]; ?></td>
     							<td><?php echo $tbData[0]["tbName"]; ?></td>
     							<td><?php echo $api->approStatusArr[$value["rcApproStatus"]]; ?></td>
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
$(function(){
	$(".confirm-save").click(function(){
		if($(".for-checked:checked").length == 0){
			alert("請先勾選要確認撥款的案件");
		}else{
			var selected = [];
			$(".for-checked:checked").each(function(){
				selected.push($(this).data("no"));
			});
			var form = {"rcNo":selected};
			var url = "ajax/appropriation/insert_new_appropriation.php";
			
			$.ajax({
				url:url,
				type:"post",
				data:form,
				datatype:"json",
				success:function(result){
					if(result.indexOf("OK") != -1){
						alert("儲存成功！");
						location.reload();
					}else{
						alert(result);
					}
				}
			});
		}
	});
});
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
	    "aoColumnDefs": [{
	        'bSortable': false,
	        'aTargets': [0]
	      } //disables sorting for column one
	    ],
	    "order": [[ 6 , "asc" ]],
	    "iDisplayLength": 100
    	
    });
    $('.dataTables_length select').addClass('browser-default');
});
</script>