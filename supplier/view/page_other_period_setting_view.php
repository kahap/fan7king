<?php 
require_once('model/require_general.php');

$ps = new Period_Setting();
$psNo = $_GET["psno"];
$psData = $ps->getOnePSByNo($psNo);

?>
<!-- page content -->
      <div class="right_col" role="main" style="min-height: 949px;">
        <div class="">

          <div class="page-title">
            <div class="title_left">
              <h3>分期計算詳細資訊</h3>
            </div>
          </div>
          <div class="clearfix"></div>
          <div class="row">
            <div class="col-md-12 col-sm-12 col-xs-12">
              <div class="x_panel">
                <div class="x_title">
                  <h2 style="text-align:center;float:none;">分期計算編號: <?php echo $psNo; ?></h2>
                  <div class="clearfix"></div>
                </div>
                <div class="x_content">
                  <br>
                  <div class="form-horizontal form-label-left">
                    <div class="form-group">
                      <label class="control-label col-md-3 col-sm-3 col-xs-12" for="first-name">
                      	期數 : 
                      </label>
                      <h5 style="color:#999;"><?php echo $psData[0]["psMonthNum"]; ?></h5>
                    </div>
                    <div class="form-group">
                      <label class="control-label col-md-3 col-sm-3 col-xs-12" for="first-name">
                      	總額倍率 : 
                      </label>
                      <h5 style="color:#999;"><?php echo $psData[0]["psRatio"]; ?></h5>
                    </div>
                    <div class="form-group">
                      <label class="control-label col-md-3 col-sm-3 col-xs-12" for="first-name">
                      	排序 : 
                      </label>
                      <h5 style="color:#999;"><?php echo $psData[0]["psOrder"]; ?></h5>
                    </div>
                    <div class="form-group">
                      <label class="control-label col-md-3 col-sm-3 col-xs-12" for="first-name">
                      	建立時間 : 
                      </label>
                      <h5 style="color:#999;"><?php echo $psData[0]["psDate"]; ?></h5>
                    </div>
                    <div class="form-group not-print">
                      <label class="control-label col-md-3 col-sm-3 col-xs-12" for="first-name">
                      	採取動作 : 
                      </label>
                      <a style="text-decoration:none;" href="?page=other&type=periodSetting&action=edit&psno=<?php echo $psData[0]["psNo"]; ?>">
                      	<button class="btn btn-success">編輯</button>
                      </a>
                      <button id="content-remove" class="btn btn-danger">刪除</button>
                    </div>
                    <div style="margin:30px;"></div>
                    <div class="form-group not-print">
                      <div class="col-md-6 col-sm-6 col-xs-12 col-md-offset-3">
                        <a style="color:#FFF;" href="?page=other&type=periodSetting">
                          <button class="btn btn-primary">回分期計算列表</button>
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
	$("#content-remove").click(function(){
		if(confirm("確定要刪除此分期計算？")){
			$.ajax({
				type: "POST",
				url: "ajax/periodSetting/delete.php",
				data: "psNo="+<?php echo $psNo; ?>,
				success: function(result){    
					if(result.indexOf("成功") != -1){
						alert(result);
						location.href = "?page=other&type=periodSetting";
					}else{
						alert(result);
					}
				}
			});
		}
	});
});
</script>