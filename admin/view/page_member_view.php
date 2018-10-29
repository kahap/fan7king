<?php 
require_once('model/require_general.php');

$member = new Member();
$memNo = $_GET["memno"];
$memOrigData = $member->getOneMemberByNo($memNo);
$memberData = $member->getOneMemberByNo($memNo);
$member->changeToReadable($memberData[0]);

$or = new Orders();
$orMemData = $or->getOrByMemberAndMethod($memNo,1);

$pm = new Product_Manage();
$pro = new Product();
$rba = new Recomm_Bonus_Apply();
$rbaMemData = $rba->getRBAByMemNo($memNo);

$lg = new Loyal_Guest();
$allLgData = $lg->getAllLoyalGuest()? $lg->getAllLoyalGuest() : [];
$ifLoyal = "否";
foreach($allLgData as $keyIn=>$valueIn){
	if($valueIn["lgIdNum"] == $memOrigData[0]["memIdNum"]){
		$ifLoyal = "是";
	}
}

$rbs = new Recomm_Bonus_Success();
$rbsData = $rbs->getRBSByMem($memNo);

$rs = new Recomm_Setting();
$rsData = $rs->getSetting();

$memberClass_array = array('0'=>'學生','1'=>'上班族','2'=>'家管','3'=>'其他','無'=>'無','4'=>'非學生');
?>
<!-- page content -->
      <div class="right_col" role="main" style="min-height: 949px;">
        <div class="">

          <div class="page-title">
            <div class="title_left">
              <h3>會員詳細資料</h3>
            </div>
          </div>
          <div class="clearfix"></div>
          <div class="row">
            <div class="col-md-12 col-sm-12 col-xs-12">
              <div class="x_panel">
                <div class="x_title">
                  <h2 style="text-align:center;float:none;">會員編號: <?php echo $memNo; ?>，<?php echo $memOrigData[0]["memName"]." "; 
						if($memOrigData[0]["memGender"] == '0'){
							echo "小姐";
						}elseif($memOrigData[0]["memGender"] == '1'){
							echo "先生";
						}
						
						
				  ?></h2>
                  <div class="clearfix"></div>
                </div>
                <div class="x_content">
                  <br>
                  <div class="form-horizontal form-label-left">
                    <div class="form-group">
                      <label class="control-label col-md-3 col-sm-3 col-xs-12" for="first-name">
                      	允許登入 : 
                      </label>
                      <h5 style="color:#999;"><?php echo $memberData[0]["memAllowLogin"]; ?></h5>
                    </div>
                    <div class="form-group">
                      <label class="control-label col-md-3 col-sm-3 col-xs-12" for="first-name">
                      	註冊方式 : 
                      </label>
                      <h5 style="color:#999;"><?php echo "<a target='blank' style='text-decoration:underline;color:blue;' href='https://www.facebook.com/".$memberData[0]["memFBtoken"]."'>".$memberData[0]["memRegistMethod"]."</a>"; ?></h5>
                    </div>
                    <div class="form-group">
                      <label class="control-label col-md-3 col-sm-3 col-xs-12" for="first-name">
                      	身分別 : 
                      </label>
                      <h5 style="color:#999;"><?php echo is_numeric($memberData[0]["memClass"]) ? $memberClass_array[$memberData[0]["memClass"]]:$memberData[0]["memClass"]; ?></h5>
                    </div>
                    <div class="form-group">
                      <label class="control-label col-md-3 col-sm-3 col-xs-12" for="first-name">
                      	身分備註 : 
                      </label>
                      <h5 style="color:#999;"><?php echo $memberData[0]["memOther"]; ?></h5>
                    </div>
                    <?php if($memOrigData[0]["memClass"] == 0){?>
                    <div class="form-group">
                      <label class="control-label col-md-3 col-sm-3 col-xs-12" for="first-name">
                      	學校系級  : 
                      </label>
                      <h5 style="color:#999;">
					  <?php echo $memberData[0]["memSchool"]; ?></h5>
                    </div>
                    <?php } ?>
                    <div class="form-group">
                      <label class="control-label col-md-3 col-sm-3 col-xs-12" for="first-name">
                      	常用聯絡Email: 
                      </label>
                      <h5 style="color:#999;"><?php echo $memberData[0]["memSubEmail"]; ?></h5>
                    </div>
                    <div class="form-group">
                      <label class="control-label col-md-3 col-sm-3 col-xs-12" for="first-name">
                      	帳號(Email) : 
                      </label>
                      <div class="col-md-6 col-sm-6 col-xs-12">
                        <h5 style="color:#999;"><?php echo $memberData[0]["memAccount"]; ?></h5>
                        <span style="color:#F00;">※使用者未完成認證前可以修改，認證後則不能異動。如要改需管理員權限。</span>
                      </div>
                    </div>
                    <div class="form-group">
                      <label class="control-label col-md-3 col-sm-3 col-xs-12" for="first-name">
                      	Email驗證狀態 : 
                      </label>
                      <h5 style="color:#999;"><?php echo $memberData[0]["memEmailAuthen"]; ?></h5>
                    </div>
                    <div class="form-group">
                      <label class="control-label col-md-3 col-sm-3 col-xs-12" for="first-name">
                      	密碼 : 
                      </label>
                      <h5 style="color:#999;"><?php echo $memberData[0]["memPwd"]; ?></h5>
                    </div>
                    <div class="form-group">
                      <label class="control-label col-md-3 col-sm-3 col-xs-12" for="first-name">
                      	姓名 : 
                      </label>
                      <h5 style="color:#999;"><?php echo $memberData[0]["memName"]; ?></h5>
                    </div>
                    <div class="form-group">
                      <label class="control-label col-md-3 col-sm-3 col-xs-12" for="first-name">
                      	性別 : 
                      </label>
                      <h5 style="color:#999;"><?php echo $memberData[0]["memGender"]; ?></h5>
                    </div>
                    <div class="form-group">
                      <label class="control-label col-md-3 col-sm-3 col-xs-12" for="first-name">
                      	生日 : 
                      </label>
                      <h5 style="color:#999;"><?php echo $memberData[0]["memBday"]; ?></h5>
                    </div>
                    <div class="form-group">
                      <label class="control-label col-md-3 col-sm-3 col-xs-12" for="first-name">
                      	身分證號 : 
                      </label>
                      <h5 style="color:#999;"><?php echo $memberData[0]["memIdNum"]; ?></h5>
                    </div>
                    <div class="form-group">
                      <label class="control-label col-md-3 col-sm-3 col-xs-12" for="first-name">
                      	現住地址 : 
                      </label>
                      <h5 style="color:#999;"><?php echo $memberData[0]["memAddr"]; ?></h5>
                    </div>
                    <div class="form-group">
                      <label class="control-label col-md-3 col-sm-3 col-xs-12" for="first-name">
                      	現住電話 : 
                      </label>
                      <h5 style="color:#999;"><?php echo $memberData[0]["memPhone"]; ?></h5>
                    </div>
                    <div class="form-group">
                      <label class="control-label col-md-3 col-sm-3 col-xs-12" for="first-name">
                      	手機 : 
                      </label>
                      <h5 style="color:#999;"><?php echo $memberData[0]["memCell"]; ?></h5>
                    </div>
                    <div class="form-group">
                      <label class="control-label col-md-3 col-sm-3 col-xs-12" for="first-name">
                      	LineID : 
                      </label>
                      <h5 style="color:#999;"><?php echo $memberData[0]["memLineId"]; ?></h5>
                    </div>
                    <div class="form-group">
                      <label class="control-label col-md-3 col-sm-3 col-xs-12" for="first-name">
                      	FB token code : 
                      </label>
                      <h5 style="color:#999;"><?php echo $memberData[0]["memFBtoken"]; ?></h5>
                    </div>
                    <div class="form-group">
                      <label class="control-label col-md-3 col-sm-3 col-xs-12" for="first-name">
                      	推薦人代碼 : 
                      </label>
                      <h5 style="color:#999;"><?php echo $memberData[0]["memRecommCode"]; ?></h5>
                    </div>
                    <div class="form-group">
                      <label class="control-label col-md-3 col-sm-3 col-xs-12" for="first-name">
                      	是否是老顧客 : 
                      </label>
                      <h5 style="color:#999;"><?php echo $ifLoyal; ?></h5>
                    </div>
                    <div class="form-group">
                      <label class="control-label col-md-3 col-sm-3 col-xs-12" for="first-name">
                      	帳號申請時間 : 
                      </label>
                      <h5 style="color:#999;"><?php echo $memberData[0]["memRegistDate"]; ?></h5>
                    </div>
                    <div class="form-group not-print">
                      <label class="control-label col-md-3 col-sm-3 col-xs-12" for="first-name">
                      	採取動作 : 
                      </label>
                      <a style="text-decoration:none;" href="?page=member&type=member&action=edit&memno=<?php echo $memNo; ?>">
                      	<button class="btn btn-success">編輯</button>
                      </a>
<!--                       <button id="content-remove" class="btn btn-danger">刪除</button> -->
                    </div>
                    <div style="margin:30px;"></div>
                    
                  </div>
                  	<div class="x_title">
	                  <h2 style="text-align:center;float:none;">客戶分期訂單</h2>
	                  <div class="clearfix"></div>
	                </div>
                  <table id="example" class="table bulk_action table-striped responsive-utilities jambo_table">
                    <thead>
                      <tr class="headings">
                        <th>訂單狀態 </th>
                        <th>訂單編號 </th>
                        <th>訂單日期 </th>
                        <th>商品名稱 </th>
                        <th>商品規格 </th>
                        <th>繳款狀態 </th>
                      </tr>
                    </thead>
                    <tbody>
                    <?php 
	                    if($orMemData != null){
	                    	//
	                    	foreach($orMemData as $key=>$value){
	                    		$orig = $value;
	                    		$or->changeToReadable($value,$value["orMethod"]);
	                    		$pmData = $pm->getOnePMByNo($value["pmNo"]);
	                    		$proData = $pro->getOneProByNo($pmData[0]["proNo"]);
                    ?>
                      <tr class="pointer">
                      	<td class=" "><?php echo $value["orStatus"]; ?></td>
                      	<td class=" ">
	                      	<a style="text-decoration:underline;color:blue;" href="?page=order&action=view&method=<?php echo $orig["orMethod"]; ?>&orno=<?php echo $value["orNo"]; ?>">
	                      	<?php echo $value["orCaseNo"]; ?>
	                      	</a>
                      	</td>
                        <td class=" "><?php echo $value["orDate"]; ?></td>
                        <td class=" "><?php echo $proData[0]["proName"]; ?></td>
                        <td class=" "><?php echo $value["orProSpec"]; ?></td>
                        <td class=" last"><?php echo $value["orPaySuccess"] ?></td>
                       
                        <!--  若欄位少
                        <td class=" last">
	                        <a class="content-edit" style="text-decoration: none;" href="#">
	                        	<span style="margin-right:10px;" class="glyphicon glyphicon-pencil"></span>
	                        </a>
	                        <a class="content-remove" style="text-decoration: none;" href="#">
	                        	<span class="glyphicon glyphicon-remove"></span>
	                        </a>
                        </td>
                        -->
                      </tr>
                     <?php 
                    		}
                    	}
                     ?>
                    </tbody>
                  </table>
                  
                  <div style="margin:70px;"></div>
                  
                  <div class="x_title">
	                <h2 style="text-align:center;float:none;">目前推薦獎金累計</h2>
	                <div class="clearfix"></div>
	              </div>
	              <div class="form-group">
                    <label class="control-label col-md-12 col-sm-12 col-xs-12" for="first-name">
                    	<?php 
                    	$availRbaCount = 0;
                    	if($rbaMemData != null){ 
                    		foreach($rbaMemData as $key=>$value){
                    			if($value["rbaStatus"] == 1){
                    				$availRbaCount++;
                    			}
                    		}
                    	}?>
                     	目前推薦人數： <span style="color:red;"><?php echo sizeof($rbaMemData); ?></span> 位，預計推薦獎金：<span style="color:red;"><?php echo number_format(sizeof($rbaMemData)*$rsData[0]["rsTotalPerOrder"]); ?></span><br><br>
                     	目前實際可領推薦獎金： <span style="color:red;" id="ava-count"><?php echo number_format($availRbaCount*$rsData[0]["rsTotalPerOrder"]); ?></span> 元。<br><br>
                    </label>
                  </div>
                  <table id="example1" class="table bulk_action table-striped responsive-utilities jambo_table">
                    <thead>
                      <tr class="headings">
                        <th>訂單日期 </th>
                        <th>會員姓名 </th>
                        <th>訂單狀態 </th>
                        <th>訂單編號 </th>
                        <th>可否領取(依後台設定自動判別) </th>
                      </tr>
                    </thead>
                    <tbody>
                    <?php 
	                    if($rbaMemData != null){
	                    	//
	                    	foreach($rbaMemData as $key=>$value){
	                    		$orData = $or->getOneOrderByNo($value["orNo"]);
	                    		$orMethd = $orData[0]["orMethod"];
	                    		$or->changeToReadable($orData[0],$orData[0]["orMethod"]);
	                    		$memData = $member->getOneMemberByNo($orData[0]["memNo"]);
                    ?>
                      <tr class="pointer">
                      	<td class=" "><?php echo $orData[0]["orDate"]; ?></td>
                      	<td class=" "><?php echo $memData[0]["memName"]; ?></td>
                        <td class=" ">
                        <?php 
                        if($orData[0]["orStatus"] == "已完成"){
                        	echo $orData[0]["orStatus"]."(".$orData[0]["orReportPeriod10Date"].")";
                        }else{
                        	echo $orData[0]["orStatus"]; 
                        }
                        ?>
                        </td>
                        <td class=" ">
                          <a style="text-decoration:underline;color:blue;" href="?page=order&action=view&method=<?php echo $orMethd; ?>&orno=<?php echo $orData[0]["orNo"]; ?>">
                        	<?php echo $orData[0]["orCaseNo"]; ?>
                          </a>
                        </td>
                        <td class=" ">
							<?php 
							if($value["rbaStatus"]==1){
								echo "是"; 
							}else{ 
								echo "否"; 
							}
							?>
                        </td>
                      </tr>
                     <?php 		
                    		}
                    	}
                     ?>
                    </tbody>
                  </table>
                  
                  <div style="margin:70px;"></div>
                  
                  <div class="x_title">
	                <h2 style="text-align:center;float:none;">已申請推薦獎金</h2>
	                <div class="clearfix"></div>
	              </div>
                  <table id="example2" class="table bulk_action table-striped responsive-utilities jambo_table">
                    <thead>
                      <tr class="headings">
                        <th>申請日期 </th>
                        <th>戶名 </th>
                        <th>銀行名稱 </th>
                        <th>分行名稱 </th>
                        <th>帳號 </th>
                        <th>金額 </th>
                        <th>撥款狀態 </th>
                      </tr>
                    </thead>
                    <tbody>
                    <?php 
	                    if($rbsData != null){
	                    	//
	                    	foreach($rbsData as $key=>$value){
	                    		$rbs->changeToReadable($value);
	                    		$rbaNoArr = json_decode($value["rbaNo"]);
                    ?>
                      <tr class="pointer">
                      	<td class=" "><?php echo $value["rbsDate"]; ?></td>
                      	<td class=" "><?php echo $value["rbsBankAccName"]; ?></td>
                      	<td class=" "><?php echo $value["rbsBankName"]; ?></td>
                      	<td class=" "><?php echo $value["rbsBankBranchName"]; ?></td>
                      	<td class=" "><?php echo $value["rbsBankAcc"]; ?></td>
                      	<td class=" "><?php echo number_format(sizeof($rbaNoArr)*$rsData[0]["rsTotalPerOrder"]); ?></td>
                      	<td class=" "><?php echo $value["rbsStatus"]; ?></td>
                      </tr>
                     <?php 		
                    		}
                    	}
                     ?>
                    </tbody>
                  </table>
                  
	              
                  <div style="margin:30px;"></div>
                    <div class="form-group not-print">
                      <div class="col-md-6 col-sm-6 col-xs-12 col-md-offset-3">
                        <a style="color:#FFF;" href="?page=member&type=member">
                          <button class="btn btn-primary">回會員列表</button>
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
        <!-- /page content -->
        
  <!-- Datatables -->
  <script src="js/datatables/js/jquery.dataTables.js"></script>
  <script src="js/datatables/tools/js/dataTables.tableTools.js"></script>

  <!-- pace -->
  <script src="js/pace/pace.min.js"></script>
<script>
$(function(){
	$(document).on("change",".rbaStatus",function(){
		var cur = $(this);
		var curVal;
		var curRbaNo = cur.siblings(".rbaNo").val();

		if(cur.is(":checked")){
			curVal = 1;
		}else{
			curVal = 0;
		}
		$.ajax({
			type: "POST",
			url: "ajax/rba/edit_status.php",
			data: {"rbaNo":curRbaNo, "rbaStatus":curVal},
			success: function(result){    
				if(result.indexOf("成功") != -1){
					alert(result.split(" ")[0]);
					$("#ava-count").text(result.split(" ")[1]*200);
				}else{
					alert(result);
				}
			}
		});
	});

	var asInitVals = new Array();
	var asInitVals1 = new Array();
	var asInitVals2 = new Array();
    $(document).ready(function() {
      var oTable = $('#example').dataTable({
        "oLanguage": {
          "sSearch": "搜尋: "
        },
        'iDisplayLength': 5,
        "sPaginationType": "full_numbers"
      });
      $("tfoot input").keyup(function() {
        /* Filter on the column based on the index of this element's parent <th> */
        oTable.fnFilter(this.value, $("tfoot th").index($(this).parent()));
      });
      $("tfoot input").each(function(i) {
        asInitVals[i] = this.value;
      });
      $("tfoot input").focus(function() {
        if (this.className == "search_init") {
          this.className = "";
          this.value = "";
        }
      });
      $("tfoot input").blur(function(i) {
        if (this.value == "") {
          this.className = "search_init";
          this.value = asInitVals[$("tfoot input").index(this)];
        }
      });

      var oTable1 = $('#example1').dataTable({
          "oLanguage": {
            "sSearch": "搜尋: "
          },
          'iDisplayLength': 5,
          "sPaginationType": "full_numbers"
        });
        $("tfoot input").keyup(function() {
          /* Filter on the column based on the index of this element's parent <th> */
          oTable1.fnFilter(this.value, $("tfoot th").index($(this).parent()));
        });
        $("tfoot input").each(function(i) {
          asInitVals1[i] = this.value;
        });
        $("tfoot input").focus(function() {
          if (this.className == "search_init") {
            this.className = "";
            this.value = "";
          }
        });
        $("tfoot input").blur(function(i) {
          if (this.value == "") {
            this.className = "search_init";
            this.value = asInitVals1[$("tfoot input").index(this)];
          }
        });

        var oTable2 = $('#example2').dataTable({
            "oLanguage": {
              "sSearch": "搜尋: "
            },
            'iDisplayLength': 5,
            "sPaginationType": "full_numbers"
          });
          $("tfoot input").keyup(function() {
            /* Filter on the column based on the index of this element's parent <th> */
            oTable2.fnFilter(this.value, $("tfoot th").index($(this).parent()));
          });
          $("tfoot input").each(function(i) {
            asInitVals2[i] = this.value;
          });
          $("tfoot input").focus(function() {
            if (this.className == "search_init") {
              this.className = "";
              this.value = "";
            }
          });
          $("tfoot input").blur(function(i) {
            if (this.value == "") {
              this.className = "search_init";
              this.value = asInitVals2[$("tfoot input").index(this)];
            }
          });
    });
});
</script>