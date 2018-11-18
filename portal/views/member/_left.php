
<div class="list-group">
    <a href="?item=member_center" class="list-group-item list-group-item-action <?php if ($active==1) echo 'active';?>">基本資料</a>
    <a href="?item=member_center&action=password_edit" class="list-group-item list-group-item-action <?php if ($active==2) echo 'active';?>">變更密碼</a>
    <a href="?item=member_center&action=order" class="list-group-item list-group-item-action <?php if ($active==3) echo 'active';?>">訂單查詢</a>
    <a href="?item=member_center&action=pay" class="list-group-item list-group-item-action <?php if ($active==4) echo 'active';?>">我要繳款</a>
</div>
<div class="sell xs-none" style="height: 430px;">
                        <img src="portal/images/Tmp/AD-1.jpg">
</div>


<?php 
/*
<div class="sell xs-none" style="height: 430px;background-image: linear-gradient(151deg, #ff7f00,#fff0c9); padding: 10px">

    <!-- left silide -->
    <div class="col-left-slide left-module">
        <ul class="owl-carousel owl-style2" data-loop="true" data-nav = "false" data-margin = "30" data-autoplayTimeout="1000" data-autoplayHoverPause = "true" data-items="1" data-autoplay="true">
            <?php
            $ad = new Advertise();
            $adData = $ad->getAllOrderBy(3,false);
            if($adData != ""){
                foreach($adData as $key => $value){
                    ?>
                    <li>
                        <a href="<?php echo $value["adLink"]; ?>">
                            <img src="admin/<?php echo $value["adImage"]; ?>" alt="slide-left" style="width: 100%">
                        </a>
                    </li>
                    <?php
                }
            }else{
                ?>
                <li><a href="#"><img alt="" src="portal/assets/images/Not-found.png" title=""  alt="slide-left"></a></li>
                <li><a href="#"><img alt="" src="portal/assets/images/Not-found.png" title=""  alt="slide-left"></a></li>
                <?php
            }
            ?>
        </ul>
    </div>
    <!--./left silde-->

</div>
 ?>