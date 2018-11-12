<?php 
require_once('model/require_general.php');

$sup = new Supplier_sales();
$supNo = $_GET["supno"];
$supData = $sup->getOneSupplier_salesByssNo($supNo);

?>
<!-- page content -->
    <div class="right_col" role="main" style="min-height: 949px;">
        <div class="">
            <div class="page-title">
                <div class="title_left">
                    <h3>銷售員資料</h3>
                </div>
            </div>
            <div class="clearfix"></div>
            <div class="row">
                <div class="col-md-12 col-sm-12 col-xs-12">
                    <div class="x_panel">
                        <div class="x_title">
                            <h2 style="text-align:center;float:none;">銷售員編號: <?php echo $supNo; ?></h2>
                            <div class="clearfix"></div>
                        </div>
                        <div class="x_content">
                            <br />
                            <div class="form-horizontal form-label-left">
                                <div class="form-group">
                                    <label class="control-label col-md-3 col-sm-3 col-xs-12" for="first-name">
                                    購物站編號 : 
                                    </label>
                                <h5 style="color:#999;"><?php echo $supData[0]["supNo"]; ?></h5>
                                </div>
                                <div class="form-group">
                                    <label class="control-label col-md-3 col-sm-3 col-xs-12" for="first-name">
                                    銷售員編號 : 
                                    </label>
                                    <h5 style="color:#999;"><?php echo $supData[0]["ssNo"]; ?></h5>
                                </div>
                                <div class="form-group">
                                    <label class="control-label col-md-3 col-sm-3 col-xs-12" for="first-name">
                                    銷售員姓名 : 
                                    </label>
                                  <h5 style="color:#999;"><?php echo $supData[0]["ssName"]; ?></h5>
                                </div>
                                <div class="form-group">
                                    <label class="control-label col-md-3 col-sm-3 col-xs-12" for="first-name">
                                    銷售員帳號 : 
                                    </label>
                                    <h5 style="color:#999;"><?php echo $supData[0]["ssLogId"]; ?></h5>
                                </div>
                                <div class="form-group">
                                    <label class="control-label col-md-3 col-sm-3 col-xs-12" for="first-name">
                                    銷售員密碼 : 
                                    </label>
                                    <h5 style="color:#999;"><?php echo $supData[0]["ssPwd"]; ?></h5>
                                </div>                    
                                <div class="form-group not-print">
                                    <label class="control-label col-md-3 col-sm-3 col-xs-12" for="first-name">
                                    採取動作 : 
                                    </label>
                                    <a style="text-decoration:none;" href="?page=supplier&action=edit&supno=<?php echo $supNo; ?>">
                                        <button class="btn btn-success">編輯</button>
                                    </a>
                                    <button id="content-remove" class="btn btn-danger">刪除</button>
                                </div>
                                <div style="margin:30px;"></div>
                                <div class="form-group not-print">
                                    <div class="col-md-6 col-sm-6 col-xs-12 col-md-offset-3">
                                        <a style="color:#FFF;" href="?page=supplier">
                                            <button class="btn btn-primary">回銷售人員列表</button>
                                        </a>
                                    <?php if (isset($_SERVER['HTTP_REFERER'])) {?>
                                        <a style="color:#FFF;" href="<?php echo $_SERVER['HTTP_REFERER']; ?>">
                                        <button class="btn btn-primary">回上頁</button>
                                        </a>
                                    <?php } ?>
                                    </div>
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
$(function() {
    $("#content-remove").click(function() {
        if (confirm("確定要刪除此業務人員？")) {
            $.ajax({
                type: "POST",
                url: "ajax/supplier/delete.php",
                data: "supNo=" + <?php echo $supNo ?>,
                success: function (result) {  
                    var results = JSON.parse(result);  
                    if (results.success == "0") {
                        alert("刪除成功");
                        location.href = "?page=supplier";
                    } else {
                        alert("刪除失敗");
                    }
                }
            });
        }
    });
});
</script>