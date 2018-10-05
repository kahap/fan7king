<?php 
require_once('model/require_general.php');

$ad = new Advertise();
if($_GET["action"] == "edit"){
	$adNo = $_GET["adno"];
	$adData = $ad->getOne($adNo);
}

?>
<!-- page content -->
      <div class="right_col" role="main" style="min-height: 949px;">
        <div class="">

          <div class="page-title">
            <div class="title_left">
            <?php if($_GET["action"]=="edit"){ ?>
              <h3>編輯廣告</h3>
            <?php }else{ ?>
              <h3>新增廣告</h3>
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
                  	  廣告編號: <?php echo $adNo; ?> &nbsp&nbsp&nbsp&nbsp
                  	<?php if(isset($_SERVER['HTTP_REFERER'])){ ?>
                  	<a style="color:#FFF;" href="<?php echo $_SERVER['HTTP_REFERER']; ?>">
	                  <button class="btn btn-success">回上頁</button>
	                </a>
	                <?php }?>
                  <?php }else{ ?>
                  	<a style="color:#FFF;" href="?page=other&type=advertise">
	                  <button class="btn btn-success">回廣告列表</button>
	                </a>
                  <?php }?>
                  </h2>
                  <div class="clearfix"></div>
                </div>
                <div class="x_content">
                  <br>
                  <form class="form-horizontal form-label-left">
                  <?php if($_GET["action"]=="edit"){ ?>
                    <input type="hidden" name="adNo" value="<?php echo $adNo; ?>">
                  <?php } ?>
                    <div class="form-group">
                      <label class="control-label col-md-3 col-sm-3 col-xs-12" for="first-name">
                      	廣告順序(數字) : 
                      </label>
                      <div class="col-md-6 col-sm-6 col-xs-12">
                      	<input value="<?php if($_GET["action"]=="edit") echo $adData[0]["adOrder"]; ?>" required="required" type="text" class="form-control" name="adOrder" />
                      </div>
                    </div>
                    <div class="form-group">
                      <label class="control-label col-md-3 col-sm-3 col-xs-12" for="first-name">
                      	輪播圖片 : 
                      </label>
                      <div class="col-md-6 col-sm-6 col-xs-12">
                        <input style="display:inline-block;" name="adImage" type="file" id="stamp-upload">
                      	<span id="adImgErr" style="color:red;"></span>
                      </div>
                      <div class="col-md-6 col-sm-6 col-xs-12">
                        <img id="show-stamp" style="max-width:500px;" src="<?php if($_GET["action"]=="edit") echo $adData[0]["adImage"]; ?>">
                      </div>
                    </div>
                    <div class="form-group">
                      <label class="control-label col-md-3 col-sm-3 col-xs-12" for="first-name">
                      	廣告所屬區塊 : 
                      </label>
                      <div class="col-md-6 col-sm-6 col-xs-12">
                      	<select name="adArea" class="form-control">
                      	<?php foreach($ad->areaArr as $key=>$value){ ?>
	                      <option <?php if($_GET["action"]=="edit" && $adData[0]["adArea"]==$key) echo "selected"; ?> value="<?php echo $key; ?>"><?php echo $value.$ad->sizeArr[$key]; ?></option>
	                    <?php } ?>
	                    </select>
                      </div>
                    </div>
                    <div class="form-group">
                      <label class="control-label col-md-3 col-sm-3 col-xs-12" for="first-name">
                      	廣告連結 : 
                      </label>
                      <div class="col-md-6 col-sm-6 col-xs-12">
                      	<input value="<?php if($_GET["action"]=="edit") echo $adData[0]["adLink"]; ?>" required="required" type="text" class="form-control" name="adLink" />
                      </div>
                    </div>
					<div class="form-group">
                      <label class="control-label col-md-3 col-sm-3 col-xs-12" for="first-name">
                      	是否顯示 : 
                      </label>
                      <div class="col-md-6 col-sm-6 col-xs-12">
                      	<select name="adIfShow" class="form-control">
                          <option <?php if($_GET["action"]=="edit" && $adData[0]["adIfShow"]==1) echo "selected"; ?> value="1">是</option>
                          <option <?php if($_GET["action"]=="edit" && $adData[0]["adIfShow"]==0) echo "selected"; ?> value="0">否</option>
                        </select>
                        <ul class="parsley-errors-list"><li id="qaQuesErr"></li></ul>
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
		$("#adImgErr").text("");
		$(".parsley-errors-list li").text("");
		e.preventDefault();
		
		var form = new FormData($("form")[0]);
		var url = "ajax/indexImage/<?php echo $_GET["action"]; ?>.php";
		var redirect = "?page=other&type=advertise<?php if($_GET["action"]=="edit") echo "&action=view&adno=".$adNo; if($_GET["action"]=="insert") echo "&pageIndex=last" ?>"
		
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
					addError($("#adImgErr"),results.errMsg.adImageErr);
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