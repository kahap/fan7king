<?php
//一般註冊
$mem = new Member();

//必填欄位
$mustFill = array(
		"company_name","contact_name","email","phone","topic"
);
foreach($mustFill as $key=>$value){
	if(!isset($_POST[$value])){
		$errMsg[$value] = "欄位不得空白";
	}
}
foreach($_POST as $key=>$value){
	$$key = $value;
	if(in_array($key, $mustFill)){
		if(trim($value) == ""){
			$errMsg[$key] = "欄位不得空白。";
		}
	}
	if($value != ""){
		switch($key){
			//帳號或常用EMAIL
			case "email":
				if(!preg_match("/^[a-zA-Z0-9]+@{1}[a-zA-Z0-9]+\.{1}[a-zA-Z0-9]+/", $value)){
					$errMsg[$key] = "不正確的Email格式。";
				}
				break;
		}
	}
}

//若沒錯誤的話預設其他欄位
if(!isset($errMsg)){
	$_POST["time"] = $date;

	$api->insert($_POST);
	
}else{
	$api->setInformation(false, 500, 0, $errMsg);
}
$api->setResult();

?>