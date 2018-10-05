<?php
header('Content-Type: text/html; charset=utf8');
require_once('../../model/require_ajax.php');

session_start();
$key_word = isset($_POST['key_word']) ? $_POST['key_word'] : "";

$apiMem = new API("member");
if(trim($key_word) != ""){
	$apiMem->setRetrieveArray(array("memNo","memClass","memName","memGender","memSubEmail","memIdNum","memCell","memRegistDate"));
	$apiMem->setOrLikeArray(array("memNo"=>$key_word,"memName"=>$key_word,"memIdNum"=>$key_word));
	$data = $apiMem->getWithConditions();
}else{
	$apiMem->setRetrieveArray(array("memNo","memClass","memName","memGender","memSubEmail","memIdNum","memCell","memRegistDate"));
	$data = $apiMem->getWithConditions();
}
?>
<style>
	select{
		display: -webkit-box;
		background-color: rgba(255,255,255,0.9);
		width: 100%;
		padding: 5px;
		border: 1px solid #1b1b1b;
		border-radius: 2px;
		height: 3rem;
	}
</style>

			<div class="card">
				<div class="card-content">
   					<table id="example" class="display responsive-table datatable-example">
						<thead>
     						<tr>
								<th>會員編號</th>
								<th>姓名</th>
								<th>身分別</th>
								<th>性別</th>
								<th>信箱</th>
								<th>身分證字號</th>
								<th>手機</th>
								<th>申請日期</th>
								<th></th>
     						</tr>
						</thead>
     					<tbody>
     					<?php 
     					if($data != null){
							$i=0;
     						foreach($data as $key=>$value){
								
     					?>
     						<tr>
							<form>
     							<td><a target="_blank" href="?page=member&type=view&no=<?php echo $value["memNo"]; ?>"><?php echo $value["memNo"]; ?></a></td>
     							<td>
									<input name="memName_<?php echo $i; ?>" value="<?php echo $value["memName"]; ?>">
								</td>
     							<td>
									<select name="memClass_<?php echo $i; ?>" class="memClass">
										<option value="">請選擇</option>
										<option value="0" <?php echo ($value["memClass"] == '0' ? 'selected':'');?>>學生</option>
										<option value="4" <?php echo ($value["memClass"] == '4' ? 'selected':'');?>>非學生</option>
									</select>	
								</td>
     							<td><?php echo $value["memGender"] == "0" ? "女" : "男"; ?></td>
     							<td>
									<input name="memSubEmail_<?php echo $i; ?>" value="<?php echo $value["memSubEmail"]; ?>">
								</td>
								<td>
									<input name="memIdnum_<?php echo $i; ?>" value="<?php echo $value["memIdNum"]; ?>">
								</td>
     							<td>
									<input name="memCell_<?php echo $i; ?>" value="<?php echo $value["memCell"]; ?>">
								</td>
     							<td><?php echo $value["memRegistDate"]; ?></td>
								<td>
									<input name="memNo_<?php echo $i; ?>" type="hidden" value="<?php echo $value["memNo"]; ?>">
									<a class="waves-effect waves-light btn green m-b-xs confirm-edit" data-gt="<?php echo $i; ?>">修改</a>
								</td>
							</form>
     						</tr>
							
     					<?php 
								$i++;
     						}
     					}
     					?>
     					</tbody>
					</table>
				</div>
			</div>
			
<script>
	$(".confirm-edit").click(function(e){
		e.preventDefault();
		var url = "ajax/member/member_update.php";
		var form = new FormData($("form")[0]);
		$.ajax({
			url:url,
			type:"POST",
			data:{ memName: $('input[name="memName_'+$(this).attr('data-gt')+'"]').val(),memClass: $('select[name="memClass_'+$(this).attr('data-gt')+'"]').val(), memIdnum: $('input[name="memIdnum_'+$(this).attr('data-gt')+'"]').val(), memSubEmail: $('input[name="memSubEmail_'+$(this).attr('data-gt')+'"]').val(), memCell: $('input[name="memCell_'+$(this).attr('data-gt')+'"]').val(),memNo: $('input[name="memNo_'+$(this).attr('data-gt')+'"]').val()},
			datatype:"html",
			success:function(result){
				$("#loading").hide();
				alert(result);
				/*if(result == "OK"){
					$("#show").html(result);
				}else{	
					alert(result);
				}*/
			}
		});
	})
</script>