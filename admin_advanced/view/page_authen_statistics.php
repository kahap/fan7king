<?php 

$api = new API("real_cases");
$apiMem = new API("member");
$apiAau = new API("admin_advanced_user");
$aauData = $apiAau->getAll();
$data = $api->getAuthenStatistics();
foreach($aauData as $k => $v){
	$aauUser[$v['aauNo']] = $v['aauName'];
}
?>
<main class="mn-inner">
	<div class="row">
		<div class="col s12">
			<div class="page-title">授信作業 - 各授信人員案件統計</div>
		</div>
		<div class="col s12 m12 l12">
			<div class="card">
				<div class="card-content">
   					<table  id="example" class="display responsive-table datatable-example">
						<thead>
     						<tr>
								<th>月份</th>
								<th>授信人員</th>
								<th>核准</th>
								<th>待核准</th>
								<th>取消訂單</th>
								<th>客戶自行撤件</th>
								<th>婉拒</th>
								<th>總計</th>
     						</tr>
						</thead>
     					<tbody>
							<?php foreach($data as $key => $value){ ?>
							<tr>
								<td><?=$value['month']?></td>
								<td><?=$aauUser[$value['aauNoAuthen']]?></td>
								<td><?=$value['rcStatus3']?></td>
								<td><?=$value['rcStatus5']?></td>
								<td><?=$value['rcStatus7']?></td>
								<td><?=$value['rcStatus701']?></td>
								<td><?=$value['rcStatus4']?></td>
								<td><?=$value['rcStatus3']+$value['rcStatus5']+$value['rcStatus7']+$value['rcStatus701']+$value['rcStatus4']?></td>
							</tr>
							<?php }?>
							
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
	    "aoColumnDefs": [{
	        'bSortable': false,
	        'aTargets': [0]
	      } //disables sorting for column one
	    ],
	    "order": [[ 0 , "asc" ]],
	    "iDisplayLength": 100
    	
    });
    $('.dataTables_length select').addClass('browser-default');
});
</script>