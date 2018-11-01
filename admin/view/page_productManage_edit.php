<?php 
require_once('model/require_general.php');

date_default_timezone_set('Asia/Taipei');
$date = date('Y-m-d H:i:s', time());

$ps = new Period_Setting();
$psData = $ps->getAllPS();
$ps2 = new Period_Setting2();
$ps2Data = $ps2->getAllPS();




if($_GET["action"] == "edit"){
	$pm = new Product_Manage();
	$proNo = $_GET["prono"];
	
	$pp = new Product_Period();
	$ppData = $pp->getPPByProduct($proNo);
	if($ppData != null){
		foreach($ppData as $key=>$value){
			switch($value["ppPeriodAmount"]){
				case "6":
					$pp6Percent = $value["ppPercent"];
					$pp6IntroText = $value["ppIntroText"];
					break;
				case "9":
					$pp9Percent = $value["ppPercent"];
					$pp9IntroText = $value["ppIntroText"];
					break;
				case "12":
					$pp12Percent = $value["ppPercent"];
					$pp12IntroText = $value["ppIntroText"];
					break;
				case "15":
					$pp15Percent = $value["ppPercent"];
					$pp15IntroText = $value["ppIntroText"];
					break;
				case "18":
					$pp18Percent = $value["ppPercent"];
					$pp18IntroText = $value["ppIntroText"];
					break;
				case "21":
					$pp21Percent = $value["ppPercent"];
					$pp21IntroText = $value["ppIntroText"];
					break;
				case "24":
					$pp24Percent = $value["ppPercent"];
					$pp24IntroText = $value["ppIntroText"];
					break;
			}
		}
	}
	
	$origPmData = $pm->getAllByProNameAndGroup($proNo);
	$pmData = $pm->getAllByProNameAndGroup($proNo);
	$pm->changeToReadable($pmData[0]);
	
	$allPmGroup = $pm->getAllPMGroupByProName();
	$proNoArr = array();
	foreach($allPmGroup as $key=>$value){
		array_push($proNoArr, $value["proNo"]);
	}
	
	$pro = new Product();
	$proData = $pro->getOneProByNo($proNo);
}else{

    $pro = new Product();
    $selectableProData = $pro->getAllPMByStatus0();

	if(isset($_GET['procaseno']) && $_GET['procaseno'] != ""){
		$pm = new Product_Manage();
		/*$allPmGroup = $pm->getAllPMGroupByProName();
		$proNoArr = array();
		foreach($allPmGroup as $key=>$value){
			array_push($proNoArr, $value["proNo"]);
		}*/
		
		$pro = new Product();
		$selectableProData = $pro->getOneByCaseNo($_GET['procaseno']);
	}
}

$sup = new Supplier();
$allSupData = $sup->getAllSupplier();



if($_GET["action"] == "insert" && isset($_GET["procaseno"])){
	$proInsertData = $pro->getOneByCaseNo($_GET["procaseno"]);
}

?>
<!-- page content -->
      <div class="right_col" role="main" style="min-height: 949px;">
        <div class="">

          <div class="page-title">
            <div class="title_left">
            <?php if($_GET["action"]=="edit"){ ?>
              <h3>編輯上架資料</h3>
            <?php }else{ ?>
              <h3>新增商品上架</h3>
            <?php } ?>
            </div>
          </div>
          <div class="clearfix"></div>
          <div class="row">
            <div class="col-md-12 col-sm-12 col-xs-12">
              <div class="x_panel">
                <div class="x_title">
                  <h2 style="text-align:center;float:none;">
                  <?php if($_GET["action"]=="edit"){ ?>
                  	  商品編號: <?php echo $proData[0]["proCaseNo"]; ?> &nbsp&nbsp&nbsp&nbsp
                  	<?php if(isset($_SERVER['HTTP_REFERER'])){ ?>
                  	<a style="color:#FFF;" href="<?php echo $_SERVER['HTTP_REFERER']; ?>">
	                  <button class="btn btn-success">回上頁</button>
	                </a>
	                <?php } ?>
                  <?php }else{ ?>
                  	<a style="color:#FFF;" href="?page=product&type=productManage">
	                  <button class="btn btn-success">回上架列表</button>
	                </a>
                  <?php }?>
                  </h2>
                  <div class="clearfix"></div>
                </div>
                <div class="x_content">
                  <br>
                  <form action="ajax/product/edit.php" method="POST" class="form-horizontal form-label-left">
                  <?php if($_GET["action"]=="edit"){ ?>
                    <input type="hidden" name="proNo" value="<?php echo $proNo; ?>">
                  <?php } ?>
                  	<div class="form-group">
                      <label class="control-label col-md-3 col-sm-3 col-xs-12" for="first-name">
                      	商品名稱 : 
                      </label>
                      <div class="col-md-6 col-sm-6 col-xs-12">
                      	<?php if($_GET["action"]=="edit"){ ?>
                      	<select disabled class="form-control">
                          <option selected><?php echo $proData[0]["proName"]; ?></option>
                      	</select>
                      	<?php }else{ ?>
                      	<select name="proNo" class="form-control">
                          <?php if($selectableProData != null){?>
                          <option disabled value="">請選擇</option>
                          <?php 
                          	foreach($selectableProData as $key=>$value){ 
                          ?>
                          	<option <?php if(isset($proInsertData) && $proInsertData[0]["proNo"] == $value["proNo"]) echo "selected"; ?> value="<?php echo $value["proNo"] ?>"><?php echo $value["proCaseNo"]." - ".$value["proName"] ?></option>
                          <?php } 
						  	}else{
                          ?>
                          	<option selected disabled value="">所有商品均已上架</option>
                          <?php 
						  	}
						  }
						  ?>
						 </select>
						 <ul class="parsley-errors-list"><li id="proNoErr"></li></ul>
                      </div>
                    </div>
                    <div class="form-group">
                      <label class="control-label col-md-3 col-sm-3 col-xs-12" for="first-name">
                      	分期基礎價 : 
                      </label>
                      <div class="col-md-6 col-sm-6 col-xs-12">
                      	<input id="period-amnt" value="<?php if($_GET["action"]=="edit") echo $origPmData[0]["pmPeriodAmnt"]; ?>" type="text" class="form-control" name="pmPeriodAmnt" />
                      	<ul class="parsley-errors-list"><li id="pmPeriodAmntErr"></li></ul>
                      </div>
                    </div>
                    <div class="form-group">
                      <label class="control-label col-md-3 col-sm-3 col-xs-12" for="first-name">
                      	是否可以直購 : 
                      </label>
                      <div class="col-md-6 col-sm-6 col-xs-12">
	                      <select name="pmIfDirect" class="form-control">
	                          <option <?php if($_GET["action"] == "edit" && $origPmData[0]["pmIfDirect"]==0) echo "selected"; ?> value="0">否</option>
	                          <option <?php if($_GET["action"] == "edit" && $origPmData[0]["pmIfDirect"]==1) echo "selected"; ?> value="1">是</option>
	                      </select>
	                  </div>
                    </div>
                    <div class="form-group">
                      <label class="control-label col-md-3 col-sm-3 col-xs-12" for="first-name">
                      	直購總金額 : 
                      </label>
                      <div class="col-md-6 col-sm-6 col-xs-12">
                      	<input id="direct-amnt" value="<?php if($_GET["action"]=="edit") echo $origPmData[0]["pmDirectAmnt"]; ?>" type="text" class="form-control" name="pmDirectAmnt" />
                      	<ul class="parsley-errors-list"><li id="pmDirectAmntErr"></li></ul>
                      </div>
                    </div>
                    <div class="form-group">
                      <label class="control-label col-md-3 col-sm-3 col-xs-12" for="first-name">
                      	最新 : 
                      </label>
                      <div class="col-md-6 col-sm-6 col-xs-12">
                      	<select name="pmNewest" class="form-control">
                          <option <?php if($_GET["action"] == "edit" && $origPmData[0]["pmNewest"]==0) echo "selected"; ?> value="0">否</option>
                          <option <?php if($_GET["action"] == "edit" && $origPmData[0]["pmNewest"]==1) echo "selected"; ?> value="1">是</option>
                      	</select>
                      </div>
                    </div>
                    <div style="display:none;" class="form-group">
                      <label class="control-label col-md-3 col-sm-3 col-xs-12" for="first-name">
                      	最新排序 : 
                      </label>
                      <div class="col-md-6 col-sm-6 col-xs-12">
                      	<input value="<?php if($_GET["action"]=="edit") echo $origPmData[0]["pmNewestOrder"]; ?>" type="text" class="form-control" name="pmNewestOrder" />
                      </div>
                    </div>
                    <div class="form-group">
                      <label class="control-label col-md-3 col-sm-3 col-xs-12" for="first-name">
                      	精選 : 
                      </label>
                      <div class="col-md-6 col-sm-6 col-xs-12">
                      	<select name="pmSpecial" class="form-control">
                          <option <?php if($_GET["action"] == "edit" && $origPmData[0]["pmSpecial"]==0) echo "selected"; ?> value="0">否</option>
                          <option <?php if($_GET["action"] == "edit" && $origPmData[0]["pmSpecial"]==1) echo "selected"; ?> value="1">是</option>
                      	</select>
                      </div>
                    </div>
                    <div style="display:none;" class="form-group">
                      <label class="control-label col-md-3 col-sm-3 col-xs-12" for="first-name">
                      	精選排序 : 
                      </label>
                      <div class="col-md-6 col-sm-6 col-xs-12">
                      	<input value="<?php if($_GET["action"]=="edit") echo $origPmData[0]["pmSpecialOrder"]; ?>" type="text" class="form-control" name="pmSpecialOrder" />
                      </div>
                    </div><div class="form-group">
                      <label class="control-label col-md-3 col-sm-3 col-xs-12" for="first-name">
                      	限時 : 
                      </label>
                      <div class="col-md-6 col-sm-6 col-xs-12">
                      	<select name="pmHot" class="form-control">
                          <option <?php if($_GET["action"] == "edit" && $origPmData[0]["pmHot"]==0) echo "selected"; ?> value="0">否</option>
                          <option <?php if($_GET["action"] == "edit" && $origPmData[0]["pmHot"]==1) echo "selected"; ?> value="1">是</option>
                      	</select>
                      </div>
                    </div>
                    <div style="display:none;" class="form-group">
                      <label class="control-label col-md-3 col-sm-3 col-xs-12" for="first-name">
                      	限時排序 : 
                      </label>
                      <div class="col-md-6 col-sm-6 col-xs-12">
                      	<input value="<?php if($_GET["action"]=="edit") echo $origPmData[0]["pmHotOrder"]; ?>" type="text" class="form-control" name="pmHotOrder" />
                      </div>
                    </div>
                    <div class="form-group">
                      <label class="control-label col-md-3 col-sm-3 col-xs-12" for="first-name">
                      	灌水數: 
                      </label>
                      <div class="col-md-6 col-sm-6 col-xs-12">
                      	<input value="<?php if($_GET["action"]=="edit") echo $origPmData[0]["pmPopular"]; ?>" type="text" class="form-control" name="pmPopular" />
                      	<ul class="parsley-errors-list"><li id="pmPopularErr"></li></ul>
                      </div>
                    </div>
                    <?php if($_GET["action"]=="edit"){?>
                    <div class="form-group">
                      <label class="control-label col-md-3 col-sm-3 col-xs-12" for="first-name">
                      	實際購買人數 : 
                      </label>
                      <div class="col-md-6 col-sm-6 col-xs-12">
                      	<input readonly value="<?php if($_GET["action"]=="edit") echo $origPmData[0]["pmBuyAmnt"]; ?>" type="text" class="form-control" name="pmBuyAmnt" />
                      </div>
                    </div>
                    <?php }?>
                    <?php if($_GET["action"]=="edit"){?>
                    <div class="form-group">
                      <label class="control-label col-md-3 col-sm-3 col-xs-12" for="first-name">
                      	商品點擊數: 
                      </label>
                      <div class="col-md-6 col-sm-6 col-xs-12">
                      	<input readonly value="<?php echo $origPmData[0]["pmClickNum"]; ?>" type="text" class="form-control" name="pmClickNum" />
                      </div>
                    </div>
                    <?php }?>
                    <div class="form-group">
                      <label class="control-label col-md-3 col-sm-3 col-xs-12" for="first-name">
                      	上架時間: 
                      </label>
                      <div class="col-md-6 col-sm-6 col-xs-12">
                      	<input value="<?php if($_GET["action"] == "edit") echo $origPmData[0]["pmUpDate"]; else echo $date; ?>" type="text" class="form-control" name="pmUpDate" />
                     	<ul class="parsley-errors-list"><li id="pmUpDateErr"></li></ul>
                      </div>
                    </div>
                    <div class="form-group">
                      <label class="control-label col-md-3 col-sm-3 col-xs-12" for="first-name">
                      	上架狀態 : 
                      </label>
                      <div class="col-md-6 col-sm-6 col-xs-12">
                      	<select class="form-control" name="pmStatus">
                      		<option <?php if($_GET["action"]=="edit" && $origPmData[0]["pmStatus"] == 0) echo "selected"; ?> value="0">下架中</option>
                      		<option <?php if($_GET["action"]=="edit" && $origPmData[0]["pmStatus"] == 1) echo "selected"; ?> value="1">上架中</option>
                      		<option <?php if($_GET["action"]=="edit" && $origPmData[0]["pmStatus"] == 2) echo "selected"; ?> value="2">缺貨中</option>
                      	</select>
                     	<ul class="parsley-errors-list"><li id="pmUpDateErr"></li></ul>
                      </div>
                    </div>
                    <?php if($_GET["action"]!="edit"){ ?>
                    <div class="form-group">
                      <label class="control-label col-md-3 col-sm-3 col-xs-12" for="first-name">
                      	供應商 : 
                      </label>
                      <div class="col-md-6 col-sm-6 col-xs-12">
                      	<select name="supNo" class="form-control">
                      	  <?php 
                      	  if($allSupData != null){
                      	  	foreach($allSupData as $key=>$value){
                      	  ?>
                          	<option value="<?php echo $value["supNo"]; ?>"><?php echo $value["supNo"]." - ".$value["supName"]; ?></option>
                          <?php 
                      	  	}
                      	  }else{
                      	  ?>
                      	  	<option selected disabled value="">尚未新增任何供應商</option>
                      	  <?php 
                      	  }
                          ?>
                      	</select>
                      	<ul class="parsley-errors-list"><li id="supNoErr"></li></ul>
                      </div>
                    </div>
                    <?php }?>
                    <?php if($_GET["action"] != "edit"){ ?>
                    <div class="form-group">
                      <label class="control-label col-md-3 col-sm-3 col-xs-12" for="first-name">
                      	供應價 : 
                      </label>
                      <div class="col-md-6 col-sm-6 col-xs-12">
                      	<input type="text" class="form-control" name="pmSupPrice" />
                      </div>
                    </div>
                    <input type="hidden" name="pmMainSup" value="1">
                    <?php }?>
					<div class="form-group">
                      <label class="control-label col-md-3 col-sm-3 col-xs-12" for="first-name">
                      	商品利率 : 
                      </label>
                      <div class="col-md-6 col-sm-6 col-xs-12">
					    <button type="button" class="btn btn-success default-ratio" data-follow="1">按照對照表1利率</button>
						<button type="button" class="btn btn-success default-ratio" data-follow="2">按照對照表2利率</button><br><br>
                      	<input type="hidden" name="ppPeriodAmount[]" value="6">06期：<input data-period="6" type="text" size="7" name="ppPercent[]" value="<?php if($_GET["action"]=="edit" && $ppData != null) echo $pp6Percent; ?>"><br>行銷字眼：<input type="text" style="width:100%;" name="ppIntroText[]" value="<?php if($_GET["action"]=="edit" && $ppData != null) echo $pp6IntroText; ?>"><br><br>
                      	<input type="hidden" name="ppPeriodAmount[]" value="9">09期：<input data-period="9" type="text" size="7" name="ppPercent[]" value="<?php if($_GET["action"]=="edit" && $ppData != null) echo $pp9Percent; ?>"><br>行銷字眼：<input type="text" style="width:100%;" name="ppIntroText[]" value="<?php if($_GET["action"]=="edit" && $ppData != null) echo $pp9IntroText; ?>"><br><br>
                      	<input type="hidden" name="ppPeriodAmount[]" value="12">12期：<input data-period="12" type="text" size="7" name="ppPercent[]" value="<?php if($_GET["action"]=="edit" && $ppData != null) echo $pp12Percent; ?>"><br>行銷字眼：<input type="text" style="width:100%;" name="ppIntroText[]" value="<?php if($_GET["action"]=="edit" && $ppData != null) echo $pp12IntroText; ?>"><br><br>
                      	<input type="hidden" name="ppPeriodAmount[]" value="15">15期：<input data-period="15" type="text" size="7" name="ppPercent[]" value="<?php if($_GET["action"]=="edit" && $ppData != null) echo $pp15Percent; ?>"><br>行銷字眼：<input type="text" style="width:100%;" name="ppIntroText[]" value="<?php if($_GET["action"]=="edit" && $ppData != null) echo $pp15IntroText; ?>"><br><br>
                      	<input type="hidden" name="ppPeriodAmount[]" value="18">18期：<input data-period="18" type="text" size="7" name="ppPercent[]" value="<?php if($_GET["action"]=="edit" && $ppData != null) echo $pp18Percent; ?>"><br>行銷字眼：<input type="text" style="width:100%;" name="ppIntroText[]" value="<?php if($_GET["action"]=="edit" && $ppData != null) echo $pp18IntroText; ?>"><br><br>
                      	<input type="hidden" name="ppPeriodAmount[]" value="21">21期：<input data-period="21" type="text" size="7" name="ppPercent[]" value="<?php if($_GET["action"]=="edit" && $ppData != null) echo $pp21Percent; ?>"><br>行銷字眼：<input type="text" style="width:100%;" name="ppIntroText[]" value="<?php if($_GET["action"]=="edit" && $ppData != null) echo $pp21IntroText; ?>"><br><br>
                      	<input type="hidden" name="ppPeriodAmount[]" value="24">24期：<input data-period="24" type="text" size="7" name="ppPercent[]" value="<?php if($_GET["action"]=="edit" && $ppData != null) echo $pp24Percent; ?>"><br>行銷字眼：<input type="text" style="width:100%;" name="ppIntroText[]" value="<?php if($_GET["action"]=="edit" && $ppData != null) echo $pp24IntroText; ?>"><br><br>
                      </div>
                    </div>
                    <div style="margin:30px;"></div>
                    <div class="form-group">
                      <div class="col-md-6 col-sm-6 col-xs-12 col-md-offset-3">
                         <button type="submit" class="btn btn-primary">
                         	<?php if($_GET["action"]=="edit") echo "確認修改"; else echo "確認新增" ?>
                         </button>
                      </div>
                    </div>
                  </form>
                </div>
              </div>
            </div>
          </div>
        </div>
        <!-- /page content -->
<script>
$(function(){
	//自動更改直購價
	<?php if($_GET["action"]=="insert"){ ?>
	$("#period-amnt").change(function(){
		var val = $("#period-amnt").val();
		$("#direct-amnt").val(val);
	});
	<?php }?>
	
	//自動帶入利率
	$(".default-ratio").click(function(){
		var which = $(this).data("follow");
		if(which == 1){
			<?php foreach($psData as $key=>$value){ ?>
			$("input[data-period='<?php echo $value["psMonthNum"] ?>']").val("<?php echo $value["psRatio"] ?>")
			<?php } ?>
		}else{
			<?php foreach($ps2Data as $key=>$value){ ?>
			$("input[data-period='<?php echo $value["psMonthNum"] ?>']").val("<?php echo $value["psRatio"] ?>")
			<?php } ?>
		}
	});
    
	$("button[type='submit']").click(function(e){
		$("#stampImgErr").text("");
		$(".parsley-errors-list li").text("");
		e.preventDefault();
		
		var form = new FormData($("form")[0]);
		var url = "ajax/productManage/<?php echo $_GET["action"]; ?>.php";
		var redirect = "?page=product&type=productManage<?php if($_GET["action"]=="edit") echo "&action=view&prono=".$proNo; if($_GET["action"]=="insert") echo "&pageIndex=last" ?>";
		
		$.ajax({
			url:url,
			type:"post",
			data:form,
			datatype:"json",
			contentType:false,
			processData: false,
			success:function(result){
				var results = JSON.parse(result);
				if(results.errMsg != ""){
					addError($("#supNoErr"),results.errMsg.supNoErr);
					addError($("#pmPopularErr"),results.errMsg.pmPopularErr);
					addError($("#proNoErr"),results.errMsg.proNoErr);
					addError($("#pmPeriodAmntErr"),results.errMsg.pmPeriodAmntErr);
					addError($("#pmDirectAmntErr"),results.errMsg.pmDirectAmntErr);
					addError($("#pmUpDateErr"),results.errMsg.pmUpDateErr);
				}else if(results.errMsg == ""){
					alert(results.success);
					location.href= redirect;
				}
			}
		});
	});

// 	//圖片上傳預覽
// 	var inputLocalFont = document.getElementById("stamp-upload");
// 	inputLocalFont.addEventListener("change",readMultipleURL,false);

// 	$("#stamp-upload").change(function(){
// 	    readURL(this);
// 	});
});
//讀單張
function readURL(input) {

    if (input.files && input.files[0]) {
        var reader = new FileReader();

        reader.onload = function (e) {
            $('#show-stamp').attr('src', e.target.result);
        }

        reader.readAsDataURL(input.files[0]);
    }
}
//讀多張
function readMultipleURL(){
	$('#preview-area img').remove();
	var fileList = this.files;

    var anyWindow = window.URL || window.webkitURL;

        for(var i = 0; i < fileList.length; i++){
          //get a blob to play with
          var objectUrl = anyWindow.createObjectURL(fileList[i]);
          // for the next line to work, you need something class="preview-area" in your html
          $('#preview-area').append('<img style="border:2px solid #AAA;padding:5px;margin:20px;max-width:300px;" src="' + objectUrl + '" />');
          // get rid of the blob
          window.URL.revokeObjectURL(fileList[i]);
        }
}
function addError(selector, errMsg){
	selector.text(errMsg);
}


</script>