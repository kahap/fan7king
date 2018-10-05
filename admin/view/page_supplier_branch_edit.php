<?php 
require_once('model/require_general.php');

$sup = new SupplierBranch();	
if($_GET["action"] == "branchEdit"){
	$no = $_GET["ssNo"];
	$supData = $sup->getOneBranchByNo($no);
}
$salesData = $sup->getAllSales();

?>
<!-- page content -->
      <div class="right_col" role="main" style="min-height: 949px;">
        <div class="">

          <div class="page-title">
            <div class="title_left">
            <?php if($_GET["action"]=="branchEdit"){ ?>
              <h3>編輯分店資料</h3>
            <?php }else{ ?>
              <h3>新增分店</h3>
            <?php } ?>
            </div>
          </div>
          <div class="clearfix"></div>
          <div class="row">
            <div class="col-md-12 col-sm-12 col-xs-12">
              <div class="x_panel">
                <div class="x_title">
                  <h2 style="text-align:center;float:none;">
                  	<p><?php if($_GET['action']== "branchEdit"){?>
                  	  分店編號: <?php echo $no; ?></p><?php }?>
                  	<a style="color:#FFF;" href="?page=supplier&action=branch&supno=<?php if($_GET['action']== "branchEdit"){echo $supData[0]["supNo"];}else{echo $_GET['supno'];}?>">
	                  <button class="btn btn-success">回分店列表</button>
	                </a>
                  </h2>
                  <div class="clearfix"></div>
                </div>
                <div class="x_content">
                  <br>
                  <form class="form-horizontal form-label-left">
                  <?php if($_GET["action"]=="branchEdit"){ ?>
                    <input type="hidden" name="ssNo" value="<?php echo $no; ?>">
                  <?php }else{ ?>
                  	<input type="hidden" name="supno" value="<?php echo $_GET['supno']; ?>">	
                  	<?php }?>
                    <div class="form-group">                 
                      <label class="control-label col-md-3 col-sm-3 col-xs-12" for="first-name">
                      	分店名稱 : 
                      </label>
                      <div class="col-md-6 col-sm-6 col-xs-12">
                      	<input value="<?php if($_GET["action"]=="branchEdit") echo $supData[0]["ssName"]; ?>" required="required" type="text" class="form-control" name="ssName" />
                        <ul class="parsley-errors-list"><li id="nameErr"></li></ul>
                      </div>
                    </div>
                    <div class="form-group">
                      <label class="control-label col-md-3 col-sm-3 col-xs-12" for="first-name">
                      	分店代碼 : 
                      </label>
                      <div class="col-md-6 col-sm-6 col-xs-12">
                      	<input value="<?php if($_GET["action"]=="branchEdit") echo $supData[0]["ssLogId"]; ?>" type="text" class="form-control" name="ssLogId" />
                      	<ul class="parsley-errors-list"><li id="phoneErr"></li></ul>
                      </div>
                    </div>
                    <div class="form-group">
                      <label class="control-label col-md-3 col-sm-3 col-xs-12" for="first-name">
                      	分店認證碼 : 
                      </label>
                      <div class="col-md-6 col-sm-6 col-xs-12">
                      	<input value="<?php if($_GET["action"]=="branchEdit") echo $supData[0]["ssPwd"]; ?>" type="text" class="form-control" name="ssPwd" />
                      	<ul class="parsley-errors-list"><li id="cellErr"></li></ul>
                      </div>
                    </div>
                    <div class="form-group">
                      <label class="control-label col-md-3 col-sm-3 col-xs-12" for="first-name">
                      	業務名稱 : 
                      </label>
                      <div class="col-md-6 col-sm-6 col-xs-12">
                     <select class="form-control" name="aauNo">
                     <option disabled selected value> -- select an option -- </option>
					 <?php
					 foreach ($salesData as $value) {
					 		$selected = "";
					 	if($value['aauNo']==$supData[0]["aauNo"]){
					 		$selected = "selected ";
					 	}
						 echo "<option value='".$value['aauNo']."' ".$selected.">".$value["aauName"]."</option>";
					 }
					 ?>
					 </select>
 	<ul class="parsley-errors-list"><li id="generalErr"></li></ul>
                      </div>
                    </div>
                    <div style="margin:30px;"></div>
                    <div class="form-group">
                      <div class="col-md-6 col-sm-6 col-xs-12 col-md-offset-3">
                         <button type="submit" class="btn btn-primary">
                         	<?php if($_GET["action"]=="branchEdit") echo "確認修改"; else echo "確認新增" ?>
                         </button>
                      </div>
                    </div>
                  </form>
                </div>
              </div>
            </div>
          </div>
        </div>
        <!-- /page content -->
<script>
$(function(){
	$("button[type='submit']").click(function(e){
		$("#stampImgErr").text("");
		$(".parsley-errors-list li").text("");
		e.preventDefault();
		
		var form = new FormData($("form")[0]);
		var url = "ajax/supplier/<?php echo $_GET["action"]; ?>.php";
		<?php  if($_GET["action"]=="branchInsert"){?>
		var redirect = "?page=supplier&action=branch&supno=<?php echo $_GET['supno'];?>"
		<?php }else{?>
		var redirect = "?page=supplier&action=branch&supno=<?php echo $supData[0]['supNo'];?>"	
		<?php }?>
		$.ajax({
			url:url,
			type:"post",
			data:form,
			datatype:"json",
			contentType:false,
			processData: false,
			success:function(result){
				var results = JSON.parse(result);
				if(results.errMsg != ""){
					addError($("#generalErr"),results.errMsg);
					}else if(results.errMsg == ""){
					alert(results.success);
					location.href= redirect;
				}
			}
		});
	});

	$("#stamp-upload").change(function(){
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
function addError(selector, errMsg){
	selector.text(errMsg);
}


</script>