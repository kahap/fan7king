
    <main role="main">
        <h1 class="d-none"><span>Nowait</span></h1>
        <section id="carousel" class="bg-white">
            <div class="container">
                <div class="slick-carorsel">
                    <?php
                    $ad = new Advertise();
                    $adData = $ad->getAllOrderBy(0,false);
                    $rightBannerData = $ad->getAllOrderBy(1,1);

                    foreach($adData as $key => $value){
                        ?>
                        <div class="carousel-item">
                            <a href="<?php echo $value["adLink"]; ?>">
                                <img class="d-block w-100"  src="admin/<?php echo $value["adImage"]; ?>" alt="First slide" style="height: 375px;">
                            </a>
                        </div>
                        <?php
                    }
                    ?>
                </div>
            </div>
        </section>
        <section id="nowait-cate" class="bg-white">
            <div class="container">
                <?php
                if(array_key_exists($itemVal,$page_other ) or $itemVal != "" or $_POST['Firm_Number'] != "" or $_SESSION['ThirdData'] != ""){

                }else{
                    ?>
                    <div class="slick-title">
                        <h2 class="span-yellow"><span>商品類別</span></h2>
                    </div>
                    <div class="row">
                        <?php
                        $i=1;
                        foreach($cate_head as $key => $value){
                            if($value['catIfDisplay'] != "0"){
                                echo '<div class="col-2">
                                        <div class="card">
                                            <a class="cate-'.($key+1).'" href="?item=category&c='.$value['catOrder'].'" title="">
                                                '.$value['catName'].'
                                            </a>
                                        </div>
                                    </div>';
                            }
                            $i++;
                        }
                        //admin/".$value['catIcon']."
                        ?>
                    </div>
                    <?php
                }
                ?>
            </div>
        </section>

    <?php
    $pm = new Product_Manage();
    $p = new Product();
    $ps = new Period_Setting();
    $month = $ps->getAllPS();
    $news = $pm->getAllNew();
    $special = $pm->getAllSpecial();
    $hot = $pm->getAllHot();

    if($hot != "") {
        ?>
        <section id="nowait-limit" class="slick-zone">
            <div class="container">
                <div class="slick-title">
                    <h2 class="span-orange"><span>限時商品</span>TIME LIMIT</h2>
                    <p class="text-right text-orange"><a href="#" class="" title="看更多限時商品">看更多&gt;&gt;</a></p>
                </div>
                <div class="slick-content card-deck">
                <?php
                if(count($hot) >= 3){
                    ?>
                    <div class="slick-page">
                        <div class="row">
                        <?php
                        $i = 1;
                        foreach($hot as $key => $value){
                            $img = json_decode($value['proImage']);
                            $img[0] = ($img[0] !="") ? "admin/".$img[0]:"admin/".$img[1];
                            foreach($month as $k => $v){
                                $price[$v['psMonthNum']] = ceil($v['psRatio']*$value['pmPeriodAmnt']/$v['psMonthNum']);
                            }
                            ?>
                            <div class="col-lg-3 col-6">
                                <div class="card">
                                    <a href="?item=product&pro=<?php echo $value['proNo'];  ?>" title="#">
                                        <img src="<?php echo $img[0];?>" class="img-fluid img-responsive" alt="product">
                                        <div class="card-body">
                                            <p class="card-title"><?php echo $value['proName'] ?></p>
<!--                                            <p class="nowait-badge text-left"><span class="bg-yellow">開學季優惠</span><span class="bg-yellow">滿千送百特惠方案</span></p>-->
                                            <p class="card-text text-orange">
                                                <?php echo "月付NT $".number_format(min($price)); ?>
                                                <small><?php echo "*".array_search(min($price),$price)."期"; ?></small>
                                            </p>
                                        </div>
                                    </a>
                                </div>
                            </div>
                            <?php
                            if (!($i % 4)){
                            ?>
                        </div>
                    </div>
                    <div class="slick-page">
                        <div class="row">
                            <?php
                            }
                            $i++;
                        }
                        ?>
                        </div>
                    </div>
                    <?php
                }
                ?>
                </div>
            </div>
        </section>
        <?php
    }
    if($news != "") {
        ?>
        <section id="nowait-arrive" class="bg-white slick-zone">
            <div class="container">
                <div class="slick-title">
                    <h2 class="span-yellow"><span>最新商品</span>NEW ARRIVE</h2>
                    <p class="text-right text-orange"><a href="#" class="" title="看更多最新商品">看更多&gt;&gt;</a></p>
                </div>
                <div class="slick-content card-deck">
                <?php
                if(count($news) >= 3){
                    ?>
                    <div class="slick-page">
                        <div class="row">
                        <?php
                        $i = 1;
                        foreach($news as $key => $value){
                            $img = json_decode($value['proImage']);
                            $img[0] = ($img[0] != "") ? "admin/" . $img[0] : "admin/" . $img[1];
                            foreach ($month as $k => $v) {
                                $price[$v['psMonthNum']] = ceil($v['psRatio'] * $value['pmPeriodAmnt'] / $v['psMonthNum']);
                            }
                            ?>
                            <div class="col-lg-3 col-6">
                                <div class="card">
                                    <a href="?item=product&pro=<?php echo $value['proNo']; ?>" title="#">
                                        <img src="<?php echo $img[0]; ?>" class="img-fluid img-responsive"
                                             alt="product">
                                        <div class="card-body">
                                            <p class="card-title"><?php echo $value['proName'] ?></p>
<!--                                            <p class="nowait-badge text-left"><span class="bg-yellow">開學季優惠</span><span class="bg-yellow">滿千送百特惠方案</span></p>-->
                                            <p class="card-text text-orange">
                                                <?php echo "月付NT $" . number_format(min($price)); ?>
                                                <small><?php echo "*" . array_search(min($price), $price) . "期"; ?></small>
                                            </p>
                                        </div>
                                    </a>
                                </div>
                            </div>
                            <?php
                            if (!($i % 4)){
                            ?>
                        </div>
                    </div>
                    <div class="slick-page">
                        <div class="row">
                            <?php
                            }
                            $i++;
                        }
                        ?>
                        </div>
                    </div>
                    <?php
                }
                ?>
                </div>
            </div>
        </section>
        <?php
    }
    if($special != "") {
        ?>
        <section id="nowait-featured" class="slick-zone">
            <div class="container">
                <div class="slick-title">
                    <h2 class="span-pale"><span>精選商品</span>FEATURED</h2>
                    <p class="text-right text-orange"><a href="#" class="" title="看更多精選商品">看更多&gt;&gt;</a></p>
                </div>
                <div class="slick-content card-deck">
                    <?php
                    if(count($special) >= 3){
                    ?>
                    <div class="slick-page">
                        <div class="row">
                        <?php
                        $i = 1;
                        foreach($special as $key => $value){
                            $img = json_decode($value['proImage']);
                            $img[0] = ($img[0] !="") ? "admin/".$img[0]:"admin/".$img[1];
                            foreach($month as $k => $v){
                                $price[$v['psMonthNum']] = ceil($v['psRatio']*$value['pmPeriodAmnt']/$v['psMonthNum']);
                            }
                        ?>
                            <div class="col-lg-3 col-6">
                                <div class="card">
                                    <a href="?item=product&pro=<?php echo $value['proNo']; ?>" title="#">
                                        <img src="<?php echo $img[0]; ?>" class="img-fluid img-responsive"
                                             alt="product">
                                        <div class="card-body">
                                            <p class="card-title"><?php echo $value['proName'] ?></p>
<!--                                            <p class="nowait-badge text-left"><span class="bg-yellow">開學季優惠</span><span class="bg-yellow">滿千送百特惠方案</span></p>-->
                                            <p class="card-text text-orange">
                                                <?php echo "月付NT $" . number_format(min($price)); ?>
                                                <small><?php echo "*" . array_search(min($price), $price) . "期"; ?></small>
                                            </p>
                                        </div>
                                    </a>
                                </div>
                            </div>
                            <?php
                            if (!($i % 4)){
                            ?>
                        </div>
                    </div>
                    <div class="slick-page">
                        <div class="row">
                            <?php
                            }
                            $i++;
                        }
                        ?>
                        </div>
                    </div>
                    <?php
                    }
                    ?>
                </div>
            </div>
        </section>
        <?php
    }
        ?>


        <?php
        include_once ('portal/views/_page_service.php');
        ?>
    </main>
