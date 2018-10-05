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
			<div class="page-title">會員列表 - 輸入關鍵字</div>
		</div>
		<div class="col s12 m12 l12">
			<div class="card">
				<div class="card-content">
					<div class="input-field col s3">
						<input type="text" id="key_memberA" placeholder="A會員編號">
						<label for="key_word">輸入A會員編號</label>
					</div>
					<div class="input-field col s3">
						<input type="text" id="key_memberB" placeholder="B會員編號">
						<label for="key_word">輸入B會員編號</label>
					</div>
					<div class="clearfix"></div>
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

<script>
$(function(){
	$(".confirm-save").click(function(){
		//location.href = "?page=member&type=list&active=edit&key_word="+$("#key_word").val();
		$("#loading").show();
		//var form = new FormData($("form")[0]);
		var url = "ajax/member/member_fbchange.php";
		
		$.ajax({
			url:url,
			type:"POST",
			data:{ memberA: $("#key_memberA").val(),memberB:$("#key_memberB").val() },
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