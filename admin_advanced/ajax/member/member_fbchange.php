<?php
header('Content-Type: text/html; charset=utf8');
require_once('../../model/require_ajax.php');

session_start();

$message = ($_POST['memberA'] != '' && $_POST['memberB'] != '') ? 'ok':'error';

$apiMem = new API("member");
if($message == "ok"){
	$apiMem->setRetrieveArray(array("memNo","memClass","memName","memGender","memIdNum","memCell","memRegistDate","memFBtoken"));
	$apiMem->setOrLikeArray(array("memNo"=>trim($_POST['memberA'])));
	$userA = $apiMem->getWithConditions();
	
	$apiMem->setOrLikeArray(array("memNo"=>trim($_POST['memberB'])));
	$userB = $apiMem->getWithConditions();
?>
<style>
	select{
		display: -webkit-box;
	}
</style>

			<div class="card">
				<div class="card-content">
   					<table>
						<tr>
							<td></td>
							<td><a href="https://facebook.com/<?=$userA['0']['memFBtoken'];?>"><img src="http://graph.facebook.com/<?=$userA['0']['memFBtoken'];?>/picture"><br>
							<? echo ($userA['0']['memFBtoken'] != "") ? "按此連結":"沒有FB";?></a></td>
							<td><a href="https://facebook.com/<?=$userB['0']['memFBtoken'];?>"><img src="http://graph.facebook.com/<?=$userB['0']['memFBtoken'];?>/picture"><br>
							<? echo ($userB['0']['memFBtoken'] != "") ? "按此連結":"沒有FB";?></a></td>
						</tr>
						<tr>
							<td>會員編號</td>
							<td><?=$userA['0']['memNo'];?></td>
							<td><?=$userB['0']['memNo'];?></td>
						</tr>
						<tr>
							<td>會員姓名</td>
							<td><?=$userA['0']['memName'];?></td>
							<td><?=$userB['0']['memName'];?></td>
						</tr>
						<tr>
							<td>身分別</td>
							<td><?php echo ($userA['0']['memClass'] == '0') ? "學生":"非學生";?></td>
							<td><?php echo ($userB['0']['memClass'] == '0') ? "學生":"非學生";?></td>
						</tr>
						<tr>
							<td>身分證字號</td>
							<td><?=$userA['0']['memIdNum'];?></td>
							<td><?=$userB['0']['memIdNum'];?></td>
						</tr>
						<tr>
							<td>註冊日期</td>
							<td><?=$userA['0']['memRegistDate'];?></td>
							<td><?=$userB['0']['memRegistDate'];?></td>
						</tr>
						<tr>
							<td colspan="3">
								<input name="memNo_A" type="hidden" value="<?php echo $userA['0']['memNo']; ?>">
								<input name="memNo_B" type="hidden" value="<?php echo $userB['0']['memNo']; ?>">
								<a class="waves-effect waves-light btn green m-b-xs confirm-edit">修改</a>
							</td>
						</tr>
					</table>
				</div>
			</div>
<?php
}else{
	echo "<script>alert('請填寫會員編號');</script>";
}
?>			
<script>
	$(".confirm-edit").click(function(e){
		e.preventDefault();
		var url = "ajax/member/member_update.php";
		var form = new FormData($("form")[0]);
		$.ajax({
			url:url,
			type:"POST",
			data:{ memNo_A: $('input[name="memNo_A"]').val(),memNo_B: $('input[name="memNo_B"]').val()},
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