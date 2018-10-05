<?php 

$api = new API("real_cases");
$apiMem = new API("member");
$apiAau = new API("admin_advanced_user");

$api->setWhereArray(array("rcStatus"=>2,"rcIfCredit"=>"1","rcIfAuthen"=>"0","aauNoAuthen"=>""));
$api->setWhereNotArray(array("aauNoCredit"=>""));
$api->setOrderArray(array("rcCaseNo"=>true));
$data = $api->getWithConditions();

$apiAau->setJoinArray(array("admin_advanced_roles"=>"aarNo"));
$aauData = $apiAau->getWithConditions();
$availableAauData = array();

$tmp=array();
foreach($aauData as $key=>$value){
	$aafNoArr = json_decode($value["aafID"]);
	if(in_array("301",$aafNoArr)){
		$aauNo=$value["aauNo"];
		if(!isset($tmp[$aauNo])){
			$tmp[$aauNo]=true;
			$availableAauData[$key] = $value;
		}
	}
}


?>
<main class="mn-inner">
	<div class="row">
		<div class="col s12">
			<div class="page-title">授信作業 - 授信人員派件</div>
		</div>
		<div class="col s12 m12 l12">
			<div class="card">
				<div class="card-content">
					<span class="card-title">審查中案件</span>
					<div class="input-field col s6">
						<select id="whichAau" name="aauNoCredit">
							<?php foreach($availableAauData as $key=>$value){ ?>
							<option value="<?php echo $value["aauNo"]; ?>" <?php echo ($_SESSION['adminUserData']['aauNo'] == $value["aauNo"]) ? 'selected':'';?>><?php echo $value["aauName"]; ?></option>
							<?php } ?>
						</select>
						<label>授信人員</label>
					</div>
					<div class="clearfix"></div>
					<div style="padding-bottom:20px;">
						<a class="waves-effect waves-light btn green m-b-xs confirm-save">確認派件</a>
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
								<th>身分別</th>
								<th>訂單狀態</th>
								<th>徵信狀態</th>
								<th>授信狀態</th>
     						</tr>
						</thead>
     					<tbody>
     					<?php 
     					if($data != null){
     						foreach($data as $key=>$value){
     							$memData = $apiMem->getOne($value["memNo"]);
								//未滿20歲註記
								$mark = (AgeOver20($memData[0]["memBday"]) < '20') ? '*':'';
     					?>
     						<tr>
     							<td style="text-align: center;">
     								<input data-no="<?php echo $value["rcNo"]; ?>" type="checkbox" class="tableflat for-checked">
     							</td>
     							<td><?php echo $key+1; ?></td>
     							<td><?php echo $value["rcCaseNo"]; ?></td>
     							<td><?php echo $memData[0]["memName"]; ?><font style="color:red"><?php echo $mark; ?></font></td>
     							<td><?php echo $memData[0]["memIdNum"]; ?></td>
								<td><?php echo $memData[0]["memClass"] != "" ? $apiMem->memClassArr[$memData[0]["memClass"]] : "無"; ?></td>
     							<td><?php echo $api->statusArr[$value["rcStatus"]]; ?></td>
     							<td><?php echo $value["rcIfCredit"] == "0" ? "尚未審核" : "已審核"; ?></td>
     							<td><?php echo $value["rcIfAuthen"] == "0" ? "尚未審核" : "已審核"; ?></td>
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
			var form = {"rcNo":selected,"aauNo":$("#whichAau option:selected").val()};
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
	    "aoColumnDefs": [{
	        'bSortable': false,
	        'aTargets': [0]
	      } //disables sorting for column one
	    ],
	    "order": [[ 1 , "desc" ]],
	    "iDisplayLength": 100
    	
    });
    $('.dataTables_length select').addClass('browser-default');
});
</script>