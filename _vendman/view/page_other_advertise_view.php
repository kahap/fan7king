<?php 
require_once('model/require_general.php');

$ad = new Advertise();
$adNo = $_GET["adno"];
$adData = $ad->getOne($adNo);
$ifShow = "";
if($adData[0]["adIfShow"] == 1){
	$ifShow = "是";
}else{
	$ifShow = "否";
}

?>
<!-- page content -->
      <div class="right_col" role="main" style="min-height: 949px;">
        <div class="">

          <div class="page-title">
            <div class="title_left">
              <h3>廣告詳細資訊</h3>
            </div>
          </div>
          <div class="clearfix"></div>
          <div class="row">
            <div class="col-md-12 col-sm-12 col-xs-12">
              <div class="x_panel">
                <div class="x_title">
                  <h2 style="text-align:center;float:none;">廣告圖編號: <?php echo $adNo; ?></h2>
                  <div class="clearfix"></div>
                </div>
                <div class="x_content">
                  <br>
                  <div class="form-horizontal form-label-left">
                    <div class="form-group">
                      <label class="control-label col-md-3 col-sm-3 col-xs-12" for="first-name">
                      	廣告順序 : 
                      </label>
                      <h5 style="color:#999;"><?php echo $adData[0]["adOrder"]; ?></h5>
                    </div>
                    <div class="form-group">
                      <label class="control-label col-md-3 col-sm-3 col-xs-12" for="first-name">
                      	廣告圖片 : 
                      </label>
                      <div id="preview-area" class="col-md-6 col-sm-6 col-xs-12">
                        <img style="border:2px solid #AAA;padding:5px;margin:20px;max-width:400px;" src="<?php echo $adData[0]["adImage"]; ?>">
                      </div>
                    </div>
                    <div class="form-group">
                      <label class="control-label col-md-3 col-sm-3 col-xs-12" for="first-name">
                      	所屬廣告區塊 : 
                      </label>
                      <h5 style="color:#999;"><?php echo $ad->areaArr[$adData[0]["adArea"]]; ?></h5>
                    </div>
                    <div class="form-group">
                      <label class="control-label col-md-3 col-sm-3 col-xs-12" for="first-name">
                      	廣告連結 : 
                      </label>
                      <h5 style="color:#999;"><a target="_blank" style="text-decoration:underline;color:blue;" href="<?php echo $adData[0]["adLink"]; ?>"><?php echo $adData[0]["adLink"]; ?></a></h5>
                    </div>
					<div class="form-group">
                      <label class="control-label col-md-3 col-sm-3 col-xs-12" for="first-name">
                      	是否顯示 : 
                      </label>
                      <h5 style="color:#999;"><?php echo $ifShow; ?></h5>
                    </div>
                    <div class="form-group">
                      <label class="control-label col-md-3 col-sm-3 col-xs-12" for="first-name">
                      	建立時間 : 
                      </label>
                      <h5 style="color:#999;"><?php echo $adData[0]["adDate"]; ?></h5>
                    </div>
                    <div class="form-group not-print">
                      <label class="control-label col-md-3 col-sm-3 col-xs-12" for="first-name">
                      	採取動作 : 
                      </label>
                      <a style="text-decoration:none;" href="?page=other&type=advertise&action=edit&adno=<?php echo $adData[0]["adNo"]; ?>">
                      	<button class="btn btn-success">編輯</button>
                      </a>
                      <button id="content-remove" class="btn btn-danger">刪除</button>
                    </div>
                    <div style="margin:30px;"></div>
                    <div class="form-group not-print">
                      <div class="col-md-6 col-sm-6 col-xs-12 col-md-offset-3">
                        <a style="color:#FFF;" href="?page=other&type=advertise">
                          <button class="btn btn-primary">回廣告列表</button>
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
		if(confirm("確定要刪除此輪播圖？")){
			$.ajax({
				type: "POST",
				url: "ajax/indexImage/delete.php",
				data: "adNo="+<?php echo $adNo; ?>,
				success: function(result){    
					if(result.indexOf("成功") != -1){
						alert(result);
						location.href = "?page=other&type=advertise";
					}else{
						alert(result);
					}
				}
			});
		}
	});
});
</script>