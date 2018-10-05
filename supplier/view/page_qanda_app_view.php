<?php 
require_once('model/require_general.php');

$qaa = new Qa_App();
$qaaNo = $_GET["qaano"];
$qaData = $qaa->getOne($qaaNo);
$ifShow = "";
if($qaData[0]["qaaIfShow"] == 1){
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
              <h3>APP常見問題詳細資訊</h3>
            </div>
          </div>
          <div class="clearfix"></div>
          <div class="row">
            <div class="col-md-12 col-sm-12 col-xs-12">
              <div class="x_panel">
                <div class="x_title">
                  <h2 style="text-align:center;float:none;">提問編號: <?php echo $qaaNo; ?></h2>
                  <div class="clearfix"></div>
                </div>
                <div class="x_content">
                  <br>
                  <div class="form-horizontal form-label-left">
                    <div class="form-group">
                      <label class="control-label col-md-3 col-sm-3 col-xs-12" for="first-name">
                      	問題 : 
                      </label>
                      <h5 style="color:#999;"><?php echo $qaData[0]["qaaQues"]; ?></h5>
                    </div>
                    <div class="form-group">
                      <label class="control-label col-md-3 col-sm-3 col-xs-12" for="first-name">
                      	回答 : 
                      </label>
                      <div class="col-md-6 col-sm-6 col-xs-12" style="color:#999;"><?php echo $qaData[0]["qaaAnsw"]; ?></div>
                    </div>
                    <div class="form-group">
                      <label class="control-label col-md-3 col-sm-3 col-xs-12" for="first-name">
                      	問題成立時間 : 
                      </label>
                      <h5 style="color:#999;"><?php echo $qaData[0]["qaaDate"]; ?></h5>
                    </div>
                    <div class="form-group">
                      <label class="control-label col-md-3 col-sm-3 col-xs-12" for="first-name">
                      	問題排序 : 
                      </label>
                      <h5 style="color:#999;"><?php echo $qaData[0]["qaaOrder"]; ?></h5>
                    </div>
					<div class="form-group">
                      <label class="control-label col-md-3 col-sm-3 col-xs-12" for="first-name">
                      	是否顯示 : 
                      </label>
                      <h5 style="color:#999;"><?php echo $ifShow; ?></h5>
                    </div>
                    
                    <div class="form-group not-print">
                      <label class="control-label col-md-3 col-sm-3 col-xs-12" for="first-name">
                      	採取動作 : 
                      </label>
                      <a style="text-decoration:none;" href="?page=qaapp&action=edit&qaano=<?php echo $qaaNo; ?>">
                      	<button class="btn btn-success">編輯</button>
                      </a>
                      <button id="content-remove" class="btn btn-danger">刪除</button>
                    </div>
                    <div style="margin:30px;"></div>
                    <div class="form-group not-print">
                      <div class="col-md-6 col-sm-6 col-xs-12 col-md-offset-3">
                        <a style="color:#FFF;" href="?page=qaapp">
                          <button class="btn btn-primary">回提問列表</button>
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
		if(confirm("確定要刪除此問答？")){
			$.ajax({
				type: "POST",
				url: "ajax/qanda_app/delete.php",
				data: "qaaNo="+<?php echo $qaaNo; ?>,
				success: function(result){    
					if(result.indexOf("成功") != -1){
						alert(result);
						location.href = "?page=qaapp";
					}else{
						alert(result);
					}
				}
			});
		}
	});
});
</script>