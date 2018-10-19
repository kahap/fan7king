<?php
if(isset($_POST["adTokenId"]) && $_POST["adTokenId"] != ""){
	$memNo = getMemberNo();
	$or = new Orders();
	$data = $or->reject_custom_nogroup($memNo);

	if($data != null){
		$api->setInformation(false, 0, 0, "您之前已被婉拒，最近可下單時間為：".$data[0]["dueDate"]);
	}else{
		$api->setInformation(true, 1, 1, "此帳號可下單。");
	}
}else{
	$api->setInformation(false, 0, 0, "請帶入TOKEN。");
}
$api->setResult();

?>