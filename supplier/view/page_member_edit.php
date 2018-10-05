<?php 
require_once('model/require_general.php');

$member = new Member();
$memNo = $_GET["memno"];
$memberData = $member->getOneMemberByNo($memNo);
$origMemberData = $member->getOneMemberByNo($memNo);
$member->changeToReadable($memberData[0]);


$lg = new Loyal_Guest();
$allLgData = $lg->getAllLoyalGuest();
$ifLoyal = "否";
foreach($allLgData as $keyIn=>$valueIn){
	if($valueIn["lgIdNum"] == $origMemberData[0]["memIdNum"]){
		$ifLoyal = "是";
	}
}

?>
<!-- page content -->
      <div class="right_col" role="main" style="min-height: 949px;">
        <div class="">

          <div class="page-title">
            <div class="title_left">
              <h3>編輯會員資料</h3>
            </div>
          </div>
          <div class="clearfix"></div>
          <div class="row">
            <div class="col-md-12 col-sm-12 col-xs-12">
              <div class="x_panel">
                <div class="x_title">
                  <h2 style="text-align:center;float:none;">
                  	會員編號: <?php echo $memNo; ?>，<?php echo $origMemberData[0]["memName"]." "; echo $origMemberData[0]["memGender"] == 0 ? "小姐" : "先生"; ?> &nbsp&nbsp&nbsp&nbsp
                  	<?php if(isset($_SERVER['HTTP_REFERER'])){ ?>
                  	<a style="color:#FFF;" href="<?php echo $_SERVER['HTTP_REFERER']; ?>">
	                  <button class="btn btn-success">回上頁</button>
	                </a>
	                <?php }?>
                  </h2>
                  <div class="clearfix"></div>
                </div>
                <div class="x_content">
                  <br>
                  <form class="form-horizontal form-label-left">
                    <input type="hidden" name="memNo" value="<?php echo $memNo; ?>">
                    <div class="form-group">
                      <label class="control-label col-md-3 col-sm-3 col-xs-12" for="first-name">
                      	允許登入 : 
                      </label>
                      <div class="col-md-6 col-sm-6 col-xs-12">
	                    <select name="memAllowLogin" class="form-control">
	                      <option <?php if($origMemberData[0]["memAllowLogin"]==0) echo "selected"; ?> value="0">停權</option>
	                      <option <?php if($origMemberData[0]["memAllowLogin"]==1) echo "selected"; ?> value="1">允許</option>
                      	</select>
                      </div>
                    </div>
                    <div class="form-group">
                      <label class="control-label col-md-3 col-sm-3 col-xs-12" for="first-name">
                      	註冊方式 : 
                      </label>
                      <div class="col-md-6 col-sm-6 col-xs-12">
                      	<select name="memRegistMethod" class="form-control">
	                      <option <?php if($origMemberData[0]["memRegistMethod"]==0) echo "selected"; ?> value="0">FB連結</option>
	                      <option <?php if($origMemberData[0]["memRegistMethod"]==1) echo "selected"; ?> value="1">一般</option>
	                    </select>
                      </div>
                    </div>
                    <div class="form-group">
                      <label class="control-label col-md-3 col-sm-3 col-xs-12" for="first-name">
                      	身分別 : 
                      </label>
                      <div class="col-md-6 col-sm-6 col-xs-12">
                      	<select name="memClass" class="form-control">
                          <option <?php if($origMemberData[0]["memClass"]==0) echo "selected"; ?> value="0">學生</option>
                          <option <?php if($origMemberData[0]["memClass"]==1) echo "selected"; ?> value="1">上班族</option>
                          <option <?php if($origMemberData[0]["memClass"]==2) echo "selected"; ?> value="2">家管</option>
                          <option <?php if($origMemberData[0]["memClass"]==3) echo "selected"; ?> value="3">其他</option>
						  <option <?php if($origMemberData[0]["memClass"]==4) echo "selected"; ?> value="4">非學生</option>
                        </select>
                      </div>
                    </div>
                    <div class="form-group">
                      <label class="control-label col-md-3 col-sm-3 col-xs-12" for="first-name">
                      	身分備註 : 
                      </label>
                      <div class="col-md-6 col-sm-6 col-xs-12">
                      	<input value="<?php echo $memberData[0]["memOther"]; ?>" type="text" class="form-control" name="memOther" />
                      </div>
                    </div>
                    <div class="form-group">
                      <label class="control-label col-md-3 col-sm-3 col-xs-12" for="first-name">
                      	學校系級 : 
                      </label>
                      <div class="col-md-6 col-sm-6 col-xs-12">
                      	<input value="<?php 
											$class = json_decode($memberData[0]["memSchool"]);
											if(is_array($class)){
												echo $class[0].$class[1]." 年級：".$class[2];
											}else{
												echo $memberData[0]["memSchool"];
											}
										
										?>" type="text" class="form-control" name="memSchool" />
                      </div>
                    </div>
                    <div class="form-group">
                      <label class="control-label col-md-3 col-sm-3 col-xs-12" for="first-name">
                      	常用聯絡Email : 
                      </label>
                      <div class="col-md-6 col-sm-6 col-xs-12">
                      	<input value="<?php echo $memberData[0]["memSubEmail"]; ?>" type="text" class="form-control" name="memSubEmail" />
                      </div>
                    </div>
                    <div class="form-group">
                      <label class="control-label col-md-3 col-sm-3 col-xs-12" for="first-name">
                      	帳號(Email) : 
                      </label>
                      <div class="col-md-6 col-sm-6 col-xs-12">
                      	<input value="<?php echo $memberData[0]["memAccount"]; ?>" type="text" class="form-control" name="memAccount" />
                      </div>
                    </div><div class="form-group">
                      <label class="control-label col-md-3 col-sm-3 col-xs-12" for="first-name">
                      	Email驗證狀態 : 
                      </label>
                      <div class="col-md-6 col-sm-6 col-xs-12">
                      	<select name="memEmailAuthen" class="form-control">
	                      <option <?php if($origMemberData[0]["memEmailAuthen"]==0) echo "selected"; ?> value="0">尚未驗證</option>
	                      <option <?php if($origMemberData[0]["memEmailAuthen"]==1) echo "selected"; ?> value="1">已驗證</option>
	                    </select>
                      </div>
                    </div>
                    <div class="form-group">
                      <label class="control-label col-md-3 col-sm-3 col-xs-12" for="first-name">
                      	密碼 : 
                      </label>
                      <div class="col-md-6 col-sm-6 col-xs-12">
                      	<input value="<?php echo $memberData[0]["memPwd"]; ?>" type="text" class="form-control" name="memPwd" />
                      </div>
                    </div>
                    <div class="form-group">
                      <label class="control-label col-md-3 col-sm-3 col-xs-12" for="first-name">
                      	姓名 : 
                      </label>
                      <div class="col-md-6 col-sm-6 col-xs-12">
                      	<input value="<?php echo $memberData[0]["memName"]; ?>" type="text" class="form-control" name="memName" />
                      </div>
                    </div>
                    <div class="form-group">
                      <label class="control-label col-md-3 col-sm-3 col-xs-12" for="first-name">
                      	性別 : 
                      </label>
                      <div class="col-md-6 col-sm-6 col-xs-12">
	                      <select name="memGender" class="form-control">
	                        <option <?php if($origMemberData[0]["memGender"]==0) echo "selected"; ?> value="0">女</option>
	                        <option <?php if($origMemberData[0]["memGender"]==1) echo "selected"; ?> value="1">男</option>
	                      </select>
	                  </div>
                    </div>
                    <div class="form-group">
                      <label class="control-label col-md-3 col-sm-3 col-xs-12" for="first-name">
                      	生日 : 
                      </label>
                      <div class="col-md-6 col-sm-6 col-xs-12">
                      	<input value="<?php echo $memberData[0]["memBday"]; ?>" type="text" class="form-control" name="memBday" />
                      </div>
                    </div>
                    <div class="form-group">
                      <label class="control-label col-md-3 col-sm-3 col-xs-12" for="first-name">
                      	身分證號 : 
                      </label>
                      <div class="col-md-6 col-sm-6 col-xs-12">
                      	<input value="<?php echo $memberData[0]["memIdNum"]; ?>" type="text" class="form-control" name="memIdNum" />
                      </div>
                    </div>
                    <div class="form-group">
                      <label class="control-label col-md-3 col-sm-3 col-xs-12" for="first-name">
                      	現住地址 : 
                      </label>
                      <div class="col-md-6 col-sm-6 col-xs-12">
                      	<input value="<?php echo $memberData[0]["memAddr"]; ?>" type="text" class="form-control" name="memAddr" />
                      </div>
                    </div>
                    <div class="form-group">
                      <label class="control-label col-md-3 col-sm-3 col-xs-12" for="first-name">
                      	現住電話 : 
                      </label>
                      <div class="col-md-6 col-sm-6 col-xs-12">
                      	<input value="<?php echo $memberData[0]["memPhone"]; ?>" type="text" class="form-control" name="memPhone" />
                      </div>
                    </div>
                    <div class="form-group">
                      <label class="control-label col-md-3 col-sm-3 col-xs-12" for="first-name">
                      	手機 : 
                      </label>
                      <div class="col-md-6 col-sm-6 col-xs-12">
                      	<input value="<?php echo $memberData[0]["memCell"]; ?>" type="text" class="form-control" name="memCell" />
                      </div>
                    </div>
                    <div class="form-group">
                      <label class="control-label col-md-3 col-sm-3 col-xs-12" for="first-name">
                      	LineID : 
                      </label>
                      <div class="col-md-6 col-sm-6 col-xs-12">
                      	<input value="<?php echo $memberData[0]["memLineId"]; ?>" type="text" class="form-control" name="memLineId" />
                      </div>
                    </div>
                    <div class="form-group">
                      <label class="control-label col-md-3 col-sm-3 col-xs-12" for="first-name">
                      	FB token code : 
                      </label>
                      <div class="col-md-6 col-sm-6 col-xs-12">
                      	<input value="<?php echo $memberData[0]["memFBtoken"]; ?>" type="text" class="form-control" name="memFBtoken" />
                      </div>
                    </div>
                    <div class="form-group">
                      <label class="control-label col-md-3 col-sm-3 col-xs-12" for="first-name">
                      	推薦人代碼 : 
                      </label>
                      <div class="col-md-6 col-sm-6 col-xs-12">
                      	<input value="<?php echo $memberData[0]["memRecommCode"]; ?>" type="text" class="form-control" name="memRecommCode" />
                      </div>
                    </div>
                    <div class="form-group">
                      <label class="control-label col-md-3 col-sm-3 col-xs-12" for="first-name">
                      	是否為老客戶 : 
                      </label>
                      <div class="col-md-6 col-sm-6 col-xs-12">
                      	<select name="ifLoyal" class="form-control">
	                      <option <?php if($ifLoyal=="否") echo "selected"; ?> value="0">否</option>
	                      <option <?php if($ifLoyal=="是") echo "selected"; ?> value="1">是</option>
	                    </select>
                      </div>
                    </div>
                    <div style="margin:30px;"></div>
                    <div class="form-group">
                      <div class="col-md-6 col-sm-6 col-xs-12 col-md-offset-3">
                         <button type="submit" class="btn btn-primary">確認修改</button>
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
		e.preventDefault();
		var data = $("form").serialize();
		$.post("ajax/member/update.php", data, function(result){
			alert(result);
			//location.href = "?page=member&type=member&action=view&memno=<?php echo $memNo; ?>";
			
		});
	});
});
</script>