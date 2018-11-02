<?php 
require_once('model/require_general.php');


$page = isset($_GET["paginate"])? $_GET["paginate"] : 1;
$search = isset($_GET["search"])? $_GET["search"] : null;


$pm = new Product_Manage();
$pro = new Product();
$key = "";
$col = "";
$ifIsCol = "";

$pmData = $pm->getAllPMGroupByProName( ($page-1)*30 , 30 ,$search);
$totalProData = $pm->getAllPMGroupByProNameCount($search);
$lastPage = ceil($totalProData/30);


switch (@$_GET["special"]){
	case "new":
		$key = "最新";
		$col = "pmNewestOrder";
		$ifIsCol = "pmNewest";
		break;
	case "special":
		$key = "精選";
		$col = "pmSpecialOrder";
		$ifIsCol = "pmSpecial";
		break;
	case "hot":
		$key = "限時";
		$col = "pmHotOrder";
		$ifIsCol = "pmHot";
		break;
}


?>
<style>
#insert-area>*{
	margin:5px 0;
}
</style>
<!-- page content -->
      <div class="right_col" role="main">
        <div class="">
          <div class="page-title">
            <div class="title_left">
              <h3>編輯"<?php echo $key; ?>"排序</h3>
              <button id="submit" class="btn btn-success">確認儲存</button>
            </div>
          </div>
          <div class="clearfix"></div>

          <div class="row">
			
            <div class="col-md-12 col-sm-12 col-xs-12">
              <div class="x_panel">
                <div class="x_content">


                    <div class="top">
                        <div class="dataTables_paginate paging_full_numbers" id="example_paginate">
                            <a href="admin.php?page=product&type=productManage<?php if(isset($_GET["special"]))echo '&special='.$_GET["special"];?>&paginate=1&search=<?php echo $search;?>" class="paginate_button first disabled" aria-controls="example" data-dt-idx="0" tabindex="0" id="example_first">
                                第一頁
                            </a>
                            <?php if ($page>1){ ?>
                            <a href="admin.php?page=product&type=productManage<?php if(isset($_GET["special"]))echo '&special='.$_GET["special"];?>&paginate=<?php echo $page-1;?>&search=<?php echo $search;?>" class="paginate_button previous disabled" aria-controls="example" data-dt-idx="1" tabindex="0" id="example_previous">
                                前一頁
                            </a>
                            <?php } ?>
                            <span>
                                <select class="paginate_button choosePage" data-href="admin.php?page=product&type=productManage<?php if(isset($_GET["special"]))echo '&special='.$_GET["special"];?>&search=<?php echo $search;?>">
                                <?php for ($i=1;$i<=$lastPage;$i++){ ?>
                                    <option value="<?php echo $i;?>" <?php if($page==$i)echo 'selected';?> >
                                        <?php echo $i;?>
                                    </option>
                                <?php } ?>
                                </select>
                                <!--                                <a  href="&paginate=2" class="paginate_button " aria-controls="example" data-dt-idx="3" tabindex="0">2</a>-->
                                <!--                                <a  href="&paginate=3" class="paginate_button " aria-controls="example" data-dt-idx="4" tabindex="0">3</a>-->
                            </span>
                            <?php if ($page<$lastPage){ ?>
                            <a href="admin.php?page=product&type=productManage<?php if(isset($_GET["special"]))echo '&special='.$_GET["special"];?>&paginate=<?php echo $page+1;?>&search=<?php echo $search;?>" class="paginate_button next" aria-controls="example" data-dt-idx="5" tabindex="0" id="example_next">
                                下一頁
                            </a>
                            <?php } ?>
                            <a href="admin.php?page=product&type=productManage<?php if(isset($_GET["special"]))echo '&special='.$_GET["special"];?>&paginate=<?php echo $lastPage;?>&search=<?php echo $search;?>" class="paginate_button last" aria-controls="example" data-dt-idx="6" tabindex="0" id="example_last">
                                最後一頁
                            </a>
                        </div>
                    </div>
                    <div class="top">
                        <div class="dataTables_info" id="example_info2" role="status" aria-live="polite">顯示 第 <?php echo ($page-1)*30+1;?> 筆 到 第 <?php echo ($page)*30;?> 筆，總共 <?php echo $totalProData;?> 筆</div>
                    </div>
                    <br />



                <form>
<!--                    <div  style="overflow-x:scroll; ">-->
                  <table id="example" class="table table-striped responsive-utilities jambo_table">
                    <thead>
                      <tr class="headings">
                        <th>商品名稱</th>
                        <th>是否為<?php echo $key; ?></th>
<!--                        <th>排序 </th>-->
                      </tr>
                    </thead>
                    <tbody>
                    <?php 
	                    if($pmData != null){
	                    	foreach($pmData as $key=>$value){
	                    		$proData = $pro->getOneProByNo($value["proNo"]);
                                ?>
                                  <tr class="pointer">
                                    <td class=" "><input type="hidden" name="proNo[]" value="<?php echo $proData[0]["proNo"]; ?>"><?php echo $proData[0]["proName"]; ?></td>
                                    <td class=" ">
                                        <span style="display:none;"><?php echo $value[$ifIsCol]; ?></span>
                                        <input type="checkbox" class="checked-one" name="<?php echo $ifIsCol."[]"; ?>" <?php if($value[$ifIsCol] == 1) echo "checked=true"; ?> value="1">
                                        <input style="display:none;" type="checkbox" class="unchecked-one" name="<?php echo $ifIsCol."[]"; ?>" <?php if($value[$ifIsCol] == 0) echo "checked=true"; ?> value="0">
                                    </td>
<!--                                    <td class=" "><span style="display:none;">--><?php //echo $value[$col]; ?><!--</span><input type="text" name="--><?php //echo $col."[]"; ?><!--" value="--><?php //echo $value[$col]; ?><!--"></td>-->
                                  </tr>
                                 <?php
                    		}
                    	}
                     ?>
                    </tbody>
                  </table>
<!--                    </div>-->

                </form>
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
  	$(function(){
  		$("#submit").click(function(){
  			$.ajax({
  				url:"ajax/productManage/edit_order.php",
  				type:"post",
  				data:$("form").serialize(),
  				success:function(result){
  					alert(result);
  					location.reload();
  				}
  			});
  	  	});

  	  	//1/0切換
  	  	$(document).on("change",".checked-one",function(){
  	  	  	if($(this).is(":checked")){
				$(this).siblings(".unchecked-one").prop("checked",false);
  	  	  	}else{
  	  	  		$(this).siblings(".unchecked-one").prop("checked",true);
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
      var oTable = $('#example').dataTable({
          "paging": false,
          "processing": true,
          "oLanguage": {
              "sSearch": "<button class='key_search'>搜尋</button>"
          },
          "sPaginationType": "full_numbers"
      })<?php if(isset($_GET["pageIndex"]) && $_GET["pageIndex"]=='last') echo ".fnPageChange( 'last' );$(window).scrollTop($(document).height())";?>;

        $('#example_info').hide();
        $('.bottom').next('.dataTables_info').hide();

        $('.choosePage').change(function () {
            location.href = $(this).data('href') + '&paginate=' + $(this).val();
        });

        // search
        $('#example_filter').find('input[type=search]').val('<?php echo $search;?>');
        $('.key_search').click(function(e) {
            var keyword = $('#example_filter').find('input[type=search]').val();
            var url = 'admin.php?page=product&type=productManage<?php if(isset($_GET["special"]))echo '&special='.$_GET["special"];?>&paginate=<?php echo $page;?>&search=';
            location.href = url+keyword;
            e.preventDefault();
            return false;
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