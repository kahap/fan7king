<?php 
require_once('model/require_general.php');

$cat = new Category();
$catNo = $_GET["catno"];
$catData = $cat->getOneCatByNo($catNo);

?>
<!-- page content -->
      <div class="right_col" role="main" style="min-height: 949px;">
        <div class="">

          <div class="page-title">
            <div class="title_left">
              <h3>分類詳細資訊</h3>
            </div>
          </div>
          <div class="clearfix"></div>
          <div class="row">
            <div class="col-md-12 col-sm-12 col-xs-12">
              <div class="x_panel">
                <div class="x_title">
                  <h2 style="text-align:center;float:none;">分類編號: <?php echo $catNo; ?></h2>
                  <div class="clearfix"></div>
                </div>
                <div class="x_content">
                  <br>
                  <div class="form-horizontal form-label-left">
                    <div class="form-group">
                      <label class="control-label col-md-3 col-sm-3 col-xs-12" for="first-name">
                      	分類名稱 : 
                      </label>
                      <h5 style="color:#999;"><?php echo $catData[0]["catName"]; ?></h5>
                    </div>
                    <div class="form-group">
                      <label class="control-label col-md-3 col-sm-3 col-xs-12" for="first-name">
                      	分類排序 : 
                      </label>
                      <h5 style="color:#999;"><?php echo $catData[0]["catOrder"]; ?></h5>
                    </div>
                    <div class="form-group">
                      <label class="control-label col-md-3 col-sm-3 col-xs-12" for="first-name">
                      	分類圖片 : 
                      </label>
                      <div id="preview-area" class="col-md-6 col-sm-6 col-xs-12">
                        <img style="border:2px solid #AAA;padding:5px;margin:20px;max-width:300px;" src="<?php echo $catData[0]["catImage"]; ?>">
                      </div>
                    </div>
                    <div class="form-group">
                      <label class="control-label col-md-3 col-sm-3 col-xs-12" for="first-name">
                      	分類圖示 : 
                      </label>
                      <div id="preview-area" class="col-md-6 col-sm-6 col-xs-12">
                        <img style="border:2px solid #AAA;padding:5px;margin:20px;max-width:300px;" src="<?php echo $catData[0]["catIcon"]; ?>">
                      </div>
                    </div>
					<div class="form-group">
                      <label class="control-label col-md-3 col-sm-3 col-xs-12" for="first-name">
                      	分類背景色 : 
                      </label>
                      <div class="col-md-6 col-sm-6 col-xs-12">
                        <div style="display:inline-block;padding:10px;background-color:<?php echo $catData[0]["catColor"]; ?>;"></div>
                      </div>
                    </div>
                    <div class="form-group">
                      <label class="control-label col-md-3 col-sm-3 col-xs-12" for="first-name">
                      	建立時間 : 
                      </label>
                      <h5 style="color:#999;"><?php echo $catData[0]["catDate"]; ?></h5>
                    </div>
                    <div class="form-group not-print">
                      <label class="control-label col-md-3 col-sm-3 col-xs-12" for="first-name">
                      	採取動作 : 
                      </label>
                      <a style="text-decoration:none;" href="?page=product&type=general&which=category&action=edit&catno=<?php echo $catData[0]["catNo"]; ?>">
                      	<button class="btn btn-success">編輯</button>
                      </a>
                    </div>
                    <div style="margin:30px;"></div>
                    <div class="form-group not-print">
                      <div class="col-md-6 col-sm-6 col-xs-12 col-md-offset-3">
                        <a style="color:#FFF;" href="?page=product&type=general&which=category">
                          <button class="btn btn-primary">回分類列表</button>
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
	
});
</script>