<?php 
require_once('model/require_general.php');

$member = new Member();
$lg = new Loyal_Guest();
$allMemData = $member->getAllMember();
$allFbMember = $member->getFbOrNormalMember(0);
$allUsualMember = $member->getFbOrNormalMember(1);
$allLoyalGuest = $lg->getAllLoyalGuestGroup();

$recommCodeCount = 0;
foreach($allMemData as $key=>$value){
	if(trim($value["memRecommCode"]) != ""){
		$recommCodeCount++;
	}
}

$or = new Orders();
$statArr = $or->statusArr;
$statArrDirect = $or->statusDirectArr;

$periodCountArr = array();
foreach($statArr as $key=>$value){
	$periodCountArr[$value] = 0;
}
$allPeriodOr = $or->getOrderByMethod(1);
if($allPeriodOr != null){
	foreach($allPeriodOr as $index=>$each){
		foreach($statArr as $key=>$value){
			if($each["orStatus"] == $key){
				$periodCountArr[$value]++;
			}
		}
	}
}

$directCountArr = array();
foreach($statArrDirect as $key=>$value){
	$directCountArr[$value] = 0;
}
$allDirectOr = $or->getOrderByMethod(0);
if($allDirectOr != null){
	foreach($allDirectOr as $index=>$each){
		foreach($statArrDirect as $key=>$value){
			if($each["orStatus"] == $key){
				$directCountArr[$value]++;
			}
		}
	}
}

$os = new Other_Setting();
$osData = $os->getAll();

?>
<!-- page content -->
      <div class="right_col" role="main">
		<!-- top tiles -->
        <div style="display:none;" class="row tile_count dashboard_graph">
          <div class="x_title row">
          	<h2 style="padding-left:20px;">總瀏覽人次</h2>
          </div>
          <div class="animated flipInY col-md-2 col-sm-4 col-xs-4 tile_stats_count">
            <div class="left"></div>
            <div class="right">
              <span class="count_top"><i class="fa fa-user"></i> 總瀏覽人次</span>
              <div class="count"><?php echo $osData[0]["viewCount"]; ?></div>
            </div>
          </div>
        </div>
        <!-- /top tiles -->

        <br />
        
        <!-- top tiles -->
        <div class="row tile_count dashboard_graph">
          <div class="x_title row">
          	<h2 style="padding-left:20px;">會員統計</h2>
          </div>
          <div class="animated flipInY col-md-2 col-sm-4 col-xs-4 tile_stats_count">
            <div class="left"></div>
            <div class="right">
              <span class="count_top"><i class="fa fa-user"></i> 全部會員</span>
              <div class="count"><?php echo sizeof($allMemData); ?></div>
            </div>
          </div>
          <div class="animated flipInY col-md-2 col-sm-4 col-xs-4 tile_stats_count">
            <div class="left"></div>
            <div class="right">
              <span class="count_top"><i class="fa fa-user"></i> 一般會員</span>
              <div class="count"><?php echo sizeof($allUsualMember); ?></div>
            </div>
          </div>
          <div class="animated flipInY col-md-2 col-sm-4 col-xs-4 tile_stats_count">
            <div class="left"></div>
            <div class="right">
              <span class="count_top"><i class="fa fa-user"></i> FB會員</span>
              <div class="count green"><?php echo sizeof($allFbMember); ?></div>
            </div>
          </div>
        </div>
        <!-- /top tiles -->

        <br />
        
		<!-- top tiles -->
        <div class="row tile_count dashboard_graph">
          <div class="x_title row">
          	<h2 style="padding-left:20px;">分期訂單統計</h2>
          </div>
          <div class="animated flipInY col-md-2 col-sm-4 col-xs-4 tile_stats_count">
            <div class="left"></div>
            <div class="right">
              <span class="count_top"><i class="fa fa-file-text-o"></i> 全部分期訂單</span>
              <div class="count"><?php echo sizeof($allPeriodOr); ?></div>
            </div>
          </div>
          <?php 
          foreach($periodCountArr as $key=>$value){
          ?>
          <div class="animated flipInY col-md-2 col-sm-4 col-xs-4 tile_stats_count">
            <div class="left"></div>
            <div class="right">
              <span class="count_top"><i class="fa fa-file-text-o"></i> <?php echo $key; ?></span>
              <div class="count"><?php echo $value; ?></div>
            </div>
          </div>
          <?php 
          } 
          ?>
        </div>
        <!-- /top tiles -->

        <br />
        
        <!-- top tiles -->
        <div class="row tile_count dashboard_graph">
          <div class="x_title row">
          	<h2 style="padding-left:20px;">直購訂單統計</h2>
          </div>
          <div class="animated flipInY col-md-2 col-sm-4 col-xs-4 tile_stats_count">
            <div class="left"></div>
            <div class="right">
              <span class="count_top"><i class="fa fa-file-text-o"></i> 全部直購訂單</span>
              <div class="count"><?php echo sizeof($allDirectOr); ?></div>
            </div>
          </div>
          <?php 
          foreach($directCountArr as $key=>$value){
          ?>
          <div class="animated flipInY col-md-2 col-sm-4 col-xs-4 tile_stats_count">
            <div class="left"></div>
            <div class="right">
              <span class="count_top"><i class="fa fa-file-text-o"></i> <?php echo $key; ?></span>
              <div class="count"><?php echo $value; ?></div>
            </div>
          </div>
          <?php 
          } 
          ?>
        </div>
        <!-- /top tiles -->

        <br />
        
        <!-- top tiles -->
        <div class="row tile_count dashboard_graph">
          <div class="x_title row">
          	<h2 style="padding-left:20px;">老顧客統計</h2>
          </div>
          <div class="animated flipInY col-md-2 col-sm-4 col-xs-4 tile_stats_count">
            <div class="left"></div>
            <div class="right">
              <span class="count_top"><i class="fa fa-user"></i> 全部老顧客</span>
              <div class="count"><?php echo sizeof($allLoyalGuest); ?></div>
            </div>
          </div>
          
        </div>
        <!-- /top tiles -->

        <br />
        
        <!-- top tiles -->
        <div class="row tile_count dashboard_graph">
          <div class="x_title row">
          	<h2 style="padding-left:20px;">有填寫推薦碼人數</h2>
          </div>
          <div class="animated flipInY col-md-2 col-sm-4 col-xs-4 tile_stats_count">
            <div class="left"></div>
            <div class="right">
              <span class="count_top"><i class="fa fa-user"></i> 總填寫人數</span>
              <div class="count"><?php echo $recommCodeCount; ?></div>
            </div>
          </div>
          
        </div>
        <!-- /top tiles -->

        <br />
        
  