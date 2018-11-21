<?php 
require_once('model/require_general.php');

$mem = new Member();
$lg = new Loyal_Guest();
$or = new Orders();

$allLGData = $lg->getAllLoyalGuestGroup();

date_default_timezone_set('Asia/Taipei');
$date = date('Y-m-d', time());
$year = explode("-", $date)[0];

$curYear = $_GET["year"];

$yearAllData = array();

for($i=1;$i<=12;$i++){
	$days = 0;
	switch($i){
		case 1:
		case 3:
		case 5:
		case 7:
		case 8:
		case 10:
		case 12:
			$days = 31;
			break;
		case 2:
			$days = 29;
			break;
		case 4:
		case 6:
		case 9:
		case 11:
			$days = 30;
			break;
	}
	if($i<10){
		$i = "0".$i;
	}
	//會員資料
	$memData = $mem->getAllMemberMonthly($curYear."-".$i."-01", $curYear."-".$i."-".$days);
	$fbMemCount = 0;
	$normalMemCount = 0;
	$recCodeCount = 0;
	if($memData != null){
		foreach($memData as $key=>$value){
			if($value["memRegistMethod"] == 0){
				$fbMemCount++;
			}else if($value["memRegistMethod"] == 1){
				$normalMemCount++;
			}
			if($value["memRecommCode"] != ""){
				$recCodeCount++;
			}
		}
	}
	
	//老顧客數量統計
	$lgCount = 0;
	if($memData != null){
		foreach($memData as $key=>$value){
			foreach($allLGData as $keyIn=>$valueIn){
				if($valueIn["lgIdNum"] == $value["memIdNum"]){
					$lgCount++;
				}
			}
		}
	}
	
	//訂單統計
	//分期
	$orPeriodData = $or->getAllOrderByDateAndMethod($curYear."-".$i."-01", $curYear."-".$i."-".$days, 1);
	$periodAllCount = 0;
	$period10Count = 0;
	$period4Count = 0;
	$period7Count = 0;
	$period13Count = 0;
	if($orPeriodData != null){
		foreach($orPeriodData as $key=>$value){
			switch($value["orStatus"]){
				case 10:
					$period10Count++;
					break;
				case 4:
					$period4Count++;
					break;
				case 7:
					$period7Count++;
					break;
				case 13:
					$period13Count++;
					break;
			}
			$periodAllCount++;
		}
	}
	//直購
	$orDirectData = $or->getAllOrderByDateAndMethod($curYear."-".$i."-01", $curYear."-".$i."-".$days, 0);
	$directAllCount = 0;
	$direct4Count = 0;
	$direct1Count = 0;
	$direct7Count = 0;
	if($orDirectData != null){
		foreach($orDirectData as $key=>$value){
			switch($value["orStatus"]){
				case 4:
					$direct4Count++;
					break;
				case 1:
					$direct1Count++;
					break;
				case 7:
					$direct7Count++;
					break;
			}
			$directAllCount++;
		}
	}
	$yearAllData[$i]["fbMemCount"] = $fbMemCount;
	$yearAllData[$i]["normalMemCount"] = $normalMemCount;
	$yearAllData[$i]["recCodeCount"] = $recCodeCount;
	$yearAllData[$i]["lgCount"] = $lgCount;
	$yearAllData[$i]["periodAllCount"] = $periodAllCount;
	$yearAllData[$i]["period10Count"] = $period10Count;
	$yearAllData[$i]["period4Count"] = $period4Count;
	$yearAllData[$i]["period7Count"] = $period7Count;
	$yearAllData[$i]["period13Count"] = $period13Count;
	$yearAllData[$i]["directAllCount"] = $directAllCount;
	$yearAllData[$i]["direct4Count"] = $direct4Count;
	$yearAllData[$i]["direct1Count"] = $direct1Count;
	$yearAllData[$i]["direct7Count"] = $direct7Count;
}



?>
<!-- page content -->
      <div class="right_col" role="main">
        <div class="">
          <div class="page-title">
            <div class="title_left">
              <h3>摘要資訊-每月統計</h3>
            </div>
          </div>
          <div class="clearfix"></div>

          <div class="row">

            <div class="col-md-12 col-sm-12 col-xs-12">
              <div class="x_panel">
                <div class="x_content">
                  <div style="padding:10px 0 20px 0;">
                  	<label>選擇年分： </label>
                  	<select id="which-year" name="which">
                  	  <?php $count = $year - 2016; ?>
                  		<?php for($i=0;$i<=$count;$i++){ ?>
                  		<option <?php if($curYear == 2016+$i) echo "selected"; ?> value="<?php echo 2016+$i; ?>">民國<?php echo 105+$i; ?>年</option>
                  		<?php } ?>
                  	</select>
                  </div>
                  <table id="example" class="table table-striped responsive-utilities jambo_table">
                    <thead>
                      <tr class="headings">
                        <th>月份 </th>
                        <th>FB會員 </th>
                        <th>非FB會員 </th>
                        <th>分期-全部分期訂單</th>
                        <th>分期-已完成 </th>
                        <th>分期-婉拒 </th>
                        <th>分期-取消訂單 </th>
                        <th>分期-完成退貨 </th>
                        <th>直購-全部直購訂單 </th>
                        <th>直購-已完成 </th>
                        <th>直購-取消訂單 </th>
                        <th>直購-完成退貨 </th>
                        <th>老顧客 </th>
                        <th>有填寫推薦人數 </th>
                      </tr>
                    </thead>
                    <tbody>
                    <?php 
	                    if($yearAllData != null){
	                    	foreach($yearAllData as $key=>$value){
                    ?>
                      <tr class="pointer">
                        <td class=" "><?php echo $key; ?></td>
                        <td class=" "><?php echo $value["fbMemCount"]; ?></td>
                        <td class=" "><?php echo $value["normalMemCount"]; ?></td>
                        <td class=" "><?php echo $value["periodAllCount"]; ?></td>
                        <td class=" "><?php echo $value["period10Count"]; ?></td>
                        <td class=" "><?php echo $value["period4Count"]; ?></td>
                        <td class=" "><?php echo $value["period7Count"]; ?></td>
                        <td class=" "><?php echo $value["period13Count"]; ?></td>
                        <td class=" "><?php echo $value["directAllCount"]; ?></td>
                        <td class=" "><?php echo $value["direct4Count"]; ?></td>
                        <td class=" "><?php echo $value["direct1Count"]; ?></td>
                        <td class=" "><?php echo $value["direct7Count"]; ?></td>
                        <td class=" "><?php echo $value["lgCount"]; ?></td>
                        <td class=" "><?php echo $value["recCodeCount"]; ?></td>
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