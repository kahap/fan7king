<?php 
foreach (json_decode(ALLOWED_HOSTS) as $key => $value) {array_push($allowed_hosts,$value);}
if (!isset($_SERVER['HTTP_HOST']) || !in_array($_SERVER['HTTP_HOST'], $allowed_hosts)) {
	$errMsg = "您無權限造訪此頁";
}else{
	if(isset($no) || isset($_GET["no"])){
		$no = $_GET["no"];
		
		$sup = new API("supplier");
		
		$supData = $sup->getOne($no);
		
		if($supData == null){
			$errMsg = "查無此會員。";
		}
	}
}
$user = new API("admin_advanced_user");
$sql = "SELECT aauNo,aauName FROM `admin_advanced_user` where aarNo like '%12%' or aarNo like '%13%'";
$aaulist = $user->customSql($sql);
?>
<style>
.actions ul li{
	float:left;
}
.btn{
	margin:10px;
}
.each-img .id-pic{
	max-width:80%;
}

</style>
<main class="mn-inner">
	<div class="row">
		<?php if(!isset($errMsg)){ ?>
		<div class="col s12">
			<div class="page-title">供應商編號: <?php echo $no; ?></div>
		</div>
		<div class="col s12 m12 l12">
			<div class="card">
				<div class="card-content">
					<span class="card-title">供應商詳細資料</span>
					<div class="row" style="text-align:center;">
						<a class="waves-effect waves-light btn green m-b-xs confirm-save">確認儲存</a>
					</div>
					<div class="row">
						<form class="col s12">
							<?php echo isset($no) ? '<input type="hidden" name="supNo" value="'.$no.'">' : "" ; ?>
							<div class="row">
								<div class="input-field col s3">
									<input type="text" name="supName" value="<?php echo isset($supData) ? $supData[0]["supName"] : ""; ?>">
									<label class="">供應商名稱</label>
								</div>
								<div class="input-field col s3">
									<input type="text" name="supContactName" value="<?php echo isset($supData) ? $supData[0]["supContactName"] : ""; ?>">
									<label class="">供應商聯絡人</label>
								</div>
								<div class="input-field col s3">
									<input type="text" name="supPhone" value="<?php echo isset($supData) ? $supData[0]["supPhone"] : "";  ?>">
									<label class="">電話</label>
								</div>
								<div class="input-field col s3">
									<input type="text" name="supCell" value="<?php echo isset($supData) ? $supData[0]["supCell"] : "";  ?>">
									<label class="">手機</label>
								</div>
							</div>
                            
							<div class="row">
								<div class="input-field col s2">
									<input type="text" name="supLogId" value="<?php echo isset($supData) ? $supData[0]["supLogId"] : ""; ?>">
									<label class="">登入帳號</label>
								</div>
								<div class="input-field col s2">
									<input type="text" name="supPwd" value="<?php echo isset($supData) ? $supData[0]["supPwd"] : ""; ?>">
									<label class="">登入密碼</label>
								</div>
								<div class="input-field col s2">
									<input type="text" name="supKey" value="<?php echo isset($supData) ? $supData[0]["supKey"] : ""; ?>">
									<label class="">驗證碼</label>
								</div>
                               	<div class="input-field col s2">
									<input type="text" name="supDisplayName" value="<?php echo isset($supData) ? $supData[0]["supDisplayName"] : ""; ?>">
									<label class="">APP 顯示名稱</label>
								</div>
                              	<div class="input-field col s2">
									<input type="text" name="supPeriod" value="<?php echo isset($supData) ? $supData[0]["supPeriod"] : ""; ?>">
									<label class="">利率(%)</label>
								</div>
							</div>
							<div class="row">
								<div class="input-field col s2">
									<input type="text" name="supFax" value="<?php echo isset($supData) ? $supData[0]["supFax"] : ""; ?>">
									<label class="">傳真</label>
								</div>
								<div class="input-field col s2">
									<input type="text" name="supPostCode" value="<?php echo isset($supData) ? $supData[0]["supPostCode"] : ""; ?>">
									<label class="">郵遞區號</label>
								</div>
								<div class="input-field col s5">
									<input type="text" name="supAddr" value="<?php echo isset($supData) ? $supData[0]["supAddr"] : ""; ?>">
									<label class="">地址</label>
								</div>
							</div>
							<div class="row">
								<div class="input-field col s12">
									<input type="text" name="supBillAddr" value="<?php echo isset($supData) ? $supData[0]["supBillAddr"] : ""; ?>">
									<label class="">特約商發票/對帳單地址</label>
								</div>
							</div>
							<div class="row">
								<div class="input-field col s3">
									<input type="text" name="supEmail" value="<?php echo isset($supData) ? $supData[0]["supEmail"] : ""; ?>">
									<label class="">聯絡Email</label>
								</div>
								<div class="input-field col s3">
									<input type="text" name="supSerialNo" value="<?php echo isset($supData) ? $supData[0]["supSerialNo"] : ""; ?>">
									<label class="">統一編號</label>
								</div>
								<div class="input-field col s3">
									<input type="text" name="supContactIdNum" value="<?php echo isset($supData) ? $supData[0]["supContactIdNum"] : ""; ?>">
									<label class="">負責人身分證字號</label>
								</div>
								<div class="input-field col s3">
									<input class="datepicker" type="text" name="supSignDate" value="<?php echo isset($supData) ? $supData[0]["supSignDate"] : ""; ?>">
									<label class="">簽約日期</label>
								</div>
							</div>
							<div class="row">
								<div class="input-field col s4">
									<input type="text" name="supTransferAccName" value="<?php echo isset($supData) ? $supData[0]["supTransferAccName"] : ""; ?>">
									<label class="">撥款帳戶名稱</label>
								</div>
								<div class="input-field col s4">
									<input type="text" name="supTransferAccIdNum" value="<?php echo isset($supData) ? $supData[0]["supTransferAccIdNum"] : ""; ?>">
									<label class="">撥款帳戶身分證字號</label>
								</div>
								<div class="input-field col s4">
									<input type="text" readonly value="<?php echo isset($supData) ? $supData[0]["supDate"] : ""; ?>">
									<label class="">建立日期</label>
								</div>
							</div>
							<div class="row">
								<div class="input-field col s3">
									<input type="text" name="supTransferBank" value="<?php echo isset($supData) ? $supData[0]["supTransferBank"] : ""; ?>">
									<label class="">銀行名稱</label>
								</div>
								<div class="input-field col s3">
									<input type="text" name="supTransferBankCode" value="<?php echo isset($supData) ? $supData[0]["supTransferBankCode"] : ""; ?>">
									<label class="">銀行代號</label>
								</div>
								<div class="input-field col s3">
									<input type="text" name="supTransferSubBank" value="<?php echo isset($supData) ? $supData[0]["supTransferSubBank"] : ""; ?>">
									<label class="">分行名稱</label>
								</div>
								<div class="input-field col s3">
									<input type="text" name="supTransferSubBankCode" value="<?php echo isset($supData) ? $supData[0]["supTransferSubBankCode"] : ""; ?>">
									<label class="">分行代號</label>
								</div>
							</div>
							<div class="row">
								<div class="input-field col s12">
									<input type="text" name="supTransferAcc" value="<?php echo isset($supData) ? $supData[0]["supTransferAcc"] : ""; ?>">
									<label class="">撥款帳號</label>
								</div>
							</div>
							<div class="row">
								<div class="input-field col s3">
									<select name="aauNo">
										<?php
											echo "<option value=''>請選擇</option>";
											foreach($aaulist as $key=> $value){	
												$select = ($supData[0]["aauNo"] == $value['aauNo']) ? "selected":"";
										?>
											<option value="<?=$value["aauNo"];?>" <?=$select?>><?=$value['aauName'];?></option>
										<?php
											}
										?>
									
									</select>
									<label class="">業務人員</label>
								</div>
								<div class="input-field col s3">
									<input type="text" readonly value="<?php echo isset($supData) ? $supData[0]["editTime"] : ""; ?>">
									<label class="">建檔日期</label>
								</div>
								<div class="input-field col s3">
									<input type="text" readonly value="<?php echo isset($supData) ? $supData[0]["editpeople"]: ""; ?>">
									<input type="hidden" name="editpeople" value="<?=$_SESSION['adminUserData']['aauName']?>">
									<label class="">修改人員</label>
								</div>
								<div class="input-field col s3">
									<input type="text"  readonly value="<?php echo isset($supData) ? $supData[0]["supDate"]: ""; ?>">
									<label class="">修改日期</label>
								</div>
								
							</div>
							<div class="row">
								<div class="input-field col s12">
									<h5>印章圖</h5>
									<input id="img-upload" type="file" name="supStampImg">
									<div class="show-image">
										<img id="show-img" style="max-width:300px" src="<?php echo isset($supData) ? "../admin/".$supData[0]["supStampImg"] : ""; ?>">
									</div>
								</div>
							</div>
							
						</form>
					</div>
					<div class="row" style="text-align:center;">
						<a class="waves-effect waves-light btn green m-b-xs confirm-save">確認儲存</a>
					</div>
				</div>
			</div>
		</div>
		<?php }else{ ?>
		<div class="col s12">
			<div class="page-title"><?php echo $errMsg; ?></div>
		</div>
		<?php } ?>
	</div>
</main>
<script src="assets/js/pages/form_elements.js"></script>
<script src="assets/js/pages/ui-modals.js"></script>
<script src="assets/plugins/datatables/js/jquery.dataTables.min.js"></script>
<script>
$(function(){
	//瀏覽上傳圖片
	$("#img-upload").change(function(){
	    readURL(this);
	});

	
	$(".confirm-save").click(function(e){
		e.preventDefault();

		var form = new FormData($("form")[0]);
		var url = "ajax/supplier/insert_edit_supplier.php";
		
		$.ajax({
			url:url,
			type:"post",
			data:form,
			datatype:"json",
			contentType:false,
			processData: false,
			success:function(result){
				if(result.indexOf("OK") != -1){
					alert("儲存成功！");
					<?php if($action == "edit"){?>
					location.reload();
					<?php }else{?>
					location.href = "?page=supplier";
					<?php }?>
				}else{
					var results = JSON.parse(result);
					var err = "";
					$.each(results, function(k,v){
						err += (k+1)+". "+v;
						err += "<br>";
					});
					Materialize.toast(err, 4000);
				}
			}
		});
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
	    "order": [[ 1 , "asc" ]],
	    "iDisplayLength": 5
    	
    });
    $('.dataTables_length select').addClass('browser-default');
});

$(document).ready(function() {
    $('#example1').DataTable({
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
	    "order": [[ 1 , "asc" ]],
	    "iDisplayLength": 5
    	
    });
    $('.dataTables_length select').addClass('browser-default');
});

</script>