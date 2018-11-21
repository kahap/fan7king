<?php 
require_once('model/require_general.php');


$pro = new Product();
$proNo = $_GET["prono"];
$proData = $pro->getOneProByNo($proNo);
$proImageArray = json_decode($proData[0]["proImage"]);


$cat = new Category();
$catData = $cat->getOneCatByNo($proData[0]["catNo"]);

$bra = new Brand();
$braData = $bra->getOneBrandByNo($proData[0]["braNo"]);
?>
<!-- page content -->
      <div class="right_col" role="main" style="min-height: 949px;">
        <div class="">

          <div class="page-title">
            <div class="title_left">
              <h3>商品詳細資料</h3>
            </div>
          </div>
          <div class="clearfix"></div>
          <div class="row">
            <div class="col-md-12 col-sm-12 col-xs-12">
              <div class="x_panel">
                <div class="x_title">
                  <h2 style="text-align:center;float:none;">商品編號: <?php echo $proData[0]["proCaseNo"]; ?></h2>
                  <div class="clearfix"></div>
                </div>
                <div class="x_content">
                  <br>
                  <div class="form-horizontal form-label-left">
                    <div class="form-group">
                      <label class="control-label col-md-3 col-sm-3 col-xs-12" for="first-name">
                      	所屬分類 : 
                      </label>
                      <h5 style="color:#999;"><?php echo $catData[0]["catName"]; ?></h5>
                    </div>
                    <div class="form-group">
                      <label class="control-label col-md-3 col-sm-3 col-xs-12" for="first-name">
                      	所屬品牌 : 
                      </label>
                      <h5 style="color:#999;"><?php echo $braData[0]["braName"]; ?></h5>
                    </div>
                    <div class="form-group">
                      <label class="control-label col-md-3 col-sm-3 col-xs-12" for="first-name">
                      	商品名稱 : 
                      </label>
                      <h5 style="color:#999;"><?php echo $proData[0]["proName"]; ?></h5>
                    </div>
                    <div class="form-group">
                      <label class="control-label col-md-3 col-sm-3 col-xs-12" for="first-name">
                      	商品型號 : 
                      </label>
                      <ul style="display: inline-block;padding-left:20px;">
                      	<?php foreach(explode("#",$proData[0]["proModelID"]) as $value){?>
                      		<li style="color:#999;"><?php echo $value; ?></li>
                      	<?php } ?>
                      </ul>
                    </div>
                    <div class="form-group">
                      <label class="control-label col-md-3 col-sm-3 col-xs-12" for="first-name">
                      	商品規格 : 
                      </label>
                      <ul style="display: inline-block;padding-left:20px;">
                      	<?php foreach(explode("#",$proData[0]["proSpec"]) as $value){?>
                      		<li style="color:#999;"><?php echo $value; ?></li>
                      	<?php } ?>
                      </ul>
                    </div>
                    <div class="form-group">
                      <label class="control-label col-md-3 col-sm-3 col-xs-12" for="first-name">
                      	商品說明 : 
                      </label>
                      <div class="col-md-6 col-sm-6 col-xs-12" style="display:inline-block;">
                      	<h5 style="color:#999;"><?php echo $proData[0]["proDetail"]; ?></h5>
                      </div>
                    </div>
                    <div class="form-group">
                      <label class="control-label col-md-3 col-sm-3 col-xs-12" for="first-name">
                      	商品圖片 : 
                      </label>
                      <div id="preview-area" class="col-md-6 col-sm-6 col-xs-12">
                        <?php 
                        if(!empty($proImageArray)){
                        	foreach($proImageArray as $value){ 
                        ?>
                        	<img style="border:2px solid #AAA;padding:5px;margin:20px;max-width:300px;" src="<?php echo $value; ?>">
                        <?php 
                        	} 
                        }
                        ?>
                      </div>
                    </div>
                    <div class="form-group not-print">
                      <label class="control-label col-md-3 col-sm-3 col-xs-12" for="first-name">
                      	採取動作 : 
                      </label>
                        <?php if ($proData[0]["bySup"]==1){ ?>
                      <a style="text-decoration:none;" href="?page=product&type=productManage&action=edit&prono=<?php echo $proNo; ?>">
                      	<button class="btn btn-success">編輯</button>
                      </a>
                        <?php } else { ?>
                        <a style="text-decoration:none;" href="?page=product&type=product&action=edit&prono=<?php echo $proNo; ?>">
                            <button class="btn btn-success">編輯</button>
                        </a>
                        <?php } ?>
<!--                       <button id="content-remove" class="btn btn-danger">刪除</button> -->
                    </div>
                    <div style="margin:30px;"></div>
                    <div class="form-group not-print">
                      <div class="col-md-6 col-sm-6 col-xs-12 col-md-offset-3">
<!--                        <a style="color:#FFF;" href="?page=product&type=product">-->
<!--                          <button class="btn btn-primary">回商品列表</button>-->
<!--                        </a>-->
                          <a style="color:#FFF;" onclick="window.history.back();">
                              <button class="btn btn-primary">回上頁</button>
                          </a>
<!--                        --><?php //if(isset($_SERVER['HTTP_REFERER'])){?>
<!--                        <a style="color:#FFF;" href="--><?php //echo $_SERVER['HTTP_REFERER']; ?><!--">-->
<!--                          <button class="btn btn-primary">回上頁</button>-->
<!--                        </a>-->
<!--                        --><?php //}?>
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
// 		if(confirm("確定要刪除此商品？")){
// 			$.ajax({
// 				type: "POST",
// 				url: "ajax/product/delete.php",
// 				data: "proNo="+,
// 				success: function(result){    
// 					if(result.indexOf("成功") != -1){
// 						alert(result);
// 						location.href = "?page=product&type=product";
// 					}else{
// 						alert(result);
// 					}
// 				}
// 			});
// 		}
// 	});
});
</script>