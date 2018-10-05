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
			<div class="page-title">還款檔上傳紀錄</div>
		</div>
		<div class="col s12 m12 l12">
			<div class="card">
				<div class="card-content">
					<form>
						<div class="row">
							<div class="input-field col s6">
								<select id="which-bank">
									<option value="">請選擇</option>
									<option value="0">CMC</option>
									<option value="1">彰銀(超商)</option>
									<option value="2">彰銀(萬用帳號)</option>
								</select>
								<label>請選擇還款銀行</label>
							</div>
						</div>
					</form>
					<div class="clearfix"></div>
					
				</div>
			</div>
		</div>
	</div>
</main>

<script src="assets/plugins/datatables/js/jquery.dataTables.min.js"></script>

<script>
$(function(){
	$("#which-bank").change(function(e){
		e.preventDefault();
		if($("#which-bank option:selected").val() != ""){
			var form = {"which":$("#which-bank option:selected").val()};
			var url = "ajax/appropriation/get_pay_history.php";
			
			$.ajax({
				url:url,
				type:"post",
				data:form,
				datatype:"json",
				success:function(result){
					$("#result_wrapper").remove();
					if(isJsonString(result)){
						createTable($(".card-content"),"result",["上傳編號","操作人員","刪除","原始檔名","檔案存放位置","上傳時間"]);
						var results = JSON.parse(result);
						printOnTable("#result",results);
					}else{
						alert(result);
					}
				},
				complete:function(result){
					initPage("#result",5,"desc");
				}
			});
		}
	});


	//刪除紀錄
	$(document).on("click",".delete-pay",function(){
		var cur = $(this);
		if(window.confirm("於這檔案的銷帳將於繳款紀錄及本息表中刪除，確定要刪除嗎？")){
			var form = {"no":cur.data("no")};
			var url;
			switch($("#which-bank option:selected").val()){
				case "0":
					url = "ajax/appropriation/delete_pay_cmc.php";
					break;
				case "1":
					url = "ajax/appropriation/delete_pay_zy_store.php";
					break;
				case "2":
					url = "ajax/appropriation/delete_pay_zy_acc.php";
					break;
			}
			
			$.ajax({
				url:url,
				type:"post",
				data:form,
				datatype:"json",
				success:function(result){
					$("#result_wrapper").remove();
					if(isJsonString(result)){
						createTable($(".card-content"),"result",["回朔案件編號","操作人員","回朔時間"]);
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