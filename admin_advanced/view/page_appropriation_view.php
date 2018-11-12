<?php 

if(isset($no)){
	$no = $_GET["no"];
	
	$rc = new API("real_cases");
	$or = new API("orders");
	$sup = new API("supplier");
	$rcMem = new API("member");
	$pm = new API("product_manage");
	$pro = new API("product");
	$pp = new API("product_period");
	$tb = new API("transfer_bank");
	$aau = new API("admin_advanced_user");
	$rcData = $rc->getOne($no);
	if($rcData != null){
		$memData = $rcMem->getOne($rcData[0]["memNo"]);
		

		
		//供應商
		//$supData = $sup->getOne($rcData[0]["supNo"]);
		
		//撥款銀行
		$tbData = $tb->getAll();
		//撥款人員
		$aauData = $aau->getOne($rcData[0]["aauNoAppro"]);
		
		//樂分期商品
		if($rcData[0]["rcType"] == "0"){
			$orData = $or->getOne($rcData[0]["rcRelateDataNo"]);
			$pmData = $pm->getOne($orData[0]["pmNo"]);
			$proData = $pro->getOne($pmData[0]["proNo"]);
			$supData = $sup->getOne($orData[0]["supNo"]);
			//實質利率
			$actualRate = number_format((float)RATE($rcData[0]["rcPeriodAmount"],$rcData[0]["rcPeriodTotal"]/$rcData[0]["rcPeriodAmount"],-$rcData[0]["rcBankTransferAmount"])*12*100, 5, '.', '');
		}else{
			$mco = new API("motorbike_cellphone_orders");
			$mcoData = $mco->getOne($rcData[0]["rcRelateDataNo"]);
			//實質利率
			$actualRate = number_format((float)RATE($rcData[0]["rcPeriodAmount"],($mcoData[0]["mcoMinMonthlyTotal"]*$rcData[0]["rcPeriodAmount"])/$rcData[0]["rcPeriodAmount"],-$rcData[0]["rcBankTransferAmount"])*12*100, 5, '.', '');
			echo $mcoData[0]["mcoMinMonthlyTotal"]*$rcData[0]["rcPeriodAmount"]." | ".$rcData[0]["rcPeriodAmount"];
		}
	}else{
		$errMsg = "查無此訂單。";
	}
}else{
	$errMsg = "錯誤的頁面導向。";
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
		<div class="actions clearfix">
			<ul role="menu" aria-label="Pagination">
				<li aria-hidden="false" aria-disabled="false">
					<a href="?page=appropriation&type=ready" role="menuitem" class="waves-effect waves-blue btn-flat">回去列表</a>
				</li>
			</ul>
		</div>
		<div class="col s12">
			<div class="page-title">案件: <?php echo $rcData[0]["rcCaseNo"]; ?></div>
		</div>
		<div class="col s12 m12 l12">
			<form class="col s12">
				<div class="row" style="text-align:center;">
					<a class="waves-effect waves-light btn green m-b-xs confirm-insert">完成撥款</a>
					<a class="waves-effect waves-light btn green m-b-xs confirm-save">待完成</a>
				</div>
				<div class="card">
					<div class="card-content">
						<span class="card-title">買帳作業	<span style="color:red;font-size:12px;font-weight:initial;">(紅色底線為可更改之欄位)</span></span><br>
						<div class="row">
							<input type="hidden" name="rcNo" value="<?php echo $rcData[0]["rcNo"]; ?>">
							<div class="row">
								<div class="input-field col s6">
									<input type="text" readonly value="<?php echo $rcData[0]["rcGetApproDate"]; ?>">
									<label class="">特約商請款日</label>
								</div>
								<div class="input-field col s6">
									<input type="text" readonly value="<?php echo $rcData[0]["rcCaseNo"]; ?>">
									<label class="">案件編號</label>
								</div>
							</div>
							<div class="row">
								<div class="input-field col s6">
									<input type="text" readonly value="<?php echo $memData[0]["memName"]; ?>">
									<label class="">申請人姓名</label>
								</div>
								<div class="input-field col s6">
									<input type="text" readonly value="<?php echo $memData[0]["memIdNum"]; ?>">
									<label class="">身份證字號</label>
								</div>
							</div>
							<div class="row">
								<div class="input-field col s4">
									<input type="text" name="rcApproDate" readonly value="<?php echo $rcData[0]["rcApproDate"] != "" ? $rcData[0]["rcApproDate"] : date("Y-m-d"); ?>">
									<label class="">買帳日期</label>
									<label id="rcApproDatelErr" class="error"></label>
								</div>
								<div class="input-field col s4">
									<input type="text" class="datepicker" name="rcPredictGetDate" value="<?php echo $rcData[0]["rcPredictGetDate"] != "" ? $rcData[0]["rcPredictGetDate"] : $predict = date("Y-m-d",strtotime("+1 day")); ?>">
									<label class="">預計撥款日</label>
									<label id="rcFirstPayDateErr" class="error"></label>
								</div>
								<div class="input-field col s4">
									<?php 
									if(isset($predict)){
// 										$first = getNextMonthDate($predict);
										if($rcData[0]['rcType'] == "0"){
											$first = date("Y-m-d",strtotime("+15 day"));
										}else{
											$first = date("Y-m-d",strtotime("+30 day"));
										}
									}
									?>
									<input type="text" class="datepicker" name="rcFirstPayDate" value="<?php echo $rcData[0]["rcFirstPayDate"] != "" ? $rcData[0]["rcFirstPayDate"] : $first; ?>">
									<label class="">第一次繳款日</label>
									<label id="rcFirstPayDateErr" class="error"></label>
								</div>
							</div>
						</div>
					</div>
				</div>
				<div class="card">
					<div class="card-content">
						<span class="card-title">特約商及分期相關資訊	<span style="color:red;font-size:12px;font-weight:initial;">(紅色底線為可更改之欄位)</span></span><br>
						<div class="row">
							<?php if($rcData['0']['rcType'] == "0"){ ?>
							<div class="row">
								<div class="input-field col s4">
									<input type="text" readonly value="<?php echo $rcData[0]["rcPeriodAmount"]; ?>">
									<label class="">期數</label>
								</div>
								<div class="input-field col s4">
									<input type="text" readonly value="<?php echo floor($rcData[0]["rcPeriodTotal"]/$rcData[0]["rcPeriodAmount"]); ?>">
									<label class="">期付款</label>
								</div>
								<div class="input-field col s4">
									<input type="text" readonly value="<?php echo $rcData[0]["rcPeriodTotal"]; ?>">
									<label class="">申請總金額</label>
								</div>
							</div>
							<div class="row">
								<div class="input-field col s6">
									<input type="text" name="rcBankTransferAmount" value="<?php echo $rcData[0]["rcBankTransferAmount"]; ?>">
									<label class="">撥款金額</label>
								</div>
								<div class="input-field col s6">
									<input id="actual-rate" type="text" readonly value="<?php echo $actualRate."%"; ?>">
									<label class="">實質利率</label>
								</div>
							</div>
							<div class="row">
<!-- 								<div class="input-field col s4"> -->
<!-- 									<input type="text" readonly value=""> -->
<!-- 									<label class="">銀行利率</label> -->
<!-- 								</div> -->
								<div class="input-field col s6">
									<input type="text" readonly value="<?php echo $rcData[0]["rcBankRiskFeeMonth"]; ?>">
									<label class="">風管費(月)</label>
								</div>
								<div class="input-field col s6">
									<input type="text" readonly value="<?php echo $rcData[0]["rcBankRiskFeeTotal"]; ?>">
									<label class="">風管費(總)</label>
								</div>
							</div>
							<div class="row">
								<div class="input-field col s12">
									<input type="text" readonly value="<?php echo isset($proData) ? $proData[0]["proName"] : "" ; ?>">
									<label class="">產品</label>
								</div>
							</div>
							<div class="row">
								<div class="input-field col s6">
									<input type="text" readonly value="<?php echo $supData[0]["supSerialNo"]; ?>">
									<label class="">特約商統編</label>
								</div>
								<div class="input-field col s6">
									<input type="text" readonly value="<?php echo $supData[0]["supName"]; ?>">
									<label class="">特約商名稱</label>
								</div>
							</div>
							<div class="row">
								<div class="input-field col s6">
									<input type="text" readonly value="<?php echo $supData[0]["supTransferAccName"]; ?>">
									<label class="">撥款帳戶名</label>
								</div>
								<div class="input-field col s6">
									<input type="text" readonly value="<?php echo $supData[0]["supTransferAccIdNum"]; ?>">
									<label class="">帳戶身份證字號</label>
								</div>
<!-- 								<div class="input-field col s4"> -->
<!-- 									<input type="text" readonly value=""> -->
<!-- 									<label class="">風險承擔比率</label> -->
<!-- 								</div> -->
							</div>
							<div class="row">
								<div class="input-field col s4">
									<input type="text" readonly value="<?php echo $supData[0]["supTransferBankCode"]." ".$supData[0]["supTransferBank"]; ?>">
									<label class="">通匯行庫名稱</label>
								</div>
								<div class="input-field col s4">
									<input type="text" readonly value="<?php echo $supData[0]["supTransferSubBank"]; ?>">
									<label class="">分行名稱</label>
								</div>
								<div class="input-field col s4">
									<input type="text" readonly value="<?php echo $supData[0]["supTransferAcc"]; ?>">
									<label class="">撥款帳號</label>
								</div>
							</div>
							<?php }else{ ?>
							<div class="row">
								<div class="input-field col s4">
									<input type="text" readonly value="<?php echo $rcData[0]["rcPeriodAmount"]; ?>">
									<label class="">期數</label>
								</div>
								<div class="input-field col s4">
									<input type="text" readonly value="<?php echo $mcoData[0]["mcoMinMonthlyTotal"]; ?>">
									<label class="">期付款</label>
								</div>
								<div class="input-field col s4">
									<input type="text" readonly value="<?php echo $rcData[0]["rcPeriodTotal"]; ?>">
									<label class="">申請總金額</label>
								</div>
							</div>
							<div class="row">
								<div class="input-field col s6">
									<input type="text" name="rcBankTransferAmount" value="<?php echo $rcData[0]["rcBankTransferAmount"]; ?>">
									<label class="">撥款金額</label>
								</div>
								<div class="input-field col s6">
									<input id="actual-rate" type="text" readonly value="<?php echo $actualRate."%"; ?>">
									<label class="">實質利率</label>
								</div>
							</div>
							<div class="row">
								<div class="input-field col s6">
									<input type="text" readonly value="<?php echo $rcData[0]["rcBankRiskFeeMonth"]; ?>">
									<label class="">風管費(月)</label>
								</div>
								<div class="input-field col s6">
									<input type="text" readonly value="<?php echo $rcData[0]["rcBankRiskFeeTotal"]; ?>">
									<label class="">風管費(總)</label>
								</div>
							</div>
							
							<?php } ?>
							<div class="row">
								<div class="input-field col s6">
									<select name="tbNo">
									<option value="">請選擇</option>
									<?php foreach($tbData as $key=>$value){ ?>
										<option <?php echo $value["tbNo"] == $rcData[0]["tbNo"] ? "selected" : ""; ?> value="<?php echo $value["tbNo"]; ?>"><?php echo $value["tbName"]; ?></option>
									<?php } ?>
									</select>
									<label class="">撥款銀行</label>
								</div>
<!-- 								<div class="input-field col s4"> -->
<!-- 									<input type="text" readonly value=""> -->
<!-- 									<label class="">銀行貸放日</label> -->
<!-- 								</div> -->
								<div class="input-field col s6">
									<input type="text" readonly value="<?php echo $rc->approStatusArr[$rcData[0]["rcApproStatus"]]; ?>">
									<label class="">撥款狀態</label>
								</div>
							</div>
							<div class="row">
<!-- 								<div class="input-field col s4"> -->
<!-- 									<input type="text" readonly value=""> -->
<!-- 									<label class="">業務單位</label> -->
<!-- 								</div> -->
<!-- 								<div class="input-field col s4"> -->
<!-- 									<input type="text" readonly value=""> -->
<!-- 									<label class="">內部承辦業務</label> -->
<!-- 								</div> -->
								<div class="input-field col s12">
									<input type="text" readonly value="<?php echo $aauData != null ? $aauData[0]["aauName"] : ""; ?>">
									<label class="">撥款人員</label>
								</div>
							</div>
						</div>
					</div>
				</div>
				<div class="row" style="text-align:center;">
					<a class="waves-effect waves-light btn green m-b-xs confirm-insert">完成撥款</a>
					<a class="waves-effect waves-light btn green m-b-xs confirm-save">待完成</a>
				</div>
			</form>
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
<script src="assets/js/pages/dialogs.js"></script>
<script>
$(function(){
	//日期自動大一個月
	$("input[name='rcPredictGetDate']").on("change",function(){
		var cur = $(this);
		var rcType = <?PHP echo $rcData[0]['rcType']; ?>;
		$.ajax({
			url:"ajax/appropriation/calc_next_month.php",
			type:"post",
			data:{date:cur.val(),rctype:rcType},
			datatype:"text",
			success:function(result){
				$("input[name='rcFirstPayDate']").val(result);
			}
		});
	});
	
	$(".confirm-save").click(function(e){
		$(".error").text("");
		e.preventDefault();

		if(parseFloat($("#actual-rate").val().replace("%","")) > 9 || window.confirm("現在的實質利率小於9%，確定還要繼續嗎？")){
			var form = $("form").serialize()+'&'+$.param({ 'process': false });
			var url = "ajax/appropriation/confirm_appropriation.php";
			
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
		}
	});

	$(".confirm-insert").click(function(e){
		$(".error").text("");
		e.preventDefault();

		if(parseFloat($("#actual-rate").val().replace("%","")) > 9 || window.confirm("現在的實質利率小於9%，確定還要繼續嗎？")){
			var form = $("form").serialize()+'&'+$.param({ 'process': true });
			var url = "ajax/appropriation/confirm_appropriation.php";
			
			$.ajax({
				url:url,
				type:"post",
				data:form,
				datatype:"json",
				success:function(result){
					if(result.indexOf("OK") != -1){
						alert("儲存成功！");
						location.href = "?page=appropriation&type=ready";
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
		}
	});
});
</script>