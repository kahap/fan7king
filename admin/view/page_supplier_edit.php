<?php 
require_once('model/require_general.php');

if($_GET["action"] == "edit"){
	$sup = new Supplier();
	$supNo = $_GET["supno"];
	$supData = $sup->getOneSupplierByNo($supNo);
}

?>
<!-- page content -->
      <div class="right_col" role="main" style="min-height: 949px;">
        <div class="">

          <div class="page-title">
            <div class="title_left">
            <?php if($_GET["action"]=="edit"){ ?>
              <h3>編輯廠商資料</h3>
            <?php }else{ ?>
              <h3>新增廠商</h3>
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
                  	  廠商編號: <?php echo $supNo; ?> &nbsp&nbsp&nbsp&nbsp
                  	<?php if(isset($_SERVER['HTTP_REFERER'])){ ?>
                  	<a style="color:#FFF;" href="<?php echo $_SERVER['HTTP_REFERER']; ?>">
	                  <button class="btn btn-success">回上頁</button>
	                </a>
	                <?php }?>
                  <?php }else{ ?>
                  	<a style="color:#FFF;" href="?page=supplier">
	                  <button class="btn btn-success">回廠商列表</button>
	                </a>
                  <?php }?>
                  </h2>
                  <div class="clearfix"></div>
                </div>
                <div class="x_content">
                  <br>
                  <form class="form-horizontal form-label-left">
                  <?php if($_GET["action"]=="edit"){ ?>
                    <input type="hidden" name="supNo" value="<?php echo $supNo; ?>">
                  <?php } ?>
                    <div class="form-group">
                      <label class="control-label col-md-3 col-sm-3 col-xs-12" for="first-name">
                      	廠商名稱 : 
                      </label>
                      <div class="col-md-6 col-sm-6 col-xs-12">
                      	<input value="<?php if($_GET["action"]=="edit") echo $supData[0]["supName"]; ?>" required="required" type="text" class="form-control" name="supName" />
                        <ul class="parsley-errors-list"><li id="nameErr"></li></ul>
                      </div>
                    </div>
                    <div class="form-group">
                      <label class="control-label col-md-3 col-sm-3 col-xs-12" for="first-name">
                      	廠商電話 : 
                      </label>
                      <div class="col-md-6 col-sm-6 col-xs-12">
                      	<input value="<?php if($_GET["action"]=="edit") echo $supData[0]["supPhone"]; ?>" type="text" class="form-control" name="supPhone" />
                      	<ul class="parsley-errors-list"><li id="phoneErr"></li></ul>
                      </div>
                    </div>
                    <div class="form-group">
                      <label class="control-label col-md-3 col-sm-3 col-xs-12" for="first-name">
                      	廠商手機 : 
                      </label>
                      <div class="col-md-6 col-sm-6 col-xs-12">
                      	<input value="<?php if($_GET["action"]=="edit") echo $supData[0]["supCell"]; ?>" type="text" class="form-control" name="supCell" />
                      	<ul class="parsley-errors-list"><li id="cellErr"></li></ul>
                      </div>
                    </div>
                    <div class="form-group">
                      <label class="control-label col-md-3 col-sm-3 col-xs-12" for="first-name">
                      	廠商地址 : 
                      </label>
                      <div class="col-md-6 col-sm-6 col-xs-12">
                      	<input value="<?php if($_GET["action"]=="edit") echo $supData[0]["supAddr"]; ?>" type="text" class="form-control" name="supAddr" />
                      	<ul class="parsley-errors-list"><li id="addrErr"></li></ul>
                      </div>
                    </div>
                    <div class="form-group">
                      <label class="control-label col-md-3 col-sm-3 col-xs-12" for="first-name">
                      	廠商聯絡人 : 
                      </label>
                      <div class="col-md-6 col-sm-6 col-xs-12">
                      	<input value="<?php if($_GET["action"]=="edit") echo $supData[0]["supContactName"]; ?>" type="text" class="form-control" name="supContactName" />
                      	<ul class="parsley-errors-list"><li id="contactNameErr"></li></ul>
                      </div>
                    </div>
                    <div class="form-group">
                      <label class="control-label col-md-3 col-sm-3 col-xs-12" for="first-name">
                      	廠商傳真 : 
                      </label>
                      <div class="col-md-6 col-sm-6 col-xs-12">
                      	<input value="<?php if($_GET["action"]=="edit") echo $supData[0]["supFax"]; ?>" type="text" class="form-control" name="supFax" />
                      	<ul class="parsley-errors-list"><li id="faxErr"></li></ul>
                      </div>
                    </div>
                    <div class="form-group">
                      <label class="control-label col-md-3 col-sm-3 col-xs-12" for="first-name">
                      	廠商印章圖 : 
                      </label>
                      <div class="col-md-6 col-sm-6 col-xs-12">
                        <input style="display:inline-block;" name="supStampImg" type="file" id="stamp-upload">
                      	<span id="stampImgErr" style="color:red;"></span>
                      </div>
                      <div class="col-md-6 col-sm-6 col-xs-12">
                        <img id="show-stamp" style="max-width:300px;" src="<?php if($_GET["action"]=="edit") echo $supData[0]["supStampImg"]; ?>">
                      </div>
                    </div>
                    <div class="form-group">
                      <label class="control-label col-md-3 col-sm-3 col-xs-12" for="first-name">
                      	廠商Email : 
                      </label>
                      <div class="col-md-6 col-sm-6 col-xs-12">
                      	<input value="<?php if($_GET["action"]=="edit") echo $supData[0]["supEmail"]; ?>" type="text" class="form-control" name="supEmail" />
                      	<ul class="parsley-errors-list"><li id="emailErr"></li></ul>
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
	$("button[type='submit']").click(function(e){
		$("#stampImgErr").text("");
		$(".parsley-errors-list li").text("");
		e.preventDefault();
		
		var form = new FormData($("form")[0]);
		var url = "ajax/supplier/<?php echo $_GET["action"]; ?>.php";
		var redirect = "?page=supplier<?php if($_GET["action"]=="edit") echo "&action=view&supno=".$supNo; if($_GET["action"]=="insert") echo "&pageIndex=last" ?>"
		
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
					addError($("#stampImgErr"),results.errMsg.supStampImgErr);
					addError($("#nameErr"),results.errMsg.supNameErr);
				}else if(results.errMsg == ""){
					alert(results.success);
					location.href= redirect;
				}
			}
		});
	});

	$("#stamp-upload").change(function(){
	    readURL(this);
	});
});
function readURL(input) {

    if (input.files && input.files[0]) {
        var reader = new FileReader();

        reader.onload = function (e) {
            $('#show-stamp').attr('src', e.target.result);
        }

        reader.readAsDataURL(input.files[0]);
    }
}
function addError(selector, errMsg){
	selector.text(errMsg);
}


</script>