<?php 

$search = new Search();
$rcData = $search->searchData($_POST);

?>
<main class="mn-inner">
	<div class="row">
		<div class="col s12">
			<div class="page-title">註冊人數查詢</div>
		</div>
		<div class="col s12 m12 l12">
			<div class="card">
				<div class="card-content">
   					<table id="example" class="display responsive-table datatable-example">
						<thead>
     						<tr>
								<th>案件編號</th>
								<th>姓名</th>
								<th>身分證字號</th>
								<th>分期數</th>
								<th>分期總金額</th>
								<th>申請日期</th>
     						</tr>
						</thead>
     					<tbody>
     					<?php 
     					if($rcData != null){
     						foreach($rcData as $key=>$value){
     					?>
     						<tr>
     							<td><a target="_blank" href="?page=accounting&type=edit_records&action=edit&no=<?php echo $value["rcNo"]; ?>"><?php echo $value["rcCaseNo"]; ?></a></td>
     							<td><?php echo $value["memName"]; ?></td>
     							<td><?php echo $value["memIdNum"]; ?></td>
     							<td><?php echo $value["rcPeriodAmount"]; ?></td>
     							<td><?php echo $value["rcPeriodTotal"]; ?></td>
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
$(function(){
	
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
	    "iDisplayLength": 50
    	
    });
    $('.dataTables_length select').addClass('browser-default');
});
</script>