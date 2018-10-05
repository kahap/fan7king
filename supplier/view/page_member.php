<?php 
require_once('model/require_general.php');

$member = new Member();
$allMemberData = $member->getAllMember();

$lg = new Loyal_Guest();
$allLgData = $lg->getAllLoyalGuest();

?>
<!-- page content -->
      <div class="right_col" role="main">
        <div class="">
          <div class="page-title">
            <div class="title_left">
              <h3>會員列表</h3>
            </div>
          </div>
          <div class="clearfix"></div>

          <div class="row">

            <div class="col-md-12 col-sm-12 col-xs-12">
              <div class="x_panel">
                <div class="x_content">
                  <table id="example" class="table table-striped responsive-utilities jambo_table">
                    <thead>
                      <tr class="headings">
                        <th>訊息發送</th>
                        <th>會員編號 </th>
                        <th>姓名 </th>
                        <th>身分證字號 </th>
                        <th>註冊時間 </th>
                        <th>老客戶 </th>
						<th>推薦碼 </th>
                        <th>fb連結</th>
                        <th>是否用APP </th>
                        <th>允許登入 </th>
                      </tr>
                    </thead>
                    <tbody>
                    <?php 
	                    if($allMemberData != null){
	                    	foreach($allMemberData as $key=>$value){
	                    		$member->changeToReadable($value);
	                    		$ifLoyal = "否";
	                    		foreach($allLgData as $keyIn=>$valueIn){
	                    			if($valueIn["lgIdNum"] == $value["memIdNum"]){
	                    				$ifLoyal = "是";
	                    			}
	                    		}
                    ?>
                      <tr class="pointer">
                        <td>
	                        <a href="?page=customer&type=textmsg&memno=<?php echo $value["memNo"]; ?>"><button class="btn btn-success">簡訊</button></a>
	                        <a href="?page=customer&type=email&memno=<?php echo $value["memNo"]; ?>"><button class="btn btn-success">Email</button></a>
                        </td>
                        <td class=" ">
	                        <a style="text-decoration:underline;color:blue;" href="?page=member&type=member&action=view&memno=<?php echo $value["memNo"]; ?>"><?php echo $value["memNo"]; ?></a>
                        </td>
                        <td class=" "><?php echo $value["memName"]; ?></td>
                        <td class=" "><?php echo $value["memIdNum"]; ?></td>
                        <td class=" "><?php echo $value["memRegistDate"]; ?></td>
                        <td class=" "><?php echo $ifLoyal; ?></td>
						<td class=" "><?php echo $value["memRecommCode"]; ?></td>
                        <td class=" "><?php echo "<a target='blank' style='text-decoration:underline;color:blue;' href='https://www.facebook.com/".$value["memFBtoken"]."'>".$value["memRegistMethod"]."</a>"; ?></td>
                        <td class=" ">
	                        <?php 
							//echo "<a target='blank' style='text-decoration:underline;color:blue;' href='https://www.facebook.com/".$value["memFBtoken"]."'>".$value["memRegistMethod"]."</a>";
	                        if($member->loginfromAPP($value["memNo"])){
	                        	echo " 是";
	                        }else{
	                        	echo " 否"; 
	                        }
	                        ?>
                        </td>
                        <td class=" last">
	                        <input class="change-login" type="checkbox" <?php if($value["memAllowLogin"] == "允許") echo "checked"; ?>>
                        </td>
                      </tr>
                     <?php 
                    		}
                    	}
                     ?>
                    </tbody>
                  </table>
                </div>
              </div>
            </div>

            <br />
            <br />
            <br />

          </div>
        </div>
        
  <!-- Datatables -->
  <script src="js/datatables/js/jquery.dataTables.js"></script>
  <script src="js/datatables/tools/js/dataTables.tableTools.js"></script>

  <!-- pace -->
  <script src="js/pace/pace.min.js"></script>
  <script>
  	
    $(document).ready(function() {
    	$(document).on("change",".change-login",function(){
    		var cur = $(this);
    		var curVal;
    		var curNo = cur.parent().parent().children("td").eq(1).children("a").text();

    		if(cur.is(":checked")){
    			curVal = 1;
    		}else{
    			curVal = 0;
    		}
    		$.ajax({
    			type: "POST",
    			url: "ajax/member/edit_status.php",
    			data: {"memNo":curNo, "memAllowLogin":curVal},
    			success: function(result){    
    				alert(result);
    			}
    		});
    	});
        
      $('input.tableflat').iCheck({
        checkboxClass: 'icheckbox_flat-green',
        radioClass: 'iradio_flat-green'
      });
    });

    var asInitVals = new Array();
    $(document).ready(function() {
      var oTable = $('#example').dataTable({
        "oLanguage": {
          "sSearch": "搜尋: "
        },
        'iDisplayLength': 100,
        "order": [[ 1, "asc" ]],
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
    });
  </script>