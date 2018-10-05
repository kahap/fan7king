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
			<div class="page-title">CMC每日撥款成功檔</div>
		</div>
		<div class="col s12 m12 l12">
			<div class="card">
				<div class="card-content">
					<form>
						<div class="input-field col s6">
							<input type="file" name="cmcSuccessGet">
						</div>
					</form>
					<div class="clearfix"></div>
					<img id="loading" src="assets/images/loading.gif">
					<div style="padding:20px 0;">
						<a class="waves-effect waves-light btn green m-b-xs confirm-save">確認上傳</a>
					</div>
				</div>
			</div>
		</div>
	</div>
</main>

<script src="assets/plugins/datatables/js/jquery.dataTables.min.js"></script>

<script>
$(function(){
	$(".confirm-save").click(function(e){
		e.preventDefault();
		$("#loading").show();
		var form = new FormData($("form")[0]);
		var url = "ajax/appropriation/load_cmc_appro_success.php";
		
		$.ajax({
			url:url,
			type:"post",
			data:form,
			datatype:"json",
			contentType:false,
			processData: false,
			success:function(result){
				$("#loading").hide();
				$("#result_wrapper").remove();
				if(isJsonString(result)){
					alert("上傳成功！");
					createTable($(".card-content"),"result",["完成撥款案件","撥款狀態","期數","期付款"]);
					var results = JSON.parse(result);
					printOnTable("#result",results);
				}else{
					alert(result);
				}
			},
			complete:function(result){
				initPage("#result",0,"asc");
			}
		});
	});
});

function isJsonString(str) {
    try {
        JSON.parse(str);
    } catch (e) {
        return false;
    }
    return true;
}
</script>