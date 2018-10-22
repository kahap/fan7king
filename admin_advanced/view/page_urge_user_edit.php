<?php 
foreach (json_decode(ALLOWED_HOSTS) as $key => $value) {array_push($allowed_hosts,$value);}
if (!isset($_SERVER['HTTP_HOST']) || !in_array($_SERVER['HTTP_HOST'], $allowed_hosts)) {
	$errMsg = "您無權限造訪此頁";
}else{
	
	$uu = new API("urge_user");
	$up = new API("urge_period");
	$aau = new API("admin_advanced_user");
	$allAauData = $aau->getAll();
	$allUpData = $up->getAll();
	
	if($action == "edit"){
		$no = $_GET["no"];
		$uuData = $uu->getOne($no);
		$aauData = $aau->getOne($uuData[0]["aauNo"]);
	}
}

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
			<div class="page-title"><?php echo $action == "insert" ? "新增" : "編輯";?>催收人員</div>
		</div>
		<div class="col s12 m12 l12">
			<div class="card">
				<div class="card-content">
					<span class="card-title">詳細資訊</span><br>
					<div class="row">
						<form class="col s12">
							<div class="row">
								<?php if($action == "edit"){ ?>
								<input type="hidden" name="uuNo" value="<?php echo $uuData[0]["uuNo"]; ?>">
								<?php } ?>
								<div class="input-field col s6">
									<select name="aauNo">
										<?php foreach($allAauData as $key=>$value){ ?>
										<option <?php echo $action == "edit" && $value["aauNo"] == $aauData[0]["aauNo"] ? "selected" : ""; ?> value="<?php echo $value["aauNo"]; ?>"><?php echo $value["aauName"]; ?></option>
										<?php } ?>
									</select>
									<label class="">催收人員</label>
								</div>
								<div class="input-field col s6">
									<select name="upNo">
										<?php foreach($allUpData as $key=>$value){ ?>
										<option <?php echo $action == "edit" && $uuData[0]["upNo"] == $value["upNo"] ? "selected" : ""; ?> value="<?php echo $value["upNo"]; ?>"><?php echo $value["upName"]; ?></option>
										<?php } ?>
									</select>
									<label class="">所屬催收範圍</label>
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

	
	$(".confirm-save").click(function(e){
		$(".error").text("");
		e.preventDefault();

		var form = $("form").serialize();
		var url = "ajax/urge/edit_urge_user.php";
		
		$.ajax({
			url:url,
			type:"post",
			data:form,
			datatype:"json",
			success:function(result){
				if(result.indexOf("OK") != -1){
					alert("儲存成功！");
					<?php if($action == "edit"){?>
					location.reload();
					<?php }else{ ?>
					location.href = "?page=urge&type=user";
					<?php } ?>
				}else{
					alert(result);
				}
			}
		});
	});
});
</script>