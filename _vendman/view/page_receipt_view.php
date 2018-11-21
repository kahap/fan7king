<?php 
require_once('model/require_general.php');

$or = new Orders();
$pm = new Product_Manage();
$pro = new Product();
$sup = new Supplier();
$mem = new Member();
$os = new Other_Setting();
$setting = $os->getAll();
$days = $setting[0]["receiptDays"];

$ifSet = isset($_GET["ifSet"]) ? $_GET["ifSet"] : 0;

$orData = $or->getOrdersForReceiptSet($ifSet);

//時間
date_default_timezone_set('Asia/Taipei');
$date = date('Y-m-d', time());



?>
<!-- page content -->
      <div class="right_col" role="main">
        <div class="">
          <div class="page-title">
            <div class="title_left">
				<?php if($ifSet == 0){?>
				<h3>未開立發票訂單</h3>
				<?php }else{ ?>
				<h3>已開立發票訂單</h3>
				<?php } ?>
            </div>
          </div>
          <div class="clearfix"></div>
		  
          <div class="row">

            <div class="col-md-12 col-sm-12 col-xs-12">
              <div class="x_panel">
                <div class="x_content">
				  <div class="query-area" style="margin:20px 0;">
                  	<label>開立狀況： </label>
                  	<select id="setStatus">
						<option <?php if($ifSet == 0) echo "selected"; ?> value="0">未開立</option>
						<option <?php if($ifSet == 1) echo "selected"; ?> value="1">已開立</option>
					</select>
                  </div>
                  <button id="export-excel" class="btn btn-success">匯出Excel</button>
                  <table id="example" class="table bulk_action table-striped responsive-utilities jambo_table">
                    <thead>
                      <tr class="headings">
                        <th>訂單編號 </th>
                        <th>內部訂單編號</th>
						<th>申請人</th>
						<th>身分證字號</th>
						<th>撥款金額(供貨價)</th>
						<th>申請金額(分期總金額)</th>
						<th>期數</th>
						<th>月付金額</th>
						<th>供應商</th>
						<th>推薦人姓名</th>
						<th>推薦碼</th>
                        <th>下單日期 </th>
                        <th>訂單已完成日期 </th>
                        <th>是否已開發票</th>
						<th>開立日期</th>
                      </tr>
                    </thead>
                    <tbody>
                    <?php 
	                    if($orData != null && !empty(array_filter($orData))){
	                    	foreach($orData as $key=>$value){
								$memData = $mem->getOneMemberByNo($value["memNo"]);
								$recName = "無";
								$recCode = "無";
								if(trim($memData[0]["memRecommCode"]) != "" && is_numeric($memData[0]["memRecommCode"])){
									$recMemData = $mem->getOneMemberByNo($memData[0]["memRecommCode"]);
									$recCode = $memData[0]["memRecommCode"];
									$recName = $recMemData[0]["memName"];
								}
								$mem->changeToReadable($memData[0]);
								$pmData = $pm->getOnePMByNo($value["pmNo"]);
								$proData = $pro->getOneProByNo($pmData[0]["proNo"]);
								$supData = $sup->getOneSupplierByNo($value["supNo"]);
	                    		if($value["orIfSetReceipt"] == 1){
	                    			$if = "已開立";
	                    		}else{
	                    			$if = "未開立";
	                    		}
	                    		$orTime = strtotime($value["orReportPeriod10Date"]);
	                    		if((time()-$orTime) >= ($days*86400) && $value["orReportPeriod10Date"] != ""){
                    ?>
                      <tr class="pointer">
                      	<td class=" "><a style="color:blue;text-decoration:underline;" href="?page=order&action=view&method=<?php echo $value["orMethod"];  ?>&orno=<?php echo $value["orNo"]; ?>"><?php echo $value["orCaseNo"]; ?></a></td>
                      	<td class=" "><?php echo $value["orInternalCaseNo"]; ?></td>
						<td class=" "><?php echo $memData[0]["memName"]; ?></td>
						<td class=" "><?php echo $memData[0]["memIdNum"]; ?></td>
						<td class=" "><?php echo number_format($value["orSupPrice"]); ?></td>
						<td class=" "><?php echo number_format($value["orPeriodTotal"]); ?></td>
						<td class=" "><?php echo $value["orPeriodAmnt"]; ?></td>
						<td class=" "><?php echo number_format($value["orPeriodTotal"]/$value["orPeriodAmnt"]); ?></td>
						<td class=" "><?php echo $supData[0]["supName"]; ?></td>
						<td class=" "><?php echo $recName; ?></td>
						<td class=" "><?php echo $recCode; ?></td>
                      	<td class=" "><?php echo $value["orDate"]; ?></td>
                        <td class=" "><?php echo $value["orReportPeriod10Date"]; ?></td>
                        <td class=" "><?php echo $if; ?></td>
						<td class=" "><?php echo $value["orSetReceiptTime"]; ?></td>
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
		$("#setStatus").change(function(){
			var curVal = $("#setStatus option:selected").val();
			location.href="?page=receipt&type=view&ifSet=" + curVal;
		});

		//匯出EXCEL
		$("#export-excel").click(function(e){
			//匯出區塊
			var tab_text = '<html xmlns:x="urn:schemas-microsoft-com:office:excel">';
			tab_text = tab_text + '<meta http-equiv="content-type" content="application/vnd.ms-excel; charset=UTF-8">';
		    tab_text = tab_text + '<head><xml><x:ExcelWorkbook><x:ExcelWorksheets><x:ExcelWorksheet>';
		    tab_text = tab_text + '<x:Name>未開立發票清單</x:Name>';
		    tab_text = tab_text + '<x:WorksheetOptions><x:Panes></x:Panes></x:WorksheetOptions></x:ExcelWorksheet>';
		    tab_text = tab_text + '</x:ExcelWorksheets></x:ExcelWorkbook></xml></head><body>';
		    tab_text = tab_text + "<table x:str border='1px'>";
		    tab_text = tab_text + $("#example").html();
		    tab_text = tab_text + '</table></body></html>';
			window.open('data:application/vnd.ms-excel,' + encodeURIComponent(tab_text));
			e.preventDefault();
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
        "order": [[ 1, "asc" ]],
        'iDisplayLength': "100",
		"aLengthMenu": [[25, 50, 100, -1], [25, 50, 100, "All"]],
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