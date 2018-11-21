<?php 
require_once('model/require_general.php');

$pm = new Product_Manage();
$pro = new Product();
$key = "";
$col = "";
$ifIsCol = "";
$pmData = $pm->getAllPMGroupByProName();
switch ($_GET["special"]){
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
                <form>
                  <table id="example" class="table table-striped responsive-utilities jambo_table">
                    <thead>
                      <tr class="headings">
                        <th>商品名稱</th>
                        <th>是否為<?php echo $key; ?></th>
                        <th>排序 </th>
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
                        <td class=" "><span style="display:none;"><?php echo $value[$col]; ?></span><input type="text" name="<?php echo $col."[]"; ?>" value="<?php echo $value[$col]; ?>"></td>
                      </tr>
                     <?php 
                    		}
                    	}
                     ?>
                    </tbody>
                  </table>
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
        "oLanguage": {
          "sSearch": "搜尋: "
        },
        "order": [[ 0, "asc" ]],
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