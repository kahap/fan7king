<?php 

$api = new API("real_cases");
$apiMem = new API("member");
$apiAau = new API("admin_advanced_user");
$aauData = $apiAau->getAll();
$data = $api->getauthenForDate($_GET['searchDate'],$_GET['searchDateEnd']);
foreach($aauData as $k => $v){
	$aauUser[$v['aauNo']] = $v['aauName'];
}
?>
<main class="mn-inner">
	<div class="row">
		<div class="col s12">
		<?php if($_GET['searchDate'] != ''){ ?>
			<div class="page-title">授信作業 - 從(<?php echo $_GET['searchDate']; ?>)到(<?php echo $_GET['searchDateEnd']; ?>)案件統計</div>
		<?php }else{ ?>
			<div class="page-title">授信作業 - 當日(<?php echo date("Y-m-d",time()); ?>)案件統計</div>
		<?php }?>
		</div>
		<div class="col s12 m12 l12">
			<div class="card">
				<div class="card-content">
					<div class="row">
						<div class="input-field col s4">
							<input type="text" class="datepicker" id="searchDate" value="<?php echo $_GET['searchDate']; ?>">
							<label>查詢起始日期</label>
						</div>
						<div class="input-field col s4">
							<input type="text" class="datepicker" id="searchDateEnd" value="<?php echo $_GET['searchDateEnd']; ?>">
							<label>查詢結束日期</label>
						</div>
						<div style="padding-bottom:20px;" class="col s4">
							<a class="waves-effect waves-light btn green m-b-xs confirm-save">查詢</a>
						</div>
					</div>
				</div>
			</div>
		</div>
		<div class="col s12 m12 l12">
			<div class="card">
				<div class="card-content">
   					<table  id="example" class="display responsive-table datatable-example">
						<thead>
     						<tr>
								<th rowspan='2'>授信人員</th>
								<th colspan='2'>核准</th>
								<th colspan='2'>待核准</th>
								<th colspan='2'>取消訂單</th>
								<th colspan='2'>客戶自行撤件</th>
								<th colspan='2'>婉拒</th>
								<th colspan='2'>總計</th>
     						</tr>
							<tr>
								<th>學生</th>
								<th>非學生</th>
								<th>學生</th>
								<th>非學生</th>
								<th>學生</th>
								<th>非學生</th>
								<th>學生</th>
								<th>非學生</th>
								<th>學生</th>
								<th>非學生</th>
								<th>總計</th>
								<th>核准率</th>
     						</tr>
						</thead>
     					<tbody>
							<?php foreach($data as $key => $value){
								if($value['aauNoAuthen'] != ""){
									if($value['status3'] !="0" or $value['status5'] != "0" or $value['status7'] != "0" or $value['status701'] != "0" or $value['status4'] != "0"){
								$St3 += $value['Stustatus3'];
								$St4 += $value['Stustatus4'];
								$St5 += $value['Stustatus5'];
								$St701 += $value['Stustatus701'];
								$St7 += $value['Stustatus7'];
								
								$S3 += $value['status3'];
								$S4 += $value['status4'];
								$S5 += $value['status5'];
								$S701 += $value['status701'];
								$S7 += $value['status7'];
								
								$total += $value['status3']+$value['Stustatus3']+$value['status5']+$value['Stustatus5']+$value['status7']+$value['Stustatus7']+$value['status701']+$value['Stustatus701']+$value['status4']+$value['Stustatus4'];
							?>
							<tr>
								<td><?=$aauUser[$value['aauNoAuthen']];?></td>
								<td><?=$value['Stustatus3']?></td>
								<td><?=$value['status3']?></td>
								<td><?=$value['Stustatus5']?></td>
								<td><?=$value['status5']?></td>
								<td><?=$value['Stustatus7']?></td>
								<td><?=$value['status7']?></td>
								<td><?=$value['Stustatus701']?></td>
								<td><?=$value['status701']?></td>
								<td><?=$value['Stustatus4']?></td>
								<td><?=$value['status4']?></td>
								<td><?=$value['status3']+$value['Stustatus3']+$value['status5']+$value['Stustatus5']+$value['status7']+$value['Stustatus7']+$value['status701']+$value['Stustatus701']+$value['status4']+$value['Stustatus4']?></td>
								<td><?=round(($value['status3']+$value['Stustatus3'])/($value['status3']+$value['Stustatus3']+$value['status5']+$value['Stustatus5']+$value['status7']+$value['Stustatus7']+$value['status4']+$value['Stustatus4'])*100)."%"?></td>
							</tr>
							<?php	} 
								}
							}
							?>
							
     					</tbody>
						<tfoot>
							<th>總計</th>
							<th><?=$St3?></th>
							<th><?=$S3?></th>
							<th><?=$St5?></th>
							<th><?=$S5?></th>
							<th><?=$St7?></th>
							<th><?=$S7?></th>
							<th><?=$St701?></th>
							<th><?=$S701?></th>
							<th><?=$St4?></th>
							<th><?=$S4?></th>
							<th><?=$total?></th>
							<th><?=($total-$St701-$S701)?round((($St3+$S3)/($total-$St701-$S701))*100)."%":"分母為零"?></th>
						</tfoot>
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
$(function(){
	$(".confirm-save").click(function(){
		if($("#searchDate").val() == ''){
			alert('必須選擇起始日期');
		}else{
			location.href = "?page=authen&type=statisticsForDate&searchDate=" + $("#searchDate").val() + '&searchDateEnd=' + $("#searchDateEnd").val();
		}
	});
});
</script>