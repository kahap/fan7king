<?php
session_cache_limiter('public');
session_start();
?>
<style>
    .product-name{
        display: inline-block;
        vertical-align: middle;
        width: 100%;
        overflow: hidden;
        white-space: nowrap;
        text-overflow: ellipsis;
    }
</style>
<main role="main">
    <section>
        <div class="container">
            <ol class="breadcrumb clearfix">
                <li class="breadcrumb-item"><a href="index.php">NoWait</a></li>
                <li class="breadcrumb-item active" aria-current="page">搜尋結果</li>
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
                        </p>
                        <p class="product-pick">
                            <span class="io io-order"></span>排序
                            <span class="nowait-tag">
                                <a href="#" title="依熱銷度">依熱銷度</a>
                                <a href="#" title="價格低者優先">價格低者優先</a>
                                <a href="#" title="依商品上架時間">依商品上架時間</a>
                            </span>
                        </p>

                        <h1 class="pd-less"><span>搜尋結果</span></h1>
                        <div class="grid-list-view text-right">
                            <button class="grid-view text-hide on">grid</button>
                            <button class="list-view text-hide">list</button>
                        </div>
                        <div class="product-list card-deck view-grid" id="center_column">
                            <div class="row">
                                <?php
                                $pm = new Product_Manage();
                                $category = isset($_GET['category'])? $_GET['category'] : 0;
                                $_GET['category'] = ($category) ? $category : 0 ;
//                                $product_data = $pm->getSearchPM($_GET);

                                $p = new Product();
                                $ps = new Period_Setting();
                                $month = $ps->getAllPS();


                                $page = isset($_GET['paginate'])? $_GET['paginate'] : 1;
                                $page_url = '?item=search&search='. $_GET['search'];

                                $totalProData = $pm->getSearchPMCount($_GET);
                                //$amount = ($totalProData<1000) ? 999 : 100;   //若總數大於999，做分頁(100 amount/page)
                                $amount = 30;   //若總數大於999，做分頁(100 amount/page)
                                $product_data = $pm->getSearchPM( $_GET, ($page-1)*$amount, $amount );
                                $lastPage = ceil($totalProData/$amount);


                                If($product_data != ""){
                                    foreach(@$product_data as $key => $value){
                                        $p_contetn = $p->getOneProByNo($value['proNo']);
                                        $img = json_decode($p_contetn[0]['proImage']);
                                        $img[0] = ($img[0] !="") ? "admin/".$img[0]:"admin/".$img[1];

                                        foreach($month as $k => $v){
                                            $total = ceil($v['psRatio']*$value['pmPeriodAmnt']/$v['psMonthNum']);
                                            if($total > 1000){
                                                $price[$v['psMonthNum']] = $total;
                                            }else{
                                                unset($price[$v['psMonthNum']]);
                                            }
                                        }
                                        ?>
                                        <div class="item col-lg-4 col-6">
                                            <div class="card">
                                                <a href="?item=product&pro=<?php echo $value['proNo'];?>" title="<?php echo $value['proName'];?>">
                                                    <img src="<?php echo $img[0];?>" class="img-fluid" alt="product">
                                                    <div class="card-body">
                                                        <p class="card-title"><?php echo $value['proName'];?></p>
                                                        <p class="nowait-badge text-left">
                                                            <span class="bg-yellow">開學季優惠</span>
                                                            <span class="bg-yellow">滿千送百特惠方案</span>
                                                        </p>
                                                        <p class="card-text text-orange">
                                                            月付：NT$ <?php echo number_format(min($price));?>
                                                            <small>*<?php echo array_search(min($price),$price);?>期</small>
                                                        </p>
                                                    </div>
                                                </a>
                                            </div>
                                        </div>
                                        <?php
                                    }
                                }else{
                                    echo "<br>查無任何結果";
                                }
                                ?>
                            </div>
                        </div>
                        <ul class="pagination justify-content-center flex-wrap">
                            <?php if ($page>1){ ?>
                                <li class="page-item"><a class="page-link" href="<?php echo $page_url;?>&paginate=<?php echo $page-1;?>">&lt;</a></li>
                            <?php } ?>
                            <?php
                            for ($i=1; $i<=$lastPage; $i++) {
                                ?>
                                <li class="page-item <?php if ($page==$i)echo 'active';?>">
                                    <a class="page-link" href="<?php echo $page_url;?>&paginate=<?php echo $i;?>">
                                        <?php echo $i;?>
                                    </a>
                                </li>
                                <?php
                            }
                            ?>
                            <?php if ($page<$lastPage){ ?>
                                <li class="page-item"><a class="page-link" href="<?php echo $page_url;?>&paginate=<?php echo $page+1;?>">&gt;</a></li>
                            <?php } ?>
                        </ul>
<!--                        <ul class="pagination justify-content-center flex-wrap">-->
<!--                            <li class="page-item"><a class="page-link" href="#">&lt;</a></li>-->
<!--                            <li class="page-item active"><a class="page-link" href="#">1</a></li>-->
<!--                            <li class="page-item"><a class="page-link" href="#">2</a></li>-->
<!--                            <li class="page-item"><a class="page-link" href="#">3</a></li>-->
<!--                            <li class="page-item"><a class="page-link" href="#">&gt;</a></li>-->
<!--                        </ul>-->
                    </div>
                </div>
            </div>
        </div>
    </section>

<?php
include_once ('views/_page_service.php');
?>
</main>