<?php
header('Content-Type: text/html; charset=utf8');
require_once('../../model/require_ajax.php');

$apiOr = new API("real_cases");
$ncd = new API("note_contact_details");
$rcData = $apiOr->getOne($_POST['rcNo']);

$or = new API("orders");
$moto = new API("motorbike_cellphone_orders");
	if($rcData	!= null){
		$array['rcContactName'] = json_decode($rcData[0]['rcContactName']);
		$array['rcContactRelation'] = json_decode($rcData[0]['rcContactRelation']);
		$array['rcContactPhone'] = json_decode($rcData[0]['rcContactPhone']);
		$array['rcContactCell'] = json_decode($rcData[0]['rcContactCell']);
		
		
		$array['rcContactName'][] = $_POST['rcContactName'][0];
		$array['rcContactRelation'][] = $_POST['rcContactRelation'][0];
		$array['rcContactPhone'][] = $_POST['rcContactPhone'][0];
		$array['rcContactCell'][] = $_POST['rcContactCell'][0];
	
	
	$data = Array("rcContactName"=>json_encode($array['rcContactName'],JSON_UNESCAPED_UNICODE),
				"rcContactRelation"=>json_encode($array['rcContactRelation'],JSON_UNESCAPED_UNICODE),
				"rcContactPhone"=>json_encode($array['rcContactPhone'],JSON_UNESCAPED_UNICODE),
				"rcContactCell"=>json_encode($array['rcContactCell'],JSON_UNESCAPED_UNICODE));
	
	$apiOr->update($data,$_POST['rcNo']);
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