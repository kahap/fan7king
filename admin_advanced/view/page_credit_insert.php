<?php 
if($_GET['item'] == "change"){
	$api = new API("real_cases");
	$or = new API("orders");
	
	$rcData = $api->getOne($no);
	
	$api->setWhereArray(array("rcRelateDataNo"=>$_GET['orderNO']));
	$newRcData = $api->getWithConditions();
	
	
	$coArray = array("rcNo"=>$no,"orNo_old"=>$rcData[0]["rcRelateDataNo"],"rcNo_new"=>$newRcData[0]['rcNo'],"orNo_new"=>$newRcData[0]['rcRelateDataNo']);	
	$co = new API("Change_Order");
	$co->insert($coArray);
	
	//更新目前的案件內的訂單編號,將舊的訂單取消掉
	$apiUpdate = array("supNo"=>$newRcData[0]['supNo'],"rcRelateDataNo"=>$newRcData[0]['rcRelateDataNo'],"rcPeriodTotal"=>$newRcData[0]['rcPeriodTotal'],"rcPeriodAmount"=>$newRcData[0]['rcPeriodAmount']);
	$NewrcUpdate = array("supNo"=>$rcData[0]["supNo"],"rcRelateDataNo"=>$rcData[0]["rcRelateDataNo"],"rcPeriodTotal"=>$rcData[0]['rcPeriodTotal'],"rcPeriodAmount"=>$rcData[0]['rcPeriodAmount']);
	
	
	$api->update($apiUpdate,$no);
	$AA = new API("real_cases");
	$AA->update($NewrcUpdate,$newRcData['0']['rcNo']);
	$AA->update(array("rcStatus"=>"7"),$newRcData['0']['rcNo']);
	$or->update(array("orStatus"=>"7"),$rcData['0']['rcRelateDataNo']);
	$or->update(array("orStatus"=>"2"),$newRcData['0']['rcRelateDataNo']);
	
	echo "<script>alert('更改訂單成功!')</script>";
	echo "<script>location.href ='?page=credit&type=insert&no=".$no."';</script>";
}


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
		
		
		$memData = $mem->getOne($rcData[0]["memNo"]);
		
		$asus = new API("assure");
		$asus->setWhereArray(array("rcNo"=>$no));
		$asusData = $asus->getWithConditions();
		
		$npData = $np->getAll();
		
		//先看是否已經有建立資料
		$nad->setWhereArray(array("rcNo"=>$no,"ass_No"=>"0"));
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
		$contactArr = json_decode($rcData[0]["rcContactName"]);
		$hasContact = false;
		$hasAssure = false;
		if(is_array($contactArr) && !empty(array_filter($contactArr))){
			$hasContact = true;
			$contactNameArr = json_decode($rcData[0]["rcContactName"]);
			$contactRelaArr = json_decode($rcData[0]["rcContactRelation"]);
			$contactPhoneArr = json_decode($rcData[0]["rcContactPhone"]);
			$contactCellArr = json_decode($rcData[0]["rcContactCell"]);
		}
		
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
		}else{
			$finalKey = count($contactArr)-1;
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
					<a href="?page=credit&type=view" role="menuitem" class="waves-effect waves-blue btn-flat">回去列表</a>
				</li>
				<li>
					<a type="button" href="#photos" class="modal-trigger waves-effect waves-light btn green m-b-xs look">查看證件上傳</a>
				</li>
				<li>
					<?php 
						switch($rcData[0]["rcType"]){
							case "0":
								?>
								<a target="_blank" href="../admin/view/print_order_details.php?orno=<?php echo $rcData[0]["rcRelateDataNo"]; ?>&rcNo=<?php echo $no; ?>&aauNoService=<?php echo $_SESSION['adminUserData']['aauNo']; ?>" class="modal-trigger waves-effect waves-light btn green m-b-xs print">列印</a>
								<?php 
								break;
							}
					?>
				</li>
			</ul>
		</div>
		<div class="col s12">
			<div class="page-title">徵信案件: <?php echo $rcData[0]["rcCaseNo"]; ?><span style="color:red;">　　(底下有紅色線為可編輯之欄位)</span></div>
		</div>
		<form id="credit_insert">
			<input type="hidden" name="rcNo" value="<?php echo $no; ?>">
			<div class="col s12 m12 l12">
				<div class="card">
					<div class="card-content">
						<a class="modal-trigger waves-effect waves-light btn blue m-b-xs" href="?page=credit&type=insert&no=<?php echo $rcData[0]['rcNo']; ?>&level=1" <?php echo ($_GET['level'] == 1) ? "disabled":"";?>>申請人徵信</a> 
						<a class="modal-trigger waves-effect waves-light btn blue m-b-xs credit_view" <?php echo ($_GET['level'] == 2) ? "disabled":"";?>>自動徵信資料表</a>
						<a class="modal-trigger waves-effect waves-light btn red m-b-xs credit_score">評分項目</a>
						<br>  
						<div class="row">
							<div class="row">
								<div class="input-field col s4">
									<input type="text" readonly name="memName" value="<?php echo $memData[0]["memName"]; ?>">
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
							<?php if($memData[0]["memClass"] == "0"){ ?>
							<div class="row">
								<?php 
								//if(strpos($memData[0]["memSchool"],"#") !== false){ 
									$schoolArr = explode("#", $memData[0]["memSchool"]);
								?>
								<div class="input-field col s4">
									<input type="text" readonly value="<?php echo $schoolArr[0]; ?>">
									<label class="">學校</label>
									<label id="schoolErr" class="error"></label>
								</div>
								<div class="input-field col s4">
									<input type="text" readonly value="<?php echo $schoolArr[1]; ?>">
									<label class="">系所</label>
									<label id="majorErr" class="error"></label>
								</div>
								<div class="input-field col s4">
									<input type="text" readonly value="<?php echo $schoolArr[2]; ?>">
									<label class="">年級</label>
									<label id="yearErr" class="error"></label>
								</div>
								<?php 
								//}
								?>
							</div>
							<?php } ?>
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
								<div class="input-field col s4">
									<input type="text" readonly value="<?php echo $memData[0]["memLineId"] ; ?>">
									<label class="">Line ID</label>
								</div>
								<div class="input-field col s4">
									<input type="text" readonly value="<?php echo ($rcData[0]["rcType"] == "0") ? $supData[0]["supName"]:""; ?>">
									<label class="">供應商</label>
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
							<?php if($memData[0]["memCompanyName"] != "" or $orData[0]["orAppApplierCompanyName"] != ""){?>
							<div class="row">
								<div class="input-field col s3">
									<input type="text" readonly value="<?php echo $orData[0]["orAppApplierCompanyName"] == "" ? $memData[0]["memCompanyName"] : $orData[0]["orAppApplierCompanyName"]; ?>">
									<label class="">公司名稱</label>
								</div>
								<div class="input-field col s3">
									<input type="text" readonly value="<?php echo $orData[0]["orAppApplierMonthSalary"] == "" ? $memData[0]["memYearWorked"] : $orData[0]["orAppApplierMonthSalary"]; ?>">
									<label class="">月收入</label>
								</div>
								<div class="input-field col s3">
									<input type="text" readonly value="<?php echo $orData[0]["orAppApplierYearExperience"] == "" ? $memData[0]["memSalary"] : $orData[0]["orAppApplierYearExperience"]; ?>">
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
									<select name="orProSpec">
										<?php
										$ProSpec = explode('#',$proData[0]['proSpec']);
										foreach($ProSpec as $key=>$value){
												$selected = ($value == $orData[0]["orProSpec"]) ? 'selected':'';
										?>
											<option value="<?php echo $value;?>" <?php echo $selected;?>><?php echo $value; ?></option>
										<?php } ?>
									</select> 
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
									<input type="text" name="mcoCellBrand" value='<?php echo $motoData[0]["mcoCellBrand"]; ?>'>
									<label class="">手機廠牌</label>  
								</div>
								<div class="input-field col s3">
									<input type="text" name="mcoCellphoneSpec" value='<?php echo $motoData[0]["mcoCellphoneSpec"]; ?>'>
									<label class="">手機型號</label>  
								</div>
							</div>
							<?php
									}else{
							?>
							<div class="row">
								<div class="input-field col s3">
									<input type="text" name="mcoMotorBrand" value='<?php echo $motoData[0]["mcoMotorBrand"]; ?>'>
									<label class="">機車廠牌</label>  
								</div>
								<div class="input-field col s3">
									<input type="text" name="mcoCcNumber" value='<?php echo $motoData[0]["mcoCcNumber"]; ?>'>
									<label class="">CC數</label>
								</div>
								<div class="input-field col s3">
									<input type="text" name="mcoCarNum" value='<?php echo $motoData[0]["mcoCarNum"]; ?>'>
									<label class="">車牌</label>
								</div>
								<div class="input-field col s3">
									<input type="text" name="mcoYear" value='<?php echo $motoData[0]["mcoYear"]; ?>'>
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
									<input type="text" readonly value="<?php echo $motoData[0]["mcoMinMonthlyTotal"]; ?>">
									<label class="">期付金額</label>
								</div>
							</div>
							<div class="row">
								<div class="input-field col s12">
									<input type="text" name="mcoApplyPurpose" value="<?php echo $motoData[0]["mcoApplyPurpose"]; ?>">
									<label class="">資金用途</label>
								</div>
							</div>							
							<?php
									
								}
							?>
							
							<div class="row">
								<div class="input-field col s12">
									<h5 class="">申請人GPS、簡訊、通訊錄詳細資料 <a target="_blank" href="https://www.facebook.com/<?php echo $memData[0]["memFBtoken"];?>" > FB 連結</a></h5>
									<a target="_blank" href="?page=member&type=view&no=<?php echo $rcData[0]["memNo"]; ?>">點我查看</a><br>
									是否來自APP：
									<font style="color:red">
									<?php
										if($rcData[0]["rcType"] == "0"){ echo ($orData[0]['orIpAddress'] != "") ? "否":"是"; }else{ echo "是";}
									?>
									</font>
								</div>
							</div>
							<?php
								$permision = json_decode($_SESSION["adminUserData"]["aarNo"]);
								if($permision[0] == "1"){
							?>
							
						</div>
					</div>
				</div>
			</div>
			<div class="col s12 m12 l12">
				<div class="card">
					<div class="card-content">
							<div class="row">
									<span class="card-title">使用換單功能</span>
									<h5 class="">於進件作業當中案件：</h5>
									<table style="margin-top:50px" class="striped responsive-table">
										<thead>
											<tr>
												<th>時間</th>
												<th>訂單編號</th>
												<th>商品名稱</th>
												<th>申貸金額</th>
												<th>期數</th>
												<th>進行動作</th>  
											</tr>
										</thead>
										<tbody>
										<?php
											foreach($orDataList as $key => $value){
												$pmDatahistory = $pm->getOne($value["pmNo"]);
												$proDatahistory = $pro->getOne($pmDatahistory[0]["proNo"]);
												if($value["orStatus"] == 1){
										?>
											<tr>
												<td><?php echo $value["orDate"]; ?></td>
												<td><?php echo $value["orCaseNo"]; ?></td>
												<td><?php echo $proDatahistory[0]["proName"];?></td>
												<td><?php echo $value["orPeriodTotal"];?></td>
												<td><?php echo $value["orPeriodAmnt"];?></td>
												<td><a href="?page=credit&type=insert&no=<?php echo $no?>&item=change&orderNO=<?php echo $value["orNo"] ?>">更換</a></td>
											</tr>
										<?php
												}
											}
										?>
										</tbody>
									</table>
									<font>*只要點選更換就能將新單的下單內容更換，本照會內容都會保留。</font><br>
									<font style="color: red;">要更換訂單前，請先將資料做儲存!</font>
								
							<?php
								}
							?>
						</div>
					</div>
				</div>
			</div>
			<div class="col s12 m12 l12">
				<div class="card">
					<div class="card-content">
						<span class="card-title">申請人徵信</span>
						<?php if($nadData == null){?>
						<div class="row">
							<?php 
							$nl->setWhereArray(array("npNo"=>1));
							$applierNlData = $nl->getWithConditions();
							foreach($applierNlData as $keyNl=>$valueNl){
								$ndc->setWhereArray(array("nlNo"=>$valueNl["nlNo"]));
								$ndcData = $ndc->getWithConditions();
							?>
							<div class="row">
								<?php if($valueNl["nlIfMultiple"] == "0"){?>
								<input type="hidden" name="applierNlNo[]" value="<?php echo $valueNl["nlNo"]; ?>">
								<div class="input-field col s3">
									<select name="applierNdcNo[]">
	                                    <?php 
	                                    foreach($ndcData as $keyNdc=>$valueNdc){
	                                    ?>
	                                    <option value="<?php echo $valueNdc["ndcNo"]; ?>"><?php echo $valueNdc["ndcOption"]; ?></option>
	                                    <?php 
	                                    }
	                                    ?>
	                                </select>
	                                <label class=""><?php echo $valueNl["nlName"]; ?></label>
	                            </div>
								<div class="input-field col s8">
									<input type="text" name="applierNadExtraInfo[]" value="">
									<label class="">備註</label>
								</div>
								<div class="input-field col s1">
									<a data-nlNo="<?php echo $valueNl["nlNo"]; ?>" data="0" href="#modal1" class="history-record-applier modal-trigger">歷史紀錄</a>
								</div>
								<?php }else{ ?>
								<div class="input-field col s12">
	                                <h5 class=""><?php echo $valueNl["nlName"]; ?></h5>
	                                <input type="hidden" name="nlNo<?php echo $valueNl["nlNo"]; ?>" value="<?php echo $valueNl["nlNo"]; ?>">
                                    <?php 
                                    foreach($ndcData as $keyNdc=>$valueNdc){
                                    ?>
                                    <input type="checkbox" <?php echo $valueNdc["ndcOption"] == "身份證" ? "checked" : "" ;?> class="tableflat" name="noteList<?php echo $valueNl["nlNo"]; ?>[]"  value="<?php echo $valueNdc["ndcNo"]; ?>">
                                    <?php echo $valueNdc["ndcOption"]; ?>
                                    <?php 
                                    }
                                    ?>
									<input type="text" name="nadComment<?php echo $valueNl["nlNo"]; ?>" placeholder="備註" value="">
	                            </div>
								<?php } ?>
							</div>
							<?php 
							}
							?>
						</div>
						<?php }else{ ?>
						<div class="row">
							<?php 
							$nl->setWhereArray(array("npNo"=>1));
							$applierNlData = $nl->getWithConditions();
							foreach($applierNlData as $keyNl=>$valueNl){
								$ndc->setWhereArray(array("nlNo"=>$valueNl["nlNo"]));
								$ndcData = $ndc->getWithConditions();
							?>
							<div class="row">
								<?php if($valueNl["nlIfMultiple"] == "0"){?>
								<input type="hidden" name="applierNlNo[]" value="<?php echo $valueNl["nlNo"]; ?>">
								<div class="input-field col s3">
									<input type="hidden" name="nadNo[]" value="<?php echo !empty(array_filter($oldDataArr)) && isset($oldDataArr[$valueNl["nlNo"]]) ? $oldDataArr[$valueNl["nlNo"]]["nadNo"] : ""; ?>">
									<select name="applierNdcNo[]">
	                                    <?php 
	                                    foreach($ndcData as $keyNdc=>$valueNdc){
	                                    ?>
	                                    <option <?php echo !empty(array_filter($oldDataArr)) && isset($oldDataArr[$valueNl["nlNo"]]) && $oldDataArr[$valueNl["nlNo"]]["ndcNo"] == $valueNdc["ndcNo"] ? "selected" : ""; ?> value="<?php echo $valueNdc["ndcNo"]; ?>"><?php echo $valueNdc["ndcOption"]; ?></option>
	                                    <?php 
	                                    }
	                                    ?>
	                                </select>
	                                <label class=""><?php echo $valueNl["nlName"]; ?></label>
	                            </div>
								<div class="input-field col s8">
									<input type="text" name="applierNadExtraInfo[]" value="<?php echo $oldDataArr[$valueNl["nlNo"]]["extra"]; ?>">
									<label class="">備註</label>
								</div>
								<div class="input-field col s1">
									<a data-nlNo="<?php echo $valueNl["nlNo"]; ?>" data="0" href="#modal1" class="history-record-applier modal-trigger">歷史紀錄</a>
								</div>
								<?php }else{ ?>
								<div class="input-field col s12">
	                                <h5 class=""><?php echo $valueNl["nlName"]; ?></h5>
	                                <input type="hidden" name="nlNo<?php echo $valueNl["nlNo"]; ?>" value="<?php echo $valueNl["nlNo"]; ?>">
	                                <input type="hidden" name="nadNo<?php echo $valueNl["nlNo"]; ?>" value="<?php echo $oldDataArr[$valueNl["nlNo"]]["nadNo"]; ?>">
                                    <?php 
                                    foreach($ndcData as $keyNdc=>$valueNdc){
                                    ?>
                                    <input <?php echo !empty(array_filter($oldDataArr)) && isset($oldDataArr[$valueNl["nlNo"]]) && in_array($valueNdc["ndcNo"],json_decode($oldDataArr[$valueNl["nlNo"]]["ndcNo"])) ? "checked" : ""; ?> type="checkbox" class="tableflat" name="noteList<?php echo $valueNl["nlNo"]; ?>[]"  value="<?php echo $valueNdc["ndcNo"]; ?>">
                                    <?php echo $valueNdc["ndcOption"]; ?>
                                    <?php 
                                    }
                                    ?>
									<input type="text" name="nadComment<?php echo $valueNl["nlNo"]; ?>" placeholder="備註" value="<?php echo $oldDataArr[$valueNl["nlNo"]]["extra"]; ?>">
	                            </div>
								<?php } ?>
							</div>
							<?php 
							}
							?>
						</div>
						<?php } ?>
					</div>
				</div>
				<div class="card">
					<div class="card-content">
						<span class="card-title">保證人徵信
						<a href="#add_people" class="modal-trigger waves-effect waves-light btn blue m-b-xs add_people">新增保證人</a></span>
						<?php
							if(count($asusData) > 0){
								foreach($asusData as $key => $value){
									//先看是否已經有建立資料
									$nad->setWhereArray(array("rcNo"=>$no,"ass_No"=>$value['assNo']));
									$nadData = $nad->getWithConditions();
									$oldDataArr = array();
									if($nadData != null){
										foreach($nadData as $k=>$v){
											if(!is_numeric($v["ndcNo"])){
												$curNdcData = $ndc->getOne(json_decode($v["ndcNo"])[0]);
											}else{
												$curNdcData = $ndc->getOne($v["ndcNo"]);
											}
											$oldDataArr[$v["nlNo"]]["nadNo"] = $v["nadNo"];
											$oldDataArr[$v["nlNo"]]["ndcNo"] = $v["ndcNo"];
											$oldDataArr[$v["nlNo"]]["extra"] = $v["nadExtraInfo"];
										}
									}
									
						?>
						<div class="row">
								<div class='input-field col s4'>
									<input type='text' name='assAppApplierName[<?php echo $value['assNo']; ?>]' value="<?php echo $value['assAppApplierName']; ?>">
									<label class=''>保證人姓名</label>
								</div>
								<div class='input-field col s4'>
									<input type='text' name='assAppApplierRelation[<?php echo $value['assNo']; ?>]' value="<?php echo $value['assAppApplierRelation']; ?>">
									<label class=''>關係</label>
								</div>
								<div class='input-field col s4'>
									<input type='text' name='assAppApplierIdNum[<?php echo $value['assNo']; ?>]' value="<?php echo $value['assAppApplierIdNum']; ?>">
									<label class=''>保證人身分證字號</label>
								</div>
								
								
							</div>
							<div class="row">
								<div class='input-field col s3'>
									<input type='text' name='assAppApplierBday[<?php echo $value['assNo']; ?>]' value="<?php echo $value['assAppApplierBday']; ?>">
									<label class=''>出生年月日(民國年)</label>
								</div>
								<div class='input-field col s3'>
									<input type='text' name='assAppApplierBirthPhone[<?php echo $value['assNo']; ?>]' value="<?php echo $value['assAppApplierBirthPhone']; ?>">
									<label class=''>戶籍電話</label>
								</div>
								<div class='input-field col s3'>
									<input type='text' name='assAppApplierCurPhone[<?php echo $value['assNo']; ?>]' value="<?php echo $value['assAppApplierCurPhone']; ?>">
									<label class=''>現住電話</label>
								</div>
								<div class='input-field col s3'>
									<input type='text' name='assAppApplierCurAddr[<?php echo $value['assNo']; ?>]' value="<?php echo $value['assAppApplierCurAddr']; ?>">
									<label class=''>現住地址</label>
								</div>	
							</div>
							<div class="row">
								<div class='input-field col s4'>
									<input type='text' name='assAppApplierCompanyName[<?php echo $value['assNo']; ?>]' value="<?php echo $value['assAppApplierCompanyName']; ?>">
									<label class=''>公司名稱</label>
								</div>
								<div class='input-field col s4'>
									<input type='text' name='assAppApplierCompanyPhone[<?php echo $value['assNo']; ?>]' value="<?php echo $value['assAppApplierCompanyPhone']; ?>">
									<label class=''>公司電話</label>
								</div>
								<div class='input-field col s4'>
									<input type='text' name='assAppApplierCell[<?php echo $value['assNo']; ?>]' value="<?php echo $value['assAppApplierCell']; ?>">
									<label class=''>行動電話</label>
								</div>
							</div>
								<div class="row">
									<?php 
										$nl->setWhereArray(array("npNo"=>1));
										$applierNlData = $nl->getWithConditions();
										foreach($applierNlData as $keyNl=>$valueNl){
											$ndc->setWhereArray(array("nlNo"=>$valueNl["nlNo"]));
											$ndcData = $ndc->getWithConditions();
									?>
									<div class="row">
										<?php if($valueNl["nlIfMultiple"] == "0"){?>
											<input type="hidden" name="applierNlNo_Ass[]" value="<?php echo $valueNl["nlNo"]; ?>">
											<div class="input-field col s3">
											<input type="hidden" name="nadNo_Ass[]" value="<?php echo !empty(array_filter($oldDataArr)) && isset($oldDataArr[$valueNl["nlNo"]]) ? $oldDataArr[$valueNl["nlNo"]]["nadNo"] : ""; ?>">
												<select name="applierNdcNoAss[<?php echo $value['assNo'];?>][]" class="select_nad" data="<?php echo $valueNl["nlNo"]; ?>">
													<?php 
														foreach($ndcData as $keyNdc=>$valueNdc){
													?>
														 <option <?php echo !empty(array_filter($oldDataArr)) && isset($oldDataArr[$valueNl["nlNo"]]) && $oldDataArr[$valueNl["nlNo"]]["ndcNo"] == $valueNdc["ndcNo"] ? "selected" : ""; ?> value="<?php echo $valueNdc["ndcNo"]; ?>"><?php echo $valueNdc["ndcOption"]; ?></option>
													<?php 
														}
													?>
												</select>
												<label class=""><?php echo $valueNl["nlName"]; ?></label>
											</div>
											<div class="input-field col s8">
												<input type="text" name="applierNadExtraInfo_Ass[<?php echo $value['assNo']; ?>][]" value="<?php echo $oldDataArr[$valueNl["nlNo"]]["extra"]; ?>">
												<label class="">備註</label>
											</div>
											<div class="input-field col s1">
												<a data-nlNo="<?php echo $valueNl["nlNo"]; ?>" data="<?php echo $value['assNo'];?>" href="#modal1" class="history-record-applier modal-trigger">歷史紀錄</a>
											</div>
										<?php 
											}
										?>
									</div>
									<?php 
										}
									?>
								</div>
								<input type="hidden" name="ass_No[]" value="<?php echo $value['assNo']; ?>">
							<?php
								
								}
							}
							?>
					</div>
				</div>
				<div class="card">
					<div class="card-content">
						<span class="card-title">聯絡人徵信 
						<a href="#contact_other" class="modal-trigger waves-effect waves-light btn blue m-b-xs add_contact">新增聯絡人</a></span>
						<?php 
						if($ncdData == null){
							if($hasContact){							
								foreach($contactArr as $key=>$value){
						?>
						<div class="row" style="border-bottom:3px dotted #CCC;">
							<div class="row">
								<div class="input-field col s3">
									<input type="text" name="rcContactName[]" value="<?php echo $contactNameArr[$key]; ?>">
									<label class="">聯絡人姓名</label>
								</div>
								<div class="input-field col s3">
									<select name="rcContactRelation[]">
										<?php foreach($api->relationArr as $keyRelation=>$valRelation){ ?>
										<option <?php echo $contactRelaArr[$key] == $valRelation ? "selected" : ""; ?> value="<?php echo $valRelation; ?>"><?php echo $valRelation; ?></option>
										<?php } ?>
									</select>
									<label class="">聯絡人關係</label>
								</div>
								<div class="input-field col s3">
									<input type="text" name="rcContactPhone[]" value="<?php echo $contactPhoneArr[$key]; ?>">
									<label class="">聯絡人市話</label>
								</div>
								<div class="input-field col s3">
									<input type="text" name="rcContactCell[]" value="<?php echo $contactCellArr[$key]; ?>">
									<label class="">聯絡人手機</label>
								</div>
							</div>
							<?php 
							$nl->setWhereArray(array("npNo"=>3));
							$contactNlData = $nl->getWithConditions();
							foreach($contactNlData as $keyNl=>$valueNl){
								$ndc->setWhereArray(array("nlNo"=>$valueNl["nlNo"]));
								$ndcData = $ndc->getWithConditions();
							?>
							<div class="row">
								<?php if($valueNl["nlIfMultiple"] == "0"){?>
								<div class="input-field col s3">
									<input type="hidden" name="contactKey[]" value="<?php echo $key; ?>">
									<input type="hidden" name="contactNlNo[]" value="<?php echo $valueNl["nlNo"]; ?>">
									<select name="contactNdcNo[]">
	                                    <?php 
	                                    foreach($ndcData as $keyNdc=>$valueNdc){
	                                    ?>
	                                    <option value="<?php echo $valueNdc["ndcNo"]; ?>"><?php echo $valueNdc["ndcOption"]; ?></option>
	                                    <?php 
	                                    }
	                                    ?>
	                                </select>
	                                <label class=""><?php echo $valueNl["nlName"]; ?></label>
	                            </div>
								<div class="input-field col s8">
									<input type="text" name="contactNcdExtraInfo[]" value="">
									<label class="">備註</label>
								</div>
								<div class="input-field col s1">
									<a data-key="<?php echo $key; ?>" data-nlNo="<?php echo $valueNl["nlNo"]; ?>" href="#modal1" class="history-record-contact modal-trigger">歷史紀錄</a>
								</div>
								<?php }else{ ?>
								<div class="input-field col s12">
	                                <h5 class=""><?php echo $valueNl["nlName"]; ?></h5>
                                    <?php 
                                    foreach($ndcData as $keyNdc=>$valueNdc){
                                    ?>
                                    <input type="checkbox" class="tableflat" name="noteList<?php echo $valueNl["nlNo"]; ?>[]"  value="<?php echo $valueNdc["ndcNo"]; ?>">
                                    <?php echo $valueNdc["ndcOption"]; ?>
                                    <?php 
                                    }
                                    ?>
	                            </div>
								<?php } ?>
							</div>
							<?php 
							}
							?>
						</div>
						<?php 
								}
							}
						}else{
								$total= count($contactArr)-1;
								for($i=0;$i<=$total;$i++){
						?>
						<div class="row" style="border-bottom:3px dotted #CCC;">
							<div class="row">
								<div class="input-field col s3">
									<input type="text" name="rcContactName[]" value="<?php echo $contactNameArr[$i]; ?>">
									<label class="">聯絡人姓名</label>
								</div>
								<div class="input-field col s3">
									<select name="rcContactRelation[]">
										<?php foreach($api->relationArr as $keyRelation=>$valRelation){ ?>
										<option <?php echo $contactRelaArr[$i] == $valRelation ? "selected" : ""; ?> value="<?php echo $valRelation; ?>"><?php echo $valRelation; ?></option>
										<?php } ?>
									</select>
									<label class="">聯絡人關係</label>
								</div>
								<div class="input-field col s3">
									<input type="text" name="rcContactPhone[]" value="<?php echo $contactPhoneArr[$i]; ?>">
									<label class="">聯絡人市話</label>
								</div>
								<div class="input-field col s3">
									<input type="text" name="rcContactCell[]" value="<?php echo $contactCellArr[$i]; ?>">
									<label class="">聯絡人手機</label>
								</div>
							</div>
						<?
									$nl->setWhereArray(array("npNo"=>3));
									$contactNlData = $nl->getWithConditions();
									foreach($contactNlData as $keyNl=>$valueNl){
										$ndc->setWhereArray(array("nlNo"=>$valueNl["nlNo"]));
										$ndcData = $ndc->getWithConditions();
									
						?>
							<div class="row">
								<div class="input-field col s3">
									<input type="hidden" name="contactKey[]" value="<?php echo $i; ?>">
									<input type="hidden" name="contactNcdNo[]" value="<?php echo $oldNcdData[$valueNl["nlNo"]]["ncdNo"][$i]; ?>">
									<input type="hidden" name="contactNlNo[]" value="<?php echo $valueNl["nlNo"]; ?>">
									<select name="contactNdcNo[]">
	                                    <?php
	                                    foreach($ndcData as $keyNdc=>$valueNdc){
	                                    ?>
	                                    <option <?php echo $oldNcdData[$valueNl["nlNo"]]["ndcNo"][$i] == $valueNdc["ndcNo"] ? "selected" : ""; ?> value="<?php echo $valueNdc["ndcNo"]; ?>"><?php echo $valueNdc["ndcOption"]; ?></option>
	                                    <?php 
	                                    }
	                                    ?>
	                                </select>
	                                <label class=""><?php echo $valueNl["nlName"]; ?></label>
	                            </div>
								<div class="input-field col s8">
									<input type="text" name="contactNcdExtraInfo[]" value="<?php echo $oldNcdData[$valueNl["nlNo"]]["extra"][$i]; ?>">
									<label class="">備註</label>
								</div>
								<div class="input-field col s1">
									<a data-key="<?php echo $i; ?>" data-nlNo="<?php echo $valueNl["nlNo"]; ?>" href="#modal1" class="history-record-contact modal-trigger">歷史紀錄</a>
								</div>
							</div>
							
						<?		
									}
								echo "</div>";
								}
						}		
						?>
					</div>
				</div>
				<div class="card">
					<div class="card-content">
						<span class="card-title">徵信備註</span>
						<div class="row">
							<div class="row">
								<div class="input-field col s12">
									<input type="text" name="rcCreditComment" value="<?php echo $rcData[0]["rcCreditComment"]; ?>">
									<label class="">徵信備註</label>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
			<div class="clear-fix"></div>
			<div class="row" style="text-align:center;">
				<a class="waves-effect waves-light btn green m-b-xs confirm-save">儲存</a>
				<a class="waves-effect waves-light btn green m-b-xs confirm-insert">儲存並送授信</a>
				<a class="waves-effect waves-light btn green m-b-xs confirm-refund">徵信退回</a>
			</div>
		</form>
		<?php }else{ ?>
		<div class="col s12">
			<div class="page-title"><?php echo $errMsg; ?></div>
		</div>
		<?php } ?>
	</div>
</main>
<div id="modal1" class="modal modal-fixed-footer">
    <div class="modal-content">
		<div class="card">
			<div class="card-content">
				<span class="card-title">徵信紀錄</span>
				<div class="row" id="records">
					<div class="row">
						<div class="input-field col s3">
							<input type="text" readonly value="">
							<label class="">選項</label>
						</div>
						<div class="input-field col s9">
							<input type="text" readonly value="">
							<label class="">備註</label>
						</div>
					</div>
				</div>
			</div>
		</div>
    </div>
    <div class="modal-footer">
        <a href="#!" class="modal-action modal-close waves-effect waves-green btn-flat ">關閉</a>
    </div>
</div>
<div id="contact_other" class="modal modal-fixed-footer">
	<div class="modal-content">
		<div class="row">
		<h5>案件編號：<?php echo $rcData['0']['rcCaseNo'];?>   申請人：<?php echo $memData[0]["memName"];?></h5>
			<form id="applyFile" action="ajax/credit/insert_contact.php" method="POST">
			<input type="hidden" name="rcNo" value="<?php echo $rcData[0]['rcNo']; ?>">
			<input type="hidden" name="finalKey" value="<?php echo $finalKey; ?>">
			<div class='input-field col s3'><input type='text' name='rcContactName[]'>
				<label class=''>聯絡人姓名</label>
			</div>
			<div class='input-field col s3'>
				<select name='rcContactRelation[]'>
				<?php foreach($api->relationArr as $keyRelation=>$valRelation){ ?>
					<option value='<?php echo $valRelation; ?>'>
					<?php echo $valRelation; ?></option><?php } ?></select>
				<label class=''>聯絡人關係</label>
			</div>
			<div class='input-field col s3'>
				<input type='text' name='rcContactPhone[]' value=''>
				<label class=''>聯絡人市話</label>
			</div>
			<div class='input-field col s3'>
				<input type='text' name='rcContactCell[]' value=''>
				<label class=''>聯絡人手機</label>
			</div>
		</div>
		<div class="row">
				<div class="input-field col s3">
					<select id="contactNdcNoA">
	                    <?php
							$ndc->setWhereArray(array("nlNo"=>"25"));
							$ndcData = $ndc->getWithConditions();
	                        foreach($ndcData as $keyNdc=>$valueNdc){
	                    ?>
	                        <option value="<?php echo $valueNdc["ndcNo"]; ?>"><?php echo $valueNdc["ndcOption"]; ?></option>
	                    <?php 
	                        }
						?>
	                </select>
	                <label class="">市話照會</label>
					<input type="hidden" name="contactNlNo[]" value="25"/>
					<input type="hidden" name="contactNdcNoA" id="contactNdcNoA_hidden" value=""/>
	            </div>
				<div class="input-field col s8">
					<input type="text" name="contactNcdExtraInfo[]" value="">
					<label class="">備註</label>
				</div>
		</div>
		<div class="row">
				<div class="input-field col s3">
					<select id="contactNdcNoB" >
	                    <?php
							$ndc->setWhereArray(array("nlNo"=>"26"));
							$ndcData = $ndc->getWithConditions();
	                        foreach($ndcData as $keyNdc=>$valueNdc){
	                    ?>
	                        <option value="<?php echo $valueNdc["ndcNo"]; ?>"><?php echo $valueNdc["ndcOption"]; ?></option>
	                    <?php 
	                        }
						?>
	                </select>
	                <label class="">手機照會</label>
					<input type="hidden" name="contactNlNo[]" value="26"/>
					<input type="hidden" name="contactNdcNoB" id="contactNdcNoB_hidden" value=""/>
	            </div>
				<div class="input-field col s8">
					<input type="text" name="contactNcdExtraInfo[]" value="">
					<label class="">備註</label>
				</div>
		</div>		
		</form>
		
	</div>
	<div class="modal-footer">
        <a href="#!" class="modal-action modal-close waves-effect waves-green btn-flat ">關閉</a>
		<a href="#!" class="modal-action waves-effect waves-green btn-flat modal-save">儲存</a>
    </div>
</div>
<div id="add_people" class="modal modal-fixed-footer">
	<div class="modal-content">
		<div class="row">
		<h5>案件編號：<?php echo $rcData['0']['rcCaseNo'];?>   申請人：<?php echo $memData[0]["memName"];?></h5>
			<form id="assAppApplier" action="" method="POST">
			<input type="hidden" name="rcNo" value="<?php echo $rcData[0]['rcNo']; ?>">
			<input type="hidden" name="finalKey" value="<?php echo $finalKey; ?>">
			<div class='input-field col s4'>
				<input type='text' name='assAppApplierName'>
				<label class=''>保證人姓名</label>
			</div>
			<div class='input-field col s4'>
				<input type='text' name='assAppApplierRelation'>
				<label class=''>關係</label>
			</div>
			<div class='input-field col s4'>
				<input type='text' name='assAppApplierIdNum'>
				<label class=''>保證人身分證字號</label>
			</div>
			
			
		</div>
		<div class="row">
			<div class='input-field col s3'>
				<input type='text' name='assAppApplierBday' value=''>
				<label class=''>出生年月日(民國年)</label>
			</div>
			<div class='input-field col s3'>
				<input type='text' name='assAppApplierBirthPhone' value=''>
				<label class=''>戶籍電話</label>
			</div>
			<div class='input-field col s3'>
				<input type='text' name='assAppApplierCurPhone' value=''>
				<label class=''>現住電話</label>
			</div>
			<div class='input-field col s3'>
				<input type='text' name='assAppApplierCurAddr' value=''>
				<label class=''>現住地址</label>
			</div>	
		</div>
		<div class="row">
			<div class='input-field col s4'>
				<input type='text' name='assAppApplierCompanyName' value=''>
				<label class=''>公司名稱</label>
			</div>
			<div class='input-field col s4'>
				<input type='text' name='assAppApplierCompanyPhone' value=''>
				<label class=''>公司電話</label>
			</div>
			<div class='input-field col s4'>
				<input type='text' name='assAppApplierCell' value=''>
				<label class=''>行動電話</label>
			</div>
		</div>
			<div class="row">
				<?php 
					$nl->setWhereArray(array("npNo"=>1));
					$applierNlData = $nl->getWithConditions();
					foreach($applierNlData as $keyNl=>$valueNl){
						$ndc->setWhereArray(array("nlNo"=>$valueNl["nlNo"]));
						$ndcData = $ndc->getWithConditions();
				?>
				<div class="row">
					<?php if($valueNl["nlIfMultiple"] == "0"){?>
						<input type="hidden" name="applierNlNo[]" value="<?php echo $valueNl["nlNo"]; ?>">
						<div class="input-field col s3">
						<input type="hidden" name="nadNo[]" value="">
							<select name="applierNdcNo[]" class="select_nad" data="<?php echo $valueNl["nlNo"]; ?>">
	                            <?php 
	                                foreach($ndcData as $keyNdc=>$valueNdc){
	                            ?>
									<option value="<?php echo $valueNdc["ndcNo"]; ?>"><?php echo $valueNdc["ndcOption"]; ?></option>
	                            <?php 
	                                }
	                            ?>
	                        </select>
	                        <label class=""><?php echo $valueNl["nlName"]; ?></label>
							<input type="hidden" name="applierNdcNo[]" id="nad_<?php echo $valueNl["nlNo"]; ?>" value="">
	                    </div>
						<div class="input-field col s8">
							<input type="text" name="applierNadExtraInfo[]" value="">
							<label class="">備註</label>
						</div>
					<?php }/*else{ ?>
						<div class="input-field col s12">
	                        <h5 class=""><?php echo $valueNl["nlName"]; ?></h5>
	                        <input type="hidden" name="nlNo<?php echo $valueNl["nlNo"]; ?>" value="<?php echo $valueNl["nlNo"]; ?>">
	                        <input type="hidden" name="nadNo<?php echo $valueNl["nlNo"]; ?>" value="">
                            <?php 
								foreach($ndcData as $keyNdc=>$valueNdc){
                            ?>
                                <input type="checkbox" class="tableflat" name="noteList<?php echo $valueNl["nlNo"]; ?>[]"  value="<?php echo $valueNdc["ndcNo"]; ?>">
                                <?php echo $valueNdc["ndcOption"]; ?>
                            <?php 
                                }
                            ?>
								<input type="text" name="nadComment<?php echo $valueNl["nlNo"]; ?>" placeholder="備註" value="">
	                     </div>
					<?php }*/   ?>
				</div>
				<?php 
					}
				?>
			</div>
		</form>
		
	</div>
	<div class="modal-footer">
        <a href="#!" class="modal-action modal-close waves-effect waves-green btn-flat ">關閉</a>
		<a href="#!" class="modal-action waves-effect waves-green btn-flat Applier-save">儲存</a>
    </div>
</div>

<script src="assets/js/pages/form_elements.js"></script>
<script>
$(function(){
	$(".status").hide();
	//申請人 歷史紀錄
	$(document).on("click",".history-record-applier",function(){
		var form = {"rcNo":"<?php echo $no; ?>","nlNo":$(this).data("nlno"),"type":"applier","ass_No":$(this).attr("data")};
		var url = "ajax/credit/get_history.php";

		$.ajax({
			url:url,
			type:"post",
			data:form,
			datatype:"json",
			success:function(result){
				$("#records").text("");
// 				alert(result);
				if(result != "empty"){
					var results = JSON.parse(result);
					var html = '<table class="striped responsive-table">';
					$.each(results,function(k,v){
						html += '<tr><td>'+v.ndcNo+'</td><td>'+v.nadExtraInfo+'</td><td>'+v.nadDate+'</td></tr>'
					});
					html += '</table>'
					$("#records").html(html);
				}else{
					$("#records").text("尚無任何紀錄");
				}
			}
		});
	});

	//聯絡人 歷史紀錄
	$(document).on("click",".history-record-contact",function(){
		var form = {"rcNo":"<?php echo $no; ?>","nlNo":$(this).data("nlno"),"ncdKey":$(this).data("key"),"type":"contact"};
		var url = "ajax/credit/get_history.php";

		$.ajax({
			url:url,
			type:"post",
			data:form,
			datatype:"json",
			success:function(result){
				$("#records").text("");
// 				alert(result);
				if(result != "empty"){
					var results = JSON.parse(result);
					var html = '<table class="striped responsive-table">';
					$.each(results,function(k,v){
						html += '<tr><td>'+v.ndcNo+'</td><td>'+v.ncdExtraInfo+'</td><td>'+v.ncdDate+'</td></tr>'
					});
					html += '</table>'
					$("#records").html(html);
				}else{
					$("#records").text("尚無任何紀錄");
				}
			}
		});
	});
	
	$(".confirm-save").click(function(){
		var form = $("#credit_insert").serialize();
		var url = "ajax/credit/insert_edit_comment.php";
		
		$.ajax({
			url:url,
			type:"post",
			data:form,
			datatype:"json",
			success:function(result){
				if(result.indexOf("OK") != -1){
					alert("儲存成功！");
					location.reload();
				}else{
					alert(result);
				}
			},
			beforeSend:function(){
				//$('.status').show();
				$(".confirm-save").hide();
			}
		});
	});

	$(".confirm-insert").click(function(){
		var form = $("#credit_insert").serialize();
		var url = "ajax/credit/insert_edit_comment_process.php";
		
		$.ajax({
			url:url,
			type:"post",
			data:form,
			datatype:"json",
			success:function(result){
				if(result.indexOf("OK") != -1){
					alert("儲存成功！");
					location.href = "?page=credit&type=view";
				}else{
					var results = JSON.parse(result);
					var err = "";
					$.each(results, function(k,v){
						err += (k+1)+". "+v;
						err += "<br>";
					});
					Materialize.toast(err, 4000);
				}
			},
			beforeSend:function(){
				//$('.status').show();
				$(".confirm-insert").hide();
			}
		});
	});

	$(".confirm-refund").click(function(){
		var form = $("#credit_insert").serialize();
		var url = "ajax/credit/insert_edit_comment_refund.php";
		
		$.ajax({
			url:url,
			type:"post",
			data:form,
			datatype:"json",
			success:function(result){
				if(result.indexOf("OK") != -1){
					alert("儲存成功！");
					location.href = "?page=credit&type=view";
				}else{
					alert(result);
				}
			}
		});
	});
	
	$(".credit_view").click(function(){
		$('.lean-overlay').hide();
		window.open('http://104.199.229.39/happyfan/v1/credit?action=query&rcCaseNo=<?php echo $rcData[0]["rcCaseNo"]; ?>', '徵信查詢', "toolbar=yes,scrollbars=yes,resizable=yes,left=650,width=950,height=600");
	})
	
	$(".credit_score").click(function(){
		$('.lean-overlay').hide();
		window.open('http://104.199.229.39/happyfan/v1/score-card/detail-page?rcCaseNo=<?php echo $rcData[0]["rcCaseNo"]; ?>', '自動評分項目', "toolbar=yes,scrollbars=yes,resizable=yes,left=650,width=950,height=600");
	})
	
	$("#contactNdcNoA").change(function(){
		$("#contactNdcNoA_hidden").val($("#contactNdcNoA").val());
	});
	
	$("#contactNdcNoB").change(function(){
		$("#contactNdcNoB_hidden").val($("#contactNdcNoB").val());
	});
	
	$(".modal-save").click(function(){
		var form = $("#credit_insert").serialize();
		var url = "ajax/credit/insert_edit_comment.php";
		
		$.ajax({
			url:url,
			type:"post",
			data:form,
			datatype:"json",
			success:function(result){
				if(result.indexOf("OK") != -1){
					alert("儲存成功！");
					$('#applyFile').submit();
				}else{
					alert(result);
				}
			},
			beforeSend:function(){
				//$('.status').show();
				$(".confirm-save").hide();
			}
		});
	})
	
	$(".Applier-save").click(function(){
		var form = $("#assAppApplier").serialize();
		var url = "ajax/credit/insert_assAppApplier.php";
		
		$.ajax({
			url:url,
			type:"post",
			data:form,
			datatype:"json",
			success:function(result){
				if(result.indexOf("OK") != -1){
					alert("新增保證人成功");
					var form = $("#credit_insert").serialize();
					var url = "ajax/credit/insert_edit_comment.php";
					
					$.ajax({
						url:url,
						type:"post",
						data:form,
						datatype:"json",
						success:function(result){
							if(result.indexOf("OK") != -1){
								alert("儲存成功！");
								location.href = "?page=credit&type=insert&no=<?php echo $_GET["no"];?>";
							}else{
								alert(result);
							}
						},
						beforeSend:function(){
							//$('.status').show();
							$(".confirm-save").hide();
						}
					});
				}else{
					alert(result);
				}
			}
		});
	})
	
	$(".select_nad").change(function(){
		var id = $(this).attr("data");
		$("#nad_"+id).val($(this).val());
	})
});

$('.look').click(function(){
	$.ajax({
        type: "post",
        url: "http://api.21-finance.com/api/ViewDocument",
        data: { AID: "<?echo $rcData[0]["rcCaseNo"]; ?>", Name: "<?echo $_SESSION['adminUserData']['aauName']; ?>", Page: "徵信作業", Btn: "查看證件上傳", Ip: "<?echo $_SERVER["REMOTE_ADDR"]; ?>", LookDate: "<?echo date("Y-m-d H:i:s"); ?>" },
        success: function (data, status) {}
    });
});

 $('.print').click(function(){
	$.ajax({
        type: "post",
        url: "http://api.21-finance.com/api/ViewDocument",
        data: { AID: "<?echo $rcData[0]["rcCaseNo"]; ?>", Name: "<?echo $_SESSION['adminUserData']['aauName']; ?>", Page: "徵信作業", Btn: "列印", Ip: "<?echo $_SERVER["REMOTE_ADDR"]; ?>", LookDate: "<?echo date("Y-m-d H:i:s"); ?>" },
        success: function (data, status) {}
    });
});
</script>