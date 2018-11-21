<?php 
require_once('model/require_general.php');

$qa = new Que_And_Ans();
$qaNo = $_GET["qano"];
$qaData = $qa->getOneQAByNo($qaNo);
$ifShow = "";
if($qaData[0]["qaIfShow"] == 1){
	$ifShow = "是";
}else{
	$ifShow = "否";
}

switch ($qaData[0]["qaType"]){
    case '1':
        $qaData[0]["qaType"]="會員註冊";
        break;
    case '2':
        $qaData[0]["qaType"]="訂購申請";
        break;
    case '3':
        $qaData[0]["qaType"]="配送物流";
        break;
    case '4':
        $qaData[0]["qaType"]="商品退換";
        break;
    case '5':
        $qaData[0]["qaType"]="付款方式";
        break;
    case '6':
        $qaData[0]["qaType"]="其他相關";
        break;
}

?>
<!-- page content -->
      <div class="right_col" role="main" style="min-height: 949px;">
        <div class="">

          <div class="page-title">
            <div class="title_left">
              <h3>常見問題詳細資訊</h3>
            </div>
          </div>
          <div class="clearfix"></div>
          <div class="row">
            <div class="col-md-12 col-sm-12 col-xs-12">
              <div class="x_panel">
                <div class="x_title">
                  <h2 style="text-align:center;float:none;">提問編號: <?php echo $qaNo; ?></h2>
                  <div class="clearfix"></div>
                </div>
                <div class="x_content">
                  <br>
                  <div class="form-horizontal form-label-left">
                      <div class="form-group">
                          <label class="control-label col-md-3 col-sm-3 col-xs-12" for="first-name">
                              種類 :
                          </label>
                          <h5 style="color:#999;"><?php echo $qaData[0]["qaType"]; ?></h5>
                      </div>
                    <div class="form-group">
                      <label class="control-label col-md-3 col-sm-3 col-xs-12" for="first-name">
                      	問題 : 
                      </label>
                      <h5 style="color:#999;"><?php echo $qaData[0]["qaQues"]; ?></h5>
                    </div>
                    <div class="form-group">
                      <label class="control-label col-md-3 col-sm-3 col-xs-12" for="first-name">
                      	回答 : 
                      </label>
                      <div class="col-md-6 col-sm-6 col-xs-12" style="color:#999;"><?php echo $qaData[0]["qaAnsw"]; ?></div>
                    </div>
                    <div class="form-group">
                      <label class="control-label col-md-3 col-sm-3 col-xs-12" for="first-name">
                      	問題成立時間 : 
                      </label>
                      <h5 style="color:#999;"><?php echo $qaData[0]["qaDate"]; ?></h5>
                    </div>
                    <div class="form-group">
                      <label class="control-label col-md-3 col-sm-3 col-xs-12" for="first-name">
                      	問題排序 : 
                      </label>
                      <h5 style="color:#999;"><?php echo $qaData[0]["qaOrder"]; ?></h5>
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
                      <a style="text-decoration:none;" href="?page=qanda&action=edit&qano=<?php echo $qaNo; ?>">
                      	<button class="btn btn-success">編輯</button>
                      </a>
                      <button id="content-remove" class="btn btn-danger">刪除</button>
                    </div>
                    <div style="margin:30px;"></div>
                    <div class="form-group not-print">
                      <div class="col-md-6 col-sm-6 col-xs-12 col-md-offset-3">
                        <a style="color:#FFF;" href="?page=qanda">
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
				url: "ajax/qanda/delete.php",
				data: "qaNo="+<?php echo $qaNo; ?>,
				success: function(result){    
					if(result.indexOf("成功") != -1){
						alert(result);
						location.href = "?page=qanda";
					}else{
						alert(result);
					}
				}
			});
		}
	});
});
</script>