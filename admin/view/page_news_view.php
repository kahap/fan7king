<?php 
require_once('model/require_general.php');

$news = new News();
$newsNo = $_GET["newsno"];
$newsData = $news->getOneNewsByNo($newsNo);

?>
<!-- page content -->
      <div class="right_col" role="main" style="min-height: 949px;">
        <div class="">

          <div class="page-title">
            <div class="title_left">
              <h3>最新消息詳細資訊</h3>
            </div>
          </div>
          <div class="clearfix"></div>
          <div class="row">
            <div class="col-md-12 col-sm-12 col-xs-12">
              <div class="x_panel">
                <div class="x_title">
                  <h2 style="text-align:center;float:none;">消息編號: <?php echo $newsNo; ?></h2>
                  <div class="clearfix"></div>
                </div>
                <div class="x_content">
                  <br>
                  <div class="form-horizontal form-label-left">
                    <div class="form-group">
                      <label class="control-label col-md-3 col-sm-3 col-xs-12" for="first-name">
                      	消息標題 : 
                      </label>
                      <h5 style="color:#999;"><?php echo $newsData[0]["newsTopic"]; ?></h5>
                    </div>
                    <div class="form-group">
                      <label class="control-label col-md-3 col-sm-3 col-xs-12" for="first-name">
                      	消息內容 : 
                      </label>
                      <div class="col-md-6 col-sm-6 col-xs-12" style="color:#999;"><?php echo $newsData[0]["newsDetails"]; ?></div>
                    </div>
                    <div class="form-group">
                      <label class="control-label col-md-3 col-sm-3 col-xs-12" for="first-name">
                      	消息日期 : 
                      </label>
                      <h5 style="color:#999;"><?php echo $newsData[0]["newsDate"]; ?></h5>
                    </div>
                    
                    <div class="form-group not-print">
                      <label class="control-label col-md-3 col-sm-3 col-xs-12" for="first-name">
                      	採取動作 : 
                      </label>
                      <a style="text-decoration:none;" href="?page=other&type=news&action=edit&newsno=<?php echo $newsNo; ?>">
                      	<button class="btn btn-success">編輯</button>
                      </a>
                      <button id="content-remove" class="btn btn-danger">刪除</button>
                    </div>
                    <div style="margin:30px;"></div>
                    <div class="form-group not-print">
                      <div class="col-md-6 col-sm-6 col-xs-12 col-md-offset-3">
                        <a style="color:#FFF;" href="?page=other&type=news">
                          <button class="btn btn-primary">回消息列表</button>
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
				url: "ajax/news/delete.php",
				data: "newsNo="+<?php echo $newsNo; ?>,
				success: function(result){    
					if(result.indexOf("成功") != -1){
						alert(result);
						location.href = "?page=other&type=news";
					}else{
						alert(result);
					}
				}
			});
		}
	});
});
</script>