<?php 
require_once('model/require_general.php');

$cat = new Category();
$pro = new Product();
$bra = new Brand();


$page = isset($_GET["paginate"])? $_GET["paginate"] : 1;
$allProData = $pro->getAllPro( ($page-1)*30 , 30 );
$totalProData = $pro->getAllProCount();
$lastPage = ceil($totalProData/30);

if(isset($_GET["catname"]) && $_GET["catname"] != "all"){
	$allProData = $pro->getAllProByCatName($_GET["catname"], ($page-1)*30 , 30);
    $pro->getAllProByCatNameCount($_GET["catname"]);
    $totalProData = $pro->db->iNoOfRecords;
    $lastPage = ceil($totalProData/30);
}
if(isset($_GET["braname"]) && $_GET["braname"] != "all"){
	$allProData = $pro->getAllProByBraName($_GET["braname"], ($page-1)*30 , 30);
    $pro->getAllProByBraNameCount($_GET["braname"]);
    $totalProData = $pro->db->iNoOfRecords;
    $lastPage = ceil($totalProData/30);
}



$allCatData = $cat->getAllCat();
$allBraData = $bra->getAllBrand();

?>
<!-- page content -->
      <div class="right_col" role="main">
        <div class="">
          <div class="page-title">
            <div class="title_left">
              <h3>商品列表</h3>
              <a style="text-decoration:none;" href="?page=product&type=product&action=insert">
                <button class="btn btn-success">新增商品</button>
              </a>
            </div>
          </div>
          <div class="clearfix"></div>

          <div class="row">

            <div class="col-md-12 col-sm-12 col-xs-12">
              <div class="x_panel">
                <div class="x_content">
                  <div style="padding:10px 0 20px 0;">
                  	<label>分類依據： </label>
                  	<select id="which" name="which">
                  		<option value="all">全部</option>
                  		<option <?php if(isset($_GET["catname"])) echo "selected"; ?> value="name">分類</option>
                  		<option <?php if(isset($_GET["braname"])) echo "selected"; ?> value="brand">品牌</option>
                  	</select>
                  	<span id="choose-area" style="display:inline-block;margin-left:10px;<?php if(!isset($_GET["catname"]) && !isset($_GET["braname"])){ ?>display:none;<?php }?>">
	                  	<label>請選擇：</label>
	                  	<select id="choose-cat">
	                  		<?php
	                  			if(isset($_GET["catname"])){
	                  				foreach($allCatData as $key=>$value){
	                  		?>
	                  			<option <?php if($_GET["catname"] == $value["catName"]) echo "selected" ?> value="<?php echo $value["catName"]; ?>"><?php echo $value["catName"]; ?></option>
	                  		<?php
	                  				}
	                  			}else if(isset($_GET["braname"])){
	                  				foreach($allBraData as $key=>$value){
	                  		?>
	                  			<option <?php if($_GET["braname"] == $value["braName"]) echo "selected" ?> value="<?php echo $value["braName"]; ?>"><?php echo $value["braName"]; ?></option>
	                  		<?php
	                  				}
	                  			}
	                  		?>
	                  	</select>
                  	</span>
                  </div>


                    <div class="top">
                        <div class="dataTables_paginate paging_full_numbers" id="example_paginate">
                            <a href="admin.php?page=product&type=product<?php if(isset($_GET["catname"]))echo '&catname='.$_GET["catname"];?><?php if(isset($_GET["braname"]))echo '&braname='.$_GET["braname"];?>&paginate=1" class="paginate_button first disabled" aria-controls="example" data-dt-idx="0" tabindex="0" id="example_first">
                                    第一頁
                            </a>
                            <a href="admin.php?page=product&type=product<?php if(isset($_GET["catname"]))echo '&catname='.$_GET["catname"];?><?php if(isset($_GET["braname"]))echo '&braname='.$_GET["braname"];?>&paginate=<?php echo $page-1;?>" class="paginate_button previous disabled" aria-controls="example" data-dt-idx="1" tabindex="0" id="example_previous">
                                    前一頁
                            </a>
                            <span>
                                <select class="paginate_button choosePage" data-href="admin.php?page=product&type=product<?php if(isset($_GET["catname"]))echo '&catname='.$_GET["catname"];?><?php if(isset($_GET["braname"]))echo '&braname='.$_GET["braname"];?>">
                                <?php for ($i=1;$i<$lastPage;$i++){ ?>
                                    <option value="<?php echo $i;?>" <?php if($page==$i)echo 'selected';?> >
                                        <?php echo $i;?>
                                    </option>
                                <?php } ?>
                                </select>
<!--                                <a  href="&paginate=2" class="paginate_button " aria-controls="example" data-dt-idx="3" tabindex="0">2</a>-->
<!--                                <a  href="&paginate=3" class="paginate_button " aria-controls="example" data-dt-idx="4" tabindex="0">3</a>-->
                            </span>
                            <a href="admin.php?page=product&type=product<?php if(isset($_GET["catname"]))echo '&catname='.$_GET["catname"];?><?php if(isset($_GET["braname"]))echo '&braname='.$_GET["braname"];?>&paginate=<?php echo $page+1;?>" class="paginate_button next" aria-controls="example" data-dt-idx="5" tabindex="0" id="example_next">
                                    下一頁
                            </a>
                            <a href="admin.php?page=product&type=product<?php if(isset($_GET["catname"]))echo '&catname='.$_GET["catname"];?><?php if(isset($_GET["braname"]))echo '&braname='.$_GET["braname"];?>&paginate=<?php echo $lastPage;?>" class="paginate_button last" aria-controls="example" data-dt-idx="6" tabindex="0" id="example_last">
                                    最後一頁
                            </a>
                        </div>
                    </div>
                    <div class="top">
                        <div class="dataTables_info" id="example_info2" role="status" aria-live="polite">顯示 第 <?php echo ($page-1)*30+1;?> 筆 到 第 <?php echo ($page)*30;?> 筆，總共 <?php echo $totalProData;?> 筆</div>
                    </div>
                    <br />

                  <table id="example" class="table table-striped responsive-utilities jambo_table">
                    <thead>
                      <tr class="headings">
                        <th>商品編號 </th>
                        <th>分類 </th>
                        <th>品牌</th>
                        <th>名稱 </th>
                        <th>規格 </th>
                        <th class=" no-link last"><span class="nobr">詳細資訊</span></th>
                      </tr>
                    </thead>
                    <tbody>
                    <?php
	                    if($allProData != null){
	                    	foreach($allProData as $key=>$value){
	                    		$cateName = $cat->getOneCatByNo($value["catNo"]);
	                    		$braName = $bra->getOneBrandByNo($value["braNo"]);
                                ?>
                                  <tr class="pointer">
                                    <td class=" "><?php echo $value["proCaseNo"]; ?></td>
                                    <td class=" "><?php echo $cateName[0]["catName"]; ?></td>
                                    <td class=" "><?php echo $braName[0]["braName"]; ?></td>
                                    <td class=" "><?php echo $value["proName"]; ?></td>
                                    <td class=" "><?php echo isset($value["proSpec"])? $pro->changeSign($value["proSpec"],"<br>") : ''; ?></td>
                                    <td class=" last">
                                        <a href="?page=product&type=product&action=view&prono=<?php echo $value["proNo"]; ?>">
                                            <button style="background-color:#FFF;border:1px solid #CCC;" class="btn btn-defult view-details">
                                                詳細資訊/編輯
                                            </button>
                                        </a>
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

                    <div class="dataTables_info" id="example_info2" role="status" aria-live="polite">顯示 第 <?php echo ($page-1)*30+1;?> 筆 到 第 <?php echo ($page)*30;?> 筆，總共 <?php echo $totalProData;?> 筆</div>

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

<!--  <link href="https://cdn.datatables.net/1.10.19/css/jquery.dataTables.min.css">-->
<!--  <script src="https://code.jquery.com/jquery-1.9.1.js"></script>-->
<!--  <script src="https://cdn.datatables.net/1.10.19/js/jquery.dataTables.min.js"></script>-->


  <!-- pace -->
  <script src="js/pace/pace.min.js"></script>
  <script>
  	$(function(){
		$("#which").change(function(){
			var whichVal = $("#which option:selected").val();
			if(whichVal == "name"){
				$("#choose-cat option").remove();
				$("#choose-cat").append("<option selected disabled value=''>請選擇</option>");
				<?php 
					$dataArr = array();
					foreach($allCatData as $key=>$value){ 
						array_push($dataArr,$value["catName"]);
					}
					foreach(array_unique($dataArr) as $value){
				?>
						$("#choose-cat").append("<option value='<?php echo $value; ?>'><?php echo $value; ?></option>");
				<?php 	
					}	
				?>
				$("#choose-area").show();
			}else if(whichVal == "brand"){
				$("#choose-cat option").remove();
				$("#choose-cat").append("<option selected disabled value=''>請選擇</option>");
				<?php 
					$dataArr = array();
					foreach($allBraData as $key=>$value){ 
						array_push($dataArr,$value["braName"]);
					}
					foreach(array_unique($dataArr) as $value){
				?>
						$("#choose-cat").append("<option value='<?php echo $value; ?>'><?php echo $value; ?></option>");
				<?php 	
					}	
				?>
				$("#choose-area").show();
			}else{
				location.href="?page=product&type=product";
			}
		});
		$("#choose-cat").change(function(){
			var nameOrBrand = $("#which").val();
			var whichVal = $("#choose-cat").val();
			if(nameOrBrand == "name"){
				location.href="?page=product&type=product&catname="+whichVal;
			}else{
				location.href="?page=product&type=product&braname="+whichVal;
			}
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

        /* BASIC ;*/
        // var oTable = $('#dt_basic').dataTable({
        //     "serverSide": true,
        //     "stateSave": true,
        //     "scrollX": true,
        //     "scrollY": '60vh',
        //     'bProcessing': true,
        //     'sServerMethod': 'GET',
        //     "aoColumns": [
        //         {
        //             "sTitle": "商品編號",
        //             "mData": "proCaseNo",
        //             "width": "40px",
        //             "sName": "proCaseNo",
        //             "bSearchable": false,
        //             "mRender": function (data, type, row) {
        //                 return data;
        //             }
        //         },
        //         // {
        //         //     "sTitle": "分類",
        //         //     "mData": "catName",
        //         //     "width": "40px",
        //         //     "sName": "catName",
        //         //     "bSearchable": false,
        //         //     "mRender": function (data, type, row) {
        //         //         if ( type === 'display' ) {
        //         //             return row.catName;
        //         //         }
        //         //     }
        //         // },
        //         // {"sTitle": "品牌", "mData": "braName", "width": "80px", "sName": "braName"},
        //         {"sTitle": "名稱", "mData": "proName", "width": "40px", "sName": "proName"},
        //         // {"sTitle": "規格", "mData": "proSpec", "width": "120px", "sName": "proSpec"},
        //         {
        //             "sTitle": "詳細資訊",
        //             "bSortable": false,
        //             "bSearchable": false,
        //             "width": '140px',
        //             "mRender": function (data, type, row) {
        //                 var btn = "無功能";
        //                 btn = '<a href="?page=product&type=product&action=view&prono='+ row.proNo + '">' +
        //                     '<button style="background-color:#FFF;border:1px solid #CCC;" class="btn btn-defult view-details">' +
        //                     '詳細資訊/編輯' +
        //                     '</button>' +
        //                     '</a>';
        //                 return btn;
        //             }
        //         },
        //     ],
        //     "sAjaxSource": 'ajax/product/server_processing.php',
        //     "ajax": 'ajax/product/server_processing.php',
        //     // "sDom": "<'dt-toolbar'<'col-xs-12 col-sm-6'f><'col-sm-6 col-xs-12 hidden-xs'l>r>" +
        //     //     "t" +
        //     //     "<'dt-toolbar-footer'<'col-sm-6 col-xs-12 hidden-xs'i><'col-xs-12 col-sm-6'p>>",
        //     "autoWidth": true,
        //     "oLanguage": {
        //         "sSearch": "搜尋: "
        //     },
        // });
        /* END BASIC */

        var oTable = $('#example').dataTable({
            "paging": false,
            "processing": true,
          "oLanguage": {
            "sSearch": "搜尋: "
          },
          "sPaginationType": "full_numbers"
        })<?php if(isset($_GET["pageIndex"]) && $_GET["pageIndex"]=='last') echo ".fnPageChange( 'last' );$(window).scrollTop($(document).height())";?>;

        $('#example_info').hide();
        $('.bottom').next('.dataTables_info').hide();

        $('.choosePage').change(function () {
           location.href = $(this).data('href') + '&paginate=' + $(this).val();
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