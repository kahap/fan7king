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
			<div class="page-title">查詢入帳日清單</div>
		</div>
		<div class="col s12 m12 l12">
			<div class="card">
				<div class="card-content">
					<div class="row">
						<div class="input-field col s4">
							<input type="text" class="datepicker" id="searchDate" value="<?php echo date("Y-m-d"); ?>">
							<label>買回起日</label>
						</div>
						<div class="input-field col s4">
							<input type="text" class="datepicker" id="endchDate" value="<?php echo date("Y-m-d"); ?>">
							<label>買回迄日</label>
						</div>
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
		$("#loading").show();
		//var form = new FormData($("form")[0]);
		var url = "ajax/appropriation/90buy.php";
			
		$.ajax({
			url:url,
			type:"POST",
			data:{ strday: $("#searchDate").val(), endday: $("#endchDate").val() },
			datatype:"html",
			success:function(result){
				$("#loading").hide();
				$("#show").html(result);
			}
		});
	});
});
</script>