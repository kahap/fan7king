<?php


$columnArray = array("memClass","memName","memIdNum","memRecommCode");

if(isset($_POST["memClass"])){
	//若沒錯誤的話預設其他欄位
	if(!isset($errMsg)){
		if(isset($_POST["adTokenId"]) && trim($_POST["adTokenId"]) != ""){
            if(getIdNUmUsed()){
                $memNo = getMemberNo();
                foreach($_POST as $key=>$value){
                    if(!in_array($key,$columnArray)){
                        unset($_POST[$key]);
                    }
                }
                $api->update($_POST, $memNo);
            }else{
                $api->setInformation(false, 0, 0, "此身份證號已有人使用。");
            }
		}else{
			$api->setInformation(false, 0, 0, "請帶入該會員之登入TokenId。");
		}
	}else{
		$api->setInformation(false, 0, 0, $errMsg);
	}
}else{
	$api->setInformation(false, 0, 0, "請選擇身分別。");
}
$api->setResult();


?>