<?php 
require_once('model/require_general.php');

$ag = new Admin_Group();

if($_GET["action"] == "edit"){
	$agNo = $_GET["agno"];
	$agData = $ag->getOneAGByNo($agNo);
}

?>
<!-- page content -->
      <div class="right_col" role="main" style="min-height: 949px;">
        <div class="">

          <div class="page-title">
            <div class="title_left">
            <?php if($_GET["action"]=="edit"){ ?>
              <h3>編輯群組</h3>
            <?php }else{ ?>
              <h3>新增群組</h3>
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
                  	  群組編號: <?php echo $agNo; ?> &nbsp&nbsp&nbsp&nbsp
                  	<?php if(isset($_SERVER['HTTP_REFERER'])){ ?>
                  	<a style="color:#FFF;" href="<?php echo $_SERVER['HTTP_REFERER']; ?>">
	                  <button class="btn btn-success">回上頁</button>
	                </a>
	                <?php } ?>
                  <?php }else{ ?>
                  	<a style="color:#FFF;" href="?page=right&type=group">
	                  <button class="btn btn-success">回群組列表</button>
	                </a>
                  <?php }?>
                  </h2>
                  <div class="clearfix"></div>
                </div>
                <div class="x_content">
                  <br>
                  <form method="POST" class="form-horizontal form-label-left">
                  <?php if($_GET["action"]=="edit"){ ?>
                    <input type="hidden" name="agNo" value="<?php echo $agNo; ?>">
                  <?php } ?>
                    <div class="form-group">
                      <label class="control-label col-md-3 col-sm-3 col-xs-12" for="first-name">
                      	群組名稱 : 
                      </label>
                      <div class="col-md-6 col-sm-6 col-xs-12">
	                      <input value="<?php if($_GET["action"]=="edit") echo $agData[0]["agName"]; ?>" type="text" class="form-control" name="agName" />
	                      <ul class="parsley-errors-list"><li id="nameErr"></li></ul>
	                  </div>
                    </div>
                    <div class="form-group">
                      <label class="control-label col-md-3 col-sm-3 col-xs-12" for="first-name">
                      	群組權限 : 
                      </label>
                      <div class="col-md-6 col-sm-6 col-xs-12">
                      	<?php foreach($ag->rightArr as $key=>$value){ ?>
                      	<input 
                      	<?php 
                      	if($_GET["action"] == "edit" && $agData[0]["agRight"] != "" && !empty(json_decode($agData[0]["agRight"]))){
	                      	foreach(json_decode($agData[0]["agRight"]) as $keyIn=>$valueIn){
	                      		if($key == $valueIn){
	                      			echo "checked";
	                      		}
	                      	}
                      	}
                      	?> value="<?php echo $key; ?>" type="checkbox" name="agRight[]" /> <?php echo $value; ?><br>
                      	<?php } ?>
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
		var url = "ajax/adminGroup/<?php echo $_GET["action"]; ?>.php";
		var redirect = "?page=right&type=group<?php if($_GET["action"]=="edit") echo "&action=view&agno=".$agNo; if($_GET["action"]=="insert") echo "&pageIndex=last" ?>";
		
		$.ajax({
			url:url,
			type:"post",
			data:form,
			datatype:"json",
			success:function(result){
				var results = JSON.parse(result);
				if(results.errMsg != ""){
					addError($("#nameErr"),results.errMsg.agNameErr);
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