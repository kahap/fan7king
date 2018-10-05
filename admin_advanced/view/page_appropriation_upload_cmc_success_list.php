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
			<div class="page-title">CMC撥款檔下載</div>
		</div>
		<div class="col s12 m12 l12">
			<div class="card">
				<div class="card-content">
					<form>
						<div class="row">
							<div class="input-field col s6">
								<input type="text" class="datepicker" id="which-date">
								<label for="which-date">請選擇日期</label>
							</div>
						</div>
						<div class="row">
							<a class="waves-effect waves-light btn green m-b-xs search-date">查詢</a>
						</div>
					</form>
					<div class="clearfix"></div>
					
				</div>
			</div>
		</div>
	</div>
</main>

<script src="assets/plugins/datatables/js/jquery.dataTables.min.js"></script>
<script src="assets/js/pages/form_elements.js"></script>

<script>
$(function(){
	$(".search-date").click(function(e){
		e.preventDefault();
		if($("#which-date").val() != ""){
			var form = {"date":$("#which-date").val()};
			var url = "ajax/appropriation/get_cmc_list.php";
			
			$.ajax({
				url:url,
				type:"post",
				data:form,
				datatype:"json",
				success:function(result){
					$("#result_wrapper").remove();
					if(isJsonString(result)){
						var results = JSON.parse(result);
						createTable($(".card-content"),"result",["編號","存放路徑","檔名","下載"]);
						printOnTable("#result",results);			
						
					}else{
						alert(result);
					}
				},
				complete:function(result){
					initPage("#result",0,"asc");
				}
			});
		}
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