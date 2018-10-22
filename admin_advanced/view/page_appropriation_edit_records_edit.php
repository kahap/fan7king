<?php 
foreach (json_decode(ALLOWED_HOSTS) as $key => $value) {array_push($allowed_hosts,$value);}
if (!isset($_SERVER['HTTP_HOST']) || !in_array($_SERVER['HTTP_HOST'], $allowed_hosts)) {
	$errMsg = "您無權限造訪此頁";
}else{
	if(isset($no) || isset($_GET["no"])){
		$no = $_GET["no"];
		
		$rc = new API("real_cases");
		
		$rcData = $rc->getOne($no);
		
		if($rcData != null){
			$mem = new API("member");
			$tpi = new API("tpi");
			$pr = new API("pay_records");
			
			$memData = $mem->getOne($rcData[0]["memNo"]);
			$tpi->setWhereArray(array("rcNo"=>$no));
			$tpi->setOrderArray(array("tpiPeriod"=>false));
			$tpiData = $tpi->getWithConditions();
			if($tpiData == null){
				$errMsg = "此案件尚未產出本息表。";
			}
		}else{
			$errMsg = "查無此訂單。";
		}
	}else{
		$errMsg = "錯誤的頁面導向。";
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
			<div class="page-title">欲調帳之案件編號: <?php echo $rcData[0]["rcCaseNo"]; ?></div>
		</div>
		<div class="col s12 m12 l12">
			<div class="card">
				<div class="card-content">
					<span class="card-title">本息表</span>
					<div class="row" style="text-align:center;">
						<a class="waves-effect waves-light btn green m-b-xs confirm-save">完成調帳</a>
					</div>
					<div class="row">
						<form class="col s12">
							<div class="row">
								<div class="input-field col s6">
									<input type="text" readonly value="<?php echo $rcData[0]["rcExtraPayTotal"]; ?>">
									<label class="">溢收款</label>
								</div>
								<div class="input-field col s6">
									<input type="text" readonly value="<?php echo $rc->extraPayStatusArr[$rcData[0]["rcIfReturnExtraPay"]]; ?>">
									<label class="">退還狀態</label>
								</div>
							</div>
							<div class="row">
								<div class="input-field col s12">
									<input type="text" readonly value="<?php echo $rcData[0]["rcExtraPayTotalComment"]; ?>">
									<label class="">溢收款備註</label>
								</div>
							</div>
							<?php foreach($tpiData as $key=>$value){?>
							<div class="row">
								<input type="hidden" name="tpiNo[]" value="<?php echo $value["tpiNo"]; ?>">
								<div class="input-field col s2">
									<input type="text" readonly value="<?php echo $value["tpiPeriod"]; ?>">
									<label class="">期數</label>
								</div>
								<div class="input-field col s2">
									<input type="text" readonly value="<?php echo $value["tpiSupposeDate"]; ?>">
									<label class="">應繳款日</label>
								</div>
								<div class="input-field col s2">
									<input id="date-id-<?php echo $key; ?>" type="text" class="datepicker" name="tpiActualDate[]" value="<?php echo $value["tpiActualDate"]; ?>">
									<label for="date-id-<?php echo $key; ?>" class="">實際繳款日</label>
								</div>
								<div class="input-field col s2">
									<input type="text" readonly value="<?php echo $value["tpiPeriodTotal"]; ?>">
									<label class="">期付款</label>
								</div>
								<div class="input-field col s1">
									<input type="text" readonly value="<?php echo $value["tpiPenalty"]; ?>">
									<label class="">滯納金</label>
								</div>
								<div class="input-field col s2">
									<input id="pay-id-<?php echo $key; ?>" type="text" name="tpiPaidTotal[]" value="<?php echo $value["tpiPaidTotal"]; ?>">
									<label for="pay-id-<?php echo $key; ?>" class="">還款金額</label>
								</div>
								<div class="input-field col s1">
									<select name="tpiIfCancelPenalty[]">
										<option <?php echo $value["tpiIfCancelPenalty"] == "0" ? "selected" : ""; ?> value="0">否</option>
										<option <?php echo $value["tpiIfCancelPenalty"] == "1" ? "selected" : ""; ?> value="1">是</option>
									</select>
									<label class="">取消滯納金</label>
								</div>
							</div>
							<?php } ?>
						</form>
					</div>
					<div class="row" style="text-align:center;">
						<a class="waves-effect waves-light btn green m-b-xs confirm-save">完成調帳</a>
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
		var url = "ajax/appropriation/edit_tpi.php";
		
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
	});
	
});
</script>