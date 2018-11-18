<?php 
require_once('model/require_general.php');

$pp = new Product_Period();
$pm = new Product_Manage();
$allData = $pm->getAllPMGroupByProName();

$pro = new Product();

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
                  <table id="example" class="table table-striped responsive-utilities jambo_table">
                    <thead>
                      <tr class="headings">
                        <th>商品編號</th>
                        <th>商品名稱</th>                     
						<th>上架狀態</th>         
                        <th>實際下單數 </th>
                        <th>商品點擊數 </th>
                        <!-- <th>上架狀態</th>  -->
                      </tr>
                    </thead>
                    <tbody>
                    <?php 
                
	                    if($allData != null){
	                    	foreach($allData as $key=>$value){
                            if($value["pmBySup"]==1)
                            {
                                if ($value["proNo"]=='')continue;

	                    		$proData = $pro->getOneProByNo($value["proNo"]);
	                    		if ($proData[0]["proNo"]=='')continue;

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
                        <td class=" "><a style="text-decoration:underline;color:blue;" href="?page=product&type=productManage&action=view&prono=<?php echo $proData[0]["proNo"]; ?>"><span style="display:none;"><?php echo $proData[0]["proNo"]; ?></span>
                              <?php 
                                    if(trim($proData[0]["proName"])=="")                                    
                                    {
                                        echo "未命名" ;
                                    }
                                    else
                                    {
                                          echo $proData[0]["proName"]; 
                                    }
                            ?>
                       </a></td>
                      
                        <td class=" "><?php echo $value["pmStatus"]; ?></td>	
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
        "oLanguage": {
          "sSearch": "搜尋: "
        },
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