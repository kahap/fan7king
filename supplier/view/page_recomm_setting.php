<?php 
require_once('model/require_general.php');

$rs = new Recomm_Setting();
$rsData = $rs->getSetting();

?>
<!-- page content -->
      <div class="right_col" role="main" style="min-height: 949px;">
        <div class="">

          <div class="page-title">
            <div class="title_left">
              <h3>推薦獎金設定</h3>
            </div>
          </div>
          <div class="clearfix"></div>
          <div class="row">
            <div class="col-md-12 col-sm-12 col-xs-12">
              <div class="x_panel">
              	<div class="x_title">
                  <h2 style="text-align:center;float:none;">
                  	<?php if(isset($_SERVER['HTTP_REFERER'])){ ?>
                  	<a style="color:#FFF;" href="<?php echo $_SERVER['HTTP_REFERER']; ?>">
	                  <button class="btn btn-success">回上頁</button>
	                </a>
	                <?php }?>
                  </h2>
                  <div class="clearfix"></div>
                </div>
                <div class="x_content">
                  <br>
                  <form class="form-horizontal form-label-left">
                    <div class="form-group">
                      <label class="control-label col-md-3 col-sm-3 col-xs-12" for="first-name">
                      	推薦碼年度(去年11/1~今年10/31)獎金歸零不計算： 
                      </label>
                      <div class="col-md-6 col-sm-6 col-xs-12">
                      	<select class="form-control" name="rsYearOption">
                      		<option <?php if($rsData[0]["rsYearOption"] == 1) echo "selected"; ?> value="1">開啟</option>
                      		<option <?php if($rsData[0]["rsYearOption"] == 0) echo "selected"; ?> value="0">關閉</option>
                      	</select>
                      </div>
                    </div>
                    <div class="form-group">
                      <label class="control-label col-md-3 col-sm-3 col-xs-12" for="first-name">
                      	推薦碼每件計算獎金： 
                      </label>
                      <div class="col-md-6 col-sm-6 col-xs-12">
                      	<input value="<?php echo $rsData[0]["rsTotalPerOrder"]; ?>" type="text" class="form-control" name="rsTotalPerOrder" />
                      	<ul class="parsley-errors-list"><li id="totalErr"></li></ul>
                      </div>
                    </div>
                    <div class="form-group">
                      <label class="control-label col-md-3 col-sm-3 col-xs-12" for="first-name">
                      	推薦碼可請款天數 : 
                      </label>
                      <div class="col-md-6 col-sm-6 col-xs-12">
                      	<input value="<?php echo $rsData[0]["rsDaysLimit"]; ?>" type="text" class="form-control" name="rsDaysLimit" />
                      	<ul class="parsley-errors-list"><li id="limitErr"></li></ul>
                      </div>
                    </div>
                    <div class="form-group">
                      <label class="control-label col-md-3 col-sm-3 col-xs-12" for="first-name">
                      	推薦碼請款金額門檻(NT$)：
                      </label>
                      <div class="col-md-6 col-sm-6 col-xs-12">
                      	<input value="<?php echo $rsData[0]["rsMinimum"]; ?>" type="text" class="form-control" name="rsMinimum" />
                      	<ul class="parsley-errors-list"><li id="minimumErr"></li></ul>
                      </div>
                    </div>
                    <div class="form-group">
                      <label class="control-label col-md-3 col-sm-3 col-xs-12" for="first-name">
                      	推薦碼手續費(NT$)：
                      </label>
                      <div class="col-md-6 col-sm-6 col-xs-12">
                        <input value="<?php echo $rsData[0]["rsCharge"]; ?>" type="text" class="form-control" name="rsCharge" />
                      	<ul class="parsley-errors-list"><li id="chargeErr"></li></ul>
                      </div>
                    </div>
                    <div class="form-group">
                      <label class="control-label col-md-3 col-sm-3 col-xs-12" for="first-name">
                      	推薦碼文字分享設定：
                      </label>
                      <div class="col-md-6 col-sm-6 col-xs-12">
                        <input value="<?php echo $rsData[0]["rsShareText"]; ?>" type="text" class="form-control" name="rsShareText" />
                      </div>
                    </div>
                    <div style="margin:30px;"></div>
                    <div class="form-group">
                      <div class="col-md-6 col-sm-6 col-xs-12 col-md-offset-3">
                         <button type="submit" class="btn btn-primary">
                         	確定儲存
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
		$(".parsley-errors-list li").text("");
		e.preventDefault();
		
		var form = $("form").serialize();
		var url = "ajax/recommSetting/edit.php";
		
		$.ajax({
			url:url,
			type:"post",
			data:form,
			datatype:"json",
			success:function(result){
				var results = JSON.parse(result);
				if(results.errMsg != ""){
					addError($("#totalErr"),results.errMsg.rsTotalPerOrderErr);
					addError($("#limitErr"),results.errMsg.rsDaysLimitErr);
					addError($("#minimumErr"),results.errMsg.rsMinimumErr);
					addError($("#chargeErr"),results.errMsg.rsChargeErr);
				}else if(results.errMsg == ""){
					alert(results.success);
					location.reload();
				}
			}
		});
	});

});
function addError(selector, errMsg){
	selector.text(errMsg);
}


</script>