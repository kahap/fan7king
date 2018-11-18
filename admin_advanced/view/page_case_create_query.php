<main class="mn-inner">
	<div class="row">
		<div class="col s12">
			<div class="page-title">進件查核表-查詢</div>
		</div>
		<div class="col s12 m12 l12">
			<!--<div class="card">
				<div class="card-content">
					<form>
						<div class="input-field col s6">
							<input type="text" id="rcCaseNo">
							<label class="">輸入案件編號查詢</label>
						</div>
					</form>
					<div style="padding:20px 0;">
						<a class="waves-effect waves-light btn green m-b-xs confirm-save">查詢</a>
					</div>
				</div>
				
			</div>
			<?php if($_GET['rcCaseNo'] != ""){ ?>
			<div class="card">
				<div class="card-content">
					<h6 class="page-title">爬蟲結果顯示</h6><br>
					<iframe src="http://192.168.0.161:42277/happyfan/v1/crawler?rcCaseNo=<?=$_GET['rcCaseNo'];?>" width="100%" height="100%"></iframe>
				</div>
			</div>
			<?php } ?>-->
			<iframe src="http://104.199.229.39" width="100%" height="765px"></iframe> 
		</div>
	</div>
</main>

<script src="assets/plugins/datatables/js/jquery.dataTables.min.js"></script>

<script>
$(function(){
	$(".confirm-save").click(function(e){
		location.href="index.php?page=case&type=query&rcCaseNo="+$('#rcCaseNo').val();
	});
});
</script>