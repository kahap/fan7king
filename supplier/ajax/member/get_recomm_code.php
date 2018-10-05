<?php

	header (" content-type: text/HTML; charset=utf-8 ");
	require_once('../../model/require_login.php');
	
	$mem = new Member();
	
	$noArr = array();
	
	$data = $mem->getMemberRecommCode($_GET["no"]);
	
	if($data != null){
		foreach($data as $key=>$value){
			echo $value["memNo"]."&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp".$value["memName"]."&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp".$value["memAccount"]."<br>";
		}
	}
	
	

?>