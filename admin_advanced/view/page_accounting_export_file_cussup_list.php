<?php 

$rc = new API("real_cases");
$mem = new API("member");

$searchDate = $_GET['searchDate'];
$searchDateEnd = $searchDateEnd == "" ? date("Y-m-d",time()) : $_GET['searchDateEnd'];
$rcData = $rc->getFinishedApproForExport($searchDate,$searchDateEnd,$_GET['selectType']);

?>
<main class="mn-inner">
	<div class="row">
		<div class="col s12">
			<div class="page-title">案件列表</div>
		</div>
		<div class="col s12 m12 l12">
			<div class="card">
				<div class="card-content">
					<div style="padding-bottom:20px;">
						<a class="waves-effect waves-light btn green m-b-xs confirm-save">打勾選項匯出Excel</a>
					</div>
   					<table id="example" class="display responsive-table datatable-example">
						<thead>
     						<tr>
     							<th style="text-align: center;">
     								<input id="select_all" type="checkbox" class="" style="position: initial;
    left: -9999px;    opacity: inherit;">
     							</th>
								<th>進銷單號碼</th>
								<th>發票號碼</th>
								<th>案件編號</th>
								<th>姓名</th>
								<th>身分證字號</th>
								<th>分期總金額</th>
								<th>案件類型</th>
								<th>買帳日期</th>
     						</tr>
						</thead>
     					<tbody>
     					<?php 
     					if($rcData != null){
     						foreach($rcData as $key=>$value){
     							$memData = $mem->getOne($value["memNo"]);
     					?>
     						<tr>
     							<td style="text-align: center;">
     								<input data-no="<?php echo $value["rcNo"]; ?>" name="Checkbox[]" type="checkbox" class=" for-checked" style="position: initial;
    left: -9999px;    opacity: inherit;">
     							</td>
								<td><?php echo $value["rcInvoiceNumber"]; ?></td>
								<td><?php echo $value["receiptNumber"]; ?></td>
     							<td><a target="_blank" href="?page=orders_view&type=view&no=<?php echo $value["rcNo"]; ?>"><?php echo $value["rcCaseNo"]; ?></a></td>
     							<td><?php echo $memData[0]["memName"]; ?></td>
     							<td><?php echo $memData[0]["memIdNum"]; ?></td>
     							<td><?php echo $value["rcPeriodTotal"]; ?></td>
     							<td><?php echo $rc->caseTypeArr[$value["rcType"]]; ?></td>
     							<td><?php echo $value["rcApproDate"]; ?></td>
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
			alert("請先勾選要匯出Excel的案件");
		}else{
			var selected = [];
			$(".for-checked:checked").each(function(){
				selected.push($(this).data("no"));
			});
			
			//匯出EXCEL
			$("body").append('<form id="excel" method="post" action="view/print_excel_accounting_cussup_export.php"></form>');
			for(var n=0; n<selected.length; n++){
				$("#excel").append('<input type="hidden" name="rcNo[]" value="'+selected[n]+'">');
			}
			$("#excel").submit();
			selected = [];
		}
	});
	
	$('#select_all').change(function() {
		if($("#select_all").prop("checked")){//如果全選按鈕有被選擇的話（被選擇是true）
			$("input[name='Checkbox[]']").prop("checked",true);//把所有的核取方框的property都變成勾選
		}else{
			$("input[name='Checkbox[]']").prop("checked",false);//把所有的核取方框的property都取消勾選
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
	    "order": [[ 0 , "asc" ]],
	    "iDisplayLength": 5000
    	
    });
    $('.dataTables_length select').addClass('browser-default');
});
</script>