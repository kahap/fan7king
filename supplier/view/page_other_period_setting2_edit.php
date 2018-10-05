<?php 
require_once('model/require_general.php');

if($_GET["action"] == "edit"){
	$ps = new Period_Setting2();
	$psNo = $_GET["psno"];
	$psData = $ps->getOnePSByNo($psNo);
}

?>
<!-- page content -->
      <div class="right_col" role="main" style="min-height: 949px;">
        <div class="">

          <div class="page-title">
            <div class="title_left">
            <?php if($_GET["action"]=="edit"){ ?>
              <h3>編輯分期計算</h3>
            <?php }else{ ?>
              <h3>新增分期計算</h3>
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
                  	  分期計算編號: <?php echo $psNo; ?> &nbsp&nbsp&nbsp&nbsp
                  	<?php if(isset($_SERVER['HTTP_REFERER'])){ ?>
                  	<a style="color:#FFF;" href="<?php echo $_SERVER['HTTP_REFERER']; ?>">
	                  <button class="btn btn-success">回上頁</button>
	                </a>
	                <?php }?>
                  <?php }else{ ?>
                  	<a style="color:#FFF;" href="?page=other&type=periodSetting2">
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
                    <input type="hidden" name="psNo" value="<?php echo $psNo; ?>">
                  <?php } ?>
                    <div class="form-group">
                      <label class="control-label col-md-3 col-sm-3 col-xs-12" for="first-name">
                      	期數 : 
                      </label>
                      <div class="col-md-6 col-sm-6 col-xs-12">
                      	<input value="<?php if($_GET["action"]=="edit") echo $psData[0]["psMonthNum"]; ?>" required="required" type="text" class="form-control" name="psMonthNum" />
                        <ul class="parsley-errors-list"><li id="monthErr"></li></ul>
                      </div>
                    </div>
                    <div class="form-group">
                      <label class="control-label col-md-3 col-sm-3 col-xs-12" for="first-name">
                      	總額倍率 : 
                      </label>
                      <div class="col-md-6 col-sm-6 col-xs-12">
                      	<input value="<?php if($_GET["action"]=="edit") echo $psData[0]["psRatio"]; ?>" type="text" class="form-control" name="psRatio" />
                      	<ul class="parsley-errors-list"><li id="ratioErr"></li></ul>
                      </div>
                    </div>
                    <div class="form-group">
                      <label class="control-label col-md-3 col-sm-3 col-xs-12" for="first-name">
                      	排序 : 
                      </label>
                      <div class="col-md-6 col-sm-6 col-xs-12">
                      	<input value="<?php if($_GET["action"]=="edit") echo $psData[0]["psOrder"]; ?>" type="text" class="form-control" name="psOrder" />
                      	<ul class="parsley-errors-list"><li id="orderErr"></li></ul>
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
		
		var form = $("form").serialize();
		var url = "ajax/periodSetting2/<?php echo $_GET["action"]; ?>.php";
		var redirect = "?page=other&type=periodSetting2<?php if($_GET["action"]=="edit") echo "&action=view&psno=".$psNo; if($_GET["action"]=="insert") echo "&pageIndex=last" ?>"
		
		$.ajax({
			url:url,
			type:"post",
			data:form,
			datatype:"json",
			success:function(result){
				var results = JSON.parse(result);
				if(results.errMsg != ""){
					addError($("#monthErr"),results.errMsg.psMonthNumErr);
					addError($("#ratioErr"),results.errMsg.psRatioErr);
					addError($("#orderErr"),results.errMsg.psOrderErr);
				}else if(results.errMsg == ""){
					alert(results.success);
					location.href= redirect;
				}
			}
		});
	});
});
function addError(selector, errMsg){
	selector.text(errMsg);
}


</script>