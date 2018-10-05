<main class="mn-inner">
	<div class="row">
		<div class="col s12">
			<div class="page-title">會計欲撥款案件列表 - 輸入關鍵字</div>
		</div>
		<div class="col s12 m12 l12">
			<div class="card">
				<div class="card-content">
					<div class="row">
						<div class="input-field col s4">
							<input type="text" class="datepicker" id="searchDate">
							<label>查詢撥款起始日期</label>
						</div>
						<div class="input-field col s4">
							<input type="text" class="datepicker" id="searchDateEnd">
							<label>查詢撥款結束日期</label>
						</div>
						<div class="input-field col s4">
							<select id="selectType">
								<option value="0" selected>妍嘉專用(每日)</option>
								<option value="1">淑芬專用(全部)</option>
								<option value="2">貞里專用</option>
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
			location.href = "?page=accounting&type=finished_appro&action=list&searchDate=" + $("#searchDate").val() + '&selectType=' + $("#selectType").val() + '&searchDateEnd=' + $("#searchDateEnd").val() ;
		}
	});
});
</script>