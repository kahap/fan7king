<?php 
require_once('model/require_general.php');

$pm = new Product_Manage();
$pro = new Product();
$sup = new Supplier();
$cat = new Category();
$bra = new Brand();


$page2 = isset($_GET["paginate"])? $_GET["paginate"] : 1;
$search = isset($_GET["search"])? $_GET["search"] : null;


foreach($_GET as $key=>$value){
	$$key = $value;
}

$allSupData = $sup->getAllSupplier();

$allPMData = $pm->getAllPMMainSup( ($page2-1)*30 , 30 , $search);
$totalProData = $pm->getAllPMMainSupCount($search);
$lastPage = ceil($totalProData/30);

if(isset($supno) && is_numeric($supno)){
	$allPMData = $pm->getAllPMBySupNo($supno , ($page2-1)*30 , 30 , $search);
    $totalProData = $pm->getAllPMBySupNoCount($supno, $search);
    $lastPage = ceil($totalProData/30);
}

?>
<!-- page content -->
      <div class="right_col" role="main">
        <div class="">
          <div class="page-title">
            <div class="title_left">
              <h3>供貨商品清單</h3>
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
                  		<option <?php if(isset($supno) && $value["supNo"] == $supno){ echo "selected"; }?> value="<?php echo $value["supNo"]; ?>"><?php echo $value["supName"]; ?></option>
                  		<?php } ?>
                  	</select>
                    <button id="query" style="margin-left:15px;" class="btn btn-success">查詢</button><br>
					<span style="color:red;">*點選「匯出Excel」前請先按查詢按鈕，才能確認資料正確性</span>
                  </div>
                  <button id="export-excel" class="btn btn-success">匯出Excel</button>


                    <div class="top">
                        <div class="dataTables_paginate paging_full_numbers" id="example_paginate">
                            <a href="admin.php?page=supplier&action=productList<?php if(isset($_GET["supno"]))echo '&supno='.$_GET["supno"];?>&paginate=1&search=<?php echo $search;?>" class="paginate_button first disabled" aria-controls="example" data-dt-idx="0" tabindex="0" id="example_first">
                                第一頁
                            </a>
                            <?php if ($page2>1){ ?>
                                <a href="admin.php?page=supplier&action=productList<?php if(isset($_GET["supno"]))echo '&supno='.$_GET["supno"];?>&paginate=<?php echo $page2-1;?>&search=<?php echo $search;?>" class="paginate_button previous disabled" aria-controls="example" data-dt-idx="1" tabindex="0" id="example_previous">
                                    前一頁
                                </a>
                            <?php } ?>
                            <span>
                                <select class="paginate_button choosePage" data-href="admin.php?page=supplier&action=productList<?php if(isset($_GET["supno"]))echo '&supno='.$_GET["supno"];?>&search=<?php echo $search;?>">
                                <?php for ($i=1;$i<=$lastPage;$i++){ ?>
                                    <option value="<?php echo $i;?>" <?php if($page2==$i)echo 'selected';?> >
                                        <?php echo $i;?>
                                    </option>
                                <?php } ?>
                                </select>
                                <!--                                <a  href="&paginate=2" class="paginate_button " aria-controls="example" data-dt-idx="3" tabindex="0">2</a>-->
                                <!--                                <a  href="&paginate=3" class="paginate_button " aria-controls="example" data-dt-idx="4" tabindex="0">3</a>-->
                            </span>
                            <?php if ($page2<$lastPage){ ?>
                                <a href="admin.php?page=supplier&action=productList<?php if(isset($_GET["supno"]))echo '&supno='.$_GET["supno"];?>&paginate=<?php echo $page2+1;?>&search=<?php echo $search;?>" class="paginate_button next" aria-controls="example" data-dt-idx="5" tabindex="0" id="example_next">
                                    下一頁
                                </a>
                            <?php } ?>
                            <a href="admin.php?page=supplier&action=productList<?php if(isset($_GET["supno"]))echo '&supno='.$_GET["supno"];?>&paginate=<?php echo $lastPage;?>&search=<?php echo $search;?>" class="paginate_button last" aria-controls="example" data-dt-idx="6" tabindex="0" id="example_last">
                                最後一頁
                            </a>
                        </div>
                    </div>
                    <div class="top">
                        <div class="dataTables_info" id="example_info2" role="status" aria-live="polite">顯示 第 <?php echo ($page2-1)*30+1;?> 筆 到 第 <?php echo ($page2)*30;?> 筆，總共 <?php echo $totalProData;?> 筆</div>
                    </div>
                    <br />


                  <table id="example" class="table bulk_action table-striped responsive-utilities jambo_table">
                    <thead>
                      <tr class="headings">
                        <th>上架編號 </th>
                        <th>分類</th>
                        <th>品牌 </th>
                        <th>商品名稱 </th>
                        <th>型號 </th>
                        <th>規格 </th>
                        <th>供應價 </th>
                        <th>上架日期 </th>
                        <th>上架狀態 </th>
                        <th style="color:#FF2D2D;">分期基礎價 </th>
                        <th style="color:#FF2D2D;">是否可直購 </th>
                        <th style="color:#FF2D2D;">直購總金額 </th>
                        <th style="color:#FF2D2D;">最新商品 </th>
                        <th style="color:#FF2D2D;">精選熱賣 </th>
                        <th style="color:#FF2D2D;">限時優惠 </th>
                        <th style="color:#FF2D2D;">灌水數 </th>
                        <th style="color:#FF2D2D;">實際購買人數 </th>
                        <th style="color:#FF2D2D;">商品點擊數 </th>
                      </tr>
                    </thead>
                    <tbody>
                    <?php 
	                    if($allPMData != null){
	                    	foreach($allPMData as $key=>$value){
	                    		$proData = $pro->getOneProByNo($value["proNo"]);
	                    		$supData = $sup->getOneSupplierByNo($value["supNo"]);
	                    		$braData = $bra->getOneBrandByNo($proData[0]["braNo"]);
	                    		$catData = $cat->getOneCatByNo($proData[0]["catNo"]);
	                    		$pm->changeToReadable($value);
                    ?>
                      <tr class="pointer">
                      	<td class=" "><?php echo $value["pmNo"]; ?></td>
                      	<td class=" "><?php echo $catData[0]["catName"]; ?></td>
                      	<td class=" "><?php echo $braData[0]["braName"]; ?></td>
                        <td class=" "><a target="_blank" style="color:blue;text-decoration:underline;" href="?page=product&type=product&action=view&prono=<?php echo $proData[0]["proNo"]; ?>"><?php echo $proData[0]["proName"]; ?></td>
                        <td class=" "><?php echo $proData[0]["proModelID"]; ?></td>
                        <td class=" "><?php echo $proData[0]["proSpec"]; ?></td>
                        <td class=" "><?php echo number_format($value["pmSupPrice"]); ?></td>
                        <td class=" "><?php echo $value["pmUpDate"]; ?></td>
                        <td class=" "><?php echo $value["pmStatus"]; ?></td>
                        <td class=" "><?php echo number_format($value["pmPeriodAmnt"]); ?></td>
                        <td class=" "><?php echo $value["pmIfDirect"]; ?></td>
                        <td class=" "><?php echo number_format($value["pmDirectAmnt"]); ?></td>
                        <td class=" "><?php echo $value["pmNewest"]; ?></td>
                        <td class=" "><?php echo $value["pmSpecial"]; ?></td>
                        <td class=" "><?php echo $value["pmHot"]; ?></td>
                        <td class=" "><?php echo $value["pmPopular"]; ?></td>
                        <td class=" "><?php echo $value["pmBuyAmnt"]; ?></td>
                        <td class=" last"><?php echo $value["pmClickNum"]; ?></td>
                      </tr>
                     <?php 
	                    	}
                    	}
                     ?>
                    </tbody>
                  </table>
				  <table style="display:none;" id="example2" class="table bulk_action table-striped responsive-utilities jambo_table">
                    <thead>
                      <tr class="headings">
                        <th>上架編號 </th>
                        <th>分類</th>
                        <th>品牌 </th>
                        <th>商品名稱 </th>
                        <th>型號 </th>
                        <th>規格 </th>
                        <th>供應價 </th>
                        <th>上架日期 </th>
                        <th>上架狀態 </th>
                        <th style="color:#FF2D2D;">分期基礎價 </th>
                        <th style="color:#FF2D2D;">是否可直購 </th>
                        <th style="color:#FF2D2D;">直購總金額 </th>
                        <th style="color:#FF2D2D;">最新商品 </th>
                        <th style="color:#FF2D2D;">精選熱賣 </th>
                        <th style="color:#FF2D2D;">限時優惠 </th>
                        <th style="color:#FF2D2D;">灌水數 </th>
                        <th style="color:#FF2D2D;">實際購買人數 </th>
                        <th style="color:#FF2D2D;">商品點擊數 </th>
                      </tr>
                    </thead>
                    <tbody>
                    <?php 
	                    if($allPMData != null){
	                    	foreach($allPMData as $key=>$value){
	                    		$proData = $pro->getOneProByNo($value["proNo"]);
	                    		$supData = $sup->getOneSupplierByNo($value["supNo"]);
	                    		$braData = $bra->getOneBrandByNo($proData[0]["braNo"]);
	                    		$catData = $cat->getOneCatByNo($proData[0]["catNo"]);
	                    		$pm->changeToReadable($value);
                    ?>
                      <tr class="pointer">
                      	<td class=" "><?php echo $value["pmNo"]; ?></td>
                      	<td class=" "><?php echo $catData[0]["catName"]; ?></td>
                      	<td class=" "><?php echo $braData[0]["braName"]; ?></td>
                        <td class=" "><a target="_blank" style="color:blue;text-decoration:underline;" href="https://happyfan7.com/?item=product&pro=<?php echo $proData[0]["proNo"]; ?>"><?php echo $proData[0]["proName"]; ?></td>
                        <td class=" "><?php echo $proData[0]["proModelID"]; ?></td>
                        <td class=" "><?php echo $proData[0]["proSpec"]; ?></td>
                        <td class=" "><?php echo number_format($value["pmSupPrice"]); ?></td>
                        <td class=" "><?php echo $value["pmUpDate"]; ?></td>
                        <td class=" "><?php echo $value["pmStatus"]; ?></td>
                        <td class=" "><?php echo number_format($value["pmPeriodAmnt"]); ?></td>
                        <td class=" "><?php echo $value["pmIfDirect"]; ?></td>
                        <td class=" "><?php echo number_format($value["pmDirectAmnt"]); ?></td>
                        <td class=" "><?php echo $value["pmNewest"]; ?></td>
                        <td class=" "><?php echo $value["pmSpecial"]; ?></td>
                        <td class=" "><?php echo $value["pmHot"]; ?></td>
                        <td class=" "><?php echo $value["pmPopular"]; ?></td>
                        <td class=" "><?php echo $value["pmBuyAmnt"]; ?></td>
                        <td class=" last"><?php echo $value["pmClickNum"]; ?></td>
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
		    tab_text = tab_text + '<x:Name>'+$("#whichSup option:selected").text()+'</x:Name>';
		    tab_text = tab_text + '<x:WorksheetOptions><x:Panes></x:Panes></x:WorksheetOptions></x:ExcelWorksheet>';
		    tab_text = tab_text + '</x:ExcelWorksheets></x:ExcelWorkbook></xml></head><body>';
		    tab_text = tab_text + "<table x:str border='1px'>";
		    tab_text = tab_text + $("#example2").html();
		    tab_text = tab_text + '</table></body></html>';
			window.open('data:application/vnd.ms-excel,' + encodeURIComponent(tab_text));
			e.preventDefault();
		});

		//查詢
		$("#query").click(function(){
			var url = "?page=supplier&action=productList";
			for(var i=0; i<$(".with-name").not($("#or-sup-area").children(".with-name")).length; i++){
				url = url + "&" + $(".with-name").eq(i).attr("name") + "=" + $(".with-name").eq(i).val();
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
          "paging": false,
          "processing": true,
        "oLanguage": {
          "sSearch": "搜尋: "
        },
        // 'iDisplayLength': 100,
        "sPaginationType": "full_numbers"
      })<?php if(isset($_GET["pageIndex"]) && $_GET["pageIndex"]=='last') echo ".fnPageChange( 'last' );$(window).scrollTop($(document).height())";?>;
        $('#example_info').hide();

        $('.bottom').next('.dataTables_info').hide();

        $('.choosePage').change(function () {
            location.href = $(this).data('href') + '&paginate=' + $(this).val();
        });

        // search
        $('#example_filter').find('input[type=search]').val('<?php echo $search;?>');
        $('#example_filter').find('input[type=search]').change(function() {
            var keyword = $(this).val();
            var url = 'admin.php?page=supplier&action=productList<?php if(isset($_GET["supno"]))echo '&supno='.$_GET["supno"];?>&paginate=<?php echo $page2;?>&search=';
            location.href = url + keyword;
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