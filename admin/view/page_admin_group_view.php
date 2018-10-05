<?php 
require_once('model/require_general.php');

$ag = new Admin_Group();
$agNo = $_GET["agno"];
$agData = $ag->getOneAGByNo($agNo);

?>
<!-- page content -->
      <div class="right_col" role="main" style="min-height: 949px;">
        <div class="">

          <div class="page-title">
            <div class="title_left">
              <h3>群組詳細資訊</h3>
            </div>
          </div>
          <div class="clearfix"></div>
          <div class="row">
            <div class="col-md-12 col-sm-12 col-xs-12">
              <div class="x_panel">
                <div class="x_title">
                  <h2 style="text-align:center;float:none;">群組編號: <?php echo $agNo; ?></h2>
                  <div class="clearfix"></div>
                </div>
                <div class="x_content">
                  <br>
                  <div class="form-horizontal form-label-left">
                    <div class="form-group">
                      <label class="control-label col-md-3 col-sm-3 col-xs-12" for="first-name">
                      	群組名稱 : 
                      </label>
                      <h5 style="color:#999;"><?php echo $agData[0]["agName"]; ?></h5>
                    </div>
                    <div class="form-group">
                      <label class="control-label col-md-3 col-sm-3 col-xs-12" for="first-name">
                      	群組權限 : 
                      </label>
                      <div class="col-md-6 col-sm-6 col-xs-12" style="color:#999;">
                      	<?php 
                      		if($agData[0]["agRight"] != "" && !empty(json_decode($agData[0]["agRight"]))){
		                      	$i=1; 
		                      	foreach(json_decode($agData[0]["agRight"]) as $key=>$value){ 
                      	?>
                      		<?php echo $i.". ".$ag->rightArr[$value]; ?><br>
                      	<?php 
	                      			$i++;
		                      	} 
							}	
                      	?>
                      </div>
                    </div>
                    <div class="form-group">
                      <label class="control-label col-md-3 col-sm-3 col-xs-12" for="first-name">
                      	成立時間 : 
                      </label>
                      <h5 style="color:#999;"><?php echo $agData[0]["agDate"]; ?></h5>
                    </div>
                    <div class="form-group not-print">
                      <label class="control-label col-md-3 col-sm-3 col-xs-12" for="first-name">
                      	採取動作 : 
                      </label>
                      <a style="text-decoration:none;" href="?page=right&type=group&action=edit&agno=<?php echo $agNo; ?>">
                      	<button class="btn btn-success">編輯</button>
                      </a>
                    </div>
                    <div style="margin:30px;"></div>
                    <div class="form-group not-print">
                      <div class="col-md-6 col-sm-6 col-xs-12 col-md-offset-3">
                        <a style="color:#FFF;" href="?page=right&type=group">
                          <button class="btn btn-primary">回群組列表</button>
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