<main class="mn-inner">
	<div class="row">
		<div class="col s12">
			<div class="page-title">會計匯出發票檔案 - 輸入關鍵字</div>
		</div>
		<div class="col s12 m12 l12">
			<div class="card">
				<div class="card-content">
					<div class="row">
						<div class="input-field col s4">
							<input type="text" class="datepicker" id="searchDate">
							<label>匯出起始日期</label>
						</div>
						<div class="input-field col s4">
							<input type="text" class="datepicker" id="searchDateEnd">
							<label>匯出結束日期</label>
						</div>
						<div class="input-field col s4">
							<select id="selectType">
								<option value="0" selected>全部</option>
								<option value="1">已開銷貨編號</option>
								<option value="2">未開銷貨編號</option>
								<option value="3">已開發票</option>
								<option value="4">未開發票</option>
							</select>
							<label>匯出類型</label>
						</div>
					</div>
					<div style="padding-bottom:20px;">
						<a class="waves-effect waves-light btn green m-b-xs confirm-save">查詢</a>
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
		if($("#searchDate").val() == ''){
			alert('必須選擇起始日期');
		}else{
			location.href = "?page=accounting&type=<?php echo $_GET['type'];?>&action=list&searchDate=" + $("#searchDate").val() + '&searchDateEnd=' + $("#searchDateEnd").val() + '&selectType=' + $("#selectType").val();
		}
	});
});
</script>