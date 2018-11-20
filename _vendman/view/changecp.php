<?php 
require_once('model/require_general.php');

date_default_timezone_set('Asia/Taipei');
$date = date('Y-m-d H:i:s', time());

?>
<!-- page content -->
      <div class="right_col" role="main" style="min-height: 949px;">
        <div class="">

          <div class="page-title">
            <div class="title_left">           
              <h3>變更驗證碼</h3>          
            </div>
          </div>
          <div class="clearfix"></div>
          <div class="row">
            <div class="col-md-12 col-sm-12 col-xs-12">                            
                <div class="x_content">
                  <br>
                  <form action="ajax/service/setup.php" method="POST" class="form-horizontal form-label-left">                
                  
                    <div class="form-group">
                      <label class="control-label col-md-3 col-sm-3 col-xs-12" for="first-name">
                      	設定驗證碼 : 
                      </label>
                      <div class="col-md-6 col-sm-6 col-xs-12">
                      	<input id="supNo" value="<?php echo $_SESSION['supplieruserdata']['supNo'] ;?>" type="hidden" class="form-control" name="supNo" />
                      	<input id="supPwd" value="" type="text" class="form-control" name="supPwd" />
                      	<ul class="parsley-errors-list"><li id="supPwdErr"></li></ul>
                      </div>
                    </div>                
                    <div style="margin:30px;"></div>
                    <div class="form-group">
                      <div class="col-md-6 col-sm-6 col-xs-12 col-md-offset-3">
                         <button type="submit" class="btn btn-primary">
                         	確認修改
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
		$("#supPwdErr").text("");
		$(".parsley-errors-list li").text("");
		e.preventDefault();
		
		var form = new FormData($("form")[0]);
		var url = "ajax/service/changecp.php";
		var redirect = "?page=product&type=productManage<?php if($_GET["action"]=="edit") echo "&action=view&prono=".$proNo; if($_GET["action"]=="insert") echo "&pageIndex=last" ?>";
			
		$.ajax({
			url:url,
			type:"post",
			data:form,
			datatype:"json",
			contentType:false,
			processData: false,
			success:function(result){				
				if(result != ""){
					addError($("#supPwdErr"),result);				
				}
                else 
                {
					alert("已變更");				
				}
			}
		});
	});
 });

function addError(selector, errMsg){
	selector.text(errMsg);
}

</script>