<?php
	$api = new API("other_setting");
	
	
	if($_GET['item'] == "update"){
		$update = array("delayDay"=>$_GET['delayDay'],"delayMoney"=>$_GET['delayMoney']);
		$api->update($update,"1");
		echo "<script>alert('更新成功');</script>";
	}
	
	$data = $api->getOne("1");
?>

<main class="mn-inner">
	<div class="row">
		<div class="col s12">
			<div class="page-title">逾期設定</div>
		</div>
		<div class="col s12 m12 l12">
			<div class="card">
				<div class="card-content">
					<div class="row">
						<div class="input-field col s4">
							<input type="text" class="" id="delayDay" value="<?php echo $data['0']['delayDay']; ?>">
							<label>逾期天數設定</label>
						</div>
						<div class="input-field col s4">
							<input type="text" class="" id="delayMoney" value="<?php echo $data['0']['delayMoney']; ?>">
							<label>逾期金額設定</label>
						</div>
					</div>
					<div style="padding-bottom:20px;">
						<a class="waves-effect waves-light btn green m-b-xs confirm-save">設定</a>
					</div>
				</div>
			</div>
		</div>
	</div>
</main>

<script src="assets/plugins/datatables/js/jquery.dataTables.min.js"></script>
<script src="assets/js/pages/form_elements.js"></script>
<script>
$(function(){
	$(".confirm-save").click(function(){
		if($("#delayDay").val() == '' || $("#delayMoney").val() == ''){
			alert('逾期設定不得為空值!!');
		}else{
			location.href = "?page=param&type=delay&item=update&delayDay=" + $("#delayDay").val() + "&delayMoney=" + $("#delayMoney").val();
		}
	});
});
</script>