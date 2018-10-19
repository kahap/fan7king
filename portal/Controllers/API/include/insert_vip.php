<?php
$mustFill = array("name","email","phone","class");

foreach($mustFill as $key=>$value){
	if(!isset($_POST[$value]) || $_POST[$value] == ""){
		$errMsg[] = "Missing ".$value.".";
	}
}

if(!isset($errMsg)){
	foreach($_POST as $key=>$value){
		$$key = $value;
	}
	
	$array = array(
		"name"=>$name,
		"email"=>$email,
		"phone"=>$phone,
		"class"=>$class,
		"category"=>"汽車貸款"
	);

	$api->insert($array);

	$api->setInformation(true, 1, 1, "成功新增");
}else{
	$api->setInformation(false, 0, 1, $errMsg);
}
$api->setResult();
?>