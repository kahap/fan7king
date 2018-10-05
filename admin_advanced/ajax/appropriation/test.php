<?php
header('Content-Type: text/html; charset=utf8');
require_once('../../model/require_ajax.php');

$rc = new API("real_cases");

$or = new API("orders");

$rcData = $rc->getAll();

foreach($rcData as $key=>$value){
	$or->setRetrieveArray(array("orStatus"));
	$or->setWhereArray(array("orNo"=>$value["rcRelateDataNo"]));
	$orData = $or->getWithConditions();
	if($orData[0]["orStatus"] == "110"){
		$rc->update(array("rcStatus"=>"110"),$value["rcNo"]);
	}
}


?>