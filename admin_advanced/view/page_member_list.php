<?php 

$key_word = isset($key_word) ? $key_word : "";

$apiMem = new API("member");

if(trim($key_word) != ""){
	$apiMem->setRetrieveArray(array("memNo","memClass","memName","memGender","memIdNum","memCell","memRegistDate"));
	$apiMem->setOrLikeArray(array("memNo"=>$key_word,"memName"=>$key_word,"memIdNum"=>$key_word));
	$data = $apiMem->getWithConditions();
}else{
	$apiMem->setRetrieveArray(array("memNo","memClass","memName","memGender","memIdNum","memCell","memRegistDate"));
	$data = $apiMem->getWithConditions();
}


?>
<main class="mn-inner">
	<div class="row">
		<div class="col s12">
			<input type="text" id="key_word" placeholder="申請人、案件編號、身分證字號">
		</div>
		<div class="col s12">
			<div class="page-title">會員列表 - 關鍵字: <?php echo trim($key_word) == "" ? "查全部" : $key_word; ?></div>
		</div>
		<div class="col s12 m12 l12">
			<div class="card">
				<div class="card-content">
   					<table id="example" class="display responsive-table datatable-example">
						<thead>
     						<tr>
								<th>會員編號</th>
								<th>姓名</th>
								<th>身分別</th>
								<th>性別</th>
								<th>身分證字號</th>
								<th>手機</th>
								<th>申請日期</th>
     						</tr>
						</thead>
     					<tbody>
     					<?php 
     					if($data != null){
     						foreach($data as $key=>$value){
     					?>
     						<tr>
     							<td><a target="_blank" href="?page=member&type=view&no=<?php echo $value["memNo"]; ?>"><?php echo $value["memNo"]; ?></a></td>
     							<td><?php echo $value["memName"]; ?></td>
     							<td><?php echo $value["memClass"] != "" ? $apiMem->memClassArr[$value["memClass"]] : "無"; ?></td>
     							<td><?php echo $value["memGender"] == "0" ? "女" : "男"; ?></td>
     							<td><?php echo $value["memIdNum"]; ?></td>
     							<td><?php echo $value["memCell"]; ?></td>
     							<td><?php echo $value["memRegistDate"]; ?></td>
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
<script>
$(function(){
	$("#key_word").keypress(function(e){
		  code = (e.keyCode ? e.keyCode : e.which);
		  if (code == 13)
		  {
			  location.href = "?page=member&type=list&key_word="+$("#key_word").val();
		  }
		});
});
</script>