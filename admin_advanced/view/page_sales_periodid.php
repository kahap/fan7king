<style>
#loading{
	display:none;
	margin-top:20px;
	margin-left:20px;
}
</style>
<main class="mn-inner">
	<div class="row">
		<div class="col s12">
			<div class="page-title">業務人員區間報表[含身分]查詢</div>
		</div>
		<div class="col s12 m12 l12">
			<div class="card">
				<div class="card-content">
					<div class="row">
						<div class="input-field col s4">
							<input type="text" class="datepicker" id="searchDate">
							<label>查詢起始日期</label>
						</div>
						<div class="input-field col s4">
							<input type="text" class="datepicker" id="searchDateEnd">
							<label>查詢結束日期</label>
						</div>
						<!--<div class="input-field col s4">
							<select id="selectType">
								<option value="0" selected>妍嘉專用(每日)</option>
								<option value="1">淑芬專用(全部)</option>
								<option value="2">貞里專用</option>
							</select>
							<label>匯出類型</label>
						</div>-->
					</div>
					<img id="loading" src="assets/images/loading.gif">
					<div style="padding-bottom:20px;">
						<a class="waves-effect waves-light btn green m-b-xs confirm-save">查詢</a>
					</div>
				</div>
			</div>
		</div>
		<div class="col s12 m12 l12" id="show">
		</div>
	</div>
</main>

<script src="assets/plugins/datatables/js/jquery.dataTables.min.js"></script>
<script src="assets/js/pages/form_elements.js"></script>
<script>
$(function(){
	$(".confirm-save").click(function(){
		$("#loading").show();
		//var form = new FormData($("form")[0]);
		var url = "ajax/sales/periodid.php";
		
		$.ajax({
			url:url,
			type:"POST",
			data:{ startday: $("#searchDate").val(),endday: $("#searchDateEnd").val() },
			datatype:"html",
			success:function(result){
				$("#loading").hide();
				$("#show").html(result);
				/*if(result == "OK"){
					$("#show").html(result);
				}else{	
					alert(result);
				}*/
			}
		});
	});
});
</script>