<?php
header('Content-Type: text/html; charset=utf8');
require_once('../../model/require_ajax.php');

$apiOr = new API("real_cases");
$ncd = new API("note_contact_details");
$rcData = $apiOr->getOne($_POST['rcNo']);

$orderContact = new API("orderContact");
$orderContact->setWhereArray(array("rcNo"=>$_POST['rcNo']));
$orderContact->setOrderArray(array("ContactSort"=>false));
$ocData=$orderContact->getWithConditions();

$or = new API("orders");
$moto = new API("motorbike_cellphone_orders");
	if($rcData	!= null){
	$orderContact->insert(array(
		"rcNo"=>$_POST['rcNo'],
		"ContactSort"=>(count($ocData)+1),
		"rcContactName"=>$_POST['rcContactName'][0],
		"rcContactRelation"=>$_POST['rcContactRelation'][0],
		"rcContactPhone"=>$_POST['rcContactPhone'][0],
		"rcContactCell"=>$_POST['rcContactCell'][0]
	));
	
	$inputDataNcd = array();
	$inputDataNcd["nlNo"] = $_POST['contactNlNo']['0'];
	$inputDataNcd["ncdKey"] = $_POST['finalKey']+1;
	$inputDataNcd["ndcNo"] = $_POST['contactNdcNoA'];
	$inputDataNcd["ncdExtraInfo"] = $_POST['contactNcdExtraInfo']['0'];
	$inputDataNcd["rcNo"] = $_POST['rcNo'];
	$inputDataNcd["ncdDate"] = date("Y-m-d H:i:s",time());
	$ncd->insert($inputDataNcd);
	
	$inputDataNcd = array();
	$inputDataNcd["nlNo"] = $_POST['contactNlNo']['1'];
	$inputDataNcd["ncdKey"] = $_POST['finalKey']+1;
	$inputDataNcd["ndcNo"] = $_POST['contactNdcNoB'];
	$inputDataNcd["ncdExtraInfo"] = $_POST['contactNcdExtraInfo']['1'];
	$inputDataNcd["rcNo"] = $_POST['rcNo'];
	$inputDataNcd["ncdDate"] = date("Y-m-d H:i:s",time());
	$ncd->insert($inputDataNcd);
		echo "<script>alert('新增聯絡人成功!!');</script>";
		echo "<script>location.href='../../index.php?page=credit&type=insert&no=".$_POST['rcNo']."';</script>";
	}
?>