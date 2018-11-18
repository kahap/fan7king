<?php
	header ("content-type: text/HTML; charset=utf-8");
	require_once('../../model/require_login.php');
	
	$pro = new Product();
	$proNo = @$_POST["proNo"] ;
	
	$newProNo = "";
	$last5Num = "";
	
	$proData = isset($proNo)? $pro->getOneProByNo($proNo) : null;
	
	if ($proData)foreach ($proData[0] as $key=>$value){
		$_POST[$key] = $value;
	}


	//最後一筆
	$lastData = $pro->getAllProDescWithCatAndBra($_POST["braNo"], $_POST["catNo"]);
	if($lastData != null){
		// if(substr($lastData[0]["proCaseNo"], -3)<9){
		// 	$old3Num = substr($lastData[0]["proCaseNo"], -3);
		// 	$last3Num = "00".++$old3Num;
		// }else if(substr($lastData[0]["proCaseNo"], -3)<99 && substr($lastData[0]["proCaseNo"], -3)>=9){
		// 	$old3Num = substr($lastData[0]["proCaseNo"], -3);
		// 	$last3Num = "0".++$old3Num;
		// }else{
		// 	$old3Num = substr($lastData[0]["proCaseNo"], -3);
		// 	$last3Num = ++$old3Num;
		// }
		$last5Num = substr($lastData[0]["proCaseNo"], -5)+1;
	}else{
		$last5Num = 1;
	}
	$newProNo = $_POST["catNo"].$_POST["biNo"].sprintf("%05d",$last5Num);
	
	$pro->insert($_POST, $newProNo);
	
	$newData = $pro->getAllProDescWithCatAndBra($_POST["braNo"], $_POST["catNo"]);
	
	echo $newData[0]["proNo"];

?>
