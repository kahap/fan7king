<?php 
$allowed_hosts = array("localhost","127.0.0.1","happyfan7.com","test.happyfan7.com");
if (!isset($_SERVER['HTTP_HOST']) || !in_array($_SERVER['HTTP_HOST'], $allowed_hosts)) {
	$errMsg = "您無權限造訪此頁";
}else{
	if(isset($no) || isset($_GET["no"])){
		$no = $_GET["no"];
		
		$rc = new API("real_cases");
		$uu = new API("urge_user");
		
		$aauNo = $_SESSION["adminUserData"]["aauNo"];
		
		$aau = new API("admin_advanced_user");
		$aar = new API("admin_advanced_roles");
		
		$aauMe = $aau->getOne($_SESSION["adminUserData"]["aauNo"]);
		$my_access_role_array=json_decode($aauMe[0]["aarNo"]);
		
// 		print_r($my_access_role_array);
		$_602 = false;
		foreach($my_access_role_array as $k =>$v){
			$my_access_role_array_i_page= json_decode($aar->getOne($v)[0]["aafID"]);
			if(in_array(602,$my_access_role_array_i_page)){
				$_602 = true;
			}
// 			if($_602){
// 				print_r($my_access_role_array_i_page);
// 			}
		}
		$uu->setWhereArray(array("aauNo"=>$aauNo));
		$uuData = $uu->getWithConditions();
		if($uuData != null || $_602){
			$rcData = $rc->getOne($no);
			if($rcData != null){
// 				$aau = new API("admin_advanced_user");
				$or = new API("orders");
				$mem = new API("member");
				$tpi = new API("tpi");
				$pr = new API("pay_records");
				$np = new API("note_person");
				$nl = new API("note_list");
				$ndc = new API("note_default_comment");
				$nad = new API("note_applier_details");
				$ncd = new API("note_contact_details");
				$tb = new API("transfer_bank");
				$etr = new API("edit_tpi_records");
				$sup = new API("supplier");
				$pro = new API("product");
				$pm = new API("product_manage");
				$ur = new API("urge_records");
				$utr = new API("urge_txt_records");
				$orderContact = new API("orderContact");
					
				$memData = $mem->getOne($rcData[0]["memNo"]);
				$tpi->setWhereArray(array("rcNo"=>$no));
				$tpi->setOrderArray(array("tpiPeriod"=>false));
				$tpiData = $tpi->getWithConditions();
				$pr->setWhereArray(array("rcNo"=>$no));
				$pr->setOrderArray(array("prDate"=>false));
				$prData = $pr->getWithConditions();
				$supData = $sup->getOne($rcData[0]["supNo"]);
				if($rcData[0]["rcType"] == "0"){
					$orData = $or->getOne($rcData[0]["rcRelateDataNo"]);
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
				
				//簡訊紀錄
				$utr->setWhereArray(array("rcNo"=>$no));
				$utr->setOrderArray(array("utrDate"=>true));
				$utrData = $utr->getWithConditions();
				
				//簡訊手機
				$cellArr = array();
				$cellArr[0] = array(
					"role"=>"申請人",
					"relationship"=>"本人",
					"number"=>$memData[0]["memCell"],
					"name"=>$memData[0]["memName"]
				);

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
					foreach($contactNameArr as $key=>$value){
						$curArr = array(
							"role"=>"聯絡人",
							"relationship"=>$contactRelaArr[$key],
							"number"=>$contactCellArr[$key],
							"name"=>$contactNameArr[$key]
						);
						array_push($cellArr,$curArr);
					}
				}
				
				//催收紀錄
				$ur->setWhereArray(array("rcNo"=>$no));
				$ur->setOrderArray(array("urDate"=>true));
				$urData = $ur->getWithConditions();
					
				//調帳紀錄
				$etr->setWhereArray(array("rcNo"=>$no));
				$etr->setOrderArray(array("etrDate"=>true));
				$etrData = $etr->getWithConditions();
					
				//申請人徵信
				//申請人徵信欄位
				$nl->setWhereArray(array("npNo"=>1));
				$nlApplierColumns = $nl->getWithConditions();
				//申請人徵信資料
				$sql = "SELECT * FROM `note_applier_details` WHERE `rcNo` = '".$no."' order by convert(`nlNo`, decimal),`nadDate` asc";
				$nadData = $nad->customSql($sql);
					
					
				//聯絡人徵信
				$ncd->setWhereArray(array("rcNo"=>$no));
				$ncd->setOrderArray(array("nlNo"=>false));
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
					
				//撥款銀行
				$tbData = $tb->getOne($rcData[0]["tbNo"]);
					
			}else{
				$errMsg = "查無此訂單。";
			}
		}else{
			$errMsg = "您不是催收人員。";
		}
		
	}else{
		$errMsg = "錯誤的頁面導向。";
	}
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

</style>
<div id="file" class="modal modal-fixed-footer">
	
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
		<?php if(!empty($rcData[0]["rcStudentIdImgTop"]) && $memData[0]["memClass"] != "4"){ ?>
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
		<?php if(!empty($rcData[0]["rcStudentIdImgBot"]) && $memData[0]["memClass"] != "4"){ ?>
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
					$src = json_decode($rcData[0]["rcExtraInfoUpload"]);
			?>
			<img class="id-pic" src="<?php echo IMG_ROOT.$src[0]; ?>">
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
    </div>
	</form>
</div>
<main class="mn-inner">
	<div class="row">
		<?php if(!isset($errMsg)){ ?>
		<div id="modal1" class="modal">
		    <div class="modal-content">
		        <div class="row">
		        	<table class="striped responsive-table">
		        		<thead>
                            <tr>
                            	<th>編號</th>
								<th>操作人員</th>
								<th>調帳內容</th>
								<th>修改日期</th>
							</tr>
						</thead>
						<tbody>
						<?php 
						if($etrData != null){
							foreach($etrData as $key=>$value){
								$aauData = $aau->getOne($value["aauNo"]);
						?>
							<tr>
								<td><?php echo $key+1; ?></td>
								<td><?php echo $aauData[0]["aauName"]; ?></td>
								<td><?php echo $value["etrDetails"]; ?></td>
								<td><?php echo $value["etrDate"]; ?></td>
							</tr>
						<?php 
							}
						}
						?>
						</tbody>
		        	</table>
		        </div>
		    </div>
		    <div class="modal-footer">
		        <a href="#!" class=" modal-action modal-close waves-effect waves-green btn-flat">關閉</a>
		    </div>
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
				<a type="button" href="#file" class="modal-trigger waves-effect waves-light btn green m-b-xs look">查看證件上傳</a>
				<?php 
					switch($rcData[0]["rcType"]){
									case "0":
				?>
								<a target="_blank" href="../admin/view/print_order_details.php?orno=<?php echo $rcData[0]["rcRelateDataNo"]; ?>&rcNo=<?php echo $no; ?>&aauNoService=<?php echo $_SESSION['adminUserData']['aauNo']; ?>" class="modal-trigger waves-effect waves-light btn green m-b-xs print">查看申請書</a>
								<?php 
									break;
									case "1":
								?>
								<a target="_blank" href="../admin/view/print_cell_details.php?mcoNo=<?php echo $rcData[0]["rcRelateDataNo"]; ?>&rcNo=<?php echo $no; ?>&aauNoService=<?php echo $_SESSION['adminUserData']['aauNo']; ?>" class="modal-trigger waves-effect waves-light btn green m-b-xs print">查看申請書</a>
								<?php 	
									break;
									
									case "2":
								?>
								<a target="_blank" href="../admin/view/print_moto_details.php?mcoNo=<?php echo $rcData[0]["rcRelateDataNo"]; ?>&rcNo=<?php echo $no; ?>&aauNoService=<?php echo $_SESSION['adminUserData']['aauNo']; ?>" class="modal-trigger waves-effect waves-light btn green m-b-xs print">查看申請書</a>
				<?php 	
						break;
					}
				?>
			</div>
		</div>
		<div class="col s12 m12 l12">
			<div class="card">
				<div class="card-content">
					<span class="card-title">案件詳細資料</span><br>
					<div class="row">
							<div class="row">
								<div class="input-field col s4">
									<input type="text" readonly value="<?php echo $rcData[0]["rcCaseNo"]; ?>">
									<label class="">案件編號</label>
								</div>
								<div class="input-field col s4">
									<input type="text" readonly value="<?php echo $rcData[0]["rcStatus2Time"] ; ?>">
									<label class="">進件時間</label>
								</div>
								<div class="input-field col s4">
									<input type="text" readonly value="<?php echo $rcData[0]["rcStatus6Time"]; ?>">
									<label class="">補件時間</label>
								</div>
							</div>
							<div class="row">
								<div class="input-field col s4">
									<input type="text" readonly value="<?php echo $memData[0]["memName"]; ?>">
									<label class="">申請人姓名 <a href="https://www.facebook.com/<?php echo $memData[0]["memFBtoken"]; ?>" target="_blank">FB 連結</a></label> 
								</div>
								<div class="input-field col s4">
									<input type="text" readonly value="<?php echo $memData[0]["memClass"] != "" ? $mem->memClassArr[$memData[0]["memClass"]] : "無"; ?>">
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
							<?php if($rcData[0]["rcType"] == "0"){ ?>
							<div class="row">
								<div class="input-field col s8">
									<input type="text" readonly value="<?php echo htmlspecialchars($proData[0]["proName"]); ?>"> 
									<label class="">購買商品</label>
								</div>
								<div class="input-field col s4">
									<input type="text" readonly value="<?php echo $orData[0]["orProSpec"]; ?>">
									<label class="">規格</label>
								</div>
							</div>
							<div class="row">
								<div class="input-field col s4">
									<input type="text" readonly value="<?php echo $orData[0]["orReceiveName"]; ?>">
									<label class="">收貨人姓名</label>
								</div>
								<div class="input-field col s8">
									<input type="text" readonly value="<?php echo $orData[0]["orReceiveAddr"]; ?>">
									<label class="">收貨人地址</label>
								</div>
							</div>
							<div class="row">
								<div class="input-field col s4">
									<input type="text" readonly value="<?php echo $orData[0]["orReceivePhone"]; ?>">
									<label class="">收貨人市話</label>
								</div>
								<div class="input-field col s4">
									<input type="text" readonly value="<?php echo $orData[0]["orReceiveCell"]; ?>">
									<label class="">收貨人手機</label>
								</div>
								<div class="input-field col s4">
									<input type="text" readonly value="<?php echo $orData[0]["orReceiveComment"]; ?>">
									<label class="">收貨備註</label>
								</div> 
							</div>
							<div class="row">
								<div class="input-field col s4">
									<input type="text" readonly value="<?php echo $rcData[0]["rcPeriodAmount"]; ?>">
									<label class="">分期數</label>
								</div>
								<div class="input-field col s4">
									<input type="text" readonly value="<?php echo $rcData[0]["rcPeriodTotal"]/$rcData[0]["rcPeriodAmount"]; ?>">
									<label class="">期付款</label>
								</div>
								<div class="input-field col s4">
									<input type="text" readonly value="<?php echo $rcData[0]["rcPeriodTotal"] ; ?>"> 
									<label class="">申請總金額</label>
								</div>
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
									<input type="text" id="mcoPeriodTotal" readonly value="<?php echo $motoData[0]["mcoPeriodTotal"]; ?>">
									<label class="">貸款金額</label>
								</div>
								<div class="input-field col s4">
									<input type="text" id="mcoPeriodAmount" readonly value="<?php echo $motoData[0]["mcoPeriodAmount"]; ?>">
									<label class="">期數</label>
								</div>
								<div class="input-field col s4">
									<input type="text" id="dismcoMaxMonthlyTotal" readonly value="<?php echo $motoData[0]["mcoMinMonthlyTotal"]; ?>">
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
							
							<div class="row">
								<div class="input-field col s12">
									<h5 class="">申請人GPS、簡訊、通訊錄詳細資料</h5>
									<a target="_blank" href="?page=member&type=view&no=<?php echo $rcData[0]["memNo"]; ?>">點我查看</a><br>
									是否來自APP：
									<font style="color:red">
									<?php
										
										if($rcData[0]["rcType"] == "0"){ echo ($orData[0]['orIpAddress'] != "") ? "否":"是"; }
									?>
									</font>
								</div>
							</div>
							
							
							<?php if($rcData[0]["rcType"] == "0"){ ?>
							<div class="row">
								<span style="font-size:18px;font-weight: bold;">曾經下過訂單資訊</span>
								<table class="striped responsive-table">
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
											<td><?php echo $rc->statusArr[$value["orStatus"]];?></td>
										</tr>
									<?php
										}
									?>
									</tbody>
								</table>
							</div>
							<?php } ?>
					</div>
					
				</div>
			</div>
			<div class="card">
				<div class="card-content">
					<span class="card-title">其他資訊</span><br>
					<div class="row">
		                <div class="col s12">
		                    <ul class="tabs tab-demo z-depth-1" style="width: 100%;">
								<li class="tab col s3"><a href="#test0">催收紀錄</a></li>
								<li class="tab col s3"><a href="#test5">發送簡訊</a></li>
								<li class="tab col s3"><a href="#test1">申請人徵信</a></li>
								<li class="tab col s3"><a href="#test2">保證人徵信</a></li>
								<li class="tab col s3"><a href="#test3">聯絡人徵信</a></li>
								<li class="tab col s3"><a href="#test4">本息表</a></li>
							</ul>
						</div>
						<div id="test0" class="row">
							<div class="col s3">
								<form id="urge-form">
									<input type="hidden" name="uuNo" value="<?php echo $uuData[0]["uuNo"]; ?>">
									<input type="hidden" name="rcNo" value="<?php echo $rcData[0]["rcNo"]; ?>">
									<div class="row">
										<div class="input-field col s12">
											<input type="text" class="datepicker" name="urPromiseDate" value="">
											<label class="">約定繳款日</label>
										</div>
										<div class="input-field col s12">
											<select name="urType">
												<?php foreach($rc->urgeMethodArr as $key=>$value){ ?>
												<option value="<?php echo $key; ?>"><?php echo $value;?></option>
												<?php } ?>
											</select>
											<label class="">處理方式</label>
										</div>
										<div class="input-field col s12">
											<select name="urStatus">
												<?php foreach($rc->urgeStatusArr as $key=>$value){ ?>
												<option value="<?php echo $key; ?>"><?php echo $value;?></option>
												<?php } ?>
											</select>
											<label class="">催收狀態</label>
										</div>
										<div class="input-field col s12">
											<textarea class="materialize-textarea" name="urDetails"></textarea>
											<label class="">電話備註</label>
										</div>
									</div>
									<div class="row" style="text-align:center;">
										<a class="waves-effect waves-light btn green m-b-xs confirm-save-urge">新增催收紀錄</a>
									</div>
								</form>
							</div>
							<div class="col s9">
								<table class="striped responsive-table">
									<thead>
										<tr>
											<th>編號</th>
											<th>催收人員</th>
											<th>催收日期</th>
											<th>電催備註</th>
											<th>約定繳款日</th>
											<th>處理方式</th>
											<th>催收狀態</th>
										</tr>
									</thead>
									<tbody>
									<?php 
									if($urData != null){
										foreach($urData as $key=>$value){ 
											$urUuData = $uu->getOne($value["uuNo"]);
											$urAauData = $aau->getOne($urUuData[0]["aauNo"]);
									?>
										<tr>
											<td><?php echo $key+1; ?></td>
											<td><?php echo $urAauData[0]["aauName"]; ?></td>
											<td><?php echo $value["urDate"]; ?></td>
											<td><?php echo $value["urDetails"]; ?></td>
											<td><?php echo $value["urPromiseDate"]; ?></td>
											<td><?php echo $rc->urgeMethodArr[$value["urType"]]; ?></td>
											<td><?php echo $rc->urgeStatusArr[$value["urStatus"]]; ?></td>
										</tr>
									<?php 
										}
									}else{
									?>
										<tr>
											<td style="text-align: center;" colspan="7">尚無催收紀錄</td>
										</tr>
									<?php 
									}
									?>
									</tbody>
								</table>
							</div>
						</div>
						<div id="test5" class="row">
							<div class="col s3">
								<form id="txt-form">
									<input type="hidden" name="uuNo" value="<?php echo $uuData[0]["uuNo"]; ?>">
									<input type="hidden" name="rcNo" value="<?php echo $rcData[0]["rcNo"]; ?>">
									<div class="row">
										<div class="input-field col s12">
											<input type="text" name="utrNumber" value="" placeholder="可使用下方快速選取">
											<label class="">號碼</label>
										</div>
										<div class="input-field col s12">
											<select id="which-cell">
												<option value="">請選擇</option>
												<?php foreach($cellArr as $key=>$value){?>
												<option value="<?php echo $value["number"]; ?>"><?php echo $value["relationship"]."-".$value["name"].":".$value["number"]; ?></option>
												<?php }?>
											</select>
											<label class="">快速選擇號碼</label>
										</div>
										<div class="input-field col s12">
											<textarea class="materialize-textarea" name="utrDetails"></textarea>
											<label class="">簡訊內容</label>
										</div>
									</div>
									<div class="row" style="text-align:center;">
										<a class="waves-effect waves-light btn green m-b-xs confirm-save-txt">發送簡訊</a>
									</div>
								</form>
							</div>
							<div class="col s9">
								<table class="striped responsive-table">
									<thead>
										<tr>
											<th>編號</th>
											<th>號碼</th>
											<th>發送內容</th>
											<th>發送者</th>
											<th>發送日期</th>
											<th>狀態</th>
										</tr>
									</thead>
									<tbody>
									<?php 
									if($utrData != null){
										foreach($utrData as $key=>$value){ 
											$urUuData = $uu->getOne($value["uuNo"]);
											$urAauData = $aau->getOne($urUuData[0]["aauNo"]);
									?>
										<tr>
											<td><?php echo $key+1; ?></td>
											<td><?php echo $value["utrNumber"]; ?></td>
											<td><?php echo $value["utrDetails"]; ?></td>
											<td><?php echo $urAauData[0]["aauName"]; ?></td>
											<td><?php echo $value["utrDate"]; ?></td>
											<td><?php echo $value["utrStatus"] == "1" ? "成功" : "失敗"; ?></td>
										</tr>
									<?php 
										}
									}else{
									?>
										<tr>
											<td style="text-align: center;" colspan="7">尚無寄送簡訊紀錄</td>
										</tr>
									<?php 
									}
									?>
									</tbody>
								</table>
							</div>
						</div>
						<div id="test1" class="row">
							<?php if($nadData != null){ ?>
									<table style="margin-top:50px" class="striped responsive-table">
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
	                                    	?>
	                                        <tr>
	                                            <td class="nlName"><?php echo $nlData[0]["nlName"]; ?></td>
	                                            <td><?php echo $ndcOption; ?></td>
	                                            <td><?php echo $value["nadExtraInfo"]; ?></td>
	                                            <td><?php echo $value["nadDate"]; ?></td>
	                                        </tr>
	                                        <?php 
	                                    		}
	                                    	?>
	                                    </tbody>
	                                </table>
                                <?php }else{ ?>
                                <p class="p-v-sm">無</p>
                                <?php } ?>
						</div>
						<div id="test2" class="col s12">
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
                                <?php
										}
									}else{ 
								
								?>
							
									<p class="p-v-sm">無</p>
								<?php
									}
								?>
						</div>
						<div id="test3" class="row">
								<?php if($ncdData != null){ ?>
									<div style="clear:both;"></div>
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
						<div id="test4" class="row">
							<div class="row">
								<div class="input-field col s4">
									<input type="text" readonly value="<?php echo ($rcData[0]["tbNo"] == '1') ? "006":""; ?>">
									<label class="">銀行代碼</label>
								</div>
								<div class="input-field col s4">
									<input type="text" readonly value="<?php echo $rcData[0]["rcVirtualAccount"]; ?>">
									<label class="">虛擬帳號</label>
								</div>
								<div class="input-field col s4">
									<input type="text" readonly value="">
									<label class="">結清狀態</label>
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
								<div class="input-field col s4">
									<input type="text" readonly value="<?php echo $rcData[0]["rcPredictGetDate"]; ?>">
									<label class="">撥款日期</label>
								</div>
								<div class="input-field col s4">
									<input type="text" readonly value="<?php echo $tbData[0]["tbName"]?>">
									<label class="">撥款銀行</label>
								</div>
								<div class="input-field col s4">
									<input type="text" readonly value="<?php echo $rcData[0]["rcBankTransferAmount"]; ?>">
									<label class="">撥款金額</label>
								</div>
							</div>
							<div class="row">
								<div>
									<a class="show-edit-history waves-effect waves-light btn green m-b-xs modal-trigger" href="#modal1">檢視調帳紀錄</a>
								</div>
								<div class="col s6">
									<h4>本息表</h4>
									<table class="striped responsive-table">
										<thead>
											<tr>
												<th>期數</th>
												<th>應繳款日</th>
												<th>實際繳款日</th>
												<th>期付款</th>
												<th>滯納金</th>
												<th>還款金額</th>
												<th>逾期天數</th>
											</tr>
										</thead>
										<tbody>
											<?php 
											if($tpiData != null){
												foreach($tpiData as $key=>$value){
											?>
											<tr>
												<td><?php echo $value["tpiPeriod"]; ?></td>
												<td><?php echo $value["tpiSupposeDate"]; ?></td>
												<td><?php echo $value["tpiActualDate"]; ?></td>
												<td><?php echo $value["tpiPeriodTotal"]; ?></td>
												<td><?php echo ($value["tpiIfCancelPenalty"] == "1") ? "取消滯納金":$value["tpiPenalty"]; ?></td>
												<td><?php echo $value["tpiPaidTotal"]; ?></td>
												<td><?php echo $value["tpiOverdueDays"]; ?></td>
											</tr>
											<?php 	
												}
											}else{
											?>
											<tr>
												<td colspan="7">尚無本息表產出</td>
											</tr>
											<?php 
											}
											?>
										</tbody>
									</table>
								</div>
								<div class="col s6">
									<h4>繳款紀錄</h4>
									<table class="striped responsive-table">
										<thead>
											<tr>
												<th>繳款日</th>
												<th>實還金額</th>
												<th>來源</th>
												<th>手續費</th>
												<th>溢收款</th>
											</tr>
										</thead>
										<tbody>
											<?php 
											if($prData != null){
												foreach($prData as $key=>$value){
											?>
											<tr>
												<td><?php echo $value["prDate"]; ?></td>
												<td><?php echo $value["prActualPay"]; ?></td>
												<td><?php echo $value["prSource"]; ?></td>
												<td><?php echo $value["prFee"]; ?></td>
												<td><?php echo $value["prExtra"]; ?></td>
											</tr>
											<?php 	
												}
											}else{
											?>
											<tr>
												<td colspan="5">尚無任何繳款紀錄</td>
											</tr>
											<?php 
											}
											?>
										</tbody>
									</table>
								</div>
							</div>
						</div>
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
<script src="assets/js/pages/form_elements.js"></script>
<script src="assets/js/pages/ui-modals.js"></script>
<script src="assets/plugins/datatables/js/jquery.dataTables.min.js"></script>
<script>
$(function(){
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
	
	//電話號碼仔入
	$("#which-cell").change(function(){
		$("input[name='utrNumber']").val($(this).find("option:selected").val());
	});

	$(".confirm-save-txt").click(function(e){
		$(".error").text("");
		e.preventDefault();

		var form = $("#txt-form").serialize();
		var url = "ajax/urge/send_txt_msg.php";
		
		$.ajax({
			url:url,
			type:"post",
			data:form,
			datatype:"json",
			success:function(result){
				if(result.indexOf("成功") != -1){
					alert("發送成功！");
					location.reload();
				}else{
					alert(result);
				}
			}
		});
	});

	$(".confirm-save-urge").click(function(e){
		e.preventDefault();

		var form = $("#urge-form").serialize();
		var url = "ajax/urge/insert_urge.php";
		
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
			}
		});
	});
});

$(document).ready(function() {
    $('#example').DataTable({
        language: {
            searchPlaceholder: '尋找關鍵字',
            sInfo: "從 _START_ 到 _END_ ，共 _TOTAL_ 筆",
            sSearch: '',
            sLengthMenu: '顯示數 _MENU_',
            sLength: 'dataTables_length',
            oPaginate: {
                sFirst: '<i class="material-icons">chevron_left</i>',
                sPrevious: '<i class="material-icons">chevron_left</i>',
                sNext: '<i class="material-icons">chevron_right</i>',
                sLast: '<i class="material-icons">chevron_right</i>' 
            }
        },
	    "aoColumnDefs": [{
	        'bSortable': false,
	        'aTargets': [0]
	      } //disables sorting for column one
	    ],
	    "order": [[ 1 , "asc" ]],
	    "iDisplayLength": 5
    	
    });
    $('.dataTables_length select').addClass('browser-default');
});

$(document).ready(function() {
    $('#example1').DataTable({
        language: {
            searchPlaceholder: '尋找關鍵字',
            sInfo: "從 _START_ 到 _END_ ，共 _TOTAL_ 筆",
            sSearch: '',
            sLengthMenu: '顯示數 _MENU_',
            sLength: 'dataTables_length',
            oPaginate: {
                sFirst: '<i class="material-icons">chevron_left</i>',
                sPrevious: '<i class="material-icons">chevron_left</i>',
                sNext: '<i class="material-icons">chevron_right</i>',
                sLast: '<i class="material-icons">chevron_right</i>' 
            }
        },
	    "order": [[ 1 , "asc" ]],
	    "iDisplayLength": 5
    	
    });
    $('.dataTables_length select').addClass('browser-default');
});

$('.look').click(function(){
	$.ajax({
        type: "post",
        url: "http://api.21-finance.com/api/ViewDocument",
        data: { AID: "<?echo $rcData[0]["rcCaseNo"]; ?>", Name: "<?echo $_SESSION['adminUserData']['aauName']; ?>", Page: "催收作業", Btn: "查看證件上傳", Ip: "<?echo $_SERVER["REMOTE_ADDR"]; ?>", LookDate: "<?echo date("Y-m-d H:i:s"); ?>" },
        success: function (data, status) {}
    });
});

 $('.print').click(function(){
	$.ajax({
        type: "post",
        url: "http://api.21-finance.com/api/ViewDocument",
        data: { AID: "<?echo $rcData[0]["rcCaseNo"]; ?>", Name: "<?echo $_SESSION['adminUserData']['aauName']; ?>", Page: "催收作業", Btn: "查看申請書", Ip: "<?echo $_SERVER["REMOTE_ADDR"]; ?>", LookDate: "<?echo date("Y-m-d H:i:s"); ?>" },
        success: function (data, status) {}
    });
});

</script>