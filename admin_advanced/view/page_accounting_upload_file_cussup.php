<style>
#loading{
	display:none;
	margin-top:20px;
	margin-left:20px;
}
</style>
<?php
	$receipt_record = new API("receipt_record");
	$rc_data = $receipt_record->getAll();
?>
<main class="mn-inner">
	<div class="row">
		<div class="col s12">
			<div class="page-title">上傳發票檔案</div>
		</div>
		<div class="col s12 m12 l12">
			<div class="card">
				<div class="card-content">
					<form>
						<div class="input-field col s6">
							<input type="file" name="uploadcussup">
						</div>
					</form>
					<div class="clearfix"></div>
					<img id="loading" src="assets/images/loading.gif">
					<div style="padding:20px 0;">
						<a class="waves-effect waves-light btn green m-b-xs confirm-save">確認上傳</a>
					</div>
				</div>
				
			</div>
			<div class="card">
				<div class="card-content">
					<h6 class="page-title">匯入21數位系統列表</h6>
					<table id="example" class="responsive-table datatable-example">
						<thead>
     						<tr>
								<th>編號</th>
								<th>存放路徑</th>
								<th>時間</th>
								<th>下載</th>
     						</tr>
						</thead>
     					<tbody>
     					<?php 
     					if($rc_data != null){
     						foreach($rc_data as $key=>$value){
     					?>
     						<tr>
								<td><?php echo $value["id"]; ?></td>
     							<td><?php echo $value["path"]; ?></td>
     							<td><?php echo $value["date"]; ?></td>
     							<td><a href="<?php echo substr($value["path"],6); ?>">點我下載</a></td>
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
</main>

<script src="assets/plugins/datatables/js/jquery.dataTables.min.js"></script>

<script>
$(function(){
	$(".confirm-save").click(function(e){
		e.preventDefault();
		$("#loading").show();
		var form = new FormData($("form")[0]);
		var url = "ajax/accounting/load_reciept_success.php";
		
		$.ajax({
			url:url,
			type:"post",
			data:form,
			datatype:"json",
			contentType:false,
			processData: false,
			success:function(result){
				$("#loading").hide();
				$("#result_wrapper").remove();
				if(result == "OK"){
					alert("上傳成功！");
					location.href="index.php?page=accounting&type=upload_file_cussup";
				}else{	
					alert(result);
				}
			}
		});
	});
});	
function isJsonString(str) {
    try {
        JSON.parse(str);
    } catch (e) {
        return false;
    }
    return true;
}
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
	    "order": [[ 0 , "desc" ]],
	    "iDisplayLength": 5000
    	
    });
    $('.dataTables_length select').addClass('browser-default');
});
</script>