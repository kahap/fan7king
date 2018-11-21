<?php 
require_once('model/require_general.php');

$rbs = new Recomm_Bonus_Success();
$rbsNo = $_GET["rbsno"];
$rbsData = $rbs->getOneRBSByNo($rbsNo);

?>
<!-- page content -->
      <div class="right_col" role="main" style="min-height: 949px;">
        <div class="">

          <div class="page-title">
            <div class="title_left">
              <h3>推薦碼獎金撥款申請者證件上傳</h3>
            </div>
          </div>
          <div class="clearfix"></div>
          <div class="row">
            <div class="col-md-12 col-sm-12 col-xs-12">
              <div class="x_panel">
                <div class="x_title">
                  <h2 style="text-align:center;float:none;">申請撥款編號: <?php echo $rbsNo; ?></h2>
                  <div class="clearfix"></div>
                </div>
                <div class="x_content">
                  <br>
                  <div class="form-horizontal form-label-left">
                    
                    <div class="form-group">
                      <label class="control-label col-md-3 col-sm-3 col-xs-12" for="first-name">
                      	身分證正面 : 
                      </label>
                      <img style="width:300px;" src="<?php echo "../".$rbsData[0]["rbsIdTopImg"]; ?>">
                    </div>
                    <div class="form-group">
                      <label class="control-label col-md-3 col-sm-3 col-xs-12" for="first-name">
                      	身分證反面 : 
                      </label>
                      <img style="width:300px;" src="<?php echo "../".$rbsData[0]["rbsIdBotImg"]; ?>">
                    </div>
                    <div class="form-group">
                      <label class="control-label col-md-3 col-sm-3 col-xs-12" for="first-name">
                      	存摺帳號正面 : 
                      </label>
                      <img style="width:300px;" src="<?php echo "../".$rbsData[0]["rbsBankBookImg"]; ?>">
                    </div>
                    <div style="margin:30px;"></div>
                    <div class="form-group not-print">
                      <div class="col-md-6 col-sm-6 col-xs-12 col-md-offset-3">
                        <a style="color:#FFF;" href="?page=recommBonus&type=confirm">
                          <button class="btn btn-primary">回撥款列表</button>
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
        <!-- /page content -->
<script>
$(function(){
// 	$("#content-remove").click(function(){
// 		if(confirm("確定要刪除此廠商？")){
// 			$.ajax({
// 				type: "POST",
// 				url: "ajax/supplier/delete.php",
//				data: "supNo="+,
// 				success: function(result){    
// 					if(result.indexOf("成功") != -1){
// 						alert(result);
// 						location.href = "?page=supplier";
// 					}else{
// 						alert(result);
// 					}
// 				}
// 			});
// 		}
// 	});
});
</script>