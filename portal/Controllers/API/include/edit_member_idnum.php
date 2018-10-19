<?php

$columnArray = array("memName","memIdNum");

if(isset($_POST["memIdNum"])){
    if(getIdNUmUsed()){
        if(isset($_POST["adTokenId"]) && trim($_POST["adTokenId"]) != ""){
            $memNo = getMemberNo();
            foreach($_POST as $key=>$value){
                if(!in_array($key,$columnArray)){
                    unset($_POST[$key]);
                }
            }
            $api->update($_POST, $memNo);
        }else{
            $api->setInformation(false, 0, 0, "請帶入該會員之登入TokenId。");
        }
    }else{
        $api->setInformation(false, 0, 0, "此身份證號已有人使用。");
    }

}else{
	$api->setInformation(false, 0, 0, "請填入身分證號。");
}
$api->setResult();


?>