<?php 
require_once('model/require_general.php');

$os = new Other_Setting();
$osData = $os->getAll();

?>
<!-- page content -->
      <div class="right_col" role="main" style="min-height: 949px;">
        <div class="">

          <div class="page-title">
            <div class="title_left">
              <h3>發票開立鑑賞期天數</h3>
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
                      	發票開立鑑賞期天數：
                      </label>
                      <div class="col-md-6 col-sm-6 col-xs-12">
                      	<input value="<?php echo $osData[0]["receiptDays"]; ?>" type="text" class="form-control" name=receiptDays />
                      	<ul class="parsley-errors-list"><li id="receiptDaysErr"></li></ul>
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
		var url = "ajax/receipt/edit_receipt.php";
		
		$.ajax({
			url:url,
			type:"post",
			data:form,
			success:function(result){
				if(result.indexOf("成功") != -1){
					alert(result);
					location.reload();
				}else{
					addError($("#receiptDaysErr"),result);
				}
			}
		});
	});

});
function addError(selector, errMsg){
	selector.text(errMsg);
}


</script>