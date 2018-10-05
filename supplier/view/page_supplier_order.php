<?php 
require_once('model/require_general.php');

$or = new Orders();
$sr = new Status_Record();
$pm = new Product_Manage();
$pro = new Product();
$sup = new Supplier();
$mem = new Member();

//時間
date_default_timezone_set('Asia/Taipei');
$date = date('Y-m-d', time());

$allOrData = array();
$allSupData = array();
$ulrIframe = "a=a";

foreach($_GET as $key=>$value){
	$$key = $value;
	$ulrIframe .= "&".$key."=".$value;
}
if(isset($orSupDateFrom)){
	$allOrData = $or->getAllOrderOnSupPage($action,$status, $supno, $orDateFrom, $orDateTo, $orSupDateFrom, $orSupDateTo);
}else{
	$allOrData = $or->getAllOrderOnSupPageNoSupOrder($action,$status, $supno, $orDateFrom, $orDateTo);
}

$allSupData = $sup->getAllSupplier();

?>
<!-- page content -->
      <div class="right_col" role="main">
        <div class="">
          <div class="page-title">
            <div class="title_left">
              <h3>分期訂貨(核准)</h3>
            </div>
          </div>
          <div class="clearfix"></div>
		  
          <div class="row">

            <div class="col-md-12 col-sm-12 col-xs-12">
              <div class="x_panel">
                <div class="x_content">
                  <div class="query-area" style="margin:5px;">
                  	<label>選擇供應商： </label>
                  	<select class="with-name" id="whichSup" name="supno">
                  		<option value="all">全部</option>
                  		<?php foreach($allSupData as $key=>$value){ ?>
                  		<option <?php if($value["supNo"] == $supno){ echo "selected"; }?> value="<?php echo $value["supNo"]; ?>"><?php echo $value["supName"]; ?></option>
                  		<?php } ?>
                  	</select>
                  </div>
                  <div class="query-area" style="margin:5px;">
                  	<label>訂單日期： </label>
                  	<input id="or-date-from" name="orDateFrom" class="date-picker with-name" type="text" value="<?php if(isset($_GET["orDateFrom"])){ echo $_GET["orDateFrom"]; }else{ echo "2016-01-01"; } ?>">
                  	至
                  	<input id="or-date-to" name="orDateTo" class="date-picker with-name" type="text" value="<?php if(isset($_GET["orDateTo"])){ echo $_GET["orDateTo"]; }else{ echo $date; } ?>">
                  </div>
                  <div class="query-area" id="or-sup-area" style="margin:5px; <?php if($status != "all") echo "display:none;"; ?>">
                  	<label>訂貨日期： </label>
                  	<input id="or-sup-date-from" name="orSupDateFrom" class="date-picker with-name" type="text" value="<?php if(isset($_GET["orSupDateFrom"])){ echo $_GET["orSupDateFrom"]; }else{ echo "2016-01-01"; } ?>">
                  	至
                  	<input id="or-sup-date-to" name="orSupDateTo" class="date-picker with-name" type="text" value="<?php if(isset($_GET["orSupDateTo"])){ echo $_GET["orSupDateTo"]; }else{ echo $date; } ?>">
                  </div>
                  <div class="query-area" style="padding:0 0 20px 0;margin:5px;">
                  	<label>訂單狀態： </label>
                  	<select id="whichStatus" name="status" class="with-name">
                  		<?php foreach($or->statusArr as $key=>$value){
                  			if($value == "核准"){
                  		?>
                  		<option <?php if($status === (string)$key) echo "selected"; ?> value="<?php echo $key ?>" ><?php echo $value; ?></option>
                  		<?php 		
                  			}
                  		} ?>
                  		<option <?php if($status == "all") echo "selected"; ?> value="all">不限</option>
                  	</select>
                    <button id="query" style="margin-left:15px;" class="btn btn-success">查詢</button>
                  </div>
                  <button id="export-excel" class="btn btn-success">匯出Excel</button>
                  <table id="example" class="table bulk_action table-striped responsive-utilities jambo_table">
                    <thead>
                      <tr class="headings">
                        <th>訂單狀態 </th>
                        <th>訂單序號</th>
                        <th>訂單編號 </th>
                        <th>訂單日期 </th>
                        <th>訂購人姓名 </th>
                        <th>商品名稱 </th>
                        <th>商品規格 </th>
                        <th>供應商 </th>
                        <th>供應價 </th>
                        <th class=" no-link last"><span class="nobr">訂貨日期</span></th>
                      </tr>
                    </thead>
                    <tbody>
                    <?php 
	                    if($allOrData != null){
	                    	foreach($allOrData as $key=>$value){
	                    		if($value["orMethod"] == $_GET["action"]){
		                    		$or->changeToReadable($value,1);
		                    		$memData = $mem->getOneMemberByNo($value["memNo"]);
		                    		$pmData = $pm->getOnePMByNo($value["pmNo"]);
		                    		$proData = $pro->getOneProByNo($pmData[0]["proNo"]);
		                    		$supData = $sup->getOneSupplierByNo($value["supNo"])
                    ?>
                      <tr class="pointer">
                      	<td class=" "><?php echo $value["orStatus"]; ?></td>
                      	<td class=" "><?php echo $value["orNo"]; ?></td>
                      	<td class=" "><a style="color:blue;text-decoration:underline;" href="?page=order&action=view&method=1&orno=<?php echo $value["orNo"]; ?>"><?php echo $value["orCaseNo"]; ?></a></td>
                        <td class=" "><?php echo $value["orDate"]; ?></td>
                        <td class=" "><?php echo $memData[0]["memName"]; ?></td>
                        <td class=" "><a style="color:blue;text-decoration:underline;" href="?page=product&type=productManage&action=view&prono=<?php echo $proData[0]["proNo"]; ?>"><?php echo $proData[0]["proName"]; ?></td>
                        <td class=" "><?php echo $value["orProSpec"]; ?></td>
                        <td class=" "><?php echo $supData[0]["supName"]; ?></td>
                        <td class=" "><?php echo number_format($value["orSupPrice"]); ?></td>
                        <td class=" last"><?php echo $value["orHandleOrderFromSupDate"]; ?></td>
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
                    	}
                     ?>
                    </tbody>
                  </table>
                </div>
              </div>
            </div>
            
			<iframe style="display:none;" src="view/print_excel_order.php?<?php echo $ulrIframe; ?>"></iframe>
            <br />
            <br />
            <br />

          </div>
        </div>
        
  <!-- Datatables -->
  <script src="js/datatables/js/jquery.dataTables.js"></script>
  <script src="js/datatables/tools/js/dataTables.tableTools.js"></script>
  
  <!-- daterangepicker -->
  <script type="text/javascript" src="js/moment/moment.min.js"></script>
  <script type="text/javascript" src="js/datepicker/daterangepicker.js"></script>

  <!-- pace -->
  <script src="js/pace/pace.min.js"></script>
  
  <script>
  	$(function(){
		$("#whichStatus").change(function(){
			//跳出訂貨日期選項
			if($(this).val() == "all"){
				$("#or-sup-area").show();
			}else{
				$("#or-sup-area").hide();
			}
		});

		//匯出EXCEL
		$("#export-excel").click(function(){
			//匯出區塊
			var tab_text = '<html xmlns:x="urn:schemas-microsoft-com:office:excel">';
		    tab_text = tab_text + '<head><xml><x:ExcelWorkbook><x:ExcelWorksheets><x:ExcelWorksheet>';
		    tab_text = tab_text + '<x:Name></x:Name>';
		    tab_text = tab_text + '<x:WorksheetOptions><x:Panes></x:Panes></x:WorksheetOptions></x:ExcelWorksheet>';
		    tab_text = tab_text + '</x:ExcelWorksheets></x:ExcelWorkbook></xml></head><body>';
		    tab_text = tab_text + "<table x:str border='1px'>";
		    tab_text = tab_text + $("iframe").contents().find("table").html();
		    tab_text = tab_text + '</table></body></html>';
			window.open('data:application/vnd.ms-excel,' + encodeURIComponent(tab_text));
			e.preventDefault();
		});

		//查詢
		$("#query").click(function(){
			var url = "?page=supplier&action=1";
			if($("#or-sup-area").css("display").indexOf("none") != -1){
				for(var i=0; i<$(".with-name").not($("#or-sup-area").children(".with-name")).length; i++){
					url = url + "&" + $(".with-name").not($("#or-sup-area").children(".with-name")).eq(i).attr("name") + "=" + $(".with-name").not($("#or-sup-area").children(".with-name")).eq(i).val();
				}
			}else{
				for(var i=0; i<$(".with-name").length; i++){
					url = url + "&" + $(".with-name").eq(i).attr("name") + "=" + $(".with-name").eq(i).val();
				}
			}
			location.href = url;
		});

		//選擇日期
		$('#or-date-from, #or-date-to, #or-sup-date-from, #or-sup-date-to').daterangepicker({
            singleDatePicker: true,
            calender_style: "picker_4",
            format: 'YYYY-MM-DD'
          });
  	});
    $(document).ready(function() {
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
        "aoColumnDefs": [{
            'bSortable': false,
            'aTargets': [0]
          } //disables sorting for column one
        ],
        'iDisplayLength': 12,
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