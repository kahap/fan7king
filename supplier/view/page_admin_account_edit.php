<?php 
require_once('model/require_general.php');

$ag = new Admin_Group();

$allAGData = $ag->getAllAG();

$sm = new System_Manager();

if($_GET["action"] == "edit"){
	$smNo = $_GET["smno"];
	$smData = $sm->getOneSMByNo($smNo);
}

?>
<!-- page content -->
      <div class="right_col" role="main" style="min-height: 949px;">
        <div class="">

          <div class="page-title">
            <div class="title_left">
            <?php if($_GET["action"]=="edit"){ ?>
              <h3>編輯管理員</h3>
            <?php }else{ ?>
              <h3>新增管理員</h3>
            <?php } ?>
            </div>
          </div>
          <div class="clearfix"></div>
          <div class="row">
            <div class="col-md-12 col-sm-12 col-xs-12">
              <div class="x_panel">
                <div class="x_title">
                  <h2 style="text-align:center;float:none;">
                  <?php if($_GET["action"]=="edit"){ ?>
                  	  管理員編號: <?php echo $smNo; ?> &nbsp&nbsp&nbsp&nbsp
                  	<?php if(isset($_SERVER['HTTP_REFERER'])){ ?>
                  	<a style="color:#FFF;" href="<?php echo $_SERVER['HTTP_REFERER']; ?>">
	                  <button class="btn btn-success">回上頁</button>
	                </a>
	                <?php } ?>
                  <?php }else{ ?>
                  	<a style="color:#FFF;" href="?page=right&type=account">
	                  <button class="btn btn-success">回管理員列表</button>
	                </a>
                  <?php }?>
                  </h2>
                  <div class="clearfix"></div>
                </div>
                <div class="x_content">
                  <br>
                  <form method="POST" class="form-horizontal form-label-left">
                  <?php if($_GET["action"]=="edit"){ ?>
                    <input type="hidden" name="smNo" value="<?php echo $smNo; ?>">
                  <?php } ?>
                    <div class="form-group">
                      <label class="control-label col-md-3 col-sm-3 col-xs-12" for="first-name">
                      	姓名 : 
                      </label>
                      <div class="col-md-6 col-sm-6 col-xs-12">
	                      <input value="<?php if($_GET["action"]=="edit") echo $smData[0]["smName"]; ?>" type="text" class="form-control" name="smName" />
	                      <ul class="parsley-errors-list"><li id="nameErr"></li></ul>
	                  </div>
                    </div>
                    <div class="form-group">
                      <label class="control-label col-md-3 col-sm-3 col-xs-12" for="first-name">
                      	帳號 : 
                      </label>
                      <div class="col-md-6 col-sm-6 col-xs-12">
	                      <input value="<?php if($_GET["action"]=="edit") echo $smData[0]["smAccount"]; ?>" type="text" class="form-control" name="smAccount" />
	                  	  <ul class="parsley-errors-list"><li id="accountErr"></li></ul>
	                  </div>
                    </div>
                    <div class="form-group">
                      <label class="control-label col-md-3 col-sm-3 col-xs-12" for="first-name">
                      	密碼 : 
                      </label>
                      <div class="col-md-6 col-sm-6 col-xs-12">
	                      <input value="<?php if($_GET["action"]=="edit") echo $smData[0]["smPwd"]; ?>" type="text" class="form-control" name="smPwd" />
	                  	  <ul class="parsley-errors-list"><li id="pwdErr"></li></ul>
	                  </div>
                    </div>
                    <div class="form-group">
                      <label class="control-label col-md-3 col-sm-3 col-xs-12" for="first-name">
                      	權限 : 
                      </label>
                      <div class="col-md-6 col-sm-6 col-xs-12">
                        <select class="form-control" name="agNo">
                        <?php foreach($allAGData as $key=>$value){ ?>
                        	<option <?php if($_GET["action"] == "edit" && $value["agNo"] == $smData[0]["agNo"]) echo "selected";?> value="<?php echo $value["agNo"]; ?>"><?php echo $value["agName"]; ?></option>
                        <?php } ?>
                        </select>
	                  </div>
                    </div>
                    <div class="form-group">
                      <label class="control-label col-md-3 col-sm-3 col-xs-12" for="first-name">
                      	電話 : 
                      </label>
                      <div class="col-md-6 col-sm-6 col-xs-12">
	                      <input value="<?php if($_GET["action"]=="edit") echo $smData[0]["smPhone"]; ?>" type="text" class="form-control" name="smPhone" />
	                  </div>
                    </div>
                    <div class="form-group">
                      <label class="control-label col-md-3 col-sm-3 col-xs-12" for="first-name">
                      	Email : 
                      </label>
                      <div class="col-md-6 col-sm-6 col-xs-12">
	                      <input value="<?php if($_GET["action"]=="edit") echo $smData[0]["smEmail"]; ?>" type="text" class="form-control" name="smEmail" />
	                  </div>
                    </div>
                    <div class="form-group">
                      <label class="control-label col-md-3 col-sm-3 col-xs-12" for="first-name">
                      	備註 : 
                      </label>
                      <div class="col-md-6 col-sm-6 col-xs-12">
	                      <input value="<?php if($_GET["action"]=="edit") echo $smData[0]["smComment"]; ?>" type="text" class="form-control" name="smComment" />
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
        <!-- /page content -->
        
<script>
$(function(){
	
	$("button[type='submit']").click(function(e){
		$(".parsley-errors-list li").text("");
		e.preventDefault();

		var form = $("form").serialize();
		var url = "ajax/systemAdmin/<?php echo $_GET["action"]; ?>.php";
		var redirect = "?page=right&type=account<?php if($_GET["action"]=="edit") echo "&action=view&smno=".$smNo; if($_GET["action"]=="insert") echo "&pageIndex=last" ?>";
		
		$.ajax({
			url:url,
			type:"post",
			data:form,
			datatype:"json",
			success:function(result){
				var results = JSON.parse(result);
				if(results.errMsg != ""){
					addError($("#nameErr"),results.errMsg.smNameErr);
					addError($("#accountErr"),results.errMsg.smAccountErr);
					addError($("#pwdErr"),results.errMsg.smPwdErr);
				}else if(results.errMsg == ""){
					alert(results.success);
					location.href= redirect;
				}
			}
		});
	});

});
function addError(selector, errMsg){
	selector.text(errMsg);
}


</script>