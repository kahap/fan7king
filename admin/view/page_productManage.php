<?php 
require_once('model/require_general.php');


$page = isset($_GET["paginate"])? $_GET["paginate"] : 1;


$pp = new Product_Period();
$pm = new Product_Manage();
$allData = $pm->getAllPMGroupByProName(($page-1)*30 , 30);
$totalProData = $pm->getAllPMGroupByProNameCount();
$lastPage = ceil($totalProData/30);

$pro = new Product();
$sup = new Supplier();

?>
<!-- page content -->
      <div class="right_col" role="main">
        <div class="">
          <div class="page-title">
            <div class="title_left">
              <h3>上架列表</h3>
              <a style="text-decoration:none;" href="?page=product&type=productManage&action=insert">
                <button class="btn btn-success">新增上架</button>
              </a>
            </div>
          </div>
          <div class="clearfix"></div>

          <div class="row">

            <div class="col-md-12 col-sm-12 col-xs-12">
              <div class="x_panel">
                <div class="x_content">

                    <div class="top">
                        <div class="dataTables_paginate paging_full_numbers" id="example_paginate">
                            <a href="admin.php?page=product&type=productManage<?php if(isset($_GET["catname"]))echo '&catname='.$_GET["catname"];?><?php if(isset($_GET["braname"]))echo '&braname='.$_GET["braname"];?>&paginate=1" class="paginate_button first disabled" aria-controls="example" data-dt-idx="0" tabindex="0" id="example_first">
                                第一頁
                            </a>
                            <?php if ($page>1){ ?>
                            <a href="admin.php?page=product&type=productManage<?php if(isset($_GET["catname"]))echo '&catname='.$_GET["catname"];?><?php if(isset($_GET["braname"]))echo '&braname='.$_GET["braname"];?>&paginate=<?php echo $page-1;?>" class="paginate_button previous disabled" aria-controls="example" data-dt-idx="1" tabindex="0" id="example_previous">
                                前一頁
                            </a>
                            <?php } ?>
                            <span>
                                <select class="paginate_button choosePage" data-href="admin.php?page=product&type=productManage<?php if(isset($_GET["catname"]))echo '&catname='.$_GET["catname"];?><?php if(isset($_GET["braname"]))echo '&braname='.$_GET["braname"];?>">
                                <?php for ($i=1;$i<$lastPage;$i++){ ?>
                                    <option value="<?php echo $i;?>" <?php if($page==$i)echo 'selected';?> >
                                        <?php echo $i;?>
                                    </option>
                                <?php } ?>
                                </select>
                                <!--                                <a  href="&paginate=2" class="paginate_button " aria-controls="example" data-dt-idx="3" tabindex="0">2</a>-->
                                <!--                                <a  href="&paginate=3" class="paginate_button " aria-controls="example" data-dt-idx="4" tabindex="0">3</a>-->
                            </span>
                            <a href="admin.php?page=product&type=productManage<?php if(isset($_GET["catname"]))echo '&catname='.$_GET["catname"];?><?php if(isset($_GET["braname"]))echo '&braname='.$_GET["braname"];?>&paginate=<?php echo $page+1;?>" class="paginate_button next" aria-controls="example" data-dt-idx="5" tabindex="0" id="example_next">
                                下一頁
                            </a>
                            <a href="admin.php?page=product&type=productManage<?php if(isset($_GET["catname"]))echo '&catname='.$_GET["catname"];?><?php if(isset($_GET["braname"]))echo '&braname='.$_GET["braname"];?>&paginate=<?php echo $lastPage;?>" class="paginate_button last" aria-controls="example" data-dt-idx="6" tabindex="0" id="example_last">
                                最後一頁
                            </a>
                        </div>
                    </div>
                    <div class="top">
                        <div class="dataTables_info" id="example_info2" role="status" aria-live="polite">顯示 第 <?php echo ($page-1)*30+1;?> 筆 到 第 <?php echo ($page)*30;?> 筆，總共 <?php echo $totalProData;?> 筆</div>
                    </div>
                    <br />


                    <div  style="overflow-x:scroll; ">
                    <table id="example" class="table table-striped responsive-utilities jambo_table">
                    <thead>
                      <tr class="headings">
                        <th>商品編號</th>
                        <th>商品名稱</th>
                        <th>商品利率</th>
						<th>上架狀態</th>
                        <th>最新 </th>
                        <th>精選 </th>
                        <th>限時 </th>
                        <th>灌水數 </th>
                        <th>實際下單數 </th>
                        <th>商品點擊數 </th>
                        <!-- <th>上架狀態</th>  -->
                      </tr>
                    </thead>
                    <tbody>
                    <?php 
	                    if($allData != null){
	                    	foreach($allData as $key=>$value){
	                    		$proData = $pro->getOneProByNo($value["proNo"]);
	                    		$ppData = $pp->getPPByProduct($value["proNo"]);
								$emptyPp = true;
								if($ppData != null){
									foreach($ppData as $keyIn=>$valueIn){
										if($valueIn["ppPercent"] != ""){
											$emptyPp = false;
											break;
										}
									}
								}
	                    		$pm->changeToReadable($value);
                                ?>
                                  <tr class="pointer">
                                    <td class=" "><a style="text-decoration:underline;color:blue;" href="?page=product&type=productManage&action=view&prono=<?php echo $proData[0]["proNo"]; ?>"><?php echo $proData[0]["proCaseNo"]; ?></a></td>
                                    <td class=" "><span style="display:none;"><?php echo $proData[0]["proNo"]; ?></span><?php echo $proData[0]["proName"]; ?></td>
                                    <td class=" ">
                                    <?php
                                    if($ppData != null && !$emptyPp){
                                    ?>
                                    <table style="text-align:center;">
                                      <tr>
                                        <th style="padding:5px 10px;">期數</th>
                                        <th style="padding:5px 10px;">利率倍數</th>
                                      </tr>
                                    <?php
                                        foreach($ppData as $keyIn=>$valueIn){
                                    ?>
                                      <tr>
                                        <td><?php echo $valueIn["ppPeriodAmount"]; ?></td>
                                        <td><?php echo $valueIn["ppPercent"]; ?></td>
                                      </tr>
                                    <?php
                                        }
                                    ?>
                                    </table>
                                    <?php
                                    }else{
                                        echo "該商品尚未設定獨立利率，將參照利率基本表";
                                    }
                                    ?>
                                    </td>
                                    <td class=" "><?php echo $value["pmStatus"]; ?></td>
                                    <td class=" ">
                                        <span style="display:none;"><?php echo $value["pmNewest"]; ?></span>
                                        <input type="checkbox" class="status" name="pmNewest" <?php if($value["pmNewest"] == "是") echo "checked=true"; ?>>
                                    </td>
                                    <td class=" ">
                                        <span style="display:none;"><?php echo $value["pmSpecial"]; ?></span>
                                        <input type="checkbox" class="status" name="pmSpecial" <?php if($value["pmSpecial"] == "是") echo "checked=true"; ?>>
                                    </td>
                                    <td class=" ">
                                        <span style="display:none;"><?php echo $value["pmHot"]; ?></span>
                                        <input type="checkbox" class="status" name="pmHot" <?php if($value["pmHot"] == "是") echo "checked=true"; ?>>
                                    </td>
                                    <td class=" ">
                                        <span><?php echo $value["pmPopular"]; ?></span>
                                        <button style="float:right;" class="btn btn-info change-popular">
                                            編輯
                                        </button>
                                    </td>
                                    <td class=" "><span><?php echo number_format($value["pmBuyAmnt"]); ?></span></td>
                                    <td class=" "><?php echo number_format($value["pmClickNum"]); ?></td>
                                    <!-- <td class=" "><?php //echo $value["pmStatus"]; ?></td> -->
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
                    </div>

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
  		var curPopNum;
  		var curPopVal;
  		var curProName;
  		
  		//編輯灌水數
		$(document).on("click",".change-popular",function(e){
			e.preventDefault();
			curProName = $(this).parent().parent().children().eq(1).children("span").text();
			curPopNum = $(this).parent().parent().children().eq(6);
			if(curPopNum.children("input").length == 0){
				curPopNum.siblings("button").remove();
				curPopVal = curPopNum.children("span").text();
				curPopNum.html('<input size="10" autofocus="true" class="lgIdNum" name="lgIdNum" value="'+curPopVal+'">'+
						'<button style="margin-left:15px;" class="btn btn-success confirm-change">確定</button>');
				curPopNum.children("input").select();
			}
		});
		$(document).on("click",".confirm-change",function(){
			var cur = $(this);
			data = {"proNo":curProName,"pmPopular":cur.siblings("input").val()};
			$.post("ajax/productManage/edit_popular.php", data, function(result){
				if(result.indexOf("成功") != -1){
					alert(result.split(" ")[0]);
					cur.parent().html('<span>'+result.split(" ")[1]+'</span>'+
                        	'<button style="float:right;" class="btn btn-info change-popular">'+
                    		'編輯'+
                    		'</button>');
				}else{
					alert(result);
				}
			});
		});

		//編輯最新/熱門/精選
		$(document).on("change",".status",function(){
			var cur = $(this);
			var curName = cur.attr("name");
			var curPro = cur.parent().parent().children().eq(1).children("span").text();
			var curVal;
			if(cur.is(":checked")){
				curVal = 1;
			}else{
				curVal = 0;
			}
			var data;
			if(curName == "pmNewest"){
				data = {"proNo":curPro, "pmNewest":curVal};
			}else if(curName == "pmHot"){
				data = {"proNo":curPro, "pmHot":curVal};
			}else if(curName == "pmSpecial"){
				data = {"proNo":curPro, "pmSpecial":curVal};
			}
			$.post("ajax/productManage/edit_status.php", data, function(result){
				if(result.indexOf("成功") != -1){
					alert(result);
				}else{
					alert(result);
				}
			});
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