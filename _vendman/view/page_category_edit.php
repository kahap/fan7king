<?php 
require_once('model/require_general.php');

if($_GET["action"] == "edit"){
	$cat = new Category();
	$catNo = $_GET["catno"];
	$catData = $cat->getOneCatByNo($catNo);
}

?>
<!-- page content -->
      <div class="right_col" role="main" style="min-height: 949px;">
        <div class="">

          <div class="page-title">
            <div class="title_left">
            <?php if($_GET["action"]=="edit"){ ?>
              <h3>編輯分類資料</h3>
            <?php }else{ ?>
              <h3>新增分類</h3>
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
                  	  分類編號: <?php echo $catNo; ?> &nbsp&nbsp&nbsp&nbsp
                  	<?php if(isset($_SERVER['HTTP_REFERER'])){ ?>
                  	<a style="color:#FFF;" href="<?php echo $_SERVER['HTTP_REFERER']; ?>">
	                  <button class="btn btn-success">回上頁</button>
	                </a>
	                <?php }?>
                  <?php }else{ ?>
                  	<a style="color:#FFF;" href="?page=product&type=general&which=category">
	                  <button class="btn btn-success">回分類列表</button>
	                </a>
                  <?php }?>
                  </h2>
                  <div class="clearfix"></div>
                </div>
                <div class="x_content">
                  <br>
                  <form class="form-horizontal form-label-left">
                  <?php if($_GET["action"]=="edit"){ ?>
                    <input type="hidden" name="catNo" value="<?php echo $catNo; ?>">
                  <?php } ?>
                    <div class="form-group">
                      <label class="control-label col-md-3 col-sm-3 col-xs-12" for="first-name">
                      	分類名稱 : 
                      </label>
                      <div class="col-md-6 col-sm-6 col-xs-12">
                      	<input value="<?php if($_GET["action"]=="edit") echo $catData[0]["catName"]; ?>" required="required" type="text" class="form-control" name="catName" />
                        <ul class="parsley-errors-list"><li id="nameErr"></li></ul>
                      </div>
                    </div>
                    <div class="form-group">
                      <label class="control-label col-md-3 col-sm-3 col-xs-12" for="first-name">
                      	分類順序(數字) : 
                      </label>
                      <div class="col-md-6 col-sm-6 col-xs-12">
                      	<input value="<?php if($_GET["action"]=="edit") echo $catData[0]["catOrder"]; ?>" type="text" class="form-control" name="catOrder" />
                      	<ul class="parsley-errors-list"><li id="phoneErr"></li></ul>
                      </div>
                    </div>
                    <div class="form-group">
                      <label class="control-label col-md-3 col-sm-3 col-xs-12" for="first-name">
                      	是否顯示 : 
                      </label>
                      <div class="col-md-6 col-sm-6 col-xs-12">
                      	<select class="form-control" name="catIfDisplay">
                      		<option <?php if($_GET["action"] == "edit" && $catData[0]["catIfDisplay"] == 1) echo "selected"; ?> value="1">是</option>
                      		<option <?php if($_GET["action"] == "edit" && $catData[0]["catIfDisplay"] == 0) echo "selected"; ?> value="0">否</option>
                      	</select>
                      </div>
                    </div>
                    <div class="form-group">
                      <label class="control-label col-md-3 col-sm-3 col-xs-12" for="first-name">
                      	分類圖片 : 
                      </label>
                      <div class="col-md-6 col-sm-6 col-xs-12">
                        <input style="display:inline-block;" name="catImage" type="file" id="stamp-upload">
                      	<span id="catImgErr" style="color:red;"></span>
                      </div>
                      <div class="col-md-6 col-sm-6 col-xs-12">
                        <img id="show-stamp" style="max-width:300px;" src="<?php if($_GET["action"]=="edit") echo $catData[0]["catImage"]; ?>">
                      </div>
                    </div>
                    <div class="form-group">
                      <label class="control-label col-md-3 col-sm-3 col-xs-12" for="first-name">
                      	分類圖示 : 
                      </label>
                      <div class="col-md-6 col-sm-6 col-xs-12">
                        <input style="display:inline-block;" name="catIcon" type="file" id="stamp-upload2">
                      	<span id="catIconErr" style="color:red;"></span>
                      </div>
                      <div class="col-md-6 col-sm-6 col-xs-12">
                        <img id="show-stamp2" style="max-width:300px;" src="<?php if($_GET["action"]=="edit") echo $catData[0]["catIcon"]; ?>">
                      </div>
                    </div>
					<div class="form-group">
                      <label class="control-label col-md-3 col-sm-3 col-xs-12" for="first-name">
                      	分類背景色 : 
                      </label>
                      <div class="col-md-6 col-sm-6 col-xs-12">
                        <input value="<?php if($_GET["action"]=="edit") echo $catData[0]["catColor"]; ?>" type="text" class="form-control" name="catColor" />
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
		$("#catImgErr").text("");
		$("#catIconErr").text("");
		$(".parsley-errors-list li").text("");
		e.preventDefault();
		
		var form = new FormData($("form")[0]);
		var url = "ajax/category/<?php echo $_GET["action"]; ?>.php";
		var redirect = "?page=product&type=general&which=category<?php if($_GET["action"]=="edit") echo "&action=view&catno=".$catNo; if($_GET["action"]=="insert") echo "&pageIndex=last" ?>"
		
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
					addError($("#nameErr"),results.errMsg.catNameErr);
					addError($("#catImgErr"),results.errMsg.catImageErr);
					addError($("#catIconErr"),results.errMsg.catIconErr);
				}else if(results.errMsg == ""){
					alert(results.success);
					location.href= redirect;
				}
			}
		});
	});

	$("#stamp-upload").change(function(){
	    readURL(this,$('#show-stamp'));
	});
	$("#stamp-upload2").change(function(){
	    readURL(this,$('#show-stamp2'));
	});
});
function readURL(input,selector) {

    if (input.files && input.files[0]) {
        var reader = new FileReader();

        reader.onload = function (e) {
        	selector.attr('src', e.target.result);
        }

        reader.readAsDataURL(input.files[0]);
    }
}
function addError(selector, errMsg){
	selector.text(errMsg);
}


</script>