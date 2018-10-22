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
		$tb = new API("transfer_bank");
		$sc = new API("status_comment");
		$scr = new API("status_comment_records");
		$aau = new API("admin_advanced_user");
		$pro = new API("product");
		$pm = new API("product_manage");
		$orderContact = new API("orderContact");

		$memData = $mem->getOne($rcData[0]["memNo"]);
		$tbData = $tb->getAll();
		
		$sql = "SELECT * FROM `note_applier_details` WHERE `rcNo` = '".$no."' order by convert(`nlNo`, decimal),`nadDate` asc";
		$nadData = $nad->customSql($sql);
		
		$ncd->setWhereArray(array("rcNo"=>$no));
		//$ncd->setOrderArray(array("nlNo"=>true));
		
		$ncdData = $ncd->getWithConditions();

		$orderContact->setWhereArray(array("rcNo"=>$no));
		$orderContact->setOrderArray(array("ContactSort"=>false));
		$ocData=$orderContact->getWithConditions();
		$contactNameArr=array();
		$contactRelaArr=array();
		$contactPhoneArr=array();
		$contactCellArr=array();
		if (count($ocData)>0) {
			for ($i=0; $i < count($ocData); $i++) { 
				array_push($contactNameArr,$ocData[$i]["rcContactName"]);
				array_push($contactRelaArr,$ocData[$i]["rcContactRelation"]);
				array_push($contactPhoneArr,$ocData[$i]["rcContactPhone"]);
				array_push($contactCellArr,$ocData[$i]["rcContactCell"]);
			}
		}
		
		//補件上傳
		$rcExtraInfoUploadArr = json_decode($rcData[0]["rcExtraInfoUpload"]);
		
		//撥款銀行
		$tbOrData = $tb->getOne($rcData[0]["tbNo"]);
		
		//案件狀態備註
		$sc->setWhereArray(array("rcNo"=>$no,"scStatusNo"=>$rcData[0]["rcStatus"]));
		$scData = $sc->getWithConditions(true);
		
		//案件狀態備註紀錄()
		$scr->setWhereArray(array("rcNo"=>$no));
		$scrData = $scr->getWithConditions(true);
		
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
				$month_rate = new API("monthly_basis");
				$rateData = $month_rate->getAll();
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
h1,h2,h3,h4,h6{
	border-bottom:1px solid #AAA;
	padding:10px 0;
	font-weight:bold;
	margin-bottom:20px;
}
table{
	border:1px solid #AAA;
}
table tr td,table tr th{
	border-left:1px solid #AAA;
	border-right:1px solid #AAA;
    border-bottom: 1px solid #AAA;
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
				<li>
					<a type="button" href="#photos" class="modal-trigger waves-effect waves-light btn green m-b-xs look">查看證件上傳</a>
				</li>
				<li>
					<a type="button" href="http://happyfan7.com/admin_advanced/view/print_report_export.php?no=<?php echo $no; ?>" class="btn green m-b-xs" target="_blank">列印回覆書</a>
				</li>
				<li>
					<?php 
						switch($rcData[0]["rcType"]){
							case "0":
								?>
								<a target="_blank" href="../admin/view/print_order_details.php?orno=<?php echo $rcData[0]["rcRelateDataNo"]; ?>&rcNo=<?php echo $no; ?>&aauNoService=<?php echo $_SESSION['adminUserData']['aauNo']; ?>" class="modal-trigger waves-effect waves-light btn green m-b-xs print">列印</a>
								<?php 
								break;
							case "1":
								?>
								<a target="_blank" href="../admin/view/print_cell_details.php?mcoNo=<?php echo $rcData[0]["rcRelateDataNo"]; ?>&rcNo=<?php echo $no; ?>&aauNoService=<?php echo $_SESSION['adminUserData']['aauNo']; ?>" class="modal-trigger waves-effect waves-light btn green m-b-xs print">列印</a>
								<?php 	
									break;
									
							case "2":
								?>
								<a target="_blank" href="../admin/view/print_moto_details.php?mcoNo=<?php echo $rcData[0]["rcRelateDataNo"]; ?>&rcNo=<?php echo $no; ?>&aauNoService=<?php echo $_SESSION['adminUserData']['aauNo']; ?>" class="modal-trigger waves-effect waves-light btn green m-b-xs print">列印</a>
								<?php 	
									break;
							}
					?>
				</li>
			</ul>
		</div>
		<div class="col s12">
			<div class="page-title">授信案件: <?php echo $rcData[0]["rcCaseNo"]; ?><span style="color:red;"></span></div>
		</div>
			<div class="col s12 m12 l12">
				<div class="card">
					<div class="card-content">
						<a class="modal-trigger waves-effect waves-light btn blue m-b-xs" href="?page=authen&type=insert&no=<?php echo $rcData[0]['rcNo']; ?>&level=1" <?php echo ($_GET['level'] == 1) ? "disabled":"";?>>申請人基本資料</a> 
						<a class="modal-trigger waves-effect waves-light btn blue m-b-xs" href="?page=authen&type=insert&no=<?php echo $rcData[0]['rcNo']; ?>&level=2" <?php echo ($_GET['level'] == 2) ? "disabled":"";?>>徵信檢核表</a>
						<iframe src="http://210.242.238.169:42277/happyfan/v1/credit?action=query&rcCaseNo=<?php echo $rcData[0]["rcCaseNo"]; ?>" width="100%" height="1500px" />
					</div>
				</div>	
						
			</div>
		<?php } ?>
	</div>
</main>
<script src="assets/js/pages/form_elements.js"></script>
<script>
	$('.look').click(function(){
	$.ajax({
        type: "post",
        url: "http://api.21-finance.com/api/ViewDocument",
        data: { AID: "<?echo $rcData[0]["rcCaseNo"]; ?>", Name: "<?echo $_SESSION['adminUserData']['aauName']; ?>", Page: "授信案件?", Btn: "查看證件上傳", Ip: "<?echo $_SERVER["REMOTE_ADDR"]; ?>", LookDate: "<?echo date("Y-m-d H:i:s"); ?>" },
        success: function (data, status) {}
    });
});

 $('.print').click(function(){
	$.ajax({
        type: "post",
        url: "http://api.21-finance.com/api/ViewDocument",
        data: { AID: "<?echo $rcData[0]["rcCaseNo"]; ?>", Name: "<?echo $_SESSION['adminUserData']['aauName']; ?>", Page: "授信案件?", Btn: "列印", Ip: "<?echo $_SERVER["REMOTE_ADDR"]; ?>", LookDate: "<?echo date("Y-m-d H:i:s"); ?>" },
        success: function (data, status) {}
    });
});

</script>