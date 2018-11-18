<?php 

if(isset($no)){
	$no = $_GET["no"];
	
	$api = new API("real_cases");
	$apiMem = new API("member");
	$rcData = $api->getOne($no);
	if($rcData != null){
		// if($rcData[0]["rcStatus"] == "1" || $rcData[0]["rcStatus"] == "6"){
			$sc = new API("status_comment");
			$scr = new API("status_comment_records");
			$aau = new API("admin_advanced_user");
			$sup = new API("supplier");
			$pro = new API("product");
			$pm = new API("product_manage");
			$service = new API("service_record");
			$sf = new API("servicefixed");
			
			
			$rcExtraInfoUploadArr = json_decode($rcData[0]["rcExtraInfoUpload"]);
			$memData = $apiMem->getOne($rcData[0]["memNo"]);
			
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
				$supData = $sup->getOne($rcData[0]["supNo"]);
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
			
			
			
			//取得所有印件人員資料
			$service->setWhereArray(array("rcNo"=>$no));
			$serviceData = $service->getWithConditions();
			
			//聯絡人陣列
			$relaNameArr = is_array(json_decode($orData[0]["orAppContactRelaName"])) ? json_decode($orData[0]["orAppContactRelaName"]) : "";
			$relaRelationArr = is_array(json_decode($orData[0]["orAppContactRelaRelation"])) ? json_decode($orData[0]["orAppContactRelaRelation"]) : "";
			$relaPhoneArr = is_array(json_decode($orData[0]["orAppContactRelaPhone"])) ? json_decode($orData[0]["orAppContactRelaPhone"]) : "";
			$relaCellArr = is_array(json_decode($orData[0]["orAppContactRelaCell"])) ? json_decode($orData[0]["orAppContactRelaCell"]) : "";
			
			$frdNameArr = is_array(json_decode($orData[0]["orAppContactFrdName"])) ? json_decode($orData[0]["orAppContactFrdName"]) : "";
			$frdRelationArr = is_array(json_decode($orData[0]["orAppContactFrdRelation"])) ? json_decode($orData[0]["orAppContactFrdRelation"]) : "";
			$frdPhoneArr = is_array(json_decode($orData[0]["orAppContactFrdPhone"])) ? json_decode($orData[0]["orAppContactFrdPhone"]) : "";
			$frdCellArr = is_array(json_decode($orData[0]["orAppContactFrdCell"])) ? json_decode($orData[0]["orAppContactFrdCell"]) : "";
		// }else{
			// $errMsg = "此案件已完成進件。";
		// }
		
	}else{
		$errMsg = "查無此訂單。";
	}
}else{
	$errMsg = "錯誤的頁面導向。";
}

?>
<style>
.actions ul li{
	float:left;
}
.btn{
	margin:10px;
}
.each-img .id-pic{
	max-width:80%;
}
table{
	border:1px solid #AAA;
}
table tr td,table tr th{
	border-left:1px solid #AAA;
	border-right:1px solid #AAA;
    border-bottom: 1px solid #AAA;
}
</style>
<div id="modal1" class="modal modal-fixed-footer">
	
	<form id="applyFile" action="ajax/case_process/edit_case_file.php" method="POST" enctype="multipart/form-data">
    <div class="modal-content">
    	<?php if(!empty($rcData[0]["rcIdImgTop"])){ ?>
		<h4>申請人身分證正面照片</h4>
		<div class="file-field input-field">
            <div class="btn teal lighten-1">
                <span>身分證正面照片</span>
                <input type="file" name="rcIdImgTop">
            </div>
            <div class="file-path-wrapper">
                <input class="file-path validate" type="text" value="<?php echo $rcData[0]["rcIdImgTop"];?>">
            </div>
        </div>
		<div class="each-img">
			<img class="id-pic" src="<?php echo IMG_ROOT.$rcData[0]["rcIdImgTop"]; ?>">
		</div>
		<?php } ?>
		<?php if(!empty($rcData[0]["rcIdImgBot"])){ ?>
		<h4>申請人身分證反面照片</h4>
		<div class="file-field input-field">
            <div class="btn teal lighten-1">
                <span>身分證反面照片</span>
                <input type="file" name="rcIdImgBot">
            </div>
            <div class="file-path-wrapper">
                <input class="file-path validate" type="text" value="<?php echo $rcData[0]["rcIdImgBot"];?>">
            </div>
        </div>
		<div class="each-img">
			<img class="id-pic" src="<?php echo IMG_ROOT.$rcData[0]["rcIdImgBot"]; ?>">
		</div>
		<?php } ?>
		<?php if(!empty($rcData[0]["rcStudentIdImgTop"])){ ?>
		<h4>申請人學生證正面照片</h4>
		<div class="file-field input-field">
            <div class="btn teal lighten-1">
                <span>學生證正面照片</span>
                <input type="file" name="rcStudentIdImgTop">
            </div>
            <div class="file-path-wrapper">
                <input class="file-path validate" type="text" value="<?php echo $rcData[0]["rcStudentIdImgTop"];?>">
            </div>
        </div>
		<div class="each-img">
			<img class="id-pic" src="<?php echo IMG_ROOT.$rcData[0]["rcStudentIdImgTop"]; ?>">
		</div>
		<?php } ?>
		<?php if(!empty($rcData[0]["rcStudentIdImgBot"])){ ?>
		<h4>申請人學生證反面照片</h4>
		<div class="file-field input-field">
            <div class="btn teal lighten-1">
                <span>學生證反面照片</span>
                <input type="file" name="rcStudentIdImgBot">
            </div>
            <div class="file-path-wrapper">
                <input class="file-path validate" type="text" value="<?php echo $rcData[0]["rcStudentIdImgBot"];?>">
            </div>
        </div>
		<div class="each-img">
			<img class="id-pic" src="<?php echo IMG_ROOT.$rcData[0]["rcStudentIdImgBot"]; ?>">
		</div>
		<?php } ?>
		<?php if(!empty($rcData[0]["rcSubIdImgTop"])){ ?>
		<h4>申請人健保卡正面照片</h4>
		<div class="file-field input-field">
            <div class="btn teal lighten-1">
                <span>健保卡正面照片</span>
                <input type="file" name="rcSubIdImgTop">
            </div>
            <div class="file-path-wrapper">
                <input class="file-path validate" type="text" value="<?php echo $rcData[0]["rcSubIdImgTop"];?>">
            </div>
        </div>
		<div class="each-img">
			<img class="id-pic" src="<?php echo IMG_ROOT.$rcData[0]["rcSubIdImgTop"]; ?>">
		</div>
		<?php } ?>
		<?php if(!empty($rcData[0]["rcCarIdImgTop"])){ ?>
		<h4>申請人行照正面照片</h4>
		<div class="file-field input-field">
            <div class="btn teal lighten-1">
                <span>行照正面照片</span>
                <input type="file" name="rcCarIdImgTop">
            </div>
            <div class="file-path-wrapper">
                <input class="file-path validate" type="text" value="<?php echo $rcData[0]["rcCarIdImgTop"];?>">
            </div>
        </div>
		<div class="each-img">
			<img class="id-pic" src="<?php echo IMG_ROOT.$rcData[0]["rcCarIdImgTop"]; ?>">
		</div>
		<?php } ?>
		<?php if(!empty($rcData[0]["rcBankBookImgTop"])){ ?>
		<h4>申請人存摺正面照片</h4>
		<div class="file-field input-field">
            <div class="btn teal lighten-1">
                <span>存摺正面照片</span>
                <input type="file" name="rcBankBookImgTop">
            </div>
            <div class="file-path-wrapper">
                <input class="file-path validate" type="text" value="<?php echo $rcData[0]["rcBankBookImgTop"];?>">
            </div>
        </div>
		<div class="each-img">
			<img class="id-pic" src="<?php echo IMG_ROOT.$rcData[0]["rcBankBookImgTop"]; ?>">
		</div>
		<?php } ?>
		<?php if(!empty($rcData[0]["rcRecentTransactionImgTop"])){ ?>
		<h4>申請人近6個月薪資往來照片</h4>
		<div class="file-field input-field">
            <div class="btn teal lighten-1">
                <span>近6個月薪資往來照片</span>
                <input type="file" name="rcRecentTransactionImgTop">
            </div>
            <div class="file-path-wrapper">
                <input class="file-path validate" type="text" value="<?php echo $rcData[0]["rcRecentTransactionImgTop"];?>">
            </div>
        </div>
		<div class="each-img">
			<img class="id-pic" src="<?php echo IMG_ROOT.$rcData[0]["rcRecentTransactionImgTop"]; ?>">
		</div>
		<?php } ?>
		
		<div class="file-field input-field">
            <div class="btn teal lighten-1">
                <span>補件資料照片</span>
                <input type="file" name="rcExtraInfoUpload[]" multiple>
            </div>
            <div class="file-path-wrapper">
                <input class="file-path validate" type="text" value="">
            </div>
        </div>
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
	<input type="hidden" name="rcNo" value="<?php echo $rcData[0]['rcNo'];?>"/>
    <div class="modal-footer">
        <a href="#!" class="modal-action modal-close waves-effect waves-green btn-flat ">關閉</a>
		<a href="#!" class="modal-action waves-effect waves-green btn-flat modal-save">儲存</a>
    </div>
	</form>
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
					<a href="?page=case" role="menuitem" class="waves-effect waves-blue btn-flat">回去列表</a>
				</li>
			</ul>
		</div>
		<div class="col s12">
			<div class="page-title">
				訂單編號: <?php echo ($rcData[0]["rcType"] == "0") ? $orData[0]["orCaseNo"]:$motoData[0]["mcoCaseNo"]; ?>&nbsp;&nbsp;&nbsp;
				案件編號: <?php echo $rcData[0]["rcCaseNo"]; ?>&nbsp;&nbsp;&nbsp;
				&nbsp;&nbsp;&nbsp;是否來自APP：
				<font style="color:red">
				<?php
					if($rcData[0]["rcType"] == "0"){ echo ($orData[0]['orIpAddress'] != "") ? "否":"是"; }else{ echo "是";}
				?>
				</font>
			</div>
		</div>
		<div class="col s12 m12 l12">
			<div class="card">
				<div class="card-content">
					<span class="card-title">申請人詳細資料	<span style="color:red;font-size:12px;font-weight:initial;">(紅色底線為可更改之欄位)</span></span><br>
					<div class="row">
						<form class="col s12">
							<input type="hidden" name="memNo" value="<?php echo $memData[0]["memNo"]; ?>">
							<input type="hidden" name="rcNo" value="<?php echo $rcData[0]["rcNo"]; ?>">
							<div class="row" style="text-align:center;">
								<a class="waves-effect waves-light btn green m-b-xs confirm-save">儲存</a>
								<?php if($rcData[0]["rcStatus"] == "5" || $rcData[0]["rcStatus"] == "6" || $rcData[0]["rcStatus"] == "1"){	?>
									<a class="waves-effect waves-light btn green m-b-xs confirm-insert">儲存並進件</a>
								<?php } ?>
								<a type="button" href="#modal1" class="modal-trigger waves-effect waves-light btn green m-b-xs look">查看證件上傳</a>
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
								
								
							</div>
							<div class="row">
								<div class="input-field col s3">
									<input type="text" name="memName" class="memName" value="<?php echo $memData[0]["memName"]; ?>">
									<label class="">申請人姓名<a href="https://www.facebook.com/<?php echo $memData[0]["memFBtoken"];?>" target="_blank"> FB 連結</a></label>
									<label id="memNameErr" class="error"></label>
								</div>
								<div class="input-field col s3">
									<input type="text" readonly value="<?php echo $api->memClassArr[$memData[0]["memClass"]]; ?>">
									<label class="">身分別</label>
								</div>
								<div class="input-field col s3">
									<select name="rcPosition">
										
										<option value="">請選擇</option>
										<?php 
											
											foreach($rcPosition  as $k => $v){
												$checked = ($v == $rcData[0]['rcPosition']) ? 'selected':'';
										?>
											<option value="<?=$v?>"  <?=$checked?>><?=$v?></option>
										<?php } ?>
									</select>
									<label class="">特殊身分</label>
								</div>
								<div class="input-field col s3">
									<input type="text" readonly value="<?php echo $memData[0]["memCell"]; ?>">
									<label class="">手機</label>
								</div>
							</div>
							<?php if($memData[0]["memClass"] == "0"){ ?>
							<div class="row">
								<?php 
								//if(strpos($memData[0]["memSchool"],"#") !== false){ 
									$schoolArr = explode("#", $memData[0]["memSchool"]);
								?>
								<div class="input-field col s4">
									<input type="text" name="school" value="<?php echo $schoolArr[0]; ?>">
									<label class="">學校</label>
									<label id="schoolErr" class="error"></label>
								</div>
								<div class="input-field col s4">
									<input type="text" name="major" value="<?php echo $schoolArr[1]; ?>">
									<label class="">系所</label>
									<label id="majorErr" class="error"></label>
								</div>
								<div class="input-field col s4">
									<input type="text" name="year" value="<?php echo $schoolArr[2]; ?>">
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
									<select name="memGender">
                                        <option <?php echo $memData[0]["memGender"] == "0" ? "selected" : ""; ?> value="0">女</option>
                                        <option <?php echo $memData[0]["memGender"] == "1" ? "selected" : ""; ?> value="1">男</option>
                                    </select>
									<label class="">性別</label>
								</div>
								<div class="input-field col s4">
									<input type="text" name="memBday" value="<?php echo $memData[0]["memBday"]; ?>">
									<?php $age = AgeOver20($memData[0]["memBday"]); ?>
									<label class="">生日(年-月-日)<font style="color:red"><?php echo ($age < '20') ? '未成年*':''; ?></font></label>
								</div>
								<div class="input-field col s4">
									<input type="text" name="memIdNum" value="<?php echo $memData[0]["memIdNum"]; ?>">
									<label class="">身分證字號</label>
								</div>
							</div>
							<div class="row">
								<div class="input-field col s4">                                   
									<input type="text" name="rcIdIssueDate" value="<?php echo ($rcData[0]['rcType'] == "0") ? $orData[0]['orIdIssueYear']."-".$orData[0]['orIdIssueMonth']."-".$orData[0]['orIdIssueDay']:$motoData[0]['mcoIdIssueYear']."-".$motoData[0]['mcoIdIssueMonth']."-".$motoData[0]['mcoIdIssueDay']; ?>">
									<label class="">發證日期</label> 
								</div>
								<div class="input-field col s4">
                                  
									<select name="rcIdIssuePlace">

										<?php 
											foreach($api->IdPlace as $key => $value){
                                                $IdSelected = ($rcData[0]['rcType'] == "0") ? $orData[0]['orIdIssuePlace']:$motoData[0]['mcoIdIssuePlace'];
                                                echo "dd".$IdSelected ;
											
											$selected = ($IdSelected == $value) ? "selected":"";
										?>
											<option value="<?php echo $value; ?>" <?php echo $selected?>><?php echo $value; ?></option>
										<?php
											}
                                        ?>
									</select>
									<label class="">發證地點</label>
								</div>
								<div class="input-field col s4">
                                   <?php
										$select =  ($rcData[0]['rcType'] == "0") ? $orData[0]['orIdIssueType']:$motoData[0]['mcoIdIssueType'];
								   ?>
									<select name="rcIdIssueType">
										<option value="初發" <?php echo ($select == "初發") ? "selected":""; ?>>初發</option>
										<option value="補發" <?php echo ($select == "補發") ? "selected":""; ?>>補發</option>
										<option value="換發" <?php echo ($select == "換發") ? "selected":""; ?>>換發</option>
									</select>
									<label class="">補換發類別</label>
								</div>
							</div>
							<div class="row">
								<div class="input-field col s6">
									<input type="text" name="memAccount" class="memAccount" value="<?php echo $memData[0]["memAccount"]; ?>">
									<?php $str = ($memData[0]["memEmailAuthen"] == 1) ? '已認證 '.$memData[0]["identify_time"]:'';?>
									<label class="">驗證Email <font style="color:red"><?php echo $str; ?></font>
										<a href="#!" class="reset_email"><?php echo ($str == "") ? "重發認證信":""; ?></a>
									</label> 
								</div>
								<div class="input-field col s6">
									<input type="text" readonly value="<?php echo $memData[0]["memSubEmail"]; ?>">
									<label class="">常用Email</label>
								</div>
							</div>
							<div class="row">
								<div class="input-field col s12">
									<input type="text" readonly value="<?php echo $memData[0]["memLineId"]; ?>">
									<label class="">Line ID</label>
								</div>
							</div>
							<div class="row">
								<div class="input-field col s4">
									<input type="text" name="memPhone" value="<?php echo $memData[0]["memPhone"]; ?>">
									<label class="">現住電話</label>
								</div>
								<div class="input-field col s2">
									<input type="text" name="memPostCode" value="<?php echo $memData[0]["memPostCode"]; ?>">
									<label class="">郵遞區號</label>
								</div>
								<div class="input-field col s6">
									<input type="text" name="memAddr" value="<?php echo $memData[0]["memAddr"]; ?>">
									<label class="">現住地址</label>
								</div>
							</div>
							<div class="row">
								<div class="input-field col s4">
									<input type="text" name="rcBirthPhone" value="<?php echo $rcData[0]["rcBirthPhone"]; ?>">
									<label class="">戶籍電話</label>
								</div>
								<div class="input-field col s2">
									<input type="text" name="rcBirthAddrPostCode" value="<?php echo $rcData[0]["rcBirthAddrPostCode"]; ?>">
									<label class="">郵遞區號</label>
								</div>
								<div class="input-field col s6">
									<input type="text" name="rcBirthAddr" value="<?php echo $rcData[0]["rcBirthAddr"]; ?>">
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
									<input type="text" readonly value="<?php echo $orData[0]["orAppApplierCompanyPhone"]." 分機".$orData[0]["orAppApplierCompanyPhoneExt"]; ?>">
									<label class="">公司電話</label>
								</div>
							</div>
							<?php } ?>
							<?php
							if($relaNameArr[0] != ""){
								foreach($relaNameArr as $key=>$value){ ?>
							<div class="row">
								<div class="input-field col s3">
									<input type="text" readonly value="<?php echo $value; ?>">
									<label class="">親屬姓名</label>
								</div>
								<div class="input-field col s3">
									<input type="text" readonly value="<?php echo $relaRelationArr[$key]; ?>">
									<label class="">關係</label>
								</div>
								<div class="input-field col s3">
									<input type="text" readonly value="<?php echo $relaPhoneArr[$key]; ?>">
									<label class="">市內電話</label>
								</div>
								<div class="input-field col s3">
									<input type="text" readonly value="<?php echo $relaCellArr[$key]; ?>">
									<label class="">行動電話</label>
								</div>
							</div>
							<?php } 
							}
							?>
							<?php
							if(!empty($frdNameArr)){
								foreach($frdNameArr as $key=>$value){ ?>
							<div class="row">
								<div class="input-field col s3">
									<input type="text" readonly value="<?php echo $value; ?>">
									<label class="">朋友姓名</label>
								</div>
								<div class="input-field col s3">
									<input type="text" readonly value="<?php echo $frdRelationArr[$key]; ?>">
									<label class="">關係</label>
								</div>
								<div class="input-field col s3">
									<input type="text" readonly value="<?php echo $frdPhoneArr[$key]; ?>">
									<label class="">市內電話</label>
								</div>
								<div class="input-field col s3">
									<input type="text" readonly value="<?php echo $frdCellArr[$key]; ?>">
									<label class="">行動電話</label>
								</div>
							</div>
							<?php } 
							}
							?>
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
								<div class="input-field col s6">
									<input type="text" readonly value='<?php echo $supData[0]["supName"]; ?>'>
									<label class="">供應商名稱</label>  
								</div>
								<div class="input-field col s6">
									<input type="text" readonly value='<?php echo ($supData[0]['aauNo'] != "") ? $supData[0]["supDisplayName"]:""; ?>'>
									<label class="">通訊行編號</label>
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
									<input type="text" id="mcoPeriodTotal" name="mcoPeriodTotal" value="<?php echo $motoData[0]["mcoPeriodTotal"]; ?>">
									<label class="">貸款金額<button style="color:#FFF;background-color:#2ab7a9;" id="calculate-total" type="button">計算期付金額</button></label>
								</div>
								<div class="input-field col s4">
									<input type="text" id="mcoPeriodAmount" name="mcoPeriodAmount" value="<?php echo $motoData[0]["mcoPeriodAmount"]; ?>">
									<label class="">期數</label>
								</div>
								<div class="input-field col s4">
									<input type="text" id="dismcoMaxMonthlyTotal" name="dismcoMaxMonthlyTotal" value="<?php echo $motoData[0]["mcoMinMonthlyTotal"]; ?>" disabled>
									<input type="hidden" id="mcoMaxMonthlyTotal" name="mcoMinMonthlyTotal" value="<?php echo $motoData[0]["mcoMinMonthlyTotal"]; ?>">
									<label class="">期付金額</label>
								</div>
									<input type="hidden" id="mbMax_6" value="<?echo $rateData['0']['mbMin']; ?>">
									<input type="hidden" id="mbMax_12" value="<?echo $rateData['1']['mbMin']; ?>">
									<input type="hidden" id="mbMax_18" value="<?echo $rateData['2']['mbMin']; ?>">
									<input type="hidden" id="mbMax_24" value="<?echo $rateData['3']['mbMin']; ?>">
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
								<h5>案件處理人員</h5>
								<table class="striped responsive-table">
	                                <thead>
	                                    <tr>
											<th>處理人員</th>
											<th>動作</th>
	                                        <th>時間</th>
											
	                                    </tr>
	                                </thead>
	                                <tbody>
									<?php
										if($serviceData != ''){
											foreach($serviceData as $key => $value){
									?>
										<tr>
											<td>
												<?php 
													$rcuserData = $aau->getOne($value["aauNoService"]);
													echo $rcuserData['0']['aauName'];  
												?>
											</td>
											<td><?php echo $value["content"]; ?></td>
											<td><?php echo $value["time"]; ?></td>
											
										</tr>
									<?php
											}
										}else{
									?>
										<tr>
											<td colspan="3">無</td>
										</tr>	
									<?php
										}
									?>
									</tbody>
								</table>
							</div>
							
							<div class="row">
								<span style="font-size:18px;font-weight: bold;">補件紀錄</span>
								<table class="striped responsive-table">
	                                <thead>
	                                    <tr>
											<th>時間</th>
	                                        <th>補件內容說明</th>
											<th>處理人員</th>
	                                    </tr>
	                                </thead>
	                                <tbody>
									<?php
										$sf->setWhereArray(array("rcNo"=>$no));
										$sfData = $sf->getWithConditions();
										if($sfData != ''){
											foreach($sfData as $key => $value){
									?>
										<tr>
											<td><?php echo $value["time"]; ?></td>
											<td><?php echo $value["orDocProvideReason"]; ?></td>
											<td>
												<?php 
													$rcuserData = $aau->getOne($value["aauNoService"]);
													echo $rcuserData['0']['aauName'];  
												?>
											</td>
										</tr>
									<?php
											}
										}else{
									?>
										<tr>
											<td colspan="3">無</td>
										</tr>	
									<?php
										}
									?> 
									</tbody>
								</table>
							</div>
							
							
							<div class="row">
								<div class="input-field col s4">
									<select name="rcStatus">
										<option <?php echo $rcData[0]["rcStatus"] == "1" ? "selected" : ""; ?> value="1">未進件</option>
										<option <?php echo $rcData[0]["rcStatus"] == "5" ? "selected" : ""; ?> value="5">待核准</option>
										<option <?php echo $rcData[0]["rcStatus"] == "4" ? "selected" : ""; ?> value="4">婉拒</option>
										<option <?php echo $rcData[0]["rcStatus"] == "7" ? "selected" : ""; ?> value="7">取消訂單</option>
										<option <?php echo $rcData[0]["rcStatus"] == "701" ? "selected" : ""; ?> value="701">客戶自行撤件</option>
									</select>
									<label class="">案件狀態</label>
								</div>
								<div class="input-field col s4">
									<a type="button" href="#modal2" class="modal-trigger waves-effect waves-light btn green m-b-xs add-status-comment" <?php echo $rcData[0]["rcStatus"] != "1" && $rcData[0]["rcStatus"] != "6" ? "" : 'style="display:none;"'; ?>>增加狀態備註</a>
									<label class=""></label>
								</div>
							</div>
							<div id="reason-area-internal" <?php echo $rcData[0]["rcStatus"] != "1" && $rcData[0]["rcStatus"] != "6" ? "" : 'style="display:none;"'; ?>>
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
							<div class="row" style="text-align:center;">
								<a class="waves-effect waves-light btn green m-b-xs confirm-save">儲存</a>
								<?php if($rcData[0]["rcStatus"] == "5" || $rcData[0]["rcStatus"] == "6" || $rcData[0]["rcStatus"] == "1"){	?>
									<a class="waves-effect waves-light btn green m-b-xs confirm-insert">儲存並進件</a>
								<?php } ?>
								<a type="button" href="#modal1" class="modal-trigger waves-effect waves-light btn green m-b-xs look">查看證件上傳</a>
								<a target="_blank" href="../admin/view/print_order_details.php?orno=<?php echo $rcData[0]["rcRelateDataNo"]; ?>&rcNo=<?php echo $no; ?>&aauNoService=<?php echo $_SESSION['adminUserData']['aauNo']; ?>" class="modal-trigger waves-effect waves-light btn green m-b-xs print">列印</a>
							</div>
						</form>
					</div>
				</div>
			</div>
		</div>
		<?php }else{ ?>
		<div class="col s12">
			<div class="page-title"><?php echo $errMsg; ?></div>
		</div>
		<?php } ?>
	</div>
</main>
<div id="modal2" class="modal">
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
<script src="assets/js/pages/ui-modals.js"></script>
<script>
$(function(){
	isChange = false;
	$('.status').hide();
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

	//案件狀態切換時要顯示/隱藏補件原因
	$("select[name='rcStatus']").on("change",function(){
		//未進件才可以進見
		if($(this).find("option:selected").val() == 1){
			$(".confirm-insert").show();
		}else{
			$(".confirm-insert").hide();
		}
		
		//內部審查人員看得補件原因
		if($(this).find("option:selected").val() != 1 && $(this).find("option:selected").val() != 6){
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
	
	$(".confirm-save").click(function(e){
		$(".error").text("");
		e.preventDefault();

		var form = $("form").serialize();
		var url = "ajax/case_process/edit_case.php";
		
		$.ajax({
			url:url,
			type:"post",
			data:form,
			datatype:"json",
			success:function(result){
				console.log(result);
				if(result.indexOf("OK") != -1){
					alert("儲存成功！");
					isChange = true;
					location.reload();
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
				$('.status').show();
				$(".confirm-save").hide();
			}
		});
	});

	$(".confirm-insert").click(function(e){
		$(".error").text("");
		e.preventDefault();

		var form = $("form").serialize();
		var url = "ajax/case_process/edit_case_process.php";
		$.ajax({
			url:url,
			type:"post",
			data:form,
			datatype:"json",
			success:function(result){
				var json = $.parseJSON(result);
				if(json.message == "OK"){
					alert("儲存成功！案件編號:"+json.rcCaseNo);
					<?php if($rcData[0]["rcCaseNo"] == ""){ ?>
					$.ajax({
						url:"https://inner.nowait.shop/v1/redirect-to-other-api-Cr9YjX4/crawler?rcCaseNo="+json.rcCaseNo,
						type:"get",
						// data:form,
						// datatype:"json",
						success:function(aaa){
							alert("爬蟲成功："+aaa.message);
						}
					});
					<?php } ?>
					isChange = true;
					location.href = "?page=case";
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
				$('.status').show();
				$(".confirm-insert").hide();
			}
		});
	});
	
	//上傳檔案
	$(".modal-save").click(function(e){
		$('#applyFile').submit();
	})
	
	//email重新認證
	$(".reset_email").click(function(){
		var memAccount = $('.memAccount').val();
		var memName = $('.memName').val();
		var memno = <?php echo $rcData['0']['memNo']; ?>;
		$.ajax({
            url: '../admin/ajax/member/member_resetmail.php',
            data: 'memNo='+memno+'&memAccount='+memAccount,
            type:"POST",
            dataType:'text',
            success: function(msg){
                if(msg){
					alert('已寄送到'+memAccount);
				}else{
					alert('寄送錯誤');
				}
            }
        });
	})
});

//計算期付金額
$("#calculate-total").click(function(){
	var number = $("#mcoPeriodAmount").val();
	if(number == "6" || number == "12" || number == "18" || number == "24"){
		var mbMax = $("#mbMax_"+number).val()/10000*$("#mcoPeriodTotal").val();
		$("#mcoMaxMonthlyTotal").val(Math.floor(mbMax));
		$("#dismcoMaxMonthlyTotal").val(Math.floor(mbMax));
		alert("期付金額已改變");
	}else{
		alert("期數不正確，必須是6,12,18,24");
	}
});

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

$('.look').click(function(){
	$.ajax({
        type: "post",
        url: "http://api.21-finance.com/api/ViewDocument",
        data: {
			AID: "<?php echo ($rcData[0]["rcType"] == "0") ? $orData[0]["orCaseNo"]:$motoData[0]["mcoCaseNo"]; ?>", 
			Name: "<?php echo $_SESSION['adminUserData']['aauName']; ?>", 
			Page: "進件作業", 
			Btn: "查看證件上傳", 
			Ip: "<?php echo $_SERVER["REMOTE_ADDR"]; ?>", 
			LookDate: "<?php echo date("Y-m-d H:i:s"); ?>" 
		},
        success: function (data, status) {}
    });
});

 $('.print').click(function(){
	$.ajax({
        type: "post",
        url: "http://api.21-finance.com/api/ViewDocument",
        data: { 
			AID: "<?php echo ($rcData[0]["rcType"] == "0") ? $orData[0]["orCaseNo"]:$motoData[0]["mcoCaseNo"]; ?>", 
			Name: "<?php echo $_SESSION['adminUserData']['aauName']; ?>", 
			Page: "進件作業", Btn: "列印", 
			Ip: "<?php echo $_SERVER["REMOTE_ADDR"]; ?>", 
			LookDate: "<?php echo date("Y-m-d H:i:s"); ?>" },
        success: function (data, status) {}
    });
});

	//登入時更換鎖定
	$(document).ready(function(){
		$.ajax({
			url:"ajax/order/orderLockIn.php",
			type:"POST",
			data:{
				"rcNo":<?php echo $_GET["no"];?>
			},
			datatype:"text",
			success:function(result){}
		});
	});

	//離開頁面時，取消案件鎖定
	$(window).bind('beforeunload', function (e) {
		$.ajax({
			type: "post",
			url: "ajax/order/orderLockOut.php",
			data: {
				"rcNo":<?php echo $_GET["no"];?>
			},
			datatype:"text",
			success: function (result) {
			}
		});
		if(!isChange){
			return " ";   
		}
	});
</script>