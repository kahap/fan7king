<style>
    .btn-per{
        width: auto;
        line-height: 35px;
        font-size: 14px;
        color: #fff;
        display: inline-block;
        margin: 0px auto;
        text-align: center;
        clear: both;
        padding-left: 15px;
        padding-right: 15px;
        margin-right: 10px;
        background: #F44336;
    }
</style>
<script>
    var fbhtml_url=window.location.toString();

</script>
<?php
unset($_SESSION['ord_code']);
unset($_SESSION['shopping_user']['0']);
unset($_SESSION['shopping_product']['0']);

$pm = new Product_Manage();
$category = new Category();
$ps = new Period_Setting();
$pp = new Product_Period();
$order = new Orders();

$proNo = isset($_GET['pro'])? $_GET['pro'] : 0;
$pm_detail = $pm->getAllByProName($proNo); //商品內容

if($pm_detail == ""){
    echo "<script>alert('查無此商品，或商品已經下架。')</script>";
    echo "<script>location.href='index.php'</script>";
}
$cat = $category->getOneCatByNo($pm_detail[0]['catNo']); //分類內容

$pm->updatepmClickNum($proNo,$pm_detail[0]['pmClickNum']);
$img = json_decode($pm_detail[0]['proImage']);
$img[0] = ($img[0] !="") ? $img[0]:$img[1];


//利率待修改區-START
$month = $ps->getAllPS();
$ppData = $pp->getPPByProduct($pm_detail[0]["proNo"]);
$followDefault = true;
if($ppData != null){
    foreach($ppData as $key=>$value){
        if($value["ppPercent"] != ""){
            $followDefault = false;
            break;
        }
    }
}

if($followDefault){
    foreach($month as $k => $v){
        $priceProduct[$v['psMonthNum']] = ceil($v['psRatio']*$pm_detail[0]['pmPeriodAmnt']/$v['psMonthNum']);
    }
}else{
    foreach($ppData as $k=>$v){
        if(!empty($v['ppPercent'])){
            $priceProduct[$v['ppPeriodAmount']] = ceil($v['ppPercent']*$pm_detail[0]['pmPeriodAmnt']/$v['ppPeriodAmount']);
        }
    }
}
//利率待修改區-END

$_SESSION['pro'] = $proNo;
?>
<main role="main">
    <section>
        <div class="container">
            <ol class="breadcrumb clearfix">
                <li class="breadcrumb-item"><a href="index.php">NoWait</a></li>
                <li class="breadcrumb-item"><a href="?item=category&c=<?php echo $cat[0]['catOrder']; ?>"><?php echo $cat[0]['catName']; ?></a></li>
                <li class="breadcrumb-item active" aria-current="page"><?php echo $pm_detail[0]['proName']; ?></li>
            </ol>
            <div class="row">
                <div class="col-lg-6">
                    <div class="product-inner text-center">
                        <div class="img-thumbs-target text-center">
                            <?php
                            foreach($img as $key => $value){
                                if($value != ""){
                                    ?>
                                    <div>
                                        <img src="<?php echo "admin/".$value ?>" alt="*" class="img-fluid" />
                                    </div>
                                    <?php
                                }
                            }
                            ?>
                        </div>
                        <div class="img-thumbs row">
                            <?php
                            foreach($img as $key => $value){
                                if($value != ""){
                                    ?>
                                    <div class="col-2">
                                        <img src="<?php echo "admin/".$value ?>" alt="*" class="img-fluid" />
                                    </div>
                                    <?php
                                }
                            }
                            ?>
                        </div>
                    </div>
                </div>
                <div class="col-lg-6">
                    <div class="product-inner">
                        <h1 class="product"><?php echo $pm_detail[0]['proName'] ?></h1>
                        <!-- <p>
                            購買人數：
                            <span class="text-orange text-span">
                                <?php echo ($pm_detail[0]['pmBuyAmnt'] != '') ? $pm_detail[0]['pmBuyAmnt']+$pm_detail[0]['pmPopular']:'1'."人"; ?>
                            </span>
                            商品加贈：
                            <span class="text-orange text-span">玻璃保護貼</span>
                        </p> -->
                        <!-- <p class="nowait-tag nm">
                            <a href="#">主機+手把組合包</a>
                            <a href="#">ipad + ipod 限量搶購</a>
                            <a href="#">送亞太399上網吃到飽 + 手機Fun心險</a>
                        </p> -->
                        <form id='shopping'>
                            <br>
                            <div class="selector selector-bd row">
<!--                                <div class="col-lg-6">-->
                                <?php
                                /*if($pm_detail[0]['proModelID'] != ""){
                                    $model = explode('#',$pm_detail[0]['proModelID']);
                                    ?>
                                        <label for="product-model" class="d-none">型號</label>
                                        <select class="form-control" name="model" id="product-model">
                                            <option value="選擇型號">選擇型號</option>
                                            <?php
                                            foreach($model as $key  => $value){
                                                echo "<option value='".$value."'>".$value."</option>";
                                            }
                                            ?>
                                        </select>
                                    <?php
                                }*/
                                ?>
<!--                                </div>-->
                                <div class="col-lg-6">
                                <?php
                                if($pm_detail[0]['proSpec'] != ""){
                                    $spec = explode('#',$pm_detail[0]['proSpec']);
                                    ?>
                                        <label for="product-specification" class="d-none">規格</label>
                                        <select class="form-control" name="spec" id="product-specification">
                                            <option value="">選擇規格</option>
                                            <?php
                                            foreach($spec as $key  => $value){
                                                echo "<option value='".$value."'>".$value."</option>";
                                            }
                                            ?>
                                        </select>
                                    <?php
                                }
                                ?>
                                </div>
                            </div>
                            <br>
                            <div class="selector form-group">
                                <p>選擇期數</p>
                                <div class="btn-group-toggle" data-toggle="buttons">
                                    <?php
                                    krsort($priceProduct);
                                    $i=0;
                                    $active = '';
                                    foreach($priceProduct as $key  => $value){
                                        // if($i == 0){
                                            if($value > 1000){
//                                                echo "<li style='background:rgba(12, 59, 144, 0.09); text-align:center;margin: 5px;' class='select_per active".$active."' dat-gt=".$key."><a href='javascript:' id=".$key.">".$key."期</a></li>";
                                                $first = $key;
                                                ?>
                                                <label class="btn bg-gray <?php echo $active;?> staging select_per" data-id="<?php echo $key;?>" data-gt="<?php echo $key;?>">
                                                    <input type="radio" name="staging" value="<?php echo $key;?>" autocomplete="off" id="<?php echo $key;?>"> <?php echo sprintf("%02d",$key);?>期
                                                </label>
                                                <?php
                                            }
                                        /*}else{
                                            if($value > 1000){
//                                                echo "<li style='background:rgba(12, 59, 144, 0.09);text-align:center;margin: 5px;' class='select_per ".$active."' dat-gt=".$key."><a href='javascript:' id=".$key.">".$key."期</a></li>";
                                                ?>
                                                <label class="btn bg-gray <?php echo $active;?> staging select_per active" data-id="<?php echo $key;?>" data-gt="<?php echo $key;?>">
                                                    <input type="radio" name="staging" value="<?php echo $key;?>" checked autocomplete="off" id="<?php echo $key;?>"> <?php echo sprintf("%02d",$key);?>期
                                                </label>
                                                <?php
                                            }
                                        }
                                        $i++;*/
                                    }
                                    ?>
                                </div>
                            </div>
                            <div class="selector">
                                <?php
                                /*擋住被婉拒客戶*/
                                $reject_custom = $order->reject_custom($_SESSION['user']['memNo']);
                                if($reject_custom == ""){
                                    ?>
                                    <button class="btn bg-orange period">
                                        立即分期
                                        <?php //if($proNo == '10190'){ ?>
                                            <!-- <img src="https://farm-tw.plista.com/activity2;domainid:718601;campaignid:717271;event:30" style="width:1px;height:1px;" alt="" /> -->
                                        <?PHP //} ?>
                                    </button>
                                    <?php
                                }else{
                                    echo "<button class='btn-add-cart message'>立即分期</button>";
                                }
                                ?>

                                <?php
                                krsort($priceProduct);
                                $i=0;
                                foreach($priceProduct as $key  => $value){
                                    if($i==0){
                                        if($value > 1000){
//                                            echo "<li style='background:rgba(144, 12, 72, 0.09);width: 126px;'  class='select_price' id='price_".$key."'><a href='javascript:'>NT $".number_format($value)."X".$key."期</a></li>";
                                            ?>
                                            <a class="btn select_price price_" id='price_<?php echo $key;?>' style="">
                                                <?php echo number_format($value);?>*<?php echo $key;?>期
                                            </a>
                                            <?php
                                        }
                                    }else{
                                        if($value > 1000){
//                                            echo "<li style='background:rgba(144, 12, 72, 0.09);width: 126px; display:none;'  class='select_price' id='price_".$key."'><a href='javascript:'>NT $".number_format($value)."X".$key."期</a></li>";
                                            ?>
                                            <a class="btn select_price price_" id='price_<?php echo $key;?>' style="display: none;">
                                                <?php echo number_format($value);?>*<?php echo $key;?>期
                                            </a>
                                            <?php
                                        }
                                    }
                                    $i++;
                                }
                                $reject  = ($reject_custom == "") ? $first:"";
                                echo "<input type='hidden' name='period' value='".$reject."'/>";
                                echo "<input type='hidden' name='user' value='".$_SESSION['user']['memNo']."'/>";
                                echo "<input type='hidden' name='pro' value='".$proNo."'/>";
                                ?>
                            </div>
                        </form>
                    </div>
                </div>
                <div class="product-tabs col-12">
                    <div class="nav nav-tabs" id="nav-tab" role="tablist">
                        <a class="nav-item nav-link active" id="nav-info-tab" data-toggle="tab" href="#nav-info" role="tab" aria-controls="nav-info" aria-selected="true">商品說明</a>
                        <a class="nav-item nav-link" id="nav-memo-tab" data-toggle="tab" href="#nav-memo" role="tab" aria-controls="nav-memo" aria-selected="false">購物須知</a>
                    </div>
                    <div class="tab-content" id="nav-tabContent">
                        <div class="tab-pane fade show active" id="nav-info" role="tabpanel" aria-labelledby="nav-info-tab">
                            <?php echo $pm_detail[0]['proDetail']; ?>
                        </div>
                        <div class="tab-pane fade" id="nav-memo" role="tabpanel" aria-labelledby="nav-memo-tab">
                            <?php
                            $front_Manage2 = new Front_Manage2();
                            $page_data2 = $front_Manage2->getAllFM('fmBuyMustKnow');
                            echo $page_data2[0]['fmBuyMustKnow'];
                            ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- <section id="relation" class="bg-white">
        <div class="container">
            <div class="slick-title ">
                <h2 class="span-yellow text-left"><span>關聯商品</span></h2>
            </div>
            <div class="row">
                <div class="item col-lg-3 col-6">
                    <div class="card">
                        <a href="#" title="#">
                            <img src="portal/images/Tmp/demo.jpg" class="img-fluid" alt="#">
                            <div class="card-body">
                                <p class="card-title">Panasonic lumix gf7 微單眼 類單眼 二手 機身+鏡頭 12-32mm</p>
                                <p class="nowait-badge text-left"><span class="bg-yellow">開學季優惠</span><span class="bg-yellow">滿千送百特惠方案</span></p>
                                <p class="card-text text-orange text-center">月付：NT$600<small>*24期</small></p>
                            </div>
                        </a>
                    </div>
                </div>
                <div class="item col-lg-3 col-6">
                    <div class="card">
                        <a href="#" title="#">
                            <img src="portal/images/Tmp/demo.jpg" class="img-fluid" alt="#">
                            <div class="card-body">
                                <p class="card-title">Panasonic lumix gf7 微單眼 類單眼 二手 機身+鏡頭 12-32mm</p>
                                <p class="nowait-badge text-left"><span class="bg-yellow">開學季優惠</span><span class="bg-yellow">滿千送百特惠方案</span></p>
                                <p class="card-text text-orange text-center">月付：NT$600<small>*24期</small></p>
                            </div>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </section> -->

<?php
    include_once ('portal/views/_page_service.php');
?>
</main>

<script>
    //選擇期數
    $('.staging').click(function () {
        var id = $(this).data('id');
        $('.price_').hide();
        $('#price_'+id).show();
    });

    $('.product_hide').hide();

    $('.message').click(function(){
        alert('曾下單婉拒之用戶在婉拒後6個月內將無法再次申請分期，如有疑問請洽客服。');
    });

    //選擇期數
    $('.select_per').click(function(e){
        var number = $(this).data('gt');
        $(this).addClass('active').siblings().removeClass('active');
        $('.select_price').hide();
        $('#price_'+number).show();
        $('input[name=period]').val(number);
    });

    //立即分期
    $('.period').click(function(e){
        e.preventDefault();
        if($('input[name=period]').val() != ""){

            if($('input[name=user]').val() != ""){
                $.ajax({
                    url: 'portal/Controllers/php/order_period.php',
                    data: $('#shopping').serialize(),
                    type:"POST",
                    dataType:'text',
                    success: function(msg){
                        if(msg == "1"){
                            location.href='index.php?item=member_center&action=order_period';
                        }else{
                            alert(msg);
                            return false;
                        }
                    },
                    error:function(xhr, ajaxOptions, thrownError){
                        alert(xhr.status);
                        alert(thrownError);
                        return false;
                    }
                });
            }else{
                alert('請先登入帳號');
                // location.href="index.php?item=login";
                location.href="index.php?item=login&pro=<?=$proNo; ?>&share=<?php echo $_GET['share']? $_GET['share'] : ''; ?>";
                e.preventDefault();
                return false;
            }
        }else{
            alert('請選擇期數');
            return false;

        }
        return false;
    });

    /*
    ////直購，現在無用
    */
    $('.direct').click(function(){
        if($('input[name=user]').val() != ""){
            $.ajax({
                url: 'portal/php/order_direct.php',
                data: $('#shopping').serialize(),
                type:"POST",
                dataType:'text',
                success: function(msg){
                    if(msg == "1"){
                        location.href='index.php?item=member_center&action=order_direct';
                    }else{
                        alert(msg);
                    }
                },

                error:function(xhr, ajaxOptions, thrownError){
                    alert(xhr.status);
                    alert(thrownError);
                }
            });
        }else{
            alert('請先登入帳號');
            location.href='index.php?item=login&pro=<?php echo $proNo; ?>';
        }
    });

</script>