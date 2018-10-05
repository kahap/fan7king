<?php 
require_once('model/require_general.php');

date_default_timezone_set('Asia/Taipei');
$date = date('Y-m-d H:i:s', time());

$ps = new Period_Setting();
$psData = $ps->getAllPS();

$ps2 = new Period_Setting2();
$ps2Data = $ps2->getAllPS();

$cat = new Category();
$allCat = $cat->getAllCatOrder();

$bra = new Brand();
$allBra = $bra->getAllBrandOrder();

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
	$pm = new Product_Manage();

	$allPmGroup = $pm->getAllPMGroupByProName();
	$proNoArr = array();

	foreach($allPmGroup as $key=>$value){
		array_push($proNoArr, $value["proNo"]);      
	}
  
	 
	$sup = new Supplier();
	$allSupData = $sup->getAllSupplier();

}

if($_GET["action"] == "insert" && isset($_GET["procaseno"])){
	$proInsertData = $pro->getOneByCaseNo($_GET["procaseno"]);
}

?>
<!-- page content -->
      <div class="right_col" role="main" style="min-height: 949px;">
        <div class="">
          <input type="hidden" id="supNo" 
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
                  <form action="ajax/product/edit.php" method="POST" class="form-horizontal form-label-left" id="form1" name="form1">
                   <input type="hidden" id="supNo" name="supNo" value="<?php echo $_SESSION['supplieruserdata']['supNo']; ?>">
                   <input type="hidden" id="pmBySup" name="pmBySup" value="1">

                  <?php if($_GET["action"]=="edit"){ ?>
                    <input type="hidden" name="proNo" value="<?php echo $proNo; ?>">
                  <?php } ?>
                   	<div class="form-group">
                      <label class="control-label col-md-3 col-sm-3 col-xs-12" for="first-name">
                      	所屬分類 : 
                      </label>
                       <div class="col-md-6 col-sm-6 col-xs-12">
	                      <select name="catNo" class="form-control">
	                      	<option selected value="">請選擇</option>
	                      	<?php foreach($allCat as $key=>$value){ ?>
	                        	<option <?php if($_GET["action"]=="edit") if($proData[0]["catNo"]==$value["catNo"]) echo "selected"; ?> value="<?php echo $value["catNo"]; ?>"><?php echo $value["catName"]; ?></option>
	                        <?php } ?>
	                      </select>
	                      <ul class="parsley-errors-list"><li id="catErr"></li></ul>
	                   </div>
                     </div>
                   	<div class="form-group">
                      <label class="control-label col-md-3 col-sm-3 col-xs-12" for="first-name">
                      所屬品牌 : 
                      </label>
                      <div class="col-md-6 col-sm-6 col-xs-12">                      	
	                      <select name="braNo" class="form-control">
	                      	<option selected value="">請選擇</option>
	                      	<?php foreach($allBra as $key=>$value){ ?>
	                        	<option <?php if($_GET["action"]=="edit") if($proData[0]["braNo"]==$value["braNo"]) echo "selected"; ?> value="<?php echo $value["braNo"]; ?>"><?php echo $value["braName"]; ?></option>
	                        <?php } ?>
	                      </select>
	                      <ul class="parsley-errors-list"><li id="braErr"></li></ul>	                
                      </div>
                    </div>
                  	<div class="form-group">
                      <label class="control-label col-md-3 col-sm-3 col-xs-12" for="first-name">
                      	商品名稱 : 
                      </label>
                      <div class="col-md-6 col-sm-6 col-xs-12">
                      	<?php if($_GET["action"]=="edit"){
                              if(trim($proData[0]["proName"])==""){ ?>
                                 <input name="proNo" id="proNo" class="form-control">
                            <?php }
                            else { ?>
                         	    <select disabled class="form-control">
                                  <option selected><?php echo $proData[0]["proName"]; ?></option>
                         	    </select>
                      	<?php }
                        }
                        else{ ?>

                          <input name="proNo" id="proNo" class="form-control">
                      
						 <?php } ?>
						
						 <ul class="parsley-errors-list"><li id="proNoErr"></li></ul>
                      </div>
                    </div>                  
                    <div class="form-group">
                      <label class="control-label col-md-3 col-sm-3 col-xs-12" for="first-name">
                      	上架金額
                      </label>
                      <div class="col-md-6 col-sm-6 col-xs-12">
                      	<input id="period-amnt" value="<?php if($_GET["action"]=="edit") echo $origPmData[0]["pmPeriodAmnt"]; ?>" type="text" class="form-control" name="pmPeriodAmnt" />
                      	<ul class="parsley-errors-list"><li id="pmPeriodAmntErr"></li></ul>
                      </div>
                    </div>
                       <div class="form-group">
                      <label class="control-label col-md-3 col-sm-3 col-xs-12" for="first-name">
                      	撥款金額 : 
                      </label>
                      <div class="col-md-6 col-sm-6 col-xs-12">
                      	<input id="period-amnt2" value="<?php if($_GET["action"]=="edit") echo $origPmData[0]["pmPeriodAmnt2"]; ?>" type="text" class="form-control" name="pmPeriodAmnt2" readonly=true />
                      	<ul class="parsley-errors-list"><li id="pmPeriodAmntErr2"></li></ul>
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
                      	上架狀態 : 
                      </label>
                      <div class="col-md-6 col-sm-6 col-xs-12">
					  <?php 
							$selected1 = ($_GET["action"]=="edit" && $origPmData[0]["pmStatus"] == 0) ? "selected":""; 
							$selected2 = ($_GET["action"]=="edit" && $origPmData[0]["pmStatus"] == 1) ? "selected":"";  
					  ?> 
                      	<select class="form-control" name="pmStatus">
							<option value="1" <?=$selected2?>>上架中</option>
                      		<option value="0" <?=$selected1?>>下架中</option>
            
                      	</select>
                     	<ul class="parsley-errors-list"><li id="pmUpDateErr"></li></ul>
                      </div>
                    </div>
                 
					<div class="form-group">
                      <label class="control-label col-md-3 col-sm-3 col-xs-12" for="first-name">
                      	商品利率 : 
                      </label>
                      <div class="col-md-6 col-sm-6 col-xs-12">					  
                      	<input type="hidden" name="ppPeriodAmount[]" value="6">06期：<input data-period="6" type="text" size="7" name="ppPercent1[]" value="<?php if($_GET["action"]=="edit" && $ppData != null) echo $pp6Percent; ?>" readonly=true>每期 : <input data-pmoney="6" type="text" size="7" name="ppMoney[]" value="" readonly=true><br>
                      	<input type="hidden" name="ppPeriodAmount[]" value="9">09期：<input data-period="9" type="text" size="7" name="ppPercent1[]" value="<?php if($_GET["action"]=="edit" && $ppData != null) echo $pp9Percent; ?>" readonly=true>每期 : <input data-pmoney="9" type="text" size="7" name="ppMoney[]" value="" readonly=true><br>
                      	<input type="hidden" name="ppPeriodAmount[]" value="12">12期：<input data-period="12" type="text" size="7" name="ppPercent1[]" value="<?php if($_GET["action"]=="edit" && $ppData != null) echo $pp12Percent; ?>" readonly=true>每期 : <input data-pmoney="12" type="text" size="7" name="ppMoney[]" value="" readonly=true><br>
                      	<input type="hidden" name="ppPeriodAmount[]" value="15">15期：<input data-period="15" type="text" size="7" name="ppPercent1[]" value="<?php if($_GET["action"]=="edit" && $ppData != null) echo $pp15Percent; ?>" readonly=true>每期 : <input data-pmoney="15" type="text" size="7" name="ppMoney[]" value="" readonly=true><br>
                      	<input type="hidden" name="ppPeriodAmount[]" value="18">18期：<input data-period="18" type="text" size="7" name="ppPercent1[]" value="<?php if($_GET["action"]=="edit" && $ppData != null) echo $pp18Percent; ?>" readonly=true>每期 : <input data-pmoney="18" type="text" size="7" name="ppMoney[]" value="" readonly=true><br>
                      	<input type="hidden" name="ppPeriodAmount[]" value="21">21期：<input data-period="21" type="text" size="7" name="ppPercent1[]" value="<?php if($_GET["action"]=="edit" && $ppData != null) echo $pp21Percent; ?>" readonly=true>每期 : <input data-pmoney="21" type="text" size="7" name="ppMoney[]" value="" readonly=true><br>
                      	<input type="hidden" name="ppPeriodAmount[]" value="24">24期：<input data-period="24" type="text" size="7" name="ppPercent1[]" value="<?php if($_GET["action"]=="edit" && $ppData != null) echo $pp24Percent; ?>" readonly=true>每期 : <input data-pmoney="24" type="text" size="7" name="ppMoney[]" value="" readonly=true><br>
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
$(function() {
    $('#period-amnt').focusout(function() {
        var val = $("#period-amnt").val();
		   $("#period-amnt2").val(val*0.95);
           getRadio() ;
    });
});

function getRadio()
{
    var which = $(this).data("follow");
    var val = $("#period-amnt").val();
	if(val !="")
    {
	<?php foreach($psData as $key=>$value){ ?>
			$("input[data-period='<?php echo $value["psMonthNum"] ?>']").val(Math.round("<?php echo $value["psRatio"]?>"*val))
            $("input[data-pmoney='<?php echo $value["psMonthNum"] ?>']").val(Math.round(("<?php echo $value["psRatio"]?>"*val)/(<?php echo $value["psMonthNum"] ?>)))
    <?php } ?>
    }
};

$(function(){	
    
	$("button[type='submit']").click(function(e){
        var val = $("#period-amnt").val();
        if(val=='')
        {
            alert("請輸入上架金額");
            return;
        }
	    $("#period-amnt2").val(val*0.95);
        getRadio() ;

		$("#stampImgErr").text("");
		$(".parsley-errors-list li").text("");
		e.preventDefault();
		
		var form = new FormData($("form")[0]);
		var url = "ajax/productManage/<?php echo $_GET["action"]; ?>.php";
		var redirect = "?page=product&type=productManage";
			
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
				    addError($("#catErr"),results.errMsg.catErr);
                    addError($("#braErr"),results.errMsg.braErr);
                    addError($("#proNoErr"),results.errMsg.proNoErr);
					addError($("#pmPeriodAmntErr"),results.errMsg.pmPeriodAmntErr);
                    addError($("#pmPeriodAmntErr2"),results.errMsg.pmPeriodAmntErr2);				
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
 <?php if($_GET["action"]=="edit"){?>
			
$(document).ready(function()
{
			getRadio() ;
         
});
   <?php } ?>
</script>