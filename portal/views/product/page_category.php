

<main role="main">
    <section>
        <div class="container">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="index.php">NoWait</a></li>
                <?php
                    /******************** page *******************/
                    if($_GET['c'] != "")
                    {
                        $page_url = '?item=category&c='. $_GET['c'];
                        if($_GET['bino'] != ""){
                            $page_url = '?item=category&c='. $_GET['c'] . "&bino=" . $_GET['bino'];
                        }
                    }
                    elseif($_GET['b'] != "")
                    {
                        $page_url = '?item=category&b='. $_GET['b'] . "&type=" . $_GET['c'];
                        if($_GET['type'] != ""){
                            $page_url = '?item=category&b='. $_GET['b'] . "&type=" . $_GET['type'];
                            if($_GET['bino'] != ""){
                                $page_url = '?item=category&b='. $_GET['b'] . "&type=" . $_GET['type'] . "&bino=" . $_GET['bino'];
                            }
                        }else{
                            $page_url = '?item=category&b='. $_GET['b'] . "&type=" . $_GET['c'] . "&bino=" . $_GET['bino'];
                        }
                    }

                    $page = isset($_GET['paginate'])? $_GET['paginate'] : 1;
                    /******************** page *******************/


                $brand = new Brand();   //品牌
                $b_item = new B_items();    //品項
                $product = new Product();
                if($_GET['type'] != ''){
                    echo '<li class="breadcrumb-item active" aria-current="page">';
                    echo $category_1[$_GET['type']];
                    echo '</li>';
                }
                if($_GET['pmSOO'] != ''){
                    switch ($_GET['pmSOO']){
                        case 'hot':
                            echo '<li class="breadcrumb-item active" aria-current="page">';
                            echo '限量推薦';
                            echo '</li>';
                            break;
                        case 'news':
                            echo '<li class="breadcrumb-item active" aria-current="page">';
                            echo '新品熱推 ';
                            echo '</li>';
                            break;
                        case 'special':
                            echo '<li class="breadcrumb-item active" aria-current="page">';
                            echo '嚴選特賣';
                            echo '</li>';
                            break;
                    }
                }
                ?>
                <li class="breadcrumb-item active" aria-current="page">
                    <?php
                    if($_GET['c'] != ""){
                        $p_data = $product->getAllProGroupbyCatNo($cat_number[$_GET['c']]);
                        foreach($p_data as $key => $value){
                            $show_brand[] = $value['braNo'];
                        }
                    }
                    if($_GET['type'] != ""){
                        $p_data = $product->getAllProGroupbyCatNo($cat_number[$_GET['type']]);
                        foreach($p_data as $key => $value){
                            $show_brand[] = $value['braNo'];
                        }
                    }
                    $brand_data = $brand->getAllBrand();
                    if($_GET['c'] != ""){
                        foreach($cate_head as $key => $value){
                            if($value['catIfDisplay'] != "0"){
                                echo ($value['catOrder'] == $_GET['c']) ? $value['catName']:"";
                            }
                        }
                    }elseif($_GET['b'] != ""){
                        foreach($brand_data as $key => $value){
                            if($_GET['b'] == $value['braNo']){
                                echo $value['braName'];
                            }
                        }
                    }elseif($_GET['bino'] != ""){
                        $b_item_data = $b_item->getAllItems();
                        foreach($b_item_data as $key => $value){
                            if($_GET['bino'] == $value['biNo']){
                                echo $value['biName'];
                            }
                        }
                    }
                    ?>
                </li>
                <?php
                if($_GET['type'] == '' && $_GET['bino'] != ''){
                    $b_item_data = $b_item->getAllItems();
                    foreach($b_item_data as $key => $value){
                        if($_GET['bino'] == $value['biNo']){
                            echo '<li class="breadcrumb-item active" aria-current="page">';
                            echo $value['biName'];
                            echo '</li>';
                        }
                    }
                }
                ?>
            </ol>
            <div class="row">
                <div class="col-lg-3">
                    <div class="product-inner">
                        <div class="product-cate">
                            <p class="product-pick br-b-gray"><span class="io io-list"></span>商品分類</p>
                            <div class="list-group d-none d-lg-block">
                            <?php
                            foreach($cate_head as $key => $value){
                                if($value['catIfDisplay'] != "0"){
                                    @$active = ($value['catOrder'] == $_GET['c']) ? 'active':'';
                                    ?>
                                    <a href="?item=category&c=<?php echo $value['catOrder'];?>" class="list-group-item list-group-item-action <?php echo $active;?>">
                                        <?php echo $value['catName'];?>
                                    </a>
                                    <?php
                                }
                            }
                            ?>
                            </div>
                        </div>
                        <div class="product-cate">
                            <p class="product-pick br-b-gray"><span class="io io-tag"></span>品牌類別</p>
                            <div class="list-group d-none d-lg-block">
                                <?php
                                foreach($brand_data as $key => $value){
                                    @$active = ($value['braNo'] == $_GET['b']) ? 'active':'';
                                    if($value['braIfDisplay'] != "0"){
                                        if($_GET['c'] != ""){
                                            if(in_array($value['braNo'],$show_brand)){
//                                                echo "<li class='".$active."'><span></span><a href='?item=category&b=".$value['braNo']."&type=".$_GET['c']."'>".$value['braName']."</a></li>";
                                                ?>
                                                <a href="?item=category&b=<?php echo $value['braNo']."&type=".$_GET['c'];?>" class="list-group-item list-group-item-action <?php echo $active;?>">
                                                    <?php echo $value['braName'];?>
                                                </a>
                                                <?php
                                            }
                                        }elseif($_GET['type'] != ""){
                                            if(in_array($value['braNo'],$show_brand)){
//                                                echo "<li class='".$active."'><span></span><a href='?item=category&b=".$value['braNo']."&type=".$_GET['type']."'>".$value['braName']."</a></li>";
                                                ?>
                                                <a href="?item=category&b=<?php echo $value['braNo']."&type=".$_GET['type'];?>" class="list-group-item list-group-item-action <?php echo $active;?>">
                                                    <?php echo $value['braName'];?>
                                                </a>
                                                <?php
                                            }
                                        }
                                    }
                                }
                                ?>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-9">
                    <div class="product-inner">
                        <p class="product-pick br-b-gray">
                            <span class="io io-list"></span>品項
                            <span class="nowait-tag orange">
                                <?php
                                /*b_item_data = $b_item->getAllItems();
                                foreach($b_item_data as $key => $value){
                                    @$active = ($value['biNo'] == $_GET['bino']) ? ' class="active"':'';
                                    if($_GET['c'] != ""){
//                                            if(in_array($value['braNo'],$show_brand)){
//                                                echo "<li class='".$active."'><span></span><a href='?item=category&b=".$value['braNo']."&type=".$_GET['c']."'>".$value['braName']."</a></li>";
                                            ?>
                                            <a href="?item=category&bino=<?php echo $value['biNo']."&c=".$_GET['c'];?>" title="" <?php echo $active;?> >
                                                <?php echo $value['biName'];?>
                                            </a>
                                            <?php
//                                            }
                                    }elseif($_GET['type'] != ""){
//                                            if(in_array($value['braNo'],$show_brand)){
//                                                echo "<li class='".$active."'><span></span><a href='?item=category&b=".$value['braNo']."&type=".$_GET['type']."'>".$value['braName']."</a></li>";
                                            ?>
                                            <a href="?item=category&bino=<?php echo $value['biNo']."&type=".$_GET['type'];?>" title="" <?php echo $active;?> >
                                                <?php echo $value['biName'];?>
                                            </a>
                                            <?php
//                                            }
                                    }
                                }*/
                                ?>
                                <?php
//                                foreach ($b_item_data as $item){
//                                    ?>
<!--                                    --><?php
//                                }
                                ?>
<!--                                <a href="#" title="手機">手機</a>-->
<!--                                <a href="#" title="平板" class="active">平板</a>-->
<!--                                <a href="#" title="10吋以上大螢幕手機">10吋以上大螢幕手機</a>-->
<!--                                <a href="#" title="大螢幕老人機">大螢幕老人機</a>-->
                            </span>
                        </p>
<!--                        <p class="product-pick">-->
<!--                            <span class="io io-order"></span>排序-->
<!--                            <span class="nowait-tag">-->
<!--                                <a href="#" title="依熱銷度">依熱銷度</a>-->
<!--                                <a href="#" title="價格低者優先">價格低者優先</a>-->
<!--                                <a href="#" title="依商品上架時間">依商品上架時間</a>-->
<!--                            </span>-->
<!--                        </p>-->

                        <h1 class="pd-less">
                            <span>
							<?php
                            if($_GET['c'] != ""){
                                foreach($cate_head as $key => $value){
                                    if($value['catIfDisplay'] != "0"){
                                        echo ($value['catOrder'] == $_GET['c']) ? $value['catName']:"";
                                    }
                                }
                            }elseif($_GET['b'] != ""){
                                foreach($brand_data as $key => $value){
                                    if($_GET['b'] == $value['braNo']){
                                        echo $value['braName'];
                                    }
                                }
                            }


                            $pm = new Product_Manage();
                            $p = new Product();
                            $ps = new Period_Setting();
                            $pp = new Product_Period();
                            $month = $ps->getAllPS();


//                            $totalData = $pm->getAllPM_forCategoryCount();
//                            $lastPage = ceil($totalData/$amount);
//                            $amount = ($totalData<1000) ? 999 : 30;   //若總數大於999，做分頁(100 amount/page)
//                            $pm_data = $pm->getAllPM_forCategory( ($page-1)*$amount, $amount );
                            $pm_data = $pm->getAllPM_forCategory();


                            //限時 最新 精選
                            if (isset($_GET['pmSOO'])){
                                switch ($_GET['pmSOO']){
                                    case 'hot':
                                        $pm_data = $pm->getAllHot();
                                        break;
                                    case 'news':
                                        $pm_data = $pm->getAllNew();
                                        break;
                                    case 'special':
                                        $pm_data = $pm->getAllSpecial();
                                        break;
                                }
                            }

                            ?>
                            </span>
                        </h1>

<!--                        <ul class="pagination justify-content-center flex-wrap">-->
<!--                            --><?php //if ($page>1){ ?>
<!--                                <li class="page-item"><a class="page-link" href="--><?php //echo $page_url;?><!--&paginate=--><?php //echo 1;?><!--">&lt;</a></li>-->
<!--                            --><?php //}
//                            $num=3;
//                            for ($i=1; $i<=$lastPage; $i++) {
//                                if ($i>$page+$num || $i<$page-$num)continue;
//                                ?>
<!--                                <li class="page-item --><?php //if ($page==$i)echo 'active';?><!--">-->
<!--                                    <a class="page-link" href="--><?php //echo $page_url;?><!--&paginate=--><?php //echo $i;?><!--">-->
<!--                                        --><?php //echo $i;?>
<!--                                    </a>-->
<!--                                </li>-->
<!--                                --><?php
//                            }
//                            if ($page<$lastPage){ ?>
<!--                                <li class="page-item"><a class="page-link" href="--><?php //echo $page_url;?><!--&paginate=--><?php //echo $lastPage;?><!--">&gt;</a></li>-->
<!--                            --><?php //} ?>
<!--                            <li class="page-item">總共 --><?php //echo $totalData;?><!-- 筆商品</li>-->
<!--                        </ul>-->

                        <div class="grid-list-view text-right">
                            <button class="grid-view text-hide on">grid</button>
                            <button class="list-view text-hide">list</button>
                        </div>
                        <div class="product-list card-deck view-grid">
                            <div class="row content">
                            <?php

                            foreach($pm_data as $key => $value){
                                if($value['catNo'] == $cat_number[$_GET['c']] && $_GET['c'] != ""){

                                    if (isset($_GET['bino']) && $value['biNo'] != $_GET['bino'])continue;

                                    $p_contetn = $p->getOneProByNoWithoutImage($value['proNo']);
                                    $img = json_decode($p_contetn[0]['proImage']);
                                    $img[0] = ($img[0] !="") ? "admin/".$img[0]:"admin/".$img[1];
                                    $ppData = $pp->getPPByProduct($value["proNo"]);
                                    $followDefault = true;
                                    if($ppData != null){
                                        foreach($ppData as $k=>$v){
                                            if($v["ppPercent"] != ""){
                                                $followDefault = false;
                                                break;
                                            }
                                        }
                                    }

                                    if($followDefault){
                                        foreach($month as $k => $v){
                                            $total = ceil($v['psRatio']*$value['pmPeriodAmnt']/$v['psMonthNum']);
                                            if($total > 1000){
                                                $price[$v['psMonthNum']] = $total;
                                            }
                                        }
                                    }else{
                                        foreach($ppData as $k=>$v){
                                            if(!empty($v['ppPercent'])){
                                                $total = ceil($v['ppPercent']*$value['pmPeriodAmnt']/$v['ppPeriodAmount']);
                                                if($total > 1000){
                                                    $price[$v['ppPeriodAmount']] = $total;
                                                }
                                            }
                                        }
                                    }
                                    ?>
                                    <div class="item col-lg-4 col-6">
                                        <div class="card">
                                            <a href="?item=product&pro=<?php echo $value['proNo'];?>" title="<?php echo $value['proName'];?>">
                                                <?php //修理圖片路徑
                                                $image = $img[0];
                                                $image = str_replace('../','',$image);
                                                $image = str_replace('admin/admin/','admin/',$image);
                                                ?>
                                                <img src="<?php echo $image;?>" class="img-fluid" alt="<?php echo $value['proName'];?>" style="width:190px;">
                                                <div class="card-body">
                                                    <p class="card-title">
                                                        <!-- <a href="?item=product&pro=<?php echo $value['proNo'];  ?>"></a> -->
                                                        <?php echo $value['proName'];?>
                                                    </p>
                                                    <!-- <p class="nowait-badge text-left">
                                                        <span class="bg-yellow">開學季優惠</span>
                                                        <span class="bg-yellow">滿千送百特惠方案</span>
                                                    </p> -->
                                                    <p class="card-text text-orange">
                                                        月付：NT$<?php echo number_format(min($price));?>
                                                        <small>*<?php echo array_search(min($price),$price);?>期</small>
                                                    </p>
                                                </div>
                                            </a>
                                        </div>
                                    </div>
                                    <?php
                                }
                                elseif($value['braNo'] == $_GET['b'] && $_GET['b'] != "" && $value['catNo'] == $cat_number[$_GET['type']])
                                {
                                    $p_contetn = $p->getOneProByNoWithoutImage($value['proNo']);
                                    $img = json_decode($p_contetn[0]['proImage']);
                                    $img[0] = ($img[0] !="") ? "admin/".$img[0]:"admin/".$img[1];
                                    $ppData = $pp->getPPByProduct($value["proNo"]);
                                    $followDefault = true;
                                    if($ppData != null){
                                        foreach($ppData as $k=>$v){
                                            if($v["ppPercent"] != ""){
                                                $followDefault = false;
                                                break;
                                            }
                                        }
                                    }

                                    if($followDefault){
                                        foreach($month as $k => $v){
                                            $total = ceil($v['psRatio']*$value['pmPeriodAmnt']/$v['psMonthNum']);
                                            if($total > 1000){
                                                $price[$v['psMonthNum']] = $total;
                                            }
                                        }
                                    }else{
                                        foreach($ppData as $k=>$v){
                                            if(!empty($v['ppPercent'])){
                                                $total = ceil($v['ppPercent']*$value['pmPeriodAmnt']/$v['ppPeriodAmount']);
                                                if($total > 1000){
                                                    $price[$v['ppPeriodAmount']] = $total;
                                                }
                                            }
                                        }
                                    }
                                    ?>
                                    <div class="item col-lg-4 col-6">
                                        <div class="card">
                                            <a href="?item=product&pro=<?php echo $value['proNo'];?>" >
                                                <?php //修理圖片路徑
                                                $image = $img[0];
                                                $image = str_replace('../','',$image);
                                                $image = str_replace('admin/admin/','admin/',$image);
                                                ?>
                                                <img src="<?php echo $image;?>" class="img-fluid" alt="product" style="width:190px;">
                                                <div class="card-body">
                                                    <p class="card-title"><?php echo $value['proName'];?></p>
                                                    <p class="nowait-badge text-left">
                                                        <span class="bg-yellow">開學季優惠</span>
                                                        <span class="bg-yellow">滿千送百特惠方案</span>
                                                    </p>
                                                    <p class="card-text text-orange">
                                                        月付：NT$<?php echo number_format(min($price));?>
                                                        <small>*<?php echo array_search(min($price),$price);?>期</small>
                                                    </p>
                                                </div>
                                            </a>
                                        </div>
                                    </div>
                                    <?php
                                }
                                elseif($_GET['b']=="" && $_GET['type']=="" && $_GET['c']==""){
                                       //限時 最新 精選
                                    $p_contetn = $p->getOneProByNoWithoutImage($value['proNo']);
                                    $img = json_decode($p_contetn[0]['proImage']);
                                    $img[0] = ($img[0] !="") ? "admin/".$img[0]:"admin/".$img[1];
                                    $ppData = $pp->getPPByProduct($value["proNo"]);
                                    $followDefault = true;
                                    if($ppData != null){
                                        foreach($ppData as $k=>$v){
                                            if($v["ppPercent"] != ""){
                                                $followDefault = false;
                                                break;
                                            }
                                        }
                                    }

                                    if($followDefault){
                                        foreach($month as $k => $v){
                                            $total = ceil($v['psRatio']*$value['pmPeriodAmnt']/$v['psMonthNum']);
                                            if($total > 1000){
                                                $price[$v['psMonthNum']] = $total;
                                            }
                                        }
                                    }else{
                                        foreach($ppData as $k=>$v){
                                            if(!empty($v['ppPercent'])){
                                                $total = ceil($v['ppPercent']*$value['pmPeriodAmnt']/$v['ppPeriodAmount']);
                                                if($total > 1000){
                                                    $price[$v['ppPeriodAmount']] = $total;
                                                }
                                            }
                                        }
                                    }
                                    ?>
                                    <div class="item col-lg-4 col-6">
                                        <div class="card">
                                            <a href="?item=product&pro=<?php echo $value['proNo'];?>" >
                                                <?php //修理圖片路徑
                                                $image = $img[0];
                                                $image = str_replace('../','',$image);
                                                $image = str_replace('admin/admin/','admin/',$image);
                                                ?>
                                                <img src="<?php echo $image;?>" class="img-fluid" alt="product" style="width:190px;">
                                                <div class="card-body">
                                                    <p class="card-title"><?php echo $value['proName'];?></p>
                                                    <p class="nowait-badge text-left">
                                                        <span class="bg-yellow">開學季優惠</span>
                                                        <span class="bg-yellow">滿千送百特惠方案</span>
                                                    </p>
                                                    <p class="card-text text-orange">
                                                        月付：NT$<?php echo number_format(min($price));?>
                                                        <small>*<?php echo array_search(min($price),$price);?>期</small>
                                                    </p>
                                                </div>
                                            </a>
                                        </div>
                                    </div>
                                    <?php
                                }
                            }
                            ?>

                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

<?php
include_once ('portal/views/_page_service.php');
?>

</main>

