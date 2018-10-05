<?php 
require_once('model/require_general.php');

$cat = new Category();
$pro = new Product();
$bra = new Brand();
$allProData = $pro->getAllPro();

if(isset($_GET["catname"]) && $_GET["catname"] != "all"){
	$allProData = $pro->getAllProByCatName($_GET["catname"]);
}
if(isset($_GET["braname"]) && $_GET["braname"] != "all"){
	$allProData = $pro->getAllProByBraName($_GET["braname"]);
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
                        <td class=" "><?php echo $pro->changeSign($value["proSpec"],"<br>"); ?></td>
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