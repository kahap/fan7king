<?php 
require_once('model/require_general.php');

$cp = new Co_Company();
$id = $_GET["id"];
$supData = $cp->getOne($id);

?>
<!-- page content -->
      <div class="right_col" role="main" style="min-height: 949px;">
        <div class="">

          <div class="page-title">
            <div class="title_left">
              <h3>合作提案詳細資料</h3>
            </div>
          </div>
          <div class="clearfix"></div>
          <div class="row">
            <div class="col-md-12 col-sm-12 col-xs-12">
              <div class="x_panel">
                <div class="x_title">
                  <h2 style="text-align:center;float:none;">提案編號: <?php echo $id; ?></h2>
                  <div class="clearfix"></div>
                </div>
                <div class="x_content">
                  <br>
                  <div class="form-horizontal form-label-left">
                    <div class="form-group">
                      <label class="control-label col-md-3 col-sm-3 col-xs-12" for="first-name">
                      	廠商名稱 : 
                      </label>
                      <h5 style="color:#999;"><?php echo $supData[0]["company_name"]; ?></h5>
                    </div>
                    <div class="form-group">
                      <label class="control-label col-md-3 col-sm-3 col-xs-12" for="first-name">
                      	聯絡人姓名 : 
                      </label>
                      <h5 style="color:#999;"><?php echo $supData[0]["contact_name"]; ?></h5>
                    </div>
                    <div class="form-group">
                      <label class="control-label col-md-3 col-sm-3 col-xs-12" for="first-name">
                      	Email : 
                      </label>
                      <h5 style="color:#999;"><?php echo $supData[0]["email"]; ?></h5>
                    </div>
                    <div class="form-group">
                      <label class="control-label col-md-3 col-sm-3 col-xs-12" for="first-name">
                      	電話 : 
                      </label>
                      <h5 style="color:#999;"><?php echo $supData[0]["phone"]; ?></h5>
                    </div>
                    <div class="form-group">
                      <label class="control-label col-md-3 col-sm-3 col-xs-12" for="first-name">
                      	提案標題 : 
                      </label>
                      <div class="col-md-6 col-sm-6 col-xs-12">
                      	<h5 style="color:#999;"><?php echo $supData[0]["topic"]; ?></h5>
                      </div>
                    </div>
                    <div class="form-group">
                      <label class="control-label col-md-3 col-sm-3 col-xs-12" for="first-name">
                      	提案內容 : 
                      </label>
                      <div class="col-md-6 col-sm-6 col-xs-12">
                      	<h5 style="color:#999;"><?php echo $supData[0]["content"]; ?></h5>
                      </div>
                    </div>
                    <div class="form-group">
                      <label class="control-label col-md-3 col-sm-3 col-xs-12" for="first-name">
                      	建立時間 : 
                      </label>
                      <h5 style="color:#999;"><?php echo $supData[0]["time"]; ?></h5>
                    </div>
                    <div style="margin:30px;"></div>
                    <div class="form-group not-print">
                      <div class="col-md-6 col-sm-6 col-xs-12 col-md-offset-3">
                        <a style="color:#FFF;" href="?page=other&type=companyCoop">
                          <button class="btn btn-primary">回提案列表</button>
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