
<!-- Home slideder-->
<div id="home-slider">
    <div class="container">
        <div class="row">
            <div class="col-sm-3 slider-left"></div>
            <div class="col-sm-9 header-top-right">
                <div class="homeslider">
                    <div class="content-slide">
                        <ul id="contenhomeslider">
						  <?php
							$ad = new Advertise();
							$adData = $ad->getAllOrderBy(0,false);
						    foreach($adData as $key => $value){ 
						  ?>
                            <li><a href="<?php echo $value["adLink"]; ?>"><img alt="" src="admin/<?php echo $value["adImage"]; ?>" title="" /></a></li>
						  <?php 
						    } 
						  ?>
                        </ul>
                    </div>
                </div>
                <div class="header-banner banner-opacity">
					<?php $rightBannerData = $ad->getAllOrderBy(1,1)?>
                    <a href="<?php echo $rightBannerData[0]["adLink"]; ?>"><img alt="" src="admin/<?php echo $rightBannerData[0]["adImage"]; ?>" /></a>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- END Home slideder-->
