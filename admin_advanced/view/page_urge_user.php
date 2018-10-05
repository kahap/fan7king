<?php 

$aau = new API("admin_advanced_user");
$uu = new API("urge_user");
$up = new API("urge_period");

$uuData = $uu->getAll();


?>
<main class="mn-inner">
	<div class="row">
		<div class="col s12">
			<div class="page-title">催收人員列表</div>
		</div>
		<div class="col s12 m12 l12">
			<div class="card">
				<div class="card-content">
					<span class="card-title">催收人員列表</span>
					<div style="padding-bottom:20px;">
						<a class="waves-effect waves-light btn green m-b-xs" href="?page=urge&type=user&action=insert">新增催收人員</a>
					</div>
   					<table id="example" class="display responsive-table datatable-example">
						<thead>
     						<tr>
								<th>編號</th>
								<th>催收人員姓名</th>
								<th>負責逾期天數群組</th>
								<th>建立日期</th>
								<th></th>
     						</tr>
						</thead>
     					<tbody>
     					<?php 
     					if($uuData != null){
     						foreach($uuData as $key=>$value){
     							$aauData = $aau->getOne($value["aauNo"]);
     							$upData = $up->getOne($value["upNo"]);
     					?>
     						<tr>
     							<td><?php echo $key+1; ?></td>
     							<td><?php echo $aauData[0]["aauName"]; ?></td>
     							<td><?php echo $upData[0]["upName"]; ?></td>
     							<td><?php echo $value["uuDate"]; ?></td>
     							<td>
     								<a class="waves-effect waves-light btn green m-b-xs" href="?page=urge&type=user&action=edit&no=<?php echo $value["uuNo"]; ?>">編輯</a>
									<a class="waves-effect waves-light btn red m-b-xs" href="?page=urge&type=user&action=del&uuNo=<?php echo $value["aauNo"]; ?>&upNo=<?php echo $value["upNo"]; ?>">刪除</a>
     							</td>
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
			alert("請先勾選要派件的案件");
		}else{
			var selected = [];
			$(".for-checked:checked").each(function(){
				selected.push($(this).data("no"));
			});
			var form = {"orNo":selected,"aauNo":$("#whichAau option:selected").val()};
			var url = "ajax/authen/insert_new_authen.php";
			
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
	    "order": [[ 0 , "asc" ]],
	    "iDisplayLength": 100
    	
    });
    $('.dataTables_length select').addClass('browser-default');
});
</script>