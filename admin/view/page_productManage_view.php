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
                      	商品名稱 : 
                      </label>
                      <h5 style="color:#999;"><a style="color:blue;text-decoration:underline;" href="?page=product&type=product&action=view&prono=<?php echo $proNo; ?>"><?php echo $proData[0]["proName"]; ?></a></h5>
                    </div>
                    <div class="form-group">
                      <label class="control-label col-md-3 col-sm-3 col-xs-12" for="first-name">
                      	分期基礎價 : 
                      </label>
                      <h5 style="color:#999;"><?php echo number_format($pmData[0]["pmPeriodAmnt"]); ?></h5>
                    </div>
                    <div class="form-group">
                      <label class="control-label col-md-3 col-sm-3 col-xs-12" for="first-name">
                      	是否可以直購 : 
                      </label>
                      <h5 style="color:#999;"><?php echo $pmData[0]["pmIfDirect"]; ?></h5>
                    </div>
                    <div class="form-group">
                      <label class="control-label col-md-3 col-sm-3 col-xs-12" for="first-name">
                      	直購金額 : 
                      </label>
                      <h5 style="color:#999;"><?php echo number_format($pmData[0]["pmDirectAmnt"]); ?></h5>
                    </div>
                    <div class="form-group">
                      <label class="control-label col-md-3 col-sm-3 col-xs-12" for="first-name">
                      	最新 : 
                      </label>
                      <div style="display:inline-block;">
                      	<h5 style="color:#999;"><?php echo $pmData[0]["pmNewest"]; ?></h5>
                      </div>
                    </div>
                    <div class="form-group">
                      <label class="control-label col-md-3 col-sm-3 col-xs-12" for="first-name">
                      	精選 : 
                      </label>
                      <div style="display:inline-block;">
                      	<h5 style="color:#999;"><?php echo $pmData[0]["pmSpecial"]; ?></h5>
                      </div>
                    </div>
                    <div class="form-group">
                      <label class="control-label col-md-3 col-sm-3 col-xs-12" for="first-name">
                      	限時 : 
                      </label>
                      <div style="display:inline-block;">
                      	<h5 style="color:#999;"><?php echo $pmData[0]["pmHot"]; ?></h5>
                      </div>
                    </div>
                    <div class="form-group">
                      <label class="control-label col-md-3 col-sm-3 col-xs-12" for="first-name">
                      	灌水人數 : 
                      </label>
                      <div style="display:inline-block;">
                      	<h5 style="color:#999;"><?php echo number_format($pmData[0]["pmPopular"]); ?></h5>
                      </div>
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
                      	商品點擊數 : 
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
					<div class="form-group">
                      <label class="control-label col-md-3 col-sm-3 col-xs-12" for="first-name">
                      	商品利率 : 
                      </label>
                      <div style="display:inline-block;">
                        <?php 
                        if($ppData != null){
                        ?>
                      	<table style="text-align:center;">
                      	  <tr>
                      		<th style="padding:5px 10px;">期數</th>
							<th style="padding:5px 10px;">利率倍數</th>
							<th style="padding:5px 10px;">行銷字眼</th>
                      	  </tr>
                      	  <?php foreach($ppData as $key=>$value){?>
                      	  <tr>
                      		<td><?php echo $value["ppPeriodAmount"]; ?></td>
                      		<td><?php echo $value["ppPercent"]; ?></td>
							<td><?php echo $value["ppIntroText"]; ?></td>
                      	  </tr>
                      	  <?php }?>
                      	</table>
                      	<?php 
						}else{
                      	?>
                      	<h5 style="color:#999;">該商品尚未設定獨立利率，將參照利率基本表</h5>
                      	<?php 
                      	}
                      	?>
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
	                <div class="x_title">
	                  <h2 style="text-align:center;float:none;">
	               	   	供應商資訊
	               	   	<button style="margin-left: 20px;" class="btn btn-success" data-toggle="modal" data-target=".bs-example-modal-lg">新增供應商</button>
	                  </h2>
	                  <div class="clearfix"></div>
	                </div>
	                <div class="x_content">
	                  <br>
	                  <table class="table table-striped">
	                    <thead>
	                      <tr>
	                        <th>#</th>
	                        <th>供應商編號</th>
	                        <th>供應商名稱</th>
	                        <th>供應價格</th>
	                        <th>主要供應</th>
	                        <!--<th>供應狀態</th>-->
	                        <th>編輯</th>
	                      </tr>
	                    </thead>
	                    <tbody>
	                      <?php 
	                      $i = 1;
	                      $count = sizeof($pmDataNoGroup);
	                      if($pmDataNoGroup != null){
		                      foreach($pmDataNoGroup as $key=>$value){
		                      	array_push($supNoArr, $value["supNo"]);
		                      	$supData = $sup->getOneSupplierByNo($value["supNo"]);
		                      	$pm->changeToReadable($value);
	                      ?>
	                      
		                      <tr>
		                        <th scope="row"><?php echo $i ?></th>
		                        <td><span style="display:none;"><?php echo $value["pmNo"]; ?></span><?php echo $value["supNo"]; ?></td>
		                        <td><?php echo $supData[0]["supName"]; ?></td>
		                        <td><?php echo $value["pmSupPrice"]; ?></td>
		                        <td><input data-supno="<?php echo $value["supNo"]; ?>" class="change-main" type="radio" name="pmMainSup" <?php if($value["pmMainSup"] == "是") echo "checked=true"; ?>></td>
		                      	<!--
								<td>
		                      		<select class="change-status" name="pmStatus">
			                      		<option <?php if($value["pmStatus"] == "下架中") echo "selected"; ?> value="0">下架中</option>
			                      		<option <?php if($value["pmStatus"] == "上架中") echo "selected"; ?> value="1">上架中</option>
			                      		<option <?php if($value["pmStatus"] == "缺貨中") echo "selected"; ?> value="2">缺貨中</option>
			                      	</select>
		                      	</td>
								-->
		                      	<td>
		                      		<a class="content-edit" style="text-decoration: none;" href="#">
			                        	<span style="margin-right:10px;" class="glyphicon glyphicon-pencil"></span>
			                        </a>
		                      	</td>
		                      </tr>
	                      <?php 
	                      		$i++;
	                      	} 
	                      }
	                      ?>
	                    </tbody>
	                  </table>
	                  <div style="margin-bottom:30px;" class="x_title"></div>
	                  <div class="form-group not-print">
	                      <div class="col-md-6 col-sm-6 col-xs-12 col-md-offset-3">
	                        <a style="color:#FFF;" href="?page=product&type=productManage">
	                          <button class="btn btn-primary">回上架列表</button>
	                        </a>
	                        <?php if(isset($_SERVER['HTTP_REFERER'])){?>
	                        <a style="color:#FFF;" href="<?php echo $_SERVER['HTTP_REFERER']; ?>">
	                          <button class="btn btn-primary">回上頁</button>
	                        </a>
	                        <?php }?>
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
        		<!-- 新增區 -->
        		<div class="modal fade bs-example-modal-lg" tabindex="-1" role="dialog" aria-hidden="true">
                  <div class="modal-dialog modal-lg">
                    <div class="modal-content">

                      <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">×</span>
                        </button>
                        <h4 class="modal-title" id="myModalLabel">新增供應商</h4>
                      </div>
                      <div class="modal-body">
                        <form>
                        	<input type="hidden" name="proNo" value="<?php echo $proNo; ?>">
                        	<div class="form-group">
		                      <label class="control-label col-md-3 col-sm-3 col-xs-12" for="first-name">
		                      	供應商 : 
		                      </label>
		                      <div class="col-md-9 col-sm-9 col-xs-12">
			                      <select name="supNo" class="form-control">
			                      <?php 
			                      $supDataExcept = $sup->getAllSupExcept($supNoArr);
			                      if($supDataExcept != null){ 
			                      ?>
			                      	<option selected disabled>請選擇</option>
				                      	<?php 
				                      	foreach($supDataExcept as $key=>$value){ 
				                      	?>
				                        	<option value="<?php echo $value["supNo"]; ?>"><?php echo $value["supNo"]." - ".$value["supName"]; ?></option>
				                    <?php 
				                      	}
		                      		}else{ 
				                    ?>
				                    <option selected disabled>已加入所有提供該商品之供應商</option>
				                    <?php }?>
			                      </select>
			                      <ul class="parsley-errors-list"><li id="supNoErr"></li></ul>
			                  </div>
		                    </div>
                        	<div class="form-group">
		                      <label class="control-label col-md-3 col-sm-3 col-xs-12" for="first-name">
		                      	供應價格 : 
		                      </label>
		                      <div class="col-md-9 col-sm-9 col-xs-12">
		                      	<input type="text" class="form-control" name="pmSupPrice" />
		                      	<ul class="parsley-errors-list"><li id="pmSupPriceErr"></li></ul>
		                      </div>
		                    </div>
                        </form>
                      </div>
                      <div class="modal-footer">
                        <button type="button" class="btn btn-default" data-dismiss="modal">關閉</button>
                        <?php if($supDataExcept != null){?><button id="confirm-insert" type="submit" class="btn btn-primary">確認新增</button><?php }?>
                      </div>

                    </div>
                  </div>
                </div>
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