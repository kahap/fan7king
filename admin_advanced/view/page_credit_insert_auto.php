<?php 

if(isset($no)){
	$no = $_GET["no"];
	
	$api = new API("real_cases");
	
	$rcData = $api->getOne($no);
	if($rcData != null){
		$mem = new API("member");
		$np = new API("note_person");
		$nl = new API("note_list");
		$ndc = new API("note_default_comment");
		$nad = new API("note_applier_details");
		$ncd = new API("note_contact_details");
		$pro = new API("product");
		$pm = new API("product_manage");
		$orderContact = new API("orderContact");
		
		$memData = $mem->getOne($rcData[0]["memNo"]);
		$npData = $np->getAll();
		
		//先看是否已經有建立資料
		$nad->setWhereArray(array("rcNo"=>$no));
		$nadData = $nad->getWithConditions();
		$oldDataArr = array();
		if($nadData != null){
			foreach($nadData as $key=>$value){
				if(!is_numeric($value["ndcNo"])){
					$curNdcData = $ndc->getOne(json_decode($value["ndcNo"])[0]);
				}else{
					$curNdcData = $ndc->getOne($value["ndcNo"]);
				}
				$oldDataArr[$value["nlNo"]]["nadNo"] = $value["nadNo"];
				$oldDataArr[$value["nlNo"]]["ndcNo"] = $value["ndcNo"];
				$oldDataArr[$value["nlNo"]]["extra"] = $value["nadExtraInfo"];
			}
		}
		
		//聯絡人
		$ncd->setWhereArray(array("rcNo"=>$no));
		$ncdData = $ncd->getWithConditions();
		$oldNcdData = array();
		$finalKey = 0;
		if($ncdData != null){
			foreach($ncdData as $key=>$value){
				$oldNcdData[$value["nlNo"]]["ncdNo"][$value["ncdKey"]] = $value["ncdNo"];
				$oldNcdData[$value["nlNo"]]["ndcNo"][$value["ncdKey"]] = $value["ndcNo"];
				$oldNcdData[$value["nlNo"]]["extra"][$value["ncdKey"]] = $value["ncdExtraInfo"];
				$finalKey = $value["ncdKey"] > $finalKey ? $value["ncdKey"] : $finalKey;
			}
		}
		
		if($rcData[0]["rcType"] == "0"){
				$or = new API("orders");
				$or->setWhereArray(array("orNo"=>$rcData[0]["rcRelateDataNo"]));
				$orData = $or->getWithConditions();
				$pmData = $pm->getOne($orData[0]["pmNo"]);
				$proData = $pro->getOne($pmData[0]["proNo"]);
				//取得所有會員下單資訊
				$or->setWhereArray(array("memNo"=>$rcData[0]["memNo"]));
				$orDataList = $or->getWithConditions();
				
		}else{
				$moto = new API("motorbike_cellphone_orders");
				$moto->setWhereArray(array("mcoNo"=>$rcData[0]['rcRelateDataNo']));
				$motoData = $moto->getWithConditions();
		}
		
		$hasContact = false;
		$hasAssure = false;		
		$orderContact->setWhereArray(array("rcNo"=>$no));
		$orderContact->setOrderArray(array("ContactSort"=>false));
		$ocData=$orderContact->getWithConditions();
		$contactNameArr=array();
		$contactRelaArr=array();
		$contactPhoneArr=array();
		$contactCellArr=array();
		if (count($ocData)>0) {
			$hasContact = true;
			for ($i=0; $i < count($ocData); $i++) { 
				array_push($contactNameArr,$ocData[$i]["rcContactName"]);
				array_push($contactRelaArr,$ocData[$i]["rcContactRelation"]);
				array_push($contactPhoneArr,$ocData[$i]["rcContactPhone"]);
				array_push($contactCellArr,$ocData[$i]["rcContactCell"]);
			}
		}
	}else{
		$errMsg = "查無此訂單。";
	}
}else{
	$errMsg = "錯誤的頁面導向。";
}

?>
<style>
input:not([type]), input[type=text], input[type=password], input[type=email], input[type=url], input[type=time], input[type=date], input[type=datetime], input[type=datetime-local], input[type=tel], input[type=number], input[type=search], textarea.materialize-textarea{
	margin:0;
}
.actions ul li{
	float:left;
}
.input-field>h5{
    line-height: 24px;
    font-size: 13px;
    font-weight: 700;
    display: block;
    margin-bottom: 20px;
    text-transform: uppercase;
    color: rgba(0,0,0,.54);
}
</style>
<main class="mn-inner">
	<div class="row">
		<?php if(!isset($errMsg)){ ?>
		<div class="actions clearfix">
			<ul role="menu" aria-label="Pagination">
				<li aria-hidden="false" aria-disabled="false">
					<a href="?page=credit&type=view" role="menuitem" class="waves-effect waves-blue btn-flat">回去列表</a>
				</li>
			</ul>
		</div>
		<div class="col s12">
			<div class="page-title">徵信案件: <?php echo $rcData[0]["rcCaseNo"]; ?><span style="color:red;">　　(底下有紅色線為可編輯之欄位)</span></div>
		</div>
		<div class="col s12 m12 l12">
			<div class="card">
					<div class="card-content">
						<a class="modal-trigger waves-effect waves-light btn blue m-b-xs" href="?page=credit&type=insert&no=<?php echo $rcData[0]['rcNo']; ?>&level=1" <?php echo ($_GET['level'] == 1) ? "disabled":"";?>>申請人徵信</a> 
						<a class="modal-trigger waves-effect waves-light btn blue m-b-xs" href="?page=credit&type=insert&no=<?php echo $rcData[0]['rcNo']; ?>&level=2" <?php echo ($_GET['level'] == 2) ? "disabled":"";?>>自動徵信資料表</a>
					<iframe src="http://210.242.238.169:42277/happyfan/v1/credit?action=query&rcCaseNo=<?php echo $rcData[0]["rcCaseNo"]; ?>" width="100%" height="1500px" />
					</div>
			</div>
		</div>
		<?php } ?>
	</div>
</main>