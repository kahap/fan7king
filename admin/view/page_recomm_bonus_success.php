<?php 
require_once('model/require_general.php');

$rbs = new Recomm_Bonus_Success();
$mem = new Member();
$allRbsData = null;

$rs = new Recomm_Setting();
$rsData = $rs->getSetting();

if(isset($_GET["status"]) && $_GET["status"] != "all"){
	$allRbsData = $rbs->getRBSByStatus($_GET["status"]);
}else{
	$allRbsData = $rbs->getAllRBS();
}

?>
<!-- page content -->
      <div class="right_col" role="main">
        <div class="">
          <div class="page-title">
            <div class="title_left">
              <h3>推薦碼獎金撥款</h3>
            </div>
          </div>
          <div class="clearfix"></div>

          <div class="row">

            <div class="col-md-12 col-sm-12 col-xs-12">
              <div class="x_panel">
                <div class="x_content">
                  <div style="padding:10px 0 20px 0;">
                  	<label>分類依據： </label>
                  	<select id="status" name="status">
                  		<option <?php if(isset($_GET["status"]) && $_GET["status"]==0) echo "selected" ?> value="0">未撥款</option>
                  		<option <?php if(isset($_GET["status"]) && $_GET["status"]==1) echo "selected" ?> value="1">已撥款</option>
                  		<option <?php if(isset($_GET["status"]) && $_GET["status"]=="all") echo "selected" ?> value="all">全部</option>
                  	</select>
                  	<button id="export-excel" style="margin-left:10px;" class="btn btn-success">勾選選項匯出Excel</button>
                  </div>
                  <table id="example" class="table table-striped responsive-utilities jambo_table">
                    <thead>
                      <tr class="headings">
                      	<th>
                          <input id="check-all" type="checkbox" class="tableflat">
                        </th>
                        <th>推薦獎金申請編號 </th>
                        <th>申請日期 </th>
                        <th>申請會員</th>
                        <th>戶名</th>
                        <th>身分證字號 </th>
                        <th>銀行名稱 </th>
                        <th>分行名稱 </th>
                        <th>帳號 </th>
                        <th>申請總金額 </th>
                        <th>是否已撥款 </th>
                        <th class=" no-link last"><span class="nobr">明細</span></th>
                      </tr>
                    </thead>
                    <tbody>
                    <?php 
	                    if($allRbsData != null){
	                    	foreach($allRbsData as $key=>$value){
	                    		$rbaNoArr = json_decode($value["rbaNo"]);
	                    		$memData = $mem->getOneMemberByNo($value["memNo"]);
                    ?>
                      <tr class="pointer">
                      	<td class="a-center ">
                          <input type="checkbox" class="for-excel tableflat">
                        </td>
                      	<td class=" "><a style="text-decoration:underline;color:blue;" href="?page=recommBonus&type=confirm&action=view&rbsno=<?php echo $value["rbsNo"]; ?>"><?php echo $value["rbsNo"]; ?></a></td>
                      	<td class=" "><?php echo $value["rbsDate"]; ?></td>
                      	<td class=" "><?php echo $memData[0]["memName"]; ?></td>
                        <td class=" "><?php echo $value["rbsBankAccName"]; ?></td>
                        <td class=" "><?php echo $value["rbsIdNum"]; ?></td>
                        <td class=" "><?php echo $value["rbsBankName"]; ?></td>
                        <td class=" "><?php echo $value["rbsBankBranchName"]; ?></td>
                        <td class=" "><?php echo $value["rbsBankAcc"]; ?></td>
                        <td class=" "><?php echo number_format(sizeof($rbaNoArr)*$rsData[0]["rsTotalPerOrder"]-$rsData[0]["rsCharge"]); ?></td>
                        <td class=" "><input class="change-status" type="checkbox" <?php if($value["rbsStatus"] == 1) echo "checked"; ?>></td>
                        <td class=" last">
                        	<button style="background-color:#FFF;border:1px solid #CCC;" class="btn btn-defult view-details">
                        		明細
                        	</button>
                        </td>
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
                  <div style="margin: 30px 0;" class="clearfix"></div>
                  <div style="display:none;" id="details">
                    <div class="x_title">
                      <h2 style="text-align:center;float:none;">推薦獎金明細</h2>
                      <div class="clearfix"></div>
                    </div>
                    <div class="clearfix"></div>
                    <div class="x-content">
                  	  <table class="table table-striped responsive-utilities jambo_table">
	                    <thead>
	                      <tr class="headings">
	                        <th>訂單日期 </th>
	                        <th>會員姓名 </th>
	                        <th>身分證字號</th>
	                        <th>訂單編號</th>
	                        <th>訂單狀態 </th>
	                      </tr>
	                    </thead>
	                    <tbody>
	                    <?php 
		                    if($allRbsData != null){
		                    	foreach($allRbsData as $key=>$value){
		                    		$rbaNoArr = json_decode($value["rbaNo"]);
		                    		$memData = $mem->getOneMemberByNo($value["memNo"]);
	                    ?>
	                      <tr class="pointer">
	                      	<td class=" "><?php echo $value["rbsNo"]; ?></td>
	                      	<td class=" "><?php echo $value["rbsDate"]; ?></td>
	                      	<td class=" "><?php echo $memData[0]["memName"]; ?></td>
	                        <td class=" "><?php echo $value["rbsBankAccName"]; ?></td>
	                        <td class=" "><?php echo $value["rbsIdNum"]; ?></td>
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
              </div>
            </div>

            <br />
            <br />
            <br />

          </div>
        </div>
        <table style="display:none;border:1px solid #000;" id="excel-area">
			<thead>
			  <tr class="headings">
				<th>推薦獎金申請編號 </th>
				<th>申請日期 </th>
				<th>申請會員</th>
				<th>戶名</th>
				<th>身分證字號 </th>
				<th>銀行名稱 </th>
				<th>分行名稱 </th>
				<th>帳號 </th>
				<th>申請總金額 </th>
				<th>是否已撥款 </th>
			  </tr>
			</thead>
			<tbody>
			
			</tbody>
        </table>
        
  
  <!-- Excel -->  
  <script src="https://cdn.jsdelivr.net/alasql/0.3/alasql.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.7.12/xlsx.core.min.js"></script>
        
  <!-- Datatables -->
  <script src="js/datatables/js/jquery.dataTables.js"></script>
  <script src="js/datatables/tools/js/dataTables.tableTools.js"></script>

  <!-- pace -->
  <script src="js/pace/pace.min.js"></script>
  <script>
  	$(function(){
		$("#status").change(function(){
			location.href="?page=recommBonus&type=confirm&status=" + $("#status").find("option:selected").val();
		});
		$(document).on("change",".change-status",function(){
			var cur = $(this);
			var curVal;
			var curNo = cur.parent().parent().children("td").eq(1).text();

			if(cur.is(":checked")){
				curVal = 1;
			}else{
				curVal = 0;
			}
			$.ajax({
				type: "POST",
				url: "ajax/rbs/edit_status.php",
				data: {"rbsNo":curNo, "rbsStatus":curVal},
				success: function(result){    
					alert(result);
				}
			});
		});

		

		//查看明細
		$(document).on("click",".view-details",function(){
			$("#details").show();
			$("#details table tbody tr").remove();
			var cur = $(this);
			var rbsNo = cur.parent().siblings("td").eq(1).text();
			$.ajax({
				type: "POST",
				url: "ajax/rbs/get_rbs_by_no.php",
				data: {"rbsNo":rbsNo},
				success: function(result){
					var results = JSON.parse(result);
					$.each(results,function(k,v){
						$("#details table tbody").append("<tr></tr>");
						$.each(v,function(key,value){
							$("#details table tbody tr").eq(k).append("<td>"+value+"</td>")
						});
					});
				}
			});
		});

		//匯出excel
		$("#export-excel").click(function(e){
			if($(".for-excel:checked").length == 0){
				alert("請先勾選要匯出項目");
			}else{
				$("#excel-area tbody").text("");
				var content = "";
				for(var i=0; i<$(".for-excel:checked").length; i++){
					content = content + "<tr>";
					for(var n=1; n<11; n++){
						if(n==10){
							var isPaid;
							if($(".for-excel:checked").eq(i).parent().parent().parent().children("td").eq(n).find("input").is(":checked")){
								isPaid = "已撥款";
							}else{
								isPaid = "未撥款";
							}
							content = content + "<td>" + isPaid + "</td>";
						}else{
							content = content + "<td>" + $(".for-excel:checked").eq(i).parent().parent().parent().children("td").eq(n).text() + "</td>";
						}
					}
					content = content + "</tr>";
				}
				$("#excel-area tbody").append(content);
				

				// 匯出區塊
				var tab_text = '<html xmlns:x="urn:schemas-microsoft-com:office:excel">';
			    tab_text = tab_text + '<head><xml><x:ExcelWorkbook><x:ExcelWorksheets><x:ExcelWorksheet>';
			    tab_text = tab_text + '<x:Name>推薦獎金申請</x:Name>';
			    tab_text = tab_text + '<x:WorksheetOptions><x:Panes></x:Panes></x:WorksheetOptions></x:ExcelWorksheet>';
			    tab_text = tab_text + '</x:ExcelWorksheets></x:ExcelWorkbook></xml></head><body>';
			    tab_text = tab_text + "<table x:str border='1px'>";
			    tab_text = tab_text + $('#excel-area').html();
			    tab_text = tab_text + '</table></body></html>';
				window.open('data:application/vnd.ms-excel,' + encodeURIComponent(tab_text));
				e.preventDefault();
			}
		});
  	});

  	
    $(document).ready(function() {
      $('input.tableflat').iCheck({
        checkboxClass: 'icheckbox_flat-green',
        radioClass: 'iradio_flat-green'
      });

      var checkAll = $('#check-all');
	    var checkboxes = $('input.for-excel');
	
	    checkAll.on('ifChecked ifUnchecked', function(event) {        
	        if (event.type == 'ifChecked') {
	            checkboxes.iCheck('check');
	        } else {
	            checkboxes.iCheck('uncheck');
	        }
	    });
	
	    checkboxes.on('ifChanged', function(event){
	        if(checkboxes.filter(':checked').length == checkboxes.length) {
	            checkAll.prop('checked', 'checked');
	        } else {
	            checkAll.removeProp('checked');
	        }
	        checkAll.iCheck('update');
	    });
    });

    var asInitVals = new Array();
    $(document).ready(function() {
      var oTable = $('#example').dataTable({
        "oLanguage": {
          "sSearch": "搜尋: "
        },
        "aoColumnDefs": [{
            'bSortable': false,
            'aTargets': [0]
          } //disables sorting for column one
        ],
        'iDisplayLength': 100,
        "sPaginationType": "full_numbers"
      })<?php if(isset($_GET["pageIndex"]) && $_GET["pageIndex"]=='last') echo ".fnPageChange( 'last' );$(window).scrollTop($(document).height())";?>;
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