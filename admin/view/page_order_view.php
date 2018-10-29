<?php 
require_once('model/require_general.php');

$or = new Orders();
$sr = new Status_Record();
$pm = new Product_Manage();
$pro = new Product();
$sup = new Supplier();
$mem = new Member();

$orNo = $_GET["orno"];
$method = $_GET["method"];

//欄位名稱
$columnName = $or->getAllColumnNames("orders");
// print_r($columnName);

//訂單
$orOrigData = $or->getOneOrderByNo($orNo);
$orData = $or->getOneOrderByNo($orNo);
$or->changeToReadable($orData[0],$method);

//聯絡人陣列
$relaNameArr = is_array(json_decode($orData[0]["orAppContactRelaName"])) ? json_decode($orData[0]["orAppContactRelaName"]) : "";
$relaRelationArr = is_array(json_decode($orData[0]["orAppContactRelaRelation"])) ? json_decode($orData[0]["orAppContactRelaRelation"]) : "";
$relaPhoneArr = is_array(json_decode($orData[0]["orAppContactRelaPhone"])) ? json_decode($orData[0]["orAppContactRelaPhone"]) : "";
$relaCellArr = is_array(json_decode($orData[0]["orAppContactRelaCell"])) ? json_decode($orData[0]["orAppContactRelaCell"]) : "";

$frdNameArr = is_array(json_decode($orData[0]["orAppContactFrdName"])) ? json_decode($orData[0]["orAppContactFrdName"]) : "";
$frdRelationArr = is_array(json_decode($orData[0]["orAppContactFrdRelation"])) ? json_decode($orData[0]["orAppContactFrdRelation"]) : "";
$frdPhoneArr = is_array(json_decode($orData[0]["orAppContactFrdPhone"])) ? json_decode($orData[0]["orAppContactFrdPhone"]) : "";
$frdCellArr = is_array(json_decode($orData[0]["orAppContactFrdCell"])) ? json_decode($orData[0]["orAppContactFrdCell"]) : "";

//商品上架
$pmData = $pm->getOnePMByNo($orData[0]["pmNo"]);

//商品
$proData = $pro->getOneProByNo($pmData[0]["proNo"]);

//供應商
$supData = $sup->getOneSupplierByNo($orData[0]["supNo"]);

//會員
$memData = $mem->getOneMemberByNo($orData[0]["memNo"]);
$mem->changeToReadable($memData[0]);

//全部有供應該商品的供應商
$allAvailSup = $pm->getAllByProName($pmData[0]["proNo"]);

//正在使用的商品上架
$curPmInOr = $pm->getOnePMBySupAndPro($pmData[0]["proNo"], $supData[0]["supNo"]);

$memberClass_array = array('0'=>'學生','1'=>'上班族','2'=>'家管','3'=>'其他','無'=>'無','4'=>'非學生','學生'=>'學生');
?>
<style>
#jump-menu ul li{
	display:inline-block;
	margin:5px 20px;
}
#jump-menu ul li a{
	color:blue;
	
}
@media print {
  body * {
    visibility: hidden;
  }
  .right_col, .right_col * {
    visibility: visible;
  }
  .right_col {
    position: absolute;
    left: 0;
    top: 0;
  }
  .right_col .form-group label, .right_col .form-group h5{
  	margin:0;
  	padding:0;
  }
  .right_col .form-group label{
  	text-decoration:underline;
  	font-size:18px;
  	line-height:18px;
  	padding-left:10px;
  }
  .right_col .form-group h5{
  	line-height:18px;
  	padding-left:10px;
  }
  .right_col .form-group,.right_col .form-group *{
  	display:inline-block;
  	width:auto !important;
  }
  .x_panel{
  	border:none;
  }
  #print{
  	visibility: hidden !important;
  }
}

</style>
			<div class="black-box">
				<div class="warning-box">
					<div class="warning-text">
						
					</div>
				</div>
			</div>
<!-- page content -->
      <div class="right_col" role="main" style="min-height: 949px;">
        <div class="">
			
          <div class="page-title">
            <div class="title_left">
              <h3><?php if($method==1){echo "分期";}else{echo "直購"; }?>訂單明細(IP位址:<?php echo $orData[0]["orIpAddress"]; ?>)</h3>
              <br>
              <h3>訂單編號：<?php echo $orData[0]["orCaseNo"]; ?> 下單時身份：<font style="color:red"><?php echo $memberClass_array[$orData[0]["memClass"]] ?></font> 是否來自APP：
				<font style="color:red">
				<?php
					echo ($orData[0]['orIpAddress'] != "") ? "否":"是";
				?>
				</font>
			  </h3>
              <br>
              <div id="jump-menu">
              	<ul style="list-style:none;">
              		<?php if($method==1){ ?>
	              		<li><a href="#">一. 購物商品</a></li>
	              		<li><a href="#">二. 申請人資料</a></li>
	              		<li><a href="#">三. 收貨人資料</a></li>
	              		<li><a href="#">四. 是否需要統編</a></li>
	              		<li><a href="#">五. 聯絡人資訊</a></li>
	              		<li><a href="#">六. 備註</a></li>
	              		<li><a href="#">七. 證件上傳</a></li>
	              		<li><a href="#">八. 訂單處理時間</a></li>
              		<?php }else{?>
	              		<li><a href="#">一. 購物商品</a></li>
	              		<li><a href="#">二. 付款及配送</a></li>
	              		<li><a href="#">三. 訂單處理時間</a></li>
              		<?php }?>
              	</ul>
              </div>
            </div>
          </div>
          <div class="clearfix"></div>
          
          <div class="row">
           <form>
            <input type="hidden" name="orNo" value="<?php echo $orNo; ?>">
            <div class="col-md-12 col-sm-12 col-xs-12">
              <div class="x_panel">
                <div class="x_title">
                  <h2 style="text-align:center;float:none;">訂單處理狀況：
				  <?php if(in_array(61, $curRrightArr) && !in_array(60, $curRrightArr)){  ?>
				  
				  <?php }else{ ?>
				  <button class="btn btn-success confirm-save">確認儲存</button>
				  <?php }?>
				  </h2>
                  <?php if($method==1){?><h2 style="text-align:center;float:none;"><a href="view/print_order_details.php?orno=<?php echo $orNo; ?>" target="_blank" class="btn print btn-success">列印</a></h2><?php }?>
                  <div class="clearfix"></div>
                </div>
                <div class="x_content">
                  <br>
                  <div id="order-handle" class="form-horizontal form-label-left">
                  	<?php 
                  	foreach($columnName as $key=>$value){ 
                  		if(strrpos($value["COLUMN_NAME"], "orHandle") !== false){
                  	?>
                    <div class="form-group">
                      <label class="control-label col-md-3 col-sm-3 col-xs-12" for="first-name">
                      	<?php echo $value["COLUMN_COMMENT"]; ?> : 
                      </label>
                      <?php if(strrpos($value["COLUMN_NAME"], "Date") !== false){ ?>
                      <div class="col-md-6 col-sm-6 col-xs-12">
                      	<input type="text" class="date-picker form-control" name="<?php echo $value["COLUMN_NAME"]; ?>" value="<?php echo $orData[0][$value["COLUMN_NAME"]]; ?>">
                      </div>
                      <?php }else{?>
                      <div class="col-md-6 col-sm-6 col-xs-12">
                      	<input class="form-control" type="text" name="<?php echo $value["COLUMN_NAME"]; ?>" value="<?php echo $orData[0][$value["COLUMN_NAME"]]; ?>">
                      </div>
                      <?php } ?>
                    </div>
                    <?php 
                  		}
                  	} 
                  	?>
                    
                    <div style="margin:30px;"></div>
                  </div>
                </div>
              </div>
             </div>
             <div id="details" class="col-md-12 col-sm-12 col-xs-12">
              <div class="x_panel">
                <div class="x_title">
                  <h2 style="text-align:center;float:none;">申請人明細：</h2>
                  <div class="clearfix"></div>
                </div>
                <div class="x_content">
                 <div>
                 <div style="border-bottom:1px solid #DDD;">
	                <div class="col-md-12 col-sm-12 col-xs-12">
	                 <h4 style="float:left;">一.　購物商品：</h4>
	                 <?php if($method == 1){?>
					 
	                 <div style="text-align:center;">內部訂單編號：<input type="text" name="orInternalCaseNo" value="<?php echo $orData[0]["orInternalCaseNo"]; ?>"></div>
	                 <?php } ?>
	                </div>
	                <div class="clearfix"></div>
                  </div>
                  <br>
                  <div class="clearfix"></div>
                  <?php if($method == 1){?>
                  <div style="border-bottom:1px solid #DDD;" class="form-horizontal form-label-left">
                    <div class="form-group">
                      <label class="control-label col-md-3 col-sm-3 col-xs-12" for="first-name">
                      	訂單狀態 : 
                      </label>
                      <div class="col-md-6 col-sm-6 col-xs-12">
	                      <select class="form-control" name="orStatus">
	                      	<?php 
							foreach($or->statusArr as $key=>$value){
								if(in_array(61, $curRrightArr) && !in_array(60, $curRrightArr)){ 
									if($key == 1 || $key == 2 || $key == 5 || $key == 6){
							?>
	                      		<option <?php if($orData[0]["orStatus"] == $value) echo "selected"; ?> value="<?php echo $key; ?>"><?php echo $value; ?></option>
							<?php 
									}
								}else{
							?>
								<option <?php if($orData[0]["orStatus"] == $value) echo "selected"; ?> value="<?php echo $key; ?>"><?php echo $value; ?></option>
							<?php
								}
							}
							?>
	                      </select>
                      </div>
                    </div>
					<div class="form-group">
                      <label class="control-label col-md-3 col-sm-3 col-xs-12" for="first-name">
                      	補件原因 : 
                      </label>
					  <h5 class="col-md-6 col-sm-6 col-xs-12" style="color:#999;">
                      	<?php 
						if(is_numeric($orData[0]["orDocProvideReason"])){
							echo $or->reasonArr[$orData[0]["orDocProvideReason"]]; 
						}else{
							echo $orData[0]["orDocProvideReason"]; 
						}
						?>
                      </h5>
					</div>
					<div class="form-group">
                      <label class="control-label col-md-3 col-sm-3 col-xs-12" for="first-name">
                      	補件備註 : 
                      </label>
					  <h5 class="col-md-6 col-sm-6 col-xs-12" style="color:#999;">
						<textarea class="form-control" name="orDocProvideComment"><?php echo $orData[0]["orDocProvideComment"]; ?></textarea>
                      </h5>
					</div>
                    <div class="form-group">
                      <label class="control-label col-md-3 col-sm-3 col-xs-12" for="first-name">
                      	訂單編號 : 
                      </label>
                      <h5 class="col-md-6 col-sm-6 col-xs-12" style="color:#999;">
                      	<?php echo $orData[0]["orCaseNo"]; ?>
                      </h5>
                    </div>
                    <div class="form-group">
                      <label class="control-label col-md-3 col-sm-3 col-xs-12" for="first-name">
                      	商品名稱 : 
                      </label>
                      <h5 class="col-md-6 col-sm-6 col-xs-12" style="color:#999;">
                      	<?php echo $proData[0]["proName"]; ?>
                      </h5>
                    </div>
                    <div class="form-group">
                      <label class="control-label col-md-3 col-sm-3 col-xs-12" for="first-name">
                      	商品規格: 
                      </label>
                      <h5 class="col-md-6 col-sm-6 col-xs-12" style="color:#999;">
						<?php $color = explode('#',$proData[0]["proSpec"]); ?>
						<select class="form-control" name='orProSpec'>
							<?php 
								foreach($color as $key => $value){
									$checked = ($value == $orData[0]["orProSpec"]) ? 'selected':'';
									echo "<option value='".$value."' ".$checked .">".$value."</option>";
								}
							?>
						</select>
                      </h5>
                    </div>
                    <div class="form-group">
                      <label class="control-label col-md-3 col-sm-3 col-xs-12" for="first-name">
                      	月付金額 : 
                      </label>
                      <h5 class="col-md-6 col-sm-6 col-xs-12" style="color:#999;">
                      	<?php echo number_format($orData[0]["orPeriodTotal"]/$orData[0]["orPeriodAmnt"]); ?>
                      </h5>
                    </div>
                    <div class="form-group">
                      <label class="control-label col-md-3 col-sm-3 col-xs-12" for="first-name">
                      	期數 : 
                      </label>
                      <h5 class="col-md-6 col-sm-6 col-xs-12" style="color:#999;">
						<input class="form-control" type="text" name="orPeriodAmnt" value="<?php echo $orData[0]["orPeriodAmnt"]; ?>">
                      </h5>
                    </div>
                    <div class="form-group">
                      <label class="control-label col-md-3 col-sm-3 col-xs-12" for="first-name">
                      	分期總金額 : 
                      </label>
                      <h5 class="col-md-6 col-sm-6 col-xs-12" style="color:#999;">
					    <input class="form-control" type="text" name="orPeriodTotal" value="<?php echo $orData[0]["orPeriodTotal"]; ?>">
                      </h5>
                    </div>
					<div class="form-group">
                      <label class="control-label col-md-3 col-sm-3 col-xs-12" for="first-name">
                      	分期基礎價 : 
                      </label>
                      <h5 class="col-md-6 col-sm-6 col-xs-12" style="color:#999;">
					    <?php echo $orData[0]["pmPeriodAmnt"]; //jimmy 
              ?>
                      </h5>
                    </div>
                    <div class="form-group">
                      <label class="control-label col-md-3 col-sm-3 col-xs-12" for="first-name">
                      	供應商名稱 : 
                      </label>
                      <div class="col-md-4 col-sm-4 col-xs-12">
	                      <select id="which-sup" class="form-control" name="supNo">
	                        <?php 
	                        foreach($allAvailSup as $key=>$value){
	                        	$supInData = $sup->getOneSupplierByNo($value["supNo"]);
	                        ?>
	                      	  <option <?php if($supInData[0]["supNo"] == $orData[0]["supNo"]) echo "selected"; ?> value="<?php echo $supInData[0]["supNo"]; ?>"><?php echo $supInData[0]["supName"]; ?></option>
	                        <?php 
	                        }
	                        ?>
	                      </select>
                      </div>
                      
                      <div id="not-avail-msg" <?php if($curPmInOr[0]["pmStatus"] != 2) echo "style='display:none;'" ?> class="col-md-2 col-sm-2 col-xs-12">
                      	<span style="color:red;">*(此商品在該供應商缺貨中)</span>
                      </div>
                    </div>
					<div class="form-group">
                      <label class="control-label col-md-3 col-sm-3 col-xs-12" for="first-name">
                      	供貨價 : 
                      </label>
                      <h5 class="col-md-6 col-sm-6 col-xs-12" style="color:#999;">                     					    
<!-- 					    <input class="form-control" type="text" name="orSupPrice" value="<?php echo $orData[0]["orSupPrice"]; ?>">
 -->   
<!--sander add-->
                      <input class="form-control" type="text" name="orSupPrice" value=
                          "<?php if ($orData[0]["orSupPrice"] != 0){
                                     echo $orData[0]["orSupPrice"];
                                 }else{
                                     echo floor($orData[0]["pmPeriodAmnt"] *0.95);
                                 }  
                            ?>"
                      > <input class="form-control" type="text" name="orSupPrice" value="<?php echo $orData[0]["orSupPrice"]; ?>">                     
                      </h5>
                    </div>
                    <div style="margin:30px;"></div>
                  </div>
                  <?php }else{?>
                  <div style="border-bottom:1px solid #DDD;" class="form-horizontal form-label-left">
                    <div class="form-group">
                      <label class="control-label col-md-3 col-sm-3 col-xs-12" for="first-name">
                      	訂單狀態 : 
                      </label>
                      <div class="col-md-6 col-sm-6 col-xs-12">
	                      <select class="form-control" name="orStatus">
	                      	<?php foreach($or->statusDirectArr as $key=>$value){ ?>
	                      		<option <?php if($orData[0]["orStatus"] == $value) echo "selected"; ?> value="<?php echo $key; ?>"><?php echo $value; ?></option>
							<?php } ?>
	                      </select>
                      </div>
                    </div>
                    <div class="form-group">
                      <label class="control-label col-md-3 col-sm-3 col-xs-12" for="first-name">
                      	訂單編號 : 
                      </label>
                      <h5 class="col-md-6 col-sm-6 col-xs-12" style="color:#999;">
                      	<?php echo $orData[0]["orCaseNo"]; ?>
                      </h5>
                    </div>
                    <div class="form-group">
                      <label class="control-label col-md-3 col-sm-3 col-xs-12" for="first-name">
                      	商品名稱 : 
                      </label>
                      <h5 class="col-md-6 col-sm-6 col-xs-12" style="color:#999;">
                      	<?php echo $proData[0]["proName"]; ?>
                      </h5>
                    </div>
                    <div class="form-group">
                      <label class="control-label col-md-3 col-sm-3 col-xs-12" for="first-name">
                      	商品規格: 
                      </label>
                      <h5 class="col-md-6 col-sm-6 col-xs-12" style="color:#999;">
                      	<?php echo $orData[0]["orProSpec"]; ?>
                      </h5>
                    </div>
                    <div class="form-group">
                      <label class="control-label col-md-3 col-sm-3 col-xs-12" for="first-name">
                      	單價 : 
                      </label>
                      <h5 class="col-md-6 col-sm-6 col-xs-12" style="color:#999;">
                      	<?php echo number_format($pmData[0]["pmDirectAmnt"]); ?>
                      </h5>
                    </div>
                    <div class="form-group">
                      <label class="control-label col-md-3 col-sm-3 col-xs-12" for="first-name">
                      	數量 : 
                      </label>
                      <h5 class="col-md-6 col-sm-6 col-xs-12" style="color:#999;">
                      	<?php echo $orData[0]["orAmount"]; ?>
                      </h5>
                    </div>
                    <div class="form-group">
                      <label class="control-label col-md-3 col-sm-3 col-xs-12" for="first-name">
                      	總金額 : 
                      </label>
                      <h5 class="col-md-6 col-sm-6 col-xs-12" style="color:#999;">
                      	<?php echo number_format($pmData[0]["pmDirectAmnt"]*$orData[0]["orAmount"]); ?>
                      </h5>
                    </div>
                    <div class="form-group">
                      <label class="control-label col-md-3 col-sm-3 col-xs-12" for="first-name">
                      	供應商名稱 : 
                      </label>
                      <div class="col-md-4 col-sm-4 col-xs-12">
	                      <select id="which-sup" class="form-control" name="supNo">
	                        <?php 
	                        foreach($allAvailSup as $key=>$value){
	                        	$supInData = $sup->getOneSupplierByNo($value["supNo"]);
	                        ?>
	                      	  <option <?php if($supInData[0]["supNo"] == $orData[0]["supNo"]) echo "selected"; ?> value="<?php echo $supInData[0]["supNo"]; ?>"><?php echo $supInData[0]["supName"]; ?></option>
	                        <?php 
	                        }
	                        ?>
	                      </select>
                      </div>
                      
                      <div id="not-avail-msg" <?php if($curPmInOr[0]["pmStatus"] != 2) echo "style='display:none;'" ?> class="col-md-2 col-sm-2 col-xs-12">
                      	<span style="color:red;">*(此商品在該供應商缺貨中)</span>
                      </div>
                    </div>
					<div class="form-group">
                      <label class="control-label col-md-3 col-sm-3 col-xs-12" for="first-name">
                      	供貨價 : 
                      </label>
                      <h5 class="col-md-6 col-sm-6 col-xs-12" style="color:#999;">         
                        <!--sander add-->
                        <?php if ($orData[0]["orSupPrice"] != 0){
                                     echo $orData[0]["orSupPrice"];
                                 }else{
                                     echo floor($orData[0]["pmPeriodAmnt"] *0.95);
                                 }  
                        ?> 
                      </h5>
                    </div>
                    <div style="margin:30px;"></div>
                  </div>
                  <?php }?>
                  <br>
                 </div> 
                 <?php if($method==1){?>
                 <div>
                  <div style="border-bottom:1px solid #DDD;">
	                  <div class="col-md-12 col-sm-12 col-xs-12">
	                  	<h4>二.　申請人資料：</h4>
	                  </div>
	                  <div class="clearfix"></div>
                  </div>
                  <br>
                  <div class="clearfix"></div>
                  <div style="border-bottom:1px solid #DDD;" class="form-horizontal form-label-left">
                  	<div class="form-group">
						<label class="control-label col-md-3 col-sm-3 col-xs-12" for="first-name">
							<input name="orIfSecret" type="checkbox" value='1' <?php echo ($orData['0']["orIfSecret"] == '1') ? 'checked':''; ?>> 
						</label>
						<h5 class="col-md-6 col-sm-6 col-xs-12" style="color:#999;">
							申請案件如需保密請打勾（照會親友聯絡人時，不告知購買事由）
						</h5>
					</div>
					<div class="form-group">
                      <label class="control-label col-md-3 col-sm-3 col-xs-12" for="first-name">
                      	申請人姓名 : 
                      </label>
                      <h5 class="col-md-6 col-sm-6 col-xs-12" style="color:#999;">
                      	<?php echo $memData[0]["memName"]; ?>
                      </h5>
                    </div>
                    <div class="form-group">
                      <label class="control-label col-md-3 col-sm-3 col-xs-12" for="first-name">
                      	身分證字號 : 
                      </label>
                      <h5 class="col-md-6 col-sm-6 col-xs-12" style="color:#999;">
                      	<?php echo $memData[0]["memIdNum"]; ?>
                      </h5>
                    </div>
                    <div class="form-group">
                      <label class="control-label col-md-3 col-sm-3 col-xs-12" for="first-name">
                      	出生日期 : 
                      </label>
                      <h5 class="col-md-6 col-sm-6 col-xs-12" style="color:#999;">
                      	<?php echo $memData[0]["memBday"]; ?>
                      </h5>
                    </div>
                    <div class="form-group">
                      <label class="control-label col-md-3 col-sm-3 col-xs-12" for="first-name">
                      	身分別 : 
                      </label>
                      <h5 class="col-md-6 col-sm-6 col-xs-12" style="color:#999;">
                      	<?php echo $memberClass_array[$orData[0]["memClass"]]; ?>
                      </h5>
                    </div>
                    <div class="form-group">
                      <label class="control-label col-md-3 col-sm-3 col-xs-12" for="first-name">
                      	學校 : 
                      </label>
                      <h5 class="col-md-6 col-sm-6 col-xs-12" style="color:#999;">
                      	<?php echo $memData[0]["memSchool"]; ?>
                      </h5>
                    </div>
                    <div class="form-group">
                      <label class="control-label col-md-3 col-sm-3 col-xs-12" for="first-name">
                      	Email : 
                      </label>
                      <h5 class="col-md-6 col-sm-6 col-xs-12" style="color:#999;">
                      	<?php echo $memData[0]["memAccount"]; ?>
                      	<?php if($memData[0]["memEmailAuthen"] == "尚未"){ ?>
	                      <span style="color:red;margin:0 10px;">*尚未驗證</span>
	                      <a class="send-authen" style="color:blue;margin:0 10px;text-decoration:underline;" href="#">重發認證信</a>
	                    <?php } ?>
                      </h5>
                    </div>
                    <div class="form-group">
                      <label class="control-label col-md-3 col-sm-3 col-xs-12" for="first-name">
                      	手機 : 
                      </label>
                      <h5 class="col-md-6 col-sm-6 col-xs-12" style="color:#999;">
                      	<?php echo $memData[0]["memCell"]; ?>
                      </h5>
                    </div>
                    <div class="form-group">
                      <label class="control-label col-md-3 col-sm-3 col-xs-12" for="first-name">
                      	現住電話 : 
                      </label>
                      <h5 class="col-md-6 col-sm-6 col-xs-12" style="color:#999;">
						<input class="form-control" type="text" name="memPhone" value="<?php echo $memData[0]["memPhone"]; ?>">
                      </h5>
                    </div>
                    <div class="form-group">
                      <label class="control-label col-md-3 col-sm-3 col-xs-12" for="first-name">
                      	現住地址 : 
                      </label>
                      <h5 class="col-md-6 col-sm-6 col-xs-12" style="color:#999;">
                      	<input class="form-control" type="text" name="memAddr" value="<?php echo $memData[0]["memAddr"]; ?>">
                      </h5>
                    </div>
                  	<?php 
                  	foreach($columnName as $key=>$value){ 
                  		if(strrpos($value["COLUMN_NAME"], "orAppApplier") !== false){
                  	?>
                    <div class="form-group">
                      <label class="control-label col-md-3 col-sm-3 col-xs-12" for="first-name">
                      	<?php echo $value["COLUMN_COMMENT"]; ?> : 
                      </label>
                      <h5 class="col-md-6 col-sm-6 col-xs-12" style="color:#999;">
                      	<?php
                      	if(is_numeric($orData[0][$value["COLUMN_NAME"]]) && $value["COLUMN_NAME"] != "orAppApplierBirthPhone"){
                      		if($value["COLUMN_NAME"] == 'orAppApplierMonthSalary'){
								if($memData[0]["memSalary"] != ""){
									echo $memData[0]["memSalary"]; 
								}else{
									echo number_format($orData[0][$value["COLUMN_NAME"]]); 
								}
							}else{
								if($value["COLUMN_NAME"] == 'orAppApplierCompanystatus'){
									echo ($orData[0][$value["COLUMN_NAME"]] == '0') ? '無工作':'有工作';
								}else{
									echo ($orData[0][$value["COLUMN_NAME"]]);
								}
							}
                      	}else{
							if($value["COLUMN_NAME"] == 'orAppApplierCompanyName'){
								if($memData[0]["memCompanyName"] != ""){
									echo $memData[0]["memCompanyName"];
								}else{
									echo $orData[0][$value["COLUMN_NAME"]];
								}
							}else if($value["COLUMN_NAME"] == 'orAppApplierMonthSalary'){
								if($memData[0]["memSalary"] != ""){
									echo $memData[0]["memSalary"]; 
								}else{
									echo number_format($orData[0][$value["COLUMN_NAME"]]); 
								}
							}else if($value["COLUMN_NAME"] == 'orAppApplierYearExperience'){
								if($memData[0]["memYearWorked"] != ""){
									echo $memData[0]["memYearWorked"];
								}else{
									echo $orData[0][$value["COLUMN_NAME"]];
								}
							}else if($value["COLUMN_NAME"] == 'orAppApplierCreditNum'){
								echo ($orData[0][$value["COLUMN_NAME"]] == '---') ? '':$orData[0][$value["COLUMN_NAME"]];
							}else if($value["COLUMN_NAME"] == 'orAppApplierBirthPhone' || $value["COLUMN_NAME"] == 'orAppApplierBirthAddr'){
						?>
						<input class="form-control" type="text" name="<?php echo $value["COLUMN_NAME"]; ?>" value="<?php echo $orData[0][$value["COLUMN_NAME"]]; ?>">
						<?php
							}else{
									echo $orData[0][$value["COLUMN_NAME"]];
								
							}
                      		
                      	}
                      	?>
                      </h5>
                    </div>
                    <?php 
                  		}
                  	} 
                  	?>
                    <div style="margin:30px;"></div>
                  </div>
                  <br>
                 </div>
                 
                 
                 <div>
                  <div style="border-bottom:1px solid #DDD;">
	                  <div class="col-md-12 col-sm-12 col-xs-12">
	                  	<h4>三.　收貨人資料：</h4>
	                  </div>
	                  <div class="clearfix"></div>
                  </div>
                  <br>
                  <div class="clearfix"></div>
                  <div style="border-bottom:1px solid #DDD;" class="form-horizontal form-label-left">
                  	<?php 
                  	foreach($columnName as $key=>$value){ 
                  		if(strrpos($value["COLUMN_NAME"], "orReceive") !== false){
                  	?>
                    <div class="form-group">
                      <label class="control-label col-md-3 col-sm-3 col-xs-12" for="first-name">
                      	<?php echo $value["COLUMN_COMMENT"]; ?> : 
                      </label>
                      <?php if(strrpos($value["COLUMN_NAME"], "Comment") !== false){ ?>
                      <div class="col-md-6 col-sm-6 col-xs-12">
                      	<textarea style="height:150px;" class="form-control" name="<?php echo $value["COLUMN_NAME"]; ?>"><?php echo $orData[0][$value["COLUMN_NAME"]]; ?></textarea>
                      </div>
                      <?php }else{?>
                      <div class="col-md-6 col-sm-6 col-xs-12">
                      	<input class="form-control" type="text" name="<?php echo $value["COLUMN_NAME"]; ?>" value="<?php echo $orData[0][$value["COLUMN_NAME"]]; ?>">
                      </div>
                      <?php } ?>
                    </div>
                    <?php 
                  		}
                  	} 
                  	?>
                    <div style="margin:30px;"></div>
                  </div>
                  <br>
                 </div>
                 
                 <div>
                  <div style="border-bottom:1px solid #DDD;">
	                  <div class="col-md-12 col-sm-12 col-xs-12">
	                  	<h4>四.　是否需要統編：</h4>
	                  </div>
	                  <div class="clearfix"></div>
                  </div>
                  <br>
                  <div class="clearfix"></div>
                  <div style="border-bottom:1px solid #DDD;" class="form-horizontal form-label-left">
                  	<?php 
                  	foreach($columnName as $key=>$value){ 
                  		if(strrpos($value["COLUMN_NAME"], "orBusinessNum") !== false){
                  	?>
                    <div class="form-group business-num-area">
                      <label class="control-label col-md-3 col-sm-3 col-xs-12" for="first-name">
                      	<?php echo $value["COLUMN_COMMENT"]; ?> : 
                      </label>
                      <?php if(strrpos($value["COLUMN_NAME"], "If") !== false){ ?>
                      	<div class="col-md-6 col-sm-6 col-xs-12 radio">
                      	  <label style="margin-right:20px;">
                      	    <input <?php if($orData[0][$value["COLUMN_NAME"]] == 1) echo "checked"; ?> type="radio" name="<?php echo $value["COLUMN_NAME"]; ?>" value="1">是
                      	  </label>
                      	  <label>
                            <input <?php if($orData[0][$value["COLUMN_NAME"]] == 0) echo "checked"; ?> type="radio" name="<?php echo $value["COLUMN_NAME"]; ?>" value="0">否
                          </label>
                        </div>
                      <?php }else{ ?>
                      <div class="col-md-6 col-sm-6 col-xs-12">
                      	<input class="form-control" type="text" name="<?php echo $value["COLUMN_NAME"]; ?>" value="<?php echo $orData[0][$value["COLUMN_NAME"]]; ?>">
                      </div>
                      <?php } ?>
                    </div>
                    <?php 
                  		}
                  	} 
                  	?>
                    <div style="margin:30px;"></div>
                  </div>
                  <br>
                 </div>
                 
                 <div>
                  <div style="border-bottom:1px solid #DDD;">
	                  <div class="col-md-12 col-sm-12 col-xs-12">
	                  	<h4>五.　聯絡人資訊：</h4>
	                  </div>
	                  <div class="clearfix"></div>
                  </div>
                  <br>
                  <div class="clearfix"></div>
                  <div style="border-bottom:1px solid #DDD;" class="form-horizontal form-label-left">
					<?php 
					if(!empty($relaNameArr)){
						foreach($relaNameArr as $key=>$value){ 
					?>
                    <div class="form-group">
                      <label class="control-label col-md-3 col-sm-3 col-xs-12" for="first-name">
                      	親屬姓名 : 
                      </label>
                      <h5 class="col-md-6 col-sm-6 col-xs-12" style="color:#999;">
                      	<?php echo $value; ?>
                      </h5>
                    </div>
					<div class="form-group">
                      <label class="control-label col-md-3 col-sm-3 col-xs-12" for="first-name">
                      	親屬關係 : 
                      </label>
                      <h5 class="col-md-6 col-sm-6 col-xs-12" style="color:#999;">
                      	<?php echo $relaRelationArr[$key]; ?>
                      </h5>
                    </div>
					<div class="form-group">
                      <label class="control-label col-md-3 col-sm-3 col-xs-12" for="first-name">
                      	市內電話 : 
                      </label>
                      <h5 class="col-md-6 col-sm-6 col-xs-12" style="color:#999;">
                      	<?php echo $relaPhoneArr[$key]; ?>
                      </h5>
                    </div>
					<div class="form-group">
                      <label class="control-label col-md-3 col-sm-3 col-xs-12" for="first-name">
                      	行動電話 : 
                      </label>
                      <h5 class="col-md-6 col-sm-6 col-xs-12" style="color:#999;">
                      	<?php echo $relaCellArr[$key]; ?>
                      </h5>
                    </div>
                  	<div style="border-bottom:1px solid #aaa;margin:20px auto; width:80%"></div>	
					<?php
						}
					}else{
					?>
					<div class="form-group">
                      <label class="control-label col-md-3 col-sm-3 col-xs-12" for="first-name">
                      	親屬姓名 : 
                      </label>
                      <h5 class="col-md-6 col-sm-6 col-xs-12" style="color:#999;">
                      	
                      </h5>
                    </div>
					<div class="form-group">
                      <label class="control-label col-md-3 col-sm-3 col-xs-12" for="first-name">
                      	親屬關係 : 
                      </label>
                      <h5 class="col-md-6 col-sm-6 col-xs-12" style="color:#999;">
                      	
                      </h5>
                    </div>
					<div class="form-group">
                      <label class="control-label col-md-3 col-sm-3 col-xs-12" for="first-name">
                      	市內電話 : 
                      </label>
                      <h5 class="col-md-6 col-sm-6 col-xs-12" style="color:#999;">
                      	
                      </h5>
                    </div>
					<div class="form-group">
                      <label class="control-label col-md-3 col-sm-3 col-xs-12" for="first-name">
                      	行動電話 : 
                      </label>
                      <h5 class="col-md-6 col-sm-6 col-xs-12" style="color:#999;">
                      	
                      </h5>
                    </div>
                  	<div style="border-bottom:1px solid #aaa;margin:20px auto; width:80%"></div>	
					<?php
					}
					?>
					
					<?php 
					if(!empty($frdNameArr)){
						foreach($frdNameArr as $key=>$value){ 
					?>
                    <div class="form-group">
                      <label class="control-label col-md-3 col-sm-3 col-xs-12" for="first-name">
                      	朋友姓名 : 
                      </label>
                      <h5 class="col-md-6 col-sm-6 col-xs-12" style="color:#999;">
                      	<?php echo $value; ?>
                      </h5>
                    </div>
					<div class="form-group">
                      <label class="control-label col-md-3 col-sm-3 col-xs-12" for="first-name">
                      	朋友關係 : 
                      </label>
                      <h5 class="col-md-6 col-sm-6 col-xs-12" style="color:#999;">
                      	<?php echo $frdRelationArr[$key]; ?>
                      </h5>
                    </div>
					<div class="form-group">
                      <label class="control-label col-md-3 col-sm-3 col-xs-12" for="first-name">
                      	市內電話 : 
                      </label>
                      <h5 class="col-md-6 col-sm-6 col-xs-12" style="color:#999;">
                      	<?php echo $frdPhoneArr[$key]; ?>
                      </h5>
                    </div>
					<div class="form-group">
                      <label class="control-label col-md-3 col-sm-3 col-xs-12" for="first-name">
                      	行動電話 : 
                      </label>
                      <h5 class="col-md-6 col-sm-6 col-xs-12" style="color:#999;">
                      	<?php echo $frdCellArr[$key]; ?>
                      </h5>
                    </div>
					<div style="border-bottom:1px solid #aaa;margin:20px auto; width:80%"></div>
					<?php
						}
					}else{
					?>
					<div class="form-group">
                      <label class="control-label col-md-3 col-sm-3 col-xs-12" for="first-name">
                      	朋友姓名 : 
                      </label>
                      <h5 class="col-md-6 col-sm-6 col-xs-12" style="color:#999;">
                      	
                      </h5>
                    </div>
					<div class="form-group">
                      <label class="control-label col-md-3 col-sm-3 col-xs-12" for="first-name">
                      	朋友關係 : 
                      </label>
                      <h5 class="col-md-6 col-sm-6 col-xs-12" style="color:#999;">
                      	
                      </h5>
                    </div>
					<div class="form-group">
                      <label class="control-label col-md-3 col-sm-3 col-xs-12" for="first-name">
                      	市內電話 : 
                      </label>
                      <h5 class="col-md-6 col-sm-6 col-xs-12" style="color:#999;">
                      	
                      </h5>
                    </div>
					<div class="form-group">
                      <label class="control-label col-md-3 col-sm-3 col-xs-12" for="first-name">
                      	行動電話 : 
                      </label>
                      <h5 class="col-md-6 col-sm-6 col-xs-12" style="color:#999;">
                      	
                      </h5>
                    </div>
					<div style="border-bottom:1px solid #aaa;margin:20px auto; width:80%"></div>
					<?php
					}
					?>
					
                    <div style="margin:30px;"></div>
                  </div>
                  <br>
                 </div>
                 
                 <div>
                  <div style="border-bottom:1px solid #DDD;">
	                  <div class="col-md-12 col-sm-12 col-xs-12">
	                  	<h4>六.　備註：</h4>
	                  </div>
	                  <div class="clearfix"></div>
                  </div>
                  <br>
                  <div class="clearfix"></div>
                  <div style="border-bottom:1px solid #DDD;" class="form-horizontal form-label-left">
                  	<?php 
                  	foreach($columnName as $key=>$value){ 
                  		if(strrpos($value["COLUMN_NAME"], "orAppExtra") !== false){
                  	?>
                    <div class="form-group">
                      <label class="control-label col-md-3 col-sm-3 col-xs-12" for="first-name">
                      	<?php echo $value["COLUMN_COMMENT"]; ?> : 
                      </label>
                      <h5 class="col-md-6 col-sm-6 col-xs-12" style="color:#999;">
                      	<?php echo $orData[0][$value["COLUMN_NAME"]]; ?>
                      </h5>
                    </div>
                    <?php 
                  		}
                  	} 
                  	?>
                    <div style="margin:30px;"></div>
                  </div>
                  <br>
                 </div>
                 
                 <div>
                  <div style="border-bottom:1px solid #DDD;">
	                  <div class="col-md-12 col-sm-12 col-xs-12">
	                  	<h4>七.　證件上傳：</h4>
	                  </div>
	                  <div class="clearfix"></div>
                  </div>
                  <br>
                  <div class="clearfix"></div>
                  <div style="border-bottom:1px solid #DDD;" class="form-horizontal form-label-left">
                  	<?php
                  	foreach($columnName as $key=>$value){ 
                  		if(strrpos($value["COLUMN_NAME"], "orAppAuthen") !== false){
                  	?>
                    <div class="form-group">
                      <label class="control-label col-md-3 col-sm-3 col-xs-12" for="first-name">
                      	<?php echo $value["COLUMN_COMMENT"]; ?> : 
                      </label>
                      <?php if(strrpos($value["COLUMN_NAME"], "Provement") !== false || strrpos($value["COLUMN_NAME"], "PromiseLetter") !== false){ ?>
                      	<div class="col-md-9 col-sm-9 col-xs-12">
                      	  <img style="margin:20px 0;display:block" src="<?php echo "../".$orData[0][$value["COLUMN_NAME"]]; ?>">
                      	</div>
                      <?php 
					  }else if(strrpos($value["COLUMN_NAME"], "ExtraInfo") !== false ){
						$orAppAuthenExtraInfoArr = array_filter( json_decode($orData[0][$value["COLUMN_NAME"]],true)?:[] );
						if(!empty($orAppAuthenExtraInfoArr)){
					  ?>
					  <div class="col-md-9 col-sm-9 col-xs-12">
					  <?php
							foreach($orAppAuthenExtraInfoArr as $key=>$value){
					  ?>
						<div class="col-md-12 col-sm-12 col-xs-12">
                          <img style="margin:20px 0;width:80%;display:block" src="<?php echo "../".$value; ?>">
                        </div>
					  <?php
							}
					  ?>
					  </div>
					  <?php
						}else{
					  ?>
                        <div class="col-md-9 col-sm-9 col-xs-12">
                          <img style="margin:20px 0;width:80%;display:block" src="<?php echo "../".$orData[0][$value["COLUMN_NAME"]]; ?>">
                        </div>
                      <?php 
						}
					  }else{ 
					  ?>
					    <div class="col-md-9 col-sm-9 col-xs-12">
                          <img style="margin:20px 0;width:40%;display:block" src="<?php echo "../".$orData[0][$value["COLUMN_NAME"]]; ?>">
                        </div>
					  <?php 
							
						}
					 
					  ?>
                    </div>
                    <?php 
                  		}
                  	}
				
                  	?>
                    <div style="margin:30px;"></div>
                  </div>
                  <br>
                 </div>
                 
                 <div>
                  <div style="border-bottom:1px solid #DDD;">
	                  <div class="col-md-12 col-sm-12 col-xs-12">
	                  	<h4>八.　訂單處理時間：</h4>
	                  </div>
	                  <div class="clearfix"></div>
                  </div>
                  <br>
                  <div class="clearfix"></div>
                  <div style="border-bottom:1px solid #DDD;" class="form-horizontal form-label-left">
                  	<?php 
                  	foreach($columnName as $key=>$value){ 
                  		if(strrpos($value["COLUMN_NAME"], "orReportPeriod") !== false){
                  	?>
                    <div class="form-group">
                      <label class="control-label col-md-3 col-sm-3 col-xs-12" for="first-name">
                      	<?php echo $value["COLUMN_COMMENT"]; ?> : 
                      </label>
                      <h5 class="col-md-6 col-sm-6 col-xs-12" style="color:#999;">
                      	<?php echo $orData[0][$value["COLUMN_NAME"]]; ?>
                      </h5>
                    </div>
                    <?php 
                  		}
                  	} 
                  	?>
                    <div style="margin:30px;"></div>
                  </div>
                  <br>
                 </div>
                 
                 <?php }else{?>
                 <div>
                  <div style="border-bottom:1px solid #DDD;">
	                  <div class="col-md-12 col-sm-12 col-xs-12">
	                  	<h4>二.　付款及配送：</h4>
	                  </div>
	                  <div class="clearfix"></div>
                  </div>
                  <br>
                  <div class="clearfix"></div>
                  <div style="border-bottom:1px solid #DDD;" class="form-horizontal form-label-left">
                  	<div class="form-group">
                  		<h4 class="control-label col-md-2 col-sm-2 col-xs-12">1. 付款方式</h4>
                  	</div>
                    <div class="form-group">
                      <label class="control-label col-md-3 col-sm-3 col-xs-12" for="first-name">
                      	付款方式 : 
                      </label>
                      <h5 class="col-md-6 col-sm-6 col-xs-12" style="color:#999;">
                      	<?php echo ($orData[0]["orPayBy"] == '1') ? 'ATM轉帳':'Web ATM轉帳'; ?>
                      </h5>
                    </div>
                    <div style="margin:30px;"></div>
                    <div class="form-group">
                  		<h4 class="control-label col-md-2 col-sm-2 col-xs-12">2. 是否需要統編</h4>
                  	</div>
                  	<?php 
                  	foreach($columnName as $key=>$value){ 
                  		if(strrpos($value["COLUMN_NAME"], "orBusinessNum") !== false){
                  	?>
                    <div class="form-group business-num-area">
                      <label class="control-label col-md-3 col-sm-3 col-xs-12" for="first-name">
                      	<?php echo $value["COLUMN_COMMENT"]; ?> : 
                      </label>
                      <?php if(strrpos($value["COLUMN_NAME"], "If") !== false){ ?>
                      	<div class="col-md-6 col-sm-6 col-xs-12 radio">
                      	  <label style="margin-right:20px;">
                      	    <input <?php if($orData[0][$value["COLUMN_NAME"]] == 1) echo "checked"; ?> type="radio" name="<?php echo $value["COLUMN_NAME"]; ?>" value="1">是
                      	  </label>
                      	  <label>
                            <input <?php if($orData[0][$value["COLUMN_NAME"]] == 0) echo "checked"; ?> type="radio" name="<?php echo $value["COLUMN_NAME"]; ?>" value="0">否
                          </label>
                        </div>
                      <?php }else{ ?>
                      <div class="col-md-6 col-sm-6 col-xs-12">
                      	<input class="form-control" type="text" name="<?php echo $value["COLUMN_NAME"]; ?>" value="<?php echo $orData[0][$value["COLUMN_NAME"]]; ?>">
                      </div>
                      <?php } ?>
                    </div>
                    <?php 
                  		}
                  	} 
                  	?>
                    <div style="margin:30px;"></div>
                    <div class="form-group">
                  		<h4 class="control-label col-md-2 col-sm-2 col-xs-12">3. 配送方式</h4>
                  	</div>
                    <div class="form-group">
                      <label class="control-label col-md-3 col-sm-3 col-xs-12" for="first-name">
                      	配送方式 : 
                      </label>
                      <h5 class="col-md-6 col-sm-6 col-xs-12" style="color:#999;">
                      	<?php echo "宅配"; ?>
                      </h5>
                    </div>
                    <div style="margin:30px;"></div>
                    <div class="form-group">
                  		<h4 class="control-label col-md-2 col-sm-2 col-xs-12">4. 訂購人資訊</h4>
                  	</div>
                    <div class="form-group">
                      <label class="control-label col-md-3 col-sm-3 col-xs-12" for="first-name">
                      	訂購人姓名 : 
                      </label>
                      <h5 class="col-md-6 col-sm-6 col-xs-12" style="color:#999;">
                      	<?php echo $memData[0]["memName"]; ?>
                      </h5>
                    </div>
                    <div class="form-group">
                      <label class="control-label col-md-3 col-sm-3 col-xs-12" for="first-name">
                      	訂購人地址 : 
                      </label>
                      <h5 class="col-md-6 col-sm-6 col-xs-12" style="color:#999;">
                      	<?php echo $memData[0]["memAddr"]; ?>
                      </h5>
                    </div>
                    <div class="form-group">
                      <label class="control-label col-md-3 col-sm-3 col-xs-12" for="first-name">
                      	訂購人電話 : 
                      </label>
                      <h5 class="col-md-6 col-sm-6 col-xs-12" style="color:#999;">
                      	<?php echo $memData[0]["memPhone"]; ?>
                      </h5>
                    </div>
                    <div class="form-group">
                      <label class="control-label col-md-3 col-sm-3 col-xs-12" for="first-name">
                      	訂購人手機 : 
                      </label>
                      <h5 class="col-md-6 col-sm-6 col-xs-12" style="color:#999;">
                      	<?php echo $memData[0]["memCell"]; ?>
                      </h5>
                    </div>
                    <div style="margin:30px;"></div>
                    <div class="form-group">
                  		<h4 class="control-label col-md-2 col-sm-2 col-xs-12">5. 收貨人資訊</h4>
                  	</div>
                  	<?php 
                  	foreach($columnName as $key=>$value){ 
                  		if(strrpos($value["COLUMN_NAME"], "orReceive") !== false){
                  	?>
                    <div class="form-group">
                      <label class="control-label col-md-3 col-sm-3 col-xs-12" for="first-name">
                      	<?php echo $value["COLUMN_COMMENT"]; ?> : 
                      </label>
                      <?php if(strrpos($value["COLUMN_NAME"], "Comment") !== false){ ?>
                      <div class="col-md-6 col-sm-6 col-xs-12">
                      	<textarea style="height:150px;" class="form-control" name="<?php echo $value["COLUMN_NAME"]; ?>"><?php echo $orData[0][$value["COLUMN_NAME"]]; ?></textarea>
                      </div>
                      <?php }else{?>
                      <div class="col-md-6 col-sm-6 col-xs-12">
                      	<input class="form-control" type="text" name="<?php echo $value["COLUMN_NAME"]; ?>" value="<?php echo $orData[0][$value["COLUMN_NAME"]]; ?>">
                      </div>
                      <?php } ?>
                    </div>
                    <?php 
                  		}
                  	} 
                  	?>
                    <div style="margin:30px;"></div>
                  </div>
                  <br>
                 </div>
                 <div>
                  <div style="border-bottom:1px solid #DDD;">
	                  <div class="col-md-12 col-sm-12 col-xs-12">
	                  	<h4>三.　訂單處理時間：</h4>
	                  </div>
	                  <div class="clearfix"></div>
                  </div>
                  <br>
                  <div class="clearfix"></div>
                  <div style="border-bottom:1px solid #DDD;" class="form-horizontal form-label-left">
                  	<?php 
                  	foreach($columnName as $key=>$value){ 
                  		if(strrpos($value["COLUMN_NAME"], "orReportDirect") !== false){
                  	?>
                    <div class="form-group">
                      <label class="control-label col-md-3 col-sm-3 col-xs-12" for="first-name">
                      	<?php echo $value["COLUMN_COMMENT"]; ?> : 
                      </label>
                      <h5 class="col-md-6 col-sm-6 col-xs-12" style="color:#999;">
                      	<?php echo $orData[0][$value["COLUMN_NAME"]]; ?>
                      </h5>
                    </div>
                    <?php 
                  		}
                  	} 
                  	?>
                    <div style="margin:30px;"></div>
                  </div>
                  <br>
                 </div>
                 
                 <?php }?>
                  
                </div>
                <div class="x_content .no-print">
                  <h2 style="text-align:center;float:none;"><?php if($method==1){?><a href="view/print_order_details.php?orno=<?php echo $orNo; ?>" target="_blank" class="btn print btn-success">列印</a><?php }?>
				  <?php if(in_array(61, $curRrightArr) && !in_array(60, $curRrightArr)){  ?>
				  
				  <?php }else{ ?>
				  <button class="btn btn-success confirm-save">確認儲存</button>
				  <?php }?>
				  </h2>
                  <div class="clearfix"></div>
                </div>
                <div class="clearfix"></div>
              </div>
            </div>
           </form>
          </div>
        </div>
        
        <!-- /page content -->
        
  <!-- daterangepicker -->
  <script type="text/javascript" src="js/moment/moment.min.js"></script>
  <script type="text/javascript" src="js/datepicker/daterangepicker.js"></script>
  
<script>
$(function(){
	$("#jump-menu ul li").click(function(e){
		e.preventDefault();
		var which = $(this).index();
		var top = $("#details .x_content>div").eq(which).offset().top;
		$("html,body").animate({
			"scrollTop":top
		},300);
	});

	<?php if($orData[0]["orStatus"] == 1 || $orData[0]["orStatus"] == 6 || $orData[0]["orStatus"] == 10){?>
	//列印並更新成已處理
	$(".print").click(function(e){
		e.preventDefault();
		$.ajax({
			type: "POST",
			url: "ajax/order/edit_if_process.php",
			data: {"orNo":<?php echo $orNo; ?>},
			success: function(result){    
				if(result != ""){
					alert(result);
				}
				window.open('view/print_order_details.php?orno=<?php echo $orNo; ?>','_blank');
			}
		});
	});
	<?php } ?>

	//切換供應商確認有無缺貨
	$(document).on("change","#which-sup",function(){
		$.ajax({
			type: "POST",
			url: "ajax/order/check_available.php",
			data: {"supNo": $("#which-sup").val(), "proNo":"<?php echo $proData[0]["proNo"]; ?>"},
			success: function(result){    
				if(result.indexOf("OK") != -1){
					$("#not-avail-msg").hide();
				}else{
					$("#not-avail-msg").show();
				}
			}
		});
	});

	//發送認證信
	$(document).on("click",".send-authen",function(e){
		e.preventDefault();
		$(".warning-text").text("發送中...");
        $(".black-box").fadeIn(500,function(){
        	$.ajax({
    			url:"ajax/member/member_resetmail.php",
    			type:"post",
    			data:{"memNo":<?php echo $orData[0]["memNo"]; ?>},
    			success:function(result){
    				if(result == true){
    					alert("驗證信成功送出！");
    				}else{
    					alert(result);
    				}
    				$(".black-box").fadeOut(500);
    			}
    		});
        });
	});
	
	//統一發票是否需要 切換時
	//初始
	if($("input[name='orBusinessNumIfNeed']:checked").val() == 0){
		$(".business-num-area").not($(".business-num-area").eq(0)).hide();
	}
	$(document).on("change","input[name='orBusinessNumIfNeed']",function(){
		var cur = $(this);
		if(cur.val() == 1){
			$(".business-num-area").not($(".business-num-area").eq(0)).show();
		}else{
			$(".business-num-area").not($(".business-num-area").eq(0)).hide();
		}
	});

	//確認儲存
	$(".confirm-save").click(function(e){
		e.preventDefault();
		var data = $("form").serialize();
		$.ajax({
			type: "POST",
			url: "ajax/order/edit.php",
			data: data,
			success: function(result){    
				alert(result);
				location.reload();
			}
		});
	});

	//插入地圖元件
	//選擇日期
	for(var i=0; i<$(".date-picker").length; i++){
		$(".date-picker").eq(i).daterangepicker({
	        singleDatePicker: true,
	        calender_style: "picker_4",
	        format: 'YYYY-MM-DD'
	    });
	}
	
});
</script>