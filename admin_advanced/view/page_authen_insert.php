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
		
		$sql = "SELECT * FROM `note_applier_details` WHERE `rcNo` = '".$no."' && ass_No = '0' order by convert(`nlNo`, decimal),`nadDate` asc";
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
<div id="photos" class="modal modal-fixed-footer">
   <div class="modal-content">
		<?php if(!empty($rcData[0]["rcIdImgTop"])){ ?>
		<div class="each-img">
			<h4>申請人身分證正面照片</h4>
			<img class="id-pic" src="<?php echo IMG_ROOT.$rcData[0]["rcIdImgTop"]; ?>">
		</div>
		<?php } ?>
		<?php if(!empty($rcData[0]["rcIdImgBot"])){ ?>
		<div class="each-img">
			<h4>申請人身分證反面照片</h4>
			<img class="id-pic" src="<?php echo IMG_ROOT.$rcData[0]["rcIdImgBot"]; ?>">
		</div>
		<?php } ?>
		<?php if(!empty($rcData[0]["rcStudentIdImgTop"])){ ?>
		<div class="each-img">
			<h4>申請人學生證正面照片</h4>
			<img class="id-pic" src="<?php echo IMG_ROOT.$rcData[0]["rcStudentIdImgTop"]; ?>">
		</div>
		<?php } ?>
		<?php if(!empty($rcData[0]["rcStudentIdImgBot"])){ ?>
		<div class="each-img">
			<h4>申請人學生證反面照片</h4>
			<img class="id-pic" src="<?php echo IMG_ROOT.$rcData[0]["rcStudentIdImgBot"]; ?>">
		</div>
		<?php } ?>
		<?php if(!empty($rcData[0]["rcSubIdImgTop"])){ ?>
		<div class="each-img">
			<h4>申請人健保卡正面照片</h4>
			<img class="id-pic" src="<?php echo IMG_ROOT.$rcData[0]["rcSubIdImgTop"]; ?>">
		</div>
		<?php } ?>
		<?php if(!empty($rcData[0]["rcCarIdImgTop"])){ ?>
		<div class="each-img">
			<h4>申請人行照正面照片</h4>
			<img class="id-pic" src="<?php echo IMG_ROOT.$rcData[0]["rcCarIdImgTop"]; ?>">
		</div>
		<?php } ?>
		<?php if(!empty($rcData[0]["rcBankBookImgTop"])){ ?>
		<div class="each-img">
			<h4>申請人存摺正面照片</h4>
			<img class="id-pic" src="<?php echo IMG_ROOT.$rcData[0]["rcBankBookImgTop"]; ?>">
		</div>
		<?php } ?>
		<?php if(!empty($rcData[0]["rcRecentTransactionImgTop"])){ ?>
		<div class="each-img">
			<h4>申請人近6個月薪資往來照片</h4>
			<img class="id-pic" src="<?php echo IMG_ROOT.$rcData[0]["rcRecentTransactionImgTop"]; ?>">
		</div>
		<?php } ?>
		<?php if(!empty($rcExtraInfoUploadArr) || !empty($rcData[0]["rcExtraInfoUpload"])){ ?>
		<div class="each-img">
			<h4>申請人補件資料照片</h4>
			<?php 
			if(!empty($rcExtraInfoUploadArr)){ 
				foreach($rcExtraInfoUploadArr as $key=>$value){
			?>
			<img class="id-pic" src="<?php echo IMG_ROOT.$value; ?>">
			<?php 
				}
			}else{
				if(!empty($rcData[0]["rcExtraInfoUpload"])){
			?>
			<img class="id-pic" src="<?php echo IMG_ROOT.$rcData[0]["rcExtraInfoUpload"]; ?>">
			<?php 
				}
			}
			?>
		</div>
		<?php } ?>
    </div>
    <div class="modal-footer">
        <a href="#!" class="modal-action modal-close waves-effect waves-green btn-flat ">關閉</a>
    </div>
</div>
<main class="mn-inner">
	<div class="row">
	<div class="preloader-wrapper big active status" style="position:fixed;
				  top:50%;
				  right:50%;
				  _position: absolute; /* position fixed for IE6 */
				  _top:expression(documentElement.scrollTop+100);
				  z-index:3;">
        <div class="spinner-layer spinner-red-only">
            <div class="circle-clipper left">
                <div class="circle"></div>
            </div>
			<div class="gap-patch">
                <div class="circle"></div>
            </div>
			<div class="circle-clipper right">
                <div class="circle"></div>
            </div>
        </div>
    </div>
		<?php if(!isset($errMsg)){ ?>
		<div class="actions clearfix">
			<ul role="menu" aria-label="Pagination">
				<li aria-hidden="false" aria-disabled="false">
					<a onclick="window.history.back();" role="menuitem" class="waves-effect waves-blue btn-flat">回去列表</a>
				</li>
				<li>
					<a type="button" href="#photos" class="modal-trigger waves-effect waves-light btn green m-b-xs">查看證件上傳</a>
				</li>
				<li>
					<a type="button" href="http://test.happyfan7.com/admin_advanced/view/print_report_export.php?no=<?php echo $no; ?>" class="btn green m-b-xs" target="_blank">列印回覆書</a>
				</li>
				<li>
					<?php 
						switch($rcData[0]["rcType"]){
							case "0":
								?>
								<a target="_blank" href="../admin/view/print_order_details.php?orno=<?php echo $rcData[0]["rcRelateDataNo"]; ?>&rcNo=<?php echo $no; ?>&aauNoService=<?php echo $_SESSION['adminUserData']['aauNo']; ?>" class="modal-trigger waves-effect waves-light btn green m-b-xs">列印</a>
								<?php 
								break;
							case "1":
								?>
								<a target="_blank" href="../admin/view/print_cell_details.php?mcoNo=<?php echo $rcData[0]["rcRelateDataNo"]; ?>&rcNo=<?php echo $no; ?>&aauNoService=<?php echo $_SESSION['adminUserData']['aauNo']; ?>" class="modal-trigger waves-effect waves-light btn green m-b-xs">列印</a>
								<?php 	
									break;
									
							case "2":
								?>
								<a target="_blank" href="../admin/view/print_moto_details.php?mcoNo=<?php echo $rcData[0]["rcRelateDataNo"]; ?>&rcNo=<?php echo $no; ?>&aauNoService=<?php echo $_SESSION['adminUserData']['aauNo']; ?>" class="modal-trigger waves-effect waves-light btn green m-b-xs">列印</a>
								<?php 	
									break;
							}
					?>
				</li>
			</ul>
		</div>
		<div class="col s12">
			<div class="page-title">授信案件: <?php echo $rcData[0]["rcCaseNo"]; ?><span style="color:red;">　　(底下有紅色線為可編輯之欄位)</span></div>
		</div>
		<form>
			<input type="hidden" name="rcNo" value="<?php echo $no; ?>">
			<div class="col s12 m12 l12">
				<div class="card">
					<div class="card-content">
						<span class="card-title">申請人基本資料</span><br>
						<div class="row">
							<div class="row">
								<a class="modal-trigger waves-effect waves-light btn blue m-b-xs credit_view" <?php echo isset($_GET['level']) && ($_GET['level'] == 2) ? "disabled":"";?>>自動徵信資料表</a>
								<br>
								<div class="input-field col s4">
									<input type="text" readonly value="<?php echo $memData[0]["memName"]; ?>">
									<label class="">申請人姓名</label>
									<label id="memNameErr" class="error"></label>
								</div>
								<div class="input-field col s4">
									<input type="text" readonly value="<?php echo $api->memClassArr[$memData[0]["memClass"]]; ?>">
									<label class="">身分別</label>
								</div>
								<div class="input-field col s4">
									<input type="text" readonly value="<?php echo $memData[0]["memIdNum"]; ?>">
									<label class="">身分證字號</label>
								</div>
							</div>
							<div class="row">
								<div class="input-field col s4">
									<input type="text" readonly value="<?php echo ($rcData[0]['rcType'] == "0") ? $orData[0]['orIdIssueYear']."-".$orData[0]['orIdIssueMonth']."-".$orData[0]['orIdIssueDay']:$motoData[0]['mcoIdIssueYear']."-".$motoData[0]['mcoIdIssueMonth']."-".$motoData[0]['mcoIdIssueDay']; ?>">
									<label class="">發證日期</label> 
								</div>
								<div class="input-field col s4">
									<input type="text" readonly value="<?php echo ($rcData[0]['rcType'] == "0") ? $orData[0]['orIdIssuePlace']:$motoData[0]['mcoIdIssuePlace']; ?>">
									<label class="">發證地點</label>
								</div>
								<div class="input-field col s4">
									<input type="text" readonly value="<?php echo ($rcData[0]['rcType'] == "0") ? $orData[0]['orIdIssueType']:$motoData[0]['mcoIdIssueType']; ?>">
									<label class="">補換發類別</label>
								</div>
							</div>
							
							<div class="row">
								<div class="input-field col s4">
									<input type="text" readonly value="<?php echo $memData[0]["memBday"]; ?>">
									<?php $age = AgeOver20($memData[0]["memBday"]); ?>
									<label class="">出生日期 <font style="color:red"><?php echo ($age < '20') ? '未成年*':''; ?></font></label>
								</div>
								<div class="input-field col s4">
									<input type="text" readonly value="<?php echo $memData[0]["memAccount"] ; ?>">
									<label class="">認證Email</label>
								</div>
								<div class="input-field col s4">
									<input type="text" readonly value="<?php echo $memData[0]["memSubEmail"]; ?>">
									<label class="">常用Email</label>
								</div>
							</div>
							<div class="row">
								<div class="input-field col s4">
									<input type="text" readonly value="<?php echo $memData[0]["memCell"]; ?>">
									<label class="">手機</label>
								</div>
								
							</div>
							<div class="row">
								<div class="input-field col s4">
									<input type="text" readonly value="<?php echo $memData[0]["memPhone"]; ?>">
									<label class="">現住電話</label>
								</div>
								<div class="input-field col s2">
									<input type="text" readonly value="<?php echo $memData[0]["memPostCode"]; ?>">
									<label class="">郵遞區號</label>
								</div>
								<div class="input-field col s6">
									<input type="text" readonly value="<?php echo $memData[0]["memAddr"] ; ?>">
									<label class="">現住地址</label>
								</div>
							</div>
							<div class="row">
								<div class="input-field col s4">
									<input type="text" readonly value="<?php echo $rcData[0]["rcBirthPhone"]; ?>">
									<label class="">戶籍電話</label>
								</div>
								<div class="input-field col s2">
									<input type="text" readonly value="<?php echo $rcData[0]["rcBirthAddrPostCode"]; ?>">
									<label class="">郵遞區號</label>
								</div>
								<div class="input-field col s6">
									<input type="text" readonly value="<?php echo $rcData[0]["rcBirthAddr"] ; ?>">
									<label class="">戶籍地址</label>
								</div>
							</div>
							<?php if($memData[0]["memCompanyName"] != ""){?>
							<div class="row">
								<div class="input-field col s3">
									<input type="text" readonly value="<?php echo $memData[0]["memCompanyName"]; ?>">
									<label class="">公司名稱</label>
								</div>
								<div class="input-field col s3">
									<input type="text" readonly value="<?php echo $memData[0]["memSalary"]; ?>">
									<label class="">月收入</label>
								</div>
								<div class="input-field col s3">
									<input type="text" readonly value="<?php echo $memData[0]["memYearWorked"]; ?>">
									<label class="">年資</label>
								</div>
								<div class="input-field col s3">
									<input type="text" readonly value="<?php echo ($rcData[0]['rcType'] == '0') ? $orData[0]["orAppApplierCompanyPhone"]." 分機".$orData[0]["orAppApplierCompanyPhoneExt"]:$motoData[0]["mcoCompanyPhone"]." 分機".$motoData[0]["mcoCompanyPhoneExt"]; ?>">
									<label class="">公司電話</label>
								</div>
							</div>
							<?php } ?>
							<?php 
								if($rcData[0]['rcType'] == 0){
							?>
							<div class="row">
								<div class="input-field col s4">
									<input type="text" name="orReceiveName" value="<?php echo $orData[0]["orReceiveName"]; ?>">
									<label class="">收貨人姓名</label>
								</div>
								<div class="input-field col s8">
									<input type="text" name="orReceiveAddr" value="<?php echo $orData[0]["orReceiveAddr"]; ?>">
									<label class="">收貨人地址</label>
								</div>
							</div>
							<div class="row">
								<div class="input-field col s4">
									<input type="text" name="orReceivePhone" value="<?php echo $orData[0]["orReceivePhone"]; ?>">
									<label class="">收貨人市話</label>
								</div>
								<div class="input-field col s4">
									<input type="text" name="orReceiveCell" value="<?php echo $orData[0]["orReceiveCell"]; ?>">
									<label class="">收貨人手機</label>
								</div>
								<div class="input-field col s4">
									<input type="text" name="orReceiveComment" value="<?php echo $orData[0]["orReceiveComment"]; ?>">
									<label class="">收貨備註</label>
								</div> 
							</div>
							<div class="row">
								<div class="input-field col s8">
									<input type="text" readonly value='<?php echo htmlspecialchars($proData[0]["proName"]); ?>'>
									<label class="">購買商品</label>  
								</div>
								<div class="input-field col s4">
									<input type="text" readonly value='<?php echo $orData[0]["orProSpec"];?>'>
									<label class="">規格</label>
								</div>
							</div>
							<div class="row">
								<div class="input-field col s4">
									<input type="text" readonly value="<?php echo $rcData[0]["rcPeriodTotal"]; ?>">
									<label class="">貸款金額</label>
								</div>
								<div class="input-field col s4">
									<input type="text" readonly value="<?php echo $rcData[0]["rcPeriodAmount"]; ?>">
									<label class="">期數</label>
								</div>
								<div class="input-field col s4">
									<input type="text" readonly value="<?php echo $rcData[0]["rcPeriodTotal"]/$rcData[0]["rcPeriodAmount"]; ?>">
									<label class="">期付金額</label>
								</div>
							</div>
							
							<div class="row">
								<h5>曾經下過訂單資訊</h5>
								<table style="margin-top:50px" class="striped responsive-table">
	                                <thead>
	                                    <tr>
	                                        <th>時間</th>
											<th>訂單編號</th>
	                                        <th>商品名稱</th>
											<th>申貸金額</th>
											<th>期數</th>
	                                        <th>狀態</th>  
	                                    </tr>
	                                </thead>
	                                <tbody>
									<?php
										foreach($orDataList as $key => $value){
											$pmDatahistory = $pm->getOne($value["pmNo"]);
											$proDatahistory = $pro->getOne($pmDatahistory[0]["proNo"]);	
									?>
										<tr>
											<td><?php echo $value["orDate"]; ?></td>
											<td><?php echo $value["orCaseNo"]; ?></td>
											<td><?php echo $proDatahistory[0]["proName"];?></td>
											<td><?php echo $value["orPeriodTotal"];?></td>
											<td><?php echo $value["orPeriodAmnt"];?></td>
											<td><?php echo $api->statusArr[$value["orStatus"]];?></td>
										</tr>
									<?php
										}
									?>
									</tbody>
								</table>
							</div>
							<?php
								}else{
									
									if($rcData[0]['rcType'] == '1'){
							?>
							<div class="row">
								<div class="input-field col s3">
									<input type="text" readonly value='<?php echo $motoData[0]["mcoCellBrand"]; ?>'>
									<label class="">手機廠牌</label>  
								</div>
								<div class="input-field col s3">
									<input type="text" readonly value='<?php echo $motoData[0]["mcoCellphoneSpec"]; ?>'>
									<label class="">手機型號</label>  
								</div>
							</div>
							<?php
									}else{
							?>
							<div class="row">
								<div class="input-field col s3">
									<input type="text" readonly value='<?php echo $motoData[0]["mcoMotorBrand"]; ?>'>
									<label class="">機車廠牌</label>  
								</div>
								<div class="input-field col s3">
									<input type="text" readonly value='<?php echo $motoData[0]["mcoCcNumber"]; ?>'>
									<label class="">CC數</label>
								</div>
								<div class="input-field col s3">
									<input type="text" readonly value='<?php echo $motoData[0]["mcoCarNum"]; ?>'>
									<label class="">車牌</label>
								</div>
								<div class="input-field col s3">
									<input type="text" readonly value='<?php echo $motoData[0]["mcoYear"]; ?>'>
									<label class="">年份</label>
								</div>
							</div>
							<?php
									}
							?>
							<div class="row">
								<div class="input-field col s4">
									<input type="text" readonly value="<?php echo $motoData[0]["mcoPeriodTotal"]; ?>">
									<label class="">貸款金額</label>
								</div>
								<div class="input-field col s4">
									<input type="text" readonly value="<?php echo $motoData[0]["mcoPeriodAmount"]; ?>">
									<label class="">期數</label>
								</div>
								<div class="input-field col s4">
									<input type="text" readonly value="<?php echo floor($motoData[0]["mcoMinMonthlyTotal"]); ?>">
									<label class="">期付金額</label>
								</div>
							</div>
							<div class="row">
								<div class="input-field col s12">
									<input type="text" readonly value="<?php echo $motoData[0]["mcoApplyPurpose"]; ?>">
									<label class="">資金用途</label>
								</div>
							</div>							
							<?php
									
								}
							?>
						</div>
					</div>
				</div>
				<div class="card">
					<div class="card-content">
						<span class="card-title">案件基本資料</span>
						<div class="row">
							<div class="row">
								<div class="input-field col s6">
									<input type="text" readonly value="<?php echo $tbOrData[0]["tbName"]; ?>">
									<label class="">撥款銀行</label>
								</div>
								<div class="input-field col s6">
									<input type="text" readonly value="<?php echo $rcData[0]["rcCaseNo"]; ?>">
									<label class="">案件編號</label>
								</div>
							</div>
							<div class="row">
								<div class="input-field col s6">
									<h5 class="">申請人GPS、簡訊、通訊錄詳細資料 <a href="https://www.facebook.com/<?php echo $memData[0]["memFBtoken"]; ?>" target="_blank">FB 連結</a></h5>
									<a target="_blank" href="?page=member&type=view&no=<?php echo $rcData[0]["memNo"]; ?>">點我查看</a><br>
									是否來自APP：
									<font style="color:red">
									<?php
										if($rcData[0]["rcType"] == "0"){ echo ($orData[0]['orIpAddress'] != "") ? "否":"是"; }else{ echo "是";}
									?>
									</font>
								</div>
							</div>
						</div>
					</div>
				</div>
				<div class="card">
					<div class="card-content">
						<span class="card-title">徵信照會資料</span>
						<div class="row">
							<div class="row">
								<?php if($nadData != null){ ?>
								<div class="row" style="background-color:rgb(250,250,250);padding: 20px 50px;">
									<h6 class="">申請人照會</h6>
									<table class="striped responsive-table">
	                                    <thead>
	                                        <tr>
	                                            <th style="width:150px">照會項目</th>
	                                            <th style="width:150px">狀況</th>
	                                            <th>備註</th>
	                                            <th style="width:150px">日期</th>
	                                        </tr>
	                                    </thead>
	                                    <tbody>
	                                    	<?php 
	                                    		foreach($nadData as $key=>$value){
	                                    			$nlData = $nl->getOne($value["nlNo"]);
	                                    			$ndcArr = json_decode($value["ndcNo"]);
	                                    			$ndcOption = "";
	                                    			if(is_array($ndcArr) && !empty(array_filter($ndcArr))){
	                                    				$arrKey = array_keys($ndcArr);
	                                    				$lastKey = array_pop($arrKey);
	                                    				foreach($ndcArr as $keyIn=>$valueIn){
	                                    					$ndcData = $ndc->getOne($valueIn);
	                                    					if($lastKey != $keyIn){
	                                    						$ndcOption .= $ndcData[0]["ndcOption"]."、";
	                                    					}else{
	                                    						$ndcOption .= $ndcData[0]["ndcOption"];
	                                    					}
	                                    				}
	                                    			}else if(is_numeric($value["ndcNo"])){
	                                    				$ndcData = $ndc->getOne($value["ndcNo"]);
	                                    				$ndcOption = $ndcData[0]["ndcOption"];
	                                    			}else{
	                                    				$ndcOption = "無";
	                                    			}
	                                    			if($ndcOption != "請選擇" || $value["nadExtraInfo"] != ""){
	                                    	?>
	                                        <tr>
	                                            <td class="nlName"><?php echo $nlData[0]["nlName"]; ?></td>
	                                            <td><?php echo $ndcOption != "請選擇" ? $ndcOption : ""; ?></td>
	                                            <td><?php echo $value["nadExtraInfo"]; ?></td>
	                                            <td><?php echo $value["nadDate"]; ?></td>
	                                        </tr>
	                                        <?php
	                                    			} 
	                                    		}
	                                    	?>
	                                    </tbody>
	                                </table>
	                            </div>
                                <?php } ?>
								<?php
									$asus = new API("assure");
									$asus->setWhereArray(array("rcNo"=>$no));
									$asusData = $asus->getWithConditions();
									
									if($asusData != null){
										foreach($asusData as $key => $value){
											$sql = "SELECT * FROM `note_applier_details` WHERE `rcNo` = '".$no."' && ass_No = '".$value['assNo']."' order by convert(`nlNo`, decimal),`nadDate` asc";
											$nadData = $nad->customSql($sql);
								?>
									<div class="row" style="background-color:rgb(250,250,250);padding: 20px 50px;">
											<h6 class="">保證人照會</h6>
											<div class="row">
										<div class='input-field col s4'>
											<input type='text' readonly value="<?php echo $value['assAppApplierName']; ?>">
											<label class=''>保證人姓名</label>
										</div>
										<div class='input-field col s4'>
											<input type='text' readonly value="<?php echo $value['assAppApplierRelation']; ?>">
											<label class=''>關係</label>
										</div>
										<div class='input-field col s4'>
											<input type='text' readonly value="<?php echo $value['assAppApplierIdNum']; ?>">
											<label class=''>保證人身分證字號</label>
										</div>
										
										
									</div>
									<div class="row">
										<div class='input-field col s3'>
											<input type='text' readonly value="<?php echo $value['assAppApplierBday']; ?>">
											<label class=''>出生年月日(民國年)</label>
										</div>
										<div class='input-field col s3'>
											<input type='text' readonly value="<?php echo $value['assAppApplierBirthPhone']; ?>">
											<label class=''>戶籍電話</label>
										</div>
										<div class='input-field col s3'>
											<input type='text' readonly value="<?php echo $value['assAppApplierCurPhone']; ?>">
											<label class=''>現住電話</label>
										</div>
										<div class='input-field col s3'>
											<input type='text' readonly value="<?php echo $value['assAppApplierCurAddr']; ?>">
											<label class=''>現住地址</label>
										</div>	
									</div>
									<div class="row">
										<div class='input-field col s4'>
											<input type='text' readonly value="<?php echo $value['assAppApplierCompanyName']; ?>">
											<label class=''>公司名稱</label>
										</div>
										<div class='input-field col s4'>
											<input type='text' readonly value="<?php echo $value['assAppApplierCompanyPhone']; ?>">
											<label class=''>公司電話</label>
										</div>
										<div class='input-field col s4'>
											<input type='text' readonly value="<?php echo $value['assAppApplierCell']; ?>">
											<label class=''>行動電話</label>
										</div>
									</div>
									<table class="striped responsive-table">
	                                    <thead>
	                                        <tr>
	                                            <th style="width:150px">照會項目</th>
	                                            <th style="width:150px">狀況</th>
	                                            <th>備註</th>
	                                            <th style="width:150px">日期</th>
	                                        </tr>
	                                    </thead>
	                                    <tbody>
	                                    	<?php 
	                                    		foreach($nadData as $key=>$value){
													if($value['nlNo'] != ''){
	                                    			$nlData = $nl->getOne($value["nlNo"]);
	                                    			$ndcArr = json_decode($value["ndcNo"]);
	                                    			$ndcOption = "";
	                                    			if(is_array($ndcArr) && !empty(array_filter($ndcArr))){
	                                    				$arrKey = array_keys($ndcArr);
	                                    				$lastKey = array_pop($arrKey);
	                                    				foreach($ndcArr as $keyIn=>$valueIn){
	                                    					$ndcData = $ndc->getOne($valueIn);
	                                    					if($lastKey != $keyIn){
	                                    						$ndcOption .= $ndcData[0]["ndcOption"]."、";
	                                    					}else{
	                                    						$ndcOption .= $ndcData[0]["ndcOption"];
	                                    					}
	                                    				}
	                                    			}else if(is_numeric($value["ndcNo"])){
	                                    				$ndcData = $ndc->getOne($value["ndcNo"]);
	                                    				$ndcOption = $ndcData[0]["ndcOption"];
	                                    			}else{
	                                    				$ndcOption = "無";
	                                    			}
	                                    			if($ndcOption != "請選擇" || $value["nadExtraInfo"] != ""){
	                                    	?>
	                                        <tr>
	                                            <td class="nlName"><?php echo $nlData[0]["nlName"]; ?></td>
	                                            <td><?php echo $ndcOption != "請選擇" ? $ndcOption : ""; ?></td>
	                                            <td><?php echo $value["nadExtraInfo"]; ?></td>
	                                            <td><?php echo $value["nadDate"]; ?></td>
	                                        </tr>
	                                        <?php
													}
	                                    			} 
	                                    		}
	                                    	?>
	                                    </tbody>
	                                </table>
	                            </div>
                                <?php
										}
									} 
								
								?>
                                <?php if($ncdData != null){ ?>
                                <div class="row"  style="background-color:rgb(250,250,250);padding: 20px 50px;">
									<h6 class="">聯絡人照會</h6>
									<?php
										$total = count($contactNameArr)-1;
										for($i=0;$i<=$total;$i++){
									?>
									<div class="card" style="border:1px solid #AAA;">
										<div class="card-content">
											<span class="card-title">聯絡人</span>
											<div class="row" style="margin-bottom:20px;">
												<div class="input-field col s3">
													<input type="text" readonly value="<?php echo $contactNameArr[$i]; ?>">
													<label class="">聯絡人姓名</label>
												</div>
												<div class="input-field col s3">
													<input type="text" readonly value="<?php echo urldecode($contactRelaArr[$i]);?>">
													<label class="">聯絡人關係</label>
												</div>
												<div class="input-field col s3">
													<input type="text" readonly value="<?php echo $contactPhoneArr[$i]; ?>">
													<label class="">聯絡人市話</label>
												</div>
												<div class="input-field col s3">
													<input type="text" readonly value="<?php echo $contactCellArr[$i]; ?>">
													<label class="">聯絡人手機</label>
												</div>
											</div>
											<table class="striped responsive-table">
			                                    <thead>
			                                        <tr>
			                                            <th style="width:150px">照會項目</th>
			                                            <th style="width:150px">狀況</th>
			                                            <th>備註</th>
			                                            <th style="width:150px">日期</th>
			                                        </tr>
			                                    </thead>
			                                    <tbody>
			                                    	<?php 
			                                    	$ncd->setWhereArray(array("rcNo"=>$no,"ncdKey"=>$i));
													$curNcdData = $ncd->getWithConditions();
													if($curNcdData != null){
														foreach($curNcdData as $key=>$value){
				                                    	$nlData = $nl->getOne($value["nlNo"]);
				                                    	$ndcArr = json_decode($value["ndcNo"]);
				                                    	if(is_array($ndcArr)){
				                                    		$arrKey = array_keys($ndcArr);
				                                    		$lastKey = array_pop($arrKey);
				                                    		foreach($ndcArr as $keyIn=>$valueIn){
				                                    			$ndcData = $ndc->getOne($valueIn);
				                                    			if($lastKey != $keyIn){
				                                    				$ndcOption .= $ndcData[0]["ndcOption"]."、";
				                                    			}else{
				                                    				$ndcOption .= $ndcData[0]["ndcOption"];
				                                    			}
				                                    		}
				                                    	}else{
				                                    		$ndcData = $ndc->getOne($value["ndcNo"]);
				                                    		$ndcOption = $ndcData[0]["ndcOption"];
				                                    	}
				                                    	if($ndcOption != "請選擇" || $value["ncdExtraInfo"] != ""){
			                                    	?>
			                                        <tr>
			                                            <td class="nlName"><?php echo $nlData[0]["nlName"]; ?></td>
			                                            <td><?php echo $ndcOption != "請選擇" ? $ndcOption : ""; ?></td>
			                                            <td><?php echo $value["ncdExtraInfo"]; ?></td>
			                                            <td><?php echo $value["ncdDate"]; ?></td>
			                                        </tr>
				                                    <?php 
				                                    	}
														}
													}
													?>
			                                    </tbody>
			                                </table>
			                        	</div>
			                        </div>
								<?php
										}
								?>
								
                                <?php 
									
                                }
                                ?>
								</div>
                                <div class="row" style="background-color:rgb(250,250,250);padding: 20px 50px;">
	                                <div class="card" style="border:1px solid #AAA;">
	                                	<div class="row">
											<div class="input-field col s12">
												<input type="text" readonly value="<?php echo $rcData[0]["rcCreditComment"]; ?>">
												<label class="">徵信備註</label>
											</div>
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
				<div class="card">
					<div class="card-content">
						<span class="card-title">授信作業管理</span>
						<div class="row">
							<div style="display:none;" class="row">
								<div class="input-field col s6">
									<input type="text" id="cal1" name="rcJustInCaseRatio" data-val="<?php echo $rcData[0]["rcJustInCaseRatio"]; ?>" value="<?php echo $rcData[0]["rcJustInCaseRatio"]; ?>">
									<label class="">風險承擔倍率</label>
								</div>
								<div class="input-field col s6">
									<input type="text" id="cal2" name="rcProbationRatio" data-val="<?php echo $rcData[0]["rcProbationRatio"]; ?>" value="<?php echo $rcData[0]["rcProbationRatio"]; ?>">
									<label class="">保留款倍率</label>
								</div>
							</div>
							<?php if($rcData['0']['rcType'] == '0'){ ?>
							<div class="row">
								<div class="input-field col s4">
									<!-- <select name="rcPeriodAmount" id="period" > -->
										<!-- <option <?php echo $rcData[0]["rcPeriodAmount"] == "6" ? "selected" : ""; ?> value="6">6</option> -->
										<!-- <option <?php echo $rcData[0]["rcPeriodAmount"] == "9" ? "selected" : ""; ?> value="9">9</option> -->
										<!-- <option <?php echo $rcData[0]["rcPeriodAmount"] == "12" ? "selected" : ""; ?> value="12">12</option> -->
										<!-- <option <?php echo $rcData[0]["rcPeriodAmount"] == "15" ? "selected" : ""; ?> value="15">15</option> -->
										<!-- <option <?php echo $rcData[0]["rcPeriodAmount"] == "18" ? "selected" : ""; ?> value="18">18</option> -->
										<!-- <option <?php echo $rcData[0]["rcPeriodAmount"] == "21" ? "selected" : ""; ?> value="21">21</option> -->
										<!-- <option <?php echo $rcData[0]["rcPeriodAmount"] == "24" ? "selected" : ""; ?> value="24">24</option> -->
									<!-- </select> -->
									<input type="text" name="rcPeriodAmount" id="period" readonly value="<?php echo $rcData[0]["rcPeriodAmount"]; ?>">
									<label class="">分期期數</label>
								</div>
								<div class="input-field col s4">
									<input type="text" id="each-amount" name="eachAmount" data-val="<?php echo ($rcData[0]["rcPeriodTotal"]-($rcData[0]["rcBankRiskFeeMonth"]*$rcData[0]["rcPeriodAmount"])-$rcData[0]["rcBankRiskFeeTotal"])/$rcData[0]["rcPeriodAmount"]; ?>" value="<?php echo ($rcData[0]["rcPeriodTotal"]-($rcData[0]["rcBankRiskFeeMonth"]*$rcData[0]["rcPeriodAmount"])-$rcData[0]["rcBankRiskFeeTotal"])/$rcData[0]["rcPeriodAmount"]; ?>">
									<label class="">期付款</label>
								</div>
								<div class="input-field col s4">
									<input type="text" id="total" readonly value="<?php echo $rcData[0]["rcPeriodTotal"]; ?>">
									<label class="">申請總金額　　<button style="color:#FFF;background-color:#2ab7a9;" id="calculate-total" type="button">計算總金額</button></label>
								</div>
							</div>
							<div class="row">
								<div class="input-field col s3">
									<input type="text" id="riskMonth" name="rcBankRiskFeeMonth" data-val="<?php echo $rcData[0]["rcBankRiskFeeMonth"]; ?>" value="<?php echo $rcData[0]["rcBankRiskFeeMonth"]; ?>">
									<label class="">風管費(月累加)</label>
								</div>
								<div class="input-field col s3">
									<input type="text" id="riskTotal" name="rcBankRiskFeeTotal" data-val="<?php echo $rcData[0]["rcBankRiskFeeTotal"]; ?>" value="<?php echo $rcData[0]["rcBankRiskFeeTotal"]; ?>">
									<label class="">風管費(總額累加)</label>
								</div>
								<div class="input-field col s3">
<!-- 									<input type="text" name="rcBankTransferAmount" value="<?php echo $rcData[0]["rcBankTransferAmount"]; ?>"> -->
<!-- sander -->
									<input type="text" name="rcBankTransferAmount" value=
									    "<?php if ($rcData[0]["rcBankTransferAmount"] != 0){
									    	       echo $rcData[0]["rcBankTransferAmount"]; 
									    	    }else{
									    	    	if($orData[0]["orSupPrice"] == 0){
									    	    		echo floor($orData[0]["pmPeriodAmnt"] * 0.95);
									    	    	}else{
									    	    		echo floor($orData[0]["orSupPrice"]);
									    	    	}

									    	    }
									    ?>"
									>
									<label class="">撥款金額</label>
								</div>
								<div class="input-field col s3">
									<select name="tbNo">
										<option <?php echo $rcData[0]["tbNo"] == "" ? "selected" : ""; ?> value="">請選擇</option>
										<?php foreach($tbData as $key=>$value){?>
										<option <?php echo $rcData[0]["tbNo"] != "" && $rcData[0]["tbNo"] == $value["tbNo"] ? "selected" : ""; ?> value="<?php echo $value["tbNo"]; ?>"><?php echo $value["tbName"]; ?></option>
										<?php } ?>
									</select>
									<label class="">撥款銀行</label>
								</div>
							</div>
							<?php }else{ ?>
							<div class="row">
								<div class="input-field col s4">
									<input type="text" id="mcoPeriodAmount" name="rcPeriodAmount" value="<?php echo $motoData[0]["mcoPeriodAmount"]; ?>">
									<label class="">分期期數</label>
								</div>
								<div class="input-field col s4">
									<input type="text" id="dismcoMaxMonthlyTotal" name="dismcoMaxMonthlyTotal" value="<?php echo $motoData[0]["mcoMinMonthlyTotal"]; ?>" disabled>
									<input type="hidden" id="mcoMaxMonthlyTotal" name="mcoMinMonthlyTotal" value="<?php echo $motoData[0]["mcoMinMonthlyTotal"]; ?>">
									<label class="">期付款</label>
								</div>
								<div class="input-field col s4">
									<input type="text" id="mcoPeriodTotal" name="rcPeriodTotal" value="<?php echo $motoData[0]["mcoPeriodTotal"]; ?>">
									<label class="">申請總金額　　<button style="color:#FFF;background-color:#2ab7a9;" id="Mcocalculate-total" type="button">計算總金額</button></label>
								</div>
									<input type="hidden" id="mbMax_6" value="<?echo $rateData['0']['mbMin']; ?>">
									<input type="hidden" id="mbMax_12" value="<?echo $rateData['1']['mbMin']; ?>">
									<input type="hidden" id="mbMax_18" value="<?echo $rateData['2']['mbMin']; ?>">
									<input type="hidden" id="mbMax_24" value="<?echo $rateData['3']['mbMin']; ?>">
							</div>
							<div class="row">
								<div class="input-field col s6">
									<input type="text" name="rcBankTransferAmount" id="rcBankTransferAmount" readonly value="<?php echo ($rcData[0]["rcBankTransferAmount"] !="") ? $rcData[0]["rcBankTransferAmount"]:round($motoData[0]["mcoPeriodTotal"]*0.9); ?>">
									<label class="">撥款金額</label>
								</div>
								<div class="input-field col s6">
									<select name="tbNo">
										<option <?php echo $rcData[0]["tbNo"] == "" ? "selected" : ""; ?> value="">請選擇</option>
										<?php foreach($tbData as $key=>$value){?>
										<option <?php echo $rcData[0]["tbNo"] != "" && $rcData[0]["tbNo"] == $value["tbNo"] ? "selected" : ""; ?> value="<?php echo $value["tbNo"]; ?>"><?php echo $value["tbName"]; ?></option>
										<?php } ?>
									</select>
									<label class="">撥款銀行</label>
								</div>
							</div>
							
							<?php } ?>
							<div class="row">
								<div class="input-field col s4">
									<select name="rcStatus">
										<option <?php echo $rcData[0]["rcStatus"] == "2" ? "selected" : ""; ?> value="2">請選擇</option>
										<option <?php echo $rcData[0]["rcStatus"] == "3" ? "selected" : ""; ?> value="3">核准</option>
										<option <?php echo $rcData[0]["rcStatus"] == "5" ? "selected" : ""; ?> value="5">待核准</option>
										<option <?php echo $rcData[0]["rcStatus"] == "4" ? "selected" : ""; ?> value="4">婉拒</option>
										<option <?php echo $rcData[0]["rcStatus"] == "7" ? "selected" : ""; ?> value="7">取消訂單</option>
										<option <?php echo $rcData[0]["rcStatus"] == "701" ? "selected" : ""; ?> value="701">客戶自行撤件</option>
									</select>
									<label class="">徵信結果</label>
								</div>
								<div class="input-field col s4">
									<a type="button" href="#modal1" class="modal-trigger waves-effect waves-light btn green m-b-xs add-status-comment" <?php echo $rcData[0]["rcStatus"] != "2" ? "" : 'style="display:none;"'; ?>>增加狀態備註</a>
									<label class=""></label>
								</div>
							</div>
							<div id="reason-area-internal" <?php echo $rcData[0]["rcStatus"] != "2" ? "" : 'style="display:none;"'; ?>>
								<div class="row">
									<table class="striped responsive-table">
										<thead>
											<tr>
												<th></th>
												<th>編號</th>
												<th>狀態備註項目</th>
											<tr>
										</thead>
										<tbody>
											<?php 
											if($scData != null){
												if($scrData != null){
													$scNoArr = json_decode($scrData[0]["scNo"]);
												}
												foreach($scData as $key=>$value){
											?>
											<tr>
												<td style="text-align:center;"><input <?php echo (isset($scNoArr) && in_array($value["scNo"],$scNoArr)) ? "checked" : "" ; ?> name="scNo[]" value="<?php echo $value["scNo"]; ?>" type="checkbox" class="tableflat for-checked"></td>
												<td><?php echo $value["scNo"]; ?></td>
												<td><?php echo $value["scComment"]; ?></td>
											</tr>
											<?php 
												}
											}
											?>
										</tbody>
									</table>
								</div>
							</div>
							<div id="reason-area" <?php echo ($rcData[0]["rcStatus"] == "5" || $rcData[0]["rcStatus"] == "6") ? "" : "style='display:none;'"; ?> class="row">
								<div class="row">
									<div class="input-field col s12">
										<select name="rcDocProvideReason">
											<?php foreach($api->reasonArr as $key=>$value){?>
											<option <?php echo $rcData[0]["rcDocProvideReason"] == $key ? "selected" : ""; ?> value="<?php echo $key; ?>"><?php echo $value; ?></option>
											<?php } ?>
										</select>
										<label class="">需補件原因(客人看到的)</label>
									</div>
								</div>
								<div class="row">
									<div class="input-field col s12">
										<textarea class="materialize-textarea" name="rcDocProvideComment"><?php echo $rcData[0]["rcDocProvideComment"]; ?></textarea>
										<label class="">需補件備註(補件原因需選擇【自訂】，客人才能看到以下內容)</label>
									</div>
								</div>
							</div>
							<div class="row">
								<div class="input-field col s12">
									<input type="text" name="rcAuthenComment" value="<?php echo $rcData[0]["rcAuthenComment"]; ?>">
									<label class="">授信備註</label>
								</div>
							</div>
						</div>
					</div>
				</div>
				<div class="card">
					<div class="card-content">
						<span class="card-title">案件狀態紀錄</span>
						<div class="row">
							<table class="striped responsive-table">
								<thead>
									<tr>
										<th>編號</th>
										<th>使用者</th>
										<th>紀錄內容</th>
										<th>紀錄時間</th>
									</tr>
								</thead>
								<tbody>
									<?php 
									if($scrData != null){
										foreach($scrData as $key=>$value){
											$aauData = $aau->getOne($value["aauNo"]);
									?>
									<tr>
										<td><?php echo $key+1; ?></td>
										<td><?php echo $aauData[0]["aauName"]; ?></td>
										<td><?php echo $value["scrInfo"]; ?></td>
										<td><?php echo $value["scrDate"]; ?></td>
									</tr>
									<?php 
										}
									}	
									?>
								</tbody>
							</table>
						</div>
					</div>
				</div>
			</div>
			<div class="clear-fix"></div>
			<div class="row" style="text-align:center;">
				<a class="waves-effect waves-light btn green m-b-xs confirm-save">儲存</a>
				<a <?php echo ($rcData[0]["rcStatus"] != "3") ? 'style="display:none;"' : ""; ?> class="waves-effect waves-light btn green m-b-xs confirm-insert">儲存並標記已授信</a>
				<a class="waves-effect waves-light btn green m-b-xs confirm-refund">退回徵信</a>
			</div>
		</form>
		<?php }else{ ?>
		<div class="col s12">
			<div class="page-title"><?php echo $errMsg; ?></div>
		</div>
		<?php } ?>
	</div>
</main>
<div id="modal1" class="modal">
    <div class="modal-content">
		<div class="row">
			<div class="input-field col s12">
				<input type="text" id="scComment" value="<?php echo $rcData[0]["rcAuthenComment"]; ?>">
				<label class="">備註項目內容</label>
			</div>
		</div>
    </div>
    <div class="modal-footer">
        <a href="#!" class="modal-action modal-close waves-effect waves-green btn-flat ">關閉</a>
		<a style="margin:6px 30px;" class="waves-effect waves-green btn-flat confirm-insert-comment">確認新增</a>
    </div>
</div>
<script src="assets/js/pages/form_elements.js"></script>
<script>
$(function(){
	$(".credit_view").click(function(){
		$('.lean-overlay').hide();
		window.open('http://104.199.229.39/happyfan/v1/credit?action=query&rcCaseNo=<?php echo $rcData[0]["rcCaseNo"]; ?>', '徵信查詢', "toolbar=yes,scrollbars=yes,resizable=yes,left=650,width=950,height=600");
	})
	
	$(".status").hide();
	//增加狀態備註
	$(".confirm-insert-comment").click(function(){
		var form = {
					"rcNo":"<?php echo $no; ?>",
					"scStatusNo":$("select[name='rcStatus']").find("option:selected").val(),
					"scComment":$("#scComment").val()
				};
		var url = "ajax/authen/insert_status_comment.php";
		
		$.ajax({
			url:url,
			type:"post",
			data:form,
			datatype:"json",
			success:function(result){
				var results = JSON.parse(result);
				if(results.error == ""){
					getCommentData();
				}else{
					alert(results.error);
				}
			}
		});
	});
	
	
	//表格重整
	// $(".nlName").each(function(index){
		// if(index != 0){
			// var count = 1;
			// if($(".nlName").eq(index-1).text() == $(this).text()){
				// count++;
				// $(".nlName").eq(index-1).attr("rowspan",count);
				// $(".nlName").eq(index-1).css("background-color","#DDD");
				// $(this).addClass("for-remove");
			// }else{
				// count = 1;
			// }
		// }
	// });
	// $(".for-remove").remove();
	
	//案件狀態切換時要顯示/隱藏補件原因
	$("select[name='rcStatus']").on("change",function(){
		//核准才可以授信
		if($(this).find("option:selected").val() == 3){
			$(".confirm-insert").show();
		}else{
			$(".confirm-insert").hide();
		}
		
		//內部審查人員看得補件原因
		if($(this).find("option:selected").val() != 2){
			$(".add-status-comment").show();
			$("#reason-area-internal").show();
			getCommentData();
		}else{
			$(".add-status-comment").hide();
			$("#reason-area-internal").hide();
		}

		//給客人看得補件原因
		if($(this).find("option:selected").val() == 5){
			$("#reason-area").show();
		}else{
			$("#reason-area").hide();
		}
	});

	//計算分期總金額
	$("#calculate-total").click(function(){
		var period = $("#period").val();
		var eachAmount = $("#each-amount").val();
		var cal1 = $("#cal1").val();
		var cal2 = $("#cal2").val();
		var riskMonth = $("#riskMonth").val();
		var riskTotal = $("#riskTotal").val();
		
		var errMsg = "";
		if(!$.isNumeric(eachAmount) || eachAmount <= 0 || !(eachAmount % 1 === 0)){
			errMsg += "期付款必須為正整數\n";
			$("#each-amount").val($("#each-amount").data('val'));
		}else{
			$("#each-amount").data('val',$("#each-amount").val());
		}
		if(!$.isNumeric(riskMonth) || riskMonth < 0 || !(riskMonth % 1 === 0)){
			errMsg += "風管費(月累加)必須為正整數\n";
			$("#riskMonth").val($("#riskMonth").data('val'));
		}else{
			$("#riskMonth").data('val',$("#riskMonth").val());
		}
		if(!$.isNumeric(riskTotal) || riskTotal < 0 || !(riskTotal % 1 === 0)){
			errMsg += "風管費(總額累加)必須為正整數\n";
			$("#riskTotal").val($("#riskTotal").data('val'));
		}else{
			$("#riskTotal").data('val',$("#riskTotal").val());
		}
// 		if(!$.isNumeric(cal1) || cal1 < 0){
// 			errMsg += "風險承擔倍率必須為大於0的整數或小數\n";
// 			$("#cal1").val($("#cal1").data('val'));
// 		}else{
// 			$("#cal1").data('val',$("#cal1").val());
// 		}
// 		if(!$.isNumeric(cal2) || cal2 < 0){
// 			errMsg += "保留款倍率必須為大於0的整數或小數\n";
// 			$("#cal2").val($("#cal2").data('val'));
// 		}else{
// 			$("#cal2").data('val',$("#cal2").val());
// 		}
		if(errMsg != ""){
			alert(errMsg);
		}else{
// 			var total = Math.ceil(period*Math.ceil(eachAmount*cal1*cal2));
			var total = Math.ceil(period*Math.ceil(eachAmount)+period*riskMonth+parseInt(riskTotal));
			$("#total").val(total);
		}
	});
	
	$(".confirm-save").click(function(){
		if($("select[name='rcStatus'] option:selected").val() != 3 || ($("select[name='rcStatus'] option:selected").val() == 3 && window.confirm('您目前選擇核准，請再次確認是否要核准此案件？'))){
			if($("input[name='rcBankTransferAmount']").val() >= 10000 || $("input[name='rcBankTransferAmount']").val() == "" || window.confirm("您輸入的撥款金額小於10,000，請問是正確的嗎？")){
				var form = $("form").serialize();
				var url = "ajax/authen/insert_edit_comment.php";
				
				$.ajax({
					url:url,
					type:"post",
					data:form,
					datatype:"json",
					success:function(result){
						if(result.indexOf("OK") != -1){							
							window.open('view/print_report_export.php?no=<?php echo $no; ?>', '_blank');
							alert("儲存成功！");
							location.reload();
						}else{
							alert(result);
						}
					},
					beforeSend:function(){
						$('.status').show();
						$(".confirm-save").hide();
					}
				});
			}
		}
	});

	$(".confirm-insert").click(function(){
		if($("select[name='rcStatus'] option:selected").val() != 3 || ($("select[name='rcStatus'] option:selected").val() == 3 && window.confirm('您目前選擇核准，請再次確認是否要核准此案件？'))){
			if($("input[name='rcBankTransferAmount']").val() >= 10000 || $("input[name='rcBankTransferAmount']").val() == "" || window.confirm("您輸入的撥款金額小於10,000，請問是正確的嗎？")){
				var form = $("form").serialize();
				var url = "ajax/authen/insert_edit_comment_process.php";
				
				$.ajax({
					url:url,
					type:"post",
					data:form,
					datatype:"json",
					success:function(result){
						if(result.indexOf("OK") != -1){
							alert("儲存成功！");
							window.open('http://test.happyfan7.com/admin_advanced/view/print_report_export.php?no=<?php echo $no; ?>', '_blank');
							location.href = "?page=authen&type=view";
						}else{
							alert(result);
						}
					},
					beforeSend:function(){
						$('.status').show();
						$(".confirm-insert").hide();
					}
				});
			}
		}
	});

	$(".confirm-refund").click(function(){
		var form = $("form").serialize();
		var url = "ajax/authen/insert_edit_comment_refund.php";
		
		$.ajax({
			url:url,
			type:"post",
			data:form,
			datatype:"json",
			success:function(result){
				if(result.indexOf("OK") != -1){
					alert("儲存成功！");
					location.href = "?page=authen&type=view";
				}else{
					alert(result);
				}
			}
		});
	});
});

//計算期付金額
$("#Mcocalculate-total").click(function(){
	var number = $("#mcoPeriodAmount").val();
	if(number == "6" || number == "12" || number == "18" || number == "24"){
		var mbMax = $("#mbMax_"+number).val()/10000*$("#mcoPeriodTotal").val();
		$("#mcoMaxMonthlyTotal").val(Math.floor(mbMax));
		$("#dismcoMaxMonthlyTotal").val(Math.floor(mbMax));
		$("#rcBankTransferAmount").val(Math.round($("#mcoPeriodTotal").val()*0.9));
		alert("期付金額已改變");
	}else{
		alert("期數不正確，必須是6,12,18,24");
	}
});

function strip(number) {
    return (parseFloat(number).toPrecision(12));
}

function getCommentData(){
	var form = {
			"rcNo":"<?php echo $no; ?>",
			"scStatusNo":$("select[name='rcStatus']").find("option:selected").val()
		};
	$.ajax({
		url:"ajax/authen/get_status_comment.php",
		type:"post",
		data:form,
		datatype:"json",
		success:function(data){
			var datas = JSON.parse(data);
			$("#reason-area-internal table tbody").html('');
			$.each(datas, function(k,v){
				if(v.isChecked == "1"){
					var html = '<tr>'+
							'<td style="text-align:center;"><input checked name="scNo[]" value="'+v.scNo+'" type="checkbox" class="tableflat for-checked"></td>'+
							'<td>'+v.scNo+'</td>'+
							'<td>'+v.scComment+'</td>'+
						'</tr>';
				}else{
					var html = '<tr>'+
							'<td style="text-align:center;"><input name="scNo[]" value="'+v.scNo+'" type="checkbox" class="tableflat for-checked"></td>'+
							'<td>'+v.scNo+'</td>'+
							'<td>'+v.scComment+'</td>'+
						'</tr>';
				}
				$("#reason-area-internal table tbody").append(html);
			});
			 $('input.tableflat').iCheck({
			     checkboxClass: 'icheckbox_flat-green',
			     radioClass: 'iradio_flat-green'
			 });
			$(".modal-close").click();
		}
	});
}
</script>