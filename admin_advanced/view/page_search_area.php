<?php 
$sup = new API("supplier");
$sup->setRetrieveArray(array("supNo","supName"));
$supData = $sup->getWithConditions();

//URL定義
if($page == "accounting" && $type == "edit_records"){
	$url = "?page=accounting&type=edit_records&action=list";
}
if($page == "orders_view"){
	$url = "?page=orders_view&type=list";
}
if($page == "orders_view_general"){
	$url = "?page=orders_view_general&type=list";
}
if($page == "accounting" && $type == "extra_pay"){
	$url = "?page=accounting&type=extra_pay&action=list";
}
if($page == "accounting" && $type == "finished_appro"){
	$url = "?page=accounting&type=finished_appro&action=list";
}
if($page == "urge" && $type == "edit_case"){
	$url = "?page=urge&type=edit_case&action=list";
}

?>
<link href="assets/plugins/select2/css/select2.css" rel="stylesheet"> 
<form id="search-form" method="POST" action="<?php echo $url; ?>">
	<div class="row">
		<div class="input-field col s4">
			<input type="text" class="datepicker" name="startDate">
			<label>起始日期</label>
		</div>
		<div class="input-field col s4">
			<input type="text" class="datepicker" name="endDate">
			<label>結束日期</label>
		</div>
		<div class="input-field col s4">
			<input type="text"  name="orCaseNo">
			<label>訂單編號</label>
		</div>
	</div>
	<div class="row">
		<div class="input-field col s4">
			<input type="text" name="memName">
			<label>申請人</label>
		</div>
		<div class="input-field col s4">
			<input type="text" name="memIdNum">
			<label>身分證字號</label>
		</div>
		<div class="input-field col s4">
			<input type="text" name="rcCaseNo">
			<label>案件編號</label>
		</div>
	</div>
	<div class="row">
		<div class="input-field col s4">
			<span>案件種類</span>
			<select name="rcType" class="js-states browser-default">
				<option value="">請選擇</option>
				<?php foreach($sup->caseTypeArr as $key=>$value){  ?>
				<option value="<?php echo $key; ?>"><?php echo $value; ?></option>
				<?php } ?>
			</select>
		</div>
		<div class="input-field col s4">
			<span>經銷商</span>
			<select name="supNo" class="js-states browser-default">
				<option value="">請選擇</option>
				<?php foreach($supData as $key=>$value){ ?>
				<option value="<?php echo $value["supNo"]; ?>"><?php echo $value["supName"]; ?></option>
				<?php } ?>
			</select>
		</div>
		<div class="input-field col s4">
			<span>案件狀態</span>
			<select name="rcStatus" class="js-states browser-default">
				<option value="">請選擇</option>
				<option value="3">核准</option>
				<option value="4">婉拒</option>
				<option value="5">待補件</option>
				<option value="6">已補件</option>
				<option value="7">取消訂單</option>
				<option value="701">客戶自行撤件</option>
			</select>
		</div>
	</div>
	
	<div style="padding-bottom:20px;">
		<a class="waves-effect waves-light btn green m-b-xs confirm-save">查詢</a>
	</div>
</form>
<script src="assets/js/pages/form_elements.js"></script>
<script src="assets/plugins/select2/js/select2.min.js"></script>
<script src="assets/js/pages/form-select2.js"></script>
<script>
$(function(){
	$(".confirm-save").click(function(){
		$("#search-form").submit();
	});
});
</script>