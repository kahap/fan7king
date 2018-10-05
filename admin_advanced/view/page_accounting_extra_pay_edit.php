<?php 
$allowed_hosts = array("localhost","127.0.0.1","happyfan7.com","test.happyfan7.com");
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
			<div class="page-title">溢收款案件編號: <?php echo $rcData[0]["rcCaseNo"]; ?></div>
		</div>
		<div class="col s12 m12 l12">
			<div class="row" style="text-align:center;">
				<a class="waves-effect waves-light btn green m-b-xs confirm-save">確認儲存</a>
			</div>
			<form class="col s12">
				<input type="hidden" name="rcNo" value="<?php echo $rcData[0]["rcNo"]; ?>">
				<div class="card">
					<div class="card-content">
						<span class="card-title">結清狀態</span>
						<div class="row">
							<div class="row">
								<div class="input-field col s4">
									<select name="rcFinishStatus">
										<?php foreach($rc->finishStatusArr as $key=>$value){ ?>
										<option <?php echo $rcData[0]["rcFinishStatus"] == $key ? "selected" : ""; ?> value="<?php echo $key; ?>"><?php echo $value; ?></option>
										<?php } ?>
									</select>
									<label class="">結清狀態</label>
								</div>
								<div class="input-field col s8">
									<input type="text" name="rcFinishComment" value="<?php echo $rcData[0]["rcFinishComment"]; ?>">
									<label class="">結清備註</label>
								</div>
							</div>
						</div>
					</div>
				</div>
				<div class="card">
					<div class="card-content">
						<span class="card-title">溢收款</span>
						<div class="row">
							<div class="row">
								<div class="input-field col s6">
									<input type="text" readonly value="<?php echo $rcData[0]["rcExtraPayTotal"]; ?>">
									<label class="">溢收款</label>
								</div>
								<div class="input-field col s6">
									<?php if($rcData[0]["rcExtraPayTotal"] == "0"){ ?>
									<input type="text" readonly value="<?php echo $rc->extraPayStatusArr[$rcData[0]["rcIfReturnExtraPay"]]; ?>">
									<?php }else{ ?>
									<select name="rcIfReturnExtraPay">
										<option <?php if($rcData[0]["rcIfReturnExtraPay"] == "1") echo "selected"?> value="1">尚未退還</option>
										<option <?php if($rcData[0]["rcIfReturnExtraPay"] == "2") echo "selected"?> value="2">已退還</option>
									</select>
									<?php } ?>
									<label class="">退還狀態</label>
								</div>
							</div>
							<div class="row">
								<div class="input-field col s12">
									<input type="text" name="rcExtraPayTotalComment" value="<?php echo $rcData[0]["rcExtraPayTotalComment"]; ?>">
									<label class="">溢收款備註</label>
								</div>
							</div>
						</div>
					</div>
				</div>
				<div class="card">
					<div class="card-content">
						<span class="card-title">本息表</span>
						<div class="row">
							<table class="striped responsive-table">
								<thead>
									<tr>
										<th>期數</th>
										<th>應繳款日</th>
										<th>實際繳款日</th>
										<th>期付款</th>
										<th>滯納金</th>
										<th>還款金額</th>
										<th>逾期天數</th>
									</tr>
								</thead>
								<tbody>
									<?php 
									if($tpiData != null){
										foreach($tpiData as $key=>$value){
									?>
									<tr>
										<td><?php echo $value["tpiPeriod"]; ?></td>
										<td><?php echo $value["tpiSupposeDate"]; ?></td>
										<td><?php echo $value["tpiActualDate"]; ?></td>
										<td><?php echo $value["tpiPeriodTotal"]; ?></td>
										<td><?php echo $value["tpiPenalty"]; ?></td>
										<td><?php echo $value["tpiPaidTotal"]; ?></td>
										<td><?php echo $value["tpiOverdueDays"]; ?></td>
									</tr>
									<?php 	
										}
									}else{
									?>
									<tr>
										<td colspan="7">尚無本息表產出</td>
									</tr>
									<?php 
									}
									?>
								</tbody>
							</table>
						</div>
					</div>
				</div>
			</form>
			<div class="row" style="text-align:center;">
				<a class="waves-effect waves-light btn green m-b-xs confirm-save">確認儲存</a>
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
		var url = "ajax/accounting/edit_extra_pay.php";
		
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