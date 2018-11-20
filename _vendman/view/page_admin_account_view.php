<?php 
require_once('model/require_general.php');

$sm = new System_Manager();
$ag = new Admin_Group();
$smNo = $_GET["smno"];
$smData = $sm->getOneSMByNo($smNo);
$agData = $ag->getOneAGByNo($smData[0]["agNo"]);

?>
<!-- page content -->
      <div class="right_col" role="main" style="min-height: 949px;">
        <div class="">

          <div class="page-title">
            <div class="title_left">
              <h3>管理員詳細資訊</h3>
            </div>
          </div>
          <div class="clearfix"></div>
          <div class="row">
            <div class="col-md-12 col-sm-12 col-xs-12">
              <div class="x_panel">
                <div class="x_title">
                  <h2 style="text-align:center;float:none;">管理員編號: <?php echo $smNo; ?></h2>
                  <div class="clearfix"></div>
                </div>
                <div class="x_content">
                  <br>
                  <div class="form-horizontal form-label-left">
                    <div class="form-group">
                      <label class="control-label col-md-3 col-sm-3 col-xs-12" for="first-name">
                      	姓名 : 
                      </label>
                      <h5 style="color:#999;"><?php echo $smData[0]["smName"]; ?></h5>
                    </div>
                    <div class="form-group">
                      <label class="control-label col-md-3 col-sm-3 col-xs-12" for="first-name">
                      	帳號 : 
                      </label>
                      <h5 style="color:#999;"><?php echo $smData[0]["smAccount"]; ?></h5>
                    </div>
                    <div class="form-group">
                      <label class="control-label col-md-3 col-sm-3 col-xs-12" for="first-name">
                      	密碼 : 
                      </label>
                      <h5 style="color:#999;"><?php echo $smData[0]["smPwd"]; ?></h5>
                    </div>
                    <div class="form-group">
                      <label class="control-label col-md-3 col-sm-3 col-xs-12" for="first-name">
                      	權限 : 
                      </label>
                      <h5 style="color:#999;"><?php echo $agData[0]["agName"]; ?></h5>
                    </div>
                    <div class="form-group">
                      <label class="control-label col-md-3 col-sm-3 col-xs-12" for="first-name">
                      	電話 : 
                      </label>
                      <h5 style="color:#999;"><?php echo $smData[0]["smPhone"]; ?></h5>
                    </div>
                    <div class="form-group">
                      <label class="control-label col-md-3 col-sm-3 col-xs-12" for="first-name">
                      	Email : 
                      </label>
                      <h5 style="color:#999;"><?php echo $smData[0]["smEmail"]; ?></h5>
                    </div>
                    <div class="form-group">
                      <label class="control-label col-md-3 col-sm-3 col-xs-12" for="first-name">
                      	備註 : 
                      </label>
                      <h5 style="color:#999;"><?php echo $smData[0]["smComment"]; ?></h5>
                    </div>
                    <div class="form-group">
                      <label class="control-label col-md-3 col-sm-3 col-xs-12" for="first-name">
                      	最後登入IP : 
                      </label>
                      <h5 style="color:#999;"><?php echo $smData[0]["smLastIp"]; ?></h5>
                    </div>
                    <div class="form-group">
                      <label class="control-label col-md-3 col-sm-3 col-xs-12" for="first-name">
                      	最後登入時間 : 
                      </label>
                      <h5 style="color:#999;"><?php echo $smData[0]["smLastTime"]; ?></h5>
                    </div>
                    <div class="form-group">
                      <label class="control-label col-md-3 col-sm-3 col-xs-12" for="first-name">
                      	建立日期 : 
                      </label>
                      <h5 style="color:#999;"><?php echo $smData[0]["smDate"]; ?></h5>
                    </div>
                    <div class="form-group not-print">
                      <label class="control-label col-md-3 col-sm-3 col-xs-12" for="first-name">
                      	採取動作 : 
                      </label>
                      <a style="text-decoration:none;" href="?page=right&type=account&action=edit&smno=<?php echo $smNo; ?>">
                      	<button class="btn btn-success">編輯</button>
                      </a>
                    </div>
                    <div style="margin:30px;"></div>
                    <div class="form-group not-print">
                      <div class="col-md-6 col-sm-6 col-xs-12 col-md-offset-3">
                        <a style="color:#FFF;" href="?page=right&type=account">
                          <button class="btn btn-primary">回管理者列表</button>
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
		if(confirm("確定要刪除此消息？")){
			$.ajax({
				type: "POST",
				url: "ajax/qanda/delete.php",
				data: "qaNo="+<?php echo $qaNo; ?>,
				success: function(result){    
					if(result.indexOf("成功") != -1){
						alert(result);
						location.href = "?page=customer&type=qanda";
					}else{
						alert(result);
					}
				}
			});
		}
	});
});
</script>