<?php 
require_once('model/require_general.php');

$pm = new Product_Manage();
$proNo = $_GET["prono"];
$pmData = $pm->getAllByProNameAndGroup($proNo);
$pm->changeToReadable($pmData[0]);
$pmDataNoGroup = $pm->getAllByProName($proNo);

$pp = new Product_Period();
$ppData = $pp->getPPByProduct($proNo);

$pro = new Product();
$proData = $pro->getOneProByNo($proNo);

$sup = new Supplier();
$supNoArr = array();
$allSupData = $sup->getOneSupplierByNo($_SESSION['supplieruserdata']['supNo']);

?>
<!-- page content -->
      <div class="right_col" role="main" style="min-height: 949px;">
        <div class="">

          <div class="page-title">
            <div class="title_left">
              <h3>商品上架詳細資料</h3>
            </div>
          </div>
          <div class="clearfix"></div>
          <div class="row">
            <div class="col-md-12 col-sm-12 col-xs-12">
              <div class="x_panel">
                <div class="x_title">
                  <h2 style="text-align:center;float:none;">商品上架資訊</h2>
                  <div class="clearfix"></div>
                </div>
                <div class="x_content">
                  <br>
                  <div class="form-horizontal form-label-left">
									<div class="form-group">
                      <label class="control-label col-md-3 col-sm-3 col-xs-12" for="first-name">
												購物站編號 : 
                      </label>
                      <h5 style="color:#999;"><?php echo $allSupData[0]["supLogId"]; ?></a></h5>
                    </div>
                    <div class="form-group">
                      <label class="control-label col-md-3 col-sm-3 col-xs-12" for="first-name">
												產品名稱 : 
                      </label>
											<h5 style="color:#999;"><a style="color:blue;text-decoration:underline;" href="?page=product&type=product&action=view&prono=<?php echo $proNo; ?>"><?php 
																				$cnt = strlen($allSupData[0]["supLogId"])+1;
                                        echo substr($proData[0]["proName"],$cnt);  ?></a></h5>
                    </div>
                    <div class="form-group">
                      <label class="control-label col-md-3 col-sm-3 col-xs-12" for="first-name" >
												產品售價 : 
                      </label>
                      <h5 style="color:#999;"><?php echo number_format($pmData[0]["pmPeriodAmnt"]); ?></h5>
                    </div>
                  
                    <div class="form-group">
                      <label class="control-label col-md-3 col-sm-3 col-xs-12" for="first-name">
                      	實際購買人數 : 
                      </label>
                      <div style="display:inline-block;">
                      	<h5 style="color:#999;"><?php echo number_format($pmData[0]["pmBuyAmnt"]); ?></h5>
                      </div>
                    </div>
                    <div class="form-group">
                      <label class="control-label col-md-3 col-sm-3 col-xs-12" for="first-name">
												點擊量 : 
                      </label>
                      <div style="display:inline-block;">
                      	<h5 style="color:#999;"><?php echo number_format($pmData[0]["pmClickNum"]); ?></h5>
                      </div>
                    </div>
                    <div class="form-group">
                      <label class="control-label col-md-3 col-sm-3 col-xs-12" for="first-name">
                      	上架日期 : 
                      </label>
                      <div style="display:inline-block;">
                      	<h5 style="color:#999;"><?php echo $pmData[0]["pmUpDate"]; ?></h5>
                      </div>
                    </div>
                    <div class="form-group">
                      <label class="control-label col-md-3 col-sm-3 col-xs-12" for="first-name">
                      	上架狀態 : 
                      </label>
                      <div style="display:inline-block;">
                      	<h5 style="color:#999;"><?php echo $pmData[0]["pmStatus"]; ?></h5>
                      </div>
                    </div>
					
                    <div class="form-group not-print">
                      <label class="control-label col-md-3 col-sm-3 col-xs-12" for="first-name">
                      	採取動作 : 
                      </label>
                      <a style="text-decoration:none;" href="?page=product&type=productManage&action=edit&prono=<?php echo $proNo; ?>">
                      	<button class="btn btn-success">編輯</button>
                      </a>
                    </div>
                    <div class="x_title"></div>
	               
	               
	                  <div style="margin-bottom:30px;" class="x_title"></div>
	                  <div class="form-group not-print">
	                      <div class="col-md-6 col-sm-6 col-xs-12 col-md-offset-3">
	                        <a style="color:#FFF;" href="?page=product&type=productManage">
	                          <button class="btn btn-primary">回上架列表</button>
	                        </a>
                              <a style="color:#FFF;" onclick="window.history.back();">
                                  <button class="btn btn-primary">回上頁</button>
                              </a>
<!--	                        --><?php //if(isset($_SERVER['HTTP_REFERER'])){?>
<!--	                        <a style="color:#FFF;" href="--><?php //echo $_SERVER['HTTP_REFERER']; ?><!--">-->
<!--	                          <button class="btn btn-primary">回上頁</button>-->
<!--	                        </a>-->
<!--	                        --><?php //}?>
	                      </div>
	                    </div>
	                </div>
	              </div>
                </div>
              </div>
            </div>
          </div>
        </div>
        <!-- /page content -->
        	
<script>
$(function(){
	<?php if(isset($_GET["pageIndex"])) echo "$(window).scrollTop($(document).height());"; ?>
	$(document).on("change",".change-main",function(){
		var supNo = $(".change-main:checked").attr("data-supno");
		var data = {"proNo":<?php echo $proNo; ?>, "supNo":supNo};
		$.post("ajax/productManage/edit_main_sup.php", data, function(result){
			if(result.indexOf("成功") != -1){
				alert(result.split(" ")[0]);
				$("input[data-supno="+result.split(" ")[0]+"]").prop("checked",true);
			}else{
				alert(result);
			}
		});
	});
	$(document).on("click","#confirm-insert",function(e){
		e.preventDefault();
		var form = $("form").serialize();
		var redirect = "?page=product&type=productManage&action=view&prono=<?php echo $proNo ?>&pageIndex=last"
		$.ajax({
			url:"ajax/productManage/insert_supplier.php",
			type:"post",
			data:form,
			datatype:"json",
			success:function(result){
				var results = JSON.parse(result);
				if(results.errMsg != ""){
					addError($("#supNoErr"),results.errMsg.supNoErr);
				}else if(results.errMsg == ""){
					alert(results.success);
					location.href= redirect;
				}
			}
		});
	});

	//更換上架狀態
	$(document).on("change",".change-status",function(){
		var pmNo = $(this).parent().siblings("td").eq(0).find("span").text();
		var data = {"pmNo":pmNo, "pmStatus":$(this).val()};
		
		$.post("ajax/productManage/edit_up_status.php", data, function(result){
			alert(result);
		});
	});
	
	//編輯
	var curPmNo;
	var curSupPrice;
	var curSupPriceVal;
	
	$(document).on("click",".content-edit",function(e){
		e.preventDefault();
		curPmNo = $(this).parent().parent().children().eq(1).children("span").text();
		curSupPrice = $(this).parent().parent().children().eq(3);
		if(curSupPrice.children("input").length == 0){
			curSupPriceVal = curSupPrice.text();
			curSupPrice.html('<input autofocus="true" class="lgSupPrice" name="lgSupPrice" value="'+curSupPriceVal+'">'+
					'<button style="margin-left:15px;background-color:#FFF;border:1px solid #CCC;" class="btn btn-defult edit-confirm">確定</button>');
			curSupPrice.children("input").select();
		}
	});
	$(document).on("mousedown",".edit-confirm",function(){
		data = {"pmNo":curPmNo,"pmSupPrice":curSupPrice.children("input").val()};
		$.post("ajax/productManage/edit_sup_price.php", data, function(result){
			if(result.indexOf("成功") != -1){
				alert(result.split(" ")[0]);
				curSupPrice.text(result.split(" ")[1]);
			}else{
				alert(result);
			}
		});
	});
	$(document).on("keypress",function(e){
		if(e.keyCode == 13 && $(".lgSupPrice").is(":focus")){
			curSupPriceVal = curSupPrice.children("input").val();
			data = {"pmNo":curPmNo,"pmSupPrice":curSupPrice.children("input").val()};
			$.post("ajax/productManage/edit_sup_price.php", data, function(result){
				if(result.indexOf("成功") != -1){
					alert(result.split(" ")[0]);
					curSupPrice.text(result.split(" ")[1]);
				}else{
					alert(result);
				}
			});
		}
	});
	$(document).on("blur",".lgSupPrice",function(e){
		curSupPrice.text(curSupPriceVal);
	});

	//刪除
	$(document).on("click",".content-remove",function(e){
		e.preventDefault();
		curPmNo = $(this).parent().parent().children().eq(1).children("span").text();
		if(window.confirm("確定要刪除嗎？")){
			data = {"pmNo":curPmNo};
			$.post("ajax/productManage/delete.php", data, function(result){
				alert(result);
				if(result.indexOf("成功") != -1){
					location.href = "?page=product&type=productManage&action=view&prono=<?php echo $proNo; ?>&pageIndex=last";
				}
			});
		}
	});
});
function addError(selector, errMsg){
	selector.text(errMsg);
}
</script>