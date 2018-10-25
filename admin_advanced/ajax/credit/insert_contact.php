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
	$orderContact->setWhereArray(array("rcNo"=>$_POST['rcNo']));
	$orderContact->setOrderArray(array("ContactSort"=>false));
	$ocData=$orderContact->getWithConditions();
	$array['rcContactName']=array();
	$array['rcContactRelation']=array();
	$array['rcContactPhone']=array();
	$array['rcContactCell']=array();
	for ($i=0; $i < count($ocData); $i++) { 
		array_push($array['rcContactName'],$ocData[$i]['rcContactName']);
		array_push($array['rcContactRelation'],$ocData[$i]['rcContactRelation']);
		array_push($array['rcContactPhone'],$ocData[$i]['rcContactPhone']);
		array_push($array['rcContactCell'],$ocData[$i]['rcContactCell']);
	}

	if($rcData[0]['rcType'] == 0){
		$data = Array("orAppContactFrdName"=>json_encode($array['rcContactName'],JSON_UNESCAPED_UNICODE),
				"orAppContactFrdRelation"=>json_encode($array['rcContactRelation'],JSON_UNESCAPED_UNICODE),
				"orAppContactFrdPhone"=>json_encode($array['rcContactPhone'],JSON_UNESCAPED_UNICODE),
				"orAppContactFrdCell"=>json_encode($array['rcContactCell'],JSON_UNESCAPED_UNICODE));	
		$or->update($data,$rcData[0]['rcRelateDataNo']);
	}else{
		$data = Array("mcoContactName"=>json_encode($array['rcContactName'],JSON_UNESCAPED_UNICODE),
				"mcoContactRelation"=>json_encode($array['rcContactRelation'],JSON_UNESCAPED_UNICODE),
				"mcoContactPhone"=>json_encode($array['rcContactPhone'],JSON_UNESCAPED_UNICODE),
				"mcoContactCell"=>json_encode($array['rcContactCell'],JSON_UNESCAPED_UNICODE));
		$moto->update($data,$rcData[0]['rcRelateDataNo']);
	}
	
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