<?php 
require_once('model/require_general.php');

$sup = new Supplier();
$supNo = $_GET["supno"];
$supData = $sup->getOneSupplierByNo($supNo);

?>
<!-- page content -->
      <div class="right_col" role="main" style="min-height: 949px;">
        <div class="">

          <div class="page-title">
            <div class="title_left">
              <h3>廠商詳細資料</h3>
            </div>
          </div>
          <div class="clearfix"></div>
          <div class="row">
            <div class="col-md-12 col-sm-12 col-xs-12">
              <div class="x_panel">
                <div class="x_title">
                  <h2 style="text-align:center;float:none;">廠商編號: <?php echo $supNo; ?></h2>
                  <div class="clearfix"></div>
                </div>
                <div class="x_content">
                  <br>
                  <div class="form-horizontal form-label-left">
                    <div class="form-group">
                      <label class="control-label col-md-3 col-sm-3 col-xs-12" for="first-name">
                      	廠商名稱 : 
                      </label>
                      <h5 style="color:#999;"><?php echo $supData[0]["supName"]; ?></h5>
                    </div>
                    <div class="form-group">
                      <label class="control-label col-md-3 col-sm-3 col-xs-12" for="first-name">
                      	廠商電話 : 
                      </label>
                      <h5 style="color:#999;"><?php echo $supData[0]["supPhone"]; ?></h5>
                    </div>
                    <div class="form-group">
                      <label class="control-label col-md-3 col-sm-3 col-xs-12" for="first-name">
                      	廠商手機 : 
                      </label>
                      <h5 style="color:#999;"><?php echo $supData[0]["supCell"]; ?></h5>
                    </div>
                    <div class="form-group">
                      <label class="control-label col-md-3 col-sm-3 col-xs-12" for="first-name">
                      	廠商地址 : 
                      </label>
                      <h5 style="color:#999;"><?php echo $supData[0]["supAddr"]; ?></h5>
                    </div>
                    <div class="form-group">
                      <label class="control-label col-md-3 col-sm-3 col-xs-12" for="first-name">
                      	廠商聯絡人 : 
                      </label>
                      <h5 style="color:#999;"><?php echo $supData[0]["supContactName"]; ?></h5>
                    </div>
                    <div class="form-group">
                      <label class="control-label col-md-3 col-sm-3 col-xs-12" for="first-name">
                      	廠商傳真 : 
                      </label>
                      <h5 style="color:#999;"><?php echo $supData[0]["supFax"]; ?></h5>
                    </div>
                    <div class="form-group">
                      <label class="control-label col-md-3 col-sm-3 col-xs-12" for="first-name">
                      	廠商印章圖 : 
                      </label>
                      <img style="width:300px;" src="<?php echo $supData[0]["supStampImg"]; ?>">
                    </div>
                    <div class="form-group">
                      <label class="control-label col-md-3 col-sm-3 col-xs-12" for="first-name">
                      	廠商Email : 
                      </label>
                      <h5 style="color:#999;"><?php echo $supData[0]["supEmail"]; ?></h5>
                    </div>
                    <div class="form-group not-print">
                      <label class="control-label col-md-3 col-sm-3 col-xs-12" for="first-name">
                      	採取動作 : 
                      </label>
                      <a style="text-decoration:none;" href="?page=supplier&action=edit&supno=<?php echo $supNo; ?>">
                      	<button class="btn btn-success">編輯</button>
                      </a>
<!--                       <button id="content-remove" class="btn btn-danger">刪除</button> -->
                    </div>
                    <div style="margin:30px;"></div>
                    <div class="form-group not-print">
                      <div class="col-md-6 col-sm-6 col-xs-12 col-md-offset-3">
                        <a style="color:#FFF;" href="?page=supplier">
                          <button class="btn btn-primary">回廠商列表</button>
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