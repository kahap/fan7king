<?php 
require_once('model/require_general.php');

if ($_GET["action"] == "edit") {
    $sup = new Supplier_sales();
    $supNo = $_GET["supno"];
    $supData = $sup->getOneSupplier_salesByssNo($supNo);
}

?>
<!-- page content -->
    <div class="right_col" role="main" style="min-height: 949px;">
        <div class="">
            <div class="page-title">
                <div class="title_left">
                <?php if($_GET["action"]=="edit"){ ?>
                    <h3>編輯銷售員資料</h3>
                <?php }else{ ?>
                    <h3>新增人銷售員</h3>
                <?php } ?>
                </div>
            </div>
            <div class="clearfix"></div>
            <div class="row">
                <div class="col-md-12 col-sm-12 col-xs-12">
                    <div class="x_panel">
                        <div class="x_title">
                            <h2 style="text-align:center;float:none;">
                            <?php if ($_GET["action"]=="edit") { ?>
                                銷售員編號: <?php echo $supNo; ?> &nbsp&nbsp&nbsp&nbsp
                                <?php if (isset($_SERVER['HTTP_REFERER'])) { ?>
                                    <a style="color:#FFF;" href="<?php echo $_SERVER['HTTP_REFERER']; ?>">
                                        <button class="btn btn-success">回上頁</button>
                                    </a>
                                <?php }?>
                            <?php } else { ?>
                                <a style="color:#FFF;" href="?page=supplier">
                                    <button class="btn btn-success">回銷售人員列表</button>
                                </a>
                            <?php }?>
                            </h2>
                            <div class="clearfix"></div>
                        </div>
                        <div class="x_content">
                            <br/>
                            <form class="form-horizontal form-label-left">
                                <?php if ($_GET["action"]=="insert") { ?>
                                <input type="hidden" name="supNo" value="<?php echo $_SESSION['supplieruserdata']['supNo']; ?>" />
                                <?php } ?>  
                                <div class="form-group">
                                    <label class="control-label col-md-3 col-sm-3 col-xs-12" for="first-name">
                                        銷售員姓名 : 
                                    </label>
                                    <div class="col-md-6 col-sm-6 col-xs-12">
                                        <input value="<?php if($_GET["action"]=="edit") echo $supData[0]["ssName"]; ?>" type="text" required="required" class="form-control" name="ssName" />
                                        <ul class="parsley-errors-list">
                                            <li id="ssNameErr"></li>
                                        </ul>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="control-label col-md-3 col-sm-3 col-xs-12" for="first-name">
                                        銷售員帳號 : 
                                    </label>
                                    <div class="col-md-6 col-sm-6 col-xs-12">
                                        <input value="<?php if($_GET["action"]=="edit") echo $supData[0]["ssLogId"]; ?>" type="text" required="required" class="form-control" name="ssLogId" />
                                        <ul class="parsley-errors-list">
                                            <li id="ssLogIdErr"></li>
                                        </ul>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="control-label col-md-3 col-sm-3 col-xs-12" for="first-name">
                                        銷售員密碼 : 
                                    </label>
                                    <div class="col-md-6 col-sm-6 col-xs-12">
                                        <input value="<?php if($_GET["action"]=="edit") echo $supData[0]["ssPwd"]; ?>" type="text" required="required" class="form-control" name="ssPwd" />
                                        <ul class="parsley-errors-list">
                                            <li id="ssPwdErr"></li>
                                        </ul>
                                    </div>
                                </div>                    
                                <div style="margin:30px;"></div>
                                <div class="form-group">
                                    <div class="col-md-6 col-sm-6 col-xs-12 col-md-offset-3">
                                        <button type="submit" class="btn btn-primary">
                                            <?php if($_GET["action"]=="edit") echo "確認修改"; else echo "確認新增" ?>
                                        </button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
        <!-- /page content -->
<script>
$(function() {
    $("button[type='submit']").click(function(e) {
        $(".parsley-errors-list li").text("");
        e.preventDefault();
        
        var form = new FormData($("form")[0]);
        var url = "ajax/supplier/<?php echo $_GET["action"]; ?>.php";
        var redirect = "?page=supplier<?php if($_GET["action"]=="edit") echo "&action=view&supno=".$supNo; if($_GET["action"]=="insert") echo "&pageIndex=last" ?>"
        $.ajax({
            url:url,
            type:"post",
            data:form,
            datatype:"json",
            contentType:false,
            processData: false,
            success:function(result) {
                var results = JSON.parse(result);
                if (results.errMsg != "") {
                    addError($("#ssNameErr"),results.errMsg.ssNameErr);
                    addError($("#ssLogIdErr"),results.errMsg.ssLogIdErr);
                    addError($("#ssPwdErr"),results.errMsg.ssPwdErr);
                    if (results.errMsg.errMsg != "") {
                        addError($("#ssLogIdErr"),results.errMsg.errMsg);
                    }
                } else if (results.errMsg == "") {
                    alert(results.success);
                    location.href= redirect;
               }

            }
        });
    });

    $("#stamp-upload").change(function() {
        readURL(this);
    });
});
function readURL(input) {

    if (input.files && input.files[0]) {
        var reader = new FileReader();

        reader.onload = function (e) {
            $('#show-stamp').attr('src', e.target.result);
        }

        reader.readAsDataURL(input.files[0]);
    }
}
function addError(selector, errMsg) {
    selector.text(errMsg);
}

</script>